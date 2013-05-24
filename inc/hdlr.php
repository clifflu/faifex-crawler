<?php

/**
 * Base Handler
 */
abstract class Hdlr {
	/**
	 * 參照 uri 連線並抓取檔案
	 *
	 * @return int
	 *	1: 執行成功, 
	 *  2: 需要重新執行,
	 *	執行失敗回傳負數 :
	 */
	abstract function fetch($uri);

	/**
	 * 下載檔案
	 * 
	 * @return int
	 * 1: 執行成功
	 * -1: 遠端檔案開啟失敗
	 * -2: 無法建立暫存檔案
	 * -3: 遠端檔案太小
	 * -4: 無法建立輸出檔案
	 * -10: 不是 zip 檔
	 */
	function fetch_uri($FN_SRC, $FN_DEST, $opt = array()) {
		try{
			$fp = fopen($FN_SRC, 'rb') ;
		} catch (Exception $e) {
			$fp = false;
		}

		if ($fp === false) return -1 ;

		$FN_TMP = tempnam('/tmp', 'spider.tmp.') ;
		$fout = fopen($FN_TMP,'wb') ;
		if (!$fout) return -2 ;

		while (!feof($fp)) {
			$raw = fread($fp, 8192) ;
			fwrite($fout, $raw) ;
		}

		fclose($fp) ;
		fclose($fout) ;

		if (filesize($FN_TMP) < 32) {
			// Filesize too small to be true
			unlink($FN_TMP) ;
			return -3 ;
		}

		// zip test
		if (@$opt['zip']) {
			$zip = zip_open($FN_TMP) ;
			if (!is_resource($zip)) 
				return -10 ;
			zip_close($zip) ;
		}

		if (!rename($FN_TMP, $FN_DEST)) return -4 ;

		return 1;
	}
	
	function safe_get_subcat_dir($subcat) {
		$dir = DIR_DATA.$this->name.'/'.$subcat;

		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}

		return $dir;
	}
	
	function latest_date($subcat) {
		$dir = $this->safe_get_subcat_dir($subcat);
		
		$tmp = scandir($dir, 1);

		if($tmp[0] == '..') 
			return null;
		
		$hit = false;
		for($i = 0;isset($tmp[$i]); $i++) {
			$hit = preg_match('/^(\d{4})_(\d{2})_(\d{2})\./', $tmp[$i], $matches);
			if ($hit) break;
		}

		if ($hit) {
			return new DateTime("{$matches[1]}-{$matches[2]}-{$matches[3]}");
		}
		return null; 
	}
	
	function fn_from_dt($date, $subcat, $format = 'Y_m_d', $append = '.zip') {
		return DIR_DATA."{$this->name}/$subcat/".$date->format($format).$append;
	}

	/* Util */
	protected $name;
	public function __construct($name) {
		$this->name = $name;
	}
}
?>
