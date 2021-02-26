<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/assets/VATTestCase.php';

if(!file_exists(__DIR__.'/config.php'))
{
    die('To run the tests, create the config file first - see README.');
}

require_once __DIR__.'/config.php';
