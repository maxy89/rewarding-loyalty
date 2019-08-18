<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * MyMVC Framework Application
 * Dispatcher Class : Determines request and loads appropriate ViewController
 */

class Dispatcher
{
    private $request;       # The request that will be dispatched to
    
    /**
     * Dispatcher constructor.
     * Creates a dispatcher instance based on the given request
     *
     * @param string|null   $request    The url path that will be dispatched
     */
    public function __construct( string $request = null )
    {
        $this->request = new stdClass();
        $this->request->url = $request ?: $_SERVER["REQUEST_URI"];
        $this->parseRequest( $this->request->url, $this->request );
    }
    
    /**
     * Request Dispatcher
     * Loads the required controller and tells it the requested action
     *
     * @return void
     */
    public function dispatch() : void
    {
        GLOBAL $CONFIG;
        $name = $this->request->controller . "Controller";
        require_once( $file = \DOCROOT . 'Modules/'. $this->request->controller . '/' . $name . '.php' );
        if ( !method_exists( $name, $this->request->action) ) {
            $this->request->controller = $CONFIG->getSetting( 'Core', 'default_controller' );
            $this->request->action = 'notFound';
        }
        $controller = new $name();
        call_user_func_array([$controller, $this->request->action], $this->request->params);
    }
    
    /**
     * Parse Request
     * Parses the requested url into its relevant sections
     *
     * @param string    $url        The requested url to process
     * @param stdClass  $request    The request object to put values into
     * @return void
     */
    public function parseRequest( string $url, \stdClass $request ) : void
    {
        $CONFIG = new Configuration();
        $url = ltrim( $url, '/' );
        $explode_url = explode( '/', $url );
        //$explode_url = array_slice( $explode_url, 2 );
    
        if ( in_array( $explode_url[0], $this->getKnownControllers() ) ) {
            # The requested controller is known, lets use it
            if ( $explode_url[1] == '' ) { $explode_url[1] = 'index'; }
                $request->controller = $explode_url[0];
                $request->action = $explode_url[1];
                $request->params = array( array_slice( $explode_url, 2 ) );
        } else {
            # The requested controller is NOT known, lets use the Default controller instead
            # Note: The Default controller can be overridden in the configuration files.
            $request->controller = $CONFIG->getSetting( 'Core', 'default_controller' );
            $request->action = 'route';
            $request->params = array($explode_url);
        }
    }
    
    /**
     * Get Known Controllers
     * Returns an array of Module Controllers that are known to the system (ie. found in Modules/* directory)
     *
     * @return array    Array of known Module Controllers
     */
    private function getKnownControllers() : array
    {
        $dirHandle = glob( DOCROOT . 'Modules/*' );
        $controllers = array();
        foreach ($dirHandle as $module ) {
            if ( ( $module != "." ) && ( $module != ".." ) && ( is_dir($module) ) ) {
                $controllers[] = basename($module);
            }
        }
        return $controllers;
    }
    
}
