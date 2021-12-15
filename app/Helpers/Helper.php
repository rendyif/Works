<?php 

use Carbon\Carbon;

function setActive($uri, $output = 'active')
{
	if( is_array($uri) ) {
		foreach ($uri as $u) {
			if (Route::is($u)) {
				return $output;
			}
		}
	} else {
		if (Route::is($uri)){
			return $output;
		}
	}
}

function rupiah($number)
{
	return number_format($number,0,',','.');
}

function date_full($date)
{
	return Carbon::parse($date)->format('d F Y');
}

function date_dmy($date)
{
	return Carbon::parse($date)->format('d M Y');
}

function diff_days($start_date, $end_date)
{
	$start = Carbon::parse($start_date);
	$res = Carbon::parse($end_date)->diffInDays($start);

	return $res;
}

function nilai_text($nilai)
{
	$text = "";

	if ($nilai >= 90 && $nilai <= 100) {
		$text = "Sangat Butuh";
	} else if ($nilai >= 80 && $nilai <= 89.9){
		$text = "Butuh";
	} else if ($nilai >= 70 && $nilai <= 79.9){
		$text = "Cukup Butuh";
	} else if ($nilai >= 60 && $nilai <= 69.9){
		$text = "Tidak Terlalu Butuh";
	} else if ($nilai <= 59) {
		$text = "Tidak Butuh";
	}

	return $text;
}