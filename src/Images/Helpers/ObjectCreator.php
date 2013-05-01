<?php
/**
 * Create objects by params
 */

namespace go\Upload\Images\Helpers;

use go\Upload\Images\Config;
use go\Upload\Images\Exceptions;

class ObjectCreator
{
    /**
     * Create object by params
     *
     * @param mixed $params
     *        params for create
     * @param go\Upload\Images\Config $config [optional]
     *        parent config (array or Config instance)
     * @param string $namespace [optional]
     *        root namespace
     * @return object
     *         created object
     * @throws go\Upload\Images\Exceptions\CreatorParams
     *         error params format
     */
    public static function create($params, $config = null, $namespace = null)
    {
        if (\is_object($params)) {
            if ($params instanceof \Closure) {
                $nconfig = self::createConfig(null, $config);
                return self::createByCreator($params, $nconfig, $namespace);
            }
            return $params;
        }
        if (\is_scalar($params)) {
            $nconfig = self::createConfig(null, $config);
            return self::createByClassname($params, $namespace, array($nconfig));
        }
        if (\is_array($params)) {
            if (\array_key_exists('classname', $params)) {
                $nconfig = self::createConfig($params, $config);
                return self::createByClassname($params['classname'], $namespace, array($nconfig));
            } elseif (\array_key_exists('creator', $params)) {
                $nconfig = self::createConfig($params, $config);
                return self::createByCreator($params['creator'], $nconfig, $namespace);
            } else {
                self::error('classname and creator not found');
            }
        }
        self::error('error type of params');
    }

    /**
     * Create instance by classname
     * 
     * @param string $classname
     * @param string $namespace [optional]
     * @param array $args [optional]
     * @return object
     */
    public static function createByClassname($classname, $namespace = null, array $args = null)
    {
        if ($namespace && (\substr($classname, 0, 1) !=='\\')) {
            $classname = $namespace.'\\'.$classname;
        }
        if (!\class_exists($classname, true)) {
            self::error('class "'.$classname.'" is not found');
        }
        $class = new \ReflectionClass($classname);
        return $class->newInstanceArgs($args);
    }

    /**
     * @param Closure $creator
     * @param mixed $config
     * @param string $namespace
     * @throws go\Upload\Images\Exceptions\CreatorParams
     */
    private static function createByCreator($creator, $config, $namespace)
    {
        if (!($creator instanceof \Closure)) {
            self::error('creator is not function');
        }
        $instance = $creator($config, $namespace);
        if (!\is_object($instance)) {
            self::error('creator return not object');
        }
        return $instance;
    }

    /**
     * @param mixed $params
     * @param mixed $config
     * @return go\Upload\Images\Config
     */
    private static function createConfig($params, $config)
    {
        $params = $params ?: array();
        if (\is_null($config)) {
            return new Config($params);
        } elseif (\is_array($config)) {
            return new Config($params, new Config($config));
        } elseif ($config instanceof Config) {
            return new Config($params, $config);
        }
        self::error('error');
    }

    /**
     * @param string $message
     * @throws go\Upload\Images\Exceptions\CreatorParams
     */
    private static function error($message)
    {
        throw new Exceptions\CreatorParams($message);
    }
}
