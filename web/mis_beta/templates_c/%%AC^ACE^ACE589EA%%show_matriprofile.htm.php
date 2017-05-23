<?php /* Smarty version 2.6.7, created on 2006-10-31 14:27:27
         compiled from show_matriprofile.htm */ ?>
<html>
<head>
   <title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../mis/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>
</script>
</head>
 <table width="761" border="0" cellspacing="0" cellpadding="2" align="CENTER">
<tr> 
<td><img src="../profile/images/logo_1.gif" width="192" height="65"></td>
</tr>
<tr>
<td class=bigwhite bgcolor="6BB97B" align=center><font color=white size=3><b>&nbsp;MATRI-PROFILE MEMBERS</b></font></td>
</tr></table>
<table width="761" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead" width="100%"><td align="center" colspan=14><a href="../jsadmin/mainpage.php?cid=<?php echo $this->_tpl_vars['checksum']; ?>
">MAIN PAGE</a></td></tr>
</table>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form method=post action="show_matriprofile.php?checksum=<?php echo $this->_tpl_vars['checksum']; ?>
">
<table width=75% border=0 align="center">
<tr class="fieldsnew">
        <td class="formhead"><b>Total Unallotted profiles:<font color=red> <?php echo $this->_tpl_vars['cnt_unallotted']; ?>
</font><br>Total On progress pofiles:<font color=red> <?php echo $this->_tpl_vars['onprogress']; ?>
</font><br>Total FollowUp profiles:<font color=red><?php echo $this->_tpl_vars['followup']; ?>
</font><br>Total On hold profiles:<font color=red> <?php echo $this->_tpl_vars['onhold']; ?>
</font><br>Total Completed profiles:<font color=red> <?php echo $this->_tpl_vars['Completed']; ?>
</font></td>
	<!--td color=white colspan=30%></td-->
        <td  class="formhead" align="left"><font size=2>Total Executives:<font color=red> <?php echo $this->_tpl_vars['cnt_executive']; ?>
</font><br>
	<?php unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['allotted_to']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['SNO']; ?>
. <a href="show_exec.php?allotted_to=<?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['NAME']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
"><?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['NAME']; ?>
</a> (OnProgress:<font color=red><?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['CNT_ONPROGRESS']; ?>
</font>, FollowUp:<font color=red> <?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['CNT_FOLLOWUP']; ?>
</font>, OnHold:<font color=red><?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['CNT_ONHOLD']; ?>
</font>, ToVerify:<font color=red><?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['CNT_COMPLETED']; ?>
</font>)<br>
	<?php endfor; endif; ?>
       </b></td>
</tr>
</table>
        <table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead">
        <td colspan=100% align="center">UNALLOTTED PROFILES</td>
        </tr>
<tr class=label>
	<td align=center >&nbsp;S. No.</td>
        <td align=center >&nbsp;PROFILEID</td>
        <td align=center >&nbsp;USER NAME</td>
        <td align=center >&nbsp;ENTRY TIME</td>
        <td align=center >&nbsp;SCHEDULED TIME</td>
	<td align=center ><?php if ($this->_tpl_vars['a'] == 1): ?><font color=red><b><?php endif; ?>&nbsp;ALLOT</b></font></td>
</tr>
<?php if ($this->_tpl_vars['flag'] == 1):  unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['unallotted']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['SNO']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
</td>
        <td align=center ><a href='/jsadmin/showstat.php?checksum=<?php echo $this->_tpl_vars['checksum']; ?>
&profileid=<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
'>&nbsp;<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['USERNAME']; ?>
</a></td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['SCHEDULED_TIME']; ?>
</td>
	<td align=center>&nbsp;<input type=checkbox name=allot[] value=<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
><input type=hidden name=pid[] value=<?php echo $this->_tpl_vars['unallotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
></td>
</tr>
<?php endfor; endif; ?>
</table>
<table width=100% >
<tr class=fieldsnew>
<td align=right colspan=80%>
<?php if ($this->_tpl_vars['b'] == 1): ?>
<b><font color=red size=2>&nbsp;<?php echo $this->_tpl_vars['emsg']; ?>
</font></b>
<?php endif; ?>
<select name=executive>
<option>CHOOSE THE EXECUTIVE</option>
<?php unset($this->_sections['sec']);
$this->_sections['sec']['name'] = 'sec';
$this->_sections['sec']['loop'] = is_array($_loop=$this->_tpl_vars['allotted_to']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option><?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec']['index']]['NAME']; ?>
</option>
<?php endfor; endif; ?>
</select></td>
<td align=center colspan=20%><center><input type=submit name=submit value=ALLOT><center></td></tr>
<?php else: ?>
<tr class=fieldsnew>
<td align= center colspan=100%><font color=red size=2><b>&nbsp;No unallotted profile</b></font></td>
</tr>
<?php endif; ?>
</table>        
<!--table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead">
        <td colspan=100% align="center">ALLOTTED PROFILES</td>
        </tr>
<?php unset($this->_sections['sec1']);
$this->_sections['sec1']['name'] = 'sec1';
$this->_sections['sec1']['loop'] = is_array($_loop=$this->_tpl_vars['allotted']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sec1']['show'] = true;
$this->_sections['sec1']['max'] = $this->_sections['sec1']['loop'];
$this->_sections['sec1']['step'] = 1;
$this->_sections['sec1']['start'] = $this->_sections['sec1']['step'] > 0 ? 0 : $this->_sections['sec1']['loop']-1;
if ($this->_sections['sec1']['show']) {
    $this->_sections['sec1']['total'] = $this->_sections['sec1']['loop'];
    if ($this->_sections['sec1']['total'] == 0)
        $this->_sections['sec1']['show'] = false;
} else
    $this->_sections['sec1']['total'] = 0;
if ($this->_sections['sec1']['show']):

            for ($this->_sections['sec1']['index'] = $this->_sections['sec1']['start'], $this->_sections['sec1']['iteration'] = 1;
                 $this->_sections['sec1']['iteration'] <= $this->_sections['sec1']['total'];
                 $this->_sections['sec1']['index'] += $this->_sections['sec1']['step'], $this->_sections['sec1']['iteration']++):
$this->_sections['sec1']['rownum'] = $this->_sections['sec1']['iteration'];
$this->_sections['sec1']['index_prev'] = $this->_sections['sec1']['index'] - $this->_sections['sec1']['step'];
$this->_sections['sec1']['index_next'] = $this->_sections['sec1']['index'] + $this->_sections['sec1']['step'];
$this->_sections['sec1']['first']      = ($this->_sections['sec1']['iteration'] == 1);
$this->_sections['sec1']['last']       = ($this->_sections['sec1']['iteration'] == $this->_sections['sec1']['total']);
?>
<tr class=label>
<td align=center><b><?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']]['SNO']; ?>
</b></td>
<td align=center>&nbsp;Executive: <font color=red><b><?php echo $this->_tpl_vars['allotted_to'][$this->_sections['sec1']['index']]; ?>
</b></font></td>
<td align=center>&nbsp;Profiles completed: <font color=red><?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']]['CNT_COMPLETED']; ?>
</font></td>
<td align=center>&nbsp;Profiles On progress: <font color=red><?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']]['CNT_ONPROGRESS']; ?>
</font></td>
<td align=center>&nbsp;Profiles On Hold: <font color=red><?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']]['CNT_ONHOLD']; ?>
</font></td>
</tr>
<tr class=label>
        <td align=center >&nbsp;SNO.</td>
        <td align=center >&nbsp;PROFILEID</td>
        <td align=center >&nbsp;USER NAME</td>
        <td align=center >&nbsp;ENTRY DATE</td>
        <td align=center >&nbsp;ALLOCATION TIME</td>
        <td align=center >&nbsp;SCHEDULED TIME</td>
        <td align=center >&nbsp;STATUS</td>
</tr>
<?php unset($this->_sections['sec2']);
$this->_sections['sec2']['name'] = 'sec2';
$this->_sections['sec2']['loop'] = is_array($_loop=$this->_tpl_vars['allotted'][$this->_sections['sec1']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sec2']['show'] = true;
$this->_sections['sec2']['max'] = $this->_sections['sec2']['loop'];
$this->_sections['sec2']['step'] = 1;
$this->_sections['sec2']['start'] = $this->_sections['sec2']['step'] > 0 ? 0 : $this->_sections['sec2']['loop']-1;
if ($this->_sections['sec2']['show']) {
    $this->_sections['sec2']['total'] = $this->_sections['sec2']['loop'];
    if ($this->_sections['sec2']['total'] == 0)
        $this->_sections['sec2']['show'] = false;
} else
    $this->_sections['sec2']['total'] = 0;
if ($this->_sections['sec2']['show']):

            for ($this->_sections['sec2']['index'] = $this->_sections['sec2']['start'], $this->_sections['sec2']['iteration'] = 1;
                 $this->_sections['sec2']['iteration'] <= $this->_sections['sec2']['total'];
                 $this->_sections['sec2']['index'] += $this->_sections['sec2']['step'], $this->_sections['sec2']['iteration']++):
$this->_sections['sec2']['rownum'] = $this->_sections['sec2']['iteration'];
$this->_sections['sec2']['index_prev'] = $this->_sections['sec2']['index'] - $this->_sections['sec2']['step'];
$this->_sections['sec2']['index_next'] = $this->_sections['sec2']['index'] + $this->_sections['sec2']['step'];
$this->_sections['sec2']['first']      = ($this->_sections['sec2']['iteration'] == 1);
$this->_sections['sec2']['last']       = ($this->_sections['sec2']['iteration'] == $this->_sections['sec2']['total']);
?>
<tr class=fieldsnew>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['SNo']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['PROFILEID']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['USERNAME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['ALLOT_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['SCHEDULED_TIME']; ?>
</td>
	<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['STATUS']; ?>
</td>
</tr>
<?php endfor; endif;  endfor; endif; ?>
</table>

        <table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead">
        <td colspan=100% align="center">COMPLETED PROFILES</td>
        </tr>
<tr class=label>
	<td align=center >&nbsp;S. No.</td>
        <td align=center >&nbsp;PROFILEID</td>
        <td align=center >&nbsp;USER NAME</td>
        <td align=center >&nbsp;ENTRY DATE</td>
        <td align=center >&nbsp;EXECUTIVE</td>
        <td align=center >&nbsp;ALLOCATION TIME</td>
        <td align=center >&nbsp;COMPLETION TIME</td>
</tr>
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
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['PROFILEID']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['USERNAME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['ALLOTTED_TO']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['ALLOT_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['completed'][$this->_sections['sec']['index']]['COMPLETION_TIME']; ?>
</td>
</tr>
<?php endfor; endif; ?>
</table-->
</form>
</body>
</html>        

