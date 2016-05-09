<?php

include("connect.inc");

if(authenticated($cid))
{
	$sql = "Select * from newjs.OBSCENE_MESSAGE where BLOCKED = 'T'";
	$result = mysql_query_decide($sql,$db) or die(mysql_error_js());

	while($myrow = mysql_fetch_array($result))
	{

		$sql_sender = "Select * from newjs.JPROFILE where PROFILEID = $myrow[SENDER] and ACTIVATED='Y'";
		$result_sender = mysql_query_decide($sql_sender);

		if(mysql_num_rows($result_sender) <= 0)
		{
			$sql="Update newjs.OBSCENE_MESSAGE set BLOCKED = 'Y' where ID='" . $myrow["ID"] . "'";
			mysql_query_decide($sql);
			continue;
		}

		$sender = mysql_fetch_array($result_sender);	
		
		$sql_receiver="Select * from newjs.JPROFILE where PROFILEID = $myrow[RECEIVER]";
                $result_receiver = mysql_query_decide($sql_receiver);
		$receiver = mysql_fetch_array($result_receiver);

		$dt_time_array = explode(" ",$myrow["DATE"]);
		$dt_array = explode("-",$dt_time_array[0]);

		$date = my_format_date($dt_array[2],$dt_array[1],$dt_array[0])." , ".$dt_time_array[1];			
		
		$values[] = array(	"ID" => $myrow["ID"],
					"SENDER" => $sender["USERNAME"],
					"RECEIVER" =>$receiver["USERNAME"],
					"DATE" =>$date,
					"MESSAGE" =>substr($myrow["MESSAGE"],0,20));
	}

	$smarty->assign("CID",$cid);
	$smarty->assign("ROWS",$values);
        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
        $smarty->display("check_obscene_msg.htm");

}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
