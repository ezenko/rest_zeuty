<?php
// {{{ Payment config begin

/**
*	Indicator of config inclusion
*/
define('PAYMENT_CONFIG', true);

/**
*  This mode uses testing payments.
*  In the mode of the real work the value PAYMENT_IGNORE_TEST_PAYMENT must be false.
*/
define('PAYMENT_IGNORE_TEST_PAYMENT', false);

/**
*	SEND mode expects that data will be send to the payment system server
*/
define('PAYMENT_ENGINE_SEND', 100);

/**
*	RECEIVE mode expects that class will recieve data from payment system server
*/
define('PAYMENT_ENGINE_RECEIVE', 200);

/**
*	Payment systems
*/
define('PAYMENT_SYSTEM_2CHECKOUT', "2checkout");

define('PAYMENT_SYSTEM_AUTHORIZENET', "authorizenet");

define('PAYMENT_SYSTEM_PAYPAL', "paypal");

define('PAYMENT_SYSTEM_WORLDPAY', "worldpay");

define('PAYMENT_SYSTEM_USAEPAY', "usaepay");

define('PAYMENT_SYSTEM_PAYSAT', "paysat");

define('PAYMENT_SYSTEM_ESEC', "esec");

define('PAYMENT_SYSTEM_IPOS', "ipos");

// }}} Payment config end

// {{{ Error handler config begin

/**
*	Indicator of config inclusion
*/
define('ERROR_HANDLER_CONFIG', false);

/**
*   Constants for setting Error_Handler class working mode
*/
define('ERROR_HANDLER_MODE_LOG', 1);		// errors log
define('ERROR_HANDLER_MODE_DISPLAY', 2);	// errors output to the screen
define('ERROR_HANDLER_MODE_DATABASE', 3);	// write errors to the database

// }}} Error handler config end

?>