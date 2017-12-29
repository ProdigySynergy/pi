<?php
/**
* 
*/
class Json
{
	
	protected static $_messages = array (
		JSON_ERROR_NONE => 'No error has occured',
		JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
		JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		JSON_ERROR_SYNTAX => 'Syntax error',
		JSON_ERROR_UTF8 => 'malformed UTF-8 characters, possibly incorrectly encoded'
	);

	public static function encode($value, $option = 0) {
		$result = json_encode($value, $option);

		if ($result) {
			return $result;
		}

		throw new RuntimeException(self::$_messages[json_last_error()]);
		
	}


	public static function decode($value, $assoc = false) {
		$result = json_decode($value, $assoc);

		if ($result) {
			return $result;
		}

		throw new RuntimeException(self::$_messages[json_last_error()]);
		
	}
}