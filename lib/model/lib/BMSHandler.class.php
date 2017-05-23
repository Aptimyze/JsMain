<?php
/*
 * Name: BMSHandler Class
 * Description: This class handles All the activity related to Zedo
 * 2014-06-25 Intial Draft Version 1 Pankaj Khandelwal  <pankaj.khandelwal@jeevansathi.com>
 */
Class BMSHandler{

public function setBMSVariable($data,$isSymfony,$request)
	{
		
		if(strstr($_SERVER["PHP_SELF"] ,"symfony_index.php"))
		{
			$request = sfContext::getInstance()->getRequest();
			$page = $request->getParameter("module");
			$action = $request->getParameter("action");
		}
		else
		{
			if (strstr($_SERVER["PHP_SELF"],"index") )
				if($data["PROFILEID"])
				{
					$page = "myjs";
				}
				else
					$page = "Home";
			else if (strstr($_SERVER["PHP_SELF"],"login"))
				$page = "Login";
			else if (strstr($_SERVER["PHP_SELF"],"logout"))
				$page = "Login"; 
			else if (strstr($_SERVER["PHP_SELF"],"mainmenu"))
				$page = "myjs"; 
			else $page = "Other";
		}
		if($data["PROFILEID"])
		{
			$profileid = $data["PROFILEID"];
		}
		else if($_COOKIE["ISEARCH"])
		{
			$profileid = $_COOKIE["ISEARCH"];
		}
		if($profileid)
		{ 
			$Detail = JsMemcache::getInstance()->get($profileid."_BMS");
			if(!$Detail )
			{
				$profile = LoggedInProfile::getInstance();
				$vdObj = new VariableDiscount();
				$vdValue = $vdObj->getSlabForProfile($profileid);
				$userObj=new memUser($profileid);
				$userObj->setMemStatus();
				switch($userObj->userType){
					case 1:
					case 2:
					case 7:
						$mem = 1;
						break;
					case 3:
						$memObj = new MembershipHandler();
						$mem = $memObj->bmsCheckRenewalDiscountGiven($profileid)?4:5;
						break;
					case 5:
						$mem = 2;
						break;
					case 4:
					case 6:
						$mem = 3;
						break;
					}
				$profileDetails["d9"] = $vdValue;
				$profileDetails["A3"] = $profile->getAGE();
				$profileDetails["d10"] = $profile->getINCOME();
				$profileDetails["d3"] = $profile->getOCCUPATION();
				$profileDetails["d1"] = $profile->getEDU_LEVEL_NEW();
				$profileDetails["d2"] = $mem;
				$profileDetails["A2"] = $profile->getGENDER();
				$profileDetails["A1"] = $profile->getRELIGION();
				$profileDetails["d4"] = $profile->getCASTE();
				$profileDetails["d5"] = $profile->getMSTATUS();
				/* j1 to j3 are being used for webengage */
				$profileDetails["j1"] = $profile->getSOURCE();
				$profileDetails["j2"] = floor((strtotime(date("Y-m-d"))-strtotime(substr($profile->getENTRY_DT(),0,10)))/(60*60*24));
				$profileDetails["j3"] = $profile->getMTONGUE();	

				if($profile->getHAVEPHOTO() == "Y" || $profile->getHAVEPHOTO() == "U")
					$profileDetails["d8"] = 1;
				else
					$profileDetails["d8"] = 2;
				$profileDetails["d6"] = $profile->getINCOMPLETE() == "Y"?"I":"C";
				$profileDetails["d7"] = 1;
				$profileDetails["d11"] = $profile->getRELATION();
				$Detail = json_encode($profileDetails);
				JsMemcache::getInstance()->set($profileid."_BMS",$Detail);
			}
		}
		else
		{
			$profileDetails["d7"] = ($_COOKIE["ISEARCH"])?1:2;
			$Detail = json_encode($profileDetails);
		}
		$profileCustomDetails = json_decode(html_entity_decode($Detail),true);
		if(is_array($profileCustomDetails)){
			foreach ($profileCustomDetails as $key=>$value)
			{
				$str = $str.$key.":".$value."^";
			}
		$str = substr($str, 0, -1);
		$profileCustomDetails["custom"] = $str;
		}
		//echo $page;
		switch($page){
			
			case "search":
				if($action == "viewSimilarProfile")
					$channel = 14;
				else
					$channel = 2;
				$zedo["masterTag"] = 660170;
				$zedo["tag"]["belly1"]["id"] = 1;
				$zedo["tag"]["belly1"]["size"] = 65;
				$zedo["tag"]["belly1"]["source"] = $channel;
				$zedo["tag"]["belly1"]["network"] = 1;
				$zedo["tag"]["belly1"]["width"] = 728;
				$zedo["tag"]["belly1"]["height"] = 90;
				$zedo["tag"]["belly2"]["id"] = 2;
				$zedo["tag"]["belly2"]["size"] = 66;
				$zedo["tag"]["belly2"]["source"] = $channel;
				$zedo["tag"]["belly2"]["network"] = 1;
				$zedo["tag"]["belly2"]["width"] = 728;
				$zedo["tag"]["belly2"]["height"] = 90;
				$zedo["tag"]["belly3"]["id"] = 3;
				$zedo["tag"]["belly3"]["size"] = 67;
				$zedo["tag"]["belly3"]["source"] = $channel;
				$zedo["tag"]["belly3"]["network"] = 1;
				$zedo["tag"]["belly3"]["width"] = 728;
				$zedo["tag"]["belly3"]["height"] = 90;
				$zedo["tag"]["belly4"]["id"] = 4;
				$zedo["tag"]["belly4"]["size"] = 68;
				$zedo["tag"]["belly4"]["source"] = $channel;
				$zedo["tag"]["belly4"]["network"] = 1;
				$zedo["tag"]["belly4"]["width"] = 728;
				$zedo["tag"]["belly4"]["height"] = 90;
				$zedo["tag"]["searchbottom"]["id"] = 5;
				$zedo["tag"]["searchbottom"]["size"] = 85;
				$zedo["tag"]["searchbottom"]["source"] = $channel;
				$zedo["tag"]["searchbottom"]["network"] = 1;
				$zedo["tag"]["searchbottom"]["width"] = 728;
				$zedo["tag"]["searchbottom"]["height"] = 90;
				$zedo["commonFooter"] = 0;
				break;
			case "profile":
				$zedo["masterTag"] = 157126;
				$zedo["tag"]["side"]["id"] = 1;
				$zedo["tag"]["side"]["size"] = 7;
				$zedo["tag"]["side"]["source"] = 3;
				$zedo["tag"]["side"]["network"] = 1;
				$zedo["tag"]["side"]["width"] = 160;
				$zedo["tag"]["side"]["height"] = 600;
				$zedo["tag"]["bottom"]["id"] = 2;
				$zedo["tag"]["bottom"]["size"] = 64;
				$zedo["tag"]["bottom"]["source"] = 3;
				$zedo["tag"]["bottom"]["network"] = 1;
				$zedo["tag"]["bottom"]["width"] = 970;
				$zedo["tag"]["bottom"]["height"] = 90;
				$zedo["commonFooter"] = 1;
				break;
			case "myjs":
				$zedo["masterTag"] = 584097;
				$zedo["tag"]["bottom"]["id"] = 1;
				$zedo["tag"]["bottom"]["size"] = 64;
				$zedo["tag"]["bottom"]["source"] = 6;
				$zedo["tag"]["bottom"]["network"] = 1;
				$zedo["tag"]["bottom"]["width"] = 970;
				$zedo["tag"]["bottom"]["height"] = 90;
				break;
			case "inbox":
				$zedo["masterTag"] = 34601;
				$zedo["tag"]["bottom"]["id"] = 1;
				$zedo["tag"]["bottom"]["size"] = 64;
				$zedo["tag"]["bottom"]["source"] = 13;
				$zedo["tag"]["bottom"]["network"] = 1;
				$zedo["tag"]["bottom"]["width"] = 970;
				$zedo["tag"]["bottom"]["height"] = 90;
				$zedo["commonFooter"] = 1;
				break;
			case "Login":
				$zedo["masterTag"] = 907802;
				$zedo["tag"]["topsmall"]["id"] = 1;
				$zedo["tag"]["topsmall"]["size"] = 95;
				$zedo["tag"]["topsmall"]["source"] = 6;
				$zedo["tag"]["topsmall"]["network"] = 1;
				$zedo["tag"]["topsmall"]["width"] = 468;
				$zedo["tag"]["topsmall"]["height"] = 60;
				$zedo["tag"]["left"]["id"] = 2;
				$zedo["tag"]["left"]["size"] = 89;
				$zedo["tag"]["left"]["source"] = 5;
				$zedo["tag"]["left"]["network"] = 1;
				$zedo["tag"]["left"]["width"] = 420;
				$zedo["tag"]["left"]["height"] = 450;
				$zedo["tag"]["right"]["id"] = 3;
				$zedo["tag"]["right"]["size"] = 9;
				$zedo["tag"]["right"]["source"] = 5;
				$zedo["tag"]["right"]["network"] = 1;
				$zedo["tag"]["right"]["width"] = 300;
				$zedo["tag"]["right"]["height"] = 250;
				$zedo["tag"]["bottom"]["id"] = 4;
				$zedo["tag"]["bottom"]["size"] = 85;
				$zedo["tag"]["bottom"]["source"] = 5;
				$zedo["tag"]["bottom"]["network"] = 1;
				$zedo["tag"]["bottom"]["width"] = 728;
				$zedo["tag"]["bottom"]["height"] = 90;
				
				break;
			case "Home":
				$zedo["masterTag"] = 646249;
				$zedo["tag"]["bottom"]["id"] = 1;
				$zedo["tag"]["bottom"]["size"] = 64;
				$zedo["tag"]["bottom"]["source"] = 1;
				$zedo["tag"]["bottom"]["network"] = 1;
				$zedo["tag"]["bottom"]["width"] = 970;
				$zedo["tag"]["bottom"]["height"] = 90;
				$zedo['commonFooter'] = 1;
				break;
			case "seo":
				$zedo["masterTag"] = 679271;
				$zedo["tag"]["bottom"]["id"] = 1;
                $zedo["tag"]["bottom"]["size"] = 64;
                $zedo["tag"]["bottom"]["source"] = 12;
                $zedo["tag"]["bottom"]["network"] = 1;
                $zedo["tag"]["bottom"]["width"] = 970;
                $zedo["tag"]["bottom"]["height"] = 90;
                $zedo['commonFooter'] = 1;
				break;
			case "static":
				if($action == "logoutPage")
				{
					$zedo["masterTag"] = 882953;
					$zedo["tag"]["bottom"]["id"] = 1;
	                $zedo["tag"]["bottom"]["size"] = 64;
	                $zedo["tag"]["bottom"]["source"] = 5;
	                $zedo["tag"]["bottom"]["network"] = 1;
	                $zedo["tag"]["bottom"]["width"] = 970;
	                $zedo["tag"]["bottom"]["height"] = 90;
	                $zedo["tag"]["left2"]["id"] = 2;
	                $zedo["tag"]["left2"]["size"] = 20;
	                $zedo["tag"]["left2"]["source"] = 5;
	                $zedo["tag"]["left2"]["network"] = 1;
	                $zedo["tag"]["left2"]["width"] = 300;
	                $zedo["tag"]["left2"]["height"] = 250;
					$zedo["tag"]["left1"]["id"] = 3;
	                $zedo["tag"]["left1"]["size"] = 9;
	                $zedo["tag"]["left1"]["source"] = 5;
	                $zedo["tag"]["left1"]["network"] = 1;
	                $zedo["tag"]["left1"]["width"] = 300;
	                $zedo["tag"]["left1"]["height"] = 250;
	                $zedo["commonFooter"] = 1;
	            }
	            else
	            {
	            	$zedo["masterTag"] = 616105;
					$zedo["tag"]["bottom"]["id"] = 1;
					$zedo["tag"]["bottom"]["size"] = 64;
					$zedo["tag"]["bottom"]["source"] = 0;
					$zedo["tag"]["bottom"]["network"] = 0;
					$zedo["tag"]["bottom"]["width"] = 970;
					$zedo["tag"]["bottom"]["height"] = 90;
					$zedo["commonFooter"] = 1;
	            }
	            break;
				
			default:
				$zedo["masterTag"] = 616105;
				$zedo["tag"]["bottom"]["id"] = 3;
				$zedo["tag"]["bottom"]["size"] = 64;
				$zedo["tag"]["bottom"]["source"] = 0;
				$zedo["tag"]["bottom"]["network"] = 0;
				$zedo["tag"]["bottom"]["width"] = 970;
				$zedo["tag"]["bottom"]["height"] = 90;
				$zedo["commonFooter"] = 1;
				
				break;
				
		}
		
	
		$profileCustomDetails["zedo"] = $zedo;
		return $profileCustomDetails;
	}


}
