<?php
include("../mis/connect.inc");
//$db=mysql_connect("localhost","root","Km7Iv80l");
$db=connect_master();
mysql_select_db("billing",$db);
//$db2=mysql_connect("localhost","root","Km7Iv80l");
//mysql_select_db("jsadmin",$db2);
//$checksum = $cid;
if(authenticated($checksum))
{
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {

        $ts=time();
	$today=date('Y-m-d G:i:s',$ts);
        //$ts+=7*24*60*60;
        //$scheduled_time=date('Y-m-d h:i:s',$ts);
	//$user=array('a','b','c');
	// $cnt_executive=count($user);
	//echo $cnt_executive;
   if($submit)
   {
	if($allot)
	{
	    if($executive!='CHOOSE THE EXECUTIVE')
	    {
		$n=0;
	/*	$sql="SELECT a.PROFILEID,a.USERNAME,a.ENTRY_DT FROM billing.PURCHASES AS a LEFT JOIN billing.MATRI_PROFILE AS b ON a.PROFILEID = b.PROFILEID WHERE b.PROFILEID IS NULL AND a.STATUS = 'DONE' AND ( SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M')";	*/
		$sql="SELECT PROFILEID,USERNAME,ENTRY_DT FROM PURCHASES WHERE STATUS = 'DONE' AND ( SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M') order by ENTRY_DT";
		$res=mysql_query_decide($sql);
		$k=0;
		while($row=mysql_fetch_array($res))
		{
		     $profileid=$row['PROFILEID'];
		     if($profileid==$allot[$n])
		     {
		        $entry_dt=$row['ENTRY_DT'];
		        $sql1="SELECT COUNT(PROFILEID) cnt,MAX(ENTRY_DT) mdt FROM MATRI_PROFILE WHERE PROFILEID='$profileid'";
		        $res1=mysql_query_decide($sql1);
		        $row1=mysql_fetch_array($res1);
		        $cnt=$row1['cnt'];
		        $m_entry_dt=$row1['mdt'];
		        if($cnt==0)
		        {
                                $username=$row['USERNAME'];//echo $entry_dt;
                                $scheduled_time=date("Y-m-d G:i:s", JSstrToTime($entry_dt)+7*24*60*60);
                                $sql_ins="INSERT INTO MATRI_PROFILE(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,SCHEDULED_TIME,ENTRY_DT,STATUS) VALUES($allot[$n],'$username','$executive','$today','$scheduled_time','$entry_dt','N')";
				$res_ins=mysql_query_decide($sql_ins) or die(mysql_error_js());
                                $n++;
		        }
		        else
		        {
                		if($entry_dt>$m_entry_dt)
		                {
                		        //if($profileid==$allot[$n])
		                        //{
                		                $username=$row['USERNAME'];
		                                $scheduled_time=date("Y-m-d G:i:s", JSstrToTime($entry_dt)+7*24*60*60);
                		                $sql_ins="UPDATE MATRI_PROFILE SET ENTRY_DT='$entry_dt',ALLOT_TIME='$today',STATUS='N',SCHEDULED_TIME='$scheduled_time' WHERE PROFILEID='$profileid'";
                                		$res_ins=mysql_query_decide($sql_ins) or die(mysql_error_js());
		                                $n++;
                		        //}
		                }
		        }
		       }      
		}
	     }

		/*$res=mysql_query_decide($sql);
		while($row=mysql_fetch_array($res))
		{	
			$proid=$row['PROFILEID'];
			if($proid==$allot[$n])
			{
				$entry_dt=$row['ENTRY_DT'];
				$username=$row['USERNAME'];
				$scheduled_time=date("Y-m-d h:i:s", JSstrToTime($entry_dt)+7*24*60*60);
				$sql_ins="INSERT INTO MATRI_PROFILE(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,SCHEDULED_TIME,ENTRY_DT,STATUS) VALUES($allot[$n],'$username','$executive','$today','$scheduled_time','$entry_dt','N')";
				$res_ins=mysql_query_decide($sql_ins) or die(mysql_error_js());				
				$n++;
			}
		}
	    }*/
	     else
	    {
		$smarty->assign("b",1);
		$smarty->assign("emsg",'Please choose the executive');
	    }
	}
	else
	{
		$smarty->assign("a",1);
	}
   }
   
   
	/*$sql="SELECT a.PROFILEID, a.USERNAME, a.ENTRY_DT, b.SCHEDULED_TIME FROM billing.PURCHASES AS a LEFT JOIN billing.MATRI_PROFILE AS b ON a.PROFILEID = b.PROFILEID WHERE b.PROFILEID IS NULL AND a.STATUS = 'DONE' AND ( SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M') ORDER BY ENTRY_DT";

	$res=mysql_query_decide($sql);	
	$k=0;
    		while($row=mysql_fetch_array($res))//Unallotted profiles
		{
				$smarty->assign("flag",1);
			        $unallotted[$k]['SNO']=$k+1;	                
				$unallotted[$k]['PROFILEID']=$row['PROFILEID'];
        		        $unallotted[$k]['USERNAME']=$row['USERNAME'];
                		$unallotted[$k]['ENTRY_DT']=$row['ENTRY_DT'];
				$scheduled_time=date("Y-m-d h:i:s", JSstrToTime($row['ENTRY_DT'])+7*24*60*60);
				$unallotted[$k]['SCHEDULED_TIME']=$scheduled_time;
				$k++;

		}*/
$sql="SELECT PROFILEID,USERNAME,ENTRY_DT FROM PURCHASES WHERE STATUS = 'DONE' AND ( SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M') ORDER BY ENTRY_DT";
$res=mysql_query_decide($sql);
$k=0;
while($row=mysql_fetch_array($res))
{
        $profileid=$row['PROFILEID'];
        $entry_dt=$row['ENTRY_DT'];
        $sql1="SELECT COUNT(PROFILEID) cnt,MAX(ENTRY_DT) mdt FROM MATRI_PROFILE WHERE PROFILEID='$profileid'";
        $res1=mysql_query_decide($sql1);
        $row1=mysql_fetch_array($res1);
        $cnt=$row1['cnt'];
        $m_entry_dt=$row1['mdt'];
        if($cnt==0)
        {
		$smarty->assign("flag",1);
                $unallotted[$k]['SNO']=$k+1;
                $unallotted[$k]['PROFILEID']=$row['PROFILEID'];
                $unallotted[$k]['USERNAME']=$row['USERNAME'];
                $unallotted[$k]['ENTRY_DT']=$row['ENTRY_DT'];
                $scheduled_time=date("Y-m-d G:i:s", JSstrToTime($row['ENTRY_DT'])+7*24*60*60);
                $unallotted[$k]['SCHEDULED_TIME']=$scheduled_time;
                $k++;
        }
        else
        {
                if($entry_dt>$m_entry_dt)
                {
			$smarty->assign("flag",1);
                        $unallotted[$k]['SNO']=$k+1;
                        $unallotted[$k]['PROFILEID']=$row['PROFILEID'];
                        $unallotted[$k]['USERNAME']=$row['USERNAME'];
                        $unallotted[$k]['ENTRY_DT']=$row['ENTRY_DT'];
                        $scheduled_time=date("Y-m-d G:i:s", JSstrToTime($row['ENTRY_DT'])+7*24*60*60);
                        $unallotted[$k]['SCHEDULED_TIME']=$scheduled_time;
                        $k++;
                }
        }
}

	$sql2="SELECT DISTINCT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE REGEXP 'MPU'";
	//$sql2="SELECT DISTINCT ALLOTTED_TO FROM MATRI_PROFILE ORDER BY ALLOTTED_TO";
        $res2=mysql_query_decide($sql2);
        $i=0;
        while($row2=mysql_fetch_array($res2))
        {
		$allotted_to[$i]['SNO']=$i+1;
		$allotted_to[$i]['NAME']=$row2['USERNAME'];//echo $row2['USERNAME'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$row2[USERNAME]' AND STATUS='N'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $allotted_to[$i]['CNT_ONPROGRESS']=$row4['cnt'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$row2[USERNAME]' AND STATUS='H'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $allotted_to[$i]['CNT_ONHOLD']=$row4['cnt'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$row2[USERNAME]' AND STATUS='F'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $allotted_to[$i]['CNT_FOLLOWUP']=$row4['cnt'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$row2[USERNAME]' AND STATUS='Y'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $allotted_to[$i]['CNT_COMPLETED']=$row4['cnt'];
		$i++;
	}

       /* $sql2="SELECT DISTINCT ALLOTTED_TO FROM MATRI_PROFILE ORDER BY ALLOTTED_TO";
        $res2=mysql_query_decide($sql2);
        $i=0;
        while($row2=mysql_fetch_array($res2))
        {
		$allotted[$i]['SNO']=$i+1;
                $allotted_to[$i]=$row2['ALLOTTED_TO'];
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to[$i]' AND STATUS='N'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $cnt_onprogress=$row4['cnt'];
                $allotted[$i]['CNT_ONPROGRESS']=$cnt_onprogress;
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to[$i]' AND STATUS='H'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);
                $cnt_onhold=$row4['cnt'];
                $allotted[$i]['CNT_ONHOLD']=$cnt_onhold;                                
                $sql4="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to[$i]' AND STATUS='Y'";
                $res4=mysql_query_decide($sql4);
                $row4=mysql_fetch_array($res4);                                        
	        $cnt_completed=$row4['cnt'];
                $allotted[$i]['CNT_COMPLETED']=$cnt_completed;
                $sql3="SELECT PROFILEID,USERNAME,ENTRY_DT,ALLOT_TIME,SCHEDULED_TIME,STATUS FROM MATRI_PROFILE WHERE ALLOTTED_TO='$allotted_to[$i]'";
                $res3=mysql_query_decide($sql3);
                $j=0;
                while($row3=mysql_fetch_array($res3))//Allotted profiles
                {
			$allotted[$i][$j]['SNo']=$j+1;
                        $allotted[$i][$j]['PROFILEID']=$row3['PROFILEID'];
                        $allotted[$i][$j]['USERNAME']=$row3['USERNAME'];
                        $allotted[$i][$j]['ENTRY_DT']=$row3['ENTRY_DT'];
                        $allotted[$i][$j]['ALLOT_TIME']=$row3['ALLOT_TIME'];
                        $allotted[$i][$j]['SCHEDULED_TIME']=$row3['SCHEDULED_TIME'];
                        $allotted[$i][$j]['STATUS']=$row3['STATUS'];
                        $j++;
                 }
                 $i++;
        }
	$m=0;
	$sql="SELECT * FROM MATRI_PROFILE WHERE STATUS='Y'";
	$res=mysql_query_decide($sql);
	while($row=mysql_fetch_array($res))//Completed profiles
	{
		$completed[$m]['PROFILEID']=$row['PROFILEID'];
		$completed[$m]['USERNAME']=$row['USERNAME'];
		$completed[$m]['ENTRY_DT']=$row['ENTRY_DT'];
		$completed[$m]['ALLOTTED_TO']=$row['ALLOTTED_TO'];
		$completed[$m]['ALLOT_TIME']=$row['ALLOT_TIME'];
		$completed[$m]['COMPLETION_TIME']=$row['COMPLETION_TIME'];
		$completed[$m]['SNO']=$m+1;
		$m++;
	}*/
	$sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE STATUS='N'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$smarty->assign("onprogress",$row['cnt']);
	$sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE STATUS='H'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$smarty->assign("onhold",$row['cnt']);
	$sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE STATUS='V'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$smarty->assign("Completed",$row['cnt']);
	$sql="SELECT COUNT(*) cnt FROM MATRI_PROFILE WHERE STATUS='F'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$smarty->assign("followup",$row['cnt']);
	/*$sql="SELECT COUNT(PROFILEID)+$k total_profiles FROM MATRI_PROFILE";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$smarty->assign("total_profiles",$row['total_profiles']);*/
	$smarty->assign("unallotted",$unallotted);
	//$smarty->assign("completed",$completed);
	//$smarty->assign("allotted",$allotted);
	$smarty->assign("allotted_to",$allotted_to);
	$smarty->assign("cnt_unallotted",$k);
	$smarty->assign("cnt_executive",$i);
        $smarty->assign("checksum",$checksum);
	//$smarty->assign("allot",$allot)
        $smarty->display("show_matriprofile.htm");			

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

