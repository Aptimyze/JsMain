<?php

class BILLING_SERVICE_STATUS extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function getMaxExpiryDate($profileid)
    {
        try
        {
            $sql="SELECT MAX(EXPIRY_DT) AS EXP_DT FROM billing.SERVICE_STATUS WHERE ACTIVE = 'Y' AND SERVEFOR LIKE '%F%' AND PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $expDate=$result['EXP_DT'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $expDate;

    } 

    public function getUpsellEligibleProfiles()
    {
        try
        {
            $currtDateTime = date("Y-m-d H:i:s");
            $prevDateTime = date("Y-m-d H:i:s", time()-30*60);
            $sql="SELECT DISTINCT(p.PROFILEID) AS PROFILEID FROM billing.PURCHASES p JOIN billing.SERVICE_STATUS s ON p.BILLID = s.BILLID WHERE p.STATUS = 'DONE' AND p.MEMBERSHIP='Y' AND p.ENTRY_DT >=:PREVDATETIME AND p.ENTRY_DT <=:CURRDATETIME";  
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PREVDATETIME",$prevDateTime,PDO::PARAM_STR);
            $prep->bindValue(":CURRDATETIME",$currtDateTime,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result['PROFILEID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    } 

    public function getRenewalEligibleProfiles()
    {
        try
        {
            /*  Code added for One time activity */
            $expDate1 = date("Y-m-d");
            $expDate2 = date("Y-m-d", time()+29*24*60*60);
            $sql="SELECT DISTINCT(PROFILEID) AS PROFILEID FROM billing.SERVICE_STATUS WHERE ACTIVE ='Y' AND SERVEFOR LIKE '%F%' AND SERVEFOR NOT LIKE '%X%' AND EXPIRY_DT>=:EXPDATE1 AND EXPIRY_DT<=:EXPDATE2";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPDATE1",$expDate1,PDO::PARAM_STR);
            $prep->bindValue(":EXPDATE2",$expDate2,PDO::PARAM_STR);
            

            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result['PROFILEID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }
    public function getRenewalProfilesForDates($expDate1,$expDate2)
    {
        try
        {
            $sql="SELECT MAX(EXPIRY_DT) as EDATE,PROFILEID FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND SERVEFOR NOT LIKE '%X%' AND ACTIVE IN('Y','E') AND EXPIRY_DT>=:EXPDATE1 GROUP BY PROFILEID HAVING EDATE<=:EXPDATE2";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPDATE1",$expDate1,PDO::PARAM_STR);
            $prep->bindValue(":EXPDATE2",$expDate2,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result;
            }
	    return $profiles;	
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    public function getMaxExpiryProfilesForDates($expDate1,$expDate2)
    {
        try
        {
            $sql="SELECT MAX(EXPIRY_DT) as EDATE,PROFILEID FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND ACTIVE IN('Y','E') GROUP BY PROFILEID HAVING EDATE>=:EXPDATE1 AND EDATE<=:EXPDATE2";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPDATE1",$expDate1,PDO::PARAM_STR);
            $prep->bindValue(":EXPDATE2",$expDate2,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result['PROFILEID'];
            }
            return $profiles;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    public function getSortedProfilesExpiryBased($profileidStr)
    {
        try
        {
            if(!$profileidStr)
                throw new jsException("","no profileid passed");

            $profileArr =@explode(",",$profileidStr);
            foreach($profileArr as $key=>$val)
                $str[] =":PROFILEID$key";   
            $newStr =@implode(",",$str);

            $sql= "SELECT PROFILEID,MAX(EXPIRY_DT) AS EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID IN($newStr) AND SERVEFOR LIKE '%F%' GROUP BY PROFILEID";
            $prep = $this->db->prepare($sql);

            foreach($profileArr as $key=>$val)
                $prep->bindValue(":PROFILEID$key",$val,PDO::PARAM_STR);

            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profileid=$result['PROFILEID'];
                $profiles[$profileid] =$result['EXPIRY_DT'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }

    /**
     *
     * Get expiry date, serviceid of viewed profile for instant EOI
     *
     * <p>
     * This function gets the membership expiry date and service id of viewed profile for instant EOI mailer.
     * </p>
     *
     * @param $profileid Integer
     * @throws jsExcetion
     * @return array
     */
    public function getExpiryDateForInstantEOIMailer($profileid) {
        try {
            $sql = "SELECT EXPIRY_DT,SERVICEID from billing.SERVICE_STATUS where PROFILEID=:PROFILEID AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' order by ID desc";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            return array($result[EXPIRY_DT], $result[SERVICEID]);
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$lessThanArray="",$fields="PROFILEID")
    {
        if(!$valueArray && !$excludeArray  && !$greaterThanArray)
            throw new jsException("","no where conditions passed");
        try
        {
            $sqlSelectDetail = "SELECT $fields FROM billing.SERVICE_STATUS WHERE ";
            $count = 1;
            if(is_array($valueArray))
            {
                foreach($valueArray as $param=>$value)
                {
                    if($count == 1)
                        $sqlSelectDetail.=" $param IN ($value) ";
                    else
                        $sqlSelectDetail.=" AND $param IN ($value) ";
                    $count++;
                }
            }
            if(is_array($excludeArray))
            {
                foreach($excludeArray as $excludeParam => $excludeValue)
                {
                    if($count == 1)
                        $sqlSelectDetail.=" $excludeParam NOT IN ($excludeValue) ";
                    else
                        $sqlSelectDetail.=" AND $excludeParam NOT IN ($excludeValue) ";
                    $count++;
                }
            }
            if(is_array($greaterThanArray))
            {
                foreach($greaterThanArray as $gParam => $gValue)
                {
                    if($count == 1)
                        $sqlSelectDetail.=" $gParam > '$gValue' ";
                    else
                        $sqlSelectDetail.=" AND $gParam > '$gValue' ";
                    $count++;
                }
            }
            if(is_array($lessThanArray))
            {
                foreach($lessThanArray as $gParam => $gValue)
                {
                    if($count == 1)
                        $sqlSelectDetail.=" $gParam <= '$gValue' ";
                    else
                        $sqlSelectDetail.=" AND $gParam <= '$gValue' ";
                    $count++;
                }
            }
            $sqlSelectDetail.=" AND SERVEFOR LIKE '%F%' ORDER BY EXPIRY_DT";
            $resSelectDetail = $this->db->prepare($sqlSelectDetail);
            $resSelectDetail->execute();
            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
            {
                $detailArr[] = $rowSelectDetail;
            }
            return $detailArr;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
        return NULL;
    }

    public function getLastExpiryDate($profileid) {
        try {

            $sql = "SELECT if(datediff(now(),EXPIRY_DT)<0,datediff(now(),ACTIVATED_ON),datediff(now(),EXPIRY_DT)) as EXP_DATE,EXPIRY_DT from billing.SERVICE_STATUS where PROFILEID=:PROFILEID and SERVEFOR LIKE '%F%' and ACTIVATED_ON!='0000-00-00' and ACTIVE IN('Y','E') order by EXPIRY_DT DESC LIMIT 1";

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();

            if($records = $prep->fetch(PDO::FETCH_ASSOC))
            {
                return array($records['EXPIRY_DT'], $records['EXP_DATE']);
            }
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function fetchProfilesFromServiceStatus()
    {
        try
        {
            $sql= "SELECT PROFILEID FROM billing.SERVICE_STATUS WHERE ACTIVE='Y' EXPIRY_DT < DATE_SUB(:EXPIRY_DATE,INTERVAL 10 DAY)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPIRY_DATE",date("Y-m-d"),PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result['PROFILEID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }

    public function getLastExpiry($profileid)
    {
        try{
            $sql="SELECT MAX(EXPIRY_DT) AS EXP_DT FROM billing.SERVICE_STATUS WHERE (SERVEFOR LIKE '%F%' OR SERVEFOR='X') AND ACTIVE IN('Y','E') AND PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $expDate=$result['EXP_DT'];
        }
        catch(Exception $e){
            throw new jsException($e);
        }
        return $expDate;

    }

    public function getBillId($profileId)
    {
        try
        {
            $sql="SELECT BILLID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%F%' ORDER BY EXPIRY_DT DESC"; 
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $res[$row['BILLID']] = $row['EXPIRY_DT'];
            }
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
        return $res;
    }

    public function getBillId1($profileId)
    {
        try
        {
            $sql="SELECT MAX(EXPIRY_DT) AS EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%F%' AND ACTIVE='Y'";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_STR);
            $prep->execute();
            if($row = $prep->fetch(PDO::FETCH_ASSOC))
                return $row['EXPIRY_DT'];
            return;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
        return $res;
    }

    public function fetchEverPaidProfiles()
    {
        try
        {
            $days90 = date("Y-m-d",time()-90*86400);
            $sql= "SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE EXPIRY_DT>='$days90' AND SERVEFOR LIKE '%F%' AND ACTIVE='E'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPIRY_DATE",date("Y-m-d"),PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result['PROFILEID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }

    public function isExclusiveActive($profileId)
    {
        try
        {
            $sql="SELECT BILLID FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVICEID LIKE '%X%' AND ACTIVE='Y'"; 
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    /**
    * get service id of active JsExclusive service
    *
    * @param $profileId
    */
    public function getActiveJsExclusiveServiceID($profileId)
    {
        try
        {
            $sql="SELECT SERVICEID FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVICEID LIKE '%X%' AND ACTIVATED='Y' AND ACTIVE='Y'"; 
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function checkJsExclusiveServiceIDEver($profileId)
    {
        try
        {
            $sql="SELECT SERVICEID FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVICEID LIKE '%X%'"; 
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function getLastActiveServiceDetails($profileid)
    {
        try
        {
            $sql="SELECT EXPIRY_DT,SERVEFOR,SERVICEID,DATEDIFF(EXPIRY_DT,CURDATE()) AS DIFF,DATEDIFF(ACTIVATED_ON,CURDATE()) AS ACTIVE_DIFF,ACTIVATED_ON FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%F%' AND ACTIVE<> 'N' ORDER BY EXPIRY_DT DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getLastActiveServiceDetailsWithoutMainFlag($profileid)
    {
        try
        {
            $sql="SELECT EXPIRY_DT,SERVEFOR,SERVICEID,DATEDIFF(EXPIRY_DT,CURDATE()) AS DIFF,ACTIVATED_ON FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND ACTIVE<> 'N' ORDER BY EXPIRY_DT DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getRenewalProfiles($expiryDate)
    {
        try
        {
            $sql="SELECT PROFILEID, MAX(EXPIRY_DT) AS EXPIRY_DT FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND ACTIVE = 'Y' GROUP BY PROFILEID HAVING EXPIRY_DT=:EXPIRY_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPIRY_DT",$expiryDate,PDO::PARAM_STR);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row['PROFILEID'];
            }
            return $res;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getActiveServicesListForProfileArr($profileArr)
    {
        $profileList = implode(",", $profileArr);
        try
        {
            $sql="SELECT PROFILEID, GROUP_CONCAT(SERVICEID SEPARATOR ', ') AS SERVICEID FROM billing.SERVICE_STATUS WHERE PROFILEID IN ($profileList) AND ACTIVE='Y' GROUP BY PROFILEID"; 
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($res = $prep->fetch(PDO::FETCH_ASSOC)){
                $sid[$res['PROFILEID']] = $res['SERVICEID'];
            }
            return $sid;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    
    public function getActiveConsumeProfileDetails($profileid)
    {
        try
        {
            $sql="SELECT ID,TOTAL_COUNT,USED_COUNT,SERVEFOR,ACTIVE FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND ACTIVE='Y' AND SERVEFOR IN ('I') ORDER BY BILLID ASC";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);   
            $prep->execute();
            while($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profiles[] = $res;
            return $profiles;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function updateConsumeCount($id, $count, $activeStatus)
    {
        try
        {
            $sql="UPDATE billing.SERVICE_STATUS SET USED_COUNT=(USED_COUNT+:COUNT)";
            if($activeStatus){
                $sql .= " , ACTIVE=:ACTIVE_STATUS WHERE ID=:ID";
            } else {
                $sql .= " WHERE ID=:ID";
            }
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":COUNT",$count,PDO::PARAM_INT);   
            $prep->bindValue(":ID",$id,PDO::PARAM_INT);   
            if($activeStatus){
                $prep->bindValue(":ACTIVE_STATUS",$id,PDO::PARAM_STR);   
            }
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getAllCountForProfileid($profileid)
    {
        try
        {
            $sql="SELECT TOTAL_COUNT,USED_COUNT,SERVEFOR,ACTIVE FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR IN ('I')";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);   
            $prep->execute();
            while($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profiles[] = $res;
            return $profiles;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function fetchSubscriptionStatusDetails($profileid, $cur_date)
    {
        try
        {
            $sql="SELECT SERVICEID, ADDON_ID,ACTIVATED_ON, EXPIRY_DT FROM billing.SERVICE_STATUS sc left join (SELECT VAS_ID,RANK FROM billing.ADDON_RANK WHERE MSID='ALL') ar on SUBSTRING(sc.SERVICEID,1,1)=ar.VAS_ID WHERE PROFILEID=:PROFILEID AND ACTIVE='Y' AND EXPIRY_DT>=:EXPIRY_DT ORDER BY ar.RANK DESC,EXPIRY_DT DESC";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);  
            $prep->bindValue(":EXPIRY_DT",$cur_date,PDO::PARAM_STR);   
            $prep->execute();
            while($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profiles[] = $res;
            return $profiles;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function genericServiceInsert($paramsStr, $valuesStr){
        if(empty($paramsStr) || empty($valuesStr)){
            throw new jsException("Error processing genericServiceInsert");
        }
        try 
        {
            $sql = "INSERT INTO billing.SERVICE_STATUS ({$paramsStr}) VALUES ({$valuesStr})";
            $prep=$this->db->prepare($sql);
            $prep->execute();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getActiveSuscriptionString($profileid)
    {
        try
        {
            $sql="SELECT SERVEFOR FROM billing.SERVICE_STATUS WHERE ACTIVE='Y' AND ACTIVATED='Y' AND EXPIRY_DT>=CURDATE() AND PROFILEID=:PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);  
            $prep->execute();

            while($res = $prep->fetch(PDO::FETCH_ASSOC)){
                $subscription[] = $res['SERVEFOR'];
            }

            $subscription_string = implode(",", @array_unique($subscription));
            return $subscription_string;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function fetchAllServiceDetailsForBillid($billid) {
        try {
            $sql = "SELECT * from billing.SERVICE_STATUS WHERE BILLID=:BILLID";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":BILLID", $billid, PDO::PARAM_INT);
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

	public function getIsRenewableEverContent($profileid, $billing_dt) {
        try {
            $sql = "SELECT ss.BILLID, EXPIRY_DT, ss.SERVICEID, DATEDIFF(EXPIRY_DT, :BILLING_DT) AS DIFF FROM billing.SERVICE_STATUS ss JOIN billing.PURCHASES p USING ( BILLID, PROFILEID ) WHERE ss.PROFILEID=:PROFILEID AND ss.SERVEFOR LIKE '%F%' AND ACTIVE <> 'N' AND STATUS='DONE' AND ENTRY_DT < :BILLING_DT ORDER BY EXPIRY_DT DESC LIMIT 1";
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $resSelectDetail->bindValue(":BILLING_DT", $billing_dt, PDO::PARAM_STR);
            $resSelectDetail->execute();
            if ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $res = $rowSelectDetail;
            }
            return $res;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }    

    public function updateActiveStatus($activeStatus, $billid)
    {
        try
        {
            $sql="UPDATE billing.SERVICE_STATUS SET ACTIVE=:STATUS WHERE BILLID=:BILLID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":STATUS",$activeStatus,PDO::PARAM_STR);   
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);   
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getLastMainExpiryDate($profileid) {
        try {

            $sql = "SELECT EXPIRY_DT,SERVICEID,DATEDIFF(EXPIRY_DT,CURDATE()) AS DIFF FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND SERVEFOR LIKE '%F%' AND ACTIVE IN ('Y','E') ORDER BY EXPIRY_DT DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            if($records = $prep->fetch(PDO::FETCH_ASSOC))
            {
                return $records;
            }
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /*function to get info of unexpired current main service for profiles
    * @param : $profileidArr,$cur_date
    * @return : $result---array of details profileid wise
    */
    public function getLatestActiveMemInfoForProfiles($profileidArr,$fields="ACTIVATED_ON") {
        try {
            if(is_array($profileidArr) && $profileidArr)
            {
                $valuesStr = implode("','", $profileidArr);
                $sql = "SELECT PROFILEID,".$fields." FROM billing.SERVICE_STATUS WHERE ACTIVATED = 'Y' AND ACTIVE = 'Y' AND SERVEFOR LIKE '%F%' AND PROFILEID IN ('".$valuesStr."')";
                $prep = $this->db->prepare($sql);
                $prep->execute();
                $fieldsArr = explode(",", $fields);
                while($records = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    foreach ($fieldsArr as $key => $value) {
                        if($value!='PROFILEID')
                            $result[$records['PROFILEID']][$value] = $records[$value];
                    } 
                }
                return $result;
            }
            else
                return null;
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getProfilesServiceBased($serviceId)
    {
        try
        {
	    $serviceId =$serviceId."%";
	    $expiryDate =date("Y-m-d");  	
            $sql="SELECT distinct PROFILEID FROM billing.SERVICE_STATUS WHERE EXPIRY_DT>=:EXPIRY_DT AND SERVICEID LIKE :SERVICEID AND ACTIVE='Y'";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":EXPIRY_DT",$expiryDate,PDO::PARAM_STR);
	    $prep->bindValue(":SERVICEID",$serviceId,PDO::PARAM_STR);	
            $prep->execute();
            while($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profiles[] = $res['PROFILEID'];
            return $profiles;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    
    /*@desc: Get Active Subscription Details
     * @input: profileid, serviceid
     * @output: profileid, serviceid, activated_on, 
     */
    
    public function getActiveSubscriptionDetail($profileid, $serviceid){
        try{
            $sql = "SELECT PROFILEID, SERVICEID, ACTIVATED_ON, EXPIRY_DT from billing.SERVICE_STATUS WHERE PROFILEID = :PROFILEID AND SERVICEID LIKE '$serviceid%' AND ACTIVATED = 'Y' AND ACTIVE = 'Y'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function getCurrentlyPaidProfiles($profileidArr){
        try{
            if(is_array($profileidArr)){
                $profileIdStr = implode(",", $profileidArr);
                $sql = "SELECT DISTINCT PROFILEID from billing.SERVICE_STATUS WHERE PROFILEID IN ($profileIdStr) AND ACTIVE = :ACTIVE AND ACTIVATED = :ACTIVATED AND SERVEFOR LIKE '%F%'";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":ACTIVATED", 'Y', PDO::PARAM_STR);
                $prep->bindValue(":ACTIVE", 'Y', PDO::PARAM_STR);
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                    $result[] = $row['PROFILEID'];
                }
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /*fetch billing details by bill id for profileid's
    * @input : $billId(array or single int value),$fields="*",$serveFor=""
    * @output: $rows
    */
    public function fetchServiceDetailsByBillId($billId,$fields="*",$serveFor="")
    {
        try
        {
            if(is_array($billId) && $billId)
            {
                $valueStr = "BILLID IN (".implode(",", $billId).")";
            }
            else if($billId)
            {
                $valueStr = "BILLID = :BILLID";
            }
            if($valueStr)
            {
                $sql= "SELECT ".$fields." FROM billing.SERVICE_STATUS WHERE ";
                if($serveFor)
                    $sql = $sql."SERVEFOR LIKE :SERVEFOR AND ".$valueStr;
                else
                    $sql = $sql.$valueStr;
                $prep = $this->db->prepare($sql);
                if(!is_array($billId))
                    $prep->bindValue(":BILLID", $billId, PDO::PARAM_INT);
                if($serveFor)
                    $prep->bindValue(":SERVEFOR", $serveFor, PDO::PARAM_STR);
                $prep->execute();
                while($result=$prep->fetch(PDO::FETCH_ASSOC))
                {
                   $rows[$result['PROFILEID']] = $result; 
                }
                return $rows;
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $result;
    }
    // get expired profiles for date	
    public function getExpiredProfilesForDate($dateSet1, $dateSet2){
        try{
            $sql = "SELECT PROFILEID,ACTIVATED_ON from billing.SERVICE_STATUS WHERE EXPIRY_DT>=:EXPIRY_DT1 AND EXPIRY_DT<=:EXPIRY_DT2 AND SERVEFOR LIKE '%F%'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPIRY_DT1", $dateSet1, PDO::PARAM_STR);
	    $prep->bindValue(":EXPIRY_DT2", $dateSet2, PDO::PARAM_STR);	
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row['PROFILEID']] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function updateActivationDates($billid, $serviceid, $start_date, $end_date)
    {
        try
        {
            $sql  = "UPDATE billing.SERVICE_STATUS SET ACTIVATE_ON='0000-00-00', ACTIVATED_ON=:ACTIVATED_ON, EXPIRY_DT=:EXPIRY_DT WHERE BILLID=:BILLID AND SERVICEID=:SERVICEID LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ACTIVATED_ON", $start_date, PDO::PARAM_STR);
            $prep->bindValue(":EXPIRY_DT", $end_date, PDO::PARAM_STR);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function updateActiveStatusForBillidAndServiceid($billid, $serviceid, $status)
    {
        try
        {
            $sql  = "UPDATE billing.SERVICE_STATUS SET ACTIVE=:STATUS, ACTIVATED='Y' WHERE BILLID=:BILLID AND SERVICEID=:SERVICEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $prep->bindValue(":SERVICEID", $serviceid, PDO::PARAM_STR);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getProfileidForBillid($billid)
    {
        try
        {
            $sql  = "SELECT PROFILEID FROM billing.SERVICE_STATUS WHERE BILLID=:BILLID LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->execute();
            if ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output = $row['PROFILEID'];
            }
            return $output;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getActiveServeFor($profileid)
    {
        try
        {
            $sql  = "SELECT ID, SERVEFOR FROM billing.SERVICE_STATUS WHERE PROFILEID=:PROFILEID AND ACTIVE='Y' AND ACTIVATED='Y'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $row['SERVEFOR'];
            }
            return implode(",",$output);
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getRenewalProfilesDetailsInRange($startDt, $endDt)
    {
        try
        {
            $sql="SELECT BILLID, PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND ACTIVE = 'Y' AND EXPIRY_DT>=:START_DATE AND EXPIRY_DT<=:END_DATE ORDER BY EXPIRY_DT, PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE",$startDt,PDO::PARAM_STR);
            $prep->bindValue(":END_DATE",$endDt,PDO::PARAM_STR);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            }
            return $res;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getRenewalProfilesDetailsInRangeWithoutActiveCheck($startDt, $endDt)
    {
        try
        {
            $sql="SELECT BILLID, PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND EXPIRY_DT>=:START_DATE AND EXPIRY_DT<=:END_DATE ORDER BY EXPIRY_DT, PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE",$startDt,PDO::PARAM_STR);
            $prep->bindValue(":END_DATE",$endDt,PDO::PARAM_STR);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            }
            return $res;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }


    public function fetchTFNSMSProfiles($curDate)
    {
        try
        {
            $thirtyTwoDays = date("Y-m-d", strtotime($curDate)+32*24*60*60);
            $sql="SELECT PROFILEID, BILLID, SERVICEID FROM billing.SERVICE_STATUS WHERE ACTIVATED='Y' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' AND DATE(EXPIRY_DT)=:EXPIRY_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EXPIRY_DT",$thirtyTwoDays,PDO::PARAM_STR);
            $prep->execute();
            $res = array();
            while ($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            } 
            return $res;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function filterActiveProfilesFromBillidArr($billIdArr)
    {
        if(!is_array($billIdArr) || empty($billIdArr)) {
            return NULL;
        }
        try
        {
            $billStr = implode(",", $billIdArr);
            $sql="SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE ACTIVATED='Y' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' AND BILLID IN ($billStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $res = array();
            while ($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            } 
            return $res;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getEligibleProfileForRBHandling($profileid,$serviceid,$startDate) {
         try{
             $sql = "SELECT PROFILEID FROM billing.SERVICE_STATUS WHERE PROFILEID = :profileid AND ACTIVATED_ON >= :startDate AND ACTIVATED = :status AND ACTIVE = :status AND SERVICEID LIKE :serviceid ;" ;
 
             $serviceid = "%".$serviceid."%";
             $status = "Y";
 
             $prep = $this->db->prepare($sql);
             $prep->bindValue(':profileid',$profileid,PDO::PARAM_INT);
             $prep->bindValue(':startDate',$startDate,PDO::PARAM_STR);
             $prep->bindValue(':status',$status,PDO::PARAM_STR);
             $prep->bindValue(':serviceid',$serviceid,PDO::PARAM_STR);
             $prep->execute();
             $prep->setFetchMode(PDO::FETCH_ASSOC);
             
             while($row = $prep->fetch()){
                 $result[] = $row;
             }
             return $result;
         } catch (Exception $ex){
             throw new jsException($ex);
         }
     }
}