<?php
/**
 * For test of Item::save()
 *
 * @package go\Upload\Files
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Files\Tests\wrappers;

class ItemMove extends \go\Upload\Files\Item
{
    /**
     * @override \go\Upload\Files\Item
     */
    protected function nativeMoveFile($source, $destination)
    {
        if ($destination == '/error/') {
            return false;
        }
        $this->logs[] = $source.' to '.$destination;
        return true;
    }

    public $logs = array();
}
