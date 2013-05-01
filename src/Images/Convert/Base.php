<?php
/**
 * Basic implementation of converter
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Convert;

use go\Upload\Images\Config;
use go\Upload\Images\Exceptions;

abstract class Base implements Iface
{
    /**
     * Constructor
     *
     * @param \go\Upload\Images\Config $config
     *        convert options
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->nativeClassname = $this->config->get('native_adapter', 'string');
        if (\substr($this->nativeClassname, 0, 1) != '\\') {
            $this->nativeClassname = __NAMESPACE__.'\\'.$this->nativeClassname;
        }
    }

    /**
     * @override \go\Upload\Images\Convert\Iface
     *
     * @param string $infile
     * @param string $outfile
     * @throws \go\Upload\Images\Exceptions\SourceFileError
     * @throws \go\Upload\Images\Exceptions\ConvertError
     * @throws \go\Upload\Images\Exceptions\SaveError
     */
    public function convertFile($infile, $outfile)
    {
        $native = $this->createNative();
        if (!$native->loadFromFile($infile)) {
            throw new Exceptions\SourceFileError($infile);
        }
        if (!$this->convert($native)) {
            throw new Exceptions\ConvertError();
        }
        if (!$native->saveFile($outfile)) {
            throw new Exceptions\SaveError($outfile);
        }
    }

    /**
     * Constructor
     *
     * @param \go\Upload\Images\Convert\Native\Iface $native
     *        native adapter
     * @return bool
     *         success or fail
     */
    abstract protected function convert(Native\Iface $native);

    /**
     * Create native adapter
     *
     * @return \go\Upload\Images\Convert\Native\Iface
     */
    protected function createNative()
    {
        $classname = $this->nativeClassname;
        return $classname();
    }

    /**
     * @var \go\Upload\Images\Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $nativeClassname;
}
