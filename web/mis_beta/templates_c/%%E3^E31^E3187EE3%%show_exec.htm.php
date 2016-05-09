<?php /* Smarty version 2.6.7, created on 2006-10-31 13:56:37
         compiled from show_exec.htm */ ?>
<html>
<head>
   <title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../mis/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>
<script language="javascript">
function alert1()
{
	alert("Please mark either Y or N for any profile");
}
function alert2()
{
	alert("Profile has been verified!!!");
}
function alert1()
{
	alert("Profile is needed to follow up again!!!");
}
</script>
</head>
 <table width="761" border="0" cellspacing="0" cellpadding="2" align="CENTER">
<tr>
<td><img src="../profile/images/logo_1.gif" width="192" height="65"></td>
</tr>
<tr>
<td class=bigwhite bgcolor="6BB97B" align=center><font color=white size=3><b>&nbsp;PROFILES, UNDER EXECUTIVE <?php echo $this->_tpl_vars['allotted_to']; ?>
</b></font></td>
<table width="761" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead" width="100%"><td width=50% align="center" colspan=14><a href="../jsadmin/mainpage.php?cid=<?php echo $this->_tpl_vars['checksum']; ?>
">MAIN PAGE</a></td><td width=50%><center><b><a href="show_matriprofile.php?checksum=<?php echo $this->_tpl_vars['checksum']; ?>
">BACK</a></b></center></td></tr>
</table>
</tr></table>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=100%>
<tr class=label>
<td align=center>&nbsp;Executive: <font color=red><b><?php echo $this->_tpl_vars['allotted_to']; ?>
</b></font></td>
<td align=center>&nbsp;Profiles On progress: <font color=red><?php echo $this->_tpl_vars['cnt_onprogress']; ?>
</font></td>
<td align=center>&nbsp;Profiles FollowUp: <font color=red><?php echo $this->_tpl_vars['cnt_followup']; ?>
</font></td>
<td align=center>&nbsp;Profiles OnHold: <font color=red><?php echo $this->_tpl_vars['cnt_onhold']; ?>
</font></td>
<td align=center>&nbsp;Profiles Completed: <font color=red><?php echo $this->_tpl_vars['cnt_completed']; ?>
</font></td>
</tr>
</table>
<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead">
        <td colspan=100% align="center">ON PROGRESS PROFILES</td>
        </tr>
</table>
<table width=100% align="center" border=0 cellspacing=4 cellpadding=5>
<tr class=label>
        <td align=center >&nbsp;S. No.</td>
        <!--td align=center >&nbsp;PROFILEID</td-->
        <td align=center >&nbsp;USER NAME</td>
        <td align=center >&nbsp;ENTRY DATE</td>
        <td align=center >&nbsp;ALLOCATION TIME</td>
        <td align=center >&nbsp;SCHEDULED TIME</td>
        <!--td align=center >&nbsp;STATUS</td-->
</tr>
<?php if ($this->_tpl_vars['allot'] == 1):  unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['allotted']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr class=fieldsnew>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['SNo']; ?>
</td>
        <!--td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
</td-->
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['USERNAME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['ALLOT_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['SCHEDULED_TIME']; ?>
</td>
        <!--td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['STATUS']; ?>
</td-->
</tr>
<?php endfor; endif;  else: ?>
<tr class=fieldsnew>
	<td align=center colspan=100%><center><font color=red size=2><b>No profile is on progress under executive <?php echo $this->_tpl_vars['allotted_to']; ?>
</b></font></center></td>
</tr>
<?php endif; ?>
</table>
<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead">
        <td colspan=100% align="center">FOLLOWUP PROFILES</td>
        </tr>
</table>
<table width=100% align="center" border=0 cellspacing=4 cellpadding=5>
<tr class=label>
        <td align=center >&nbsp;S. No.</td>
        <!--td align=center >&nbsp;PROFILEID</td-->
        <td align=center >&nbsp;USER NAME</td>
        <td align=center >&nbsp;ENTRY DATE</td>
        <td align=center >&nbsp;ALLOCATION TIME</td>
        <td align=center >&nbsp;FOLLOWUP TIME</td>
        <!--td align=center >&nbsp;STATUS</td-->
</tr>
<?php if ($this->_tpl_vars['follow'] == 1):  unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['followup']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr class=fieldsnew>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['followup'][$this->_sections['sec']['index']]['SNO']; ?>
</td>
        <!--td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
</td-->
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['followup'][$this->_sections['sec']['index']]['USERNAME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['followup'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['followup'][$this->_sections['sec']['index']]['ALLOT_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['followup'][$this->_sections['sec']['index']]['COMPLETION_TIME']; ?>
</td>
        <!--td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['STATUS']; ?>
</td-->
</tr>
<?php endfor; endif;  else: ?>
<tr class=fieldsnew>
        <td align=center colspan=100%><center><font color=red size=2><b>No profile is to be followed up under executive <?php echo $this->_tpl_vars['allotted_to']; ?>
</b></font></center></td>
</tr>
<?php endif; ?>
</table>

<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
<tr class="formhead">
        <td colspan=100% align="center">ON HOLD PROFILES</td>
        </tr>
<tr class=label>
        <td align=center >&nbsp;S. No.</td>
        <td align=center >&nbsp;USER NAME</td>
        <td align=center >&nbsp;ENTRY DATE</td>
        <td align=center >&nbsp;ALLOCATION TIME</td>
        <td align=center >&nbsp;ONHOLD TIME</td>
        <td align=center >&nbsp;REASON</td>
</tr>
<?php if ($this->_tpl_vars['b'] == 1):  unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['onhold']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr class=fieldsnew>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['SNO']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['USERNAME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['ALLOT_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['ONHOLD_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['REASON']; ?>
</td>
</tr> <?php endfor; endif;  else: ?>
<tr class=fieldsnew align=center>
<td colspan=100%><font color=red size=2><b><center><b>No profile is on hold under executive <?php echo $this->_tpl_vars['allotted_to']; ?>
</b></center></b></font></td>
</tr> <?php endif; ?>
</table> 
        <table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead">
        <td colspan=100% align="center">TO VERIFY</td>
        </tr>
<tr class=label>
        <td align=center rowspan=2>&nbsp;S. No.</td>
        <td align=center rowspan=2>&nbsp;USER NAME</td>
        <td align=center rowspan=2>&nbsp;ENTRY DATE</td>
        <td align=center colspan=3 width=140>&nbsp;CONTACTS</td>
        <td align=center rowspan=2>&nbsp;COMPLETION TIME</td>
        <td align=center rowspan=2>&nbsp;DOWNLOAD</td>
        <td align=center rowspan=2>&nbsp;<?php if ($this->_tpl_vars['c'] == 1): ?><font color=red><?php endif; ?>VERIFY</font></td>
</tr>
<tr class=label>
	<td align=center width=60>E-Mail</td>
	<td align=center width=40>Mob</td>
	<td align=center width=40>Res</td>
<?php unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['completed']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr class=fieldsnew>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['SNO']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['USERNAME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['EMAIL']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PHONE_MOB']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PHONE_RES']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['COMPLETION_TIME']; ?>
</td>
        <form method=post action="matri_downfile.php?id=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PROFILEID']; ?>
&cuts=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['CUTS']; ?>
&username=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['USERNAME']; ?>
"><td align=center ><input type=submit name=download value=DOWNLOAD></td></form>
<form method=post action="show_exec.php?allotted_to=<?php echo $this->_tpl_vars['allotted_to']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
">
        <td align=center ><input type=checkbox name=Y[] value=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PROFILEID']; ?>
><input type=hidden name=id[] value=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PROFILEID']; ?>
>Y <input type=checkbox name=N[] value=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PROFILEID']; ?>
><input type=hidden name=id value=<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PROFILEID']; ?>
>N</td>
</tr>
<?php endfor; endif;  if ($this->_tpl_vars['a'] == 1): ?>
<tr class=fieldsnew align=center>
<td colspan=100%><font color=red size=2><b><center><?php echo $this->_tpl_vars['msg']; ?>
</center></b></font></td>
</tr>
<?php endif; ?>
</table>
<center><input type=submit name=verify value=VERIFY></center>
</form>
<?php if ($this->_tpl_vars['c'] == 2): ?><script language="javascript">alert1();</script><?php endif;  if ($this->_tpl_vars['verified'] == 1): ?><script language="javascript">alert2();</script><?php endif;  if ($this->_tpl_vars['hold'] == 1): ?><script language="javascript">alert3();</script><?php endif; ?>
</body>
</html>