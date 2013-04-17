<?php
/**
 * Mime-type of uploaded file
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files;

/**
 * @property-read string $mime
 *                full mime-type (image/gif for example)
 * @property-read string $media
 *                media part (image)
 * @property-read string $subtype
 *                subtype (gif)
 */
final class Type
{
    /**
     * Constructor
     *
     * @param string $mime
     */
    public function __construct($mime)
    {
        $this->mime = $mime;
        $mime = \explode('/', $mime, 2);
        $this->media = $mime[0];
        $this->subtype = isset($mime[1]) ? $mime[1] : '';
    }

    /**
     * Magic get
     *
     * @param string $key
     * @return string
     * @throws \go\Upload\Files\Exceptions\PropNotFound
     */
    public function __get($key)
    {
        switch ($key) {
            case 'mime':
                return $this->mime;
            case 'media':
                return $this->media;
            case 'subtype':
                return $this->subtype;
            default:
                throw new Exceptions\PropNotFound('Type', $key);
        }
    }

    /**
     * Set is forbidden
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\Upload\Files\Exceptions\ReadOnly
     */
    public function __set($key, $value)
    {
        throw new Exceptions\ReadOnly('Type', $key);
    }

    /**
     * Full mime-type
     *
     * @var string
     */
    private $mime;

    /**
     * Media type
     *
     * @var string
     */
    private $media;

    /**
     * Subtype of mime-type
     *
     * @var string
     */
    private $subtype;
}
