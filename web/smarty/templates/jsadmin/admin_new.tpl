<html>
<head>
<SCRIPT language="JavaScript">
<!--
function sure()
{
        return confirm("Are you sure to delete?");
}
-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>AdminNew : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=30% class="formhead" border=1><font><b>Welcome : ~$name`</b></font></td>
          <td width="25%" class="formhead" align="center"><a href="mainpage.php?cid=~$cid`">Click here to go to main page</a></td>
          <td width="10%" class="formhead" align="center">
          	<a href="admin_edit.php?cid=~$cid`&name=~$name`">Alternate Edit</a></td>
          <td width="10%" class="formhead" align="center">
		<a href="alternate.php?cid=~$cid`&name=~$name`&val=edit">Edited Profiles</a></td>
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table> 
<br><br>
 <form action="admin_new.php" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=name value="~$name`">
  <table width=100% align="CENTER" >
    <tr align="CENTER">
      <td class="formhead" colspan="6" height="23"><b><font size="4" color="blue"> New Profiles </font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=8% height="20"><b>S.No.</b></td>
      <td class="label" width="11%" height="20"><b>Check to Assign</b></td>
      <td class="label" width=13% height="21"><b>Profile ID</b></td>
      <td class="label" width=26% height="21"><b>User Name</b></td>
      <td class="label" width=15% height="21"><b>Receive Time(IST)</b></td>
      <td class="label" width=15% height="21"><b>Submit Time(IST)</b></td>      
      <td class="label" width=15% height="21"><b>Remaining Time</b></td>      
      </tr>
  </table>
    ~section name=index loop=$ROW`
  <table width=100% align="CENTER" >
    <tr align="CENTER" bgcolor="#fbfbfb" class="~$ROW[index].bandcolor`">
      <td height="20" align="CENTER" width="8%">~$ROW[index].sno`</td>
      <td height="20" width="11%">
	<input type=checkbox name="cb~$ROW[index].profileid`">
	</td>
      <td height="21" width="13%">~$ROW[index].profileid`</td>
      <td height="21" width="26%" align="LEFT">~$ROW[index].username`</td>
      <td height="21" width="15%">~$ROW[index].receive_time`</td>
      <td height="21" width="15%" >~$ROW[index].submit_time`</td>
      <td height="21" width="15%" class="~$ROW[index].status_color`">~$ROW[index].remaining_time`</td>
    </tr>
  </table>
    ~/section`
  <table width=100% align="CENTER" >
    <tr bgcolor="#fbfbfb">
      <td colspan="6" height="21">&nbsp; </td>
   </tr>
    <tr>
      <td colspan="6" height="21">&nbsp; </td>
   </tr>
    <tr >
      <td width="8%" height="21">&nbsp; </td>
      <td height="21" width="11%" align="center">
	<select name="allotto" size=1>
	~section name=Index loop=$user`
	<option value="~$user[Index]`">~$user[Index]`</option>
	~/section`
	</select>
	</td>
      <td colspan="4" height="21"><input type=submit value="Assign" name=Submit> 
      &nbsp;&nbsp;&nbsp;<input type=submit value="Delete" name=Submit1  onclick="return sure()"> </td>
   </tr>
  </table>

 </form>
 

 <br><br><br><br><br><br>
~include file="foot.htm"`

</body>
</html>
