<?php
/*****************************************************************************************************************
Filename    : mmmjs_module.php
Description : Module to update branch/venue details for mass mailer management system [2530]
Coded By    : Sadaf Alam
Coded On    : 5 December 2007 
******************************************************************************************************************/

include("connect.inc");

$db=connect_db();

if(authenticated($cid))
{
	if($assign)
	{
		if($assignsubmit)
		{
			if($city)
			{
				foreach($city as $value)
				{
					$sql="SELECT COUNT(*) AS CNT FROM jsadmin.MMM_NEARBRANCH WHERE CITY_VALUE='$value'";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row=mysql_fetch_assoc($res);
					if($row["CNT"])
					$sql="UPDATE jsadmin.MMM_NEARBRANCH SET NEAR_BRANCH='$branch' WHERE CITY_VALUE='$value'";
					else
					$sql="INSERT INTO jsadmin.MMM_NEARBRANCH(CITY_VALUE,NEAR_BRANCH) VALUES('$value','$branch')";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
			}
			$smarty->assign("assigned",1);
		}
		
		
			$sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if(mysql_num_rows($res))
			{
				while($row=mysql_fetch_assoc($res))
				{
					$sqllab="SELECT NEAR_BRANCH FROM jsadmin.MMM_NEARBRANCH WHERE CITY_VALUE='$row[VALUE]'";
					$reslab=mysql_query_decide($sqllab) or die("$sqllab".mysql_error_js());
					if(mysql_num_rows($reslab))
					{
						$rowlab=mysql_fetch_assoc($reslab);
						$sqllab="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$rowlab[NEAR_BRANCH]'";
						$reslab=mysql_query_decide($sqllab) or die("$sqllab".mysql_error_js());
						$rowlab=mysql_fetch_assoc($reslab);
						$cities.="<option value=\"".$row["VALUE"]."\">$row[LABEL] ($rowlab[LABEL])</option>";
					}
					else
					$cities.="<option value=\"".$row["VALUE"]."\">$row[LABEL]</option>";
				}
				$smarty->assign("cities",$cities);
				$sql="SELECT BRANCH_VALUE FROM jsadmin.MMM_BRANCH";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_num_rows($res))
				{
					while($row=mysql_fetch_assoc($res))
					{
						$sqllab="SELECT NAME FROM jsadmin.MMM_BRANCH_LIST WHERE VALUE='$row[BRANCH_VALUE]'";
						$reslab=mysql_query_decide($sqllab) or die("$sqllab".mysql_error_js());
						$rowlab=mysql_fetch_assoc($reslab);
						$branches.="<option value=\"".$row["BRANCH_VALUE"]."\">$rowlab[NAME]</option>";
					}
				$smarty->assign("branches",$branches);
				}
			
			}
		
		$smarty->assign("assign",1);
	}
	elseif($edit)
	{
		if($morevenueignore)
		{
			$smarty->assign("editdone",1);
		}
		elseif($morevenuesubmit)
		{
			foreach($_POST as $key=>$value)
                        {
                                if(strstr($key,"mvnadd") && trim($value))
                                $vnum++;
                        }
			for($i=1;$i<=$vnum;$i++)
                        {
                                $nadd="mvnadd".$i;
                                $nid="mvnid".$i;
                                $numbers="mvnumbers".$i;
                                if(trim($_POST[$nadd]) && trim($_POST[$nid]) && trim($_POST[$numbers]))
                                {
                                        $nadd=addslashes(stripslashes(trim($_POST[$nadd])));
                                        $nid=addslashes(stripslashes(trim($_POST[$nid])));
                                        $numbers=addslashes(stripslashes(trim($_POST[$numbers])));
                                        $sql="INSERT INTO MMM_VENUE(BRANCH,VENUE_NAME,VENUE_CPERSON,VENUE_NUMBER) VALUES('$city','$nadd','$nid','$numbers')";
                                        mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                }
                        }       
			$smarty->assign("editdone",1);          
			$smarty->assign("branchlabel",$branchlabel);
		}
		elseif($editbranch)
		{
			$fromid=addslashes(stripslashes(trim($fromid2)));
			$signature=addslashes(stripslashes(trim($signature2)));
			$subject=addslashes(stripslashes(trim($subject2)));
			$sql="UPDATE jsadmin.MMM_BRANCH SET FROM_EMAIL='$fromid',SIGNATURE='$signature',SUBJECT='$subject'";
			if($disablebranch)
			$sql.=",DISABLED='Y'";
			if($enablebranch)
			$sql.=",DISABLED=''";	
			$sql.="  WHERE BRANCH_VALUE='$city'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());	
			foreach($_POST as $key=>$value)
			{
				if(strstr($key,"arrayid"))
				{
					$id=explode("check",$value);
					$venueid=$id[1];
					$del="check".$venueid;
					if($_POST[$del])
					{
						$sql="DELETE FROM jsadmin.MMM_VENUE WHERE VENUEID='$venueid'";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
					}
					else
					{
						$edit=explode("arrayid",$key);
						$editid=$edit[1];
						$nadd="nadd2".$editid;
						$nid="nid2".$editid;
						$numbers="numbers2".$editid;
						$nadd=addslashes(stripslashes(trim($_POST[$nadd])));
						$nid=addslashes(stripslashes(trim($_POST[$nid])));
						$numbers=addslashes(stripslashes(trim($_POST[$numbers])));
						$sql="UPDATE jsadmin.MMM_VENUE SET VENUE_NAME='$nadd',VENUE_CPERSON='$nid',VENUE_NUMBER='$numbers' WHERE VENUEID='$venueid'";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
					}
				}
			}
			if($morevenues)
			{
				$smarty->assign("morevenues",trim($morevenues));
				$smarty->assign("city",$city);
				for($i=1;$i<=trim($morevenues);$i++)
				$mvidarr[]=array("id"=>$i);
				$smarty->assign("mvidarr",$mvidarr);
			}
			else
			{
				$smarty->assign("editdone",1);
			}
			$smarty->assign("branchlabel",$branchlabel);
		}
		elseif($editsubmit)
		{
			$sql="SELECT * FROM jsadmin.MMM_BRANCH WHERE BRANCH_VALUE='$city'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			$smarty->assign("subject",$row["SUBJECT"]);
			$smarty->assign("signature",$row["SIGNATURE"]);
			$smarty->assign("fromid",$row["FROM_EMAIL"]);
			if($row["DISABLED"])
			$smarty->assign("disablebranch",1);
			$sql="SELECT * FROM jsadmin.MMM_VENUE WHERE BRANCH='$city'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$smarty->assign("venuenum",mysql_num_rows($res));
			$i=1;
			while($row=mysql_fetch_assoc($res))
			{
				$venue_name=$row["VENUE_NAME"];
				$venue_cperson=$row["VENUE_CPERSON"];
				$venue_number=$row["VENUE_NUMBER"];
				$venue[]=array( "id"=>$i,
						"nadd"=>$venue_name,
						"nid"=>$venue_cperson,
						"numbers"=>$venue_number,
						"checkid"=>"check".$row["VENUEID"]);
				$vidarr.=$row["VENUEID"]."#";
				$i++;
			}
			$vidarr=substr($vidarr,0,strlen($vidarr)-1);
			$smarty->assign("vidarr",$vidarr);
			$sql="SELECT NAME FROM jsadmin.MMM_BRANCH_LIST WHERE VALUE='$city'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			$smarty->assign("branchlabel",$row["NAME"]);
			$smarty->assign("venue",$venue);
			$smarty->assign("city",$city);
			$smarty->assign("editform",1);
		}
		$sql="SELECT BRANCH_VALUE FROM jsadmin.MMM_BRANCH";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$sqlcity="SELECT NAME FROM jsadmin.MMM_BRANCH_LIST WHERE VALUE='$row[BRANCH_VALUE]'";
			$rescity=mysql_query_decide($sqlcity) or die("$sqlcity".mysql_error_js());
			$rowcity=mysql_fetch_assoc($rescity);
			$cityindia.="<option value=\"".$row["BRANCH_VALUE"]."\">".$rowcity["NAME"]."</option>";
		}
		$smarty->assign("edit",1);
		$smarty->assign("cityindia",$cityindia);
	}
	elseif($new)
	{
		
		if($venuesubmit)
		{	
			$sql="SELECT BRANCH_VALUE FROM jsadmin.MMM_BRANCH WHERE BRANCH_VALUE='$branchname'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if(mysql_num_rows($res)==0)
			{
				foreach($_POST as $key=>$value)
				{
					if(strstr($key,"nadd") && trim($value))
					$vnum++;
				}	
				$signature=addslashes(stripslashes($signature));
				$fromid=addslashes(stripslashes($fromid));
				$subject=addslashes(stripslashes($subject));
				$sql="INSERT INTO jsadmin.MMM_BRANCH(BRANCH_VALUE,FROM_EMAIL,SUBJECT,SIGNATURE) VALUES('$branchname','$fromid','$subject','$signature')";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="UPDATE jsadmin.MMM_NEARBRANCH SET NEAR_BRANCH='$branchname' WHERE CITY_VALUE='$branchname'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if(mysql_affected_rows_js()==0)
				{
					$sql="INSERT INTO jsadmin.MMM_NEARBRANCH(CITY_VALUE,NEAR_BRANCH) VALUES('$branchname','$branchname')";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
				for($i=1;$i<=$vnum;$i++)
				{
					$nadd="nadd".$i;
					$nid="nid".$i;
					$numbers="numbers".$i;
					if(trim($_POST[$nadd]) && trim($_POST[$nid]) && trim($_POST[$numbers]))
					{
						$nadd=addslashes(stripslashes(trim($_POST[$nadd])));
						$nid=addslashes(stripslashes(trim($_POST[$nid])));
						$numbers=addslashes(stripslashes(trim($_POST[$numbers])));
						$sql="INSERT INTO MMM_VENUE(BRANCH,VENUE_NAME,VENUE_CPERSON,VENUE_NUMBER) VALUES('$branchname','$nadd','$nid','$numbers')";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
					}
				}
			}			
			$smarty->assign("branchlabel",$branchlabel);
			$smarty->assign("newdone",1);
		}
		elseif($submit)
		{
			if(trim($fromid) && trim($subject) && trim($signature) && trim($venuenum))
			{
			 	$sql="SELECT NAME FROM jsadmin.MMM_BRANCH_LIST WHERE VALUE='$branchname'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($res);
				for($i=1;$i<=$venuenum;$i++)
				$idarr[]=array("id"=>$i);
				$smarty->assign("idarr",$idarr);
				$smarty->assign("branchlabel",$row["NAME"]);
				$smarty->assign("venueform",1);
			}
			else
			{
				if(!trim($fromid))
				$smarty->assign("fromid_err",1);
				if(!trim($subject))
                                $smarty->assign("sub_err",1);
				if(!trim($signature))
                                $smarty->assign("sig_err",1);
				if(!trim($venuenum))
                                $smarty->assign("vnum_err",1);
				$smarty->assign("error",1);
			}
			$smarty->assign("fromid",trim($fromid));
			$smarty->assign("subject",trim($subject));
			$smarty->assign("signature",trim($signature));
			$smarty->assign("venuenum",trim($venuenum));
			$smarty->assign("branchname",$branchname);
		}
		$sql="SELECT BRANCH_VALUE FROM jsadmin.MMM_BRANCH";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$alreadybranch.=$row["BRANCH_VALUE"]."','";
		}
		$sql="SELECT VALUE,NAME FROM jsadmin.MMM_BRANCH_LIST";
		if($alreadybranch)
		{
			$alreadybranch=substr($alreadybranch,0,strlen($alreadybranch)-3);
			$sql.=" WHERE VALUE NOT IN('$alreadybranch')";	
		}
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			if($branchname && ($branchname==$row["VALUE"]))
			$citylist.="<option value=\"".$row["VALUE"]."\" selected>".$row["NAME"]."</option>";
			else
			$citylist.="<option value=\"".$row["VALUE"]."\">".$row["NAME"]."</option>";
		}
		$smarty->assign("citylist",$citylist);
		$smarty->assign("new",1);
	}	
	$smarty->assign("cid",$cid);
	$smarty->display("mmm_module.htm");	
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
