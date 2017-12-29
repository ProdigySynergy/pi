<?php
/**
* 
*/
class Date
{
	
	public static function now()
	{
		return time();//date("Y-m-d H:i:s");
	}

	public static function readable($stamp, $format = "Y-m-d H:i:s")
	{
		return ($stamp !== "") ? date($format, $stamp) : false;
	}

  public static function hour_only($stamp)
  {
    $hr_only = self::readable(strtotime($stamp), 'H');
    if ($hr_only >= 12)
    {
      $period = ' PM';
      $hr_only = ($hr_only > 12) ? $hr_only - 12 : $hr_only;
      $ret = $hr_only.$period;
    }
    else
    {
      $period = ' AM';
      if ($hr_only == '00') { $hr_only = 12; }
      $hr_only = ($hr_only < 10) ? substr($hr_only, 1) : $hr_only;
      $ret = $hr_only.$period;
    }

    if ($hr_only)
    {
      return $ret;
    }
    else
    {
      return false;
    }
  }

	public static function convert_datetime($str) {
   		list($date, $time) = explode(' ', $str);
    	list($year, $month, $day) = explode('-', $date);
    	list($hour, $minute, $second) = explode(':', $time);
    	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    	return $timestamp;
	}

	public static function makeAgo($timestamp){
   		$difference = time() - $timestamp;
   		$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
   		$lengths = array("60","60","24","7","4.35","12","10");
   		for($j = 0; $difference >= $lengths[$j]; $j++)
   		{
   			$difference /= $lengths[$j];
   			$difference = round($difference);
   			if($difference != 1)
   			{
   				$periods[$j].= "s";
   			}
   			$text = "$difference $periods[$j] ago";
   			return $text;
   		}
	}
}