<?php
/**
 * Chain of configs
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Images;

class Config
{
    /**
     * Constructor
     *
     * @param array $config
     *        current config
     * @param \go\Upload\Images\Config $parent [optional]
     *        parent config
     * @param string $name [optional]
     *        name of config owner (for debug)
     */
    public function __construct(array $config, Config $parent = null, $name = null)
    {
        $this->current = $config;
        $this->parent = $parent;
        $this->name = $name;
    }

    /**
     * Get value of config property
     *
     * @param string $key
     *        name of property
     * @param string $type [optional]
     *        type of value (including scalar)
     * @return mixed
     *         value
     * @throws \go\Upload\Images\Exceptions\PropNotFound
     *         property is not found or type error
     */
    public function get($key, $type = null)
    {
        if (!\array_key_exists($key, $this->cache)) {
            if (\array_key_exists($key, $this->current)) {
                $this->cache[$key] = $this->current[$key];
            } else {
                if (!$this->parent) {
                    throw new Exceptions\PropNotFound($key, $this->name);
                }
                $this->cache[$key] = $this->parent->get($key, null);
            }
        }
        $result = $this->cache[$key];
        if ($type) {
            if ($type == 'scalar') {
                if (!\is_scalar($result)) {
                    throw new Exceptions\PropNotFound($key, $this->name);
                }
            } elseif (\gettype($result) != $type) {
                throw new Exceptions\PropNotFound($key, $this->name);
            }
        }
        return $result;
    }

    /**
     * Determine if property exists in config
     *
     * @param string $key
     * @param string $type [optional]
     * @return bool
     */
    public function exists($key, $type = null)
    {
        try {
            $this->get($key, $type);
        } catch (Exceptions\PropNotFound $e) {
            return false;
        }
        return true;
    }

    /**
     * Determine if property exists in current config
     *
     * @param string $key
     * @return bool
     */
    public function hasOwnProperty($key)
    {
        return \array_key_exists($key, $this->current);
    }

    /**
     * Get child config
     *
     * @param string $key
     *        property name
     * @param string $name [optional]
     *        config name (for debug)
     * @return \go\Upload\Images\Config
     *         child config
     * @throws \go\Upload\Images\Exceptions\PropNotFound
     *         property is not found or is not array
     */
    public function child($key, $name = null)
    {
        if (\is_null($name)) {
            $name = $this->name.'.'.$name;
        }
        return new self($this->get($key, 'array'), $this, $name);
    }

    /**
     * Get parent config
     *
     * @return \go\Upload\Images\Config
     */
    public function parentGet()
    {
        return $this->parent;
    }

    /**
     * Parent config
     *
     * @var \go\Upload\Images\Config
     */
    private $parent;

    /**
     * Name of config
     *
     * @var string
     */
    private $name;

    /**
     * Cache of config
     *
     * @var array
     */
    private $cache = array();

    /**
     * Current config
     *
     * @var array
     */
    private $current;

    /**
     * Cache of childs
     *
     * @var array
     */
    private $childs = array();
}
