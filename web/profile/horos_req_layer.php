<?php
include_once("search.inc");
include_once("connect.inc");
include_once("contact.inc");
$db=connect_db();

$dt=date("Y-m-d H:i:s");

global $count;
$count=1;
$data=authenticated();
if($Submit)
{
	if(isset($data))
	{
		$chkprofilechecksum=explode("i",$profilechecksum);
		$profileid=$data['PROFILEID'];

		check_photo_subs($profileid);

		$sql_name="SELECT USERNAME,GENDER FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$chkprofilechecksum[1]'";
		$result_name=mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
		$myrow_name=mysql_fetch_array($result_name);
		$gender=$myrow_name['GENDER'];
		$contact_status=get_contact_status($profileid,$chkprofilechecksum[1]);
                                                                                                                             
        if($contact_status!='RI' && $contact_status!='RA' && $contact_status!='RD' && $contact_status!='A')
        {
			if($gender==$data['GENDER'])
				$samegender=1;
			$filtered=check_privacy_filtered1($profileid,$chkprofilechecksum[1]);
        }
		if($filtered)
		{
			$error='F';
		}
		elseif($samegender)
		{			  
         	$error='G';
		}
		else
		{
			self_astro_details($profileid);
			$flag_show_template=photo_req_common($profileid,$chkprofilechecksum[1],$dt);
			$error=$flag_show_template;
			$memObject=new UserMemcache;
			$memObject->setDataToMem('','commHistory_'.$profileid.'_'.$chkprofilechecksum[1]);
			$memObject->setDataToMem('','commHistory_'.$chkprofilechecksum[1].'_'.$profileid);
		}
		echo $error;die;
	}echo 'ye';die;	
}
else	
{
			
	$smarty->assign("USERNAME",$_GET['view_username']);
	$smarty->assign("profilechecksum",$profilechecksum);
		
	$smarty->display("horos_req_layer.htm");
	if($data['ACTIVATED']!='Y')
		echo "<script>photo_req_end('U');</script>";
}
function check_photo_subs($profileid)
{
	global $smarty;
	$sql="SELECT HAVEPHOTO,SHOW_HOROSCOPE,SUBSCRIPTION FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$res=mysql_query_decide($sql);
	$row=mysql_fetch_array($res);
	$horohave=$row['SHOW_HOROSCOPE'];
	$photohave=$row['HAVEPHOTO'];
        $photosubs=$row['SUBSCRIPTION'];
	if($photohave=='Y' || $photohave=='U')
		$smarty->assign("photohave",'Y');
	if($photosubs=='' || $photosubs=='D')
		$smarty->assign("photosubs",'Y');

	if(!check_astro_details($profileid,$horohave))
	{
		$smarty->assign("horohave",'N');	
	}
}
function photo_req_common($profileid,$chkprofilechecksum,$dt='')
{
        //Sharding Concept added by Lavesh Rawat on table HOROSCOPE_REQUEST
        //affectedDb list of database need to be updated as 2 shards can have same entry.
        $mysqlObj=new Mysql;

	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        $affectedDb[0]=$myDb;

        $myDbName=getProfileDatabaseConnectionName($chkprofilechecksum,'',$mysqlObj);
        $viewedDb=$mysqlObj->connect("$myDbName");
        if(!in_array($viewedDb,$affectedDb))
                $affectedDb[1]=$viewedDb;

	$sql_chk="SELECT CNT FROM HOROSCOPE_REQUEST WHERE PROFILEID='$profileid' and PROFILEID_REQUEST_BY='$chkprofilechecksum'";
        $result_chk = $mysqlObj->executeQuery($sql_chk,$myDb);
        $myrow_chk=$mysqlObj->fetchArray($result_chk);
	if($myrow_chk['CNT'] && $myrow_chk['CNT']<1)
	{
		$sql_update="UPDATE HOROSCOPE_REQUEST SET CNT=CNT+1 WHERE PROFILEID='$profileid' and PROFILEID_REQUEST_BY='$chkprofilechecksum'";
                for($ll=0;$ll<count($affectedDb);$ll++)
                {
                        $tempDb=$affectedDb[$ll];
                        $mysqlObj->executeQuery($sql_update,$tempDb);
                        unset($tempDb);
                }

		$flag_show_template='true';	
	}
	elseif($myrow_chk['CNT']>=1)
	{
		$flag_show_template='E';
	}
	else
	{
		if($chkprofilechecksum && $profileid > 0)
		{
			$CONTACT_STATUS_FIELD['HOROSCOPE']=1;
			$CONTACT_STATUS_FIELD['HOROSCOPE_NEW']=1;
            updatememcache($CONTACT_STATUS_FIELD,$chkprofilechecksum,1);
			//$sql_ins = "UPDATE CONTACTS_STATUS SET HOROSCOPE_REQUESTS=HOROSCOPE_REQUESTS+1 WHERE PROFILEID='$chkprofilechecksum'";
			//mysql_query_decide($sql_ins) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ins,"ShowErrTemplate");

			if(!$dt)
				$dt=date("Y-m-d H:i:s");

			$sql_insert="INSERT INTO HOROSCOPE_REQUEST(PROFILEID,PROFILEID_REQUEST_BY,DATE,CNT) VALUES ('$profileid','$chkprofilechecksum','$dt','1')";
                        for($ll=0;$ll<count($affectedDb);$ll++)
                        {
                                $tempDb=$affectedDb[$ll];
                                $mysqlObj->executeQuery($sql_insert,$tempDb);
                                unset($tempDb);
                        }
		}
		$flag_show_template='true';	
	}
return $flag_show_template;
}
?>
