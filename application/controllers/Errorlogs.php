<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Error logs server.
*/

class Errorlogs extends CI_Controller
{
    public function index(){}

    public function apnx()
    {
        $log_file = FCPATH."application/logs/appnexus_reports.log";
        $contents = file_get_contents($log_file);
        header("Content-Type: text/plain");
        echo $contents;
    }
    public function phpInfo()
    {
        phpinfo();
    }
}