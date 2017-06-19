<?php
	include("../jsadmin/connect.inc");
	include("comfunc_sums.php");
	$returned_val = check_voucher_discount_code($voucher_discount_code);
	if($returned_val['CODE_EXISTS'] > 0)
		echo "Code Available|#|".$returned_val['PERCENT'];
	else
		echo "Code Invalid / Not Available";
?>
