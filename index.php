<?php
/**
 * RewardingLoyalty API
 * - Request Handler
 *
 * This file manages all requests that go through the website, redirecting them or loading-in content as required.
 */

define( "IN_SITE", true );                  // Setup the file inclusion security protection method
require_once ( "includes/common.php" );     // Bring in the Common Include file

/**
 * Calculate request from PrettyURL
 */
$request_module = '';
$request_path = '';

# RequestPath Query String Method
if ( isset($_GET["rp"]) ) {
    
    # Extract the path from the RequestPath query string
    $request_path = explode( "/", $_GET["rp"] );
    
    # Determine requested module
    if ( count($request_path) > 0 ) {
        $request_module = array_shift( $request_path );
    }
    
}

# Request URI Method
elseif ( ( count($_GET) == 0 ) && ( isset($_SERVER['REQUEST_URI']) ) ) {
    
    # Extract the path set from the Request URI
    $request_path = explode( "/", $_SERVER['REQUEST_URI'] );
    
    # Determine the requested module
    if ( count($request_path) > 0 ) {
        $request_module = array_shift( $request_path );
    }
    
}

# Direct Query String Method
else {
    
    # Set the requested path
    if ( isset($_GET['path']) ) { $request_path = $_GET['path']; }
    
    # Set the requested module
    if ( isset($_GET['module']) ) { $request_module = $_GET['module']; }
}


/**
 * Hand over to the module that needs to handle the incoming request
 */

if ( ( !empty($request_module) ) && ( in_array($request_module, getModules()) ) ) {
    
    # We are looking for a specific valid module, lets load that in.
    require_once( MODULESPATH . $request_module . "/main.php" );
    
} elseif ( $_SERVER['REQUEST_URI'] == "/" ) {
    
    # Request is for root url, Go to the default 'frontend' module.
    require_once( MODULESPATH . "frontend/main.php" );
    
} else {
    
    # We've got nowhere to go. Give 'em the 'ol 404!
    $frontendModule_showErrorCode = 404;
    require_once( MODULESPATH . "frontend/error.php" );
    
}
