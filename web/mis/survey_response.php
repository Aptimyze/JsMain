<?php
/*****************************************************************************************************
Filename    : survey_response.php
Description : Display responses of different users [2326]
Created On  : 9 October 2007
Created By  : Sadaf Alam
******************************************************************************************************/
include("connect.inc");
include("../crm/display_result.inc");

ini_set('memory_limit',-1);

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($search)
	{
		$smarty->assign("search","1");
	}
	$PAGELEN=25;
        $LINKNO=5;
        if (!$j )
        $j = 0;
	$i=$j+1;
	$sql="SELECT DISTINCT(VALUE) AS VALUE,LABEL FROM newjs.RELIGION";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($res))
	{
		$value=$row["VALUE"];
		$label=$row["LABEL"];
		$rlist[$value]=$label;
	}
	$sql="SELECT DISTINCT(VALUE) AS VALUE,LABEL FROM newjs.MTONGUE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($res))
	{
		$value=$row["VALUE"];
		$label=$row["LABEL"];
		$mlist[$value]=$label;
	}
	$sql="SELECT DISTINCT(VALUE) AS VALUE,LABEL FROM newjs.CASTE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($res))
	{
		$value=$row["VALUE"];
		$label=$row["LABEL"];
		$clist[$value]=$label;
	}
	if($rel || $comm)
        {
                $i=1;
		$sql="SELECT PROFILEID FROM newjs.SEARCH_MALE WHERE";
                if($comm && $rel)
                $sql.=" RELIGION='$rel' AND MTONGUE='$comm'";
                elseif($comm)
                $sql.=" MTONGUE='$comm'";
                else
                $sql.=" RELIGION='$rel'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($res))
                {
			$profileidarr[]=$row["PROFILEID"];
		}
		if($profileidarr)
		{
		$profileidstr=implode("','",$profileidarr);
		$sql="SELECT PROFILEID,QUES1,QUES2,QUES3,QUES4 FROM MIS.SURVEY WHERE PROFILEID IN('$profileidstr') AND (QUES1!='' OR QUES2!='' OR QUES3!='' OR QUES4!='')";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if(mysql_num_rows($res))
		{
			while($row=mysql_fetch_assoc($res))
			{
				$sqldata="SELECT RELIGION,MTONGUE,CASTE,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
				$resdata=mysql_query_decide($sqldata,$db) or die("$sqldata".mysql_error_js($db));
				$rowdata=mysql_fetch_assoc($resdata);
				$index=$rowdata["CASTE"];
				$caste=$clist[$index];
				$index=$rowdata["RELIGION"];
				$religion=$rlist[$index];
				$index=$rowdata["MTONGUE"];
				$mtongue=$mlist[$index];
				$username=$rowdata["USERNAME"];
				$table[]=array("sno"=>$i,"username"=>$username,"ques1"=>$row["QUES1"],
					       "ques2"=>$row["QUES2"],"ques3"=>$row["QUES3"],
					       "ques4"=>$row["QUES4"],"religion"=>$religion,"caste"=>$caste,
					       "mtongue"=>$mtongue);
				$i++;
			}
		}
		}
                $sql="SELECT PROFILEID FROM newjs.SEARCH_FEMALE WHERE";
                if($comm && $rel)
                $sql.=" RELIGION='$rel' AND MTONGUE='$comm'";
                elseif($comm)
                $sql.=" MTONGUE='$comm'";
                else
                $sql.=" RELIGION='$rel'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                while($row=mysql_fetch_assoc($res))
                {
			$profileidarr[]=$row["PROFILEID"];
		}
		if($profileidarr)
		{
		$profileidstr=implode("','",$profileidarr);		
		$sql="SELECT PROFILEID,QUES1,QUES2,QUES3,QUES4 FROM MIS.SURVEY WHERE PROFILEID IN('$profileidstr') AND (QUES1!='' OR QUES2!='' OR QUES3!='' OR QUES4!='')";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if(mysql_num_rows($res))
		{
		
                        while($row=mysql_fetch_assoc($res))
			{ 
				$sqldata="SELECT RELIGION,MTONGUE,CASTE,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
				$resdata=mysql_query_decide($sqldata,$db) or die("$sqldata".mysql_error_js($db));
				$rowdata=mysql_fetch_assoc($resdata);
				$index=$rowdata["CASTE"];
				$caste=$clist[$index];
				$index=$rowdata["RELIGION"];
				$religion=$rlist[$index];
				$index=$rowdata["MTONGUE"];
				$mtongue=$mlist[$index];
				$username=$rowdata["USERNAME"];
				$table[]=array("sno"=>$i,"username"=>$username,"ques1"=>$row["QUES1"],
                                                        "ques2"=>$row["QUES2"],"ques3"=>$row["QUES3"],
							"ques4"=>$row["QUES4"],"religion"=>$religion,									     "caste"=>$caste,"mtongue"=>$mtongue);
                                $i++;
			}
		}
		}
		$smarty->assign("table",$table);
		if($rel)
		{
			$smarty->assign("rel",$rlist[$rel]);
		}
		if($comm)
		{
			$smarty->assign("mtongue",$mlist[$comm]);
		}
		$smarty->assign("CHECKSUM",$cid);
		$smarty->assign("responses",$i-1);
		$smarty->display("survey_response.htm");
		die;
	}
	if($search)
	{
		if($submit)
		{	
			$sqldata="SELECT RELIGION,MTONGUE,CASTE,PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$username'";
			$resdata=mysql_query_decide($sqldata,$db) or die("$sqldata".mysql_error_js($db));
			if(mysql_num_rows($resdata))
			{
				$rowdata=mysql_fetch_assoc($resdata);
				$sqldet="SELECT COUNT(*) AS COUNT FROM MIS.SURVEY WHERE PROFILEID='$rowdata[PROFILEID]'";
				$resdet=mysql_query_decide($sqldet,$db) or die("$sqldet".mysql_error_js($db));
				$rowdet=mysql_fetch_assoc($resdet);
				if($rowdet["COUNT"])
				{
					$index=$rowdata["RELIGION"];
					$religion=$rlist[$index];
					$index=$rowdata["MTONGUE"];
					$mtongue=$mlist[$index];
					$index=$rowdata["CASTE"];
					$caste=$clist[$index];
					$sql="SELECT QUES1,QUES2,QUES3,QUES4 FROM MIS.SURVEY WHERE PROFILEID='$rowdata[PROFILEID]'";
					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					while($row=mysql_fetch_assoc($res))
					{
						$table[]=array("sno"=>$i,"username"=>$username,"ques1"=>$row["QUES1"],
							"ques2"=>$row["QUES2"],"ques3"=>$row["QUES3"],"ques4"=>$row["QUES4"],
							"religion"=>$religion,"caste"=>$caste,"mtongue"=>$mtongue);
					}
				}
				else
				$smarty->assign("nores","1");
				
			}
			else
			$smarty->assign("nouser","1");
		}
	}
	else
	{
		$sql="SELECT * FROM MIS.SURVEY WHERE QUES1!='' OR QUES2!='' OR QUES3!='' OR QUES4!='' LIMIT $j,$PAGELEN";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($res))
		{
			$sqldata="SELECT RELIGION,MTONGUE,CASTE,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
			$resdata=mysql_query_decide($sqldata,$db) or die("$sqldata".mysql_error_js($db));
			if(mysql_num_rows($resdata))
			{
				$rowdata=mysql_fetch_assoc($resdata);
				$index=$rowdata["RELIGION"];
				$religion=$rlist[$index];
				$index=$rowdata["MTONGUE"];
				$mtongue=$mlist[$index];
				$index=$rowdata["CASTE"];
				$caste=$clist[$index];
				$table[]=array("sno"=>$i,"username"=>$rowdata["USERNAME"],"ques1"=>$row["QUES1"],
						"ques2"=>$row["QUES2"],"ques3"=>$row["QUES3"],"ques4"=>$row["QUES4"],
						"religion"=>$religion,"caste"=>$caste,"mtongue"=>$mtongue);
				$i++;
			}
		}
		if( $j )
		$cPage = ($j/$PAGELEN) + 1;
		else
		$cPage = 1;
		$sql="SELECT COUNT(*) as CNT FROM MIS.SURVEY WHERE QUES1!='' OR QUES2!='' OR QUES3!='' OR QUES4!=''";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_assoc($res);
		$TOTALREC=$row["CNT"];
		$smarty->assign("responses",$TOTALREC);	
		pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"survey_response.php",'');
	}
	if($table)
	$smarty->assign("table",$table);
	$smarty->assign("CHECKSUM",$cid);
	$smarty->display("survey_response.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
