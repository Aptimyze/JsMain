<?php
  $curFilePath = dirname(__FILE__)."/";
  include_once("/usr/local/scripts/DocRoot.php");

        chdir(dirname(__FILE__));
        include("../connect.inc");
        //include_once("/usr/local/scripts/connect_db.php");    //for testing

        $db_master = connect_db();
        $db = connect_slave();
        $profiles = array();
        $profiles1 = array();
        $profiles2 =array();
	$profiles3 =array();
	$todaysDt =date("Y-m-d H:i:s");
        $sql_track="SELECT * FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME='0000-00-00 00:00:00'";
        $res_track=mysql_query($sql_track,$db_master) or die("mysql error");
        while($row_track=mysql_fetch_assoc($res_track))
        {
                $profileid=$row_track['PROFILEID'];

                $sql_jprofile="SELECT PROFILEID,ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
                $res_jprofile=mysql_query($sql_jprofile,$db_master) or die("mysql jprofile error");
                if($row_jprofile=mysql_fetch_assoc($res_jprofile))
                {
			$sql1 ="select ACTIVATED_ON,EXPIRY_DT from billing.SERVICE_STATUS WHERE SERVEFOR LIKE '%F%' AND PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
			$res1=mysql_query($sql1,$db_master) or die("mysql jprofile error");
			if($row1=mysql_fetch_assoc($res1))
			{
				$expiryDt =$row1['EXPIRY_DT'];
				$entryDt =$row1['ACTIVATED_ON'];	

				$sql_jprofile2="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
				$res_jprofile2=mysql_query($sql_jprofile2,$db_master) or die("mysql jprofile error");
				if($row_jprofile2=mysql_fetch_assoc($res_jprofile2))
				{
					$subscription=$row_jprofile2['SUBSCRIPTION'];
					if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!="")){
						
						if(!in_array($profileid,$profiles)){
							$profiles[] =$profileid;
							echo "P_P=".$sql3 ="update incentive.MAIN_ADMIN SET STATUS='P',ALLOT_TIME=DATE_SUB('$entryDt',INTERVAL (5) DAY) where PROFILEID='$profileid'";
							echo "\n";
							mysql_query($sql3,$db_master) or die("mysql error");	
		
							echo "P_P=".$sql4 ="update incentive.CRM_DAILY_ALLOT SET ALLOT_TIME=DATE_SUB('$entryDt',INTERVAL (5) DAY),DE_ALLOCATION_DT=DATE_ADD('$expiryDt',INTERVAL (10) DAY) where PROFILEID='$profileid' AND ALLOT_TIME='0000-00-00 00:00:00'";
							echo "\n";	
							mysql_query($sql4,$db_master) or die("mysql error");
						}
						
					}
					else{
				        	if(!in_array($profileid,$profiles1)){
                        				$profiles1[] =$profileid;
		                                        echo "D_EP=".$sql5 ="delete from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND DATE_ADD('$expiryDt' ,INTERVAL (10) DAY)<'$todaysDt'";

        		                                echo "\n";
                        		                mysql_query($sql5,$db_master) or die("mysql error");

                                        		echo "D_EP=".$sql6 ="delete from incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' AND ALLOT_TIME='0000-00-00 00:00:00'";
                                        		echo "\n";
                                        		mysql_query($sql6,$db_master) or die("mysql error");
						}	
					}
				}
			}
                        else{
                        	if(!in_array($profileid,$profiles2)){
                                	$profiles2[] =$profileid;
                                        echo "D_NP=".$sql7 ="delete from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOT_TIME<DATE_SUB('$todaysDt',INTERVAL (15) DAY)";
                                        echo "\n";
                                        mysql_query($sql7,$db_master) or die("mysql error");

		                        echo "D_NP=".$sql8 ="delete from incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' AND ALLOT_TIME='0000-00-00 00:00:00'";
        		                echo "\n";
					mysql_query($sql8,$db_master) or die("mysql error");
				}
			}
                }
                elseif(!in_array($profileid,$profiles3)){
                        $profiles3[] =$profileid;
			echo "D_D=".$sql9 ="delete from incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' AND ALLOT_TIME='0000-00-00 00:00:00'";
			echo "\n";
			mysql_query($sql9,$db_master) or die("mysql error");
		}
        }

	/*
        echo count($profiles);
        echo "dssd";
        echo count($profiles1);
        echo "=";
        echo count($profiles2);
	*/

?>

