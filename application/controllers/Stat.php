<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stat extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper("url");
        $this->load->database();
    }
    public function index(){
        $seller = $this->input->get("seller");
        $name = $this->input->get('name');
        $sort = $this->input->get('sort');
        $order = $this->input->get('order');
        $mode = $this->input->get('mode');
        if($seller != "" && strlen($name) > 2){
            $table = 'stat_'.$seller;
            // Check sorting
            if(strlen($sort) > 2){
                $sort = $sort;
            }
            else{
                $sort = "country";
            }
            // Check ordering
            if($order == "desc"){
                $order = 'DESC';
            }
            else{
                $order = 'ASC';
            }
            $sql_string = "SELECT * FROM `{$table}` ORDER BY `{$sort}` {$order}";
            $resource = $this->db->query($sql_string);
            $result = $resource->result();
            $result_array = array();
            foreach($result as $value){
                $result_array[] = array('country'=>$value->country,'impression'=>$value->impression);
            }
            $table = $this->load->view('v_statistics_table',array('stats'=>$result_array,'order'=>strtolower($order),'sort'=>$sort),true);
            if($mode=="json"){
                $json_array = array(
                    'status'=>'success',
                    'content'=>$table
                );
                echo json_encode($json_array);
            }
            else{
                $this->load->view('v_statistics',array('title'=>$name,'table'=>$table));
            }
        }
    }
    public function json(){
        $seller = $this->input->get("seller");
        $sort = $this->input->get('sort');
        $order = $this->input->get('order');
        $mode = $this->input->get('mode');
        if($seller != ""){
            if($seller == "map_all")
            {
                $this->getMapCombined();
            }
            else
            {
                $table = 'stat_'.$seller;
                // Check sorting
                if(strlen($sort) > 2){$sort = $sort;}
                else{$sort = "country";}
                // Check ordering
                if($order == "desc"){$order = 'DESC';}
                else{$order = 'ASC';}
                $sql_string = "SELECT * FROM `{$table}` ORDER BY `{$sort}` {$order}";
                $resource = $this->db->query($sql_string);
                $items = $resource->result_array();
                $data = [];
                $excl = ['--','A1','A2'];
                foreach($items as $item)
                {
                    $country = trim($item['country']);
                    if(!in_array($country, $excl))
                    {
                        $data[$country] = (int)$item['impression'];
                    }
                }
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: application/json");
                echo json_encode($data);
            }
        }
    }
    private function getMapCombined()
    {
        $sql  = "SELECT `stat_google`.`country`,`stat_google`.`impression` as 'google',";
        $sql .= "`stat_microsoft`.`impression` as 'microsoft',";
        $sql .= "`stat_openx`.`impression` as 'openx',";
        $sql .= "`stat_rubicon`.`impression` as 'rubicon',";
        $sql .= "`stat_smaato`.`impression` as 'smaato' FROM `stat_google`";
        $sql .= " INNER JOIN `stat_microsoft` ON `stat_google`.`country`=`stat_microsoft`.`country`";
        $sql .= " INNER JOIN `stat_openx` ON `stat_google`.`country`=`stat_openx`.`country`";
        $sql .= " INNER JOIN `stat_rubicon` ON `stat_google`.`country`=`stat_rubicon`.`country`";
        $sql .= " INNER JOIN `stat_smaato` ON `stat_google`.`country`=`stat_smaato`.`country`";
        $sql .= " ORDER BY `stat_google`.`country`";
        $query = $this->db->query($sql);
        $items = $query->result_array();
        $data = ['map'=>[],'label'=>[]];
        $excl = ['--','A1','A2'];
        foreach($items as $item)
        {
            $country = trim($item['country']);
            if(!in_array($country, $excl))
            {
                $data['map'][$country] = (int)$item['google'] + (int)$item['microsoft'] + (int)$item['openx'] + (int)$item['rubicon'] + (int)$item['smaato'];
                $data['label'][$country] = [
                    'google' => (int)$item['google'],
                    'microsoft' => (int)$item['microsoft'],
                    'openx' => (int)$item['openx'],
                    'rubicon' => (int)$item['rubicon'],
                    'smaato' => (int)$item['smaato']
                ];
                arsort($data['label'][$country]);
            }
        }
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}