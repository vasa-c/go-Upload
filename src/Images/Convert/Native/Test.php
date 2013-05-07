<?php
/**
 * Test convert implementation
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Convert\Native;

class Test implements Iface
{
    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param string $filename
     * @return bool
     */
    public function loadFromFile($filename)
    {
        if (!\file_exists($filename)) {
            return false;
        }
        $this->log('loadFromFile '.$filename);
        return $this->loadFromBlob(\file_get_contents($filename));
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param string $blob
     * @return bool
     */
    public function loadFromBlob($blob)
    {
        $blob = \explode(':', $blob, 4);
        if (\count($blob) < 2) {
            return false;
        }
        $this->width = (int)$blob[0];
        $this->height = (int)$blob[1];
        $this->quality = isset($blob[2]) ? (int)$blob[2] : 100;
        $this->log('loadFromBlob');
        return true;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     */
    public function destroy()
    {
        $this->width = 0;
        $this->height = 0;
        $this->quality = 0;
        $this->log('destroy');
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param string $filename
     * @param string $format [optional]
     * @return bool
     */
    public function saveFile($filename, $format = null)
    {
        $this->log('saveFile '.$filename);
        return true;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param string $format
     * @return string
     */
    public function getBlob($format = null)
    {
        return $this->width.':'.$this->height.':'.$this->quality;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @return int
     */
    public function getCompressionQuality()
    {
        return $this->quality;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param int $width
     * @param int $height
     */
    public function resize($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->log('resize '.$width.','.$height);
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     */
    public function crop($left, $top, $width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->log('crop '.$left.','.$top.','.$width.','.$height);
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param int $quality
     */
    public function setCompressionQuality($quality)
    {
        $this->quality = $quality;
        $this->log('quality '.$quality);
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param string $log
     */
    private function log($log)
    {
        $this->logs[] = $log;
    }

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $quality;

    /**
     * @var array
     */
    private $logs = array();
}
