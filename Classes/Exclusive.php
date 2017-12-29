<?php
/**
* Get the page/controller one is
*/
class Exclusive
{
	
	public static function controller($controller = '')
	{
		if ( empty($controller) OR $controller == '' ) 
		{
			$controller = '';
		}
		
			$allUrl = Url::allUrl();
			if ($allUrl != Url::localUrl())
			{
				$remainingUrl = str_replace(Url::localUrl().'/', "", $allUrl);
				$splitRemainingUrl = explode("/", $remainingUrl);
			}
			else
			{
				$splitRemainingUrl[0] = '';
			}
			

			if ($splitRemainingUrl[0] === $controller)
			{
				return true;
			}
			else
			{
				return false;
			}

		
	}
}