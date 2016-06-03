<?php
/**
 * JsCommon
 * 
 * This class contains common functionality for the website
 * 
 * @package    jeevansathi
 * @author     Tanu Gupta
 * @created    30-06-2011
 */

class JsOpsCommon{

	//Created by Nikhil

	static public function getProfileFromChecksum($checksum)
	{
		if($checksum)
		{
                    $checksum;
			$profileid=substr($checksum,33,strlen($checksum));
			$temp_check=substr($checksum,0,32);
			$real_check=md5($profileid);
			if($temp_check==$real_check)
				return $profileid;
		}
		return 0;
	}

	/**
	 * returns profilechecksum of given profileid
	 * @param $profileid profileid of user
	 * @return $checksum mixed
	 */
	static public function createChecksumForProfile($profileid)
	{
		$checksum='';
		if($profileid)
		{
			$start_tag="start";
			$end_tag="end";
			$checksum=md5($profileid)."i".$profileid;
			//$checksum=md5($start_tag.$profileid.$end_tag).$profileid;

		}
		return $checksum;
	}
	static public function login()
	{
		$request=sfContext::getInstance()->getRequest();
		$name = $request->getAttribute('name');
        include($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");//for login()
        $arr = $request->getParameterHolder()->getAll();
        $id=$arr['username'];
        $pass=$arr['password'];
        $cid = login($id,$pass);
        $domain=sfConfig::get("app_site_url");
        $this->redirect("$domain/jsadmin/mainpage.php?name=$name&cid=$cid");
        

	}
	static public function getcidname($cid)
	{
		$obj=new AgentAllocationDetails();
		return $obj->fetchAgentName($cid);
	}
		public static function getCasteLabel(Profile $profileObj){
		$sectArr=array(
			"Muslim","Christian"
		);
		$religion=$profileObj->getDecoratedReligion();
		if(in_array($religion,$sectArr))
			return "Sect";
		else
			return "Caste";
	}
		public static function getSectLabel(Profile $profileObj){
		$sectArr=array(
			"Muslim","Christian"
		);
		$religion=$profileObj->getDecoratedReligion();
		if(in_array($religion,$sectArr))
			return "Caste";
		else
			return "Sect";
	}
	/**
	 * returns profile from given checksum
	 * @param $checksum encoded value of profileid of user
	 * @return $profiled/0
	 */
	public static function formatDate($date, $format=""){
	$datesArr = explode(" ",$date);
	$dateArr = $datesArr[0];
	$hrsArr = $datesArr[1];
	$hourArr = explode(":",$hrsArr);
	$dateArr = explode("-",$date);
	$day = $dateArr[2];
	$month = $dateArr[1];
	$year = $dateArr[0];
	$hour = $hourArr[0];
	$min = $hourArr[1];
	if($format == 2)//Old format 2
        {
                if($month=="01" || $month=="1") $month="jan";
                elseif($month=="02" || $month=="2") $month="feb";
                elseif($month=="03" || $month=="3") $month="mar";
                elseif($month=="04" || $month=="4") $month="apr";
                elseif($month=="05" || $month=="5") $month="may";
                elseif($month=="06" || $month=="6") $month="jun";
                elseif($month=="07" || $month=="7") $month="july";
                elseif($month=="08" || $month=="8") $month="aug";
                elseif($month=="09" || $month=="9") $month="sep";
                elseif($month=="10") $month="oct";
                elseif($month=="11") $month="nov";
                else $month="dec";
        }
	elseif($format==4)//Old format 4
	{
		if($month=="01" || $month=="1") $month="January";
                elseif($month=="02" || $month=="2") $month="February";
                elseif($month=="03" || $month=="3") $month="March";
                elseif($month=="04" || $month=="4") $month="April";
                elseif($month=="05" || $month=="5") $month="May";
                elseif($month=="06" || $month=="6") $month="June";
                elseif($month=="07" || $month=="7") $month="July";
                elseif($month=="08" || $month=="8") $month="August";
                elseif($month=="09" || $month=="9") $month="September";
                elseif($month=="10") $month="October";
                elseif($month=="11") $month="November";
                else $month="December";
	}
	else
        {
                if($month=="01" || $month=="1") $month="Jan";
                elseif($month=="02" || $month=="2") $month="Feb";
                elseif($month=="03" || $month=="3") $month="Mar";
                elseif($month=="04" || $month=="4") $month="Apr";
                elseif($month=="05" || $month=="5") $month="May";
                elseif($month=="06" || $month=="6") $month="Jun";
                elseif($month=="07" || $month=="7") $month="Jul";
                elseif($month=="08" || $month=="8") $month="Aug";
                elseif($month=="09" || $month=="9") $month="Sep";
                elseif($month=="10") $month="Oct";
                elseif($month=="11") $month="Nov";
                else $month="Dec";
        }
        if(strlen($day)==1) $day= "0" . $day;
	if($hour!='')
        {
                if($hour>12)
                {
                        $hour=$hour-12;
                        if($hour<10) $hour="0".$hour;
                        $clock='PM';
                }
                elseif($hour==12) $clock='PM';
                elseif($hour==0)
                {
                        $hour=12;
                        $clock='AM';
                }
                else
                        $clock='AM';
                return $day." ".$month." ".$year." ".$hour.".".$min." ". $clock;
        }
        //added by lavesh
        if($format==1)
        {
                $suffix = $this->getDateSuffix($day);
                return $day.$suffix." ".$month.", ".$year;
        }
        elseif($format==2)
        {
                $yr = substr($year,2,3);
                return $day.$month."'".$yr;
        }
	elseif($format==3)
        {
                return $month;
        }
	elseif($format==4)
	{
		$suffix = $this->getDateSuffix($day);
                return $day.$suffix." ".$month.", ".$year;
	}
        //ends here.

        return $month . " " . $day . ", " . $year;
	}
/*
	 * Functions return the string of particular field
	 * @param $label String 
	 * @param $values String
	 * @param $default  To return default if blank.
	 * @returns String	Concanated String of Labels of values given in @values
	 */
	public static function getMultiLabels($label,$values,$default="")
	{
		$data=JsOpsCommon::display_format($values);
		if(is_array($data))
		{
			if($data[0]=="DM" || $data[0]=="")
				return $default;	
			for($ll=0;$ll<count($data);$ll++)
			{
				$temp[]=FieldMap::getFieldLabel($label,$data[$ll]);
			}
			
			$ret=implode(", ",$temp);
			return $ret;
		}
		return $default;
	}
	/**
	 * Returns the array by removing quotes from string
	 * return String
	 */
	public static function display_format($str)
	{
			if($str)
			{
					$str=trim($str,"'");

					$arr=explode("','",$str);
					return $arr;
			}

	}
	public static function  updateFtoStatus($usersArray,$makePaid='')
	{
		if(is_array($usersArray))
		{
			if(1)
			{
				$profArrObj = new ProfileArray;
				$profileIdArr["PROFILEID"]=implode(",",$usersArray);
				$jpObj=new JPROFILE();
				$data=$jpObj->getArray($profileIdArr,'','',"ENTRY_DT,PROFILEID",""," ENTRY_DT ASC "," 1 ");
				if($data[0][PROFILEID])
				{
				
					$nplObj=new incentive_NEGATIVE_PROFILE_LIST();
					$records=$nplObj->AllEntry($data[0][PROFILEID]);
					if(is_array($records))
					{
						include("$_SERVER[DOCUMENT_ROOT]/crm/negativeListFlagArray.php");
						$fto_duplicate_arr=array();
						for($i=0;$i<count($records);$i++)
						{
							$type=$records[$i][TYPE];
							$fto_duplicate_arr=JsOpsCommon::updateFlag($fto_duplicate_arr,$negativeListFlagArray[$type]);
        	        	                        $fto_duplicate_arr["TYPE"] = $records[$i][TYPE];
	                        	                $fto_duplicate_arr["ENTRY_BY"] = $records[$i][ENTRY_BY];
	                                	        $fto_duplicate_arr["ENTRY_DT"] = $records[$i][ENTRY_DT];
						}
						if(count($fto_duplicate_arr))
						{
							$fto_duplicate_arr[PROFILEID]=$data[0][PROFILEID];
							$ntl=new INCENTIVE_NEGATIVE_TREATMENT_LIST();
							$ntl->updateRecord($fto_duplicate_arr);
							JsOpsCommon::FtoStatus($data[0][PROFILEID],$makePaid);
							if($fto_duplicate_arr[FLAG_VIEWABLE]=="Y")
								JsOpsCommon::insertSwap($data[0][PROFILEID]);
							
							
						}
						
					}
					else
					{
						$ntl=new INCENTIVE_NEGATIVE_TREATMENT_LIST();
						$ntl->deleteRecord($data[0][PROFILEID]);
						JsOpsCommon::FtoStatus($data[0][PROFILEID],$makePaid);
						JsOpsCommon::insertSwap($data[0][PROFILEID]);
					}
				}
			}
		}
	}
	public static function insertSwap($pid)
	{
		//inserting into swap_jprofile
                $dbObj=new NEWJS_SWAP_JPROFILE;
                $dbObj->insert($pid);
	}
	public static function  updateFlag($flagArray,$array)
	{
		foreach($array as $key=>$val)
		{
			if(!$flagArray[$key] || $val=='N')
				$flagArray[$key]=$val;
		}
		return $flagArray;
	}
	public static function  FtoStatus($pid,$makePaid='')
	{
		$Profile=new Profile();
                $Profile->getDetail($pid,"PROFILEID");

		if($makePaid)
                        $Profile->getPROFILE_STATE()->updateFTOState($Profile,FTOStateUpdateReason::TAKE_MEMBERSHIP);
                else
                        $Profile->getPROFILE_STATE()->updateFTOState($Profile,FTOStateUpdateReason::MARK_NON_DUPLICATE);
	}
}
?>
