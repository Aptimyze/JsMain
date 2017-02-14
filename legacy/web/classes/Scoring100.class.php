<?php

/** 
* @author Vibhor Garg 
* @copyright Copyright 2011, Infoedge India Ltd.
*/
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class Scoring
{
	//Variables
	public $PROFILEID;
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
	public $INCOME;
	public $SOURCE;
	public $CASTE;
	public $MOB_STATUS;
	public $LANDL_STATUS;
	public $OCCUPATION;
	public $EDU_LEVEL;
	public $MSTATUS;
	public $GET_SMS;
	public $RELIGION;
	public $EDU_LEVEL_NEW;
	public $VERIFY_EMAIL;
	public $HEIGHT;
	public $TIME_TO_CALL_START;
	public $TIME_TO_CALL_END;
	public $HAVE_CAR;
	public $OWN_HOUSE;
	public $FAMILY_STATUS;
        public $SHOWADDRESS;
        public $WORK_STATUS;
        public $DTOFBIRTH;
	public $NATIONALITY;
	public $AGE_BIN;
	public $MARKETING_SOURCE;
	public $TENURE_BIN;
	public $FISH;
	public $PARTNER_FIELDSFILLED;
	public $modals = array();
	public $transformers = array();
	public $globalParamsObj;

	public function __construct($profileid,$myDb,$shDb,$parameter="*",$ptype,$globalParamsObj)
        {
                $this->globalParamsObj = $globalParamsObj;
                $this->setAllVariables($profileid,$myDb,$shDb,$parameter="*",$ptype);
        }

	/**
	* This function is used to set all the required variables of a profile depending upon the profile type.
	*/
	public function setAllVariables($profileid,$myDb,$shDb,$parameter="*",$ptype)
	{
		if($profileid)
                        $this->PROFILEID=$profileid;

		/*Set all common parameters*/
                $sql="SELECT $parameter FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
                $result = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                $myrow = mysql_fetch_array($result);
		if($myrow)
                {
                        foreach ($myrow as $key => $value)
                                $this->$key=$value;
                }
	
		/*Set computational parameters depending upon the profile type*/

		// NationalityIndia and NationalityNRI
		$this->NATIONALITY = $this->globalParamsObj->getNationality($this->COUNTRY_RES);
		if($ptype=='F' || $ptype=='O')
                {
			//AGE_BIN
	                $age = $this->AGE;
        	        if($age<24)
                	        $age_bin="24-";
	                elseif($age>=24 && $age<32)
        	                $age_bin=$age;
                	elseif($age>=32 && $age<34)
                        	$age_bin="32-34";
	                elseif($age>=34 && $age<37)
        	                $age_bin="34-37";
                	elseif($age>=37 && $age<41)
                        	$age_bin="37-41";
	                elseif($age>=41)
        	                $age_bin="41+";
                	$this->AGE_BIN=$age_bin;

			//MARKETING_SOURCE
			$source = $this->SOURCE;
	                $group = $this->globalParamsObj->getGroupName($source);
			$source=strtolower($source);
			if((strstr($group,"profilepg")) || (strstr($group,"jeevansathi")) || (strstr($group,"seo"))) 
				$marketing_source="Direct-Desktop";
			elseif((strstr($group,"mobiledirect")) || (strstr($group,"mobileapp")))
				$marketing_source="Direct-Mobile";
			elseif((strstr($group,"google_custom")) && ((strstr($source,"marry59")) || (strstr($source,"punjabi59")) || (strstr($source,"hindi59")) || (strstr($source,"marathi59"))))
				$marketing_source="Google-Brand";
			elseif((strstr($group,"google_custom")) && ((strstr($source,"nonbrand59")) || (strstr($source,"hindiform")) || (strstr($source,"punjabiform")) || (strstr($source,"marathiform")) || (strstr($source,"bengali59")) || (strstr($source,"tamil59")) || (strstr($source,"christian59")) || (strstr($source,"gujarati59")) || (strstr($source,"malayalee59")) || (strstr($source,"muslim59")) || (strstr($source,"telegu59")) || (strstr($source,"kannada59")) || (strstr($source,"oriya59")) || (strstr($source,"sindhi59")) || (strstr($source,"brahmin59")) || (strstr($source,"kayastha59"))))
				$marketing_source="Google-Generic";
			elseif((strstr($group,"google_custom")) && ((strstr($source,"hindikwds")) || (strstr($source,"marathikwd")) || (strstr($source,"nri59")) || (strstr($source,"community59"))))
				$marketing_source="Google-Community";
			elseif((strstr($group,"google_custom")) && (strstr($source,"competition59")))
				$marketing_source="Google-Competition";
			elseif(strstr($group,"mobilesem"))
				$marketing_source="Google-Mobile";
			elseif((strstr($group,"google_custom")) && ((strstr($source,"default59a")) || (strstr($source,"default59b")) || (strstr($source,"content59")) || (strstr($source,"default")) || (strstr($source,"default59")) || (strstr($source,"dis_cam_op"))))
				$marketing_source="Google-Content";
			elseif((strstr($group,"google_custom")) && (strstr($source,"c_")))
				$marketing_source="Google-Content";
			elseif((strstr($group,"google_custom")) && (strstr($source,"rtg")))
				$marketing_source="Google-Content";
			elseif((strstr($group,"google_custom")) && (strstr($source,"remarketing")))
				$marketing_source="Google-Content";
			elseif((strstr($group,"google_custom")) && (strstr($source,"marathifor")))
				$marketing_source="Google-Generic";
			elseif((strstr($group,"yahoosearch_2008")) && ((strstr($source,"ysm_main")) || (strstr($source,"hindiysm")) || (strstr($source,"marathiysm")) || (strstr($source,"punjabiysm")) || (strstr($source,"ysm_maina")) || (strstr($source,"ysm_mainb")) || (strstr($source,"y_marry59"))))
				$marketing_source="YSM-Brand";
			elseif((strstr($group,"yahoosearch_2008")) && ((strstr($source,"yahoo1")) || (strstr($source,"gen-2-c")) || (strstr($source,"gen-c")) || (strstr($source,"gen-3-c")) || (strstr($source,"smas-c")) || (strstr($source,"dhin-c")) || (strstr($source,"gen-1-c")) || (strstr($source,"bengnet-c")) || (strstr($source,"bangmus-c")) || (strstr($source,"astro-c")) || (strstr($source,"andbzr-c"))))
				$marketing_source="YSM-Content";
			elseif((strstr($group,"yahoosearch_2008")) && ((strstr($source,"y_nbrand59")) || (strstr($source,"yahoo3")) || (strstr($source,"yahoo2"))))
				$marketing_source="YSM-Non-Brand";
			elseif(strstr($group,"ysm mobile"))
				$marketing_source="YSM-Mobile";
			elseif(strstr($group,"bandhan"))
				$marketing_source="Bandhan";
			elseif(strstr($group,"rediff"))
				$marketing_source="Rediff";
			elseif(strstr($group,"facebook"))
				$marketing_source="Facebook";
			elseif(strstr($group,"yahoo"))
				$marketing_source="Yahoo";
			elseif(strstr($group,"danik_bhaskar_amj12"))
				$marketing_source="Danik_Bhaskar";
			elseif(strstr($group,"precisionmatch_jas13"))
				$marketing_source="Adnetworks-PrecisionMatch";
			elseif(strstr($group,"dgm_jfm13"))
				$marketing_source="Adnetworks-DGM";
			elseif(strstr($group,"komli_amj_08_cpa"))
				$marketing_source="Adnetworks-Komli";
			elseif(strstr($group,"tyroo_amj@120"))
				$marketing_source="Adnetworks-Tyroo";
			elseif(strstr($group,"ozone"))
				$marketing_source="Adnetworks-Ozone";
			elseif(strstr($group,"vizury_amj13"))
				$marketing_source="Vizury";
			elseif(strstr($group,"sokrati_ond13"))
				$marketing_source="Sokrati";
			else
				$marketing_source="other";
			$this->MARKETING_SOURCE=$marketing_source;

			//TENURE_BIN
	                $tenure = round(((time()-JSstrToTime($this->ENTRY_DT))/86400)/30,0);
        	        if($tenure>=0 && $tenure<6)
                	        $tenure_bin=$tenure;
	                elseif($tenure>=6 && $tenure<9)
        	                $tenure_bin="6-9";
                	elseif($tenure>=9 && $tenure<12)
                        	$tenure_bin="9-12";
	                elseif($tenure>=12)
        	                $tenure_bin="12+";
	                $this->TENURE_BIN=$tenure_bin;

			//FISH
			$nationality=$this->NATIONALITY;
			$cityzone = $this->globalParamsObj->getCityZone($this->CITY_RES);
			$comzone = $this->globalParamsObj->getCommunityZone($this->MTONGUE);
			/*if($nationality == "NRI")
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
                	$this->FISH=$fish;
			*/
		}

		if($ptype=='R')
		{
			//PARTNER_FIELDSFILLED
			$PartnerFieldsFilled=0;
			$sql1 = "SELECT LAGE,LHEIGHT,CASTE_MTONGUE,PARTNER_CASTE,PARTNER_CITYRES,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_OCC,PARTNER_RELIGION FROM newjs.JPARTNER WHERE PROFILEID='$this->PROFILEID'";
			$res1 = mysql_query_decide($sql1,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($shDb)));
			while($row1 = mysql_fetch_array($res1))
			{
				if($row1["LAGE"]!='')
					$PartnerFieldsFilled++;
				if($row1["LHEIGHT"]!='')
					$PartnerFieldsFilled++;
				if($row1["CASTE_MTONGUE"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_CASTE"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_CITYRES"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_ELEVEL_NEW"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_INCOME"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_MSTATUS"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_MTONGUE"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_OCC"]!='')
					$PartnerFieldsFilled++;
				if($row1["PARTNER_RELIGION"]!='')
                                $PartnerFieldsFilled++;
                	}
			$this->PARTNER_FIELDSFILLED=$PartnerFieldsFilled;
		}

		if($ptype == "C")
		{
			//Profile Data
			$this->newmodel[PROFILEID] = $this->PROFILEID;
			$this->newmodel[CASTE] = $this->CASTE;
			$this->newmodel[MSTATUS] = $this->MSTATUS;
			$this->newmodel[GET_SMS] = $this->GET_SMS;
			$this->newmodel[RELIGION] = $this->RELIGION;
			$this->newmodel[OCCUPATION] = $this->OCCUPATION;
			$this->newmodel[EDU_LEVEL] = $this->EDU_LEVEL;
			$this->newmodel[EDU_LEVEL_NEW] = $this->EDU_LEVEL_NEW;
			$this->newmodel[MOB_STATUS] = $this->MOB_STATUS;
                        $this->newmodel[LANDL_STATUS] = $this->LANDL_STATUS;
                        $this->newmodel[VERIFY_EMAIL] = $this->VERIFY_EMAIL;
                        $this->newmodel[HEIGHT] = $this->HEIGHT;
                        $this->newmodel[TIME_TO_CALL_START] = $this->TIME_TO_CALL_START;
                        $this->newmodel[TIME_TO_CALL_END] = $this->TIME_TO_CALL_END;
                        $this->newmodel[HAVE_CAR] = $this->HAVE_CAR;
			$this->newmodel[OWN_HOUSE] = $this->OWN_HOUSE;
			$this->newmodel[SHOWADDRESS] = $this->SHOWADDRESS;
			$this->newmodel[SHOW_HOROSCOPE] = $this->SHOW_HOROSCOPE;
			$this->newmodel[WORK_STATUS] = $this->WORK_STATUS;
			$this->newmodel[CITY_RES] = $this->CITY_RES;
			$this->newmodel[MTONGUE] = $this->MTONGUE;
			$this->newmodel[GENDER] = $this->GENDER;
			$this->newmodel[INCOME] = $this->INCOME;
			$this->newmodel[FAMILY_STATUS] = $this->FAMILY_STATUS;
			$this->newmodel[DOB] = $this->DTOFBIRTH;
	
			//Purchase Data
			 $sqlpd = "SELECT SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE,SERVICEID,CUR_TYPE,NET_AMOUNT,DISCOUNT,START_DATE FROM billing.PURCHASE_DETAIL WHERE PROFILEID='$this->PROFILEID'";
                        $respd = mysql_query_decide($sqlpd,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlpd.mysql_error($myDb)));
                        while($rowpd = mysql_fetch_array($respd))
                        {
				$this->newmodel[SUBSCRIPTION_START_DATE] = $rowpd['SUBSCRIPTION_START_DATE'];
				$this->newmodel[SUBSCRIPTION_END_DATE] = $rowpd['SUBSCRIPTION_END_DATE'];
				$this->newmodel[SERVICEID] = $rowpd['SERVICEID'];
				$this->newmodel[CUR_TYPE] = $rowpd['CUR_TYPE'];
				$this->newmodel[NET_AMOUNT] = $rowpd['NET_AMOUNT'];
				$this->newmodel[DISCOUNT] = $rowpd['DISCOUNT'];
				$this->newmodel[START_DATE] = $rowpd['START_DATE'];
			}

			//Activity Data
			$fdate = date("Y-m-d", strtotime($this->newmodel[$this->PROFILEID]['SUBSCRIPTION_END_DATE'])-60*86400);
			$ldate = date("Y-m-d", strtotime($this->newmodel[$this->PROFILEID]['SUBSCRIPTION_END_DATE']));
                        $sqlml2 = "SELECT COUNT(*) as cnt FROM newjs.MESSAGE_LOG WHERE RECEIVER='$this->PROFILEID' AND DATE>='$fdate' AND DATE<'$ldate' AND TYPE='I'";
                        $resml2 = mysql_query_decide($sqlml2,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlml2.mysql_error($shDb)));
                        if($rowml2 = mysql_fetch_array($resml2)){
				$this->newmodel[MESSAGES_COUNT] = $rowml2["cnt"];
                        }

			$lim_30_dt = date("Y-m-d",time()-30*86400);
			$sqll = "SELECT COUNT(*) as cnt FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$this->PROFILEID' AND LOGIN_DT >= '$lim_30_dt'";
	                $resl = mysql_query_decide($sqll,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqll.mysql_error($shDb)));
                	if($rowl = mysql_fetch_array($resl)){
                	        $this->newmodel[LOGINS_LAST30]=$rowl["cnt"];
                	}

                        $sqle = "SELECT COUNT(*) as cnt FROM newjs.EOI_VIEWED_LOG WHERE VIEWED = '$this->PROFILEID' AND DATE >= '$lim_30_dt'";
                        $rese = mysql_query_decide($sqle,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqle.mysql_error($shDb)));
                        if($rowe = mysql_fetch_array($rese)){
                                $this->newmodel[VIEWS_LAST30]=$rowe["cnt"];
                        }
		}
	}

	/**
        * This function is used to set all the modal parameters of a profile depending upon the profile type.
        */
        public function setAllModalParameters($myDb,$shDb,$ptype)
        {
		//modals array
		if($ptype=='F')
		{
			$this->modals['profilepostedbyXgender'] 	= $this->RELATION.",".$this->GENDER;
	                $this->modals['occupationXgender']       	= $this->OCCUPATION.",".$this->GENDER;
        	        $this->modals['incomeXgender']           	= $this->INCOME.",".$this->GENDER;
			$this->modals['age_binXgender']		 	= $this->AGE_BIN.",".$this->GENDER;
			$this->modals['communityXfish']			= $this->MTONGUE.",".$this->FISH;
			$this->modals['tenure_bin']                     = $this->TENURE_BIN;
	                $this->modals['city']                           = $this->CITY_RES;
        	        $this->modals['caste']                          = $this->CASTE;
                	$this->modals['edu_level']                      = $this->EDU_LEVEL;
                	$this->modals['msource']                        = $this->MARKETING_SOURCE;
		}
		if($ptype=='R')
		{
			$this->modals['profilepostedbyXgender']         = $this->RELATION.",".$this->GENDER;
                        $this->modals['occupationXgender']              = $this->OCCUPATION.",".$this->GENDER;
                        $this->modals['incomeXgender']                  = $this->INCOME.",".$this->GENDER;
                        $this->modals['partner_fieldsfilled']           = $this->PARTNER_FIELDSFILLED;
			$this->modals['city']                           = $this->CITY_RES;
                        $this->modals['caste']                          = $this->CASTE;
                        $this->modals['edu_level']                      = $this->EDU_LEVEL;
		}
		if($ptype=='O')
		{
			$this->modals['occupationXgender']              = $this->OCCUPATION.",".$this->GENDER;
                        $this->modals['incomeXgender']                  = $this->INCOME.",".$this->GENDER;
                        $this->modals['age_binXgender']                 = $this->AGE_BIN.",".$this->GENDER;
                        $this->modals['communityXfish']                 = $this->MTONGUE.",".$this->FISH;
			$this->modals['tenure_bin']                     = $this->TENURE_BIN;
                        $this->modals['city']                           = $this->CITY_RES;
                        $this->modals['edu_level']                      = $this->EDU_LEVEL;
                        $this->modals['msource']                        = $this->MARKETING_SOURCE;
			$this->modals['nationalityXprofilepostedby']    = $this->NATIONALITY.",".$this->RELATION;
		}
	
		//modals_bias array
                $this->modals_bias = $this->param_bias($this->modals,$ptype,$myDb);
        }

        /**
        * This function is used to set all the transform parameters of a profile depending upon the profile type.
        */
        public function setAllTransformParameters($myDb,$shDb,$ptype)
        {
		$pid = $this->PROFILEID;
		$lim_7_dt = date("Y-m-d",time()-7*86400);
                $lim_14_dt = date("Y-m-d",time()-14*86400);

		//SEARCHES_14_cap and SEARCHES_accel
		if($ptype=='F'){
			$searches_7=0;
			$searches_14=0;
			$search_param_array = $this->globalParamsObj->getSearchParameters($pid);
			$searches_7=$search_param_array[0];
			$searches_14=$search_param_array[1];
		}
	
		//VIEWS_7_cap and views_accel
		if($ptype=='F'){
			$views_7=0;
			$views_14=0;
			$view_param_array = $this->globalParamsObj->getViewParameters($pid);
                	$views_7=$view_param_array[0];
                	$views_14=$view_param_array[1];
		}
                
		//LOGIN_LAST_accel
        	$login_last7=0;
        	$login_last14=0;
		$sql1 = "SELECT COUNT(CASE WHEN LOGIN_DT >= '$lim_7_dt' THEN 1 ELSE NULL END) AS CNT1, COUNT(CASE WHEN LOGIN_DT >= '$lim_14_dt' THEN 1 ELSE NULL END) AS CNT2 FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid'";
                $res1 = mysql_query_decide($sql1,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($shDb)));
                if($row1 = mysql_fetch_array($res1)){
                	$login_last7=$row1["CNT1"];
                	$login_last14=$row1["CNT2"];
                }
                        

		//photo_modN
		if($this->HAVEPHOTO != 'Y')
			$photo_modN=1;
		else
			$photo_modN=-1;

		//payment_hits_14_1_mod,payment_hits_14_2_mod and payment_hits_14_3_mod
		$payment_hits_array = $this->globalParamsObj->getPaymentPageHits($pid);
		$payment_hits_14_1=$payment_hits_array[1];
		$payment_hits_14_2=$payment_hits_array[2];
		$payment_hits_14_3=$payment_hits_array[3];
		
                //mob_verified_modJ,mob_verified_modN and mob_verified_modX,mob_verified_modM
		$mob_verified_modJ=0;
                $mob_verified_modN=0;
                $mob_verified_modX=0;
		$mob_verified_modM=0;
		if($this->MOB_STATUS=="")
		{
			$mob_verified_modX=1;
			$mob_verified_modM=1;
		}
		elseif($this->MOB_STATUS=="J")
			$mob_verified_modJ=1;
		elseif($this->MOB_STATUS=="N")
                        $mob_verified_modN=1;
		else
		{
			$mob_verified_modJ=-1;
	                $mob_verified_modN=-1;
        	        $mob_verified_modX=-1;
                	$mob_verified_modM=-1;
		}

                //landline_verified_modN and landline_verified_modX
		$landline_verified_modX=0;
                $landline_verified_modN=0;
		$landline_verified_modM=0;
		if($this->LANDL_STATUS=="")
		{
			$landline_verified_modX=1;
			$landline_verified_modM=1;
		}
		elseif($this->LANDL_STATUS=="N")
                        $landline_verified_modN=1;
		else
		{
			$landline_verified_modX=-1;
	                $landline_verified_modN=-1;
        	        $landline_verified_modM=-1;
		}

                //mob_landline_verified1 and mob_landline_verified0
		$mob_landline_verified0=0;
		$mob_landline_verified1=0;
		if($this->MOB_STATUS=="Y" && $this->LANDL_STATUS=="Y")
		{
                        $mob_landline_verified0=-1;
			$mob_landline_verified1=-1;
		}
		elseif($this->MOB_STATUS=="Y" || $this->LANDL_STATUS=="Y")
			$mob_landline_verified1=1;
		else
			$mob_landline_verified0=1;

		//NationalityIndia and NationalityNRI
		$NationalityIndia = 0;
                $NationalityNRI = 0;
		if($this->NATIONALITY == 'India')
			$NationalityIndia = 1;
		elseif($this->NATIONALITY == 'NRI')
			$NationalityNRI = 1;
		else
		{
			$NationalityIndia = -1;
			$NationalityNRI = -1;
		}

		//if($ptype=='F' || $ptype=='R')
		if($ptype=='R')
                {
                        //CONT_14_SA_cap and CONT_SI_accel
                        $count_14_sa=0;
                        $count_7_si=0;
                        $count_14_si=0;
			/*$sql1 = "SELECT COUNT(*) AS CNT1 FROM newjs.CONTACTS WHERE SENDER='$pid' AND TIME>='$lim_7_dt' AND TYPE='I'";
	                $res1 = mysql_query_decide($sql1,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($shDb)));
        	        if($row1 = mysql_fetch_array($res1))
                	        $count_7_si=$row1["CNT1"];
			*/
	                $sql2 = "SELECT COUNT(*) AS CNT2,TYPE FROM newjs.CONTACTS WHERE SENDER='$pid' AND TIME>='$lim_14_dt' AND TYPE IN ('A','I') GROUP BY TYPE";
        	        $res2 = mysql_query_decide($sql2,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql2.mysql_error($shDb)));
                	while($row2 = mysql_fetch_array($res2))
			{
				if($row2["TYPE"]=='A')
                        		$count_14_sa=$row2["CNT2"];
				else
					$count_14_si=$row2["CNT2"];
			}

                        //CONT_14_RA_cap
                        /*$sql4 = "SELECT COUNT(*) AS CONT_14_RA FROM newjs.CONTACTS WHERE RECEIVER='$pid' AND TYPE='A' AND TIME>='$lim_14_dt'";
                        $res4 = mysql_query_decide($sql4,$shDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql4.mysql_error($shDb)));
                        if($row4 = mysql_fetch_array($res4))
                        $count_14_ra = $row4["CONT_14_RA"];
			*/
                }

		if($ptype=='R')
		{
			//time_since_last_mem_exp_cap,addon_b,addon_r
			$addon_b=0;
			$addon_r=0;
			$lim_1000_dt = date("Y-m-d",time()-1000*86400);
			$sql7 = "SELECT q.START_DATE,q.END_DATE,q.SERVICEID FROM billing.PAYMENT_DETAIL as p,billing.PURCHASE_DETAIL q WHERE p.BILLID = q.BILLID AND p.PROFILEID ='$pid' order by q.END_DATE";
			$res7 = mysql_query_decide($sql7,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql7.mysql_error($myDb)));
                        while($row7 = mysql_fetch_array($res7))
                        {
                                if(strstr($row7["SERVICEID"],","))
                                        $mem_type_arr=explode(",",$row7["SERVICEID"]);
                                else
                                        $mem_type_arr[0]=$row7["SERVICEID"];
                                for($i=0;$i<count($mem_type_arr);$i++)
                                {
                                        if(strstr($mem_type_arr[$i],'B') && $row7["START_DATE"]>=$lim_1000_dt)
                                                $addon_b++;
                                        elseif(strstr($mem_type_arr[$i],'R') && $row7["START_DATE"]>=$lim_1000_dt)
                                                $addon_r++;
                                        if(strstr($mem_type_arr[$i],'P') || strstr($mem_type_arr[$i],'S') || strstr($mem_type_arr[$i],'C'))
					{
						if($row7["END_DATE"]<=date("Y-m-d"))
                                        	        $last_mem_exp_dt=$row7["END_DATE"];
					}
                                }
                        }
		}

		//transformers array
		if($ptype=='F')
		{
			//Profile_len_cap
	                $plen=strlen($this->YOURINFO)+strlen($this->FATHER_INFO)+strlen($this->SIBLING_INFO)+strlen($this->JOB_INFO);

			$this->transformers['SEARCHES_14_cap']		=log($searches_14+1);
			$this->transformers['SEARCHES_accel']           =$searches_7/($searches_14+15);
			$this->transformers['VIEWS_7_cap']		=log($views_7+1);
			$this->transformers['CONT_14_SA_cap']		=log($count_14_sa+1);
			$this->transformers['CONT_SI_accel']		=$count_7_si/($count_14_si+10);
			$this->transformers['CONT_14_RA_cap']		=log($count_14_ra+1);
			$this->transformers['Profile_len_cap']		=log(round($plen,-2)+1);
			$this->transformers['LOGIN_LAST_accel']		=$login_last7/($login_last14+0.001);	
			$this->transformers['login_last7']              =$login_last7;
			$this->transformers['photo_modN']		=$photo_modN;	
			$this->transformers['payment_hits_14_1_mod']	=log($payment_hits_14_1+1);
			$this->transformers['payment_hits_14_2_mod']	=log($payment_hits_14_2+1);
			$this->transformers['payment_hits_14_3_mod']	=log($payment_hits_14_3+1);
			$this->transformers['mob_verified_modJ']	=$mob_verified_modJ;
			$this->transformers['mob_verified_modN']        =$mob_verified_modN;
			$this->transformers['mob_verified_modX']        =$mob_verified_modX;
			$this->transformers['landline_verified_modN']	=$landline_verified_modN;
			$this->transformers['landline_verified_modX']   =$landline_verified_modX;
			$this->transformers['mob_landline_verified1']   =$mob_landline_verified1;
			$this->transformers['NationalityIndia']         =$NationalityIndia;
                	$this->transformers['NationalityNRI']           =$NationalityNRI;
		}
		if($ptype=='R')
		{
			$this->transformers['VIEWS_7_cap']              =log($views_7+1);
			$this->transformers['SEARCHES_14_cap']          =log($searches_14+1);
			$this->transformers['CONT_14_RA_cap']           =log($count_14_ra+1);
                        $this->transformers['CONT_14_SI_cap']           =log($count_14_si+1);
			$this->transformers['photo_modN']               =$photo_modN;
			$this->transformers['NationalityIndia']         =$NationalityIndia;
                        $this->transformers['NationalityNRI']           =$NationalityNRI;
			$this->transformers['payment_hits_14_1_mod']    =log($payment_hits_14_1+1);
                        $this->transformers['payment_hits_14_2_mod']    =log($payment_hits_14_2+1);
                        $this->transformers['payment_hits_14_3_mod']    =log($payment_hits_14_3+1);
			$this->transformers['mob_landline_verified0']   =$mob_landline_verified0;
			$this->transformers['mob_landline_verified1']   =$mob_landline_verified1;
			$this->transformers['LOGIN_LAST_accel']         =$login_last7/($login_last14+0.001);
			$this->transformers['partner_fields_filled']	=$this->PARTNER_FIELDSFILLED;
			$this->transformers['mob_verified_modN']        =$mob_verified_modN;
                        $this->transformers['mob_verified_modM']        =$mob_verified_modM;
                        $this->transformers['landline_verified_modM']   =$landline_verified_modM;
			$this->transformers['LOGIN_LAST14_cap']		=log($login_last14+1); 
                        $time_since_last_mem_exp_cap_temp               =round(((time()-JSstrToTime($last_mem_exp_dt))/86400)/30,0);
                        if($time_since_last_mem_exp_cap_temp>3)
                                $time_since_last_mem_exp_cap_temp=3;
                        $this->transformers['time_since_last_mem_exp_cap']=$time_since_last_mem_exp_cap_temp;
			$this->transformers['Addon_1000_B']		=$addon_b;
			$this->transformers['Addon_1000_R']		=$addon_r;
		}
		if($ptype=='O')
                {
			$this->transformers['VIEWS_7_cap']              =log($views_7+1);
                        $this->transformers['SEARCHES_14_cap']          =log($searches_14+1);
			$this->transformers['mob_verified_modJ']        =$mob_verified_modJ;
			$this->transformers['mob_verified_modN']        =$mob_verified_modN;
                        $this->transformers['mob_verified_modM']        =$mob_verified_modM;
                        $this->transformers['landline_verified_modM']   =$landline_verified_modM;
			$this->transformers['mob_landline_verified0']   =$mob_landline_verified0;
			$this->transformers['mob_landline_verified1']   =$mob_landline_verified1;
			$this->transformers['payment_hits_14_1_mod']    =log($payment_hits_14_1+1);
                        $this->transformers['payment_hits_14_2_mod']    =log($payment_hits_14_2+1);
                        $this->transformers['payment_hits_14_3_mod']    =log($payment_hits_14_3+1);
			$this->transformers['photo_modN']               =$photo_modN;
                        $this->transformers['NationalityIndia']         =$NationalityIndia;
                        $this->transformers['NationalityNRI']           =$NationalityNRI;
			$this->transformers['LOGIN_LAST_accel']         =$login_last7/($login_last14+0.001);
			$this->transformers['LOGIN_LAST14_cap']         =log($login_last14+1);
		}
        }

        /**
        * This function is used to return the bias of the modal parameter based on single parameters.
        */
        public function giveModalParameter_bias_single($param,$param_val,$ptype,$myDb)
        {
		if(is_numeric($param_val))
                        $sql = "select bias from scoring_new.$param where $param=$param_val AND profile_type='$ptype'";
                else
                        $sql = "select bias from scoring_new.$param where $param='$param_val' AND profile_type='$ptype'";
                $res = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                if($row = @mysql_fetch_array($res))
                        return $row["bias"];
                else
                        return 0;
        }

        /**
        * This function is used to return the bias of the modal parameter based on double parameters.
        */
        public function giveModalParameter_bias_double($param,$param1,$param_val1,$param2,$param_val2,$ptype,$myDb)
        {
		if(is_numeric($param_val1) && is_numeric($param_val2))
                        $sql = "select bias from scoring_new.$param where $param1=$param_val1 AND $param2=$param_val2 AND profile_type='$ptype'";
		elseif(is_numeric($param_val1))
			$sql = "select bias from scoring_new.$param where $param1=$param_val1 AND $param2='$param_val2' AND profile_type='$ptype'";
                else
			$sql = "select bias from scoring_new.$param where $param1='$param_val1' AND $param2='$param_val2' AND profile_type='$ptype'";
                $res = mysql_query_decide($sql,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($myDb)));
                if($row = @mysql_fetch_array($res))
                        return $row["bias"];
                else
                        return 0;
        }

	/**
        * This function is used to return the bias of the modal parameter depending upon the profile type.
        */
        function param_bias($modals_arr,$ptype,$myDb)
        {
		$bias_arr = array();
		$param_arr = array_keys($modals_arr);
		for($i=0;$i<count($param_arr);$i++)
		{
			$param=$param_arr[$i];
                        $param_val=$modals_arr["$param"];
                        if(strstr($param_val,","))
                        {
				$p_arr = explode("X",$param);
				$pv_arr = explode(",",$param_val);
				$param1=$p_arr[0];
				$param2=$p_arr[1];
				$param_val1=$pv_arr[0];
                                $param_val2=$pv_arr[1];
				$bias_arr["$param"]=$this->giveModalParameter_bias_double($param,$param1,$param_val1,$param2,$param_val2,$ptype,$myDb);
                        }
                        else
                                $bias_arr["$param"]=$this->giveModalParameter_bias_single($param,$param_val,$ptype,$myDb);
		}
		return $bias_arr;
        }
}
?>
