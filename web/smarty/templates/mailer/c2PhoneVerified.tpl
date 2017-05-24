<html>
<head>

<title>Jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px; min-width:320px; border:1px solid #efefef; -webkit-text-size-adjust: none;" bgcolor="#ffffff">
  <tr>
    <td colspan="3" height="100" valign="top"><table border="0" cellpadding="0" cellspacing="0" align="left" style="margin-left:17px;">
        <tr>
          <td height="49" width="205"><img src="~$IMG_URL`/images/mailer/PhoneVerify/jsLogo.gif" alt="Jeevansathi.com" align="left" border="0" height="63" vspace="0" width="205" hspace="0"></td>
        </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" align="right" style="font-family:Arial; font-size:12px; color:#474646; margin-top:15px;">
        <tr>
          <td width="240" height="49"><img src="~$IMG_URL`/images/mailer/PhoneVerify/call_IC.gif" width="27" height="20" vspace="0" hspace="0" align="left" /><strong style="font-size:12px">Call us at:</strong> <var>{{TOLLNO:profileid=~$profileid`}}</var> (Toll free) &nbsp; &nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
  	<td></td>
    <td>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Tahoma; font-size:19px; color:#000000">
          <tr>
            <td rowspan="3" width="136"><img src="~$IMG_URL`/images/mailer/PhoneVerify/MobImage.jpg" width="136" height="131" vspace="0" hspace="0" align="left" /></td>
            <td background="~$IMG_URL`/images/mailer/PhoneVerify/dottedTopImg.gif" height="42" width="405"></td>
          </tr>
          <tr>
            <td height="76"><font style="font-size:23px; line-height:28px;">Phone successfully verified</font><br />Next Step - <font color="#b20403"><strong>UPLOAD PHOTO</strong></font> <img src="~$IMG_URL`/images/mailer/PhoneVerify/hurryTxt.gif" width="69" height="29" vspace="0" hspace="0" align="absmiddle" /></td>
          </tr>
          <tr>
            <td background="~$IMG_URL`/images/mailer/PhoneVerify/dottedBotImg.gif" height="13"><span style="font-size:1px;"> </span></td>
          </tr>
        </table>

	</td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="20"></td>
  </tr>
  <tr>
    <td width="34"></td>
    <td width="541"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#000000;">
        <tr>
          <td>
          	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
            	<tr>
                	<td colspan="3">Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var></td>
                </tr>
                <tr>
                	<td colspan="3" height="10"></td>
                </tr>
                <tr>
                <td style="line-height:18px;">
Your phone number ~$PHONE_NUMBER_VERIFIED` has been successfully verified.<br />
<strong>Now Upload your photo before  <var>{{FTO_END_DAY:profileid=~$profileid`}}</var><sup style="font-size:11px;"><var>{{FTO_END_DAY_SUFFIX:profileid=~$profileid`}}</var></sup> <var>{{FTO_END_MONTH:profileid=~$profileid`}}</var> <var>{{FTO_END_YEAR:profileid=~$profileid`}}</var> and get
paid membership worth Rs.<var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- for FREE.</strong><br />In this Free Trial Offer see Phone/Email of profiles you like*.</td>
				<td width="70" align="center"><table width="47" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Tahoma; font-size:10px; margin:10px 10px 0 0px;">
              <tr>
                <td bgcolor="#C00402" height="16" align="center" background="~$IMG_URL`/images/mailer/PhoneVerify/calTop.jpg" style="color:#ffffff"><strong><var>{{FTO_END_MONTH:profileid=~$profileid`}}</var></strong></td>
              </tr>
              <tr>
                <td height="24" align="center" background="~$IMG_URL`/images/mailer/PhoneVerify/dateBG.jpg" bgcolor="#E6E4E5" style="font-size:19px;"><strong><var>{{FTO_END_DAY:profileid=~$profileid`}}</var></strong></td>
              </tr>
              <tr>
                <td background="~$IMG_URL`/images/mailer/PhoneVerify/calBot.jpg" height="31" align="center" valign="top" style="color:#ffffff; line-height:15px;"><strong><var>{{FTO_END_YEAR:profileid=~$profileid`}}</var></strong></td>
              </tr>
            </table></td>
                <td width="59"></td>
                </tr>
            </table>
         </td>
        </tr>
        <tr>
          <td height="18"></td>
        </tr>
        <tr>
          <td>To get the Offer,</td>
        </tr>
        <tr>
          <td style="border-bottom:1px dashed #d4d3d3"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px; color:#000000; line-height:18px;" align="left">
              <tr>
                <td height="6"></td>
              </tr>
              <tr>
                <td><table width="187" border="0" cellspacing="0" cellpadding="0" align="left" style="margin-right:12px;">
                    <tr>
		~if $GENDER eq 'M'`
                      <td width="57"><img src="~$IMG_URL`/images/mailer/PhoneVerify/UPphOTOMale.gif" width="57" height="50" vspace="0" hspace="0" align="left" /></td>
		~elseif $GENDER eq 'F'`
                      <td width="57"><img src="~$IMG_URL`/images/mailer/PhoneVerify/UPphOTO.gif" width="57" height="50" vspace="0" hspace="0" align="left" /></td>
		~/if`
                      <td height="45" background="~$IMG_URL`/images/mailer/PhoneVerify/btnBG1.gif" bgcolor="#FFE817" valign="top" width="130"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" target="_blank" style="line-height:35px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#000000; text-decoration:none;">UPLOAD PHOTO NOW</a></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>Or you can mail your photos to <a href="mailto:<var>{{PHOTO_EMAILID}}</var>" target="_blank" style="color:#0f529d; text-decoration:underline;"><var>{{PHOTO_EMAILID}}</var></a></td>
              </tr>
              <tr>
                <td height="15"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td style="font-size:11px; color:#474646;" height="26" align="right"><span style="background:#f6f6f6; padding:5px 10px;"><a href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d;">Know more</a> about Free Trial Offer</span></td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
        <tr>
          <td valign="top">Photo security features on Jeevansathi.com:</td>
        </tr>
        <tr>
          <td>
          	<table width="151" border="0" cellspacing="0" align="left" cellpadding="0" bgcolor="#f6f6f6" style="margin-right:12px; margin-top:6px; font-family:Arial; font-size:12px; color:#000000;">
              <tr>
				  <td height="79" align="center">
					~if $GENDER eq 'M'`
						<img src="~$IMG_URL`/images/mailer/PhoneVerify/likeImgICFemale.gif" width="110" height="45" vspace="0" hspace="0" align="absmiddle" />
					~elseif $GENDER eq 'F'`
						<img src="~$IMG_URL`/images/mailer/PhoneVerify/likeImgICMale.gif" width="110" height="45" vspace="0" hspace="0" align="absmiddle" />
					~/if`
				</td>
              </tr>
              <tr>
                <td align="center" height="54" valign="top">Show your photo to<br />
people who you like</td>
              </tr>
            </table>
            <table width="151" border="0" cellspacing="0" align="left" cellpadding="0" bgcolor="#f6f6f6" style="margin-right:12px; font-family:Arial; font-size:12px; color:#000000; margin-top:6px;">
              <tr>
                <td height="79" align="center"><img src="~$IMG_URL`/images/mailer/PhoneVerify/downloadImg.jpg" width="134" height="67" vspace="0" hspace="0" align="absmiddle" /></td>
              </tr>
              <tr>
                <td align="center" height="54" valign="top">Photos on Jeevansathi
cannot be downloaded<br />using right click</td>
              </tr>
            </table>
            <table width="151" border="0" cellspacing="0" align="left" cellpadding="0" bgcolor="#f6f6f6" style="font-family:Arial; font-size:12px; color:#000000; margin-top:6px;">
              <tr>
                <td height="79" align="center">
					~if $GENDER eq 'M'`
						<img src="~$IMG_URL`/images/mailer/PhoneVerify/watermarkICMale.gif" width="62" height="79" vspace="0" hspace="0" align="absmiddle" />
					~elseif $GENDER eq 'F'`
						<img src="~$IMG_URL`/images/mailer/PhoneVerify/watermarkICFemale.gif" width="62" height="79" vspace="0" hspace="0" align="absmiddle" />
					~/if`
					</td>
              </tr>
              <tr>
                <td align="center" height="54" valign="top" style="padding:0 5px">Photos on Jeevansathi
are watermarked to
prevent tempering</td>
              </tr>
            </table>
		  </td>
        </tr>
        <tr>
            <td height="20"></td>
          </tr>
          <tr>
            <td height="45" style="font-size:13px;" valign="top"><font style="font-size:16px;">HURRY!</font><br />Upload your photo NOW and you can contact profiles likes these for FREE.</td>
          </tr>
          <tr>
            ~$suggested_profiles`
          <tr>
            <td height="35" align="right" style="font-size:11px; color:#474646;"><a href="(LINK)SUGGESTED_MATCHES:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#0f529d;">Click here</a> to see more suggested matches</td>
          </tr>
        <tr>
          <td height="30"></td>
        </tr>
      </table></td>
    <td width="25"></td>
  </tr>
  <tr>
    <td></td>
    <td><table style="font-family:Arial" border="0" cellpadding="0" cellspacing="0" width="100%">
       <tr>
          <td><table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#000000; margin-left:10px; -webkit-text-size-adjust: none;" align="left" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="font-size:12px" valign="top">Warm Regards,<br>
                    <b style="color:#c4161c;">Jeevansathi<span style="font-size:1px;"> </span><font color="#00000">.com Team</font></b><br />
                    <a href="(LINK)JS_FB_PAGE(/LINK)" target="_blank"><img src="~$IMG_URL`/images/mailer/PhoneVerify/fbBTN.gif" width="111" height="29" border="0" alt="Join Us on Facebook" vspace="4"></a></td>
                  <td style="font-size:12px" valign="top" width="10">&nbsp;</td>
                </tr>
              </tbody>
            </table>
            <table align="right" style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#000000; text-align:left;" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top"><img src="~$IMG_URL`/images/mailer/PhoneVerify/icon1.gif" align="absmiddle" height="24" width="24"></td>
                <td style="font-family:Arial;font-size:12px;" width="226" align="left"><span style="font-size:13px"><b>Call us at:</b></span><var>{{TOLLNO:profileid=~$profileid`}}</var> (Toll free), or</span></td>
              </tr>
              <tr>
                <td valign="top"><img src="~$IMG_URL`/images/mailer/PhoneVerify/visitIC.gif" align="absmiddle" height="33" width="32"></td>
                <td style="font-family:Arial;font-size:12px;-webkit-text-size-adjust: none;" align="left">Visit your <strong>nearest Jeevansathi centre:</strong><br />
                ~$jeevansathi_contact_address`
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="22"></td>
  </tr>
  <tr>
    <td></td>
    <td height="25" valign="top" align="right" style="font-family:Arial; font-size:10px; color:#605f5f;">*For Offer details see Terms &amp; Conditions on the website.</td>
    <td></td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" align="center" style="max-width:600px; min-width:320px;-webkit-text-size-adjust: none;" bgcolor="#ffffff">
  <tr>
    <td height="30" align="center"><font face="Arial" style="font-size:11px;" color="#a3a3a3">You are receiving this mail as a registered member of Jeevansathi.com. To unsubscribe, <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d;">click here</a>.</font></td>
  </tr>
</table>
</body>
</html>
