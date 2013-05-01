<?php
/**
 * Test of Storage class
 *
 * @package go\Upload\Images
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Images;

use go\Upload\Images\Storage;
use go\Upload\Images\Config;

/**
 * @covers go\Upload\Images\Storage
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Config for tests
     *
     * @var array
     */
    private $config = array(
        'param' => 3,
        'param2' => 4,
        'types' => array(
            'one' => array(
                'kind' => 'Test',
                'param' => 'x',
            ),
            'two' => array(
                'kind' => 'Test',
            ),
        ),
    );


    /**
     * @covers go\Upload\Images\Storage::__construct
     * @covers go\Upload\Images\Storage::getConfig
     */
    public function testGetConfig()
    {
        $config1 = $this->config;
        $storage1 = new Storage($config1);
        $this->assertInstanceOf('go\Upload\Images\Config', $storage1->getConfig());
        $expected = \array_merge(Storage::getRootConfig()->getFullConfig(), $config1);
        $this->assertEquals($expected, $storage1->getConfig()->getFullConfig());
        $config2 = new Config($this->config);
        $storage2 = new Storage($config2);
        $this->assertInstanceOf('go\Upload\Images\Config', $storage2->getConfig());
        $this->assertEquals($this->config, $storage2->getConfig()->getFullConfig());
    }

    /**
     * @covers go\Upload\Images\Storage::getType
     */
    public function testGetType()
    {
        $storage = new Storage($this->config);

        $one = $storage->getType('one');
        $this->assertInstanceOf('go\Upload\Images\Types\Base', $one);
        $this->assertEquals($storage, $one->getStorage());
        $this->assertEquals('one', $one->getName());
        $this->assertEquals($one, $storage->getType('one')); // cache
        $fullconfig = $one->getConfig();
        $this->assertEquals('x', $fullconfig->get('param'));
        $this->assertEquals(4, $fullconfig->get('param2'));

        $this->assertEquals('two', $storage->getType('two')->getName());

        $this->setExpectedException('go\Upload\Images\Exceptions\PropNotFound');
        return $storage->getType('unknown');
    }

    /**
     * @covers go\Upload\Images\Storage::__construct
     * @expectedException go\Upload\Images\Exceptions\ConfigFormat
     * @dataProvider providerErrorConstructor
     */
    public function testErrorConstructor($config)
    {
        return new Storage($config);
    }
    public function providerErrorConstructor()
    {
        return array(
            array(
                array(),
            ),
            array(
                array(
                    'types' => 'types',
                ),
            ),
        );
    }
}
