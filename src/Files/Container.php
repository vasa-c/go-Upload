<?php
/**
 * List of uploaded files
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files;

class Container
{
    /**
     * Get container instance for system ($_FILES)
     *
     * @return \go\Upload\Files\Container
     */
    public static function getInstanceForSystem()
    {
        if (!self::$instanceSystem) {
            self::$instanceSystem = new self(null);
        }
        return self::$instanceSystem;
    }

    /**
     * Constructor
     *
     * @param array $files [optional]
     *        files list ($_FILES by default)
     * @throws \go\Upload\Files\Exceptions\Format
     */
    public function __construct(array $files = null)
    {
        if (!$files) {
            $files = FilesArray::getSystemArray();
        }
        $this->files = FilesArray::normalize($files);
    }

    /**
     * Finds out whether single item is exists
     *
     * @param string $name
     * @return bool
     */
    public function existsSingle($name)
    {
        if (!isset($this->files[$name])) {
            return false;
        }
        $v = $this->files[$name];
        if ((!isset($v['name'])) || \is_array($v['name'])) {
            return false;
        }
        return true;
    }

    /**
     * Finds out whether plain list is exists
     *
     * @param string $name
     * @return bool
     */
    public function existsPlainList($name)
    {
        if (!isset($this->isplain[$name])) {
            $this->isplain[$name] = $this->checkPlain($name);
        }
        return $this->isplain[$name];
    }

    /**
     * Finds out whether tree-like list is exists
     *
     * @param string $name
     * @return bool
     */
    public function existsTreeList($name)
    {
        if (!isset($this->files[$name])) {
            return false;
        }
        $v = $this->files[$name];
        if (isset($v['name']) && (!\is_array($v['name']))) {
            return false;
        }
        return true;
    }

    /**
     * Get file by single item
     *
     * @param string $name
     *        key of array
     * @return \go\Upload\Files\Item
     *         Item object
     * @throws \go\Upload\Files\Exceptions\ItemNotFound
     */
    public function getSingle($name)
    {
        if (!$this->existsSingle($name)) {
            throw new Exceptions\ItemNotFound($name);
        }
        return new Item($this->files[$name]);
    }

    /**
     * Get plain list by name
     *
     * @param string $name [optional]
     *        by default - full array
     * @param bool $uploaded
     *        only uploaded files
     * @throws \go\Upload\Files\Exceptions\ItemNotFound
     */
    public function getPlainList($name = null, $uploaded = false)
    {
        if ($name) {
            if (!$this->existsPlainList($name)) {
                throw new Exceptions\ItemNotFound($name);
            }
            $items = $this->files[$name];
        } else {
            $items = $this->files;
        }
        $result = array();
        foreach ($items as $k => $v) {
            if (($v['error'] == 0) || (!$uploaded)) {
                $result[$k] = new Item($v);
            }
        }
        return $result;
    }

    /**
     * Get tree-like list by name
     *
     * @param string $name [optional]
     *        by default - full array
     * @param bool $uploaded
     *        only uploaded files
     * @throws \go\Upload\Files\Exceptions\ItemNotFound
     */
    public function getTreeList($name = null, $uploaded = false)
    {
        if ($name) {
            if (!$this->existsTreeList($name)) {
                throw new Exceptions\ItemNotFound($name);
            }
            $items = $this->files[$name];
        } else {
            $items = $this->files;
        }
        return $this->getTreeNode($items, $uploaded);
    }

    /**
     * Finds out whether files[name] is plain list
     *
     * @param string $name
     * @return bool
     */
    private function checkPlain($name)
    {
        if (\is_null($name)) {
            $list = $this->files;
        } else {
            if (!isset($this->files[$name])) {
                return false;
            }
            $list = $this->files[$name];
            if (!\is_array($list)) {
                return false;
            }
        }
        foreach ($list as $k => $v) {
            if (!\is_array($v)) {
                return false;
            }
            if (!isset($v['name'])) {
                return false;
            }
            if (\is_array($v['name'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $node
     * @param bool $uploaded
     * @return array
     */
    private function getTreeNode($node, $uploaded)
    {
        $result = array();
        foreach ($node as $k => $v) {
            if (isset($v['name']) && (!\is_array($v['name']))) {
                if (($v['error'] == 0) || (!$uploaded)) {
                    $result[$k] = new Item($v);
                }
            } else {
                $result[$k] = $this->getTreeNode($v, $uploaded);
            }
        }
        return $result;
    }

    /**
     * Normalized files
     *
     * @var array
     */
    private $files;

    /**
     * Cache existsPlain
     *
     * @var array
     */
    private $isplain = array();

    /**
     * Instance for $_FILES
     *
     * @var array
     */
    private static $instanceSystem;
}
