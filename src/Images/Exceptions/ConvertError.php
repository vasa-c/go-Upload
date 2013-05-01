<?php
/**
 * Exception: convert error
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Images\Exceptions;

class ConvertError extends Runtime
{
    /**
     * Constructor
     *
     * @param string $error [optional]
     */
    public function __construct($error = null)
    {
        $this->error = $error;
        $message = 'Convert error: '.$error;
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @var string
     */
    private $error;
}
