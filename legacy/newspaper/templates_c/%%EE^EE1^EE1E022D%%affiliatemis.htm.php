<?php /* Smarty version 2.6.6, created on 2008-09-11 02:36:43
         compiled from affiliatemis.htm */ ?>
<html>
<head>
<title>NEWSPAPER RECORDS MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="jeevansathi.css" type="text/css">
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>                                                                                                                   </head>
<?php echo $this->_tpl_vars['HEAD']; ?>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php if ($this->_tpl_vars['mflag'] == '1'): ?>
<br>
<table width=100%  ALIGN="CENTER" >
<tr class="formhead">
<td width=25% align=center><font><b>Welcome : <?php echo $this->_tpl_vars['username']; ?>
</b></font></td>
<td align=right>
<a href="mainpage.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&name=<?php echo $this->_tpl_vars['name']; ?>
&mode=<?php echo $this->_tpl_vars['mode']; ?>
">Main Page</a>
</td>
<td width=15% align='CENTER'><a href="logout.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
">Logout</a></td>
</tr>
</table>
<table width=100% align=center>
<tr class=formhead align=center cellspacing=4 cellpadding=2>
<td align=center>
Year &nbsp;:&nbsp;<?php echo $this->_tpl_vars['myear']; ?>

</td>
<?php if ($this->_tpl_vars['srcgrp'] == '1'): ?>
<td width=15%>
<span class=gray align=left>
N: NEWSPAPER A:AFFILIATE
</span>
</td>
<?php endif; ?>
</tr>
</table>
<br>
<table width="100%" align="center" border=0 cellspacing=1 cellpadding=5>
<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['userarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['user']['show'] = true;
$this->_sections['user']['max'] = $this->_sections['user']['loop'];
$this->_sections['user']['step'] = 1;
$this->_sections['user']['start'] = $this->_sections['user']['step'] > 0 ? 0 : $this->_sections['user']['loop']-1;
if ($this->_sections['user']['show']) {
    $this->_sections['user']['total'] = $this->_sections['user']['loop'];
    if ($this->_sections['user']['total'] == 0)
        $this->_sections['user']['show'] = false;
} else
    $this->_sections['user']['total'] = 0;
if ($this->_sections['user']['show']):

            for ($this->_sections['user']['index'] = $this->_sections['user']['start'], $this->_sections['user']['iteration'] = 1;
                 $this->_sections['user']['iteration'] <= $this->_sections['user']['total'];
                 $this->_sections['user']['index'] += $this->_sections['user']['step'], $this->_sections['user']['iteration']++):
$this->_sections['user']['rownum'] = $this->_sections['user']['iteration'];
$this->_sections['user']['index_prev'] = $this->_sections['user']['index'] - $this->_sections['user']['step'];
$this->_sections['user']['index_next'] = $this->_sections['user']['index'] + $this->_sections['user']['step'];
$this->_sections['user']['first']      = ($this->_sections['user']['iteration'] == 1);
$this->_sections['user']['last']       = ($this->_sections['user']['iteration'] == $this->_sections['user']['total']);
?>
<table width=100% align=center cellspacing=4 cellpadding=5>
<tr class=formhead align=left>
<td colspan=14><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo $this->_tpl_vars['userarr'][$this->_sections['user']['index']]; ?>
</td>
</tr>
<tr class=label>
<td width=8% align=center rowspan=1><span class=black>MONTH</span></td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td width=6% align=center><?php echo $this->_tpl_vars['mmarr'][$this->_sections['mon']['index']]; ?>
</td>
<?php endfor; endif; ?>
<td width=6% align=center>Total</td>
</tr>
<?php unset($this->_sections['mode']);
$this->_sections['mode']['name'] = 'mode';
$this->_sections['mode']['loop'] = is_array($_loop=$this->_tpl_vars['srcarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mode']['show'] = true;
$this->_sections['mode']['max'] = $this->_sections['mode']['loop'];
$this->_sections['mode']['step'] = 1;
$this->_sections['mode']['start'] = $this->_sections['mode']['step'] > 0 ? 0 : $this->_sections['mode']['loop']-1;
if ($this->_sections['mode']['show']) {
    $this->_sections['mode']['total'] = $this->_sections['mode']['loop'];
    if ($this->_sections['mode']['total'] == 0)
        $this->_sections['mode']['show'] = false;
} else
    $this->_sections['mode']['total'] = 0;
if ($this->_sections['mode']['show']):

            for ($this->_sections['mode']['index'] = $this->_sections['mode']['start'], $this->_sections['mode']['iteration'] = 1;
                 $this->_sections['mode']['iteration'] <= $this->_sections['mode']['total'];
                 $this->_sections['mode']['index'] += $this->_sections['mode']['step'], $this->_sections['mode']['iteration']++):
$this->_sections['mode']['rownum'] = $this->_sections['mode']['iteration'];
$this->_sections['mode']['index_prev'] = $this->_sections['mode']['index'] - $this->_sections['mode']['step'];
$this->_sections['mode']['index_next'] = $this->_sections['mode']['index'] + $this->_sections['mode']['step'];
$this->_sections['mode']['first']      = ($this->_sections['mode']['iteration'] == 1);
$this->_sections['mode']['last']       = ($this->_sections['mode']['iteration'] == $this->_sections['mode']['total']);
?>
<tr class=fieldsnew>
<td align=center class=label><?php echo $this->_tpl_vars['srcarr'][$this->_sections['mode']['index']]; ?>
</td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center rowspan=1>&nbsp;<span><?php echo $this->_tpl_vars['mmcount2'][$this->_sections['user']['index']][$this->_sections['mode']['index']][$this->_sections['mon']['index']]; ?>
</span></td>                                          <?php endfor; endif; ?>
<td class=label align=center>&nbsp;<span><?php echo $this->_tpl_vars['totmmcount2'][$this->_sections['user']['index']][$this->_sections['mode']['index']]; ?>
</span></td>
</tr>
<?php endfor; endif; ?>
<tr class=label>
<td align=center><b>Total</b></td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center>&nbsp;<?php echo $this->_tpl_vars['totmodecount2'][$this->_sections['user']['index']][$this->_sections['mon']['index']]; ?>
</td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<?php echo $this->_tpl_vars['total'][$this->_sections['user']['index']]; ?>
</td>
</tr>
<!--tr>
<td align=center class=label>Registered</td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center class=fieldsnew><span>&nbsp;<?php echo $this->_tpl_vars['reg_count'][$this->_sections['user']['index']][$this->_sections['mon']['index']]; ?>
</span></td>                       <?php endfor; endif; ?>
<td align=center class=label><span>&nbsp;<?php echo $this->_tpl_vars['tot_reg_count'][$this->_sections['user']['index']]; ?>
</span></td>
</tr>
<tr class=fieldsnew>
<td align=center class=label width=9% rowspan=1>Member</td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center rowspan=1 width=7%>&nbsp;<span><?php echo $this->_tpl_vars['mem_count'][$this->_sections['user']['index']][1][$this->_sections['mon']['index']]; ?>
</span></td>                             <?php endfor; endif; ?>
<td class=label align=center>&nbsp;<span width=6%><?php echo $this->_tpl_vars['tot_mem_count'][$this->_sections['user']['index']][1]; ?>
</span></td>                   </tr-->
</table>
<br>
<?php endfor; endif; ?>
<table width=100% align=center>
<tr class=label>
<td align=center width=9% rowspan=1><span><br><b>Grand Total</b><br></span></td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center class=label width=7%><font color=blue><b>&nbsp;<?php echo $this->_tpl_vars['totmmcount'][$this->_sections['mon']['index']]; ?>
 </b></font></td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<font color=blue><b><?php echo $this->_tpl_vars['grandtotal']; ?>
</b></font></td>
</tr>
<!--tr class=label>
<td align=center width=9% rowspan=1><span><br><b>Grand Total Of Registered members</b><br></span></td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center class=label width=7%><font color=blue><a href="membernames.php?paid=0&myear=<?php echo $this->_tpl_vars['myear']; ?>
&$srcgrp=<?php echo $this->_tpl_vars['srcgrp']; ?>
&month=<?php echo $this->_tpl_vars['montharr'][$this->_sections['mon']['index']]; ?>
"><b>&nbsp;<?php echo $this->_tpl_vars['grandtot_reg_count'][$this->_sections['mon']['index']]; ?>
 </b></a></font></td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<font color=blue><b><?php echo $this->_tpl_vars['grandtotal_reg']; ?>
</b></font></td>
</tr>
<tr class=label>
<td align=center width=9% rowspan=1><span><br><b>Grand Total Of Paid  members</b><br></span></td>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<td align=center class=label width=7%><font color=blue><a href="membernames.php?paid=1&myear=<?php echo $this->_tpl_vars['myear']; ?>
&month=<?php echo $this->_tpl_vars['montharr'][$this->_sections['mon']['index']]; ?>
"><b>&nbsp;<?php echo $this->_tpl_vars['grandtot_mem_count'][1][$this->_sections['mon']['index']]; ?>
 </b></a></font></td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<font color=blue><b><?php echo $this->_tpl_vars['grandtotal_mem'][1]; ?>
</b></font></td>
</tr-->

</table>
</table>

<?php elseif ($this->_tpl_vars['dflag'] == '1'): ?>
<br>
<table width=100% cellspacing="1" cellpadding='3' ALIGN="CENTER" >
<tr class="formhead">
<td width=25% align=center><font><b>Welcome : <?php echo $this->_tpl_vars['username']; ?>
</b></font></td>
<td align=right>
<a href="mainpage.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&name=<?php echo $this->_tpl_vars['name']; ?>
&mode=<?php echo $this->_tpl_vars['mode']; ?>
">Main Page</a>
</td>
<td width=15% align='CENTER'><a href="logout.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
">Logout</a></td>
</tr>
</table>

<table width="100%" align="center"  border=0>
<tr class=formhead align=center>
<td align=center height=20>
<?php echo $this->_tpl_vars['mmarr'][$this->_tpl_vars['month']]; ?>

&nbsp;<?php echo $this->_tpl_vars['dyear']; ?>

</td>
<?php if ($this->_tpl_vars['srcgrp'] == '1'): ?>
<td width=15%>
<span class=gray align=left>
N: NEWSPAPER A:AFFILIATE
</span>
</td>
<?php endif; ?>
</tr>
</table>

<table width="100%" align="center" border=0 cellspacing=1 cellpadding=5>
<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['userarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['user']['show'] = true;
$this->_sections['user']['max'] = $this->_sections['user']['loop'];
$this->_sections['user']['step'] = 1;
$this->_sections['user']['start'] = $this->_sections['user']['step'] > 0 ? 0 : $this->_sections['user']['loop']-1;
if ($this->_sections['user']['show']) {
    $this->_sections['user']['total'] = $this->_sections['user']['loop'];
    if ($this->_sections['user']['total'] == 0)
        $this->_sections['user']['show'] = false;
} else
    $this->_sections['user']['total'] = 0;
if ($this->_sections['user']['show']):

            for ($this->_sections['user']['index'] = $this->_sections['user']['start'], $this->_sections['user']['iteration'] = 1;
                 $this->_sections['user']['iteration'] <= $this->_sections['user']['total'];
                 $this->_sections['user']['index'] += $this->_sections['user']['step'], $this->_sections['user']['iteration']++):
$this->_sections['user']['rownum'] = $this->_sections['user']['iteration'];
$this->_sections['user']['index_prev'] = $this->_sections['user']['index'] - $this->_sections['user']['step'];
$this->_sections['user']['index_next'] = $this->_sections['user']['index'] + $this->_sections['user']['step'];
$this->_sections['user']['first']      = ($this->_sections['user']['iteration'] == 1);
$this->_sections['user']['last']       = ($this->_sections['user']['iteration'] == $this->_sections['user']['total']);
?>
<table width=100% align=center cellspacing=4 cellpadding=5>
<tr class=formhead align=left>
<td colspan=33><b>&nbsp;&nbsp;<?php echo $this->_tpl_vars['userarr'][$this->_sections['user']['index']]; ?>
</b></td>
</tr>
<tr class=label>
<td width=5% align=center rowspan=1><span class=black>MODE </span></td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center width=3%><?php echo $this->_tpl_vars['ddarr'][$this->_sections['dd']['index']]; ?>
</td>
<?php endfor; endif; ?>
<td width=5% align=center>Total</td>
</tr>

<?php unset($this->_sections['mode']);
$this->_sections['mode']['name'] = 'mode';
$this->_sections['mode']['loop'] = is_array($_loop=$this->_tpl_vars['srcarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mode']['show'] = true;
$this->_sections['mode']['max'] = $this->_sections['mode']['loop'];
$this->_sections['mode']['step'] = 1;
$this->_sections['mode']['start'] = $this->_sections['mode']['step'] > 0 ? 0 : $this->_sections['mode']['loop']-1;
if ($this->_sections['mode']['show']) {
    $this->_sections['mode']['total'] = $this->_sections['mode']['loop'];
    if ($this->_sections['mode']['total'] == 0)
        $this->_sections['mode']['show'] = false;
} else
    $this->_sections['mode']['total'] = 0;
if ($this->_sections['mode']['show']):

            for ($this->_sections['mode']['index'] = $this->_sections['mode']['start'], $this->_sections['mode']['iteration'] = 1;
                 $this->_sections['mode']['iteration'] <= $this->_sections['mode']['total'];
                 $this->_sections['mode']['index'] += $this->_sections['mode']['step'], $this->_sections['mode']['iteration']++):
$this->_sections['mode']['rownum'] = $this->_sections['mode']['iteration'];
$this->_sections['mode']['index_prev'] = $this->_sections['mode']['index'] - $this->_sections['mode']['step'];
$this->_sections['mode']['index_next'] = $this->_sections['mode']['index'] + $this->_sections['mode']['step'];
$this->_sections['mode']['first']      = ($this->_sections['mode']['iteration'] == 1);
$this->_sections['mode']['last']       = ($this->_sections['mode']['iteration'] == $this->_sections['mode']['total']);
?>
<tr class=fieldsnew>
<td align=center class=label><?php echo $this->_tpl_vars['srcarr'][$this->_sections['mode']['index']]; ?>
</td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center rowspan=1>&nbsp;<span><?php echo $this->_tpl_vars['mmcount2'][$this->_sections['user']['index']][$this->_sections['mode']['index']][$this->_sections['dd']['index']]; ?>
</span></td>                                	     <?php endfor; endif; ?>
<td class=label align=center>&nbsp;<span><?php echo $this->_tpl_vars['totmmcount2'][$this->_sections['user']['index']][$this->_sections['mode']['index']]; ?>
</span></td>
</tr>
<?php endfor; endif; ?>
<tr class=label>
<td align=center><b>Total</b></td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center>&nbsp;<?php echo $this->_tpl_vars['totmodecount2'][$this->_sections['user']['index']][$this->_sections['dd']['index']]; ?>
</td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<?php echo $this->_tpl_vars['total'][$this->_sections['user']['index']]; ?>
</td>
</tr>
<!--tr>
<td align=center class=label>Registered</td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center class=fieldsnew><span>&nbsp;<?php echo $this->_tpl_vars['reg_count'][$this->_sections['user']['index']][$this->_sections['dd']['index']]; ?>
</span></td>
<?php endfor; endif; ?>
<td align=center class=label><span>&nbsp;<?php echo $this->_tpl_vars['tot_reg_count'][$this->_sections['user']['index']]; ?>
</span></td>
</tr-->
<!--tr class=fieldsnew>
<td align=center class=label width=9% rowspan=1>Member</td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center rowspan=1 width=7%>&nbsp;<span><?php echo $this->_tpl_vars['mem_count'][$this->_sections['user']['index']][1][$this->_sections['dd']['index']]; ?>
</span></td>                           <?php endfor; endif; ?>
<td class=label align=center>&nbsp;<span width=6%><?php echo $this->_tpl_vars['tot_mem_count'][$this->_sections['user']['index']][1]; ?>
</span></td>        </tr-->
<br>
<?php endfor; endif; ?>
<tr></tr>
<tr></tr>
<tr class=label>
<td align=center width=5%><br><b>Grand Total</b></td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center width=3%><font color=blue><?php echo $this->_tpl_vars['totmmcount'][$this->_sections['dd']['index']]; ?>
</font></td>
<?php endfor; endif; ?>
<td align=center width=5%><font color=blue><?php echo $this->_tpl_vars['grandtotal']; ?>
</font></td>
</font>
</tr>
<!--tr class=label>
<td align=center width=9% rowspan=1><span><br><b>Grand Total Of Registered members</b><br></span></td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center class=class4 width=7%><font color=blue><b><a href="membernames.php?paid=0&dyear=<?php echo $this->_tpl_vars['dyear']; ?>
&dmonth=<?php echo $this->_tpl_vars['dmonth']; ?>
&day=<?php echo $this->_tpl_vars['ddarr'][$this->_sections['dd']['index']]; ?>
&$srcgrp=<?php echo $this->_tpl_vars['srcgrp']; ?>
">&nbsp;<?php echo $this->_tpl_vars['grandtot_reg_count'][$this->_sections['dd']['index']]; ?>
 </a></b></font></td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<font color=blue><b><?php echo $this->_tpl_vars['grandtotal_reg']; ?>
</b></font></td>
</tr>
<tr class=label>
<td align=center width=9% rowspan=1><span><br><b>Grand Total Of Paid  members</b><br></span></td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center class=class4 width=7%><font color=blue><b><a href="membernames.php?paid=1&dyear=<?php echo $this->_tpl_vars['dyear']; ?>
&dmonth=<?php echo $this->_tpl_vars['dmonth']; ?>
&day=<?php echo $this->_tpl_vars['ddarr'][$this->_sections['dd']['index']]; ?>
&$srcgrp=<?php echo $this->_tpl_vars['srcgrp']; ?>
">&nbsp;<?php echo $this->_tpl_vars['grandtot_mem_count'][1][$this->_sections['dd']['index']]; ?>
 </a></b></font></td>
<?php endfor; endif; ?>
<td align=center>&nbsp;<font color=blue><b><?php echo $this->_tpl_vars['grandtotal_mem'][1]; ?>
</b></font></td>
</tr-->



<!--<tr class=label>
<td align=center width=9% rowspan=1><span><br><b>Grand Total Of Paid  members</b><br></span></td>
<?php unset($this->_sections['dd']);
$this->_sections['dd']['name'] = 'dd';
$this->_sections['dd']['loop'] = is_array($_loop=$this->_tpl_vars['ddarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dd']['show'] = true;
$this->_sections['dd']['max'] = $this->_sections['dd']['loop'];
$this->_sections['dd']['step'] = 1;
$this->_sections['dd']['start'] = $this->_sections['dd']['step'] > 0 ? 0 : $this->_sections['dd']['loop']-1;
if ($this->_sections['dd']['show']) {
    $this->_sections['dd']['total'] = $this->_sections['dd']['loop'];
    if ($this->_sections['dd']['total'] == 0)
        $this->_sections['dd']['show'] = false;
} else
    $this->_sections['dd']['total'] = 0;
if ($this->_sections['dd']['show']):

            for ($this->_sections['dd']['index'] = $this->_sections['dd']['start'], $this->_sections['dd']['iteration'] = 1;
                 $this->_sections['dd']['iteration'] <= $this->_sections['dd']['total'];
                 $this->_sections['dd']['index'] += $this->_sections['dd']['step'], $this->_sections['dd']['iteration']++):
$this->_sections['dd']['rownum'] = $this->_sections['dd']['iteration'];
$this->_sections['dd']['index_prev'] = $this->_sections['dd']['index'] - $this->_sections['dd']['step'];
$this->_sections['dd']['index_next'] = $this->_sections['dd']['index'] + $this->_sections['dd']['step'];
$this->_sections['dd']['first']      = ($this->_sections['dd']['iteration'] == 1);
$this->_sections['dd']['last']       = ($this->_sections['dd']['iteration'] == $this->_sections['dd']['total']);
?>
<td align=center class=label width=7%><font color=blue><b>&nbsp;<?php echo $this->_tpl_vars['mem_totcount'][$this->_sections['dd']['index']]; ?>
 </b></font></td>                  <?php endfor; endif; ?>
<td align=center>&nbsp;<font color=blue><b><?php echo $this->_tpl_vars['mem_grandtotal']; ?>
</b></font></td>
</tr> -->

</table>
</table>
<?php else: ?>
<br>
<table width=560 cellspacing="1" cellpadding='2' ALIGN="CENTER" >
<tr class="formhead">
<td width=30%><font><b>Welcome : <?php echo $this->_tpl_vars['username']; ?>
</b></font></td>
<td align="right"><a href="mainpage.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
&name=<?php echo $this->_tpl_vars['name']; ?>
&mode=<?php echo $this->_tpl_vars['mode']; ?>
">Main Page</a></td>
<td width=20%  align="center"><a href="logout.php?cid=<?php echo $this->_tpl_vars['cid']; ?>
">Logout</a></td>
</tr>
</table>
<br>
<form action=affiliatemis.php method=POST>
<table width=560 cellspacing=1 cellpadding=2 align=center>
<tr class=formhead>
<td height=23 colspan=2 align=center>SELECT THE CRITERIA </td>
</tr>
<tr>
<td height="23" colspan="2" class="formhead" align="center">&nbsp;</td>
</tr>
<tr>
<td align=center class=label width=30%>CATEGORY OF MIS</td>
<td class=fieldsnew width=70%>
<select name="sourcegrp" class=Textbox>
<option value="all">ALL</option>
<option value="NPPR">NEWSPAPER</option>
<option value="AFFL">AFFILIATE</option>
</select>
</td>
</tr>
<tr>
<td align=left class=label width=30%><input type=radio name=criteria value='M' checked>MONTHWISE</td>
<td class=fieldsnew width=70%>
<select name="myear" class=Textbox>
<?php unset($this->_sections['yr']);
$this->_sections['yr']['name'] = 'yr';
$this->_sections['yr']['loop'] = is_array($_loop=$this->_tpl_vars['yyarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['yr']['show'] = true;
$this->_sections['yr']['max'] = $this->_sections['yr']['loop'];
$this->_sections['yr']['step'] = 1;
$this->_sections['yr']['start'] = $this->_sections['yr']['step'] > 0 ? 0 : $this->_sections['yr']['loop']-1;
if ($this->_sections['yr']['show']) {
    $this->_sections['yr']['total'] = $this->_sections['yr']['loop'];
    if ($this->_sections['yr']['total'] == 0)
        $this->_sections['yr']['show'] = false;
} else
    $this->_sections['yr']['total'] = 0;
if ($this->_sections['yr']['show']):

            for ($this->_sections['yr']['index'] = $this->_sections['yr']['start'], $this->_sections['yr']['iteration'] = 1;
                 $this->_sections['yr']['iteration'] <= $this->_sections['yr']['total'];
                 $this->_sections['yr']['index'] += $this->_sections['yr']['step'], $this->_sections['yr']['iteration']++):
$this->_sections['yr']['rownum'] = $this->_sections['yr']['iteration'];
$this->_sections['yr']['index_prev'] = $this->_sections['yr']['index'] - $this->_sections['yr']['step'];
$this->_sections['yr']['index_next'] = $this->_sections['yr']['index'] + $this->_sections['yr']['step'];
$this->_sections['yr']['first']      = ($this->_sections['yr']['iteration'] == 1);
$this->_sections['yr']['last']       = ($this->_sections['yr']['iteration'] == $this->_sections['yr']['total']);
?>
<option value=<?php echo $this->_tpl_vars['yyarr'][$this->_sections['yr']['index']]; ?>
><?php echo $this->_tpl_vars['yyarr'][$this->_sections['yr']['index']]; ?>
</option>
<?php endfor; endif; ?>
</select>&nbsp;&nbsp;&nbsp;Year
</td>
<td>
</tr>
<tr >
<td align=left class=label><input name=criteria type=radio value='D'>DAYWISE </td>
<td class=fieldsnew>
<select name="dmonth" class=Textbox>
<?php unset($this->_sections['mon']);
$this->_sections['mon']['name'] = 'mon';
$this->_sections['mon']['loop'] = is_array($_loop=$this->_tpl_vars['mmarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mon']['show'] = true;
$this->_sections['mon']['max'] = $this->_sections['mon']['loop'];
$this->_sections['mon']['step'] = 1;
$this->_sections['mon']['start'] = $this->_sections['mon']['step'] > 0 ? 0 : $this->_sections['mon']['loop']-1;
if ($this->_sections['mon']['show']) {
    $this->_sections['mon']['total'] = $this->_sections['mon']['loop'];
    if ($this->_sections['mon']['total'] == 0)
        $this->_sections['mon']['show'] = false;
} else
    $this->_sections['mon']['total'] = 0;
if ($this->_sections['mon']['show']):

            for ($this->_sections['mon']['index'] = $this->_sections['mon']['start'], $this->_sections['mon']['iteration'] = 1;
                 $this->_sections['mon']['iteration'] <= $this->_sections['mon']['total'];
                 $this->_sections['mon']['index'] += $this->_sections['mon']['step'], $this->_sections['mon']['iteration']++):
$this->_sections['mon']['rownum'] = $this->_sections['mon']['iteration'];
$this->_sections['mon']['index_prev'] = $this->_sections['mon']['index'] - $this->_sections['mon']['step'];
$this->_sections['mon']['index_next'] = $this->_sections['mon']['index'] + $this->_sections['mon']['step'];
$this->_sections['mon']['first']      = ($this->_sections['mon']['iteration'] == 1);
$this->_sections['mon']['last']       = ($this->_sections['mon']['iteration'] == $this->_sections['mon']['total']);
?>
<option value=<?php echo $this->_tpl_vars['mmarr'][$this->_sections['mon']['index']]; ?>
><?php echo $this->_tpl_vars['mmarr'][$this->_sections['mon']['index']]; ?>
</option>
<?php endfor; endif; ?>
</select> -

<select name="dyear">
<?php unset($this->_sections['yr']);
$this->_sections['yr']['name'] = 'yr';
$this->_sections['yr']['loop'] = is_array($_loop=$this->_tpl_vars['yyarr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['yr']['show'] = true;
$this->_sections['yr']['max'] = $this->_sections['yr']['loop'];
$this->_sections['yr']['step'] = 1;
$this->_sections['yr']['start'] = $this->_sections['yr']['step'] > 0 ? 0 : $this->_sections['yr']['loop']-1;
if ($this->_sections['yr']['show']) {
    $this->_sections['yr']['total'] = $this->_sections['yr']['loop'];
    if ($this->_sections['yr']['total'] == 0)
        $this->_sections['yr']['show'] = false;
} else
    $this->_sections['yr']['total'] = 0;
if ($this->_sections['yr']['show']):

            for ($this->_sections['yr']['index'] = $this->_sections['yr']['start'], $this->_sections['yr']['iteration'] = 1;
                 $this->_sections['yr']['iteration'] <= $this->_sections['yr']['total'];
                 $this->_sections['yr']['index'] += $this->_sections['yr']['step'], $this->_sections['yr']['iteration']++):
$this->_sections['yr']['rownum'] = $this->_sections['yr']['iteration'];
$this->_sections['yr']['index_prev'] = $this->_sections['yr']['index'] - $this->_sections['yr']['step'];
$this->_sections['yr']['index_next'] = $this->_sections['yr']['index'] + $this->_sections['yr']['step'];
$this->_sections['yr']['first']      = ($this->_sections['yr']['iteration'] == 1);
$this->_sections['yr']['last']       = ($this->_sections['yr']['iteration'] == $this->_sections['yr']['total']);
?>
<option value=<?php echo $this->_tpl_vars['yyarr'][$this->_sections['yr']['index']]; ?>
><?php echo $this->_tpl_vars['yyarr'][$this->_sections['yr']['index']]; ?>
</option>
<?php endfor; endif; ?>
</select>&nbsp;&nbsp;&nbsp;Month - Year
</td>
</tr>
<tr class=fieldsnew>
<td>&nbsp;</td>
<td align=center>
<input type=hidden name=cid value=<?php echo $this->_tpl_vars['cid']; ?>
>
<input type=hidden name=name value=<?php echo $this->_tpl_vars['name']; ?>
>
<Input type=submit name=submit value=Submit class="testbox">
</td>
</tr>
</table>
<?php endif; ?>
</html>
