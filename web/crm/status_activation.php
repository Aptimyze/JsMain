<?php

include("connect.inc");
include ("display_result.inc");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
include_once(JsConstants::$docRoot."/classes/JProileUpdateLib.php");

$PAGELEN=10;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;
                                                                                                 
$sno=$j+1;

if(authenticated($cid))
{
	$serviceObj = new Services;
	$name= getname($cid);
	$centre_label=get_centre($cid);
	if($centre_label!="HO")
	{	
		$sql="SELECT VALUE from BRANCH_CITY where UPPER(LABEL) ='".strtoupper($centre_label)."'";
		$myrow=mysql_fetch_array(mysql_query_decide($sql));
		$centre=$myrow['VALUE'];
	}
	else
		$centre="HO";
	
//	if($centre=="HO" || $name =="mahesh")
//                $smarty->assign("showlink","Y");

        if($privilage = getprivilage($cid))
        {
                $priv_arr = explode("+",$privilage);
                if(is_array($priv_arr))
                {
                        if(in_array('PSA',$priv_arr))
                                $smarty->assign("showlink","Y");
                }
        }

	
	if($submit)
	{
		$cnt=0;
                foreach( $_POST as $key => $value )
                {
                        if( substr($key, 0, 2) == "rb" )
                        {
                                $cnt=$cnt+1;
//                              $pid = ltrim($key, "rb");
//                              $proid[] = $pid;
//				$status_subscript="rb".$pid;
//				$status[]= $_POST[$status_subscript];

                                list($pid ,$id)= explode("|X|",ltrim($key, "rb"));
                                $proid[] = $pid;
                                $id_arr[] = $id;
				$status_subscript="rb".$pid."|X|".$id;
				$status[]= $_POST[$status_subscript];
			}
                }
                for($i=0;$i<count($proid);$i++)
                {
			$chbname="rb".$proid[$i];
			if($status[$i] != '')
			{
				$sql3 = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,REF_ID,DISCOUNT) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID, '$id_arr[$i]',DISCOUNT FROM incentive.PAYMENT_COLLECT where ID='$id_arr[$i]'";
        		        mysql_query_decide($sql3) or die("$sql3".mysql_error_js());
				
				$sql="UPDATE incentive.PAYMENT_COLLECT set STATUS='$status[$i]', ENTRYBY='$name', ENTRY_DT=now() where ID='$id_arr[$i]'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js()); 

				/*Added by Alok on 19th Feb 2005 start*/
				if($status[$i] == 'S')
				{
					$sql = "select SERVICE, ADDON_SERVICEID from incentive.PAYMENT_COLLECT where ID = '$id_arr[$i]'";
					$res = mysql_query_decide($sql) or die("Failed to get profile");
					$myrow = mysql_fetch_array($res);
					$sid=$myrow['SERVICE'];
					if($myrow['ADDON_SERVICEID'])
						$sid.=",".$myrow['ADDON_SERVICEID'];

					$rights_arr=$serviceObj->getRights($sid);
					$rights=implode(",",$rights_arr);
					/*$sql = "update newjs.JPROFILE set SUBSCRIPTION = '$rights' where PROFILEID = '$proid[$i]'";
					mysql_query_decide($sql) or die("Service not activated. because of : ".mysql_error_js());*/
					$jprofileObj    =JProfileUpdateLib::getInstance();
					$paramArr =array("SUBSCRIPTION"=>$rights);
					$jprofileObj->editJPROFILE($paramArr,$proid[$i],'PROFILEID');

				}
				/*Added by Alok on 19th Feb 2005 end*/
			}
		}
                if($cnt > 0)
                        $msg = "You have successfully changed status of $cnt records<br>";
                else
                        $msg = "Sorry, You have not selected any record<br><br>";
                                                                                                 
                $msg .= "<a href=\"status_activation.php?name=$name&cid=$cid";
		if($showall=="Y")
                        $msg .= "&showall=Y";
                $msg .= "\">Continue &gt;&gt;</a>";
                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("crm_msg.tpl");
	
	}
	else
	{
		$i=1;
		$sql="SELECT AR_BRANCH FROM ARAMEX_BRANCHES WHERE ACTIVATION_BRANCH='$centre'";		         $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{	
			$ar_branch[]=$myrow['AR_BRANCH'];
		}
		if(count($ar_branch)>0)			
			$ar=implode("','",$ar_branch);

		if($showall=="Y")
			$sql="SELECT COUNT(*) from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
                else
			$sql="SELECT COUNT(*) from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY IN ('$ar') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];
		
		if($showall=="Y")
			$sql="SELECT PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,COMMENTS,ENTRYBY,COURIER_TYPE,ARAMEX_DT, SERVICE,ADDRESS,BRANCH_CITY.LABEL as CITY,PIN from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE ORDER BY ARAMEX_DT";// LIMIT $j,$PAGELEN";		
                else
			$sql="SELECT PAYMENT_COLLECT.ID,PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,COMMENTS,ENTRYBY,COURIER_TYPE,ARAMEX_DT, SERVICE,ADDRESS,BRANCH_CITY.LABEL as CITY,PIN from incentive.PAYMENT_COLLECT, incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS='' and DISPLAY <> 'N' and PAYMENT_COLLECT.CITY IN ('$ar') and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE ORDER BY ARAMEX_DT LIMIT $j,$PAGELEN ";

		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			while($myrow=mysql_fetch_array($result))
			{
//                                $sql="SELECT ENTRYBY from incentive.LOG where PROFILEID='$myrow[PROFILEID]' and CONFIRM='Y' and AR_GIVEN=''";

				$initiate_dt = "";
				$initiate_by = "";
				$entry_by = "";

				$sql = "Select * from incentive.LOG where REF_ID = $myrow[ID] order by ID desc";
//				$sql = "Select * from incentive.LOG where PROFILEID = $myrow[PROFILEID]";
                                $result1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                while($myrow1=mysql_fetch_array($result1))
				{ 
					if($myrow1["CONFIRM"] == "")
						$stage= "Payment Entry Stage";
					elseif($myrow1["CONFIRM"] == "Y" && $myrow1["AR_GIVEN"] == "")
					{
						$stage="Confirm Client Stage";	
						$entryby = $initiate_by = $myrow1['ENTRYBY'];
						$initiate_dt = get_date_format($myrow1["ENTRY_DT"]);
					}	
					elseif($myrow1["CONFIRM"] == "Y" && $myrow1["AR_GIVEN"] == "Y")
						$stage = "Dispatch Invoice Stage";

					$log_values[] = array("profileid"=>$myrow1["PROFILEID"],
								"comments"=>$myrow1["COMMENTS"],
								"entryby"=>$myrow1["ENTRYBY"],
								"stage"=>$stage,
								"username"=>$myrow1["USERNAME"],
								"logid"=>$myrow1["REF_ID"]	
								);
				}

				$address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
				$aramex_dt = get_date_format($myrow["ARAMEX_DT"]);

				$values[] = array("sno"=>$sno,
						  "id"=>$myrow["ID"],	
						  "profileid"=>$myrow["PROFILEID"],
						  "username"=>$myrow["USERNAME"],
						  "name"=>$myrow["NAME"],
						  "email"=>$myrow["EMAIL"],
						  "phone_res"=>$myrow["PHONE_RES"],
						  "phone_mob"=>$myrow["PHONE_MOB"],
						  "service"=>$myrow["SERVICE"],
						  "address"=>$address,
						  "entryby"=>$entryby,	
						  "comments"=>$myrow["COMMENTS"],
						  "courier"=>$myrow["COURIER_TYPE"],	
						  "comment_entryby"=>$myrow["ENTRYBY"],	
						  "stage"=>"Invoice Generation Stage",	
						  "aramex_dt"=>$aramex_dt,
						  "initiate_by"=>$initiate_by,
						  "initiate_dt"=>$initiate_dt,
						  "log_values"=>$log_values		
						 );
				unset($log_values);
				$sno++;
			}
		}
                if( $j )
                        $cPage = ($j/$PAGELEN) + 1;
                else
                        $cPage = 1;
		$smarty->assign("j",$j);
		$smarty->assign("PAGELEN",$PAGELEN);
                pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"status_activation.php",'','','',$showall);
                $smarty->assign("COUNT",$TOTALREC);
                $smarty->assign("CURRENTPAGE",$cPage);
                $no_of_pages=ceil($TOTALREC/$PAGELEN);
                $smarty->assign("NO_OF_PAGES",$no_of_pages);
		$smarty->assign("ROW",$values);
		if($showall=="Y")
                        $smarty->assign("showall","Y");
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("status_activation.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}

function get_date_format($dt)
{
        $date_time_arr = explode(" ",$dt);
        $date_arr = explode("-",$date_time_arr[0]);
        $date_val = date("d-M-Y",mktime(0,0,0,$date_arr[1],$date_arr[2],$date_arr[0]));
        return $date_val;

}

?>
