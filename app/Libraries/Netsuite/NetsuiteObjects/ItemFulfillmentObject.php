<?php

namespace App\Libraries\Netsuite\NetSuiteObjects;

use Illuminate\Http\Request;
use NetSuite\Classes\ItemFulfillment;
use NetSuite\Classes\TransactionSearchBasic;
use NetSuite\NetSuiteService;
use Symfony\Component\HttpFoundation\ParameterBag;
use NetSuite\Classes\SearchMoreWithIdRequest;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteDataHelper;
use App\Libraries\Netsuite\Traits\QueryBuilder;
use Carbon\Carbon;

class ItemFulfillmentObject implements ObjectInterface
{
    use QueryBuilder;

    const NO_OF_RESULTS = 25;
    const TIME_FRAME = '-12 hours';
    protected $netsuite_service;
    private $object_type;

    public function setNetsuiteConfig($config = [])
    {
        $this->netsuite_service =  new NetSuiteService($config);
        $this->object_type = NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT;
    }

    public function get($query = [])
    {
        $fulfillment = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        // create a new transaction search
        $search = new TransactionSearchBasic();

        // query for item fulfillment objects
        // $search->type = NetSuiteDataHelper::searchEnumMultiSelectField(
        //     '_itemFulfillment',
        //     NetSuiteConstantHelper::NETSUITE_ANY_OF_OPERATOR
        // );

        // query for time frame
        // $search->dateCreated = NetSuiteDataHelper::searchDateField(
        //     NetSuiteConstantHelper::NETSUITE_AFTER_OPERATOR,
        //     date('Y-m-d\TH:i:s.000\Z', strtotime(self::TIME_FRAME))
        // );

        if (!empty($query)) {
            $search = $this->transactionQueryBuilder($this->object_type, $search, $query);
        }


        // validate if theres an error in search
        if (empty($search->type) || empty($search->dateCreated)) {
            \Log::info("[ITEMFULFILLMENT][SEARCH] no search type or date created filter");
            return null;
        }

        $response = $this->netsuite_service->search(
            NetSuiteDataHelper::searchRequest($search)
        );

        if ($response->searchResult->status->isSuccess && !is_null($response->searchResult->recordList->record)) {
            $search_id = $response->searchResult->searchId;
            $fulfillment = $response->searchResult->recordList->record;

            $loop = $response->searchResult->totalPages > 1 ? true : false;

            for ($page_number = 2; $loop == true; $page_number++) {
                $paginated_request = new SearchMoreWithIdRequest();
                $paginated_request->searchId = $search_id;
                $paginated_request->pageIndex = $page_number;
                $paginated_output = $this->netsuite_service->searchMoreWithId($paginated_request);
                $fulfillment = $paginated_output->searchResult->status->isSuccess ?
                    array_merge($fulfillment, $paginated_output->searchResult->recordList->record) :
                    $fulfillment;
                $loop = $paginated_output->searchResult->status->isSuccess;
            }
        } else {
            // TODO log fail netsuite here
            \Log::error("[ITEMFULFILLMENT][RESPONSE] fail to fetch response " . json_encode($response));
        }

        return $fulfillment;
    }

    public function update(ParameterBag $parameter)
    {
        $result = null;

        $response = $this->netsuite_service->update(NetSuiteDataHelper::updateRequest(
            NetSuiteDataHelper::itemObject(NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT, $parameter->all())
        ));

        if (!$response->writeResponse->status->isSuccess) {
            $result = $response->writeResponse->baseRef->internalId;
        }

        return $result;
    }

    public function find($id)
    {
        $fulfillment = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        $response = $this->netsuite_service->get(
            NetSuiteDataHelper::getRequest(
                NetSuiteDataHelper::recordRef($id, null, 'itemFulfillment')
            )
        );

        if ($response->readResponse->status->isSuccess) {
            $fulfillment = $response->readResponse->record;
        }

        return $fulfillment;
    }

    /**
     * @param $id
     * @return ItemFulfillment |null
     */
    public function findByTransaction($id)
    {
        $fulfillment = null;

        $search = new TransactionSearchBasic();
        $search->tranId = NetSuiteDataHelper::searchStringField(NetSuiteConstantHelper::NETSUITE_IS_OPERATOR, $id);

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        $response = $this->netsuite_service->search(
            NetSuiteDataHelper::searchRequest(
                $search
            )
        );

        if ($response->searchResult->status->isSuccess) {
            $fulfillment = $response->searchResult->recordList->record[0];
        }

        return $fulfillment;
    }

    public function updateCustomFields(ParameterBag $parameter)
    {
        $result = null;

        $array = array(
            'internalId' => $parameter->get('internalId'),
            'customFieldList' => NetSuiteDataHelper::customFieldList($parameter->get('custom_fields'))
        );

        $response = $this->netsuite_service->update(NetSuiteDataHelper::updateRequest(
            NetSuiteDataHelper::itemObject($this->object_type, $array)
        ));

        if (!$response->writeResponse->status->isSuccess) {
            $result = $response->writeResponse->baseRef->internalId;
        }

        return $result;
    }

    /**
     * @param $start
     * @param $end
     * @param array $query
     * @return array|null
     */
    public function findByRange($start, $end, $query = [])
    {
        $fulfillment = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        // create a new transaction search
        $search = new TransactionSearchBasic();

        if (!empty($query)) {
            $search = $this->transactionQueryBuilder($this->object_type, $search, $query);
        }

        if (property_exists($search, 'dateCreated') && !empty($search->dateCreated)) {
            unset($search->dateCreated);
        }

        // replace query with time frame
        $search->dateCreated = NetSuiteDataHelper::searchDateField(
            NetSuiteConstantHelper::NETSUITE_SEARCH_DATE_FIELD_OPR_WITHIN,
            Carbon::parse($start)->format('Y-m-d\TH:i:s.000\Z'),
            Carbon::parse($end)->format('Y-m-d\TH:i:s.000\Z')
        );

        $response = $this->netsuite_service->search(
            NetSuiteDataHelper::searchRequest($search)
        );

        if ($response->searchResult->status->isSuccess && !is_null($response->searchResult->recordList->record)) {
            $search_id = $response->searchResult->searchId;
            $fulfillment = $response->searchResult->recordList->record;

            $loop = $response->searchResult->totalPages > 1 ? true : false;

            for ($page_number = 2; $loop == true; $page_number++) {
                $paginated_request = new SearchMoreWithIdRequest();
                $paginated_request->searchId = $search_id;
                $paginated_request->pageIndex = $page_number;
                $paginated_output = $this->netsuite_service->searchMoreWithId($paginated_request);
                $fulfillment = $paginated_output->searchResult->status->isSuccess ?
                    array_merge($fulfillment, $paginated_output->searchResult->recordList->record) :
                    $fulfillment;
                $loop = $paginated_output->searchResult->status->isSuccess;
            }
        } else {
            // TODO log fail netsuite here
            \Log::error("[ITEMFULFILLMENT][RESPONSE] fail to fetch response " . json_encode($response));
        }

        return $fulfillment;
    }
}
