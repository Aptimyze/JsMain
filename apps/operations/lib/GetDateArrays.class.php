<?php
//This class is used to return day,month and year arrays for looping required in MIS's

class GetDateArrays
{
	public function __construct()
	{
	}
	
	/*
	This function returns the day array 
	*/
	public static function getDayArray()
	{
		for($i=1;$i<=31;$i++)
		{
			if(strlen($i)==1)
				$val = "0".$i;
			else
				$val = $i;
			$dateArr[] = array("NAME"=>$i,"VALUE"=>$val);
		}
		return $dateArr;
	}

	/*
	This function returns the month array 
	*/
	public static function getMonthArray()
	{
		$monthArr = array(
			array("NAME" => "January", "VALUE" => "01"),
			array("NAME" => "February", "VALUE" => "02"),
			array("NAME" => "March", "VALUE" => "03"),
			array("NAME" => "April", "VALUE" => "04"),
			array("NAME" => "May", "VALUE" => "05"),
			array("NAME" => "June", "VALUE" => "06"),
			array("NAME" => "July", "VALUE" => "07"),
			array("NAME" => "August", "VALUE" => "08"),
			array("NAME" => "September", "VALUE" => "09"),
			array("NAME" => "October", "VALUE" => "10"),
			array("NAME" => "November", "VALUE" => "11"),
			array("NAME" => "December", "VALUE" => "12")
		);
		return $monthArr;
	}

	/*
	This function returns the year array 
	*/
	public static function getYearArray()
	{
		$todayYear = date("Y");
		for($i=2010;$i<=$todayYear+2;$i++)
		{
                    	$yearArr[] = array("NAME"=>$i,"VALUE"=>$i);
		}
		return $yearArr;
	}

	/*
	This function returns the dates to be shown on template on the basis of a start date and end date
	@param - start date, end date.... start date should not be greater than end date and the difference should not be more than 45 days
	*/
	public static function getDateArrayForTemplate($start_dt,$end_dt)
	{
		$daysGap = ceil((strtotime($end_dt)-strtotime($start_dt))/(24*60*60));
		if(!$start_dt || !$end_dt || $start_dt>$end_dt || $daysGap>45)
			return null;
		for($i=0; $i<=$daysGap; $i++){
			$ddarr[$start_dt] = date('j', strtotime($start_dt));
			$start_dt = date('Y-m-d', strtotime('+1 day', strtotime($start_dt)));
		}
		return $ddarr;
	}

	public static function generateDateDataForRange($st, $end){
		$dateArr = array();
		$monthArr =  array( 1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',);
		for($i=$st;$i<=$end;$i++){
			for($j=1;$j<=12;$j++){
				for($k=1;$k<=31;$k++){
					if(date('L', strtotime("$i-1-1")) && $j == 2 && $k == 29){
						$dateArr[$i][$monthArr[$j]] = $k;
						break;
					} elseif ($j == 2 && $k == 28){
						$dateArr[$i][$monthArr[$j]] = $k;
						break;
					} elseif ($j < 8 && $j%2 != 0 && $k == 31) {
						$dateArr[$i][$monthArr[$j]] = $k;
						break;
					} elseif ($j >= 8 && $j%2 == 0 && $k == 31){
						$dateArr[$i][$monthArr[$j]] = $k;
						break;
					} elseif ($j < 8 && $j%2 == 0 && $k == 30) {
						$dateArr[$i][$monthArr[$j]] = $k;
						break;
					} elseif ($j >= 8 && $j%2 != 0 && $k == 30){
						$dateArr[$i][$monthArr[$j]] = $k;
						break;
					}
				}
			}
		}
		return $dateArr;
	}
} 
?>
