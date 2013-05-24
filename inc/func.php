<?php

/** 
 * functions
 */

function config_get_entry($name) {
	global $config;
	foreach($config['src'] as $entry) {
		if ($entry['name'] == $name) {
			return $entry;
		}
	}
}

function say($str, $omit_newline=false) {
	echo $str;
	if (!$omit_newline) {
		echo "\n";
	}

	flush();
	@ob_flush();
}
?>

