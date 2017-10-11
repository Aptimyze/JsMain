<?php
include("connect.inc");
include("uploadphoto_inc.php");
//$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg|image/htm|image/swf";
$acceptable_file_types = array('gif','jpeg','pjpeg','jpg','swf','htm','png');
//$default_extension = ".jpg";,''

$path = "/usr/local/source_banners/";

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	
	if ($CMDGo)
	{
		$flag = 1;
		$iserror = 0;
		$target1 = $path.basename($_FILES['banner']['name']);
		$last = substr("$target1",-3,3);

		$target = $path.$source.".".$last;
		if (!$property)
		{
			$iserror++;
			$smarty->assign("ER_PROPERTY","Y");
		}
		if ($_FILES['banner']['size'] > 0)
		{
			if(in_array($last,$acceptable_file_types))
			{
				passthru("rm -rf ".$path.$source."*");
				if(move_uploaded_file($_FILES['banner']['tmp_name'], $target))
                        	{
                                	$cmd="chmod 777 $target";
					passthru("$cmd");
				}
			}
			else
			{
				$iserror++;

			}
		}
		else
		{
			//$iserror++;
			//$smarty->assign("flag","2");
		}
		if ($iserror > 0)
		{
			$i = 0;
			$sql = "SELECT PID , PROPERTY FROM MIS.SOURCE_PROPERTY WHERE GROUPNAME='$group'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$proparr[$i]['PROP_VAL']=$row['PID'];
				$proparr[$i]['PROP_NAME']=$row['PROPERTY'];
				$i++;
			}
			$smarty->assign("propval",$property);
			$smarty->assign("btype",$bannertype);
			$smarty->assign("proparr",$proparr);
			$smarty->assign("Error","(There was a problem in uploading the requested file.Check the format)");
			$smarty->assign("flag","2");
			$smarty->assign("source",$source);
			$smarty->assign("group",$group);
			$smarty->assign("banner",$banner);
			$smarty->display("manage_source_property.htm");
		}
		else
		{
			if (!is_numeric($property))
			{
				$sql = "SELECT PID FROM MIS.SOURCE_PROPERTY WHERE GROUPNAME='$group' AND PROPERTY='$property'";
				$res = mysql_query_decide($sql) or  die("$sql".mysql_error_js());
				$row = mysql_fetch_array($res);
				if ($row['PID'])
				{
					$pid = $row['PID'];
				}
				else
				{
					$sql = "INSERT INTO MIS.SOURCE_PROPERTY(GROUPNAME,PROPERTY) VALUES ('$group','$property')";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$pid = mysql_insert_id_js();
				}
			}
				else
					$pid = $property;

			$sql = "UPDATE MIS.SOURCE SET PID='$pid', SourceGifType='$bannertype' WHERE SourceID='$source' AND GROUPNAME='$group'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$smarty->assign("flag",$flag);
			$msg="Record Updated.<br>  ";
			$msg .="<a href=\"manage_banner_sources.php?cid=$cid\">";
			$msg .="Continue </a>";
			$smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");

			//$smarty->display("manage_source_property.htm");
		}
	}
	else
	{
		$i = 0;
		$sql = "SELECT PID , PROPERTY FROM MIS.SOURCE_PROPERTY WHERE GROUPNAME='$group'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        $proparr[$i]['PROP_VAL']=$row['PID'];
			$proparr[$i]['PROP_NAME']=$row['PROPERTY'];
			$i++;
                }

		$sql = "SELECT PID , SourceGifType FROM MIS.SOURCE WHERE SourceID='$source'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row = mysql_fetch_array($res);
		
		$smarty->assign("btype",$row['SourceGifType']);
		$smarty->assign("propval",$row['PID']);
		$smarty->assign("proparr",$proparr);	
		$smarty->assign("source",$source);
		$smarty->assign("group",$group);
		$smarty->display("manage_source_property.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
