<?php
//print_r($_SERVER);
include_once("connect.inc");
//for preventing timeout to maximum possible
ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 days
ini_set(log_errors_max_len,0);
//for preventing timeout to maximum possible
mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db);
if (authenticated($cid))
{
        $now=time();
        $now+=60*60;
        $today=date("Y-m-d",$now)." 23:59:59";
        $name= getname($cid);

//        $sql ="SELECT newjs.JPROFILE.USERNAME as user,incentive.MAIN_ADMIN_POOL.SCORE as score FROM incentive.MAIN_ADMIN,incentive.MAIN_ADMIN_POOL,newjs.JPROFILE,userplane.users WHERE newjs.JPROFILE.PROFILEID=incentive.MAIN_ADMIN_POOL.PROFILEID AND incentive.MAIN_ADMIN_POOL.PROFILEID=userplane.users.userID and incentive.MAIN_ADMIN_POOL.PROFILEID <>incentive.MAIN_ADMIN.PROFILEID ";
//	$sql ="SELECT newjs.JPROFILE.USERNAME as user,incentive.MAIN_ADMIN_POOL.SCORE as score FROM incentive.MAIN_ADMIN,incentive.MAIN_ADMIN_POOL,newjs.JPROFILE,userplane.users WHERE newjs.JPROFILE.PROFILEID=incentive.MAIN_ADMIN_POOL.PROFILEID AND incentive.MAIN_ADMIN_POOL.PROFILEID=userplane.users.userID and incentive.MAIN_ADMIN.PROFILEID is null" ;
//	$sql= "SELECT D.USERNAME as user,C.SCORE as score FROM userplane.users_chk A, incentive.MAIN_ADMIN B, incentive.MAIN_ADMIN_POOL C, newjs.JPROFILE D WHERE A.userID = C.PROFILEID AND A.userId = D.PROFILEID AND B.PROFILEID is null";
//	$sql ="SELECT D.USERNAME as user,C.SCORE as score FROM  userplane.users_chk A left JOIN incentive.MAIN_ADMIN_POOL C on  A.userID = C.PROFILEID left JOIN newjs.JPROFILE D on A.userId = D.PROFILEID LEFT JOIN incentive.MAIN_ADMIN B ON A.userID = B.PROFILEID WHERE D.ACTIVATED <> 'Y' and B.PROFILEID is null";
	//$sql ="SELECT sql_cache D.USERNAME as user,C.SCORE as score FROM  userplane.users A left JOIN incentive.MAIN_ADMIN_POOL C on  A.userID = C.PROFILEID left JOIN newjs.JPROFILE D on A.userId = D.PROFILEID LEFT JOIN incentive.MAIN_ADMIN B ON A.userID = B.PROFILEID WHERE D.ACTIVATED <> 'Y' and B.PROFILEID is null order by score desc";
	$sql ="SELECT C.PROFILEID as ID,C.SCORE as score FROM userplane.users A left JOIN incentive.MAIN_ADMIN_POOL C on  A.userID = C.PROFILEID left JOIN incentive.MAIN_ADMIN B ON A.userID = B.PROFILEID WHERE B.PROFILEID is null order by score desc";
        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
$i=0;
        while($myrow = mysql_fetch_array($result))
        {
		$SQL="SELECT USERNAME AS user,SUBSCRIPTION AS SUB FROM newjs.JPROFILE WHERE PROFILEID='$myrow[ID]'";
                $result1=mysql_query_decide($SQL,$db) or die(mysql_error($db));
                $RES=mysql_fetch_array($result1);
		if($RES['user']!='' && $RES['SUB']=='')
		{
			$arr[$i]['seq']=$i+1;
                	$arr[$i]['score']=$myrow['score'];
	                $arr[$i]['user']=$RES['user'];
			$i++;
		}

        }
        $smarty->assign("cid",$cid);
        $smarty->assign("score",$arr);
         $smarty->assign("name",$name);
        $smarty->display("inbound_online.htm");
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

//        $TOTALREC = $myrow[0];
?>

