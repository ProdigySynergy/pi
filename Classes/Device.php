<?php
/**
* 
*/
class Device
{
	
	public static function check()
	{
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		if ($deviceType == 'computer') {
			return 'computer';
		} else if ($deviceType == 'tablet')
		{
			return 'tablet';
		}
		else if ($deviceType == 'phone') {
			return 'phone';
		}
	}
	
}