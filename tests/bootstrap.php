<?php
/**
 * Initialization of unit tests for go\Upload packages
 *
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 * @package go\Upload\Tests
 */

namespace go\Upload\Tests;

require_once(__DIR__.'/../src/Autoloader.php');
\go\Upload\Autoloader::register();

\call_user_func(
    function () {
        $autoloader = new \go\Upload\Autoloader(__NAMESPACE__, __DIR__);
        $autoloader->registerAsAutoloader();
    }
);
