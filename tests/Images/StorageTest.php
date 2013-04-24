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
        'types' => array(
            'one' => array(
                'kind' => 'Test',
            ),
            'two' => array(
                'kind' => 'Test',
            ),
        ),
    );


    /**
     * @covers go\Upload\Images\Storate::__construct
     * @covers go\Upload\Images\Storate::getConfig
     */
    public function testGetConfig()
    {
        $storage = new Storage($this->config);
        $this->assertEquals($this->config, $storage->getConfig());
    }
}
