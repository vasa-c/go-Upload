<?php
/**
 * Uploaded file
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files;

/**
 * @property-read string $basename
 *                original file name
 * @property-read \go\Upload\Files\Type $type
 *                mime-type of file
 * @property-read string $tempFilename
 *                path to temporary file
 * @property-read int $size
 *                size of file
 * @property-read \go\Upload\Files\Error $error
 *                upload error
 */
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
        $this->fail = ($params['error'] != 0);
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
     * Magic get
     *
     * @param string $key
     * @return mixed
     * @throws \go\Upload\Files\Exceptions\FailUpload
     * @throws \go\Upload\Files\Exceptions\PropNotFound
     */
    public function __get($key)
    {
        if ($key == 'error') {
            return $this->getErrorObject();
        }
        $this->mustUploaded();
        switch ($key) {
            case 'basename':
                return $this->params['name'];
            case 'type':
                return $this->getTypeObject();
            case 'tempFilename':
                return $this->params['tmp_name'];
            case 'size':
                return $this->params['size'];
            default:
                throw new Exceptions\PropNotFound('Item', $key);
        }
    }

    /**
     * Set is forbidden
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\Upload\Files\Exceptions\ReadOnly
     */
    public function __set($key, $value)
    {
        throw new Exceptions\ReadOnly('Item', $key);
    }

    /**
     * Success or fail upload
     *
     * @return bool
     */
    public function isUploaded()
    {
        return (!$this->fail);
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
     * Get mime-type object
     *
     * @return \go\Upload\Files\Type
     */
    private function getTypeObject()
    {
        if (!$this->typeObject) {
            $this->typeObject = new Type($this->params['type']);
        }
        return $this->typeObject;
    }

    /**
     * Get error object
     *
     * @return \go\Upload\Files\Error
     */
    private function getErrorObject()
    {
        if (!$this->errorObject) {
            $this->errorObject = new Error($this->params['error']);
        }
        return $this->errorObject;
    }

    /**
     * File must be uploaded
     *
     * @throws \go\Upload\Files\Exceptions\FailUpload
     */
    private function mustUploaded()
    {
        if ($this->fail) {
            throw new Exceptions\FailUpload();
        }
    }

    /**
     * Item params
     *
     * @var array
     */
    private $params;

    /**
     * Mime-type object
     *
     * @var \go\Upload\Files\Type
     */
    private $typeObject;

    /**
     * Object of upload error
     *
     * @var \go\Upload\Files\Error
     */
    private $errorObject;

    /**
     * Item is not uploaded
     *
     * @var bool
     */
    private $fail;
}
