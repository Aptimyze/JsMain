<?php 
  $curFilePath = dirname(__FILE__)."/"; 
  include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	include("../connect.inc");
	//include_once("/usr/local/scripts/connect_db.php");	//for testing

	$db_master = connect_db();
	$db = connect_slave();
	$profiles = array();
	$j=0;
	$k=0;
	$sql_track="SELECT * FROM incentive.DEALLOCATION_TRACK";
	$res_track=mysql_query($sql_track,$db_master) or die("mysql error");	
	while($row_track=mysql_fetch_assoc($res_track))
	{
		$deAllocDate=$row_track['DEALLOCATION_DT'];
		$profileid=$row_track['PROFILEID'];
		$sql_jprofile="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
                $res_jprofile=mysql_query($sql_jprofile,$db_master) or die("mysql jprofile error");
                if($row_jprofile=mysql_fetch_assoc($res_jprofile))
                {
                        $subscription=$row_jprofile['SUBSCRIPTION'];
			if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
	                        $profiles[]=$profileid;
                }
		$j++;
	}
	for($i=0;$i<count($profiles);$i++)
	{
		$profileid=$profiles[$i];
                $sql_billing="SELECT ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID=$profileid ORDER BY ENTRY_DT DESC LIMIT 1";
                $res_billing=mysql_query($sql_billing,$db_master);
                $row_billing=mysql_fetch_assoc($res_billing);
                if($deAllocDate>=$row_billing['ENTRY_DT'])
                        $profilesRequired[]=$profileid;

	}
	/*for($i=0;$i<count($profilesRequired);$i++)
        {
                $profileid=$profilesRequired[$i];
                $sql_billing1="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID=$profileid";
                $res_billing1=mysql_query($sql_billing1,$db_master);
                if($row_billing1=mysql_fetch_assoc($res_billing1))
                        $gone_case[]=$profileid;

        }*/
	for($i=0;$i<count($profilesRequired);$i++)
        {
                $profileid=$profilesRequired[$i];
                $sql_billing2="SELECT * FROM incentive.MAIN_ADMIN_LOG WHERE PROFILEID=$profileid ORDER BY ID DESC LIMIT 1";
                $res_billing2=mysql_query($sql_billing2,$db_master);
                if($row_billing2=mysql_fetch_assoc($res_billing2))
		{
				
                        /*$PROFILEID=$row_billing2["PROFILEID"];
			$CONTACTS_ACC=$row_billing2["CONTACTS_ACC"];
			$CONTACTS_RCV=$row_billing2["CONTACTS_RCV"];
			$ALLOT_TIME=$row_billing2["ALLOT_TIME"];
			$CLAIM_TIME=$row_billing2["CLAIM_TIME"];
			$ALLOTED_TO=$row_billing2["ALLOTED_TO"];
			$STATUS=$row_billing2["STATUS"];
			$ALTERNATE_NUMBER=$row_billing2["ALTERNATE_NUMBER"];
			$FOLLOWUP_TIME=$row_billing2["FOLLOWUP_TIME"];
			$MODE=$row_billing2["MODE"];
                        $CONVINCE_TIME=$row_billing2["CONVINCE_TIME"];
                        $COMMENTS=$row_billing2["COMMENTS"];
                        $RES_NO=$row_billing2["RES_NO"];
                        $MOB_NO=$row_billing2["MOB_NO"];
                        $EMAIL=$row_billing2["EMAIL"];
                        $WILL_PAY=$row_billing2["WILL_PAY"];
                        $TIMES_TRIED=$row_billing2["TIMES_TRIED"];
			$ORDERS=$row_billing2["ORDERS"];
                        $REASON=$row_billing2["REASON"];*/

			$sql_1="update incentive.MAIN_ADMIN_POOL set ALLOTMENT_AVAIL='N' WHERE PROFILEID=$profileid";
	                $res_1=mysql_query($sql_1,$db_master);

			echo $sql_2="INSERT IGNORE INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,FOLLOWUP_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT PROFILEID,ALLOT_TIME,CLAIM_TIME,FOLLOWUP_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN_LOG WHERE PROFILEID=$profileid";
			echo "\n";
        	        $res_2=mysql_query($sql_2,$db_master) or die(mysql_error($db_master));
			$k++;
		}
        }
?>
