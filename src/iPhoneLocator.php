<?php
/**
 * @author Danny Carrillo <odannycx@gmail.com>
 * @package iphone-locator
 */
namespace iPhoneLocator;

class iPhoneLocator
{
    /**
     * @var Config $config This is the configuration class.
     */
    public $config;

    /**
     * iPhoneLocator constructor.
     *
     * @param string $username Username of iCloud.
     * @param string $password Password of iCloud.
     *
     * @throws \Exception
     */
    public function __construct($username, $password)
    {
        $this->config = new Config($username, $password);
        $this->app = new iPhoneLocatorApp($this->config);
    }

    /**
     * Returns all the devices with all their info.
     *
     * @return array
     */
    public function devices()
    {
        return $this->app->allDevices();
    }
}
