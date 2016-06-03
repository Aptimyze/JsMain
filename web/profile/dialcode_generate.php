<?php

include_once("connect.inc");
$db=connect_db();

        $data=authenticated($checksum);
        if(isset($data))
        {
                $profileid=$data['PROFILEID'];
		$rec_profileid = $_GET['RECEIVER'];
		
		$sql ="SELECT `DIALCODE` FROM newjs.DIALCODE_GENERATE WHERE CALLER='$profileid' AND RECEIVER='$rec_profileid'";
		$result = mysql_query_decide($sql,$db) or logError("error",$sql);
		$row =mysql_fetch_array($result);
		$dialcode = $row['DIALCODE'];			
		if(!$dialcode){
			$sql_ ="SELECT count(*) as CNT from newjs.DIALCODE_GENERATE";
			$result_ = mysql_query_decide($sql_,$db) or logError("error",$sql_);
			$rows_ =mysql_fetch_array($result_);
			$cnt =$rows_['CNT'];

			if($cnt >0)
                		$sql = "INSERT INTO newjs.DIALCODE_GENERATE(`DIALCODE`,`CALLER`,`RECEIVER`) values('','$profileid','$rec_profileid')";
			else
				$sql = "INSERT INTO newjs.DIALCODE_GENERATE(`DIALCODE`,`CALLER`,`RECEIVER`) values('10000','$profileid','$rec_profileid')";
                	mysql_query_decide($sql,$db) or logError("error",$sql);
			$dialcode = mysql_insert_id($db);
		}
		if($dialcode)
               		echo $dialcode;
		else
			echo "ERROR";
                die;
        }
        else
        {
                $smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		include_once("include_file_for_login_layer.php");
                $smarty->display("login_layer.htm");
                die;
        }
?>

