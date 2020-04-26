<?php

namespace App\Libraries\Netsuite\NetSuiteObjects;

use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteDataHelper;
use Carbon\Carbon;
use NetSuite\Classes\CustomerSearchBasic;
use NetSuite\Classes\SearchMoreWithIdRequest;
use NetSuite\Classes\Customer;
use NetSuite\Classes\TransactionSearchBasic;
use Symfony\Component\HttpFoundation\ParameterBag;
use NetSuite\NetSuiteService;
use App\Libraries\Netsuite\Traits\QueryBuilder;

class SalesOrderObject implements ObjectInterface
{
    use QueryBuilder;

    protected $netsuite_service;
    private $object_type;
    const NO_OF_RESULTS = 25;
    const TIME_FRAME = '-12 hours';

    public function setNetsuiteConfig($config = [])
    {
        $this->netsuite_service =  new NetSuiteService($config);
        $this->object_type = NetSuiteConstantHelper::NETSUITE_SALES_ORDER;
    }

    public function get($query = [])
    {
        $salesOrder = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        // create a new transaction search
        $search = new TransactionSearchBasic();

        // 1 - FILTER TO GET ITEM FULFILLMENT DATA
        // $search->type = NetSuiteDataHelper::searchEnumMultiSelectField(
        //     'salesOrder',
        //     NetSuiteConstantHelper::NETSUITE_ANY_OF_OPERATOR
        // );

        // 2 - FILTER TRANSACTIONS FOR THE PAST 12HRS
        // $search->dateCreated = NetSuiteDataHelper::searchDateField(
        //     NetSuiteConstantHelper::NETSUITE_AFTER_OPERATOR,
        //     date('Y-m-d\TH:i:s.000\Z', strtotime(self::TIME_FRAME))
        // );

        if (!empty($query)) {
            $search = $this->transactionQueryBuilder($this->object_type, $search, $query);
        }

        // validate if theres an error in search
        if (empty($search->type) || empty($search->dateCreated)) {
            \Log::info("[SALESORDER][SEARCH] no search type or date created filter");
            return null;
        }

        $response = $this->netsuite_service->search(
            NetSuiteDataHelper::searchRequest($search)
        );

        if ($response->searchResult->status->isSuccess && !is_null($response->searchResult->recordList->record)) {
            $search_id = $response->searchResult->searchId;
            $salesOrder = $response->searchResult->recordList->record;

            $loop = $response->searchResult->totalPages > 1 ? true : false;

            for ($page_number = 2; $loop == true; $page_number++) {
                $paginated_request = new SearchMoreWithIdRequest();
                $paginated_request->searchId = $search_id;
                $paginated_request->pageIndex = $page_number;
                $paginated_output = $this->netsuite_service->searchMoreWithId($paginated_request);
                $salesOrder = $paginated_output->searchResult->status->isSuccess ?
                    array_merge($salesOrder, $paginated_output->searchResult->recordList->record) :
                    $salesOrder;
                $loop = $paginated_output->searchResult->status->isSuccess;
            }
        }

        return $salesOrder;
    }

    public function update(ParameterBag $parameter)
    {
        $result = null;

        $data = $this->transactionUpdateDataBuilder(
            $this->object_type,
            $parameter->all()
        );

        $response = $this->netsuite_service->update(NetSuiteDataHelper::updateRequest(
            NetSuiteDataHelper::itemObject($this->object_type, $data)
        ));

        if (!$response->writeResponse->status->isSuccess) {
            $result = $response->writeResponse->baseRef->internalId;
        }

        return $result;
    }

    public function findByTransaction($id)
    {
        // TODO: Implement findByTransaction() method.
    }

    public function updateCustomFields(ParameterBag $parameter)
    {
        $result = null;

        $data = $this->transactionUpdateDataBuilder(
            $this->object_type,
            $parameter->get('custom_fields')
        );

        $array = array(
            'internalId' => $parameter->get('internal_id'),
            'customFieldList' => NetSuiteDataHelper::customFieldList($data)
        );

        $response = $this->netsuite_service->update(NetSuiteDataHelper::updateRequest(
            NetSuiteDataHelper::itemObject($this->object_type, $array)
        ));

        if (!$response->writeResponse->status->isSuccess) {
            $result = $response->writeResponse->baseRef->internalId;
        }

        return $result;
    }

    public function find($id)
    {
        $so = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        $response = $this->netsuite_service->get(
            NetSuiteDataHelper::getRequest(
                NetSuiteDataHelper::recordRef($id, null, 'salesOrder')
            )
        );

        if ($response->readResponse->status->isSuccess) {
            $so = $response->readResponse->record;
        }

        return $so;
    }

    public function findByRange($start, $end, $query = [])
    {
        $salesOrder = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        // create a new transaction search
        $search = new TransactionSearchBasic();

        // 1 - FILTER TO GET ITEM FULFILLMENT DATA
        /*$search->type = NetSuiteDataHelper::searchEnumMultiSelectField(
            'salesOrder',
            NetSuiteConstantHelper::NETSUITE_ANY_OF_OPERATOR
        );*/

        // 2 - FILTER TRANSACTIONS FOR THE PAST 12HRS
        /*$search->dateCreated = NetSuiteDataHelper::searchDateField(
            NetSuiteConstantHelper::NETSUITE_AFTER_OPERATOR,
            date('Y-m-d\TH:i:s.000\Z', strtotime(self::TIME_FRAME))
        );*/

        if (count($query)) {
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

        if ($response->searchResult->status->isSuccess && count($response->searchResult->recordList->record) > 0) {
            $search_id = $response->searchResult->searchId;
            $salesOrder = $response->searchResult->recordList->record;

            $loop = $response->searchResult->totalPages > 1 ? true : false;

            for ($page_number = 2; $loop == true; $page_number++) {
                $paginated_request = new SearchMoreWithIdRequest();
                $paginated_request->searchId = $search_id;
                $paginated_request->pageIndex = $page_number;
                $paginated_output = $this->netsuite_service->searchMoreWithId($paginated_request);
                $salesOrder = $paginated_output->searchResult->status->isSuccess ?
                    array_merge($salesOrder, $paginated_output->searchResult->recordList->record) :
                    $salesOrder;
                $loop = $paginated_output->searchResult->status->isSuccess;
            }
        }

        return $salesOrder;
    }
}
