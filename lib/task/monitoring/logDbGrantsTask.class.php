<?php

/**
 * This will log all db grant to a file.
 * @author : Bhavana Kadwal
 * @package Monitoring
 * @since 2017-05-03
 */
class logDbGrantsTask extends sfBaseTask {
        protected function configure() {
                $this->namespace = 'monitoring';
                $this->name = 'logDbGrants';
                $this->briefDescription = '';
                $this->detailedDescription = <<<EOF
The [connectionHigh|INFO] log all db grant to a file.
Call it with:
  [php symfony monitoring:logDbGrants|INFO]
EOF;
        }

        protected function execute($arguments = array(), $options = array()) {
                $SERVER_ARR = get_class_vars("MysqlDbConstants");
                $serverArray = array();
                foreach ($SERVER_ARR as $serverName => $server) {
                        if (is_array($server) && !in_array($serverName,array("BMS_99","MMM_99"))) {
                                $db = @mysql_connect($server["HOST"] . ":" . $server["PORT"], $server["USER"], $server["PASS"]);
                                $res = mysql_query("SHOW GRANTS", $db);
                                if ($res) {
                                        while ($row = @mysql_fetch_assoc($res)) {
                                                $arrayValue = array_values($row);
                                                $arrayValue = explode("TO",$arrayValue[0]);
                                                $serverArray[$serverName] = $arrayValue[0];
                                        }
                                }
                                unset($db);
                        }
                }
                ksort($serverArray);
                $fileName = sfConfig::get("sf_upload_dir") . "/SearchLogs/dbGrants".date("Ymd").".txt";
                $grantStr = "";
                foreach($serverArray as $key=>$grantString){
                        $grantStr .= $key.":: ".$grantString."\n\n";
                }
                file_put_contents($fileName,$grantStr);
        }

}
