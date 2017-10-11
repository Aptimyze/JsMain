<?php /* Smarty version 2.6.7, created on 2006-10-31 12:15:31
         compiled from matriprofile_attach_status.htm */ ?>
<html>

<body <?php if ($this->_tpl_vars['FLAG'] == '0'): ?> onload="opener.window.location.reload(true);"<?php endif; ?>>

<?php if ($this->_tpl_vars['FLAG'] == '1'): ?>
<p><?php echo $this->_tpl_vars['Invalidfile']; ?>
</p>
<p>Please Select a file to Upload</p>
<form method="post" enctype="multipart/form-data" action="matri_attach.php">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
<input type="hidden" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">
<input type="hidden" name="status" value="<?php echo $this->_tpl_vars['status']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
<input type="file" name="uploaded" size=40>
<input type="submit" value="Upload">
</form>

<?php elseif ($this->_tpl_vars['FLAG'] == '2'): ?>
<p><?php echo $this->_tpl_vars['Already']; ?>
</p>

<?php elseif ($this->_tpl_vars['FLAG'] == '3'): ?>
<p><?php echo $this->_tpl_vars['Error']; ?>
</p>
<p>Please Select a file to Upload</p>
<form method="post" enctype="multipart/form-data" action="matri_attach.php">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
<input type="hidden" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">
<input type="hidden" name="status" value="<?php echo $this->_tpl_vars['status']; ?>
">
<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
<input type="file" name="uploaded" size=40>
<input type="submit" value="Upload">
</form>

<?php elseif ($this->_tpl_vars['FLAG'] == '4'): ?>
<p><?php echo $this->_tpl_vars['Noid']; ?>
</p>
<p>Please Select a file to Upload</p>
<form method="post" enctype="multipart/form-data" action="matri_attach.php">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
<input type="hidden" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type="hidden" name="status" value="<?php echo $this->_tpl_vars['status']; ?>
">
<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
<input type="file" name="uploaded" size=40>
<input type="submit" value="Upload">
</form>

<?php elseif ($this->_tpl_vars['FLAG'] == '5'): ?>
<p>File doesn't exists</p>
<p>Please Select a file to Upload</p>
<form method="post" enctype="multipart/form-data" action="matri_attach.php">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
<input type="hidden" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<input type="hidden" name="status" value="<?php echo $this->_tpl_vars['status']; ?>
">
<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">
<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
<input type="file" name="uploaded" size=40>
<input type="submit" value="Upload">
</form>

<?php elseif ($this->_tpl_vars['FLAG'] == '0'): ?>
<p><?php echo $this->_tpl_vars['Done']; ?>
</p>
<form method="post" action="matri_attach.php">
<table>
<tr>
        <td>To:</td>
        <td><input type="text" name="to" value="<?php echo $this->_tpl_vars['to']; ?>
" size="40"></td>
</tr>
<tr>
        <td>CC:</td>
        <td><input type="text" name="cc1" size="40"></td><td>,matri1profile@jeevansathi.com<input type="hidden" name="cc2" value="nitesh.s@jeevansathi.com"><input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
"><input type="hidden" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
"></td>
</tr>
<tr>
        <td>Message:</td>
</tr></table>
<table>
<tr><td>
        <textarea name="msg" rows="10" cols="60">Please find the attached document.<br><a href=<?php echo $this->_tpl_vars['exec_email']; ?>
>Click here</a> to give feedback</textarea>
</td>
</tr>

<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">
<input type="hidden" name="status" value="<?php echo $this->_tpl_vars['status']; ?>
">
<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<tr>
        <td><input type="submit" name="sendmail" value="Send Mail"></td>
</tr>
</table>
</form>

<?php elseif ($this->_tpl_vars['FLAG'] == '7'): ?>
<p><?php echo $this->_tpl_vars['MailSent']; ?>
</p>

<?php else: ?>
<p>Please Select a file to Upload</p>
<form method="post" enctype="multipart/form-data" action="matri_attach.php">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
<input type="hidden" name="username" value="<?php echo $this->_tpl_vars['username']; ?>
">
<input type="hidden" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">
<input type="hidden" name="status" value="<?php echo $this->_tpl_vars['status']; ?>
">
<input type="hidden" name="exec_email" value="<?php echo $this->_tpl_vars['exec_email']; ?>
">
<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
<input type="file" name="uploaded" size=40>
<input type="submit" value="Upload">
</form>
</body>
<?php endif; ?>
</html>
