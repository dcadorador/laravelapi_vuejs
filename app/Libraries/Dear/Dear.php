<?php
namespace App\Libraries\Dear;

use App\Libraries\Dear\Api\Sale;
use App\Traits\LoggerTrait;
use App\Libraries\Dear\Api\SaleFulfilment;
use App\Libraries\Dear\Api\SaleList;

class Dear
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $account_id;

    /**
     * @var string
     */
    protected $application_key;

    /**
     * @var string
     */
    protected $client;


    /**
     * Dear constructor.
     * @param $account_id
     * @param $application_key
     */
    public function __construct($account_id, $application_key)
    {
        $this->client = new Client($account_id, $application_key);
    }

    /**
     * @return SaleList
     */
    public function saleList()
    {
        return new SaleList($this->client);
    }

    /**
     * @return SaleFulfilment
     */
    public function saleFulfilment()
    {
        return new SaleFulfilment($this->client);
    }

    /**
     * @return Sale
     */
    public function sale()
    {
        return new Sale($this->client);
    }
}
