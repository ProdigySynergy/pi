<?php
function escape($string) {
	return htmlentities(trim($string), ENT_QUOTES, 'UTF-8');
}

function escapeOut($string) {
	$string = html_entity_decode(stripslashes(trim($string)), ENT_QUOTES, 'UTF-8');
	$string = htmlspecialchars_decode($string);

	return $string;
}

function linkyfy($string)
{
	
	// The Regular Expression filter
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

	// Check if there is a url in the string
	if(preg_match($reg_exUrl, $string, $url)) {

	       // make the urls hyper links
	       $string = preg_replace($reg_exUrl, '<a href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $string);

	} else {

	       // if no urls in the string just return the string
	       $string = $string;

	}

	return $string;
}

function decode($string)
{
	
	$string = html_entity_decode(addslashes(trim($string)));
	$string = htmlspecialchars_decode($string);

	return $string;
}

function escapeURL($url)
{
	if ($url) {
		return filter_var($url, FILTER_VALIDATE_URL);
	}

	return false;
}

function isEmail($email)
{
	if ($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	return false;
}

function validateDate($date, $format='d/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function formatDate($dateStr, $old='d/m/y', $new='Y-m-d')
{
	$date = DateTime::createFromFormat($old, $dateStr);
    return $date->format($new);
}

function parseUrlTitle($urltoparse)
{
	$urltoparse = escapeOut($urltoparse);
	$stripurl = preg_replace("#[^0-9a-zA-Z ]#i", "", $urltoparse);
	$stripurl = str_replace('8211', '', $stripurl);

	$newurl = strtolower(substr(str_replace(" ", "-", $stripurl), 0, 75));
	$newurl = str_replace("--", "-", $newurl);
	$newurl = rtrim($newurl, '-');

	return $newurl;
}

function enc($value)
{
	return substr(sha1($value), 0, 15);
}

function clean($txt)
{
    $txt=preg_replace("{(<br[\\s]*(>|\/>)\s*){2,}}i", "", $txt);
    $txt=preg_replace("{(<br[\\s]*(>|\/>)\s*)}i", "", $txt);
    return $txt;
}


function description($str)
{
	$str = strip_tags(escapeOut($str));
	return substr($str, 0, 148).' '.escapeOut(' Read More &raquo;');
}

// function keywords($str)
// {
// 	$keywords = '';
// 	$str = strip_tags(escapeOut($str));
// 	$extracted = Keyword::extract($str);
// 	foreach ($extracted as $aKeyword) {
		
// 		$keywords += $aKeyword.', ';

// 	}
// 	return $keywords;
// }

function my_callback($element)
{

    if ($element->tag=='table')
    {
        return $element->plaintext;    //$element->outertext = '';
    }
}

function closetags($html) {
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i=0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</'.$openedtags[$i].'>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
} 

function escape_whitespace($string)
{
	
    $value = strip_tags(escapeOut(trim($string)));

    $converted = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES))); 
    $new_value = trim($converted, chr(0xC2).chr(0xA0));

    return $new_value;
}



?>