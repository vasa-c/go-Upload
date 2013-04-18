<?php
/**
 * Handling system array $_FILES
 *
 * @package go\Upload\FilesArray
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files;

class FilesArray
{
    /**
     * Get system array of upload variables
     *
     * @return array
     */
    public static function getSystemArray()
    {
        return $_FILES;
    }

    /**
     * Normalize array for tree-like structures
     *
     * @param array $files [optional]
     * @return array
     * @throws \go\Upload\Files\Exceptions\Format
     */
    public static function normalize(array $files = null)
    {
        if (!$files) {
            $files = self::getSystemArray();
        }
        $result = array();
        foreach ($files as $key => $value) {
            if (!\is_array($value)) {
                throw new Exceptions\Format();
            }
            if (self::isValidItem($value)) {
                $result[$key] = $value;
            } else {
                $result[$key] = self::normalizeTree($value);
            }
        }
        return $result;
    }

    /**
     * Check item params format
     *
     * @param array $params
     * @return bool
     */
    public static function isValidItem(array $params)
    {
        if (\count($params) != 5) {
            return false;
        }
        foreach (self::$fields as $field) {
            if (!\array_key_exists($field, $params)) {
                return false;
            }
            if (!\is_scalar($params[$field])) {
                return false;
            }
        }
        if ((!\is_int($params['size'])) || (!\is_int($params['error']))) {
            return false;
        }
        return true;
    }

    /**
     * Normalize tree-like struct
     *
     * @param array $arr
     * @return array
     * @throws \go\Upload\Files\Exceptions\Format
     */
    private static function normalizeTree(array $arr)
    {
        if (\count($arr) != 5) {
            throw new Exceptions\Format();
        }
        foreach (self::$fields as $field) {
            if (!\array_key_exists($field, $arr)) {
                throw new Exceptions\Format();
            }
            $v = $arr[$field];
            if (!\is_array($v)) {
                throw new Exceptions\Format();
            }
        }
        $result = array();
        foreach ($arr['name'] as $k => $v) {
            if (\is_array($v)) {
                $result[$k] = self::loadTreeArray($arr, $k);
            } else {
                $result[$k] = self::loadTreeScalar($arr, $k);
            }
        }
        return $result;
    }

    /**
     * @param array $arr
     * @param string $key
     * @return array
     * @throws Exceptions\Format
     */
    private static function loadTreeScalar($arr, $key)
    {
        $result = array();
        foreach (self::$fields as $field) {
            $f = $arr[$field];
            if ((!\array_key_exists($key, $f)) || \is_array($f[$key])) {
                throw new Exceptions\Format();
            }
            $result[$field] = $f[$key];
        }
        return $result;
    }

    /**
     * @param array $arr
     * @param string $key
     * @return array
     * @throws Exceptions\Format
     */
    private static function loadTreeArray($arr, $key)
    {
        $narr = array();
        foreach (self::$fields as $field) {
            $f = $arr[$field];
            if ((!\array_key_exists($key, $f)) || (!\is_array($f[$key]))) {
                throw new Exceptions\Format();
            }
            $narr[$field] = $f[$key];
        }
        $result = array();
        foreach ($narr['name'] as $k => $v) {
            if (\is_array($v)) {
                $result[$k] = self::loadTreeArray($narr, $k);
            } else {
                $result[$k] = self::loadTreeScalar($narr, $k);
            }
        }
        return $result;
    }

    /**
     * @var array
     */
    private static $fields = array('name', 'type', 'tmp_name', 'size', 'error');
}
