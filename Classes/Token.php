<?php
/**
* Generate token for each page
*/
class Token
{
	public static $token_name = null;

	public static function generate($token_name = null)
	{
		if ($token_name === null) {
			self::$token_name = Config::get('token/token_name');
		} else {
			self::$token_name = $token_name;
		}

		return Session::put(self::$token_name, sha1(uniqid()));
	}

	public static function check($token, $name = '')
	{
		$tokenName = ($name != '') ? $name : Config::get('token/token_name');

		if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
			Session::delete($tokenName);
			return true;
			exit();
		}

		return false;
		exit();
	}

	public static function ajaxCheck($token)
	{
		$tokenName = Config::get('token/token_name');

		if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
			
			return true;
		}

		return false;
	}
}