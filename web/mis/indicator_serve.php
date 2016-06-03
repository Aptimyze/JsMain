<?php
include("connect.inc");
$db2=connect_master();


if(authenticated($cid))
{
	if ($id)
	{
		$filename="/usr/local/indicators/".$id;

		$fp = @fopen($filename,"r");
		if (!$fp)
		{
			echo("no record found");
			exit;
		}
		echo $data=fread($fp,filesize($filename));
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
