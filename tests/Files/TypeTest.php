<?php
/**
 * Test of Type class
 *
 * @package go\Upload\Files
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Files;

use go\Upload\Files\Type;

/**
 * @covers \go\Upload\Files\Type
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \go\Upload\Files\Type::__construct
     * @covers \go\Upload\Files\Type::__get
     */
    public function testGet()
    {
        $type = new Type('image/gif');
        $this->assertEquals($type->mime, 'image/gif');
        $this->assertEquals($type->media, 'image');
        $this->assertEquals($type->subtype, 'gif');
    }

    /**
     * @covers \go\Upload\Files\Type::__get
     * @expectedException go\Upload\Files\Exceptions\PropNotFound
     */
    public function testErrorProp()
    {
        $type = new Type('image/gif');
        return $type->unknown;
    }

    /**
     * @covers \go\Upload\Files\Type::__set
     * @expectedException go\Upload\Files\Exceptions\ReadOnly
     */
    public function testReadOnly()
    {
        $type = new Type('image/gif');
        $type->media = 'qwerty';
    }
}
