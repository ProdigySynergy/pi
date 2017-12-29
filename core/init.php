<?php
session_start();
date_default_timezone_set('Africa/Lagos');

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' 		=> '127.0.0.1',
		'username' 	=> 'root',
		'password' 	=> '',
		'db' 		=> 'the_db'
	),
	'remember' => array(
		'cookie_name'	=> 'hash',
		'cookie_expiry'	=> 604800
	),
	'session' => array(
		'name'		=> 'sess',
		'name_id'	=> 'sess_id'
	),
	'token' => array(
		'name' => 'token',
		'hash' => '()87*&6734!@jhBYUDS~/>/?,"]{+_'
	),
	'tables' => array(
		'pages'				=>	'pages',
		'files'				=>	'file_uploads',
		'news'				=>	'news'
	),
	'url' => array (
		'fb'		=>	'https://www.facebook.com/',
		'twitter'	=>	'https://twitter.com/'
	),
	'mail' => array(
		'contact'	=> 'info@domain.com',
		'admin'		=> 'admin@domain.com',
		'bot'		=> 'no-reply@domain.com'
	),
	'ip' => getenv("REMOTE_ADDR")
);

//Define the application directory separator
// define("DS", DIRECTORY_SEPARATOR);
define("DS", "/");

//Define the application path
define('ROOT', dirname(dirname(__FILE__)));

//Autoload classes
spl_autoload_register(function($class) {
	
	require_once ROOT.DS.'Classes'. DS . ucwords($class) . '.php';
	
});

require_once ROOT.DS.'functions'.DS.'sanitize.php';

// Autoload Model
Autoload::model();// e.g. Autoload::model('View'); /*Just View not ViewModel*/

// Autoload Controller
Autoload::controller();
