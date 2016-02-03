<?php
/*
 This file contains functions that handles API query resquests.
*/

// Login function.
function login($username,$password) {
	global $config, $_TOKEN, $_TOKEN_TIME, $_STATUS_CLEAR, $_STATUS_ERROR, $_REMAINING_TIME;

	$data = array("auth"=>array("username"=>trim($username),"password"=>trim($password)));

	$curl = curl_init($config['api_base']."/auth");
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($curl);
	$response_data = json_decode($response, true);

	if(isset($response_data['response']['token'])){
		$_TOKEN = $response_data['response']['token'];
		$_TOKEN_TIME = time() + $config['expire_duration'];
		$access = "{$_TOKEN}\n{$_TOKEN_TIME}";
		file_put_contents($config['token_file'], $access);
		$_STATUS_CLEAR = true;
		$_STATUS_ERROR = "";
		$_REMAINING_TIME = round(($_TOKEN_TIME - time()) / 60);
		return "success";
	}
	else{
		$_STATUS_ERROR = $response_data['response']['error'];
		return "failed";
	}
}

// Optional to get advertisers lists.
function get_advertisers() {
	global $config,$_TOKEN;
	$session = curl_init($config['api_base'].'/advertiser');
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($session, CURLOPT_HTTPHEADER, array("Authorization: {$_TOKEN}"));
	$response = curl_exec($session);
	echo "<pre>";
	print_r(json_decode($response));
}

// Optional untested function.
function make_creative_audit($advertiser_id) {

	// External variables.
	global $config, $_TOKEN, $_TOKEN_TIME, $_STATUS_CLEAR, $_STATUS_ERROR, $_REMAINING_TIME, $_REPORT_ID;

	// Endpoint.
	$api_url = $config['api_base'];

	// Request headers.
	$headers = array(
		'Authorization: '.$_TOKEN,
		'Content-Type: text/plain'
	);

	// Request body (JSON).
	$create_report_json = file_get_contents("json/creative_audit_report.json");
	$create_report_json = preg_replace('/{{member_id}}/', $advertiser_id, $create_report_json);

	// Set POST options.
	$service = '/report';
	$create = curl_init($api_url.$service);
	curl_setopt($create, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($create, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($create, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($create, CURLOPT_POSTFIELDS, $create_report_json);

	// Send POST request.
	$create_response = json_decode(curl_exec($create), true);

	// Check for errors.
	if(isset($create_response['response']['report_id'])) {
		$_REPORT_ID = $create_response['response']['report_id'];

		function get_report($api_url,$headers,$_REPORT_ID) {
			$check = curl_init($api_url."/report?id={$_REPORT_ID}");
			curl_setopt($check, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($check, CURLOPT_HTTPHEADER, $headers);
			$check_reponse = json_decode(curl_exec($check), true);

			if($check_reponse['response']['execution_status'] == "ready") {
				$get = curl_init($api_url."/report-download?id={$_REPORT_ID}");
				curl_setopt($get, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($get, CURLOPT_HTTPHEADER, $headers);
				$get_response = curl_exec($get);
				echo $get_response;
			}
			else {
				sleep(1);
				get_report($api_url,$headers,$_REPORT_ID);
			}
		}

		// Get the report.
		get_report($api_url,$headers,$_REPORT_ID);
	}
	else {
		$error = $create_response['response']['error'];
		//echo $error;
		print_r($create_response);
	}
}

// Outputs JSON formated billing report.
function make_billing_report($advertiser_id="") {

	// External variables.
	global $config, $_TOKEN, $_TOKEN_TIME, $_STATUS_CLEAR, $_STATUS_ERROR, $_REMAINING_TIME, $_REPORT_ID;

	// Endpoint.
	$api_url = $config['api_base'];

	// Request headers.
	$headers = array(
		'Authorization: '.$_TOKEN,
		'Content-Type: text/plain'
	);

	// Request body (JSON).
	$create_report_json = file_get_contents("json/billing_report.json");

	// Set POST options.
	$service = "/report";
	if(is_numeric($advertiser_id)){$service .= $service.'?advertiser_id='.$advertiser_id;}
	$create = curl_init($api_url.$service);
	curl_setopt($create, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($create, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($create, CURLOPT_CUSTOMREQUEST, "POST"); 
	curl_setopt($create, CURLOPT_POSTFIELDS, $create_report_json);

	// Send POST request.
	$create_response = json_decode(curl_exec($create), true);

	// Check for errors.
	if(isset($create_response['response']['report_id'])) {
		$_REPORT_ID = $create_response['response']['report_id'];

		function get_report($api_url,$headers,$_REPORT_ID) {
			$check = curl_init($api_url."/report?id={$_REPORT_ID}");
			curl_setopt($check, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($check, CURLOPT_HTTPHEADER, $headers);
			$check_reponse = json_decode(curl_exec($check), true);

			if($check_reponse['response']['execution_status'] == "ready") {
				//$date = $check_reponse['response']['report']['created_on'];
				$get = curl_init($api_url."/report-download?id={$_REPORT_ID}");
				curl_setopt($get, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($get, CURLOPT_HTTPHEADER, $headers);
				$get_response = curl_exec($get);
				$csv_entries = explode("\n", $get_response);
				$headers = array_shift($csv_entries);
				// Process data.
				$media_costs = array(
					"direct" => 0,
					"indirect" => 0
				);
				foreach($csv_entries as $line_entry){
					if(!empty($line_entry)){
						$csv_array = str_getcsv($line_entry);
						if($csv_array[1] == "181"){ // Cleared direct seller
							$media_costs['direct'] += $csv_array[2];
						}
						else {
							$media_costs['indirect'] += $csv_array[2];
						}
					}
				}
				echo json_encode($media_costs); // Output here.
			}
			else {
				sleep(1);
				get_report($api_url,$headers,$_REPORT_ID);
			}
		}

		// Get the report.
		get_report($api_url,$headers,$_REPORT_ID);
	}
	else {
		$error = $create_response['response']['error'];
		//echo $error;
		print_r($create_response);
	}
}