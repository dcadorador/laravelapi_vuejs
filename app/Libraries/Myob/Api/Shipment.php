<?php
namespace App\Libraries\Myob\Api;

class Shipment extends ApiAbstract
{
    /**
     * @var string
     */
    protected $endpoint = '/Shipment';

    /**
     * @param $filter
     * @return array
     */
    public function shipmentDetails($filter = null)
    {
        return $this->client->get($this->endpoint, [], $filter);
    }

    /**
     * @param $nbr
     * @param null $filter
     * @return array
     */
    public function shipmentByShipmentNbr($nbr, $filter = null)
    {
        $endpoint = $this->endpoint . '/' . $nbr;
        return $this->client->get($endpoint, [], $filter);
    }

    /**
     * Update shipment
     *
     * @param      Array  $data    The data that will be updated
     * @param      Array  $params  The parameters that will be use in the filters
     *
     * @return     array
     */
    public function updateShipment($data, $params)
    {
        return $this->client->put($this->endpoint, $data, $params);
    }

    /**
     * @param $data
     * @return array
     */
    public function updateShipmentStatus($data)
    {
        return $this->client->post($this->endpoint . '/ConfirmShipment', $data);
    }
}
