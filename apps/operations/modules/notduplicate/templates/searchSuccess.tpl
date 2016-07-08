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
</script>
</head>


<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form action="~$moduleurl`/search" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`
<br><br>
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="2" align="center" >Mark profile pair as Not Duplicate</td>
	</tr>
<br>
          <tr>
            <td width="48%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Search profiles identified as duplicate of:
</td>

            <td width="50%" bgcolor="#F9F9F9">
              <input type="text" name="username" value="~if $username`~$username`~else`[Enter Username]~/if`" size="38" maxlength="40" class="textboxes1" onclick="javascript:check_mes(this,'[Enter Username]')">
              ~if $error eq 1`<BR><b style="color:red;font-size:12px">Please enter a valid Username</b></BR>~/if`
              ~if $error eq 2` <BR><b style="color:red;font-size:12px">No duplicate profile found</b></BR>~/if`
            </td>
          </tr>

                     <tr valign="middle" align="center" >
	
              <td class="fieldsnew" colspan="2"><BR><input type="submit" name="Go" value="  Search  " class="textboxes1" style="width:70px;height:30px;background:green;color:white"></td></tr>
	
	</form>
<br>
~if $error eq -1`	
<form name=fr1 action='~$moduleurl`/submit' method="POST" onSubmit="return checkform()">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=duplicateProfile value="~$duplicateProfile`">
<TR><TD></TD></TR>
<TR><TD></TD></TR>
<TR><TD></TD></TR>
<TR><td colspan=2  width="48%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">

Choose profiles which are not duplicate of 
<!--<a href="~sfConfig::get('app_site_url')`/jsadmin/showstat.php?cid=~$cid`&profileid=~$duplicateProfile`" target="_blank">~$username`</a>-->
<a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/ShowProfileStats?cid=~$cid`&profileid=~$duplicateProfile`" target="_blank">~$username`</a>

<td></TR>
~foreach from=$duplicates item=Value key=Label name=foo`
<TR>
~if $duplicateProfile neq $Label`
<td  width="48%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">
<input type="checkbox" name="profiles[]" value="~$Label`" onclick="javascript:checkbox_clicked(this.checked)"/>&nbsp;&nbsp;&nbsp;</td>
<TD width="50%" bgcolor="#F9F9F9">
<!--<a href="~sfConfig::get('app_site_url')`/jsadmin/showstat.php?cid=~$cid`&profileid=~$Label`"  target="_blank">~$Value`</a>-->
<a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/ShowProfileStats?cid=~$cid`&profileid=~$Label`"  target="_blank">~$Value`</a>
<input type="hidden" name=ids[] value="~$Label`">
</TD>~/if`
</TR>
~/foreach`
<tr><td width="48%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="right">Comments</td><TD width="50%" bgcolor="#F9F9F9"><textarea name="comments" style="height:120px;width:250px"></textarea></TD></tr>
<TR  valign="middle" align="center"><TD  class="fieldsnew" colspan="2" ><input type="submit" name="Go" value="  Not Duplicate  " class="textboxes1" ~if $disabled` disabled="disabled" ~/if` style="margin-top:40px;margin-left:100px;width:170px;height:30px;background:~$background_color`;color:white"><BR><BR><BR><BR><BR><BR></TD></tr>
</table>
~/if`
</form>
<script>
var check=0;
function checkbox_clicked(checked)
{
	if(checked)
		check++;
	else
		check--;
}
function check_mes(ele,defaultmes)
{
	if(ele.value==defaultmes)
		ele.value="";
}
function checkform()
{
	if(document.fr1.comments.value=="")
	{
		alert("Please provide comments");
		return false;
	}
	if(check<=0)
	{
		alert("Please check for whom profile is not duplicate");
		return false;
	}
}
</script>
~include_partial('global/footer')`
</body>
</html>
