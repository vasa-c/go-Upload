<?php
/**
 * Exception: source file error (error image format, permission, file not found)
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

class SourceFileError extends Runtime
{
    /**
     * Constructor
     *
     * @param string $filename [optional]
     * @param string $error [optional]
     * @param \Exception $previous [optional]
     */
    public function __construct($filename = null, $error = null, \Exception $previous = null)
    {
        $this->filename = $filename;
        $this->error = $error;
        $message = 'Source file '.($filename ? '('.$filename.') ' : '').' error';
        if ((!$error) && ($previous)) {
            $error = $previous->getMessage();
        }
        if ($error) {
            $message .= ': '.$error;
        }
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    final public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    final public function getError()
    {
        return $this->error;
    }

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $error;
}
