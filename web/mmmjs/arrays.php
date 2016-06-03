<?php

//*****************Array of Fields whose Value is there in the array******************

$from_array = array(
	"GENDER" => array("M" => "Male",
			"F" => "Female"),
			
	"MANGLIK" => array("M" => "Manglik",
			 "N" => "Non Manglik",
			"D" => "Don't know"),
			
	"MSTATUS"=>array("N" => "Never Married",
			"W" => "Widowed",
			"D" => "Divorced",
			"S" => "Separated",
			"O" => "Other"),
			
	"HAVECHILD"=>array("N" => "No",
			"YT" => "Yes, living together",
			"YS" => "Yes, living separately",
			"Y" => "Yes"),
			
	"BTYPE"=>array("1" => "Slim",
			"2" => "Average",
			"3" => "Athletic",
			"4" => "Heavy"),
			
	"COMPLEXION"=>array("1" => "Very Fair",
			"2" => "Fair",
			"3" => "Wheatish",
			"4" => "Wheatish Brown",
			"5" => "Dark"),
			
	"DIET"=>array("V" => "Vegetarian",
			"N" => "Non Vegetarian",
			"J" => "Jain"),
			
	"SMOKE"=>array("Y" => "Yes",
			"N" => "No",
			"O" => "Occasionally"),
			
	"DRINK"=>array("Y" => "Yes",
			"N" => "No",
			"O" => "Occasionally"),
			
	"RES_STATUS"=>array("1" => "Citizen",
			"2" => "Permanent Resident",
			"3" => "Work Permit",
			"4" => "Student Visa",
			"5" => "Temporary Visa"),
			
	"HANDICAPPED"=>array("N" => "None",
			"1" => "Physically Handicapped from birth",
			"2" => "Physically Handicapped due to accident",
			"3" => "Mentally Challenged from birth",
			"4" => "Mentally Challenged due to accident"),
			
	"RELATION"=>array("1" => "Self",
				"2" => "Parent/Guardian",
				"3" => "Sibling",
				"4" => "Friend",
				"5" => "Marriage Bureau",
				"6" => "Other"),
				
	"MESSENGER_CHANNEL"=>array("1" => "Yahoo",
				"2" => "MSN",
				"3" => "Skype",
				"4" => "Others",
				"5" => "ICQ"),


	"INCOME"=>array("1" => "Under Rs 50000",
			"2" => "Rs 50001 to 100000",
			"3" => "Rs 100001 to 200000",
			"4" => "Rs 200001 to 300000",
			"5" => "Rs 300001 to 400000",
			"6" => "Rs 400001 to 500000",
			"7" => "Rs 500001 and above",
			"8" => "Under $25000",
			"9" => "$25001 to 50000",
			"10" => "$50001 to 75000",
			"11" => "$75001 to 100000",
			"12" => "$100001 to 150000",
			"13" => "$150001 to 200000",
			"14" => "$200001 and above",
			"15" => "No Income"),

	"SUBSCRIPTION"=>array(
			"" => "Free Member",
			"F" => "Full Member",
			"F,B" =>"Value Added Member"),

	"INCOMPLETE" =>array(
			"N" => "COMPLETE",
			"Y" => "INCOMPLETE"),

	"FAMILY_BACK" =>array(		
		"1" => "Business Family",
		"2" => "Service - Private",
		"3" => "Service - Govt./PSU",
		"4" => "Army/Armed Forces",
		"5" => "Civil Services")

);

//$GENDER1="GENDER";
//$M1="M";
//echo $from_array[$GENDER1][$M1];
	
$from_direct=array(
		"PROFILEID",
		"USERNAME",
		"PASSWORD",
		"EMAIL",
		"CITY_BIRTH",
		"NTIMES",
		"AGE",
		"GOTHRA",
		"NAKSHATRA",
		"PHONE_RES",
		"PHONE_MOB",
		"YOURINFO",
		"FAMILYINFO",
		"SPOUSE",
		"EDUCATION",	
		"SUBCASTE",
		"BTIME"
);

$from_date=array(
		"DTOFBIRTH",
		"ENTRY_DT",
		"MOD_DT",
		"SUBSCRIPTION_EXPIRY_DT",
		"ACTIVATE_ON",
		"LAST_LOGIN_DT"
);


$from_table=array(
		"RELIGION" => "RELIGION",
		"CASTE" => "CASTE",
		"MTONGUE" => "MTONGUE",
		"OCCUPATION" => "OCCUPATION",
		"COUNTRY_RES" => "COUNTRY",
		"CITY_RES" => "CITY_INDIA",
		"HEIGHT" => "HEIGHT",
		"EDU_LEVEL" => "EDUCATION_LEVEL",
		"COUNTRY_BIRTH" => "COUNTRY"
);


function my_format_date($day,$month,$year)
{
        if($month=="01" || $month=="1")
                $month="January";
        elseif($month=="02" || $month=="2")
                $month="February";
        elseif($month=="03" || $month=="3")
                $month="March";
        elseif($month=="04" || $month=="4")
                $month="April";
        elseif($month=="05" || $month=="5")
                $month="May";
        elseif($month=="06" || $month=="6")
                $month="June";
        elseif($month=="07" || $month=="7")
                $month="July";
        elseif($month=="08" || $month=="8")
                $month="August";
        elseif($month=="09" || $month=="9")
                $month="September";
        elseif($month=="10")
                $month="October";
        elseif($month=="11")
                $month="November";
        else
                $month="December";
                                                                                                 
        if(strlen($day)==1)
                $day= "0" . $day;
                                                                                                 
        return $month . " " . $day . ", " . $year;
}
                                                                                                 


function label_select($columnname,$value,$database="")
{
        if($database=="")
                $database="newjs";
                                                                                                 
echo    $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";
        $res = mysql_query($sql) or die("$sql".mysql_error());//logError("error",$sql) ;
        $myrow= mysql_fetch_array($res);
        return $myrow['LABEL'];
}


?>
