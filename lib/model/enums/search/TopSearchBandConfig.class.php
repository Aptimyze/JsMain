<?php
//This class has configurabe variables for top search band
class TopSearchBandConfig
{
	public static $minAgeFemale = 18;
	public static $minAgeMale = 21;
	public static $maxAge = 70;
	public static $minDefaultAge = 21;
	public static $maxDefaultAge = 35;
	public static $minDefaultHeight = 1;
	public static $maxDefaultHeight = 37;
	public static $minDefaultIncome = "0";
	public static $maxDefaultIncome = 19;
	public static $femaleGenderValue = "F";
	public static $maleGenderValue = "M";
	public static $maleLabel = "Groom";
	public static $femaleLabel = "Bride";
	public static $doesNotMatterLabel = "Doesn't Matter";
	public static $doesNotMatterValue = "DONT_MATTER";
	public static $neverMarriedValue = "N";
	public static $marriedEarlierValue = "E";
	public static $mstatusArr = array("DONT_MATTER"=>"All","N"=>"Never Married","E"=>"Married Earlier");
	public static $countries = array(51,128,125,126,7,22);
	public static $cities = array('DE00','MH04','GU01','KA02','GU04','MP02','OR01','PH00','TN02','UP12','HA03','AP03','MP08','RA07','UP18','WB05','UP19','PU07','MH05','UP25','BI06','MH08','GU10');
	public static $countriesApp = array(128,125,126,7,22,51);
        public static $citiesApp = array('GU01','KA02','MP02','OR01','PH00','TN02','DE00','UP12','HA03','AP03','MP08','RA07','WB05','UP19','PU07','MH04','MH05','UP25','BI06','MH08');
	public static $citiesExcludeApp = array('GU01','KA02','GU04','MP02','OR01','PH00','TN02','UP12','HA03','AP03','MP08','RA07','UP18','WB05','UP19','PU07','MH05','UP25','BI06','MH08','GU10');
	public static $mumbaiRegion = "MH04,MH12,MH28,MH29";
	public static $mumbaiRegionLabel = "Mumbai Region";
	public static $ncrLabel = "Delhi NCR";
	public static $metroCities = array('DE00','MH04','WB05','TN02','KA02','AP03','MH08');
	public static $sectLabelReligions = array(2,3);
	public static $noIncomeLabel = "No Income";
	public static $allHindiLabel = "All Hindi";
	public static $searchFormDataLogicalChangeLatest = "2015-03-29 00:00:00";
	public static $religionAllCasteMapping = array("1"=>"14","2"=>"149","4"=>"154","3"=>"2","9"=>"173");
        public static $topCities = array('DE00','MH04','KA02','AP03','MH08','TN02','WB05');
}
?>
