<?php
/**
 * Test of Item class
 *
 * @package go\Upload\Files
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Files\Tests;

use go\Upload\Files\Item;

/**
 * @covers \go\Upload\Files\Item
 */
class ItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \go\Upload\Files\Item::__construct
     * @covers \go\Upload\Files\Item::getParams
     */
    public function testGetParams()
    {
        $params = array(
            'name' => '1.txt',
            'type' => 'plain/text',
            'size' => 10,
            'tmp_name' => '/tmp/tmp.txt',
            'error' => 0,
        );
        $item = new Item($params);
        $this->assertEquals($params, $item->getParams());
    }

    /**
     * @covers \go\Upload\Files\Item::__construct
     * @dataProvider providerErrorFileParams
     * @expectedException \go\Upload\Files\Exceptions\FileParams
     */
    public function testErrorFileParams($params)
    {
        return new Item($params);
    }

    public function providerErrorFileParams()
    {
        return array(
            array(array( // empty array
            )),
            array(array( // required param not found
                'name' => '1.txt',
                'type' => 'plain/text',
            )),
            array(array( // unknown param
                'name' => '1.txt',
                'type' => 'plain/text',
                'size' => '10',
                'unknown' => 'tst',
                'tmp_name' => '/tmp/tmp.txt',
                'error' => '0',
            )),
            array(array( // not scalar param
                'name' => '1.txt',
                'type' => array(1),
                'size' => 10,
                'tmp_name' => '/tmp/tmp.txt',
                'error' => 0,
            )),
            array(array( // not numeric size
                'name' => '1.txt',
                'type' => 'plain/text',
                'size' => '10s5',
                'tmp_name' => '/tmp/tmp.txt',
                'error' => 1,
            )),
            array(array( // not numeric error
                'name' => '1.txt',
                'type' => 'plain/text',
                'size' => '10',
                'tmp_name' => '/tmp/tmp.txt',
                'error' => '3x',
            )),
        );
    }
}
