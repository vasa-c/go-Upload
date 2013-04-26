<?php
/**
 * Test of ObjectCreator class
 *
 * @package go\Upload\Images
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Images\Helpers;

use go\Upload\Images\Helpers\ObjectCreator;
use go\Upload\Tests\Images\Helpers\mocks\UserObject;
use go\Upload\Images\Config;

/**
 * @covers go\Upload\Images\Helpers\ObjectCreator::create
 */
class ObjectCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $instance1 = new UserObject();
        $instance2 = new UserObject();
        $this->assertSame($instance1, ObjectCreator::create($instance1));
        $this->assertSame($instance1, ObjectCreator::create($instance1, array('x' => 2)));
        $this->assertSame($instance1, ObjectCreator::create($instance1), null, 'testns');
        $this->assertSame($instance2, ObjectCreator::create($instance2));
    }

    public function testClassname()
    {
        $namespace = 'go\Upload\Tests\Images\Helpers\mocks';
        $shortname = 'UserObject';
        $fullname  = $namespace.'\\'.$shortname;
        $absname   = '\\'.$fullname;
        $this->assertInstanceOf($fullname, ObjectCreator::create($fullname));
        $this->assertInstanceOf($fullname, ObjectCreator::create($absname));
        $this->assertInstanceOf($fullname, ObjectCreator::create($shortname, null, $namespace));
        $this->assertInstanceOf($fullname, ObjectCreator::create($absname, null, 'One\Two\Three'));

        $instance = ObjectCreator::create($fullname, array('x' => 1));
        $config = $instance->getConfig();
        $this->assertInstanceOf('go\Upload\Images\Config', $config);
        $this->assertEquals(array('x' => 1), $config->getFullConfig());

        $this->setExpectedException('go\Upload\Images\Exceptions\CreatorParams');
        return ObjectCreator::create('Unknown', null, $namespace);
    }

    public function testClassnameAndConfigArray()
    {
        $aconfig = array(
            'a' => 1,
            'b' => 2,
        );
        $instance = ObjectCreator::create('UserObject', $aconfig, 'go\Upload\Tests\Images\Helpers\mocks');
        $this->assertInstanceOf('go\Upload\Tests\Images\Helpers\mocks\UserObject', $instance);
        $config = $instance->getConfig();
        $this->assertInstanceOf('go\Upload\Images\Config', $config);
        $this->assertEquals($aconfig, $config->getFullConfig());
    }

    public function testClassnameAndConfigConfig()
    {
        $aconfig = array(
            'a' => 1,
            'b' => 2,
        );
        $config = new Config($aconfig);
        $instance = ObjectCreator::create('UserObject', $config, 'go\Upload\Tests\Images\Helpers\mocks');
        $this->assertInstanceOf('go\Upload\Tests\Images\Helpers\mocks\UserObject', $instance);
        $this->assertEquals($aconfig, $instance->getConfig()->getFullConfig());
    }

    public function testClassnameInsideParams()
    {
        $params = array(
            'classname' => 'UserObject',
            'b' => 3,
            'c' => 4,
        );
        $aconfig = array(
            'a' => 1,
            'b' => 2,
        );
        $instance = ObjectCreator::create($params, $aconfig, 'go\Upload\Tests\Images\Helpers\mocks');
        $this->assertInstanceOf('go\Upload\Tests\Images\Helpers\mocks\UserObject', $instance);
        $expectedConfig = array(
            'classname' => 'UserObject',
            'a' => 1,
            'b' => 3,
            'c' => 4,
        );
        $this->assertEquals($expectedConfig, $instance->getConfig()->getFullConfig());

        $params = array(
            'classname' => 'Unknown',
            'b' => 3,
            'c' => 4,
        );
        $this->setExpectedException('go\Upload\Images\Exceptions\CreatorParams');
        return ObjectCreator::create($params, $aconfig, 'go\Upload\Tests\Images\Helpers\mocks');
    }

    public function testCreatorInsideParams()
    {
        $creator = (function ($config, $namespace) {
            if ($namespace == 'empty') {
                return null;
            }
            $nconfig = new Config(array('ns' => $namespace), $config);
            return new mocks\UserObject($nconfig);
        });
        $params = array(
            'creator' => $creator,
            'b' => 3,
        );
        $aconfig = array(
            'a' => 1,
            'b' => 2,
        );

        $instance = ObjectCreator::create($params, $aconfig, 'One\Two');
        $this->assertInstanceOf('go\Upload\Tests\Images\Helpers\mocks\UserObject', $instance);
        $config = $instance->getConfig();
        $this->assertEquals(1, $config->get('a'));
        $this->assertEquals(3, $config->get('b'));
        $this->assertEquals('One\Two', $config->get('ns'));

        $this->setExpectedException('go\Upload\Images\Exceptions\CreatorParams');
        return ObjectCreator::create($params, $aconfig, 'empty');
    }

    public function testCreatorAsParams()
    {
        $creator = (function () {
            $a = array('a' => 1, 'b' => 2);
            return (object)$a;
        });
        $instance = ObjectCreator::create($creator);
        $this->assertInternalType('object', $instance);
        $this->assertEquals(2, $instance->b);
    }

    /**
     * @dataProvider providerErrorParams
     * @expectedException go\Upload\Images\Exceptions\CreatorParams
     */
    public function testErrorParams($params)
    {
        return ObjectCreator::create($params);
    }
    public function providerErrorParams()
    {
        return array(
            array(
                array(
                    'notclassname' => 'qwe',
                    'notcreator'   => 'rty',
                ),
            ),
            array(
                array(
                    'creator' => 'notfunction',
                ),
            ),
        );
    }
}
