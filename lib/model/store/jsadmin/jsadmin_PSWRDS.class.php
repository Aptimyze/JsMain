<?php
class jsadmin_PSWRDS extends TABLE
{
    private static $instance;

    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    /**
         * @fn getInstance
         * @brief fetches the instance of the class
         * @param $dbName - Database name to which the connection would be made
     * @return instance of this class
         */
    public static function getInstance($dbName='')
    {
            if(!$dbName)
                $dbName="newjs_master";
            if(isset(self::$instance))
            {
                    //If different instance is required
                    if($dbName != self::$instance->dbName){
                            $class = __CLASS__;
                            self::$instance = new $class($dbName);
                    }
            }
            else
            {
                    $class = __CLASS__;
                    self::$instance = new $class($dbName);
            }
            return self::$instance;
    }

    //functions for innodb transactions
    public function startTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->db->commit();
    }

    public function getPrivilage($user)
    {
        try
        {
            $sql="select PRIVILAGE from jsadmin.PSWRDS where RESID=:USER";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USER",$user,PDO::PARAM_INT);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $priv=$result['PRIVILAGE'];

        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $priv;
    }

    public function fetchExecutivesWithCenter($center)
    {
        try
        {
            $last20Days=date("Y-m-d H:i:s",time()-20*86400);
            $sql="SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%PREALL%' and UPPER(PSWRDS.SUB_CENTER)=:CENTER AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":CENTER",$center,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $agents[] = $result['USERNAME'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $agents;

    }

    public function fetchAgentsWithPriviliges($priv)
    {
        try
        {
            $last20Days=date("Y-m-d H:i:s",time()-20*86400);
            $sql="SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like :PRIVILIGE AND ACTIVE='Y' AND LAST_LOGIN_DT>=:LAST20Days";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PRIVILIGE",$priv,PDO::PARAM_STR);
            $prep->bindValue(":LAST20Days",$last20Days,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $agents[] = $result['USERNAME'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $agents;

    } 

    public function fetchCentersOfAgents()
    {
        try
        {
            $todayDate=date("Y-m-d");
            $last20Days=date("Y-m-d H:i:s",JSstrToTime("$todayDate -20 days"));
            $sql="SELECT DISTINCT CENTER from jsadmin.PSWRDS WHERE LAST_LOGIN_DT>=:LAST20DAYS";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LAST20DAYS",$last20Days,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $center[] = $result['CENTER'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $center;

    }

    public function getExecutiveDetails($username)
    {
        try
        {
            $last20Days=date("Y-m-d H:i:s",time()-20*86400);
            $sql="SELECT * from jsadmin.PSWRDS where USERNAME=:USERNAME AND ACTIVE='Y' AND LAST_LOGIN_DT>=:LAST20Days";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->bindValue(":LAST20Days",$last20Days,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $result;
    }

    public function getName($id)
    {
        try
        {
            $sql="select USERNAME from jsadmin.PSWRDS where RESID=:USER";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USER",$id,PDO::PARAM_INT);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $name=$result['USERNAME'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $name;
    }

    public function getEmployeeIdForHead($headId,$privilege='',$status='')
    {
        try
        {
            $privArr =@explode(",",$privilege);
            $sql="SELECT distinct EMP_ID from jsadmin.PSWRDS where HEAD_ID IN ($headId)";
            if($privilege){
                foreach($privArr as $key=>$privilege){
                    $sql .=" AND PRIVILAGE like :PRIVILAGE_$key";
                }
            }
            if($status!='ALL')
                $sql .=" AND ACTIVE='Y'";   

            $prep = $this->db->prepare($sql);
                        //$prep->bindValue(":HEAD_ID",$headId,PDO::PARAM_INT);
            foreach($privArr as $key=>$privilege)
                $prep->bindValue(":PRIVILAGE_$key",$privilege,PDO::PARAM_STR);

            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $empIdArr[] = $result['EMP_ID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $empIdArr;

    }

    public function getUsernames($employeeIdStr,$privilege='',$status='')
    {
        try
        {
            $privArr =@explode(",",$privilege);
            $sql="SELECT USERNAME from jsadmin.PSWRDS where EMP_ID IN ($employeeIdStr)";
            if($privilege){
                foreach($privArr as $key=>$privilege){
                    $sql .=" AND PRIVILAGE like :PRIVILAGE_$key";
                }
            }
            if($status!='ALL')
                $sql .=" AND ACTIVE='Y' ORDER BY USERNAME ASC";

            $prep = $this->db->prepare($sql);
            foreach($privArr as $key=>$privilege)
                $prep->bindValue(":PRIVILAGE_$key",$privilege,PDO::PARAM_STR);
                        //$prep->bindValue(":EMP_ID",$employeeIdStr,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {   
                $usernameArr[] = $result['USERNAME'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $usernameArr;
    }

    public function fetchAgentsWithPriviligesAndCenter($privilege,$center)
    {
        try
        {   
            $last20Days=date("Y-m-d H:i:s",time()-20*86400);
            $sql="SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like :PRIVILEGE AND UPPER(PSWRDS.SUB_CENTER)=:CENTER AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PRIVILEGE",$privilege,PDO::PARAM_STR);   
            $prep->bindValue(":CENTER",$center,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $agents[] = trim($result['USERNAME']);
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $agents;

    }

    public function getAllExecutives()
    {
        try
        {
            $last20Days=date("Y-m-d H:i:s",time()-20*86400);
            $sql="SELECT USERNAME from jsadmin.PSWRDS where COMPANY='JS' AND ACTIVE='Y' AND LAST_LOGIN_DT>=:LAST20Days ORDER BY USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LAST20Days",$last20Days,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $agents[] = $result['USERNAME'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $agents;
    }

    public function getAllExecutivesDetails($fields="USERNAME")
    {
        try
        {
            $last20Days=date("Y-m-d H:i:s",time()-20*86400);
            $sql="SELECT RESID,".$fields." from jsadmin.PSWRDS where COMPANY='JS' AND ACTIVE='Y' AND LAST_LOGIN_DT>=:LAST20Days";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LAST20Days",$last20Days,PDO::PARAM_STR);
            $prep->execute();
            $fieldsArr = explode(",", $fields);
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                foreach ($fieldsArr as $key => $value) {
                     $agents[$result["RESID"]][$value] = $result[$value];
                }
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $agents;
    }


    public function getPrivilegeForAgent($agent)
    {
        try
        {
            $sql="select PRIVILAGE from jsadmin.PSWRDS where USERNAME=:USER";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USER",$agent,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $priv=$result['PRIVILAGE'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $priv;
    }

    public function insertProfile($USERNAME,$PASSWORD,$EMAIL,$PRIVSTR,$CENTER,$ACTIVE,$name,$SIGN='',$PHONE='',$EMP_ID,$HEAD,$SUBLOCATION='',$FIRSTNAME,$LASTNAME,$PHOTO_URL='')
    {
        try
        {
            if(!$SUBLOCATION)
                $SUBLOCATION='';
            if(!$SIGN)
                $SIGN='';
            if(!$PHONE)
                $PHONE='';
            if(!$PHOTO_URL)
                $PHOTO_URL='';
            $SIGN = addslashes(stripslashes($SIGN));
            $sql = "INSERT INTO jsadmin.PSWRDS (USERNAME,PASSWORD,EMAIL,PRIVILAGE,CENTER,ACTIVE,MOD_DT,ENTRYBY,PHONE,SIGNATURE,LAST_LOGIN_DT,COMPANY,EMP_ID,HEAD_ID,SUB_CENTER,FIRST_NAME,LAST_NAME,PHOTO_URL) VALUES (:USERNAME,:PASSWORD,:EMAIL,:PRIVSTR,:CENTER,:ACTIVE,NOW(),:name,:PHONE,:SIGN,NOW(),'JS',:EMP_ID,:HEAD,:SUBLOCATION,:FIRSTNAME,:LASTNAME,:PHOTO_URL)";

            $prep = $this->db->prepare($sql);

            $prep->bindValue(":USERNAME",$USERNAME,PDO::PARAM_STR);
            $prep->bindValue(":PASSWORD",$PASSWORD,PDO::PARAM_STR);
            $prep->bindValue(":EMAIL",$EMAIL,PDO::PARAM_STR);
            $prep->bindValue(":PRIVSTR",$PRIVSTR,PDO::PARAM_STR);
            $prep->bindValue(":CENTER",$CENTER,PDO::PARAM_STR);
            $prep->bindValue(":ACTIVE",$ACTIVE,PDO::PARAM_STR);
            $prep->bindValue(":name",$name,PDO::PARAM_STR);
            $prep->bindValue(":PHONE",$PHONE,PDO::PARAM_STR);
            $prep->bindValue(":SIGN",$SIGN,PDO::PARAM_STR);
            $prep->bindValue(":EMP_ID",$EMP_ID,PDO::PARAM_INT);
            $prep->bindValue(":HEAD",$HEAD,PDO::PARAM_INT);
            $prep->bindValue(":SUBLOCATION",$SUBLOCATION,PDO::PARAM_STR);
            $prep->bindValue(":FIRSTNAME",$FIRSTNAME,PDO::PARAM_STR);
            $prep->bindValue(":LASTNAME",$LASTNAME,PDO::PARAM_STR);
            $prep->bindValue(":PHOTO_URL",$PHOTO_URL,PDO::PARAM_STR);

            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

    public function getId($username)
    {
        try
        {
            $sql="select RESID from jsadmin.PSWRDS where USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $resid = $result['RESID'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $resid;
    }

    public function edit($paramArr=array(),$Id)
    {
        try
        {
            foreach($paramArr as $key=>$val)
            {
                $set[] = $key." = :".$key;
            }
            $setValues = implode(",",$set);

            $sql = "UPDATE jsadmin.PSWRDS SET $setValues WHERE RESID = :ID";
            $res = $this->db->prepare($sql);
            foreach($paramArr as $key=>$val)
            {
                $res->bindValue(":".$key, $val);
            }
            $res->bindValue(":ID",$Id,PDO::PARAM_INT);
            $res->execute();
            return true;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
        return false;
    }

    /*update active status for agents with last login date lesser than given date
    * @input:$lastLoginDt,$status
    * @output: none
    */
    public function updateActiveStatus($lastLoginDt,$status)
    {
        try
        {
            $sql = "UPDATE jsadmin.PSWRDS SET ACTIVE=:ACTIVE WHERE LAST_LOGIN_DT < :LAST_LOGIN_DT";
            $res = $this->db->prepare($sql);
            $res->bindValue(":LAST_LOGIN_DT", $lastLoginDt,PDO::PARAM_STR);
            $res->bindValue(":ACTIVE",$status,PDO::PARAM_STR);
            $res->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getPhotoUrl($username)
    {
        try
        {
            $sql="select PHOTO_URL from jsadmin.PSWRDS where USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $photoUrl = $result['PHOTO_URL'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $photoUrl;
    }

    public function getCenter($username)
    {
        try
        {
            $sql="select CENTER from jsadmin.PSWRDS where USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $center = $result['CENTER'];
        }
        catch(Exception $e){
            throw new jsException($e);
        }
        return $center;
    }
    
    public function getPrivilegesForSalesTarget()
    {
        try
        {
            $sql="SELECT USERNAME, PRIVILAGE FROM jsadmin.PSWRDS WHERE ACTIVE='Y'";
            $prep = $this->db->prepare($sql);
            $prep->execute();

            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$result['USERNAME']] = $result['PRIVILAGE'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }

    public function get_Emp_Head_Id_Array($UsernameArr)
    {
        try
        {
            $UsernameStr = implode("','", $UsernameArr);
            $UsernameStr = "'".$UsernameStr."'";

            $sql="select EMP_ID, HEAD_ID from jsadmin.PSWRDS where USERNAME IN ($UsernameStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();

            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$result['EMP_ID']] = $result['HEAD_ID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }

    public function get_Emp_Name_Array($UsernameArr)
    {
        try
        {
            $UsernameStr = implode("','", $UsernameArr);
            $UsernameStr = "'".$UsernameStr."'";

            $sql="select EMP_ID, USERNAME from jsadmin.PSWRDS where USERNAME IN ($UsernameStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();

            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$result['EMP_ID']] = $result['USERNAME'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }

    public function get_All_EmpID_Name_HeadID()
    {
        try
        {
            $sql="select SQL_CACHE EMP_ID, USERNAME, HEAD_ID from jsadmin.PSWRDS where ACTIVE='Y'";
            $prep = $this->db->prepare($sql);
            $prep->execute();

            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res1[$result['EMP_ID']] = $result['USERNAME'];
                $res2[$result['EMP_ID']] = $result['HEAD_ID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return array($res1, $res2);
    }

    public function fetchAgentInfo($userArr='')
    {
        try
        {
	    if(is_array($userArr)){
	            $userArr = implode("','", $userArr);
        	    $userArr = "'".$userArr."'";
	    }	
            $sql="SELECT USERNAME, UCASE(CENTER) AS CENTER, PRIVILAGE, ACTIVE,EMAIL,UPPER(SUB_CENTER) SUB_CENTER from jsadmin.PSWRDS WHERE";
	    if($userArr)
		$sql .=" USERNAME IN($userArr)";	
	    else
		$sql .=" ACTIVE='Y'";	
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
		$res[$result['USERNAME']] =$result;
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }

    public function fetchAllDistinctCenters()
    {
        try
        {
            $sql="SELECT DISTINCT UCASE(CENTER) AS CENTER from jsadmin.PSWRDS";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                if(!$row['CENTER'])  continue;
                $center[] = $row['CENTER'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $center;
    }
    
    public function fetchAllUsernamesAndEmpID($usernameArr='')
    {
        try
        {
            $sql="SELECT DISTINCT USERNAME, EMP_ID from jsadmin.PSWRDS";
            if($usernameArr != '')
            {
                $usernameStr = implode("','", $usernameArr);
                $sql .= " WHERE USERNAME IN ('".$usernameStr."')";
            }
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                if($row['USERNAME'])
                    $res[$row['USERNAME']] = $row['EMP_ID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }

    public function get_name_priv($usernameArr='')
    {
        try
        {
            $sql="select USERNAME, PRIVILAGE from jsadmin.PSWRDS";
            if($usernameArr && is_array($usernameArr))
            {
                $usernameStr = implode("','", $usernameArr);
                $sql .= " WHERE USERNAME IN ('$usernameStr')";
            }
            $prep = $this->db->prepare($sql);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            
            while($row=$prep->fetch(PDO::FETCH_ASSOC)){
                $res[$row['USERNAME']]=$row['PRIVILAGE'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }

    public function getSubCenter($username)
    {
        try
        {
            $sql="select UPPER(SUB_CENTER) AS SUB_CENTER from jsadmin.PSWRDS where USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $subcenter = $result['SUB_CENTER'];
        }
        catch(Exception $e){
            throw new jsException($e);
        }
        return $subcenter;
    } 

    public function getEmail($username)
    {
        try
        {
            $sql="SELECT EMAIL from jsadmin.PSWRDS where USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
            $email = $result['EMAIL'];
        }
        catch(Exception $e){
            throw new jsException($e);
        }
        return $email;
    }
    
    public function fetchAgentsAndPrivilegeHavingPrivilege($priv)
    {
        try{
            $sql = "SELECT USERNAME, PRIVILAGE from jsadmin.PSWRDS where PRIVILAGE like :PRIVILIGE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PRIVILIGE",$priv,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $agents[$result['USERNAME']] = $result['PRIVILAGE'];
            }
        } catch (Exception $ex) {
            throw new jsException($e);
        }
        return $agents;
    }

    public function fetchLoggedInAgentDetails($username,$pass)
    {
        try
        {
            $sql="SELECT RESID AS agentid, LAST_LOGIN_DT as last_login_dt,PRIVILAGE as privilege,ACTIVE as active FROM jsadmin.PSWRDS WHERE USERNAME= :USERNAME AND PASSWORD=:PASSWORD";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->bindValue(":PASSWORD",$pass,PDO::PARAM_STR);
            $prep->execute();
            if($result=$prep->fetch(PDO::FETCH_ASSOC))
                return $result;
            else
                return null;
        }
        catch(Exception $e){
            throw new jsException($e);
        }
    }

    public function get($value="",$criteria="RESID",$fields="",$extraWhereClause=null){
        if(!$value)
            throw new jsException("","$criteria IS BLANK");
        try {    
            $sqlSelectDetail = "SELECT $fields FROM jsadmin.PSWRDS WHERE $criteria = :$criteria";
            if(is_array($extraWhereClause))
            {
            foreach($extraWhereClause as $key=>$val)
            {
                $sqlSelectDetail.=" AND $key=:$key";
                $extraBind[$key]=$val;
            }
            }

            $resSelectDetail = $this->db->prepare($sqlSelectDetail);
            $resSelectDetail->bindValue(":$criteria", $value, PDO::PARAM_INT);
            if(is_array($extraBind))
            foreach($extraBind as $key=>$val)
            $resSelectDetail->bindValue(":$key", $val);
            $resSelectDetail->execute();
            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
            
            return $rowSelectDetail;
        }
        catch(PDOException $e){
                throw new jsException($e);
        }
        return NULL;
    }

    public function getArray($value="",$criteria="RESID",$fields="",$extraWhereClause=null,$greaterThanClause=null,$inValue=""){
        if(!$value && !$inValue)
            throw new jsException("","$value or $inValue IS BLANK in getArray func of jsadmin_PSWRDS class");
        try {    
            $sqlSelectDetail = "SELECT $fields FROM jsadmin.PSWRDS ";
            if($inValue)
            {
                $sqlSelectDetail = $sqlSelectDetail."WHERE $criteria IN (:$criteria)";
            }
            else
            {
                if($criteria !== "PRIVILAGE")
                    $sqlSelectDetail = $sqlSelectDetail."WHERE $criteria = :$criteria";
                else
                    $sqlSelectDetail = $sqlSelectDetail."WHERE $criteria LIKE :$criteria";
            }
            if(is_array($extraWhereClause))
            {
                foreach($extraWhereClause as $key=>$val)
                {
                    $sqlSelectDetail.=" AND $key=:$key";
                    $extraBind[$key]=$val;
                }
            }
            if(is_array($greaterThanClause))
            {
                foreach($greaterThanClause as $key=>$val)
                {
                    $sqlSelectDetail.=" AND $key>=:$key";
                    $extraBind[$key]=$val;
                }
            }
            $resSelectDetail = $this->db->prepare($sqlSelectDetail);
            if($inValue)
                $resSelectDetail->bindValue(":$criteria", $inValue, PDO::PARAM_STR); 
            else 
                $resSelectDetail->bindValue(":$criteria", $value, PDO::PARAM_INT);
            if(is_array($extraBind))
            foreach($extraBind as $key=>$val)
            $resSelectDetail->bindValue(":$key", $val);
            $resSelectDetail->execute();
            while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
            {
                $result[] = $rowSelectDetail;
            }
            
            return $result;
        }
        catch(PDOException $e){
                throw new jsException($e);
        }
        return NULL;
    }

    public function fetchAgentSupervisor($username)
    {
        try
        {
            $sql="SELECT HEAD_ID FROM jsadmin.PSWRDS WHERE USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                if($result['HEAD_ID'] != 0) {
                    $sql2="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID=:EMP_ID";
                    $prep2 = $this->db->prepare($sql2);
                    $prep2->bindValue(":EMP_ID",$result['HEAD_ID'],PDO::PARAM_INT);
                    $prep2->execute();
                    if ($result2 = $prep2->fetch(PDO::FETCH_ASSOC)) {
                        return $result2['USERNAME'];
                    } else {
                        return NULL;
                    }
                } else {
                    return NULL;
                }
            }
            else {
                return NULL;
            }
        }
        catch(Exception $e){
            throw new jsException($e);
        }
    }
    
    public function getPrivilegesForSalesTargetWithLastLogin()
    {
        try
        {
            $sql="SELECT USERNAME, PRIVILAGE, LAST_LOGIN_DT FROM jsadmin.PSWRDS WHERE ACTIVE='Y'";
            $prep = $this->db->prepare($sql);
            $prep->execute();

            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$result['USERNAME']]['USERNAME'] = $result['USERNAME'];
                $res[$result['USERNAME']]['PRIVILAGE'] = $result['PRIVILAGE'];
                $res[$result['USERNAME']]['LAST_LOGIN_DT'] = $result['LAST_LOGIN_DT'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }
    
    
}
?>
