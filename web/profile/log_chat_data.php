<?php
include('connect.inc');
$db=connect_db();
$data=authenticated($checksum);
$checksum=$data["CHECKSUM"];
$ajaxValidation=1;
$sen=$_POST['SEN'];
$rec=$_POST['REC'];
	if($data){
		if($entry)
		{
			$first_mes=$_POST['MES'];
			$action='I';
			$received=0;
			$time=date("Y-m-d G:i:s");
			$date=date("Y-m-d");
			
			$sen_sub='N';
			if(isPaid($data['SUBSCRIPTION']))
				$sen_sub='Y';
			$rec_sub='N';
			$senu=$data['USERNAME'];
			$sql="select USERNAME,SUBSCRIPTION from newjs.JPROFILE where  activatedKey=1 and PROFILEID ='$rec'";
			$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			if($row=mysql_fetch_array($res))
			{
					$recu=$row['USERNAME'];

					if(isPaid($row['SUBSCRIPTION']))
						$rec_sub='Y';
			}
			$sql="insert HIGH_PRIORITY into userplane.LOG_CHAT_REQUEST(SEN,REC,SENU,RECU,SEN_P,REC_P,MES,ACTION,RECEIVED,`TIME`,`DATE`) values('$sen','$rec','$senu','$recu','$sen_sub','$rec_sub','$first_mes','$action','$received','$time','$date')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			
		}
		if($received)
		{
			$sql="select USERNAME,SUBSCRIPTION from newjs.JPROFILE where PROFILEID ='$sen' and activatedKey=1";
                        $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			if($row=mysql_fetch_array($res))
			{
				if(!isPaid($row['SUBSCRIPTION']))
                                {
					die("2");
				}
			}
				$sql="UPDATE userplane.`LOG_CHAT_REQUEST` SET RECEIVED =1 WHERE SEN ='$sen' AND REC ='$rec' order by ID DESC LIMIT 1";
				$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		if($action)
		{
			if($action=='L')
				$sql="UPDATE userplane.`LOG_CHAT_REQUEST` SET ACTION='$action' WHERE SEN ='$sen' AND REC ='$rec' and ACTION='I' order by ID DESC LIMIT 1";
			else
				$sql="UPDATE userplane.`LOG_CHAT_REQUEST` SET ACTION='$action' WHERE SEN ='$sen' AND REC ='$rec' order by ID DESC LIMIT 1";
			$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
	}


echo true;

?>

