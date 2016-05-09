<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UserEdit : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<SCRIPT language="JavaScript">
<!--
function sure()
{
	return confirm("Are you sure to delete?");
}
-->
</script>
</head>
~include file="head.htm"`
<br>
<body bgcolor="#FFFFFF" topmargin="0" marginheight="0"   onload="populate_textarea()">

<table width="600" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<table width="600" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr class=label align=center>
        <td width=10%>&nbsp;Gender</td>
        <td width=10%>&nbsp;Age</td>
        <td width=10%>&nbsp;Country of Residence</td>
        <td width=10%>&nbsp;City of Residence</td>
        <td width=10%>&nbsp;Marital Status</td>
        <td width=10%>&nbsp;Ethinicty (State of Origin)</td>
        <td width=10%>&nbsp;Religion</td>
        <td width=10%>&nbsp;Caste</td>
        <td width=10%>&nbsp;Country of Birth</td>
        <td width=10%>&nbsp;City of Birth</td>
        <td width=10%>&nbsp;Posted By</td>
    </tr>
    <tr class=fieldsnew align=center>
        <td>&nbsp;~$SHOW_GENDER`</td>
        <td>&nbsp;~$SHOW_AGE`</td>
        <td>&nbsp;~$SHOW_COUNTRY[0]`</td>
        <td>&nbsp;~if $SHOW_CITYRES[0] neq ''` ~$SHOW_CITYRES[0]` ~else` Outside India ~/if`</td>
        <td>&nbsp;~$SHOW_MSTATUS`</td>
        <td>&nbsp;~$SHOW_MTONGUE[0]`</td>
        <td>&nbsp;~$SHOW_RELIGION[0]`</td>
        <td>&nbsp;~$SHOW_CASTE[0]`</td>
        <td>&nbsp;~$SHOW_COUNTRY_BIRTH[0]`</td>
        <td>&nbsp;~if $SHOW_CITY_BIRTH neq ''` ~$SHOW_CITY_BIRTH` ~else` Not Specified ~/if`</td>
        <td>&nbsp;~$POSTED_BY`</td>
    </tr>
</table>
<form action="useredit.php" method="post" name="fr1">
<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="343">&nbsp;</td>
  </tr>
  <tr>
    <td >Username : </td>
    <td >~$USERNAME`</td>
  </tr>
  <tr>
    <td width="157">&nbsp;</td>
    <td width="343">&nbsp;</td>
  </tr>
~if $SHOWSUBCASTE eq "Y"`
  <tr class="label">
    <td align="center">Subcaste</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
	<td><input type="text" name="SUBCASTE" value="~$SUBCASTEvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`

~if $SHOWNAKSHATRA eq "Y"`
  <tr class="label">
    <td align="center">Nakshatra</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="NAKSHATRA" value="~$NAKSHATRAvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWGOTHRA eq "Y"`
  <tr class="label">
    <td align="center">Gothra</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="GOTHRA" value="~$GOTHRAvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWPHONERES eq "Y"`
  <tr class="label">
    <td align="center">Phone Residence</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="PHONE_RES" value="~$PHONE_RESvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWPHONEMOB eq "Y"`
  <tr class="label">
    <td align="center">Phone Mobile</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="PHONE_MOB" value="~$PHONE_MOBvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWCITY eq "Y"`
  <tr class="label">
    <td align="center">City of Birth</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="CITY_BIRTH" value="~$CITY_BIRTHvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWEDUCATION eq "Y"`
  <tr class="label">
    <td align="center">Education</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="EDUCATION" value="~$EDUCATIONvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWMESSENGER eq "Y"`
  <tr class="label">
    <td align="center">Messenger ID</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="MESSENGER_ID" value="~$MESSENGER_IDvalue`" size="40" class="textboxes1"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
 ~if $SHOWANNULLED eq "Y"`
                                <tr class="label" style="color:red"><TD colspan=2><B>Why Marital Status is Annulled </b></td></tr>
                                <Tr class="fieldsnew"><Td>&nbsp;</td>
                                <td><textarea name="Annulled_Reason" rows="3" cols="40" class="textboxes1">~$Annulled_Reason`</textarea></td>
                                </tr>
~/if`
~if $SHOWCONTACT eq "Y"`
  <tr class="label">
    <td align="center">Contact</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>
	<textarea name="CONTACT" rows=3 cols=40>~$CONTACTvalue`</textarea>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWEMAIL eq "Y"`
  <tr class="label">
    <td align="center">Email</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>
	~if $BUREAU eq 'Y'` Profile Posted by Marriage Bureau
	~else`
	<input type="text" name="EMAIL" value="~$EMAILvalue`" size="40" class="textboxes1">
	~/if`
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~if $SHOWYOURINFO eq "Y"`
  <tr class="label">
    <td align="center">Your Info </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="YOURINFO" cols="40" rows="8" class="textboxes1">~$YOURINFOvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWFAMILYINFO eq "Y"`
  <tr class="label">
    <td align="center">Family Info </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="FAMILYINFO" cols="40" rows="8" class="textboxes1">~$FAMILYINFOvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWSPOUSE eq "Y"`
  <tr class="label">
    <td align="center">Spouse </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="SPOUSE" cols="40" rows="8" class="textboxes1">~$SPOUSEvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWJOBINFO eq "Y"`
  <tr class="label">
    <td align="center">Job Info </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="JOB_INFO" cols="40" rows="8" class="textboxes1">~$JOB_INFOvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWFATHERINFO eq "Y"`
  <tr class="label">
    <td align="center">Father Info </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="FATHER_INFO" cols="40" rows="8" class="textboxes1">~$FATHER_INFOvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWSIBLINGINFO eq "Y"`
  <tr class="label">
    <td align="center">Sibling Info </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="SIBLING_INFO" cols="40" rows="8" class="textboxes1">~$SIBLING_INFOvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
~if $SHOWPARENTSCONTACT eq "Y"`
  <tr class="label">
    <td align="center">Parents Contact </td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td>&nbsp;</td>
    <td><textarea name="PARENTS_CONTACT" cols="40" rows="8" class="textboxes1">~$PARENTS_CONTACTvalue`</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if` 
	<TR class="fieldsnew">
		<Td colspan=2>&nbsp;&nbsp;<input type="checkbox" name="INCOMPLETE" value="INCOMPLETE">
		&nbsp; &nbsp;&nbsp;Mark profile as incomplete<BR><BR></td>
	</tr>
 
	<tr class="fieldsnew">
	    <td align="center"><input type=submit value="Delete" name=Submit1 onclick="return sure()"></td>
	    <td align="center">
		<input type="hidden" name=pid value="~$pid`">
		<input type="hidden" name=screen value="~$screen`">
		<input type="hidden" name=name value="~$names`">
		<input type="hidden" name=cid value="~$cid`">
		<input type="hidden" name=val value="~$val`">
		<input type="hidden" name=user value="~$user`">
		<input onclick="return checkthis()" type=submit value="Save" name=Submit></td> 
	</tr>
</table>
<script>
					var original='';
					function populate_textarea()
					{
						
						if(document.fr1.YOURINFO)
							original=document.fr1.YOURINFO.value;
						
					}
					function checkthis()
					{
						return true;
						if(document.fr1.YOURINFO)
						{
							currentval=document.fr1.YOURINFO.value;
							if(currentval!=original && !document.fr1.INCOMPLETE.checked)
							{
								alert("Please mark profile incomplete, since you have \r\nchanged Your Info field value");
								return false;
							}
						}
						return true;
					}
					</script>
<br>
<br>
</form>
</body>
~include file="foot.htm"`
</html>
