<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UserView : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=30% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width=25% class="formhead" border=1 align='CENTER'><a href="mainpage.php?cid=~$cid`">Click here to go to main page</a></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<br>
<!--form action="normal_operator_bid.php" method=post>
<table width=80% align=center>
<tr>
<td align=center>Profile type
<select name=val>
	<option value="new">New</option>
	<option value="edit">Edit</option>
</select>
</td>
<td align=center>How many : 
<input type="text" name="num">
</td>
<td align=center>
<input type="submit" name="CMDBid" value="  Bid  ">
</td>
</tr>
</table>
<input type="hidden" name="cid" value="~$cid`">
</form-->
<br>
 <form action="admin_new.php" method="post" >
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=user value="~$user`">
  <table width=100% align="CENTER" >
    <tr align="CENTER">
      <td class="formhead" colspan="7" height="23"><b><font size="4" color="blue">New Profiles </font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=8% height="20"><b>S.No.</b></td>
      <td class="label" width=13% height="21"><b>Profile ID</b></td>
      <td class="label" width=26% height="21"><b>User Name</b></td>
      <td class="label" width=15% height="21"><b>Alloted Time(IST)</b></td>
      <td class="label" width=15% height="21"><b>Submit Time(IST)</b></td>      
      <td class="label" width=15% height="21"><b>View </b></td>	
      <td class="label" width=15% height="21"><b>Remaining Time </b></td>	
      </tr>
    ~section name=index loop=$ROW`
    <tr align="CENTER" bgcolor="#fbfbfb" class="~$ROW[index].bandcolor`">
      <td height="20" align="CENTER" width="8%">~$ROW[index].sno`</td>
      <td height="21" width="13%">~$ROW[index].profileid`</td>
      <td height="21" width="26%" align="LEFT">~$ROW[index].username`</td>
      <td height="21" width="15%">~$ROW[index].receive_time`</td>
      <td height="21" width="15%">~$ROW[index].submit_time`</td>
      <td width=15% height="21"><b> <a href="useredit.php?pid=~$ROW[index].profileid`&cid=~$cid`&user=~$user`&val=new">view</a></td> </b></td>	
	<td height="20" width="13%" class="~$ROW[index].status_color`" >~$ROW[index].remaining_time`</td>

    </tr>
    ~/section`
    <tr bgcolor="#fbfbfb">
      <td colspan="7" height="21">&nbsp; </td>
   </tr>
    <tr>
      <td colspan="7" height="21">&nbsp; </td>
   </tr>
    <tr >
      <td width="8%" height="21">&nbsp; </td>
      <td height="21" width="11%" align="center">
  </table>

  <table width=100% align="CENTER" >
    <tr align="CENTER">
      <td class="formhead" colspan="7" height="23"><b><font size="4" color="blue">Edited Profiles </font></b></td>
    </tr>
    <tr align="CENTER">
      <td class="label" width=8% height="20"><b>S.No.</b></td>
      <td class="label" width=13% height="21"><b>Profile ID</b></td>
      <td class="label" width=26% height="21"><b>User Name</b></td>
      <td class="label" width=15% height="21"><b>Alloted Time(IST)</b></td>
      <td class="label" width=15% height="21"><b>Submit Time(IST)</b></td>      
      <td class="label" width=15% height="21"><b>View </b></td>	
      <td class="label" width=15% height="21"><b>Remaining Time </b></td>	
      </tr>
    ~section name=index loop=$ROW1`
    <tr align="CENTER" bgcolor="#fbfbfb" class="~$ROW1[index].bandcolor`">
      <td height="20" align="CENTER" width="8%">~$ROW1[index].sno`</td>
      <td height="21" width="13%">~$ROW1[index].profileid`</td>
      <td height="21" width="26%" align="LEFT">~$ROW1[index].username`</td>
      <td height="21" width="15%">~$ROW1[index].receive_time`</td>
      <td height="21" width="15%">~$ROW1[index].submit_time`</td>
      <td width=15% height="21"><b> <a href="useredit.php?pid=~$ROW1[index].profileid`&cid=~$cid`&user=~$user`&val=edit">view</a></td> </b></td>	
	<td height="20" width="13%" class="~$ROW1[index].status_color`" >~$ROW1[index].remaining_time`</td>
    </tr>
    ~/section`
    <tr bgcolor="#fbfbfb">
      <td colspan="7" height="21">&nbsp; </td>
   </tr>
    <tr>
      <td colspan="7" height="21">&nbsp; </td>
   </tr>
    <tr >
      <td width="8%" height="21">&nbsp; </td>
      <td height="21" width="11%" align="center">
  </table>
 </form>
 

 <br><br><br><br><br><br>
~include file="foot.htm"`


</body>
</html>
