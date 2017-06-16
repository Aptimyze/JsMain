<?php
header('Access-Control-Allow-Origin: *');
/**
* This class will monitor the health check for all the servers.
*/
Class ServerHealthCheck{
        /**
        * This function will check the load of the server.
        */
        public static function checkLoad(){
                $load =exec("w|head -1|awk '{ print \" \"$(NF-2) \" \"$(NF-1)\" \"$(NF)\" \"}'");
                $load = str_replace(" ","",trim($load));
                $loadArr = explode(",",$load);
                $updateKeyArr = array("1 Minute","5 Minutes","15 Minute");
                return self::ReplaceKeyWithText($loadArr,$updateKeyArr);
        }

        /**
        * This function will check the free memory.
        */
        public static function checkMemory($type){
                if($type=="Phy"){
                        $mem =exec("free -m | grep \"Mem:\" |awk '{ print \" \"$2\" \"$3\" \"$4\" \"}'");
                        $updateKeyArr = array("Total Physical","Used Physical","Free Physical");
                }
                elseif($type=="Cached"){
                        $mem =exec("free -m | grep \"buffers\/cache:\" |awk '{ print \" \"$(NF-1)\" \"$(NF)\" \"}'");
                        $updateKeyArr = array("Cache Used","Cached Free");
                }
                elseif($type=="Swap"){
                        $mem =exec("free -m | grep \"Swap:\" |awk '{ print \" \"$2\" \"$3\" \"$4\" \"}'");
                        $updateKeyArr = array("Total Swap","Used Swap","Free Swap");
                }
                $mem = trim($mem);
                $memArr = explode(" ",$mem);
                return self::ReplaceKeyWithText($memArr,$updateKeyArr);
        }

        public static function ReplaceKeyWithText($arr,$updateKeyArr){
                foreach($arr as $k=>$v){
                        $kk = $updateKeyArr[$k];
                        $arr[$kk] = $v;
                        unset($arr[$k]);
                }
                return $arr;
        }
}

$finalArr["whoami"] = $_SERVER["HTTP_HOST"];
$finalArr["load"] = ServerHealthCheck::checkload();
$finalArr["Memory_Physical"] = ServerHealthCheck::checkMemory('Phy');
$finalArr["Memory_Swap"] = ServerHealthCheck::checkMemory('Swap');
$finalArr["Memory_cached"] = ServerHealthCheck::checkMemory('Cached');
echo $finalArr = json_encode($finalArr);
//return $finalArr;

