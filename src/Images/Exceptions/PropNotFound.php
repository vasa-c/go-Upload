<?php
/**
 * Exception: property is not found
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Exceptions;

final class PropNotFound extends Logic
{
    /**
     * Constructor
     *
     * @param string $property
     *        requested property
     * @param string $service [optional]
     *        container of properties
     */
    public function __construct($property, $service = null)
    {
        $this->property = $property;
        $this->servicee = $service;
        if ($service) {
            $message = $service.'->'.$property.' is not found';
        } else {
            $message = 'property "'.$property.'" is not found';
        }
        parent::__construct($message);
    }

    /**
     * Get requested property
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Get name of properties container
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Requested property
     *
     * @var string
     */
    private $property;

    /**
     * Container of properties
     *
     * @var string
     */
    private $service;
}
