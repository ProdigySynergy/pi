<?php
/**
* 
*/
class PIViews
{
	
	public function render($page = NULL, $onload=false)
	{
		$spitUp = '';
		$spitDown = '';
		if ($onload == true)
		{
			$url = ROOT.DS.'application'.DS.'views'.DS.$page.'.php';
			$spitUp = '
			<script type="text/javascript">
				$(document).ready(function() { alert("OK"); } );
				document.addEventListener("DOMContentLoaded", function(event) {
	   				document.write("OK");
			';
			$spitDown = '
				});
			</script>
			';
		}

		//echo $spitUp;echo $spitDown;
		if ($page === NULL) {
			json_encode(require_once ROOT.DS. 'application' . DS . 'views'.DS.'index.php');
		} else {
			if (file_exists(ROOT.DS. 'application' . DS . 'views'.DS.$page.'.php'))
			{
				json_encode(require_once ROOT.DS. 'application' . DS . 'views'.DS.$page.'.php');
			} else {
				json_encode(require_once ROOT.DS. 'application' . DS . 'views'.DS.'inc'.DS.'error'.DS.'404.php');
			}
		}

	}
	

}