<?php

/**
*	Filename	:	mmm_create_table.php
*       Included        :       connect.inc
*       Included        :       mmm_create_table_inc.php

*	Description	:	It is called twice  one on submit event and another on create_table event :-
					submit event -->It takes mailer_id as input and finds the saved query of that mailer and finally calculates the total results of that query and also current mail queue and finally passes to the mmm_create_table.tpl
					create_table-->It takes the table splitting information in percentage or in numbers of records to be taken in one table and finally creates tables with unsubscribe results omitted
**/




/**
*	Tpls used :a)mmm_create_table.tpl
			   b)mmm_create_table1.tpl
			   c)mmm_message.tpl
**/
ini_set('max_execution_time',0);

include_once('mmm_create_table_inc.php');
		

//// THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE///////////////////////
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid,$ip))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
                                                                                                 
/////////////AUTHENTICATION ROUTINE ENDS HERE///////////////
//when a mailer is selected for table creation	
if($submit)
{
	$mail_type=get_mail_type($mailer_id);
	$result_no=get_subquery_result($mailer_id);
	$svarr = array();
	$iparr = array();
	$srv = get_servers();
	for($i=1;$i<=$srv;$i++)
        {
		$ip = getip($i);
        	$iparr[] = array("ip"=>$ip);
		$spstr = "sp".$i;
        	$svarr[] = array("spstr"=>$spstr);
        }
	$smarty->assign("mail_type",$mail_type);
	$smarty->assign("result_no",$result_no);
	$smarty->assign("mailer_id",$mailer_id);
//	$smarty->assign("mq1",$mq1);
//	$smarty->assign("mq2",$mq2);
	$smarty->assign("svarr",$svarr);
	$smarty->assign("iparr",$iparr);
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_create_table.htm");
}
// when the tables are to be created
elseif($create_table)
{
	$sparr = array();
	$limit_arr = array();
	$srv = get_servers();
	for($i=1;$i<=$srv;$i++)
	{
	 	$spstr = "sp".$i;
                if(trim($$spstr) != "")
                        $sparr[] = array("spstr"=>$spstr,"value"=>trim($$spstr));
	}
	
	$sum = 0;
	for($j=0;$j<sizeof($sparr);$j++)
		$sum += $sparr[$j][value];
	if($sum != 100)
	{
		$msg = "The splitting percentage sum should be 100 for $mailer_id";
		header("Location: mmm_create_table3.php?mailer_id=$mailer_id & msg=$msg & cid=$cid");
		die;
	}	
	$table_name=$mailer_id."mailer";
        $mail_type=get_mail_type($mailer_id);
        $sub_sub_query=get_sub_sub_query($mailer_id);
        $field_arr=get_table_field($mailer_id);
//	echo $table_name."* * ".$field_arr."* *".$sub_sub_query."* *".$mail_type."* *".$timer;
        $response=get_response_type($mailer_id);
        create_full_table($table_name,$field_arr,$sub_sub_query,$mail_type,$timer,$response);

        $sql_num="SELECT COUNT(*) AS COUNT FROM $table_name";
       
	global $db;
        $result_num=mysql_query($sql_num,$db);
        $row_num=mysql_fetch_array($result_num);
        $no=$row_num[COUNT];
        $old_table_name=$table_name;
       
       
        
	if(sizeof($sparr) == 1)
	{
		$sarr = explode("p",$sparr[0][spstr]);
		$srvid = $sarr[1];
	//	$new_table_name=$table_name."_s".$srvid;
		
	//code added to split table generation	
		$sql_maxid = "SELECT MAX(ID) FROM MAILER_SERVER";
		$res_maxid = mysql_query($sql_maxid,$db) or die(mysql_error().$sql_maxid);
		$row_maxid = mysql_fetch_row($res_maxid);
		
		$sql_lasttbname = "SELECT TABLE_NAME FROM MAILER_SERVER WHERE ID = '$row_maxid[0]'";
		$res_lasttbname = mysql_query($sql_lasttbname,$db) or die(mysql_error().$sql_lasttbname);
		$row_lasttbname = mysql_fetch_array($res_lasttbname);

		if(substr($row_lasttbname['TABLE_NAME'],-2,2) == "s1")
		{
			$new_table_name = $table_name."_s2";
		}
		elseif(substr($row_lasttbname['TABLE_NAME'],-2,2) == "s2")
		{
			$new_table_name = $table_name."_s1";
		}
		
	//end of code added to split gernertion
                echo"<br>New table name-->$new_table_name<br>";
                rename_table($new_table_name,$old_table_name,$mailer_id,$srvid,$response);
	}
	else
	{
		$limit_arr[] = 0;
		for($t=0 ; $t<sizeof($sparr) ; $t++)
		{
			$sarr = explode("p",$sparr[$t][spstr]);
  		      	$srvid = $sarr[1];
			$lim = (int)(($sparr[$t][value] * $no)/100);
			$limit_arr[] = $lim;
		}
		split_table($sparr,$limit_arr,$no,$mailer_id,$old_table_name,$response);
	}
}
else	
{
	$mailer_id_arr=get_valid_mailers($mailer_id);	
	$smarty->assign("msg",$msg);
	$smarty->assign("cid",$cid);
	$smarty->assign("mailer_id_arr",$mailer_id_arr);	
	$smarty->display("mmm_create_table1.htm");
}

?>
