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
 * @property-read string $finalFilename
 *                path to final file
 */
class Item
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
        if (!FilesArray::isValidItem($params)) {
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
            case 'finalFilename':
                if (!$this->saved) {
                    throw new Exceptions\NotSaved();
                }
                return $this->finalFilename;
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
     * @return bool
     */
    public function isUploaded()
    {
        return (!$this->fail);
    }

    /**
     * @return boolead
     */
    public function isSaved()
    {
        return $this->saved;
    }

    /**
     * Save file
     *
     * @param string $filename
     *        final file name
     * @param bool $repeat [optional]
     *        allow re-save
     * @throws \go\Upload\Files\Exceptions\FailUpload
     * @throws \go\Upload\Files\Exceptions\AlreadySaved
     * @throws \go\Upload\Files\Exceptions\FailSave
     */
    public function save($filename, $repeat = false)
    {
        $this->mustUploaded();
        if ($this->saved && (!$repeat)) {
            throw new Exceptions\AlreadySaved();
        }
        if (!$this->nativeMoveFile($this->params['tmp_name'], $filename)) {
            throw new Exceptions\FailSave($filename);
        }
        $this->saved = true;
        $this->finalFilename = $filename;
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
     * Move uploaded file
     *
     * @param string $destination
     * @return bool
     */
    protected function nativeMoveFile($source, $destination)
    {
        // @ warning off - throw exception FailSave
        return @\move_uploaded_file($source, $destination);
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

    /**
     * File is saved
     *
     * @var bool
     */
    private $saved = false;

    /**
     * Name of saved file
     *
     * @var string
     */
    private $finalFilename;
}
