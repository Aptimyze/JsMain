<?php
	
	include("connect.inc");
	
	$db=connect_misdb();
	$db2=connect_master();
	
	if(authenticated($checksum))
	{
		if($profileid)
		{
			$sql="select count(*) as cnt,left(TIME,10) as time1 from newjs.CONTACTS where SENDER='$profileid' group by time1 order by time1 desc";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
			
			while($myrow=mysql_fetch_array($result))
			{
				$arr[]=array("COUNT" => $myrow["cnt"],
							"DATE" => $myrow["time1"]);
			}
			
			mysql_free_result($result);
			
			$smarty->assign("ARR",$arr);
			$smarty->assign("PERSONAL","1");
			$smarty->assign("CID",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->display("spammis.htm");
		}
		else 
		{
			$sql="select count(*) as cnt, SENDER from newjs.CONTACTS group by SENDER having cnt >=500 order by cnt desc";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
			
			while($myrow=mysql_fetch_array($result))
			{
				$str.=$myrow["SENDER"] . ",";
				$arr[]=array("PROFILEID" => $myrow["SENDER"],
							"USERNAME" => "",
							"COUNT" => $myrow["cnt"]);
			}
			
			mysql_free_result($result);
			
			$str=substr($str,0,-1);
			
			if ($str!="")
			{
				$sql="select PROFILEID,USERNAME from newjs.JPROFILE where PROFILEID in ($str) and ACTIVATED='Y'";
				$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
				
				while($myrow=mysql_fetch_array($result))
				{
					$profileid=$myrow["PROFILEID"];
					$username1=$myrow["USERNAME"];
				
					$cnt=count($arr);
					for($i=0;$i<$cnt;$i++)
					{
						if($arr[$i]["PROFILEID"]==$profileid)
						{
							$arr[$i]["USERNAME"]=$username1;
							break;
						}
					}
				}
			
				mysql_free_result($result);
				
				$cnt=count($arr);
				for($i=0;$i<$cnt;$i++)
				{
					if($arr[$i]["USERNAME"]!="")
					{
						$arr1[]=$arr[$i];
					}
				}
			}
			
			$smarty->assign("ARR",$arr1);
			$smarty->assign("CID",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->display("spammis.htm");
		}
	}
	else 
	{
		$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
	}
?>
	