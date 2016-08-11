<?php

/** 
* @author Vibhor Garg 
* @copyright Copyright 2010, Infoedge India Ltd.
*/
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class Scoring_Variables
{
	public $PROFILEID;
	public $USERNAME;
	public $GENDER;
	public $MTONGUE;
	public $RELATION;
	public $COUNTRY_RES;
	public $CITY_RES;
	public $ENTRY_DT;
	public $DRINK;
	public $SMOKE;
	public $BTYPE;
	public $DIET;
	public $MANGLIK;
	public $HAVEPHOTO;
	public $SHOW_HOROSCOPE;
	public $AGE;
	public $YOURINFO;
	public $FATHER_INFO;
	public $SIBLING_INFO;
	public $JOB_INFO;
	public $ACTIVATED;
	public $INCOME;
	public $SUBSCRIPTION;
	public $LAST_LOGIN_DT;
	public $SOURCE;

	public function __construct($profileid,$myDb,$parameter)
        {
                $this->setAllVariables($profileid,$myDb,$parameter);
        }

        public function getPROFILEID()
        {
                return $this->PROFILEID;
        }
	public function setPROFILEID($profileid)
	{
		$this->PROFILEID=$profileid;
	}
	public function getUSERNAME()
        {
                return $this->USERNAME;
        }
        public function setUSERNAME($username)
        {
                $this->USERNAME=$username;
        }
        public function getGENDER()
        {
                return $this->GENDER;
        }
        public function setGENDER($gender)
        {
                $this->GENDER=$gender;
        }
        public function getMTONGUE()
        {
                return $this->MTONGUE;
        }
        public function setMTONGUE($mtongue)
        {
                $this->MTONGUE=$mtongue;
        }
        public function getRELATION()
        {
                return $this->RELATION;
        }
        public function setRELATION($relation)
        {
                $this->RELATION=$relation;
        }
	public function getCOUNTRY_RES()
	{
		return $this->COUNTRY_RES;
	}
	public function setCOUNTRY_RES($country_res)
	{
		$this->COUNTRY_RES=$country_res;
	}
	public function getCITY_RES()
	{
		return $this->CITY_RES;
	}
	public function setCITY_RES($city_res)
	{
		$this->CITY_RES=$city_res;
	}
	public function getENTRY_DT()
	{
		return $this->ENTRY_DT;
	}
	public function setENTRY_DT($entry_dt)
	{
		$this->ENTRY_DT=$entry_dt;
	}
	public function getDRINK()
	{
		return $this->DRINK;
	}
	public function setDRINK($drink)
	{
		$this->DRINK=$drink;
	}
	public function getSMOKE()
        {
		return $this->SMOKE;
        }
        public function setSMOKE($smoke)
        {
		$this->SMOKE=$smoke;
        }
	public function getBTYPE()
        {
		return $this->BTYPE;
        }
        public function setBTYPE($btype)
        {
		$this->BTYPE=$btype;
        }
        public function getDIET()
        {
		return $this->DIET;
        }
        public function setDIET($diet)
        {
		$this->DIET=$diet;
        }
	public function getMANGLIK()
        {
		return $this->MANGLIK;
        }
        public function setMANGLIK($manglik)
        {
		$this->MANGLIK=$manglik;
        }
        public function getHAVEPHOTO()
        {
		return $this->HAVEPHOTO;
        }
        public function setHAVEPHOTO($photo)
        {
		$this->HAVEPHOTO=$photo;
        }
	public function getSHOW_HOROSCOPE()
        {
		return $this->SHOW_HOROSCOPE;
        }
        public function setSHOW_HOROSCOPE($show_horoscope)
        {
		$this->SHOW_HOROSCOPE=$show_horoscope;
        }
        public function getAGE()
        {
		return $this->AGE;
        }
        public function setAGE($age)
        {
		$this->AGE=$age;
        }
	public function getYOURINFO()
        {
		return $this->YOURINFO;
        }
        public function setYOURINFO($yourinfo)
        {
		$this->YOURINFO=$yourinfo;
        }
	public function getFATHER_INFO()
        {
                return $this->FATHER_INFO;
        }
        public function setFATHER_INFO($father_info)
        {
                $this->FATHER_INFO=$father_info;
        }
	public function getSIBLING_INFO()
        {
                return $this->SIBLING_INFO;
        }
        public function setSIBLING_INFO($sibling_info)
        {
                $this->SIBLING_INFO=$sibling_info;
        }
	public function getJOB_INFO()
        {
                return $this->JOB_INFO;
        }
        public function setJOB_INFO($job_info)
        {
                $this->JOB_INFO=$job_info;
        }
        public function getACTIVATED()
        {
		return $this->ACTIVATED;
        }
        public function setACTIVATED($activated)
        {
		$this->ACTIVATED=$activated;
        }
	public function getINCOME()
        {
		return $this->INCOME;
        }
        public function setINCOME($income)
        {
		$this->INCOME=$income;
        }
        public function getSUBSCRIPTION()
        {
		return $this->SUBSCRIPTION;
        }
        public function setSUBSCRIPTION($subscription)
        {
		$this->SUBSCRIPTION=$subscription;
        }
	public function getLAST_LOGIN_DT()
        {
		return $this->LAST_LOGIN_DT;
        }
        public function setLAST_LOGIN_DT($last_login_dt)
        {
		$this->LAST_LOGIN_DT=$last_login_dt;
        }
        public function getSOURCE()
        {
		return $this->SOURCE;
        }
        public function setSOURCE($source)
        {
		$this->SOURCE=$source;
        }
	/**
	* This function is used to set all the required variables of a profile.
	*/
	public function setAllVariables($profileid,$myDb,$parameter="*")
	{
		if($profileid)
                        $this->PROFILEID=$profileid;
                $sql="SELECT $parameter FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
                $result = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                $myrow = mysql_fetch_array($result);
		if($myrow)
                {
                        foreach ($myrow as $key => $value)
                                $this->$key=$value;
                }
	}
}
class Attribute_Parameters
{
	private $PHOTO;
	private $PROFILE_POSTEDBY;
	private $PROFILE_LEN;
	private $CITY;
	private $COMMUNITY;
	private $INCOME_GENDER;
	private $AGE_GENDER;
	private $COMMUNITY_FISH;
	private $FIELDSFILLED;
	private $TENURE;

	public function __construct($scorevars,$myDb)
        {
                $this->setAllAttributeParameters($scorevars,$myDb);
        }

        public function getPHOTO()
        {
		return $this->PHOTO;
        }
        public function setPHOTO($photo)
        {
		$this->PHOTO=$photo;
        }
	public function getPROFILE_POSTEDBY()
        {
                return $this->PROFILE_POSTEDBY;
        }
        public function setPROFILE_POSTEDBY($profile_postedby)
        {
                $this->PROFILE_POSTEDBY=$profile_postedby;
        }
	public function getPROFILE_LEN()
        {
                return $this->PROFILE_LEN;
        }
        public function setPROFILE_LEN($profile_len)
        {
                $this->PROFILE_LEN=$profile_len;
        }	
	public function getCITY()
        {
                return $this->CITY;
        }
        public function setCITY($city)
        {
                $this->CITY=$city;
        }
	public function getCOMMUNITY()
        {
                return $this->COMMUNITY;
        }
        public function setCOMMUNITY($community)
        {
                $this->COMMUNITY=$community;
        }
	public function getINCOME_GENDER()
        {
                return $this->INCOME_GENDER;
        }
        public function setINCOME_GENDER($income_gender)
        {
                $this->INCOME_GENDER=$income_gender;
        }
	public function getAGE_GENDER()
        {
                return $this->AGE_GENDER;
        }
        public function setAGE_GENDER($age_gender)
        {
                $this->AGE_GENDER=$age_gender;
        }
	public function getCOMMUNITY_FISH()
        {
                return $this->COMMUNITY_FISH;
        }
        public function setCOMMUNITY_FISH($community_fish)
        {
                $this->COMMUNITY_FISH=$community_fish;
        }
	public function getFIELDSFILLED()
        {
                return $this->FIELDSFILLED;
        }
        public function setFIELDSFILLED($fieldsfilled)
        {
                $this->FIELDSFILLED=$fieldsfilled;
        }
	public function getTENURE()
        {
                return $this->TENURE;
        }
        public function setTENURE($tenure)
        {
                $this->TENURE=$tenure;
        }	
	/**
	* This function is used to set all the attribute parameters of a profile.
	*/
	public function setAllAttributeParameters($scorevars,$myDb)
	{
		$this->setPHOTO($scorevars->getHAVEPHOTO());		
		$this->setPROFILE_POSTEDBY($scorevars->getRELATION());
		$plen=strlen($scorevars->getYOURINFO())+strlen($scorevars->getFATHER_INFO())+strlen($scorevars->getSIBLING_INFO())+strlen($scorevars->getJOB_INFO());
		$this->setPROFILE_LEN(round($plen,-2));
		$this->setCITY($scorevars->getCITY_RES());
		$this->setCOMMUNITY($scorevars->getMTONGUE());
		$this->setINCOME_GENDER($scorevars->getINCOME().",".$scorevars->getGENDER());
		$this->setAGE_GENDER($scorevars->getAGE().",".$scorevars->getGENDER());
		$con = $scorevars->getCOUNTRY_RES();
                $sql1="SELECT SQL_CACHE Nationality FROM scoring.country_nationality WHERE Country='$con'";
                $result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb)));
                $myrow1 = mysql_fetch_array($result1);
		$nationality=$myrow1["Nationality"];
		$ci = $scorevars->getCITY_RES();
		$sql2="SELECT SQL_CACHE CityZone FROM scoring.city_zone WHERE City='$ci'";
                $result2 = mysql_query_decide($sql2,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql2.mysql_error($myDb)));
                $myrow2 = mysql_fetch_array($result2);
                $cityzone=$myrow2["CityZone"];
		$com = $scorevars->getMTONGUE();
		$sql3="SELECT SQL_CACHE ComZone FROM scoring.community_zone WHERE Community='$com'";
                $result3 = mysql_query_decide($sql3,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql3.mysql_error($myDb)));
                $myrow3 = mysql_fetch_array($result3);
                $comzone=$myrow3["ComZone"];
		if($nationality == "NRI")
			$fish = "NRI";
		elseif($nationality == "")
			$fish = "CANT_SAY";
		elseif($cityzone == "" || $cityzone == "Foreign")
                        $fish = "CANT_SAY";
		elseif($comzone == "" || $comzone == "Foreign" || $comzone == "Others")
                        $fish = "CANT_SAY";
		elseif($cityzone == $comzone)
                        $fish = "IN";
		elseif($cityzone != $comzone)
			$fish = "OUT";
		$this->setCOMMUNITY_FISH($scorevars->getMTONGUE().",".$fish);
		$ffcount=0;
		if($scorevars->getDRINK()!='')
			$ffcount++;
		if($scorevars->getSMOKE()!='')
                        $ffcount++;
		if($scorevars->getDIET()!='')
                        $ffcount++;
		if($scorevars->getMANGLIK()!='')
                        $ffcount++;
		if($scorevars->getBTYPE()!='')
                        $ffcount++;
		if($scorevars->getSHOW_HOROSCOPE()!='')
                        $ffcount++;
		$this->setFIELDSFILLED($ffcount);
		$this->setTENURE(round(((time()-JSstrToTime($scorevars->getENTRY_DT()))/86400)/30,0));
	}
	/**
        * This function is used to return the bias of the attribute parameter based on single parameters.
        */
	public function giveAttributeParameter_bias_single($param,$param_val,$ptype,$myDb)	
	{
		if(is_numeric($param_val))
			$sql = "select SQL_CACHE bias from scoring.$param where $param=$param_val AND payment_type='$ptype'";
		else
			$sql = "select SQL_CACHE bias from scoring.$param where $param='$param_val' AND payment_type='$ptype'";
		$res = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
		if($row = @mysql_fetch_array($res))
			return $row["bias"];
		else
			return 1;
	}
	/**
        * This function is used to return the bias of the attribute parameter based on double parameters.
        */
	public function giveAttributeParameter_bias_double($param,$param_val,$ptype,$myDb)
        {
		$param_val_both=explode(",",$param_val);
                switch($param)
		{
			case 'incgen':
			{
				if($param_val_both[0]=='')
					$sql = "select SQL_CACHE bias from scoring.$param where income='' AND gender='$param_val_both[1]' AND payment_type='$ptype'";
				else
					$sql = "select SQL_CACHE bias from scoring.$param where income=$param_val_both[0] AND gender='$param_val_both[1]' AND payment_type='$ptype'";
				break;
			}
			case 'agegen':
			{
				if($param_val_both[0]=='')
					$sql = "select SQL_CACHE bias from scoring.$param where age='' AND gender='$param_val_both[1]' AND payment_type='$ptype'";
				else
					$sql = "select SQL_CACHE bias from scoring.$param where age=$param_val_both[0] AND gender='$param_val_both[1]' AND payment_type='$ptype'";
				break;
			}
			case 'commfish':	
			{
				if($param_val_both[0]=='')
					$sql = "select SQL_CACHE bias from scoring.$param where community='' AND fish='$param_val_both[1]' AND payment_type='$ptype'";
				else
					$sql = "select SQL_CACHE bias from scoring.$param where community=$param_val_both[0] AND fish='$param_val_both[1]' AND payment_type='$ptype'";
				break;
			}
		}
                $res = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                if($row = @mysql_fetch_array($res))
                        return $row["bias"];
                else
                        return 1;
        }
}
class Behaviour_Parameters
{
        private $INTREST_LAST7;
        private $ACCEPT_LAST7;
        private $DECLINE_LAST7;
        private $LOGIN_LAST7;
        private $MAX_PAYMENT_PAGE;
        private $TIME_SINCE_LAST_PAY_MEMTYPE;

	public function __construct($scorevars,$myDb,$shDb)
        {
                $this->setAllBehaviourParameters($scorevars,$myDb,$shDb);
        }

        public function getINTREST_LAST7()
        {
                return $this->INTREST_LAST7;
        }
        public function setINTREST_LAST7($intrest_last7)
        {
                $this->INTREST_LAST7=$intrest_last7;
        }
        public function getACCEPT_LAST7()
        {
                return $this->ACCEPT_LAST7;
        }
        public function setACCEPT_LAST7($accept_last7)
        {
                $this->ACCEPT_LAST7=$accept_last7;
        }
        public function getDECLINE_LAST7()
        {
                return $this->DECLINE_LAST7;
        }
        public function setDECLINE_LAST7($decline_last7)
        {
                $this->DECLINE_LAST7=$decline_last7;
        }
        public function getLOGIN_LAST7()
        {
                return $this->LOGIN_LAST7;
        }
        public function setLOGIN_LAST7($login_last7)
        {
                $this->LOGIN_LAST7=$login_last7;
        }
        public function getMAX_PAYMENT_PAGE()
        {
                return $this->MAX_PAYMENT_PAGE;
        }
        public function setMAX_PAYMENT_PAGE($max_payment_page)
        {
                $this->MAX_PAYMENT_PAGE=$max_payment_page;
        }
        public function getTIME_SINCE_LAST_PAY_MEMTYPE()
        {
                return $this->TIME_SINCE_LAST_PAY_MEMTYPE;
        }
        public function setTIME_SINCE_LAST_PAY_MEMTYPE($time_since_last_pay_memtype)
        {
                $this->TIME_SINCE_LAST_PAY_MEMTYPE=$time_since_last_pay_memtype;
        }
        /**
        * This function is used to set all the behaviour parameters of a profile.
        */
        public function setAllBehaviourParameters($scorevars,$myDb,$shDb)
        {
		$I=$A=$D=$C=0;
		$pid = $scorevars->getPROFILEID();
		$cut_off = date("Y-m-d");
		$lim_1_dt = date("Y-m-d",time()-86400);
		$lim_7_dt = date("Y-m-d",time()-7*86400);
		$sql3 = "SELECT MAX(PAGE) AS PAGE FROM  billing.PAYMENT_HITS WHERE PROFILEID='$pid' AND ENTRY_DT BETWEEN '$lim_7_dt' AND '$cut_off'";
                $res3 = mysql_query_decide($sql3,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql3.mysql_error($myDb)));
                if($row3 = mysql_fetch_array($res3))
                        $this->setMAX_PAYMENT_PAGE($row3["PAGE"]);
                else
                        $this->setMAX_PAYMENT_PAGE(0);
                $sql4 = "SELECT pd.ENTRY_DT,p.SERVICEID FROM billing.PURCHASES as p,billing.PAYMENT_DETAIL as pd WHERE p.BILLID = pd.BILLID AND pd.PROFILEID ='$pid' order by pd.RECEIPTID desc";
                $res4 = mysql_query_decide($sql4,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql4.mysql_error($myDb)));
                while($row4 = mysql_fetch_array($res4))
                {
                        $last_pay_date=$row4["ENTRY_DT"];
                        if(strstr($row4["SERVICEID"],","))
                                $mem_type_arr=explode(",",$row4["SERVICEID"]);
                        else
                                $mem_type_arr[0]=$row4["SERVICEID"];
                        for($i=0;$i<count($mem_type_arr);$i++)
                        {
                                if(strstr($mem_type_arr[$i],'C') || strstr($mem_type_arr[$i],'P') || strstr($mem_type_arr[$i],'D') || strstr($mem_type_arr[$i],'S'))
                                {
                                        $mem_type = $mem_type_arr[$i];
                                        break 2;
                                }
                        }
                }
                $this->setTIME_SINCE_LAST_PAY_MEMTYPE(round(((time()-JSstrToTime($last_pay_date))/86400)/30,0)."*".$mem_type);
		$sql1 = "SELECT TYPE FROM newjs.CONTACTS WHERE SENDER='$pid' AND TIME BETWEEN '$lim_7_dt 00:00:00' AND '$cut_off 23:59:59'";
		$res1 = mysql_query_decide($sql1,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($shDb)));
		while($row1 = mysql_fetch_array($res1))
		{
			if($row1["TYPE"]=="I")
				$I++;
			if($row1["TYPE"]=="A")
				$A++;
			if($row1["TYPE"]=="D")
                                $D++;
                        if($row1["TYPE"]=="C")
                                $C++;
		}
		$T=$I+$A+$D+$C;
		$this->setINTREST_LAST7($T);
		if(!$T)
		{
			$this->setACCEPT_LAST7(-1);
			$this->setDECLINE_LAST7(-1);
		}
		else
		{
			$this->setACCEPT_LAST7(round($A/$T,-1));
			$this->setDECLINE_LAST7(round($D/$T,-1));
		}
		$sql2 = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID='$pid' AND LOGIN_DT BETWEEN '$lim_7_dt' AND '$lim_1_dt'";
		$res2 = mysql_query_decide($sql2,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql2.mysql_error($shDb)));
                if($row2 = mysql_fetch_array($res2))
			$this->setLOGIN_LAST7($row2["CNT"]);
		else
			$this->setLOGIN_LAST7(0);
        }
	/**
        * This function is used to return the bias of the behaviour parameter based on single parameters.
        */
	public function giveBehaviourParameter_bias_single($param,$param_val,$ptype,$myDb)
        {
		if(is_numeric($param_val))
	                $sql = "select SQL_CACHE bias from scoring.$param where $param=$param_val AND payment_type='$ptype'";
		else
			$sql = "select SQL_CACHE bias from scoring.$param where $param='$param_val' AND payment_type='$ptype'";
                $res = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                if($row = @mysql_fetch_array($res))
                        return $row["bias"];
                else
                        return 1;
        }
	/**
        * This function is used to return the bias of the behaviour parameter based on single parameters.
        */
	public function giveBehaviourParameter_bias_double($param,$param_val,$myDb)
        {
                $param_val_both=explode("*",$param_val);
		$param_mem=explode(",",$param_val_both[1]);
		if(count($param_mem)>1)
			$param_val_both[1]=implode("','",$param_mem);
		else
			$param_val_both[1]=$param_mem[0];
                $sql = "select SQL_CACHE bias from scoring.$param where time_since_last_pay=$param_val_both[0] AND memtype IN ('$param_val_both[1]')";
                $res = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                if($row = @mysql_fetch_array($res))
                        return $row["bias"];
                else
                        return 1;
        }
}
?>
