<?php

/**
 * NEWJS_JPROFILE
 *
 * This class handles all database queries to JPROFILE
 *
 * @package    jeevansathi
 * @author     Kunal Verma
 * @created    08th Juny 2016
 */
class NEWJS_JPROFILE extends TABLE
{

    /**
     * @var
     */
    private static $instance;

    /**
     * archiving
     * @var
     */
    var $activatedKey;

    /**
     * To Stop clone of this class object
     */
    private function __clone() {}

    /**
     * To stop unserialize for this class object
     */
    private function __wakeup() {}

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database name to which the connection would be made
     */
    protected function __construct($dbname = "")
    {        //$this->setActivatedKey(1); //Set default activatedKey to false i.e. disable archiving
        parent::__construct($dbname); //To connect to the database
    }

    /**
     * @fn getInstance
     * @brief fetches the instance of the class
     * @param $dbName - Database name to which the connection would be made
     * @return instance of this class
     */
    public static function getInstance($dbName = '')
    {
        if (!$dbName)
            $dbName = "newjs_master";
        if (isset(self::$instance)) {
            //If different instance is required
            if ($dbName != self::$instance->dbName) {
                $class = __CLASS__;
                self::$instance = new $class($dbName);
            }
        } else {
            $class = __CLASS__;
            self::$instance = new $class($dbName);
        }
        return self::$instance;
    }

    /**
     * @fn getActivatedKey
     * @brief fetches activatedKey value
     * @return activatedKey value
     */
    public function getActivatedKey()
    {
        return $this->activatedKey;
    }

    /**
     * @fn setActivatedKey
     * @brief Sets activatedKey for arhiving.
     * @param $activatedKey true/false
     */
    public function setActivatedKey($activatedKey)
    {
        $this->activatedKey = $activatedKey;
    }

    /**
     * @fn get
     * @brief fetches results from JPROFILE
     * @param $value Query criteria value
     * @param $criteria Query criteria column
     * @param $fields Columns to query
     * @param $where additional where parameter
     * @return results according to criteria
     * @exception jsException for blank criteria
     * @exception PDOException for database level error handling
     */
    public function get($value = "", $criteria = "PROFILEID", $fields = "", $extraWhereClause = null, $cache = false)
    {
        if (!$value)
            throw new jsException("", "$criteria IS BLANK");
        try {
            $fields = $fields ? $fields : $this->getFields(); //Get columns to query
            $defaultFieldsRequired = array("HAVE_JCONTACT", "HAVEPHOTO", "MOB_STATUS", "LANDL_STATUS", "SUBSCRIPTION", "INCOMPLETE", "ACTIVATED", "PHOTO_DISPLAY", "GENDER", "PRIVACY");
            if (!stristr($fields, "*")) {
                if ($fields) {
                    foreach ($defaultFieldsRequired as $k => $fieldName) {
                        if (!stristr($fields, $fieldName))
                            $fields .= "," . $fieldName;
                    }
                } else {
                    $fields = implode(", ", $defaultFieldsRequired);
                }
            }
            if ($cache)
                $sqlSelectDetail = "SELECT SQL_CACHE $fields FROM newjs.JPROFILE WHERE $criteria = :$criteria";
            else
                $sqlSelectDetail = "SELECT $fields FROM newjs.JPROFILE WHERE $criteria = :$criteria";
            if (is_array($extraWhereClause)) {
                foreach ($extraWhereClause as $key => $val) {
                    $sqlSelectDetail .= " AND $key=:$key";
                    $extraBind[$key] = $val;
                }
            }

            $resSelectDetail = $this->db->prepare($sqlSelectDetail);
            $resSelectDetail->bindValue(":$criteria", $value, PDO::PARAM_INT);
            if (is_array($extraBind))
                foreach ($extraBind as $key => $val)
                    $resSelectDetail->bindValue(":$key", $val);
            $resSelectDetail->execute();
            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
            return $rowSelectDetail;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
        return NULL;
    }

    /**
     * @fn getFields
     * @brief Returns column names to query
     */
    public function getFields()
    {
        $fields = sfConfig::get("mod_" . sfContext::getInstance()->getModuleName() . "_" . sfContext::getInstance()->getActionName() . "_LoggedInProfile");//Fields name set to module level module.yml
        if (!$fields)
            $fields = sfConfig::get("mod_" . sfContext::getInstance()->getModuleName() . "_default_LoggedInProfile");//Fields name set to app level module.yml
        return $fields;
    }

    public function getProfileIdsThatSatisfyConditions($equality_cond_arr = '', $between_cond = '')
    {
        if (!$equality_cond_arr && !$between_cond)
            throw new jsException("", "no where conditions passed");
        try {
            $sql = "SELECT PROFILEID from newjs.JPROFILE where ";
            if ($equality_cond_arr) {
                foreach ($equality_cond_arr as $var_name => $var_val)
                    $sql .= "$var_name=$var_val AND";
            }
            if ($between_cond)
                $sql .= " $between_cond";
            else
                $sql = substr($sql, 0, -3);
            $resSelectDetail = $this->db->prepare($sql);
            $resSelectDetail->execute();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $detailArr[] = $rowSelectDetail['PROFILEID'];
            }
            return $detailArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
        return NULL;
    }

    /**
     * @fn edit
     * @brief edits JPROFILE
     * @param $value Query criteria value
     * @param $criteria Query criteria column
     * @param $paramArr key-value pair of columns and values to edit
     * @return edits results
     * @exception jsException for blank criteria
     * @exception PDOException for database level error handling
     */
    public function edit($paramArr = array(), $value, $criteria = "PROFILEID", $extraWhereCnd = "")
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        if (!$value)
            throw new jsException("", "$criteria IS BLANK");
        try {
            foreach ($paramArr as $key => $val) {
                $set[] = $key . " = :" . $key;
            }
            $setValues = implode(",", $set);
            $sqlEditProfile = "UPDATE JPROFILE SET $setValues WHERE $criteria = :$criteria";
            if (0 !== strlen($extraWhereCnd)) {
                $sqlEditProfile .= " AND " . $extraWhereCnd;
            }

            $resEditProfile = $this->db->prepare($sqlEditProfile);
            foreach ($paramArr as $key => $val) {
                $resEditProfile->bindValue(":" . $key, $val);
            }

            $paramType = PDO::PARAM_STR;
            if (is_numeric(intval($value))) {
                $paramType = PDO::PARAM_INT;
            }

            $resEditProfile->bindValue(":$criteria", $value, $paramType);
            $resEditProfile->execute();
            return true;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function insert($paramArr = array())
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            $keys_arr = array_keys($paramArr);
            $keys = implode(",", $keys_arr);
            $values = ":" . implode(",:", $keys_arr);
            $sqlProfile = "INSERT INTO JPROFILE ( $keys ) VALUES( $values )";
            $resProfile = $this->db->prepare($sqlProfile);
            foreach ($paramArr as $key => $val) {
                $resProfile->bindValue(":" . $key, $val);
            }
            $resProfile->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * @fn fields
     * @brief Helper function for edit. Contains JPROFILE field names.
     */
    public function fields()
    {
        return array("PROFILEID", "USERNAME", "PASSWORD", "GENDER", "RELIGION", "CASTE", "MANGLIK", "MTONGUE", "MSTATUS", "DTOFBIRTH", "OCCUPATION", "COUNTRY_RES", "CITY_RES", "HEIGHT", "EDU_LEVEL", "EMAIL", "IPADD", "ENTRY_DT", "MOD_DT", "RELATION", "COUNTRY_BIRTH", "SOURCE", "INCOMPLETE", "PROMO", "DRINK", "SMOKE", "HAVECHILD", "RES_STATUS", "BTYPE", "COMPLEXION", "DIET", "HEARD", "INCOME", "CITY_BIRTH", "BTIME", "HANDICAPPED", "NTIMES", "SUBSCRIPTION", "SUBSCRIPTION_EXPIRY_DT", "ACTIVATED", "ACTIVATE_ON", "AGE", "GOTHRA", "NAKSHATRA", "MESSENGER_ID", "MESSENGER_CHANNEL", "PHONE_RES", "PHONE_MOB", "FAMILY_BACK", "SCREENING", "CONTACT", "SUBCASTE", "YOURINFO", "FAMILYINFO", "SPOUSE", "EDUCATION", "LAST_LOGIN_DT", "SHOWPHONE_RES", "SHOWPHONE_MOB", "HAVEPHOTO", "PHOTO_DISPLAY", "PHOTOSCREEN", "PREACTIVATED", "KEYWORDS", "PHOTODATE", "PHOTOGRADE", "TIMESTAMP", "PROMO_MAILS", "SERVICE_MESSAGES", "PERSONAL_MATCHES", "SHOWADDRESS", "UDATE", "SHOWMESSENGER", "PINCODE", "PRIVACY", "EDU_LEVEL_NEW", "FATHER_INFO", "SIBLING_INFO", "WIFE_WORKING", "JOB_INFO", "MARRIED_WORKING", "PARENT_CITY_SAME", "PARENTS_CONTACT", "SHOW_PARENTS_CONTACT", "FAMILY_VALUES", "SORT_DT", "VERIFY_EMAIL", "SHOW_HOROSCOPE", "GET_SMS", "STD", "ISD", "MOTHER_OCC", "T_BROTHER", "T_SISTER", "M_BROTHER", "M_SISTER", "FAMILY_TYPE", "FAMILY_STATUS", "CITIZENSHIP", "BLOOD_GROUP", "HIV", "WEIGHT", "NATURE_HANDICAP", "ORKUT_USERNAME", "WORK_STATUS", "ANCESTRAL_ORIGIN", "HOROSCOPE_MATCH", "SPEAK_URDU", "PHONE_NUMBER_OWNER", "PHONE_OWNER_NAME", "MOBILE_NUMBER_OWNER", "MOBILE_OWNER_NAME", "RASHI", "TIME_TO_CALL_START", "TIME_TO_CALL_END", "PHONE_WITH_STD", "MOB_STATUS", "LANDL_STATUS", "PHONE_FLAG", "CRM_TEAM", "SUNSIGN", "ID_PROOF_TYP", "ID_PROOF_NO", "SEC_SOURCE");
    }

    /**
     * Function to fetch profiles based on some conditions : lastlogin and registration date
     *
     * @param   $lastLoginOffset ,$lastRegistrationOffset
     * @return $profiles - array of desired profiles
     */
    public function fetchProfilesConditionBased($lastLoginOffset, $lastRegistrationOffset)
    {
        try {
            $date15 = strtotime(date('Y-m-d') . $lastLoginOffset);
            $date15daysback = date('Y-m-d', $date15);
            $date6 = strtotime(date('Y-m-d H:i:s') . $lastRegistrationOffset);
            $date6monthsback = date('Y-m-d H:i:s', $date6);
            $sql = "SELECT PROFILEID,EMAIL,USERNAME,COUNTRY_RES FROM JPROFILE WHERE DATE(LAST_LOGIN_DT)>=:LAST_LOGIN_DT AND ENTRY_DT<=:ENTRY_DT ";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LAST_LOGIN_DT", $date15daysback, PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DT", $date6monthsback, PDO::PARAM_STR);
            $prep->execute();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profilesArr[] = $res;
            return $profilesArr;
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function getProfileSelectedDetails($pid, $fields = "*", $extraWhereClause = null,$orderby="")
    {
        try {
            if (is_array($pid))
                $str = "(" . implode(",", $pid) . ")";
            else
                $str = $pid;
            $sql = "SELECT $fields FROM newjs.JPROFILE WHERE PROFILEID";
            if (is_array($pid))
                $sql = $sql . " IN " . $str;
            else
                $sql = $sql . " = " . $str;
            if (is_array($extraWhereClause)) {
                foreach ($extraWhereClause as $key => $val) {
                    if ($key == 'SUBSCRIPTION'){
                        if(empty($val)){
                            $sql .= " AND ($key LIKE '' OR $key IS NULL)";
                        }
                        else{
                            $sql .= " AND $key LIKE :$key";
                            $extraBind[$key] = $val;
                        }
                    }
                    else{
                        $sql .= " AND $key=:$key";
                        $extraBind[$key] = $val;
                    }
                }
            }
            if($orderby != ""){
                $sql .= " ORDER BY $orderby";
            }

            $prep = $this->db->prepare($sql);
            if (is_array($extraBind))
                foreach ($extraBind as $key => $val)
                    $prep->bindValue(":$key", $val);
            $prep->execute();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profilesArr[$res['PROFILEID']] = $res;
            return $profilesArr;
        } catch (Exception $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }

    }

    public function checkPhone($numberArray = '', $isd = '')
    {
        try {
            $res = null;
            $str = '';
            if ($numberArray) {
                foreach ($numberArray as $k => $num) {
                    if ($k != 0)
                        $valueArrayM['PHONE_MOB'] .= ", ";
                    $valueArrayM['PHONE_MOB'] .= "'" . $num . "'";
                }
            }
            if ($valueArrayM) {
                $returnArr = $this->getArray($valueArrayM, '', '', 'PROFILEID, PHONE_MOB, ISD,ACTIVATED,MOB_STATUS');
                $i = 0;
                if ($returnArr) {
                    foreach ($returnArr as $k => $result) {
                        $res[$i]["PROFILEID"] = $result['PROFILEID'];
                        $res[$i]["NUMBER"] = $result['PHONE_MOB'];
                        $res[$i]["TYPE"] = "MOBILE";
                        $res[$i]["ISD"] = $result['ISD'];
                        $res[$i]["ACTIVATED"] = $result['ACTIVATED'];
                        $res[$i]["MOB_STATUS"] = $result['MOB_STATUS'];

                        $i++;
                    }
                }
                $valueArrayL['PHONE_WITH_STD'] = $valueArrayM['PHONE_MOB'];
                $returnArr = $this->getArray($valueArrayL, '', '', 'PROFILEID, PHONE_WITH_STD, ISD,ACTIVATED,MOB_STATUS');
                if ($returnArr) {
                    foreach ($returnArr as $k => $result) {
                        $res[$i]["PROFILEID"] = $result['PROFILEID'];
                        $res[$i]["ISD"] = $result['ISD'];
                        $res[$i]["NUMBER"] = $result['PHONE_WITH_STD'];
                        $res[$i]["ACTIVATED"] = $result['ACTIVATED'];
                        $res[$i]["MOB_STATUS"] = $result['MOB_STATUS'];
                        $res[$i]["TYPE"] = "LANDLINE";
                        $i++;
                    }
                }
            } else
                throw new jsException("No phone number as Input paramter");

            return $res;
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    /**
     * @fn getArray
     * @brief fetches results for multiple profiles to query from JPROFILE
     * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
     * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
     * @param $fields Columns to query
     * @param $orderby string FIELDS ASC/DESC
     * @param $limit string 1/2/3
     * @return results Array according to criteria having incremented index
     * @exception jsException for blank criteria
     * @exception PDOException for database level error handling
     */

    public function getArray($valueArray = "", $excludeArray = "", $greaterThanArray = "", $fields = "PROFILEID", $lessThanArray = "", $orderby = "", $limit = "", $greaterThanEqualArrayWithoutQuote = "", $lessThanEqualArrayWithoutQuote = "", $like = "", $nolike = "", $addWhereText = "")
    {
        if (!$valueArray && !$excludeArray && !$greaterThanArray && !$lessThanArray && !$lessThanEqualArrayWithoutQuote)
            throw new jsException("", "no where conditions passed");
        try {
            if ($fields != 'returnOnlySql') {
                $fields = $fields ? $fields : $this->getFields();//Get columns to query
                $defaultFieldsRequired = array("HAVE_JCONTACT", "HAVEPHOTO", "MOB_STATUS", "LANDL_STATUS", "SUBSCRIPTION", "INCOMPLETE", "ACTIVATED", "PHOTO_DISPLAY", "GENDER", "PRIVACY");
                if (!stristr($fields, "*")) {
                    if ($fields) {
                        foreach ($defaultFieldsRequired as $k => $fieldName) {
                            if (!stristr($fields, $fieldName))
                                $fields .= "," . $fieldName;
                        }
                    } else {
                        $fields = implode(", ", $defaultFieldsRequired);
                    }
                }
            }
        
            $sqlSelectDetail = "SELECT $fields FROM newjs.JPROFILE WHERE ";
            $count = 1;
            if (is_array($valueArray)) {
                foreach ($valueArray as $param => $value) {
                    $value = $this->convertValueToQuotseparated($value);
                    if ($count == 1)
                        $sqlSelectDetail .= " $param IN ($value) ";
                    else
                        $sqlSelectDetail .= " AND $param IN ($value) ";
                    $count++;
                }
            }
            if (is_array($excludeArray)) {
                foreach ($excludeArray as $excludeParam => $excludeValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $excludeParam NOT IN ($excludeValue) ";
                    else
                        $sqlSelectDetail .= " AND $excludeParam NOT IN ($excludeValue) ";
                    $count++;
                }
            }
            if (is_array($greaterThanArray)) {
                foreach ($greaterThanArray as $gParam => $gValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $gParam > '$gValue' ";
                    else
                        $sqlSelectDetail .= " AND $gParam > '$gValue' ";
                    $count++;
                }
            }
            if (is_array($greaterThanEqualArrayWithoutQuote)) {
                foreach ($greaterThanEqualArrayWithoutQuote as $gParam => $gValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $gParam >= $gValue ";
                    else
                        $sqlSelectDetail .= " AND $gParam >= $gValue ";
                    $count++;
                }
            }
            if (is_array($lessThanArray)) {
                foreach ($lessThanArray as $gParam => $gValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $gParam < '$gValue' ";
                    else
                        $sqlSelectDetail .= " AND $gParam < '$gValue' ";
                    $count++;
                }
            }
            if (is_array($lessThanEqualArrayWithoutQuote)) {
                foreach ($lessThanEqualArrayWithoutQuote as $gParam => $gValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $gParam <= $gValue ";
                    else
                        $sqlSelectDetail .= " AND $gParam <= $gValue ";
                    $count++;
                }
            }
            if (is_array($like)) {
                foreach ($like as $gParam => $gValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $gParam LIKE '%$gValue%' ";
                    else
                        $sqlSelectDetail .= " AND $gParam LIKE '%$gValue%' ";
                    $count++;
                }
            }
            if (is_array($nolike)) {
                foreach ($nolike as $gParam => $gValue) {
                    if ($count == 1)
                        $sqlSelectDetail .= " $gParam NOT LIKE '%$gValue%' ";
                    else
                        $sqlSelectDetail .= " AND $gParam NOT LIKE '%$gValue%' ";
                    $count++;
                }
            }

            if ($addWhereText)
                $sqlSelectDetail .= " AND $addWhereText ";

            if ($orderby) {
                $sqlSelectDetail .= " order by $orderby ";
            }
            if ($limit) {
                $sqlSelectDetail .= " limit $limit ";
            }

            if ($fields == 'returnOnlySql')
                return $sqlSelectDetail;
    
            $resSelectDetail = $this->db->prepare($sqlSelectDetail);

            /*
            foreach ($valueArray as $k => $val)
            {
                $resSelectDetail->bindValue(($k+1), $val);
            }
            */
            $resSelectDetail->execute();
            $this->logGetArrayCount();
            while ($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)) {
                $detailArr[] = $rowSelectDetail;
            }
            return $detailArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
        return NULL;
    }

    private function convertValueToQuotseparated($x)
    {
        if ($x && !strstr($x, "'")) {
            $y = explode(",", $x);
            $z = implode("','", $y);
            $z = "'" . $z . "'";
            return $z;
        }
        return $x;
    }

    public function Deactive($pid)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            $sql = "update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', MOD_DT=now(),activatedKey=0 where PROFILEID=:profileid";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":profileid", $pid, PDO::PARAM_INT);
            $prep->execute();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function duplicateEmail($email)
    {
        try {
            $sql = "SELECT ACTIVATED FROM newjs.JPROFILE WHERE EMAIL = :EMAIL";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC))
                return $result[ACTIVATED];
            else
                return -1;
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }


    public function getPassword($username)
    {
        try {

            $sql = "SELECT PASSWORD FROM newjs.JPROFILE WHERE USERNAME =:USERNAME AND activatedKey=1";

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME", $username, PDO::PARAM_STR);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            $password = $res['PASSWORD'];
        } catch (Exception $e) {
            throw new jsException($e);
        }
        return $password;
    }

    public function getUsername($profileid)
    {
        try {

            $sql = "SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID =:PROID";

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            $username = $res['USERNAME'];
        } catch (Exception $e) {
            throw new jsException($e);
        }
        return $username;
    }

    public function getProfileSubscription($proid)
    {
        try {
            $sql = "SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID =:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $proid, PDO::PARAM_STR);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            $subscriptions = $res['SUBSCRIPTION'];
        } catch (Exception $e) {
            throw new jsException($e);
        }
        return $subscriptions;
    }

    /* Update Login Date Sort Date From Api Login Authentication
     * @param int profileid
     * @return int rowCount
     */

    public function updateLoginSortDate($pid)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        if (!$pid)
            throw new jsException("", "VALUE OR TYPE IS BLANK IN insertIntoLoginHistory() of NEWJS_LOG_LOGIN_HISTORY.class.php");
        try {
            $sql = "update JPROFILE set LAST_LOGIN_DT=now(),SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>SORT_DT,DATE_SUB(NOW(),INTERVAL 7 DAY),SORT_DT) where PROFILEID=:profileid";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":profileid", $pid, PDO::PARAM_INT);
            $prep->execute();
            return $prep->rowCount();

        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getLoggedInProfilesForDateRange($logindDtStart, $loginDtEnd)
    {
        try {
            $sql = "SELECT PROFILEID,USERNAME,ENTRY_DT FROM newjs.JPROFILE WHERE LAST_LOGIN_DT>=:LOGIN_DT_START AND LAST_LOGIN_DT<=:LOGIN_DT_END";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LOGIN_DT_START", $logindDtStart, PDO::PARAM_STR);
            $prep->bindValue(":LOGIN_DT_END", $loginDtEnd, PDO::PARAM_STR);
            $prep->execute();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profileIdArr[] = $res;
            return $profileIdArr;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getAllPasswords($l1, $l2)
    {
        try {

            $sql = "SELECT PROFILEID, PASSWORD FROM newjs.JPROFILE WHERE PROFILEID BETWEEN :L1 AND :L2";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":L1", $l1, PDO::PARAM_INT);
            $prep->bindValue(":L2", $l2, PDO::PARAM_INT);
            $prep->execute();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $data[] = $res;
        } catch (Exception $e) {
            throw new jsException($e);
        }
        return $data;
    }

    public function getCity($profileIdArr)
    {
        try {
            $profileIdArr = implode("','", $profileIdArr);
            $sql = "SELECT PROFILEID, CITY_RES FROM newjs.JPROFILE WHERE COUNTRY_RES=51 AND PROFILEID IN ('" . $profileIdArr . "')";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                if ($row['PROFILEID'] && $row['CITY_RES'])
                    $res[$row['PROFILEID']] = $row['CITY_RES'];
            }
            return $res;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getMembershipMailerProfiles($condition)
    {
        try {
            $sql = "SELECT PROFILEID, SUBSCRIPTION, ISD,LAST_LOGIN_DT FROM newjs.JPROFILE WHERE ACTIVATED='Y' AND " . $condition;
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $row;
            }
            return $res;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getLoggedInProfilesForPreAlloc($logindDtStart, $loginDtEnd)
    {
        try {
            //$sql = "SELECT PROFILEID,CITY_RES FROM newjs.JPROFILE WHERE LAST_LOGIN_DT>=:LOGIN_DT_START AND LAST_LOGIN_DT<:LOGIN_DT_END";
            $sql = "SELECT PROFILEID,CITY_RES,ISD,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT FROM newjs.JPROFILE WHERE LAST_LOGIN_DT>=:LOGIN_DT_START AND LAST_LOGIN_DT<=:LOGIN_DT_END";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LOGIN_DT_START", $logindDtStart, PDO::PARAM_STR);
            $prep->bindValue(":LOGIN_DT_END", $loginDtEnd, PDO::PARAM_STR);
            $prep->execute();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profileIdArr[] = $res;
            return $profileIdArr;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchSourceWiseProfiles($start_dt, $end_dt)
    {
        try {
            $sql = "SELECT PROFILEID, SOURCE, ENTRY_DT FROM newjs.JPROFILE WHERE ENTRY_DT >= :START_DATE AND ENTRY_DT <= :END_DATE AND DATEDIFF(VERIFY_ACTIVATED_DT,ENTRY_DT) >= 0 AND DATEDIFF(VERIFY_ACTIVATED_DT,ENTRY_DT) <=2";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DATE", $end_dt, PDO::PARAM_STR);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                if ($row['SOURCE']){
                    $res[$row['SOURCE']][] = $row['PROFILEID'];
                    $entryDtArr[$row['PROFILEID']] = $row['ENTRY_DT'];
                }
            }
            return array($res,$entryDtArr);
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function updateHaveJEducation($profiles)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            if ($profiles) {
                $sql = "UPDATE  `JPROFILE` SET  `HAVE_JEDUCATION` = 'Y' WHERE  `PROFILEID` IN (" . $profiles . ")";
                $prep = $this->db->prepare($sql);
                $prep->execute();
                return true;
            }
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getProfilesForDateRange($start_dt, $end_dt, $status)
    {
        try {
            $sql = "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE MOD_DT >= :START_DATE AND MOD_DT <= :END_DATE AND ACTIVATED=:ACTIVATED";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DATE", $end_dt, PDO::PARAM_STR);
            $prep->bindValue(":ACTIVATED", $status, PDO::PARAM_STR);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC))
                $res[$row['PROFILEID']] = $row['USERNAME'];
            return $res;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getSubscriptions($profileid, $field)
    {
        try {
            $sql = "SELECT " . $field . " FROM newjs.JPROFILE WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res = $result[$field];
            }
        } catch (Exception $e) {
            throw new jsException($e);
        }
        return $res;
    }

    public function updateOfflineBillingDetails($profileid)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            $sql = "UPDATE newjs.JPROFILE set PREACTIVATED = IF(ACTIVATED<>'Y', ACTIVATED, PREACTIVATED), ACTIVATED = 'Y' where PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function updateSubscriptionStatus($subscription, $profileid)
    {
        $paramArr = array('SUBSCRIPTION'=>$subscription);
        return $this->updateRecord($paramArr, $profileid, 'PROFILEID');
    }

    public function updatePrivacy($privacy, $profileid)
    {
        $paramArr = array('PRIVACY'=>$privacy, 'MOD_DT'=>date('Y-m-d H:i:s'));
        return $this->updateRecord($paramArr, $profileid, "PROFILEID","activatedKey=1");
    }

    public function SelectPrivacy($profileId)
    {
        try {
            $sql = "Select PRIVACY from newjs.JPROFILE where  activatedKey=1 and PROFILEID = :PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $prep->execute();
            $privacyVal = $prep->fetch(PDO::FETCH_ASSOC);
            $privacyVal = $privacyVal["PRIVACY"];
            return $privacyVal;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }


    public function SelectHide($profileid)
    {
        try {
            $sql = "select EMAIL,ACTIVATED,ACTIVATE_ON from newjs.JPROFILE where  PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $hideDetails[] = $prep->fetch(PDO::FETCH_ASSOC);
            return $hideDetails;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function SelectActicated($profileid)
    {
        try {
            $sql = "select PREACTIVATED from JPROFILE where  PROFILEID='$profileid'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $activate = $prep->fetch(PDO::FETCH_ASSOC);
            return $activate;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function updateHide($privacy, $profileid, $dayinterval)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            $sql = "update JPROFILE set PREACTIVATED=if(ACTIVATED<>'H',ACTIVATED,PREACTIVATED), ACTIVATED='H', ACTIVATE_ON=DATE_ADD(CURDATE(), INTERVAL $dayinterval DAY) where PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function updateUnHide($privacy, $profileid)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            $now = date('Y-m-d');
            $sql = "update JPROFILE set ACTIVATED=PREACTIVATED, ACTIVATE_ON=:ACT_ON where PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":ACT_ON", $now, PDO::PARAM_STR);
            //$prep->bindValue(":PRIVACY", $privacy, PDO::PARAM_STR);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function SelectDeleteData($profileid)
    {
        try {
            $sql = "SELECT USERNAME,EMAIL,GENDER,ACTIVATED,CONTACT,SUBSCRIPTION FROM newjs.JPROFILE WHERE  PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $activate = $prep->fetch(PDO::FETCH_ASSOC);
            return $activate;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }


    public function updateDeleteData($profileid)
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {

            $sql = "update newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', MOD_DT=now(),activatedKey=0 where PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            //$prep->bindValue(":PRIVACY", $privacy, PDO::PARAM_STR);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getEmailFromUsername($username)
    {
        try {
            $sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME", $username, PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getEmailFromProfileId($profileid)
    {
        try {
            $sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    /**
     * Function to fetch profiles(registered after given date)
     *
     * @param   $registerDate ,$fieldsRequired(default-all JPROFILE fields)
     * @return $profilesArr - array of desired profiles
     */
    public function getRegisteredProfilesAfter($registerDate, $fieldsRequired = "*")
    {
        try {
            $sql = "SELECT " . $fieldsRequired . " FROM newjs.JPROFILE WHERE ENTRY_DT>=:ENTRY_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DT", $registerDate, PDO::PARAM_STR);
            $prep->execute();

            while ($res = $prep->fetch(PDO::FETCH_ASSOC))
                $profilesArr[$res["PROFILEID"]] = $res;
            return $profilesArr;
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    /**
     * Function to update incomplete status for profiles
     *
     * @param   $profileid
     * @return update results
     */
    public function updateIncompleteProfileStatus($profileIdArray)
    {
        try {
            $inCondition = implode("','", $profileIdArray);
            $inCondition = "'" . $inCondition . "'";
            $sql = "UPDATE newjs.JPROFILE set INCOMPLETE ='Y' where PROFILEID IN ($inCondition)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            return true;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * Function to fetch profiles registered in last 3 days)
     * @return $profilesArr - array of desired profiles
     */
    public function getProfileQualityRegistationData($registerDate)
    {
        try {
            $sql = "SELECT jp.`PROFILEID` , jp.`GENDER` , jp.`MTONGUE` , jp.`ENTRY_DT` , jp.`SOURCE` , jp.`AGE` ,case when (jp.MOB_STATUS = 'Y' || jp.LANDL_STATUS = 'Y') THEN 'Y' ELSE jpc.ALT_MOB_STATUS END as MV, jp.CITY_RES AS SOURCECITY,jp.COUNTRY_RES as SOURCE_COUNTRY FROM `JPROFILE` as jp LEFT JOIN JPROFILE_CONTACT as jpc ON jpc.PROFILEID = jp.profileid	 WHERE (jp.`ENTRY_DT` >= :REG_DATE AND jp.`ENTRY_DT` < CURDATE()) AND jp.`ACTIVATED` = 'Y'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":REG_DATE", $registerDate, PDO::PARAM_STR);
            $prep->execute();
            $profilesArr = array();
            while ($res = $prep->fetch(PDO::FETCH_ASSOC)) {
                $profilesArr[$res["PROFILEID"]] = $res;
            }
            return $profilesArr;
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function getAllSubscriptionsArr($profileArr)
    {
        try {
            $profileStr = implode(",", $profileArr);
            $sql = "SELECT * FROM newjs.JPROFILE WHERE PROFILEID IN ($profileStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res[$result['PROFILEID']] = $result;
            }
        } catch (Exception $e) {
            throw new jsException($e);
        }
        return $res;
    }

    /*
     * this function returns profileids with entry date specified
     * @return - profileid array
     */
    public function getProfilesWithGivenRegDates($dateArr)
    {
        try {
            if ($dateArr && is_array($dateArr)) {
                $sql = "SELECT PROFILEID FROM newjs.JPROFILE AS I LEFT JOIN PROFILE.DPP_REVIEW_MAILER_LOG AS L ON I.PROFILEID = L.RECEIVER WHERE ((ENTRY_DT >= :FIRST_LOWER AND ENTRY_DT < :FIRST_UPPER) OR (ENTRY_DT >= :SEC_LOWER AND ENTRY_DT < :SEC_UPPER) OR (ENTRY_DT >= :THIRD_LOWER AND ENTRY_DT < :THIRD_UPPER) OR (ENTRY_DT >= :FOURTH_LOWER AND ENTRY_DT < :FOURTH_UPPER)) AND ACTIVATED = 'Y' AND L.RECEIVER IS NULL";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":FIRST_UPPER", $dateArr['first_up'], PDO::PARAM_STR);
                $prep->bindValue(":FIRST_LOWER", $dateArr['first_low'], PDO::PARAM_STR);
                $prep->bindValue(":SEC_UPPER", $dateArr['sec_up'], PDO::PARAM_STR);
                $prep->bindValue(":SEC_LOWER", $dateArr['sec_low'], PDO::PARAM_STR);
                $prep->bindValue(":THIRD_UPPER", $dateArr['third_up'], PDO::PARAM_STR);
                $prep->bindValue(":THIRD_LOWER", $dateArr['third_low'], PDO::PARAM_STR);
                $prep->bindValue(":FOURTH_UPPER", $dateArr['fourth_up'], PDO::PARAM_STR);
                $prep->bindValue(":FOURTH_LOWER", $dateArr['fourth_low'], PDO::PARAM_STR);
                $prep->execute();
                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res[] = $result;
                }
                return $res;
            }
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getEntryDtJprofile($profileArray){
        try{
            $sql = "SELECT PROFILEID,ENTRY_DT  FROM newjs.JPROFILE WHERE PROFILEID IN (";
            $COUNT=1;
            foreach($profileArray as $key => $value){
                $valueToSearch[] = ":KEY".$COUNT;
                $bind["KEY".$COUNT]["VALUE"] = $value;
                $COUNT++;
            }
            $values = implode(",",$valueToSearch).");";
            $sql .= $values;
            $prep = $this->db->prepare($sql);
            foreach($bind as $key=>$val) {
                $prep->bindValue($key, $val["VALUE"], PDO::PARAM_STR);
            }
            $prep->execute();
            while($result  = $prep->fetch(PDO::FETCH_ASSOC)){
                $res[$result["PROFILEID"]] = $result["ENTRY_DT"];
            }
            return $res;
        }catch(Exception $e){
            throw new jsException($e);
        }
    }
    /*
     * this function return array of profileids who have registered within given dates
     * @param - date after which users have registered
     * @return - array of profiledids
     */
    public function getProfilesWithinGivenActiveDate($date)
    {
        try {
            if ($date) {
                $sql = "SELECT PROFILEID FROM newjs.JPROFILE AS I LEFT JOIN PROFILE.DPP_REVIEW_MAILER_LOG AS L ON I.PROFILEID = L.RECEIVER WHERE DATE(LAST_LOGIN_DT) > :date AND ACTIVATED = 'Y' AND L.RECEIVER IS NULL";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":date", $date, PDO::PARAM_STR);
                $prep->execute();
                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res[] = $result;
                }
                return $res;
            }
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getLatestValue($field)
    {
        try {
            $sql = "SELECT " . $field . " FROM newjs.JPROFILE ORDER BY PROFILEID DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $result;
            }
        } catch (Exception $e) {
            throw new jsException($e);
        }

        return $res;
    }

    /**
     * updateProfileForArchive
     * Update Profile Columns for archive i.e. setting
     * PREACTIVATED,ACTIVATED,activatedKey,JsArchived,MOD_DT column
     * @param type $iProfileID
     * @throws jsException
     * @return rowCount
     */
    public function updateProfileForArchive($iProfileID)
    {
        try {
            $sql = "update newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', activatedKey=0,JSARCHIVED=1, MOD_DT=now() where PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $iProfileID, PDO::PARAM_INT);
            $prep->execute();
            return $prep->rowCount();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /**
     * updateProfileForBilling
     * Update Profile Columns for archive i.e. setting
     * PREACTIVATED,ACTIVATED,activatedKey,column
     * @param type $iProfileID
     * @throws jsException
     * @return rowCount
     */
    public function updateProfileForBilling($paramArr = array(), $value, $criteria = "PROFILEID", $extraStr = '')
    {
        if (!$value)
            throw new jsException("", "$criteria IS BLANK");
        try {
            if (is_array($paramArr)) {
                foreach ($paramArr as $key => $val) {
                    $set[] = $key . " = :" . $key;
                }
                if (is_array($set))
                    $setValues = implode(",", $set);
            }
            if ($setValues)
                $sqlEditProfile = "UPDATE JPROFILE SET $setValues,$extraStr WHERE $criteria = :$criteria";
            else
                $sqlEditProfile = "UPDATE JPROFILE SET $extraStr WHERE $criteria = :$criteria";

            $resEditProfile = $this->db->prepare($sqlEditProfile);
            if (is_array($paramArr)) {
                foreach ($paramArr as $key => $val) {
                    $resEditProfile->bindValue(":" . $key, $val);
                }
            }
            $paramType = PDO::PARAM_INT;

            $resEditProfile->bindValue(":$criteria", $value, $paramType);
            $resEditProfile->execute();
            return true;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * updateProfileSeriousnessCount
     * This query is in use at SugarCRM
     * @param $profileArr
     * @return bool
     */
    function updateProfileSeriousnessCount($profileArr)
    {
        if (!is_array($profileArr) || !count($profileArr)) {
            throw new jsException("Param is not array or an empty is provided");
        }

        try {
            $now = date('Y-m-d h:i:s');
            $szINs = implode(',', array_fill(0, count($profileArr), '?'));

            $sql = "UPDATE newjs.JPROFILE SET SERIOUSNESS_COUNT=SERIOUSNESS_COUNT+1,SORT_DT=? WHERE PROFILEID IN ($szINs)";
            $pdoStatement = $this->db->prepare($sql);
            $count = 1;
            $pdoStatement->bindValue($count, $now, PDO::PARAM_STR);
            //Bind Value

            foreach ($profileArr as $k => $value) {
                ++$count;
                $pdoStatement->bindValue(($count), $value, PDO::PARAM_INT);
            }
            $pdoStatement->execute();
            return true;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }


    /**
     * updateForMutipleProfiles
     * This query is in use to edit values for mutiple profiles
     * @param $paramArr
     * @param $profileArr
     * @return bool
     */
    function updateForMutipleProfiles($paramArr, $profileArr)
    {
        if (!is_array($paramArr) || !count($paramArr) || !is_array($profileArr) || !count($profileArr)) {
            throw new jsException("Param is not array or an empty is provided");
        }

        try {
            foreach ($paramArr as $key => $val) {
                $set[] = $key . " = :" . $key;
            }
            $setValues = implode(",", $set);

            foreach ($profileArr as $k => $value) {
                $pString[] = ":" . $k;
            }
            $szINs = implode(",", $pString);
            $sql = "UPDATE newjs.JPROFILE SET $setValues WHERE PROFILEID IN ($szINs)";
            $pdoStatement = $this->db->prepare($sql);

            //Bind Value
            $count = 0;
            foreach ($profileArr as $k => $value) {
                $pdoStatement->bindValue(":" . $k, $value, PDO::PARAM_INT);
            }
            foreach ($paramArr as $key => $val) {
                $pdoStatement->bindValue(":" . $key, $val);
            }

            $pdoStatement->execute();
            return true;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }


    //This function gets data for CITY_RES/MTONGUE/(AGE/GENDER) grouped by the same along with month/day as per the condition
    public function getRegistrationMisGroupedData($fromDate, $toDate, $month = '', $groupType)
    {
        try {
            if ($groupType != "") {
                if ($month == "") {
                    $sql = "SELECT COUNT(*) AS COUNT,$groupType,EXTRACT(MONTH FROM ENTRY_DT) AS MONTH FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN :FROMDATE AND :TODATE AND DATEDIFF(VERIFY_ACTIVATED_DT,ENTRY_DT)<'3' GROUP BY $groupType,MONTH";
                } else {
                    $sql = "SELECT COUNT(*) AS COUNT, $groupType,EXTRACT(DAY FROM ENTRY_DT) AS DAY FROM newjs.JPROFILE  WHERE  ENTRY_DT BETWEEN :FROMDATE AND :TODATE AND DATEDIFF(VERIFY_ACTIVATED_DT,ENTRY_DT)<'3' GROUP BY $groupType,DAY";
                }
            } else {
                return;
            }
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":FROMDATE", $fromDate, PDO::PARAM_STR);
            $prep->bindValue(":TODATE", $toDate, PDO::PARAM_STR);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $detailArr[] = $result;
            }
            return $detailArr;


        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function checkUsername($username)
    {
        try {
            $sql = "SELECT COUNT(1) AS CNT FROM newjs.JPROFILE WHERE USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME", $username, PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            return $row['CNT'];
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * update sort date in Jprofile
     * @param $profileId
     * @return bool
     */
    function updateSortDate($profileId)
    {
        try {

            $sql = "update newjs.JPROFILE set SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>=SORT_DT,DATE_ADD(SORT_DT,INTERVAL 7 DAY),SORT_DT) where PROFILEID=:PROFILEID";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(':PROFILEID', $profileId, PDO::PARAM_INT);
            $pdoStatement->execute();
            return $pdoStatement->rowCount();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /**
     * @param array $paramArr
     * @return mixed
     */
    public function insertRecord($paramArr = array())
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        try {
            $keys_arr = array_keys($paramArr);
            $keys = implode(",", $keys_arr);
            $values = ":" . implode(",:", $keys_arr);
            $sqlProfile = "INSERT INTO JPROFILE ( $keys ) VALUES( $values )";
            $resProfile = $this->db->prepare($sqlProfile);
            foreach ($paramArr as $key => $val) {
                $resProfile->bindValue(":" . $key, $val);
            }
            $resProfile->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * @param array $paramArr
     * @param $value
     * @param string $criteria
     * @param string $extraWhereCnd
     * @return bool
     */
    public function updateRecord($paramArr = array(), $value, $criteria = "PROFILEID", $extraWhereCnd = "")
    {
        if ($this->dbName == "newjs_masterRep")
            $this->setConnection("newjs_master");
        if (!$value){
            throw new jsException("", "$criteria IS BLANK");
        }

        try {
            foreach ($paramArr as $key => $val) {
                $set[] = $key . " = :" . $key;
            }
            $setValues = implode(",", $set);
            $sqlEditProfile = "UPDATE JPROFILE SET $setValues WHERE $criteria = :$criteria";
            if (0 !== strlen($extraWhereCnd)) {
                $sqlEditProfile .= " AND " . $extraWhereCnd;
            }

            $resEditProfile = $this->db->prepare($sqlEditProfile);
            foreach ($paramArr as $key => $val) {
                $resEditProfile->bindValue(":" . $key, $val);
            }

            $paramType = PDO::PARAM_STR;
            if (is_numeric(intval($value))) {
                $paramType = PDO::PARAM_INT;
            }

            $resEditProfile->bindValue(":$criteria", $value, $paramType);
            $resEditProfile->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
        return true;
    }

    public function replaceRecod()
    {

    }

    /**
     * selectRecord
     * @param string $value
     * @param string $criteria
     * @param string $fields
     * @param null $extraWhereClause
     * @param bool $cache
     * @return null
     */
    public function selectRecord($value = "", $criteria = "PROFILEID", $fields = "", $extraWhereClause = null, $cache = false)
    {
        if (!$value)
            throw new jsException("", "$criteria IS BLANK");
        try {
            $fields = $fields ? $fields : $this->getFields(); //Get columns to query
            $defaultFieldsRequired = array("HAVE_JCONTACT", "HAVEPHOTO", "MOB_STATUS", "LANDL_STATUS", "SUBSCRIPTION", "INCOMPLETE", "ACTIVATED", "PHOTO_DISPLAY", "GENDER", "PRIVACY");
            if (!stristr($fields, "*")) {
                if ($fields) {
                    foreach ($defaultFieldsRequired as $k => $fieldName) {
                        if (!stristr($fields, $fieldName))
                            $fields .= "," . $fieldName;
                    }
                } else {
                    $fields = implode(", ", $defaultFieldsRequired);
                }
            }
            if ($cache)
                $sqlSelectDetail = "SELECT SQL_CACHE $fields FROM newjs.JPROFILE WHERE $criteria = :$criteria";
            else
                $sqlSelectDetail = "SELECT $fields FROM newjs.JPROFILE WHERE $criteria = :$criteria";
            if (is_array($extraWhereClause)) {
                foreach ($extraWhereClause as $key => $val) {
                    $sqlSelectDetail .= " AND $key=:$key";
                    $extraBind[$key] = $val;
                }
            }

            $resSelectDetail = $this->db->prepare($sqlSelectDetail);
            $resSelectDetail->bindValue(":$criteria", $value, PDO::PARAM_INT);
            if (is_array($extraBind))
                foreach ($extraBind as $key => $val)
                    $resSelectDetail->bindValue(":$key", $val);
            $resSelectDetail->execute();
            $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
            //$this->logSelectCount();
     	    JsCommon::logFunctionCalling(__CLASS__, __FUNCTION__);
            return $rowSelectDetail;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
        return NULL;
    }

    public function DeactiveProfiles($profileArr)
    {
        try{
            foreach($profileArr as $k=>$v)
                $keyArr[] = ":PROFILEID".$k;
            $keyStr = implode(",",$keyArr);
            $sql="update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', activatedKey=0 where PROFILEID IN (".$keyStr.")";
            $prep = $this->db->prepare($sql);
            foreach($profileArr as $k=>$v)
                $prep->bindValue(":PROFILEID".$k,$v,PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }


    function getNewScreenProfileCount()
    {
        try
        {
            $sql = "SELECT count(J.PROFILEID) AS COUNT FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID WHERE ACTIVATED='N' AND INCOMPLETE = 'N' AND MSTATUS != '' and activatedKey=1  and MOD_DT < date_sub(now(), interval 10 minute) AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            $result  = $pdoStatement->fetch(PDO::FETCH_ASSOC);
            return $result['COUNT'];
        }
        catch (Exception $ex){
            throw new jsException($ex);
        }
    }
    function getEditScreenProfileCount()
    {
        try
        {
            $sql = "SELECT count(jp.PROFILEID) AS COUNT FROM newjs.JPROFILE jp LEFT JOIN jsadmin.MAIN_ADMIN mad ON jp.PROFILEID=mad.PROFILEID WHERE mad.PROFILEID IS NULL AND jp.ACTIVATED='Y' AND jp.INCOMPLETE <> 'Y' AND jp.SCREENING<1099511627775 and jp.activatedKey=1 and jp.MOD_DT < date_sub(now(), interval 10 minute)";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            $result  = $pdoStatement->fetch(PDO::FETCH_ASSOC);
            return $result['COUNT'];
        }
        catch(Exception $ex){
            throw new jsException($ex);
        }
    }
    public function getPhotoScreenAcceptQueueCount()
    {
        try
        {
            $sql= "SELECT count(DISTINCT J.PROFILEID) as count,J.HAVEPHOTO AS HAVEPHOTO FROM ((newjs.JPROFILE J INNER JOIN (SELECT PROFILEID, ORDERING, UPDATED_TIMESTAMP, SCREEN_BIT, GROUP_CONCAT( IF (ORDERING =  '0', IF ((CHAR_LENGTH(SCREEN_BIT) >2 AND SCREEN_BIT NOT LIKE  '1%' ) OR OriginalPicUrl =  '', 0, IF (CHAR_LENGTH(SCREEN_BIT) =1,  '1144444', SCREEN_BIT)), IF (OriginalPicUrl =  '', 0, IF (CHAR_LENGTH(SCREEN_BIT) >2, SUBSTRING( SCREEN_BIT, 2, 1 ) , SCREEN_BIT))) ORDER BY ORDERING ASC SEPARATOR ' ') AS BITS FROM PICTURE_FOR_SCREEN_NEW GROUP BY PROFILEID HAVING (SCREEN_BIT NOT IN ('0000000',  '0100000') AND BITS NOT LIKE  '%0%' AND ((BITS LIKE  '1%1%' AND ORDERING =0) OR (BITS LIKE  '%1%' AND ORDERING !=0)))) AS P ON J.PROFILEID = P.PROFILEID) LEFT JOIN jsadmin.MAIN_ADMIN M ON J.PROFILEID = M.PROFILEID AND M.SCREENING_TYPE =  'P') WHERE M.PROFILEID IS NULL AND J.PHOTOSCREEN =0 AND J.HAVEPHOTO IN ('U','Y') AND P.SCREEN_BIT NOT IN ( '0000000',  '0100000') AND J.PHOTODATE <  now() GROUP BY J.HAVEPHOTO";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            while($result  = $pdoStatement->fetch(PDO::FETCH_ASSOC))
            {
                if($result['HAVEPHOTO']=="U")
                    $return['NEW_PHOTO_ACCEPT']=$result['count'];
                if($result['HAVEPHOTO']=="Y")
                    $return['EDIT_PHOTO_ACCEPT']=$result['count'];
            }
            return $return;
        }
        catch(Exception $ex){
            throw new jsException($ex);
        }
    }
    public function getPhotoScreenProcessQueueCount()
    {
        try
        {
            $sql = "SELECT count(DISTINCT J.PROFILEID) AS count, J.HAVEPHOTO AS HAVEPHOTO FROM ((newjs.JPROFILE J INNER JOIN (SELECT PROFILEID, UPDATED_TIMESTAMP,SCREEN_BIT, GROUP_CONCAT(IF(ORDERING='0',IF(SCREEN_BIT NOT LIKE '1%' OR SCREEN_BIT LIKE '1%1%' OR OriginalPicUrl='', 0, SCREEN_BIT), IF(OriginalPicUrl='' OR SCREEN_BIT LIKE '%0%',0,IF(CHAR_LENGTH(SCREEN_BIT)>2, SUBSTRING( SCREEN_BIT, 2, 1 ), SCREEN_BIT)) ) ORDER BY ORDERING ASC SEPARATOR ' ') AS BITS FROM PICTURE_FOR_SCREEN_NEW GROUP BY PROFILEID HAVING (BITS NOT LIKE '%0%' AND BITS LIKE '%4%')) AS P ON J.PROFILEID = P.PROFILEID) LEFT JOIN jsadmin.MAIN_ADMIN M ON J.PROFILEID = M.PROFILEID AND M.SCREENING_TYPE =  'P') WHERE M.PROFILEID IS NULL AND J.PHOTOSCREEN =0 AND J.HAVEPHOTO IN  ('U','Y') AND P.SCREEN_BIT NOT IN ('0000000','0100000')  AND J.PHOTODATE < now() GROUP BY J.HAVEPHOTO";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            while($result  = $pdoStatement->fetch(PDO::FETCH_ASSOC))
            {
                if($result['HAVEPHOTO']=="U")
                    $return['NEW_PHOTO_PROCESS']=$result['count'];
                if($result['HAVEPHOTO']=="Y")
                    $return['EDIT_PHOTO_PROCESS']=$result['count'];
            }
            return $return;
        }
        catch(Exception $ex){
            throw new jsException($ex);
        }
    }

    //get email similar to supplied values
    public function getEmailLike($email)
    {
        try
        {
            $sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE EMAIL LIKE :EMAILID";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":EMAILID",$email.'%',PDO::PARAM_STR);
            $pdoStatement->execute();
            while($result  = $pdoStatement->fetch(PDO::FETCH_ASSOC))
                $return[]=$result;
            return $return;
        }
        catch(Exception $ex){
            throw new jsException($ex);
        }
    }
    //update existing email with value appended
    public function updateEmail($email,$newEmail)
    {
        try
        {
            $sql = "UPDATE newjs.JPROFILE SET EMAIL = :NEW_EMAIL WHERE EMAIL= :EMAILID";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":EMAILID",$email,PDO::PARAM_STR);
            $pdoStatement->bindValue(":NEW_EMAIL",$newEmail,PDO::PARAM_STR);
            $pdoStatement->execute();
            return $pdoStatement->rowCount();
        }
        catch(Exception $ex){
            throw new jsException($ex);
        }

    }

    //This function executes a select query on join of jprofile and incentives.name_of_user to fetch PROFILEID,EMAIL,USERNAME for the profiles that match the criteria
    public function getDataForLegal($nameArr,$age,$addressArr,$email)
    {
        $parentAddressCondition .=" OR (";

        //if both name and address are not array
        if(!is_array($nameArr) && !is_array($addressArr) && $email=="")
        {
            throw new jsException("Both usernameArr and AddressArr are empty");
        }
        try
        {
            $sql .= "SELECT J.PROFILEID, J.USERNAME, J.EMAIL, N.NAME, J.AGE, J.CONTACT, J.PARENTS_CONTACT FROM newjs.JPROFILE as J LEFT JOIN incentive.NAME_OF_USER as N ON N.PROFILEID = J.PROFILEID	 WHERE "; //N.NAME LIKE '%vikas%' AND N.NAME LIKE '%jyana%'";
            if(is_array($nameArr))
            {
                foreach($nameArr as $key=>$value)
                {
                    $nameCondition .="N.NAME LIKE :NAMEARR$key AND ";
                }
                $nameCondition = rtrim($nameCondition," AND");
                $sql .=$nameCondition;
            }
            if($age)
            {
                if(is_array($nameArr))
                    $ageCondition = " AND J.AGE = :AGE";
                else
                    $ageCondition = "J.AGE = :AGE";
                $sql .=$ageCondition;
            }
            if(is_array($addressArr))
            {
                if($ageCondition != "" || $nameCondition != "")
                {
                    $addressCondition .= " AND ((";
                }
                else
                {
                    $addressCondition .= " ((";
                }
                foreach($addressArr as $key=>$value)
                {
                    $addressCondition .= " J.CONTACT LIKE :CONTACTARR$key AND ";
                    $parentAddressCondition .=" J.PARENTS_CONTACT LIKE :PCONTACTARR$key AND ";
                }
                $addressCondition = rtrim($addressCondition,"AND ");
                $addressCondition = $addressCondition." )";
                $parentAddressCondition = rtrim($parentAddressCondition,"AND ");
                $parentAddressCondition = $parentAddressCondition." )";

                $sql .= $addressCondition.$parentAddressCondition.")";
            }
            if($email)
            {
                if(is_array($nameArr) || is_array($addressArr) || $age)
                {
                    $emailCondition .=" AND J.EMAIL LIKE :EMAIL";
                }
                else
                {
                    $emailCondition .=" J.EMAIL LIKE :EMAIL";
                }
                $sql .=$emailCondition;
            }

            $pdoStatement = $this->db->prepare($sql);
            if(is_array($nameArr))
            {
                foreach($nameArr as $key=>$value)
                {
                    $pdoStatement->bindValue(":NAMEARR".$key,'%'.$value.'%',PDO::PARAM_STR);
                }
            }
            if($age)
            {
                $pdoStatement->bindValue(":AGE",$age,PDO::PARAM_INT);
            }
            if(is_array($addressArr))
            {
                foreach($addressArr as $key=>$value)
                {
                    $pdoStatement->bindValue(":CONTACTARR".$key,'%'.$value.'%',PDO::PARAM_STR);
                    $pdoStatement->bindValue(":PCONTACTARR".$key,'%'.$value.'%',PDO::PARAM_STR);
                }
            }
            if($email)
            {
                $pdoStatement->bindValue(":EMAIL",$email.'%',PDO::PARAM_STR);
            }
            $pdoStatement->execute();
            while($result  = $pdoStatement->fetch(PDO::FETCH_ASSOC))
            {
                $finalArr[]  = $result;
            }
            return $finalArr;
        }
        catch(Exception $ex)
        {
            throw new jsException($ex);
        }
    }

    
    
    public function getActiveProfiles($totalScript=1,$currentScript=0,$lastLoginWithIn='6 months',$limitProfiles=0)
    {
        if(!is_numeric(intval($totalScript)) || !$totalScript)
        {
            throw new jsException("","totalScript is not numeric in getUncomputedProfiles OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
        }

        if(!is_numeric(intval($currentScript)))
        {
            throw new jsException("","currentScript is not numeric in getUncomputedProfiles OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
        }

        $time = new DateTime();
$time->sub(date_interval_create_from_date_string($lastLoginWithIn));

        try{
            $sql =  <<<SQL
            SELECT PROFILEID
            FROM  newjs.`JPROFILE`
            WHERE DATE(LAST_LOGIN_DT)  >=  :LAST_LOGIN_DT
            AND activatedKey=1
            AND PROFILEID MOD :T_SCRIPT = :CUR_SCRIPT
            AND ACTIVATED = 'Y'
SQL;
            if($limitProfiles)
                $sql .= ' LIMIT '. $limitProfiles;

            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":LAST_LOGIN_DT",$time->format('Y-m-d'),PDO::PARAM_STR);
            $pdoStatement->bindValue(":T_SCRIPT",$totalScript,PDO::PARAM_STR);
            $pdoStatement->bindValue(":CUR_SCRIPT",$currentScript,PDO::PARAM_STR);
            $pdoStatement->execute();

            return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /**
     *  //Function to log Select Query Count
     */
    private function logSelectCount()
    {return;
        $key = 'selCount_'.date('Y-m-d');
        JsMemcache::getInstance()->incrCount($key);

        $key .= '::'.date('H');
        JsMemcache::getInstance()->incrCount($key);
    }
    
    /**
     *  //Function to log Select Query Count
     */
    private function logGetArrayCount()
    {return;
        $key = 'getArrayCount_'.date('Y-m-d');
        JsMemcache::getInstance()->incrCount($key);

        $key .= '::'.date('H');
        JsMemcache::getInstance()->incrCount($key);
    }

    //This function is used to fetch the latest entry date in JPROFILE so as to check in MIS whether there is a lag in slave.
    public function getLatestEntryDate()
    {
        try
        {
            $sql = "SELECT date(ENTRY_DT) as ENTRY_DT FROM newjs.JPROFILE order by PROFILEID DESC Limit 1";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();
            return $pdoStatement->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

   public function getProfileIdFromUsername($username)
    {
        try {
            $sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME=:USERNAME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME", $username, PDO::PARAM_STR);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            return $row['PROFILEID'];
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getZombieProfiles($gtDate,$limit=0,$ltDate=null) 
    {
        try{
            $sql =  <<<SQL
        
            SELECT P.PROFILEID
            FROM  newjs.`JPROFILE` P
            LEFT JOIN newjs.`NEW_DELETED_PROFILE_LOG` D
            ON P.PROFILEID=D.PROFILEID
            WHERE  
            P.ACTIVATED = 'D'
            AND P.activatedKey = 0
            AND P.MOD_DT > :GT_DATE
            AND D.PROFILEID IS NULL
SQL;
        
            if($ltDate) {
                $sql .= " AND P.MOD_DT < :LT_DATE";  
            }

            if($limit) {
                $sql .= " LIMIT :LIMIT";      
            }

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":GT_DATE", $gtDate, PDO::PARAM_STR);
            
            if($ltDate) {
                $prep->bindValue(":LT_DATE", $ltDate, PDO::PARAM_STR);
            }

            if($limit) {
                $prep->bindValue(":LIMIT", $limit, PDO::PARAM_INT);   
            }

            $prep->execute();
            return $prep->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex) {
            throw new jsException($e);   
        }
    }
   public function getDetailsForPhone($phoneArray, $fields)
    {
        try {
                $i=0;
                foreach($phoneArray as $key=>$val){
                        $arr[] =":PHONE".$i;
                        $i++;
                }
                $fields.=", ACTIVATED";
                $phoneStr =implode(",", $arr);
                $sql = "SELECT SQL_CACHE $fields FROM newjs.JPROFILE WHERE PHONE_MOB IN($phoneStr) OR PHONE_WITH_STD IN($phoneStr)";
                $prep = $this->db->prepare($sql);
                $i=0; 
                foreach($phoneArray as $key=>$val){
                        $prep->bindValue(":PHONE$i", $val, PDO::PARAM_STR);
                        $i++;
                }
                $prep->execute();
                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {          
                    if($row['ACTIVATED'] != 'D')
                            $dataArr[] =$row;
                }
                return $dataArr;
        } catch (PDOException $e) {
            throw new jsException($e);
        } 
    }
    
    public function getLastLoggedInData($conditionNew,$limitStr = "0,2000")
    {
        try {
                $result = NULL;
                $sqlSelectDetail = "SELECT jp.PROFILEID FROM newjs.JPROFILE as jp LEFT JOIN newjs.JPROFILE_CONTACT as jpc ON jpc.PROFILEID = jp.profileid WHERE ".$conditionNew." ORDER BY jp.LAST_LOGIN_DT DESC LIMIT $limitStr";
                $resSelectDetail = $this->db->prepare($sqlSelectDetail);
                $resSelectDetail->execute();
                while($row = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                {
                        $result[]= $row["PROFILEID"];
                }
                return $result;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
        return NULL;
    }

    public function getProfileForNoPhotoMailer($dateConditionArr)
    {
        try
        {            
            $sql = "SELECT PROFILEID,IF(DATEDIFF(NOW( ) , ENTRY_DT) IN (".noPhotoMailerEnum::NOPHOTODATES."),1,2) as TYPE FROM newjs.JPROFILE WHERE HAVEPHOTO NOT IN (".noPhotoMailerEnum::havePhotoCondition.") AND ACTIVATED = ".noPhotoMailerEnum::ACTIVATED." AND activatedKey = ".noPhotoMailerEnum::activatedKey." AND (";
            $count=1;
            foreach($dateConditionArr as $key=>$val)
            {
                $dateTime = $val." ".noPhotoMailerEnum::TIME;
                $sqlAppend .= " (ENTRY_DT BETWEEN  :VAL".$count." AND :DATETIME".$count.") OR";
                $count++;
            }
            $sqlAppend = rtrim($sqlAppend," OR");
            $sql .=$sqlAppend.")";

            $prep = $this->db->prepare($sql);
            $i=1; 
            foreach($dateConditionArr as $key=>$val)
            {
                $dt = $val." ".noPhotoMailerEnum::TIME;
                $prep->bindValue(":VAL$i", $val, PDO::PARAM_STR);
                $prep->bindValue(":DATETIME$i", $dt, PDO::PARAM_STR);
                $i++;
            }                       
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {                          
                    $dataArr[] =$row;
            }
            return $dataArr;
            
        }
        catch (PDOException $e)
        {
            throw new jsException($e);
        }        
    }
    //This function gets data for campaigns data
        public function getRegistrationMisCampaignsData($fromDate, $toDate, $source) {
                try {
                        if ($source != "" && $source != NULL) {
                                $source_names = '"'.implode('","', array_map('addSlashes',$source)).'"';
                                $sql = "SELECT  DISTINCT(jp.PROFILEID),jp.ENTRY_DT,jp.MTONGUE,jp.COUNTRY_RES,jp.RELIGION,jp.CASTE,jp.OCCUPATION,jp.EDU_LEVEL_NEW,jp.INCOME,jp.RELATION, jp.GENDER, jp.AGE, jp.CITY_RES, jp.CASTE, jp.SOURCE, jp.INCOMPLETE,s.GROUPNAME, ct.CAMPAIGN, ct.ADNAME, ct.KEYWORD, ct.ADGROUP, ct.MEDIUM,ct.PHOTO_UPLOADED, ct.ACTIVATED_STATUS, ct.IS_PAID, ct.IS_QUALITY,IF (DATEDIFF(VERIFY_ACTIVATED_DT, jp.ENTRY_DT) < '3', 'Y','N') AS VERIFIED_ONTIME,rt.CHANNEL,ct.GCLID FROM `JPROFILE` jp LEFT JOIN MIS.CAMPAIGN_KEYWORD_TRACKING ct ON ct.PROFILEID = jp.PROFILEID LEFT JOIN MIS.SOURCE s ON s.SourceID = jp.SOURCE LEFT JOIN MIS.REG_TRACK_CHANNEL rt ON rt.PROFILEID = jp.PROFILEID AND (rt.PAGE_TYPE = 'Page1' || rt.PAGE_TYPE = 'page1') WHERE ct.PROFILEID IS NOT NULL AND jp.ENTRY_DT BETWEEN :FROMDATE AND :TODATE AND s.GROUPNAME IN ($source_names)";
                        } else {
                                $sql = "SELECT DISTINCT(jp.PROFILEID),jp.ENTRY_DT,jp.MTONGUE,jp.COUNTRY_RES,jp.RELIGION,jp.CASTE,jp.OCCUPATION,jp.EDU_LEVEL_NEW,jp.INCOME,jp.RELATION, jp.GENDER, jp.AGE, jp.CITY_RES, jp.CASTE, jp.SOURCE, jp.INCOMPLETE,s.GROUPNAME, ct.CAMPAIGN, ct.ADNAME, ct.KEYWORD, ct.ADGROUP, ct.MEDIUM,ct.PHOTO_UPLOADED, ct.ACTIVATED_STATUS, ct.IS_PAID, ct.IS_QUALITY,IF (DATEDIFF(VERIFY_ACTIVATED_DT, jp.ENTRY_DT) < '3', 'Y','N') AS VERIFIED_ONTIME,rt.CHANNEL,ct.GCLID FROM `JPROFILE` jp LEFT JOIN MIS.CAMPAIGN_KEYWORD_TRACKING ct ON ct.PROFILEID = jp.PROFILEID LEFT JOIN MIS.SOURCE s ON s.SourceID = jp.SOURCE LEFT JOIN MIS.REG_TRACK_CHANNEL rt ON rt.PROFILEID = jp.PROFILEID AND (rt.PAGE_TYPE = 'Page1' || rt.PAGE_TYPE = 'page1') WHERE ct.PROFILEID IS NOT NULL AND jp.ENTRY_DT BETWEEN :FROMDATE AND :TODATE";
                        }
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":FROMDATE", $fromDate." 00:00:00", PDO::PARAM_STR);
                        $prep->bindValue(":TODATE", $toDate." 23:59:59", PDO::PARAM_STR);
                        $prep->execute();
                        while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                $detailArr[] = $result;
                        }
                        return $detailArr;
                } catch (Exception $e) {
                        throw new jsException($e);
                }
        }
        public function getProfileCampaingnRegistationData($registerDate)
        {
            try {
                $sql = "SELECT jp.`PROFILEID` , jp.`ACTIVATED`,jp.`HAVEPHOTO` FROM `JPROFILE` as jp WHERE (jp.`ENTRY_DT` >= :REG_DATE AND jp.`ENTRY_DT` < CURDATE())";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":REG_DATE", $registerDate, PDO::PARAM_STR);
                $prep->execute();
                $profilesArr = array();
                while ($res = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $profilesArr[$res["PROFILEID"]] = $res;
                }
                return $profilesArr;
            } catch (PDOException $e) {
                /*** echo the sql statement and error message ***/
                throw new jsException($e);
            }
        }
        
        public function getMtongue($profileID){
            try{
                $sql = "SELECT MTONGUE from newjs.JPROFILE where PROFILEID = :PROFILEID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileID,PDO::PARAM_INT);
                $prep->execute();
                if($row = $prep->fetch(PDO::FETCH_ASSOC)){
                    return $row["MTONGUE"];
                }
            }catch(Exception $e){
                throw new jsException($e);
            }
        }
}

?>
