<?php
/***********************************************************************************************************************
* FILE NAME     : businesssathi_payment_mis.php
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
$db=connect_db();

$data=authenticated($checksum);

$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

 
if(isset($data))
{
//	mysql_close($db);
//        $db=connect_slave();

	$ID=$data["AFFILIATEID"];

        if($submit)
        {

		/********************************************************************************************************
		The query given below finds the PAYMENT MODEL and the various rates(Cost Per Click, Cost Per Free Leads 
		and Cost for different slabs) for the AFFILIATE
		*********************************************************************************************************/
		$select_strt_date=$Year1."-".$Month1."-01";
		$select_end_date=$Year2."-".$Month2."-31";
		$today=date("Y-m-d");
		
//		$sql="SELECT * FROM affiliate.AFF_RECORDS WHERE AFFILIATEID='$ID' AND ENTRY_DT BETWEEN '".$select_strt_date."' AND '".$select_end_date."' ORDER BY ID";
		$sql="SELECT * FROM affiliate.AFF_RECORDS WHERE AFFILIATEID='$ID' AND ENTRY_DT < '".$select_end_date."' ORDER BY ID";
		$res=mysql_query($sql) or logError(mysql_error(),$sql);
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
						"PAID_REV"=>0);
		}
		
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
			$smarty->assign("GRAND_TOTAL",$aff_data[0]['CNT_REV']+$aff_data[0]['PAID_REV']);
		}
		else
		{	
			$DATA=calculate_revenue($ID,$strt,$aff_data[1]['ENTRY_DT'],$aff_data[0]);
			$aff_data[0]['CNT']=$DATA['COUNT'];
			$aff_data[0]['CNT_REV']=$DATA['COUNT_REV'];
			$aff_data[0]['CNT_PAID']=$DATA['CNT_PAID'];
			$aff_data[0]['PAID_REV']=$DATA['PAID_REV'];
			
			for($i=0;$i<count($aff_data)-2;$i++)
			{
				$DATA=calculate_revenue($ID,$aff_data[$i+1]['ENTRY_DT'],$aff_data[$i+2]['ENTRY_DT'],$aff_data[$i+1]);
				$aff_data[$i+1]['CNT']=$DATA['COUNT'];
				$aff_data[$i+1]['CNT_REV']=$DATA['COUNT_REV'];
				$aff_data[$i+1]['CNT_PAID']=$DATA['CNT_PAID'];
				$aff_data[$i+1]['PAID_REV']=$DATA['PAID_REV'];
			}

			$DATA=calculate_revenue($ID,$aff_data[count($aff_data)-1]['ENTRY_DT'],$last,$aff_data[count($aff_data)-1]);
			$aff_data[count($aff_data)-1]['CNT']=$DATA['COUNT'];
			$aff_data[count($aff_data)-1]['CNT_REV']=$DATA['COUNT_REV'];
			$aff_data[count($aff_data)-1]['CNT_PAID']=$DATA['CNT_PAID'];
			$aff_data[count($aff_data)-1]['PAID_REV']=$DATA['PAID_REV'];

			$gtotal=0;
			for($arr_cnt=0;$arr_cnt<count($aff_data);$arr_cnt++)
			{
				$gtotal=$gtotal+$aff_data[$arr_cnt]['CNT_REV']+$aff_data[$arr_cnt]['PAID_REV'];
			}
			$smarty->assign("GRAND_TOTAL",$gtotal);
		}

		$smarty->assign("DATA",$aff_data);
		$smarty->display("business_sathi/businesssathi_payment_modle.htm");
		unset($DATA);
        }
        else
        {
                $smarty->display("business_sathi/businesssathi_payment_mis.htm");
        }
}
else
{
	TimedOut();
}


function calculate_revenue($id,$strt_dt,$end_dt,$arr)
{
	if($arr['PAYMENT_MODEL']=='C')
	{
		$sql="SELECT COUNT(DISTINCT IPADD) AS CNT FROM MIS.HITS WHERE SourceID LIKE 'af".$id."%' AND Date BETWEEN '".$strt_dt."' AND '".$end_dt."'";
		$res=mysql_query($sql) or logError(mysql_error(),$sql);
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
				"TYPE"=>'C');

		return $data;
	}
	else if($arr['PAYMENT_MODEL']=='F')
	{
		$sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_MEMBERS WHERE SOURCEID LIKE 'af".$id."%' AND SUBSCRIPTION!='Y' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."' AND ENTRY_MODIFY='E'";
		$res=mysql_query($sql) or logError(mysql_error(),$sql);
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
				"TYPE"=>'F');
		return $data;
	}
	else if($arr['PAYMENT_MODEL']=='CP')
	{
		$sql="SELECT COUNT(DISTINCT IPADD) AS CNT FROM MIS.HITS WHERE SourceID LIKE 'af".$id."%' AND Date BETWEEN '".$strt_dt."' AND '".$end_dt."'";
		$res=mysql_query($sql) or logError(mysql_error(),$sql);
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
				"TYPE"=>"CP");
	
		return $data;
	}
	else if($arr['PAYMENT_MODEL']=='FP')
        {
                $sql="SELECT SUM(COUNT) AS CNT FROM MIS.SOURCE_MEMBERS WHERE SOURCEID LIKE 'af".$id."%' AND SUBSCRIPTION!='Y' AND ENTRY_DT BETWEEN '".$strt_dt."' AND '".$end_dt."' AND ENTRY_MODIFY='E'";
                $res=mysql_query($sql) or logError(mysql_error(),$sql);
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
				"TYPE"=>"FP");
		return $data;
        }

unset($data);
}
		
		
?>
