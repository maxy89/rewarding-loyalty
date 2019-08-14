<?php if ( !defined('IN_SITE' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * RewardingLoyalty Website
 * - Basic Functions
 *
 * These are the basic functions that are used throughout the website package.
 */

# Required basic definitions
defined( "DOCUMENT_ROOT" ) ?: define( "DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT'] );

/**
 * SimpleXMLExtended Class
 * Extends the default PHP SimpleXMLElement class by allowing the addition of CData
 *
 * @param string $cdata_text
 */
class SimpleXMLExtended extends SimpleXMLElement {
    public function addCData( $cdata_text ) {
        $node = dom_import_simplexml( $this );
        $no = $node->ownerDocument;
        $node->appendChild( $no->createCDATASection( $cdata_text ) );
    }
}

/**
 * Get XML Data
 * Turns the XML file into a SimpleXMLExtended object
 *
 * @param string $file                - The file to load data from
 * @return object[SimpleXMLExtended]  - The SimpleXMLObject object
 */
function getXML( $file ) : object {
    if ( isFile( $file, '', 'xml' ) ) {
        $xml = @file_get_contents( $file );
        if ( $xml ) {
            $data = simplexml_load_string( $xml, 'SimpleXMLExtended', LIBXML_NOCDATA );
            return $data;
        } else { /*die( "[basic.php] getXML: Could not load XML data - $file" );*/ return (object)[]; }
    } else { /*die( "[basic.php] getXML: Not an XML file - $file" );*/ return (object)[]; }
}

/**
 * XML Save
 * Saves given XML object to file
 *
 * @param object $xml     - The XML object to save to file
 * @param string $file    - Filename (inc. path) that is will be saved as
 * @return bool           - True: Save successful, False: Save failed
 */
function XMLsave( $xml, $file ) : bool {
    GLOBAL $CONFIG;
    if ( is_object($xml) === false ) {
        # die( "[basic.php] XMLsave: Data is not a valid XML object" );
        return false;
    }
    $data = @$xml->asXML();
    if ( $CONFIG['datahandling']['formatxml'] ) {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML( $data );
        $data = $dom->saveXML();
    }
    return file_put_contents( $file, $data );
}

/**
 * Clean URL
 * Cleans up a given URL, removing/replacing inappropriate characters, then properly encodes it
 *
 * @param string $urlstring   - The URL string to process
 * @return string             - The converted and encoded [cleaned] URL string
 */
function clean_url( $urlstring = '' ) : string {
    $urlstring = strip_tags( strtolower( $urlstring ) );
    $code_entities_match = array(
        ' ?',' ','--','&quot;','!','#','$','%','^','&','*','(',')','+','{','}','|',
        ':','"','<','>','?','[',']','\\',';',"'",',','/','*','+','~','`','='
    );
    $code_entities_replace = array(
        '','-','-','','','','','','','','','','','','','','','','','','','','','','','',''
    );
    $urlstring = str_replace( $code_entities_match, $code_entities_replace, $urlstring );
    $urlstring = urlencode( $urlstring );
    $urlstring = str_replace( '--', '-', $urlstring );
    $urlstring = rtrim( $urlstring, "-" );
    return $urlstring;
}

/**
 * Search Engine Optimised URL
 * Prepares and optimises the given string as a URL that is friendly to search engines
 *
 * @param string $sString   - The input string to optimise
 * @return string $sString  - SEO optimized string
 */
function seofy ($sString = '') : string {
    $sString = preg_replace ('/[^\pL\d_]+/u', '-', $sString);
    $sString = trim ($sString, "-");
    $sString = iconv ('utf-8', "us-ascii//TRANSLIT", $sString);
    $sString = strtolower ($sString);
    $sString = preg_replace ('/[^-a-z0-9_]+/', '', $sString);
    return $sString;
}

/**
 * Trailing Slash
 * Adds a trailing slash to a pathname if one doesn't already exist
 *
 * @param string $path    - Original pathname with possible trailing slash
 * @return string         - Pathname with trailing slash
 */
function tsl( $path ) : string {
    if ( substr($path, strlen($path) - 1) != "/" ) {
        $path .= "/";
    }
    return $path;
}

/**
 * Is File
 * Checks if given filename with path and optional type relates to a real file on the filesystem and if readable
 *
 *@param string $file             - Name of the file to check
 *@param string $path             - Path where file is located : Trailing slash is optional
 *@param string $type : optional  - The file type match against : Optional, default is 'php'
 *@return bool                    - True: File exists and correct, False: File non-existent or not correct
 */
function isFile( $file, $path = '', $type = '' ) : bool {
    if ( empty( $path ) ) { $path = dirname( $file ); }
    if ( is_file( tsl($path) . $file ) &&
         $file != "." && $file != ".." &&
         ( empty($type) ? true : strstr( $file, '.' . $type ) )
    ) { return true; }
    return false;
}

/**
 * Get Files
 * Returns an array of files from the passed path
 *
 * @param string $path    - The path to list files from
 * @return array          - Array of files within path
 */
function getFiles( $path = DOCUMENT_ROOT ) : array {
    $handle = opendir( $path ); # or die( "[basic.php] getFiles: Unable to open $path" );
    $file_array = array();
    while ( $file = readdir( $handle ) ) {
        if ( isFile( $file, $path ) ) {
            $file_array[] = $file;
        }
    }
    closedir( $handle );
    return $file_array;
}

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
