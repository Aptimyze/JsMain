<?php
/************************************************************************************************************************
*    FILENAME           : jsProfileVerify.class.php 
*    DESCRIPTION        : This class validate different parameters of the profiles registered with Jeevansathi.
                        : Returns/Update the status of the profile
***********************************************************************************************************************/

if(!$_SERVER['DOCUMENT_ROOT'])
        $_SERVER['DOCUMENT_ROOT'] =realpath(dirname(__FILE__))."/..";
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");

class jsProfileVerify
{
	private $mysqlObj;
	private $db;
	private $dbConn;

	public function __construct($mysqlObj="",$dbConn="")
	{
		if(!$mysqlObj)
			$this->mysqlObj = new Mysql;
		if(!$dbConn)
			$this->dbConn="master";
		$this->db = $this->mysqlObj->connect("$this->dbConn");
	}

        /**
        * This function validate the profile id of the user and active status of profileId
        * Accepts @param int $profileId
        * Return @param int $profileId or 0
        * Table used: newjs.JPROFILE  
        **/
        public function profileCodeVerify($profileid,$phone)
        {
		$phoneType = self::getPhoneType($profileid,$phone);
		if(!$phoneType)
			return false;
                $sql = "SELECT PROFILEID from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid' AND ACTIVATED='Y'";
                $result = $this->mysqlObj->executeQuery($sql,$this->db);
                $myrow=$this->mysqlObj->fetchArray($result);
                $profileid = $myrow["PROFILEID"];
                if($profileid){
			if($phoneType=='M'){
				$phone = mobileformat($phone);
 	                   	if(strlen($phone)==11)
                                $phone=substr($phone,1,10);
			}
			else if($phoneType=='L'){
				$phone = landlineformat($phone);
			}
			self::phoneNumberVerifyStatus($profileid,$phone,'verify','Y',$phoneType);
                        return true;
		}
                return false;
        }

	/**
	* @return phone type (L-landline/M-mobile)
	**/
	public function getPhoneType($profileid,$phone,$checkVerified=false)
	{
		$sql = "SELECT `STD`,`PHONE_MOB`,`PHONE_RES`,MOB_STATUS,LANDL_STATUS FROM newjs.JPROFILE where PROFILEID='$profileid' AND activatedKey=1";
                $result = $this->mysqlObj->executeQuery($sql,$this->db);
		if($result){
			$row = $this->mysqlObj->fetchArray($result);
			$std		= $row['STD'];
			$landline	= $row['PHONE_RES'];
			$mobile		= $row['PHONE_MOB'];
		}
		$sqlAlt ="SELECT ALT_MOBILE,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
		$resAlt=$this->mysqlObj->executeQuery($sqlAlt,$this->db);
		if($resAlt){
			$rowAlt= $this->mysqlObj->fetchArray($resAlt);
			$alt_mobile	= $rowAlt['ALT_MOBILE'];
		}
		if(!$mobile && !$landline && !$alt_mobile)
			return;
		$phone = removeAllSpecialChars($phone);
		$phone = substr($phone,-5);
		$type ="";
		if($mobile){	
			$mobile =mobileformat($mobile);
			if(strstr($phone,$mobile) || strstr($mobile,$phone))
			{
				if($checkVerified==true)
				{
					if($row['MOB_STATUS']=="Y")
						return "M|Y";
					else
						return "M|N";
				}
				else
					return $type ="M";	// flag set for Mobile
			}
		}
		if($landline){
			$landline =landlineformat($landline,$std);
			if(strstr($phone,$landline) || strstr($landline,$phone))
			{
				if($checkVerified==true)
				{
					if($row['LANDL_STATUS']=="Y")
						return "L|Y";
					else
						return "L|N";
				}
				else
					return $type ="L";	// flag set for Landline
			}
		}
		if($alt_mobile){
			$alt_mobile=mobileformat($alt_mobile);
			if(strstr($phone,$alt_mobile) || strstr($alt_mobile,$phone))
			{
				if($checkVerified==true)
				{
					if($rowAlt['ALT_MOB_STATUS']=="Y")
						return "A|Y";
					else
						return "A|N";
				}
				else
					return $type ="A";	// flag set for Alternate Mobile
			}
		}
		return false;	
	}

	/**
	* This function insert the records of the profiles whose mobile/landline verifications request has sent to Cellcast.
	* @accept profileId,mobile no, landline no, mobile message, landline message, mobile status, landline status 
	* @return true/false
	* table used: newjs.PHONE_VERIFICATION_SENT
	* status flag: Y-Success, I- Invalid request, F- Request failed  
	**/
	public function phoneNumberSentStatus($profileid,$phone,$phoneMsg,$phoneStatus,$type,$action)
	{
		if($type =='M')
			$phone =mobileformat($phone);
		else if($type =='L')
			$phone =landlineformat($phone);

		$phoneMsg   = trim(addslashes(stripslashes($phoneMsg)));
		$sql = "INSERT INTO newjs.PHONE_VERIFICATION_SENT(PROFILEID,PHONE,MSG,STATUS,TYPE,ACTION,ENTRY_DT) VALUES('$profileid','$phone','$phoneMsg','$phoneStatus','$type','$action',now())";
		$this->mysqlObj->executeQuery($sql,$this->db);
		$rows_aff= $this->mysqlObj->affectedRows();
		if($rows_aff >0)
			return true;
		return false;
	}

        /**
        * This function update the mobile/landline number verification status of the profile whose no. is verified 
        * Accepts @param int profileId, int phone no,string message, string status, string type.  
        * Return @param true/false 
        * Table used: newjs.MOBILE_VERIFICATION_SMS,newjs.MOBILE_VERIFICATION_IVR, newjs.LANDLINE_VERIFICATION_IVR,jsadmin.OFFLINE_MATCHES  
	* @variable STATUS :Y-confirm,D-denied ,B-busy I-invalid
	* @variable TYPE   :M- mobile no, L-landline no 
        **/
	public function phoneNumberVerifyStatus($profileid,$phone,$msg,$status,$type)
	{
		$msg=addslashes(stripslashes($msg));
		$phone = trim($phone);

		if($status =='Y')
		{
			phoneUpdateProcess($profileid,$phone,$type,'Y',$msg);
		}
		else if($status=='D')
		{
			// sms sent when User denies the IVR phone-verification call (.i.e User presses button 2 to deny the request)
			//$phoneNumberCompl =getfullPhoneNumber('M',$phone,'','');
//			SEND_MOBSMS($profileid,$phone,'D');
			phoneUpdateProcess($profileid,$phone,$type,'D',$msg);			
		}
		else
		{
			/* Condition Used when the the IVR verification call fails
			 * Reason : User has neither verified nor denied (.i.e has not pressed 1 nor 2), 
			 * Reasen:  Has not responded,pressed some other number, phone call may be busy, phone is not reachable, switched off etc 	
			*/
                        $sqlC ="SELECT PROFILEID,ACTION from newjs.PHONE_VERIFICATION_SENT where PROFILEID='$profileid' ORDER BY ID DESC limit 1";
                        $resC = $this->mysqlObj->executeQuery($sqlC,$this->db);
                        $rowC =$this->mysqlObj->fetchArray($resC);
                        $pid =$rowC['PROFILEID'];
			$act =trim($rowC['ACTION']);
			if($act=='edit')
				$check ="E";
			else if($act=='edit_both')
				$check ="E_B";
			else
				$check ="";

			if($type=='M'){
				include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
		
				// SMS sent when User Mobile number is busy,not reachable etc 
				$sms_status =sms_sent_status($profileid,$phone);
				if($sms_status && $status=='B'){
					//$phoneNumberCompl =getfullPhoneNumber('M',$phone,'','');
//					SEND_MOBSMS($profileid,$phone);
//					include_once "../profile/InstantSMS.php";
//					$sms= new InstantSMS("PHONE_UNVERIFY",$profileid);
//					$sms->send();

				}
			
				/* Diversion to Landline number 
				 * if Both Mobile and Landline are edited from edit  page and  Mobile was found busy, IVR call gets diverted to Landline
				*/
				if($check =='E_B')
				{
					$sql ="SELECT `PHONE_RES`,`STD` from JPROFILE where `PROFILEID`='$profileid' AND activatedKey=1";
					$res = $this->mysqlObj->executeQuery($sql,$this->db);	
					$row =$this->mysqlObj->fetchArray($res);
					$phone_res =$row['PHONE_RES'];
					$std	   =$row['STD'];
					if($phone_res && $std){
						ivrPhoneVerification($profileid,$phone_res,$std,$act);	
						return true;
					}
				}
			}

			// if any Phone number (either Mobile or Landline) is already Verified, IVR process stops further ahead.
			$chk_phoneStatus = getPhoneStatus('',$profileid);
			if($chk_phoneStatus =='Y')
				return true;

			// Phone no. is edited (either any one or both) from edit page and the IVR call is not responded
			if($check){
				$comments ="User fails to reply IVR verification call";
				$sql_rInvalid ="replace into jsadmin.REPORT_INVALID_PHONE(SUBMITTER,SUBMITTEE,SUBMIT_DATE,PHONE,MOBILE,VERIFIED,COMMENTS) values('','$profileid',now(),'Y','Y','N','$comments')";
				$this->mysqlObj->executeQuery($sql_rInvalid,$this->db);
				return true;
			}	
		
		}
		return true;
	}

        // log IVR hit to JS (Cellcast server hits Jeevansathi server with the phone status) 
        public function logIVRHit($profileid,$phone,$req_type,$data)
        {
               $sql = "INSERT INTO newjs.IVR_HIT(PROFILEID,PHONE,REQUEST_TYPE,ENTRY_DT,TEXT) VALUES('$profileid','$phone','$req_type',now(),'$data')";
                $this->mysqlObj->executeQuery($sql,$this->db);
        }

}
?>
