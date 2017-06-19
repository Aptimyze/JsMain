<?php
include "connect.inc";
/**
*	Filename	:	search.php
*	Included	:	searchinc.php
*	Called From	: 	searchpage.php
*	Description	:	contains basic search logic
**/

function save_query($mailer_id,$sub_query)
{
	//echo $sub_query=addslashes($sub_query);
//        echo $sub_query;
                                                                                                 
        $sql="UPDATE MAIN_MAILER SET SUB_QUERY=\"$sub_query\", STATE='qs' WHERE MAILER_ID=$mailer_id";
        
        mysql_query($sql) or die("Coud not save query in mmm_save_search.php ".mysql_error());
}
// ADDED BY Neha on 25th October 2012 against ticket #1015
function save_mmm_split_info($mailer_id,$mmm_split){
	$sql = "UPDATE MAIN_MAILER SET MMM_SPLIT=\"$mmm_split\" WHERE MAILER_ID=$mailer_id";
	mysql_query($sql) or die("Coud not save query in mmm_save_search.php ".mysql_error());
}
// ADDED BY Neha on 25th October 2012 against ticket #1015 ends here
if($submit)
{
	save_query($mailer_id,$sql);
	// ADDED BY Neha on 25th October 2012 against ticket #1015
	save_mmm_split_info($mailer_id,$mmm_split);
	// ADDED BY Neha ends here
	$message="Your Query has been saved with mailer_id :<font color=\"red\"> $mailer_id </font>Now you can assign variables corresponding to this mailer";
        $smarty->assign("message",$message);
        $smarty->display("mmm_message.htm");

}
elseif($edit)
{
	$table_name=$mailer_id."FINAL";
	$sql="SHOW TABLES";
	$result=mysql_query($sql) or die("could not get table info ");
	$row=mysql_fetch_array($result);
	while($row=mysql_fetch_array($result))
        {
		if($row['Tables_in_mmmjs']==$table_name)	
		{
			$sql="DROP TABLE ".$mailer_id."FINAL";
			mysql_query($sql) or die("Editing query is not possible.Please click Form Query to create new query ".mysql_error());
		}
        }
	header("Location:advance_search99_new.php?FLAG=search&checksum=&cid=$cid");
}
?>
