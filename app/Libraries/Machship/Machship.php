<?php

namespace App\Libraries\Machship;

class Machship
{
    private $curl;
    private $token;

    /**
     * Machship constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->setCurlService($token);
    }

    /**
     * @param null $token
     */
    public function setCurlService($token = null)
    {
        $this->curl = $token ? new CurlService($token) : new CurlService($this->token);
    }

    /**
     * COMPANY SERVICES
     */

    /**
     * @param null $id
     * @return mixed
     */
    public function getAllCompanies($id = null)
    {
        $url = isset($id)
            ? "/companies/getAll?atOrBelowCompanyId={$id}"
            : "/companies/getAll";

        return $this->curl->get($url);
    }

    /**
     * CARRIER SERVICES
     */

    /**
     * @return mixed
     */
    public function getAllCarriers()
    {
        $url = "https://live.machship.com/api/carriers/GetAllCarriers?retrieveSize=400";

        $params['custom_url'] = $url;

        return $this->curl->get($url, $params);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCarrierServices($id)
    {
        $url = "https://live.machship.com/api/carrierservices/SearchAllServicesByCarrierId?id={$id}&retrieveSize=4000";

        $params['custom_url'] = $url;

        return $this->curl->get($url, $params);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCarrierAccounts($id)
    {
        $url = "https://live.machship.com/api/carrierAccounts/GetAllCarrierAccounts?carrierId={$id}&retrieveSize=400&startIndex=1";

        $params['custom_url'] = $url;

        return $this->curl->get($url, $params);
    }

    /**
     * COMPANY ITEM SERVICES
     */

    /**
     * @param null $company_id
     * @param int $start_index
     * @param int $retrieve_size
     * @return mixed
     */
    public function getAllCompanyItem($company_id = null, $start_index = 1, $retrieve_size = 200)
    {
        $url = isset($company_id)
            ? "/items/getAll?companyId={$company_id}&startIndex={$start_index}&retrieveSize={$retrieve_size}"
            : "/items/getAll?startIndex={$start_index}&retrieveSize={$retrieve_size}";

        return $this->curl->get($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCompanyItem($id)
    {
        $url = "/items/get?id=$id";

        return $this->curl->get($url);
    }

    /**
     * @param $company_id
     * @param $sku
     * @return mixed
     */
    public function getCompanyItemBySku($company_id, $sku)
    {
        $url = "/items/getBySku?companyId={$company_id}&sku={$sku}";

        return $this->curl->get($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCompanyItemComplex($id)
    {
        $url = "/items/getComplex?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param null $company_id
     * @param int $start_index
     * @param int $retrieve_size
     * @return mixed
     */
    public function getCompanyItemAllComplex($company_id = null, $start_index = 1, $retrieve_size = 200)
    {
        $url = isset($company_id)
            ? "/items/getAllComplex?companyId={$company_id}&startIndex={$start_index}&retrieveSize={$retrieve_size}"
            : "/items/getAllComplex?startIndex={$start_index}&retrieveSize={$retrieve_size}";

        return $this->curl->get($url);
    }

    /**
     * @param $sku
     * @param null $company_id
     * @return mixed
     */
    public function getCompanyItemBySkuComplex($sku, $company_id = null)
    {
        $url = isset($company_id)
            ? "items/getComplex?sku={$sku}&companyId={$company_id}"
            : "items/getComplex?sku={$sku}";

        return $this->curl->get($url);
    }

    /**
     * COMPANY LOCATION SERVICES
     */

    /**
     * @param $id
     * @return $this
     */
    public function getCompanyLocation($id)
    {
        $url = "/companyLocations/get?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param null $company_id
     * @return mixed
     */
    public function getAllCompanyLocations($company_id = null)
    {
        $url = isset($company_id)
            ? "/companyLocations/getAll?companyId={$company_id}"
            : "/companyLocations/getAll";

        return $this->curl->get($url);
    }

    /**
     * CONSIGNMENT SERVICES
     */

    /**
     * @param $id
     * @return mixed
     */
    public function getConsignment($id)
    {
        $url = "/consignments/getConsignment?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUnmanifestedConsignmentForEdit($id)
    {
        $url = "/consignments/getUnmanifestedConsignmentForEdit?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getConsignmentByPendingConsignmentId($id)
    {
        $url = "/consignments/getConsignmentByPendingConsignmentId?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param $consignment_ids
     * @return mixed
     */
    public function returnConsignments($consignment_ids)
    {
        $url = "/consignments/returnConsignments";

        return $this->curl->post($url, $consignment_ids);
    }

    /**
     * @param $carrier_consignment_ids
     * @return mixed
     */
    public function returnConsignmentsByCarrierConsignmentId($carrier_consignment_ids)
    {
        $url = "/consignments/returnConsignmentsByCarrierConsignmentId";

        return $this->curl->post($url, $carrier_consignment_ids);
    }

    /**
     * @param $references
     * @return mixed
     */
    public function returnConsignmentsByReference1($references)
    {
        $url = "/consignments/returnConsignmentsByReference1";

        return $this->curl->post($url, $references);
    }

    /**
     * @param $references
     * @return mixed
     */
    public function returnConsignmentsByReference2($references)
    {
        $url = "/consignments/returnConsignmentsByReference2";

        return $this->curl->post($url, $references);
    }

    /**
     * @param $consignment_ids
     * @param null $since_date_created_utc
     * @return mixed
     */
    public function returnConsignmentStatuses($consignment_ids, $since_date_created_utc = null)
    {
        $url = isset($since_date_created_utc)
            ? "/consignments/returnConsignmentStatuses?sinceDateCreatedUtc={$since_date_created_utc}"
            : "/consignments/returnConsignmentStatuses";

        return $this->curl->post($url, $consignment_ids);
    }

    /**
     * @param null $company_id
     * @param int $start_index
     * @param int $retrieve_size
     * @param null $carrier_id
     * @param bool $include_child_companies
     * @return mixed
     */
    public function getUnmanifestedConsignments(
        $company_id = null,
        $start_index = 1,
        $retrieve_size = 200,
        $carrier_id = null,
        $include_child_companies = false
    ) {
        $url = isset($company_id)
            ? "/consignments/getUnmanifested?companyId={$company_id}&retrieveSize={$retrieve_size}&includeChildCompanies={$include_child_companies}"
            : "/consignments/getUnmanifested?retrieveSize={$retrieve_size}&includeChildCompanies={$include_child_companies}";

        $url .= isset($carrier_id) ? "&carrierId={$carrier_id}" : "";

        return $this->curl->get($url);
    }

    /**
     * @param null $company_id
     * @param int $start_index
     * @param int $retrieve_size
     * @param null $carrier_id
     * @param bool $include_child_companies
     * @return mixed
     */
    public function getActiveConsignments(
        $company_id = null,
        $start_index = 1,
        $retrieve_size = 200,
        $carrier_id = null,
        $include_child_companies = false
    ) {
        $url = isset($company_id)
            ? "/consignments/getActive?companyId={$company_id}&retrieveSize={$retrieve_size}&includeChildCompanies={$include_child_companies}"
            : "/consignments/getActive?retrieveSize={$retrieve_size}&includeChildCompanies={$include_child_companies}";

        $url .= isset($carrier_id) ? "&carrierId={$carrier_id}" : "";

        return $this->curl->get($url);
    }

    /**
     * @param null $company_id
     * @param int $start_index
     * @param int $retrieve_size
     * @param null $carrier_id
     * @param bool $include_child_companies
     * @param bool $include_deleted_consignments
     * @return mixed
     */
    public function getAllConsignments(
        $company_id = null,
        $start_index = 1,
        $retrieve_size = 200,
        $carrier_id = null,
        $include_child_companies = false,
        $include_deleted_consignments = false
    ) {
        $url = isset($company_id)
            ? "/consignments/getAll?companyId={$company_id}&retrieveSize={$retrieve_size}&includeChildCompanies={$include_child_companies}&includeDeletedConsignments={$include_deleted_consignments}"
            : "/consignments/getAll?retrieveSize={$retrieve_size}&includeChildCompanies={$include_child_companies}&includeDeletedConsignments={$include_deleted_consignments}";

        $url .= isset($carrier_id) ? "&carrierId={$carrier_id}" : "";

        return $this->curl->get($url);
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param null $company_id
     * @param bool $include_child_companies
     * @return mixed
     */
    public function getCompletedConsignments(
        $start_date,
        $end_date,
        $company_id = null,
        $include_child_companies = false
    ) {
        $url = isset($company_id)
            ? "/consignments/getCompleted?companyId={$company_id}&startDate={$start_date}&endDate={$end_date}&includeChildCompanies={$include_child_companies}"
            : "/consignments/getCompleted?startDate={$start_date}&endDate={$end_date}&includeChildCompanies={$include_child_companies}";

        return $this->curl->get($url);
    }

    /**
     * @param $consignment
     * @return mixed
     */
    public function createConsignment($consignment)
    {
        $url = "/consignments/createConsignment";

        return $this->curl->post($url, $consignment);
    }

    /**
     * @param $consignment
     * @return mixed
     */
    public function editUnmanifestedConsignment($consignment)
    {
        $url = "/consignments/editUnmanifestedConsignment";

        return $this->curl->post($url, $consignment);
    }

    /**
     * @param $consignment
     * @return mixed
     */
    public function createConsignmentWithComplexItems($consignment)
    {
        $url = "/consignments/createConsignmentwithComplexItems";

        return $this->curl->post($url, $consignment);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function deleteUnmanifestedConsignments($ids)
    {
        $url = "/consignments/deleteUnmanifestedConsignments";

        return $this->curl->post($url, $ids);
    }

    /**
     * @param $consignment_id
     * @return mixed
     */
    public function getConsignmentAttachments($consignment_id)
    {
        $url = "/consignments/getAttachments";

        return $this->curl->post($url, $consignment_id);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getConsignmentForClone($id = null)
    {
        $url = isset($id)
            ? "/consignments/getConsignmentForClone?id={$id}"
            : "/consignments/getConsignmentForClone";

        return $this->curl->get($url);
    }

    /**
     * LOCATION SERVICES
     */

    /**
     * @return $this
     */
    public function getAllLocations()
    {
        $url = "/locations/getAllLocations";
        return $this->curl->get($url);
    }

    public function getLocations($params = [])
    {
        $s = implode(' ', $params);
        $url = "/locations/getLocations?s={$s}";
        return $this->curl->get($url);
    }

    /**
     * PENDING CONSIGNMENT SERVICES
     */

    /**
     * @param $id
     * @return mixed
     */
    public function getPendingConsignment($id)
    {
        $url = "/pendingConsignments/getPendingConsignment?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param $pending_consignments_id
     * @return mixed
     */
    public function returnPendingConsignments($pending_consignments_id)
    {
        $url = "/pendingConsignments/returnPendingConsignments";

        return $this->curl->post($url, $pending_consignments_id);
    }

    /**
     * @param $references
     * @return mixed
     */
    public function returnPendingConsignmentsByReference1($references)
    {
        $url = "/pendingConsignments/returnPendingConsignmentsByReference1";

        return $this->curl->post($url, $references);
    }

    /**
     * @param $references
     * @return mixed
     */
    public function returnPendingConsignmentsByReference2($references)
    {
        $url = "/pendingConsignments/returnPendingConsignmentsByReference2";

        return $this->curl->post($url, $references);
    }

    /**
     * @param $pending_consignment
     * @return mixed
     */
    public function createPendingConsignment($pending_consignment)
    {
        $url = "/pendingConsignments/createPendingConsignment";

        return $this->curl->post($url, $pending_consignment);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function deletePendingConsignment($ids)
    {
        $url = "/pendingConsignments/deletePendingConsignments";

        return $this->curl->post($url, $ids);
    }

    /**
     * QUOTE SERVICES
     */

    /**
     * @param $quote
     * @return mixed
     */
    public function createQuote($quote)
    {
        $url = "/quotes/createQuote";

        return $this->curl->post($url, $quote);
    }

    /**
     * @param $quote
     * @return mixed
     */
    public function createQuoteWithComplexItems($quote)
    {
        $url = "/quotes/createQuoteWithComplexItems";

        return $this->curl->post($url, $quote);
    }

    /**
     * @param null $company_id
     * @return mixed
     */
    public function getAllQuotes($company_id = null)
    {
        $url = isset($company_id)
            ? "/quotes/getAll?companyId={$company_id}"
            : "/quotes/getAll";

        return $this->curl->get($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getQuote($id)
    {
        $url = "/quotes/getQuote?id={$id}";

        return $this->curl->get($url);
    }

    /**
     * @param $quoteIds
     * @return mixed
     */
    public function returnQuotes($quoteIds)
    {
        $url = "/quotes/returnQuotes";

        return $this->curl->post($url, $quoteIds);
    }
}
