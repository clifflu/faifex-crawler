<?php

/* 目錄設定 */
define('DIR_BASE',__DIR__.'/');
define('DIR_INC', DIR_BASE.'inc/');
define('DIR_DATA', DIR_BASE.'data/');

/* 引入檔 */
require(DIR_INC.'func.php');
require(DIR_INC.'flock.php');

/* 載入設定檔 */
// $config = file_get_contents(DIR_BASE.'config.json');
// $config = json_decode($config, true);

function fuel_load_file($fn) {
	return include $fn;
}
$config = fuel_load_file(DIR_BASE.'config.php');

/* 一般設定 */
date_default_timezone_set('Asia/Taipei');

/* Autoloader */
function autoloader($class) {
	$tmp = DIR_INC.strtolower($class).".php";
	if (is_file($tmp)) {
		include($tmp);
	}
}
spl_autoload_register('autoloader');

?>
