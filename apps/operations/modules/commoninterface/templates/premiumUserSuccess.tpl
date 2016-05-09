<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Admin Search : Jeevansathi.com Matrimonial Services</title>
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">
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
~if $mes`
alert("~$mes`");
~/if`
</script>
</head>


<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="updatePremiumUser" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`
<br><br>
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="2" align="center" >Tag Profile as Premium User</td>
	</tr>
~if $success`
          <tr>
<Td colspan=2 style='color:green;font-size:13px'>
Succesfully removed
</Td></TR>
         ~/if`
          <tr>
            <td width="48%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Current Premium User:
</td>

            <td width="50%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~if $username`~$username`~else`~/if`" size="38" maxlength="40" class="textboxes1" onclick="javascript:check_mes(this,'[Enter Username]')">
            </td>
          </tr>
          <tr>
            <td width="48%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Dummy User:
</td>

            <td width="50%" bgcolor="#F9F9F9">
              <input type="text" name="dusername" value="~if $dusername`~$dusername`~else`~/if`" size="38" maxlength="40" class="textboxes1" onclick="javascript:check_mes(this,'[Enter Username]')">
            </td>
          </tr>

          <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan=2><BR><input type="submit" name="Add" value="  SUBMIT  " class="textboxes1" style="width:70px;height:30px;background:green;color:white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--<input type="submit" name="Remove" value="  Remove  " class="textboxes1" style="width:70px;height:30px;background:green;color:white"></td>
</tr>

	</table>
	</form>
<br>
</form>
<script>
function check_mes(ele,defaultmes)
{
	if(ele.value==defaultmes)
		ele.value="";
}
</script>
~include_partial('global/footer')`
</body>
</html>
