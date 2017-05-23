<?php
include("connect.inc");
    
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		$error=0;
		$srcid=trim($srcid);	
		if($srcid=='')
		{
			$error++;
			$smarty->assign("ER_SRCID","Y");
		}
                else
		{       
			if(($srcid[0]=='a' || $srcid[0]=='A') && ($srcid[1]=='f' || $srcid[1]=='F'))
			{
                	        $error++;
	                        $smarty->assign("ER_SRCID","Y");
			}
			//Modified on 29th march 2006 By lavesh. source Id should not begin with mb			
			elseif(($srcid[0]=='m' || $srcid[0]=='M') && ($srcid[1]=='b' || $srcid[1]=='B'))
			{
				$error++;
                                $smarty->assign("ER_SRCID","Y");
			}
			// Modification end here
                }
		
		if(trim($srcname)=='')
		{
			$error++;
			$smarty->assign("ER_SRCNAME","Y");
		}
		if(trim($srcgp)=='' && trim($srcgpo)=='')
		{
			$error++;
			$smarty->assign("ER_SRCGP","Y");
		}
                
		if($error)
		{
			$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
        	        $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                	while($row=mysql_fetch_array($res))
                	{
                        	$srcarr[]=$row['GROUPNAME'];
                	}
                	$smarty->assign("srcarr",$srcarr);
			$smarty->assign("srcid",$srcid);
			$smarty->assign("srcname",$srcname);
			$smarty->assign("srcgp",$srcgp);
			$smarty->assign("srcgpo",$srcgpo);
			$smarty->assign("active",$active);
			$smarty->assign("force_email",$force_email);
			$smarty->assign("cpc",$cpc);
			$smarty->assign("val",$val);
                        $smarty->assign("aurl",$aurl);
                        $smarty->assign("iurl",$iurl);
                        $smarty->assign("noreg",$noreg);
			$smarty->display("manage_banner_sources.htm");
		}
		else
		{
			if($val=='edit')
			{
				if(!$srcgp)
					$srcgp=$srcgpo;
				if(!$active)
					$active=='N';
				//$sql="INSERT INTO MIS.SOURCE (SourceID,SourceName,GROUPNAME,CPC,ACTIVE) VALUES ('".addslashes($srcid)."','".addslashes($srcname)."','".addslashes($groupname)."','$cpc','$active')";
				$sql="UPDATE MIS.SOURCE SET SourceID='".addslashes($srcid)."',SourceName='".addslashes($srcname)."',GROUPNAME='".addslashes($srcgp)."',CPC='$cpc',ACTIVE='$active',FORCE_EMAIL='$force_email', NOREG='$noreg',AURL='".addslashes($aurl)."',IURL='".addslashes($iurl)."' WHERE ID='$id'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$msg="Record Updated.<br>  ";
				$msg .="<a href=\"manage_source_property.php?cid=$cid&group=$srcgp&source=$srcid\">";
                                $msg .="Click here to edit property/upload banner for ".$srcname." source </a><br><br>";

				$msg .="<a href=\"manage_banner_sources.php?cid=$cid\">";
				$msg .="Continue </a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("jsadmin_msg.tpl");
			}
			//elseif($val=='add')
			else
			{
				$sql="SELECT COUNT(*) as cnt FROM MIS.SOURCE WHERE SourceID='$srcid'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_array($res);
				$cnt=$row['cnt'];
				if($cnt>0)
				{
					$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					while($row=mysql_fetch_array($res))
					{
						$srcarr[]=$row['GROUPNAME'];
					}
					$smarty->assign("srcarr",$srcarr);

					$smarty->assign("ID_EXIST","Y");
					$smarty->assign("srcid",$srcid);
					$smarty->assign("srcname",$srcname);
					$smarty->assign("srcgp",$srcgp);
					$smarty->assign("srcgpo",$srcgpo);
					$smarty->assign("active",$active);
					$smarty->assign("cpc",$cpc);
					$smarty->assign("val",$val);
					$smarty->assign("force_email",$force_email);
                                        $smarty->assign("aurl",$aurl);
                                        $smarty->assign("iurl",$iurl);
                                        $smarty->assign("noreg",$noreg);
					$smarty->display("manage_banner_sources.htm");
				}
				else
				{
					if(!$srcgp)
						$srcgp=$srcgpo;
					if(!$active)
						$active=='N';
                                        //FORCE_EMAIL='$force_email', NOREG='$noreg',AURL='".addslashes($aurl).",IURL='".addslashes($iurl)."' 
                                        $aurl=addslashes($aurl);
                                        $iurl=addslashes($iurl);
					$sql="INSERT INTO MIS.SOURCE (SourceID,SourceName,GROUPNAME,CPC,ACTIVE,FORCE_EMAIL,NOREG,AURL,IURL) VALUES ('".addslashes($srcid)."','".addslashes($srcname)."','".addslashes($srcgp)."','$cpc','$active','$force_email','$noreg','$aurl','$iurl')";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());

					$msg="Record Inserted.<br><br>  ";
					$msg .="<a href=\"manage_source_property.php?cid=$cid&group=$srcgp&source=$srcid\">";
					$msg .="Click here to attach a property/upload banner for ".$srcname." source </a><br><br>";
					$msg .="<a href=\"manage_banner_sources.php?cid=$cid\">";
					$msg .="Continue to add another source</a>";
					$smarty->assign("MSG",$msg);
					$smarty->display("jsadmin_msg.tpl");
				}
			}
		}
	}
	elseif($CMDGetSrc)
	{
		if(!$val)
			$val="add";
                
		if($srcid)
			$smarty->assign("flag",1);
		$sql="SELECT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$srcgp'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcarr[]=$row['SourceID'];
		}
		$smarty->assign("srcidarr",$srcarr);
		$smarty->assign("srcgp",$srcgp);
		unset($srcarr);

		$sql="SELECT * FROM MIS.SOURCE WHERE SourceID='$srcid'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$smarty->assign("id",$row['ID']);
			$smarty->assign("srcid",$row['SourceID']);
			$smarty->assign("srcname",$row['SourceName']);
			$smarty->assign("srcgp",$row['GROUPNAME']);
			$smarty->assign("cpc",$row['CPC']);
			$smarty->assign("active",$row['ACTIVE']);
			$smarty->assign("force_email",$row['FORCE_EMAIL']);
			$smarty->assign("noreg",$row['NOREG']);
                        $smarty->assign("aurl",$row[AURL]);
                        $smarty->assign("iurl",$row[IURL]);
                        
		}
		$smarty->assign("val",$val);
		$smarty->display("manage_banner_sources.htm");
	}
	else
	{
		if(!$val)
			$val="add";
		$smarty->assign("flag",0);

		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcarr[]=$row['GROUPNAME'];
		}

		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("val",$val);
		$smarty->display("manage_banner_sources.htm");
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
?>
