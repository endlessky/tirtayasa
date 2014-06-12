<?php
// Please use this template for the static pages.

// Header
$title['id'] = 'Tentang KIRI';
$title['en'] = 'About KIRI';
include '../../etc/static.php';
$locale = validateLocale(retrieve_from_get($proto_locale, false));
printHeader($title[$locale], $locale);

if ($locale == 'id') {
// Content for Bahasa Indonesia.
?>
<p class="socialmedia">
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fkiri.travel&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=408834732492486" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>
<a href="http://www.facebook.com/kiriupdate" target="_blank" title="Klik untuk temukan kami di Facebook"><img src="images/fb_id.png" alt="Temukan di Facebook"/></a>
<a href="https://twitter.com/kiriupdate" class="twitter-follow-button" data-show-count="false" data-lang="id">Ikuti @kiriupdate</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</p>
<p>Pemanasan global. Kemacetan. Harga BBM tinggi.</p>
<p>Tiga topik di atas adalah contoh masalah yang terjadi saat ini, yang
sebenarnya dapat diatasi oleh transportasi publik di Indonesia. Meskipun begitu
masih banyak yang tidak menggunakannya, sebagian karena tidak praktis
dibandingkan dengan kendaraan pribadi. Ironisnya, turis-turis yang datang
dari beberapa negara tetangga justru lebih memilih menggunakan transportasi publik,
karena lebih murah dan memberi kesempatan untuk berpetualang dan melihat
rupa-rupa kota Bandung.</p>
<p>Peran dari KIRI sangat sederhana: Anda beritahu kepada kami dari mana
dan ingin ke mana, kami beritahu caranya. Dengan transportasi publik. Tidak
peduli apakah Anda turis atau warga lokal yang ingin turut membantu mengurangi
masalah-masalah di atas.</p>

<h2>Kunjungi Kami</h2>
<iframe src="http://kiri.travel/widget?apikey=583B17FF404AC44&finish=Bandung+Digital+Valley/-6.87324,107.58682&locale=id" width=100% height="400"></iframe>

<h2>Penghargaan &amp; Asosiasi</h2>
<div id="footerAwards" class="row" style="text-align:center">	
	<div class="large-3 columns">
	<p>Finalis - Mandiri Young Technopreneur 2012<br/><img src="images/myt.jpg" alt="Mandiri Young Technopreneur"/></p>
	</div>
	<div class="large-3 columns">
	<p>Finalis - Blackberry Business Plan Competition 2012<br/><img src="images/bbic.png" alt="Blackberry Business Plan Competition"/></p>
	</div>
	<div class="large-3 columns">
	<p>Pemenang &amp; diinkubasi oleh Indigo Incubator<br/><img src="images/indigo.jpg" alt="Indigo Incubator"/></p>
	</div>
	<div class="large-3 columns">
	<p>Didukung oleh<br/><br/><a href="http://viroes.com"><img src="images/viroes.png" alt="Viroes Innovation Center"/></a></p>
	</div>
</div>

<?php
} else {
// Content for English (default)
?>
<p>Global warming. Heavy traffic. High fuel cost.</p>
<p>Those are three examples of current problems, which actually can be solved
by the Indonesia local public transport. Nevertheles, there are
still many people hesitate to use them, mostly because it is less practical
than riding a private vehicle. Ironically, tourists from neighbouring countries
prefer to use the public transports, as they are cheaper and give a chance to see the
city.</p>
<p>KIRI's role is very simple: you tell us where you are and where to go, and we
tell you how, by using angkots. Either you are a tourist or local people who wants
to help reduce the problems mentioned before.</p>

<p class="socialmedia">
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fkiri.travel&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=408834732492486" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>
<a href="http://www.facebook.com/kiriupdate" target="_blank" title="Click to find us on Facebook"><img src="images/fb_en.png" alt="Find us on Facebook"/></a>
<a href="https://twitter.com/kiriupdate" class="twitter-follow-button" data-show-count="false">Follow @kiriupdate</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</p>

<h2>Visit Us</h2>
<iframe src="http://kiri.travel/widget?apikey=583B17FF404AC44&finish=Bandung+Digital+Valley/-6.87324,107.58682&locale=en" width=100% height="400"></iframe>

<h2>Awards &amp; Associations</h2>
<div id="footerAwards" class="row">	
	<div class="large-3 columns">
	<p>Finalist - Mandiri Young Technopreneur 2012<br/><img src="images/myt.jpg" alt="Mandiri Young Technopreneur"/></p>
	</div>
	<div class="large-3 columns">
	<p>Finalist - Blackberry Business Plan Competition 2012<br/><img src="images/bbic.png" alt="Blackberry Business Plan Competition"/></p>
	</div>
	<div class="large-3 columns">
	<p>Winner &amp; incubated by Indigo Incubator<br/><img src="images/indigo.jpg" alt="Indigo Incubator"/></p>
	</div>
	<div class="large-3 columns">
	<p>Powered by<br/><br/><a href="http://viroes.com"><img src="images/viroes.png" alt="Viroes Innovation Center"/></a></p>
	</div>
</div>

<?php
}

// Footer
printFooter($locale);
?>