<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

//mysql_select_db("newjs");
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
		mysql_query_decide($sql,$db) or die(mysql_error_js());	
	}
	
	if($c==0)
        	$msg = "Please check the records to discard<br><br>";
        else
                $msg = "You have successfully discarded $c feedbacks<br><br>";
        $msg .= "<a href=\"feedbackmis.php\">";
        $msg .= "Continue &gt;&gt;</a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("../jsadmin/jsadmin_msg.tpl");
}
else
{
	$sql="SELECT SQL_CALC_FOUND_ROWS * from FEEDBACK where STATUS='' ORDER BY DATE desc";
	$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	$sql="SELECT FOUND_ROWS() as NUM";
	$result1=mysql_query_decide($sql,$db) or die(mysql_error_js());
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
			  "ID"=>$myrow["ID"]	
                          );
	$i++;
	}
	$smarty->assign("NUM",$myrow1["NUM"]);
	$smarty->assign("ROW",$values);
	$smarty->display("feedbackmis.tpl");
}
?>
