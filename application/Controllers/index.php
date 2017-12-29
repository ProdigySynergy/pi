<?php

class Index extends PIController {
    
    public function __construct() {
        parent::__construct();
        $this->index_model = parent::loadModel('index');
    }

    public function index() {


	$this->view->render('index/index_view');
	    
    }

    
}
