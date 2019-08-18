<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


interface DatabaseHandler
{
    public function __construct ();
    public function getTable( string $name ) : array;
    public function getRow ( string $table, int $rowId ) : array;
    public function getCell ( string $table, int $rowId, string $column );
    public function findRowsWhere ( string $table, string $column, string $value ) : array;
    public function createTable ( string $name, array $columns ) : bool;
    public function createRow ( string $table, array $values ) : int;
    public function updateRow ( string $table, int $rowId, $values ) : bool;
}
