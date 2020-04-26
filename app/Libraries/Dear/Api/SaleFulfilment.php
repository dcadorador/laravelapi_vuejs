<?php
namespace App\Libraries\Dear\Api;

class SaleFulfilment extends ApiAbstract
{
    /**
     * @var string
     */
    protected $entity = 'Fulfilments';

    /**
     * @var string
     */
    protected $endpoint = 'sale/fulfilment';

    /**
     * @var string
     */
    protected $id_parameter = 'SaleID';
}
