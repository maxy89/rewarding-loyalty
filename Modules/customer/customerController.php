<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class customerController implements ModuleController {
    
    public function index () {
        $ViewController = new ViewController();
        $ViewController->setVar( 'pagetitle', 'Customers');
        $ViewController->prepareOutput( 'customer/Views/index.html' );
        $ViewController->renderOutput();
    }
    
    public function login () {
        $ViewController = new ViewController();
        $ViewController->setVar( 'pagetitle', 'Customer Login');
        $ViewController->prepareOutput( 'customer/Views/login.html' );
        $ViewController->renderOutput();
    }
}
