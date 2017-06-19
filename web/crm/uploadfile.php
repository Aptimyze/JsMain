<?php
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include("connect.inc");

/**
*       Included        :       extract_csv.php
*       Description     :       contains functions related to extracting csv contents from uploaded file
**/
include("extract_csv.php");

/**
*       Included        :       put_csv.php
*       Description     :       contains functions related to insertion of extracted csv contents into database
**/
include("put_csv.php");

/**
*       Included        :       uploadfile_inc.php
*       Description     :       contains all functions related to file save and update
**/
include_once('uploadfile_inc.php');
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
global $file;

//$path = "/usr/local/apache/htdocs/jeevansathi/incentive/csv_files/";
$path = JsConstants::$docRoot."/crm/csv_files/";
//$acceptable_file_types = "text/csv|text/plain|text/x-comma-separated-values|text/comma-separated-values|application/zip|application/x-zip-compressed";
$acceptable_file_types = "application/zip|application/x-zip-compressed";
//$default_extension = ".csv";
$default_extension = ".zip";

if(authenticated($cid))
{	
        $mail_login= get_login_email($cid);
        $loginname= getuser($cid);

	if($Upload)
	{
		$flag_error = 0;
		$flag_upload = 0;	

		$datafile="datafile";
			
		//upload the file to a temporary location
		if(upload($datafile, $acceptable_file_types, $default_extension)){
			$fp = fopen($file["tmp_name"],"rb");
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$file_content = addslashes($fcontent)?addslashes($fcontent):'';
			$flag_upload = 1;
		}elseif($datafile){
			$flag_error = 1;		
		}	
		//end of upload to temporary location
	
		if($flag_error)//if any of the photos could not be uploaded to temp location
		{
			$msg="The file could not be uploaded ";
			$msg .="&nbsp;&nbsp;";
			$msg .="<a href=\"uploadfile.php?username=$username&profileid=$profileid&cid=$cid\">";
			$msg .="Upload again</a>";
			$smarty->assign("MSG",$msg);
			$smarty->assign("name",$username);
			$smarty->assign("cid",$cid);		
			$smarty->display("jsadmin_msg.tpl");
			die;	
		}
		elseif($flag_upload)//successful upload of photos to a temporary location
		{
			$fname="transcripts";
			if($save_file=save_file($path,$fname,1))
			{
				extract_csv($path,"transcripts.zip","transcripts.csv");
//				$maillist=put_csv($path,"new.csv");
//				if(count($maillist)>0)
//					insert_claim($maillist,$mail_login);
//				else
//					echo "\nWarning! File is not in the correct format to claim the entries. Please make sure that the csv file is being uploaded in the zip format\n";
				$msg="You have successfully uploaded the file<br>";
				$msg .="<a href=\"chat_db.php?username=$username&cid=$cid&flag_upload=$flag_upload&save_file=$save_file\">";		
				$msg .="Click to claim the entries&gt;&gt;</a>";
				$smarty->assign("MSG",$msg);
				$smarty->assign("name",$username);
				$smarty->assign("cid",$cid);		
				$smarty->display("jsadmin_msg.tpl");		
			}
		}
	}
	else
	{
		$smarty->assign("cid",$cid);
		$smarty->assign("username",$username);
		$smarty->display("upload_file.htm");
	}
}
	
else//user timed out
{
	$msg="Your session has been timed out<br><br>";
	$msg .="<a href=\"uploadfile.php?username=$username&cid=$cid\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}
	
?>
