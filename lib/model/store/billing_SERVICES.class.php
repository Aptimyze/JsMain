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
            
            $sql = "UPDATE billing.SERVICES SET SHOW_ONLINE='Y' where SERVICEID IN($newStr)";
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
        	$serviceIdArr = explode(",", $serviceid_str);
        	foreach ($serviceIdArr as $key => $val) {
        		$str[] = ":SERVICEID$key";
        	}
            $newStr = @implode(",", $str);
            $sql = "SELECT * from billing.SERVICES WHERE SERVICEID IN ({$newStr})";
            $resSelectDetail = $this->db->prepare($sql);
            foreach ($serviceIdArr as $key => $val) {
            	$resSelectDetail->bindValue(":SERVICEID$key", trim($val,"'"), PDO::PARAM_STR);
            }
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $rowSelectDetail;
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
            if(!empty($showOnline)){
                $sql .= " AND SHOW_ONLINE='Y'";
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
                $output[$i]['SHOW_ONLINE'] = $rowSelectDetail['SHOW_ONLINE'];
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

    public function getLowestActiveMainMembership($serviceArr, $device='desktop'){
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
        $rsKey = $device."_RS";
        $dolKey = $device."_DOL";
        try{
            $sql = "SELECT SERVICEID,NAME,{$rsKey} AS PRICE_INR,{$dolKey} AS PRICE_USD FROM billing.SERVICES WHERE ({$search_id}) AND SHOW_ONLINE='Y' AND ACTIVE='Y' ORDER BY PRICE_INR ASC";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $output[$rowSelectDetail['SERVICEID']] = $rowSelectDetail;
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

    public function getServiceInfo($search_id,$id,$offer,$price_str) {
        try {
        	if(is_array($id)){
		        if ($offer) {
		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE FROM billing.SERVICES WHERE ({$search_id}) AND ACTIVE='Y' AND SHOW_ONLINE IN('Y','S') order by PRICE ASC";
		        } else {
		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE FROM billing.SERVICES WHERE ({$search_id}) AND ACTIVE='Y' AND SHOW_ONLINE = 'Y' order by PRICE ASC";
		        }
	        } else {
	        	if ($id == 'M') {
	        		$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE FROM billing.SERVICES WHERE SERVICEID LIKE '$search_id' AND ACTIVE='Y' order by PRICE ASC";
	        	} elseif ($offer) {
		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE FROM billing.SERVICES WHERE SERVICEID LIKE '$search_id' AND ACTIVE='Y' AND SHOW_ONLINE IN('Y','S') order by PRICE ASC";
		        } else {
		        	$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE FROM billing.SERVICES WHERE SERVICEID LIKE '$search_id' AND ACTIVE='Y' AND SHOW_ONLINE = 'Y' order by PRICE ASC";
		        }
	        }
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
            	$row_services[$rowSelectDetail["SERVICEID"]] = array('NAME'=>$rowSelectDetail["NAME"],'PRICE'=>$rowSelectDetail["PRICE"]);
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $row_services;
    }

    public function getAddonInfo($price_str,$offer) {
        try {
        	if ($offer) {
        		$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE,DURATION FROM billing.SERVICES WHERE ACTIVE='Y' AND ADDON='Y' AND SHOW_ONLINE IN('Y','S') order by DURATION DESC";
        	} else {
        		$sql = "SELECT SQL_CACHE SERVICEID, NAME, $price_str as PRICE,DURATION FROM billing.SERVICES WHERE ACTIVE='Y' AND ADDON='Y' AND SHOW_ONLINE='Y' order by DURATION DESC";
        	}
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
    public function getOnlineActiveDurations() {
        try {
            $sql = "SELECT SQL_CACHE distinct DURATION from billing.SERVICES WHERE SHOW_ONLINE='Y' AND ACTIVE='Y' AND ADDON!='Y' AND SERVICEID!='P1'";
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
}

