<?php

session_start();
header('Content-type: text/html; charset=utf-8');

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

define('PAYPAL_CLIENTID', 'xxx');
define('PAYPAL_SECRET',   'xxx');
define('PAYPAL_PROFILE',  'xxx');
define('PAYPAL_TRUE',     'http://site.com.br/xxx.php?success=true');
define('PAYPAL_FALSE',    'http://site.com.br/xxx.php?success=false');

$apiContext = new ApiContext(
    new OAuthTokenCredential(
        PAYPAL_CLIENTID,
        PAYPAL_SECRET
    )
);

$apiContext->setConfig(
    array(
        'mode' => 'sandbox',
        'log.LogEnabled' => true,
        'log.FileName' => 'PayPal.log',
        'log.LogLevel' => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
        'validation.level' => 'log',
        'cache.enabled' => true,
        'cache.FileName' => 'auth.cache'
    )
);