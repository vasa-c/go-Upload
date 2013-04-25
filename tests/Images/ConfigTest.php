<?php
/**
 * Test of Config class
 *
 * @package go\Upload\Images
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c
 */

namespace go\Upload\Tests\Images;

use go\Upload\Images\Config;

/**
 * @covers go\Upload\Images\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $c1 = array(
        'one'   => 1,
        'two'   => 2,
        'three' => 3,
    );

    private $c2 = array(
        'two'   => array('a' => 1, 'b' => 2, 'c' => 3),
        'three' => true,
        'four'  => 4,
    );

    private $c3 = array(
        'three' => "string",
        'four'  => array('x' => 5, 'y' => 6, 'z' => 7),
        'six'   => 6,
    );

    /**
     * @covers go\Upload\Images\Config::parentGet
     */
    public function testParentGet()
    {
        $config1 = new Config($this->c1);
        $config2 = new Config($this->c2, $config1);
        $config3 = new Config($this->c3, $config2);

        $this->assertNull($config1->parentGet());
        $this->assertSame($config1, $config2->parentGet());
        $this->assertSame($config2, $config3->parentGet());
    }

    /**
     * @covers go\Upload\Images\Config::exists
     * @dataProvider providerExistsWithoutType
     * @param \go\Upload\Images\Config $config
     * @param string $key
     * @param bool $expected
     */
    public function testExistsWithoutType($config, $key, $exists)
    {
        if ($exists) {
            $this->assertTrue($config->exists($key));
        } else {
            $this->assertFalse($config->exists($key));
        }
    }
    public function providerExistsWithoutType()
    {
        $config1 = new Config($this->c1);
        $config2 = new Config($this->c2, $config1);
        $config3 = new Config($this->c3, $config2);
        return array(
            array($config1, 'one', true),
            array($config2, 'one', true),
            array($config3, 'one', true),
            array($config1, 'four', false),
            array($config2, 'four', true),
            array($config3, 'four', true),
            array($config1, 'six', false),
            array($config2, 'six', false),
            array($config3, 'six', true),
            array($config1, 'unk', false),
            array($config1, 'unk', false),
            array($config1, 'unk', false),
        );
    }

    /**
     * @covers go\Upload\Images\Config::exists
     * @dataProvider providerExistsWithType
     * @param \go\Upload\Images\Config $config
     * @param string $key
     * @param string $type
     * @param bool $expected
     */
    public function testExistsWithType($config, $key, $type, $exists)
    {
        if ($exists) {
            $this->assertTrue($config->exists($key, $type));
        } else {
            $this->assertFalse($config->exists($key, $type));
        }
    }
    public function providerExistsWithType()
    {
        $config1 = new Config($this->c1);
        $config2 = new Config($this->c2, $config1);
        $config3 = new Config($this->c3, $config2);
        return array(
            array($config1, 'one', 'scalar', true),
            array($config1, 'one', 'integer', true),
            array($config1, 'one', 'array', false),
            array($config1, 'two', 'scalar', true),
            array($config2, 'two', 'scalar', false),
            array($config2, 'two', 'array', true),
            array($config2, 'three', 'boolean', true),
            array($config3, 'three', 'boolean', false),
            array($config3, 'three', 'string', true),
            array($config3, 'unk', 'string', false),
        );
    }

    /**
     * @covers go\Upload\Images\Config::hasOwnProperty
     */
    public function testExistsHasOwnProperty()
    {
        $config1 = new Config($this->c1);
        $config2 = new Config($this->c2, $config1);

        $this->assertTrue($config1->hasOwnProperty('one'));
        $this->assertFalse($config2->hasOwnProperty('one'));
        $this->assertTrue($config1->hasOwnProperty('two'));
        $this->assertTrue($config2->hasOwnProperty('two'));
        $this->assertFalse($config1->hasOwnProperty('four'));
        $this->assertTrue($config2->hasOwnProperty('four'));
        $this->assertFalse($config1->hasOwnProperty('unk'));
        $this->assertFalse($config2->hasOwnProperty('unk'));
    }

    /**
     * @covers go\Upload\Images\Config::hasOwnProperty
     * @dataProvider providerGetWithoutType
     * @param \go\Upload\Images\Config $config
     * @param string $key
     * @param mixed $expected
     * @param bool $throws [optional]
     */
    public function testGetWithoutType($config, $key, $expected, $throws = false)
    {
        if ($throws) {
            $this->setExpectedException('go\Upload\Images\Exceptions\PropNotFound');
            return $config->get($key);
        }
        $this->assertEquals($expected, $config->get($key));
    }
    public function providerGetWithoutType()
    {
        $config1 = new Config($this->c1);
        $config2 = new Config($this->c2, $config1);
        $config3 = new Config($this->c3, $config2);

        return array(
            array($config1, 'one', 1),
            array($config1, 'two', 2),
            array($config1, 'three', 3),
            array($config1, 'four', null, true),

            array($config2, 'one', 1),
            array($config2, 'two', array('a' => 1, 'b' => 2, 'c' => 3)),
            array($config2, 'three', true),
            array($config2, 'four', 4),
            array($config2, 'six', null, true),

            array($config3, 'one', 1),
            array($config3, 'two', array('a' => 1, 'b' => 2, 'c' => 3)),
            array($config3, 'three', 'string'),
            array($config3, 'four', array('x' => 5, 'y' => 6, 'z' => 7)),
            array($config3, 'six', 6),

            array($config1, 'unk', null, true),
            array($config2, 'unk', null, true),
            array($config3, 'unk', null, true),
        );
    }

    /**
     * @covers go\Upload\Images\Config::child
     */
    public function testChild()
    {
        $config1 = new Config($this->c1);
        $config2 = new Config($this->c2, $config1);

        $child = $config2->child('two');
        $this->assertSame($config2, $child->parentGet());

        $this->assertEquals(2, $child->get('b'));
        $this->assertEquals(1, $child->get('one'));
        $this->assertEquals(true, $child->get('three'));

        $this->setExpectedException('go\Upload\Images\Exceptions\PropNotFound');
        return $child->child('b');
    }
}
