<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper("url");
    }
	public function index()
	{
		$data = array();

        /* Coutry List Values */
        $country_list = file("countries/countries.txt");
        $country_data = array();
        foreach($country_list as $country){
            $parts = explode(' - ', $country);
            $country_data[] = array('code'=>trim($parts[0]),'name'=>trim($parts[1]));
        }
        
        sort($country_data);

        /* View Output */
        $data['countries'] = $country_data;
        $this->load->view('v_dashboard',$data);
	}
}