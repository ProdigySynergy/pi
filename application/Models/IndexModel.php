<?php
/**
* 
*/
class IndexModel extends PIModel
{
	
	public function __construct()
	{
		parent::__construct();

        	$this->pages_tb = Config::get('tables/pages'); // Table name in core/init.php
	}

	// Other model functions goes here


}
