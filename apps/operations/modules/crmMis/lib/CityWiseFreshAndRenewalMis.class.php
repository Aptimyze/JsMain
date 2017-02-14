<?php

// Library for cityWiseFreshAndRenewalMis
// Neha Gupta

class CityWiseFreshAndRenewalMis{
	
	private $start_dt;     // start date of selected duration
	private $end_dt;       // end date of selected duration
	private $rangeType;    // range type is either 'M' (for Month-wise data) or 'Q' (for Quarter-wise data)
	private $saleType;     // sale type is either 'F' for fresh sales, or 'R' for renewal sales, or 'T' for total sales
        private $rangeArr;     // range-wise distribution of data 

	public function __construct($year, $rangeType, $saleType){
		// Constructor code here
		$this->start_dt = $year."-04-01 00:00:00";
		$this->end_dt = ($year+1)."-03-31 23:59:59";
		$this->rangeType = $rangeType;
		$this->saleType = $saleType;

                $this->rangeArr[0] = array('start_dt' => $year."-04-01 00:00:00", 'end_dt' => $year."-06-30 23:59:59");
                $this->rangeArr[1] = array('start_dt' => $year."-07-01 00:00:00", 'end_dt' => $year."-09-30 23:59:59");
                $this->rangeArr[2] = array('start_dt' => $year."-10-01 00:00:00", 'end_dt' => $year."-12-31 23:59:59");
                $this->rangeArr[3] = array('start_dt' => ($year+1)."-01-01 00:00:00", 'end_dt' => ($year+1)."-03-31 23:59:59");
	}

	public function getSaleProfileWise($rangeLabel, $start_dt, $end_dt) {
                $pObj = new BILLING_PURCHASES('newjs_slave');

		$saleInfo = $pObj->fetchTotalSalesProfileWise($rangeLabel, $start_dt, $end_dt);
		foreach($saleInfo as $info) 
			$totalSale[$info['PROFILEID']][$info['rangeType']] += $info['SALE'];
		if($this->saleType == 'T')
			return $totalSale;
		
		foreach($saleInfo as $k => $info) {
			$firstPurchaseDt = $pObj->getFirstMainMembershipPurchaseDate($info['PROFILEID']);
			if(!$firstPurchaseDt || ($firstPurchaseDt && $firstPurchaseDt >= $this->end_dt) || ($firstPurchaseDt && $firstPurchaseDt >= $this->start_dt && $info['ENTRY_DT'] <= $firstPurchaseDt)) 
				$freshSale[$info['PROFILEID']][$info['rangeType']] += $info['SALE'];
			unset($saleInfo[$k]);
		}
		if($this->saleType == 'F')
			return $freshSale;

		$renewalSale = array();
		foreach($totalSale as $pid => $v) {
			foreach($v as $k => $sale)
				$renewalSale[$pid][$k] = $totalSale[$pid][$k] - $freshSale[$pid][$k]; 		
			unset($totalSale[$pid]);
			unset($freshSale[$pid]);
		}
		return $renewalSale;
	}
	public function getCityLabelArr(&$profileWiseSale) {
		$jprofileObj = new JPROFILE("newjs_slave");
		$cityObj = new newjs_CITY_NEW("newjs_slave");
		$cityLabels = $cityObj->getAllCityLabel();

		$cityLabelArr = array();
		foreach($profileWiseSale as $k => $v) {
			$profileIdArr = array();
			$cnt_v = count($v);
			$cv = 1;
			foreach($v as $pid => $val) {
				$profileIdArr[] = $pid;
				if(($cv == $cnt_v) || (count($profileIdArr)==100)) {
					$info = $jprofileObj->getCity($profileIdArr);
					foreach($info as $profileid => $city) {
						$cityLabelArr[$profileid] = $cityLabels[$city];
					}
					// foreach($profileIdArr as $profileid) {
					// 	if($info[$profileid])
					// 		$cityLabelArr[$profileid] = $cityLabels[$info[$profileid]];
					// 	else
					// 		unset($profileWiseSale[$k][$pid]);
					// }
					unset($profileIdArr);
				}
				$cv++;
			}
		}
		return $cityLabelArr;
	}

	public function getSaleCityWise() {
		$rangeLabel = $this->rangeType=='M' ? 'MONTH' : 'QUARTER';

		foreach($this->rangeArr as $k => $v) {
			$profileWiseSale[$k] = $this->getSaleProfileWise($rangeLabel, $v['start_dt'], $v['end_dt']);
		}
		$cityLabelArr = $this->getCityLabelArr($profileWiseSale);

                foreach($profileWiseSale as $kk => $val) {
                        foreach($val as $pid => $v) {
                                foreach($v as $k => $sale) {
                                        $cityWiseSale[$cityLabelArr[$pid]][$k] += $sale;
                                        $cityWiseSale[$cityLabelArr[$pid]]['TOTAL'] += $sale;
                                }
                        }
                }
		unset($profileWiseSale);
		foreach($cityWiseSale as $k => $v) 
			if($cityWiseSale[$k]['TOTAL'] <= 0)
				unset($cityWiseSale[$k]);
		return $cityWiseSale;
	}
	public function sort_citywiseSale($cityWiseSale) {
		foreach($cityWiseSale as $k => $v) {
			$totalCityWiseSale[$k] = $v['TOTAL']; 
		}
		arsort($totalCityWiseSale); 
		
		$res = array();
		foreach($totalCityWiseSale as $k => $v) {
			$res[$k] = $cityWiseSale[$k];
		}
		unset($cityWiseSale);
		unset($totalCityWiseSale);
        $topCities =  75; //Set here the top number of cities whose data is required, the 'total' of rest of the cities will go in $othersTotal
        foreach($res as $key=>$val){
            foreach($val as $k=>$v){
                $total[$k]+= $v;
            }
        }
		$res = array_slice($res, 0, $topCities);
        $res['Total'] = $total;
		return $res;
	}
        public function createExcelFormatOutput($resultArr, $header, $displayDate)
        {
                $header .= "\n\nCity\t";
		if($this->rangeType == 'Q') {
			$header .= "Apr-Jun\tJul-Sep\tOct-Dec\tJan-Mar";
			$indexArr = array(2,3,4,1);
		}
		else if($this->rangeType == 'M') {
			$header .= "Apr\tMay\tJun\tJul\tAug\tSep\tOct\tNov\tDec\tJan\tFeb\tMar";
			$indexArr = array(4,5,6,7,8,9,10,11,12,1,2,3);
		}
		$header .= "\tTOTAL\n";

                foreach($resultArr as $k=>$saleArr)
                {
			$message .= $k;
			$cnt = $this->rangeType=='M' ? 12 : 4;
			foreach($indexArr as $ii) {
				$message .= "\t";
				if($resultArr[$k][$ii])
					$message .= $resultArr[$k][$ii];
			}
			$message .= "\t".$resultArr[$k]['TOTAL']."\n";
                } 
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=City_Wise_Fresh_And_Renewal_MIS_".$displayDate.".xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $header."\n".$message;
                die;
        }

}

?>
