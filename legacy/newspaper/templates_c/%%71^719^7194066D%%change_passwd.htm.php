<?php /* Smarty version 2.6.6, created on 2008-09-11 07:51:50
         compiled from change_passwd.htm */ ?>
<HTML>
<HEAD>
<script language="JavaScript">
function checkpasswd()
{
if(document.changepassword.old_passwd.value == "")
{
alert("Please Enter Old Password");
return false;
}
if(document.changepassword.new_pwd.value =="")
{
alert("Please Enter New Password");
return false;
}
if(document.changepassword.confirm_new_pwd.value =="")
{
alert("Please Confirm New Password");
return false;
}

return true;
}

</script>
<TITLE>Change Password.</TITLE>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
</HEAD>
<BODY>
<br>
<center>
<p style="font-family:Verdana; font-size:10pt; font-color:#FCFFCF; font-weight: bold">CHANGE YOUR PASSWORD</p>
<br>
<table width="50%" cellspacing ="2" cellpadding= "2">
<form name="changepassword" method="post" action="change_passwd.php">
<tr class="formhead" height="23">
<td colspan="2" align="center"><?php echo $this->_tpl_vars['msg']; ?>
</td>
</tr>
<tr class="formhead" height="23">
<td align="left" width="40%"><font color="grey" style="font-weight:bold">&nbsp;&nbsp;Username : <?php echo $this->_tpl_vars['name']; ?>
</font></td>
<td align="right" ><a href="javascript: history.go(-1)">Go Back</a></td>
</tr>
<tr>
<td width="50%"class="label" align="center">
<?php if ($this->_tpl_vars['isempty_old_passwd'] == '1'): ?>
<font color ="red">
<?php endif; ?>
Enter Old password
</td>
<td class="fieldsnew" align="left" style="border:1px solid #000080">
<input type = "password" name="old_passwd"  size="30" style ="background-color:#DEEFEF">
<tr>
<tr>
<td class="label" align="center">
<?php if ($this->_tpl_vars['isempty_new_pwd'] == '1'): ?>
<font color ="red">
<?php endif; ?>
Enter New password</td>
<td class="fieldsnew" align="left" style="border:1px solid #000080">
<input type = "password" name="new_pwd" size="30" style ="background-color:#DEEFEF">
<tr>
<tr>
<td class="label" align="center">
<?php if ($this->_tpl_vars['isempty_confirm_new_pwd'] == '1'): ?>
<font color ="red">
<?php endif; ?>
Confirm New password</td>
<td class="fieldsnew" align="left" style="border:1px solid #000080">
<input type = "password" name="confirm_new_pwd" size="30" style ="background-color:#DEEFEF" >
<tr>
<tr class="fieldsnew">
<td align="center">&nbsp;</td>
<td align="center">
<input type="submit" name="submit" value="Submit"  onclick="return checkpasswd();" style="border:1px bordercolor:black  font-family: Times; font-size: 9pt; font-weight: bold; background-color:#DEEFEF">
<input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['cid']; ?>
">
<input type="hidden" name="name" value="<?php echo $this->_tpl_vars['name']; ?>
">
</td>
<tr>
<table>
</center>
</BODY>
</HTML>

