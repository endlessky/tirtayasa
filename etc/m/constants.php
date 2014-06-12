<?php
	// Protocols for communication between mobile web itself, called st.hall-ciumbuleuit
	/** GET parameter for start point typed by user. */
	$protocs_query_start = 'qs';
	/** GET parameter for finish point typed by user. */
	$protocs_query_finish = 'qf';
	/** GET parameter for region. */
	$protocs_region = 'r';
	/** GET parameter for start point as found in the place search. */
	$protocs_start = 's';
	/** GET parameter for finish point as found in the place search. */
	$protocs_finish = 'f';
	/** GET parameter for start location in latlng format as found in the place search. */
	$protocs_latlon_start = 'ls';
	/** GET parameter for finish location in latlng format as found in the place search. */
	$protocs_latlon_finish = 'lf';
	/** GET parameter to determine if submit button was pressed. */
	$protocs_submit = 'sb';
	/** GET parameter to determine the locale. */
	$protocs_locale = 'l';
	
	// Full site get parameters
	/** GET parameter for forcing full site. */
	$fsproto_forcefullsite = 'fullsite';
	/** GET parameter for start point. */
	$fsproto_start = 'start';
	/** GET parameter for finish point. */
	$fsproto_finish = 'finish';	
	
	/** Number of seconds from now when cookie is expired. */
	$cookie_expiry = 3600 * 24 * 365;
	
	/** Twitter share URL. */
	$twitter_share_url = 'https://twitter.com/share?text=%text&url=%url&via=kiriupdate';

	/** Regular expression matcher for lat/lng format. */
	$latlng_matcher = '/^-?[0-9.]+,-?[0-9.]+$/';
	
	/** API key for main website. */
	$apikey_kiri = 'E5D9904F0A8B4F99';
		
	// Protocol for handle.php
	$proto_apikey = 'apikey';
	$proto_location = 'location';
	$proto_locale = 'locale';
	$proto_message = 'message';
	$proto_mode = 'mode';
	$proto_mode_search = 'searchplace';
	$proto_mode_findroute = 'findroute';
	$proto_placename = 'placename';
	$proto_presentation = 'presentation';
	$proto_presentation_mobile = 'mobile';
	$proto_region = 'region';
	$proto_routestart = 'start';
	$proto_routefinish = 'finish';
	$proto_routingresults = 'routingresults';
	$proto_routingresult_means_walk = "walk";
	$proto_routingresult_means_none = "none";
	$proto_search_result = 'searchresult';
	$proto_search_querystring = 'querystring';
	$proto_status = 'status';
	$proto_status_ok = 'ok';
	$proto_status_error = 'error';
	$proto_steps = 'steps';
	$proto_traveltime = 'traveltime';
	$proto_version = 'version';
	
	/** Color defined for public transport track. */
	$color_publictransport = '0x008000';
	/** Color defined for walking. */
	$color_walk = '0xA00000';
	/** Color defined for board point. */
	$color_start = '0xFFD700';
	/** Color defined for alight point. */
	$color_finish = '0xFF7A00';
	/** Replacement for from icon token. */
	$from_icon = '<img src="../images/marker-from.png"/>'; 	
	/** Replacement for to icon token. */
	$to_icon = '<img src="../images/marker-to.png"/>';

	/** Permanent link for this service. */
	$kirimobile_permanent_link = 'http://kiri.travel/m';
	/** Host name for everything. */
	$tirtayasa_host = 'http://' . $_SERVER['SERVER_NAME'];
	/** URL for tirtayasa handler. */
	$tirtayasahandler_url = "$tirtayasa_host/handle.php";
	/** URL for tirtayasa mobile home. */
	$home_url = "$tirtayasa_host/m";
	/** URL for result page. */
	$result_url = "$home_url/r";
	/** URL for place selection page. */
	$selection_url = "$home_url/s";
	/** Google Static Map URL. */
	$staticmap_url = "http://maps.googleapis.com/maps/api/staticmap";
	/** Maximum size of response from external request acceptable by the script. */
	$maximum_http_response_size = 102400;
	/** The file to log error report. */
	$errorlog_file = "../../log/error.log";
	/** Maximum path points to be shown on map. */
	$maximum_path_points = 20;
	
?>