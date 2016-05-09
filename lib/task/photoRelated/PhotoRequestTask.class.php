<?php

/*
 * Author: Kumar Anand
 * This task gets all the profiles having photo requests and send photo request emails to those profiles
*/

class PhotoRequestTask extends sfBaseTask
{
 	protected function configure()
  	{

		$this->addArguments(array(
		new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
		));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'PhotoRequest';
	    $this->briefDescription = 'sends photo request mailers to profiles';
	    $this->detailedDescription = <<<EOF
	This cron runs daily and fetches the profiles having photo requests and then send photo request emails to those profiles.
	Call it with:

	  [php symfony cron:PhotoRequest currentScriptNo] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

		$currentScript = $arguments["currentScript"]; // current script number	either 0 or 1 or 2
		if($currentScript == 0 || $currentScript == 1 || $currentScript == 2)
		{
			$days=1;
			$dt=date('Y-m-d',JSstrToTime('now -'.$days.' days'));
			$activeServerId = $currentScript;

			$dbName = JsDbSharding::getShardDbName($activeServerId,1);
			$prObj = new NEWJS_PHOTO_REQUEST($dbName);
			//GET PROFILES TO WHICH PHOTO REQUEST HAVE BEEN MADE
			$profile_having_request_array = $prObj->getProfilesHavingPhotoRequest($activeServerId,$dt);	//DAILY BASIS
			//GETTING PROFILES ENDS
			if($profile_having_request_array && is_array($profile_having_request_array))
			{
				foreach($profile_having_request_array as $profileid_requested)
				{
					$profileArrayObj = new ProfileArray;
					$profileArr = $profileArrayObj->getResultsBasedOnJprofileFields(array("PROFILEID"=>$profileid_requested,"ACTIVATED"=>"'Y'","HAVEPHOTO"=>"'N',''"),'','','PROFILEID,EMAIL,GENDER,SERVICE_MESSAGES,USERNAME,SUBSCRIPTION','JPROFILE','newjs_slave','',array("SORT_DT"=>"DATE_SUB(CURDATE(), INTERVAL 5 MONTH)"));
					unset($profileArrayObj);
					if($profileArr && is_array($profileArr) && $profileArr[0]->getPROFILEID())
					{
						$profileObj = $profileArr[0];
						if($profileObj->getSERVICE_MESSAGES()=="S")
						{
							$profile_made_request_array = $prObj->getProfilesWhichMadePhotoRequest($profileid_requested,1,$dt);
							if($profile_made_request_array && is_array($profile_made_request_array))
							{
								$crObj = new ContactsRecords;
								$CorD_Profiles = $crObj->checkIfContactTypeIsCorD($dbName,$profileid_requested,$profile_made_request_array);
								unset($crObj);

								if($CorD_Profiles && is_array($CorD_Profiles))
								{
									foreach($CorD_Profiles as $kk=>$vv)
									{
										$key = array_search($vv,$profile_made_request_array);
										if($key || $key===0)
											unset($profile_made_request_array[$key]);
									}
								}
								unset($CorD_Profiles);

								if($profile_made_request_array && is_array($profile_made_request_array))
								{
									$profileArrayObj = new ProfileArray;
									$outputArr = $profileArrayObj->getResultsBasedOnJprofileFields(array("PROFILEID"=>implode(",",$profile_made_request_array),"ACTIVATED"=>"'Y'"),'','',"PROFILEID,HAVEPHOTO",'JPROFILE','newjs_slave');
									unset($profileArrayObj);

									if($outputArr && is_array($outputArr))
									{
										foreach($outputArr as $k=>$row)
										{
											$profile_made_request_final_array[$k]["PROFILEID"] = $row->getPROFILEID();
											$profile_made_request_final_array[$k]["HAVEPHOTO"] = $row->getHAVEPHOTO();
										}

										if($profile_made_request_final_array && is_array($profile_made_request_final_array))
										{
											$popular_profile_made_request_arr = $this->getPopularProfile($profile_made_request_final_array);
											if($popular_profile_made_request_arr && is_array($popular_profile_made_request_arr) && $popular_profile_made_request_arr["PROFILEID"])
											{
												$this->sendMail($popular_profile_made_request_arr,count($profile_made_request_final_array)-1,$profileObj);
											}
										}
										unset($profile_made_request_final_array);
									}
									unset($outputArr);
								}
							}
							unset($profile_made_request_array);
						}
						unset($profileObj);
					}
					unset($profileArr);
				}
			}
			unset($prObj);
			unset($profile_having_request_array);
		}
		else
		{
			echo "Invalid Arguement Passed";
		}
  	}
	
	private function sendMail($requestedProfileArr,$countOfRequest,$receiverProfileObj)
	{
                $photo_requested_by_profileid = $requestedProfileArr["PROFILEID"];
		$mailGroup = MailerGroup::PHOTO_REQUEST;
		if(1)
                {
                        $fto_sub_state = JsCommon::getProfileState($receiverProfileObj);
			if($requestedProfileArr["HAVEPHOTO"]=="Y")
			{
                        	if($fto_sub_state==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO)  //C1
                        	{
                			$email_sender=new EmailSender($mailGroup,1746);
					$photo_request_mailer_case = 1;
                        	}
                        	elseif($fto_sub_state==FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)   	//C2
                        	{
                			$email_sender=new EmailSender($mailGroup,1750);
					$photo_request_mailer_case = 2;
                        	}
                        	elseif($fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::NEVER_EXPOSED || $fto_sub_state==FTOSubStateTypes::DUPLICATE || $fto_sub_state==FTOSubStateTypes::PAID)      //E1-E5,F,G,Paid
                        	{
                			$email_sender=new EmailSender($mailGroup,1751);
					$photo_request_mailer_case = 3;
                        	}
			}
			else
			{
                        	if($fto_sub_state==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO)  //C1
                        	{
                			$email_sender=new EmailSender($mailGroup,1747);
					$photo_request_mailer_case = 1;
                        	}
                        	elseif($fto_sub_state==FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)   	//C2
                        	{
                			$email_sender=new EmailSender($mailGroup,1752);
					$photo_request_mailer_case = 2;
                        	}
                        	elseif($fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::NEVER_EXPOSED || $fto_sub_state==FTOSubStateTypes::DUPLICATE || $fto_sub_state==FTOSubStateTypes::PAID)      //E1-E5,F,G,Paid
                        	{
                			$email_sender=new EmailSender($mailGroup,1753);
					$photo_request_mailer_case = 3;
                        	}
			}
                }
                //else
		if(!$photo_request_mailer_case)
                {
			if($requestedProfileArr["HAVEPHOTO"]=="Y")
				$email_sender=new EmailSender($mailGroup,1751);
			else
				$email_sender=new EmailSender($mailGroup,1753);
			$photo_request_mailer_case = 3;
                }

		if($email_sender)
		{
			$tpl = $email_sender->setProfileId($receiverProfileObj->getPROFILEID());
			$p_list = new PartialList;
			$p_list->addPartial('requested_tuple','photo_profiles',array($photo_requested_by_profileid));
			$tpl->setPartials($p_list);

			$smartyObj = $tpl->getSmarty();
			$smartyObj->assign("PHOTO_REQUEST_MAILER_CASE",$photo_request_mailer_case);
			$smartyObj->assign("PHOTO_REQUESTED_BY_PROFILEID",$photo_requested_by_profileid);
			$smartyObj->assign("PHOTO_REQUEST_MAILER",1);
			$smartyObj->assign("otherProfile",$photo_requested_by_profileid);
			if($countOfRequest)
				$smartyObj->assign("TOTAL_REQUEST",$countOfRequest);


			$fto_exp_date = $receiverProfileObj->getPROFILE_STATE()->getFTOStates()->getExpiryDate();
			$smartyObj->assign("FTO_END_MONTH_UPPERCASE",strtoupper(date("M",JSstrToTime($fto_exp_date))));
			$smartyObj->assign("FTO_END_MONTH",date("M",JSstrToTime($fto_exp_date)));
			$smartyObj->assign("FTO_END_YEAR",date("Y",JSstrToTime($fto_exp_date)));
			$smartyObj->assign("FTO_END_DAY",date("d",JSstrToTime($fto_exp_date)));
			$smartyObj->assign("FTO_END_DAY_SUFFIX",date("S",JSstrToTime($fto_exp_date)));
			$smartyObj->assign("FTO_END_DAY_SINGLE_DOUBLE_DIGIT",date("j",JSstrToTime($fto_exp_date)));
			$smartyObj->assign("photoGender",$receiverProfileObj->getGENDER()=="M"?"":"girl-");
			$email_sender->send();
		}
	}

	private function getPopularProfile($profileArr)
	{
		if($profileArr && is_array($profileArr))
		{
			if(count($profileArr)>1)
			{
				foreach($profileArr as $k=>$v)
				{
					if($v["HAVEPHOTO"]=="Y")
						$profile_with_photo_arr[] = $v["PROFILEID"];
					else
						$profile_with_no_photo_arr[] = $v["PROFILEID"];
				}

				if($profile_with_photo_arr && is_array($profile_with_photo_arr))
				{
					if(count($profile_with_photo_arr)>1)
					{
						$search_array=SearchCommonFunctions::findMostSortTypeProfile($profile_with_photo_arr,'mailer_photo_request',SearchSortTypesEnums::popularSortFlag);
						if($search_array["SEARCH_RESULTS"] && is_array($search_array["SEARCH_RESULTS"]))
						{
							$output["PROFILEID"] = implode("",$search_array["SEARCH_RESULTS"]);
							$output["HAVEPHOTO"] = "Y";
						}
					}
					else
					{
						$output["PROFILEID"] = $profile_with_photo_arr[0];
						$output["HAVEPHOTO"] = "Y";
					}
				}
				else
				{
					$search_array=SearchCommonFunctions::findMostSortTypeProfile($profile_with_no_photo_arr,'mailer_photo_request',SearchSortTypesEnums::popularSortFlag);
					if($search_array["SEARCH_RESULTS"] && is_array($search_array["SEARCH_RESULTS"]))
					{
						$output["PROFILEID"] = implode("",$search_array["SEARCH_RESULTS"]);
						$output["HAVEPHOTO"] = "N";
					}
				}
			}
			else
			{
				foreach($profileArr as $k=>$v)
				{
					$output["PROFILEID"] = $v["PROFILEID"];
					$output["HAVEPHOTO"] = $v["HAVEPHOTO"];
				}
			}
		}
		return $output;
	}
}
