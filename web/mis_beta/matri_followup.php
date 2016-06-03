<?php
include("../mis/connect.inc");
        $db=mysql_connect("localhost","root","Km7Iv80l");
        mysql_select_db("billing",$db);
        //$db2=mysql_connect("localhost","root","Km7Iv801");
        //mysql_select_db("newjs",$db2);
//$checksum=$cid;
if(authenticated($checksum))
{
        
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {

	if($complete)
	{
		$sql="UPDATE MATRI_PROFILE SET STATUS='Y' WHERE PROFILEID='$profileid'"; 
		$res=mysql_query_decide($sql);
	}
	if($onhold)
	{
		$follow_status='H';
		$smarty->assign("hold",1);
	}
	else
	{
		$follow_status='F';
	}
        $sql1="SELECT USERNAME,ENTRY_DT,ALLOTTED_TO,ALLOT_TIME,STATUS FROM MATRI_PROFILE WHERE STATUS='$follow_status' and PROFILEID='$profileid'";
        $res1=mysql_query_decide($sql1);
	$x=0;
        while($row1=mysql_fetch_array($res1))//Follow Up profiles
        {
                $followup[$x]['PROFILEID']=$profileid;
                $followup[$x]['USERNAME']=$row1['USERNAME'];
                $followup[$x]['ENTRY_DT']=$row1['ENTRY_DT'];
                $followup[$x]['ALLOTTED_TO']=$row1['ALLOTTED_TO'];
                $followup[$x]['ALLOT_TIME']=$row1['ALLOT_TIME'];
                $followup[$x]['COMPLETION_TIME']=$row1['COMPLETION_TIME'];
                $followup[$x]['STATUS']=$row1['STATUS'];
                $followup[$x]['SNO']=$x+1;
                $sql_con="Select EMAIL,PHONE_MOB,PHONE_RES from newjs.JPROFILE where PROFILEID='$profileid'";
                $result_con = mysql_query_decide($sql_con) or die(mysql_error_js());
                $myrow_con = mysql_fetch_array($result_con);
                $followup[$x]['EMAIL']=$myrow_con['EMAIL'];
                $followup[$x]['PHONE_MOB']=$myrow_con['PHONE_MOB'];
                $followup[$x]['PHONE_RES']=$myrow_con['PHONE_RES'];
                $sql2="SELECT FOLLOWUP_TIME,CUTS,PFOLLOWUP_TIME,RCV_TIME FROM MATRI_FOLLOWUP WHERE PROFILEID='$profileid' ORDER BY CUTS";
                $res2=mysql_query_decide($sql2) or die(mysql_error_js());
		$y=0;
                while($row2=mysql_fetch_array($res2))
                {
			$followup[$x][$y]['SNO']=$y+1;
                        $followup[$x][$y]['FOLLOWUP_TIME']=$row2['FOLLOWUP_TIME'];
                        $followup[$x][$y]['PFOLLOWUP_TIME']=$row2['PFOLLOWUP_TIME'];
                        $followup[$x][$y]['RCV_TIME']=$row2['RCV_TIME'];
			$followup[$x][$y]['CUTS']=$row2['CUTS'];
			$y++;
                }
                $x++;
        }
        if(mysql_num_rows($res1)==0)
        {                 $smarty->assign("b",1);
                $smarty->assign("fmsg","No profile is present to follow up under executive $allotted_to");
        } 
	$smarty->assign("username",$followup[$x-1]['USERNAME']);
	$smarty->assign("y",$y);
	$smarty->assign("followup",$followup);
	$smarty->assign("checksum",$checksum);
	$smarty->display("matri_followup.htm");
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
