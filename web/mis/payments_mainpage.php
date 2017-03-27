<?php
include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{
		if($val=='community')
			include("community_payments.php");
		elseif($val=='age')
			include("male_female_payments.php");
	}
	else
	{
                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

                $sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE WHERE 1";
                $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $commarr[$i]["VAL"]=$row['VALUE'];
                        $commarr[$i]["LABEL"]=$row['SMALL_LABEL'];
                        $i++;
                }

		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$srcgrp[$i]=$row['GROUPNAME'];
			$i++;
		}

		$sql="SELECT VALUE,LABEL FROM newjs.TOP_COUNTRY WHERE 1";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$ctryarr[$i]['VAL']=$row['VALUE'];
			$ctryarr[$i]['LABEL']=$row['LABEL'];
			$i++;
		}

                $smarty->assign("srcgrp",$srcgrp);
                $smarty->assign("ctryarr",$ctryarr);
                $smarty->assign("commarr",$commarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("mmarr",$mmarr);
		$smarty->assign("cid",$cid);
		$smarty->display("payments_mainpage.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
