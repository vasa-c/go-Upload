<?php
/**
 * Exception: magic __set is forbidden
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

final class ReadOnly extends Logic
{
    /**
     * Constructor
     *
     * @param string $service
     * @param string $prop [optional]
     */
    public function __construct($service, $prop = null)
    {
        $message = $service.' is readonly';
        if ($prop) {
            $message .= ' ['.$service.'->'.$prop.'=value is forbidden]';
        }
        parent::__construct($message);
    }
}
