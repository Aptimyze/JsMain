<?php /* Smarty version 2.6.7, created on 2006-10-31 13:54:26
         compiled from show_matri_allot.htm */ ?>
<html>
<head>
   <title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../mis/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<LINK rel="stylesheet" href="calendar.css" type="text/css">
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>
<script language="javascript">
  function openBrWindow(theURL,winName,features)
  {
        var status = confirm("Do you really want to keep this user On Hold?");
        if (status)
                win = window.open(theURL,winName,features);
  }
/*function confirm1()
{
	var status1=confirm("Do you really want to add the response time of user?");
	if(status1)
		file.open("show_matri_allot.php?profileid=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&followup_time=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['FOLLOWUP_TIME']; ?>
");
}
function confirm2(theURL,winName,features)
{
	var status2=confirm("Do you really want to add the followup time?");
	if(status2)
		win2=window.open(theURL,winName,features);
}*/
function alert1()
{
	alert("Please fill the response time of user.");
}
/*function alert5()
{
	alert("Follow Up time is added.");
}
function alert4()
{
	alert("Response time is added.");
}*/
function alert2()
{
	alert("Warning:Time should not be more than current time!!");
}
function alert3()
{
	alert("Please fill the followup time");
}
function openCalendar(params, form, field, type) 
{
    window.open("../crm/calendar.php?" + params, "calendar", "width=400,height=240,status=yes");
    dateField = eval("document." + form + "." + field);
    dateType = type;
}
function MM_openBrWindow(theURL,winName,features)
{
	     window.open(theURL,winName,features);
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
</tr></table>
<table width="761" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead" width="100%"><td align="center" colspan=14><a href="../jsadmin/mainpage.php?cid=<?php echo $this->_tpl_vars['checksum']; ?>
">MAIN PAGE</a></td></tr>
</table>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width=100%>
<tr class=label >
<td align=center>&nbsp;Executive: <font color=red><b><?php echo $this->_tpl_vars['allotted_to']; ?>
</b></font></td>
<td align=center>&nbsp;Profiles On progress: <font color=red><?php echo $this->_tpl_vars['cnt_onprogress']; ?>
</font></td>
<td align=center>&nbsp;Profiles Follow Up: <font color=red><?php echo $this->_tpl_vars['cnt_followup']; ?>
</font></td>
<td align=center>&nbsp;Profiles On Hold: <font color=red><?php echo $this->_tpl_vars['cnt_onhold']; ?>
</font></td>
<td align=center>&nbsp;Profiles completed: <font color=red><?php echo $this->_tpl_vars['cnt_completed']; ?>
</font></td>
</tr>
</table>
<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
<tr class="formhead">
<td colspan=100% align="center">PROFILES ON PROGRESS</td>
</tr></table>
<table width=100% align=center border=0 cellspacing=4 cellpadding=5>
<tr class=label>
<td align=center width=5 rowspan=2>&nbsp;S. No.</td>
<td align=center rowspan=2>&nbsp;USER NAME</td>
<td align=center width=180 colspan=3>&nbsp;CONTACT DETAILS</td>
<td align=center rowspan=2>&nbsp;ENTRY DATE</td>
<td align=center rowspan=2>&nbsp;ALLOCATION TIME</td>
<td align=center rowspan=2>&nbsp;SCHEDULED TIME</td>
<td align=center rowspan=2>&nbsp;UPLOAD FILE</td>
</tr>
<tr class=label><td align=center width=100>E-Mail</td><td align=center width=40>Mob</td><td align=center width=40>Res</td>
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
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['USERNAME']; ?>
</td>
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['EMAIL']; ?>
</td>
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['PHONE_MOB']; ?>
</td>
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['PHONE_RES']; ?>
</td>
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['ALLOT_TIME']; ?>
</td>
<td align=center >&nbsp;<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['SCHEDULED_TIME']; ?>
</td>
<td align=center >&nbsp;<input type="button" name="upload" value="UPLOAD" onclick="return MM_openBrWindow('matriprofile_attach_status.php?id=<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['PROFILEID']; ?>
&username=<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['USERNAME']; ?>
&status=<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['STATUS']; ?>
&mid=<?php echo $this->_tpl_vars['allotted'][$this->_sections['sec']['index']]['ID']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
','AttachWindow','width=600,height=400 resizable=1')";></td>
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
<tr class=label>
<td align=center rowspan=2 width=5>S. No</td>
<td align=center rowspan=2>User Name</td>
<td align=center colspan=3 width=120>Contact Details</td>
<td align=center colspan=2 width=120>Follow Up time</td>
<td align=center rowspan=2 width=60>Receiving time</td>
<td align=center rowspan=2>Upload file</td>
</tr>
<tr class=label>
<td align=center width=40>E-Mail</td>
<td align=center width=40>Res</td>
<td align=center width=40>Mob</td>
<td align=center width=40>By Email</td>
<td align=center width=80>By Phone</td>
</tr>
<?php unset($this->_sections['sec1']);
$this->_sections['sec1']['name'] = 'sec1';
$this->_sections['sec1']['loop'] = is_array($_loop=$this->_tpl_vars['followup']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr class=fieldsnew>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
</td>
<td align=center><a href="matri_followup.php?profileid=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&username=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
"><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
</a></td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['EMAIL']; ?>
</td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PHONE_RES']; ?>
</td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PHONE_MOB']; ?>
</td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['FOLLOWUP_TIME']; ?>
</td>
<td align=center>
<form name=mod1_form_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
 method=post action="show_matri_allot.php?profileid=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&username=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
&followup_time=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['FOLLOWUP_TIME']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
">
<script type="text/javascript" src="calendar.js"></script>
<input type=text name="pfollow_time" value="<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PFOLLOWUP_TIME']; ?>
" size=16 maxlength=20 class=textfield tabindex="1" id="input1_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
"><input type=hidden name=pfollow value=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
>
<!--script language="javascript">
document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1;\', \'insertForm\', \'input1\', \'datetime\')"><img class="calendar" src="../crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
</script-->
<script type="text/javascript">
<!--
document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\',\'mod1_form_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
\', \'input1_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
\', \'datetime\')"><img class="calendar" src="../crm/img/b_calendar.png" alt="Calendar"  border=0></a>');
//-->
</script>
<br>
<input type=submit name=add_call value=ADD>
</form>
</td>
<td align=center>
<form name=mod2_form_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
 method=post action="show_matri_allot.php?profileid=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&username=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
&followup_time=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['FOLLOWUP_TIME']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
">
<script type="text/javascript" src="calendar.js"></script>
<input type=text name="rcv_time" value="<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['RCV_TIME']; ?>
" size=16 maxlength=20 class=textfield tabindex="1" id="input2_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
"><input type=hidden name=rcv value=$followup[sec1].PROFILEID`>
<script type="text/javascript">
<!--
document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\',\'mod2_form_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
\', \'input2_<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
\', \'datetime\')"><img class="calendar" src="../crm/img/b_calendar.png" alt="Calendar"  border=0></a>');
//-->
</script>
<!--script language="javascript">
document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1;\', \'mod1_form\', \'input2\', \'datetime\')"><img class="calendar" src="../crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
</script--><br>
<input type=submit name=add_rcv value=ADD></form></td>
<td align=center >&nbsp;<input type="button" name="upload" value="UPLOAD" onclick="return MM_openBrWindow('matriprofile_attach_status.php?id=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&username=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
&status=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['STATUS']; ?>
&save=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['SAVE']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
','AttachWindow','width=600,height=400 resizable=1')";></td>
</tr>
<?php endfor; endif;  if ($this->_tpl_vars['b'] == 1): ?>
<tr class=fieldsnew align=center>
<td colspan=100%><font color=red size=2><b><center><?php echo $this->_tpl_vars['fmsg']; ?>
</center></b></font></td>
</tr>
<?php endif; ?>
<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
<tr class="formhead">
        <td colspan=100% align="center">ON HOLD PROFILES</td>
        </tr>
<tr class=label>
        <td align=center rowspan=2 width=5>&nbsp;S. No.</td>
        <td align=center rowspan=2>&nbsp;USER NAME</td>
        <td align=center rowspan=2>&nbsp;ENTRY DATE</td>
        <td align=center colspan=3 width=140>&nbsp;CONTACTS</td>
        <td align=center rowspan=2>&nbsp;ONHOLD TIME</td>
        <td align=center rowspan=2>&nbsp;REASON</td>
        <td align=center rowspan=2>&nbsp;UPLOAD</td>
</tr>
<tr class=label>
<td align=center width=60>EMail</td>
<td align=center width=40>Mob</td>
<td align=center width=40>Res</td>
</tr>
<?php unset($this->_sections['sec']);
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
        <td align=center ><a href="matri_followup.php?profileid=<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['PROFILEID']; ?>
&onhold=1&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
"><?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['USERNAME']; ?>
</a></td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['ENTRY_DT']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['EMAIL']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['PHONE_MOB']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['PHONE_RES']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['ONHOLD_TIME']; ?>
</td>
        <td align=center >&nbsp;<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['REASON']; ?>
</td>
<td align=center >&nbsp;<input type="button" name="upload" value="UPLOAD" onclick="return MM_openBrWindow('matriprofile_attach_status.php?id=<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['PROFILEID']; ?>
&username=<?php echo $this->_tpl_vars['onhold'][$this->_sections['sec']['index']]['USERNAME']; ?>
&status=H&save=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['SAVE']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
','AttachWindow','width=600,height=400 resizable=1')";></td>
</tr>
<?php endfor; endif;  if ($this->_tpl_vars['a'] == 1): ?>
<tr class=fieldsnew align=center>
<td colspan=100%><font color=red size=2><b><center><?php echo $this->_tpl_vars['hmsg']; ?>
</center></b></font></td>
</tr>
<?php endif; ?>
</table>
<?php if ($this->_tpl_vars['c'] == 3): ?><script language="javascript">alert3();</script><?php endif;  if ($this->_tpl_vars['c'] == 1): ?><script language="javascript">alert1();</script><?php endif;  if ($this->_tpl_vars['c'] == 2): ?><script language="javascript">alert2();</script><?php endif;  if ($this->_tpl_vars['rcv'] == 1): ?><script language="javascript">alert4();</script><?php endif;  if ($this->_tpl_vars['follow'] == 1): ?><script language="javascript">alert5();</script><?php endif; ?>
</body>
</html>
