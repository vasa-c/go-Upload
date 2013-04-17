<?php
/**
 * Exception: file has not been saved
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

final class NotSaved extends Logic
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('File has not been saved');
    }
}
