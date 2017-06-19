<?php
	$string="Serial No : ".$voucher_no;
	header("Content-type: image/jpeg");
	if($clientid=='VLCC01')
		$im = imagecreatefromjpeg("$IMG_URL/profile/ser4_images/voucher_vlcc1.jpg");
	elseif($clientid=='VLCC02')
		$im = imagecreatefromjpeg("$IMG_URL/profile/ser4_images/voucher_vlcc2.jpg");
	$orange = imagecolorallocate($im, 100, 100, 100);
	$px = (imagesx($im) - 7.5 * strlen($string)) / 2;
	imagestring($im, 5, 500, 20, $string, $orange);
	imagepng($im);
?>
