<?php

// Disable error messages.
error_reporting(0);

/**
 * Log an error to the predefined file
 * @param int $messsage The error message
 */
function log_error($message) {
	global $errorlog_file;
	$file = fopen($errorlog_file, "a");
	if ($file == NULL) {
		// Try to reopen the file one directory deeper.
		$file = fopen("../" . $errorlog_file, "a");
		if ($file == NULL) {
			header("Content-type: text/html\n\n");
			print("Internal fatal error. I couldn't tell the coder. Could you mail to pascalalfadian@live.com? Please...?");
			exit(0);
		}
	}
 	$time = strftime('%d-%b-%Y %H:%M:%S GMT', time());
 	$server = '';
 	foreach ($_SERVER as $key => $value) {
 		$server .= str_replace("\n", "\\n", "$key=>$value;");
 	}
 	$post = '';
 	foreach ($_POST as $key => $value) {
 		$post .= str_replace("\n", "\\n", "$key=>$value;");
 	}
	fwrite($file, "time=$time;message=$message;\$SERVER=$server;\$POST=$post\n");	
	fclose($file);
}


/**
 * Print unexpected error message. It has to be called before any
 * output is given. In other words, you need to validate everything
 * before printing. TODO hide from end user.
 * @param string $message The error message to log
 */
function die_nice($message) {
	global $home_url;
	?>
<!doctype html>
<html>
<head>
<title>Uh-oh...</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>
	<p>
		Sorry, all work and no play made the coder a dull boy. There is an unexpected error, please <a
			href="<?php print $home_url; ?>">start over</a>.
	</p>
	<p><em>
		Maaf, bermain terus menerus membuat programmernya ceroboh. Ada kesalahan yang tidak diperkirakan, mohon <a
			href="<?php print $home_url; ?>">ulang dari awal</a>.
	</em></p>	
</body>
</html>
	<?php
	log_error($message);
	exit(0);
}

/**
 * Perform POST request and retrieve the result. TODO limit output?
 * Taken and modified from http://wezfurlong.org/blog/2006/nov/http-post-from-php-without-curl/
 * @param string $url URL to open
 * @param string $data the POST data
 * @param string $optional_headers optional headers
 * @return the result or FALSE if failed
 */
function do_post_request($url, $data, $optional_headers = null)
{
	$params = array('http' => array(
              'method' => 'POST',
              'content' => $data
	));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		return FALSE;
	}
	$response = @stream_get_contents($fp);
	return $response;
}

/**
* Get a parameter from get method, or return an error if not available
* @param string $param the parameter name from post
* @param boolean $mandatory when true, script will return error if parameter is not found
*/
function retrieve_from_get($param, $mandatory = true) {
	if (isset($_GET[$param])) {
		return urldecode($_GET[$param]);
	} else {
		if ($mandatory) {
			die_nice("Value of $param is expected but not found");			
		} else {
			return null;
		}
	}
}


/**
 * Log statistic entry to database
 * @param unknown $verifier the API key or host name
 * @param unknown $type the event type, one of $type_xxx in constants.
 * @param unknown $additional_info additional information about the event
 */
function log_statistic($verifier, $type, $additional_info) {
	require_once '../../etc/constants.php';
	$mysqli_link = mysqli_connect($config_mysql_host, $config_mysql_username, $config_mysql_password, $config_mysql_database) or
		die_nice("MySQL Connect error: " . mysqli_connect_error(), $header_printed);
	mysqli_query($mysqli_link, "INSERT INTO statistics (verifier, type, additionalInfo) VALUES ('$verifier','$type','$additional_info')") or
		die_nice("Error updating statistic: " . mysqli_error($mysqli_link));
	mysqli_close($mysqli_link) or
		die_nice("Failure in closing mysql connection. Your transaction may have been processed.");
}

?>