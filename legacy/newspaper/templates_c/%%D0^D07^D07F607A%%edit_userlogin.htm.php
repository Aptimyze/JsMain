<?php /* Smarty version 2.6.7, created on 2006-08-10 16:25:49
         compiled from edit_userlogin.htm */ ?>
<html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> EDITING THE USER INFORMATION </title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
<body>
<center><h3>EDIT ACCOUNT DETAILS</h3></center>
<br>
<form action="edit_userlogin.php" method=POST>
<table border="0" cellspacing="2" cellpadding="2" width=70% align=center>
<tr>
	<td class=label> USERNAME</td>
	<td class=fieldsnew><?php echo $this->_tpl_vars['USERNAME']; ?>
</font></td>
</tr>
<tr>
        <td class=label> PASSWORD </td>
        <td class=fieldsnew><input type=text   name=MOD_PASSWD   value="<?php echo $this->_tpl_vars['MOD_PASSWD']; ?>
"><font color=red>&nbsp;&nbsp;(Enter password only in case you wish to change your existing one)</font></td>
</tr>
<tr>
	<td class=label><?php if ($this->_tpl_vars['check_email'] == 1): ?> <font color=red> EMAIL </font> <?php else: ?> EMAIL <?php endif; ?></td>
	<td class=fieldsnew><input type=text   name=MOD_EMAIL  value="<?php echo $this->_tpl_vars['EMAIL']; ?>
"></td>
</tr>
<tr>
        <td class=label><?php if ($this->_tpl_vars['check_priv'] == 1): ?> <font color=red> PRIVILAGE </font> <?php else: ?> PRIVILAGE <?php endif; ?></td>
        <td class=fieldsnew><select name="MOD_PRIV[]" size="4" multiple class="TextBox"><?php echo $this->_tpl_vars['options']; ?>
</td>
</tr>
<tr>
        <td class=label><?php if ($this->_tpl_vars['check_center'] == 1): ?> <font color=red> CENTER</font> <?php else: ?> CENTER <?php endif; ?></td>
        <td class=fieldsnew><select name=MOD_CENTER  ><?php echo $this->_tpl_vars['center']; ?>
</td>
</tr>
<tr>
	<td class=label><?php if ($this->_tpl_vars['check_active'] == 1): ?> <font color=red> ACTIVE </font> <?php else: ?> ACTIVE <?php endif; ?></td>
	<td class=fieldsnew><input type=checkbox name=MOD_ACTIVE value="Y" <?php if ($this->_tpl_vars['ACTIVE'] == 'Y'): ?> checked <?php endif; ?>></td>
</tr>
<tr align=center>
<td class=fieldsnew colspan=100%>
	<input type=Hidden  name=RESID    value=<?php echo $this->_tpl_vars['RESID']; ?>
>
	<input type=Hidden  name=cid    value=<?php echo $this->_tpl_vars['cid']; ?>
>
	<input type=submit  name=submit value=MODIFY>
</td>
</tr>
</table>
</body>
</html>   