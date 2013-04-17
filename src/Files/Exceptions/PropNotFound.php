<?php
/**
 * Exception: access to unknown property
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

final class PropNotFound extends Logic
{
    /**
     * Constructor
     *
     * @param string $service
     * @param string $prop
     */
    public function __construct($service, $prop)
    {
        $message = 'Property ['.$service.'->'.$prop.'] is not found';
        parent::__construct($message);
    }
}
