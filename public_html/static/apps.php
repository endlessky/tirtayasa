<?php
// Please use this template for the static pages.

// Header
$title['id'] = 'KIRI App';
$title['en'] = 'KIRI Apps';
include '../../etc/static.php';
$locale = validateLocale(retrieve_from_get($proto_locale, false));
printHeader($title[$locale], $locale);

if ($locale == 'id') {
// Content for Bahasa Indonesia.
?>

<p>Bawa KIRI bersama Anda. Downloadlah app-app di bawah ini untuk
	smartphone Anda!</p>
<h2>App Ofisial</h2>
<p>Saat ini tersedia aplikasi untuk Android, paling baik dilihat di ponsel.</p>
<table>
	<thead>
		<tr>
			<th>Smart Public Transport</th>
		</tr>
		<tr>
			<td><a
				href="https://play.google.com/store/apps/details?id=travel.kiri.smarttransportapp">
					<img alt="Dapatkan di Google Play"
					src="images/getit_googleplay.png" />
			</a></td>
		</tr>
	</thead>
</table>
<h2>App Pihak Ketiga</h2>
<p>
	Beberapa app telah dibuat oleh pihak ketiga memanfaatkan <a
		href="https://dev.kiri.travel">KIRI API</a>:
<ul>
	<li><img class="textsize" src="images/android.png" alt="Android" /> <a
		href="https://play.google.com/store/apps/details?id=id.gits.angkot">Rute
			Angkot Bandung</a> oleh GITS Indonesia</li>
	<li><img class="textsize" src="images/iphone.png" alt="iPhone" /> <a
		href="https://itunes.apple.com/sg/app/rute/id709129004?mt=8">Rute</a>
		oleh Yohan Totting</li>
	<li><img class="textsize" src="images/blackberry.png" alt="Blackberry" />
		<a
		href="http://appworld.blackberry.com/webstore/content/47062893/?lang=en&countrycode=ID">KIRI</a>
		oleh Layang Layang Mobile</li>
	<li><img class="textsize" src="images/windowsphone.png"
		alt="Windows Phone" /> <a
		href="http://www.windowsphone.com/en-us/store/app/kiritravel/e6cce453-1c32-462e-88e8-f624207af180">KiriTravel</a>
		oleh Mohammad Dimas</li>
	<li><img class="textsize" src="images/windowsphone.png"
		alt="Windows Phone" /> <a
		href="http://www.windowsphone.com/en-us/store/app/naik-angkot/6ada52b9-01a2-475f-8121-8fa26bfe7510">Naik
			Angkot</a> oleh adhitya</li>
</ul>
<?php
} else {
// Content for English (default)
?>
<p>Take KIRI with you. Download these apps for your smartphones!</p>
<h2>Official Apps</h2>
<p>We currently support Android based device, best viewed in smartphones.</p>
<table>
	<thead>
		<tr>
			<th>Smart Public Transport</th>
		</tr>
		<tr>
			<td><a
				href="https://play.google.com/store/apps/details?id=travel.kiri.smarttransportapp">
					<img alt="Get it on Google Play"
					src="images/getit_googleplay.png" />
			</a></td>
		</tr>
	</thead>
</table>
<h2>3rd Party Apps</h2>
<p>
	These 3rd party apps have been developed using <a
		href="https://dev.kiri.travel">KIRI API</a>.
<ul>
	<li><img class="textsize" src="images/android.png" alt="Android" /> <a
		href="https://play.google.com/store/apps/details?id=id.gits.angkot">Rute
			Angkot Bandung</a> by GITS Indonesia</li>
	<li><img class="textsize" src="images/iphone.png" alt="iPhone" /> <a
		href="https://itunes.apple.com/sg/app/rute/id709129004?mt=8">Rute</a>
		by Yohan Totting</li>
	<li><img class="textsize" src="images/blackberry.png" alt="Blackberry" />
		<a
		href="http://appworld.blackberry.com/webstore/content/47062893/?lang=en&countrycode=ID">KIRI</a>
		by Layang Layang Mobile</li>
	<li><img class="textsize" src="images/windowsphone.png"
		alt="Windows Phone" /> <a
		href="http://www.windowsphone.com/en-us/store/app/kiritravel/e6cce453-1c32-462e-88e8-f624207af180">KiriTravel</a>
		by Mohammad Dimas</li>
	<li><img class="textsize" src="images/windowsphone.png"
		alt="Windows Phone" /> <a
		href="http://www.windowsphone.com/en-us/store/app/naik-angkot/6ada52b9-01a2-475f-8121-8fa26bfe7510">Naik
			Angkot</a> by adhitya</li>
</ul>
<?php
}

// Footer
printFooter($locale);
?>