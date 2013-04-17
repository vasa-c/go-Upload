<?php
/**
 * Exception: file is already saved
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

final class AlreadySaved extends Logic
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('File is already saved');
    }
}
