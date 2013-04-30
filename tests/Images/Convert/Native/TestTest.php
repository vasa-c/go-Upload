<?php
/**
 * @package go\Upload\Images
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Images\Covert\Native;

use go\Upload\Images\Convert\Native\Test;

/**
 * @covers go\Upload\Images\Convert\Native\Test
 */
class TestTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadFromFile()
    {
        $t = new Test();
        $filename = __DIR__.'/test.txt';
        $this->assertTrue($t->loadFromFile($filename));
        $this->assertEquals('100:200:90', $t->getBlob());
        $expected = array(
            'loadFromFile '.$filename,
            'loadFromBlob',
        );
        $this->assertEquals($expected, $t->getLogs());

        $t2 = new Test();
        $filename2 = __DIR__.'/test2.txt';
        $this->assertFalse($t2->loadFromFile($filename2));
    }

    public function testConvert()
    {
        $t = new Test();

        $t->loadFromBlob('100:200:90');
        $this->assertEquals(100, $t->getWidth());
        $this->assertEquals(200, $t->getHeight());
        $this->assertEquals(90, $t->getCompressionQuality());
        $this->assertEquals('100:200:90', $t->getBlob());

        $t->resize(200, 300);
        $this->assertEquals(200, $t->getWidth());
        $this->assertEquals(300, $t->getHeight());

        $t->setCompressionQuality(80);
        $this->assertEquals(80, $t->getCompressionQuality());

        $t->crop(10, 10, 150, 200);
        $this->assertEquals(150, $t->getWidth());
        $this->assertEquals(200, $t->getHeight());

        $this->assertEquals('150:200:80', $t->getBlob());
        $t->destroy();
        $expected = array(
            'loadFromBlob',
            'resize 200,300',
            'quality 80',
            'crop 10,10,150,200',
            'destroy',
        );
        $this->assertEquals($expected, $t->getLogs());
    }
}
