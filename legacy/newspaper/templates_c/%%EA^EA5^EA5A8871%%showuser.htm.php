<?php /* Smarty version 2.6.7, created on 2006-08-10 16:25:44
         compiled from showuser.htm */ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi.com - JSADMIN</title>
  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
</head>
<body>
<table border="0" cellspacing="2" cellpadding="2" align=center width=80%>
<tr> <td colspan=100% class=formhead align=center><a href="managebackend.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&name=<?php echo $this->_tpl_vars['name']; ?>
&mode=<?php echo $this->_tpl_vars['mode']; ?>
">Main Page</a> </td></tr>
</table>
<br>
<table border="0" cellspacing="2" cellpadding="2" align=center width=100%>
<tr class=label align=center>
	<td>ID</td>
	<td>USERNAME</td>
	<td>EMAIL</td>
	<td>PRIVILAGE</td>
	<td>CENTER</td>
	<td>ACTIVE</td>
	<td>MOD_DATE</td>
	<td>ENTRY BY</td>
	<td>EDIT</td>
	<td>ACTIVATE / DEACTIVATE</td>
</tr>
<?php unset($this->_sections['show']);
$this->_sections['show']['name'] = 'show';
$this->_sections['show']['loop'] = is_array($_loop=$this->_tpl_vars['user']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['show']['show'] = true;
$this->_sections['show']['max'] = $this->_sections['show']['loop'];
$this->_sections['show']['step'] = 1;
$this->_sections['show']['start'] = $this->_sections['show']['step'] > 0 ? 0 : $this->_sections['show']['loop']-1;
if ($this->_sections['show']['show']) {
    $this->_sections['show']['total'] = $this->_sections['show']['loop'];
    if ($this->_sections['show']['total'] == 0)
        $this->_sections['show']['show'] = false;
} else
    $this->_sections['show']['total'] = 0;
if ($this->_sections['show']['show']):

            for ($this->_sections['show']['index'] = $this->_sections['show']['start'], $this->_sections['show']['iteration'] = 1;
                 $this->_sections['show']['iteration'] <= $this->_sections['show']['total'];
                 $this->_sections['show']['index'] += $this->_sections['show']['step'], $this->_sections['show']['iteration']++):
$this->_sections['show']['rownum'] = $this->_sections['show']['iteration'];
$this->_sections['show']['index_prev'] = $this->_sections['show']['index'] - $this->_sections['show']['step'];
$this->_sections['show']['index_next'] = $this->_sections['show']['index'] + $this->_sections['show']['step'];
$this->_sections['show']['first']      = ($this->_sections['show']['iteration'] == 1);
$this->_sections['show']['last']       = ($this->_sections['show']['iteration'] == $this->_sections['show']['total']);
?>
<tr align=center class=fieldsnew>   
	<td><?php echo $this->_sections['show']['index_next']; ?>
</td>
	<td><?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['USERNAME']; ?>
</td>
	<td><?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['EMAIL']; ?>
</td>
	<td align=left>
	<?php unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['priv'][$this->_sections['show']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sec']['show'] = true;
$this->_sections['sec']['max'] = $this->_sections['sec']['loop'];
$this->_sections['sec']['step'] = 1;
$this->_sections['sec']['start'] = $this->_sections['sec']['step'] > 0 ? 0 : $this->_sections['sec']['loop']-1;
if ($this->_sections['sec']['show']) {
    $this->_sections['sec']['total'] = $this->_sections['sec']['loop'];
    if ($this->_sections['sec']['total'] == 0)
        $this->_sections['sec']['show'] = false;
} else
    $this->_sections['sec']['total'] = 0;
if ($this->_sections['sec']['show']):

            for ($this->_sections['sec']['index'] = $this->_sections['sec']['start'], $this->_sections['sec']['iteration'] = 1;
                 $this->_sections['sec']['iteration'] <= $this->_sections['sec']['total'];
                 $this->_sections['sec']['index'] += $this->_sections['sec']['step'], $this->_sections['sec']['iteration']++):
$this->_sections['sec']['rownum'] = $this->_sections['sec']['iteration'];
$this->_sections['sec']['index_prev'] = $this->_sections['sec']['index'] - $this->_sections['sec']['step'];
$this->_sections['sec']['index_next'] = $this->_sections['sec']['index'] + $this->_sections['sec']['step'];
$this->_sections['sec']['first']      = ($this->_sections['sec']['iteration'] == 1);
$this->_sections['sec']['last']       = ($this->_sections['sec']['iteration'] == $this->_sections['sec']['total']);
?>
		<?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']][$this->_sections['sec']['index']]['PRIVILAGE']; ?>
<br>
	<?php endfor; endif; ?>
	</td>
	
	<td><?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['CENTER']; ?>
</td>
	<td><?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['ACTIVE']; ?>
</td>
	<td><?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['MOD_DT']; ?>
</td>
	<td><?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['ENTRYBY']; ?>
</td>
	<td><a href=edit_userlogin.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&RESID=<?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['RESID']; ?>
>Edit</a></td>
	<td>
	<?php if ($this->_tpl_vars['user'][$this->_sections['show']['index']]['ACTIVE'] == 'N'): ?>
		<a href=edit_userlogin.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&RESID=<?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['RESID']; ?>
&act=Y>Activate</a>
	<?php else: ?>
		<a href=edit_userlogin.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&RESID=<?php echo $this->_tpl_vars['user'][$this->_sections['show']['index']]['RESID']; ?>
&act=N>De-Activate</a></font></td>
	<?php endif; ?>
	</td>
</tr>
<?php endfor; endif; ?>
</body>
</html>