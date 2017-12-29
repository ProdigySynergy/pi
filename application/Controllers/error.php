<?php

class Error extends PIController {

    function __construct() {
        parent::__construct(); 
    }
    
    function index() {
        $this->view->title = '404 Error';
        $this->view->msg = 'This page doesnt exist';
        
        $this->view->render('error/inc/header');
        $this->view->render('inc/nav.inc');
        $this->view->render('error/index');
        $this->view->render('error/inc/footer');
    }

}