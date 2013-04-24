<?php
/**
 * Basic class of upload types
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Types;

use go\Upload\Images\Storage;
use go\Upload\Images\Exceptions;

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
    public static function getTypeByKind(Storage $storage, $name)
    {
        $config = $storage->getConfig();
        if (!isset($config['types'][$name])) {
            throw new Exceptions\TypeNotFound($name);
        }
        $type = $config['types'][$name];
        if ((!\is_array($type)) && (!isset($type['kind']))) {
            throw new Exceptions\ConfigFormat('not specified kind for type "'.$name.'"');
        }
        $kind = $type['kind'];
        $classname = __NAMESPACE__.'\\'.$kind; // @todo
        return new $classname($storage, $name); // @todo class_exists
    }

    /**
     * Constructor
     *
     * @param \go\Upload\Images\Storage $storage
     *        parent storage
     * @param string $name
     *        type name
     */
    public function __construct(Storage $storage, $name)
    {
        $this->storage = $storage;
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
     * Name of type
     *
     * @var string
     */
    protected $name;
}
