<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Admin Search : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="head.htm"`
<br>
<body bgcolor="#FFFFFF" topmargin="0" marginheight="0">

<table width="600" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER">
    <tr width=100% class="formhead">
          <td width=15% class="formhead" border=1><font><b>Welcome : ~$user`</b></font></td>
          <td width="70%" class="formhead" align="center">~if $STATUS eq "UPGRADE"`Upgrade Membership ~elseif $STATUS eq "RENEW"` Renew Membership ~/if`</td>
          <td width=15% class="formhead" border=1 align='CENTER'><a href="logout.php?cid=~$cid`">Logout</a></td>
    </tr>
</table>
<form action="makepaid.php" method="post">
<table width="375" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="218">&nbsp;</td>
  </tr>
  <tr class="label">
    <td align="center">User Name</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
	<td>~$username`</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr class="label">
    <td align="center">Email</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>~$EMAIL`</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label">
    <td align="center">Current Membership</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>~$MEMBERSHIP`</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label">
<td align="center">Expiry Date</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>~$EXP_DT`</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label">
<td align="center">Discount</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="discount_new" value="~$DISCOUNT_NEW`"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr class="label">
    <td align="center">~if $CHECK_DISCOUNT_TYPE eq "Y"`<font color="red">Discount Type*</font>~else`Discount Type~/if`</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>
	<select name="discount_type" size="1" class="TextBoxes1">
            <option value="">Select</option>
            <option value="1" ~if $DISCOUNT_TYPE eq "1"` selected ~/if`>Renewal Discount</option>            <option value="2" ~if $DISCOUNT_TYPE eq "2"` selected ~/if`>General Discount</option>            <option value="3" ~if $DISCOUNT_TYPE eq "3"` selected ~/if`>Complementary Discount</option>
            <option value="4" ~if $DISCOUNT_TYPE eq "4"` selected ~/if`>Referral Discount</option>
          </select>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr class="label">
<td align="center">~if $CHECK_REASON eq "Y"`<font color="red">Reason</font>~else`Reason~/if`</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td><input type="text" name="reason_new" value="~$REASON_NEW`"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr class="label">
    <td align="center">~if $CHECK_MTYPE eq "Y"`<font color="red">Membership Type*</font>~else`Membership Type*~/if`</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>
	 <select name="mtype" size="1" class="TextBoxes1">
            <option value="">Select</option>
            <option value="F" ~if $mtype eq 'F'`selected~/if`>Full Member</option>   
            <option value="V" ~if $mtype eq 'V'`selected~/if`>Value Added Member</option>   
         </select>
	
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="label">
    <td align="center">~if $CHECK_DURATION eq "Y"`<font color="red">Duration*</font>~else`Duration*~/if`</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>
	 <select name="duration" size="1" class="TextBoxes1">
            <option value="">Select</option>
            <option value="3" ~if $duration eq "3"`selected~/if`>3 Months</option>   
            <option value="6" ~if $duration eq "6"`selected~/if`>6 Months</option>   
            <option value="12" ~if $duration eq "12"`selected~/if`>12 Months</option>   
         </select>
	
    </td>
  </tr>
~if $STATUS eq "UPGRADE"`
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr class="label">
    <td align="center">~if $CHECK_EXP eq "Y"`<font color="red">Start Service from*</font>~else`Start Service from*~/if`</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
    <td >&nbsp;</td>
    <td>
	 <select name="exp" size="1" class="TextBoxes1">
            <option value="">Select</option>
            <option value="prev" ~if $exp eq 'prev'`selected~/if`>Last service activation date</option>   
            <option value="today" ~if $exp eq 'today'`selected~/if`>Today</option>   
         </select>
	
    </td>
  </tr>
~/if`
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="fieldsnew">
  <td></td>
    <td align="center"><input type=submit value="Done" name=Done></td>
    <td align="center">
	
	<input type="hidden" name=link_msg value="~$link_msg`">
	<input type="hidden" name=username value="~$username`">
	<input type="hidden" name=user value="~$user`">
	<input type="hidden" name=email value="~$EMAIL`">
	<input type="hidden" name=membership value="~$MEMBERSHIP`">
	<input type="hidden" name=exp_dt value="~$EXP_DT`">
	<input type="hidden" name=cid value="~$cid`">
	<input type="hidden" name=pid value="~$pid`">
	<input type="hidden" name=discount value="~$DISCOUNT`">
	<input type="hidden" name=reason value="~$REASON`">
	<input type="hidden" name=status value="~$STATUS`">
	<input type="hidden" name=source value="~$SOURCE`"
   </tr>
</table>
<br>
<br>
</form>
</body>
~include file="foot.htm"`
</html>
