<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class adminController implements ModuleController {
    
    public function index ($path) {  }
    
    public function apikeys ( $action ) {}
    
    public function billing ( $action ) {}
    
    public function business ( $action ) {}
    
    public function customer ( $action ) {}
    
    public function insights ( $action ) {}
    
    public function loyaltycard ( $action ) {}
    
    public function transactions ( $action ) {}
}
