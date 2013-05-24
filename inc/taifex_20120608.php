<?php

class Taifex_20120608 extends Hdlr {
	function fetch($uri) {
		say("Parsing " . __CLASS__);
		$ret = array(
			$this->fetch_base($uri, 'future'),
			$this->fetch_base($uri, 'future_b'),
			$this->fetch_base($uri, 'future_c'),
			$this->fetch_base($uri, 'option'),
		);
	}

	function fetch_future($uri) {
		return $this->fetch_base($uri, 'future');
	}

	function fetch_base($uri, $subcat) {
		$DI_1D = new DateInterval('P1D');
		
		say("\tFetching '$subcat' ");

		// get last date
		$latest_date = $this->latest_date($subcat);
		
		if ($latest_date === null) {
			$start_date = new DateTime('-30 days');
		} else {
			$start_date = $latest_date->add($DI_1D);
		}

		$end_date = new DateTime('now');

		for($date = $start_date; $date <= $end_date; $date->add($DI_1D)) {
			say("\t\t".$date->format('Y-m-d')." ", true);
			$ret = $this->fetch_uri(
				$this->uri_from_dt($date, $uri, $subcat), 
				$this->fn_from_dt($date, $subcat),
				array(
					'zip' => true,
				)
			);
			
			if ($ret == 1) {
				say("done");
			} else {
				say("failed");
			}
		}
	}

	function uri_from_dt($date, $uri, $subcat) {
		$ds = $date->format('Y_m_d');

		switch ($subcat) {
			case 'future_b':
				$fn = 'DailyDownload_B/Daily_'.$ds.'_B.zip';
				break;
			case 'future_c':
				$fn = 'DailyDownload_C/Daily_'.$ds.'_C.zip';
				break;
			case 'future':
				$fn = 'DailyDownload/Daily_'.$ds.'.zip';
				break;
			case 'option':
				$fn = 'OptionsDailyDownload/OptionsDaily_'.$ds.'.zip';
		}

		return $uri.$fn;
	}

}
