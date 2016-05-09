<?
include("connect.inc");
include("new_voucher.php");
$db=connect_db();

if($receiver!="")
{
	$name=set_discount_code($sender,$receiver,$email,$discount_code);
	if($name=="")
		echo "Discount code already used";
	else
		echo $name;
}
else
{
	$smarty->assign("SENDER",$NAME);
	if($profileid=="")
	{
		die(header("Location:../index.php"));
	}

	get_discount_code($profileid);
	$smarty->display("voucher_refrence.htm");
}
	
	
?>
