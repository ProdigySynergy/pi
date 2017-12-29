<?php
/**
* Default
*/
class PIController
{
    public function __construct() {
        $this->view = new PIViews();
    }

	public function loadModel($name)
	{
		$modelName = ucwords($name).'Model';
		$path = ROOT.DS. 'application' . DS . 'Models'.DS.$modelName.'.php';
		if(file_exists($path))
		{
			require_once $path;

			
			$this->model = new $modelName();
			return $this->model;
		}

		return false;
	}

}