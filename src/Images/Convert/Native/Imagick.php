<?php
/**
 * Wrapper on Imagick
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 * @uses    php_imagick (http://php.net/imagick)
 */

namespace go\Upload\Images\Convert\Native;

class Imagick implements IFace
{
    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @param string $filename
     * @return bool
     */
    public function loadFromFile($filename)
    {
        try {
            $this->imagick = new \Imagick($filename);
        } catch (\ImagickException $e) {
            return false;
        }
        return true;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @param string $blob
     * @return bool
     */
    public function loadFromBlob($blob)
    {
        try {
            $this->imagick = new \Imagick();
            $this->imagick->readImageBlob($blob);
        } catch (\ImagickException $e) {
            return false;
        }
        return true;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     */
    public function destroy()
    {
        $this->imagick->clear();
        $this->imagick->destroy();
        $this->imagick = null;
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @param string $filename
     * @return bool
     */
    public function saveFile($filename)
    {
        $this->imagick->writeImage($filename);
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @return string
     */
    public function getBlob()
    {
        return $this->imagick->getImageBlob();
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->imagick->getImageWidth();
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->imagick->getImageHeight();
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @return int
     */
    public function getCompressionQuality()
    {
        return $this->imagick->getImageCompressionQuality();
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @param int $width
     * @param int $height
     */
    public function resize($width, $height)
    {
        $this->imagick->resizeImage($width, $height, 0, 0);
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     */
    public function crop($left, $top, $width, $height)
    {
        $this->imagick->cropImage($width, $height, $left, $top);
    }

    /**
     * @override \go\Upload\Images\Convert\Native\IFace
     *
     * @param int $quality
     */
    public function setCompressionQuality($quality)
    {
        $this->imagick->setImageCompressionQuality($quality);
    }

    /**
     * @var \Imagick
     */
    private $imagick;
}
