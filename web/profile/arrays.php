<?php
	 $FAMILY_VALUES=array("0"=>"-",
                           "1"=>"Conservative",
                           "2"=>"Moderate",
                           "3"=>"Liberal",
			   "4"=>"Orthodox"
                          );

	$FAMILY_TYPE=array("0"=>"-",
			   "1"=>"Joint Family",
			   "2"=>"Nuclear Family",
			   "3"=>"Others"
			  );
	/*$FAMILY_STATUS=array("0"=>"-",
			     "1"=>"Middle Class",
		             "2"=>"Upper Middle Class",
			     "3"=>"Rich/Affluent"
			    );*/

	$FAMILY_STATUS=array("3"=>"Rich/Affluent",
		             "2"=>"Upper Middle Class",
			     "1"=>"Middle Class"
			    );

	$GENDER=array("M" => "Male",
			"F" => "Female");
			
	$MANGLIK=array("M" => "Yes",
			"A" => "Angshik (partial manglik)",
			"N" => "No",
			"D" => "Don't know");

	$MANGLIK_LABEL=array("M" => "Manglik",
			"N" => "Non Manglik",
			"D" => "Don't know",
			"A" => "Angshik (partial manglik)");
			
	/*$MSTATUS=array("N" => "Never Married",
			"W" => "Widowed",
			"D" => "Divorced",
			"S" => "Separated",
			"O" => "Other",
			"A" =>"Annulled");*/

	/*
	$MSTATUS=array("N" => "Never Married",
			"M" => "Married",
                        "AD" => "Awaiting Divorce",
                        "D" => "Divorced",
			"W" => "Widowed",
                        "A" =>"Annulled");
	*/
	//Separated renamed as Awaiting Divorce	
	$MSTATUS=array("N" => "Never Married",
			"M" => "Married",
                        "S" => "Awaiting Divorce",
                        "D" => "Divorced",
		//	"S" => "Separated",
			"O" => "Other",
			"W" => "Widowed",
                        "A" =>"Annulled");
			
	$CHILDREN=array("N" => "No",
			"YT" => "Yes, living together",
			"YS" => "Yes, living separately"
			);
			
	$BODYTYPE=array("1" => "Slim",
			"2" => "Average",
			"3" => "Athletic",
			"4" => "Heavy");
			
	$COMPLEXION=array("1" => "Very Fair",
			"2" => "Fair",
			"3" => "Wheatish",
			"4" => "Wheatish Brown",
			"5" => "Dark");
			
	$DIET=array("V" => "Vegetarian",
			"N" => "Non Vegetarian",
			"J" => "Jain",
			"E" => "Eggetarian");
			
	$SMOKE=array("Y" => "Yes",
			"N" => "No",
			"O" => "Occasionally");
			
	$DRINK=array("Y" => "Yes",
			"N" => "No",
			"O" => "Occasionally");
			
	$RSTATUS=array("1" => "Citizen",
			"2" => "Permanent Resident",
			"3" => "Work Permit",
			"4" => "Student Visa",
			"5" => "Temporary Visa");
			
	$HANDICAPPED=array("N" => "None",
			"1" => "Physically Handicapped from birth",
			"2" => "Physically Handicapped due to accident",
			"3" => "Mentally Challenged from birth",
			"4" => "Mentally Challenged due to accident");
			
	$RELATIONSHIP=array("1" => "Self",
				"2" => "Parent",
				"3" => "Sibling",
				"4" => "Relative/Friend",
				"5" => "MarriageBureau",
				"6" => "Other");
	global $MESSENGER_CHANNEL;			
	$MESSENGER_CHANNEL=array("1" => "Yahoo",
				"2" => "MSN",
				"3" => "Skype",
				//"4" => "Others",
				"5" => "ICQ",
				"6" => "Google Talk",
				"7" => "Rediff Bol");
		
	$RESIDENCY_STATUS=array("1" => "Citizen",
			"2" => "Permanent Resident",
			"3" => "Work Permit",
			"4" => "Student Visa",
			"5" =>"Temporary Visa");

	$BLOOD_GROUP = array("1" => "A+",
		"2" => "A-",
		"3" => "B+",
		"4" => "B-",
		"5" => "AB+",
		"6" => "AB-",
		"7" => "O+",
		"8" => "O-");

	$NATURE_HANDICAP = array("1" => "Cripple",
		"2" => "Hearing Impaired",
		"3" => "Visually Impaired",
		"4" => "Speech Impaired",
		"5" => "Others");

	$WORK_STATUS = array("1" => "Not Working",
		"2" => "Employed",
		"3" => "Entrepreneur",
		"4" => "Consultant",
		"5" => "Student",
		"6" => "Academia",
		"7" => "Defence",
		"8" => "Independent Worker/Freelancer");

	$PHOTO_PRIVACY = array("A" => "Visible to All",
		"C" => "Visible to contacted and accepted members",);
		
	/*$PHOTO_PRIVACY = array("A" => "Visible to All",
		"C" => "Visible to contacted and accepted members",
		"H" => "Visible to none");*/

	$NAMAZ = array("1" => "5 times",
		"2" => "Only jummah",
		"3" => "Not regular",
		"4" => "During ramadan",
		"5" => "None");

	$FASTING = array("1" => "Ramadan & Sunnah",
		"2" => "Ramadan",
		"3" => "None");

	$UMRAH_HAJJ = array("1" => "Umrah/Hajj",
		"2" => "Umrah",
		"3" => "None");

	$QURAN = array("1" => "Daily",
		"2" => "Occasionally",
		"3" => "On Fridays",
		"4" => "None");

	$SUNNAH_BEARD = array("1" => "Always",
		"2" => "After Nikah",
		"3" => "None");

	$SUNNAH_CAP = array("1" => "Always",
		"2" => "During prayer",
		"3" => "Occasionally",
		"4" => "Only at functions",
		"5" => "None");

	$SAMPRADAY = array("1" => "Murthipujak",
		"2" => "Sthanakwas",
		"3" => "Terapanth");

	$NUMBER_OWNER = array("1"=>"Bride",
			"2" => "Groom",
			"3" => "Parent",
			"4" => "Son",
			"5" => "Daughter",
			"6" => "Sibling",
			"7" => "Other");
	$MATHTHAB_SUNNI = array("1" => "Hanafi",
			 "2" => "Hanbali",
			 "3" => "Maliki",
			 "4" => "Shafi".chr(39)."I");

 $MATHTHAB_SHIA = array("5" => "Ismaili",
			 "6" => "Ithna- ashariyyah",
			 "7" => "Zaidi",
			 "8" => "Dawoodi Bohra");
	$MATHTHAB= array("1"=>"Hanafi",
                        "2"=>"Hanbali",
                        "3"=>"Maliki",
                        "4"=>"Shafi".chr(39)."I",
                        "5"=>"Ismaili",
                        "6"=>"Ithna ashariyyah",
                        "7"=>"Zaidi",
                        "8"=>"Dawoodi Bohra"
                        );
	$SUBSCRIPTION= array("F" => "Of Paid members only",
				"70"=>"Of Paid members only",
				"D"=>"Whose contact information is visible",
				"68"=>"Whose contact information is visible",
				"O"=>"Of Match point members only",
				"79"=>"Of Match point members only",
				"Q"=>"Of Match point & contact information is visible members only",//wont be display
				"81"=>"Of Match point & contact information is visible members only"//wont be display
				);

	$ORIGINAL_SUBSCRIPTION= array("70"=>"F",
					"68"=>"D",
					"79"=>"O");

	$EDU_CLUSTER_ARRAY = array("P"=>array(3,4,6,7,8,10,13,14,16,17,18,19,20,21,37),
				   "D"=>array(17,19,25,26,31,32),
				   "E"=>array(3,13,16,18,29,34,35),
				   "A"=>array(7,8,10,11,12,13,14,15,16,18,19,20,21,29,31,36,41,42),
				   "G"=>array(1,2,3,4,5,6,14));

        $EDU_CLUSTER_LAYER_ARRAY = array("P"=>array(3,4,6,7,8,10,13,14,16,17,18,19,20,21,25,26,29,31,32,34,35,37),
                                         "A"=>array(11,12,15,41,42),
                                         "G"=>array(1,2,5,33,38,39,40));

        $OCC_CLUSTER_ARRAY = array("B"=>array(13,52),
                                   "I"=>array(17,20,21,30,59),
                                   "M"=>array(24,53,57),
                                   "D"=>array(34),
                                   "MS"=>array(2,4,5,8,9,10,11,14,15,28,49,50,54),
                                   "T"=>array(31,58,60),
                                   "F"=>array(1,7),
                                   "A"=>array(3,12,16,19,22,23,29,56),
                                   "MA"=>array(25,26,27,30,61,62),
                                   "G"=>array(33,35),
                                   "O"=>array(6,18,32,36,37,38,39,40,41,42,43,44,45,46,47,48,51,55));

	$OCC_GROUP_ARRAY= array("B","I","M","D","MS","T","F","A","MA","G");
	
	
	$OCC_CLUSTER_REVERSE_ONE_MATCH_ARRAY = array("13"=>array('B'),
					             "17"=>array('I'),
					             "24"=>array('M'),
					             "34"=>array('D'),
					             "2"=>array('MS'),
					             "31"=>array('T'),
					             "1"=>array('F'),
					             "12"=>array('A'),
					             "25"=>array('MA'),
					             "33"=>array('G'),
					             "42"=>array('O'));
	$OCC_CLUSTER_REVERSE_GROUP_ARRAY= array(13,15,17,20,21,24,34,2,4,5,8,9,10,11,14,28,31,1,7,3,12,16,19,22,23,29,25,26,27,30,33,35,52,59,53,57,49,50,54,58,60,56,61,62);

	//8,9,10,11,12,13,14,21
        $INC_CLUSTER_ARRAY = array("M0"=>array(15),
                                   "M1"=>array(1,2,3,4,8,15),
                                   "M2"=>array(15,5,6,8,9,10,11,12,13,21,14,16,17,18,20,22,23),
                                   "M3"=>array(16,17,18,20,22,23,9,10,11,12,13,21,14),
                                   "F0"=>array(1,2,3,4,5,6,8,9,15),
                                   "F1"=>array(9,10,11,12,13,21,14,16,17,18,20,22,23),
                                   "F2"=>array(10,11,12,13,21,14,17,18,20,22,23),
                                   "F3"=>array(11,12,13,21,14,18,20,22,23));

        $INC_CLUSTER_RANGE_ARRAY=array("M0"=>array(0,0,0,0),
                                       "M1"=>array(0,4,0,12),
                                       "M2"=>array(4,19,0,19),
                                       "M3"=>array(6,19,12,19),
                                       "F0"=>array(0,6,0,13),
                                       "F1"=>array(6,19,12,19),
                                       "F2"=>array(7,19,13,19),
                                       "F3"=>array(8,19,14,19));



	$income_map=array(
"2" => "< Rs. 1Lac",
"3" => "Rs. 1 - 2Lac",
"4" => "Rs. 2 - 3Lac",
"5" => "Rs. 3 - 4Lac",
"6" => "Rs. 4 - 5Lac",
"8" => "< $ 25K",
"9" => "$ 25 - 40K",
"10" => "$ 40 - 60K",
"11" => "$ 60 - 80K",
"12" => "$ 80K - 1lac",
"13" => "$ 1 - 1.5lac",
"21" => "$ 1.5 - 2lac",
"14" => "> $ 2lac",
"15" => "No Income",
"16" => "Rs. 5 - 7.5lac",
"17" => "Rs. 7.5 - 10lac",
"18" => "Rs. 10 - 15lac",
"20" => "Rs. 15 - 20lac",
"22" => "Rs. 20 - 25lac",
"23" => "Rs. 25 - 35lac",
"24" => "Rs. 35 - 50lac",
"25" => "Rs. 50 - 70lac",
"26" => "Rs. 70lac - 1cr",
"27" => "> Rs. 1cr",
);

$INCOME_NEW_SUGG_ALGO[1] = "Under Rs.50K p.a.";
$INCOME_NEW_SUGG_ALGO[2] = "Rs.50K-1 lakh p.a.";
$INCOME_NEW_SUGG_ALGO[3] = "Rs.1-2 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[4] = "Rs.2-3 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[5] = "Rs.3-4 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[6] = "Rs.4-5 lakhs p.a.";
//$INCOME_NEW_SUGG_ALGO[7] = "Rs.5 lakhs and above  p.a."; //no entries for this index are present on the search table
$INCOME_NEW_SUGG_ALGO[8] = "Under $25K p.a.";
$INCOME_NEW_SUGG_ALGO[9] = "$25-40K p.a.";
$INCOME_NEW_SUGG_ALGO[10] = "$40K-60K p.a.";
$INCOME_NEW_SUGG_ALGO[11] = "$60K-80K p.a.";
$INCOME_NEW_SUGG_ALGO[12] = "$80K-100K p.a.";
$INCOME_NEW_SUGG_ALGO[13] = "$100K-150K p.a.";
$INCOME_NEW_SUGG_ALGO[21] = "$150K 200K p.a.";
$INCOME_NEW_SUGG_ALGO[14] = "> $200K p.a.";
$INCOME_NEW_SUGG_ALGO[15] = "No Income";
$INCOME_NEW_SUGG_ALGO[16] = "Rs.5-7.5 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[17] = "Rs.7.5-10 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[18] = "Rs.10-15 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[20] = "Rs.15-20 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[22] = "Rs.20-25 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[23] = "Rs.25-35 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[24] = "Rs.35-50 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[25] = "Rs.50-70 lakhs p.a.";
$INCOME_NEW_SUGG_ALGO[26] = "Rs.70lakhs-1 crore p.a.";
$INCOME_NEW_SUGG_ALGO[27] = "> Rs.1 crore p.a.";
//$INCOME_NEW_SUGG_ALGO[19] = "Max Income";


	$AGE_GROUP_SUGG_ALGO=array("MALE"=>array("21" => "21,25",
					"22" => "21,25",
					"23" => "22,25",
					"24" => "22,26",
					"25" => "23,27",
					"26" => "24,28",
					"27" => "25,29",
					"28" => "26,30",
					"29" => "26,30",
					"30" => "28,32",
					"31" => "28,33",
					"32" => "29,35",
					"33" => "30,36",
					"34" => "30,37",
					"35" => "31,39",
					"MAX" => "32,70"),
				"FEMALE"=>array("18" => "18,22",
					"19" => "18,22",
					"20" => "18,23",
					"21" => "18,24",
					"22" => "19,25",
					"23" => "20,26",
					"24" => "21,27",
					"25" => "22,28",
					"26" => "23,29",
					"27" => "24,29",
					"28" => "25,30",
					"29" => "26,32",
					"30" => "27,33",
					"31" => "28,34",
					"32" => "29,35",
					"33" => "30,36",
					"MAX" => "31,70"));
	$ALL_HINDI_MTONGUES=array(10,19,33,7,13,28);
	$ALL_MARRIED_MSTATUS=array('M','S','D','O','W','A');
?>
