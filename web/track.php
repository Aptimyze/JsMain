<?php
$id = $_GET['MAILER_ID'];
$filepath = "/var/www/html/web/uploads/csv_files/";
$filename = $filepath."mailer_open_rate_log.txt";
$file = fopen($filename,"a+");
file_put_contents($filename, $id."\n", FILE_APPEND);
fclose($file);
?>
