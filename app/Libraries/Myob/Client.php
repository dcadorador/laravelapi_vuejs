<?php
namespace App\Libraries\Myob;

use App\Models\DebugLogs;
use Illuminate\Support\Facades\Cache;
use App\Traits\LoggerTrait;

class Client
{
    use LoggerTrait;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @int string
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $baseURL;

    /**
     * @var array headers
     */
    protected $headers = [
        'Content-Type' => 'Content-Type: application/json'
    ];
    private $cookieName;

    /**
     * Client constructor.
     * @param $url
     * @param $version
     */
    public function __construct($url, $version)
    {
        // this should contain initialization
        $this->baseURL = $url . $version;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setHeaders($key, $value)
    {
        $this->headers[$key] = $value ? "{$key}: {$value}" : $value;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $code
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    /**
     * @return string
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * @return mixed
     */
    public function getCookieName()
    {
        return $this->cookieName;
    }

    /**
     * @param $name
     */
    public function setCookieName($name)
    {
        $this->cookieName = $name;
    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $data
     * @param null $filter
     * @return array
     */
    private function request($method, $endpoint, $data = [], $filter = null)
    {
        // initialize the CURL
        $curl = curl_init();

        // set the final url
        $finalURL = array_key_exists('custom_url', $data)
            ? $data['custom_url'] :
            $this->baseURL . $endpoint;

        // process and encode URL parameters
        if ($filter) {
            $finalURL = $finalURL . '?' . $filter;
            $this->infoLog('CURL Request URL', 'URL: ' .$finalURL);
        }

        // format the CURL FILE for the CLIENT
        $url = $this->getBaseURL();
        $cookieName = str_replace('/entity/', '', str_replace('https://', '', substr($url, 0, strpos($url, "Default"))));
        // $cookieFile = storage_path() . '/' . $cookieName . '.txt';

        // set curl options
        curl_setopt_array($curl, [
            CURLOPT_URL => $finalURL,
            CURLOPT_HEADER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIESESSION => true
        ]);

        // save the COOKIE FILE first during LOGIN
        // todo: please check @John
        /*if (strpos($endpoint, 'auth') !== false) {
            curl_setopt($curl, CURLOPT_COOKIEJAR, realpath($cookieFile));
            curl_setopt($curl, CURLOPT_COOKIEFILE, realpath($cookieFile));
        } else {
            curl_setopt($curl, CURLOPT_COOKIEFILE, realpath($cookieFile));
        }*/

        // todo: ARRRGGGHHHHH!!!! This is NOT RIGHT, ABOVE SHOULD BE THE SOLUTION BUT NOT WORKING LOCALLY!!!! F*CK!!!!!!
        if (Cache::has($cookieName) && strpos($endpoint, 'auth') === false) {
            curl_setopt($curl, CURLOPT_COOKIE, Cache::get($cookieName));
        }

        // set DATA for the request
        switch ($method) {
            case 'PUT':
                // set CURL POST fields
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::METHOD_PUT);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_TIMEOUT, 120); // 2 mins timeout
                // set the Content-Length header for the data
                $this->setHeaders('Content-Length', strlen(json_encode($data)));
                break;
            case 'PATCH':
            case 'POST':
                // set CURL POST fields
                curl_setopt_array($curl, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($data)
                ]);
                // set the Content-Length header for the data
                $this->setHeaders('Content-Length', strlen(json_encode($data)));
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::METHOD_GET);
                break;
        }

        // set CURL Headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, array_filter(array_values($this->headers)));

        // execute CURL request
        $curlResponse = curl_exec($curl);

        // get the status code from response
        $this->setStatusCode(curl_getinfo($curl, CURLINFO_HTTP_CODE));

        // todo: remove this if, LINE 93 is working!!
        // added this there here as MANUAL HANDLER for COOKIE, there is a bug CURLOPT_COOKIEFILE
        if (strpos($endpoint, 'auth') !== false) {
            $this->parseCookie($curlResponse, $cookieName);
        }

        // get response
        $response = json_decode(
            substr($curlResponse, curl_getinfo($curl, CURLINFO_HEADER_SIZE)),
            true,
            512,
            JSON_BIGINT_AS_STRING
        );

        // close CURL
        curl_close($curl);

        // return data as an array
        return $response;
    }

    /**
     * @param $curlResponse
     * @param $cacheKey
     */
    private function parseCookie($curlResponse, $cacheKey)
    {
        // match all instances of set cookie
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $curlResponse, $matches);

        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        // set the cookie name
        $this->setCookieName($cacheKey);

        if (count($cookies)) {
            $cookie_auth = '';
            foreach ($cookies as $key => $value) {
                if (stripos($key, 'ASPXAUTH') !== false) {
                    $cookie_auth .= '.ASPXAUTH'. '=' . $value . '; path=/; HttpOnly;';
                }
                if (stripos($key, 'NET_SessionId') !== false) {
                    $cookie_auth .= 'ASP.NET_SessionId'. '=' . $value . '; path=/; HttpOnly;';
                }
            }
            Cache::put($cacheKey, $cookie_auth, 3600);
        }
    }

    /**
     * @param $endpoint
     * @param array $data
     * @param array $filter
     * @return array
     */
    public function get($endpoint, $data = [], $filter = [])
    {
        return $this->request(self::METHOD_GET, $endpoint, $data, $filter);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return array
     */
    public function post($endpoint, $data = [])
    {
        return $this->request(self::METHOD_POST, $endpoint, $data);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @param array $filter
     * @return array
     */
    public function put($endpoint, $data = [], $filter = [])
    {
        return $this->request(self::METHOD_PUT, $endpoint, $data, $filter);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return array
     */
    public function delete($endpoint, $data = [])
    {
        return $this->request(self::METHOD_DELETE, $endpoint, $data = []);
    }
}
