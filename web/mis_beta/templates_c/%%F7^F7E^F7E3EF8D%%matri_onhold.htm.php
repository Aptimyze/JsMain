<?php /* Smarty version 2.6.7, created on 2006-10-30 18:39:54
         compiled from matri_onhold.htm */ ?>
<html>  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="../jsadmin/jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
       <script lang="javascript">
        function doit()
        {
                window.close();

        }
       </script>
</head>
<body>
<?php if ($this->_tpl_vars['flag'] == 1): ?>
<script language=javascript>
doit();
</script>
<?php endif; ?>
<form method=post action=matri_onhold.php?profileid=<?php echo $this->_tpl_vars['profileid']; ?>
&username=<?php echo $this->_tpl_vars['username']; ?>
>
<table>
<tr>
 <td width="40%" class="label" align=center><font size=2><b>Reason to keep the user on hold:</b></font></td>
 <td class=fieldsnew width=60%>
  <textarea name="reason"  class="textbox" cols="65" rows="3"></textarea>
 </td>
</tr></table>
<table><tr><td align=center class=fieldsnew><input type=submit name=submit value=SUBMIT></td></tr></table>
</form>
</body>
</html>