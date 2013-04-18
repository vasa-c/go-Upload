<?php
/**
 * Exception: $_FILES item is not found
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

class ItemNotFound extends Logic
{
    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $message = '$_FILES['.$name.'] is not found';
        parent::__construct($message);
    }
}
