<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Admin Search : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<script language="JavaScript">
<!--
function sure()
{
        return confirm("Are you sure to change the membership of PAID user?");
}

function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}
function loadForm()
{
        document.form1.submit();
}                                                                                 

-->
</script>
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="searchpage.php" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=PAGE value="~$PAGE`">
<input type="hidden" name=grp_no value="~$grp_no`">
<input type="hidden" name=CHECKDELETED value="~$CHECKDELETED`">
<table width=760 cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="35%" class="formhead" align="center"><a href="mainpage.php?name=&cid=~$cid`">Main Page</a></td>
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br><br>
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	<td width=20% class="formhead">Status Legends</td>
	<td width=2% class="red">I</td>
	<td width=10% >Incomplete</td>
	<td width=2% class="red">H</td>
	<td width=10% >Hidden</td>
	<td width=2% class="red">D</td>
	<td width=10% >Deleted</td>
	<td width=2% class="red">O</td>
        <td width=10% >Offline</td>

	<td width=40% class="fieldsnewgreen">Green Band means member is Paid</td>
	
</tr>
</table>
<br>
<table width="60%" border="0" cellspacing="1" cellpadding="4" align="center">
          <tr>
            <td class="formhead" valign="middle" colspan="2">&#155; Search</td>
          </tr>
          <tr>

          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Select</td>
            <td width="70%" bgcolor="#F9F9F9" >
		<select name="phrase" class="textboxes1" >
			<option value="U" ~if $phrase eq 'U'`selected~/if`>By Username</option>
			<option value="E" ~if $phrase eq 'E'`selected~/if`>By Email</option>
		</select>
            </td>
          </tr>
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Username/Email</td>

            <td width="70%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~$USER_NAME`" size="18" maxlength="40" class="textboxes1">
            </td>
          </tr>

          <tr valign="middle" align="center">
	    <td width=30% class="fieldsnew"></td>
              <td class="fieldsnew"><input type="submit" name="Go" value="  Search  " class="textboxes1"></td></tr>
	</table>
<br>	
~if $SEARCH eq "YES"`
~if $operator_regby neq ""`
      <tr>
        <td colspan="3" height="20" style="font-family:verdana; font-size:12px; color:#FF0000">&nbsp;<B>~if $ROW[0].source eq 'O'` Offline ~else` 101 ~/if` Profile</B> Registered By&nbsp;:&nbsp;<B>~$operator_regby`</B>
        ~/if`
        ~if $operator_assto neq ""`
        &nbsp;& Assigned To&nbsp;:&nbsp;<B>~$operator_assto`</B></td>
      </tr>
        ~/if`
<table width=100% align="CENTER" >
    <tr align="CENTER">
      <td class="formhead"><font size="2" color="blue">page ~$PAGE` of ~if $PAGEREF eq "zero"`1~else`~$NUM_PAGE`~/if`</font></td>
      <td class="formhead" colspan="8" height="23"><b><font size="4" color="blue">~if $CHECKDELETED eq "Y"` Deleted Profiles ~else`Search Results~/if` <font color="green">~$TOTAL` </font>&nbsp;(Incomplete: <font color="red">~$COUNT_INCOMPLETE`</font>)</font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=8% height="20"><b>S.No.</b></td>
      <td class="label" width=10% height="21"><b>User Name</b></td>
      <td class="label" width=11% height="21"><b>Last Modified on </b></td>
      <td class="label" width=8% height="21"><b>Edit</b></td>
      <td class="label" width=10% height="21"><b>Show Stats</b></td>
      <td class="label" width=10% height="21"><b>&nbsp;</b></td>
      <td class="label" width=12% height="21"><b>Mark for Email Verification</b></td>
      <td class="label" width=10% height="21"><b>Change Request</b></td>
      <td class="label" width=10% height="21"><b>Verify Email</b></td>
      <td class="label" width=10% height="21"><b>Email Manager</b></td>
      <td class="label" width=10% height="21"><b>Trends</b></td>
      <td class="label" width=10% height="21"><b>Generate PDF</b></td>
      </tr>
 ~section name=index loop=$ROW`
    <tr align="CENTER" bgcolor="#fbfbfb" class="~$ROW[index].bandcolor`">
      <td height="20" align="CENTER" width="8%">1. ~if $ROW[index].marked_del eq "1"` <font color="red">M</font>~/if` ~if $ROW[index].source eq "O"` <font color="red">O</font>~/if` ~if $ROW[index].incomplete eq "Y"` <font color="red">I</font>~/if`~if $ROW[index].activated eq "D"` <font color="red">D</font>~/if`~if $ROW[index].activated eq "H"` <font color="red">H</font>~/if`</td>
      <td height="21" width="15%" align="LEFT"><!-- ~if $CHECKDELETED eq "Y"`<a href="../profile/viewprofile_jsadmin.php?checksum=~$cid`&profilechecksum=~$ROW[index].Profilechecksum`&viewprofile=INTERNAL" target="_blank">~$ROW[index].Username`</a>~else`<a href="../profile/viewprofile.php?checksum=~$cid`&profilechecksum=~$ROW[index].Profilechecksum`&viewprofile=INTERNAL" target="_blank">~$ROW[index].Username`</a>~/if` -->~$ROW[index].Username` ~if $ROW[index].Old_Username`<br><span class="red">(Old Username : ~$ROW[index].Old_Username`)</span>~/if`</td>
      <td height="21" width="15%">~$ROW[index].Mod_dt`</td>
      <td height="21" width="8%" align="CENTER">
	<a href="edit_details.php?cid=~$cid`&pid=~$ROW[index].Profileid`&user=~$ROW[index].Username`&SHOW=~$SHOW`">Edit
	</a></td>
      <!--<td height="21" width="15%" align="CENTER"><a href="showstat.php?cid=~$cid`&profileid=~$ROW[index].Profileid`" target="_blank">Show statistics</a></td>-->
      <td height="21" width="15%" align="CENTER"><a href="../operations.php/commoninterface/ShowProfileStats?cid=~$cid`&profileid=~$ROW[index].Profileid`" target="_blank">Show statistics</a></td>	


<!--      <td height="21" width="15%"><a href="show_album_photos.php?cid=~$cid`&profileid=~$ROW[index].Profileid`" target="_blank">View All Photos</a></td>-->
      <td height="21" width="15%"><a href="../operations.php/photoScreening/getAlbum?cid=~$cid`&profileid=~$ROW[index].Profileid`" target="_blank">View All Photos</a></td>





      <td height="21" width="15%" align="CENTER">&nbsp;~if $SHOW eq 'Y'`<a href="add_profile_validate_email.php?cid=~$cid`&profileid=~$ROW[index].Profileid`">Mark</a>~/if`</td>
      <td>~if $SHOW eq "Y"`<input type=checkbox name="cb~$ROW[index].Profileid`" value="Y" >~else` &nbsp; ~/if`</td>
      <td><a href="editprofile_request.php?pid=~$ROW[index].Profileid`&cid=~$cid`&user=~$ROW[index].Username`">Change request</a></td>
	<td>~$ROW[index].Verify_Email`</td>
	<td>~if $SHOW eq 'Y'`<a href="~$SITE_URL`/P/unsubscribe.php?checksum=&crmback=admin&pid=~$ROW[index].Profileid`&cid=~$cid`">Email Manager</a>~/if`</td>
	<td>~if $SHOW eq 'Y'`<a href="~$SITE_URL`/mis/3d_trends.php?checksum=&crmback=admin&pid=~$ROW[index].Profileid`&cid=~$cid`&username=~$ROW[index].Username`">Trends</a>~/if`</td>
<td height="21" width="15%" align="CENTER"><a href="../profile/pdf?profilechecksum=~$ROW[index].Profilechecksum`&username=~$ROW[index].Username`" target="_blank">Generate PDF</a></td>
            </tr>
	~if $JSARCH_DATE`
	<tr bgcolor="#fbfbfb" class=fieldsnew>
                <td>&nbsp;</td>
                <td height="21" align="CENTER"><a href="#" onclick="MM_openBrWindow('showdeletion_detail.php?profileid=~$ROW[index].Profileid`','search','width=640,height=480,scrollbars=yes'); return false;"> ~$ROW[index].del_ret_by`</a></td>
                <td height="21" align="CENTER">Date: ~$JSARCH_DATE`</td><td>Deleted by system because of inactivity </td>
                </tr>
    ~elseif $ROW[index].negativeListcheck eq "1"`
        <tr bgcolor="#fbfbfb" class=fieldsnew>
                    <td>&nbsp;</td>
                    <td height="21" align="CENTER" colspan=3>Deleted due to Negative Treatment</td>
        </tr>
	~else`
	 ~if $ROW[index].del_scr eq "N"  and $ROW[index].activated eq "D"`
        <tr bgcolor="#fbfbfb" class=fieldsnew>
                <td>&nbsp;</td>
                <td height="21" align="CENTER" colspan=3>Deleted by Profile-owner</td>
	</tr>~/if`
        ~if $ROW[index].del_scr eq "N2"`
        <tr bgcolor="#fbfbfb" class=fieldsnew>
                <td>&nbsp;</td>
                <td height="21" align="CENTER" colspan=3>Unknown Reason</td>
       </tr>
        ~/if`
~if $ROW[index].del_scr eq "N1"`
	<tr bgcolor="#fbfbfb" class=fieldsnew>
                <td>&nbsp;</td>
                <td height="21" align="CENTER"><a href="#" onclick="MM_openBrWindow('showdeletion_detail.php?profileid=~$ROW[index].Profileid`','search','width=640,height=480,scrollbars=yes'); return false;"> ~$ROW[index].del_ret_by`</a></td>
                <td height="21" align="CENTER">Date: ~$ROW[index].timeofdel`</td><td>Now deleted by profile-owner</td>
		</tr>~/if`
	
	<tr bgcolor="#fbfbfb" class=fieldsnew>
	~if $ROW[index].del_scr eq "y"`
		<td>&nbsp;</td>
		<td height="21" align="CENTER"><a href="#" onclick="MM_openBrWindow('showdeletion_detail.php?profileid=~$ROW[index].Profileid`','search','width=640,height=480,scrollbars=yes'); return false;"> ~$ROW[index].del_ret_by`</a></td>
		<td height="21" align="CENTER">Date: ~$ROW[index].timeofdel`</td>
        ~/if`
	~/if`
<!--code modified to show userdetails link-->

		~if $USERDETAILSLINK eq 1`
        <td height="21" align="center" colspan="2"><a href="#" onclick="MM_openBrWindow('searchpage_userdetails.php?cid=~$cid`&username=~$USER_NAME`','udetails','width=640,height=480,scrollbars=yes'); return false;">Show User Details</td>
        ~else`
      <td colspan="6" height="21">&nbsp; </td>
	~/if`
	</tr> 

    ~/section`
   

  </table>
                                                                                                 
~else`
	
<table width=50% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100%>
          <td width=55% class="fieldsnew" align="center" border=1><font><b>~$message`</b></font></td>
    </tr>
</table>
~/if`
<!--~if $ADMIN eq 'Y'`
<table width=50% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
	<tr class="fieldsnew">
		<td colspan="7" height="21" align="CENTER">&nbsp;</td>
			<td height="21" align="CENTER">
				<a href="gender_dob_editing.php?cid=~$cid`&profileid=~$profileid`">Click to edit Gender / Date of Birth</a>
			</td>
		</td>
	</tr>
</table>
~/if`-->
</form> 

<table align="CENTER">
 ~section name=index loop=$LINKS`
      ~$LINKS[index].lnk`
    ~/section`
</table>
</body>
</html>
