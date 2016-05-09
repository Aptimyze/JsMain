<?php
 $log = $_POST['log'];
    $fileName = "/tmp/timetrackerlog.txt";
    $fph = fopen($fileName, "a+");
    $logstr = $log."\n";
    fputs($fph, $logstr);
    fclose($fph);
?>
