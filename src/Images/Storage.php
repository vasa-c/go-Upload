<?php
/**
 * Storage of uploaded images
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images;

class Storage
{
    /**
     * Constructor
     *
     * @param array $config
     *        config of upload storage
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get config of upload storage
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Config of upload storage
     *
     * @var array
     */
    private $config;
}
