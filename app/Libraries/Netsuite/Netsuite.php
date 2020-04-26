<?php
namespace App\Libraries\Netsuite;

use App\Libraries\IntegrationInterface;
use App\Libraries\Netsuite\NetsuiteObjects\ObjectInterface;
use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;

class Netsuite
{
    protected $object;
    protected $config = [];

    /**
     *  Netsuite constructor.
     *  This should set the configuration parameters to instantiate the Netsuite Service package.
     *  Parameters are the following and sample values:
     *    "endpoint"        =>      "2019_1"
     *    "host"            =>      "https://3929178.suitetalk.api.netsuite.com"
     *    "account"         =>      "3929178"
     *    "app_id"          =>      "4AD027CA-88B3-46EC-9D3E-41C6E6A325E2"
     *  IF USING TOKEN, ADD THE CONFIG BELOW:
     *    "consumerKey"     =>      "593f6595885eaccd23cdc6022b3a601d715fa39313b9680f243b526f1d1e0685"
     *    "consumerSecret"  =>      "a2fb03d9b9071ecda4c2609c28f6e15fe5b58b2029d315a0943eefe612a355ca"
     *    "token"           =>      "a2ed4a6f569619286bb0ee6cbd9e0c5ce5b437c3865ce4778b47e112a5127b6c"
     *    "tokenSecret"     =>      "e5d1cc66f8a95b0e8be7b8576e5aa8fce693451f8927591a8392735efb3ec060"
     *  IF USING EMAIL PASSWORD:
     *    "email"           =>      "email@email.com"
     *    "password"        =>      "password"
     *    "role"            =>      "3"
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * @param ObjectInterface $object
     * @return $this
     */
    public function setObject(ObjectInterface $object)
    {
        $this->object = $object;
        $this->object->setNetsuiteConfig($this->config);
        return $this;
    }

    public function get($query = [])
    {
        $result = null;

        try {
            $result = $this->object->get($query);
        } catch (Exception $e) {
            \Log::error('Error getting netsuite object list: '.json_encode($e->getMessage()));
        }

        return $result;
    }

    public function list()
    {
        $result = null;

        try {
            $result = $this->object->get();
        } catch (\Exception $e) {
            \Log::error('Error getting object list: '.json_encode($e->getMessage()));
        }

        return $result;
    }

    public function update(ParameterBag $parameter)
    {
        $result = null;

        try {
            $result = $this->object->update($parameter);
        } catch (Exception $e) {
            \Log::error('Error updating netsuite object: '.json_encode($e->getMessage()));
        }

        return $result;
    }

    public function updateCustomFields(ParameterBag $parameter)
    {
        $result = null;

        try {
            $result = $this->object->updateCustomFields($parameter);
        } catch (Exception $e) {
            \Log::error('Error updating netsuite object custom fields: '.json_encode($e->getMessage()));
        }

        return $result;
    }

    public function find($id)
    {
        $result = null;

        try {
            $result = $this->object->find($id);
        } catch (Exception $e) {
            \Log::error('Error finding netsuite object: '.json_encode($e->getMessage()));
        }

        return $result;
    }

    public function findByTransaction($id)
    {
        $result = null;

        try {
            $result = $this->object->findByTransaction($id);
        } catch (Exception $e) {
            \Log::error('Error finding by transaction netsuite object: '.json_encode($e->getMessage()));
        }

        return $result;
    }

    public function findByDateRange($start, $end, $query = [])
    {
        $result = null;

        try {
            $result = $this->object->findByRange($start, $end, $query);
        } catch (Exception $e) {
            \Log::error('Error finding by transaction netsuite object: '.json_encode($e->getMessage()));
        }

        return $result;
    }
}
