<?php
function imageResize($url,$width,$height){
		header('Content-type: image/jpeg');
	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accepts-Language: ' . $_SERVER["HTTP_ACCEPT_LANGUAGE"]));

	$contents=curl_exec($ch);
	curl_close ($ch);
	$img=@imagecreatefromstring($contents);
	 if($img){
		 $width_orig=imagesx($img);
	$height_orig=imagesy($img);
	$ratio_orig = $width_orig/$height_orig;
	if($width/$height > $ratio_orig) {
		$width = $height*$ratio_orig;
    } else {
		$height = $width/$ratio_orig;
	}
	$image_p=imagecreatetruecolor($width,$height);
	imagecopyresampled($image_p, $img, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	imagejpeg($image_p, null, 89);
	}
}
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
	imageResize($_GET['url'], $_GET['w'], $_GET['h']);
}
elseif ($method == 'POST') {
	imageResize($_POST['url'], $_POST['w'], $_POST['h']);
}
?>

