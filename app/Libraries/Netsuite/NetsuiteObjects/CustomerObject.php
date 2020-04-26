<?php

namespace App\Libraries\Netsuite\NetSuiteObjects;

use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteDataHelper;
use NetSuite\Classes\CustomerSearchBasic;
use NetSuite\Classes\SearchMoreWithIdRequest;
use NetSuite\Classes\Customer;
use Symfony\Component\HttpFoundation\ParameterBag;
use NetSuite\NetSuiteService;
use App\Libraries\Netsuite\Traits\QueryBuilder;

class CustomerObject implements ObjectInterface
{
    use QueryBuilder;

    private $object_type;
    protected $netsuite_service;
    const NO_OF_RESULTS = 25;

    public function setNetsuiteConfig($config = [])
    {
        $this->netsuite_service =  new NetSuiteService($config);
        $this->object_type = NetSuiteConstantHelper::NETSUITE_CUSTOMER;
    }

    public function get($query = [])
    {
        $customers = null;
        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        $search = new CustomerSearchBasic();

        if (count($query)) {
            $search = $this->transactionQueryBuilder($this->object_type, $search, $query);
        }

        // get the customer search basic without any parameters to get all customers
        $response = $this->netsuite_service->search(
            NetSuiteDataHelper::searchRequest($search)
        );

        // return records
        if ($response->searchResult->status->isSuccess && !is_null($response->searchResult->recordList->record)) {
            $search_id = $response->searchResult->searchId;
            $customers = $response->searchResult->recordList->record;

            $loop = $response->searchResult->totalPages > 1 ? true : false;

            for ($page_number = 2; $loop == true; $page_number++) {
                $paginated_request = new SearchMoreWithIdRequest();
                $paginated_request->searchId = $search_id;
                $paginated_request->pageIndex = $page_number;
                $paginated_output = $this->netsuite_service->searchMoreWithId($paginated_request);
                $customers = $paginated_output->searchResult->status->isSuccess ?
                    array_merge($customers, $paginated_output->searchResult->recordList->record) :
                    $customers;
                $loop = $paginated_output->searchResult->status->isSuccess;
            }
        }

        return $customers;
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

    public function find($id)
    {
        $customer = null;

        // set the search preferences
        $this->netsuite_service->setSearchPreferences(false, self::NO_OF_RESULTS);

        $response = $this->netsuite_service->get(
            NetSuiteDataHelper::getRequest(
                NetSuiteDataHelper::recordRef($id, null, 'customer')
            )
        );

        if ($response->readResponse->status->isSuccess) {
            $customer = $response->readResponse->record;
        }

        return $customer;
    }

    public function updateCustomFields(ParameterBag $parameter)
    {
        $result = null;

        $customer = new Customer();
        $customer->internalId = $parameter->get('internal_id');
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

    public function findByTransaction($id)
    {
        // TODO: Implement findByTransaction() method.
    }

    public function findByRange($start, $end, $query = [])
    {
        // TODO: Implement findByRange() method.
    }
}
