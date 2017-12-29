<?php
/**
* 
*/
class Url
{
	public static function urlHost()
	{
		return $_SERVER['HTTP_HOST'];
	}

	public static function allUrl()
	{
		return rtrim("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '/');
	}

	public static function fullUrl()
	{
		$urlSplit = explode("?", self::allUrl());
		return rtrim($urlSplit[0], '/');
	}
	
	public static function localUrl()
	{
		return rtrim("http://".self::urlHost().'/no_news', '/');
	}

	public static function makeActiveLink($originalString) 
	{
	
        $newString = preg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\">\\0</a>", $originalString);
        return $newString;
    }
}
