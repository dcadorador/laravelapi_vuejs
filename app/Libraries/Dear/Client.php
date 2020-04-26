<?php
namespace App\Libraries\Dear;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;

class Client
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * Client constructor.
     * @param $account_id
     * @param $application_key
     */
    public function __construct($account_id, $application_key)
    {
        $this->client = new HttpClient([
            'base_uri' => config('constants.DEFAULT_DEAR_SYSTEM_URL'),
            'headers' => [
                    'Content-Type' => 'application/json',
                    'api-auth-accountid' => $account_id,
                    'api-auth-applicationkey' => $application_key
                ]
        ]);
    }

    /**
     * Prepare option data to be passed to the Guzzle request
     *
     * @param array $params
     * @param array $options
     * @return array
     */
    private function prepareData($params = null, $options = [])
    {
        if (Arr::get($options, 'content_type') == "json") {
            $data['json'] = $params; // pass data as array which gets json_encoded
        } else {
            $data['query'] = $this->prepareQueryString($params); // pass data as query string
        }

        return array_merge($data, $options);
    }

    /**
     * Prepare a query string from an array of params
     *
     * @param array $params
     * @return string
     */
    private function prepareQueryString(array $params = [])
    {
        return preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($params));
    }

    /**
     * @param $endPoint
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function get($endPoint, array $params = [], array $options = [])
    {
        $response = $this->client->get($endPoint, $this->prepareData($params, $options));
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $endPoint
     * @param array $params
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function post($endPoint, array $params = null, array $options = [])
    {
        $response = $this->client->post($endPoint, $this->prepareData($params, $options));
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * @param $endPoint
     * @param array $params
     * @param array $options
     * @return string
     */
    public function put($endPoint, array $params = [], array $options = [])
    {
        $response = $this->client->put($endPoint, $this->prepareData($params, $options));
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $endPoint
     * @param array $params
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function delete($endPoint, array $params = [], array $options = [])
    {
        $response = $this->client->delete($endPoint, $this->prepareData($params, $options));
        return json_decode($response->getBody()->getContents(), true);
    }
}
