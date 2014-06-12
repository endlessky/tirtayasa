<?php
require_once("utils.php");
error_reporting(0);

// Template for static contents.

$nav['id'] = '<li><a href="/?locale=id">Kembali ke Awal</a></li>' .
	'<li><a href="legal.php?locale=id">Legalitas</a></li>' .
	'<li><a href="feedback.php?locale=id">Beri Masukan</a></li>' .
	'<li><a href="about.php?locale=id">Tentang KIRI</a></li>' .
	'<li><a href="apps.php?locale=id">Apl. Mobile</a></li>';
$nav['en'] = '<li><a href="/?locale=en">Home</a></li>' .
	'<li><a href="legal.php">Legal</a></li>' .
	'<li><a href="feedback.php">Feedback</a></li>' .
	'<li><a href="about.php">About KIRI</a></li>' .
	'<li><a href="apps.php">Mobile Apps</a></li>';

/**
 * 
 * Print the header, just before the content. Should include the opening <html> tag.
 * @param $title The title to show
 * @param $locale the validated locale
 */
function printHeader($title, $locale) {
	global $nav;
	?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>KIRI: <?php print $title; ?></title>
<link rel="stylesheet" href="../foundation/css/foundation.css" />
<script src="../foundation/js/vendor/modernizr.js"></script>
<script src="../foundation/js/vendor/custom.modernizr.js"></script>
<link rel="stylesheet" href="css/static.css">
	</head>
<body>
	<div class="row">
		<div class="large-12 columns">
			<nav class="top-bar" data-topbar>
				<ul class="title-area">
					<!-- Title Area -->
					<li class="name"></li>
					<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
					<li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
				</ul>
				<section class="top-bar-section">
					<ul class="left">
					<?php print $nav[$locale]; ?>
						<li class="has-dropdown"><a href="#">Language</a> 
							<ul class="dropdown">
								<li><a href="<?php print $_SERVER['PHP_SELF']; ?>">English</a></li>
								<li><a href="<?php print $_SERVER['PHP_SELF']; ?>?locale=id">Bahasa
										Indonesia</a></li>
							</ul></li> 
					</ul>
	               <ul class="right">
	                   	<li><a href="#"? style="font-size:10px;">Save the earth, use public transport!</a></li>
		            </ul> 	
					
				</section>
			</nav>
		</div>
	</div>
	<div class = "row">
		<div class = "large-3 large-push-9 columns">
			<img src ="../images/kiri200.png"/>
		</div>
		<div class = "large-9 large-pull-3 columns">
			<h1><?php print $title; ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
	<?php
}

/**
 * 
 * Print the footer, just after the content. Should include the opening <html> tag.
 * @param $locale the validated locale
 */
function printFooter($locale) {
	global $nav;
	?>
		</div>
	</div>

    <script src="../foundation/js/vendor/jquery.js"></script>
    <script src="../foundation/js/foundation.min.js"></script>
    <script>$(document).foundation();</script>
    
    
<!-- javascript for input analytics.google.com -->
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
<?php
}
?>
