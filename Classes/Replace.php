<?php
/**
* 
*/
class Replace
{
	
	public static function numeric($string)
	{
		return preg_replace('#[^0-9]#i', '', trim($string));
	}
	
	public static function alphanum($string)
	{
		return preg_replace('#[^0-9A-Za-z_]#i', '', trim($string));
	}
	
	public static function char_space($string)
	{
		return preg_replace('#[^0-9A-Za-z_ ]#i', '', trim($string));
	}
	
	public static function spaceUnderscore($string)
	{
		return str_replace(' ', '_', trim($string));
	}
	
	public static function underscoreSpace($string)
	{
		return str_replace('_', ' ', trim($string));
	}

	public static function pregurl ($url)
	{
		return preg_replace("/ /", "%20", $url);
	}
	
}