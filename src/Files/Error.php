<?php
/**
 * Description of error uploading
 *
 * @package go\Upload\Files
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Files;

/**
 * @property-read int $code
 *                numeric code of error
 * @property-read string $name
 *                string name of error
 * @property-read string $message
 *                description of error
 */
final class Error
{
    /**
     * Constructor
     *
     * @see http://www.php.net/manual/en/features.file-upload.errors.php
     *
     * @param int $code
     *        UPLOAD_ERR-constant
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \go\Upload\Files\Exceptions\PropNotFound
     */
    public function __get($key)
    {
        switch ($key) {
            case 'code':
                return $this->code;
            case 'name':
                return self::code2name($this->code);
            case 'message':
                return self::name2message(self::code2name($this->code));
            default:
                throw new Exceptions\PropNotFound('Error', $key);
        }
    }

    /**
     * Set is forbidden
     *
     * @param string $key
     * @param mixed $value
     * @thorws \go\Upload\Files\Exceptions\ReadOnly
     */
    public function __set($key, $value)
    {
        throw new Exceptions\ReadOnly('Error', $key);
    }

    /**
     * Whether error occurred
     *
     * @return bool
     */
    public function isError()
    {
        return ($this->code != \UPLOAD_ERR_OK);
    }

    /**
     * Get list names by codes
     *
     * @return array
     */
    public static function getNamesList()
    {
        if (!self::$names) {
            self::createNames();
        }
        return self::$names;
    }

    /**
     * Get list messages by names
     *
     * @return array
     */
    public static function getMessagesList()
    {
        return self::$messages;
    }

    /**
     * Get name by code
     *
     * @param int $code
     * @return string
     */
    public static function code2name($code)
    {
        $names = self::getNamesList();
        if (!isset($names[$code])) {
            return 'unknown';
        }
        return $names[$code];
    }

    /**
     * Get message by name
     *
     * @param string $name
     * @return string
     */
    public static function name2message($name)
    {
        return self::$messages[$name];
    }

    /**
     * Code of error
     *
     * @var int
     */
    private $code;

    /**
     * Create list names by codes
     */
    private static function createNames()
    {
        $names = array();
        $names[\UPLOAD_ERR_OK] = 'ok';
        $names[\UPLOAD_ERR_INI_SIZE] = 'ini_size';
        $names[\UPLOAD_ERR_FORM_SIZE] = 'form_size';
        $names[\UPLOAD_ERR_PARTIAL] = 'partial';
        $names[\UPLOAD_ERR_NO_FILE] = 'no_file';
        $names[\UPLOAD_ERR_NO_TMP_DIR] = 'no_tmp_dir';
        $names[\UPLOAD_ERR_CANT_WRITE] = 'cant_write';
        $names[\UPLOAD_ERR_EXTENSION] = 'extension';
        self::$names = $names;
    }

    /**
     * List names by codes
     *
     * @var array
     */
    private static $names;

    /**
     * List messages by names
     *
     * @var array
     */
    private static $messages = array(
        'ok' => 'There is no error, the file uploaded with success. ',
        'ini_size' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        'form_size' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        'partial' => 'The uploaded file was only partially uploaded.',
        'no_file' => 'No file was uploaded.',
        'no_tmp_dir' => 'Missing a temporary folder.',
        'cant_write' => 'Failed to write file to disk.',
        'extension' => 'A PHP extension stopped the file upload.',
        'unknown' => 'Unknown error.',
    );
}
