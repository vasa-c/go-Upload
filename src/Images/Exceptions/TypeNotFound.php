<?php
/**
 * Exception: requested type is not found
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

class TypeNotFound extends Logic
{
    /**
     * Constructor
     *
     * @param string $type
     *        requested type name
     */
    public function __construct($type)
    {
        $this->type = $type;
        $message = 'Type "'.$type.'" is not found in upload storage';
        parent::__construct($message);
    }

    /**
     * Get requested type name
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * @var string
     */
    private $type;
}
