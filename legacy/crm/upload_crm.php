<?php
/***************************************************************************************************
Filename     :  upload_crm.php
Description  :  Upload xls/csv for profile allotment to crm agents
Created By   :  Sadaf Alam
Created On   :  27 Aug 2007
***************************************************************************************************/

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$path = $_SERVER["DOCUMENT_ROOT"];

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
        $entryby=getname($cid);
	if($back)
	{
		$sql="TRUNCATE incentive.ALLOT_DATA";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	}
	elseif($skip)
	{
		$sql="SELECT USERNAME,ALLOT_DATE,AGENTID FROM incentive.ALLOT_DATA";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_assoc($result))
		{
			$sqlid="SELECT PHONE_RES,PHONE_MOB,EMAIL,PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$row[USERNAME]'";
			$resid=mysql_query_decide($sqlid) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlid,"ShowErrTemplate");
			$rowid=mysql_fetch_assoc($resid);
			$sqlins="INSERT INTO incentive.MAIN_ADMIN(PROFILEID,ALLOT_TIME,ALLOTED_TO,RES_NO,MOB_NO,EMAIL) VALUES('$rowid[PROFILEID]','$row[ALLOT_DATE]','$row[AGENTID]','$rowid[PHONE_RES]','$rowid[PHONE_MOB]','$rowid[EMAIL]')";
			mysql_query_decide($sqlins) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlins,"ShowErrTemplate");
			$sqlins="INSERT INTO incentive.CRM_DAILY_ALLOT(PROFILEID,ALLOTED_TO,ALLOT_TIME) VALUES('$rowid[PROFILEID]','$row[AGENTID]','$row[ALLOT_DATE]')";
			mysql_query_decide($sqlins) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlins,"ShowErrTemplate");
			$sqlins="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='N' WHERE PROFILEID='$rowid[PROFILEID]'";
			mysql_query_decide($sqlins) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlins,"ShowErrTemplate");
		}
		$sql="TRUNCATE incentive.ALLOT_DATA";
		mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$smarty->assign("DONE","1");
	}
	elseif($submit)
	{
		if($uploadfile)
		{
			$file=$_FILES["uploadfile"];
			$flag=1;
			echo $file["type"];
			if(strstr($file["type"],"csv") || strstr($file['name'],"csv") || strstr($file['name'],"CSV"))
			{
				$fp=fopen($file["tmp_name"],"rb") or $flag=0;
				if($flag)
				{
					$fcontent=fread($fp,filesize($file["tmp_name"]));
					$handle=fopen("$path/crm/uploadfile.csv","wb");
					shell_exec("chmod 777 $path/crm/uploadfile.csv");
					if($handle)
					{
						fwrite($handle,$fcontent);
						fclose($handle);
						$filename="$path/crm/uploadfile.csv";
						$sql="TRUNCATE incentive.ALLOT_DATA";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$sql="LOAD DATA LOCAL INFILE '$filename' INTO TABLE ALLOT_DATA FIELDS TERMINATED BY ','";
						mysql_query_decide($sql) or die(mysql_error_js());
						$today=date("Y-m-d");
						list($year,$month,$day)=explode("-",$today);
						$sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS";
						$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						while($row=mysql_fetch_assoc($result))
						{
							$privarray=explode("+",$row["PRIVILAGE"]);
							if(in_array("IUO",$privarray) || in_array("IUI",$privarray))
							$agentarr[]=$row["USERNAME"];
						}
						$sql="SELECT USERNAME FROM incentive.ALLOT_DATA WHERE YEAR(ALLOT_DATE)!='$year'";
						$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						if(mysql_num_rows($result))
						{
							while($row=mysql_fetch_assoc($result))
							$err_year[]=$row["USERNAME"];
						}
						$sql="DELETE FROM incentive.ALLOT_DATA WHERE YEAR(ALLOT_DATE)!='$year'";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$sql="SELECT USERNAME,AGENTID FROM incentive.ALLOT_DATA";
						$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						if(mysql_num_rows($result))
						{
							while($row=mysql_fetch_assoc($result))
							{
								$sqlid="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$row[USERNAME]'";
								$resid=mysql_query_decide($sqlid) or die("$sqlid".mysql_error_js());
								if(mysql_num_rows($resid))
								{
									$rowid=mysql_fetch_assoc($resid);
									$sqlallot="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$rowid[PROFILEID]'";
									$resallot=mysql_query_decide($sqlallot) or die("$sqlallot".mysql_error_js());
									if(mysql_num_rows($resallot))
									{
										$err_alloted[]=$row['USERNAME'];
										$sqldel="DELETE FROM incentive.ALLOT_DATA WHERE USERNAME='$row[USERNAME]'";
										mysql_query_decide($sqldel) or die("$sqldel".mysql_error_js());
										continue;
									}
									else
									{
										if(!in_array($row['AGENTID'],$agentarr))
										{
											$err_agent[]=$row["USERNAME"];
											$sqldel="DELETE FROM incentive.ALLOT_DATA WHERE USERNAME='$row[USERNAME]'";
											mysql_query_decide($sqldel) or die("$sqldel".mysql_error_js());
										}
									}	
								}
								else
								{
									$err_notexist[]=$row["USERNAME"];
									$sqldel="DELETE FROM incentive.ALLOT_DATA WHERE USERNAME='$row[USERNAME]'";
									mysql_query_decide($sqldel) or die("$sqldel".mysql_error_js());
									continue;
								}
							
							}
						}
						$smarty->assign("UPRESULT","1");
						$smarty->assign("ERR_YEAR",$err_year);
						$smarty->assign("ERR_NOTEXIST",$err_notexist);
						$smarty->assign("ERR_ALLOTED",$err_alloted);
						$smarty->assign("ERR_AGENT",$err_agent);
											
					}
					else
					$smarty->assign("ERR_MSG","Some error occured while uploading file. Please try again");
				}
			}
			else
			$smarty->assign("ERR_MSG","Only csv files allowed!");
		}
		else
		$smarty->assign("ERR_MSG","No file selected!");
	}
        $smarty->assign("name",$entryby);
	$smarty->display("upload_crm.htm");
}
else
{
	$msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
