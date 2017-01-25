<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function index()
    {
        $keyword = $this->input->get("keyword");
        $country = $this->input->get("country");
        $device  = $this->input->get("device_type");
        $supply  = $this->input->get("supply_type");
        $chunks  = $this->input->get("per_page");
        $sort    = $this->input->get("sort_by");
        $order   = $this->input->get("order_by");
        $page    = $this->input->get("page");

        $devices    = array();
        $devices[0] = "d_all";
        $devices[1] = "d_dsk";
        $devices[2] = "d_phn";
        $devices[3] = "d_tab";

        $supplies   = array();
        $supplies[0] = "s_all";
        $supplies[1] = "s_web";
        $supplies[2] = "s_mwb";
        $supplies[3] = "s_map";

        $filter = $devices[$device].'_'.$supplies[$supply];
        $table = 'country_'.strtolower($country);

        $sorting  = array();
        $sorting['seller_name'] = "seller_member_name";
        $sorting['seller_id'] = "seller_member_id";
        $sorting['filtered_impressions'] = "filtered_imps";
        $sorting['filtered_uniques'] = "filtered_uniques";

        // Initial query start.
        $sql_string = "SELECT * FROM `{$table}`";

        // WHERE clause.
        if(is_numeric($keyword)){ // Keyword is numeric
            $sql_string .= " WHERE `filter` = '{$filter}' AND `seller_member_id` = {$keyword}";
        }
        elseif(trim($keyword) != ""){ // Keyword is alphaneumeric
            $keyword = $this->db->escape_like_str($keyword);
            $sql_string .= " WHERE `filter`='{$filter}' AND `seller_member_name` LIKE '%{$keyword}%'";
        }
        else{
            $sql_string .= " WHERE `filter`='{$filter}'";
        }
        // ORDER BY clause.
        if(strlen($sort) > 2 && !is_numeric($sort)){
            if($order == "asc"){
                $order = "ASC";
            }
            else{
                $order = "DESC";
            }
            if($sorting[$sort] != ""){
                $sql_string .= " ORDER BY `{$sorting[$sort]}` {$order}";
            }
        }

        /* Pagination Values */

        #-1 Count total number of rows.
        $res_count = $this->db->query($sql_string);
        $total_row = $res_count->num_rows();
        #-2 Get total page.
        if(!is_numeric($chunks)){ // check if per page is not defined
            $chunks = 10;   // set value to 10
        }
        $page_total = ceil($total_row/$chunks);
        #-3 Get page offset index.
        if(!is_numeric($page)){ // if page number is not defined
            $page_index = 0;
        }
        elseif($page > 0){
            $page_index = ($page-1)*$chunks;
        }
        #-4 Makes pages list.
        $page_list = array();
        if($page_total == 0){
            $page_list[] = 1;
        }
        else{
            for($i=1;$i<($page_total+1);$i++){
                $page_list[] = $i;
            }
        }
        
        // Add limit
        $sql_string .= " LIMIT {$page_index},{$chunks}";
        $resource = $this->db->query($sql_string);
        $result = $resource->result();

        /* JSON Output */
        $pagination = $this->load->view("v_pagination",array('page'=>$page,'list'=>$page_list,'total'=>$page_total),true);
        $table = $this->load->view("v_table",array('rows'=>$result,'sort'=>$sort,'order'=>$order),true);
        
        $output = array(
            "status" => "success",
            "table"  => $table,
            "pagination" => $pagination
        );
        
        header("Access-Control-Allow-Origin: *");
        echo json_encode($output);
        //echo $sql_string;
    }

    public function bk()
    {
        $keyword  = $this->input->get("keyword");
        $category = $this->input->get("category");
        $chunks   = $this->input->get("per_page");
        $sort     = $this->input->get("sort_by");
        $order    = $this->input->get("order_by");
        $page     = $this->input->get("page");

        // Initial query start.
        $sql_string = "SELECT * FROM `{$category}`";

        // WHERE clause.
        if(is_numeric($keyword)){ // Keyword is numeric
            $sql_string .= " WHERE `{$category}`.`bk_id` = {$keyword} OR `{$category}`.`apn_id` = {$keyword}";
        }
        elseif(trim($keyword) != ""){ // Keyword is alphaneumeric
            $keyword = $this->db->escape_like_str($keyword);
            $sql_string .= " WHERE `{$category}`.`path` LIKE '%{$keyword}%'";
        }
        // ORDER BY clause.
        if(strlen($sort) > 2 && !is_numeric($sort)){
            if($order == "asc"){
                $order = "ASC";
            }
            else{
                $order = "DESC";
            }
            if($sort != ""){
                $sql_string .= " ORDER BY `{$sort}` {$order}";
            }
        }

        /* Pagination Values */

        #-1 Count total number of rows.
        $res_count = $this->db->query($sql_string);
        $total_row = $res_count->num_rows();
        #-2 Get total page.
        if(!is_numeric($chunks)){ // check if per page is not defined
            $chunks = 30;   // set value to 10
        }
        $page_total = ceil($total_row/$chunks);
        #-3 Get page offset index.
        if(!is_numeric($page)){ // if page number is not defined
            $page_index = 0;
        }
        elseif($page > 0){
            $page_index = ($page-1)*$chunks;
        }
        #-4 Makes pages list.
        $page_list = array();
        if($page_total == 0){
            $page_list[] = 1;
        }
        else{
            for($i=1;$i<($page_total+1);$i++){
                $page_list[] = $i;
            }
        }
        
        // Add limit
        $sql_string .= " LIMIT {$page_index},{$chunks}";
        $resource = $this->db->query($sql_string);
        $result = $resource->result();

        /* JSON Output */
        $pagination = $this->load->view("v_pagination",array('page'=>$page,'list'=>$page_list,'total'=>$page_total),true);
        $table = $this->load->view("v_bk_table",array('rows'=>$result,'sort'=>$sort,'order'=>$order),true);
        
        $output = array(
            "status" => "success",
            "table"  => $table,
            "pagination" => $pagination
        );
        header("Access-Control-Allow-Origin: *");
        echo json_encode($output);
    }
}