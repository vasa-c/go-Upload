<?php
/**
 * Test of FilesArray class
 *
 * @package go\Upload\Files
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Tests\Files;

use go\Upload\Files\FilesArray;

/**
 * @covers \go\Upload\Files\FilesArray
 */
class FilesArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \go\Upload\Files\FilesArray::normalize
     * @dataProvider providerNormalize
     * @param array $files
     * @param array $expected
     *        if null - exception
     */
    public function testNormalize($files, $expected)
    {
        if (\is_null($expected)) {
            $this->setExpectedException('\go\Upload\Files\Exceptions\Format');
        }
        $result = FilesArray::normalize($files);
        $this->assertEquals($expected, $result);
    }

    public function providerNormalize()
    {
        $f1 = array(
            'name' => 'one.txt',
            'type' => 'text/plain',
            'size' => 100,
            'tmp_name' => '/tmp/qwe',
            'error' => 0,
        );
        $f2 = array(
            'name' => 'two.gif',
            'type' => 'image/gif',
            'size' => 23544,
            'tmp_name' => '/tmp/rty',
            'error' => 0,
        );
        $f3 = array(
            'name' => '',
            'type' => '',
            'size' => 0,
            'tmp_name' => '',
            'error' => \UPLOAD_ERR_NO_FILE,
        );

        $sets = array();

        $sets[] = array( // empty = empty
            array(),
            array(),
        );

        $sets[] = array( // plain = plain
            array(
                'f1' => $f1,
                'f2' => $f2,
                'f3' => $f3,
            ),
            array(
                'f1' => $f1,
                'f2' => $f2,
                'f3' => $f3,
            ),
        );

        $sets[] = array( // autoarray
            array(
                'farr' => array(
                    'name' => array(
                        0 => $f1['name'],
                        1 => $f2['name'],
                        2 => $f3['name'],
                    ),
                    'type' => array(
                        0 => $f1['type'],
                        1 => $f2['type'],
                        2 => $f3['type'],
                    ),
                    'tmp_name' => array(
                        0 => $f1['tmp_name'],
                        1 => $f2['tmp_name'],
                        2 => $f3['tmp_name'],
                    ),
                    'error' => array(
                        0 => $f1['error'],
                        1 => $f2['error'],
                        2 => $f3['error'],
                    ),
                    'size' => array(
                        0 => $f1['size'],
                        1 => $f2['size'],
                        2 => $f3['size'],
                    ),
                ),
            ),
            array(
                'farr' => array(
                    0 => $f1,
                    1 => $f2,
                    2 => $f3,
                ),
            ),
        );

        $sets[] = array( // tree-like
            array(
                'farr' => array(
                    'name' => array(
                        'x' => array(
                            'y' => $f1['name'],
                            'z' => $f2['name'],
                        ),
                        'a' => $f3['name'],
                    ),
                    'type' => array(
                        'x' => array(
                            'y' => $f1['type'],
                            'z' => $f2['type'],
                        ),
                        'a' => $f3['type'],
                    ),
                    'tmp_name' => array(
                        'x' => array(
                            'y' => $f1['tmp_name'],
                            'z' => $f2['tmp_name'],
                        ),
                        'a' => $f3['tmp_name'],
                    ),
                    'error' => array(
                        'x' => array(
                            'y' => $f1['error'],
                            'z' => $f2['error'],
                        ),
                        'a' => $f3['error'],
                    ),
                    'size' => array(
                        'x' => array(
                            'y' => $f1['size'],
                            'z' => $f2['size'],
                        ),
                        'a' => $f3['size'],
                    ),
                ),
                'f' => $f2,
            ),
            array(
                'farr' => array(
                    'x' => array(
                        'y' => $f1,
                        'z' => $f2,
                    ),
                    'a' => $f3,
                ),
                'f' => $f2,
            ),
        );

        $sets[] = array( // error format
            array(
                'f1' => $f1,
                'f2' => array(
                    'name' => 'f2'
                ),
            ),
            null
        );

        $sets[] = array( // error format
            array(
                'farr' => array(
                    'x' => 3,
                ),
            ),
            null
        );

        $sets[] = array( // error format
            array(
                'f' => array(
                    'name' => 'one.txt',
                    'type' => 'text/plain',
                    'size' => array(),
                    'tmp_name' => '/tmp/qwe',
                    'error' => 0,
                ),
            ),
            null
        );

        return $sets;
    }

    /**
     * @covers \go\Upload\Files\FilesArray::isValidParams
     * @dataProvider providerValidItem
     */
    public function testIsValidItem($params, $valid)
    {
        if ($valid) {
            $this->assertTrue(FilesArray::isValidItem($params));
        } else {
            $this->assertFalse(FilesArray::isValidItem($params));
        }
    }

    public function providerValidItem()
    {
        $valid = array(
            array(
                'name' => '1.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/file',
                'size' => 123,
                'error' => 0,
            ),
            array(
                'name' => '',
                'type' => '',
                'tmp_name' => '',
                'size' => 0,
                'error' => 4,
            ),
        );
        $notvalid = array(
            array(),
            array(
                'name' => '1.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/file',
                'error' => '0',
            ),
            array(
                'name' => '1.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/file',
                'size' => '123',
                'error' => '0',
                'unknown' => 'qwe',
            ),
            array(
                'name' => '1.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/file',
                'size' => '123',
                'unknown' => 'qwe',
            ),
            array(
                'name' => '1.txt',
                'type' => 'text/plain',
                'tmp_name' => array(1),
                'size' => '123',
                'error' => '0',
            ),
        );
        $sets = array();
        foreach ($valid as $item) {
            $sets[] = array($item, true);
        }
        foreach ($notvalid as $item) {
            $sets[] = array($item, false);
        }
        return $sets;
    }
}
