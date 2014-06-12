<?php
	require_once 'constants.php';
	require_once 'utils.php';

	init_mysql();
	recap_yesterday();
	deinit_mysql();
	
	function recap_day($day, $month, $year) {
		global $global_mysqli_link;
		mysqli_query($global_mysqli_link, "INSERT INTO apiusagedaily(count, date, verifier, type) SELECT COUNT(verifier) as count, '$year-$month-$day', verifier, type FROM statistics WHERE DAY(timeStamp) = $day AND MONTH(timeStamp) = $month AND YEAR(timeStamp) = $year GROUP BY verifier, type") or
			die("Recap day error: " . mysqli_error($global_mysqli_link));
	}
	
	function recap_yesterday() {
		$yesterday = getdate(time() - 60 * 60 * 24);
		$day = $yesterday['mday'];
		$month = $yesterday['mon'];
		$year = $yesterday['year'];
		recap_day($day, $month, $year);
	}
?>
