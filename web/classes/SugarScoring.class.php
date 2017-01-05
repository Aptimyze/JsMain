<?php

/** 
* @author Vibhor Garg 
* @copyright Copyright 2010, Infoedge India Ltd.
*/
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class Sugar_Variables
{
	public $ID_C;
	public $GENDER_C;
	public $RELIGION_C;
	public $CASTE_C;
	public $MOTHER_TONGUE_C;
	public $MARITAL_STATUS_C;
	public $OCCUPATION_C;
	public $EDUCATION_C;
	public $INCOME_C;
	public $AGE_C;
	public $HAVE_PHOTO_C;

	public function __construct($profileid,$myDb,$parameter)
        {
                $this->setAllVariables($profileid,$myDb,$parameter);
        }

        public function getID_C()
        {
                return $this->ID_C;
        }
	public function setID_C($id_c)
	{
		$this->ID_C=$id_c;
	}
        public function getGENDER_C()
        {
                return $this->GENDER_C;
        }
        public function setGENDER_C($gender_c)
        {
                $this->GENDER_C=$gender_c;
        }
	public function getRELIGION_C()
        {
                return $this->RELIGION_C;
        }
        public function setRELIGION_C($religion_c)
        {
                $this->RELIGION_C=$religion_c;
        }
        public function getCASTE_C()
        {
                return $this->CASTE_C;
        }
        public function setCASTE_C($caste_c)
        {
                $this->CASTE_C=$caste_c;
        }
        public function getMOTHER_TONGUE_C()
        {
                return $this->MOTHER_TONGUE_C;
        }
        public function setMOTHER_TONGUE_C($mother_tongue_c)
        {
                $this->MOTHER_TONGUE_C=$mother_tongue_c;
        }
	public function getMARITAL_STATUS_C()
        {
                return $this->MARITAL_STATUS_C;
        }
        public function setMARITAL_STATUS_C($marital_status_c)
        {
                $this->MARITAL_STATUS_C=$marital_status_c;
        }
	public function getOCCUPATION_C()
        {
                return $this->OCCUPATION_C;
        }
        public function setOCCUPATION_C($occupation_c)
        {
                $this->OCCUPATION_C=$occupation_c;
        }
	public function getEDUCATION_C()
        {
                return $this->EDUCATION_C;
        }
        public function setEDUCATION_C($education_c)
        {
                $this->EDUCATION_C=$education_c;
        }
	public function getINCOME_C()
        {
                return $this->INCOME_C;
        }
        public function setINCOME_C($income_c)
        {
                $this->INCOME_C=$income_c;
        }
	public function getAGE_C()
        {
                return $this->AGE_C;
        }
        public function setAGE_C($age_c)
        {
                $this->AGE_C=$age_c;
        }
        public function getHAVE_PHOTO_C()
        {
		return $this->HAVE_PHOTO_C;
        }
        public function setHAVE_PHOTO_C($have_photo_c)
        {
		$this->HAVE_PHOTO_C=$have_photo_c;
        }
	/**
	* This function is used to set all the required variables of a lead.
	*/
	public function setAllVariables($leadid,$myDb,$parameter="*")
	{
		if($leadid)
                        $this->ID_C=$leadid;
                $sql="SELECT $parameter FROM sugarcrm.leads_cstm WHERE id_c='$leadid'";
                $result = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                $myrow = mysql_fetch_array($result);
		if($myrow)
                {
                        foreach ($myrow as $key => $value)
                                $this->$key=$value;
                }
	}
	 /**
        * This function is used to return the bias of the variable based on gender.
        */
        public function giveVariable_bias($param,$param_val,$gtype,$myDb)
        {
		if($param=='CASTE_C')
		{
			$param_val_temp=explode("_",$param_val);
			$param_val=$param_val_temp[1];
			unset($param_val_temp);
		}
		elseif($param=='AGE_C')
		{
			if($param_val>=18 && $param_val<20)
				$param_val='18-20';
			if($param_val>=20 && $param_val<23)
                                $param_val='20-23';
			if($param_val>=23 && $param_val<26)
                                $param_val='23-26';
			if($param_val>=26 && $param_val<30)
                                $param_val='26-30';
			if($param_val>=30 && $param_val<35)
                                $param_val='30-35';
                        if($param_val>=35 && $param_val<40)
                                $param_val='35-40';
                        if($param_val>=40)
                                $param_val='40+';
		}
                if(is_numeric($param_val))
                        $sql = "select SQL_CACHE bias from sugarcrm.bias where param_name='$param' AND param_val=$param_val AND gender='$gtype'";
                else
                        $sql = "select SQL_CACHE bias from sugarcrm.bias where param_name='$param' AND param_val='$param_val' AND gender='$gtype'";
                $res = mysql_query_decide($sql,$myDb) or die($sql.mysql_error($myDb));
                if($row = @mysql_fetch_array($res))
                        return $row["bias"];
                else
                        return 0;
        }
}
?>
