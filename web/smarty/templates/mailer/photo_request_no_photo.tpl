<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#afe0e1" style="font-family:Arial; max-width:600px; min-width:320px; -webkit-text-size-adjust: none; border:1px solid #e4e0e0;">
	<tr bgcolor="#ffffff">
        <td colspan="3" style="font-family:Arial; font-size:11px; color:#000000; padding:5px;">~$PREHEADER`</td>
        </tr>
  <tr bgcolor="#ffffff">
    <td colspan="2" height="49"><img src="~$IMG_URL`/images/mailer/header_footer/jsLogo2.gif" width="205" height="49" vspace="0" hspace="0" alt="Jeevansathi.com" align="left" border="0" style = "margin-left:10px;" /><img src="~$IMG_URL`/images/mailer/header_footer/ventureTxt.gif" width="158" height="21" vspace="13" hspace="0" align="right" /></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3"><div style="width:100%"><img src="~$IMG_URL`/images/mailer/photoRequest/topGradient.jpg" vspace="0" hspace="0" align="left" style="device-width: 320px; display:block; orientation:portrait; width:inherit;" /></div></td>
  </tr>
  <tr>
    <td width="20"></td>
    <td width="545" style="padding-left:13px; font-family:Tahoma; font-size:26px; color:#012501;"><font face="Georgia" style="font-size:20px;" color="#012501"><em>Don't give ~if $GENDER eq 'F'`him~elseif $GENDER eq 'M'`her~/if` a first blank impression...</em></font><br /><strong>ADD A PHOTO</strong> <font face="Verdana" style="font-size:19px;">TO YOUR</font> <strong>PROFILE NOW</strong></td>
    <td width="20"></td>
  </tr>
  <tr>
    <td colspan="3" height="30"></td>
  </tr>
  <tr>
    <td></td>
    <td style="padding-left:13px;">
    	<table width="80%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px;">
          <tr>
            <td width="433">Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,<br /><br />

<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> has liked your profile and sent you a <strong>PHOTO REQUEST!</strong><br /> 

Your photo is your first impression to ~if $GENDER eq 'F'`him~elseif $GENDER eq 'M'`her~/if` and you should not give ~if $GENDER eq 'F'`him~elseif $GENDER eq 'M'`her~/if` an incomplete look in the beginning. </td>
          </tr>
        </table>
	</td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="18"></td>
  </tr>  
  <tr>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td valign="top"><img src="~$IMG_URL`/images/mailer/photoRequest/img2.gif" width="11" height="64" vspace="0" hspace="0" align="right" /></td>
    <td valign="top">
    	<table width="297" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td colspan="2"><img src="~$IMG_URL`/images/mailer/photoRequest/topGreen.gif" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td background="~$IMG_URL`/images/mailer/photoRequest/detailBG.gif" bgcolor="#3fb3be" width="247" valign="top">&nbsp; &nbsp; <font face="Arial" color="#FFFFFF" style="font-size:13px;"><strong>Details of Profile: <var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var></strong></font></td>
            <td background="~$IMG_URL`/images/mailer/photoRequest/detailBG.gif" bgcolor="#3fb3be" width="50"><img src="~$IMG_URL`/images/mailer/photoRequest/img3.gif" width="50" height="25" vspace="0" hspace="0" align="right" /></td>
          </tr>
          <tr>
            <td colspan="2"><img src="~$IMG_URL`/images/mailer/photoRequest/detailBGshad.gif" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td colspan="2" style="border:4px solid #dff5f7; border-top:none; padding-left:18px;" bgcolor="#ffffff">
		~$profile_data`
            </td>
          </tr>
        </table>
        <table width="194" border="0" cellspacing="0" cellpadding="0" align="right">
          <tr>
            <td height="21" valign="bottom" align="right"><font face="Georgia" color="#085234" style="font-size:16px;"><em>So wait no more</em></font>&nbsp;</td>
          </tr>
          <tr>
            <td><img ~if $GENDER eq 'F'`src="~$IMG_URL`/images/mailer/photoRequest/upPIC.jpg"~elseif $GENDER eq 'M'`src="~$IMG_URL`/images/mailer/photoRequest/upPIC-male.jpg"~/if` width="194" height="123" align="left" /></td>
          </tr>
          <tr>
            <td height="18"></td>
          </tr>
          <tr>
            <td align="right">
            	<table width="177" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial">
                  <tr>
                    <td background="~$IMG_URL`/images/mailer/photoRequest/btnBG1.gif" height="37" align="center" bgcolor="#ffe774"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="font-size:14px; text-decoration:none; color:#000000; display:block;" target="_blank"><strong>UPLOAD PHOTO NOW!</strong></a></td>
                  </tr>
                </table>
			</td>
          </tr>
        </table>
	</td>
    <td></td>
  </tr>
	~if $TOTAL_REQUEST`
	<tr>
            <td></td>
            <td height="9"></td>
            <td></td>
          </tr>
  <tr>
    <td></td>
    <td style="padding-left:11px;"><strong><a href="(LINK)PHOTO_REQUEST_PAGE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#000000;font-family:Arial; font-size:14px;" target="_blank">~$TOTAL_REQUEST` more member~if $TOTAL_REQUEST neq 1`s~/if`</a></strong> ~if $TOTAL_REQUEST eq 1`has~else`have~/if` asked for your photo. <strong><a href="(LINK)PHOTO_REQUEST_PAGE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#000000;font-family:Arial; font-size:14px;" target="_blank">Click here</a></strong> to view them</td>
    <td></td>
  </tr>
	~/if`
  <tr>
    <td colspan="3" height="20">
	~if $PHOTO_REQUEST_MAILER_CASE eq 1 || $PHOTO_REQUEST_MAILER_CASE eq 2`
    	<table width="97%" style="padding:0px 10px; background:#d7f8e4; font:13px arial; margin:10px 10px" cellpadding="10">
        	<tr>
            	<td><div><var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,<br />
                  If you <a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#0f529d; font-family:Arial;" target="_blank">add your photo</a> ~if $PHOTO_REQUEST_MAILER_CASE eq 1`and also <a href="(LINK)VERIFY_PHONE:profileid=~$profileid`(/LINK)" style="text-decoration:underline; color:#0f529d; font-family:Arial;" target="_blank">verify your phone</a>~/if` before <font color="#c4161c"> <strong>~$FTO_END_DAY_SINGLE_DOUBLE_DIGIT`<sup>~$FTO_END_DAY_SUFFIX`</sup> ~$FTO_END_MONTH` ~$FTO_END_YEAR`</strong></font>, you will also get Jeevansathi's<strong><font color="#c4161c"> FREE TRIAL OFFER!</font></strong><br />
                  This Offer worth <strong>Rs.<var>{{FTO_WORTH:profileid=~$profileid`}}</var>/-</strong> lets you<br />
                  see phone number, email id of profiles you like for FREE</div>
                  <div style="font:11px arial; float:right"><a href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)" style="color:#0F529D" target="_blank">Know more</a> about Free Trial Offer</div>
                </td></tr>
            
        </table>
	~/if`
    </td>
  </tr>
  <tr>
    <td></td>
    <td height="20" valign="top" style="font-size:15px; color:#000000;" align="left"><strong>HOW TO UPLOAD</strong></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                      <tr>
                        <td><table width="160" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#000000; border:4px solid #dff5f7;" align="left">
                                              <tr>
                                                <td bgcolor="#ffffff" align="center" width="172" height="48">Choose and upload<br /><strong>directly</strong> from computer</td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#ffffff" height="66" align="center"><img src="~$IMG_URL`/images/mailer/photoRequest/directIC1.gif" width="73" height="55" vspace="0" hspace="0" align="absmiddle" /></td>
                                              </tr>
                                            </table>
                                            <table width="160" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#000000; border:4px solid #dff5f7; border-left:none;" align="left">
                                              <tr>
                                                <td bgcolor="#ffffff" align="center" width="172" height="48"><strong>Email</strong> your photo to<br /><a href="<var>{{PHOTO_EMAILID}}</var>" target="_blank" style="color:#0f529d;"><var>{{PHOTO_EMAILID}}</var></a></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#ffffff" height="66" align="center"><img src="~$IMG_URL`/images/mailer/photoRequest/mailIC1.gif" width="65" height="41" vspace="0" hspace="0" align="absmiddle" /></td>
                                              </tr>
                                            </table>
                                            <table width="160" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#000000; border:4px solid #dff5f7; border-left:none;" align="left">
                                               <tr>
                                                <td bgcolor="#ffffff" align="center" width="172" height="48"><strong>Courier</strong> your photo to any <br />of our <a href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)" target="_blank" style="color:#0f529d;">60+ offices</a></td>
                                              </tr>
                                              <tr>
                                                <td bgcolor="#ffffff" height="66" align="center" valign="bottom"><img src="~$IMG_URL`/images/mailer/photoRequest/courIC1.gif" width="73" height="55" vspace="0" hspace="0" align="bottom" /></td>
                                              </tr>
                                            </table></td>
                      </tr>
                    </table></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="6"></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="191" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial">
                  <tr>
                    <td background="~$IMG_URL`/images/mailer/photoRequest/btnBG1.gif" height="37" align="center" bgcolor="#ffe774"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="font-size:14px; text-decoration:none; color:#000000; display:block;" target="_blank">UPLOAD YOUR  PHOTO</a></td>
                  </tr>
                </table></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="25"></td>
  </tr>
  <tr>
    <td></td>
    <td>
    	<table width="141" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td style="font-size:12px; line-height:18px;">Warm Regards,<br /><strong>Jeevansathi<span style="font-size:1px;"> </span>.com Team</strong></td>
          </tr>
        </table><table width="289" border="0" cellspacing="0" cellpadding="0" align="right">
                  <tr>
                    <td style="font-size:12px; color:#474646;"><img src="~$IMG_URL`/images/mailer/photoRequest/phIC.gif" width="25" height="22" vspace="0" hspace="0" align="absmiddle" /><strong>Call us at:</strong> <font style="font-size:11px;"><var>{{TOLLNO}}</var> (Toll free), <var>{{NOIDALANDL}}</var></font></td>
                  </tr>
                </table>

     </td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="25"></td>
  </tr>
  <tr>
    <td></td>
    <td height="32" align="center" style="font-size:11px; color:#37a1a3;">If you do not wish to receive any such photo request mailers, click here to <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#37a1a3;">UNSUBSCRIBE</a>.</td>
    <td></td>
  </tr>
</table>

</body>
</html>
