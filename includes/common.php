<?php if ( !defined('IN_SITE' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * RewardingLoyalty API
 * - Common Include Handler
 *
 * Contains definitions and basic functions that are commonly required across all files in the package
 */

# Prepare basic definitions
define( "SITE_NAME", "RewardingLoyalty");

# Define Configuration Defaults
defined( "LANGUAGE" ) ?: define( "LANGUAGE", "en-AU" );
defined( "MERGELANG" ) ?: define( "MERGERLANG", false );
defined( "FORMATXML" ) ?: define( "FORMATXML", true );

# Define inclusion paths
define("LANGPATH", '');

# Basic File Inclusions
require_once ( "basic.php" );       // Basic Functions
require_once ( "languages.php" );   // Language Management

/**
 * Bad Stuff Protection
 */
require_once ( "security_functions.php" );
if ( version_compare( PHP_VERSION, "5" ) >= 0 ) {
    foreach ( $_GET as &$xss ) {
        $xss = antixss( $xss );
    }
}

