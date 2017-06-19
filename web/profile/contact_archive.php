<?php
include("connect.inc");
$db=connect_db();
$data=authenticated($checksum);
if($data)
{
	$profileid = $data["PROFILEID"];
        $sql_pid= "SELECT PROFILEID,IPADD,ENTRY_DT FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
        $res_pid= mysql_query_decide($sql_pid) or die(mysql_error_js());
	$row_pid= mysql_fetch_assoc($res_pid);
        $pid= $row_pid['PROFILEID'];
	$ip_r=$row_pid['IPADD'];
	$dt_r=$row_pid['ENTRY_DT'];
	//EMAIL
	$field = "EMAIL";
        $sql= "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid' AND FIELD='$field'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)>0)
        {
		$row= mysql_fetch_assoc($res);
                $changeid= $row['CHANGEID'];
                $sql1="SELECT DATE,IPADD,NEW_VAL,OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $res1=mysql_query_decide($sql1) or die(mysql_error_js());
                $i=0;
		$j=0;
                while($row1=mysql_fetch_array($res1))
                {
			if($j)
			{
				$date=explode(' ',$row1['DATE']);
				$dt= explode('-',$date[0]);
				$dt_rev=array_reverse($dt);
				$dt=implode('-',$dt_rev);
				$val= addslashes(stripslashes($row1['NEW_VAL']));
				$val = str_replace("\r\n"," ",trim(htmlentities($val)));
				$info[$i]=array('date'=>$dt,
					'ip'=>$row1['IPADD'],
					'val'=>$val);
				$i++;
			}
			else
				$j++;
                }
		$query   = "SELECT OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
		$result  = mysql_query_decide($query) or die('Error, query failed');
		while($row= mysql_fetch_array($result))
		{
			$val=addslashes(stripslashes($row['OLD_VAL']));
			$val = str_replace("\r\n"," ",trim(htmlentities($val)));
			$oldval = $val;
		}
		if($oldval!='')
		{
			{
				$dat=explode(" ",$dt_r);
				$dt= explode('-',$dat[0]);
                        	$dt_rev=array_reverse($dt);
                        	$dt=implode('-',$dt_rev);
				$info[$i]=array('date'=>$dt,
                                'ip'=>$ip_r,
                                'val'=>$oldval);				
			}
		}
	}
        $smarty->assign("info_email",$info);
	//EMAIL
	unset($info);
	//PHONE_MOB
        $field = "PHONE_MOB";
        $sql= "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid' AND FIELD='$field'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)>0)
        {
                $row= mysql_fetch_assoc($res);
                $changeid= $row['CHANGEID'];
                $sql1="SELECT DATE,IPADD,NEW_VAL,OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $res1=mysql_query_decide($sql1) or die(mysql_error_js());
                $i=0;
		$j=0;
                while($row1=mysql_fetch_array($res1))
                {
			if($j)
			{
				$date=explode(' ',$row1['DATE']);
				$dt= explode('-',$date[0]);
				$dt_rev=array_reverse($dt);
				$dt=implode('-',$dt_rev);
				$val= addslashes(stripslashes($row1['NEW_VAL']));
				$val = str_replace("\r\n"," ",trim(htmlentities($val)));
				$info[$i]=array('date'=>$dt,
					'ip'=>$row1['IPADD'],
					'val'=>$val);
				$i++;
			}
			else
				$j++;
                }
                $query   = "SELECT OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $result  = mysql_query_decide($query) or die('Error, query failed');
                while($row= mysql_fetch_array($result))
                {
                        $val=addslashes(stripslashes($row['OLD_VAL']));
                        $val = str_replace("\r\n"," ",trim(htmlentities($val)));
                        $oldval = $val;
                }
                if($oldval!='')
                {
                        {
                                $dat=explode(" ",$dt_r);
                                $dt= explode('-',$dat[0]);
                                $dt_rev=array_reverse($dt);
                                $dt=implode('-',$dt_rev);
                                $info[$i]=array('date'=>$dt,
                                'ip'=>$ip_r,
                                'val'=>$oldval);
                        }
                }
        }
        $smarty->assign("info_mob",$info);
        //PHONE_MOB
	unset($info);
	//PHONE_RES	
        $field = "PHONE_RES";
        $sql= "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid' AND FIELD='$field'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)>0)
        {
                $row= mysql_fetch_assoc($res);
                $changeid= $row['CHANGEID'];
                $sql1="SELECT DATE,IPADD,NEW_VAL,OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $res1=mysql_query_decide($sql1) or die(mysql_error_js());
                $i=0;
		$j=0;
                while($row1=mysql_fetch_array($res1))
                {
			if($j)
			{
				$date=explode(' ',$row1['DATE']);
				$dt= explode('-',$date[0]);
				$dt_rev=array_reverse($dt);
				$dt=implode('-',$dt_rev);
				$val= addslashes(stripslashes($row1['NEW_VAL']));
				$val = str_replace("\r\n"," ",trim(htmlentities($val)));
				$info[$i]=array('date'=>$dt,
					'ip'=>$row1['IPADD'],
					'val'=>$val);
				$i++;
			}
			else
				$j++;
                }
                $query   = "SELECT OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $result  = mysql_query_decide($query) or die('Error, query failed');
                while($row= mysql_fetch_array($result))
                {
                        $val=addslashes(stripslashes($row['OLD_VAL']));
                        $val = str_replace("\r\n"," ",trim(htmlentities($val)));
                        $oldval = $val;
                }
                if($oldval!='')
                {
                        {
                                $dat=explode(" ",$dt_r);
                                $dt= explode('-',$dat[0]);
                                $dt_rev=array_reverse($dt);
                                $dt=implode('-',$dt_rev);
                                $info[$i]=array('date'=>$dt,
                                'ip'=>$ip_r,
                                'val'=>$oldval);
                        }
                }
        }
        $smarty->assign("info_res",$info);
        //PHONE_RES
	unset($info);
	//CONTACT     
        $field = "CONTACT";
        $sql= "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid' AND FIELD='$field'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)>0)
        {
                $row= mysql_fetch_assoc($res);
                $changeid= $row['CHANGEID'];
                $sql1="SELECT DATE,IPADD,NEW_VAL,OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $res1=mysql_query_decide($sql1) or die(mysql_error_js());
                $i=0;
		$j=0;
                while($row1=mysql_fetch_array($res1))
                {
			if($j)
			{
				$date=explode(' ',$row1['DATE']);
				$dt= explode('-',$date[0]);
				$dt_rev=array_reverse($dt);
				$dt=implode('-',$dt_rev);
				$val= addslashes(stripslashes($row1['NEW_VAL']));
				$val = str_replace("\r\n"," ",trim(htmlentities($val)));
				$info[$i]=array('date'=>$dt,
					'ip'=>$row1['IPADD'],
					'val'=>$val);
				$i++;
			}
			else
				$j++;
                }
                $query   = "SELECT OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $result  = mysql_query_decide($query) or die('Error, query failed');
                while($row= mysql_fetch_array($result))
                {
                        $val=addslashes(stripslashes($row['OLD_VAL']));
                        $val = str_replace("\r\n"," ",trim(htmlentities($val)));
			$oldval = $val;
                }
                if($oldval!='')
                {
                        {
                                $dat=explode(" ",$dt_r);
                                $dt= explode('-',$dat[0]);
                                $dt_rev=array_reverse($dt);
                                $dt=implode('-',$dt_rev);
                                $info[$i]=array('date'=>$dt,
                                'ip'=>$ip_r,
                                'val'=>$oldval);
                        }
                }
        }
        $smarty->assign("info_contact",$info);
        //CONTACT
	unset($info);
	//PARENTS_CONTACT     
        $field = "PARENTS_CONTACT";
        $sql= "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid' AND FIELD='$field'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)>0)
        {
                $row= mysql_fetch_assoc($res);
                $changeid= $row['CHANGEID'];
                $sql1="SELECT DATE,IPADD,NEW_VAL,OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $res1=mysql_query_decide($sql1) or die(mysql_error_js());
                $i=0;
		$j=0;
                while($row1=mysql_fetch_array($res1))
                {
			if($j)
			{
				$date=explode(' ',$row1['DATE']);
				$dt= explode('-',$date[0]);
				$dt_rev=array_reverse($dt);
				$dt=implode('-',$dt_rev);
				$val= addslashes(stripslashes($row1['NEW_VAL']));
				$val = str_replace("\r\n"," ",trim(htmlentities($val)));
				$info[$i]=array('date'=>$dt,
					'ip'=>$row1['IPADD'],
					'val'=>$val);
				$i++;
			}
			else
				$j++;
                }
                $query   = "SELECT OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $result  = mysql_query_decide($query) or die('Error, query failed');
                while($row= mysql_fetch_array($result))
                {
                        $val=addslashes(stripslashes($row['OLD_VAL']));
                        $val = str_replace("\r\n"," ",trim(htmlentities($val)));
			$oldval = $val;
                }
                if($oldval!='')
                {
                        {
                                $dat=explode(" ",$dt_r);
                                $dt= explode('-',$dat[0]);
                                $dt_rev=array_reverse($dt);
                                $dt=implode('-',$dt_rev);
                                $info[$i]=array('date'=>$dt,
                                'ip'=>$ip_r,
                                'val'=>$oldval);
                        }
                }
        }
        $smarty->assign("info_pcontact",$info);
        //PARENTS_CONTACT
	unset($info);
	//MESSENGER     
        $field = "MESSENGER";
        $sql= "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid' AND FIELD='$field'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        if(mysql_num_rows($res)>0)
        {
                $row= mysql_fetch_assoc($res);
                $changeid= $row['CHANGEID'];
                $sql1="SELECT DATE,IPADD,NEW_VAL,OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $res1=mysql_query_decide($sql1) or die(mysql_error_js());
                $i=0;
		$j=0;
                while($row1=mysql_fetch_array($res1))
                {
			if($j)
			{
				$date=explode(' ',$row1['DATE']);
				$dt= explode('-',$date[0]);
				$dt_rev=array_reverse($dt);
				$dt=implode('-',$dt_rev);
				$val= addslashes(stripslashes($row1['NEW_VAL']));
				$val = str_replace("\r\n"," ",trim(htmlentities($val)));
				$val1=explode('@',$row1['NEW_VAL']);
				if($val1[1]==1)
					$val=$val1[0]."(Yahoo)";
				elseif($val1[1]==2)
					$val=$val1[0]."(MSN)";
				elseif($val1[1]==3)
					$val=$val1[0]."(Skype)";
				elseif($val1[1]==4)
					$val=$val1[0]."(Others)";
				elseif($val1[1]==5)
					$val=$val1[0]."(ICQ)";
				elseif($val1[1]==6)
					$val=$val1[0]."(Google Talk)";
				elseif($val1[1]==7)
					$val=$val1[0]."(Rediff Bol)";
				$info[$i]=array('date'=>$dt,
					'ip'=>$row1['IPADD'],
					'val'=>$val);
				$i++;
			}
			else
				$j++;
                }
                $query   = "SELECT OLD_VAL FROM newjs.CONTACT_ARCHIVE_INFO WHERE CHANGEID='$changeid' ORDER BY DATE DESC";
                $result  = mysql_query_decide($query) or die('Error, query failed');
                while($row= mysql_fetch_array($result))
                {
                        $val=addslashes(stripslashes($row['OLD_VAL']));
                        $val = str_replace("\r\n"," ",trim(htmlentities($val)));
			$val1=explode('@',$row['OLD_VAL']);
			if($val1[1]==1)
				$val=$val1[0]."(Yahoo)";
			elseif($val1[1]==2)
				$val=$val1[0]."(MSN)";
			elseif($val1[1]==3)
				$val=$val1[0]."(Skype)";
			elseif($val1[1]==3)
				$val=$val1[0]."(Others)";
			elseif($val1[1]==5)
				$val=$val1[0]."(ICQ)";
			elseif($val1[1]==6)
				$val=$val1[0]."(Google Talk)";
			elseif($val1[1]==7)
				$val=$val1[0]."(Rediff Bol)";
                        $oldval = $val;
                }
                if($oldval!='')
                {
                        {
                                $dat=explode(" ",$dt_r);
                                $dt= explode('-',$dat[0]);
                                $dt_rev=array_reverse($dt);
                                $dt=implode('-',$dt_rev);
                                $info[$i]=array('date'=>$dt,
                                'ip'=>$ip_r,
                                'val'=>$oldval);
                        }
                }
        }
        $smarty->assign("info_messenger",$info);
        //MESSENGER
	unset($info);
}
	$smarty->assign("profileid",$profileid);
	$smarty->assign("checksum",$checksum);
	$smarty->display("profile_edit_view_archive.htm");
?>
