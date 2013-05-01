<?php
/**
 * Interface of native convert implementation
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Convert\Native;

interface Iface
{
    /**
     * Load image from filename
     *
     * @param string $filename
     * @return bool
     */
    public function loadFromFile($filename);

    /**
     * Load image from binary string
     *
     * @param string $blob
     * @return bool
     */
    public function loadFromBlob($blob);

    /**
     * Destroy resource
     */
    public function destroy();

    /**
     * Save image to file
     *
     * @param string $filename
     * @return bool
     */
    public function saveFile($filename);

    /**
     * Get binary representation of image
     *
     * @return string
     */
    public function getBlob();

    /**
     * Get image width
     *
     * @return int
     */
    public function getWidth();

    /**
     * Get image height
     *
     * @return int
     */
    public function getHeight();

    /**
     * Get image quality
     *
     * @return int
     */
    public function getCompressionQuality();

    /**
     * Resize image
     *
     * @param int $width
     * @param int $height
     */
    public function resize($width, $height);

    /**
     * Crop image
     *
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     */
    public function crop($left, $top, $width, $height);

    /**
     * Set image quality
     *
     * @param int $quality
     */
    public function setCompressionQuality($quality);
}
