<?

include"connect.inc";
                                                                                                 
//********************* THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE*****************************************************//

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
//***************AUTHENTICATION ROUTINE ENDS HERE*******************//



                /**
                *       Function        :       get_complete_mailer()
                *       Input           :       
                *       Output          :       array of mailer_name which are composed and ready to be tested
                *       Description     :       This function fetches the mailer_name of completed mailers and returns it as array    **/

function get_complete_mailers()
{
	global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
	$sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE ( (STATE='mdi' AND STATUS='') OR (STATE='mdi' AND STATUS='kil') OR (STATE='mdi' AND STATUS='old')) AND TEST='N' AND NAUKRI_STATE='com' AND CTIME > '$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
			  "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no complete mailer ";
		return $message;
        }
	else
	        return $arr;
}
                                     
                /**
                *       Function        :       get_tested_mailers()
                *       Input           :
                *       Output          :       array of mailer_name which are tested and ready to be fired
                *       Description     :       This function fetches the mailer_name of completed mailers and returns it as array    **/

function get_tested_mailers()
{
        global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
        $sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE ((STATE='mdi' AND STATUS='nok') OR (STATE='mdi' AND STATUS='rok') OR (STATE='mdi' AND STATUS='ook') ) AND RETEST='N' AND S1_FIRE='N' AND S2_FIRE='N' AND CTIME >'$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
			  "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no complete mailer ";
                return $message;
        }
        else
                return $arr;
}
                                                            
                                                                                                 
                /**
                *       Function        :       get_running_mailers()
                *       Input           :
                *       Output          :       array of mailer_name which are currently running
                *       Description     :       This function fetches the mailer_name of currently running mailers and returns it as array    **/

function get_running_mailers()
{
	global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
        $sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE ( (STATE='mdi' AND STATUS='run') OR (STATE='mdi' AND STATUS='res') OR (STATE='mdi' AND STATUS='orn')) AND STOP='N' AND CTIME >'$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
			  "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no running mailer ";
		return $message;
        }
	else
	        return $arr;
}
                                                                                                 
function get_ran_mailers()
{
        global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
        $sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE STATE='mdi' AND STATUS='ran' AND CTIME > '$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
                          "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no running mailer ";
                return $message;
        }
        else
                return $arr;
}


                /**
                *       Function        :       get_execution_test_mailers(),get_execution_fire_mailers(),get_execution_stop_mailers()
                *       Input           :
                *       Output          :       array of mailer_name which are under execution:test,fire,stop
                *       Description     :       This function fetches the mailer_name of under execution mailers and returns it as array    **/

function get_execution_test_mailers()
{
        global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
        $sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE (TEST='Y' OR RETEST='Y') AND CTIME >'$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
                         "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no incomplete mailer ";
                return $message;
        }
        else
                return $arr;
}
function get_execution_fire_mailers()
{
        global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
        $sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE S1_FIRE='Y' AND S2_FIRE='Y' AND CTIME >'$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
                         "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no incomplete mailer ";
                return $message;
        }
        else
                return $arr;
}
function get_execution_stop_mailers()
{
        global $smarty;
	$timeStampBefore3Months = getTimeStampBefore3Months();
        $sql="select MAILER_NAME,MAILER_ID,STATUS from MAIN_MAILER WHERE STOP='Y' AND CTIME >'$timeStampBefore3Months'";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
                         "status"=>$row[STATUS]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no incomplete mailer ";
                return $message;
        }
        else
                return $arr;
}



//*******************MAIN MODULE STARTS HERE***********************//                                                                                                 
if($test)
{
	foreach( $_POST as $key => $value )
        {
        	if( substr($key, 0, 8) == "complete" )
                {
                	$cnt=$cnt+1;
                        $mid = ltrim($key, "complete");
                        $mailerid[] = $mid;
                }
        }
	$complete=implode("','",$mailerid);	
/*	$sql1="SELECT STATUS FROM MAIN_MAILER  WHERE MAILER_ID IN ('$complete') ";
        $result1=mysql_query($sql1) or die("could not get valid mailers ".mysql_error());
	$row1=mysql_fetch_array($result1);
	$status=$row1['STATUS'];
	if($status=='')
	{
		$sql="UPDATE MAIN_MAILER SET STATUS='nok' WHERE MAILER_ID IN ('$complete') ";
		mysql_query($sql) or die("could not get valid mailers ".mysql_error());
		
	}
	elseif($status=='kil')
	{
		$sql="UPDATE MAIN_MAILER SET STATUS='rok' WHERE MAILER_ID IN ('$complete')";
		mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	}
	elseif($status=='old')
        {
                $sql="UPDATE MAIN_MAILER SET STATUS='ook' WHERE MAILER_ID IN ('$complete')";
                mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        }



*/
	$sql="UPDATE MAIN_MAILER SET TEST='Y' WHERE MAILER_ID IN ('$complete') ";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());

	header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_fire_menu.php?cid=$cid");	
}
elseif($retest)
{
        foreach( $_POST as $key => $value )
        {
                if( substr($key, 0, 6) == "tested" )
                {
                        $cnt=$cnt+1;
                        $mid = ltrim($key, "tested");
                        $mailerid[] = $mid;
                }
        }

	if(is_array($mailerid))
        $tested=implode("','",$mailerid);
	else
	$tested=$mailerid;

	$sql="UPDATE MAIN_MAILER SET RETEST='Y' WHERE MAILER_ID IN ('$tested') ";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_fire_menu.php?cid=$cid");

}
elseif($start)
{
	foreach( $_POST as $key => $value )
        {
        	if( substr($key, 0, 6) == "tested" )
                {
                	$cnt=$cnt+1;
                        $mid = ltrim($key, "tested");
                        $mailerid[] = $mid;
                }
	}
        $tested=implode("','",$mailerid);

        $sql1="SELECT STATUS FROM MAIN_MAILER  WHERE MAILER_ID IN ('$tested')";
        $result1=mysql_query($sql1) or die("could not get valid mailers ".mysql_error());
        $row1=mysql_fetch_array($result1);
        $status=$row1['STATUS'];
        if($status=='nok')
	{
//	        $sql="UPDATE MAIN_MAILER SET STATUS='run' WHERE MAILER_ID IN ('$tested') ";
//	        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        }
	elseif($status=='rok')
	{                                                                                        
//	        $sql="UPDATE MAIN_MAILER SET STATUS='res' WHERE MAILER_ID IN ('$tested')";
//        	mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	}
	elseif($status=='ook')
        {
//              $sql="UPDATE MAIN_MAILER SET STATUS='orn' WHERE MAILER_ID IN ('$tested')";
//              mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        }
	$sql="UPDATE MAIN_MAILER SET S1_FIRE='Y' WHERE MAILER_ID IN ('$tested')";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());

	$sql="UPDATE MAIN_MAILER SET S2_FIRE='Y' WHERE MAILER_ID IN ('$tested')";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());

	header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_fire_menu.php?cid=$cid");	
}
elseif($stop)
{
        foreach( $_POST as $key => $value )
        {
                if( substr($key, 0, 7) == "running" )
                {
                        $cnt=$cnt+1;
                        $mid = ltrim($key, "running");
                        $mailerid[] = $mid;
                }
        }
        $running=implode("','",$mailerid);

	$sql1="SELECT STATUS FROM MAIN_MAILER  WHERE MAILER_ID IN ('$running')";
        $result1=mysql_query($sql1) or die("could not get valid mailers ".mysql_error());
        $row1=mysql_fetch_array($result1);
        $status=$row1['STATUS'];
	if($status=='run')
	{
//	        $sql="UPDATE MAIN_MAILER SET STATUS='kil' WHERE MAILER_ID IN ('$running')";
//	        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        }
	elseif($status=='res')
	{                                                                                        
//	        $sql="UPDATE MAIN_MAILER SET STATUS='kil' WHERE MAILER_ID IN ('$running')";
//	        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	}
	elseif($status=='orn')
        {
//              $sql="UPDATE MAIN_MAILER SET STATUS='kil' WHERE MAILER_ID IN ('$running')";
//              mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        }

	$sql="UPDATE MAIN_MAILER SET STOP='Y' WHERE MAILER_ID IN ('$running')";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_fire_menu.php?cid=$cid");	
}
elseif($add)
{
        foreach( $_POST as $key => $value )
        {
                if( substr($key, 0, 15) == "add_to_complete" )
                {
                        $cnt=$cnt+1;
                        $mid = ltrim($key, "add_to_complete");
                        $mailerid[] = $mid;
                }
        }
        $add_to_complete=implode("','",$mailerid);
                                                                                                 
        $sql="UPDATE MAIN_MAILER SET STATUS='old' WHERE MAILER_ID IN ('$add_to_complete') ";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_fire_menu.php?cid=$cid");

// add code for refreshing sent fields of the s1 and s2 table here//
	foreach($mailerid as $mailer_id)
	{
	        $table_name=$mailer_id."mailer_s1";
        	$sql="UPDATE $table_name SET SENT=0 ";
	        mysql_query($sql) or die("Sql : $sql \n Error :".mysql_error());
	}
	foreach($mailerid as $mailer_id)
        {
                $table_name=$mailer_id."mailer_s2";
                $sql="UPDATE $table_name SET SENT=0 ";
                mysql_query($sql) or die("Sql : $sql \n Error :".mysql_error());
        }
}
else
{
        $comp=get_complete_mailers();
	$tested=get_tested_mailers();
        $run=get_running_mailers();
//	$incomp=get_incomplete_mailers();
	$exe_test=get_execution_test_mailers();
        $exe_fire=get_execution_fire_mailers();
        $exe_stop=get_execution_stop_mailers();
	$ran=get_ran_mailers();
                                                                                         
        $smarty->assign("comp",$comp);
	$smarty->assign("tested",$tested);
        $smarty->assign("run",$run);
	$smarty->assign("ran",$ran);
//      $smarty->assign("incomp",$incomp);
	$smarty->assign("exe_test",$exe_test);
        $smarty->assign("exe_fire",$exe_fire);
        $smarty->assign("exe_stop",$exe_stop);

        $smarty->assign("cid",$cid);
        $smarty->display("mmm_fire_menu.htm");
}
//*************************MAIN MODULE ENDS HERE**************************************//

?>
