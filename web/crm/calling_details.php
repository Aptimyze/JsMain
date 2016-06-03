<?php

include("connect.inc");
include("history.php");

if(authenticated($cid))
{
	$flag=0;
	$name= getname($cid);
        $privilage = explode("+",getprivilage($cid));
        if(in_array("SLHD",$privilage) || in_array("SLSUP",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage) || in_array("TRNG",$privilage))
		$limit =0;
	else
		$limit =5;

	if($GetHistory)
	{
		$flag=1;
		$sql = "SELECT PROFILEID,EMAIL,SUBSCRIPTION FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                if($myrow=mysql_fetch_array($result))
		{
	                $profileid=$myrow['PROFILEID'];
			$temp_email=explode("@",$myrow['EMAIL']);
			$email=$temp_email[0]."@xxx.com";
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("EMAIL",$email);

			if(in_array("IA",$privilage))
			{
				$admin=1;
				$smarty->assign("ADMIN","Y");
			}

			$sql="SELECT CENTER,EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$name' AND COMPANY='JS'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$center=strtoupper($row['CENTER']);
			$emp_id=$row['EMP_ID'];

			$emp_id_str="'$emp_id'";
			$emp_id_str1="";
			
			if($admin)
				$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID='$emp_id' AND COMPANY='JS'";
			else
				$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE UPPER(CENTER)='$center' AND COMPANY='JS'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        	
			while($row=mysql_fetch_array($res))
			{
				if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
				{
					$emp_id_str1 .= "'$row[EMP_ID]',";
				}
 			}

			$emp_id_str = $emp_id_str1 . $emp_id_str;
			$val=0;
			if($admin)
			{			
				while(1)
				{
					$emp_id_str1=substr($emp_id_str1, 0, strlen($emp_id_str1) - 1);
					$emp_id_str2="";
				
					if($emp_id_str1=='')
						$emp_id_str1="''";		
					$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID IN ($emp_id_str1) AND COMPANY='JS'";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

					if(mysql_num_rows($res) == 0)
					{
						break;
					}  
					else
					{
						while($row=mysql_fetch_array($res))
						{
							if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
							$emp_id_str2 .= "'$row[EMP_ID]',";
						}
						if(!$emp_id_str2)
							break;
						$emp_id_str1=$emp_id_str2;
						$emp_id_str = $emp_id_str2 . $emp_id_str;
						$val++;
						if($val == 15)
						{
							echo $val;
							die();
						}
					}
				}
			}                        	  

			$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID IN ($emp_id_str) AND COMPANY='JS'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			while($row=mysql_fetch_array($res))
			{
				$allotarr[]=$row['USERNAME'];
			}

			if($allotarr)
			{
				/*	
	                        $sqlD ="select DEALLOCATION_DT from incentive.DEALLOCATION_TRACK where PROFILEID='$profileid'";
        	                $resD=mysql_query_decide($sqlD) or die("$sqlD".mysql_error_js());
        	                while($rowD=mysql_fetch_array($resD))
        	                        $deAllocateDates[]=$rowD['DEALLOCATION_DT'];
				*/

				$allot_str=implode("','",$allotarr);
				//$dateSet =JSstrToTime('2013-03-01 00:00:00');
				$curenttAlloted =0;
				//$dateSet =JSstrToTime('2013-03-01 00:00:00');	
				$sql="SELECT ALLOTED_TO,STATUS FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOTED_TO IN ('$allot_str')";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
					$curenttAlloted =1;
				if($limit && !$curenttAlloted)
					$smarty->assign("NOTFOUND","Y");
				else{
					if($curenttAlloted)
					{	
						$smarty->assign("NOTFOUND","");
						$orig_alloted_to=$row['ALLOTED_TO'];

						if($limit){
							$limitCount =getHistoryCount($profileid);
							if($limitCount>=5)
								$limit =$limitCount;
						}
						$user_values=gethistory($USERNAME,$limit);
						$smarty->assign("ROW",$user_values);

						if($row['STATUS']=='P' && $myrow['SUBSCRIPTION']<>'')
							$smarty->assign("ALREADY_PAID","Y");
						elseif($myrow['SUBSCRIPTION']<>'')
							$smarty->assign("ALREADY_PAID","Y");
						$smarty->assign("orig_alloted_to",$orig_alloted_to);
						$sql="SELECT ALLOTED_TO, ALLOT_TIME, RELAX_DAYS,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid'";
						$sql .=" UNION select ALLOTED_TO, ALLOT_TIME, RELAX_DAYS,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE PROFILEID='$profileid' ORDER BY ALLOT_TIME ASC";
						$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						if($row=mysql_fetch_array($res))
						{
							$i=0;
							do
							{
								if(strstr($allot_str,$row['ALLOTED_TO']))
        	                                                {
									$allotTime                              =$row['ALLOT_TIME'];
									$deAllocationDt                         =$row['DE_ALLOCATION_DT'];

									$handled_arr[$i]["SNO"]=$i+1;
									$handled_arr[$i]["ALLOTED_TO"]		=$row['ALLOTED_TO'];
									$handled_arr[$i]["ALLOT_TIME"]		=$allotTime;
									$handled_arr[$i]["DE_ALLOCATION_DT"]    =$deAllocationDt;
									$handled_arr[$i]["RELAX_DAYS"]		=$row['RELAX_DAYS'];
									$handled_arr[$i]["ACTUAL_DEALLOCATION_DT"]=$row['REAL_DE_ALLOCATION_DT'];

									/*
									if((JSstrToTime($allotTime)>$dateSet) && (count($deAllocateDates))>0){
									foreach($deAllocateDates as $key=>$val){
										$actualDeAllocDt =JSstrToTime($val);
										if($actualDeAllocDt>JSstrToTime($allotTime)){
											$handled_arr[$i]['ACTUAL_DEALLOCATION_DT'] =$val;
											break;
										}
									}}
									unset($deAllocateDates[$key]);
									*/
									$i++;
								}
							}while($row=mysql_fetch_array($res));

							$smarty->assign("handled_arr",$handled_arr);
						}
					}
					else
					{
						$sql="SELECT ALLOTED_TO, ALLOT_TIME, RELAX_DAYS,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid'";
						$sql .=" UNION select ALLOTED_TO, ALLOT_TIME, RELAX_DAYS,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE PROFILEID='$profileid' ORDER BY ALLOT_TIME ASC";
						$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
						if($row=mysql_fetch_array($res))
						{
							$i=0;
							$smarty->assign("NOTFOUND","");
							if(strstr($allot_str,$row['ALLOTED_TO']))
								$orig_alloted_to=$row['ALLOTED_TO'];
							
							if($limit){
								$limitCount =getHistoryCount($profileid);
								if($limitCount>=5)
									$limit =$limitCount;
							}
							$user_values=gethistory($USERNAME,$limit);
							$smarty->assign("ROW",$user_values);

							if($row['STATUS']=='P' && $myrow['SUBSCRIPTION']<>'')
								$smarty->assign("ALREADY_PAID","Y");
							elseif($myrow['SUBSCRIPTION']<>'')
								$smarty->assign("ALREADY_PAID","Y");
							do
							{
								if(strstr($allot_str,$row['ALLOTED_TO']))
	                                                        {
									$allotTime                              =$row['ALLOT_TIME'];
									$deAllocationDt                         =$row['DE_ALLOCATION_DT'];

									$handled_arr[$i]["SNO"]=$i+1;
									$handled_arr[$i]["ALLOTED_TO"]		=$row['ALLOTED_TO'];
									$handled_arr[$i]["ALLOT_TIME"]		=$allotTime;
									$handled_arr[$i]["DE_ALLOCATION_DT"]    =$deAllocationDt;	
									$handled_arr[$i]["RELAX_DAYS"]		=$row['RELAX_DAYS'];
									$handled_arr[$i]["ACTUAL_DEALLOCATION_DT"]=$row['REAL_DE_ALLOCATION_DT'];
									/*
									if((JSstrToTime($allotTime)>$dateSet) && (count($deAllocateDates))>0){
									foreach($deAllocateDates as $key=>$val){
										$actualDeAllocDt =JSstrToTime($val);
										if($actualDeAllocDt>JSstrToTime($allotTime)){
											$handled_arr[$i]['ACTUAL_DEALLOCATION_DT'] =$val;
											break;
										}
									}}
									unset($deAllocateDates[$key]);
									*/
									$i++;
								}
							}while($row=mysql_fetch_array($res));

							$smarty->assign("handled_arr",$handled_arr);
						}
						else
						{
							$smarty->assign("NOTFOUND","Y");
						}
					}
				}
			}
		}
                else
		{
                        $smarty->assign("wrong_username","Y");
		}
		$smarty->assign("flag",$flag);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->display("calling_details.htm");
	}
	else
	{
		$smarty->assign("flag",$flag);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("calling_details.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("crm_msg.tpl");
}
?>
