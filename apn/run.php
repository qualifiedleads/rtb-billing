<?php
/* Initialize Variables */
$_TOKEN = ""; // Token variable.
$_TOKEN_TIME = 0; // Unix time in seconds to expire.
$_STATUS_CLEAR = false; // Login status.
$_STATUS_ERROR = "No authentication done."; // Authentication error placeholder.
$_REMAINING_TIME = 0; // Session life (in minutes).
$_REPORT_ID = "";

/* Essential Includes */
include_once "config.php";
include_once "functions.php";
include_once "auth.php";

authenticate();

// Make execution logs.
date_default_timezone_set('UTC');
$date_text = date($config['datetime_format'],time())."\n";
file_put_contents("logs/executions.txt", $date_text, FILE_APPEND);

// Execute report process.
if($_STATUS_CLEAR){
    if(isset($_GET['billing'])){
        if(is_numeric($_GET['billing'])){
            $advertiser_id = (Integer) $_GET['billing'];
        }
        else{
            $advertiser_id = "";
        }
        make_billing_report($advertiser_id);
    }
    if(isset($_GET['creative_audit'])){
        if(is_numeric($_GET['creative_audit'])){
            $advertiser_id = (Integer) $_GET['creative_audit'];
            make_creative_audit($advertiser_id);
        }
    }
}
else {
	echo "Not clear.";
	echo $_STATUS_ERROR;
}