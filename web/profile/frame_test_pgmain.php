<?php
include("connect.inc");
connect_db();
?>
<html>
<body onLoad="document.abc.submit();">
<form name=abc action="<?php echo $ACTION_PATH ?>" method=post>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td height="5"><div align="center"><br>
	<input type="hidden" name="service_str" value="<?php echo  $service_str ?>">
	<input type="hidden" name="service_main" value="<?php echo $service_main ?>">
	<input type="hidden" name="type" value="<?php echo $type ?>">
	<input type="hidden" name="discount" value="<?php echo $discount ?>">
	<input type="hidden" name="total" value="<?php echo $total ?>">
	<input type="hidden" name="paymode" value="<?php echo $paymode ?>">
	<input type="hidden" name="setactivate" value="<?php echo $setactivate ?>">
	<input type="hidden" name="checksum" value="<?php echo  $checksum ?>">
	<input type="hidden" name="checkout" value="true">
	<!--input name="checkout" type="image" src="images/submit_button.gif" width="76" height="23"--> </div>
	</td>
	</tr>
</table>
<noscript>
<br><br>
<center>We cannot redirect you to online payment gateway because <b>Javascript</b> is disable in your browser.<br>Please click click on <b>Redirect</b> to reach online payment gateway.<br><br>
<input type=submit name=redirect value=Redirect></center>
</noscript>

</form>
</body>
</html>
<?php
?>
