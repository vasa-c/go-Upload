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
     * Get type by name
     *
     * @param string $name
     *        type name
     * @return \go\Upload\Images\Types\Base
     *         type instance
     * @throws \go\Upload\Images\Exceptions\TypeNotFound
     *         type is not found
     * @throws \go\Upload\Images\Exceptions\ConfigFormat
     *         error type config
     */
    public function getType($name)
    {
        if (!isset($this->types[$name])) {
            $this->types[$name] = Types\Base::getTypeByKind($this, $name);
        }
        return $this->types[$name];
    }

    /**
     * Config of upload storage
     *
     * @var array
     */
    private $config;

    /**
     * Cache of instances of types
     *
     * @var array
     */
    private $types = array();
}
