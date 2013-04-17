<?php
/**
 * Exception: error format of file params
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files\Exceptions;

final class FileParams extends Logic
{
    /**
     * Constructor
     *
     * @param array $param
     */
    public function __construct($params)
    {
        $this->params = $params;
        if (\is_array($params)) {
            $params = ' ['.\implode(',', \array_keys($params)).']';
        } else {
            $params = '';
        }
        $message = 'Error format of file params'.$params;
        parent::__construct($message);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @var array
     */
    private $params;
}
