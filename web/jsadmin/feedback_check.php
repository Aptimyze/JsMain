<?php
include("connect.inc");
//connect_db();
mysql_select_db_js("newjs");
if($Submit)
{
	$c=0;
        foreach( $_POST as $key => $value )
        {
        	if( substr($key, 0, 2) == "cb" )
                {
                	$c=$c+1;
                        $idarr[]=ltrim($key, "cb");
                }
     	}
	if(count($idarr)>0)
        {
        	$idstring="'".implode("','",$idarr)."'";
		$sql="UPDATE newjs.FEEDBACK set STATUS='D', STATUS_DT=now() where ID in ($idstring)";
		mysql_query_decide($sql);	
	}
	
	if($c==0)
        	$msg = "Please check the records to discard<br><br>";
        else
                $msg = "You have successfully discarded $c feedbacks<br><br>";
        $msg .= "<a href=\"feedback_check.php\">";
        $msg .= "Continue &gt;&gt;</a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
else
{
	$sql="SELECT SQL_CALC_FOUND_ROWS * from FEEDBACK where STATUS='' ORDER BY DATE desc";
	$result=mysql_query_decide($sql);
	$sql="SELECT FOUND_ROWS() as NUM";
	$result1=mysql_query_decide($sql);
	$myrow1=mysql_fetch_array($result1);
	$i=1;
	while($myrow=mysql_fetch_array($result))
	{
		$values[] = array("SNO"=>$i,
				  "NAME"=>$myrow["NAME"],
				  "ADDRESS"=>$myrow["ADDRESS"],
				  "EMAIL"=>$myrow["EMAIL"],
				  "COMMENTS"=>$myrow["COMMENTS"],
				  "DATE"=>$myrow["DATE"],
				  "ID"=>$myrow["ID"],
				  "ABUSE"=>$myrow['ABUSE']
                          );
	$i++;
	}
	$smarty->assign("NUM",$myrow1["NUM"]);
	$smarty->assign("ROW",$values);
	$smarty->display("feedback_check.tpl");
}
?>
