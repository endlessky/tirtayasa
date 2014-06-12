<?php
// Please use this template for the static pages.

// Header
$title['id'] = 'Legalitas';
$title['en'] = 'Legal';
include '../../etc/static.php';
$locale = validateLocale(retrieve_from_get($proto_locale, false));
printHeader($title[$locale], $locale);

if ($locale == 'id') {
// Content for Bahasa Indonesia.
?>
<p>Terima kasih Anda menggunakan Kiri. Mohon menyempatkan diri membaca
	beberapa poin ketentuan layanan kami.</p>
<ol>
	<li>KIRI menyediakan layanan untuk mencari rute transportasi publik Indonesia
		secara gratis. Hasil pencarian rute disediakan sebagaimana adanya, dan
		KIRI tidak bertanggung jawab atas segala kerugian yang mungkin
		ditimbulkan (walaupun begitu, kami menghargai jika Anda melaporkannya
		kepada kami melalui halaman <a href="feedback.php?locale=id">Masukan</a>).</li>
	<li>Anda tidak diperkenankan untuk melakukan kegiatan merusak, seperti
		<em>hacking</em>, <em>reverse engineering</em>, mendownload data
		secara masif dan/atau otomatis, dan hal-hal lainnya yang dilarang oleh
		hukum.
	</li>
	<li>Segala upaya untuk mereproduksi harus seizin tertulis dari Kiri,
		dengan pengecualian sebagai berikut: screenshot dan/atau print untuk
		keperluan pribadi, atau liputan mengenai produk Kiri.</li>
	<li>KIRI bermaksud untuk berterimakasih kepada layanan pihak ketiga:
		<ul>
			<li>Gambar Angkot oleh Bram Ton (<a href="http://www.b87.nl">http://www.b87.nl</a>)
			<li>Google Maps, Google Geocoding oleh Google, Inc
				(<a href="http://www.google.com">http://www.google.com</a>)
			<li>Foursquare (<a href="http://www.foursquare.com">http://www.foursquare.com</a>)
			<li>Bing Maps (<a href="http://www.bing.com/maps">http://www.bing.com/maps</a>)
			<li>Data trayek dari <a href="http://angkot.web.id">http://angkot.web.id</a> dengan lisensi <a href="http://opendatacommons.org/licenses/odbl/summary/">ODBL</a>
			<li>Supporter KIRI pada Wujudkan: anonym, Andrew Octaviano, fdauction, omgery, Patricia Susanto, Prima Rusdi, Rigel Centauri, Trinanti Sulamit
		</ul>
	</li>
</ol>
<?php
} else {
// Content for English (default)
?>
<p>Thank you for using KIRI. Please spend few minutes to read our terms
	of use.</p>
<ol>
	<li>KIRI provides a service to find a route of public transport
		in Indonesia for free. The result of routing is provided as is, and we
		are not responsible for any problems that may occur (however, we do
		appreciate if you feedback to us through <a href="feedback.php">this
			page</a>).
	</li>
	<li>You are not allowed to hack, reverse engineer, bulk and/or
		automatic download of data, and other actions forbidden by law.</li>
	<li>Any kind of reproduction of this website must be under written
		approval from KIRI, with the exception of: screenshot and/or printout
		for personal purpose, or a report of this product.</li>
	<li>KIRI would like to thank 3rd party entities:
		<ul>
			<li>Pictures of Angkot by Bram Ton (<a href="http://www.b87.nl">http://www.b87.nl</a>)
			<li>Google Maps, Google Places, Google Geocoding by Google, Inc (<a
				href="http://www.google.com">http://www.google.com</a>)
			<li>Foursquare (<a href="http://www.foursquare.com">http://www.foursquare.com</a>)
			<li>Bing Maps (<a href="http://www.bing.com/maps">http://www.bing.com/maps</a>)
			<li>Path data from <a href="http://angkot.web.id">http://angkot.web.id</a> with <a href="http://opendatacommons.org/licenses/odbl/summary/">ODBL</a> license
			<li>KIRI Supporters in Wujudkan: anonym, Andrew Octaviano, fdauction, omgery, Patricia Susanto, Prima Rusdi, Rigel Centauri, Trinanti Sulamit				
		</ul>
	</li>
</ol>


<?php
}

// Footer
printFooter($locale);
?>