<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$pay_arrayfull = array(	"uscard3" => "24",
			"uscard6" => "35",
			"uscard12" => "59",
			"rscard3" => "750",
			"rscard6" => "1100",
			"rscard12" => "1800",
			"uscheque3" => "24",
			"uscheque6" => "35",
			"uscheque12" => "59",
			"rscheque3" => "750",
			"rscheque6" => "1100",
			"rscheque12" => "1800"
		);

$pay_arrayval = array(	"uscard3" => "27",
			"uscard6" => "41",
			"uscard12" => "68",
			"rscard3" => "850",
			"rscard6" => "1250",
			"rscard12" => "2050",
			"uscheque3" => "27",
			"uscheque6" => "41",
			"uscheque12" => "68",
			"rscheque3" => "850",
			"rscheque6" => "1250",
			"rscheque12" => "2050"
		);

$pay_erishta = array(	"uscard2" => "44",
			"uscard3" => "51",
			"uscard4" => "60",
			"uscard6" => "75",
			"uscard9" => "96",
			"uscardl" => "141",
			"rscard2" => "1495",
			"rscard3" => "1695",
			"rscard4" => "1995",
			"rscard6" => "2495",
			"rscard9" => "3195",
			"rscardl" => "4695",
		);

$pay_eclassified = array("uscard2" => "33",
			"uscard3" => "41",
			"uscard4" => "48",
			"uscard5" => "54",
			"uscard6" => "60",
			"uscard12" => "93",
			"rscard2" => "1195",
			"rscard3" => "1345",
			"rscard4" => "1524",
			"rscard5" => "1695",
			"rscard6" => "1895",
			"rscard12" => "2645",
		);

$pay_evalue = array(	"uscard2" => "60",
			"uscard3" => "66",
			"uscard4" => "78",
			"uscard6" => "93",
			"uscard9" => "119",
			"uscardl" => "153",
			"rscard2" => "1995",
			"rscard3" => "2195",
			"rscard4" => "2595",
			"rscard6" => "3095",
			"rscard9" => "3995",
			"rscardl" => "5095",
		);

//This variable is used to get rupee value for doller payment
//$DOL_CONV_RATE = 43; //prev value 45
?>
