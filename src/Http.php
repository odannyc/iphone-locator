<?php
/**
 * @author Danny Carrillo <odannycx@gmail.com>
 * @package iphone-locator
 */

namespace iPhoneLocator;

class Http
{
    /**
     * @var Config $config The config class saved to a variable
     */
    public $config;

    /**
     * @var string $scope Not really sure why this is used
     */
    private $scope;

    /**
     * Http constructor.
     *
     * @param Config $config The Config class/object.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Initializes the icloud client.
     */
    public function init()
    {
        $data = json_encode([
            'clientContext' => $this->config->clientContext
        ]);
        $headers = $this->parseHeaders($this->request('initClient', $data, true));
        $this->config->host = $headers['X-Apple-MMe-Host'];
        $this->scope = $headers['X-Apple-MMe-Scope'];

        return $this->refresh();
    }

    /**
     * Refresh Client
     */
    public function refresh()
    {
        $post_data = json_encode([
            'clientContext' => $this->config->clientContext,
            'serverContext' => $this->config->serverContext
        ]);

        $res= json_decode($this->request('refreshClient', $post_data))->content;
        $devices = [];

        foreach ($res as $id => $device) {
            $devices[$id] = $device;
        }

        return $devices;
    }

    /**
     * Make request to the Find My iPhone server.
     *
     * @param $method - the method
     * @param $data - the POST data
     * @param $returnHeaders - also return headers when true
     * @param $headers - optional headers to send
     *
     * @throws \Exception
     *
     * @return HTTP response
     */
    private function request($method, $data, $returnHeaders = false, $headers = [])
    {
        if (!is_string($method)) {
            throw new \Exception('Expected $method to be a string');
        }
        if (!$this->is_json($data)) {
            throw new \Exception('Expected $post_data to be json');
        }
        if (!is_array($headers)) {
            throw new \Exception('Expected $headers to be an array');
        }
        if (!is_bool($returnHeaders)) {
            throw new \Exception('Expected $returnHeaders to be a bool');
        }
        if (!isset($this->scope)) {
            $this->scope = $this->config->username;
        }
        array_push($headers, 'Accept-Language: en-us');
        array_push($headers, 'Content-Type: application/json; charset=utf-8');
        array_push($headers, 'X-Apple-Realm-Support: 1.0');
        array_push($headers, 'X-Apple-Find-Api-Ver: 3.0');
        array_push($headers, 'X-Apple-Authscheme: UserIdGuest');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_TIMEOUT => 9,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_VERBOSE => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => $returnHeaders,
            CURLOPT_URL => sprintf("https://%s/fmipservice/device/%s/%s", $this->config->host, $this->scope, $method),
            CURLOPT_USERPWD => $this->config->username . ':' . $this->config->password,
            CURLOPT_USERAGENT => 'FindMyiPhone/376 CFNetwork/672.0.8 Darwin/14.0.0'
        ]);
        $http_result = curl_exec($curl);
        curl_close($curl);
        return $http_result;
    }

    /**
     * Parse cURL headers
     *
     * @param $response - cURL response including the headers
     *
     * @return array of headers
     */
    private function parseHeaders($response) {
        $headers = array();
        foreach (explode("\r\n", substr($response, 0, strpos($response, "\r\n\r\n"))) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    /**
     * Finds whether a variable is json.
     *
     * @param $var
     *
     * @return bool
     */
    private function is_json($var) {
        json_decode($var);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
