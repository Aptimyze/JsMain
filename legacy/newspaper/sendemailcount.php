
<?php

	include("connect.inc");
	dbsql2_connect();

	if(authenticated($cid))
	{
		if($submit)
		{
			
			for ($i = 0; $i < 31; $i++)
                        	$ddarr[$i] = $i + 1;
			$dflag = 1;

			$sql = "SELECT COUNT(*) AS CNT , DAYOFMONTH(SENDTIME) AS dd FROM jsadmin.MAILER_TEST";
			if ($dmonth <= 9)
			{
				$sql.=" WHERE SENDTIME BETWEEN '$dyear-0$dmonth-01' AND '$dyear-0$dmonth-31' ";
			}
			else
			{
				$sql.=" WHERE SENDTIME BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' ";
			}
				
			$sql.="AND EMAILSTATUS ='Y'  GROUP BY dd ";	
			$res = mysql_query($sql) or die("$sql".mysql_error());
			while ($row=mysql_fetch_array($res))
			{
				$dd = $row["dd"]-1;
				$count[$dd] = $row["CNT"];
				$total+= $row["CNT"];
				
		
			}

			$smarty->assign("dflag",$dflag);
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("count",$count);
			$smarty->assign("total",$total);
			$smarty->assign("dmonth",$dmonth);
			$smarty->assign("dyear",$dyear);
			$smarty->assign("cid",$cid);
			$smarty->assign("name",$name);
			$smarty->display("sendemailcount.htm");
		}
		else
		{
			for ($i = 0; $i < 12; $i++)
                        {
                                $mmarr[$i] = $i + 1;
                        }
                                                                                                                             
                        for ($i = 0; $i < 10; $i++)
                        {
                                $yyarr[$i] = $i + 2005;
                        }

			$smarty->assign("ddarr",$ddarr);
                        $smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("name",$name);
                        $smarty->display("sendemailcount.htm");

		}

	}

	else
	{
		$msg="Your session has been timed out<br>  ";
                $msg .="<a href=\"index.htm\">";
                $msg .="Login again </a>";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");

	}
?>
