<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }
    
    public function index( $path = '' )
    {
        $this->load->view( 'includes/header' );
        $this->load->view( 'includes/primary_navigation' );
        $this->load->view( 'coming_soon' );
        $this->load->view( 'includes/footer' );
    }
    
    public function about_us() {}
    
    public function resellers() {}
    
    public function contact_us() {}
    
    public function locations() {}
    
    public function legal_jargon() {}
}
