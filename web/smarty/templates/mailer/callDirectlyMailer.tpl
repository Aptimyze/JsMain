<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Call Directly Mailer</title>
</head>

<body>
<font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
<table width="600" border="0" cellspacing="0">
<tr>
<td background="~$IMG_URL`/images/mailer/callDirectly/mailer_bg.gif">
<table width="600" border="0" cellspacing="0">
  <tr>
    <td height="27" colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><img src="~$IMG_URL`/images/mailer/callDirectly/logo.gif" /></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="17">&nbsp;</td>
    <td width="550"><table width="558" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3">Dear <b><var>{{USERNAME:profileid=~$profileid`}}</var>,</td>
  </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="3">A Jeevansathi member <b><var>{{USERNAME:profileid=~$viewerprofileid`}}</var></b> has shown interest in you by viewing your contact details. The particulars of user <b><var>{{USERNAME:profileid=~$viewerprofileid`}}</var></b> are enclosed below</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="5" background="~$IMG_URL`/images/mailer/callDirectly/table_hdr_bg.gif"><img src="~$IMG_URL`/images/mailer/callDirectly/left_cur.gif" /></td>
    <td width="550" background="~$IMG_URL`/images/mailer/callDirectly/table_hdr_bg.gif"><font color="#FF0000"><b><var>{{USERNAME:profileid=~$viewerprofileid`}}</var></b></font></td>
    <td width="5"><img src="~$IMG_URL`/images/mailer/callDirectly/right_cur.gif" /></td>
  </tr>
  <tr><td colspan="3">
  <font face="Arial, Helvetica, sans-serif">
  <table width="557" bgcolor="#FFFFFF"  style="border:1px solid #f6c751">
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td width="16">&nbsp;</td>
    <td><a href="(LINK)PHOTO_ALBUM:receiver_id=~$viewerprofileid`,profileid=~$profileid`(/LINK)" target="_blank"><img src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$viewerprofileid`,photo_type=thumbnail(/PHOTO)" hspace="0" vspace="0" border="0" align="left" /></a></td>
    <td width="432" colspan="2" valign="top"><b>~$SMALL_DETAILS`</b></td>
    <td width="18">&nbsp;</td>
  </tr>
  <tr>
    <td width="16">&nbsp;</td>
    <td width="72">&nbsp;</td>
    <td width="432" valign="top">Gotra: <var>{{GOTHRA:profileid=~$viewerprofileid`}}</var></td>
    <td width="215" valign="top">Nakshatra: <var>{{NAKSHATRA:profileid=~$viewerprofileid`}}</var></td>
    <td width="18">&nbsp;</td>
  </tr>
  <tr>
    <td width="16">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td width="18">&nbsp;</td>
  </tr>
  <tr>
    <td width="16">&nbsp;</td>
    <td colspan="3"><b>About <var>{{USERNAME:profileid=~$viewerprofileid`}}</var></b><br />
    <var>{{YOURINFO:profileid=~$viewerprofileid`}}</var>
<br /><br />
<b>Family</b><br />
<var>{{FAMILYINFO:profileid=~$viewerprofileid`}}</var>
<br /><br />
</td>
    <td width="18">&nbsp;</td>
  </tr>
  <tr>
    <td width="16">&nbsp;</td>
    <td colspan="3"><table width="530" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="94" valign="middle"><font color="#7c7c7c"><b>Family Values</b></font></td>
    <td width="131" valign="middle">: <var>{{FAMILY_VALUES:profileid=~$viewerprofileid`}}</var></td>
    <td width="58" valign="middle">&nbsp;</td>
    <td width="123" valign="middle"><font color="#7c7c7c"><b>Mother</b></font></td>
    <td width="104" valign="middle">: <var>{{MOTHER_OCCUPATION:profileid=~$viewerprofileid`}}</var></td>
  </tr>
  <tr>
    <td valign="middle"><font color="#7c7c7c"><b>Family Type</b></font></td>
    <td valign="middle">: <var>{{FAMILY_TYPE:profileid=~$viewerprofileid`}}</var></td>
    <td valign="middle">&nbsp;</td>
    <td valign="middle"><font color="#7c7c7c"><b>Brother(s)</b></font></td>
    
   <td valign="middle">:  ~if $tBrother`~$tBrother` brother~if $tBrother neq 1`s~/if` ~if $mBrother` <span class="gray">of which married</span> ~$mBrother`~/if`~else`0 brothers~/if`</td>
   
  </tr>
  <tr>
    <td valign="middle"><font color="#7c7c7c"><b>Family Status</b></font></td>
    <td valign="middle">: <var>{{FAMILY_STATUS:profileid=~$viewerprofileid`}}</var></td>
    <td valign="middle">&nbsp;</td>
    <td valign="middle"><font color="#7c7c7c"><b>Sister(s)</b></font></td>
    <td valign="middle">: ~if $tSister`~$tSister` sister~if $tSister neq 1`s~/if` ~if $mSister` <span class="gray">of which married</span> ~$mSister`~/if`~else`0 sisters~/if`</td>
  </tr>
  <tr>
    <td valign="middle"><font color="#7c7c7c"><b>Father</b></font></td>
    <td valign="middle">: <var>{{FATHER:profileid=~$viewerprofileid`}}</var></td>
    <td valign="middle">&nbsp;</td>
    <td valign="middle"><font color="#7c7c7c"><b>Living with parents</b></font></td>
    <td valign="middle">:<var>{{LIVING_WITH_PARENTS:profileid=~$viewerprofileid`}}</var></td>
  </tr>
</table>
</td>
    <td width="18">&nbsp;</td>
  </tr>
  <tr>
    <td width="16">&nbsp;</td>
    <td colspan="3"><br />
<b>Education</b><br />
<var>{{EDUCATION_DETAIL:profileid=~$viewerprofileid`}}</var>
<br /><br />
<b>Occupation</b><br />

<var>{{JOB_INFO:profileid=~$viewerprofileid`}}</var>

<br /><br />
</td>
    <td width="18">&nbsp;</td>
  </tr>
</table>
</font>

  </td></tr>
  <tr><td colspan="3" align="center"><br />
<a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$viewerprofileid`(/LINK)"target="_blank"><img src="~$IMG_URL`/images/mailer/callDirectly/view_contact_det_btn.gif" border="0"></a>
  </td>
  </tr>
  <tr><td colspan="3"><br />
  <i><b><font color="#4e4e4e" size="3">Wish you good luck in search for a Jeevansathi</font></b></i><br /><br />
  Regards,<br />
  <b><font color="#c5161d">Jeevansathi</font>.com</b> Team
  </td>
  </tr>
  <tr><td colspan="3" align="center"><br />
<a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)"  target="_blank" style="text-decoration:underline; color:#0f529d;">Click here</a> to unsubscribe</td>
</tr>
<tr></tr>
</table>
</td>
</tr>
  
</table>
</td>
</tr>
</table>
</font>
</body>
</html>
