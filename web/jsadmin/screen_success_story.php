<?php
/***********************************************************************************************************************
*    FILENAME           : screen_success_story.php
*    DESCRIPTION        : Screen and Upload / Remove Success Stories
*    CREATED BY         : Sadaf Alam
*    CREATED ON         : 21 June 07
***********************************************************************************************************************/

include ("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include ("../crm/func_sky.php");
include("uploadphoto_inc.php");
include_once("../profile/new_voucher.php");
global $max_filesize;
global $file;
$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";


//Redirect all the images path to ser6 and also form action to ser6, since from now disc write will only take place at the ser6 server.

if(strstr($_SERVER['HTTP_HOST'],"server") || strstr($_SERVER['HTTP_HOST'],"infoedge"))
	$SITE_URL="http://".$_SERVER['HTTP_HOST'];

else 
	$SITE_URL=JsConstants::$ser6Url; 
	
$smarty->assign("SITE_URL",$SITE_URL);				


if(authenticated($cid))
{
	$user=getname($cid);
	//$max_filesize = 153600;
	$max_filesize = 1048576*5;
	if($Vskip)
	{
		/* Changing Made for Show Date & Time which is uploaded by User by Anurag */
		/* Changed to WEDDING_DATE insted of DATETIME in below Query and also in $values[] */
		$sql="SELECT ID,NAME_H,NAME_W,USERNAME_H,USERNAME_W,WEDDING_DATE,SKIP_COMMENTS FROM newjs.SUCCESS_STORIES WHERE UPLOADED='S' ORDER BY DATETIME";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_assoc($result))
		{
			$id=$row["ID"];
			$name_h=$row["NAME_H"];
			$name_w=$row["NAME_W"];
			$user_h=$row["USERNAME_H"];
			$user_w=$row["USERNAME_W"];
			$date=$row["WEDDING_DATE"];
			$datetime=$row["DATETIME"];
			$skip_comments=$row["SKIP_COMMENTS"];
			$values[]=array("id"=>$id,
					"name_h"=>$name_h,
					"name_w"=>$name_w,
					"user_h"=>$user_h,
					"user_w"=>$user_w,
					"date"=>$date,
					"skip_comments"=>$skip_comments);
		}

		$smarty->assign("values",$values);
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->assign("VSKIP","1");
		
	}
	elseif($unhold)
	{
		if($accept)
		{
			//print_r($_FILES);
			$dontexecute=0;
			if($STORY_ID)
			$story_id=trim($STORY_ID);
			if($user_h)
			$user_h=trim($user_h);
			if($user_w)
			$user_w=trim($user_w);
			if($contact)
			$contact=trim($contact);
			if($textstory)
			$textstory=trim($textstory);
			if($name_h)
			$name_h=trim($name_h);
			if($name_w)
			$name_w=trim($name_w);
			if($email)
			$email=trim($email);
			if(($name_h || $name_w) && $textstory)
			$dontexecute=0;
			else
			{
				if(!$name_h && !$name_w)
				$smarty->assign("NONAME","1");
				if(!$textstory)
				$smarty->assign("NOSTORY","1");
				$smarty->assign("screenid",$id);
				$unsearch=1;
				$dontexecute=1;
				
			}
			if(!$photo && ($frame || $fullphoto))
			{
				if(!$frame || !$fullphoto)
				{	
					$dontexecute=1;
					$smarty->assign("NOPIC","1");
					$smarty->assign("screenid",$id);
					$unsearch=1;
				}
			}
			if(!$sid && $photo && !$delete)
			{
				if(!$frame||!$fullphoto)
				{
					$dontexecute=1;
					$smarty->assign("NOPIC","1");
					$smarty->assign("screenid",$id);
					$unsearch=1;
				}
			}
			if(!$dontexecute)
			{
				$field_is["NAME1"]=$name_h;
				$field_ss["NAME_H"]=$name_h;
				$field_v["NAME_H"]=$name_h;
				$field_is["NAME2"]=$name_w;
				$field_ss["NAME_W"]=$name_w;
				$field_v["NAME_W"]=$name_w;
    				$field_ss["USERNAME_H"]=$user_h;
				$field_v["USERNAME_H"]=$user_h;
				$field_ss["USERNAME_W"]=$user_w;
				$field_v["USERNAME_W"]=$user_w;
				$field_ss["CONTACT_DETAILS"]=$contact;
				$field_v["CONTACT"]=$contact;
				$field_ss["EMAIL"]=$email;
				$field_v["EMAIL"]=$email;
				if($heading)
				{
					$field_is["HEADING"]=$heading;
				}
                                elseif($name_w && $name_h)
				{
                    			$heading=$name_h." weds ".$name_w;
					$field_is["HEADING"]=$heading;
				}
				$field_ss["COMMENTS"]=$textstory;
				$field_is["STORY"]=$textstory;
				if($user_h || $user_w)
	                        {
        	                	$sqldet="SELECT";
                	                if(!$email)
                        	        $sqldet.=" EMAIL,";
                                	if(!$contact)
                                        $sqldet.=" CONTACT,";
					if(!$city)
	                                $sqldet.=" CITY_RES, ";
					$sqldet.=" PHONE_RES,PROFILEID,PHONE_MOB,OCCUPATION,RELIGION,MTONGUE,COUNTRY_RES,CASTE ";
					$sqldet.=" FROM newjs.JPROFILE WHERE USERNAME ='";
                	                if($user_h)
                        	        $sqldetfinal.=$sqldet."$user_h'";
                                	else
                                        $sqldetfinal.=$sqldet."$user_w'";
	                                $resultdet=mysql_query_decide($sqldetfinal) or die("$sqldetfinal".mysql_error_js());
        	                        if(mysql_num_rows($resultdet))
                	                {
	                        	        $row=mysql_fetch_assoc($resultdet);
                               	                if($row["CONTACT"])
						{
	                                       	        $field_ss["CONTACT_DETAILS"]=$row["CONTACT"];
							$field_v["CONTACT"]=$row["CONTACT"];
						}
                                               	if($row["EMAIL"])
						{
	                                        	$field_ss["EMAIL"]=$row["EMAIL"];
							$field_v["EMAIL"]=$row["EMAIL"];
						}
						if($row["PHONE_RES"])
						$field_v["PHONE_RES"]=$row["PHONE_RES"];
						if($row["PHONE_MOB"])
						$field_v["PHONE_MOB"]=$row["PHONE_MOB"];
						$field_v["PROFILEID"]=$row["PROFILEID"];
                                                if($row["CITY_RES"]);
               	                                $field_v["CITY_RES"]=$row["CITY_RES"];
                       	                }
					 elseif($user_h && $user_w)
	                                {
                                                $sqldetfinal=$sqldet."$user_w'";
               	                                $resultdet=mysql_query_decide($sqldetfinal) or die("$sqldetfinal".mysql_error_js());
                       	                        if(mysql_num_rows($resultdet))
                               	                {
                                       	                $row=mysql_fetch_assoc($resultdet);
                                               	        if($row["CONTACT"])
							{
                                                       		$field_ss["CONTACT_DETAILS"]=$row["CONTACT"];
								$field_v["CONTACT"]=$row["CONTACT"];
							}
	                                                if($row["EMAIL"])
							{
                                                        	$field_ss["EMAIL"]=$row["EMAIL"];
								$field_v["EMAIL"]=$row["EMAIL"];
							}
							if($row["PHONE_RES"])
							$field_v["PHONE_RES"]=$row["PHONE_RES"];
							if($row["PHONE_MOB"])
							$field_v["PHONE_MOB"]=$row["PHONE_MOB"];
							$field_v["PROFILEID"]=$row["PROFILEID"];
               	                                        if($row["CITY_RES"])
                       	                                $field_v["CITY_RES"]=$row["CITY_RES"];
                               	                }
	                                }
						$field_is["OCCUPATION"]=$row["OCCUPATION"];
						$field_is["RELIGION"]=$row["RELIGION"];
						$field_is["CASTE"]=$row["CASTE"];
						$field_is["MTONGUE"]=$row["MTONGUE"];
						$field_is["COUNTRY"]=$row["COUNTRY_RES"];
						$field_is["CITY"]=$row["CITY_RES"];

				}
				$field_ss["WEDDING_DATE"]=$year."-".$month."-".$day;
				if($delete)
				{
					if($sid)
					{
						$sqldet="UPDATE newjs.INDIVIDUAL_STORIES SET PICTURE='',HOME_PICTURE='' WHERE SID='$sid'";
 						mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
						$path=$_SERVER['DOCUMENT_ROOT'];
						passthru("rm $path/success/images_06_05/".$sid."_sm.jpg");
					}
				}
				else 
				{
					if($frame)
					{
                                       		if(upload("frame",$acceptable_file_types,$default_extension))
                                       		{
                                               		 $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
		                                         $fcontent = fread($fp,filesize($file["tmp_name"]));
						//	echo "<br>".$fcontent;
        		                         }
                		                 elseif($frame)
                        		         {
                                	               $error=1;
                                       		 }
						if(!$sid)
						{
							$sqldet="SELECT MAX(SID) AS SID FROM newjs.INDIVIDUAL_STORIES";
 							$resultdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
							$rowdet=mysql_fetch_assoc($resultdet);
							$newsid=$rowdet["SID"]+1;
							$field_is["SID"]=$newsid;
							$filename=$newsid."_sm.jpg";
						}
						else
						$filename=$sid."_sm.jpg";
        	                	        $path=$_SERVER["DOCUMENT_ROOT"];
					//	echo $filename;
                	                	 $handle_large = fopen("$path/success/images_06_05/$filename","wb");
	                        	         if($handle_large)
        	                                {
                	                       	        fwrite($handle_large,$fcontent);
                        	                       	fclose($handle_large);
	                        	        }
        	                        	else
	                	                {
        	                	                die("Some error has occured while uploading photo(s).Please try again");
                	                        }
					}
					if($fullphoto)
					{
						if(upload("fullphoto",$acceptable_file_types,$default_extension))
	                                       	{
        	                               	         $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                	                        	 $fullphotocontent = fread($fp,filesize($file["tmp_name"]));
	                	                         $field_is["PICTURE"]=addslashes($fullphotocontent);
							 if(!$photo_ss && !$photo)
							 $field_ss["PHOTO"]=addslashes($fullphotocontent);
                 	                	}
					        else
        	                               	{
                	                               	$error=1;
                        	               	}
					}
// Addition by Anurag for Displaying HOME_PHOTO in INDIVIDUAL_STORIES
 
					if($homephoto)
					{
						if(upload("homephoto",$acceptable_file_types,$default_extension))
	                                       	{
        	                               	         $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                	                        	 $homephotocontent = fread($fp,filesize($file["tmp_name"]));
							 // $filed_is["HOME_PICTURE"]=addslashes($fullphotocontent);
							 $field_is["HOME_PICTURE"]=addslashes($homephotocontent);
                 	                	}
					        else
        	                               	{
                	                               	$error=1;
                        	               	}
					}

// End of Addition
	                                if($error)//if any of the photos could not be uploaded to temp location
        	                        {
                	                        $msg="The image(s) could not be uploaded ";
                        	                $msg .="&nbsp;&nbsp;";
                                	        $msg .="<a href=\"screen_success_story.php?user=$user&cid=$cid&unhold=1&unsearch=1&search_user_h=$search_user_h&search_user_w=$search_user_w&search_name_h=$search_name_h&search_name_w=$search_name_w\">";
                                        	$msg .="Please enter the details again</a>";
	                                        $smarty->assign("MSG",$msg);
        	                                $smarty->assign("cid",$cid);
                	                        $smarty->display("jsadmin_msg.tpl");
                        	                die;
                                	}
				}
				$field_ss["WEDDING_DATE"]=$year."-".$month."-".$day;
				$field_ss["UPLOADED"]="A";
				$field_is["STATUS"]="A";
				$field_is["STORYID"]=$id;
				$field_v["STORYID"]=$id;
				$field_is['YEAR']=$year;
				$dontexecute=0;
				$sql="UPDATE newjs.SUCCESS_STORIES SET";
				
				$size = sizeof($field_ss);
				$i=0;
				foreach($field_ss as $key=>$value)
				{
					if($i<$size-1)
					$sql.=" ".$key."='".$value."' ,";
					else
					$sql.=" ".$key."='".$value;
					$i++;
				}
				$sql.="' ";

				/* Changing Made for Show Date & Time which is uploaded by User by Anurag, 
 			        Commented Below 2 Lines for Stoping Current Date & Time in INDIVIDUAL_STORIES Table */

				/* $sql.="DATETIME=NOW() WHERE ID='$id'";
				mysql_query_decide($sql) or $dontexecute=1; */
                              
			        $sql.="WHERE ID='$id'";
				mysql_query_decide($sql) or $dontexecute=1;

				if($sid)
				{
					$sql="UPDATE newjs.INDIVIDUAL_STORIES SET";
	                                foreach($field_is as $key=>$value)
        	                        {
                	                        $sql.=" ".$key."='".$value."',";
                        	        }
					$sql=substr($sql,0,strlen($sql)-1);
                                	$sql.=" WHERE SID='$sid'";
				}
				else
				{
                                	foreach($field_is as $key=>$value)
                                	{
                                        	$fieldis.=$key.",";
	                                        $valueis.="'".$value."',";
        	                        }
                	                $fieldis=substr($fieldis,0,strlen($fieldis)-1);
                        	        $valueis=substr($valueis,0,strlen($valueis)-1);
                                	$sql="INSERT INTO newjs.INDIVIDUAL_STORIES($fieldis) VALUES($valueis)";
                                }
				
				if(!$dontexecute)
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
 				$sql="SELECT ID FROM billing.VOUCHER_SUCCESSSTORY WHERE STORYID='$id'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_num_rows($result)==0)
				{
					if($field_v["EMAIL"] && $field_v["CONTACT"])
					{
						foreach($field_v as $key=>$value)
						{
							$fieldv.=$key.",";
								$valuev.="'".$value."',";
						}
						$fieldv=substr($fieldv,0,strlen($fieldv)-1);
						$valuev=substr($valuev,0,strlen($valuev)-1);
						$sql="INSERT INTO billing.VOUCHER_SUCCESSSTORY($fieldv) VALUES($valuev)";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
					}
				}
				$msg="You have successfully uploaded the story";
				$msg.="&nbsp;&nbsp;";
				$msg.="<a href=\"screen_success_story.php?cid=$cid&user=$user&unhold=1\">";
				$msg.="Continue&gt;&gt;</a>";
				$smarty->assign("cid",$cid);
				$smarty->assign("MSG",$msg);
				$smarty->display("jsadmin_msg.tpl");
				die;				
			}
		}
		elseif($remove)
		{
			$sql="UPDATE newjs.INDIVIDUAL_STORIES SET STATUS='R' WHERE SID='$sid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='R' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		elseif($reject)
		{
			$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='D' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		elseif($hold)
		{
			$smarty->assign("mail_name_h",$mail_name_h);
			$smarty->assign("mail_name_w",$mail_name_w);
			$smarty->assign("useemail",$useemail);
			$smarty->assign("id",$id);
			$smarty->assign("UNHOLDMAIL","1");
		}
		elseif($send)
		{		
			$mail=trim($mail);
                        send_mail($email,'','',$mail,'Success Story Held','Promotions@jeevansathi.com');
                        $sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='H' WHERE ID='$id'";
                        mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		if($unsearch)
		{
			if($search_name_h || $search_name_w || $search_user_h || $search_user_w)
			{
				// Made Changes for the Date and Time for Held Back Section 	
				$sql="SELECT ID,COMMENTS,DATETIME,EMAIL,WEDDING_DATE,CONTACT_DETAILS,PHOTO,USERNAME_H,USERNAME_W,NAME_H,NAME_W,UPLOADED FROM newjs.SUCCESS_STORIES WHERE";
				
				if($search_user_h)
                                $sql.=" USERNAME_H='$search_user_h' AND";
                                if($search_user_w)
                                $sql.=" USERNAME_W='$search_user_w' AND";
                                if($search_name_h)
                                $sql.=" NAME_H='$search_name_h' AND";
                                if($search_name_w)
                                $sql.=" NAME_W='$search_name_w' AND";
                                $sql=substr($sql,0,strlen($sql)-3);
                                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_num_rows($result))
				{
					while($row=mysql_fetch_assoc($result))
					{
						$id=$row["ID"];
						if($row["UPLOADED"]=="A")
						$status="UPLOADED";
						elseif($row["UPLOADED"]=="H")
						$status="HELD BACK";
						elseif($row["UPLOADED"]=="S")
						$status="SKIPPED";
						elseif($row["UPLOADED"]=="D")
						$status="REJECTED";
						elseif($row["UPLOADED"]=="R")
						$status="REMOVED";
						else
						$status="TO BE SCREENED";
						list($year,$month,$day)=explode("-",$row["WEDDING_DATE"]);
						if($year=="0000")
						{
							$year="2007";
							$day="15";
							$month="03";
						}
						if($row["UPLOADED"]=="A" || $row["UPLOADED"]=="R")
						{
							$id=$row["ID"];
							$sql2="SELECT SID,HEADING,STORY,PICTURE FROM newjs.INDIVIDUAL_STORIES WHERE STORYID='$id'";
							$result2=mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
							$row2=mysql_fetch_assoc($result2);
							
						//	$sql3="SELECT DATETIME FROM SUCCESS_STORIES WHERE ID = '2991'";   //New
						//	$result3=mysql_query($sql3);  // New 
						//	while($row=mysql_fetch_array($result3))
						//	{
						//		$datetime[]=$row['DATETIME'];
						//	}

// Have to Check for Addition
							if($row2["PICTURE"])
							$photo=1;
							else
							$photo=0;
							if($row["PHOTO"])
							$photo_ss=1;
							else
							$photo_ss=0;
						
							$story[]=array("user_h" => $row["USERNAME_H"],
									"user_w"=> $row["USERNAME_W"],
									"name_h" => $row["NAME_H"],
									"name_w" => $row["NAME_W"],
									"heading" => $row2["HEADING"],
									"story"=>$row2["STORY"],
									"id" => $row["ID"],
									"sid" => $row2["SID"],
									"status"=>$status,
									"email"=>$row["EMAIL"],
									"datetime"=>$row["DATETIME"],
									"contact"=>$row["CONTACT_DETAILS"],
									"photo" => $photo,
									"photo_ss"=>$photo_ss,
									"year" => $year,
									"month" => $month,
									"day" => $day);
						}
						
						else
						{
							if($row["PHOTO"])
							$photo=1;
							else
							$photo=0;
							$story[]=array("user_h"=>$row["USERNAME_H"],
									"user_w"=>$row["USERNAME_W"],
									"name_h"=>$row["NAME_H"],
									"name_w"=>$row["NAME_W"],
									"story"=>$row["COMMENTS"],
									"id"=>$row["ID"],
									"status"=>$status,
									"email"=>$row["EMAIL"],
									"contact"=>$row["CONTACT_DETAILS"],
									"photo"=>$photo,
									"datetime"=>$row["DATETIME"],
									"year" => $year,
                                                                        "month" => $month,      
                                                                        "day" => $day);
						}//end of if
										
					}//end of while
					$smarty->assign("showformunhold","1");
					$smarty->assign("story",$story);
					if($search_user_h)
					$smarty->assign("search_user_h",$search_user_h);
					if($search_user_w)
					$smarty->assign("search_user_w",$search_user_w);
					if($search_name_h)
					$smarty->assign("search_name_h",$search_name_h);
					if($search_name_w)
					$smarty->assign("search_name_w",$search_name_w);
				}//end of story search in ss
				else
				$smarty->assign("NOSTORY","1");
			}//end of checking input data
			elseif($STORY_ID)
			{
				$id=$STORY_ID;
			$sql2="SELECT STATUS,NAME1,NAME2,SID,STORYID,HEADING,STORY,PICTURE,HOME_PICTURE,YEAR FROM newjs.INDIVIDUAL_STORIES WHERE SID='$id'";
// echo for sql in Edit Page	$sql2;
				$result2=mysql_query_decide($sql2) or die("$sql2".mysql_error_js());

			
//Added by Anurag for Date & Time
				$sql3="SELECT newjs.SUCCESS_STORIES.DATETIME FROM newjs.SUCCESS_STORIES,newjs.INDIVIDUAL_STORIES WHERE newjs.INDIVIDUAL_STORIES.SID='$id' AND newjs.INDIVIDUAL_STORIES.STORYID=newjs.SUCCESS_STORIES.ID";
//				$sql3="SELECT DATETIME FROM newjs.SUCCESS_STORIES WHERE ID = '2991'"; 
				$result3=mysql_query($sql3);  
                                while($row=mysql_fetch_array($result3))
                                {
                                    $datetime=$row['DATETIME'];
	                        }
//Upto Here Date and Time	

				if(mysql_num_rows($result2)>0)
				{
					$row2=mysql_fetch_assoc($result2);
					
					$sql="SELECT ID,COMMENTS,EMAIL,WEDDING_DATE,CONTACT_DETAILS,PHOTO,USERNAME_H,USERNAME_W,NAME_H,NAME_W,UPLOADED FROM newjs.SUCCESS_STORIES where ID='".$row2['STORYID']."'";
					 $res=mysql_query_decide($sql) or die(mysql_error_js());

					if(!($row=mysql_fetch_assoc($res)))
					{
						$row['NAME_H']=$row2['NAME1'];
						$row['NAME_W']=$row2['NAME2'];
						$row['ID']=$row2['STORYID'];
						$row['YEAR']=$row2['YEAR'];
						$row['UPLOADED']=$row2['STATUS'];
						$weddingdate=$row2['YEAR']."0101";
					
						//If record is not found in SUCCESS_STORIES table than insert record into 
						//table and update INDIVIDUAL_STORIES table.	
						$sql="INSERT INTO newjs.SUCCESS_STORIES (NAME_H,NAME_W,UPLOADED,WEDDING_DATE,PHOTO) values('".$row['NAME_H']."','".$row['NAME_W']."','".$row['UPLOADED']."','".$weddingdate."','".addslashes($row2["PICTURE"])."')";

						mysql_query_decide($sql) or die(mysql_error_js());
						$mysql_insert_id=mysql_insert_id_js();
						
						 $row['ID']=$mysql_insert_id;	
						$sql="update newjs.INDIVIDUAL_STORIES set STORYID='$mysql_insert_id' where SID='$id'";
						mysql_query_decide($sql) or die(mysql_error_js());
						
					}
					list($year,$month,$day)=explode("-",$row["WEDDING_DATE"]);
					if($year=="0000")
					{
						$year="2007";
						$day="15";
						$month="03";
					}
					if($row2['YEAR']!='')
						$year=$row2['YEAR'];

					if($row["UPLOADED"]=="A")
						$status="UPLOADED";
                                        elseif($row["UPLOADED"]=="H")
	                                        $status="HELD BACK";
                                        elseif($row["UPLOADED"]=="S")
        	                                $status="SKIPPED";
                                        elseif($row["UPLOADED"]=="D")
                	                        $status="REJECTED";
                                        elseif($row["UPLOADED"]=="R")
                        	                $status="REMOVED";
                                        else
                                	        $status="TO BE SCREENED";

					if($row2["PICTURE"])
					$photo=1;
					else
					$photo=0;
					if($row["PHOTO"])
					$photo_ss=1;
					else
					$photo_ss=0;

					$story[]=array("user_h" => $row["USERNAME_H"],
							"user_w"=> $row["USERNAME_W"],
							"name_h" => $row["NAME_H"],
							"name_w" => $row["NAME_W"],
							"heading" => $row2["HEADING"],
							"story"=>$row2["STORY"],
							"id" => $row["ID"],
							"sid" => $row2["SID"],
							"status"=>$status,
							"email"=>$row["EMAIL"],
							"datetime"=>$datetime,
							"contact"=>$row["CONTACT_DETAILS"],
							"photo" => $photo,
							"photo_ss"=>$photo_ss,
							"year" => $year,
							"month" => $month,
							"day" => $day);
				
				 $smarty->assign("showformunhold","1");
                                 $smarty->assign("story",$story);
	

				}
				else
					$smarty->assign("NODATA","1");
			}
			else
			$smarty->assign("NODATA","1");
		}

		$smarty->assign("rand",rand());
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("UNHOLD","1");
		$smarty->display("screen_success_story.htm");
		die;		
	}
	elseif($offline)
	{
		if($Upload)
		{
			$dontexecute=0;

			/*			if($user_h || $user_w || $email_h || $email_w)
			{

				$sql="select ID,USERNAME_H,EMAIL,EMAIL_W,USERNAME_W,PHOTO from SUCCESS_STORIES where (USERNAME_H='$user_h' or USERNAME_W='$user_w' or EMAIL='$email_h' or EMAIL_W='$email_w' ) and UPLOADED='A' ";
				$res=mysql_result($sql) or die(mysql_error_js());
				if($row=mysql_fetch_row($res))
				{
					if($frame && $fullphoto && $row['PHOTO']=="")
					{
						if($user_h==$row['USERNAME_H'] && $user_h!="")
						$msg="Husband id is already live<BR>";
						if($user_w==$row['USERNAME_W'] && $user_w=="")
						$msg="Wife id is already live<BR>";
						if(($email_h==$row['EMAIL']) && $email!="" )
						$msg="Husband EMAIL ID already live<BR>";
						if($email_w==$row['EMAIL_W'] || $email_w!="")
						$msg="Wife email id already live<BR>";
						$dontexecute=1;
						$smarty->assign("dup_mes",$msg);
					}

				}
			}*/

			if((!$name_h&&!$name_w)||!$story)
			{
				if(!$name_h && !$name_w)
				$smarty->assign("noname","1");
				if(!$story)
				$smarty->assign("nostory","1");
				else
				$smarty->assign("story",$story);
				$dontexecute=1;
			}
			if($frame||$fullphoto)
			{
				if(!$frame || !$fullphoto)
				{
					$smarty->assign("nopic","1");
					$dontexecute=1;
				}
				
			}
			if(!$dontexecute)
			{
				if($user_h)
				{
					$field_ss["USERNAME_H"]=$user_h;
					$field_v["USERNAME_H"]=$user_h;
				}
				if($user_w)
				{
					$field_ss["USERNAME_W"]=$user_w;
					$field_v["USERNAME_W"]=$user_w;
				}
				if($name_h)
				{
					$field_is["NAME1"]=$name_h;
					$field_ss["NAME_H"]=$name_h;
					$field_v["NAME_H"]=$name_h;
				}
				if($name_w)
				{
				    $field_is["NAME2"]=$name_w;
				    $field_ss["NAME_W"]=$name_w;
					$field_v["NAME_W"]=$name_w;
				}
				if($heading)
				$field_is["HEADING"]=$heading;
				elseif($name_h && $name_w)
				{
					$field_is["HEADING"]=$name_h." weds ".$name_w;
				}
				if($contact)
				{
					$field_ss["CONTACT_DETAILS"]=$contact;
					$field_v["CONTACT"]=$contact;
				}
				if($email_h)
				{
					$field_ss["EMAIL"]=$email_h;
					$field_v["EMAIL"]=$email_h;
					$field_ss['SEND_EMAIL']=$email_h;
				}
				if($email_w)
				{
					$field_ss["EMAIL_W"]=$email_w;
					$field_ss['SEND_EMAIL']=$email_w;
					$field_v["EMAIL"]=$email_w;
				}

				if($user_h || $user_w)
				{
					$sqldet="SELECT";
					if(!$email)
					$sqldet.=" EMAIL,";
					if(!$contact)
					$sqldet.=" CONTACT,";
					$sqldet.=" CITY_RES,PHONE_RES,PHONE_MOB,PROFILEID,OCCUPATION,COUNTRY_RES,RELIGION,CASTE,MTONGUE ";
					$sqldet.=" FROM newjs.JPROFILE WHERE USERNAME ='";
					if($user_h)
					$sqldetfinal.=$sqldet."$user_h'";
					else
					$sqldetfinal.=$sqldet."$user_w'";
					$resultdet=mysql_query_decide($sqldetfinal) or die("$sqldetfinal".mysql_error_js());
					if(mysql_num_rows($resultdet))
					{
						$row=mysql_fetch_assoc($resultdet);
						if($row["CONTACT"])
						{
							$field_ss["CONTACT_DETAILS"]=$row["CONTACT"];
							$field_v["CONTACT"]=$row["CONTACT"];
						}
						if($row["EMAIL"])
						{
							$field_ss["EMAIL"]=$row["EMAIL"];
							$field_v["EMAIL"]=$row["EMAIL"];
						}
						if($row["CITY_RES"]);
						$field_v["CITY_RES"]=$row["CITY_RES"];
						if($row["PHONE_RES"])
						$field_v["PHONE_RES"]=$row["PHONE_RES"];
						if($row["PHONE_MOB"])
						$field_v["PHONE_MOB"]=$row["PHONE_MOB"];
						$field_v["PROFILEID"]=$row["PROFILEID"];
					}
					elseif($user_h && $user_w)
					{
						$sqldetfinal=$sqldet."$user_w'";
						$resultdet=mysql_query_decide($sqldetfinal) or die("$sqldetfinal".mysql_error_js());
        	                                if(mysql_num_rows($resultdet))
		       	                        {
							$row=mysql_fetch_assoc($resultdet);
                        	                       	if($row["CONTACT"])
							{
                                	                	$field_ss["CONTACT_DETAILS"]=$row["CONTACT"];
								$field_v["CONTACT"]=$row["CONTACT"];
							}
                                        	        if($row["EMAIL"])
							{
                                                		$field_ss["EMAIL"]=$row["EMAIL"];
								$field_v["EMAIL"]=$row["EMAIL"];
							}
							if($row["CITY_RES"])
							$field_v["CITY_RES"]=$row["CITY_RES"];
							if($row["PHONE_RES"])
	                                                $field_v["PHONE_RES"]=$row["PHONE_RES"];
        	                                        if($row["PHONE_MOB"])
                	                                $field_v["PHONE_MOB"]=$row["PHONE_MOB"];
                        	                        $field_v["PROFILEID"]=$row["PROFILEID"];
                                        	}

					}
					
					$field_is["OCCUPATION"]=$row["OCCUPATION"];
					$field_is["RELIGION"]=$row["RELIGION"];
					$field_is["CASTE"]=$row["CASTE"];
					$field_is["MTONGUE"]=$row["MTONGUE"];
					$field_is["COUNTRY"]=$row["COUNTRY_RES"];
					$field_is["CITY"]=$row["CITY_RES"];
				}
				$field_ss["COMMENTS"]=$story;
				$field_is["STORY"]=$story;
				$field_ss["UPLOADED"]="A";
				$field_is["STATUS"]="A";
				$field_ss["WEDDING_DATE"]=$year."-".$month."-".$day."";
				$field_is['YEAR']=$year;
				$field_ss["DATETIME"]=date("Y-m-d H:i:s");
				if($frame)
				{
					$sqldet="SELECT MAX(SID) AS SID FROM newjs.INDIVIDUAL_STORIES";
					$resultdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
					$rowdet=mysql_fetch_assoc($resultdet);
					$sid=$rowdet["SID"]+1;
					$field_is["SID"]=$sid;
					 if(upload("frame",$acceptable_file_types,$default_extension))
	                                {
        	                                 $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                	                        $fcontent = fread($fp,filesize($file["tmp_name"]));
	                                }
        	                        elseif($frame)
                	                {
                        	                $error=1;
                                	}
	                                $filename=$sid."_sm.jpg";
        	                        $path=$_SERVER["DOCUMENT_ROOT"];
                	                $handle_large = fopen("$path/success/images_06_05/$filename","wb");
                        	        if($handle_large)
                                	{
                                        	fwrite($handle_large,$fcontent);
	                                        fclose($handle_large);
        	                        }
                	                else
                        	        {
                                	        die("Some error has occured while uploading photo(s).Please try again");
                	                }
					 if(upload("fullphoto",$acceptable_file_types,$default_extension))
        	                        {
                	                         $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                        	                 $fullphotocontent = fread($fp,filesize($file["tmp_name"]));
						 $field_is["PICTURE"]=addslashes($fullphotocontent);
						 $field_ss["PHOTO"]=addslashes($fullphotocontent);
				        }
	                                elseif($fullphoto)
        	                        {
                	                        $error=1;
                        	        }

/* Added by Anurag for HomePhoto*/


					 if(upload("homephoto",$acceptable_file_types,$default_extension))
        	                        {
                	                         $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                        	                 $homephotocontent = fread($fp,filesize($file["tmp_name"]));
						 $field_is["HOME_PICTURE"]=addslashes($homephotocontent);
						// $field_ss["PHOTO"]=addslashes($fullphotocontent);
				        }
	                                else   //if($homephoto)
        	                        {
                	                        $error=1;
                        	        }


/* Upto Here */



	                                if($error)//if any of the photos could not be uploaded to temp location
        	                        {
                	                        $msg="The image(s) could not be uploaded ";
                        	                $msg .="&nbsp;&nbsp;";
                                	        $msg .="<a href=\"screen_success_story.php?user=$user&cid=$cid&offline=1\">";
                                        	$msg .="Please enter the details again</a>";
	                                        $smarty->assign("MSG",$msg);
        	                                $smarty->assign("cid",$cid);
                	                        $smarty->display("jsadmin_msg.tpl");
                        	                die;
                                	}
			
			
				}
				foreach($field_ss as $key=>$value)
				{
					$fieldss.=$key.",";
					$valuess.="'".$value."',";
				}
				$dontexecute=0;
				$fieldss=substr($fieldss,0,strlen($fieldss)-1);
				$valuess=substr($valuess,0,strlen($valuess)-1);
				$sql="INSERT INTO newjs.SUCCESS_STORIES($fieldss) VALUES($valuess)";
                                mysql_query_decide($sql) or $dontexecute=1;
				$storyid=mysql_insert_id_js();
				$field_is["STORYID"]=$storyid;
				$field_v["STORYID"]=$storyid;


				foreach($field_is as $key=>$value)
                		{
                       			$fieldis.=$key.",";
                        		$valueis.="'".$value."',";
                		}
				$fieldis=substr($fieldis,0,strlen($fieldis)-1);
				$valueis=substr($valueis,0,strlen($valueis)-1);
				if(!$dontexecute)
				{
					$sql="INSERT INTO newjs.INDIVIDUAL_STORIES($fieldis) VALUES($valueis)";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
				if($field_v["CONTACT"] && $field_v["EMAIL"])
				{
					foreach($field_v as $key=>$value)
					{
						$fieldv.=$key.",";
						$valuev.="'".$value."',";
					}
					$fieldv=substr($fieldv,0,strlen($fieldv)-1);
					$valuev=substr($valuev,0,strlen($valuev)-1);
					$sql="INSERT INTO billing.VOUCHER_SUCCESSSTORY($fieldv) VALUES($valuev)";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
				$msg="You have successfully uploaded the story";
				$msg .="&nbsp;&nbsp;";
	                        $msg .="<a href=\"screen_success_story.php?user=$user&cid=$cid&offline=1\">";
        	                $msg .="Continue&gt;&gt;</a>";
                	        $smarty->assign("MSG",$msg);
                        	$smarty->assign("user",$user);
	                        $smarty->assign("cid",$cid);
        	                $smarty->display("jsadmin_msg.tpl");
                	        die;
			}
			else
			{
				if($user_h)
				$smarty->assign("user_h",$user_h);
				if($user_w)
                                $smarty->assign("user_w",$user_w);
				if($name_h)
                                $smarty->assign("name_h",$name_h);
				if($name_w)
                                $smarty->assign("name_w",$name_w);
				if($heading)
                                $smarty->assign("heading",$heading);
				if($contact)
                                $smarty->assign("contact",$contact);
				if($email)
                                $smarty->assign("email",$email);
				if($story)
                                $smarty->assign("story",$story);
				if($day)
                                $smarty->assign("day",$day);
				if($month)
				$smarty->assign("month",$month);
				if($year)
				$smarty->assign("year",$year);
			}
		}
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->assign("OFFLINE","1");
		$smarty->display("screen_success_story.htm");
		die;
	}
	elseif($Remove)
	{
		if($doremove)
		{
			$sql="UPDATE newjs.INDIVIDUAL_STORIES SET STATUS='R' WHERE SID='$sid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='R' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$msg="You have successfully removed the story";
                        $msg .="&nbsp;&nbsp;";
                        $msg .="<a href=\"screen_success_story.php?user=$user&cid=$cid&Remove=1\">";
                        $msg .="Continue&gt;&gt;</a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->assign("user",$user);
                        $smarty->assign("cid",$cid);
                        $smarty->display("jsadmin_msg.tpl");
                        die;
					
		}
		elseif($cancelremove)
		{
			header("Location:$SITE_URL/jsadmin/screen_success_story.php?cid=$cid&Remove=1");
		}
		elseif($search)
		{
			$user_h=trim($user_h);
			$user_w=trim($user_w);
			$name_h=trim($name_h);
			$name_w=trim($name_w);
			if($user_h || $user_w || $name_h || $name_w)
			{
				$sql="SELECT ID,USERNAME_H,USERNAME_W,NAME_H,NAME_W,UPLOADED FROM newjs.SUCCESS_STORIES WHERE";
				if($user_h)
				$sql.=" USERNAME_H='$user_h' AND";
				if($user_w)
				$sql.=" USERNAME_W='$user_w' AND";
				if($name_h)
				$sql.=" NAME_H='$name_h' AND";
				if($name_w)
				$sql.=" NAME_W='$name_w' AND";
				$sql=substr($sql,0,strlen($sql)-3);
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_num_rows($result))
				{
					$dontexecute=1;
					while($row=mysql_fetch_assoc($result))
					{
						if($row["UPLOADED"]!="A")
						{	
							if($user_h)
							$smarty->assign("user_h",$user_h);
							if($user_w)
							$smarty->assign("user_w",$user_w);
							if($name_h)
							$smarty->assign("name_h",$name_h);
							if($name_w)
							$smarty->assign("name_w",$name_w);			
						}
						else
						{
							$id=$row["ID"];
/* New Addition of HOME_PICTURE*/		        $sql2="SELECT SID,HEADING,STORY,PICTURE,HOME_PICTURE FROM newjs.INDIVIDUAL_STORIES WHERE STORYID='$id'";
							$result2=mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
							$row2=mysql_fetch_assoc($result2);
							$smarty->assign("showformremove","1");
							if($row2["PICTURE"])
        	        	        		{
								$photo=1;               	                
							}
							else
							$photo=0;
							$story[]=array("name_h" => $row["NAME_H"],
								       "name_w" => $row["NAME_W"],
									"user_h"=>$row["USERNAME_H"],
									"user_w"=>$row["USERNAME_W"],
						                       "story" => $row2["STORY"],
									"id" => $id,
									"sid"=>$row2["SID"],
									"heading"=>$row2["HEADING"],
									"photo"=>$photo);
							$dontexecute=0;
						}
					}
					if($dontexecute)
					$smarty->assign("NOTUP","1");
					else
					$smarty->assign("story",$story);
				}		
				else
				$smarty->assign("NOSTORY","1");
				
			}
			else
			$smarty->assign("NODATA","1");
		}
		$smarty->assign("REMOVE","1");
		
	}
	else
	{
		if($Accept)
		{
			$dontexecute=0;
			if(!$delete && $photo)
			{
				if(!$frame || !$fullphoto)
				{
					$smarty->assign("NOPIC","1");
					$dontexecute=1;
					if($skip)
					$screenskip=1;
				}
//New Code Added by Anurag
				if(!$fullphoto || !$homephoto)
				{	
					$smarty->assign("NOPIC","1");
					$dontexecute=1;
					if($skip)
					$screenskip=1;
				}
//End of Anurag Code
			}
			$story=trim($story);
			if(!$story)
			{
				$smarty->assign("NOSTORY","1");
				$dontexecute=1;
				if($skip)
					$screenskip=1;
				if($delete)
				$smarty->assign("delete","1");
			}
			if(!$dontexecute)
			{
			
				if($name_h && $name_w)
				$heading="$name_h weds $name_w";
				elseif($user_h)
				$heading=$user_h;
				else
				$heading=$user_w;
				if($photo && !$delete)
				{
					$sql="SELECT MAX(SID) AS SID FROM newjs.INDIVIDUAL_STORIES";
					$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row=mysql_fetch_assoc($result);
					$sid=$row["SID"]+1;
					if(upload("frame",$acceptable_file_types,$default_extension))
					{
						$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
        	                	        $fcontent = fread($fp,filesize($file["tmp_name"]));
					}
					elseif($frame)
					{
						$error=1;
					}
					
					$filename=$sid."_sm.jpg";
					$path=$_SERVER["DOCUMENT_ROOT"];
                	                $handle_large = fopen("$path/success/images_06_05/$filename","wb");

                        	       	if($handle_large)
                                	{
	                                        fwrite($handle_large,$fcontent);
        	                                fclose($handle_large);
					}
					else
                                	{
	                                        die("Some error has occured while uploading photo(s).Please try again");
        	                        }
 					if(upload("fullphoto",$acceptable_file_types,$default_extension))
                        	        {
                                	         $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                                        	 $fullphotocontent = fread($fp,filesize($file["tmp_name"]));
        	                        }
                 	               elseif($fullphoto)
                        	        {
                                	        $error=1;
                                	}
	
// New  Added by Anurag		
					if(upload("homephoto",$acceptable_file_types,$default_extension))
                        	        {
                                	         $fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
                                        	 $homephotocontent = fread($fp,filesize($file["tmp_name"]));
						 $field_is["HOME_PICTURE"]=addslashes($homephotocontent);  // New Addition
        	                        }
                 	               else //if($homephoto)
                        	        {
                                	        $error=1;
                                	}

// upto here


				  	if($error)//if any of the photos could not be uploaded to temp location
                	        	{
                        	        	$msg="The image(s) could not be uploaded ";
	                        	        $msg .="&nbsp;&nbsp;";
        	                        	$msg .="<a href=\"screen_success_story.php?user=$user&cid=$cid\">";
	                	                $msg .="Please screen the story and photos again</a>";
        	                	        $smarty->assign("MSG",$msg);
                	                	$smarty->assign("cid",$cid);
	                	                $smarty->display("jsadmin_msg.tpl");
        	                	        die;
                	        	}
				}
				if($user_h || $user_w)
				{
					$sql="SELECT PROFILEID,PHONE_RES,PHONE_MOB,CITY_RES,GENDER,COUNTRY_RES,OCCUPATION,RELIGION,CASTE,MTONGUE FROM 						newjs.JPROFILE WHERE EMAIL='$email'";
							$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
							if($row=mysql_fetch_assoc($result)){ }
							elseif($user_h && $k!=1)
							{
								
							$sql="SELECT PROFILEID,PHONE_RES,PHONE_MOB,CITY_RES,COUNTRY_RES,OCCUPATION,RELIGION,CASTE,MTONGUE FROM newjs.JPROFILE WHERE USERNAME='$user_h'";
							$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
							if(mysql_num_rows($result)==0)
							{
								if($user_w)
								{
									$sql="SELECT PROFILEID,PHONE_RES,PHONE_MOB,CITY_RES,COUNTRY_RES, 										OCCUPATION,RELIGION,CASTE,MTONGUE FROM newjs.JPROFILE WHERE 										USERNAME='$user_w'";
				//echo					$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
									 if(mysql_num_rows($result)>0)
									$row=mysql_fetch_assoc($result);
								}
							}
							else
								$row=mysql_fetch_assoc($result);
						
						}
						if($row)
						{
								if($row['GENDER']=='M')
									$NAME=$name_h;
								else 	
									$NAME=$name_w;
									
								$cityres=$row["CITY_RES"];
								$profileid=$row["PROFILEID"];
								$phone_res=$row["PHONE_RES"];
								$phone_mob=$row["PHONE_MOB"];
								$country_res=$row["COUNTRY_RES"];
								$religion=$row["RELIGION"];
								$caste=$row["CASTE"];
								$mtongue=$row["MTONGUE"];
								$occupation=$row["OCCUPATION"];
						}
				}
				
// Insertion of HOME_PICTURE in database -- Done

				if($photo && !$delete)
				{
					$fullphotocontent=addslashes($fullphotocontent);
					$homephotocontent=addslashes($homephotocontent);
					$sql="INSERT ignore into newjs.INDIVIDUAL_STORIES(SID,STORYID,NAME1,NAME2,HEADING,STORY,PICTURE,HOME_PICTURE,STATUS,YEAR,CITY,COUNTRY,OCCUPATION,CASTE,RELIGION,MTONGUE) VALUES('$sid','$id','$name_h','$name_w','$heading','$story','$fullphotocontent','$homephotocontent','A','$year','$cityres','$country_res','$occupation','$caste','$religion','$mtongue')";
					
					
				}
				else
				{
					 $sql="INSERT INTO newjs.INDIVIDUAL_STORIES(STORYID,NAME1,NAME2,HEADING,STORY,STATUS,YEAR,CITY,COUNTRY,OCCUPATION,CASTE,RELIGION,MTONGUE) VALUES('$id','$name_h','$name_w','$heading','$story','A','$year','$cityres','$country_res','$occupation','$caste','$religion','$mtongue')";
				}
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='A' WHERE ID='$id'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="SELECT  SEND_EMAIL,EMAIL,CONTACT_DETAILS FROM newjs.SUCCESS_STORIES WHERE ID='$id'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($result);
				$email=$row['SEND_EMAIL'];
				if($email=="")
					$email=$row['EMAIL'];
			
				$contact=$row["CONTACT_DETAILS"];
				
				$sql="INSERT INTO billing.VOUCHER_SUCCESSSTORY(STORYID,PROFILEID,USERNAME_H,USERNAME_W,NAME_H,NAME_W,CONTACT,PHONE_RES,PHONE_MOB,EMAIL,CITY_RES) VALUES('$id','$profileid','$user_h','$user_w','$name_h','$name_w','$contact','$phone_res','$phone_mob','$email','$cityres')";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				
				if($profileid!="")
				{	
					//issuevouchers($profileid,$NAME,$id);
					rand_discount_no($profileid,$NAME,$email);
				}	
				header("Location:screen_success_story.php?user=$user&cid=$cid");
        		      
        	        	die;
			}
		
		}
		elseif($Reject)
		{	
			$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='D' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			
			header("Location:screen_success_story.php?user=$user&cid=$cid");
 			
	                die;
		}
		elseif($Hold)
		{
			$smarty->assign("MAIL","1");
			$smarty->assign("name_h",$name_h);
			$smarty->assign("name_w",$name_w);
			$smarty->assign("id",$id);
			$smarty->assign("email",$email);			
			$smarty->assign("cid",$cid);
			$smarty->assign("user",$user);
			if($skip)
			$smarty->assign("skip",$skip);
			$smarty->display("screen_success_story.htm");
			die;
		}
		elseif($Send)
		{
			$mail=trim($mail);
			$mail=nl2br($mail);
			send_mail($email,'','',$mail,'Success Story Held','Promotions@jeevansathi.com');
			$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='H' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$msg="The mail has been sent successfully";
            $msg .="&nbsp;&nbsp;";
            $msg .="<a href=\"screen_success_story.php?user=$user&cid=$cid";
			if($skip)
        	    $msg.="&Vskip=1";
            $msg.="\">";
            $msg .="Continue&gt;&gt;</a>";
            $smarty->assign("MSG",$msg);
            $smarty->assign("user",$user);
            $smarty->assign("cid",$cid);
            $smarty->display("jsadmin_msg.tpl");
                        die;
		}
		elseif($Skip)
		{
			$smarty->assign("FROM","SS");
			$smarty->assign("user",$user);
			$smarty->assign("cid",$cid);
			$smarty->assign("id",$id);
			$smarty->assign("c","1");
			$smarty->display("skip_page.htm");
			die;			
		}
		if($screenskip)
		{
			$sql="SELECT ID,USERNAME,NAME_H,NAME_W,WEDDING_DATE,CONTACT_DETAILS,SEND_EMAIL,EMAIL,COMMENTS,USERNAME_H,USERNAME_W,PHOTO,DATETIME FROM newjs.SUCCESS_STORIES WHERE ID='$id'";
			$smarty->assign("showformskip","1");
			$res=mysql_query_decide($sql);
                        $row=mysql_fetch_assoc($res);
                        $storyid=$row['ID'];
                        $USERNAME_H=$row['USERNAME_H'];
                        $USERNAME_W=$row['USERNAME_W'];
                        $EMAIL=$row['EMAIL'];
                        $SEND_EMAIL=$row['SEND_EMAIL'];
		        $dateandtime=$row["DATETIME"];  // New Addition of date and time for Skip Section by Anurag 
						

                        $sql1="select ID,PHOTO,UPLOADED from newjs.SUCCESS_STORIES where ((USERNAME_H='".addslashes($USERNAME_H)."' AND USERNAME_H is not null AND USERNAME_H!='') or( USERNAME_W='".addslashes($USERNAME_W)."' AND USERNAME_W is not null AND USERNAME_W!='') or (EMAIL='$EMAIL'  AND EMAIL is not null AND EMAIL!='') or (SEND_EMAIL='$SEND_EMAIL'  AND SEND_EMAIL is not null AND SEND_EMAIL!='')) AND  ID!='$storyid' and UPLOADED='A'";
			$res1=mysql_query_decide($sql1);
			if($row1=mysql_fetch_array($res1))
			{	
				$sql="update newjs.SUCCESS_STORIES set UPLOADED='X' where ID='$storyid'";
				mysql_query_decide($sql) or die(mysql_error_js());
				
				echo "<script>alert(\"This story has already been uploaded,\\r\\n marking story as duplicate\");document.location='screen_success_story.php?cid=$cid&user=$user&Vskip=1';</script>";
				exit;
			}

			
		}
		else
		{
			while(1)
			{
				$duplicate='';
				//Inserted DATETIME in the query for Reflecting Date and Time in the Screened Page

				$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS ID,USERNAME,NAME_H,NAME_W,WEDDING_DATE,CONTACT_DETAILS,SEND_EMAIL,EMAIL,COMMENTS,USERNAME_H,USERNAME_W,PHOTO,DATETIME FROM newjs.SUCCESS_STORIES WHERE UPLOADED='N'  AND UPLOADED<>'S' AND UPLOADED!='X'  ORDER BY DATETIME ASC LIMIT 1";
				$res=mysql_query_decide($sql);
				$row=mysql_fetch_assoc($res);
				$storyid=$row['ID'];
				$USERNAME_H=$row['USERNAME_H'];
				$USERNAME_W=$row['USERNAME_W'];
				$EMAIL=$row['EMAIL'];
				$SEND_EMAIL=$row['SEND_EMAIL'];
				$dateandtime=$row["DATETIME"];
					
				$sql1="select ID,PHOTO,UPLOADED from newjs.SUCCESS_STORIES where ((USERNAME_H='".addslashes($USERNAME_H)."' AND USERNAME_H is not null AND USERNAME_H!='') or( USERNAME_W='".addslashes($USERNAME_W)."' AND USERNAME_W is not null AND USERNAME_W!='') or (EMAIL='$EMAIL'  AND EMAIL is not null AND EMAIL!='') or (SEND_EMAIL='$SEND_EMAIL'  AND SEND_EMAIL is not null AND SEND_EMAIL!='')) AND  ID!='$storyid' and UPLOADED='A'";
		
				$res1=mysql_query_decide($sql1) or die(mysql_error_js());
				if($row1=mysql_fetch_assoc($res1))
				{
					if($row1['PHOTO']!="" || $row['PHOTO']=="")
					{
						$duplicate='X';
					}
					elseif($row1['PHOTO']=="" && $row['PHOTO']=="")
					{
						$duplicate="X";
					}
					if($duplicate=="X")
						$id=$row['ID'];
					else
						$id=$row1["ID"];

					$ss="update newjs.SUCCESS_STORIES set UPLOADED='X' where ID=$id";
					$is="update newjs.INDIVIDUAL_STORIES set STATUS='X' where STORYID=$id";
					mysql_query_decide($ss) or die(mysql_error_js());
					mysql_query_decide($is) or die(mysql_error_js());
				}
				
				if($duplicate!='X')
					break;
			}
		
		}
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());	
		
		$res_total=mysql_query_decide("select found_rows() as cnt") or die(mysql_error_js());
		$row_total=mysql_fetch_row($res_total);
		
		$smarty->assign("row_total",$row_total[0]);

		$row=mysql_fetch_assoc($result);
		$smarty->assign("SCREEN","1");
		list($year,$month,$day)=explode("-",$row['WEDDING_DATE']);
		$smarty->assign("year",$year);
		$smarty->assign("wedding_date",$row["WEDDING_DATE"]);
		//For Assigning Date & Time for the Screened Page i.e 1st Page
		$smarty->assign("dateandtime",$dateandtime); 
	        $smarty->assign("contact",$row["CONTACT_DETAILS"]);
		if(!$comments)
	        $smarty->assign("comments",$row["COMMENTS"]);
		else
		$smarty->assign("comments",$comments);
	        if($row["PHOTO"])
	        {
	                $smarty->assign("photo","1");
	        }
		if($year==""||$year=="0000")
			$year=2007;

	        for($i=date("Y");$i>=($year-5);$i--)
        	{
                	if($year==$i)
				$YEAR.="<option value=$i selected>$i</option>";
			else
				$YEAR.="<option value=$i>$i</option>";
	        }
		$smarty->assign("YEAR",$YEAR);

		$smarty->assign("username",$row["USERNAME"]);
		$smarty->assign("name_h",$row["NAME_H"]);
	    $smarty->assign("name_w",$row["NAME_W"]);
        $smarty->assign("user_h",$row["USERNAME_H"]);
        $smarty->assign("user_w",$row["USERNAME_W"]);
	    if($row['SEND_EMAIL']=="")
	    	$smarty->assign("email",$row["EMAIL"]);
	    else
	    	$smarty->assign("email",$row["SEND_EMAIL"]);
	    	
        $smarty->assign("id",$row["ID"]);
	}

	$smarty->assign("cid",$cid);
        $smarty->assign("user",$user);
	
        $smarty->display("screen_success_story.htm");
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
