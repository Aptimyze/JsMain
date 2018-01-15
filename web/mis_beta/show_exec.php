<?php
	include("../mis/connect.inc");
	$db=mysql_connect("localhost","root","Km7Iv80l");
	mysql_select_db("billing",$db);
	//$db2=mysql_connect("localhost","root","Km7Iv80l");
	//mysql_select_db("newjs",$db2);
	//$db=connect_master();
if(authenticated($checksum))
{
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {

	$ts=time();
	$today=date('Y-m-d G:i:s'.$ts);
	if($verify)
	{
		if($Y || $N)
		{
			for($i=0;$i<count($Y);$i++)
			{
				for($j=0;$j<count($N);$j++)
				{
					if($Y[$i]==$N[$j])
					{
						$smarty->assign("c",2);
					}
				}
			}
		}
		else
		{
			$smarty->assign("c",1);
		}
					
			if($Y)
			{
				$sql="SELECT PROFILEID FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='Y'";
				$res=mysql_query_decide($sql);
				while($row=mysql_fetch_array($res))
				{
					$profileid=$row['PROFILEID'];
					for($i=0;$i<count($Y);$i++)
					{
						if($profileid==$Y[$i])
						{
							//echo "Verified<br>";
							$sql1="UPDATE MATRI_PROFILE SET STATUS='V' WHERE PROFILEID='$profileid'";
						 	mysql_query_decide($sql1);
							$sql7="DELETE FROM MATRI_FOLLOWUP WHERE PROFILEID='$profileid'";
							mysql_query_decide($sql7);
							$sql7="DELETE FROM MATRI_ONHOLD WHERE PROFILEID='$profileid'";
							mysql_query_decide($sql7);
							$sql6="SELECT USERNAME FROM MATRI_PROFILE WHERE PROFILEID='$profileid'";
							$res6=mysql_query_decide($sql6);
							$row6=mysql_fetch_array($res6);
							$username=$row6['USERNAME'];
							$verified_by=getname($checksum);
							$sql5="INSERT INTO MATRI_COMPLETED(PROFILEID,USERNAME,ALLOTTED_TO,VERIFIED_BY,VERIFY_DATE) VALUES('$profileid','$username','$allotted_to','$verified_by','$today')";
							mysql_query_decide($sql5);
							$smarty->assign("verified",1);
						}
					}
				}
			}
			if($N)
			{
                                $sql2="SELECT PROFILEID FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='Y'";
                                $res2=mysql_query_decide($sql2);               
                                while($row2=mysql_fetch_array($res2))
                                {
                                        $profileid=$row2['PROFILEID'];
					for($j=0;$j<count($N);$j++)
					{
                                        	if($profileid==$N[$j])
	                                        {
							$sql8="SELECT COUNT(PROFILEID) cnt FROM MATRI_ONHOLD WHERE PROFILEID='$profileid'";			
							$res8=mysql_query_decide($sql8);
							$row8=mysql_fetch_array($res8);
							$cnt=$row8['cnt'];
							if($cnt==0)
							{
								$f_status='F';
							}
							else
							{
								$f_status='H';
							}									                                        	     $sql1="UPDATE MATRI_PROFILE SET STATUS='$f_status' WHERE PROFILEID='$profileid'";
							mysql_query_decide($sql1); 
							$smarty->assign("hold",1);
						}
	                                }
                                }
                        }
		
	}
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='N'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $cnt_onprogress=$row4['cnt'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='H'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $cnt_onhold=$row4['cnt'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='Y'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $cnt_completed=$row4['cnt'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' AND STATUS='F'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $cnt_followup=$row4['cnt'];
                $sql3="SELECT PROFILEID,USERNAME,ENTRY_DT,ALLOT_TIME,SCHEDULED_TIME,STATUS FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to' and STATUS='N' ORDER BY ENTRY_DT";
                $res3=mysql_query_decide($sql3);
                $i=0;
                while($row3=mysql_fetch_array($res3))//Allotted profiles
                {
			$smarty->assign("allot",1);
                        $allotted[$i]['SNo']=$i+1;
                        $allotted[$i]['PROFILEID']=$row3['PROFILEID'];
                        $allotted[$i]['USERNAME']=$row3['USERNAME'];
                        $allotted[$i]['ENTRY_DT']=$row3['ENTRY_DT'];
                        $allotted[$i]['ALLOT_TIME']=$row3['ALLOT_TIME'];
                        $allotted[$i]['SCHEDULED_TIME']=$row3['SCHEDULED_TIME'];
                        $allotted[$i]['STATUS']=$row3['STATUS'];
                        $i++;
                }
        $m=0;
        $sql="SELECT B.PROFILEID,B.USERNAME,A.ENTRY_DT,A.ALLOT_TIME,B.ONHOLD_TIME,B.REASON FROM MATRI_PROFILE AS A,MATRI_ONHOLD AS B WHERE A.PROFILEID=B.PROFILEID AND A.STATUS='H' and A.ALLOTTED_TO='$allotted_to'";
        $res=mysql_query_decide($sql);
        while($row=mysql_fetch_array($res))//On Hold profiles
        {
		$smarty->assign("b",1);
                $onhold[$m]['PROFILEID']=$row['PROFILEID'];
                $onhold[$m]['USERNAME']=$row['USERNAME'];
                $onhold[$m]['ENTRY_DT']=$row['ENTRY_DT'];
                $onhold[$m]['ALLOT_TIME']=$row['ALLOT_TIME'];
                $onhold[$m]['ONHOLD_TIME']=$row['ONHOLD_TIME'];
                $onhold[$m]['REASON']=$row['REASON'];
                $onhold[$m]['SNO']=$m+1;
                $m++;
        }
             	$smarty->assign("cnt_onprogress",$cnt_onprogress);
             	$smarty->assign("cnt_onhold",$cnt_onhold);
             	$smarty->assign("cnt_completed",$cnt_completed);
             	$smarty->assign("cnt_followup",$cnt_followup);
		$smarty->assign("allotted",$allotted);
		$smarty->assign("onhold",$onhold);
        $m=0;
        $sql="SELECT * FROM MATRI_PROFILE WHERE STATUS='Y' and ALLOTTED_TO='$allotted_to'";
        $res=mysql_query_decide($sql);
        while($row=mysql_fetch_array($res))//Completed profiles
        {
                $completed[$m]['PROFILEID']=$row['PROFILEID'];
                $completed[$m]['USERNAME']=$row['USERNAME'];
                $completed[$m]['ENTRY_DT']=$row['ENTRY_DT'];
                //$completed[$m]['ALLOTTED_TO']=$row['ALLOTTED_TO'];
                //$completed[$m]['ALLOT_TIME']=$row['ALLOT_TIME'];
                $completed[$m]['COMPLETION_TIME']=$row['COMPLETION_TIME'];
                $completed[$m]['SNO']=$m+1;
		$sql9="SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
		$res9=mysql_query_decide($sql9);
		$row9=mysql_fetch_array($res9);
		$completed[$m]['EMAIL']=$row9['EMAIL'];		
		$completed[$m]['PHONE_MOB']=$row9['PHONE_MOB'];		
		$completed[$m]['PHONE_RES']=$row9['PHONE_RES'];		
		$sql10="SELECT MAX(CUTS) CUTS FROM MATRI_FOLLOWUP WHERE PROFILEID='$row[PROFILEID]'";
		$res10=mysql_query_decide($sql10);
		$row10=mysql_fetch_array($res10);
		$completed[$m]['CUTS']=$row10['CUTS'];
                $m++;
        }
	if(mysql_num_rows($res)==0)
	{
		$smarty->assign("a",1);
		$smarty->assign("msg","No profile has been completed by executive $allotted_to");
	}
        $m=0;
        $sql="SELECT * FROM MATRI_PROFILE WHERE STATUS='F' and ALLOTTED_TO='$allotted_to'";
        $res=mysql_query_decide($sql);
        while($row=mysql_fetch_array($res))//Followup profiles
        {
		$smarty->assign("follow",1);
                $followup[$m]['PROFILEID']=$row['PROFILEID'];
                $followup[$m]['USERNAME']=$row['USERNAME'];
                $followup[$m]['ENTRY_DT']=$row['ENTRY_DT'];
                $followup[$m]['ALLOT_TIME']=$row['ALLOT_TIME'];
                //$completed[$m]['ALLOTTED_TO']=$row['ALLOTTED_TO'];
                //$completed[$m]['ALLOT_TIME']=$row['ALLOT_TIME'];
                $followup[$m]['COMPLETION_TIME']=$row['COMPLETION_TIME'];
                $followup[$m]['SNO']=$m+1;
                $sql9="SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
                $res9=mysql_query_decide($sql9);
                $row9=mysql_fetch_array($res9);
                $followup[$m]['EMAIL']=$row9['EMAIL'];
                $followup[$m]['PHONE_MOB']=$row9['PHONE_MOB'];
                $followup[$m]['PHONE_RES']=$row9['PHONE_RES'];
                /*$sql10="SELECT MAX(CUTS) CUTS FROM MATRI_FOLLOWUP WHERE PROFILEID='$row[PROFILEID]'";
                $res10=mysql_query_decide($sql10);
                $row10=mysql_fetch_array($res10);
                $completed[$m]['CUTS']=$row10['CUTS'];*/
                $m++;
        }

		$smarty->assign("allotted_to",$allotted_to);
		$smarty->assign("completed",$completed);
		$smarty->assign("followup",$followup);
		$smarty->assign("checksum",$checksum);
		$smarty->display("show_exec.htm");
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
