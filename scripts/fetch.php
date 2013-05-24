#!/usr/bin/php
<?php
require(__DIR__.'/../common.php');

if (!get_lock('finance')) {
	die("can't acquire lock\n");
}

/* 抓取對象 */
$targets = array();

if ($argc > 1) {
	for($i=1;isset($argv[$i]);$i++) {
		$entry = config_get_entry($argv[$i]);
		if ($entry && class_exists($entry['hdlr'])) {
			$targets[] = $entry;
		}
	}
} else {
	$targets = $config['src'];
}

foreach($targets as $entry) {
	$obj = new $entry['hdlr']($entry['name']);
	$ret = $obj->fetch($entry['uri']);
}

release_lock('finance');
