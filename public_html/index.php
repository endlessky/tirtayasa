<?php
include '../etc/utils.php';
include '../etc/constants.php';

init_mysql ();

// Set locale and validate
$locale = retrieve_from_get ( $proto_locale, false );
if (is_null($locale)) {
	if (isset($_COOKIE[$proto_locale])) {
		$locale = validateLocale($_COOKIE[$proto_locale]);
	} else {
		$locale = $proto_locale_english;
	}
} else {
	// Locale is enforced, save it to cookie.
	$locale = validateLocale($locale);
	setcookie($proto_locale, $locale, time() + $cookie_expiry);
}
include "../etc/locale/tirtayasa_$locale.php";

// Set region and validate
$region = retrieve_from_get ( $proto_region, false );
if (is_null($region)) {
	if (isset($_COOKIE[$proto_region])) {
		$region = validateRegion($_COOKIE[$proto_region]);
	} else {
		$region = $proto_region_bandung;
	}
} else {
	// region is enforced, save it to cookie.
	$region = validateRegion($region);
	setcookie($proto_region, $region, time() + $cookie_expiry);
}

// Retrieve start/finish information
foreach (array ($proto_routestart,	$proto_routefinish) as $endpoint) {
	$startfinish = retrieve_from_get ($endpoint, false);
	if (!is_null($startfinish)) {
		if (preg_match('/^(.+)\\/(-?[0-9.]+,-?[0-9.]+)$/', $startfinish, $matches)) {
			$textual_endpoint [$endpoint] = $matches[1];
			$coordinate_endpoint [$endpoint] = $matches[2];
		} else {
			$textual_endpoint [$endpoint] = $startfinish;
			$coordinate_endpoint [$endpoint] = null;
		}
	} else {
		$textual_endpoint [$endpoint] = null;
		$coordinate_endpoint [$endpoint] = null;
	}
}

// Set custom images
$logo = null;
if (file_exists ( "images/logo$apikey.png" )) {
	$logo = "images/logo$apikey.png";
}
foreach (array ($proto_routestart,	$proto_routefinish) as $endpoint) {
	if (file_exists("images/$endpoint$apikey.png")) {
		$markerImage[$endpoint] = "widget/images/$endpoint$apikey.png";
	} else {
		$markerImage[$endpoint] = "widget/images/$endpoint.png";
	}
}

// Retrieve start/finish information
foreach (array ($proto_routestart,	$proto_routefinish) as $endpoint) {
	$startfinish = retrieve_from_get ($endpoint, false);
	if (!is_null($startfinish)) {
		if (preg_match('/^(.+)\\/(-?[0-9.]+,-?[0-9.]+)$/', $startfinish, $matches)) {
			$textual_endpoint [$endpoint] = $matches[1];
			$coordinate_endpoint [$endpoint] = $matches[2];
		} else {
			$textual_endpoint [$endpoint] = $startfinish;
			$coordinate_endpoint [$endpoint] = null;
		}
	} else {
		$textual_endpoint [$endpoint] = null;
		$coordinate_endpoint [$endpoint] = null;
	}
}

deinit_mysql ();
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<title>KIRI</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description"
	content="KIRI helps people travelling by means of public transport. Therefore saving both money and the environment at the same time. Tell us where you are and where to go, and we will tell you the transports you need to take." />
<meta name="keywords" content="angkot, navigation, go green, travel" />
<meta name="author" content="Project Kiri (KIRI)" />
<meta name="google-site-verification"
	content="9AtqvB-LWohGnboiTyhtZUXAEcOql9B-8lDjo_wcUew" />
<link rel="stylesheet" href="foundation/css/foundation.css" />
<link rel="stylesheet" href="css/styleIndex.css" />
<script src="foundation/js/vendor/modernizr.js"></script>
</head>
<body>
	<div class="row">
		<div id="controlpanel" class="large-3 large-push-9 columns">
			<div class="row center">
				<img src="images/kiri200.png" alt="KIRI logo"/>
			</div>
			<!-- TODO remove after one month -->
			<div class="row">
				<div class="large-12 columns">
					<div data-alert class="alert-box secondary round" id="newversionalert">
					<?php
						if ($locale == $proto_locale_indonesia) {
							print 'Tampilan baru KIRI! Berikan <a href="/static/feedback.php?locale=id">masukan</a> atau kembali ke <a href="http://classic.kiri.travel">versi klasik</a>.';
						} else {
							print 'Welcome to new KIRI! Give us <a href="/static/feedback.php">feedback</a> or revert to <a href="http://classic.kiri.travel">classic</a>.';
						} 
					?>
						<a href="#" class="close">&times;</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="small-5 columns">
					<select class="fullwidth" id="regionselect">
<?php
	foreach ($regioninfos as $key => $value) {
		print "<option value=\"$key\"";
		if ($key == $region) {
			print " selected";
		}
		print ">" . $value['name'] . "</option>\n";
	}
?>
					</select>
				</div>
				<div class="small-7 columns">
					<select class="fullwidth" id="localeselect">
						<option value="en">English</option>
						<option value="id"
							<?php if ($locale == $proto_locale_indonesia) print " selected"; ?>>Bahasa
							Indonesia</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="small-2 columns">
					<label for="startInput" class="inline"><?php print $index_from; ?></label>
				</div>
				<div class="small-10 columns">
					<input type="text" id="startInput" value=""
						placeholder="<?php print $index_placeholder_start; ?>">
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<select id="startSelect" class="hidden"></select>
				</div>
			</div>
			<div class="row">
				<div class="small-2 columns">
					<label for="finishInput" class="inline"><?php print $index_to; ?></label>
				</div>
				<div class="small-10 columns">
					<input type="text" id="finishInput" value=""
						placeholder="<?php print $index_placeholder_finish; ?>">
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<select id="finishSelect" class="hidden"></select>
				</div>
			</div>
			<div class="row">
				<div class="small-6 columns">
					<a href="#" class="small button expand" id="findbutton"><strong><?php print $widget_find; ?></strong></a>
				</div>
				<div class="small-3 columns">
					<a href="#" class="small button secondary expand" id="swapbutton"><img
						src="images/swap.png" alt="swap"></a>
				</div>
				<div class="small-3 columns">
					<a href="#" class="small button secondary expand" id="resetbutton"><img
						src="images/reset.png" alt="reset"></a>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns" id="routingresults">
					<div id="results-section-container"></div>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<footer>
					<?php 
						print "<a href=\"static/apps.php?locale=$locale\">$index_apps</a> | ";
						print "<a href=\"http://classic.kiri.travel\">$index_old_version</a><br/>\n";
						print "<a href=\"static/legal.php?locale=$locale\">$index_legal</a> | \n";
						print "<a href=\"static/feedback.php?locale=$locale\">$index_feedback</a> | \n";
						print "<a href=\"static/about.php?locale=$locale\">$index_about_kiri</a>\n";
					?>
					</footer>
					&nbsp;
				</div>
			</div>
		</div>
		<div id="map" class="large-9 large-pull-3 columns"></div>
	</div>
	<script src="foundation/js/vendor/jquery.js"></script>
	<script src="foundation/js/vendor/fastclick.js"></script>
	<script src="foundation/js/foundation.min.js"></script>
	<script src="foundation/js/foundation/foundation.alert.js"></script>
	<script src="http://maps.google.com/maps/api/js?v=3&sensor=false"></script>
	<script src="openlayers/OpenLayers.js"></script>
	<script>
	var input_text = [], coordinates = [];
	<?php
		foreach (array ($proto_routestart,	$proto_routefinish) as $endpoint) {
			print "input_text['$endpoint'] = " . (is_null($textual_endpoint[$endpoint]) ? 'null' : "'$textual_endpoint[$endpoint]'") . ";\n";
			print "coordinates['$endpoint'] = " . (is_null($coordinate_endpoint[$endpoint]) ? 'null' : "'$coordinate_endpoint[$endpoint]'") . ";\n";
		}
		print "var locale='$locale';\n";
		print "var region='$region';\n";
		print "var messageConnectionError = '$index_connectionerror';\n";
		print "var messageFillBoth = '$index_fillboth';\n";
		print "var messageNotFound = '$index__notfound';\n";
		print "var messageOops = '$index_oops';\n";
		print "var messageOrderTicketHere = '$index_order_ticket_here';\n";
		print "var messagePleaseWait = '<img src=\"images/loading.gif\" alt=\"... \"/> ' + '$index_pleasewait';\n";
		print "var messageITake = '$index_itake';\n";
				
		print "var region = '$region';\n";
		print "var regions = {\n";
		foreach ($regioninfos as $key => $value) {
			print "$key: {center: '" . $value['lat'] . "," . $value['lon'] . "', zoom: " . $value['zoom'] ."},\n";
		}
		print "};\n";
	?>
		$(document).foundation();    
	</script>
	<script src="js/newprotocol.js"></script>
	<script src="js/index.php.js"></script>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-36656575-2', 'kiri.travel');
  ga('send', 'pageview');

	</script>
</body>
</html>
