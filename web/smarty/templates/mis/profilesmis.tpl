<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Profiles Detail : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="/jeevansathi/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="/jeevansathi/profile/images/styles.css" type="text/css">
</head>
~include file="jeevansathi/head.htm"`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="profilesmis.php" method="post">
<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr width=100% class="formhead">
          <td width=55% class="formhead" border=1><font><b>Welcome</b></font></td>
          <td width="15%" class="formhead" align="center">
          <td width=10% class="formhead" border=1 align='CENTER'><a href="/jeevansathi/mis/index.php">MIS Home</a></td>
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
      	<td class="label" width="21%">Tieup Source</td>
      	<td bgcolor="#f5f5f5" colspan="4">
         <select name="Source" size="1" class="TextBoxes1">
            <option value="ALL">All</option>
		~$source`
          </select>
     	 </td>
	</tr>
  <tr valign="middle" align="Right">
              <td colspan="2"><input type="submit" name="Submit" value="  Search  " class="textboxes1"></td></tr>
~if $PAGE eq '1'`
	 <tr>
            <td class="formhead" valign="middle" colspan="1">&#155; New Profiles</td>
            <td class="formhead" valign="middle" colspan="2">~$NEW`</td>
          </tr>
	 <tr>
            <td class="formhead" valign="middle" colspan="1">&#155; Edited Profiles</td>
            <td class="formhead" valign="middle" colspan="2">~$EDIT`</td>
          </tr>
	 <tr>
            <td class="formhead" valign="middle" colspan="1">&#155; Total Profiles</td>
            <td class="formhead" valign="middle" colspan="2">~$TOTAL`</td>
          </tr>
~/if`
</table>
 <br><br><br>
</form>
~include file="jeevansathi/foot.htm"`


</body>
</html>
