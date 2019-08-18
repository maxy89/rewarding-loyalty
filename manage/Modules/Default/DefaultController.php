<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class DefaultController implements ModuleController {
    
    public function index () {
        require_once ( 'DefaultNavigation.php' );
        $ViewController = new ViewController();
        $ViewController->setVar( 'pagetitle', 'Login');
        $ViewController->prepareOutput( 'Default/Views/login.html' );
        $ViewController->renderOutput();
    }
    
    public function notFound($path) {}
}
