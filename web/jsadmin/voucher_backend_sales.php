<?php
/************************************************************************************************
Filename     :  voucher_backend_sales.php 
Description  :  Backend module for sales team to upload new deal / edit existing deal [2177]
Created On   :  3 September 2007
Created By   :	Sadaf Alam
*************************************************************************************************/

include("connect.inc");
include("../crm/func_sky.php");
$db=connect_db();
$path=$_SERVER["DOCUMENT_ROOT"];

if(authenticated($cid))
{
	if($Skip)
	{
		$sqlstatus="SELECT SERVICE,STATUS,CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
		$resstatus=mysql_query_decide($sqlstatus) or die("$sqlstatus".mysql_error_js());
		$rowstatus=mysql_fetch_assoc($resstatus);
		if($rowstatus["SERVICE"]!="N")
		{
			if(($rowstatus["STATUS"]=="T" || $rowstatus["STATUS"]=="OT")&& $rowstatus["SERVICE"]=='')
			{
				$sql="UPDATE billing.VOUCHER_CLIENTS SET STATUS=";
				if($rowstatus["STATUS"]=="T")
				$sql.="'D'";
				else
				$sql.="'C'";
				$sql.="WHERE CLIENTID='$clientid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}
			$smarty->assign("tech","1");
			$smarty->assign("skip","1");
			$smarty->assign("donedeal",$rowstatus["CLIENT_NAME"]);
			$smarty->assign("DONE","1");
			$sql="SELECT CLIENT_NAME,CDETAILS,HEADLINE,VSUMMARY,VDETAILS,COMMENTS,START_DATE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			$msg="A new deal has been assigned for designing details. The client details are as follows :";
			$msg.="<br> Client Name : $row[CLIENT_NAME]";
			$msg.="<br> Client Details : $row[CDETAILS]";
			$msg.="<br> Headline : $row[HEADLINE]";
			$msg.="<br> Voucher Summary : $row[VSUMMARY]";
			$msg.="<br> Voucher Details : $row[VDETAILS]";
			$msg.="<br> Comments : $row[COMMENTS]";
			$msg.="<br> Start Date : $row[START_DATE]";
			$msg.="<br> Kindly update the design details and forward to the tech team from the Design pending deals section";
			$subject="Deal Received for design details : ".$insert["CLIENT_NAME"];
			send_mail("ashish.anand@jeevansathi.com,shweta.bahl@naukri.com",'lotika.sharma@naukri.com','',$msg,$subject,"promotions@jeevansathi.com");
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->display("voucher_backend_sales.htm");
			die;
		}
		else
		{
			$msg="The deal has been stopped.";
                        $msg .="&nbsp;&nbsp;";
                        $msg .="<a href=\"voucher_backend_sales.php?name=$name&cid=$cid&tech=1\">";
                        $msg .="Click here to continue&gt;&gt;</a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->assign("name",$name);
                        $smarty->assign("cid",$cid);
                        $smarty->display("jsadmin_msg.tpl");
                        die;
		}
	}
	if($cmode)
	{
		if($mode)
		{
			$smarty->assign("techdeal","1");
			$smarty->assign("mode",$mode);
			$smarty->assign("clientid",$clientid);
			$smarty->assign("edate",$edate);
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->display("voucher_backend_sales.htm");
			die;
		}
		else
		{
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->assign("techdeal","1");
			$smarty->assign("error","Choose atleast one mode!");
			$smarty->assign("edate",$edate);
			$smarty->assign("comments",$comments);
			$smarty->assign("num",$num);
			$smarty->assign("cname",$cname);
			$smarty->assign("duration",$duration);
			$smarty->assign("clientid",$clientid);
			$smarty->assign("sdate",$sdate);
			$smarty->display("voucher_backend_sales.htm");
			die;
		}
	}
	if($techsubmit)
	{
		$sqlstatus="SELECT SERVICE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
		$resstatus=mysql_query_decide($sqlstatus) or die("$sqlstatus".mysql_error_js());
		$rowstatus=mysql_fetch_assoc($resstatus);
		if($rowstatus["SERVICE"]!="N")
		{
			if($mode=="csv")
			{
			if($seriesfile)
			{
				$file=$_FILES["seriesfile"];
				if(!strpos($file["type"],"csv") && !strpos($file["name"],"csv") && !strpos($file2["name"],"CSV"))
				{
					$error="Only csv files are allowed!";
				}
				else
				{
					$fp=fopen($file["tmp_name"],"rb");
					if($fp)
					{	
						$size=$file["size"];
						$fcontent=fread($fp,$size);
						fclose($fp);
					}
					else
					$error="Error in file being uploaded!";	
					if($fcontent)
					{
						$path=$_SERVER["DOCUMENT_ROOT"];
						$fp=fopen("$path/jsadmin/uploadfile.csv","wb");
						fwrite($fp,$fcontent);
						fclose($fp);
						$sql="TRUNCATE billing.testvoucher";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$filename="$path/jsadmin/uploadfile.csv";
						$sql="LOAD DATA LOCAL INFILE '$filename' INTO TABLE billing.testvoucher FIELDS TERMINATED BY ','";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$sql="SELECT * FROM billing.testvoucher";
						$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						if(mysql_num_rows($res))
						{
							while($row=mysql_fetch_assoc($res))
							{
								$sqlins="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('$clientid','$row[voucherno]','E','$edate')";
								mysql_query_decide($sqlins) or die("$sqlins".mysql_error_js());
							}
							$sql="TRUNCATE billing.testvoucher";
							mysql_query_decide($sql) or die("$sql".mysql_error_js());
							$done=1;
						}
						else
						$error="No rows in csv!";
					}
				}
			}	
			else
			{
				$error="No file uploaded!";
			}
			}
			elseif($mode=="common")
			{
				if(trim($vnum) && trim($code))
				{
					$y=trim($vnum);
					$code=trim($code);
					if($y>0)
					{
						for($i=1;$i<=$y;$i++)
						{
							$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('$clientid','$code','E','$edate')";
							mysql_query_decide($sql) or die("$sql".mysql_error_js());
					 	}
						$done=1;
					}
					else
					{
						$smarty->assign("code",trim($code));
						$error="Please enter a valid number of vouchers!";
					}
				
				}
				else
				{
					if(!trim($vnum) && !trim($code))
					$error="Please enter number of vouchers and common code!";
					elseif(!$code)
					{
						$smarty->assign("vnum",trim($vnum));
						$error="Please enter common code!";
					}
					else
					{
						$smarty->assign("code",trim($code));
						$error="Please enter number of vouchers!";
					}
				}
			}
			elseif($mode=="nocode")
			{
				if(trim($vnum))
				{
					$y=trim($vnum);
					if($y>0)
					{
						for($i=1;$i<=$y;$i++)
						{
							$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('$clientid','$i','E','$edate')";
							mysql_query_decide($sql) or die("$sql".mysql_error_js());
						}
						$done=1;
					}
					$error="Please enter a valid number of vouchers!";
				}
				$error="Please enter a valid number of vouchers!";
			}
		        if($done)
			{
				$sqlstatus="SELECT CLIENT_NAME,SERVICE,STATUS FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
				$resstatus=mysql_query_decide($sqlstatus) or die("$sqlstatus".mysql_error_js());
				$rowstatus=mysql_fetch_assoc($resstatus);
				if(($rowstatus["STATUS"]=="T" || $rowstatus["STATUS"]=="OT") && $rowstatus["SERVICE"]=='')
				{
					$sql="UPDATE billing.VOUCHER_CLIENTS SET ";
					if($rowstatus["STATUS"]=="T")
					$sql.="STATUS='D' WHERE CLIENTID='$clientid'";
					else
					$sql.="STATUS='C' WHERE CLIENTID='$clientid'";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
					if($rowstatus["STATUS"]=="T")
					{
						$sql="SELECT CLIENT_NAME,CDETAILS,HEADLINE,VSUMMARY,VDETAILS,COMMENTS,START_DATE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
						$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$row=mysql_fetch_assoc($res);
						$msg="A new deal has been assigned for designing details. The client details are as follows :";
						$msg.="<br> Client Name : $row[CLIENT_NAME]";
						$msg.="<br> Client Details : $row[CDETAILS]";
						$msg.="<br> Headline : $row[HEADLINE]";
						$msg.="<br> Voucher Summary : $row[VSUMMARY]";
						$msg.="<br> Voucher Details : $row[VDETAILS]";
						$msg.="<br> Comments : $row[COMMENTS]";
						$msg.="<br> Start Date : $row[START_DATE]";
						$msg.="<br> Kindly update the design details and forward to the tech team from the Design pending deals section";
						$subject="Deal Received for design details : ".$insert["CLIENT_NAME"];
						send_mail("ashish.anand@jeevansathi.com,shweta.bahl@naukri.com",'lotika.sharma@naukri.com','',$msg,$subject,"promotions@jeevansathi.com");
					}
				}
				$smarty->assign("DONE","1");
				$smarty->assign("tech","1");
				$smarty->assign("donedeal",$rowstatus["CLIENT_NAME"]);
				$smarty->assign("name",$name);
				$smarty->assign("cid",$cid);
				$smarty->display("voucher_backend_sales.htm");
				die;
			}
			$smarty->assign("error",$error);
			$smarty->assign("techdeal","1");
			$smarty->assign("cid",$cid);
			$smarty->assign("name",$name);
			$smarty->assign("edate",$edate);
			$smarty->assign("comments",$comments);
			$smarty->assign("sdate",$sdate);
			$smarty->assign("duration",$duration);
			$smarty->assign("cname",$cname);
			$smarty->assign("clientid",$clientid);
			$smarty->assign("num",$num);
			$smarty->assign("mode",$mode);
			$smarty->display("voucher_backend_sales.htm");
			die;
		}
		else
		{
			$msg="The deal has been stopped.";
                        $msg .="&nbsp;&nbsp;";
                        $msg .="<a href=\"voucher_backend_sales.php?name=$name&cid=$cid&tech=1\">";
                        $msg .="Click here to continue&gt;&gt;</a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->assign("name",$name);
                        $smarty->assign("cid",$cid);
                        $smarty->display("jsadmin_msg.tpl");
                        die;
		}
	}
	if($techdeal)
	{
		$sql="SELECT SERVICE,CLIENT_NAME,DURATION,NUM,START_DATE,EXPIRY_DATE,COMMENTS FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_assoc($res);
		if($row["SERVICE"]!="N")
		{
			$smarty->assign("cname",$row["CLIENT_NAME"]);
			$smarty->assign("duration",$row["DURATION"]);
			$smarty->assign("num",$row["NUM"]);
			$smarty->assign("sdate",$row["START_DATE"]);
			$smarty->assign("edate",$row["EXPIRY_DATE"]);
			$smarty->assign("comments",$row["COMMENTS"]);
			$smarty->assign("clientid",$deal);
			$smarty->assign("techdeal","1");
			$smarty->assign("cid",$cid);
			$smarty->assign("name",$name);
			$smarty->display("voucher_backend_sales.htm");
			die;
		}	
		else
		{
			$msg="The deal has been stopped.";
                        $msg .="&nbsp;&nbsp;";
                        $msg .="<a href=\"voucher_backend_sales.php?name=$name&cid=$cid&tech=1\">";
                        $msg .="Click here to continue&gt;&gt;</a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->assign("name",$name);
                        $smarty->assign("cid",$cid);
                        $smarty->display("jsadmin_msg.tpl");
                        die;

		}		
	}
	if($Stopdeal)
	{
		$sql="SELECT CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_assoc($result);
		$sql="UPDATE billing.VOUCHER_CLIENTS SET SERVICE='N' WHERE CLIENTID='$deal'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$msg="The following deal has been stopped by the sales team : $row[CLIENT_NAME]";
		$subject="Deal $row[CLIENT_NAME] stopped";
		send_mail('shweta.bahl@naukri.com','lotika.sharma@naukri.com','',$msg,$subject,"promotions@jeevansathi.com");
		$smarty->assign("donedeal",$row["CLIENT_NAME"]);
		$smarty->assign("DONE","1");
		$smarty->assign("stop","1");
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->display("voucher_backend_sales.htm");
		die;
		
	}
	if($formsubmit || $designsubmit || $directsubmit || $sersubmit || $destechsubmit)
	{
		$sql_status="SELECT STATUS,SERVICE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
		$res_status=mysql_query_decide($sql_status) or die("$sql_status".mysql_error_js());
		$row_status=mysql_fetch_assoc($res_status);
		if($designsubmit)
		{
			if($row_status["SERVICE"]=="N")
			$insert["STATUS"]="D";
			else
			{
				if($row_status["STATUS"]=="OT" || $row_status["STATUS"]=="T")
				$insert["STATUS"]="T";
				elseif($row_status["STATUS"]=="D" || $row_status["STATUS"]=="RD")
				$insert["STATUS"]="RD";
				else
				$insert["STATUS"]="D";
			}
		}
		if($sersubmit)
		$insert["STATUS"]="OT";
		if($destechsubmit)
		$insert["STATUS"]="T";
		if($directsubmit)
		{
			if($row_status["SERVICE"]=="N")	
			{
				$insert["STATUS"]="C";
			}
		}
		if($row_status["SERVICE"]=="N")
		$insert["SERVICE"]="";
		$error=0;
		if(!trim($cname))
		{
			$error=1;
			$smarty->assign("cname_error","1");
		}	
		if(!trim($headline))
		{
			$error=1;
			$smarty->assign("headline_error","1");
		}
		if(!trim($summary))
		{
			$error=1;
			$smarty->assign("summary_error","1");
		}
		if(!trim($vdetails))
		{
			$error=1;
			$smarty->assign("vdetails_error","1");
		}
		if(!trim($cdetails))
		{
			$error=1;
			$smarty->assign("cdetails_error","1");	
		}
		if($new && !$zipfile)
		{
			$error=1;
			$smarty->assign("zipfile_error","1");
		}
		if(!trim($username))
		{
			$error=1;
			$smarty->assign("username_error","1");
		}
		if(!trim($password))
		{
			$error=1;
			$smarty->assign("password_error","1");
		}
		if($zipfile)
		{
			$file=$_FILES["zipfile"];
			if(!strpos($file["type"],"zip") && !strpos($file["type"],"Zip") && !$strpos($file["type"],"ZIP") && !strpos($file["name"],"zip") && !strpos($file["name"],"ZIP"))
			{
				$error=1;
				$smarty->assign("zipfiletype_error","1");
			}
		}
		if(!$editdeal || $row_status["SERVICE"]=="N")
		{
			$today=date("Y-m-d");
			list($todyear,$todmonth,$todday)=explode("-",$today);
			if($todyear==$year)
			{
				if($todmonth>$month)
				{
					$error=1;
					$smarty->assign("date_error","1");
				}
				elseif($todmonth==$month)
				{
					if($service=="N")
					{
						if($day-$todday<2)
						{
							$error=1;
							$smarty->assign("date_error","1");
						}
					}
					else
					{
						if($day<$todday)
						{
							$error=1;
							$smarty->assign("date_error","1");
						}
					}
				}
			}
		}
		if(!$slab)
		{
			$error=1;
			$smarty->assign("slab_error","1");
		}
		if(!$contact)
		{
			$error=1;
			$smarty->assign("contact_error","1");
		}
		if(!$state)
		{
			$error=1;
			$smarty->assign("state_error","1");
		}
		if(!$city)
		{
			$error=1;
			$smarty->assign("city_error","1");
		}
		if(trim($hyperlink))
		{
			if(substr(trim($hyperlink),0,7)!="http://")
			{
				$error=1;
				$smarty->assign("hyperlink_error","1");
			}
		}
		if($error)
		{
			if($cname)
			$smarty->assign("cname",trim($cname));
			if($headline)
			$smarty->assign("headline",trim($headline));
			if($summary)
			$smarty->assign("summary",trim($summary));
			if($vdetails)
			$smarty->assign("vdetails",trim($vdetails));
			if($cdetails)
			$smarty->assign("cdetails",trim($cdetails));
			if($number)
			$smarty->assign("number",trim($number));
			if($leftnum)
			$smarty->assign("leftnum",$leftnum);
			if($slab)
			{
				if(in_array("All",$slab))
				$slabs="<option value=All selected>All</option><option value=2>2 months</option><option value=3>3 months</option><option value=4>4 months</option><option value=5>5 months</option><option value=6>6 months</option><option value=12>12 months</option>";
				else
				{
					if(in_array("2",$slab))
					$slabs.="<option value=2 selected>2 months</option>";
					else
					$slabs.="<option value=2>2 months</option>";
					if(in_array("3",$slab))
					$slabs.="<option value=3 selected>3 months</option>";
					else
					$slabs.="<option value=3>3 months</option>";
					if(in_array("4",$slab))
					$slabs.="<option value=4 selected>4 months</option>";
					else
					$slabs.="<option value=4>4 months</option>";
					if(in_array("5",$slab))
					$slabs.="<option value=5 selected>5 months</option>";
					else
					$slabs.="<option value=5>5 months</option>";
					if(in_array("6",$slab))
					$slabs.="<option value=6 selected>6 months</option>";
					else
					$slabs.="<option value=6>6 months</option>";
					if(in_array("12",$slab))
					$slabs.="<option value=12 selected>12 months</option>";
					else
					$slabs.="<option value=12>12 months</option>";
				}
			}
			else
			$slabs="<option value=All>All</option><option value=2>2 months</option><option value=3>3 months</option><option value=4>4 months</option><option value=5>5 months</option><option value=6>6 months</option><option value=12>12 months</option>";
			if($contact)
			{
				if(in_array("N",$contact))
				$contact_display="<option value=N selected>None</option>";
				else
				$contact_display="<option value=N>None</option>";
				if(in_array("A",$contact))
				$contact_display.="<option value=A selected>Address</option>";
				else
				$contact_display.="<option value=A>Address</option>";
				if(in_array("E",$contact))
				$contact_display.="<option value=E selected>Email ID</option>";
				else
				$contact_display.="<option value=E>Email ID</option>";
				if(in_array("P",$contact))
				$contact_display.="<option value=P selected>Phone Number</option>";
				else
				$contact_display.="<option value=P>Phone Number</option>";
				if(in_array("C",$contact))
				$contact_display.="<option value=C selected>City</option>";
				else
				$contact_display.="<option value=C>City</option>";
				if(in_array("Y",$contact))
				$contact_display.="<option value=Y selected>All</option>";
				else
				$contact_display.="<optin value=Y>All</option>";
			}
			else
			$contact_display="<option value=N>None</option><option value=A>Address</option><option value=E>Email ID</option><option value=P>Phone Number</option><option value=C>City</option><option value=Y>All</option>";
			
			$sql="SELECT SQL_CACHE VALUE, LABEL,TYPE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($city && in_array("All",$city))
			$city_india="<option value=All selected>All/None</option>";
			else
			$city_india="<option value=All>All/None</option>";
			if($state && in_array("All",$state))
			$state_india="<option value=All selected>All/None</option>";
			else
			$state_india="<option value=All>All/None</option>";
			while($row=mysql_fetch_assoc($result))
			{
				$value=$row["VALUE"];
				$label=$row["LABEL"];
				if($row["TYPE"]=="CITY")
				{
					if($city && in_array($value,$city))
					$city_india.="<option value=$value selected>$label</option>";
					else
					$city_india.="<option value=$value>$label</option>";
					
				}
				else
				{
					if($state && in_array($value,$state))
					$state_india.="<option value=$value selected>$label</option>";
					else
					$state_india.="<option value=$value>$label</option>";
				}
		
			}
			if(in_array("NRI",$state))
			$state_india.="<option value=NRI selected>International</option>";
			else
			$state_india.="<option value=NRI>International</option>";
			$smarty->assign("contact_display",$contact_display);
			$smarty->assign("slabs",$slabs);
			$smarty->assign("city_india",$city_india);
			$smarty->assign("state_india",$state_india);
			$smarty->assign("type",$type);
			$smarty->assign("gender",$gender);
			$smarty->assign("slab",$slab);
			if($display)
			$smarty->assign("display",$display);
			$smarty->assign("day",$day);
			$smarty->assign("month",$month);
			$smarty->assign("year",$year);
			$smarty->assign("duration",$duration);
			if($comments)
			$smarty->assign("comments",trim($comments));
			if($username)
			$smarty->assign("username",trim($username));
			if($password)
			$smarty->assign("password",trim($password));
			if($hyperlink)
			$smarty->assign("hyperlink",trim($hyperlink));
			if($new)
			$smarty->assign("new","1");
			if($directsubmit || $sersubmit || $designsubmit || $destechsubmit)
			{
				$smarty->assign("deal",$deal);
				if($design)
				{
					if($oldvoucher)
					$smarty->assign("oldvoucher",$oldvoucher);
					$smarty->assign("design","1");
				}
				$smarty->assign("editdeal","1");
				$smarty->assign("service",$service);
				$sql="SELECT IMAGE_FILE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($result);
				if($row["IMAGE_FILE"])
				{
					$filename=$row["IMAGE_FILE"];
					$link="<a href=\"voucher_backend_download.php?filename=$filename&clientid=$deal&design=1\">Download</a>";
					$smarty->assign("imagelink",$link);
				}
				
			}
			$smarty->assign("error","1");
		}
		else
		{
			$insert["CLIENT_NAME"]=trim($cname);
			$insert["TYPE"]=$type;
			if($gender!="B")
			$insert["GENDER"]=$gender;
			$headline=trim($headline);
			$summary=trim($summary);
			$vdetails=trim($vdetails);
			$cdetails=trim($cdetails);
			$username=trim($username);
			$password=trim($password);
			$hyperlink=trim($hyperlink);
			$insert["HEADLINE"]=htmlentities($headline,ENT_QUOTES);
			$insert["VSUMMARY"]=htmlentities($summary,ENT_QUOTES);
			$insert["VDETAILS"]=htmlentities($vdetails,ENT_QUOTES);
			$insert["CDETAILS"]=htmlentities($cdetails,ENT_QUOTES);
			if(in_array("N",$contact))
			$insert["CONTACTS_DISPLAY"]='';
			elseif(in_array("Y",$contact))
			$insert["CONTACTS_DISPLAY"]="Y";
			else
			{
				foreach($contact as $key=>$value)
				$insert["CONTACTS_DISPLAY"].=$value.",";
				$insert["CONTACTS_DISPLAY"]=substr($insert["CONTACTS_DISPLAY"],0,strlen($insert["CONTACTS_DISPLAY"])-1);
			}
			if(in_array("All",$slab))
			     ;
			else
			{
				$insert["SLABS"]=implode(",",$slab);				
			}
			if($number)
			$insert["NUM"]=$number;
			if($new)
			$insert["SERVICE"]='';
			if($city)
			{
				if(!in_array("All",$city))
				{
					for($i=0;$i<count($city);$i++)
					$insert["AVAILABLE_IN"].=$city[$i].",";
				}
			}
			if($state)
			{
				if(!in_array("All",$state))
				{
					if($insert["AVAILABLE_IN"])
					$insert["AVAILABLE_IN"]=substr($insert["AVAILABLE_IN"],0,strlen($insert["AVAILABLE_IN"])-1);
					$insert["AVAILABLE_IN"].=":";
					for($i=0;$i<count($state);$i++)
					$insert["AVAILABLE_IN"].=$state[$i].",";
				}
			}
			if($insert["AVAILABLE_IN"])
			$insert["AVAILABLE_IN"]=substr($insert["AVAILABLE_IN"],0,strlen($insert["AVAILABLE_IN"])-1);
			else
			$insert["AVAILABLE_IN"]='';
			$insert["START_DATE"]=$year."-".$month."-".$day;
			$insert["DURATION"]=$duration;
			if($duration%12==0)
			{
				if($duration<12)
				{
					$expyear=$year;
					$expmonth=$expmonth+$duration;
				}
				else
				{
					$expyear=$year+($duration/12);
					$expmonth=$month;
				}
				$insert["EXPIRY_DATE"]=$expyear."-".$expmonth."-".$day;
			}
			else
			{
				if(($duration/12)>1)
				{
					$expyear=$year+1;
					$expmonth=$duration-12+$month;
					if($expmonth>12)
					{
						$expyear++;
						$expmonth-=12;
					}
				}
				else
				{
					$expmonth=$duration+$month;
					if($expmonth>12)
					{
						$expyear=$year+1;
						$expmonth-=12;
					}
					else
					$expyear=$year;
				}
				$insert["EXPIRY_DATE"]=$expyear."-".$expmonth."-".$day;
			}
			$insert["USERNAME"]=$username;
			$insert["PASSWORD"]=$password;
			$insert["COMMENTS"]=htmlentities($comments,ENT_QUOTES);
			$insert["HYPERLINK"]=$hyperlink;
			if($new)
			{
				$sql="SELECT MAX(ID) AS ID FROM billing.VOUCHER_CLIENTS";
				$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($result);
				$id=$row["ID"]+1;
				$clientid=substr($insert["CLIENT_NAME"],0,3);
				$clientid=trim($clientid);
				if(strstr($clientid," "))
				$clientid=substr($clientid,0,1);
				$clientid=strtoupper($clientid);
				$clientid.=$id;
				$insert["ID"]=$id;
				$insert["CLIENTID"]=$clientid;
				if($type=="E")
				$insert["STATUS"]="T";
				else
				$insert["STATUS"]="D";
			}
			if($file)
			{
				$filename=$file["name"];
				$fp=fopen($file["tmp_name"],"rb");
				if($fp)
				{
					if(filesize($file["tmp_name"]))
					{
						$fcontent=fread($fp,filesize($file["tmp_name"]));
						$insert["IMAGE_FILE"]=addslashes(stripslashes($filename));
						$insert["IMAGEFILE_CONTENT"]=addslashes($fcontent);
					}
					else
					die("Please check the image zip file being uploaded");
				}
				else
				{
						die("Some error occured during reading the image zip file. Please try again");
						
				}	
			}
			 if($new)
			{
				foreach($insert as $key=>$value)
				{
					$field.=$key.",";
					$values.="'".$value."',";
				}
				$field=substr($field,0,strlen($field)-1);
				$values=substr($values,0,strlen($values)-1);
				$sql="INSERT INTO billing.VOUCHER_CLIENTS($field) VALUES($values)";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$smarty->assign("new","1");
			}
			if($directsubmit || $designsubmit || $sersubmit || $destechsubmit)
			{
				$sql="UPDATE billing.VOUCHER_CLIENTS SET ";
				foreach($insert as $key=>$value)
				{
					$sql.=$key."='".$value."',";
				}
				$sql=substr($sql,0,strlen($sql)-1);
				$sql.=" WHERE CLIENTID='$deal'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($designsubmit)
				{
					$msg="A  deal has been edited by sales team and requires designing details. The client details are as follows :";
					$msg.="<br> Client Name : $insert[CLIENT_NAME]";
	                                $msg.="<br> Client Details : $insert[CDETAILS]";
        	                        $msg.="<br> Headline : $insert[HEADLINE]";
                	                $msg.="<br> Voucher Summary : $insert[CDETAILS]";
                        	        $msg.="<br> Voucher Details : $insert[VDETAILS]";
                                	$msg.="<br> Comments : $insert[COMMENTS]";
	                                $msg.="<br> Start Date : $insert[START_DATE]";
					$subject="Deal Received for design details : ".$insert["CLIENT_NAME"];
					send_mail("ashish.anand@jeevansathi.com,shweta.bahl@naukri.com",'lotika.sharma@naukri.com','',$msg,$subject,"promotions@jeevansathi.com");
				}
			}
			$smarty->assign("donedeal",$cname);
			$smarty->assign("DONE","1");
			 	
		}
		$smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->display("voucher_backend_sales.htm");
                die;
	}
	if($Editdeal || $new)
	{
		if($Editdeal)
		{
			$sql="SELECT CLIENT_NAME,TYPE,AVAILABLE_IN,GENDER,SLABS,CONTACTS_DISPLAY,SERVICE,USERNAME,PASSWORD,HEADLINE,VSUMMARY,VDETAILS,CDETAILS,NUM,START_DATE,DURATION,COMMENTS,STATUS,IMAGE_FILE,LOGO_FILE,VLOGO,VOUCHER,HYPERLINK FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_assoc($result);
			$smarty->assign("cname",$row["CLIENT_NAME"]);
			$smarty->assign("type",$row["TYPE"]);
			$smarty->assign("gender",$row["GENDER"]);
			$smarty->assign("username",$row["USERNAME"]);
			$smarty->assign("password",$row["PASSWORD"]);
			$smarty->assign("comments",$row["COMMENTS"]);
			$smarty->assign("duration",$row["DURATION"]);
			$smarty->assign("headline",html_entity_decode($row["HEADLINE"],ENT_QUOTES));
			$smarty->assign("summary",html_entity_decode($row["VSUMMARY"],ENT_QUOTES));
			$smarty->assign("vdetails",html_entity_decode($row["VDETAILS"],ENT_QUOTES));
			$smarty->assign("cdetails",html_entity_decode($row["CDETAILS"],ENT_QUOTES));
			$smarty->assign("hyperlink",$row["HYPERLINK"]);	
			$smarty->assign("service",$row["SERVICE"]);
			if($row["TYPE"]=="E")
			{
				$sqlnum="SELECT COUNT(*) AS CNT FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$deal' AND ISSUED=''";
				$resnum=mysql_query_decide($sqlnum) or die("$sqlnum".mysql_error_js());
				$rownum=mysql_fetch_assoc($resnum);
				if($rownum["CNT"])
				$smarty->assign("leftnum",$rownum["CNT"]);
				else
				$smarty->assign("leftnum","None");
			}
			if($row["NUM"])
			$smarty->assign("number",$row["NUM"]);
			else
			$smarty->assign("num","Not Specified");
			if($row["IMAGE_FILE"])
			{
				$client=$row["CLIENTID"];
				$filename=$row["IMAGE_FILE"];
				$link="<a href=\"voucher_backend_download.php?clientid=$deal&filename=$filename&design=1\">Download</a>";
				$smarty->assign("imagelink",$link);
			}
			list($year,$month,$day)=explode("-",$row["START_DATE"]);
			$smarty->assign("year",$year);
			$smarty->assign("month",$month);
			$smarty->assign("day",$day);
			if($row["AVAILABLE_IN"])
			{
				if(strpos($row["AVAILABLE_IN"],":")===false)
				$place[0]=$row["AVAILABLE_IN"];
				else
				$place=explode(":",$row["AVAILABLE_IN"]);
				if($place[0])
				$city=explode(",",$place[0]);
				else
				$allcity=1;
				if($place[1])
				$state=explode(",",$place[1]);
				else
				$allstate=1;
			}
			else
			{
				$allstate=1;
				$allcity=1;
			}
			if($row["VLOGO"])
			{
				$smarty->assign("design","1");
				if($row["TYPE"]=="E" && !$row["VOUCHER"])
				$smarty->assign("oldvoucher","1");
			}
			if($row["SLABS"])
			{
				$slab=explode(",",$row["SLABS"]);
				$slabs="<option value=All>All</option>";
				if(in_array("2",$slab))
				$slabs.="<option value=2 selected>2 months</option>";				
				else
				$slabs.="<option value=2>2 months</option>";
				if(in_array("3",$slab))                 
                                $slabs.="<option value=3 selected>3 months</option>";                   
                                else
                                $slabs.="<option value=3>3 months</option>";
				if(in_array("4",$slab)) 
                                $slabs.="<option value=4 selected>4 months</option>"; 
                                else
                                $slabs.="<option value=4>4 months</option>";
				if(in_array("5",$slab)) 
                                $slabs.="<option value=5 selected>5 months</option>"; 
                                else
                                $slabs.="<option value=5>5 months</option>";
				if(in_array("6",$slab)) 
                                $slabs.="<option value=6 selected>6 months</option>"; 
                                else
                                $slabs.="<option value=6>6 months</option>";
				if(in_array("12",$slab)) 
                                $slabs.="<option value=12 selected>12 months</option>"; 
                                else
                                $slabs.="<option value=12>12 months</option>";
			}
			if($row["CONTACTS_DISPLAY"])
			{
				$contacts_display=explode(",",$row["CONTACTS_DISPLAY"]);
				$contact_display="<option value=N>None</option>";
				if(in_array("A",$contacts_display))
				$contact_display.="<option value=A selected>Address</option>";
				else
				$contact_display.="<option value=A>Address</option>";
				if(in_array("E",$contacts_display))
				$contact_display.="<option value=E selected>Email ID</option>";
				else
				$contact_display.="<option value=E>Email ID</option>";
				if(in_array("P",$contacts_display))
				$contact_display.="<option value=P selected>Phone Number</option>";
				else
				$contact_display.="<option value=P>Phone Number</option>";
				if(in_array("C",$contacts_display))
				$contact_display.="<option value=C selected>City</option>";
				else
				$contact_display.="<option value=C>City</option>";
				if(in_array("Y",$contacts_display))
				$contact_display.="<option value=Y selected>All</option>";
				else
				$contact_display.="<option value=Y>All</option>";
			}
		}
		$sql="SELECT SQL_CACHE VALUE, LABEL,TYPE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($new || $allcity)
		$city_india="<option value=All selected>All/None</option>";
		else
		$city_india="<option value=All>All/None</option>";
		if($new || $allstate)
		$state_india="<option value=All selected>All/None</option>";
		else
		$state_india="<option value=All>All/None</option>";
		while($row=mysql_fetch_assoc($result))
		{
			$value=$row["VALUE"];
			$label=$row["LABEL"];
			if($Editdeal)
			{
				if($row["TYPE"]=="CITY")
				{
					if($city)
					{
						if(in_array($value,$city))	
						{	
							$city_india.="<option value=$value selected>$label</option>";
						}
						else
						$city_india.="<option value=$value>$label</option>";
					}
					else	
					$city_india.="<option value=$value>$label</option>";
				}
				else
				{
					if($state)
					{
						if(in_array($value,$state))
						$state_india.="<option value=$value selected>$label</option>";
						else
						$state_india.="<option value=$value>$label</option>";
					}
					else
					$state_india.="<option value=$value>$label</option>";

				}	
			}
			else
			{
				if($row["TYPE"]=="CITY")
				$city_india.="<option value=$value>$label</option>";
				else
				$state_india.="<option value=$value>$label</option>";
			}
		}
		if($state)
		{
			if(in_array("NRI",$state))
			$state_india.="<option value=NRI selected>International</option>";
		}
		else
		$state_india.="<option value=NRI>International</option>";
		$smarty->assign("city_india",$city_india);
		$smarty->assign("state_india",$state_india);
		if(!$slabs)
		$slabs="<option value=All selected>All</option><option value=2>2 months</option><option value=3>3 months</option><option value=4>4 months</option><option value=5>5 months</option><option value=6>6 months</option><option value=12>12 months</option>";
		$smarty->assign("slabs",$slabs);
		if(!$contact_display)
		$contact_display="<option value=N selected>None</option><option value=A>Address</option><option value=E>Email Address</option><option value=P>Phone Number</option><option value=C>City</option><option value=Y>All</option>";
		$smarty->assign("contact_display",$contact_display);
		if($Editdeal)
		{
			$smarty->assign("deal",$deal);
			$smarty->assign("editdeal","1");
		}
		if($new)
		$smarty->assign("new",$new);
	}
	if($edit || $stop || $tech)
	{
		$sql="SELECT CLIENTID,CLIENT_NAME FROM billing.VOUCHER_CLIENTS";
		if($stop)
		$sql.=" WHERE SERVICE!='N'";
		if($tech)
		$sql.=" WHERE SERVICE!='N' AND TYPE='E'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result))
		{
			while($row=mysql_fetch_assoc($result))
			{
				$value=$row["CLIENTID"];
				$label=$row["CLIENT_NAME"];
				$options.="<option value=\"$value\">$label</option>";
			}
		}
		else
		$smarty->assign("nodeals","1");
		$smarty->assign("options",$options);
		if($edit)
		$smarty->assign("edit","1");
		if($stop)
		$smarty->assign("stop","1");
		if($tech)
		$smarty->assign("tech","1");
	}
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->display("voucher_backend_sales.htm");
}
else
{
	$msg="Your session has been timed out  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
