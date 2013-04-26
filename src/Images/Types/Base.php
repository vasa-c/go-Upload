<?php
/**
 * Basic class of upload types
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Types;

use go\Upload\Images\Storage;
use go\Upload\Images\Config;

abstract class Base
{
    /**
     * Get type instance by config params
     *
     * @param \go\Upload\Images\Storage $storage
     *        parent storage
     * @param string $name
     *        name of type
     * @return \go\Upload\Images\Types\Base
     *         requested type instance
     * @throws \go\Upload\Images\Exception\TypeNotFound
     *         type is not found
     * @throws \go\Upload\Images\Exception\ConfigFormat
     *         error type config format
     */
    public static function createTypeInstance(Storage $storage, $name)
    {
        $config = $storage->getConfig();
        $ctypes = $config->child('types');
        $ctype = $ctypes->get($name, 'array');
        $ctype = new Config($ctype, $config, 'Type['.$name.']');
        $kind = $ctype->get('kind', 'string');
        $classname = __NAMESPACE__.'\\'.$kind; // @todo
        return new $classname($storage, $ctype, $name); // @todo class_exists
    }

    /**
     * Constructor (protected. use createTypeInstance)
     *
     * @param \go\Upload\Images\Storage $storage
     *        parent storage
     * @param \go\Upload\Images\Config $config
     *        config of type
     * @param string $name
     *        type name
     */
    protected function __construct(Storage $storage, Config $config, $name)
    {
        $this->storage = $storage;
        $this->config = $config;
        $this->name = $name;
    }

    /**
     * Get parent storage
     *
     * @return \go\Upload\Images\Storage
     */
    final public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Get type config
     *
     * @return \go\Upload\Images\Config
     */
    final public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get name of type
     *
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Parent storage
     *
     * @var \go\Upload\Images\Storage
     */
    protected $storage;

    /**
     * Config of type
     *
     * @var \go\Upload\Images\Config
     */
    protected $config;

    /**
     * Name of type
     *
     * @var string
     */
    protected $name;
}
