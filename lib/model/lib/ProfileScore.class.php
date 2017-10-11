<?php
class ProfileScore
{
public static function getProfileScore($profileObj)
{
	if($profileObj)
	{
		$gender=$profileObj->getGENDER();
		$age = $profileObj->getAGE();
		$yourinfo = $profileObj->getYOURINFO();
		$relation = $profileObj->getRELATION();
		$photo = $profileObj->getHAVEPHOTO();
		$mtongue = $profileObj->getMTONGUE();
		$drink = $profileObj->getDRINK();
		$smoke = $profileObj->getSMOKE();
		$btype = $profileObj->getBTYPE();
		$manglik = $profileObj->getMANGLIK();
		$diet = $profileObj->getDIET();
		$horo = $profileObj->getSHOW_HOROSCOPE();
		$btime = $profileObj->getBTIME();
		$birth_city = $profileObj->getCITY_BIRTH();
		$city_res = $profileObj->getCITY_RES();
		$city = substr("$city_res", 0, 2);  
		$country = $profileObj->getCOUNTRY_RES();
		$family = $profileObj->getFAMILYINFO();
		$spouse = $profileObj->getSPOUSE();
		$job = $profileObj->getJOB_INFO();
		$sibling = $profileObj->getSIBLING_INFO();
		$father = $profileObj->getFATHER_INFO();

		if($gender == 'F')	
			$maxAge=52;
		else
			$maxAge=66;
		if($age >= $maxAge)
			$age=$maxAge;

		if($gender == 'F')
		{ 
			$s1=array("19" => "3",
				  "20" => "3",
				  "21" => "11",
				  "22" => "11",
				  "23" => "19",
				  "24" => "36",
				  "25" => "36",
				  "26" => "36",
				  "27" => "53",
				  "28" => "53",
				  "29" => "53",
				  "30" => "53",
				  "31" => "53",
				  "32" => "53",
				  "33" => "36",
				  "34" => "53",
				  "35" => "53",
				  "36" => "53",
				  "37" => "53",
				  "38" => "53",
				  "39" => "53",
				  "40" => "53",
				  "41" => "53",
				  "42" => "53",	
				  "43" => "36",
				  "44" => "36",
				  "45" => "36",
				  "46" => "36",
				  "47" => "36",
				  "48" => "36",
				  "49" => "36",
				  "50" => "36",
				  "51" => "19",
				  "52" => "19");
		}
		else
		{
			$s1=array("21" => "3",
				  "22" => "3",
				  "23" => "3",
				  "24" => "3",
				  "25" => "3",
				  "26" => "11",
				  "27" => "11",
				  "28" => "19",
				  "29" => "25",
				  "30" => "25",
				  "31" => "25",
				  "32" => "19",
				  "33" => "25",
				  "34" => "36",
				  "35" => "25",
				  "36" => "36",
				  "37" => "25",
				  "38" => "25",
				  "39" => "36",
				  "40" => "25",
				  "41" => "36",
				  "42" => "25",
				  "43" => "25",
				  "44" => "25",
				  "45" => "25",
				  "46" => "25",
				  "47" => "25",
				  "48" => "19",
				  "49" => "25",	
				  "50" => "25",
				  "51" => "36",
				  "52" => "36",
				  "53" => "25",
				  "54" => "19",
				  "55" => "25",
				  "56" => "19",
				  "57" => "25",	
				  "58" => "25",
				  "59" => "36",
				  "60" => "36",
				  "61" => "36",
				  "62" => "25",
				  "63" => "25",
				  "64" => "36",
				  "65" => "3",
				  "66" => "3");
		} 
		
		$s1=$s1[$age];
							//Calculating the Value of S2 (Character Length)
		$length = strlen($yourinfo) + strlen($job) + strlen($spouse) + strlen($family) + strlen($father) + strlen($sibling) ;

		if ($length <= 200){
			$s2 = 4;
		}elseif ($length > 200 && $length <= 400){
			$s2 = 21;
		}elseif ($length > 400 && $length <= 600){
			$s2 = 50;
		}elseif ($length > 600){
			$s2 = 100;
		}
							// Calculating the Value of S3 (Profile Posted By)
		if ($relation == 1){
			$s3 = 19;
		}elseif ($relation == 2){
			$s3 = 46;
		}elseif ($relation == 3){
			$s3 = 58;
		}elseif ($relation == 4){
			$s3 = 6;
		}elseif ($relation == 5){
			$s3 = 3;
		}elseif ($relation == 6){
			$s3 = 12;
		}elseif ($relation == ''){
			$s3 = 3;
		}

							// Calculating the Value of S4 (Photo)
		if($photo != 'N' && $photo != ''){
			$s4 = 66;
		}else{
			$s4 = 40;                     // Have to Change to 12 in case of New Registartion Page
		}

			
			$s5 = ProfileScore::s5($city,$mtongue);

							//Calculate value of 'n'
			if($smoke !='' )
				$score_smoke = 1;
			else
				$score_smoke = 0;
			if($btype != '')
				$score_btype = 1;
			else
				$score_btype = 0;                   
			if($drink != '')
				$score_drink = 1;
			else
				$score_drink = 0;
			if($maglik != '')
				$score_manglik = 1;
			else
				$score_manglik = 0;
			if($diet != '')
				$score_diet = 1;
			else
				$score_diet = 0;
			if($horo != '' && $btime != '' && $birth_city != '')
				$score_horo = 1;
			else
				$score_horo = 0;

			$sum = $score_smoke + $score_btype + $score_drink + $score_manglik + $score_diet + $score_horo;

			if($sum  < 4)
				$n = 0;
			else
				$n = 1;
	}
						// Calculating the Seriousness Score
			
			$nri = array('113','59','40','42','81','112','103','126','128','22','7','82');
			$flag=false;
			for($i=0;$i<12;$i++)
			{
				if($nri[$i]==$country)
				{
					$flag=true;
					break;
				}
			}
			
			if($flag) {
				$m = ($s1 + $s2 + $s3 + $s4) / 4 ;
			}
			else {
				$m = (($s1 + $s2 + $s3 + $s4 +$s5) * $n) / 5 ;
			}
			
			$l = 25;

			if($flag) {
				$min_m = 5.5;
				$max_m = 69.25;
				$a = (($m - $min_m) / ($max_m - $min_m));
				$SAB = round((600 / $l) * round($l * $a));
				return $SAB;
			}
			else {
				$min_m = 6.4;
				$max_m = 61.8;
				$a = (($m - $min_m) / ($max_m - $min_m));
				$SAB = round((600 / $l) * round($l * $a));
				return $SAB;
			}
			
}	
							// Calculating the Value of S5 (Community X FISH)
		public static function cityZone($city)
		{
			$zone = array( 	"AP" => "S",
					"AR" => "E",
					"AS" => "E",
					"BI" => "E",
					"CH" => "N",
					"DD" => "W",
					"DE" => "N",
					"DN" => "W",
					"GO" => "W",
					"GU" => "W",
					"HA" => "N",
					"HP" => "N",
					"JH" => "E",
					"JK" => "N",
					"KA" => "S",
					"KE" => "S",
					"LA" => "S",
					"MA" => "E",
					"ME" => "E",
					"MH" => "W",
					"MI" => "E",
					"MP" => "N",
					"NA" => "E",
					"OR" => "E",
					"PH" => "N",
					"PO" => "S",
					"PU" => "N",
					"RA" => "W",
					"SI" => "E",
					"TN" => "S",
					"TR" => "E",
					"UP" => "N",
					"UT" => "N",
					"WB" => "E");

				return $zone[$city];
		}	
		
		public static function comnZone($mtongue)
		{
			$comn = array(  "7"  => "N",
					"10" => "N",			 
					"13" => "N",
					"14" => "N",
					"15" => "N",
					"27" => "N",
					"28" => "N",
					"30" => "N",
					"33" => "N",
					"8"  => "W",
					"9"  => "W",
					"11" => "W",
					"12" => "W",
					"19" => "W",
					"20" => "W",
					"30" => "W",
					"34" => "W",
					"2"  => "S",
					"3"  => "S",
					"16" => "S",
					"17" => "S",
					"18" => "S",
					"26" => "S",
					"31" => "S",
					"4"  => "E",
					"5"  => "E",
					"6"  => "E",
					"21" => "E",
					"22" => "E",
					"23" => "E",
					"24" => "E",
					"25" => "E",
					"29" => "E",
					"32" => "E");
				
				return $comn[$mtongue];
		}

		public static function s5($city,$mtongue)
		{	
			$czone = ProfileScore::cityZone($city);
			$comnzone = ProfileScore::comnZone($mtongue);
			
			$FCT1 = 32;
			$FCT2 = 25;
			$FCT3 = 15;

			if($mtongue == 6 && $czone != $comnzone)
				return $FCT1;
			elseif($mtongue == 6 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 7 && $czone != $comnzone)
				return $FCT1;
			elseif($mtongue == 7 && $czone == $comnzone) 
				return $FCT1;
			if($mtongue == 12 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 12 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 14 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 14 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 19 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 19 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 33 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 33 && $czone == $comnzone)
				return $FCT1;
			if($mtongue == 16 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 16 && $czone == $comnzone) 
				return $FCT3;
			if($mtongue == 34 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 34 && $czone == $comnzone)
				return $FCT1;
			if($mtongue == 17 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 17 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 20 && $czone != $comnzone) 
				return $FCT1;
			elseif($mtongue == 20 && $czone == $comnzone)
				return $FCT1;
			if($mtongue == 27 && $czone != $comnzone)
				return $FCT1;
			elseif($mtongue == 27 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 28 && $czone != $comnzone)
				return $FCT1;
			elseif($mtongue == 28 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 30 && $czone != $comnzone)
				return $FCT1;
			elseif($mtongue == 30 && $czone == $comnzone)
				return $FCT1;
			if($mtongue == 34 && $czone == '' && $comnzone == '')
				return $FCT1;
			elseif($mtongue == 34 && $czone != '' && $comnzone != '')
				return $FCT3;
			if($mtongue == 3 && $czone == '' && $comnzone == '')  
				return $FCT2;
			elseif($mtongue == 3 && $czone != '' && $comnzone != '')
				return $FCT3;
			if($mtongue == 17 && $czone == '' && $comnzone == '')
				return $FCT2;
			elseif($mtongue == 17 && $czone != '' && $comnzone != '')
				return $FCT3;
			if($mtongue == 20 && $czone == '' && $comnzone == '')  
				return $FCT2;
			elseif($mtongue == 20 && $czone != '' && $comnzone != '')
				return $FCT3;
			if($mtongue == 13 && $czone != $comnzone)
				return $FCT2;
			elseif($mtongue == 13 && $czone == $comnzone) 
				return $FCT3;
			if($mtongue == 25 && $czone != $comnzone)
				return $FCT2;
			elseif($mtongue == 25 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 10 && $czone != $comnzone)
				return $FCT2;
			elseif($mtongue == 10 && $czone == $comnzone)
				return $FCT2;
			if($mtongue == 29 && $czone != $comnzone) 
				return $FCT2;
			elseif($mtongue == 29 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 31 && $czone != $comnzone) 
				return $FCT2;
			elseif($mtongue == 31 && $czone == $comnzone)
				return $FCT3;
			if($mtongue == 16 && $czone == $comnzone)
				return $FCT2;
			elseif($mtongue == 16 && $czone != $comnzone)
				return $FCT3;
			if($mtongue == 27 && $czone == $comnzone)
				return $FCT2;
			elseif($mtongue == 27 && $czone != $comnzone)
				return $FCT3;
			if($mtongue == 19 && $czone == $comnzone)
				return $FCT2;
			elseif($mtongue == 19 && $czone != $comnzone)
				return $FCT3;
		}
}
?>
