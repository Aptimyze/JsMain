<?php
/***************************
For tkt 667 - Sugar Data Flow MIS
****************************/

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
include("connect.inc");
include("sugarCRMCommon.php");
include($_SERVER["DOCUMENT_ROOT"]."/sugarcrm/custom/crons/housekeepingConfig.php");
include($_SERVER["DOCUMENT_ROOT"]."/sugarcrm/custom/include/language/en_us.lang.php");
include($_SERVER["DOCUMENT_ROOT"]."/profile/connect_reg.inc");

$mainDb=connect_master();
$data=authenticated($cid);
unset($mainDb);

global $partitionsArray;

$db=connect_misdb(); // Same as connect_slave check web/mis/config.php for function definition 
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

if(isset($data))
{
	if($submit)
	{
		if($criteria)
		{
			if($criteria=='detailed_report')
			{
				$fromDate="$detailed_from_year-$detailed_from_month-$detailed_from_date";
				$toDate="$detailed_to_year-$detailed_to_month-$detailed_to_date";
				$checkMonth=ltrim($detailed_from_month,"0");
				$checkDate=ltrim($detailed_from_date,"0");
				$checkYear=$detailed_from_year;
				$timestamp1=mktime(0,0,0,$checkMonth,$checkDate,$checkYear);
				$checkMonth=ltrim($detailed_to_month,"0");
                                $checkDate=ltrim($detailed_to_date,"0");
                                $checkYear=$detailed_to_year;
                                $timestamp2=mktime(0,0,0,$checkMonth,$checkDate,$checkYear);
				if($timestamp1<=$timestamp2)
				{
					$sortArr=array();
					if(!$sort || $sort=="undo")
						$sortByClause="ORDER BY created_by,campaign_id,date_entered";
					else
						$sortByClause="ORDER BY $sortBy $sort";
					$fromDateToDate="Selected Time Period  :  $detailed_from_date-$detailed_from_month-$detailed_from_year    To    $detailed_to_date-$detailed_to_month-$detailed_to_year";
					$smarty->assign("fromDateToDate",$fromDateToDate);
					$sql="SELECT leads.id,leads.last_name,leads.created_by,leads.campaign_id,leads.date_entered,email_addresses.email_address,leads_cstm.enquirer_email_id_c,leads.phone_mobile,leads_cstm.std_c,leads.phone_home,leads_cstm.enquirer_mobile_no_c,leads_cstm.std_enquirer_c,leads_cstm.enquirer_landline_c,leads_cstm.gender_c,leads_cstm.date_birth_c,leads.status,leads_cstm.disposition_c FROM sugarcrm.leads join sugarcrm.leads_cstm on leads.id=leads_cstm.id_c left join sugarcrm.email_addr_bean_rel on leads.id=email_addr_bean_rel.bean_id left join sugarcrm.email_addresses on email_addr_bean_rel.email_address_id=email_addresses.id where (bean_id IS NULL OR bean_module='Leads') and leads.date_entered>='$fromDate' and leads.date_entered<='$toDate 23:59:59' $sortByClause";
					$res=mysql_query_decide($sql,$db) or die("Error in data fetch sql  ".$sql."  ".mysql_error($db));
					while($row=mysql_fetch_assoc($res))
					{
						$createdBy=getSugarUser($row["created_by"]);
                                                $campaign=getCampaignName($row["campaign_id"]);
                                                $dateEntered=displayDate($row["date_entered"]);
                                                $age='';
                                                if($row["date_birth_c"])
                                                        $age=getAge($row["date_birth_c"]);
                                                $highlight=0;
                                                if(!$row["phone_home"] && !$row["phone_mobile"] && !$row["enquirer_landline_c"] && !$row["enquirer_mobile_no_c"])
                                                        $highlight=1;
						if($row["status"]=='45' || $row["status"]=='17' || $row["status"]=='46' || $row["status"]=="11")
							$highlight=1;
						if(!$sort || $sort=="undo")
						{
							$indexVar1=$createdBy;
							$indexVar2=$campaign;
							$indexVar3=$dateEntered;
							if(!in_array($createdBy,$sortArr))
								$sortArr[]=$createdBy;
						}
						else
						{
							if($sortBy=="date_entered")
							{
								$indexVar1=$dateEntered;
								if(!in_array($dateEntered,$sortArr))
									$sortArr[]=$dateEntered;
							}
							elseif($sortBy=="created_by")
							{
                                                                $indexVar1=$createdBy;
								if(!in_array($createdBy,$sortArr))
									$sortArr[]=$createdBy;
							}
							elseif($sortBy=="campaign_id")
							{
								$indexVar1=$campaign;
								if(!in_array($campaign,$sortArr))
									$sortArr[]=$campaign;
							}
							$indexVar2=1;
							$indexVar3=1;
						}
				                $unSortedResultArr[$indexVar1][$indexVar2][$indexVar3][]=array(
                                                                "name"=>$row["id"],
                                                                "entry_by"=>$createdBy,
                                                                "campaign"=>$campaign,
                                                                "created_on"=>$dateEntered,
                                                                "lead_email_id"=>$row["email_address"],
                                                                "enquirer_email_id"=>$row["enquirer_email_id_c"],
                                                                "Mobile1"=>$row["phone_mobile"],
                                                                "STD1"=>$row["std_c"],
                                                                "Landline1"=>$row["phone_home"],
                                                                "Mobile2"=>$row["enquirer_mobile_no_c"],
                                                                "STD2"=>$row["std_enquirer_c"],
                                                                "Landline2"=>$row["enquirer_landline_c"],
                                                                "Gender"=>$row["gender_c"],
                                                                "Age"=>$age,
                                                                "Status"=>$GLOBALS["app_list_strings"]["lead_status_dom"][$row["status"]],
                                                                "Disposition"=>$GLOBALS["app_list_strings"]["disposition_list"][$row["disposition_c"]],
								"highlight"=>$highlight
                                                                );
					}
					foreach($partitionsArray as $partition=>$partitionArray)
					{
						$leadsTable="sugarcrm_housekeeping.$partition"."_leads";
						$leadsCstmTable="sugarcrm_housekeeping.$partition"."_leads_cstm";
						$emailBeanTable="sugarcrm_housekeeping.$partition"."_email_addr_bean_rel";
						$emailAddressTable="sugarcrm_housekeeping.$partition"."_email_addresses";
						$sql="SELECT $leadsTable.id,$leadsTable.last_name,$leadsTable.created_by,$leadsTable.campaign_id,$leadsTable.date_entered,$emailAddressTable.email_address,$leadsCstmTable.enquirer_email_id_c,$leadsTable.phone_mobile,$leadsCstmTable.std_c,$leadsTable.phone_home,$leadsCstmTable.enquirer_mobile_no_c,$leadsCstmTable.std_enquirer_c,$leadsCstmTable.enquirer_landline_c,$leadsCstmTable.gender_c,$leadsCstmTable.date_birth_c,$leadsTable.status,$leadsCstmTable.disposition_c FROM $leadsTable join $leadsCstmTable on $leadsTable.id=$leadsCstmTable.id_c left join $emailBeanTable on $leadsTable.id=$emailBeanTable.bean_id left join $emailAddressTable on $emailBeanTable.email_address_id=$emailAddressTable.id where (bean_id IS NULL OR bean_module='Leads') and $leadsTable.date_entered>='$fromDate' and $leadsTable.date_entered<='$toDate 23:59:59' $sortByClause";
						$res=mysql_query_decide($sql,$db) or die("Error in data fetch sql  ".$sql."  ".mysql_error($db));
	                                        while($row=mysql_fetch_assoc($res))
						{
							$createdBy=getSugarUser($row["created_by"]);
							$campaign=getCampaignName($row["campaign_id"]);
							$dateEntered=displayDate($row["date_entered"]);
							$age='';
							if($row["date_birth_c"])
								$age=getAge($row["date_birth_c"]);
							$highlight=0;
							if(!$row["phone_home"] && !$row["phone_mobile"] && !$row["enquirer_landline_c"] && !$row["enquirer_mobile_no_c"])
								$highlight=1;
							if($row["status"]=='45' || $row["status"]=='17' || $row["status"]=='46'  || $row["status"]=="11")
								$highlight=1;
							if(!$sort || $sort=="undo")
							{
								$indexVar1=$createdBy;
	                                                        $indexVar2=$campaign;
        	                                                $indexVar3=$dateEntered;
								if(!in_array($createdBy,$sortArr))
									$sortArr[]=$createdBy;
							}
							else
							{
								if($sortBy=="date_entered")
								{
									$indexVar1=$dateEntered;
									if(!in_array($dateEntered,$sortArr))
										$sortArr[]=$dateEntered;
								}
								elseif($sortBy=="created_by")
								{
	                                                                $indexVar1=$createdBy;
									if(!in_array($createdBy,$sortArr))
										$sortArr[]=$createdBy;
								}
								elseif($sortBy=="campaign_id")
								{
									$indexVar1=$campaign;
									if(!in_array($campaign,$sortArr))
										$sortArr[]=$campaign;
								}
								$indexVar2=1;
								$indexVar3=1;
							}
							$unSortedResultArr[$indexVar1][$indexVar2][$indexVar3][]=array(
									"name"=>$row["id"],
									"entry_by"=>$createdBy,
									"campaign"=>$campaign,
									"created_on"=>$dateEntered,
									"lead_email_id"=>$row["email_address"],
									"enquirer_email_id"=>$row["enquirer_email_id_c"],
									"Mobile1"=>$row["phone_mobile"],
									"STD1"=>$row["std_c"],
									"Landline1"=>$row["phone_home"],
									"Mobile2"=>$row["enquirer_mobile_no_c"],
									"STD2"=>$row["std_enquirer_c"],
									"Landline2"=>$row["enquirer_landline_c"],
									"Gender"=>$row["gender_c"],
									"Age"=>$age,
									"Status"=>$GLOBALS["app_list_strings"]["lead_status_dom"][$row["status"]],
									"Disposition"=>$GLOBALS["app_list_strings"]["disposition_list"][$row["disposition_c"]],
									"highlight"=>$highlight
									);
						}
					}
					if(is_array($unSortedResultArr))
					{
						sort($sortArr);
						foreach($sortArr as $sortedIndex)
						{
							$resultArr[$sortedIndex]=$unSortedResultArr[$sortedIndex];
							unset($unSortedResultArr[$sortedIndex]);
						}
						$count=1;
						foreach($resultArr as $index1=>$index1Arr)
						foreach($resultArr[$index1] as $index2=>$index2Arr)
						foreach($resultArr[$index1][$index2] as $index3=>$index3Arr)
						foreach($resultArr[$index1][$index2][$index3] as $dataIndex=>$dataArr)
							$resultArr[$index1][$index2][$index3][$dataIndex]["count"]=$count++;
						unset($index1Arr);
						unset($index2Arr);
						unset($index3Arr);
						unset($dataArr);
						if($result_format=="xls")
						{
							$result.="Detailed report of Leads entered by Lead Entry Vendors";
							$result.="\n\n$fromDateToDate";
							$result.="\n\nName\tEntry By\tCampaign\tCreated On\tLead Email ID\tEnquirer Email ID\tMobile 1\tSTD1\tLandline 1 \tMobile 2\tSTD2\tLandline 2\tGender\tAge\tStatus\tDisposition";
							foreach($resultArr as $index1=>$index1Arr)
		                                              foreach($resultArr[$index1] as $index2=>$index2Arr)
                			                             foreach($resultArr[$index1][$index2] as $index3=>$index3Arr)
                                        				    foreach($resultArr[$index1][$index2][$index3] as $resultIndex=>$resultRow)
										{
											$result.="\n$resultRow[name]\t$resultRow[entry_by]\t$resultRow[campaign]\t$resultRow[created_on]\t$resultRow[lead_email_id]\t$resultRow[enquirer_email_id]\t$resultRow[Mobile1]\t$resultRow[STD1]\t$resultRow[Landline1]\t$resultRow[Mobile2]\t$resultRow[STD2]\t$resultRow[Landline2]\t$resultRow[Gender]\t$resultRow[Age]\t$resultRow[Status]\t$resultRow[Disposition]";
										}
							header("Content-Type: application/vnd.ms-excel");
			                                header("Content-Disposition: attachment; filename=Lead_Entry_Report.xls");
        	                        		header("Pragma: no-cache");
                			                header("Expires: 0");
							echo $result;
							die;
						}
						elseif($result_format=="html")
						{
							$link="sugarDataFlowMIS.php?cid=$cid&user=$user&criteria=detailed_report&detailed_from_date=$detailed_from_date&detailed_from_month=$detailed_from_month&detailed_from_year=$detailed_from_year&detailed_to_date=$detailed_to_date&detailed_to_month=$detailed_to_month&detailed_to_year=$detailed_to_year&submit=1&result_format=html";
							if($sort=="asc")
								$newSort="desc";
							if($sort=="desc")
								$newSort="undo";
							if($sort=="undo")
								$newSort="asc";
							if($sortBy=="created_by")
								$entryByLink=$link."&sort=$newSort&sortBy=created_by";
							else
								$entryByLink=$link."&sort=asc&sortBy=created_by";
							if($sortBy=="campaign_id")
								$campaignLink=$link."&sort=$newSort&sortBy=campaign_id";
							else    
								$campaignLink=$link."&sort=asc&sortBy=campaign_id";
							if($sortBy=="date_entered")
								$createdOnLink=$link."&sort=$newSort&sortBy=date_entered";
							else
								$createdOnLink=$link."&sort=asc&sortBy=date_entered";
							$smarty->assign("results",1);
							$smarty->assign("criteria",$criteria);
							$smarty->assign("resultArr",$resultArr);
							$smarty->assign("entryByLink",$entryByLink);
							$smarty->assign("campaignLink",$campaignLink);
							$smarty->assign("createdOnLink",$createdOnLink);
						}
					}							
				}
				else
					$errorMsg="Check Date Range specified";
			}
			elseif($criteria=="exec_performance" || $criteria=="source_performance")
			{
				if($performance_range)
				{
					if($criteria=="exec_performance")
					{
						$groupByClause="group by created_by";
						$select="created_by";
						$title="Lead Entry Executive Performance";
					}
					elseif($criteria=="source_performance")
					{
						$groupByClause="group by source_c";
						$select="source_c,campaign_id";
						$title="Lead Source Performance";
					}
					if($performance_range=="date_wise")
					{
						$fromDate=date("Y",time()) . "-$performance_month-01";
						$toDate=date("Y",time()) . "-$performance_month-31 23:59:59";
						$selectedTimePeriod="For the month $performance_month";
						$timeFunction="DAY";
						$groupByClause.=",DAY(date_entered)";
						/*$fromDate="2011-02-01";
						$toDate="2011-02-30";*/
					}
					elseif($performance_range=="month_wise")
					{
						$fromDate="$performance_year-01-01";
						$toDate="$performance_year-12-31 23:59:59";
						$selectedTimePeriod="For the Year $performance_year";
						$timeFunction="MONTH";
						$groupByClause.=",MONTH(date_entered)";
						/*$fromDate="2009-01-01";
						$toDate="2009-12-31";*/
					}
					if($criteria=="exec_performance")
						$groupByClause.=",status";
					else
						$groupByClause.=",status,campaign_id";
					$sql="SELECT COUNT(*) AS COUNT,$select,$timeFunction(date_entered) AS TIME_PERIOD,status FROM sugarcrm.leads";
					if($criteria=="source_performance")
						$sql.=" JOIN sugarcrm.leads_cstm ON id=id_c";
					$sql.=" WHERE date_entered>='$fromDate' AND date_entered<='$toDate' $groupByClause";
					$res=mysql_query_decide($sql) or die("Error while fetching results  ".$sql."  ".mysql_error_js());
					$labelArr=array();
					while($row=mysql_fetch_assoc($res))
					{
						if($criteria=="exec_performance")
						{
							if($row["created_by"] && !array_key_exists($row["created_by"],$labelArr))
								$labelArr[$row["created_by"]]=getSugarUser($row["created_by"]);
							if($row["created_by"])
								$name=$row["created_by"];
							else
							{
								$name="Unknown";
								$labelArr["Unknown"]="Unknown";
							}
						}
						elseif($criteria=="source_performance")
						{
							$newsPaper=0;
							$newsPaperVal='';
							$newsPaperName='';
							if($row["source_c"])
							{
								if($row["source_c"]=='4')
								{
									$newsPaperVal=getCampaignNewsPaperName($row["campaign_id"]);
									if($newsPaperVal)
									{
										$newsPaperName=$GLOBALS["app_list_strings"]["type_lead"][$newsPaperVal];
										if($newsPaperName)
										{
											$newsPaper=1;
											$labelArr["news_$newsPaperVal"]=$newsPaperName;
											$name="news_$newsPaperVal";
										}
									}
								}
								if(!$newsPaper)
								{
									$labelArr[$row["source_c"]]=$GLOBALS["app_list_strings"]["source_dom"][$row["source_c"]];
									$name=$row["source_c"];
								}
							}
							else
							{
								$name="Unknown";
								$labelArr["Unknown"]="Unknown";
							}
						}
						$counts[$name][$row["TIME_PERIOD"]]+=$row["COUNT"];
						$timeTotals[$row["TIME_PERIOD"]]+=$row["COUNT"];
						if($row["status"]=='26')
						{
							$regCount[$name][$row["TIME_PERIOD"]]+=$row["COUNT"];
							$timeRegTotals[$row["TIME_PERIOD"]]+=$row["COUNT"];
						}
					}
					foreach($partitionsArray as $partition=>$partitionArray)
                                        {
                                                $leadsTable="sugarcrm_housekeeping.$partition"."_leads";
                                                $leadsCstmTable="sugarcrm_housekeeping.$partition"."_leads_cstm";
						$sql="SELECT COUNT(*) AS COUNT,$select,$timeFunction(date_entered) AS TIME_PERIOD,status FROM $leadsTable";
	                                        if($criteria=="source_performance")
                                                $sql.=" JOIN $leadsCstmTable ON id=id_c";
						$sql.=" WHERE date_entered>='$fromDate' AND date_entered<='$toDate' $groupByClause";
						$res=mysql_query_decide($sql) or die("Error while fetching results  ".$sql."  ".mysql_error_js());
						while($row=mysql_fetch_assoc($res))
						{
							if($criteria=="exec_performance")
							{
								if($row["created_by"] && !array_key_exists($row["created_by"],$labelArr))
									$labelArr[$row["created_by"]]=getSugarUser($row["created_by"]);
								if($row["created_by"])
									$name=$row["created_by"];
								else
								{
									$name="Unknown";
									$labelArr["Unknown"]="Unknown";
								}
							}
							elseif($criteria=="source_performance")
							{
								$newsPaper=0;
								$newsPaperVal='';
								$newsPaperName='';
								if($row["source_c"])
								{
									if($row["source_c"]=='4')
									{
										$newsPaperVal=getCampaignNewsPaperName($row["campaign_id"]);
										if($newsPaperVal)
										{
											$newsPaperName=$GLOBALS["app_list_strings"]["type_lead"][$newsPaperVal];
											if($newsPaperName)
											{
												$newsPaper=1;
												$labelArr["news_$newsPaperVal"]=$newsPaperName;
												$name="news_$newsPaperVal";
											}
										}
									}       
									if(!$newsPaper)
									{
										$labelArr[$row["source_c"]]=$GLOBALS["app_list_strings"]["source_dom"][$row["source_c"]];                                                            
										$name=$row["source_c"];
									}
								}
								else
								{
									$name="Unknown";
									$labelArr["Unknown"]="Unknown";
								}
							}
							$counts[$name][$row["TIME_PERIOD"]]+=$row["COUNT"];
							$timeTotals[$row["TIME_PERIOD"]]+=$row["COUNT"];
							if($row["status"]=='26')
							{
								$regCount[$name][$row["TIME_PERIOD"]]+=$row["COUNT"];
								$timeRegTotals[$row["TIME_PERIOD"]]+=$row["COUNT"];
							}
						}
					}
					if(is_array($counts))
					{
						$timeArr=array();
						if($performance_range=="date_wise")
						{
							for($i=1;$i<=31;$i++)
								$timeArr[]=$i;
							$totalSpan="32";
							$width="3%";
						}
						elseif($performance_range=="month_wise")
						{
							for($i=1;$i<=12;$i++)
								$timeArr[]=$i;
							$totalSpan="13";
							$width="10%";
						}
						foreach($counts as $name=>$nameArr)
						{
							foreach($timeArr as $time)
							{
								if($counts[$name][$time])
								{
									$total=$counts[$name][$time];
									if($regCount[$name][$time])
										$reg=$regCount[$name][$time];
									else
										$reg=0;
									$per=ceil($reg/$total*100);
								}
								else
								{
									$total=0;
									$reg=0;
									$per=0;
								}
								$resultArr[$name][$time]=array(
												"registered"=>$reg,
												"reg_percentage"=>$per,
												"total"=>$total
												);
							}
						}
						foreach($timeArr as $time)
						{
							if($timeTotals[$time])
							{
								$total=$timeTotals[$time];
								if($timeRegTotals[$time])
									$reg=$timeRegTotals[$time];
								else
									$reg=0;
								$per=ceil($reg/$total*100);
								
							}
							else
							{
								$total=0;
								$reg=0;
								$per=0;
							}
							$totalResultArr[$time]=array(
											"registered"=>$reg,
                                                                                        "reg_percentage"=>$per,
                                                                                        "total"=>$total
											);
						}
						if($result_format=="xls")
						{
							$result="$title";
							$result.="\n$selectedTimePeriod";
							$result.="\nLabel\tRegistered\tTotal\tRegistered Percentage";
							$result.="\nLabel\t";
							foreach($timeArr as $time)
								$result.="$time\t\t\t";
							foreach($labelArr as $labelKey=>$label)
							{
								$result.="\n$label\t";
								foreach($timeArr as $time)
									$result.=$resultArr[$labelKey][$time]["registered"]."\t".$resultArr[$labelKey][$time]["total"]."\t".$resultArr[$labelKey][$time]["reg_percentage"]."\t";
								
							}
							$result.="\nTotals\t";
							foreach($totalResultArr as $time=>$totalRow)
							{
								$result.="$totalRow[registered]\t$$totalRow[total]\t$totalRow[reg_percentage]\t";
							}	
							header("Content-Type: application/vnd.ms-excel");
                                                        header("Content-Disposition: attachment; filename=Lead_Entry_Report.xls");
                                                        header("Pragma: no-cache");
                                                        header("Expires: 0");
                                                        echo $result;
                                                        die;
						}
						elseif($result_format=="html")
						{
							$smarty->assign("results",1);
							$smarty->assign("title",$title);
							$smarty->assign("width",$width);
							$smarty->assign("totalSpan",$totalSpan);
							$smarty->assign("resultArr",$resultArr);
							$smarty->assign("totalResultArr",$totalResultArr);
							$smarty->assign("labelArr",$labelArr);
							$smarty->assign("timeArr",$timeArr);
							$smarty->assign("selectedTimePeriod",$selectedTimePeriod);
							$smarty->assign("performance_range",$performance_range);
							$smarty->assign("criteria",$criteria);
						}
					}
				}
				else
					$errorMsg="Please select a performance range - Month or Year wise";
			}
		}
		else
			$errorMsg="Please specify criteria";
	}
	$today=date("Y-n-j");
	$dateArr=explode("-",$today);
	$thisYear=$dateArr[0];
	for($i=1;$i<=4;$i++)
	{
		$yearDD.="<option value=\"".$thisYear."\"";
		if($dateArr[0]==$thisYear)
			$yearDD.=" selected";
		$yearDD.=">$thisYear</option>";
		$thisYear--;
	}
	for($i=1;$i<=12;$i++)
	{
		if($i<10)
			$displayI="0".$i;
		else
			$displayI=$i;
		$monthDD.="<option value=\"".$displayI."\"";
                if($dateArr[1]==$i)
                        $monthDD.=" selected";
                $monthDD.=">$displayI</option>";
	}
	for($i=1;$i<=31;$i++)
        {
		if($i<10)
			$displayI="0".$i;
		else
			$displayI=$i;
                $dayDD.="<option value=\"".$displayI."\"";
                if($dateArr[2]==$i)
                        $dayDD.=" selected";
                $dayDD.=">$displayI</option>";
        }
	if($errorMsg)
		$smarty->assign("errorMsg",$errorMsg);
	$smarty->assign("yearDD",$yearDD);
	$smarty->assign("monthDD",$monthDD);
	$smarty->assign("dayDD",$dayDD);
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->display("sugarDataFlowMIS.htm");
}
else
{
	$smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}
?>
