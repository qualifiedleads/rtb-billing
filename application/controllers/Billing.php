<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Billing.
*/

class Billing extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('UTC');
        $this->load->library("Appnexus/Apnx");
    }

    public function index()
    {}

    public function getAdvertiserCostImps($adv_id=null)
    {
        $plain_output = $this->input->get('plain');
        $timestamp = time();
        $YYYY = date("Y", $timestamp);  // A four digit representation of a year.
        $MM = date("m", $timestamp);    // A numeric representation of a month, with leading zeros (01 to 12).
        $MT = date("F", $timestamp);    // A full textual representation of a month (January through December).
        $DD = date("d", $timestamp);    // The day of the month with leading zeros (01 to 31).
        $hh = date("H", $timestamp);    // 24-hour format of an hour (00 to 23).
        $mm = date("i", $timestamp);    // Minutes with leading zeros (00 to 59).
        $ss = date("s", $timestamp);    // Seconds, with leading zeros (00 to 59).
        $output = [
            "status" => "error",
            "message" => "User id is required.",
            "data" => ""
        ];

        if ($adv_id)
        {

            $json_request = $this->load->view("json/advertiser_analytics", "", true);
            $response = $this->apnx->requestPost("report?advertiser_id=".$adv_id, $json_request);
            $response_body = json_decode($response['body'], true);
        
            if (!$response_body)
            {
                $output = [
                    "status" => "error",
                    "message" => "Unknown error while getting analytics report.",
                    "data" => ""
                ];
            }
            else if (isset($response_body['response']['error']))
            {
                $error = $response_body['response']['error'];
                $output = [
                    "status" => "error",
                    "message" => $error,
                    "data" => ""
                ];
            }
            else
            {
                $report_id = $response_body['response']['report_id'];
                $report_csv = $this->apnx->getReport($report_id);

                $report_csv = $report_csv['body'];
                $report_array = explode("\n", $report_csv);
                $header = array_shift($report_array);

                $data = [
                    "google" => [
                        "media_cost" => 0,
                        "imps" => 0
                    ],
                    "yahoo" => [
                        "media_cost" => 0,
                        "imps" => 0
                    ],
                    "others" => [
                        "media_cost" => 0,
                        "imps" => 0
                    ],
                    "all" => [
                        "media_cost" => 0,
                        "imps" => 0
                    ]
                ];
                $date = [
                    "YYYY" => $YYYY,
                    "MM" => $MM,
                    "MT" => $MT,
                    "DD" => $DD,
                    "hh" => $hh,
                    "mm" => $mm,
                    "ss" => $ss
                ];
                
                // Filter, group according to seller
                foreach ($report_array as $csv)
                {
                    $csv = trim($csv);
                    if(!empty($csv))
                    {
                        $csv_line_pieces = str_getcsv($csv);
                        if(count($csv_line_pieces) == 4)
                        {
                            $member_name = $csv_line_pieces[0];
                            $member_id = $csv_line_pieces[1];
                            $media_cost = $csv_line_pieces[2];
                            $imps = $csv_line_pieces[3];

                            // All
                            $data['all']['media_cost'] += $media_cost;
                            $data['all']['imps'] += $imps;

                            // Google
                            if ($member_id == 181)
                            {
                                $data['google']['media_cost'] += $media_cost;
                                $data['google']['imps'] += $imps;
                            }
                            // Yahoo
                            elseif ($member_id == 273)
                            {
                                $data['yahoo']['media_cost'] += $media_cost;
                                $data['yahoo']['imps'] += $imps;
                            }
                            // Others
                            else
                            {
                                $data['others']['media_cost'] += $media_cost;
                                $data['others']['imps'] += $imps;
                            }
                        } 
                    }
                }

                $output = [
                    "status" => "ok",
                    "message" => "Report retrieved successfully.",
                    "data" => $data,
                    "date" => $date
                ];
            }
        }

        // Response.
        if($plain_output)
        {
            header("Content-Type: text/plain");
            print_r($output);
        }
        else
        {
            header("Content-Type: application/json");
            echo json_encode($output);
        }
    }

    public function getBuyerCostImps($user_id=null)
    {
        $plain_output = $this->input->get('plain');
        $range_last_month = $this->input->get('last_month');
        $timestamp = time();
        $YYYY = date("Y", $timestamp);  // A four digit representation of a year.
        $MM = date("m", $timestamp);    // A numeric representation of a month, with leading zeros (01 to 12).
        $MT = date("F", $timestamp);    // A full textual representation of a month (January through December).
        $DD = date("d", $timestamp);    // The day of the month with leading zeros (01 to 31).
        $hh = date("H", $timestamp);    // 24-hour format of an hour (00 to 23).
        $mm = date("i", $timestamp);    // Minutes with leading zeros (00 to 59).
        $ss = date("s", $timestamp);    // Seconds, with leading zeros (00 to 59).
        $output = [
            "status" => "error",
            "message" => "User id is required.",
            "data" => ""
        ];

        if ($user_id)
        {
            $advertiser_ids = $this->getAdvertiserIds(trim($user_id));

            if(count($advertiser_ids) > 0)
            {
                $advertiser_ids = '['.implode(',', $advertiser_ids).']';

                if($range_last_month) {
                    $json_request = $this->load->view("json/network_analytics_last_month", ['advertiser_ids'=>$advertiser_ids], true);
                }
                else {
                    $json_request = $this->load->view("json/network_analytics", ['advertiser_ids'=>$advertiser_ids], true);
                }
                
                $response = $this->apnx->requestPost("report", $json_request);
                $response_body = json_decode($response['body'], true);
            
                if (!$response_body)
                {
                    $output = [
                        "status" => "error",
                        "message" => "Unknown error while getting analytics report.",
                        "data" => ""
                    ];
                }
                else if (isset($response_body['response']['error']))
                {
                    $error = $response_body['response']['error'];
                    $output = [
                        "status" => "error",
                        "message" => $error,
                        "data" => ""
                    ];
                }
                else
                {
                    $report_id = $response_body['response']['report_id'];
                    $report_csv = $this->apnx->getReport($report_id);

                    $report_csv = $report_csv['body'];
                    $report_array = explode("\n", $report_csv);
                    $header = array_shift($report_array);

                    $data = [
                        "google" => [
                            "media_cost" => 0,
                            "imps" => 0
                        ],
                        "yahoo" => [
                            "media_cost" => 0,
                            "imps" => 0
                        ],
                        "others" => [
                            "media_cost" => 0,
                            "imps" => 0
                        ],
                        "all" => [
                            "media_cost" => 0,
                            "imps" => 0
                        ]
                    ];
                    $date = [
                        "YYYY" => $YYYY,
                        "MM" => $MM,
                        "MT" => $MT,
                        "DD" => $DD,
                        "hh" => $hh,
                        "mm" => $mm,
                        "ss" => $ss
                    ];
                    
                    // Filter, group according to seller
                    foreach ($report_array as $csv)
                    {
                        $csv = trim($csv);
                        if(!empty($csv))
                        {
                            $csv_line_pieces = str_getcsv($csv);
                            if(count($csv_line_pieces) == 4)
                            {
                                $member_name = $csv_line_pieces[0];
                                $member_id = $csv_line_pieces[1];
                                $media_cost = $csv_line_pieces[2];
                                $imps = $csv_line_pieces[3];

                                // All
                                $data['all']['media_cost'] += $media_cost;
                                $data['all']['imps'] += $imps;

                                // Google
                                if ($member_id == 181)
                                {
                                    $data['google']['media_cost'] += $media_cost;
                                    $data['google']['imps'] += $imps;
                                }
                                // Yahoo
                                elseif ($member_id == 273)
                                {
                                    $data['yahoo']['media_cost'] += $media_cost;
                                    $data['yahoo']['imps'] += $imps;
                                }
                                // Others
                                else
                                {
                                    $data['others']['media_cost'] += $media_cost;
                                    $data['others']['imps'] += $imps;
                                }
                            } 
                        }
                    }

                    $output = [
                        "status" => "ok",
                        "message" => "Report retrieved successfully.",
                        "data" => $data,
                        "date" => $date
                    ];
                }
            }
            else
            {
                $output['message'] = "No associated advertisers for this user.";
            }
        }

        // Response.
        if($plain_output)
        {
            header("Content-Type: text/plain");
            print_r($output);
        }
        else
        {
            header("Content-Type: application/json");
            echo json_encode($output);
        }
    }

    public function getCostCreativeAudit($apnx_id = null)
    {
        $timestamp = time();
        $YYYY = date("Y", $timestamp);  // A four digit representation of a year.
        $MM = date("m", $timestamp);    // A numeric representation of a month, with leading zeros (01 to 12).
        $MT = date("F", $timestamp);    // A full textual representation of a month (January through December).
        $DD = date("d", $timestamp);    // The day of the month with leading zeros (01 to 31).
        $hh = date("H", $timestamp);    // 24-hour format of an hour (00 to 23).
        $mm = date("i", $timestamp);    // Minutes with leading zeros (00 to 59).
        $ss = date("s", $timestamp);    // Seconds, with leading zeros (00 to 59).

        $json_request = $this->load->view("json/json_create_audit_cost", "", true);
        $response = $this->apnx->requestPost("report", $json_request);
        $response = json_decode($response['body'], true);
        
        if (isset($response['response']['error']))
        {
            $error = $response['response']['error'];
            $output = [
                "status" => "error",
                "message" => $error,
                "data" => ""
            ];
        }
        else
        {
            $report_id = $response['response']['report_id'];
            $report_csv = $this->apnx->getReport($report_id);
            $report_csv = $report_csv['body'];
            $report_array = explode("\n", $report_csv);
            $header = array_shift($report_array);
            array_pop($report_array);
            $output = [
                "status" => "ok",
                "message" => "Report retrieved successfully.",
                "data" => ""
            ];
            $date = [
                "YYYY" => $YYYY,
                "MM" => $MM,
                "MT" => $MT,
                "DD" => $DD,
                "hh" => $hh,
                "mm" => $mm,
                "ss" => $ss
            ];
            // Filter data.
            $data = [];
            foreach ($report_array as $csv)
            {
                $csv_string = str_getcsv($csv);
                $adv_id = $csv_string[0];
                $adv_name = $csv_string[1];
                $aud_count = $csv_string[2];
                $aud_fee = $csv_string[3];
                $data[] = [
                    "adv_id" => $adv_id,
                    "adv_name" => $adv_name,
                    "aud_count" => $aud_count,
                    "aud_fee" => number_format($aud_fee,2)
                ];
            }

            $output['status'] = "ok";
            $output['data'] = $data;
            $output['date'] = $date;
        }

        header("Content-Type: application/json");
        echo json_encode($output);
    }

    public function getBuyingClients()
    {
        $response = $this->apnx->requestGet("user?user_type=member_advertiser&state=active");
        $data = json_decode($response['body'],true);
        if(isset($data['response']['error']))
        {
            $output = [
                'status' => 'error',
                'message' => $data['response']['error']
            ];
            $output = json_encode($output);
            header("Content-Type: application/json");
            die($output);
        }
        else
        {
            $clients = [];
            $users = $data['response']['users'];
            foreach ($users as $user) {
                $clients[] = [
                    "id" => $user['id'],
                    "first_name" => $user['first_name'],
                    "last_name" => $user['last_name'],
                    "user_name" => $user['username'],
                    "advertisers" => $user['advertiser_access']
                ];
            }
            header("Content-Type: text/plain");
            print_r($clients);
        }
    }

    public function getAdvertisers($user_id=null)
    {
        $plain_output = $this->input->get('plain');
        if($user_id)
        {
            $response = $this->apnx->requestGet("user?id={$user_id}");
            $data = json_decode($response['body'],true);
            if(isset($data['response']['error']))
            {
                $output = [
                    'status' => 'error',
                    'message' => $data['response']['error']
                ];
                $output = json_encode($output);
                header("Content-Type: application/json");
                die($output);
            }
            else
            {
                $users = $data['response']['user']['advertiser_access'];
                if($plain_output)
                {
                    header("Content-Type: text/plain");
                    print_r($users);
                }
                else
                {
                    return $users;
                }
            }
        }
    }

    public function getAdvertiserIds($user_id=null)
    {
        $plain_output = $this->input->get('plain');
        if($user_id)
        {
            $response = $this->apnx->requestGet("user?id={$user_id}");
            $data = json_decode($response['body'],true);
            if(isset($data['response']['error']))
            {
                $output = [
                    'status' => 'error',
                    'message' => $data['response']['error']
                ];
                $output = json_encode($output);
                header("Content-Type: application/json");
                die($output);
            }
            else
            {
                $users = $data['response']['user']['advertiser_access'];
                $user_ids = [];

                foreach($users as $user)
                {
                    $user_ids[] = $user['id'];
                }

                return $user_ids;
            }
        }
    }

    public function test($file=null)
    {
        echo $file;
    }
}