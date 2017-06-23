<?php 


class phoneKnowlarity{
	
private $profileObject;
private $phoneType;
private $virtualNo;
private $virtualNoId;

public function __construct($profileObject,$phoneType)
{

		try
		{

			
			if (!$profileObject || !$phoneType){
				throw new jsException('',"No phoneType or profileObject", 1);
			}
				$this->profileObject=$profileObject;
				$this->phoneType=$phoneType;
				switch($phoneType)
				{

					case 'M':
					$this->phone=$profileObject->getISD().$profileObject->getPHONE_MOB();
					break;

					case 'L':
					$this->phone=$profileObject->getISD().$profileObject->getPHONE_WITH_STD();
					break;
					
					case 'A':
					$contactArray= (new ProfileContact())->getArray(array('PROFILEID'=>$profileObject->getPROFILEID()),'','',"ALT_MOBILE");
					$this->phone=$profileObject->getISD().$contactArray['0']['ALT_MOBILE'];
					break;
				}

	
			
		}
		catch(Exception $e)
		{
				return null;
		}
		
}


//Virtual numbers list provided by Third party - Knowlarity for leads generation

public static function virtualNumbersListForLeads(){
	return phoneEnums::$virtualNoForLeads;
}

//Function that creates leads out of incoming phone numbers
public static function createLead($phoneno)
{
	if(is_numeric($phoneno) && strlen($phoneno)>=10)
	{
		global $SITE_URL;
		$link=$SITE_URL."/sugarcrm/custom/crons/create_sugar_lead.php?last_name=$phoneno&mobile1=$phoneno&source_c=17&js_source_c=ProfilePgK";
		$handle = curl_init();
        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
        curl_setopt($handle, CURLOPT_HEADER, $header);
        curl_setopt($handle,CURLOPT_USERAGENT,"JsInternal");
		curl_setopt($handle,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle,CURLOPT_MAXREDIRS, 5);
		curl_setopt($handle,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle,CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($handle, CURLOPT_URL,$link);
		curl_exec($handle);
		curl_close($handle);
	}
}

/**************************
name: getVirtualNumber
function takes the profileid and phone number and find its virtual number if already exist or generates a new for the corresponding profileid
**************************/
public function getVirtualNumber()
{
		$completeNumber = $this->phone;
		if($vNoid=$this->searchExistingPid($completeNumber))
		{
			$vNo=self::findvno($vNoid);
		}
		else
		{
			if($viridArr=self::checkDuplicatNumber($this->phone))//returns false if not duplicate
				$ar=$this->generateVno($viridArr);
			else
				$ar=$this->generateVno();
			$vNo=$ar["vNo"];
			$id=$ar["id"];
			$this->saveVNumber($id);
			
		}
		
        JsMemcache::getInstance()->setHashObject('missLog_'.$this->phone,array('rVno'=>$vNo,'pId'=>$this->profileObject->getPROFILEID()));

                            if($this->isd=="91")
				return "011".$vNo;
			else
				return "+9111".$vNo;

                
}
/*********
Name findvno
return virtual no for a virtual number id
************/
private function findvno($vNoid)
{
		$storeObj=new NEWJS_VIRTUALNO();
		$row=$storeObj->getVNoFromVId($vNoid);
        return $row["VIRTUALNO"];
}

/**************************
Name: generates a virtual number id in round robin
***************************/
private function generateVno($viridArr="")
{
	$storeObj=new NEWJS_VIRTUALNO();
	$row=$storeObj->getVirtualNumbers();
//print_r($row); die;
	if($row[0])
	{
		$id=$row[0]["ID"];
		$vNo=$row[0]["VIRTUALNO"];
	}
	if($viridArr!='' && in_array($id,$viridArr))
	{
		foreach ($row as $key => $value) {
			$idr=$value["ID"];
			$vNor=$value["VIRTUALNO"];
                        if(!in_array($vNor,$viridArr))
                        {
                        	$id=$idr;
                        	$vNo=$vNor;
                                break;
			}
		}

	}	
	$storeObj->updateVIdAsLatest($id);
	$ar['id']=$id;
	$ar['vNo']=$vNo;
	return $ar;
}


/***********return vid already used******/
public static function checkDuplicatNumber($phone)
{

	$knowlarityObj=new newjs_KNWLARITYVNO();
    $arr=$knowlarityObj->getVnoFromPhone($phone);
    $profUnqArr[]=array_unique($arr);
	if(count($arr)<1)
		return false;
	else
		return $arr;
}


public function searchExistingPid($phoneno)
{
	$knowlarityObj=new newjs_KNWLARITYVNO();
	$row=$knowlarityObj->getDetailsFromProfileId($this->profileObject->getPROFILEID());
     if($row)
	{
		$vNoid= $row["VIRTUALNO"];
		$rowphn=$row["PHONENO"];
		if($phoneno!=$rowphn)
			$this->saveVNumber($vNoid);
		return $vNoid;
	} 
	return false;
}



private  function saveVNumber($vNoid)
{
        JsMemcache::getInstance()->setHashObject('missLog_'.$this->phone,array('vNosaved'=>$vNoid,'pId'=>$this->profileObject->getPROFILEID()));

	$knowlarityObj=new newjs_KNWLARITYVNO();
    $knowlarityObj->insertNewVno($this->profileObject->getPROFILEID(),$this->phone,$vNoid);

}
/************************************************************************
api functions**********************************************************/
public static function findVnoId($virtualNo)
{

	$ob=new NEWJS_VIRTUALNO();
	$res=$ob->getVIdFromVNo($virtualNo);
	return $res["ID"];
}


/****************************************************************************************
Desc: function searches for a combination of phone number and virtual number id in KNWLARITYVNO table and returns the profileid againt the combination.
***************************************************************************************/
public static function getProfileFromPhoneVNo($phone,$virtualNo)
{
	if(!$phone || !$virtualNo) return null;
		$phone=trim(ltrim($phone,'+'));
    	$phone=trim(ltrim($phone,'0'));
    	
    	$virtualNo=trim(ltrim($virtualNo,'0'));
    	
    	$virtualNoId=self::findVnoId($virtualNo);
    	$arr=(new newjs_KNWLARITYVNO())->profileIDFromVnoIdAndPhone($phone,$virtualNoId);
		$profileId=$arr[0]['PROFILEID'];
      	return $profileId; 
}

/****************************************************************************************
Desc: function deletes entry from KNWLARITYVNO table 
**************************************************************************************/


public static function checkMobileNumber($number, $profileid='',$db='',$isd='')
{
    $number=self::removeAllSpecialChars($number);//remove specail chars from $this->number//for mobile need to remove everything except numbers
	if($profileid!='')
	{
		if(self::notInINVALID_PHONE($profileid))
			return 'N';
	}
	if($isd!='' && $isd!='91')
	{
		$length = strlen($number);
		return ($length>=6 && $length<=14)?'Y':'N';
	}
	else
	{
		return (self::lengthCheckMobile($number) && self::checkIndianMobileFormat($number) && self::notInJunk($number,$db))?'Y':'N';
	}
}
static public function getIsdInFormat($isd)
{
	$isd = self::removeAllSpecialChars($isd);
	$length = strlen($isd);
	if($length>0 && $length<=3)
		return $isd;
	return false;
}



static public function lengthCheckMobile($number)
{
        return (strlen($number)==10)?true:false;
}

static public function checkIndianMobileFormat($number)
{
        return (in_array(substr($number,0,1),array(7,8,9)))?true:false;
}

static public function removeAllSpecialChars($number)
{
	 return ltrim(preg_replace("/[^0-9]/","",$number),0);//remove everything except numbers
}

static public function removeSpecialCharsExceptHyphen($number)
{
	 return $number=ltrim(preg_replace("/[^0-9\-]/","",$number),0);//remove everything except numbers and hyphen(-)
}

static public function checkIndianLandlineFormat($number)
{
        return (in_array(substr($number,0,1),array(2,3,4,5,6)))?true:false;
}

static public function checkLandlineNumber($landline,$std,$profileid='',$db='',$isd='')
{
        if($profileid!='')
        {
                if(!self::notInINVALID_PHONE($profileid,$db))
                        return 'N';
        }
	$std=self::removeAllSpecialChars($std);
	if(!$std)
	{
		$landline=self::removeSpecialCharsExceptHyphen($landline);
		$numberArr=explode("-",$landline);
		$landline=$numberArr[1];
		$std=$numberArr[0];
	}
	else
		$landline=self::removeAllSpecialChars($landline);
	$number=ltrim($std,0).ltrim($landline,0);
        if($isd!='' && $isd!='91')
        {
                $length = strlen($number);
                return ($length>=7)?'Y':'N';
        }
        else
	        return (self::lengthCheckMobile($number) && self::checkIndianLandlineFormat($landline) && self::notInJunk($number,$db))?'Y':'N';
}			




static public function notInINVALID_PHONE($profileid){

if((new incentive_INVALID_PHONE())->existInINVALID_PHONE($profileid) == 'Y')return false;
else return true;


}
static public function notInJunk($phone){

if((new PHONE_JUNK())->checkJunk($phone) == 'Y') return false;
else return true;


}


public static function genrate_xml()
{
        header('content-type: text/xml');
        $xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xmlStr.="<Status>OK</Status>";
        return $xmlStr;
}


}
