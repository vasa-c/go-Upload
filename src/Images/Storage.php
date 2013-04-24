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
     * @throws \go\Upload\Images\Exceptions\ConfigFormat
     */
    public function __construct(array $config)
    {
        if ((!isset($config['types'])) || (!\is_array($config['types']))) {
            throw new Exceptions\ConfigFormat('types list is not found');
        }
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
