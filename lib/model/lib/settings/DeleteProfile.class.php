<?php

/**
 * Delete Profile Prashant Pal
 * 2 Dec 2015
 */
class DeleteProfile
{
	public $msg = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Congratulations from Jeevansathi</title>
</head>

<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #CCCCCC;">
  <tr>
    <td height="15" colspan="2" align="left"></td>
  </tr>
  <tr>

    <td align="left"><img src="http://www.ieplads.com/bmsjs/banners/js_product_aug22/images/con_img1.gif" alt="" width="291" height="63" align="left" /></td>
    <td align="left"><a href="http://www.jeevansathi.com/" target="_blank"><img src="http://www.ieplads.com/bmsjs/banners/js_product_aug22/images/con_img2_logo.gif" alt="" width="309" height="63" border="0" align="left" /></a></td>
  </tr>
  <tr>
    <td width="291" align="left"><img src="http://www.ieplads.com/bmsjs/banners/js_product_aug22/images/con_img3.jpg" alt="" width="291" height="301" align="left" /></td>
    <td width="309" align="left"><img src="http://www.ieplads.com/bmsjs/banners/js_product_aug22/images/con_img4.jpg" border="0" alt="" width="309" height="301" align="left" /></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><img src="http://www.ieplads.com/bmsjs/banners/js_product_aug22/images/con_img5.gif" alt="" width="600" height="102" align="left" /></td>

  </tr>
  <tr>
    <td colspan="2" align="center" valign="top">
	<table width="582" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; color:#000000; line-height:20px; margin-bottom:10px;">
      <tr>
        <td align="left" valign="top" style="padding:10px 14px 10px 21px;"><div align="justify"><b>Dear User,</b><br />
          <br />
Hearty congratulations from the JS team on your successful partner hunt. Nothing gladdens us more than a happy customer. We would like to partake in this joyous occassion of your life. Kindly upload your success story by going to <a href="~$SITE_URL`/success/success_stories.php" target="_blank" style="color:#0476DF; text-decoration:none;"><b>~$SITE_URL`/success/success_stories.php</b></a> and clicking on option "Send us your Success Story".Whats more? We are not going to just stop with words. You will receive a surprise gift from our team within 4-6 weeks after uploading your success story.

<br /><br /><b>Warm Regards</b><br />
The JS Team</div></td>
      </tr>
    </table>   </td>
  </tr>
</table>

</body>
</html>';


	public function delete_profile($profileid,$delete_reason='',$specify_reason='',$username)
	{
		$jprofileObj = new JPROFILE;
		$markDelObj = new JSADMIN_MARK_DELETE;
		$ProfileDelReasonObj = new NEWJS_PROFILE_DEL_REASON;
		$successStoryObj = new NEWJS_SUCCESS_STORIES;
		$AP_ProfileInfo = new ASSISTED_PRODUCT_AP_PROFILE_INFO;
		$AP_MissedServiceLog = new ASSISTED_PRODUCT_AP_MISSED_SERVICE_LOG;
		$AP_CallHistory = new ASSISTED_PRODUCT_AP_CALL_HISTORY;
		$ssMailerObj = new MAILER_SS_MAILER;
		//$newDeletedProfileObj = new NEWJS_NEW_DELETED_PROFILE_LOG;
		$profileInfo = $jprofileObj->SelectDeleteData($profileid);
		$email = $profileInfo["EMAIL"];
		if(!$delete_reason)
        		$delete_reason="I found my match on Jeevansathi.com";
		$ProfileDelReasonObj->Replace($username,$delete_reason,$specify_reason,$profileid);

		$jprofileObj->updateDeleteData($profileid);
		if($delete_reason=="I found my match on Jeevansathi.com")
		{
			$successSToryData = $successStoryObj->getId($username);
			if($successSToryData==0)
		{
			$from='customerservice@jeevansathi.com';
			$sub='Congratulations from Jeevansathi !';
			if($email)
			{
				SendMail::send_email($this->email,$msg,$sub,$from);
				$ssMailerObj->insertSent($username);
				}
		}
		}
		$markDelObj->Update($profileid);
		$AP_ProfileInfo->Delete($profileid);
		$AP_MissedServiceLog->Update($profileid);
		$AP_CallHistory->UpdateDeleteProfile($profileid);
		//$newDeletedProfileObj->Insert($profileid);
		$producerObj=new Producer();
		if($producerObj->getRabbitMQServerConnected())
		{
			$sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$profileid)), 'redeliveryCount'=>0 );
			$producerObj->sendMessage($sendMailData);
			$sendMailData = array('process' =>'USER_DELETE','data' => ($profileid), 'redeliveryCount'=>0 );
			$producerObj->sendMessage($sendMailData);
		}
		else
		{
			$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
            $cmd = JsConstants::$php5path." -q ".$path;
            passthru($cmd);
		}
	}

	public function callDeleteCronBasedOnId($profileid,$background='Y')
	{
		if($profileid=='EXPORT')
			$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing EXPORT";
		elseif($profileid=='DELTA')
			$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing DELTA";
		else
			$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing PROFILEID ".$profileid;
		//$command.= " >> /var/www/htmlrevamp/ser6/branches/milestoneConfig/cache/l.txt";
		if($background=='Y')
			$command.=" &";
		//echo  $command;echo "\n\n";
		exec($command);
		
	}
}
