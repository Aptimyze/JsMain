<?php
$mailer_id=$_GET['mailer_id'];
$file_name = 'MMM_CSV_' .$mailer_id.'mailer.csv';
header("Content-disposition: attachment; filename=$file_name");
header('Content-Type: text/csv; charset=utf-8');
$path = $_SERVER['DOCUMENT_ROOT'];
readfile("$path/mmmjs/cheetah_csv/".$file_name);

?>

			
