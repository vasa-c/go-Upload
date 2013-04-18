<?php
/**
 * Exception: error $_FILES format
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

class Format extends Logic
{
    /**
     * Constructor
     *
     * @param string $error [optional]
     */
    public function __construct($error = null)
    {
        $message = 'Error $_FILES format'.($error ? ': '.$error : '');
        parent::__construct($message);
    }
}
