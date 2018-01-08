<?php

class billing_SERVICES extends TABLE
{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function getServiceName($serviceId) {
        if (!$serviceId) {
        	throw new jsException("", "SERVICEID IS BLANK");
        }
        try {
            $sql = "SELECT SQL_CACHE NAME from billing.SERVICES WHERE SERVICEID=:SERVICEID";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":SERVICEID", $serviceId, PDO::PARAM_STR);
            $resSelectDetail->execute();
            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
            return $rowSelectDetail['NAME'];
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function getServices($serviceIdStr) {
        if (!$serviceIdStr) throw new jsException("", "SERVICEID IS BLANK");
        try {
            $serviceIdArr = @explode(",", $serviceIdStr);
            foreach ($serviceIdArr as $key => $val) $str[] = ":SERVICEID$key";
            $newStr = @implode(",", $str);
            
            $sql = "SELECT SQL_CACHE NAME from billing.SERVICES WHERE SERVICEID IN($newStr)";
            $resSelectDetail = $this->db->prepare($sql);
            foreach ($serviceIdArr as $key => $val) $resSelectDetail->bindValue(":SERVICEID$key", $val, PDO::PARAM_STR);
            
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) $serviceNamesArr[] = $rowSelectDetail['NAME'];
            return $serviceNamesArr;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function getExclusiveInfo($currency = '', $device='desktop') {
        try {
            $serviceIdStr = "'X3','X6','X12'";
            if ($currency == 'DOL') $price = $device."_DOL";
            else $price = $device."_RS";
            $sql = "SELECT {$price} AS PRICE,SERVICEID from billing.SERVICES WHERE SERVICEID IN($serviceIdStr)";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) $serviceArr[$rowSelectDetail["SERVICEID"]] = $rowSelectDetail["PRICE"];
            return $serviceArr;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function activateShowOnlineForServices($serviceIdStr) {
        if (!$serviceIdStr) throw new jsException("", "SERVICEID IS BLANK");
        try {
            $serviceIdArr = @explode(",", $serviceIdStr);
            foreach ($serviceIdArr as $key => $val) $str[] = ":SERVICEID$key";
            $newStr = @implode(",", $str);
            
            $sql = "UPDATE billing.SERVICES SET SHOW_ONLINE_NEW=CASE WHEN SHOW_ONLINE_NEW = '' THEN ',-1,' ELSE CONCAT(SHOW_ONLINE_NEW,'-1,') END where SERVICEID IN($newStr)";
            $res = $this->db->prepare($sql);
            foreach ($serviceIdArr as $key => $val) $res->bindValue(":SERVICEID$key", $val, PDO::PARAM_STR);
            $res->execute();
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function deActivateShowOnlineForServices() {
        try {
            $sql = "UPDATE billing.SERVICES SET SHOW_ONLINE='N' WHERE ADDON='N' AND ACTIVE='Y'";
            $res = $this->db->prepare($sql);
            $res->execute();
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function fetchServiceDetailForRupeesTrxn($serviceId, $device='desktop') {
        if (!$serviceId) throw new jsException("", "SERVICEID IS BLANK");
        $rsKey = $device."_RS";
        $dolKey = $device."_DOL";
        if(is_array($serviceId) && !empty($serviceId)){
        	foreach ($serviceId as $key => $val) {
        		$str[] = ":SERVICEID$key";
        	}
            $newStr = @implode(",", $str);
        	try {
	            $sql = "SELECT SERVICEID, NAME, {$rsKey} AS PRICE, ADDON from billing.SERVICES WHERE SERVICEID IN ({$newStr})";
	            $resSelectDetail = $this->db->prepare($sql);
	            foreach ($serviceId as $key => $val) {
	            	$resSelectDetail->bindValue(":SERVICEID$key", $val, PDO::PARAM_STR);
	            }
	            $resSelectDetail->execute();
	            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
	            	$resultArr[$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
	            }
	            return $resultArr;
	        }
	        catch(Exception $e) {
	            throw new jsException($e);
	        }
        } else {
        	try {
	            $sql = "SELECT NAME, {$rsKey} AS PRICE, ADDON from billing.SERVICES WHERE SERVICEID=:SERVICEID";
	            $resSelectDetail = $this->db->prepare($sql);
	            $resSelectDetail->bindValue(":SERVICEID", $serviceId, PDO::PARAM_STR);
	            $resSelectDetail->execute();
	            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
	            return $rowSelectDetail;
	        }
	        catch(Exception $e) {
	            throw new jsException($e);
	        }
        }
    }
    
    public function fetchServiceDetailForDollarTrxn($serviceId, $device='desktop') {
        if (!$serviceId) throw new jsException("", "SERVICEID IS BLANK");
        $rsKey = $device."_RS";
        $dolKey = $device."_DOL";
        if(is_array($serviceId) && !empty($serviceId)){
        	foreach ($serviceId as $key => $val) {
        		$str[] = ":SERVICEID$key";
        	}
            $newStr = @implode(",", $str);
        	try {
	            $sql = "SELECT SERVICEID, NAME, {$dolKey} AS PRICE, ADDON from billing.SERVICES WHERE SERVICEID IN ({$newStr})";
	            $resSelectDetail = $this->db->prepare($sql);
	            foreach ($serviceId as $key => $val) {
	            	$resSelectDetail->bindValue(":SERVICEID$key", $val, PDO::PARAM_STR);
	            }
	            $resSelectDetail->execute();
	            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
	            	$resultArr[$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
	            }
	            return $resultArr;
	        }
	        catch(Exception $e) {
	            throw new jsException($e);
	        }
        } else {
        	try {
	            $sql = "SELECT NAME, {$dolKey} AS PRICE, ADDON from billing.SERVICES WHERE SERVICEID=:SERVICEID";
	            $resSelectDetail = $this->db->prepare($sql);
	            $resSelectDetail->bindValue(":SERVICEID", $serviceId, PDO::PARAM_STR);
	            $resSelectDetail->execute();
	            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
	            return $rowSelectDetail;
	        }
	        catch(Exception $e) {
	            throw new jsException($e);
	        }
        }
    }
    
    public function fetchAllServiceDetails($serviceid_str) {
    	try {
            $convertCToNCP = false;
            if(in_array('J', VariableParams::$mainMemBasedVasFiltering['NCP']) && !strstr($serviceid_str, 'NCP') && strstr($serviceid_str, 'C') && strstr($serviceid_str, 'J')){
                $convertCToNCP = true;
            }
        	$serviceIdArr = explode(",", $serviceid_str);
        	foreach ($serviceIdArr as $key => $val) {
        		$str[] = ":SERVICEID$key";
        	}
            $newStr = @implode(",", $str);
            $sql = "SELECT * from billing.SERVICES WHERE SERVICEID IN ({$newStr})";
            $resSelectDetail = $this->db->prepare($sql);
            foreach ($serviceIdArr as $key => $val) {
                $serviceIdStr1 = trim($val,"'");
                if($convertCToNCP == true && strstr($serviceIdStr1,'C')){
                    $serviceIdStr1 = str_replace("C", "NCP", $serviceIdStr1);
                }
            	$resSelectDetail->bindValue(":SERVICEID$key", $serviceIdStr1, PDO::PARAM_STR);
            }
            $resSelectDetail->execute();
            $counter = 0;
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $res[$counter] = $rowSelectDetail;
                if($convertCToNCP == true && strstr($rowSelectDetail["SERVICEID"], "NCP")){
                    if(!empty($rowSelectDetail["SERVICEID"])){
                        $res[$counter]['SERVICEID'] = str_replace('NCP', 'C', $rowSelectDetail["SERVICEID"]);
                    }
                    if(!empty($rowSelectDetail["NAME"])){
                        $res[$counter]['NAME'] = str_replace('eAdvantage', 'e-Value Pack', $rowSelectDetail["NAME"]);
                    }
                    if(!empty($rowSelectDetail["SORTBY"])){
                        $res[$counter]['SORTBY'] = 50;
                    }
                }
                ++$counter;
            }
            return $res;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchServicePrice($serviceId, $device='desktop') {
        if (!$serviceId) throw new jsException("", "SERVICEID IS BLANK");
        $rsKey = $device."_RS";
        $dolKey = $device."_DOL";
        try {
            $sql = "SELECT {$rsKey} AS PRICE_RS_TAX, {$dolKey} AS PRICE_DOL from billing.SERVICES WHERE SERVICEID=:SERVICEID";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":SERVICEID", $serviceId, PDO::PARAM_STR);
            $resSelectDetail->execute();
            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
            return $rowSelectDetail;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function getActivedOnlineServicesForID($search_id) {
        if (empty($search_id)) {
            throw new jsException("", "SEARCH ID BLANK IN getActivedOnlineServicesForID");
        } 
        else {
            $search_id = $search_id . "%";
        }
        try {
            $sql = "SELECT SQL_CACHE SERVICEID FROM billing.SERVICES WHERE SERVICEID LIKE :SEARCHID AND ACTIVE='Y' AND SHOW_ONLINE = 'Y'";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":SEARCHID", $search_id, PDO::PARAM_STR);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $rowSelectDetail['SERVICEID'];
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        
        return $output;
    }

    public function getAllServiceDataForActiveServices($showOnline=NULL) {
        try {
            $sql = "SELECT SQL_CACHE * FROM billing.SERVICES WHERE ACTIVE='Y'";
            if(!empty($showOnline) && $showOnline != "A"){
                $sql .= " AND SHOW_ONLINE_NEW LIKE '%,$showOnline,%'";
            }
            else if($showOnline == "A"){
                $sql .= " AND SHOW_ONLINE_NEW NOT LIKE ''";
            }
            $sql .= " ORDER BY SERVICEID ASC";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            $i=0;
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $output[$i]['ID'] = $rowSelectDetail['ID'];
                $output[$i]['SERVICEID'] = $rowSelectDetail['SERVICEID'];
                $output[$i]['NAME'] = $rowSelectDetail['NAME'];
                $output[$i]['DESCRIPTION'] = $rowSelectDetail['DESCRIPTION'];
                $output[$i]['DURATION'] = $rowSelectDetail['DURATION'];
                $output[$i]['PRICE_RS'] = $rowSelectDetail['PRICE_RS'];
                $output[$i]['PRICE_RS_TAX'] = $rowSelectDetail['PRICE_RS_TAX'];
                $output[$i]['PRICE_DOL'] = $rowSelectDetail['PRICE_DOL'];
                $output[$i]['desktop_DOL'] = $rowSelectDetail['desktop_DOL'];
                $output[$i]['mobile_website_DOL'] = $rowSelectDetail['mobile_website_DOL'];
                $output[$i]['old_mobile_website_DOL'] = $rowSelectDetail['old_mobile_website_DOL'];
                $output[$i]['JSAA_mobile_website_DOL'] = $rowSelectDetail['JSAA_mobile_website_DOL'];
                $output[$i]['iOS_app_DOL'] = $rowSelectDetail['iOS_app_DOL'];
                $output[$i]['desktop_RS'] = $rowSelectDetail['desktop_RS'];
                $output[$i]['mobile_website_RS'] = $rowSelectDetail['mobile_website_RS'];
                $output[$i]['old_mobile_website_RS'] = $rowSelectDetail['old_mobile_website_RS'];
                $output[$i]['JSAA_mobile_website_RS'] = $rowSelectDetail['JSAA_mobile_website_RS'];
                $output[$i]['iOS_app_RS'] = $rowSelectDetail['iOS_app_RS'];
                $output[$i]['COMPID'] = $rowSelectDetail['COMPID'];
                $output[$i]['PACKID'] = $rowSelectDetail['PACKID'];
                $output[$i]['ADDON'] = $rowSelectDetail['ADDON'];
                $output[$i]['SORTBY'] = $rowSelectDetail['SORTBY'];
                if(!empty($showOnline)){
                    $output[$i]['SHOW_ONLINE'] = 'Y';
                }
                else{
                    $output[$i]['SHOW_ONLINE'] = $rowSelectDetail['SHOW_ONLINE'];
                }
                $output[$i]['ACTIVE'] = $rowSelectDetail['ACTIVE'];
                $output[$i]['ENABLE'] = $rowSelectDetail['ENABLE'];
                $output[$i]['FREEBIES'] = $rowSelectDetail['FREEBIES'];
                $output[$i]['MOST_POPULAR'] = $rowSelectDetail['MOST_POPULAR'];
                $i++;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        
        return $output;
    }

    public function getPreviousExpiryDate($profileid, $rights){
        try{
            $sql = "SELECT EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%$rights%' AND ACTIVE='Y' ORDER BY ID DESC LIMIT 1";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $resSelectDetail->execute();
            if ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $previous_expiry_date["EXPIRY_DATE"] = $rowSelectDetail["EXPIRY_DT"];
            }
        } catch(Exception $e) {
            throw new jsException($e);
        }
        return $previous_expiry_date;
    }

    public function getLowestActiveMainMembership($serviceArr, $device='desktop',$mtongue="-1"){

        if(empty($serviceArr)){
            throw new jsException("Empty serviceArr passed in getLowestActiveMainMembership, billing_SERVICES.class.php");
        } else if(is_array($serviceArr)){
            foreach($serviceArr as $key=>$val){
                if($key == 0){
                    $search_id .= "SERVICEID LIKE '{$val}%'";
                } else {
                    $search_id .= " OR SERVICEID LIKE '{$val}%'";
                }
            }
        } else {
            $search_id = "SERVICEID LIKE '{$serviceArr}%'";
        }
        if(empty($mtongue)){
            $mtongue[-1] = "default";
        }
        $rsKey = $device."_RS";
        $dolKey = $device."_DOL";
        try{
            $sql = "SELECT SERVICEID,NAME,{$rsKey} AS PRICE_INR,{$dolKey} AS PRICE_USD, SHOW_ONLINE_NEW FROM billing.SERVICES WHERE ({$search_id}) AND ACTIVE='Y' AND (";
            $COUNT=1;
            foreach($mtongue as $key=>$value) {
                $sql .= " SHOW_ONLINE_NEW LIKE '%,$key,%' OR";
            }
            $sql = rtrim($sql,'OR').") ORDER BY PRICE_INR ASC";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $output[$rowSelectDetail['SHOW_ONLINE_NEW']][$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
            }
            return $output;
        } catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function getMostPopularMembershipList(){
        try{
            $sql = "SELECT SQL_CACHE SERVICEID FROM billing.SERVICES WHERE MOST_POPULAR = 'Y'";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($row = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                if (substr($row['SERVICEID'], 0, 3) == "ESP") {
                    $most_popular['ESP'] = $row['SERVICEID'];
                }
                else if (substr($row['SERVICEID'], 0, 3) == "NCP") {
                    $most_popular['NCP'] = $row['SERVICEID'];
                }
                else {
                    $most_popular[substr($row['SERVICEID'], 0, 1) ] = $row['SERVICEID'];
                }
            }
            return $most_popular;
        } catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function getEnabledServices(){
        try{
            $sql = "SELECT SQL_CACHE DISTINCT SUBSTRING(SERVICEID,1,1) as SERVICEID FROM billing.SERVICES WHERE (SERVICEID LIKE 'P%' OR SERVICEID LIKE 'D%' OR SERVICEID LIKE 'C%' OR SERVICEID LIKE 'NCP%' OR SERVICEID LIKE 'ESP%' OR SERVICEID LIKE 'X%') AND ENABLE = 'Y'";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            $i=0;
            while ($row = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                if ($row['SERVICEID'] == "E") {
                    $serviceTabs[$i] = "ESP";
                }
                elseif ($row['SERVICEID'] == "N") {
                    $serviceTabs[$i] = "NCP";
                }
                else {
                    $serviceTabs[$i] = $row['SERVICEID'];
                }
                $i++;
            }
            return $serviceTabs;
        } catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function getServiceInfo($search_id,$id,$offer,$price_str,$fetchOnline=true,$fetchOffline=false,$mtongue="-1") {
        $showOnlineStr = "";
        if(empty($mtongue) || $mtongue==""){
            $mtongue = "-1";
        }
        try {
            if($fetchOnline == true || $fetchOffline == true){
                if($fetchOffline == true && $fetchOnline == false){
                    if(strlen($search_id) > 0){
                        $showOnlineStr .= " AND ";
                    }
                    $showOnlineStr .= "(SHOW_ONLINE_NEW NOT LIKE '%,$mtongue,%')";
                }
                else if($fetchOnline == true && $fetchOffline == false){
                    if(strlen($search_id) > 0){
                        $showOnlineStr .= " AND ";
                    }
                    $showOnlineStr .= "(SHOW_ONLINE_NEW LIKE '%,$mtongue,%')";
                }
                //var_dump($SHOW_ONLINE);
            	if(is_array($id)){
    		        if ($offer) {
    		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, SHOW_ONLINE_NEW, $price_str as PRICE FROM billing.SERVICES WHERE ({$search_id}){$showOnlineStr} AND ACTIVE='Y' order by PRICE ASC";
    		        } else {
    		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, SHOW_ONLINE_NEW, $price_str as PRICE FROM billing.SERVICES WHERE ({$search_id}){$showOnlineStr} AND ACTIVE='Y' order by PRICE ASC";
    		        }
    	        } else {
    	        	if ($id == 'M') {
    	        		$sql = "SELECT SQL_CACHE SERVICEID, NAME, SHOW_ONLINE_NEW, $price_str as PRICE FROM billing.SERVICES WHERE SERVICEID LIKE '$search_id' AND ACTIVE='Y' order by PRICE ASC";
    	        	} elseif ($offer) {
    		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, SHOW_ONLINE_NEW, $price_str as PRICE FROM billing.SERVICES WHERE SERVICEID LIKE '$search_id'{$showOnlineStr} AND ACTIVE='Y' order by PRICE ASC";
    		        } else {
    		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, SHOW_ONLINE_NEW, $price_str as PRICE FROM billing.SERVICES WHERE SERVICEID LIKE '$search_id'{$showOnlineStr} AND ACTIVE='Y' order by PRICE ASC";
    		        }
    	        }
                //var_dump($sql);
                $resSelectDetail = $this->db->prepare($sql);
                $resSelectDetail->execute();
                while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
                    if(strpos($rowSelectDetail["SHOW_ONLINE_NEW"],",".$mtongue.",") !== false){
                	   $row_services[$rowSelectDetail["SERVICEID"]] = array('NAME'=>$rowSelectDetail["NAME"],'PRICE'=>$rowSelectDetail["PRICE"],'SHOW_ONLINE'=>'Y');
                    }
                    else{
                       $row_services[$rowSelectDetail["SERVICEID"]] = array('NAME'=>$rowSelectDetail["NAME"],'PRICE'=>$rowSelectDetail["PRICE"],'SHOW_ONLINE'=>'N');
                    }
                }
            }
            else{
                $row_services = null;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $row_services;
    }

    public function getAddonInfo($price_str,$offer,$mtongue="-1") {
        if(empty($mtongue)){
            $mtongue = "-1";
        }
        try {
        	/*if ($offer) {
        		$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE,DURATION FROM billing.SERVICES WHERE ACTIVE='Y' AND ADDON='Y' AND SHOW_ONLINE IN('Y','S') order by DURATION DESC";
        	} else {
        		$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE,DURATION FROM billing.SERVICES WHERE ACTIVE='Y' AND ADDON='Y' AND SHOW_ONLINE='Y' order by DURATION DESC";
        	}*/
            $sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE,DURATION FROM billing.SERVICES WHERE ACTIVE='Y' AND ADDON='Y' AND SHOW_ONLINE_NEW LIKE '%,$mtongue,%' order by DURATION DESC";
        	$resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while($row = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
            	$id = $row["SERVICEID"];
	            $name = substr($id, 0, 1);
	            $dur = substr($id, 1);
	            if ($dur == 'L') $dur = 12;
	            if ($dur == '5') continue;
	            $addon[$name][$id]['NAME'] = $row["NAME"];
	            $addon[$name][$id]['PRICE'] = $row["PRICE"];
	            $addon[$name][$id]['DURATION'] = $dur;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $addon;
    }

    public function getServiceNameArr($serviceIdArr) {
        if (empty($serviceIdArr)) {
        	throw new jsException("", "SERVICEID IS BLANK");
        }
        try {
        	foreach ($serviceIdArr as $key => $val) {
        		$str[] = ":SERVICEID$key";
        	}
            $newStr = @implode(",", $str);
            $sql = "SELECT SQL_CACHE NAME, SERVICEID from billing.SERVICES WHERE SERVICEID IN ({$newStr})";
            $resSelectDetail = $this->db->prepare($sql);
            foreach ($serviceIdArr as $key => $val) {
            	$resSelectDetail->bindValue(":SERVICEID$key", $val, PDO::PARAM_STR);
            }
            $resSelectDetail->execute();
            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
            	$output[$rowSelectDetail['SERVICEID']] = $rowSelectDetail['NAME'];
            }
            return $output;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    public function getServiceDetailsArr($fields='') {
        try {
            if(!$fields)
                $fields ="*";
            $sql = "SELECT SQL_CACHE $fields from billing.SERVICES";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
                $output[$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
            }
            return $output;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    public function getOnlineActiveDurations($mtongue="-1",$addon="N") {
        try {
            $sql = "SELECT distinct DURATION from billing.SERVICES WHERE ";
            if($mtongue == "A"){
                $sql .= "SHOW_ONLINE_NEW NOT LIKE ''";
            }
            else{
                $sql .="SHOW_ONLINE_NEW LIKE '%,$mtongue,%'";
            }
            if($addon == "N"){
                $sql .= " AND ADDON!='Y'";
            }
            else{
                $sql .= " AND ADDON='Y'";
            }
            //ankita code removed to hide P1
            $sql .= " AND ACTIVE='Y'";/* AND SERVICEID!='P1'*/;
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while($row = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
                $output[$row['DURATION']] = $row['DURATION'];
            }
            return $output;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function getPreviousExpiryDetails($profileid, $rights, $mainCheck){
        try{
            if ($mainCheck == 'Y') {
                $sql = "SELECT COUNT(*) as CNT FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%F%' AND ACTIVE='Y' ORDER BY ID DESC";
            } else {
                $sql = "SELECT COUNT(*) as CNT FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%$rights%' AND ACTIVE='Y' ORDER BY ID DESC";
            }
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $resSelectDetail->execute();
            if ($row = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
                if ($row['CNT'] >= 2 ) {
                    if ($mainCheck == 'Y') {
                        $sql1 = "SELECT EXPIRY_DT, SERVICEID, BILLID FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%F%' AND ACTIVE='Y' ORDER BY ID DESC LIMIT 1,1";
                    } else {
                        $sql1 = "SELECT EXPIRY_DT, SERVICEID, BILLID FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%$rights%' AND ACTIVE='Y' ORDER BY ID DESC LIMIT 1,1";
                    }
                    $resSelectDetail1 = $this->db->prepare($sql1);
                    $resSelectDetail1->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                    $resSelectDetail1->execute();
                    if ($rowSelectDetail1 = $resSelectDetail1->fetch(PDO::FETCH_ASSOC)) {
                        $previous_expiry = $rowSelectDetail1;
                    }   
                }
            }
        } catch(Exception $e) {
            throw new jsException($e);
        }
        return $previous_expiry;
    }

    public function getServicesForActivationInterface($servArr,$mtongue="-1") {
        try {
            $sql = "SELECT SERVICEID, NAME, CASE WHEN SHOW_ONLINE_NEW LIKE '%,$mtongue,%' THEN 'Y' ELSE 'N' END AS SHOW_ONLINE,SHOW_ONLINE_NEW FROM billing.SERVICES WHERE ACTIVE='Y' AND ENABLE='Y' AND (";
            foreach ($servArr as $key=>$val) {
                $sqlArr[] = "SERVICEID LIKE '{$val}%'";
            }
            $sql .= implode(" OR ", $sqlArr);
            $sql .= ") ORDER BY SERVICEID ASC";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $output[$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
            }
            return $output;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchServiceDetails($servArr) {
        try {
            if(is_array($servArr)){
                $servStr = "'".implode("','", $servArr)."'";
                $sql = "SELECT SERVICEID, ACTIVE FROM billing.SERVICES WHERE SERVICEID IN ($servStr)";
                $resSelectDetail = $this->db->prepare($sql);
                $resSelectDetail->execute();
                while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                    $output[$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
                }
            }
            return $output;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function changeServiceActivations($updateShowOnlineNew=null) {
        try {
            if(is_array($updateShowOnlineNew) && count($updateShowOnlineNew) > 0){
                $sql = "UPDATE billing.SERVICES SET SHOW_ONLINE_NEW = CASE";
                foreach ($updateShowOnlineNew as $key => $value) {
                    $sql .= " WHEN SERVICEID LIKE '$key' THEN '$value'";
                }
                $sql .= " ELSE SHOW_ONLINE_NEW END";
                //var_dump($sql);
                $resSelectDetail = $this->db->prepare($sql);
                $resSelectDetail->execute();
            }
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getFinanceDataServiceNames() {
        try {
            $sql = "SELECT SQL_CACHE SERVICEID, NAME FROM billing.SERVICES WHERE SERVICEID LIKE 'T%' OR SERVICEID LIKE 'R%' OR SERVICEID LIKE 'A%' OR SERVICEID LIKE 'I%' OR SERVICEID LIKE 'B%' OR SERVICEID LIKE 'P%' OR SERVICEID LIKE 'C%' OR SERVICEID LIKE 'D%' OR SERVICEID LIKE 'NCP%' OR SERVICEID LIKE 'ESP%' OR SERVICEID LIKE 'X%'";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $output[$rowSelectDetail['SERVICEID']] = $rowSelectDetail['NAME'];
            }
            return $output;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}

