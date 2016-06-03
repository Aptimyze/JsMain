<?php

// maximum zlib compression level is 9
ini_set('zlib.output_compression_level', 5);

$php5_path="/usr/local/php/bin/php";

include_once("config.php");
include_once("../profile/connect_functions.inc");
include_once("../profile/google_key.php");
include_once("../profile/connect_db.php");
include_once($_SERVER['DOCUMENT_ROOT']."/../classes/Mysql.class.php");
?>
