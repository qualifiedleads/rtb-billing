<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("url");
    }
    public function index()
    {
        // Receipient
        //$to = "cyril@rtb.cat";
        $to = "jen@rtb.cat";
        $subj = "New Enquiry";
        // Sender
        $name = $this->input->get("name");
        $mail = $this->input->get("email");
        $type = $this->input->get("type");
        $mesg = $this->input->get("message");
        // Process input.
        if(strlen($name) == 0){
            $name = "Unknown";
        }
        if(strlen($mail) == 0){
            $mail = "unknown@ignore.spam";
        }
        if(is_array($type)){
            if(count($type) == 0){
                $type = "Not specified";
            }
            else{
                $type = implode(',', $type);
            }
        }
        elseif(($strlen) == 0){
            $type = "Not specified";
        }
        if(strlen($mesg) == 0){
            $mesg = "No message written.";
        }
        else{
            $mesg = <<<MESSAGE
Inventory Type: {$type}
Message:

{$mesg}
MESSAGE;
        }

        $from = "From: {$name} <{$mail}>";
        header('Access-Control-Allow-Origin: *');
        
        if(mail($to,$subj,$mesg,$from)){
            header('Content-Type: application/json');
            echo '{"status":"success"}';
        }
        else{
            header('Content-Type: application/json');
            echo '{"status":"failed"}';
        }
    }
	public function test()
    {
        // Receipient
        //$to = "cyril@rtb.cat";
        $to = "mike@rtb.cat";
        $subj = "New Enquiry";
        // Sender
        $name = $this->input->get("name");
        $mail = $this->input->get("email");
        $type = $this->input->get("type");
        $mesg = $this->input->get("message");
        // Process input.
        if(strlen($name) == 0){
            $name = "Unknown";
        }
        if(strlen($mail) == 0){
            $mail = "unknown@ignore.spam";
        }
        if(is_array($type)){
            if(count($type) == 0){
                $type = "Not specified";
            }
            else{
                $type = implode(',', $type);
            }
        }
        elseif(($strlen) == 0){
            $type = "Not specified";
        }
        if(strlen($mesg) == 0){
            $mesg = "No message written.";
        }
        else{
            $mesg = <<<MESSAGE
Inventory Type: {$type}
Message:

{$mesg}
MESSAGE;
        }

        $from = "From: {$name} <{$mail}>";
        header('Access-Control-Allow-Origin: *');
        if(mail($to,$subj,$mesg,$from)){
            header('Content-Type: application/json');
            echo '{"status":"success"}';
        }
        else{
            header('Content-Type: application/json');
            echo '{"status":"failed"}';
        }
    }
}