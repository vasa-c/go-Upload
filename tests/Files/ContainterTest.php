<?php
/**
 * Test of Container class
 *
 * @package go\Upload\Files
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Files;

use go\Upload\Files\Container;

/**
 * @covers \go\Upload\Files\Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $files = array(
        'single' => array(
            'name' => 'singl.txt',
            'type' => 'text/plain',
            'size' => 10,
            'tmp_name' => '/tmp/t',
            'error' => 0,
        ),
        'plain' => array(
            'name' => array('s', ''),
            'type' => array('text/plain', ''),
            'size' => array(10, 0),
            'tmp_name' => array('/tmp/t', ''),
            'error' => array(0, 4),
        ),
        'tree' => array(
            'name' => array(
                'x' => array('a' => 's', 'b' => ''),
            ),
            'type' => array(
                'x' => array('a' => 'text/plain', 'b' => ''),
            ),
            'size' => array(
                'x' => array('a' => 10, 'b' => 0),
            ),
            'tmp_name' => array(
                'x' => array('a' => '/tmp/t', 'b' => ''),
            ),
            'error' => array(
                'x' => array('a' => 0, 'b' => 4),
            ),
        ),
    );

    /**
     * @covers \go\Upload\Files\Contaner::existsSingle
     * @covers \go\Upload\Files\Contaner::existsPlainList
     * @covers \go\Upload\Files\Contaner::existsTreeList
     */
    public function testExists()
    {
        $container = new Container($this->files);

        $this->assertTrue($container->existsSingle('single'));
        $this->assertFalse($container->existsSingle('plain'));
        $this->assertFalse($container->existsSingle('tree'));
        $this->assertFalse($container->existsSingle('unknown'));

        $this->assertFalse($container->existsPlainList('single'));
        $this->assertTrue($container->existsPlainList('plain'));
        $this->assertFalse($container->existsPlainList('tree'));
        $this->assertFalse($container->existsPlainList('unknown'));

        $this->assertFalse($container->existsTreeList('single'));
        $this->assertTrue($container->existsTreeList('plain'));
        $this->assertTrue($container->existsTreeList('tree'));
        $this->assertFalse($container->existsTreeList('unknown'));
    }

    /**
     * @covers \go\Upload\Files\Contaner::getSingle
     */
    public function testGetSingle()
    {
        $container = new Container($this->files);
        $this->assertEquals('singl.txt', $container->getSingle('single')->basename);
        $this->setExpectedException('go\Upload\Files\Exceptions\ItemNotFound');
        return $container->getSingle('plain');
    }

    /**
     * @covers \go\Upload\Files\Contaner::getPlainList
     */
    public function testGetPlainList()
    {
        $container = new Container($this->files);
        $list = $container->getPlainList('plain');
        $this->assertCount(2, $list);
        $item1 = $list[0];
        $item2 = $list[1];
        $this->assertEquals('s', $item1->basename);
        $this->assertFalse($item2->isUploaded());

        $expected = array($item1);
        $this->assertEquals($expected, $container->getPlainList('plain', true));

        $this->setExpectedException('go\Upload\Files\Exceptions\ItemNotFound');
        return $container->getPlainList('tree');
    }

    /**
     * @covers \go\Upload\Files\Contaner::getTreeList
     */
    public function testGetTreeList()
    {
        $container = new Container($this->files);
        $list = $container->getTreeList('tree');
        $item1 = $list['x']['a'];
        $item2 = $list['x']['b'];

        $this->assertEquals('s', $item1->basename);
        $this->assertFalse($item2->isUploaded());

        $expected = array('x' => array('a' => $item1));
        $this->assertEquals($expected, $container->getTreeList('tree', true));

        $this->setExpectedException('go\Upload\Files\Exceptions\ItemNotFound');
        return $container->getPlainList('single');
    }

    /**
     * @covers \go\Upload\Files\Contaner::__construct
     * @expectedException \go\Upload\Files\Exceptions\Format
     */
    public function testErrorFormat()
    {
        $files = array(
            'f' => array(
                'name' => 'x',
            ),
        );
        return (new Container($files));
    }
}
