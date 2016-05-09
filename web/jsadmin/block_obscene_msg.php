<?php

include("connect.inc");
//$_SERVER['DOCUMENT_ROOT']="/var/www";
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysqlObj=new Mysql;
//$db2 = connect_slave();


for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");

}


$db=connect_db();
if(authenticated($cid))
{
	if($CMDBlock || $CMDUnblock)
	{
		$smarty->assign("CID",$cid);
		$smarty->assign("FROM_POPUP",$FROM_POPUP);
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		foreach( $_POST as $key => $value )
                {
                        if( substr($key, 0, 2) == "cb" )
                        {
                                $cnt=$cnt+1;
                                list($id )= explode("|X|",ltrim($key, "cb"));
                                $id_arr[] = $id;
                        }
                }

		if(isset($id_arr) || $FROM_POPUP)
		{
			$dt = date("Y-m-d H:i:s",time());
			if(is_array($id_arr))
				$id_str = implode(",",$id_arr);
			else
				$id_str = $id;
			if($CMDBlock)
			{
				if($username = getuser($cid))
				{

					$sql = "Update newjs.OBSCENE_MESSAGE set BLOCKED = 'Y',USER='$username', DATE_EDIT ='$dt' where ID IN ($id_str) ";
					$result = mysql_query_decide($sql) or die(mysql_error_js());
					$display = "Message successfully blocked.";
					$smarty->assign("DISPLAY",$display);
					$smarty->display("msg_obscene_result.htm");
				}
			}
			elseif($CMDUnblock)
			{
	                	if($username = getuser($cid))
        	        	{

                	        	$dt = date("Y-m-d H:i:s",time());
					$sql = "Update newjs.OBSCENE_MESSAGE set BLOCKED = 'N',USER='$username',DATE_EDIT ='$dt' where ID IN ($id_str) ";
		                        $result = mysql_query_decide($sql) or die(mysql_error_js());



					$display =  "Message successfully Unblocked.";

					$sql="SELECT ID,SENDER,RECEIVER FROM newjs.OBSCENE_MESSAGE WHERE ID IN ($id_str)";
					$result_obsid=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					while($row_obsid=mysql_fetch_array($result_obsid))
					{
						$sender=$row_obsid['SENDER'];
						
						$receiver=$row_obsid['RECEIVER'];
						$id_obsid=$row_obsid['ID'];
						$myDbName1=getProfileDatabaseConnectionName($sender,'',$mysqlObj);
						$myDbName2=getProfileDatabaseConnectionName($receiver,'',$mysqlObj);
						if($myDbName1==$myDbName2)
							$myDb1=$myDbarr[$myDbName1];
						else
						{
							$myDb1=$myDbarr[$myDbName1];
							$myDb2=$myDbarr[$myDbName2];
						}		
							
						//$sql_id="SELECT ID FROM newjs.MESSAGE_LOG WHERE MSG_OBS_ID IN ($id_str)";
						$sql_id="SELECT ID FROM newjs.MESSAGE_LOG WHERE SENDER='$sender' AND RECEIVER='$receiver' AND MSG_OBS_ID='$id_obsid'";
						$result_id = $mysqlObj->ExecuteQuery($sql_id,$myDb1) or die("Error in MESSAGE_LOG fetching data");
						$row_id=mysql_fetch_row($result_id);
						
						//print_r($row_id);
						
						//if(count($row_id)>0)	
						$id_str_msg_log="'".implode("','",$row_id)."'";
						
						$sql_mes = "Update newjs.MESSAGE_LOG set OBSCENE = 'N' where ID IN ($id_str_msg_log) ";
						//type condition is applied in the below query bcos MSG_DEL was getting set only in case of obscene initiated contact.
						$sql_con = "Update newjs.CONTACTS set MSG_DEL = '' WHERE SENDER='$sender' AND RECEIVER='$receiver' AND TYPE='I' AND MSG_DEL='D' ";
						//May be both user belong to same shard
						if($myDbName1==$myDbName2)
						{
							//Update in MESSAGE_LOG 
							$mysqlObj->ExecuteQuery($sql_mes,$myDb1) or die('error in update of message log');
							//Update in CONTACTS
							$mysqlObj->ExecuteQuery($sql_con,$myDb1) or die('error in update of contacts');
						}
						else
						{
							//Update in MESSAGE_LOG 
							$mysqlObj->ExecuteQuery($sql_mes,$myDb1)  or die('error in update of message log at sender');
							$mysqlObj->ExecuteQuery($sql_mes,$myDb2) or die('error in update of message log at receiver');
							//Update in CONTACTS
							$mysqlObj->ExecuteQuery($sql_con,$myDb1)  or die('error in update at contats at sender');
							$mysqlObj->ExecuteQuery($sql_con,$myDb2) or die('error in update of contacts at receiver');
						}	
						
					}
					
	                	        $smarty->assign("DISPLAY",$display);
                        		$smarty->display("msg_obscene_result.htm");
        		       	}
	
			}
		}
		else
		{
			$display = "No messages selected for the operation";
			$smarty->assign("DISPLAY",$display);
			$smarty->display("msg_obscene_result.htm");
		}
	}
	else
	{
		$sql = "Select * from newjs.OBSCENE_MESSAGE where ID = $id";
		$result = mysql_query_decide($sql);
		$myrow = mysql_fetch_array($result);

		$smarty->assign("ID",$id);
		$smarty->assign("CID",$cid);
		$smarty->assign("MSG",nl2br($myrow["MESSAGE"]));
		$smarty->display("block_obscene_msg.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
