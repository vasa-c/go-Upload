<?php
/**
 * Exception: error config format
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

class ConfigFormat extends Logic
{
    /**
     * Constructor
     *
     * @param string $error [optional]
     *        error description
     */
    public function __construct($error = null)
    {
        $message = 'Error config of upload storage';
        if ($error) {
            $message .= ': '.$error;
        }
        parent::__construct($message);
    }
}
