<?php
/*
	Basic configuation file.
*/
$config = array(
	"token_file" => "private/token",
	"user_data" => "private/user",
	"error_logs" => "logs/errors.txt",
	"api_base" => "http://api.appnexus.com",
	"expire_duration" => 7200, // 2 hrs.
	"renew_threshold" =>  300, // 5 min.
	"datetime_format" => 'Y-m-d H:i:s T'
);