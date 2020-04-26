<?php
namespace App\Libraries\Myob;

use App\Libraries\Myob\Api\Shipment;
use App\Traits\LoggerTrait;
use App\Libraries\Myob\Api\Authenticate;
use Illuminate\Support\Facades\Cache;

class Myob
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Myob constructor.
     * @param $uri
     * @param $version
     */
    public function __construct($uri, $version)
    {
        $this->uri = $uri;
        $this->version = $version;
        $this->client = new Client($uri, $version);
    }

    /**
     * @param $url
     */
    public function setURL($url)
    {
        $this->uri = $url;
    }

    /**
     * @param $version
     */
    public function setVERSION($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function checkIfLoggedIn()
    {
        $cookie = $this->client->getCookieName();

        return app('cache')->has($cookie) ?
            true :
            false;
    }

    /**
     * @return Authenticate
     */
    public function auth()
    {
        return new Authenticate($this->client);
    }

    /**
     * @return Shipment
     */
    public function shipment()
    {
        return new Shipment($this->client);
    }
}
