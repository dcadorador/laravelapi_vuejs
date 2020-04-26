<?php
namespace App\Libraries\Dear\Api;

use App\Libraries\Dear\Client;
use Exception;

abstract class ApiAbstract
{
    const RESULT_TOTAL = 'Total';
    const RESULT_PAGE = 'Page';

    /**
     * Client object
     *
     * @var Client
     */
    protected $client;

    /**
     * Class of the entity.
     *
     * @var string
     */
    protected $entity;

    /**
     * @var int
     */
    protected $limit = 100;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * The API endpoint for the entity
     *
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $id_parameter;

    /**
     * Inject API Client
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the API endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getIdParameter()
    {
        return $this->id_parameter;
    }

    /**
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function getDefaultParams()
    {
        return [
            'Page' => $this->page,
            'Limit' => $this->limit
        ];
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function all(array $filter = [])
    {
        $params = array_merge($filter, $this->getDefaultParams());
        $response = $this->client->get($this->getEndpoint(), $params);

        // if no results return
        if (empty($response[self::RESULT_TOTAL])) {
            return $response;
        }

        // handle the pagination
        $results = $response[$this->entity];
        $total_records = $response[self::RESULT_TOTAL];
        $total_number_pages = round($total_records / $this->limit, PHP_ROUND_HALF_UP);

        // loop through the results, update current page, and merge results
        $current_page = $this->page;
        while ($current_page < $total_number_pages) {
            $current_page = $params['Page'] + 1;
            $params['Page'] = $current_page;
            $paginated_results = $this->client->get($this->getEndpoint(), $params);

            // skip fetch pagination
            if ($paginated_results[self::RESULT_TOTAL] == 0) {
                break;
            }

            $results = array_merge($results, $paginated_results[$this->entity]);
        }

        return $results;
    }

    /**
     * Get a specified Entity from the API resource.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        // Prep the endpoint
        $endpoint = $this->getEndpoint();

        // params
        $params = [
            $this->id_parameter => $id
        ];

        // Get the resource
        return $this->client->get($endpoint, $params);
    }

    /**
     * Update a specified Entity from the API resource.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(array $data)
    {
        // Prep the endpoint
        $endpoint = $this->getEndpoint();

        // Get the resource
        return $this->client->put($endpoint, $data, ['content_type' => 'json']);
    }

    /**
     * Create a specified Entity from the API resource.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function create(array $data)
    {
        // Prep the endpoint
        $endpoint = $this->getEndpoint();

        // Get the resource
        return $this->client->post($endpoint, $data, ['content_type' => 'json']);
    }

    /**
     * Delete a specified Entity from the API resource
     *
     * @param $id
     * @return boolean
     * @throws Exception
     */
    public function delete(int $id)
    {
        // Prep the endpoint
        $endpoint = $this->getEndpoint() . "/" . $id;

        // Send "delete" request
        $response = $this->client->delete($endpoint, null, ['content_type' => 'json']);

        // Parse response
        $response = json_decode(json_encode($response), true);

        return isset($response['deleted']) ? $response['deleted'] : false;
    }
}
