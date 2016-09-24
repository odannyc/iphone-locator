<?php
/**
 * @author Danny Carrillo <odannycx@gmail.com>
 * @package iphone-locator
 */
namespace iPhoneLocator;

class Config
{
    /**
     * @var string $username The username of the icloud user.
     */
    public $username;

    /**
     * @var string $password The password of the icloud user.
     */
    public $password;

    /**
     * @var string $host
     */
    public $host = 'fmipmobile.icloud.com';

    /**
     * @var array $client_context The stuff needed to send to the client.
     */
    public $clientContext = array(
        'appName' => 'FindMyiPhone',
        'appVersion' => '3.0',
        'buildVersion' => '376',
        'clientTimestamp' => 0,
        'deviceUDID' => null,
        'inactiveTime' => 1,
        'osVersion' => '7.0.3',
        'productType' => 'iPhone6,1'
    );

    /**
     * @var array $server_context The stuff needed to send to the client.
     */
    public $serverContext = array(
        'callbackIntervalInMS' => 10000,
        'classicUser' => false,
        'clientId' => null,
        'cloudUser' => true,
        'deviceLoadStatus' => '200',
        'enableMapStats' => false,
        'isHSA' => false,
        'lastSessionExtensionTime' => null,
        'macCount' => 0,
        'maxDeviceLoadTime' => 60000,
        'maxLocatingTime' => 90000,
        'preferredLanguage' => 'en-us',
        'prefsUpdateTime' => 0,
        'sessionLifespan' => 900000,
        'timezone' => null,
        'trackInfoCacheDurationInSecs' => 86400,
        'validRegion' => true
    );

    /**
     * Sets the username and password.
     *
     * @param string $username
     * @param string $password
     *
     * @throws \Exception
     *
     * @return Config $instance
     */
    public function __construct($username, $password)
    {
        if (!is_string($username)) {
            throw new \Exception('Username was not passed or is not a string.');
        }
        if (!is_string($password)) {
            throw new \Exception('Password was not passed or is not a string.');
        }

        $this->username = $username;
        $this->password = $password;
    }
}
