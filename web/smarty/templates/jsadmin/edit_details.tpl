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

function MM_openBrWindow(obj,theURL,winName,features)
{
        window.open(theURL,winName,features);
        obj.onclick= null;
        obj.style.color='#008800';
}
-->
</script>
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
<form action="edit_details.php" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr height="21">
    <td width="157">&nbsp;</td>
    <td width="343">&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Username</b></td>
    <td>~$profilename`&nbsp;</td>
  </tr>


~if $SHOWEMAIL eq "Y"`
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Email</b></td>
    <td>~$email`&nbsp;</td>
  </tr>
~/if`
~if $SHOWCONTACT eq "Y"`
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Contact</b></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right"> : &nbsp;</td>
    <td>~$contact`&nbsp;&nbsp;<b>Show</b>&nbsp;&nbsp;~$showaddress`</td>
  </tr>
~if $SHOWEDITCONTACT eq "Y"`
  <tr class="fieldsnew" height="21">
    <td align="right">change with : &nbsp;</td>
    <td> <textarea rows="4" cols="30" name="address" value="" size="20" class="textboxes1"></textarea></td>
  </tr>
~/if`  
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Parents Contact</b></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right"> : &nbsp;</td>
    <td>~$parentscontact`&nbsp;&nbsp;<b>Show</b>&nbsp;&nbsp;~$show_parents_contact`</td>
  </tr>
~if $SHOWEDITCONTACT eq "Y"`
  <tr class="fieldsnew" height="21">
    <td align="right">change with : &nbsp;</td>
    <td> <textarea rows="4" cols="30" name="parents_address" value="" size="20" class="textboxes1"></textarea></td>
  </tr>
~/if`
~/if`
~if $SHOWPHONE eq "Y"`
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Phone</b></td>
    <td>&nbsp;</td>
  </tr>
<tr class="fieldsnew" height="21">
    <td align="right"> : &nbsp;</td>
    <td>ISD: ~$isd`</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right"> : &nbsp;</td>
    <td>~$phoneres`&nbsp;&nbsp;<b>Show</b>&nbsp;&nbsp;~$showphone_res`<br>~$phonemob`&nbsp;&nbsp;<b>Show</b>&nbsp;&nbsp;~$showphone_mob`<br>~$altmob`&nbsp;&nbsp;<b>Show</b>&nbsp;&nbsp;~$showalt_mob`</td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Messenger</b></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right"> : &nbsp;</td>
    <td>~$messenger_id`&nbsp;&nbsp;~$messenger_channel`&nbsp;&nbsp;<b>Show</b>&nbsp;&nbsp;~$showmessenger`</td>
  </tr>
~/if`
~if $SHOWINFO eq "Y"`
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Date of Birth</b></td>
    <td>~$dateofbirth`&nbsp;</td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Entry Date</b></td>
    <td>~$entry_dt`&nbsp;</td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label" height="21">
    <td align="center"><b>Profile Privacy Setting</b></td>
    <td>~$privacy`&nbsp;</td>
  </tr>
  <tr class="fieldsnew" height="21">
    <td align="right">&nbsp;</td>
    <td> &nbsp; </td>
  </tr>
  <tr height="21">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
~/if`
  <tr class="fieldsnew" height="21">
    <td align="center"></td>
    <td align="center">
	<input type="hidden" name=pid value="~$pid`">
	<input type="hidden" name=cid value="~$cid`">
	<input type="hidden" name=user value="~$user`">

	~if $SHOWEDITCONTACT eq "Y"`
	<input type=submit value="Update" name=Submit></td> 
	~else`
	<font color="red"> You are not authorized to make the changes.</font>
	~/if`
	
   </tr>
</table>
<br>
<br>
</form>
</body>
~include file="foot.htm"`
</html>
