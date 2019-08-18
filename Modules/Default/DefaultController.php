<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class DefaultController implements ModuleController {
    
    public function index () {
        require_once ( 'DefaultNavigation.php' );
        $ViewController = new ViewController();
        $ViewController->setVar( 'pagetitle', 'Welcome!');
        $ViewController->prepareOutput( 'Default/Views/index.html' );
        $ViewController->renderOutput();
    }
    
    public function route ($path) {
        require_once ( 'DefaultNavigation.php' );
        
        if ( $path[0] == '' ) { $this->index(); }
        else {
            $ViewController = new ViewController();
            $ViewController->prepareOutput( 'Default/Views/other.html' );
            $ViewController->renderOutput();
        }
    }
    
    public function notFound($path) {}
}
