<?php
	// Global debugging configuration
	error_reporting(E_ERROR | E_COMPILE_ERROR | E_COMPILE_WARNING);
	/** experimental flag. */
	$enable_experimental = false;
	
	/** hostname for mysql server */
	$config_mysql_host = 'localhost';
	/** username for mysql account */
	$config_mysql_username = 'tirtayasa';   
	/** password for the mysql account */
	$config_mysql_password = 'tirtayasa';
	/** database name that stores the tables */
	$config_mysql_database = 'tirtayasa';

	/** API key for main website. */
	$apikey_kiri = 'E5D9904F0A8B4F99';

	// Cache types and expiry
	$cache_geocoding = 'geocoding';
	$cache_expiry_geocoding_mysql = '6 MONTH';
	$cache_searchplace = 'searchplace';
	$cache_expiry_searchplace_mysql = '1 MONTH';
	
	/** Cookie expiry time. */
	$cookie_expiry = 3600 * 24 * 365;
		
	/** MySQL interval for session expiry. */
	$session_expiry_interval_mysql = '6 HOUR';
	/** Unix time interval for session expiry (seconds). */
	$session_expiry_interval_unix = 6 * 3600;
	/** Major customer's timezone in seconds, currently points to Bandung (GMT+7) */
	$timezone_offset = 7 * 60 * 60;
	
	/** Number of decimal digits for lat/lon. */ 
	$latlon_precision = 5;
	
	/** maximum uploaded file size */
	$max_filesize = 100 * 1024;
	
	/** The file to log error report. */
	$errorlog_file = "../log/error.log";
	/** The path for bukitjarian image uploads. */
	$fileupload_path = "./images/uploads";
	/** The path for storing image ads in bukitjarian. */
	$adsimage_path = "images/ads";
	/** Extension for storing the ad images. */
	$adsimage_extension = image_type_to_extension(IMAGETYPE_JPEG); 

	/** The menjangan server URL. */
	// $menjangan_url = "http://49.50.9.135:8080"; // MWN
	// $menjangan_url = "http://localhost:8000"; // localhost
	$menjangan_url = "http://180.250.80.194:8000"; // Telkom Sigma
	/** Timeout for waiting response from menjangan server, in seconds. */
	$menjangan_timeout = 3;
	/** Maximum size of response from external request acceptable by the script. */ 
	$maximum_http_response_size = 102400;
	
	/** The API key for Google Maps. Useful for place search. */
	$gmaps_api_key = 'AIzaSyAmSzop-RSfWX467YfxZOoZL5DZiNaga_g';
	/** Client id to be provided for 4sq. */
	$foursq_client_id = 'EXFO1JBVNDPUI2N0WAAY0UIM5ILL5INQD13GFB4NSJ45KUQS';
	/** Client secret for 4sq. */
	$foursq_client_secret = 'TYUQSXW2PPSXFOC1L0JKHOVHAKZWRKY5P0ECXMLPPHBMI13Y';
	/** URL for Places Search web service. */
//  pascal: switch to use foursquare
// 	$places_url = 'https://api.foursquare.com/v2/venues/search';
	$places_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
	/** Maximum number of search result. */
	$search_maxresult = 10;
	/** API key for server side apps. */
	$gmaps_server_key = 'AIzaSyCWO4THwk5456oaHD1J3Lh_t-LiISRrG_s';
	/** URL for Google Maps' Reverse geocoding web service. */
	$gmaps_geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json';
	
	$means_image_extension = '.png';
	
	/** Average speed of walking, in km/h. */
	$speed_walk = 5;
	
	/** Maximum length of user id input. */
	$maximum_userid_length = 128;
	/** Maximum length of password input. */
	$maximum_password_length = 32;	
	/** The number of times hash will be done, final value will be 2^n. */
	$passwordhash_cost_log2 = 8;
	/** Allow hash function be portable (work in older system but less secured. */
	$passwordhash_portable = FALSE;
	/** Banner width in pixel. */
	$banner_width = 125;
	/** Banner height in pixel. */
	$banner_height = 50;
	
	/** Slots available for platinum ad. */
	$platinumad_slots = 4;
	/** Time span of platinum ad slot. It is currently one hour. */
	$platinumad_slotlength = 3600;
	
	// CicaheumLedeng protocol constants
	$proto_address = 'address';
	$proto_apikey = 'apikey';
	$proto_apikeys_list = 'apikeyslist';
	$proto_attributions = 'attributions';	
	$proto_company = 'company';
	$proto_description = 'description';
	$proto_domainfilter = 'domainfilter';
	$proto_errorcode = 'errorcode';
	$proto_filename = 'filename';
	$proto_fqcategoryid = 'fqcategoryid';
	$proto_fullname = 'fullname';
	$proto_geodata = 'geodata';
	$proto_internalinfo = 'internalinfo';
	$proto_locale = 'locale';
	$proto_locale_indonesia = 'id';
	$proto_locale_english = 'en';
	$proto_location = 'location';
	$proto_message = 'message';
	$proto_mode = 'mode';
	$proto_mode_add_track = 'addtrack';
	$proto_mode_add_apikey = 'addapikey';
	$proto_mode_cleargeodata = 'cleargeodata';
	$proto_mode_delete_place = 'deleteplace';
	$proto_mode_delete_track = 'deletetrack';
	$proto_mode_findroute = 'findroute';
	$proto_mode_getdetails_track = 'getdetailstrack';
	$proto_mode_getprofile = 'getprofile';
	$proto_mode_importkml = 'importkml';
	$proto_mode_list_apikeys = 'listapikeys';
	$proto_mode_list_places = 'listplaces';
	$proto_mode_list_tracks = 'listtracks';
	$proto_mode_login = 'login';
	$proto_mode_logout = 'logout';
	$proto_mode_register = 'register';
	$proto_mode_reporterror = 'reporterror';
	$proto_mode_search = 'searchplace';
	$proto_mode_update_apikey = 'updateapikey';
	$proto_mode_update_profile = 'updateprofile';
	$proto_mode_update_track = 'updatetrack';
	$proto_mode_nearbytransports = 'nearbytransports';
	$proto_nearbytransports = 'nearbytransports';
	$proto_new_trackid = 'newtrackid';
	$proto_orderid = 'orderid';
	$proto_password = 'password';
	$proto_pathloop = 'loop';
	$proto_penalty = 'penalty';
	$proto_phonenumber = 'phonenumber';
	$proto_placename = 'placename';
	$proto_placeslistresult = 'placeslistresult';
	$proto_presentation = 'presentation';
	$proto_presentation_desktop = 'desktop';
	$proto_presentation_mobile = 'mobile';
	$proto_privilege_apiUsage = 'apiusage';
	$proto_privilege_route = 'route';
	$proto_privileges = 'privileges';
	$proto_querystring = 'querystring';
	$proto_radius = 'radius';
	$proto_rating = 'rating';
	$proto_region = 'region';
	$proto_region_bandung = 'bdo';
	$proto_region_jakarta = 'cgk';
	$proto_region_surabaya = 'sub';
	$proto_routefinish = 'finish';
	$proto_routestart = 'start';
	$proto_routingresult = 'routingresult';
	$proto_routingresults = 'routingresults';
	$proto_search_querystring = 'querystring';
	$proto_search_result = 'searchresult';
	$proto_sessionid = 'sessionid';
	$proto_status = 'status';
	$proto_status_credentialfail = 'credentialfail';
	$proto_status_error = 'error';
	$proto_status_ok = 'ok';
	$proto_status_sessionexpired = 'sessionexpired';
	$proto_steps = 'steps';
	$proto_time = 'time';
	$proto_trackid = 'trackid';
	$proto_trackname = 'trackname';
	$proto_tracktype = 'tracktype';
	$proto_trackslist = 'trackslist';
	$proto_tracktypeslist = 'tracktypeslist';
	$proto_transfernodes = 'transfernodes';
	$proto_traveltime = 'traveltime';
	$proto_updateprofile = 'updateprofile';
	$proto_uploadedfile = 'uploadedfile';
	$proto_url = 'url';
	$proto_userid = 'userid';
	$proto_venuedetailsref = 'venuedetailsref';
	$proto_venuephotoref = 'venuephotoref';
	$proto_venueid = 'venueid';
	$proto_venues = 'venues';
	$proto_verifier = 'verifier';
	$proto_version = 'version';
	$proto_width = 'width';
	// KalapaDago protocol constants
	$protokd_point_finish = 'finish';
	$protokd_point_start = 'start';
	$protokd_result_none = 'none';
	$protokd_transitmode_walk = 'walk';
	$protokd_maximumwalk = 'mw';
	$protokd_walkingmultiplier = 'wm';
	$protokd_penaltytransfer = 'pt';
	
	/** Different parameters for routing alternatives. */
	$alternatives = array(
			/* Normal */
			array(
					$protokd_maximumwalk => 0.75,
					$protokd_walkingmultiplier => 1,
					$protokd_penaltytransfer => 0.15
			),
			/* Prefer walking */
			array(
					$protokd_maximumwalk => 1,
					$protokd_walkingmultiplier => 0.75,
					$protokd_penaltytransfer => 0.15
			),
			/* Avoid transfers */
			array(
					$protokd_maximumwalk => 0.75,
					$protokd_walkingmultiplier => 1,
					$protokd_penaltytransfer => 0.45
			),
	);

	/** Different parameters for different regions. */
	$regioninfos = array(
		$proto_region_bandung => array(
			'lat' => -6.91474,
			'lon' => 107.60981,
			'radius' => 17000,
			'zoom' => 12,
			'searchplace_regex' => ', *(bandung|bdg)$',
			'name' => 'Bandung'
		),
		$proto_region_jakarta => array(
			'lat' => -6.21154,
			'lon' => 106.84517,
			'radius' => 15000,
			'zoom' => 10,
			'searchplace_regex' => ', *(jakarta|jkt)$',
			'name' => 'Jakarta'
		),
		$proto_region_surabaya => array(
			'lat' => -7.27421,
			'lon' => 112.71908,
			'radius' => 15000,
			'zoom' => 12,
			'searchplace_regex' => ', *(surabaya|sby)$',
			'name' => 'Surabaya'
		)
	);

?>
