<?php /* Smarty version 2.6.7, created on 2008-02-18 13:07:43
         compiled from addnew_user.htm */ ?>
<html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title> ADDING NEW USER INFORMATION </title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
<body>
<center><h3>ADD NEW USER INFORMATION<h3><center>
<form action="addnew_user.php" method=POST>
<table border="0" cellspacing="2" cellpadding="2" width=80% align=center>
<tr>
	<td class=label width=40%>
		<?php if ($this->_tpl_vars['check_name'] == 1 || $this->_tpl_vars['user_exists'] == '1'): ?> <font color=red> USERNAME <?php if ($this->_tpl_vars['user_exists'] == '1'): ?> &nbsp;&nbsp;(This username already exists) <?php endif; ?></font> <?php else: ?> USERNAME <?php endif; ?>
	</td>
	<td class=fieldsnew><input type=text   name=USERNAME  value="<?php echo $this->_tpl_vars['USERNAME']; ?>
"></font></td>
</tr>
<tr>
        <td class=label><?php if ($this->_tpl_vars['check_passwd'] == 1): ?><font color=red> PASSWORD </font> <?php else: ?> PASSWORD <?php endif; ?></td>
        <td class=fieldsnew><input type=text   name=PASSWORD   value="<?php echo $this->_tpl_vars['PASSWORD']; ?>
"></td>
</tr>
<tr>
	<td class=label><?php if ($this->_tpl_vars['check_email'] == 1): ?> <font color=red> EMAIL </font> <?php else: ?> EMAIL <?php endif; ?></td>
	<td class=fieldsnew><input type=text   name=EMAIL  value="<?php echo $this->_tpl_vars['EMAIL']; ?>
"></td>
</tr>
<tr>
        <td class=label><?php if ($this->_tpl_vars['check_priv'] == 1): ?> <font color=red> PRIVILAGE </font> <?php else: ?> PRIVILAGE <?php endif; ?></td>
        <td class=fieldsnew><select name="PRIVILAGE[]" size="5" multiple class="TextBox"><?php echo $this->_tpl_vars['options']; ?>
</td>
</tr>
<tr>
        <td class=label><?php if ($this->_tpl_vars['check_center'] == 1): ?> <font color=red> CENTER</font> <?php else: ?> CENTER <?php endif; ?></td>
        <td class=fieldsnew><select  name=CENTER ><?php echo $this->_tpl_vars['center']; ?>
</td>
</tr>
<tr>
	<td class=label><?php if ($this->_tpl_vars['check_active'] == 1): ?> <font color=red> ACTIVE </font> <?php else: ?> ACTIVE <?php endif; ?></td>
	<td class=fieldsnew><input type=checkbox name=ACTIVE value="Y" checked></td>
</tr>
<tr align=center>
<td class=fieldsnew colspan=100%>
	<input type=Hidden  name=RESID    value=<?php echo $this->_tpl_vars['RESID']; ?>
>
	<!--<input type=Hidden  name=PRIVILAGE value=<?php echo $this->_tpl_vars['PRIVILAGE']; ?>
> -->
	<input type=Hidden  name=cid    value=<?php echo $this->_tpl_vars['cid']; ?>
>
	<input type=submit  name=submit value=Submit>
</td>
</tr>
</table>
</body>
</html>   