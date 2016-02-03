<?php

// API endpoint of reporting service.
$api_url = "http://api.appnexus.com/report";

// Necessary request headers.
$headers = array(
	"Authorization: hbapi:178820:56757c77582f3:nym2",
	'Content-Type: text/plain'
);

// Request body in array form.
$create_report_array = array(
    "report" => array(
        "format" => "csv",
        "report_interval" => "yesterday",
        "columns" => array(
            "day",
            "creative_id",
            "creative_name",
            "advertiser_name",
            "member_id",
            "audit_completion_date",
            "audit_type",
            "num_audits_completed",
            "total_audit_fee",
            "audit_completion_date"
        ),
        "report_type" => "completed_creative_audits"
    )
);

// Request body in JSON form.
$create_report_json = json_encode($create_report_array);

// Make a post request with required headers and body.
$session = curl_init($api_url);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($session, CURLOPT_POSTFIELDS, $create_report_json);
$create_response = json_decode(curl_exec($session), true);

// Output
print_r($create_response);