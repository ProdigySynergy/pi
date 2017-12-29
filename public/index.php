<?php
// An MVC framework
// Created By: Ajala John T.
// Name: PI PHP

/** Include core/init.php
*   spl_autoload_register function
*/
include_once (dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'init.php');


// Load the Bootstrap!
$bootstrap = new Bootstrap();

$bootstrap->init();
?>