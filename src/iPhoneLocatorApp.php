<?php
/**
 * @author Danny Carrillo <odannycx@gmail.com>
 * @package iphone-locator
 */

namespace iPhoneLocator;

class iPhoneLocatorApp
{
    private $config;
    private $http;
    public $devices = [];

    public function __construct($config)
    {
        if (!extension_loaded('curl')) {
            throw new \Exception('cURL Extension is missing.');
        }

        $this->config = $config;
        $this->http = new Http($this->config);

        // We need to initialize the application through Http
        $this->devices = $this->http->init();
    }

    /**
     * Returns all the current devices.
     *
     * @return array
     */
    public function allDevices()
    {
        // Just to make sure, lets refresh the client
        $this->http->refresh();

        return $this->devices;
    }

    /**
     * Locates the device and returns it's location.
     *
     * @param int $deviceId
     * @param int $timeout
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function locateDevice($deviceId, $timeout = 120)
    {
        // Make sure that the device ID passed is an integer
        if (!is_integer($deviceId)) {
            throw new \Exception('Expected $device to be an integer');
        }

        // If the device ID passed is not a part of the devices in the array: refresh the client
        if (!isset($this->devices[$deviceId])) {
            $this->devices = $this->http->refresh();
        }

        // We need to start the timer for timeout reasons
        $start = time();
        while (!$this->devices[$deviceId]->location->locationFinished) {
            if ((time() - $start) > intval($timeout)) {
                throw new \Exception('Failed to locate device! Request timed out.');
            }
            sleep(5);
            $this->http->refresh();
        }

        return $this->devices[$deviceId]->location;
    }
}
