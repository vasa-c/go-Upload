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
    public function __construct($config)
    {
        if (\is_array($config)) {
            $config = new Config($config, self::getRootConfig(), 'Storage');
        } elseif (!($config instanceof Config)) {
            throw new Exceptions\ConfigFormat('Config must be array or Config instance');
        }
        $this->config = $config;
        $this->config->child('types', 'Types');
    }

    /**
     * Get config of upload storage
     *
     * @return go\Upload\Config
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
            $this->types[$name] = Types\Base::createTypeInstance($this, $name);
        }
        return $this->types[$name];
    }

    /**
     * Get root config (default options)
     *
     * @return \go\Upload\Images\Config
     */
    final public static function getRootConfig()
    {
        if (!self::$rootConfig) {
            self::$rootConfig = new Config(include(__DIR__.'/defaults.php'));
        }
        return self::$rootConfig;
    }

    /**
     * Config of upload storage
     *
     * @var \go\Upload\Images\Config
     */
    private $config;

    /**
     * Cache of instances of types
     *
     * @var array
     */
    private $types = array();

    /**
     * @var \go\Upload\Images\Config
     */
    private static $rootConfig;
}
