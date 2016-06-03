<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");

$db2=connect_db();
$db=connect_slave();

	//************************************    Condition after submit state  ***************************************
		$exec_id_arr 	=array();	
		$exec_name_arr 	=array();
                $uname_arr      =array(); 
                $uname_str      ='';
		$todayDate	=date("Y-m-d");
		$lastDuration	=date("Y-m-d H:i:s",time()-45*24*60*60);
		$last15Days     =date("Y-m-d H:i:s",strtotime("$todayDate -15 days"));
		$type	 	='REG';
	
		// Get the currently active executives from the PSWRDS table
                $sql_unames = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE LAST_LOGIN_DT>='$last15Days'";
                $res_unames = mysql_query_decide($sql_unames,$db) or die($sql_unames.mysql_error_js());
                while($row_unames = mysql_fetch_array($res_unames))
                {
                	$uname_arr[] = $row_unames['USERNAME'];
                }
		$uname_arr =array_unique($uname_arr);
                $uname_str = "'".@implode("','",$uname_arr)."'";

		if($uname_str){
			// Get the sugarcrm registered executives which are currently active in PSWRDS table 
			$sql1 ="SELECT id,user_name from sugarcrm.users where user_name in($uname_str) and id!='1'";
			$res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			while($row1=mysql_fetch_array($res1))
			{
				$exec_id_arr[] 	=$row1['id'];
				$exec_name_arr[]=$row1['user_name'];
			}
		}
		
		$total_exec =count($exec_id_arr);
		for($i=0; $i<$total_exec; $i++)
		{			
			$exec_id 	=$exec_id_arr[$i];
			$exec_name	=$exec_name_arr[$i];
			$usernameArr 	=array();

			// sugarcrm database 
			// Get all the usernames with complete registration from sugarcrm per executive wise
                        $sql_con ="SELECT l_cstm.jsprofileid_c from sugarcrm.leads_cstm l_cstm,sugarcrm.leads l where l.id=l_cstm.id_c and l.assigned_user_id='$exec_id' AND l.status='26' AND l.date_modified>='$lastDuration' AND l_cstm.jsprofileid_c!=''";
                        $res_con =mysql_query_decide($sql_con,$db) or die("$sql_con".mysql_error_js()); 
                        while($row_con=mysql_fetch_array($res_con))
                        {
                                $usernameArr[] =$row_con['jsprofileid_c'];
                        }

			$usernameArr =array_unique($usernameArr);
			$totUsername =count($usernameArr);
			for($j=0; $j<$totUsername; $j++)
			{
				$usernameStr ='';
				$usernameStr =$usernameArr[$j];
				if($usernameStr)
				{
	                		// Get all the profileids which are registered in last 3 Hrs in JPROFILE table
	                		$sql2 ="SELECT PROFILEID,ENTRY_DT from newjs.JPROFILE where USERNAME='$usernameStr' AND activatedKey=1";
                			$res2 =mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
                			$row2=mysql_fetch_array($res2);
                			$profileid              =$row2['PROFILEID'];
                	       		$dateVal 		=$row2['ENTRY_DT'];

					// Registration new records entered in the table MIS.LTF
					$sql_ins="INSERT IGNORE into MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`) VALUES('$profileid','$exec_name','$type','$dateVal')";
					mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
				}
			}
		}
mail("manoj.rana@naukri.com","LTF 4-Hour Cron Onetime", "");


?>
