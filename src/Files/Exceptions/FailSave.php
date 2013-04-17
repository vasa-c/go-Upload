<?php
/**
 * Exception: file save failure
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

final class FailSave extends Logic
{
    /**
     * Constructor
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        parent::__construct('File "'.$filename.'" save failure');
    }
}
