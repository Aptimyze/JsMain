<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px; min-width:320px; border:1px solid #efefef; -webkit-text-size-adjust: none;" bgcolor="#ffffff">
  <tr>
    ~$HEADER`
  </tr>
  <tr>
  	<td></td>
    <td valign="bottom">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Times New Roman; font-size:22px; color:#000000;">
          <tr>
            <td height="87"></td>
            <td width="244" rowspan="2"><img src="~$IMG_URL`/images/mailer/clockImage.jpg" width="244" height="167" vspace="0" hspace="0" align="right" /></td>
          </tr>
          <tr>
            <td valign="top">You are <font style="font-size:31px; color:#c4161c; line-height:20px;"><em>missing out</em></font> on a<br /><font face="Verdana" style="font-size:31px;"><strong>FREE Trial Offer!</strong></font></td>
          </tr>
        </table>
	</td>
    <td></td>
  </tr>
  <tr>
  	<td></td>
    <td colspan="2">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:16px; color:#000000;">
          <tr>
            <td height="73" width="391"><font style="font-size:19px; line-height:22px;">Upload Photo and Verify Phone today</font><br /> 
to get paid membership worth Rs.<var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- absolutely FREE!</td>
            <td width="150"><img  src= "~if $GENDER eq 'M'`~$IMG_URL`/images/mailer/snapsMale.jpg~else`~$IMG_URL`/images/mailer/snaps.jpg~/if`" width="150" height="73" vspace="0" hspace="0" align="right" /></td>
          </tr>
        </table>
	</td>
  </tr>
  <tr>
    <td colspan="3" height="20"></td>
  </tr>
  <tr>
    <td width="34"></td>
    <td width="541"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#000000; line-height:22px;">
        <tr>
          <td width="541"><img src="~$IMG_URL`/images/mailer/hurryIC.jpg" width="20" height="23" vspace="0" hspace="0" align="absmiddle" />Today is the <font style="font-size:16px;" color="#c4161c" face="Times New Roman"><em>last day</em></font> to upload your photo &amp; verify your phone in order to get this Free Trial Offer. In this Offer, you can see <strong>phone/email of members</strong> you like* for <strong>Free!</strong>
</td>
        </tr>
        <tr>
          <td height="30"></td>
        </tr>
        <tr>
          <td>To get the Offer,</td>
        </tr>
        <tr>
          <td style="border-bottom:1px dashed #d4d3d3"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#000000; line-height:18px;" align="left">
              <tr>
                <td height="6"></td>
              </tr>
              <tr>
                <td><table width="162" border="0" cellspacing="0" cellpadding="0" align="left" style="margin-right:12px;">
                    <tr>
                      <td width="57"><img src= "~if $GENDER eq 'M'` ~$IMG_URL`/images/mailer/UPphOTOMale.gif~else`~$IMG_URL`/images/mailer/UPphOTO.gif~/if`"  width="57" height="50" vspace="0" hspace="0" align="left" /></td>
                      <td height="45" background="~$IMG_URL`/images/mailer/btnBG2.gif" bgcolor="#FFE817" valign="top" width="105"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" target="_blank" style="line-height:35px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#000000; text-decoration:none;">UPLOAD PHOTO</a></td>
                    </tr>
                  </table>
                  <table width="252" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:12px; color:#000000;">
                    <tr>
                      <td>You can upload directly from your computer,<br />
                        or mail us at <a href="mailto:<var>{{PHOTO_EMAILID}}</var>" style="color:#0f529d; text-decoration:underline;"><var>{{PHOTO_EMAILID}}</var></a></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><table width="154" border="0" cellspacing="0" cellpadding="0" align="left" style="margin:0 12px 0 9px;">
                    <tr>
                      <td width="49"><img src="~$IMG_URL`/images/mailer/phoBTN2.gif" width="49" height="50" vspace="0" hspace="0" align="left" /></td>
                      <td height="50" background="~$IMG_URL`/images/mailer/btnBG2.gif" bgcolor="#FFE817" valign="top"><a href="(LINK)VERIFY_PHONE:profileid=~$profileid`(/LINK)" target="_blank" style="line-height:35px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#000000; text-decoration:none;">VERIFY PHONE</a></td>
                    </tr>
                  </table>
                  <table width="252" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:12px; color:#000000;">
                    <tr>
                      <td>~if $INVALID_PHONE eq 'Y'`Phone number on your profile is <var>{{CONTACT_NUMBER:profileid=~$profileid`}}</var>.
					To change your number <a href="(LINK)CHANGE_NUMBER:profileid=~$profileid`(/LINK)" style="color:#0f529d; text-decoration:underline;">click here</a>~else`You will receive a call from Jeevansathi<span style="font-size:1px;"> </span>.com. 
                        Press '1' to verify.~/if`</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="10"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="right" style="font-size:11px; color:#474646;" height="26"><a href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d;">Know more</a> about Free Trial Offer</td>
        </tr>
        <tr>
          <td height="15"></td>
        </tr>
        <tr>
          <td valign="top">Adding a photo also:</td>
        </tr>
        <tr>
          <td>
          	<table width="30%" border="0" cellspacing="0" align="left" cellpadding="0" bgcolor="#f6f6f6" style="margin-right:8px; margin-top:6px; font-family:Arial; font-size:12px; color:#000000;">
              <tr>
                <td height="68" align="center" colspan="2"><img src= "~if $GENDER eq 'M'` ~$IMG_URL`/images/mailer/boyImg.jpg~else`~$IMG_URL`/images/mailer/girlImg.jpg~/if`" width="104" height="55" vspace="0" hspace="0" align="absmiddle" /></td>
              </tr>
              <tr>
                <td align="center" width="40" valign="top"><font style="font-size:34px;" color="#aaa9a9"><strong>1.</strong></font></td>
                <td height="42" valign="top" width="121" style="line-height:18px;">Gives a face to<br />your profile</td>
              </tr>
            </table>
            <table width="30%" border="0" cellspacing="0" align="left" cellpadding="0" bgcolor="#f6f6f6" style="margin-right:8px; font-family:Arial; font-size:12px; color:#000000; margin-top:6px;">
              <tr>
                <td height="68" align="center" colspan="2"><img src="~$IMG_URL`/images/mailer/msgImg.jpg" width="104" height="55" vspace="0" hspace="0" align="absmiddle" /></td>
              </tr>
              <tr>
                <td align="right" width="40" valign="top"><font style="font-size:34px;" color="#aaa9a9"><strong>2.</strong></font></td>
                <td height="42" width="121" valign="top" style="line-height:18px;">Gets you 8 times
more responses</td>
              </tr>
            </table>
            <table width="30%" border="0" cellspacing="0" align="left" cellpadding="0" bgcolor="#f6f6f6" style="font-family:Arial; font-size:12px; color:#000000; margin-top:6px;">
              <tr>
                <td height="68" align="center" colspan="2"><img src="~$IMG_URL`/images/mailer/downloadImg.jpg" width="104" height="55" vspace="0" hspace="0" align="absmiddle" /></td>
              </tr>
              <tr>
                <td align="right" width="40" valign="top"><font style="font-size:34px;" color="#aaa9a9"><strong>3.</strong></font></td>
                <td height="42" width="121" valign="top" style="line-height:18px;">Is safe and<br />
secure</td>
              </tr>
            </table>

          </td>
        </tr>
        <tr>
          <td height="30"></td>
        </tr>
      </table></td>
    <td width="25"></td>
  </tr>
  <tr>
    <td></td>
    ~$FOOTER`
  </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" align="center" style="max-width:600px; min-width:320px;-webkit-text-size-adjust: none;" bgcolor="#ffffff">
  <tr>
    <td height="30" align="center"><font face="Arial" style="font-size:11px;" color="#a3a3a3">You are receiving this mail as a registered member of Jeevansathi.com. To unsubscribe, <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d;">click here</a>.</font></td>
  </tr>
</table>
</body>
</html>
