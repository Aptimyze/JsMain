<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UserView : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="/jeevansathi/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="/jeevansathi/P/I/styles.php" type="text/css">
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="searchpage.php" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=PAGE value="~$PAGE`">
<input type="hidden" name=grp_no value="~$grp_no`">
<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="/jeevansathi/jsadmin/logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br><br>
<table width="50%" border="0" cellspacing="1" cellpadding="4" align="center">
          <tr>
            <td class="formhead" valign="middle" colspan="2">&#155; Search</td>
          </tr>

          <tr>
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
          </tr>
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
          <tr valign="middle" align="Right">
              <td colspan="2"><input type="submit" name="Go" value="  Search  " class="textboxes1"></td></tr>
        </table>

~if $SEARCH eq "YES"`

 <table width=100% align="CENTER" >
    <tr align="CENTER">
      <td class="formhead"><font size="2" color="blue">page ~$PAGE` of ~$NUM_PAGE`</font></td>
      <td class="formhead" colspan="5" height="23"><b><font size="4" color="blue">Search Results <font color="green">~$TOTAL` </font></font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=8% height="20"><b>S.No.</b></td>
      <td class="label" width=15% height="21"><b>User Name</b></td>
      <td class="label" width=15% height="21"><b>Email</b></td>
      <td class="label" width=15% height="21"><b>Last Modified on </b></td>
      <td class="label" width=15% height="21"><b>Check to Delete</b></td>
      </tr>
 ~section name=index loop=$ROW`
    <tr align="CENTER" bgcolor="#fbfbfb" class="~$ROW[index].bandcolor`">
      <td height="20" align="CENTER" width="8%">~$ROW[index].Sno`</td>
      <td height="21" width="15%" align="LEFT"><a href="/jeevansathi/profile/viewprofile.php?checksum=~$cid`&profilechecksum=~$ROW[index].Profilechecksum`" target="_blank">~$ROW[index].Username`</a></td>
      <td height="21" width="15%">~$ROW[index].Email`</td>
      <td height="21" width="15%">~$ROW[index].Mod_dt`</td>
      <td><input type=checkbox name="cb~$ROW[index].Profileid`" value="Y" ></td>
    </tr>
    ~/section`
    <tr bgcolor="#fbfbfb">
      <td colspan="5" height="21">&nbsp; </td>
   </tr>
    <tr>
      <td colspan="5" height="21" align="CENTER">
	<input type="submit" name="Delete" value="Delete">		
      </td>
   </tr>
  </table>
                                                                                                 
~else`
	
<table width=50% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>~$message`</b></font></td>
    </tr>
</table>
~/if`
</form> 

<table align="CENTER">
 ~section name=index loop=$LINKS`
      ~$LINKS[index].lnk`
    ~/section`
</table>
 <br><br><br><br><br><br>
~include file="foot.htm"`


</body>
</html>
