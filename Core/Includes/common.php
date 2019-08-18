<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }

# Load all the interfaces
$interfaces = array(
    DOCROOT . 'Core/Interfaces/DatabaseHandler.php',
    DOCROOT . 'Core/Interfaces/ModuleController.php'
);
foreach ( $interfaces as $interface ) {
    require_once ( $interface );
}

# Load all the Models
$models = array(
    DOCROOT . 'Core/Models/NavigationItem.php',
);
foreach ( $models as $model ) {
    require_once ( $model );
}

# Load our required classes
require_once ( DOCROOT . 'Core/Configuration.php' );
$CONFIG = new Configuration();
require_once ( DOCROOT . 'Core/Database.php' );
$DB = new Database();
require_once ( DOCROOT . 'Core/Languages.php' );
$LANG = new Languages();
require_once ( DOCROOT . 'Core/ViewController.php' );

/**
 * Case-Insensitive In-Array
 * Creates a function that PHP should already have, but doesn't
 *
 * @param string $needle  - The 'needle' to search for
 * @param array $haystack - The 'haystack' to search within
 */
if ( !function_exists('in_arrayi') ) {
    function in_arrayi ( $needle, $haystack ) {
        return in_array( strtolower($needle), array_map('strtolower', $haystack) );
    }
}
