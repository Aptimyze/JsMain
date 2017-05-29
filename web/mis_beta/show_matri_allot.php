<?php
include("../mis/connect.inc");
        $db=mysql_connect("localhost","root","Km7Iv80l");
        mysql_select_db("billing",$db);
	//$db2=mysql_connect("localhost","root","Km7Iv801");
	//mysql_select_db("newjs",$db2);
	//$db=connect_master();
	//$checksum=$cid;
if(authenticated($checksum))
{
   	$user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {

	$ts=time();
	$today=date('Y-m-d G:i:s',$ts);
	if($add_rcv)
	{
		if($rcv_time=='0000-00-00 00:00:00')
		{
			$smarty->assign("c",1);//echo "error";
		}
		else
		{
			if($rcv_time>$today)
			{
				$smarty->assign("c",2);
			}
			else
			{
				$sql="UPDATE MATRI_FOLLOWUP SET RCV_TIME='$rcv_time' where PROFILEID='$profileid' and FOLLOWUP_TIME='$followup_time'";
				mysql_query_decide($sql);
				$smarty->assign("rcv",1);
			}
		}
	}
	if($add_call)
	{
		if($pfollow_time=='0000-00-00 00:00:00')
		{
			$smarty->assign("c",3);//echo "error";
		}
		else
		{
                        if($pfollow_time>$today)                         
			{
                                $smarty->assign("c",2);
                        }
                        else                     
			{
				$sql="UPDATE MATRI_FOLLOWUP SET PFOLLOWUP_TIME='$pfollow_time' WHERE PROFILEID='$profileid' AND FOLLOWUP_TIME='$followup_time'";
				mysql_query_decide($sql);
				$smarty->assign("follow",1);
			}
		}
	}
		$allotted_to=$user;
                $sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='N'";
                $res=mysql_query_decide($sql);
                $row=mysql_fetch_array($res);
                $cnt_onprogress=$row['cnt'];
                $sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='H'";
                $res=mysql_query_decide($sql);
                $row=mysql_fetch_array($res);
                $cnt_onhold=$row['cnt'];
                $sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='Y'";
                $res=mysql_query_decide($sql);
                $row=mysql_fetch_array($res);
                $cnt_completed=$row['cnt'];
                $sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='F'";
                $res=mysql_query_decide($sql);
                $row=mysql_fetch_array($res);
                $cnt_followup=$row['cnt'];
                $sql="SELECT ID,PROFILEID,USERNAME,ENTRY_DT,ALLOT_TIME,SCHEDULED_TIME,STATUS FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' and STATUS='N'";
                $res=mysql_query_decide($sql);
                $i=0;
                while($row=mysql_fetch_array($res))//Allotted profiles
                {
                        $smarty->assign("allot",1);
                        $allotted[$i]['SNo']=$i+1;
			$allotted[$i]['ID']=$row['ID'];
                        $allotted[$i]['PROFILEID']=$row['PROFILEID'];
                        $allotted[$i]['USERNAME']=$row['USERNAME'];
                        $allotted[$i]['ENTRY_DT']=$row['ENTRY_DT'];
                        $allotted[$i]['ALLOT_TIME']=$row['ALLOT_TIME'];
                        $allotted[$i]['SCHEDULED_TIME']=$row['SCHEDULED_TIME'];
                        $allotted[$i]['STATUS']=$row['STATUS'];
			$sql_con="Select EMAIL,PHONE_MOB,PHONE_RES from newjs.JPROFILE where PROFILEID=$row[PROFILEID]";
                        $result_con = mysql_query_decide($sql_con) or die(mysql_error_js());
                        $myrow_con = mysql_fetch_array($result_con);
                        $allotted[$i]['EMAIL']=$myrow_con['EMAIL'];
                        $allotted[$i]['PHONE_MOB']=$myrow_con['PHONE_MOB'];
                        $allotted[$i]['PHONE_RES']=$myrow_con['PHONE_RES'];
			$i++;
                }
                $smarty->assign("cnt_onprogress",$cnt_onprogress);
                $smarty->assign("cnt_onhold",$cnt_onhold);
                $smarty->assign("cnt_completed",$cnt_completed);
                $smarty->assign("cnt_followup",$cnt_followup);
                $smarty->assign("allotted",$allotted);

        $x=0;
        $sql1="SELECT PROFILEID,USERNAME,ENTRY_DT,ALLOTTED_TO,ALLOT_TIME,STATUS FROM MATRI_PROFILE WHERE STATUS='F' and ALLOTTED_TO='$allotted_to'";
        $res1=mysql_query_decide($sql1);
        while($row1=mysql_fetch_array($res1))//Follow Up profiles
        {
                $followup[$x]['PROFILEID']=$row1['PROFILEID'];
                $followup[$x]['USERNAME']=$row1['USERNAME'];
                $followup[$x]['ENTRY_DT']=$row1['ENTRY_DT'];
                $followup[$x]['ALLOTTED_TO']=$row1['ALLOTTED_TO'];
                $followup[$x]['ALLOT_TIME']=$row1['ALLOT_TIME'];
                $followup[$x]['COMPLETION_TIME']=$row1['COMPLETION_TIME'];
                $followup[$x]['STATUS']=$row1['STATUS'];
                $followup[$x]['SNO']=$x+1;
                $sql_con="Select EMAIL,PHONE_MOB,PHONE_RES from newjs.JPROFILE where PROFILEID=$row1[PROFILEID]";
                $result_con = mysql_query_decide($sql_con) or die(mysql_error_js());
                $myrow_con = mysql_fetch_array($result_con);
                $followup[$x]['EMAIL']=$myrow_con['EMAIL'];
                $followup[$x]['PHONE_MOB']=$myrow_con['PHONE_MOB'];
                $followup[$x]['PHONE_RES']=$myrow_con['PHONE_RES'];
		$sql2="SELECT MAX(FOLLOWUP_TIME) mf FROM MATRI_FOLLOWUP WHERE PROFILEID=$row1[PROFILEID]";
		$res2=mysql_query_decide($sql2) or die(mysql_error_js());
		$row2=mysql_fetch_array($res2);
		$followup[$x]['FOLLOWUP_TIME']=$row2['mf'];
		$sql3="SELECT PFOLLOWUP_TIME,RCV_TIME FROM MATRI_FOLLOWUP WHERE FOLLOWUP_TIME='$row2[mf]'";
		$res3=mysql_query_decide($sql3) or die(mysql_error_js());
		$row3=mysql_fetch_array($res3);
		$followup[$x]['PFOLLOWUP_TIME']=$row3['PFOLLOWUP_TIME'];
		$followup[$x]['RCV_TIME']=$row3['RCV_TIME'];
                $x++;
        }
        if(mysql_num_rows($res1)==0)
        {
                $smarty->assign("b",1);
                $smarty->assign("fmsg","No profile is present to follow up under executive $allotted_to");
        }
        $m=0;
        $sql="SELECT B.PROFILEID,B.USERNAME,A.ENTRY_DT,A.ALLOT_TIME,B.ONHOLD_TIME,B.REASON,C.EMAIL,C.PHONE_MOB,C.PHONE_RES FROM MATRI_PROFILE AS A,MATRI_ONHOLD AS B,newjs.JPROFILE AS C  WHERE A.PROFILEID=B.PROFILEID AND A.PROFILEID=C.PROFILEID AND A.STATUS='H' and A.ALLOTTED_TO='$allotted_to'";
        $res=mysql_query_decide($sql);
        while($row=mysql_fetch_array($res))//On Hold profiles
        {
                $onhold[$m]['PROFILEID']=$row['PROFILEID'];
                $onhold[$m]['USERNAME']=$row['USERNAME'];
                $onhold[$m]['ENTRY_DT']=$row['ENTRY_DT'];
                $onhold[$m]['ALLOT_TIME']=$row['ALLOT_TIME'];
                $onhold[$m]['ONHOLD_TIME']=$row['ONHOLD_TIME'];
		$onhold[$m]['REASON']=$row['REASON'];
		$onhold[$m]['EMAIL']=$row['EMAIL'];
		$onhold[$m]['PHONE_MOB']=$row['PHONE_MOB'];
		$onhold[$m]['PHONE_RES']=$row['PHONE_RES'];
                $onhold[$m]['SNO']=$m+1;
                $m++;
        }
        if(mysql_num_rows($res)==0)
        {
                $smarty->assign("a",1);
                $smarty->assign("hmsg","No profile is on hold under executive $allotted_to");
        }
		$smarty->assign("onhold",$onhold);
		$smarty->assign("followup",$followup);
                $smarty->assign("allotted_to",$allotted_to);
                $smarty->assign("completed",$completed);
		$smarty->assign("checksum",$checksum);
                $smarty->display("show_matri_allot.htm");		
        }
        else
        {
                echo "You don't have permission to view this mis";
                die();
        }
}
else
{
        $smarty->display("jsconnectError.tpl");
}

?>

