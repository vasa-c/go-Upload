<?php
/**
 * Class for test ObjectCreator
 *
 * @package go\Upload\Images
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Images\Helpers\mocks;

class UserObject
{
    /**
     * Constructor
     *
     * @param \go\Upload\Images\Config $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
    }

    /**
     * @return \go\Upload\Images\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @var \go\Upload\Images\Config
     */
    private $config;
}
