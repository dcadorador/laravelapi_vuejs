<?php
namespace App\Libraries\Myob\Api;

use App\Libraries\Myob\Client;

abstract class ApiAbstract
{
    /**
     * @var Client
     */
    protected $client;

    /*
     * @var string
     */
    protected $endpoint;

    /**
     * Inject API Client
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
