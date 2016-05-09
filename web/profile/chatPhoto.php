<?php
include_once("connect.inc");
include_once("functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("arrays.php");
include_once("payment_array.php");
include_once("sphinx_search_function.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$db=connect_db();
$data=authenticated();
//echo "cool";
	if($data)
	{
	//echo "000000000".$profileid;
	//$profileid=164144;
	//echo "profileid is >>>>".$profileid;
	//echo "checksum is >>>>".$profilechecksum;
	$profileid=getProfileidFromChecksum($profilechecksum);
	$profileid=intval($profileid);
	//echo "profileid is >>>>".$profileid;
	if(!is_int($profileid))
		die();
	//echo "111111";
	$resultprofiles=$profileid;
		//if($data["PROFILEID"]!="" && mysql_num_rows($result)>0)
		//echo "2222222222";
        if($data["PROFILEID"]!="" && $resultprofiles)
        {
               // echo "33333333";
                //Sharding On Contacts done by Lavesh Rawat
                $contactResult=getResultSet("RECEIVER,TYPE",$data["PROFILEID"],"",$resultprofiles,"","","","","","","Y","");
			
                if(is_array($contactResult))
                {
                        foreach($contactResult as $key=>$value)
                        {
                                $contacted1[$contactResult[$key]["RECEIVER"]]=$contactResult[$key]["TYPE"];
                                $contacted2[$contactResult[$key]["RECEIVER"]]="R";
                        }
                }
                unset($contactResult);
				//echo "4444444444";
                $contactResult=getResultSet("SENDER,TYPE,TIME",$resultprofiles,"",$data["PROFILEID"],"","","","","","","Y","");
                if(is_array($contactResult))
                {
                        foreach($contactResult as $key=>$value)
                        {
                                $profile_time_arr[$i]["PROFILEID"]=$contactResult[$key]["SENDER"];
                                $profile_time_arr[$i]["TIME"]=$contactResult[$key]["TIME"];
                                $contacted1[$contactResult[$key]["SENDER"]]=$contactResult[$key]["TYPE"];
                                $contacted2[$contactResult[$key]["SENDER"]]="S";
                        }
                }
                unset($contactResult);
	}
		$sql="select HAVEPHOTO,PRIVACY,PHOTO_DISPLAY,GENDER,PROFILEID from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql);
		while($myrow=mysql_fetch_array($res))
		{

		 if($myrow["HAVEPHOTO"]=="U")
                        $havephoto="U";
                elseif($myrow["HAVEPHOTO"]=="Y")
                        $havephoto="Y";
                else
                        $havephoto="N";
if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
                {
                        if(!$data)
                        {
                                //$havephoto="P";
                                $havephoto="L";
                        }
                        elseif($data && $myrow["PRIVACY"]=="F")
                        {
                                if(check_privacy_filtered1($data["PROFILEID"],$myrow["PROFILEID"]))
                                        //$havephoto="P";
                                        $havephoto="F";
                        }
                }
if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
                {
                        //if(!$data || $myrow["PHOTO_DISPLAY"]=="H")
                                //$havephoto="P";
                        if($myrow["PHOTO_DISPLAY"]=="H")
                                $havephoto="H";
                        elseif(!$data && $myrow["PHOTO_DISPLAY"]=="C")
                                $havephoto="L";
                        elseif(!$data && $myrow["PHOTO_DISPLAY"]=="F")
                                $havephoto="L";
                        elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
                        {
                                if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A"))))
                                        ;
                                else
                                {
                                        //$havephoto="P";
                                        $havephoto="C";
                                }
                        }
                        elseif($data && $myrow["PHOTO_DISPLAY"]=="F")
                        {
                                if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" ))))
                                        ;
                                elseif(check_privacy_filtered1($data["PROFILEID"],$myrow["PROFILEID"]))
                                        //$havephoto="P";
                                        $havephoto="P";
                        }
                }

		$profilechecksum=md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];

		$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
                $photochecksum_new = intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow["PROFILEID"]+5);

		//Symfony Photo Modification.
		$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($myrow["PROFILEID"]);
		$profilePicObj = $profilePicObjs[$myrow["PROFILEID"]];
		if ($profilePicObj)
			$thumbnailUrl = $profilePicObj->getThumbailUrl(); 
		else
			$thumbnailUrl = null;

		$gender=$myrow['GENDER'];
		$image_file=return_image_file_small($havephoto,$gender);
		$my_photo="$IMG_URL/profile/ser4_images/$image_file";
                if($havephoto=='Y')
                {
			header('Content-type: image/jpeg');
			$my_photo=$thumbnailUrl;//Symfony Photo Modification.
		}
		else
			header('Content-type: image/gif');
		
		readfile($my_photo);
	}
	}
