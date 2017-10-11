<?php

include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include(JsConstants::$docRoot."/profile/arrays.php");
	$con = mysql_connect("localhost","user","CLDLRTa9");
	mysql_select_db("crawler",$con);
	if (!$con)
		die('Could not connect: ' . mysql_error());

//	$table[]='crawler.crawler_JS_competition_blood_group_values_mapping'; //done
	$table[]='crawler.crawler_JS_competition_caste_values_mapping'; //done 
//	$table[]='crawler.crawler_JS_competition_city_res_values_mapping'; //done
//	$table[]='crawler.crawler_JS_competition_country_res_values_mapping'; //done
//	$table[]='crawler.crawler_JS_competition_mstatus_values_mapping'; //done

//	$table[]='crawler.crawler_JS_competition_mtongue_values_mapping';

//	$table[]='crawler.crawler_JS_competition_edu_level_new_values_mapping'; //done

//	$table[]='crawler.crawler_JS_competition_citizenship_values_mapping';
//	$table[]='crawler.crawler_JS_competition_diet_values_mapping';
//	$table[]='crawler.crawler_JS_competition_drink_values_mapping';
//	$table[]='crawler.crawler_JS_competition_gender_values_mapping';
//	$table[]='crawler.crawler_JS_competition_height_values_mapping';
//	$table[]='crawler.crawler_JS_competition_religion_values_mapping';
//	$table[]='crawler.crawler_JS_competition_smoke_values_mapping';
//	$table[]='crawler.crawler_JS_competition_t_brothers_values_mapping';
//	$table[]='crawler.crawler_JS_competition_t_sisters_values_mapping';

//	$map[]='BLOOD_GROUP';
	$map[]='CASTE_DROP';
//	$map[]='CITY_DROP';
//	$map[]='COUNTRY_DROP';
//	$map[]='MSTATUS';
//	$map[]='MTONGUE_DROP';
//	$map[]='EDUCATION_LEVEL_NEW_DROP';

//	$map[]='RSTATUS';
//	$map[]='DIET';
//	$map[]='DRINK';
//	$map[]='GENDER';
//	$map[]='HEIGHT_DROP';
//	$map[]='RELIGIONS';
//	$map[]='SMOKE';

	foreach($table as $index=>$table)
	{
echo		$sql = "SELECT distinct JS_FIELD_VALUE FROM $table WHERE SITE_ID=1";
		$res = mysql_query($sql,$con) or die(mysql_error());

//		$code = '$mappingArray='.$map[$index];
//		eval($code.";");
//print_r($mappingArray);
		$mappingArray=${$map[$index]};

		while($row = mysql_fetch_array($res))
		{
echo			$mappingField = $row['JS_FIELD_VALUE'];
echo "--";
			foreach($mappingArray as $key=>$value)
			{
				if($map[$index] == 'CASTE_DROP')
				{
					$val = explode(":",$value);
					$value = trim($val[1], " ");
				}
				if(trim($value," ") == trim($mappingField," "))
				{
					$found = 1;
echo					$mapped_value = $key;
echo "\n";
echo					$update = "UPDATE $table SET JS_FIELD_VALUE = '$mapped_value' WHERE JS_FIELD_VALUE = '$mappingField' ";
					mysql_query($update,$con) or die(mysql_error());
					break;
				}
			}
			
			if($found == 0 && $table=='crawler.crawler_JS_competition_city_res_values_mapping')
			{
				foreach($CITY_INDIA_DROP as $key=>$value)
				{
					if(strpos($value, "/"))
					{
						$val = explode("/",$value);
						foreach($val as $val)
						{
							if(trim($val," ") == trim($mappingField," "))
							{
								$found = 1;
echo								$mapped_value = $key;
echo "\n";
echo								$update = "UPDATE $table SET JS_FIELD_VALUE = '$mapped_value' WHERE JS_FIELD_VALUE = '$mappingField' ";
								mysql_query($update,$con) or die(mysql_error());
								break;
							}
						}
					}
					if(trim($value," ") == trim($mappingField," "))
					{
						$found = 1;
echo						$mapped_value = $key;
echo "\n";
echo						$update = "UPDATE $table SET JS_FIELD_VALUE = '$mapped_value' WHERE JS_FIELD_VALUE = '$mappingField' ";
						mysql_query($update,$con) or die(mysql_error());
						break;
					}
					
				}
			}

			if($found == 0)
				echo "$mappingField-----NOT FOUND";
			$found = 0;
echo "\n ******************* \n";

		}
//		break;
	}

	mysql_close($con);
?>
