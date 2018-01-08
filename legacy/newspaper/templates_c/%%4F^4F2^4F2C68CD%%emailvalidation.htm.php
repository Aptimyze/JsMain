<?php /* Smarty version 2.6.6, created on 2008-09-11 02:35:42
         compiled from emailvalidation.htm */ ?>
<html>
<head>
<title>JeevanSathi Matrimonials - Promotion through newspapers</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="styles.css" type="text/css">
<script language="JavaScript">
<!--
function validateForm()
{

var docF=document.nppr_form;
var error="";
var i = 0;
var radio_site = false;
for (i=0;i<docF.TYPE.length;i++)
{         if (docF.TYPE[i].checked)
radio_site = true;
}
if(radio_site == false)
{
error=error+"\n Please choose Email or Phone";
}
if(error=="")
{
return true;
}
else
{
alert(error);
return false;
}
}
-->
</script>
<?php echo $this->_tpl_vars['HEAD']; ?>

</head>
<body>
<form action="emailvalidation.php" name="nppr_form" method="POST" onsubmit="return validateForm();">

<br><br><br>
<table width="560" border="0" cellpadding="1" cellspacing="2" align="center">
<tr class=formhead>
<td width=30% align=center >Welcome : <?php echo $this->_tpl_vars['username']; ?>
</td>
<td height="20" align="right">
<a href=mainpage.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&mode=<?php echo $this->_tpl_vars['mode']; ?>
>Main page</a>&nbsp;&nbsp;
</td>
<td height="20" width=20% align="center">
<a href=logout.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
>Logout</a>
</td>
</tr>
</table>
<table width="560" border="0" cellpadding="1" cellspacing="1" align="center">
<tr class="label" >
<td colspan="2" height="20" align="center">
<?php echo $this->_tpl_vars['MSG']; ?>

</td>
</tr>
<tr>
<td class="label" width=30% align="center"><font color="<?php echo $this->_tpl_vars['fntclr']; ?>
">EMAIL</font></td>
<td class="fieldsnew" width=55% align="left">
<input type="text" name="EMAIL" value=<?php echo $this->_tpl_vars['EMAIL']; ?>
>
</td>
</tr>
<tr>
<td class="label" width=30% align="center"><font color="<?php echo $this->_tpl_vars['pfntclr']; ?>
">PHONE</font></td>
<td class="fieldsnew" width=55% align="left">
<input type="text" name="PHONE" value=<?php echo $this->_tpl_vars['PHONE']; ?>
>
</td>
</tr>
<tr class="fieldsnew">
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td class="fieldsnew" align="center" width=40%>&nbsp;</td>
<td class="fieldsnew" align="left" width=60%>
<input type=hidden name=cid   value=<?php echo $this->_tpl_vars['cid']; ?>
>
<input type=hidden name=name  value=<?php echo $this->_tpl_vars['name']; ?>
>
<input type=hidden name=mode  value="N">
<input type=hidden name=username  value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type=hidden name=email value="<?php echo $this->_tpl_vars['EMAIL']; ?>
">
<input type=hidden name=modcontinue value=<?php echo $this->_tpl_vars['modcontinue']; ?>
>
<input type=submit name=isvalidemail value="Check Validity" class="testbox">
</td>
</tr>
</table>
</body>
</html>
