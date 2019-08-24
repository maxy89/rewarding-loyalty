<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }
    
    public function index( $path = '' )
    {
        $this->load->view( 'includes/header' );
        $this->load->view( 'coming_soon' );
        $this->load->view( 'includes/footer' );
    }
}
