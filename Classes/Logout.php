<?php
/**
* 
*/
class logout
{
	
    private $_db,
    		$_cookieName,
            $_sessionName,
            $_sessionID,
            $_sessionMail;


	public static function to()
	{
		if (Input::get('url'))
		{
			$url = escapeURL(Input::get('url'));
			// if ($permission === "Standard User") {
		        $this->_sessionID = Config::get('session/id');
		        $this->_sessionName = Config::get('session/user');
		        $this->_sessionMail = Config::get('session/mail');
		        $this->_cookieName = Config::get('remember/user');
			// }
			// else if($permission === "Administrator") {
			// 	$sessionName = 'session/su_session_control_name';
			// 	$cookieName = 'remember/cookie_control_name';
			// }
			self::$_db = PIModel::getInstance();

			self::$_db->delete(Config::get('tables/users_session'), array('user_id ='), array(Session::get(Config::get($this->_sessionID))) );
			self::$_db->update( Config::get('tables/users'), array('status' => 'offline'), array('id ='=>Session::get(Config::get($_sessionID))) );

			Session::delete(Config::get($_sessionID));
			Session::delete(Config::get($_sessionName));
			Session::delete(Config::get($_sessionMail));
			Cookie::delete(Config::get($_cookieName));

			if($url) {
				Redirect::to($url);
			}
		}
		
	}
}