<?php
$LOCK_TBL = array();

function get_lock($lock_name) {

	$hit = preg_match('/[\w_]+/', $lock_name);
	if (!$hit) {
		throw new Exception('Lock 名稱不合法, 格式需符合 /[\w_]+/');
	}

	$fp = fopen("/tmp/$lock_name", 'w');
	$ret = flock($fp, LOCK_EX|LOCK_NB);

	if ($ret) {
		$LOCK_TBL[$lock_name] = $fp;
		return true;
	} else {
		fclose($fp);
		return false;
	}
}

function release_lock($lock_name) {
	if (@is_resource($LOCK_TBL[$lock_name])) {
		fclose($LOCK_TBL[$lock_name]);
		unset($LOCK_TBL[$lock_name]);
	}
}
?>
