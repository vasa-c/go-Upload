<?php
/**
 * Exception: params for create object are erroneous
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

class CreatorParams extends Logic
{

    public function __construct($error = null, $objectName = null)
    {
        $message = 'Error params for create ';
        $message .= $objectName ? $objectName : 'object';
        if ($error) {
            $message .= ': '.$error;
        }
        parent::__construct($message);
    }
}
