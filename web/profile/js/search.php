<?php
        include("common.php");
	header("Content-type: application/x-javascript");
       	echo "var CHAT_URL='".$CHAT_URL."';\n";
	readfile($_SERVER["DOCUMENT_ROOT"].'/profile/js/search.js');
?>

