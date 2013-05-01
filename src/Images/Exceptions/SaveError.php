<?php
/**
 * Exception: error saving file (permission, path ...)
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

class SaveError extends Runtime
{
    /**
     * Constructor
     *
     * @param string $filename
     * @param \Exception $previous [optional]
     */
    public function __construct($filename, \Exception $previous = null)
    {
        $message = 'Error saving file "'.$filename.'"';
        if ($previous) {
            $message .= ': '.$previous->getMessage();
        }
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @var string
     */
    private $filename;
}
