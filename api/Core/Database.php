<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * MyMVC Framework Application
 * XML Flat File Database Handler : Manages the connection and exchange of data with a database
 */

class Database implements DatabaseHandler
{
    private $databaseuser = '';
    private $databasepass = '';
    private $databasesrvr = '';
    
    public function __construct () { }
    
    public function getTable( string $name ) : array
    {
        # A table = A directory containing a collection of XML files (rows)
        # Returns an array of all rows from the table
    }
    
    public function getRow ( string $table, int $rowId ) : array
    {
        # A row = a single XML file
        # Returns an array from a single row from the table, where $key [ column ] => $value [ cell ]
    }
    
    public function getCell ( string $table, int $rowId, string $column )
    {
        # A cell = An XML value from the file, where $column is the <XMLTag/> and $id is the row (XML file) to get from.
    }
    
    public function findRowsWhere ( string $table, string $column, string $value ) : array
    {
        # Return array of rows that have a column containing the value
    }
    
    public function createTable ( string $name, array $columns ) : bool
    {
        # Creates a new empty table (directory) in the database. $columns names to be storred in structure.ini
    }
    
    public function createRow ( string $table, array $values ) : int
    {
        # Create a new row (XML File) in table (directory) where each <$key> $value </$key>
        # Returns rowId of the new row
    }
    
    public function updateRow ( string $table, int $rowId, $values ) : bool
    {
        # Update a row (XMLFile) in the table (directory). $values = updated values to replace. Only replace given
        # values, keep all others the same
    }
    
    /**
     * XML Save
     * Saves given XML object to file
     *
     * @param object $xml     - The XML object to save to file
     * @param string $file    - Filename (inc. path) that is will be saved as
     * @return bool           - True: Save successful, False: Save failed
     */
    private function XMLsave( $xml, $file ) : bool {
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
     * Get XML Data
     * Turns the XML file into a SimpleXMLExtended object
     *
     * @param string $file                - The file to load data from
     * @return object[SimpleXMLExtended]  - The SimpleXMLObject object
     */
    private function getXML( $file ) : object {
        if ( isFile( $file, '', 'xml' ) ) {
            $xml = @file_get_contents( $file );
            if ( $xml ) {
                $data = simplexml_load_string( $xml, 'SimpleXMLExtended', LIBXML_NOCDATA );
                return $data;
            } else { /*die( "[basic.php] getXML: Could not load XML data - $file" );*/ return (object)[]; }
        } else { /*die( "[basic.php] getXML: Not an XML file - $file" );*/ return (object)[]; }
    }
}
