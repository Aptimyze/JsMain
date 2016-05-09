<?php /* Smarty version 2.6.6, created on 2008-09-11 02:36:38
         compiled from showpromotiondetails.htm */ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Promotion Details</title>
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<?php echo $this->_tpl_vars['HEAD']; ?>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"><br><br>
<table width=560 cellspacing="1" cellpadding='2' ALIGN="CENTER">
<tr class=formhead>
<td width=30% align=center>Welcome : <?php echo $this->_tpl_vars['username']; ?>
</td>
<td align=right><a href="mainpage.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&name=<?php echo $this->_tpl_vars['name']; ?>
">Main Page</a>
<td width=20% align=center><a href="logout.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
">Logout</a></td>
</tr>
</table>
<br>
<table border=0 align="center" width=560 cellspacing="1" cellpadding='2'>
<tr>
<td height="23" colspan="2" class="formhead" align="center"><h2 align="center">Your Work details for this month</h2></td>
</tr>
<tr>
<td height="23" colspan="2" class="formhead" align="center">&nbsp;</td>
</tr>
<tr>
<td width=70% class="label" align="center">Total Entry from newspaper / mobile no : </td>
<td width=30% class="fieldsnew" align="center"><?php echo $this->_tpl_vars['newscount']; ?>
</td>
</tr>
<!--<tr>
<td class="label" align="center">Total Entry from Affiliate : </td>
<td class="fieldsnew" align="center"><?php echo $this->_tpl_vars['smscount']; ?>
</td>
</tr>
<tr>
<td class="label" align="center">Total number of Registered Users(via promotions) :</td>
<td class="fieldsnew" align="center"><?php echo $this->_tpl_vars['regcount']; ?>
</td>
</tr>
<tr>
<td class="label" align="center">Total number of Paid Members(via promotions) :</td>
<td class="fieldsnew" align="center"><?php echo $this->_tpl_vars['paidcount']; ?>
</td> -->
<td><input type=hidden name=cid value=<?php echo $this->_tpl_vars['cid']; ?>
></td>
<td><input type=hidden name=name value=<?php echo $this->_tpl_vars['name']; ?>
></td>
<td><input type=hidden name=mode value=<?php echo $this->_tpl_vars['mode']; ?>
></td>
</tr>
</table>
</body>
</html>
