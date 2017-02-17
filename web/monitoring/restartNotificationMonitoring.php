<?php
    $command = "/var/www/html/web/monitoring/notificationMonitoring.php";
    exec("ps aux | grep \"".$command."\" | grep -v grep | awk '{ print $2 }'", $output);
    print_r($output);
    if(!(!empty($output) && is_array($output))){
        passthru(JsConstants::$php5path." ".$command." > /dev/null &");
        mail ("vibhor.garg@jeevansathi.com,manoj.rana@naukri.com,nitishpost@gmail.com","Notification Monitoring was not running. Resarted.","");
    }
?>