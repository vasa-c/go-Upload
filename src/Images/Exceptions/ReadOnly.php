<?php
/**
 * Exception: __set() is forbidden
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

class ReadOnly extends Logic
{
    /**
     * Constructor
     *
     * @param string $service [optional]
     *        properties container
     * @param string $property [optional]
     *        property name
     */
    public function __construct($service = null, $property = null)
    {
        $this->service = $service;
        $this->property = $property;
        if ($service) {
            $message = $service.($property ? '->'.$property : '').' is read-only';
        } else {
            $message = 'Service is forbidden';
        }
        parent::__construct($message);
    }

    /**
     * Get properties container name
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get property
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Name of properties container
     *
     * @var string
     */
    private $service;

    /**
     * Name of property
     *
     * @var string
     */
    private $property;
}
