<?php
require_once 'constants.php';
require_once 'PHPMailer/PHPMailerAutoload.php';

/** Determines whether we should return error message or log it */
$global_hush_hush = false;

/** Stores the mysql connection for the whole script. */
$global_mysqli_link = null;

/**
 * Initialize mysql connection with the default configuration as specified in
 * constants.php. This method will also exit and print error message in case of problem.
 */
function init_mysql() {
	global $global_mysqli_link, $config_mysql_host, $config_mysql_username, $config_mysql_password, $config_mysql_database;
	$global_mysqli_link = mysqli_connect($config_mysql_host, $config_mysql_username, $config_mysql_password, $config_mysql_database) or
		die_nice("MySQL Connect error: " . mysqli_connect_error(), $header_printed);
}

/**
 * Close mysql connection, with additional error reporting.
 */
function deinit_mysql() {
	global $global_mysqli_link;
	mysqli_close($global_mysqli_link) or
		die_nice("Failure in closing mysql connection. Your transaction may have been processed.");
}

/**
 * Increment the track version by 1. Normally called after successful insert/delete/update
 */
function update_trackversion() {
	global $global_mysqli_link;
	mysqli_query($global_mysqli_link, "UPDATE properties SET propertyvalue=propertyvalue+1 WHERE propertyname='trackversion'") or
	die_nice("Error updating track version: " . mysqli_error($global_mysqli_link));
}

/**
* Increment the places version by 1. Normally called after successful insert/delete/update
*/
function update_placesversion() {
	global $global_mysqli_link;
	mysqli_query($global_mysqli_link, "UPDATE properties SET propertyvalue=propertyvalue+1 WHERE propertyname='placesversion'") or
	die_nice("Error updating places version: " . mysqli_error($global_mysqli_link));
}

/**
 * Log statistic entry to database
 * @param unknown $verifier the API key or host name
 * @param unknown $type the event type, one of $type_xxx in constants.
 * @param unknown $additional_info additional information about the event
 */
function log_statistic($verifier, $type, $additional_info) {
	global $global_mysqli_link;
	mysqli_query($global_mysqli_link, "INSERT INTO statistics (verifier, type, additionalInfo) VALUES ('$verifier','$type','$additional_info')") or
		die_nice("Error updating statistic: " . mysqli_error($global_mysqli_link));
}

/**
 * Return JSON error message and quit nicely
 * @param string $message The error message to return
 * @param boolean $mysqlclose set true to close existing mysql connection
 */
function die_nice($message, $mysqlclose=false) {
	global $locale, $global_mysqli_link;
	global $proto_status, $proto_status_error, $proto_message, $global_hush_hush;
	if ($global_hush_hush) {
		log_error($message);
		// In case we have a localization
		if ($locale == "id") {
			$message = 'Mohon ampun, ada masalah internal. Programmer amatir! Tapi jangan khawatir, ia akan ditegur.';
		} else {
			$message = 'Sorry, there\'s internal error. Bad coder, but he\'ll be notified!';
		}
	}
	$json = array(
		$proto_status => $proto_status_error,
		$proto_message => $message);
	print(json_encode($json));
	if ($mysqlclose) {
		mysqli_close($global_mysqli_link);
	}
	exit(0);
}

/**
 * Checks if the api key is valid, currently checks with domain
 * @param string $apikey the api key to check
 * @return booleean true if API key is valid, false if it is not registered, or a string containing other error messages  
 */
function check_apikey_validity($apikey)
{
	global $global_mysqli_link;
	
	// check apikey: is this api key exist in database?
	$apisqlquery = mysqli_query ( $global_mysqli_link, "SELECT verifier, ipFilter, domainFilter FROM apikeys WHERE
			verifier = '$apikey'" ) or die_nice( "failed to execute query on Apikey check." );
	
	// if it exist, it must be found 1 row (because apiKeyOrDomain are unique)
	if ($fields = mysqli_fetch_array ( $apisqlquery )) {
		// check for ip filter
		if ($_SERVER ['REMOTE_ADDR'] != $fields['ipFilter'] && $fields['ipFilter'] != NULL) {
			return "Usage of this api key is forbidden from IP Address " . $_SERVER ['REMOTE_ADDR'];
		}
		// check domain filter
		if (!domain_matches($_SERVER['HTTP_REFERER'], $fields['domainFilter'])) {
			return "Usage of this api key is forbidden from referer " . $_SERVER['HTTP_REFERER'];
		}
	} else {
		return false;
	}
	return true;
}

/**
 * Returns true if domain matches the filter
 * @param unknown $url the domain name
 * @param unknown $filter domain name, may contain * filter
 */
function domain_matches($url, $filter) {
	$filter = str_replace('.', '\.', $filter);
	$filter = str_replace('*', '.*', $filter);
	$domain = is_null($url) ? '' : parse_url($url, PHP_URL_HOST);
	return preg_match("/^$filter\$/", $domain) > 0;
}

/**
 * Return JSON status of OK, optionally with a message. Exits
 * the execution to ensure there's no additional outputs. 
 * @param string $message Optional message to be passed
 */
function well_done($message = null) {
	global $proto_status, $proto_status_ok, $proto_message;
	$json = array(
		$proto_status => $proto_status_ok,
	);
	if ($message != null) {
		$json[$proto_message] = $message;
	}
	print(json_encode($json));
	exit(0);
}

/**
 * Perform initializations on the PHP script
 */
function start_working() {
	header('Content-Type: application/json');
	header('Cache-control: no-cache');
	header('Pragma: no-cache');
}

/**
 * Get a parameter from post method, or return an error if not available
 * @param string $param the parameter name from post or get
 * @param boolean $mandatory when true, script will return error if parameter is not found 
 */
function retrieve_from_post($param, $mandatory = true) {
	$value = is_null($_POST[$param]) ? $_GET[$param] : $_POST[$param];
	if ($mandatory && $value == null) {
		die_nice("Value of $param is expected but not found");
	}
	// TODO try urldecode it, but see the impact for mjnserve 
	return $value;
}

/**
 * Get a parameter from get method, or return an error if not available
 * @param string $param the parameter name from get
 * @param boolean $mandatory when true, script will return error if parameter is not found
 */
function retrieve_from_get($param, $mandatory = true) {
	$value = $_GET[$param];
	if ($mandatory && $value == null) {
		die_nice("Value of $param is expected but not found");
	}
	return $value;
}

/**
 * Log an error to the predefined file
 * @param int $messsage The error message
 * @param $errorlog_location the error file location if needed to change.
 */
function log_error($message, $errorlog_location = null) {
	global $errorlog_file, $global_hush_hush;
	if (is_null($errorlog_location)) {
		$errorlog_location = $errorlog_file;
	}
	$file = fopen($errorlog_location, "a");
	if ($file == NULL) {
		// Don't let be in infinite loop
		$global_hush_hush = false;
		die_nice("Internal fatal error. I couldn't tell the coder. Could you mail to pascalalfadian@live.com? Please...?");
	}
 	$time = strftime('%d-%b-%Y %H:%M:%S GMT', time());
 	$server = '';
 	foreach ($_SERVER as $key => $value) {
 		$server .= str_replace("\n", "\\n", "$key=>$value;");
 	}
 	$post = '';
 	foreach ($_GET as $key => $value) {
 		$post .= str_replace("\n", "\\n", "$key=>$value;");
 	}
	fwrite($file, "time=$time;message=$message;\$POST=$post\n");	
	fclose($file);
}

/**
 * Compute distance in kilometers, between two latlon points.
 * Taken from http://www.movable-type.co.uk/scripts/latlong.html
 * @param unknown_type $lat1 latitude of the 1st point
 * @param unknown_type $lon1 longitude of the 1st point
 * @param unknown_type $lat2 latitude of the 2nd point
 * @param unknown_type $lon2 longitude of the 2nd point
 */
function compute_distance($lat1, $lon1, $lat2, $lon2) {
	$earth_radius= 6371; // in kilometers
	$dLat = deg2rad($lat2-$lat1);
	$dLon = deg2rad($lon2-$lon1);
	$lat1 = deg2rad($lat1);
	$lat2 = deg2rad($lat2);
	
	$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
	return $earth_radius * $c;
}

/**
 * Generates a random session id.
 * @return string the session id generated
 */
function generate_sessionid() {
	return generate_random("abcdefghiklmnopqrstuvwxyz0123456789", 16);
}

function generate_apikey() {
	return generate_random("01234456789ABCDEF", 16);
}

function generate_password() {
	return generate_random("abcdefghiklmnopqrstuvwxyz0123456789", 8);
}

/**
 * Generates a random string
 * @param string $chars available characters
 * @param int $length size of the string
 * @return string the generated string
 */
function generate_random($chars, $length) {
	$chars_size = strlen($chars);
	$string = '';
	for ($i = 0; $i < $length; $i++) {
		$string .= $chars[rand(0, $chars_size)];
	}
	return $string;
}

/**
 * Converts SQL's LINESTRING() format into array of LatLng
 * @param string $lineString
 * @return array of "lat,lng"
 */
function lineStringToLatLngArray($lineString) {
	if (is_null($lineString)) {
		return null;
	}
	$lineString = preg_replace('/LINESTRING\(([^)]+)\)/', '$1', $lineString);
	$lnglatArray = split(',', $lineString);
	$returnValue = array();
	foreach ($lnglatArray as $lnglat) {
		list($lng,$lat) = split(' ', $lnglat);
		$returnValue[] = sprintf("%.$latlon_precision" . "f,%.$latlon_precision" . "f", $lat, $lng);
	}
	return $returnValue;
}

/**
 * Checks cache if there is a value stored with a given key
 * @param unknown $type cache type
 * @param unknown $key the cache key
 * @return the cache value, or null if cache miss
 */
function get_from_cache($type, $key) {
	global $global_mysqli_link;
	$result = mysqli_query($global_mysqli_link, "SELECT cacheValue FROM cache WHERE type='$type' AND cacheKey='$key'") or
		die_nice("Unable to retrieve from cache: " . mysqli_error($global_mysqli_link));
	if ($row = mysqli_fetch_row($result)) {
		return $row[0];
	} else {
		return null;
	}
}

/**
 * Put into cache, log warning if duplicate.
 * @param unknown $type the cache type
 * @param unknown $key the cache key
 * @param unknown $value cache value
 */
function put_to_cache($type, $key, $value) {
	global $global_mysqli_link;
	$value = mysqli_real_escape_string($global_mysqli_link, $value);
	$result = mysqli_query($global_mysqli_link, "INSERT INTO cache(type, cacheKey, cacheValue) VALUES('$type', '$key','$value')") or
		log_error("Warning: Can't store cache of $type/$key($value) " . mysqli_error($global_mysqli_link));
}

/**
 * Send password to recipient
 * @param unknown $email the recipient email
 * @param unknown $password password
 * @param unknown $fullname Full name of the recipient
 * @param unknown $debug_level 0 to 2 for more debug options
 */
function sendPassword($email, $password, $fullname, $debug_level = 0) {
	date_default_timezone_set ( 'Asia/Jakarta' );
	$mail = new PHPMailer ();
	$mail->isSMTP ();
	$mail->SMTPDebug = $debug_level;
	$mail->Host = "smtp-mail.outlook.com";
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Username = 'noreply@kiri.travel';
	$mail->Password = "f98*(J2dq";
	$mail->setFrom ( 'noreply@kiri.travel', 'KIRI Automated System' );
	$mail->addAddress ( $email, $fullname );
	$mail->addBCC('hello@kiri.travel');
	$mail->Subject = 'KIRI API Registration';
	$mail->msgHTML ( "<p>Hello $fullname,</p>" . "<p>Thank you for becoming KIRI Friends. Please find below your<br/>" . "initial password (8 characters of alphanumerics): <pre>$password</pre><br/>" . "Please login to our site and change your password immediately.</p>" . "<p>Sincerely yours,<br/>" . "Pascal & Budyanto</p>" );
	$mail->AltBody = "Hello $fullname,\n\n" . "Thank you for becoming KIRI Friends. Please find below your\n" . "initial password (8 characters of alphanumerics): $password\n" . "Please login to our site and change your password immediately.\n\n" . "Sincerely yours,\n" . "Pascal & Budyanto\n";

	// send the message, check for errors
	if (!$mail->send ()) {
		die_nice('Email error: ' . $mail->ErrorInfo);
	}
}

/**
 * Detect whether it is a mobile browser or not. Taken from http://detectmobilebrowsers.com/
 * @return boolean true if a mobile browser, false otherwise
 */
function isMobileBrowser() {
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
}

/**
 * Validates the passed locale, allowing only valid ones.
 * @param string $locale the locale to validate
 * @return the validated locale, or default to english if invalid
 */
function validateLocale($locale) {
	global $proto_locale, $proto_locale_english, $proto_locale_indonesia;
	switch ($locale) {
		case $proto_locale_indonesia:
			return $locale;
		default:
			return $proto_locale_english;
	}
}

/**
 * Validates the passed region, allowing only valid ones.
 * @param string $region the region to validate
 * @return the validated region, or default to Bandung if invalid
 */
function validateRegion($region) {
	global $proto_region, $proto_region_bandung, $regioninfos;
	if (isset($regioninfos[$region])) {
		return $region;
	} else {
		return $proto_region_bandung;
	}
}
?>