<?php
/**
 * Uploaded file
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Files;

final class Item
{
    /**
     * Constructor
     *
     * @param array $params
     *        params of uploaded file ($_FILES item)
     * @throws \go\Upload\Files\Exceptions\FileParams
     *         error format of params
     */
    public function __construct(array $params)
    {
        if (!$this->isValidParams($params)) {
            throw new Exceptions\FileParams($params);
        }
        $this->params = $params;
    }

    /**
     * Get item params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Check params format
     *
     * @param array $params
     * @return bool
     */
    private function isValidParams(array $params)
    {
        if (\count($params) != 5) {
            return false;
        }
        $fields = array('name', 'type', 'tmp_name', 'size', 'error');
        foreach ($fields as $field) {
            if (!\array_key_exists($field, $params)) {
                return false;
            }
            if (!\is_scalar($params[$field])) {
                return false;
            }
        }
        if ((!\is_int($params['size'])) || (!\is_int($params['error']))) {
            return false;
        }
        return true;
    }

    /**
     * Item params
     *
     * @var array
     */
    private $params;
}
