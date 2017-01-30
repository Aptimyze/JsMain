<?php

// Author : Neha Gupta
// This class handles all the logics related to Gateway-wise MIS.

class GatewayWiseMis
{
	private $start_dt;
	private $end_dt;
	private $currency;

	public function __construct($start_dt, $end_dt, $currency)
	{
		$this->start_dt = $start_dt." 00:00:00";
		$this->end_dt = $end_dt." 23:59:59";
		$this->currency = $currency;
	}

	public function fetchGatewayAndChannelWiseData()
	{
		$ordDevObj = new billing_ORDERS_DEVICE('newjs_slave');
        if($this->start_dt >= "2017-04-01 00:00:00" ){
            $table = "PAYMENT_DETAIL_NEW";
            $condition = "IN ('DONE','BOUNCE','CANCEL', 'REFUND', 'CHARGE_BACK')";
        }
        else{
            $table = "PAYMENT_DETAIL";
            $condition = "='DONE'";
        }
		if(strtotime($this->end_dt) < strtotime("2015-01-01 00:00:00")) 
		{
			$info = $ordDevObj->getGatewayWiseData($this->start_dt, $this->end_dt, $this->currency,$table,$condition);
		} 
		else if(strtotime($this->start_dt) >= strtotime("2015-01-01 00:00:00"))
		{
			$info = $ordDevObj->getGatewayAndChannelWiseData($this->start_dt, $this->end_dt, $this->currency,$table,$condition);
		}
		else
		{
			$info1 = $ordDevObj->getGatewayWiseData($this->start_dt, '2014-12-31 23:59:59', $this->currency,$table,$condition);
			$info = $ordDevObj->getGatewayAndChannelWiseData('2015-01-01 00:00:00', $this->end_dt, $this->currency,$table,$condition);

			foreach($info1 as $gateway => $srcArr)
			{
				$info[$gateway]['desktop'] += $srcArr['desktop'];
			}
		}
		$this->mergeOldAndNewMobileWebsiteData($info);
		$this->mergeOldAndNewAndroidAppData($info);
		return $info;
	}

	public function mergeOldAndNewMobileWebsiteData(&$info)
	{
		foreach($info as $gateway => $srcArr)
		{
			if($info[$gateway]["old_mobile_website"])
			{
				$info[$gateway]["mobile_website"] += $info[$gateway]["old_mobile_website"];
				unset($info[$gateway]["old_mobile_website"]);
			}
		}
	}

	public function mergeOldAndNewAndroidAppData(&$info)
	{
		foreach($info as $gateway => $srcArr)
		{
			if($info[$gateway]["JSAA_mobile_website"])
			{
				$info[$gateway]["Android_app"] += $info[$gateway]["JSAA_mobile_website"];
				unset($info[$gateway]["JSAA_mobile_website"]);
			}
		}
	}

	public function fetchGatewayAndChannelWiseTotal(&$info)
	{
      	$sourceArr = array();
      	foreach($info as $gateway => $srcArr)
      	{
      		$info[$gateway]['TOTAL'] = 0;
      		foreach($srcArr as $src => $amount)
      		{
      			$info[$gateway]['TOTAL'] += $amount;
      			$sourceArr[$src] += $amount;
      			$sourceArr['TOTAL'] += $amount;
      		}
      	}		
      	return $sourceArr;
	}
	
	public function createExcelFormatOutput($info, $sourceArr, $header, $displayDate)
	{
		$header .= "\n\nGateway\tDesktop\tMobile Website\tAndroid App\tiOS App\tTOTAL\n";

		foreach($info as $gateway => $srcArr)
		{
			$message .= $gateway."\t".$info[$gateway]['desktop']."\t".$info[$gateway]['mobile_website']."\t".$info[$gateway]['Android_app']."\t".$info[$gateway]['iOS_app']."\t".$info[$gateway]['TOTAL']."\n";
		} 
		$message .= "GRAND TOTAL\t".$sourceArr['desktop']."\t".$sourceArr['mobile_website']."\t".$sourceArr['Android_app']."\t".$sourceArr['iOS_app']."\t".$sourceArr['TOTAL'];

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Gateway_Wise_MIS_".$displayDate.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $header."\n".$message;
		die;
	}
}
?>
