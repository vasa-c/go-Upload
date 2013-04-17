<?php
/**
 * Autoloader go\Upload classes
 *
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload;

class Autoloader
{
    /**
     * Get autoloader for this library
     *
     * @return \go\Upload\Images\Autoloader
     */
    public static function getInstanceForThis()
    {
        if (!self::$instance) {
            self::$instance = new self(__NAMESPACE__, __DIR__);
        }
        return self::$instance;
    }

    /**
     * Register autoloader for this library
     */
    public static function register()
    {
        self::getInstanceForThis()->registerAsAutoloader();
    }

    /**
     * Unregister autoloader for this library
     */
    public static function unregister()
    {
        self::getInstanceForThis()->unregisterAsAutoloader();
    }

    /**
     * Constructor
     *
     * @param string $namespace
     *        library namespace
     * @param string $dir
     *        library root folder
     */
    public function __construct($namespace, $dir)
    {
        $this->prefix = $namespace ? $namespace.'\\' : '';
        $this->prefixlen = \strlen($this->prefix);
        $this->dir = $dir.\DIRECTORY_SEPARATOR;
    }

    /**
     * Register autoloader in system
     */
    public function registerAsAutoloader()
    {
        if (!$this->registered) {
            \spl_autoload_register($this->getCallbackForAutoload());
            $this->registered = true;
        }
    }

    /**
     * Remove autoloader from system
     */
    public function unregisterAsAutoloader()
    {
        if ($this->registered) {
            \spl_autoload_unregister($this->getCallbackForAutoload());
            $this->registered = false;
        }
    }


    /**
     * Load class by full name
     *
     * @param string $classname
     *        full name of class
     * @return bool
     *         true, if class was found
     */
    public function loadClassByFullName($classname)
    {
        $shortname = $this->getShortNameByFull($classname);
        if (!$shortname) {
            return false;
        }
        return $this->loadClassByShortName($shortname);
    }

    /**
     * Load class by short name (in current namespace)
     *
     * for example:
     * current namespace - One\Two
     * full name of class - One\Two\Three\Four
     * short name in current namespace - Three\Four
     *
     * @param string $shortname
     *        short name of class
     * @return bool
     *         true, if class was found
     */
    public function loadClassByShortName($shortname)
    {
        $filename = $this->getFilenameByShortName($shortname, true);
        if (!$filename) {
            return false;
        }
        require_once($filename);
        return true;
    }

    /**
     * Get shor name by full name
     *
     * @param string $classname
     *        full name
     * @return string
     *         short name or NULL (class from another namespace)
     */
    public function getShortNameByFull($classname)
    {
        if (\strpos($classname, $this->prefix) !== 0) {
            return null;
        }
        return \substr($classname, $this->prefixlen);
    }

    /**
     * Get filename of class by short name
     *
     * @param string $shortname
     *        short name of class
     * @param bool $check [optional]
     *        checks whether a file exists
     * @return string
     *         filename or NULL (file is not found)
     */
    public function getFilenameByShortName($shortname, $check = true)
    {
        $filename = \str_replace('\\', \DIRECTORY_SEPARATOR, $shortname).'.php';
        $filename = $this->dir.$filename;
        if ($check && (!\is_file($filename))) {
            return null;
        }
        return $filename;
    }

    /**
     * Get autoload function
     *
     * @return callback
     */
    public function getCallbackForAutoload()
    {
        return array($this, 'loadClassByFullName');
    }

    /**
     * Autoloader for this library
     *
     * @var \go\Upload\Images\Autoloader
     */
    private static $instance;

    /**
     * Prefix of class name (in current namespace)
     *
     * @var string
     */
    private $prefix;

    /**
     * Length of prefix
     *
     * @var int
     */
    private $prefixlen;

    /**
     * Library root folder
     *
     * @var string
     */
    private $dir;

    /**
     * Whether the autoloader is registered
     *
     * @var bool
     */
    private $registered = false;
}
