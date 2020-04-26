<?php
namespace App\Libraries\Machship;

class CurlService
{
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_GET = 'GET';
    const METHOD_DELETE = 'DELETE';

    protected $url;
    protected $http_code;
    protected $response;
    protected $errors;
    private $remainingRequests;
    private $resetTime;
    private $lastRequestTime;

    protected $headers = [
        'Content-Type' => 'Content-Type: application/json',
    ];

    public function __construct($token, $url = null)
    {
        $this->url = $url ? $url : config('machship.uri');
        $this->setHeaders('Token', $token);
    }

    public function setHeaders($key, $value)
    {
        $this->headers[$key] = $value ? "{$key}: {$value}" : $value;
    }

    public function getStatusCode()
    {
        return $this->http_code;
    }

    public function setStatusCode($code)
    {
        $this->http_code = $code;
    }

    public function getContents()
    {
        return $this->response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    private function request($method, $service, $data = [])
    {
        $curl = curl_init();
        $final_url = $this->url . $service;

        if (array_key_exists('custom_url', $data)) {
            $final_url = $data['custom_url'];
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => $final_url,
            CURLOPT_HEADER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        switch ($method) {
            case 'PUT':
            case 'PATCH':
            case 'POST':
                curl_setopt_array($curl, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($data)
                ]);
                $this->setHeaders('Content-Length', strlen(json_encode($data)));
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::METHOD_GET);
                break;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, array_filter(array_values($this->headers)));

        $response = curl_exec($curl);

        $this->setStatusCode(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $this->errors = curl_error($curl) ? curl_error($curl) : null;
        $this->response = json_decode(
            substr($response, curl_getinfo($curl, CURLINFO_HEADER_SIZE)),
            false,
            512,
            JSON_BIGINT_AS_STRING
        );
        curl_close($curl);

        return $this->response;
    }

    /**
     * GET
     * @param $url
     * @param $data
     * @return mixed
     */
    public function get($url, $data = [])
    {
        // add the api limiter
        // $this->enforceApiRateLimit();

        return $this->request(self::METHOD_GET, $url, $data);
    }

    /**
     * POST
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function post($url, $data = [])
    {
        // add the api limiter
        // $this->enforceApiRateLimit();

        return $this->request(self::METHOD_POST, $url, $data);
    }

    /**
     * PUT
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function put($url, $data = [])
    {
        // add the api limiter
        // $this->enforceApiRateLimit();

        return $this->request(self::METHOD_PUT, $url, $data);
    }

    /**
     * DELETE
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function delete($url, $data = [])
    {
        // add the api limiter
        // $this->enforceApiRateLimit();

        return $this->request(self::METHOD_DELETE, $url, $data);
    }

    private function enforceApiRateLimit()
    {

        if ($this->remainingRequests > 0) {
            return;
        } else {
            if (!empty($this->lastRequestTime)) {
                $elapsedTime = (time() - $this->lastRequestTime);
                if ($elapsedTime > $this->resetTime) {
                    return;
                } else {
                    $waitingTime =  ($this->resetTime - $elapsedTime);
                    sleep($waitingTime);
                }
            } else {
                return;
            }
        }
    }
}
