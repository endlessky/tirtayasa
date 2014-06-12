<?php

include '../../etc/static.php';
include '../../etc/adgotchalib.php';
$locale = validateLocale(retrieve_from_get($proto_locale, false));
$gotchaAPIKey  = '3b8a817334571c9496cadab5004a98b4';
$gotchaMemberID  = 'pascal.alfadian@viroes.com';

// Process POST message, if any
$recipient = "pascal.alfadian@viroes.com, budyanto.kurniawan@viroes.com";
$sender = "hello@kiri.travel";
$subject = "KIRI Feedback"; 
if (isset($_POST) && sizeof($_POST) > 0) {
	$responseValue  = $_POST["gotcha_val"];
	$remoteAddress  = $_SERVER["REMOTE_ADDR"];
	$emailbody = "<html><body>";
	foreach (array('fullname', 'email', 'comment') as $field) {
		$emailbody .= "<h1>$field</h1>";
		$emailbody .= '<p>' . htmlspecialchars($_POST[$field]) . '</p>';
	}

	$name = retrieve_from_post("fullname", FALSE);
	$email = retrieve_from_post("email", FALSE);
	$comment = retrieve_from_post("comment", FALSE);
	
	//input data into database
	init_mysql();
	mysqli_query($global_mysqli_link, "INSERT INTO feedbacks (fullName, email, feedback) VALUES ('$name','$email','$comment')") or
		die_nice("Error updating feedback: " . mysqli_error($global_mysqli_link));
	deinit_mysql();
	
	$emailbody .= "</body></html>";
	$header = 'From: ' . $sender . "\r\nReply-To: " . $_POST['email'] . "\r\nContent-type: text/html\r\n";
	if (mail($recipient, $subject, wordwrap($emailbody), $header)) {
		if (ValidateCaptcha($responseValue)) {
			$message = $locale == 'id' ? 'Terima kasih atas masukan Anda.' : 'Thank you for your feedback.';
		} else {
			$message = $locale == 'id' ? 'Anda gagal dalam tes Captcha, tapi kami tetap menampung masukan Anda. Terima kasih!' : 'You failed to solve the Captcha test, but we still collect your feedback. Thank you!';
		}
	} else {
		$message = $locale == 'id' ? 'Maaf, sistem kami sedang mengalami gangguan. Please email to pascal [dot] alfadian [at] viroes [dot] com' : 'Sorry, our system is experiencing some problem. Please email to pascal [dot] alfadian [at] viroes [dot] com';
	}
}

// Header
$title['id'] = 'Beri Masukan';
$title['en'] = 'Feedback';
printHeader($title[$locale], $locale);
if (isset($message)) {
	print '<div data-alert class="alert-box">';
	print $message;	
	print '<a href="#" class="close">&times;</a></div>';
	print "\n";
}
if ($locale == 'id') {
// Content for Bahasa Indonesia.
?>
<p>Kami sangat menghargai masukan Anda. Gunakan form di bawah ini untuk memberikannya.
Walaupun kami memperhitungkan semua masukan, mohon maaf kami jika tidak dapat membalas semua masukan dari Anda.</p>
<form action="feedback.php?locale=id" method="post">
<table class="form">
<tr><td>Nama Lengkap:</td><td><input type="text" name="fullname" maxlength="128"></td></tr>
<tr><td>Alamat E-mail (opsional):</td><td><input type="text" name="email" maxlength="128"></td></tr>
<tr><td>Pesan:</td><td><textarea name="comment" rows="12" cols="40"></textarea></td></tr>
<tr><td colspan="2"><?php echo GetCaptcha($gotchaAPIKey, $gotchaMemberID); ?>
<tr><td colspan="2"><input type="submit" value="Submit"/>
</table>
</form>
<?php
} else {
// Content for English (default)
?>
<p>We really appreciate your feedback. Please use this form to give it to us. While we
consider every feedback, it is with regret that we cannot reply to all feedbacks.</p>
<form action="feedback.php" method="post">
<table class="form">
<tr><td>Full Name:</td><td><input type="text" name="fullname" maxlength="128"></td></tr>
<tr><td>E-mail address (optional):</td><td><input type="text" name="email" maxlength="128"></td></tr>
<tr><td>Message:</td><td><textarea name="comment" rows="12" cols="40"></textarea></td></tr>
<tr><td colspan="2"><?php echo GetCaptcha($gotchaAPIKey, $gotchaMemberID); ?>
<tr><td colspan="2"><input type="submit" value="Submit"/>
</table>
</form>
<?php
}

// Footer
printFooter($locale);
?>