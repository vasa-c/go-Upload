<?php
/**
 * Wrapper on GD
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 * @uses    php_imagick (http://php.net/imagick)
 */

namespace go\Upload\Images\Convert\Native;

class GD implements Iface
{
    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param string $filename
     * @return bool
     */
    public function loadFromFile($filename)
    {
        return $this->loadFromBlob(\file_get_contents($filename));
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IfFace
     *
     * @param string $blob
     * @return bool
     */
    public function loadFromBlob($blob)
    {
        $this->image = @\imagecreatefromstring($blob);
        if (!$this->image) {
            return false;
        }
        $this->loadImageInfo($blob);
        return true;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     */
    public function destroy()
    {
        \imagedestroy($this->image);
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
        return $this->saveImage($filename, $format, $this->quality);
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param string $format [optional]
     * @return string
     */
    public function getBlob($format = null)
    {
        return $this->saveImage(null, $format, $this->quality);
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
        $old = $this->image;
        $this->image = \imagecreatetruecolor($width, $height);
        \imagecopyresized($this->image, $old, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
        \imagedestroy($old);
        $this->width = $width;
        $this->height = $height;
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
        $old = $this->image;
        $this->image = \imagecreatetruecolor($width, $height);
        \imagecopyresized($this->image, $old, 0, 0, $left, $top, $width, $height, $width, $height);
        \imagedestroy($old);
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\Iface
     *
     * @param int $quality
     */
    public function setCompressionQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * @param string $filename
     * @param string $format
     * @param int $quality
     * @return bool
     */
    private function saveImage($filename, $format, $quality)
    {
        if (!$format) {
            $format = 'jpg';
        }
        switch ($format) {
            case 'jpg':
                return @imagejpeg($this->image, $filename, $quality);
            case 'gif':
                return @imagegif($this->image, $filename);
            case 'png':
                return @imagepng($this->image, $filename, $quality);
            case 'wbmp':
                return @imagewbmp($this->image, $filename);
        }
        return false;
    }

    /**
     * @param string $blob [optional]
     */
    private function loadImageInfo($blob = null)
    {
        if (\is_null($blob)) {
            $blob = $this->getBlob();
        }
        $info = \getimagesizefromstring($blob);
        $this->width = $info[0];
        $this->height = $info[1];
    }

    /**
     * @var resource
     */
    private $image;

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
    private $quality = 100;
}
