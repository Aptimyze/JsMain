<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#4f0770" style="font-family:Arial; max-width:600px; min-width:320px; -webkit-text-size-adjust: none;">
	<tr bgcolor="#ffffff">
        <td colspan="3" style="font-family:Arial; font-size:11px; color:#000000; padding:5px;">~$PREHEADER`</td>
        </tr>
  <tr>
    <td width="8"><img src="~$IMG_URL`/images/mailer/photoRequest/lftIMG1.gif" width="8" height="44" vspace="0" hspace="0" align="left" /></td>
    <td width="584" bgcolor="#ffffff" valign="bottom"><table border="0" cellpadding="0" cellspacing="0" align="left">
          <tr>
            <td width="219" valign="bottom"><img src="~$IMG_URL`/images/mailer/photoRequest/logo.gif" alt="Jeevansathi.com" align="left" border="0" height="44" vspace="0" width="219" hspace="0"></td>
            </tr>
            </table><table border="0" cellpadding="0" cellspacing="0" align="right">
          <tr>
            <td width="122" height="49" align="right" style="padding-right:12px;"><img src="~$IMG_URL`/images/mailer/photoRequest/callIC.gif" width="24" height="23" vspace="0" hspace="0" align="bottom" /><font style="font-size:12px;" color="#aa29ca">Call us at:</font><br />
<font style="font-size:10px;" color="#aa29ca"><var>{{TOLLNO}}</var> (Toll free)
<var>{{NOIDALANDL}}</var></font></td>
          </tr>
      </table></td>
    <td width="8"><img src="~$IMG_URL`/images/mailer/photoRequest/rhtIMG1.gif" width="8" height="44" vspace="0" hspace="0" align="left" /></td>
  </tr>
  <tr>
    <td colspan="3"><div style="width:100%"><img src="~$IMG_URL`/images/mailer/photoRequest/imgR1.gif" style="device-width: 320px; display:block; orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
  </tr>
  <tr>
    <td colspan="3" valign="bottom">
    	<table border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
		<td width="314" ~if $GENDER eq 'F'` background="~$IMG_URL`/images/mailer/photoRequest/framPIC.jpg" ~elseif $GENDER eq 'M'` background="~$IMG_URL`/images/mailer/photoRequest/framPIC_She.jpg" ~/if` align="center" height="225"><div style="padding-top:25px; padding-right:32px;"><img src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$PHOTO_REQUESTED_BY_PROFILEID`,photo_type=search(/PHOTO)" vspace="0" hspace="0" align="absmiddle" /></div></td>
          </tr>
          <tr>
            <td height="150" style="color:#ffffff; font-family:Tahoma; padding-left:35px;"><font style="font-size:32px;">SHOW ~if $GENDER eq 'F'`HIM~elseif $GENDER eq 'M'`HER~/if`</font> <br /><font style="font-size:26px;">HOW YOU LOOK LIKE...</font><br /><font face="Times New Roman" color="#efb8fb" style="font-size:19px;">&nbsp; &nbsp; &nbsp; &nbsp; <em>Add a photo to your profile...</em></font><br /></td>
          </tr>
        </table>
        <table width="213" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
		~if $GENDER eq 'F'`
            <td valign="bottom" style="padding-top:96px;"><img src="~$IMG_URL`/images/mailer/photoRequest/bodyGirl2.jpg" width="213" height="279" vspace="0" hspace="0" align="left" /></td>
		~elseif $GENDER eq 'M'`
	     <td valign="bottom" style="padding-top:52px;"><img src="~$IMG_URL`/images/mailer/photoRequest/bodyboy1.jpg" width="227" height="324" vspace="0" hspace="0" align="left" /></td>
                ~/if`
          </tr>
        </table>
	</td>
  </tr>
  <tr>
    <td></td>
    <td valign="top"><div style="width:100%"><img src="~$IMG_URL`/images/mailer/photoRequest/topRound.gif" style="device-width: 320px; display:block; orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#ffffff" style="padding:0px 10px;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#000000;">
          <tr>
            <td width="564" style="padding-left:11px;">Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,<br /><br />
<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> has liked your profile and sent you a <strong style="color:#8f08b0;">PHOTO REQUEST.</strong> Don't miss out on a great opportunity!</td>
          </tr>
          <tr>
            <td height="9"></td>
          </tr>
          <tr>
            <td bgcolor="#fbf3ff" style="padding:6px;">
		~$requested_tuple`
	    </td>
          </tr>
	~if $TOTAL_REQUEST`
          <tr>
            <td height="9"></td>
          </tr>
          <tr>
            <td width="564" style="padding-left:11px;"><strong><a href="(LINK)PHOTO_REQUEST_PAGE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#8f08b0;" target="_blank">~$TOTAL_REQUEST` more member~if $TOTAL_REQUEST neq 1`s~/if`</a></strong> ~if $TOTAL_REQUEST eq 1`has~else`have~/if` asked for your photo. <strong><a href="(LINK)PHOTO_REQUEST_PAGE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#8f08b0;" target="_blank">Click here</a></strong> to view them</td>
          </tr>
    	~/if`
        </table>

    </td>
    
    
    
    
    
    <td></td>
  </tr>
  
   <tr>
    <td></td>
   ~if $PHOTO_REQUEST_MAILER_CASE eq 1 || $PHOTO_REQUEST_MAILER_CASE eq 2` 
    <td bgcolor="#ffffff" style="padding:0px 0px;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
             <tr style="height:15px"><td>&nbsp;</td></tr>  
      <tr>
                <td style="line-height:18px; padding:10px; background:#fff4da; font:13px arial" height="80" ><div><var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,<br />
                  If you <a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#0f529d; font-family:Arial;" target="_blank">add your photo</a> ~if $PHOTO_REQUEST_MAILER_CASE eq 1`and also <a href="(LINK)VERIFY_PHONE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#0f529d; font-family:Arial;" target="_blank">verify your phone</a>~/if` before <font color="#c4161c"> <strong>~$FTO_END_DAY_SINGLE_DOUBLE_DIGIT`<sup>~$FTO_END_DAY_SUFFIX`</sup> ~$FTO_END_MONTH` ~$FTO_END_YEAR`</strong></font>, you will also get Jeevansathi's<strong><font color="#c4161c"> FREE TRIAL OFFER!</font></strong><br />
                  This Offer worth <strong>Rs.<var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-</strong> lets you<br />
                  see phone number, email id of profiles you like for FREE</div>
		<br />
          <div style="font:11px arial; float:right"><a href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)" style="color:#0F529D" target="_blank">Know more</a> about Free Trial Offer</div></td>
				
          </tr>
            </table>

    </td>
    ~elseif $PHOTO_REQUEST_MAILER_CASE eq 3`
            <td bgcolor="#ffffff" style="padding:0 20px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px; font-family:Arial; color:#000000;">
                  <tr>
                    <td height="18"></td>
                  </tr>
                  <tr>
                    <td valign="top" height="21">With our flexible <font color="#8f08b0"><strong>PRIVACY SETTINGS</strong></font> you can also choose to make your photo:</td>
                  </tr>
                  <tr>
                    <td style="font-size:13px; text-align:left" bgcolor="#fbf3ff" height="26"><img src="~$IMG_URL`/images/mailer/photoRequest/bull.gif" width="42" height="15" vspace="0" hspace="0" align="left" /> Visible to All</td>
                  </tr>
                  <tr>
                    <td height="8"></td>
                  </tr>
                  <tr>
                    <td style="font-size:13px; text-align:left" bgcolor="#fbf3ff" height="26"><img src="~$IMG_URL`/images/mailer/photoRequest/bull.gif" width="42" height="15" vspace="0" hspace="0" align="left" /> Visible to profile(s) you like<font color="#7e7e7e">*</font></td>
                  </tr>
                </table>
        </td>
    ~/if`
    
    <td></td>
  </tr>
  
  
   <tr>
    <td></td>
    <td bgcolor="#ffffff" style="padding:0px 10px;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#000000;">
          <tr>
            <td width="564" style="padding:0 10px;">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px; font-family:Arial; color:#000000;">
                  
                  <tr>
                    <td height="18"></td>
                  </tr>
                  <tr>
                    <td height="28">So add a photo &amp; give a face to your profile now.</td>
                  </tr>
                  <tr>
                    <td><table width="230" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:19px;">
                  <tr>
                    <td background="~$IMG_URL`/images/mailer/photoRequest/btnBG.gif" height="38" align="center" bgcolor="#fc7b00"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="text-decoration:none; color:#ffffff; display:block;" target="_blank"><strong>ADD YOUR PHOTO</strong></a></td>
                  </tr>
                </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp; <font style="font-size:12px;" color="#8f08b0"><em>And Increase profile responses by 12 times</em></font></td>
                  </tr>
                  <tr>
                    <td height="30"></td>
                  </tr>
                  <tr>
                    <td height="20" valign="top" style="font-size:15px; color:#8f08b0;" align="left"><strong>HOW TO UPLOAD</strong></td>
                  </tr>
                  <tr>
                    <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #f4e4fc; padding:0 0 5px 0;">
                      <tr>
                        <td><table width="152" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#000000; margin-left:5px;" align="left">
                                               <tr>
                                                <td height="5"></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#fbf3ff" align="center" width="172" height="48">Choose and upload<br /><strong>directly</strong> from computer</td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#fbf3ff" height="66" align="center"><img src="~$IMG_URL`/images/mailer/photoRequest/directIC.gif" width="73" height="55" vspace="0" hspace="0" align="absmiddle" /></td>
                                              </tr>
                                            </table>
                                            <table width="152" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#000000; margin-left:5px;" align="left">
                                               <tr>
                                                <td height="5"></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#fbf3ff" align="center" width="172" height="48"><strong>Email</strong> your photo to<br /><a href="<var>{{PHOTO_EMAILID}}</var>" target="_blank" style="color:#0f529d;"><var>{{PHOTO_EMAILID}}</var></a></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#fbf3ff" height="66" align="center"><img src="~$IMG_URL`/images/mailer/photoRequest/mailIC.gif" width="65" height="41" vspace="0" hspace="0" align="absmiddle" /></td>
                                              </tr>
                                            </table>
                                            <table width="152" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#000000; margin-left:5px;" align="left">
                                               <tr>
                                                <td height="5"></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#fbf3ff" align="center" width="172" height="48"><strong>Courier</strong> your photo to any <br />of our <a href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)" target="_blank" style="color:#0f529d;">60+ offices</a></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#fbf3ff" height="66" align="center" valign="bottom"><img src="~$IMG_URL`/images/mailer/photoRequest/courIC.gif" width="73" height="55" vspace="0" hspace="0" align="bottom" /></td>
                                              </tr>
                                            </table></td>
                      </tr>
                    </table>

                    	
                    </td>
                  </tr>
		~if $PHOTO_REQUEST_MAILER_CASE eq 3`
		<tr>
                    <td height="5"></td>
                  </tr>
                  <tr>
                    <td><table width="203" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:15px;">
                  <tr>
                    <td background="images2/btnBG.gif" height="38" align="center" bgcolor="#fc7b00"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="text-decoration:none; color:#ffffff; display:block;" target="_blank"><strong>ADD YOUR PHOTO NOW</strong></a></td>
                  </tr>
                </table></td>
                  </tr>
		~/if`
                  <tr>
                    <td height="45"></td>
                  </tr>
                  <tr>
                    <td><font style="font-size:12px;" color="#000000">Warm Regards,<br /><strong>Jeevansathi.com Team</strong></font><br /><img src="~$IMG_URL`/images/mailer/photoRequest/ventureTxt.gif" width="112" height="11" vspace="5" hspace="0" align="left" /></td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </table>

            </td>
          </tr>
        </table>

    </td>
    
    
    
    
    
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td valign="top"><div style="width:100%"><img src="~$IMG_URL`/images/mailer/photoRequest/botRound.gif" style="device-width: 320px; display:block; orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td height="29" align="center" valign="top"><font style="font-size:11px;" color="#c6c6c6">If you do not wish to receive any such photo request mailers, click here to <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#c6c6c6;" target="_blank">UNSUBSCRIBE</a>.</font></td>
    <td></td>
  </tr>
</table>

</body>
</html>
