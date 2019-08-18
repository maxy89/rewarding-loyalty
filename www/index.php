<?php
/**
 * MyMVC Framework Application
 * Front View Controller
 *
 * @version 0.0.1-alpha
 * @author John Stray [mail@johnstray.id.au]
 * @license GNU General Public License v3
 */

define( 'WEBROOT', rtrim( str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME'] ), '/' ) . '/' );
define( 'DOCROOT', rtrim( str_replace( 'index.php', '', $_SERVER['SCRIPT_FILENAME'] ), '/' ) . '/' );
define( 'IN_APP', true ); # Application Environment Constant : Used to prevent direct file access

require_once ( DOCROOT . "Core/Includes/basic.php" );
require_once ( DOCROOT . "Core/Includes/common.php" );
require_once ( DOCROOT . "Core/Dispatcher.php" );

$dispatch = new Dispatcher();
$dispatch->dispatch();
