<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class businessController implements ModuleController {
    
    public function index () {
        $ViewController = new ViewController();
        $ViewController->setVar( 'pagetitle', 'Businesses');
        $ViewController->prepareOutput( 'business/Views/index.html' );
        $ViewController->renderOutput();
    }
}
