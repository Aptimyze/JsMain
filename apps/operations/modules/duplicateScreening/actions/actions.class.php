<?php

/**
 * duplicateScreening actions.
 *
 * @package    jeevansathi
 * @subpackage duplicateScreening
 * @author     Vibhor Garg
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class duplicateScreeningActions extends sfActions
{
 /**
  * This function is used to screen the pair of duplicates by executives.
  **/
  public function executeScreening(sfWebRequest $request)
  {
	$pairObj = new DuplicateProfileScreen;

	//Post-action handling//
	if($request->getParameter("marked") == 'Duplicate')
		$pairObj->updateProbableDuplicate($this->setActionParameters($request,'YES','OUT','EXECUTIVE'));
	elseif($request->getParameter("marked") == 'Not Duplicate')
		$pairObj->updateProbableDuplicate($this->setActionParameters($request,'NO','OUT','EXECUTIVE'));
	elseif($request->getParameter("marked") == 'Cant Say')
		$pairObj->updateProbableDuplicate($this->setActionParameters($request,'PROBABLE','OUT','EXECUTIVE'));
	//Post-action handling//

	//Priority pair//
	$profileid = $request->getParameter("profileid1");
	$this->cid = $request->getParameter("cid");
	$this->pair = $pairObj->fetchProbableDuplicate($request->getAttribute("name"),$profileid);
	if(!$this->pair->getProfileid2())
	{
		echo "Queue is Empty !!!";die;
	}
	//Priority pair//	

	//Profile wise info//	
	//$this->profile1 = new LoggedInProfile($db,$this->pair->getProfileid1());
	//$this->profile2 = new LoggedInProfile($db,$this->pair->getProfileid2());
        $this->profile1 = Operator::getInstance('newjs_master',$this->pair->getProfileid1());
        $this->profile2 = Operator::getInstance('newjs_master',$this->pair->getProfileid2());
	
	$this->profiles_info($pairObj);
	//Profile wise info//

	//Profile wise photo(s)//
	$pictureServiceObj1 = new PictureService($this->profile1);
	$this->album1 = $pictureServiceObj1->getAlbumWithDeletedPhotos();
	$pictureServiceObj2 = new PictureService($this->profile2);
	$this->album2 = $pictureServiceObj2->getAlbumWithDeletedPhotos();
	//Profile wise photo(s)//
  }

  /**
  * This function is used to screen the pair of duplicates by supervisor.
  **/
  public function executeExamine(sfWebRequest $request)
  {
	$pairObj = new DuplicateProfileScreen;

	//Post-action handling//
        if($request->getParameter("marked") == 'Duplicate')
                $pairObj->updateProbableDuplicate($this->setActionParameters($request,'YES','OUT','SUPERVISOR'));
        elseif($request->getParameter("marked") == 'Not Duplicate')
                $pairObj->updateProbableDuplicate($this->setActionParameters($request,'NO','OUT','SUPERVISOR'));
        elseif($request->getParameter("marked") == 'Cant Say')
                $pairObj->updateProbableDuplicate($this->setActionParameters($request,'CANTSAY','OUT','SUPERVISOR'));
        //Post-action handling//

	//Priority pair//
        $profileid = $request->getParameter("profileid1");
        $this->cid = $request->getParameter("cid");
	$this->pair = $pairObj->fetchCantsayDuplicate($request->getAttribute("name"),$profileid);
	if(!$this->pair->getProfileid2())
	{
		echo "Queue is Empty !!!";die;
	}
	//Priority pair//

	//Comments Section//
	$comment_arr = explode("#OPS#",$this->pair->getComments());
	for($i=0;$i<count($comment_arr);$i++)
	{
		$comment = explode("#",$comment_arr[$i]);
		$label = $comment[0];
		$val = $comment[1];
		$this->$label = $val;
	}
	//Comments Section//

	//Profile wise info//
	//$this->profile1 = new LoggedInProfile($db,$this->pair->getProfileid1());
	//$this->profile2 = new LoggedInProfile($db,$this->pair->getProfileid2());
        $this->profile1 = Operator::getInstance('newjs_master',$this->pair->getProfileid1());
        $this->profile2 = Operator::getInstance('newjs_master',$this->pair->getProfileid2());
	$this->profiles_info($pairObj);	
	//Profile wise info//

	//Profile wise photo(s)//
	$pictureServiceObj1 = new PictureService($this->profile1);
        $this->album1 = $pictureServiceObj1->getAlbumWithDeletedPhotos();
	$pictureServiceObj2 = new PictureService($this->profile2);
        $this->album2 = $pictureServiceObj2->getAlbumWithDeletedPhotos();
	//Profile wise photo(s)//
	
	$this->page = "Examine";
	$this->setTemplate('Screening');
  }

 /**
  * This function is used to display the efficiency reports.
  **/
  public function executeMis(sfWebRequest $request)
  {
	if($request->getParameter("Show") || $request->getParameter("outside"))
        {
		if($request->getParameter("outside")=='Y')
	        {
			$day = 1;
                	$month = date("m");
	                $year = date("Y");
                
        	        $day2 = date("d");
                	$month2 = date("m");
	                $year2 = date("Y");
		}
		else
		{
			$day = $request->getParameter("day");
	                $month = $request->getParameter("month");
        	        $year = $request->getParameter("year");
			
			$day2 = $request->getParameter("day2");
	                $month2 = $request->getParameter("month2");
        	        $year2 = $request->getParameter("year2");
		}

                $this->st_date=$year."-".$month."-".$day." 00:00:00";
                $this->end_date=$year2."-".$month2."-".$day2." 23:59:59";
                
                $this->date1 = $this->my_format_date($day,$month,$year);
                $this->date2 = $this->my_format_date($day2,$month2,$year2);
                
                $this->cid = $request->getParameter("cid");
                $this->flag = $request->getParameter("flag");
		$this->exec = $request->getParameter("name");
                $this->show = 1;

		$pairObj = new DuplicateProfileScreen;
		$this->report = $pairObj->fetchCountScreenedProfiles($this->st_date,$this->end_date,$this->flag,$this->exec);
        }
	else
	{
		for($i=0;$i<12;$i++)
			$mmarr[$i]=$i+1;
		for($i=0;$i<10;$i++)
			$yyarr[$i]=$i+2012;
		for($i=0;$i<31;$i++)
			$ddarr[$i]=$i+1;
		$this->ddarr = $ddarr;
		$this->mmarr = $mmarr;
		$this->yyarr = $yyarr;
		$this->month = date("m");
		$this->year = date("Y");
		$this->day = date("d");
		$this->cid = $request->getParameter("cid");
		$this->flag = $request->getParameter("flag");
		$this->exec = $request->getParameter("name");
	}
  }

  /**
  * This function is used to set the action parameters .
  **/
  public function setActionParameters($request,$isDuplicate,$screen_action,$marked_by)
  {
        $param_arr["profileid1"] = $request->getParameter("profileid1");
        $param_arr["profileid2"] = $request->getParameter("profileid2");
        $param_arr["isDuplicate"] = $isDuplicate;
        $param_arr["screen_action"] = $screen_action;
        $param_arr["reason"] = $request->getParameter("reason");
	$param_arr["identified_on"] = $request->getParameter("identified_on");
        $param_arr["comments"] = "comments#".$request->getParameter("comments")."#OPS#comments_bi#".$request->getParameter("comments_bi")."#OPS#comments_ti#".$request->getParameter("comments_ti")."#OPS#comments_mi#".$request->getParameter("comments_mi");
        $param_arr["screened_by"] = $request->getParameter("screened_by");
	$param_arr["marked_by"] = $marked_by;
        return $param_arr;
   }

 /**
  * This function is used to retrieve the profile info.
  **/
  public function profiles_info($pairObj)
  {
	$fetchParams =array('PHONE_RES','PHONE_MOB','EMAIL','MESSENGER_ID','ALT_MOBILE','ALT_MESSENGER_ID');
	$hindiMTongueArr =array("7","10","13","19","28","33");

        //Common object
        $rashiObj = new NEWJS_RASHI();
        //Profile:1
        $this->profile1->getDetail($this->pair->getProfileid1(),"PROFILEID","*");
        $r1 = $rashiObj->getRashi($this->profile1->getRASHI());
        $this->profile1->setRASHI($r1[LABEL]);
        //Profile:2
        $this->profile2->getDetail($this->pair->getProfileid2(),"PROFILEID","*");
        $r2 = $rashiObj->getRashi($this->profile2->getRASHI());
        $this->profile2->setRASHI($r2[LABEL]);

	if($this->profile1->getM_BROTHER()=='')
		$this->profile1->setM_BROTHER('0');
        if($this->profile2->getM_BROTHER()=='')
                $this->profile2->setM_BROTHER('0');
        if($this->profile1->getM_SISTER()=='')
                $this->profile1->setM_SISTER('0');
        if($this->profile2->getM_SISTER()=='')
                $this->profile2->setM_SISTER('0');

	$mTongue1 =$this->profile1->getMTONGUE();
	$mTongue2 =$this->profile2->getMTONGUE();
	if(in_array("$mTongue1",$hindiMTongueArr))
		$this->mTongueProfile1 =true;	
	if(in_array("$mTongue2",$hindiMTongueArr))
		$this->mTongueProfile2 =true;

	$this->archiveInfo1 =$pairObj->fetchArchiveInfo($this->pair->getProfileid1(),$fetchParams);
	$this->archiveInfo2 =$pairObj->fetchArchiveInfo($this->pair->getProfileid2(),$fetchParams);			

	// Phone Number String Match with all Phone Numbers
	$phoneNoArray1		=array("PHONE_RES"=>$this->profile1->getPhoneNumber(),"PHONE_MOB"=>$this->profile1->getPHONE_MOB(),"ALT_MOBILE"=>$this->profile1->getExtendedContacts()->ALT_MOBILE,"ALTERNATE_NUM"=>$this->archiveInfo1['ALTERNATE_NUM'],"CONTACT_PHONE_RES"=>$this->archiveInfo1['CONTACT']['PHONE_RES'],"CONTACT_PHONE_MOB"=>$this->archiveInfo1['CONTACT']['PHONE_MOB'],"CONTACT_ALT_MOBILE"=>$this->archiveInfo1['CONTACT']['ALT_MOBILE']);
        $phoneNoArray2  	=array("PHONE_RES"=>$this->profile2->getPhoneNumber(),"PHONE_MOB"=>$this->profile2->getPHONE_MOB(),"ALT_MOBILE"=>$this->profile2->getExtendedContacts()->ALT_MOBILE,"ALTERNATE_NUM"=>$this->archiveInfo2['ALTERNATE_NUM'],"CONTACT_PHONE_RES"=>$this->archiveInfo2['CONTACT']['PHONE_RES'],"CONTACT_PHONE_MOB"=>$this->archiveInfo2['CONTACT']['PHONE_MOB'],"CONTACT_ALT_MOBILE"=>$this->archiveInfo2['CONTACT']['ALT_MOBILE']);
	$this->phoneFlagArr1 	=$pairObj->stringCompValidation($phoneNoArray1,$phoneNoArray2);
	$this->phoneFlagArr2 	=$pairObj->stringCompValidation($phoneNoArray2,$phoneNoArray1);

	// Email String Match with All Emails, Messengers IDs
	$email1 		=@explode("@",$this->profile1->getEMAIL());
	$messenger1 		=@explode("@",$this->profile1->getMESSENGER_ID());
	$alt_messenger1		=@explode("@",$this->profile1->getExtendedContacts()->ALT_MESSENGER_ID);
        $email2                 =@explode("@",$this->profile2->getEMAIL());
        $messenger2             =@explode("@",$this->profile2->getMESSENGER_ID());
        $alt_messenger2         =@explode("@",$this->profile2->getExtendedContacts()->ALT_MESSENGER_ID);

	$emailArr1		=array("EMAIL"=>$email1[0],"MESSENGER_ID"=>$messenger1[0],"ALT_MESSENGER_ID"=>$alt_messenger1[0],"CONTACT_EMAIL"=>$this->archiveInfo1['CONTACT']['EMAIL'],"CONTACT_MESSENGER_ID"=>$this->archiveInfo1['CONTACT']['MESSENGER_ID'],"CONTACT_ALT_MESSENGER_ID"=>$this->archiveInfo1['CONTACT']['ALT_MESSENGER_ID']);
	$emailArr2		=array("EMAIL"=>$email2[0],"MESSENGER_ID"=>$messenger2[0],"ALT_MESSENGER_ID"=>$alt_messenger2[0],"CONTACT_EMAIL"=>$this->archiveInfo2['CONTACT']['EMAIL'],"CONTACT_MESSENGER_ID"=>$this->archiveInfo2['CONTACT']['MESSENGER_ID'],"CONTACT_ALT_MESSENGER_ID"=>$this->archiveInfo2['CONTACT']['ALT_MESSENGER_ID']);
	$this->emailFlagArr1    =$pairObj->stringCompValidation($emailArr1,$emailArr2);
	$this->emailFlagArr2    =$pairObj->stringCompValidation($emailArr2,$emailArr1);	

	// IP Address metch with all IPs 	
	$ipArr1			=array("IPADD"=>$this->profile1->getIPADD(),"PAYMENT_IP"=>$this->archiveInfo1['PAYMENT_IP'],"CONTACT_IP"=>$this->archiveInfo1['CONTACT_IP']);
	$ipArr2			=array("IPADD"=>$this->profile2->getIPADD(),"PAYMENT_IP"=>$this->archiveInfo2['PAYMENT_IP'],"CONTACT_IP"=>$this->archiveInfo2['CONTACT_IP']);
	$this->ipFlagArr1    =$pairObj->stringCompValidation($ipArr1,$ipArr2);
	$this->ipFlagArr2    =$pairObj->stringCompValidation($ipArr2,$ipArr1);	

	// Address String Match
	$addressArr1		=array("CONTACT"=>$this->profile1->getCONTACT(),"PARENTS_CONTACT"=>$this->profile1->getPARENTS_CONTACT());
	$addressArr2		=array("CONTACT"=>$this->profile2->getCONTACT(),"PARENTS_CONTACT"=>$this->profile2->getPARENTS_CONTACT());
	$this->addFlagArr1    	=$pairObj->stringCompValidation($addressArr1,$addressArr2);
	$this->addFlagArr2    	=$pairObj->stringCompValidation($addressArr2,$addressArr1);	

  }
	
  /**
  * This function is used to convert the date in desired format.
  **/
  function my_format_date($day,$month,$year)
  {
        if($month=="01" || $month=="1")
                $month="Jan";
        elseif($month=="02" || $month=="2")
                $month="Feb";
        elseif($month=="03" || $month=="3")
                $month="Mar";
        elseif($month=="04" || $month=="4")
                $month="Apr";
        elseif($month=="05" || $month=="5")
                $month="May";
        elseif($month=="06" || $month=="6")
                $month="Jun";
        elseif($month=="07" || $month=="7")
                $month="Jul";
        elseif($month=="08" || $month=="8")
                $month="Aug";
        elseif($month=="09" || $month=="9")
                $month="Sep";
        elseif($month=="10")
                $month="Oct";
        elseif($month=="11")
                $month="Nov";
        else
                $month="Dec";

        if(strlen($day)==1)
                $day= "0" . $day;

        return $month . " " . $day . ", " . $year;
  }
}
?>
