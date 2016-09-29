<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 */
class gunascorev1Action extends sfAction
{
        public function execute($request)
	{
		$apiObj=ApiResponseHandler::getInstance();
		//Contains login credentials
                $this->loginData=$data=$request->getAttribute("loginData");
		$profileid=$this->loginData['PROFILEID'];  //getting the profileid
                $gender=$this->loginData["GENDER"];
		$oProfile=CommonFunction::getProfileFromChecksum($request->getParameter("oprofile"));
		//$oProfile=intval($request->getParameter("oprofile"));
		$parent="";
		if($oProfile && $profileid)
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$dbJprofile= new JPROFILE();
        	        $myrow=$dbJprofile->get($profileid,"PROFILEID","CASTE,GENDER");
			$other_row=$dbJprofile->get($oProfile,"PROFILEID","CASTE,GENDER");
			if($myrow[GENDER]==$other_row[GENDER])
				$notshow=1;
			$caste=$myrow["CASTE"];
	                $gender=$myrow["GENDER"];
        	        if($gender == 'M')
                	        $gender_value = 1;
	                else
        	                $gender_value = 2;
			if($caste)
			{
				$dbObj=new NEWJS_CASTE;
				$parent=$dbObj->getParentIfSingle($caste);
			}
			
			if(in_array($parent,array(1,9,4,7)) && !$notshow)
			{
				
				$dbObj= ProfileAstro::getInstance();
				$dbdata=$dbObj->getAstroDetails(array(intval($profileid),intval($oProfile)),"PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL",1);
                                if(is_array($dbdata))
				foreach($dbdata as $key=>$myrow_astro)
				{
					$astro_pid1=$myrow_astro["PROFILEID"];
					if($astro_pid1)
					{
						$lagna=$myrow_astro['LAGNA_DEGREES_FULL'];
						$sun=$myrow_astro['SUN_DEGREES_FULL'];
						$mo=$myrow_astro['MOON_DEGREES_FULL'];
						$ma=$myrow_astro['MARS_DEGREES_FULL'];
						$me=$myrow_astro['MERCURY_DEGREES_FULL'];
						$ju=$myrow_astro['JUPITER_DEGREES_FULL'];
						$ve=$myrow_astro['VENUS_DEGREES_FULL'];
						$sa=$myrow_astro['SATURN_DEGREES_FULL'];
						$gender_val=1;
						if($gender_value==1)
							$gender_val=2;
						if($astro_pid1==$profileid)
							$logged_astro_details="$astro_pid1:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
						else
							$compstring="$astro_pid1:$gender_val:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa@";
					}
				}
				//$compstring=$logged_astro_details."@";
				if($logged_astro_details && $compstring)
				{
					$url = "http://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull?".$logged_astro_details."&".$compstring;
					//$fp = @fsockopen("vendors.vedic-astrology.net", 80, &$errno, &$errstr);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
					curl_setopt($ch, CURLOPT_TIMEOUT, 4);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

					$fresult = curl_exec($ch);
					$subject = $fresult;
					$pattern = '/Guna:([0-9|.]{1,6})/';
					preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
					$matches[1][0]=intval($matches[1][0]);
					curl_close ($ch);
				}

				
			}
		}
		else
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		}
		$apiObj->setResponseBody(array("SCORE"=>$matches[1][0]));
		$apiObj->generateResponse();
		if($request->getParameter('INTERNAL')==1){
			return sfView::NONE;
		} else {
			die;
		}

        }
} 
