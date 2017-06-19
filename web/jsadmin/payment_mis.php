<?php

/***********************************************************************************************************************
* FILE NAME     : payment_mis.php
* DESCRIPTION   : Displays Business Sathi MIS to the Affiliate. Note:This script connects to the 241 server and not 205
* INCLUDES      : connect.inc,sourcefunc.php
* FUNCTIONS     : get_source_regstrations()          	: Returns the people registered within a certain time period for
*		:					: a particular source and amount paid by them.
*               : authenticated()       		: To check if the user is authenticated or not
*               : TimedOut()            		: To take action if the user is not authenticated
* CREATION DATE : 4 July, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

include("sourcefunc.php");
include("connect.inc");
//mysql_close($db);
$db=connect_slave();
$db2=connect_db();

$user=getname($cid);
$smarty->assign("user",$user);
$smarty->assign("cid",$cid);
 
if(authenticated($cid))
{
        if($Submit)
        {
		if($ID!=0)
		{
/*************************************************************************************************************************
		The query given below finds the PAYMENT MODEL and the various rates(Cost Per Click, Cost Per Free Leads 
		and Cost for different slabs) for the AFFILIATE
**************************************************************************************************************************/
		
			$select_strt_date=$Year1."-".$Month1."-01";
			$select_end_date=$Year2."-".$Month2."-31";
			$today=date("Y-m-d");
			
//			$sql="SELECT * FROM affiliate.AFF_RECORDS WHERE AFFILIATEID='$ID' AND ENTRY_DT BETWEEN '".$select_strt_date."' AND '".$select_end_date."' ORDER BY ID";
			$sql="SELECT * FROM affiliate.AFF_RECORDS WHERE AFFILIATEID='$ID' AND ENTRY_DT<'".$select_end_date."' ORDER BY ID";
			$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);

			if(mysql_num_rows($res)<=0)
			{
				$msg="No Record Found<br>  ";
			        $msg .="<a href=\"maingate.php?cid=$cid\">";
			        $msg .="Go to Manage Affiliate Page </a>";
			        $smarty->assign("MSG",$msg);
			        $smarty->display("jsadmin_msg.tpl");
			}
			else
			{	
				if($select_strt_date > $aff_data[0]['ENTRY_DT'])
				{
					$strt=$select_strt_date;
				}
				else if($select_strt_date <= $aff_data[0]['ENTRY_DT'])
				{
					$strt=$aff_data[0]['ENTRY_DT'];
				}

				if($select_end_date > $today)
				{
					$last=$today;
				}	
				else if($select_end_date <= $today)
				{
					$last=$select_end_date;
				}

				while($row=mysql_fetch_array($res))
				{
					$aff_data[]=array(	"ID"=>$row['ID'],
								"AFFILIATEID"=>$row['AFFILIATEID'],
								"USERNAME"=>$row['USERNAME'],
								"PAYMENT_MODEL"=>$row['PAYMENT_MODEL'],
								"CLICKS_RATE"=>$row['CLICKS_RATE'],
								"FREE_RATE"=>$row['FREE_RATE'],
								"PAID_REG_MODEL"=>$row['PAID_REG_MODEL'],
								"PAID_1_TO_50"=>$row['PAID_1_TO_50'],
								"PAID_51_TO_200"=>$row['PAID_51_TO_200'],
								"PAID_201"=>$row['PAID_201'],
								"ENTRY_DT"=>$row['ENTRY_DT'],
								"CNT"=>0,
								"CNT_REV"=>0,
								"CNT_PAID"=>0,
								"PAID_REV"=>0,
								"CONVERSION"=>0,
								"IP"=>0);
				}
				for($unset_aff=0;$unset_aff<count($aff_data);$unset_aff++)
				{
					if($aff_data[$unset_aff+1]['ENTRY_DT']>$aff_data[$unset_aff]['ENTRY_DT'] && $aff_data[$unset_aff+1]['ENTRY_DT']<$strt)
					{
						unset($aff_data[$unset_aff]);
					}
				}
		
	
				if(count($aff_data)==1)
				{
					$DATA=calculate_revenue($ID,$strt,$last,$aff_data[0]);
					$aff_data[0]['CNT']=$DATA['COUNT'];
					$aff_data[0]['CNT_REV']=$DATA['COUNT_REV'];
					$aff_data[0]['CNT_PAID']=$DATA['CNT_PAID'];
					$aff_data[0]['PAID_REV']=$DATA['PAID_REV'];
					$aff_data[0]['CONVERSION']=$DATA['CONVERSION'];
					$aff_data[0]['IP']=$DATA['IP'];
					$smarty->assign("GRAND_TOTAL",$aff_data[0]['CNT_REV']+$aff_data[0]['PAID_REV']);
				}
				else
				{	
					$DATA=calculate_revenue($ID,$strt,$aff_data[1]['ENTRY_DT'],$aff_data[0]);
					$aff_data[0]['CNT']=$DATA['COUNT'];
					$aff_data[0]['CNT_REV']=$DATA['COUNT_REV'];
					$aff_data[0]['CNT_PAID']=$DATA['CNT_PAID'];
					$aff_data[0]['PAID_REV']=$DATA['PAID_REV'];
					$aff_data[0]['CONVERSION']=$DATA['CONVERSION'];
					$aff_data[0]['IP']=$DATA['IP'];
			
					for($i=0;$i<count($aff_data)-2;$i++)
					{
						$DATA=calculate_revenue($ID,$aff_data[$i+1]['ENTRY_DT'],$aff_data[$i+2]['ENTRY_DT'],$aff_data[$i+1]);
						$aff_data[$i+1]['CNT']=$DATA['COUNT'];
						$aff_data[$i+1]['CNT_REV']=$DATA['COUNT_REV'];
						$aff_data[$i+1]['CNT_PAID']=$DATA['CNT_PAID'];
						$aff_data[$i+1]['PAID_REV']=$DATA['PAID_REV'];
						$aff_data[$i+1]['CONVERSION']=$DATA['CONVERSION'];
						$aff_data[$i+1]['IP']=$DATA['IP'];
					}

					$DATA=calculate_revenue($ID,$aff_data[count($aff_data)-1]['ENTRY_DT'],$last,$aff_data[count($aff_data)-1]);
					$aff_data[count($aff_data)-1]['CNT']=$DATA['COUNT'];
					$aff_data[count($aff_data)-1]['CNT_REV']=$DATA['COUNT_REV'];
					$aff_data[count($aff_data)-1]['CNT_PAID']=$DATA['CNT_PAID'];
					$aff_data[count($aff_data)-1]['PAID_REV']=$DATA['PAID_REV'];
					$aff_data[count($aff_data)-1]['CONVERSION']=$DATA['CONVERSION'];
					$aff_data[count($aff_data)-1]['IP']=$DATA['IP'];

					$gtotal=0;
					for($arr_cnt=0;$arr_cnt<count($aff_data);$arr_cnt++)
					{
						$gtotal+=$aff_data[$arr_cnt]['CNT_REV']+$aff_data[$arr_cnt]['PAID_REV'];
					}
					$smarty->assign("GRAND_TOTAL",$gtotal);
				}

				$smarty->assign("USERNAME",$aff_data[0]["USERNAME"]);
				$smarty->assign("PAYMENT_MODEL",$aff_data[0]["PAYMENT_MODEL"]);
				$smarty->assign("DATA",$aff_data);
				$smarty->assign("ID",$ID);
				$smarty->assign("start_date",$select_strt_date);
				$smarty->assign("end_date",$select_end_date);
				$smarty->display("payment_modle.htm");
				unset($DATA);
			}
		}
		else
		{
			$select_strt_date=$Year1."-".$Month1."-01";
			$select_end_date=$Year2."-".$Month2."-31";
			$today=date("Y-m-d");
			$arr_cnt=0;

//			$sql1="SELECT * FROM affiliate.AFF_RECORDS WHERE ENTRY_DT BETWEEN '".$select_strt_date."' AND '".$select_end_date."' ORDER BY AFFILIATEID,ID";
			$sql1="SELECT * FROM affiliate.AFF_RECORDS WHERE ENTRY_DT<'".$select_end_date."' ORDER BY AFFILIATEID,ID";
			$res1=mysql_query_decide($sql1,$db) or logError(mysql_error_js(),$sql1);
			while($row1=mysql_fetch_array($res1))
			{
				if(is_array($aff_arr))
				{
					if(!in_array($row1['AFFILIATEID'],$aff_arr))
					{
						$aff_arr[]=$row1['AFFILIATEID'];
					}
				}
				else
				{
					$aff_arr[]=$row1['AFFILIATEID'];
				}

				$arr_cnt=array_search($row1['AFFILIATEID'],$aff_arr);

				$aff_data[$arr_cnt][]=array(	"AFFILIATEID"=>$row1['AFFILIATEID'],
								"USERNAME"=>$row1['USERNAME'],
								"PAYMENT_MODEL"=>$row1['PAYMENT_MODEL'],
								"CLICKS_RATE"=>$row1['CLICKS_RATE'],
								"FREE_RATE"=>$row1['FREE_RATE'],
								"PAID_1_TO_50"=>$row1['PAID_1_TO_50'],
								"PAID_51_TO_200"=>$row1['PAID_51_TO_200'],
								"PAID_201"=>$row1['PAID_201'],
								"ENTRY_DT"=>$row1['ENTRY_DT'],
								"COUNT_C_P"=>0,
								"REV_C_P"=>0,
								"CNT_PAID"=>0,
								"REV_PAID"=>0,
								"CONVERSION"=>0,
								"IP"=>0);
			}
			for($i=0;$i<count($aff_data);$i++)
			{
				if($select_strt_date > $aff_data[$i][0]['ENTRY_DT'])
				{
					$strt=$select_strt_date;
				}
				else if($select_strt_date <= $aff_data[$i][0]['ENTRY_DT'])
				{
					$strt=$aff_data[$i][0]['ENTRY_DT'];
				}
				if($select_end_date > $today)
				{
					$last=$today;
				}	
				else if($select_end_date <= $today)
				{
					$last=$select_end_date;
				}

				for($unset_aff=0;$unset_aff<count($aff_data[$i]);$unset_aff++)
				{
					if($aff_data[$i][$unset_aff+1]['ENTRY_DT']>$aff_data[$i][$unset_aff]['ENTRY_DT'] && $aff_data[$i][$unset_aff+1]['ENTRY_DT']<$strt)
					{
						unset($aff_data[$i][$unset_aff]);
					}
				}
		
				if(count($aff_data[$i])==1)
				{
					$DATA=calculate_revenue($aff_data[$i][0]['AFFILIATEID'],$strt,$last,$aff_data[$i][0]);
					$aff_data[$i][0]['COUNT_C_P']=$DATA['COUNT'];
					$aff_data[$i][0]['REV_C_P']=$DATA['COUNT_REV'];
					$aff_data[$i][0]['CNT_PAID']=$DATA['CNT_PAID'];
					$aff_data[$i][0]['REV_PAID']=$DATA['PAID_REV'];
					$aff_data[$i][0]['CONVERSION']=$DATA['CONVERSION'];
					$aff_data[$i][0]['IP']=$DATA['IP'];
				}
				else
				{	
					$DATA=calculate_revenue($aff_data[$i][0]['AFFILIATEID'],$strt,$aff_data[$i][1]['ENTRY_DT'],$aff_data[$i][0]);
					$aff_data[$i][0]['COUNT_C_P']=$DATA['COUNT'];
					$aff_data[$i][0]['REV_C_P']=$DATA['COUNT_REV'];
					$aff_data[$i][0]['CNT_PAID']=$DATA['CNT_PAID'];
					$aff_data[$i][0]['REV_PAID']=$DATA['PAID_REV'];
					$aff_data[$i][0]['CONVERSION']=$DATA['CONVERSION'];
					$aff_data[$i][0]['IP']=$DATA['IP'];
				
					for($C=0;$C<=count($aff_data[$i])-2;$C++)
					{
						$DATA=calculate_revenue($aff_data[$i][0]['AFFILIATEID'],$aff_data[$i][$C+1]['ENTRY_DT'],$aff_data[$i][$C+2]['ENTRY_DT'],$aff_data[$i][$C+1]);
						$aff_data[$i][$C+1]['COUNT_C_P']=$DATA['COUNT'];
						$aff_data[$i][$C+1]['REV_C_P']=$DATA['COUNT_REV'];
						$aff_data[$i][$C+1]['CNT_PAID']=$DATA['CNT_PAID'];
						$aff_data[$i][$C+1]['REV_PAID']=$DATA['PAID_REV'];
						$aff_data[$i][$C+1]['CONVERSION']=$DATA['CONVERSION'];
						$aff_data[$i][$C+1]['IP']=$DATA['IP'];
					}

					$DATA=calculate_revenue($aff_data[$i][0]['AFFILIATEID'],$aff_data[$i][count($aff_data[$i])-1]['ENTRY_DT'],$last,$aff_data[$i][count($aff_data[$i])-1]);
					$aff_data[$i][count($aff_data[$i])-1]['COUNT_C_P']=$DATA['COUNT'];
					$aff_data[$i][count($aff_data[$i])-1]['REV_C_P']=$DATA['COUNT_REV'];
					$aff_data[$i][count($aff_data[$i])-1]['CNT_PAID']=$DATA['CNT_PAID'];
					$aff_data[$i][count($aff_data[$i])-1]['REV_PAID']=$DATA['PAID_REV'];
					$aff_data[$i][count($aff_data[$i])-1]['CONVERSION']=$DATA['CONVERSION'];
					$aff_data[$i][count($aff_data[$i])-1]['IP']=$DATA['IP'];
				}
			}
			//print_r($aff_data);
			for($i=0;$i<count($aff_data);$i++)
			{
				for($j=0;$j<count($aff_data[$i]);$j++)
				{
					$grand_rev[$i]=$grand_rev[$i]+$aff_data[$i][$j]['REV_C_P']+$aff_data[$i][$j]['REV_PAID'];
				}
			}
			$smarty->assign("start_date",$select_strt_date);
			$smarty->assign("end_date",$select_end_date);
			$smarty->assign("DATA",$aff_data);
			$smarty->assign("REV",$grand_rev);
			$smarty->display("payment_all.htm");
		}
        }
        else
        {
		$sql="SELECT AFFILIATEID,USERNAME FROM affiliate.AFFILIATE_DET";
		$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
		while($row=mysql_fetch_array($res))
		{
			$aff[]=array("id"=>$row['AFFILIATEID'],"name"=>$row['USERNAME']);
		}
		$smarty->assign("aff",$aff);
                $smarty->display("payment_mis.htm");
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



function calculate_revenue($id,$strt_dt,$end_dt,$arr)
{	
	global $db;
	$sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_HITS WHERE SourceID LIKE 'af".$id."%' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."'";
	$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$source_hits=$row['CNT'];		
	
	$sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_MEMBERS WHERE SourceID LIKE 'af".$id."%' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."' AND ENTRY_MODIFY='E'";
	$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$source_members=$row['CNT'];		
	
	$sql="SELECT COUNT(DISTINCT IPADD) AS CNT FROM newjs.JPROFILE  WHERE SOURCE LIKE 'af".$id."%' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."'";
	$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$distinct_ip=$row['CNT'];		
	
	if($source_hits!=0)
		$conversion=($source_members/$source_hits)*100;
	else
		$conversion=0;	
	
	if($source_members!=0)
		$ip_conversion=($distinct_ip/$source_members)*100;
	else
		$ip_conversion=0;

	if($ip_conversion>100)
		$ip_conversion=100;	

	if($arr['PAYMENT_MODEL']=='C')
	{
		$sql="SELECT COUNT(DISTINCT IPADD) AS CNT FROM MIS.HITS WHERE SourceID LIKE 'af".$id."%' AND Date BETWEEN '".$strt_dt."' AND '".$end_dt."'";
		$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		
		if($row['CNT']==0)
			$CNT=0;
		else
			$CNT=$row['CNT'];
		
		$data=array(	"COUNT"=>$CNT,
				"COUNT_REV"=>$row['CNT']*$arr['CLICKS_RATE'],
				"CNT_PAID"=>0,
				"PAID_REV"=>0,
				"MF"=>$arr['CLICKS_RATE'],
				"TYPE"=>'C',
				"CONVERSION"=>round($conversion,2),
				"IP"=>round($ip_conversion,2));

		return $data;
	}
	else if($arr['PAYMENT_MODEL']=='F')
	{
		$sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_MEMBERS WHERE SOURCEID LIKE 'af".$id."%' AND SUBSCRIPTION!='Y' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."' AND ENTRY_MODIFY='E'";
		$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);

		if($row['CNT']==0)
                        $CNT=0;
                else
                        $CNT=$row['CNT'];

		
		$data=array(	"COUNT"=>$CNT,
				"COUNT_REV"=>$row['CNT']*$arr['FREE_RATE'],
				"CNT_PAID"=>0,
				"PAID_REV"=>0,
				"MF"=>$arr['FREE_RATE'],
				"TYPE"=>'F',
				"CONVERSION"=>round($conversion,2),
				"IP"=>round($ip_conversion,2));
		return $data;
	}
	else if($arr['PAYMENT_MODEL']=='CP')
	{
		$sql="SELECT COUNT(DISTINCT IPADD) AS CNT FROM MIS.HITS WHERE SourceID LIKE 'af".$id."%' AND Date BETWEEN '".$strt_dt."' AND '".$end_dt."'";
		$res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
                $row_clicks=mysql_fetch_array($res);
		$rev_clicks=$row_clicks['CNT']*$arr['CLICKS_RATE'];

		//code for calculating paid members and their revenue
		$paid_mem=get_source_registrations("af".$id."%",$strt_dt,$end_dt,"Y");
		
		if($paid_mem['cnt']<=50)
			$rev_paid=($arr['PAID_1_TO_50']*$paid_mem['amt'])/100;
		else if($paid_mem['cnt']<=200 && $paid_mem['cnt']>50)
			$rev_paid=($arr['PAID_51_TO_200']*$paid_mem['amt'])/100;
		else if($paid_mem['cnt']>200)
			$rev_paid=($arr['PAID_201']*$paid_mem['amt'])/100;

		$data=array(	"COUNT"=>$row_clicks['CNT'],
				"COUNT_REV"=>$rev_clicks,
				"CNT_PAID"=>$paid_mem['cnt'],
				"PAID_REV"=>$rev_paid,
				"MF"=>"NA",
				"TYPE"=>"CP",
				"CONVERSION"=>round($conversion,2),
				"IP"=>round($ip_conversion,2));
	
		return $data;
	}
	else if($arr['PAYMENT_MODEL']=='FP')
        {
                $sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_MEMBERS WHERE SOURCEID LIKE 'af".$id."%' AND SUBSCRIPTION!='Y' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."' AND ENTRY_MODIFY='E'";
                $res=mysql_query_decide($sql,$db) or logError(mysql_error_js(),$sql);
                $row_free=mysql_fetch_array($res);
		$rev_free=$row_free['CNT']*$arr['FREE_RATE'];

		//code for calculating paid members and their revenue
		$paid_mem=get_source_registrations("af".$id."%",$strt_dt,$end_dt,"Y");
		
		if($paid_mem['cnt']<=50)
			$rev_paid=($arr['PAID_1_TO_50']*$paid_mem['amt'])/100;
		else if($paid_mem['cnt']>50 && $paid_mem['cnt']<=200)
			$rev_paid=($arr['PAID_51_TO_200']*$paid_mem['amt'])/100;
		else if($paid_mem['cnt']>200)
			$rev_paid=($arr['PAID_201']*$paid_mem['amt'])/100;

		$data=array(	"COUNT"=>$row_free['CNT'],
				"COUNT_REV"=>$rev_free,
				"CNT_PAID"=>$paid_mem['cnt'],
				"PAID_REV"=>$rev_paid,
				"MF"=>"NA",
				"TYPE"=>"FP",
				"CONVERSION"=>round($conversion,2),
				"IP"=>round($ip_conversion,2));
		return $data;
        }

unset($data);
}
		
?>
