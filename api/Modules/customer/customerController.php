<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class customerController implements ModuleController {
    
    public function index () {
        $ViewController = new ResponseController();
        $ViewController->prepareOutput( 'customer/Views/index.html' );
        $ViewController->renderOutput();
    }
}
