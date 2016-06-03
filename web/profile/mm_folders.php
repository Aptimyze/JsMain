<?php        
/*
*	File Name       :	mm_folders.php
*       Description     :       shows users folders and options to add delete folders
*       Created by      :       Gaurav Arora
*       Created on      :	01 Sept 2005
**/
include_once("connect.inc");
include_once("contact.inc");
include_once('mmm.inc');
connect_db();

$lang=$_COOKIE["JS_LANG"];
if($lang=="deleted")
	$lang="";

$data = authenticated($checksum);

if($data)
{
 	login_relogin_auth($data);//For contacts details on left panel.
	$profileid=$data['PROFILEID'];
	$mysql= new Mysql;
	
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysql);
	$myDb=$mysql->connect("$myDbName");
	$db=connect_db();

	$sent_cnt=get_mssg_cnt($profileid,'',0);
	$smarty->assign("sent_cnt",$sent_cnt);
	
	$trash_cnt=get_mssg_cnt($profileid,1000,1);
	$smarty->assign("trash_cnt",$trash_cnt);
	
	$inbox_cnt=get_mssg_cnt($profileid,0,1);
	$smarty->assign("inbox_cnt",$inbox_cnt);

	if($Submit)
	{
		if($Flag=='new')
		{
			$error=0;
			if($newfolder || $newfolder==0)
			{
				//check for the folder names.
				$check_foldername=isvalid_foldername($newfolder);
				if($check_foldername)
				{
					$error++;
					$error_msg="* Please use only numbers and alphabets for your folder name.";
				}
				else
				{
					$tmp_foldername=strtolower($newfolder);
					if($tmp_foldername=='inbox' || $tmp_foldername=='trash' || $tmp_foldername=='sentitems')
					{
						$error++;
						$error_msg="* That folder name is reserved. Please enter any other folder name.";
					}			
					//query to find number of FOLDERS
					$sql_cnt="SELECT count(*) as count FROM FOLDERS WHERE PROFILEID='$profileid'";
					$result_cnt=mysql_query_decide($sql_cnt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cnt,"ShowErrTemplate");
					$row_cnt=mysql_fetch_array($result_cnt);
					if($row_cnt['count']>=9)
					{
						$error++;
						$error_msg="* You can not create a new folder as You have already created 9 folders.";
					}
					else
					{
						$sql="SELECT * FROM FOLDERS WHERE PROFILEID='$profileid'";
						$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

						while($row=mysql_fetch_array($result))
						{
							if($row['FOLDER_NAME']==$newfolder)
							{
								$error++;
								$error_msg="* Folder named $newfolder already exists, Please specify some other name.";
								break;
							}
						}
					}
				}
			}
			else
			{
				$error++;
				$error_msg="* Please specify a Folder name.";
			}
			//if the FOLDER NAME already exists then show error
			if($error>0)
			{
				$smarty->assign("ERROR",$error);
				$smarty->assign("ERROR_MSG",$error_msg);
			}
			//if there is no error and new FOLDER name is to be inserted in the FOLDER table
			else
			{
				//query to find MAX folderid for a particular USER
				$sql_id="SELECT MAX(FOLDERID) as MAX_FOLDERID FROM `FOLDERS` WHERE PROFILEID='$profileid'";
				$res_id=mysql_query_decide($sql_id) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_id,"ShowErrTemplate");

				$row_id=mysql_fetch_array($res_id);
				$max_folderid=$row_id['MAX_FOLDERID']+1;
				if($max_folderid==1000)
					$max_folderid++;
				maStripVARS("stripslashes");	

				//query to insert new folder name into FOLDER table for that USER
				$sql_insert_id="INSERT INTO FOLDERS (PROFILEID,FOLDERID,FOLDER_NAME) VALUES ('$profileid','$max_folderid','".addslashes(stripslashes($newfolder))."')";
				mysql_query_decide($sql_insert_id) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_insert_id,"ShowErrTemplate");
				
				if($ids)
				{	
					$ids=substr($ids,0,-1);
					$smarty->assign("SHOWMSG","Y");
					$smarty->assign("DEL",$del_mov_mark);
					$smarty->assign("j",$j);
					$smarty->assign("folderid",$folderid);
					$smarty->assign("MOV",$max_folderid);
					//$smarty->assign("MOV",$msg_move);
					$smarty->assign("IDS",$ids);
					$smarty->assign("sortorder",$sortorder);
				}
			}

		}	
		elseif($Flag=='del')
		{
			//query to find number of messages in a FOLDER
			$sql_del="DELETE FROM FOLDERS WHERE PROFILEID='$profileid' AND FOLDERID='$FolderID'";
			mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del,"ShowErrTemplate");

		}
	}
	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	}
	
	$smarty->assign("INBOX_FOLDER_CLICKED",$inbox_folder_clicked);
	$smarty->assign("FOLDER_FOLDER_CLICKED",$folder_folder_clicked);
	$sql="SELECT * FROM FOLDERS WHERE PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	while($row=mysql_fetch_array($result))
	{
		//query to find number of messages in a FOLDER
		$smarty->assign("folder_exists",1);
		$row_cnt_msg=get_mssg_cnt($profileid,$row['FOLDERID'],1);
		$folders_arr[]=$row['FOLDER_NAME'];	//array to store folder name
		$folderid_arr[]=$row['FOLDERID'];	//array to store folder id
		$arr_folder_msg_cnt[]=$row_cnt_msg;      //array to store number of mssgs
	}
	$smarty->assign("ARRAY_FOLDERS",$folders_arr);
	$smarty->assign("ARRAY_FOLDERID",$folderid_arr);
	$smarty->assign("ARRAY_FOLDER_MSG_CNT",$arr_folder_msg_cnt);
	//if request for new form
	
	$sql_cnt="SELECT count(*) as count FROM FOLDERS WHERE PROFILEID='$profileid'";
	$result_cnt=mysql_query_decide($sql_cnt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cnt,"ShowErrTemplate");
	$row_cnt=mysql_fetch_array($result_cnt);
	$cnt=$row_cnt['count'];
	$smarty->assign("cnt",$cnt);
	
	$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
	$smarty->assign("CHECKSUM",$checksum);
	
	$smarty->display("inbox_managefolder.htm");
}
else
{	
	TimedOut();	
}

?>
