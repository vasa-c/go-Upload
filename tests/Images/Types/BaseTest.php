<?php
/**
 * Test of type basic class
 *
 * @package go\Upload\Images
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Images\Types;

use go\Upload\Images\Types\Base;
use go\Upload\Images\Storage;

/**
 * @covers go\Upload\Images\Types\Base
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{

    private $config = array(

        'param1' => 1,
        'param2' => 2,

        'types' => array(
            'one' => array(
                'kind' => 'Test', // short classname
                'param1' => 3, // override param
            ),
            'two' => array(
                'kind' => '\go\Upload\Tests\Images\Types\mocks\TestType', // full classname
            ),
            'kindnotfound' => array(
                'param2' => 4,
            ),
            'kinderror' => array(
                'kind' => array(),
            ),
            'kindunknown' => array(
                'kind' => 'Unknown',
            ),
            'errortype' => false,
        ),
    );

    /**
     * @covers go\Upload\Images\Types\Base::create
     */
    public function testCreate()
    {
        $storage = new Storage($this->config);

        $one = Base::createTypeInstance($storage, 'one');
        $this->assertInstanceOf('go\Upload\Images\Types\Test', $one);
        $this->assertEquals('one', $one->getName());
        $this->assertEquals($storage, $one->getStorage());
        $config1 = $one->getConfig();
        $this->assertInstanceOf('go\Upload\Images\Config', $config1);
        $this->assertEquals(3, $config1->get('param1'));
        $this->assertEquals(2, $config1->get('param2'));

        $two = Base::createTypeInstance($storage, 'two');
        $this->assertInstanceOf('go\Upload\Tests\Images\Types\mocks\TestType', $two);
    }

    /**
     * @covers go\Upload\Images\Types\Base::create
     * @dataProvider providerCreateError
     * @expectedException go\Upload\Images\Exceptions\ConfigFormat
     */
    public function testCreateError($name)
    {
        $storage = new Storage($this->config);
        return Base::createTypeInstance($storage, $name);
    }
    public function providerCreateError()
    {
        return array(
            array('kindnotfound'),
            array('kinderror'),
            array('kindunknown'),
            array('errortype'),
            array('unknown'),
        );
    }
}
