<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Contacts Detail : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
~include file="jsadmin/head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="contactsmis.php" method="post">
<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome</b></font></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="/jeevansathi/mis/index.php">MIS Home</a></td>
    </tr>
</table>
<br><br><br>
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
      	<td class="label" width="21%">Contact Status</td>
      	<td bgcolor="#f5f5f5" colspan="4">
         <select name="cstatus" size="1" class="TextBoxes1">
            <option value="ALL" ~if $CSTATUS eq 'ALL'` selected ~/if`>All</option>
            <option value="A" ~if $CSTATUS eq 'A'` selected ~/if`>Accepted</option>
            <option value="D" ~if $CSTATUS eq 'D'` selected ~/if`>Declined</option>
            <option value="I" ~if $CSTATUS eq 'I'` selected ~/if`>Initial</option>
            <option value="C" ~if $CSTATUS eq 'C'` selected ~/if`>Cancelled</option>
          </select>
     	 </td>
	</tr>
  <tr valign="middle" align="Right">
              <td colspan="2"><input type="submit" name="Submit" value="  Search  " class="textboxes1"></td></tr>
~if $SHOWALL eq "Y"`
~section name=index loop=$TYPE`
	 <tr>
            <td class="formhead" valign="middle" colspan="1">&#155;~if $TYPE[index] eq 'A'` Accepted ~elseif $TYPE[index] eq "C"` Cancelled ~elseif $TYPE[index] eq "D"` Declined ~elseif $TYPE[index] eq "I"` Initial ~/if`</td>
            <td class="formhead" valign="middle" colspan="2">~$COUNT[index]`</td>
          </tr>
~/section`
~/if`	 
	<tr>
            <td class="formhead" valign="middle" colspan="1">&#155;~if $CSTATUS eq 'A'`Accepted ~elseif $TYPE[index] eq "C"` Cancelled ~elseif $CSTATUS eq "D"` Declined ~elseif $CSTATUS eq "I"` Initial ~else` Total~/if`</td>
            <td class="formhead" valign="middle" colspan="2">~$TOTAL`</td>
          </tr>
</table>
 <br><br>
</form>
~include file="jsadmin/foot.htm"`

</body>
</html>
