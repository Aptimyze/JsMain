<?php
class PixelCode
{

	/** 
	 * This function is used to fecth rocket fuel pixelcode for conversion after registration. Currently it is called from registration pages 1,2,3 in case of desktop registration and upload photo and edit profile page after incomplete layer on delstop.
	 * */
	 public static function fetchRocketFuelCode($page="") {
		 $uid=uniqid();
		 if($page=="regPage1")
		 {
			 $pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548335p.rfihub.com/ca.gif?rb=8177&ca=20548335&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0' ,style:'display:none'}).appendTo(\"body\");
    });
</script>";
		}
		else if($page=="regPage2")
		{
			$pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548337p.rfihub.com/ca.gif?rb=8177&ca=20548337&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0',style:'display:none' }).appendTo(\"body\");
    });
</script>";
		}
		else if($page=="regPage3")
		{
			$pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548343p.rfihub.com/ca.gif?rb=8177&ca=20548343&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0',style:'display:none' }).appendTo(\"body\");
    });
</script>";
		}
		else if($page=="upload")
		{
			$pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548339p.rfihub.com/ca.gif?rb=8177&ca=20548339&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0',style:'display:none' }).appendTo(\"body\");
    });
</script>";
		}

		return $pixelcode;
	}
	
	/** 
	 * This function is used to fecth pixelcode for conversion after registration. Currently it is called from fto/offer in case mobile and page 3 registration in case of desktop registration 
	 * */
    public static function fetchPixelcode($groupname, $adnetwork1,$profile) {
        $pixelcode_pdo = new MIS_PIXELCODE();
        $pixelcode = $pixelcode_pdo->getPixelcode($groupname);
        $pixelcode = str_replace('~$CITY`', $profile->getDecoratedCity(), $pixelcode);
        $pixelcode = str_replace('~$USERNAME`', $profile->getUSERNAME(), $pixelcode);
        $pixelcode = str_replace('~$AGE`', $profile->getAge(), $pixelcode);
        $pixelcode = str_replace('~$GENDER`', $profile->getDecoratedGender(), $pixelcode);
        $pixelcode = str_replace('~$PROFILEID`', $profile->getPROFILEID(), $pixelcode);
        $pixelcode = str_replace('~$ADNETWORK1`', $adnetwork1, $pixelcode);
        if(($groupname == "VCommission_May10") && !self::firePixelCheck($profile))
            return;
        else 
            return $pixelcode;
        
    }

	/** 
	 * This function is used to validate whether rocket fuel pixel need to fired as per product requirement
	 * @param $groupname=source gorup name
	 * $sourceId source from where the profile is coming
	 * $age of profile
	 * $gender gender of profile
	 * $mtongue mother tongue of profile
	 * $religion=religion of profile
	 * $page whether it belongs to the registratio page1 or not if yes then it should be equal to 1
     * @return true or false
	 * */
	public static function RocketFuelValidation($groupname,$sourceId,$age="",$gender="",$mtongue="",$religion="",$page=0)
	{
		if($groupname=="RocketFuel")
			return true;
		
		//checks whether belogn to requried Source and SourceGroup 
		if(($groupname=="jeevansathi" || $groupname=="facebook" || $groupname=="facebook_nov10" || strtolower(substr($groupname,0,3))=="seo"))
			$checkFlag= true;
		elseif($groupname=="google_custom" && ($sourceId=="marry59" || $sourceId=="Prof"))
			$checkFlag= true;
		elseif($groupname=="MobileSEM" && $sourceId=="m_marry59")
			$checkFlag= true;
		else
			$checkFlag= false;

		//if coming from a requried source and sourcegroup and for registration page1
		if($checkFlag && $page)
			return true;
		
		// If belongs to a required source and sourceGroup 
		if($checkFlag)
		{	
			$mtongueArr=FieldMap::getFieldLabel("allHindiMtongues","",1);
			//for mtnogues punjabi and marathi
			$mtongueArr[]="27";
			$mtongueArr[]="20";
			
			//if belong to religion hindu and required motherTongues and satisfy conditions of male female age group
			if($religion=="1" && in_array($mtongue,$mtongueArr))
			{
				if($gender=="M" && $age>=26 && $age<=34)
					return true;
				elseif($gender=="F" && $age>=24 && $age<=32)
					return true;
				else
					return false;			
			}
			else
				return false;
		}
		else
		{
			return false;
		}	
	}
       public static function firePixelCheck($profileDetails)
       {
           $new_cityarr=array('PU11','UP01','GU01','MH30','RA01','MH01','UP02','UP03','RA02','HA01','UP04','AP13','PU01','UP32','GU02','AP14','KA01','BI08','MH02','UP05','WB07','WB08','KA02','WB09','WB10','WB02','UP06','GU04','WB11','WB12','PU12','PU02','RA12','KA10','BI09','GU13','GU14','CH04','RA04','MP13','MH14','HA07','MP02','OR01','GU15','MH15','BI10','KA11','RA05','CH03','JH04','UP34','UP35','MP14','WB16','WB17','PH00','MH16','BI11','AP16','WB18','OR02','HP01','BI12','WB20','UK05','MP15','JH03','KA05','MH17','CH02','WB03','GU16','UP36','UP08','UP09','HA02','PU03','UP10','PU14','UP11','GU17','GU05','RA06','BI03','UP12','GU18','MH18','UP13','JK01','MP16','PU05','HA03','MP07','WB22','WB23','WB24','UP14','UK02','UP37','HA08','PU06','AP03','MP08','MP09','RA07','RA11','PU10','MH20','MH21','RA13','GU19','JK04','JH02','UP16','RA08','GU06','AP04','GU20','GU07','UP17','UP18','HA09','HP02','BI13','WB04','MH03','WB05','RA09','WB27','MH22','UP19','PU07','MH23','UP20','UP38','WB29','UP21','UP39','UP40','PU15','UP22','MP18','GU21','MH04','BI14','MP19','UK01','UP24','BI05','KA09','WB30','GU22','MH05','WB31','MH06','MH24','GU23','DE00','UP25','GU08','RA14','WB32','HA06','MH25','PU08','PU09','BI06','UP41','GU24','MH08','OR07','BI15','UP26','WB33','CH01','GU09','UP42','JH01','WB34','MP21','MP22','HA10','UK04','HA04','UK03','MP23','UP29','BI16','OR09','UP43','MH09','PU16','MP24','WB36','UP44','HP03','MH10','MP25','RA15','HA05','UP45','MH11','HA11','WB38','JK03','GU10','MH12','WB39','RA16','RA10','KA26','MP11','MH13','UP46','GU25','GU12','UP30','AP09','MH26','HA12','MH27','GO01','GO','GO02','GO03');
           if($profileDetails)
           { 
                $age=$profileDetails->getAGE();
                $gender=$profileDetails->getGENDER();
                $city=$profileDetails->getCITY_RES();
                if(($gender== 'F' && $age>=22)||($gender== 'M' && $age>=25))
                {
                    if(in_array($city, $new_cityarr))
                            return true;
                } 
           } 
           return false;
       }
}
?>
