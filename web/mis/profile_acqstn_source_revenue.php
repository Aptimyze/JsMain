<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();


if(authenticated($cid))
{
	if ($CMDSubmit)
	{
		//$filename= "/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/cpp_".$year."_mis.htm";
		$filename="/usr/local/indicators/cpp_".$source."_".$year."_mis.htm";
		$fp = @fopen($filename,"r");
		if (!$fp)
		{
			echo("no record found");
			exit;
		}

		echo $data=fread($fp,filesize($filename));

		//$filename= "/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/cpp_".$year."_mis.htm";
		//$smarty->display("cpp_".$source."_".$year."_mis.htm");
	}
	else
	{
		$user=getname($cid);
                $smarty->assign("flag","0");

		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        $srcarr[]=$row['GROUPNAME'];
                }
		for($i=2005;$i<=date('Y')+1;$i++)
                        $yyarr[] = $i;

		$smarty->assign("srcarr",$srcarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("profile_acqstn_source_revenue.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
