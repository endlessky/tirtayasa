<?php
	require_once 'constants.php';
	require_once 'utils.php';

	init_mysql();
	export_lastmonth();
	truncate_lastmonth();
	clean_cache();
	deinit_mysql();
	
	function export_lastmonth() {
		global $global_mysqli_link;
		$today = getdate();
		$month = $today['mon'] - 1;
		$year = $today['year'];
		if ($month <= 0) {
			$month += 12;
			$year--;
		}
		$result = mysqli_query($global_mysqli_link, "SELECT * FROM statistics WHERE MONTH(timeStamp)=$month AND YEAR(timeStamp)=$year") or
			die("Can't select last month data: " . mysqli_error($global_mysqli_link));
		$month = sprintf("%02d", $month);
		
		// Output file
		$csvfile = "../log/statistics-$year-$month.csv.gz";
		$output = gzopen($csvfile, 'w');
		while ($row = mysqli_fetch_row($result)) {
			fputcsv($output, $row);
		}
		gzclose($output);
	}
	
	function truncate_lastmonth() {
		global $global_mysqli_link;
		$today = getdate();
		$month = $today['mon'] - 1;
		$year = $today['year'];
		if ($month <= 0) {
			$month += 12;
			$year--;
		}
		$result = mysqli_query($global_mysqli_link, "DELETE FROM statistics WHERE MONTH(timeStamp)=$month AND YEAR(timeStamp)=$year") or
			die("Can't remove last month data: " . mysqli_error($global_mysqli_link));
	}

	function clean_cache() {
		global $global_mysqli_link;
		global $cache_geocoding, $cache_expiry_geocoding_mysql;
		global $cache_searchplace, $cache_expiry_searchplace_mysql;
		mysqli_query($global_mysqli_link, "DELETE FROM cache WHERE type='$cache_geocoding' AND timestamp < (NOW() - INTERVAL $cache_expiry_geocoding_mysql)") or
			die('Failed to clean expired geocoding cache: ' . mysqli_error($global_mysqli_link));
		mysqli_query($global_mysqli_link, "DELETE FROM cache WHERE type='$cache_searchplace' AND timestamp < (NOW() - INTERVAL $cache_expiry_searchplace_mysql)") or
			die('Failed to clean expired searchplace cache: ' . mysqli_error($global_mysqli_link));
		
	}
	
?>