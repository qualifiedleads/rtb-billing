<?php
/*
	This "authenticate()" function ensures token is renewed before expiration is reached.
*/

$attempts_max = 10;
$attempts_count = 0;

function authenticate() {
	global $config, $attempts_max, $attempts_count, $_TOKEN, $_TOKEN_TIME, $_STATUS_CLEAR, $_STATUS_ERROR, $_REMAINING_TIME;

	$token = file($config['token_file']); // Source of token ID and expiration (seconds).
	$login = file($config['user_data']); // Source of login credentials.

	/* Session Logic */
	if(isset($token[0], $token[1])) { # Make sure token exist.
		$_TOKEN = trim($token[0]);
		$_TOKEN_TIME = trim($token[1]);

		if(time() > ($_TOKEN_TIME - $config['renew_threshold'])) { # If token is about to expire.
			$login_status = login($login[0],$login[1]); # Login result.
			if($login_status != "success") {
				if($attempts_count < $attempts_max) {
					$attempts_count++;
					$_STATUS_ERROR = "Login 1 failed.";
					sleep(2);
					authenticate();
				}
				else {
					$error_text = date(DATE_COOKIE)."\n".$_STATUS_ERROR."\n";
					$error_file = $config['error_logs'];
					file_put_contents($error_file, $error_text, FILE_APPEND);
				}
			}
		}
		else { # If token is still usable set main variables.
			$_STATUS_CLEAR = true;
			$_STATUS_ERROR = "No errors. Token is good.";
			$_REMAINING_TIME = round(($_TOKEN_TIME - time()) / 60);
		}
	}
	else { # Login to aquire new token.
		login($login[0],$login[1]);
	}
}
