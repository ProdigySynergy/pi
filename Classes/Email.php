<?php
/**
* 
*/
class Email
{
	
	public static function send($to, $title, $body, $foot = '')
	{
		$from = Config::get('mail/bot');
	    $headers = "From: $from $title\n";
	    $headers .= "MIME-Version: 1.0\n";
	    $headers .= "Content-Type: text/html; charset=utf-8\n";


	    @$author = ($author) ? $author: 'Admin.';
		$mail_foot = '
			<br />
			<br />
			<div>
				<p>'.$author.'</p><br />
				<p><a href="'.Url::localUrl().'">'.Url::localUrl().'</a></p>
				<p><a href="'.Url::localUrl().'contact-us">Contact Us</a></p>
				<p>Facebook Page: <a href="'.Config::get('url/facebook_page').'">Naija Class Captain</a></p>
				<p>Twitter: <a href="'.Config::get('url/twitter_handle').'">@naija_observer</a></p>
				<!--<p>Facebook Group: </p>-->
				<p>Google Plus: <a href="'.Config::get('url/google_plus_page').'">NaijaObserver News</a></p>
			</div>
		';



	    $body = ($foot === '') ? $body.$mail_foot : $body.$foot;

		mail($to, $title, $body, $headers);
	}


	public static function mail_footer($additional = '', $author = '')
	{
		$author = ($author) ? $author: 'Admin.';
		$mail_foot = '
			<br />
			<br />
			<div>
				<p>'.$author.'</p><br />
				<p><a href="'.Url::localUrl().'">'.Url::localUrl().'</a></p>
				<p><a href="'.Url::localUrl().'contact-us">Contact Us</a></p>
				<p>Facebook Page: <a href="https://www.facebook.com/naijaobserver">Naija Class Captain</a></p>
				<p>Twitter: <a href="https://www.twitter.com/naija_observer">@naija_observer</a></p>
				<!--<p>Facebook Group: </p>
				<p>Google Plus: </p>-->
				'.$additional.'
			</div>
		';

		return $mail_foot;
	}
}