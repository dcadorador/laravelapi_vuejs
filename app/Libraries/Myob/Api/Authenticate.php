<?php
namespace App\Libraries\Myob\Api;

class Authenticate extends ApiAbstract
{
    /**
     * @var string
     */
    protected $endpoint = 'auth/';

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public function login($username, $password)
    {
        // create the custom URL for login based on the BASE URL
        $baseURL = $this->client->getBaseURL();
        $url = substr($baseURL, 0, strpos($baseURL, "Default")) . $this->endpoint . 'login';

        // create the data for the login, with the custom URL
        $data = [
            'name' => $username,
            'password' => $password,
            'custom_url' => $url
        ];

        // send api request
        $response = $this->client->post($this->endpoint, $data);

        // get status code
        $statusCode = $this->client->getStatusCode();

        return ($statusCode == 204) ?
            [ 'message' => 'Successfully logged in. '] :
            $response;
    }

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public function logout($username, $password)
    {
        // create the custom URL for login based on the BASE URL
        $baseURL = $this->client->getBaseURL();
        $url = substr($baseURL, 0, strpos($baseURL, "Default")) . $this->endpoint . 'logout';


        // create the data for the login, with the custom URL
        $data = [
            'name' => $username,
            'password' => $password,
            'custom_url' => $url
        ];

        // send api request
        $response =  $this->client->post($this->endpoint, $data);

        // get status code
        $statusCode = $this->client->getStatusCode();

        return ($statusCode == 204) ?
            [ 'message' => 'Successfully logged out. '] :
            $response;
    }
}
