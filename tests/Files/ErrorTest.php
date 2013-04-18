<?php
/**
 * Test of Error class
 *
 * @package go\Upload\Files
 * @subpackage Tests
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Tests\Files;

use go\Upload\Files\Error;

/**
 * @covers \go\Upload\Files\Error
 */
class ErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \go\Upload\Files\Error::__construct
     * @covers \go\Upload\Files\Error::__get
     * @covers \go\Upload\Files\Error::$code
     */
    public function testCode()
    {
        $error = new Error(\UPLOAD_ERR_FORM_SIZE);
        $this->assertEquals(\UPLOAD_ERR_FORM_SIZE, $error->code);
    }

    /**
     * @covers \go\Upload\Files\Error::__get
     * @covers \go\Upload\Files\Error::$name
     */
    public function testName()
    {
        $error1 = new Error(\UPLOAD_ERR_FORM_SIZE);
        $this->assertEquals('form_size', $error1->name);
        $error2 = new Error(\UPLOAD_ERR_OK);
        $this->assertEquals('ok', $error2->name);
        $error3 = new Error(-2143325);
        $this->assertEquals('unknown', $error3->name);
    }

    /**
     * @covers \go\Upload\Files\Error::__get
     * @covers \go\Upload\Files\Error::$message
     */
    public function testMessage()
    {
        $messages = Error::getMessagesList();
        $error1 = new Error(\UPLOAD_ERR_FORM_SIZE);
        $this->assertEquals($messages['form_size'], $error1->message);
        $error2 = new Error(-324);
        $this->assertEquals($messages['unknown'], $error2->message);

    }

    /**
     * @covers \go\Upload\Files\Error::isError
     */
    public function testIsError()
    {
        $error1 = new Error(\UPLOAD_ERR_FORM_SIZE);
        $this->assertTrue($error1->isError());
        $error2 = new Error(-235);
        $this->assertTrue($error2->isError());
        $error3 = new Error(\UPLOAD_ERR_OK);
        $this->assertFalse($error3->isError());
    }

    /**
     * @covers \go\Upload\Files\Error::__get
     * @expectedException go\Upload\Files\Exceptions\PropNotFound
     */
    public function testErrorProp()
    {
        $error = new Error(0);
        return $error->unknown;
    }

    /**
     * @covers \go\Upload\Files\Error::__set
     * @expectedException go\Upload\Files\Exceptions\ReadOnly
     */
    public function testReadOnly()
    {
        $error = new Error(0);
        $error->code = 5;
    }
}
