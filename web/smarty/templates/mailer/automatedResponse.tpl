<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>jeevansathi.com</title>
</head>

<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #e3e3e3;">
  <tr>
    <td background="~$IMG_URL`/images/mailer/screening_edit/top_bg_rept.gif"><a href="~$SITE_URL`" target="_blank"><img src="~$IMG_URL`/images/mailer/screening_edit/logo_an1.gif" width="304" height="99" hspace="0" vspace="0" border="0" align="left" /></a></td>
    <td background="~$IMG_URL`/images/mailer/screening_edit/top_bg_rept.gif"><img src="~$IMG_URL`/images/mailer/screening_edit/top_img_right.gif" width="296" height="99" hspace="0" vspace="0" align="left" /></td>
  </tr>
  <tr>
    <td height="18" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="top" style="padding:0px 15px 30px 23px;"><div style="font-family:Verdana, Arial; font-size:12px; color:#000000; line-height:17px;"><b>Dear <var>{{NAME_PROFILE:profileid=~$profileid`}},</b><br /><br />
      The changes made by you in your profile are live now.</div></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:0px 15px 22px 23px;"><table width="552" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="29" align="left" valign="middle" background="~$IMG_URL`/images/mailer/screening_edit/orange_top_bg.gif" bgcolor="#FEF3D5" style="padding:0px 0px 0px 15px;"><div style="font-family:Verdana, Arial; font-size:12px; color:#3b3a3a;"><b>Profile Status</b></div></td>
      </tr>
      <tr>
        <td style="border:solid 1px #fce6ad; border-top:none; padding:18px 10px 18px 15px;" background="~$IMG_URL`/images/mailer/screening_edit/box_bg_rept.jpg">
		<table width="517" border="0" cellspacing="0" cellpadding="0">
          		<tr>
		            <td align="left" valign="top"><div style="font-family:Verdana, Arial; font-size:11px; color:#3a3a3a;"><b>Your Profile is ~$PROFILE_PERCENT`% complete</b></div></td>
		        </tr>
          	<tr>
			<td>
<div style="border: 1px solid #87B43F!important; float: left; width: 300px!important; margin-top:10px;"><table width="~$PROFILE_PERCENT`%"><tbody><tr>
<td background="http://ser4.jeevansathi.com/img_revamp/mjspgbar.gif" style="background-repeat:x 0; background-position:0 1px; height: 13px;">  </td>
</tr></tbody></table></div>
			</td>
</tr>
          	<tr>
            			<td height="22"></td>
		        </tr>
	~if $PROFILE_PERCENT neq 100`
          <tr>
            <td><div style="font-family:Verdana, Arial; font-size:11px; line-height:17px; color:#3a3a3a;"><b>Prospective matches want to know more about you. Follow these simple steps to make your profile 100% complete :</b></div></td>
          </tr>
	~/if`
          <tr>
            <td height="10"></td>
          </tr>
          ~foreach from=$arrMsgDetails key=szKey item=szVal`
			<tr>
            <td><div style="font-family:Verdana, Arial; font-size:11px;"> &nbsp; &nbsp; <img src="~$IMG_URL`/images/mailer/screening_edit/bullet.gif" width="4" height="8" align="absmiddle" /> &nbsp; 
				<a href="(LINK)~$arrLinkDetails[$szKey]`:profileid=~$profileid`(/LINK)" class="blink">~$szVal`</a><br>
				</div></td></tr>
	<tr><td height="10"></td></tr>			
		~/foreach`          
        </table></td>
      </tr>
    </table></td>
  </tr>
  ~if $PROFILE_PERCENT neq 100`
  <tr>
    <td colspan="2" align="left" valign="top" style="padding:0px 0px 32px 22px;"><table width="281" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="281" height="52" align="center" valign="middle" background="~$IMG_URL`/images/mailer/screening_edit/button_bg.gif" bgcolor="#84AD05"><div style="font-family:Arial, Helvetica; font-size:18px; color:#ffffff;"><b><a href="(LINK)OWN_PROFILE:profileid=~$profileid`(/LINK)" style="color:#ffffff; text-decoration:none; display:block;">Complete your profile now</a></b></div></td>
      </tr>
    </table></td>
  </tr>
  ~/if`
  <tr>
    <td colspan="2" align="left" valign="top" style="padding:0px 0px 5px 30px;"><div style="font-family:Verdana, Arial; font-size:12px; color:#565656;"><b>In case of a query :</b></div></td>
  </tr>
  <tr>
    <td height="80" colspan="2" align="left" valign="top" background="~$IMG_URL`/images/mailer/screening_edit/footer_bg.gif" style="padding:8px 0px 0px 37px;"><div style="font-family:Verdana, Arial; font-size:11px; color:#3d3d3d; line-height:21px;">Please reach us at : B-8, Sector - 132, Noida - 201301<br />
      Phone no : <var>{{TOLLNO}}</var> (Toll Free) or <var>{{NOIDALANDL}}</var><br />
      E-mail id : <a href="mailto:feedback@jeevansathi.com" target="_blank" style="color:#0478e5; text-decoration:underline;"><strong>feedback@jeevansathi.com</strong></a></div></td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="top" style="padding:22px 0px 22px 28px;"><div style="font-family:Verdana, Arial; font-size:12px; color:#000000; line-height:22px;">Wish you good luck in your search for Jeevansathi.<br />
            <br />
            <b>Regards,<br />
            <font color="#c4151c">Jeevansathi</font>.com Team</b> </div></td>
  </tr>
</table>
<table width="600" border="0" align="center" cellpadding="0" 
cellspacing="0">

  <tr>

    <td style="padding:15px 0px 0px 0px; font-family:Verdana, Arial; 
font-size:10px; color:999999;"><div align="center" 
style="font-size:10px; color:999999; font-family:Verdana, Arial;">You
are receiving this mail as a registered member of Jeevansathi.com. In
case you do not wish to receive mails from Jeevansathi.com in the
future, you can <a 
href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)">unsubscribe</a></div></td>

  </tr>

</table>

</body>
</html>
