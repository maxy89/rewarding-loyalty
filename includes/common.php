<?php if ( !defined('IN_SITE' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * RewardingLoyalty API
 * - Common Include Handler
 *
 * Contains definitions and basic functions that are commonly required across all files in the package
 */

# Define Configuration Defaults
require_once( "default_config.php" );
$CONFIG_INI = parse_ini_file( $_SERVER['DOCUMENT_ROOT'] . "config.ini.php", true, INI_SCANNER_TYPED );
foreach ( $CONFIG as $section => $values ) {
    if ( array_key_exists($section, $CONFIG_INI) ) {
        foreach ( $values as $key => $value ) {
            if ( array_key_exists($key, $CONFIG_INI[$section]) ) {
                $CONFIG[$section][$key] = $value;
            }
        }
    }
}

# Define inclusion paths
define("ROOTPATH", $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define("INCPATH", ROOTPATH . "includes" . DIRECTORY_SEPARATOR);
define("LANGPATH", ROOTPATH . "languages" . DIRECTORY_SEPARATOR);
define("MODULESPATH", ROOTPATH . "modules" . DIRECTORY_SEPARATOR);
define("THEMESPATH", ROOTPATH . "themes" . DIRECTORY_SEPARATOR);

# Basic File Inclusions
require_once ( INCPATH . "basic.php" );       // Basic Functions
require_once ( INCPATH . "languages.php" );   // Language Management

/**
 * Bad Stuff Protection
 */
require_once ( INCPATH . "security_functions.php" );
if ( version_compare( PHP_VERSION, "5" ) >= 0 ) {
    foreach ( $_GET as &$xss ) {
        $xss = antixss( $xss );
    }
}

