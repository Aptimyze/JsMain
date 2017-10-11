<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>AdminRetrieve : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<script language="JavaScript" SRC="../profile/profile.js"></SCRIPT>
<script language="JavaScript">
                                                                                
function validate()
{       
if(document.form1.del_scr.value=='y')
{    
	var aman= confirm('Do You still want to retrieve?');
	 if(aman)
	{
		return true;
	}
	else
	{
	 	return false;	
	}
}
}
function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}

</script>
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="15%" class="formhead" align="center"><a href="mainpage.php?user=&cid=~$cid`">Main Page</a></td>
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br><br>
~if $retflag eq 'Y'`
<form action="retrievepage.php" method="post">
<table width="60%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
<td width="30%" class="label" bgcolor="#F9F9F9">Reason</td>
<td width="70%" bgcolor="#F9F9F9">
<input type='text' name='reason' value='' size="42" maxlength="42"  class="textboxes1">
<tr class="fieldsnew">
<td width="30%" class="label" bgcolor="#F9F9F9">comments</td>
            <td width="70%" bgcolor="#F9F9F9">

        <textarea name="comments" class="testbox" cols="40" rows="2"></textarea>
            </td>
          </tr>
<input type='hidden' name='retflag' value='Y'>
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=Profileid value="~$Profileid`">
<tr valign="middle" align="Right">
              <td colspan="1"><input type="submit" name="Retrieve" value="Retrieve" class="textboxes1"></td></tr>
        </table>
                                                                                                                             
</form>




~else`


 
<table width="60%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	<td width=20% class="formhead">Status Legends</td>
<!--	<td width=2% class="red">I</td>
	<td width=10% >Incomplete</td>
	<td width=2% class="red">H</td>
	<td width=10% >Hidden</td>
	<td width=2% class="red">D</td>
	<td width=10% >Deleted</td>
-->
	<td width=40% class="fieldsnewgreen">Green Band means member is Paid</td>
</tr>
</table>	
<br>
~if $NOENTRY`
<p align="center"><font color="red">Please enter either username or email!</font></p>
<br>
~/if`
<form action="retrievepage.php" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=ret_scr value="~$ret_scr`">
<table width="60%" border="0" cellspacing="1" cellpadding="4" align="center">
          <tr>
            <td class="formhead" valign="middle" colspan="2">&#155; Search</td>
          </tr>
       <!--   <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">From</td>
            <td width="70%" bgcolor="#F9F9F9">
              <input type="text" name="year1" value="~$YEAR1`" size="4" maxlength="4"  class="textboxes1">
              <input type="text" name="month1" value="~$MONTH1`" size="2" maxlength="2" class="textboxes1">
              <input type="text" name="day1" value="~$DAY1`" size="2" maxlength="2"  class="textboxes1">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		<font color="black">to </font>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
              <input type="text" name="year2" value="~$YEAR2`" size="4" maxlength="4"  class="textboxes1">
              <input type="text" name="month2" value="~$MONTH2`" size="2" maxlength="2" class="textboxes1">
              <input type="text" name="day2" value="~$DAY2`" size="2" maxlength="2" class="textboxes1">
            </td>
		
          </tr>-->
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Username</td>
            <td width="70%" bgcolor="#F9F9F9" >
              <input type="text" name="username" value="~$USER_NAME`" size="18" maxlength="40" class="textboxes1">
            </td>
          </tr>
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Email</td>

            <td width="70%" bgcolor="#F9F9F9">
              <input type="text" name="email" value="~$E_MAIL`" size="18" maxlength="40" class="textboxes1">
            </td>
          </tr>
<!--	  <tr>
	    <td width=30% class="label" bgcolor="#F9F9F9">Paid Members Only</td>
            <td width="70%" bgcolor="#F9F9F9">
              <input type="checkbox" name="checkpaid" value="Y" size="18" maxlength="40" ~if $checkpaid eq 'Y'` selected ~/if`>
            </td>
	  </tr>
	  <tr>
	    <td width=30% class="label" bgcolor="#F9F9F9">Deleted Members only</td>
            <td width="70%" bgcolor="#F9F9F9">
              <input type="checkbox" name="checkdeleted" value="Y" size="18" maxlength="40" ~if $checkdeleted eq 'Y'` selected ~/if`>
            </td>
	  </tr>
-->
          <tr valign="middle" align="center">
	    <td width=30% class="fieldsnew"></td>
              <td class="fieldsnew"><input type="submit" name="CMDSearch" value="  Search  " class="textboxes1"></td></tr>
	</table>
</form>
<br>

~if $SHOWSEARCH eq "Y"`
<form action="retrievepage.php" method="post" name="form1" >
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=del_scr value="~$del_scr`">
 <table width=100% align="CENTER" >
    <tr align="CENTER">
      <td class="formhead" colspan="5" height="23"><b><font size="4" color="blue">Deleted Profiles<font color="green">~$TOTAL` </font></font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=8% height="20"><b>S.No.</b></td>
      <td class="label" width=15% height="21"><b>User Name</b></td>
      <td class="label" width=15% height="21"><b>Email</b></td>
      <td class="label" width=15% height="21"><b>Deleted On </b></td>
    <!--  <td class="label" width=15% height="21"><b>Check to Retrieve</b></td>-->
      </tr>
 ~section name=index loop=$ROW`
    <tr align="CENTER" bgcolor="#fbfbfb" class="~$ROW[index].bandcolor`">
      <td height="20" align="CENTER" width="8%">~$ROW[index].Sno`</td>
      <td height="21" width="15%" align="LEFT">
	<!--<a href="showstat.php?cid=~$cid`&profileid=~$ROW[index].Profileid`" target="_blank">~$ROW[index].Username`</a>-->
	<a href="../operations.php/commoninterface/ShowProfileStats?cid=~$cid`&profileid=~$ROW[index].Profileid`" target="_blank">~$ROW[index].Username`</a>
      </td>
      <td height="21" width="15%">~$ROW[index].Email`</td>
      <td height="21" width="15%">~$ROW[index].Del_dt`</td>
<!--      <td><input type=checkbox name="cb~$ROW[index].Profileid`" value="Y" ></td>-->
    </tr>
~if $ROW[index].prof_arch eq '1'`
	<tr bgcolor="#fbfbfb" class=fieldsnew>
                        <td>&nbsp;</td>
                        <td height="21" align="CENTER" colspan=3>Deleted by system because of inactivity</td>
                </tr>
~else`
	~if $ROW[index].del_scr eq "N"`
        	<tr bgcolor="#fbfbfb" class=fieldsnew>
                	<td>&nbsp;</td>
	                <td height="21" align="CENTER" colspan=3>Deleted by Profile-owner</td>
        	</tr>~/if`
                                                                                
        ~if $ROW[index].del_scr eq "y"`
        <tr bgcolor="#fbfbfb" class=fieldsnew>
		 <td>&nbsp;</td>
                <td height="21" align="CENTER"><a href="#" onclick="MM_openBrWindow('showdeletion_detail.php?profileid=~$ROW[index].Profileid`','retrieve','width=640,height=480,scrollbars=yes'); return false;"> Deleted by ~$ROW[index].deletedby`</a></td>
<td height="21" align="CENTER" colspan=1>Date: ~$ROW[index].Del_dt`</td>
<!--<td height="21" align="CENTER" colspan=1>Reason: ~$ROW[index].reason`</td>
                <td height="21" align="LEFT" colspan=2>Comments: ~$ROW[index].comments`</td>-->
        </tr>
        ~/if`
~/if`

<input type=hidden name="Profileid" value="~$ROW[index].Profileid`">

    ~/section`
    <tr bgcolor="#fbfbfb">
      <td colspan="5" height="21">&nbsp; </td>
   </tr>
    <tr>
      <td colspan="5" height="21" align="CENTER">
	<input type="submit" name="Retrieve" value="Retrieve">		
      </td>
   </tr>
  </table>
</form> 
~/if`
~/if`                                                                                                 
 <br><br><br><br>
~include file="foot.htm"`


</body>
</html>
