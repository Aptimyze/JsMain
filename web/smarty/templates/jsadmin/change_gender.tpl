<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UserEdit : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`
<br>
<body bgcolor="#FFFFFF" topmargin="0" marginheight="0">

<table width="600" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<form action="change_gender.php" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr height="21">
    <td width="157">&nbsp;</td>
    <td width="343">&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Username</b></td>
    <td>~$username`&nbsp;</td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Gender</b></td>
    <td>~$gender`&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right">change with : &nbsp;</td>
    <td><select name="Gender">
	<option value="">Select</option>
	<option value="M">Male</option>
	<option value="F">Female</option>
	</td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Date of Birth</b></td>
    <td>~$dateofbirth`&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right">change with : &nbsp;</td>
    <td> <input type="text" name="Dtofbirth" value="" size="20" class="textboxes1"></td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="center"></td>
    <td align="center">
	<input type="hidden" name=pid value="~$pid`">
	<input type="hidden" name=cid value="~$cid`">
	<input type="hidden" name=user value="~$user`">
	<input type="hidden" name=gender value="~$gender`">
	<input type=submit value="Update" name=Submit></td> 
   </tr>
</table>
<br>
<br>
</form>
</body>
~include file="foot.htm"`
</html>
