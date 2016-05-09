<?php /* Smarty version 2.6.7, created on 2006-10-31 13:54:19
         compiled from matri_followup.htm */ ?>
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
  function openBrWindow(theURL,winName,features)
  {
        var status = confirm("Do you really want to keep this user On Hold?");
        if (status)
                win = window.open(theURL,winName,features);
  }
function confirm1(theURL,winName,features)
{
        var status1=confirm("Do you really want to enter the response time of user?");
        if(status1)
                win=window.open(theURL,winName,features);
}
function openCalendar(params, form, field, type)
{
    window.open("../crm/calendar1.php?", "calendar", "width=400,height=200,status=yes");
    dateField = eval("document." + form + "." + field);
    dateType = type;
}
function MM_openBrWindow(theURL,winName,features)
{
             window.open(theURL,winName,features);
}
function completed()
{
	var status2=confirm("Profile is really completed?");
	if(status2==true)
	{
		alert("Profile is added to verify from the team leader");
		return true;
	}
	else
	{
		return false;
	}
	document.write(status2);
}
</script>
</head>
<body>
 <table width="761" border="0" cellspacing="0" cellpadding="2" align="CENTER">
<tr>
<td><img src="../profile/images/logo_1.gif" width="192" height="65"></td>
</tr>
<tr>
<td class=bigwhite bgcolor="6BB97B" align=center><b><font size =3 color=white>FOLLOW UP</font></b></td>
</tr></table>
<table width="761" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead" width="100%"><td width =50% align="center" colspan=14><a href="../jsadmin/mainpage.php?cid=<?php echo $this->_tpl_vars['checksum']; ?>
">MAIN PAGE</a></td><td width=50%><center><a href="show_matri_allot.php?checksum=<?php echo $this->_tpl_vars['checksum']; ?>
"><b>BACK</b></a></center></td></tr>
<table>
<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
<tr class="formhead">
       <td colspan=100% align="center">FOLLOWUPS FOR USER <?php echo $this->_tpl_vars['username']; ?>
</td>
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
<tr class=label>
<td align=center>S. No: &nbsp;<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['SNO']; ?>
</td>
<td align=center>User Name: <font color=red><b><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
</b></font></td>
<td align=center>E-Mail: <b><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['EMAIL']; ?>
</b><br>Mob: <b><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PHONE_MOB']; ?>
</b> Res:<b> <?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PHONE_RES']; ?>
</b></td> <td align=center>Entry Date: <?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['ENTRY_DT']; ?>
<br>Allocation time: <?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['ALLOT_TIME']; ?>
</td>
<td align=center><a href="matri_followup.php?profileid=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&complete=1&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
" alt="profile will be added to verify from the team leader">Completed??</a><?php if (! $this->_tpl_vars['hold']): ?><br><a href="#" onclick="openBrWindow('matri_onhold.php?username=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
&profileid=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
','','width=600,height=150,scrollbars=yes'); return false;">Keep this user On Hold</a><?php endif; ?></td> </tr>
<tr class=label>
<td align=center rowspan=2 width=5>S. No.</td>
<td align=center colspan=2 width=140>FOLLOW UP TIME</td>
<td align=center rowspan=2>RECEIVING TIME</td>
<td align=center rowspan=2>DOWNLOAD FILE</td>
</tr>
<tr class=label>
<td align=center width=100>By E-Mail</td><td align=center width=40>By Phone</td>
</tr>
<?php unset($this->_sections['sec2']);
$this->_sections['sec2']['name'] = 'sec2';
$this->_sections['sec2']['loop'] = is_array($_loop=$this->_tpl_vars['y']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 if ($this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]): ?>
<form method=post action="matri_downfile.php?id=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['PROFILEID']; ?>
&cuts=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['CUTS']; ?>
&username=<?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']]['USERNAME']; ?>
&checksum=<?php echo $this->_tpl_vars['checksum']; ?>
">
<tr class=fieldsnew>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['SNO']; ?>
</td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['FOLLOWUP_TIME']; ?>
</td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['PFOLLOWUP_TIME']; ?>
</td>
<td align=center><?php echo $this->_tpl_vars['followup'][$this->_sections['sec1']['index']][$this->_sections['sec2']['index']]['RCV_TIME']; ?>
</td>
<td align=center >&nbsp;<input type="submit" name="download" value="DOWNLOAD"></td>
</tr>
</form>
<?php endif;  endfor; endif;  endfor; endif;  if ($this->_tpl_vars['b'] == 1): ?>
<tr class=fieldsnew align=center>
<td colspan=100%><font color=red size=2><b><center><?php echo $this->_tpl_vars['fmsg']; ?>
</center></b></font></td>
</tr>
<?php endif; ?>
</table>
</body>
</html>