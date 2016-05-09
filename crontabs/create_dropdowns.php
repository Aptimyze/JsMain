<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include_once("config.php");
	chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
	$fp=fopen(JsConstants::$docRoot."/commonFiles/dropdowns.php","w");
	
	if(!$fp)
	{
		exit;
	}
	
	fwrite($fp,"<?php\r\n");
	
	$db=connect_db();

	//To genrate array of incomes less and greater than a particular value
	$sql="SELECT SORTBY,VALUE FROM newjs.INCOME WHERE VISIBLE='Y' ORDER BY SORTBY";
	$res= mysql_query_decide($sql) or die(mysql_error());
	$income_arr=array(2,3,4,5,6,8,9,10,11,12,13,14,16,17,18,20,21,22,23,24,25,26,27);
	$arr[]= 15;
	$more_val=implode("','",$income_arr);
	fwrite($fp,"\$INCOME[\"15 \"]['LESS']=\"'" . $less_val . "'\";\r\n");
        fwrite($fp,"\$INCOME[\"15\"]['MORE']=\"'" . $more_val . "'\";\r\n");
	while($row=mysql_fetch_array($res))
	{
		$val=$row['VALUE'];
		$income_less[$val]=$arr;
		$less_val=implode("','",$arr);
        	$arr[]=$val;
		$income_more[$val]=array_diff($income_arr,$arr);
		$more_val=implode("','",$income_more[$val]);
		fwrite($fp,"\$INCOME[\"" . $val . "\"]['LESS']=\"'" . $less_val . "'\";\r\n");
        	fwrite($fp,"\$INCOME[\"" . $val . "\"]['MORE']=\"'" . $more_val . "'\";\r\n");
	}

		
	$sql="select VALUE,LABEL from CASTE";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$CASTE_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);

	//CASTE GROUP CACHE STARTS******************************
	$statement = "SELECT CG.GROUP_VALUE AS GROUP_VALUE,CG.CASTE_VALUE AS CASTE_VALUE FROM newjs.CASTE_GROUP_MAPPING CG, newjs.CASTE C WHERE CG.CASTE_VALUE = C.VALUE ORDER BY CG.GROUP_VALUE,C.SORTBY";
	$result = mysql_query($statement);
	$casteGroupArray = array();
	while($row = mysql_fetch_array($result))
	{
	        $casteGroupArray[$row["GROUP_VALUE"]] = $casteGroupArray[$row["GROUP_VALUE"]].$row["CASTE_VALUE"].",";
	}
	mysql_free_result($result);

	foreach ($casteGroupArray as $k=>$v)
	{
	        fwrite($fp,"\$CASTE_GROUP_ARRAY[\"".$k."\"] = \"".rtrim($v,",")."\";\r\n");
	}
	//CASTE GROUP CACHE ENDS**********************************

	$sql="select VALUE,LABEL from OCCUPATION";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$OCCUPATION_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);
	
	$sql="select VALUE,LABEL from HEIGHT";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$HEIGHT_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);
	
	$sql="SELECT VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$CITY_INDIA_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);
	
	$sql="SELECT VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$CITY_USA_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);
	
	$sql="select VALUE,LABEL from COUNTRY_NEW";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$COUNTRY_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);
	
	$sql="select VALUE,LABEL from RELIGION";
	$result=mysql_query($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fp,"\$RELIGIONS[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
	}
	
	mysql_free_result($result);

	$sql="select VALUE,LABEL from MTONGUE";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$MTONGUE_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="SELECT GROUP_CONCAT(VALUE ORDER BY SORTBY_NEW SEPARATOR ',') AS VALS,REGION FROM newjs.MTONGUE GROUP BY REGION ORDER BY REGION DESC";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$MTONGUE_REGION_DROP[\"" . $myrow["REGION"] . "\"]=\"" . $myrow["VALS"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="SELECT GROUP_CONCAT(VALUE ORDER BY SORTBY_NEW SEPARATOR ',') AS VALS,REGION FROM newjs.MTONGUE WHERE REG_DISPLAY!='N' GROUP BY REGION ORDER BY REGION DESC";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$MTONGUE_REGION_REGISTRATION_DROP[\"" . $myrow["REGION"] . "\"]=\"" . $myrow["VALS"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,LABEL from EDUCATION_LEVEL_NEW ORDER BY SORTBY";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$EDUCATION_LEVEL_NEW_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,LABEL from EDUCATION_GROUPING ORDER BY SORTBY";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$EDUCATION_GROUPING_DROP[" . $myrow["VALUE"] . "]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,LABEL,MIN_LABEL,MAX_LABEL,MAX_VALUE,MIN_VALUE from INCOME";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$INCOME_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
                fwrite($fp,"\$INCOME_MIN_DROP[\"" . $myrow["MIN_VALUE"] . "\"]=\"" . $myrow["MIN_LABEL"] . "\";\r\n");
                fwrite($fp,"\$INCOME_MAX_DROP[\"" . $myrow["MAX_VALUE"] . "\"]=\"" . $myrow["MAX_LABEL"] . "\";\r\n");
                
        }

        mysql_free_result($result);
	
	$sql="select VALUE,LABEL from EDUCATION_LEVEL";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$EDUCATION_LEVEL_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,LABEL from FAMILY_BACK";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$FAMILY_BACK_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,LABEL from MOTHER_OCC";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$MOTHER_OCC_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,SMALL_LABEL from CASTE";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$CASTE_DROP_SMALL[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["SMALL_LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	$sql="select VALUE,SMALL_LABEL from MTONGUE";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
			if($myrow["SMALL_LABEL"]=='Rajasthani')
				$myrow["SMALL_LABEL"]="Rajasthani/Marwari"; 
             fwrite($fp,"\$MTONGUE_DROP_SMALL[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["SMALL_LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);
	
	 $sql="select VALUE,LABEL from CITY_NEW WHERE TYPE!='STATE' ORDER BY SORTBY";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fp,"\$CITY_DROP[\"" . $myrow["VALUE"] . "\"]=\"" . $myrow["LABEL"] . "\";\r\n");
        }

        mysql_free_result($result);

	//added by anand
	$statement = "select NAME,VALUE from newjs.NAKSHATRA_MATCHASTRO";
	$result = mysql_query($statement);
	while ($row = mysql_fetch_array($result))
	{
		fwrite($fp,"$"."nakshatra_matchastro[\"".$row['NAME']."\"] = ".$row['VALUE'].";\n");
	}

	$statement = "select NAME,VALUE from newjs.RASHI_MATCHASTRO";
	$result = mysql_query($statement);
	while ($row = mysql_fetch_array($result))
	{
		fwrite($fp,"$"."rashi_matchastro[\"".$row['NAME']."\"] = ".$row['VALUE'].";\n");
	}

	$statement = "select NAME,VALUE from newjs.SUNSIGN_MATCHASTRO";
	$result = mysql_query($statement);
	while ($row = mysql_fetch_array($result))
	{
		fwrite($fp,"$"."sunsign_matchastro[\"".$row['NAME']."\"] = ".$row['VALUE'].";\n");
	}
	
	$statement = "select OTHERS,VALUE from newjs.NAKSHATRA";
	$result = mysql_query($statement);
	while ($row = mysql_fetch_array($result))
	{
		fwrite($fp,"$"."NAKSHATRA_DROP[\"".$row['VALUE']."\"] = \"".$row['OTHERS']."\";\n");
	}

	$statement = "select LABEL,VALUE from newjs.RASHI";
	$result = mysql_query($statement);
	while ($row = mysql_fetch_array($result))
	{
		fwrite($fp,"$"."RASHI_DROP[\"".$row['VALUE']."\"] = \"".$row['LABEL']."\";\n");
	}

	$statement = "select LABEL,VALUE from newjs.SUNSIGN";
	$result = mysql_query($statement);
	while ($row = mysql_fetch_array($result))
	{
		fwrite($fp,"$"."SUNSIGN_DROP[\"".$row['VALUE']."\"] = \"".$row['LABEL']."\";\n");
	}
	//added by anand

	//view Similar
      /*  $sql="SELECT DISTINCT(VALUE),TYPE FROM newjs.INCOME";
        $result=mysql_query($sql);
        while($row=mysql_fetch_assoc($result))
        {
                $value=$row["VALUE"];
                $type=$row["TYPE"];
                $income_drop_plus4=plus4_income($value,$type,$db);
		fwrite($fp,"$"."INCOME_DROP_PLUS4[\"".$value."\"] = \"".$income_drop_plus4."\";\n");
        }*/
	//view Similar

	//Added by Jaiswal for sugar
    $personality_atr=array(
	"1"=> 'Jovial',
	"2"=> 'Hard Working',
	"3"=> 'Religious',
	"4"=> 'Introvert',
	"5"=> 'Studious',
	"6"=> 'Adventurous',
	"7"=> 'Just a common man',
);
	$hobbies_arr=array(
		"1" => 'Collecting Stamps',
		"2" => 'Collecting Coins',
		"3" => 'Collecting antiques',
		"4" => 'Art / Handicraft',
		"5" => 'Painting',
		"6" => 'Cooking',
		"7" => 'Photography',
		"8" => 'Film-making',
		"9" => 'Model building',
		"10" => 'Gardening / Landscaping',
		"11" => 'Fishing',
		"12" => 'Bird watching',
		"13" => 'Taking care of pets',
		"14" => 'Playing musical instruments',
		"15" => 'Singing',
		"16" => 'Dancing',
		"17" => 'Acting',
		"18" => 'Ham radio',
		"19" => 'Astrology / Palmistry / Numerology',
		"20" => 'Graphology',
		"21" => 'Solving Crosswords, Puzzles',
		);
		$mstatus_arr=array(
			"N"=>"Never Married",
			"S"=>"Awaiting Divorce",
			"D"=>"Divorced",
			"W"=>"Widowed",
			"A"=>"Annulled",
			"M"=>"Married",
			);
		$rel_drop_sugar=array(
			"1"=>"Self",
			"2"=>"Son",
			"2D"=>"Daughter",
			"4"=>"Relative/Friend",
			"6D"=>"Sister",
			"6"=>"Brother",
			"5"=>"Client-Marriage Bureau",
			);
		$mtongue_region_label=array(
			"4"=>"North",
			"3"=>"West",
			"2"=>"South",
			"1"=>"East",
			"0"=>"Others",
			);
	foreach($personality_atr as $key=>$val)
		fwrite($fp,"$"."PERSONALITY_ATTRIBUTE_DROP[\"".$key."\"] = \"".$val."\";\n");
	foreach($hobbies_arr as $key=>$val)
		fwrite($fp,"$"."HOBBIES_DROP[\"".$key."\"] = \"".$val."\";\n");
	foreach($mstatus_arr as $key=>$val)
		fwrite($fp,"$"."MSTATUS_DROP[\"".$key."\"] = \"".$val."\";\n");
	foreach($rel_drop_sugar as $key=>$val)
		fwrite($fp,"$"."RELATIONSHIP_DROP[\"".$key."\"] = \"".$val."\";\n");
	foreach($mtongue_region_label as $key=>$val)
		fwrite($fp,"$"."MTONGUE_REGION_LABEL[\"".$key."\"] = \"".$val."\";\n");

        $sql ="SELECT * FROM newjs.INCOME  ORDER BY SORTBY";
        $result=mysql_query($sql);
        $i=0;
        while($row=mysql_fetch_array($result))
        {
           fwrite($fp,"\$INCOME_DATA[".$i."]=array(\"VALUE\"=>".$row['VALUE'].",\"SORTBY\"=>".$row['SORTBY'].",\"MIN_VALUE\"=>".$row['MIN_VALUE'].",\"MAX_VALUE\"=>".$row['MAX_VALUE'].",\"MAPPED_MIN_VAL\"=>".$row['MAPPED_MIN_VAL'].",\"MAPPED_MAX_VAL\"=>".$row['MAPPED_MAX_VAL'].",\"TYPE\"=>'".$row['TYPE']."',\"VISIBLE\"=>'".$row['VISIBLE']."',\"LABEL\"=>'".$row['LABEL']."',\"MIN_LABEL\"=>'".$row['MIN_LABEL']."',\"MAX_LABEL\"=>'".$row['MAX_LABEL']."',\"TRENDS_SORTBY\"=>'".$row['TRENDS_SORTBY']."');\r\n");
           $i++;
        }
        
        
     fwrite($fp,"\n\n\$INCOME_NEW_SUGG_ALGO[2] = \"< Rs.1 lakh p.a.\";
\$INCOME_NEW_SUGG_ALGO[3] = \"Rs.1-2 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[4] = \"Rs.2-3 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[5] = \"Rs.3-4 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[6] = \"Rs.4-5 lakhs p.a.\";
//\$INCOME_NEW_SUGG_ALGO[7] = \"Rs.5 lakhs and above  p.a.\"; //no entries for this index are present on the search table
\$INCOME_NEW_SUGG_ALGO[8] = \"Under \$25K p.a.\";
\$INCOME_NEW_SUGG_ALGO[9] = \"\$25-40K p.a.\";
\$INCOME_NEW_SUGG_ALGO[10] = \"\$40K-60K p.a.\";
\$INCOME_NEW_SUGG_ALGO[11] = \"\$60K-80K p.a.\";
\$INCOME_NEW_SUGG_ALGO[12] = \"\$80K-100K p.a.\";
\$INCOME_NEW_SUGG_ALGO[13] = \"\$100K-150K p.a.\";
\$INCOME_NEW_SUGG_ALGO[21] = \"\$150K 200K p.a.\";
\$INCOME_NEW_SUGG_ALGO[14] = \"> \$200K p.a.\";
\$INCOME_NEW_SUGG_ALGO[15] = \"No Income\";
\$INCOME_NEW_SUGG_ALGO[16] = \"Rs.5-7.5 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[17] = \"Rs.7.5-10 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[18] = \"Rs.10-15 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[20] = \"Rs.15-20 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[22] = \"Rs.20-25 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[23] = \"Rs.25-35 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[24] = \"Rs.35-50 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[25] = \"Rs.50-70 lakhs p.a.\";
\$INCOME_NEW_SUGG_ALGO[26] = \"Rs.70lakhs-1 crore p.a.\";
\$INCOME_NEW_SUGG_ALGO[27] = \"> Rs.1 crore p.a.\";
");   

fwrite($fp,"\n\n \$income_map=array(
\"2\" => \"< Rs. 1Lac\",
\"3\" => \"Rs. 1 - 2Lac\",
\"4\" => \"Rs. 2 - 3Lac\",
\"5\" => \"Rs. 3 - 4Lac\",
\"6\" => \"Rs. 4 - 5Lac\",
\"8\" => \"< \$ 25K\",
\"9\" => \"\$ 25 - 40K\",
\"10\" => \"\$ 40 - 60K\",
\"11\" => \"\$ 60 - 80K\",
\"12\" => \"\$ 80K - 1lac\",
\"13\" => \"\$ 1 - 1.5lac\",
\"21\" => \"\$ 1.5 - 2lac\",
\"14\" => \"> \$ 2lac\",
\"15\" => \"No Income\",
\"16\" => \"Rs. 5 - 7.5lac\",
\"17\" => \"Rs. 7.5 - 10lac\",
\"18\" => \"Rs. 10 - 15lac\",
\"20\" => \"Rs. 15 - 20lac\",
\"22\" => \"Rs. 20 - 25lac\",
\"23\" => \"Rs. 25 - 35lac\",
\"24\" => \"Rs. 35 - 50lac\",
\"25\" => \"Rs. 50 - 70lac\",
\"26\" => \"Rs. 70lac - 1cr\",
\"27\" => \"> Rs. 1cr\",
);");
 	fwrite($fp,"?>\r\n");
	
	
	fclose($fp);
	
?>
