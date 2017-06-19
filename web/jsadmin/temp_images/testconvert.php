<?php
	$path=JsConstants::$docRoot."/jsadmin/temp_images/";
passthru('convert -resize '.$width."x269! ".$path."white.jpeg ".$path."white.jpeg");
?>
