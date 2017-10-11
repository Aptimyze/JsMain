<?php
//This will generate class JsLabelsFromDb for all dropdown, country, caste labels 
//that will be difined as consts.
//@author Jaiswal
$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/lib/FieldMapLib.class.php","w");
$fhobby=fopen($socialRoot."/lib/model/lib/HobbyLib.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
include_once($socialRoot."/lib/model/lib/search/SearchConfig.php");
include_once($socialRoot."/web/crm/negativeListFlagArray.php");
fwrite($fp,"<?php\n /*
	This is auto-generated class by running lib/utils/JsLabelsCreater.php
	This class should not be updated manually.
	Created on $now
	unit test of this class is test/unit/utils/FieldLabelTest.php
 */
	class FieldMap{
		/*This will return label corresponding to value*/
public static function getFieldLabel(\$label,\$value,\$returnArr=''){
	switch(\$label){
	case \"income\":
		\$arr=array( \n");

fwrite($fhobby,"<?php\n /*
	This is auto-generated class by running lib/utils/JsLabelsCreater.php
	This class should not be updated manually.
	Created on $now
	unit test of this class is test/unit/utils/FieldLabelTest.php
 */
	class HobbyLib{
		/*This will return label corresponding to hobby value*/
public static function getHobbyLabel(\$label,\$value,\$returnArr=''){
	switch(\$label){\n");
	

$db=connect_db();

//To genrate array of incomes less and greater than a particular value

$sql="SELECT SORTBY,VALUE FROM newjs.INCOME WHERE VISIBLE='Y' ORDER BY SORTBY";
$res= mysql_query_decide($sql) or die(mysql_error());
$income_arr=array(2,3,4,5,6,8,9,10,11,12,13,14,16,17,18,20,21,22,23,24,25,26,27);
$arr[]= 15;
$more_val=implode("','",$income_arr);
fwrite($fp,"\"15\"=>array('LESS'=>\"'" . $less_val . "'\",\n
	'MORE'=>\"'" . $more_val . "'\"),\n");
while($row=mysql_fetch_array($res))
{
	$val=$row['VALUE'];
	$income_less[$val]=$arr;
	$less_val=implode("','",$arr);
		$arr[]=$val;
	$income_more[$val]=array_diff($income_arr,$arr);
	$more_val=implode("','",$income_more[$val]);
	fwrite($fp,"\"" . $val . "\"=>array(\n'LESS'=>\"'" . $less_val . "'\",\n
		'MORE'=>\"'" . $more_val . "'\"),\n");
}
fwrite($fp,");\n
	break;\n

case \"income_sortby\":\n
        \$arr=array(\n");
$sql="select VALUE,SORTBY from newjs.INCOME WHERE VISIBLE ='Y' ORDER BY SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["SORTBY"] . "\",\n");
}
fwrite($fp,");\n
        break;\n

case \"search_clusters\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="select VALUE,DISPLAY_LABEL from newjs.SEARCH_CLUSTERS WHERE ACTIVE ='Y' ORDER BY VALUE";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["DISPLAY_LABEL"] . "\",\n");
}
fwrite($fp,");\n
        break;\n

case \"solr_clusters\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="select VALUE,SOLR_LABEL from newjs.SEARCH_CLUSTERS WHERE ACTIVE ='Y' ORDER BY VALUE";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["SOLR_LABEL"] . "\",\n");
}
fwrite($fp,");\n
        break;\n

case \"caste\":\n
	\$arr=array(\n");
mysql_free_result($result);
$sql="select VALUE,LABEL from CASTE";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}
fwrite($fp,");\n
	break;\n


case \"religion_caste\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="SELECT VALUE, PARENT
FROM  `CASTE` 
WHERE ISALL !=  'Y' and REG_DISPLAY!='N' order by SORTBY ASC";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	$cas_arr[$myrow[PARENT]][]=$myrow[VALUE];
}
foreach($cas_arr as $key=>$val)
{
        fwrite($fp,"\"" . $key . "\"=>\"" . implode(",",$val) . "\",\n");
	
}
unset($cas_arr);
fwrite($fp,");\n
        break;\n



case \"caste_group_array\":\n
	\$arr=array(\n");
mysql_free_result($result);
$sql="SELECT CG.GROUP_VALUE AS GROUP_VALUE,CG.CASTE_VALUE AS CASTE_VALUE FROM newjs.CASTE_GROUP_MAPPING CG, newjs.CASTE C WHERE CG.CASTE_VALUE = C.VALUE ORDER BY CG.GROUP_VALUE,C.SORTBY";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
        {
                $casteGroupArray[$row["GROUP_VALUE"]] = $casteGroupArray[$row["GROUP_VALUE"]].$row["CASTE_VALUE"].",";
        }

foreach ($casteGroupArray as $k=>$v)
        {
                fwrite($fp,"\"".$k."\" => \"".rtrim($v,",")."\",\n");
        }
fwrite($fp,");\n
	break;\n

case \"castegroup_from_caste_array\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="SELECT GROUP_VALUE,CASTE_VALUE FROM newjs.CASTE_GROUP_MAPPING";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
	$tempArr[$row["CASTE_VALUE"]][] = $row["GROUP_VALUE"]; 	
}
foreach($tempArr as $k=>$v)
{
        fwrite($fp,"\"".$k."\" => \"".implode(",",$v)."\",\n");
}
fwrite($fp,");\n
        break;\n


case \"income_duplication_check\":\n
//	\$arr=array(\n");
$sql="select TYPE,SORTBY,VALUE from newjs.INCOME WHERE VISIBLE='Y'";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
//	fwrite($fp,"$"."INCOME_DUPLICATION_CHECK[\"".$myrow['TYPE']."\"][\"".$myrow['SORTBY']."\"] = \"".$myrow['VALUE']."\";\n");
	fwrite($fp,"$"."arr[\"".$myrow['TYPE']."\"][\"".$myrow['SORTBY']."\"] = \"".$myrow['VALUE']."\";\n");
}
fwrite($fp,"\n
	break;\n

case\"min_height\":\n
	\$arr=array(\n");
$sql="select MIN(VALUE) AS VALUE from newjs.HEIGHT";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"0\"=>\"" . $myrow["VALUE"] . "\",\n");
}
fwrite($fp,");\n
	break;\n

case\"max_height\":\n
	\$arr=array(\n");
$sql="select MAX(VALUE) AS VALUE from newjs.HEIGHT";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"0\"=>\"" . $myrow["VALUE"] . "\",\n");
}
fwrite($fp,");\n
	break;\n

case\"photo_display_logic\":\n
	\$arr=array(\n");

mysql_free_result($result);

$sql = "SELECT * FROM newjs.PICTURE_DISPLAY_LOGIC";
$result = mysql_query($sql);
while($row=mysql_fetch_assoc($result))
{
        $str = $row['HAVEPHOTO'].$row['PHOTO_DISPLAY'].$row['PRIVACY'].$row['LOGIN_STATUS'].$row['FILTERS_PASSED'].$row['CONTACT_STATUS'];
fwrite($fp,"\"".$str."\"=>\"".$row['IS_PHOTO_SHOWN']."\",\n");
//        $resultArr[$str]=$row['IS_PHOTO_SHOWN'];
}
fwrite($fp,");\n
	break;
case \"occupation\":\n
	\$arr=array(\n");

mysql_free_result($result);

$sql="select VALUE,LABEL,GROUPING from OCCUPATION  order by SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	$grouping[$myrow["GROUPING"]][] = $myrow["VALUE"];
}
fwrite($fp,");\n
	break;\n
case \"occupation_grouping_mapping_to_occupation\":\n
	\$arr=array(\n");
foreach($grouping as $k=>$v)
{
	$vv = implode(",",$v);
	fwrite($fp,"\"" . $k . "\"=>\"" . $vv . "\",\n");
}
unset($grouping);
unset($vv);


fwrite($fp,");\n
	break;\n
case \"height\":\n
	\$arr=array(\n");
mysql_free_result($result);
$sql="select VALUE,LABEL from HEIGHT";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
        break;\n
case \"height_without_meters\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="select VALUE,LABEL from HEIGHT";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        $temp = $myrow["LABEL"];
        $tempArr = explode("(",$temp);
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $tempArr[0] . "\",\n");
}

fwrite($fp,");\n
        break;\n
case \"height_json\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="select VALUE,LABEL from HEIGHT";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        $temp = $myrow["LABEL"];
        $tempArr = explode("&quot; ",$temp);
        if($myrow["VALUE"] != 37)
          fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $tempArr[0] ."\\\" ". $tempArr[1]."\",\n");
        else
          fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $tempArr[0] ."\",\n");
}


fwrite($fp,");\n
	break;\n
case \"topindia_city\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="SELECT VALUE,LABEL,COUNTRY_VALUE 
FROM  `CITY_NEW` 
WHERE COUNTRY_VALUE!='' and  DD_TOP='Y'  order by DD_TOP_SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        $city_arr[$myrow[COUNTRY_VALUE]][]=$myrow[VALUE];
}
foreach($city_arr as $key=>$val)
{
        fwrite($fp,"\"" . $key . "\"=>\"" . implode(",",$val) . "\",\n");

}
unset($city_arr);
fwrite($fp,");\n
        break;\n

case \"country_city\":\n
        \$arr=array(\n");
mysql_free_result($result);
$sql="SELECT VALUE,LABEL,COUNTRY_VALUE 
FROM  `CITY_NEW` 
WHERE COUNTRY_VALUE!='' and TYPE='CITY' order by SORTBY ASC";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        $city_arr[$myrow[COUNTRY_VALUE]][]=$myrow[VALUE];
}
foreach($city_arr as $key=>$val)
{
        fwrite($fp,"\"" . $key . "\"=>\"" . implode(",",$val) . "\",\n");

}
unset($city_arr);
fwrite($fp,");\n
        break;\n


case \"city_india\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="SELECT VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
	break;\n

case \"state_india\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="SELECT VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 AND TYPE = 'STATE' ORDER BY SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
	break;\n

case \"state_CITY\":\n
	\$arr=array(\n");
mysql_free_result($result);
$sql="SELECT SUBSTRING( VALUE, 1, 2 ) AS STATE, VALUE AS CITYNAME FROM  newjs.CITY_NEW WHERE TYPE = 'CITY' ORDER BY STATE";
$result=mysql_query($sql);
$i=0;
while($myrow=mysql_fetch_array($result))
{
    
    
    if($state != $myrow["STATE"] && $state!=""){
    	$cityStr=implode(',',array_values($city));
	fwrite($fp,"\"" . $state . "\"=>\"" . $cityStr . "\",\n");
	$state=$myrow["STATE"];
	$i=0;
	unset($city);
	$city[$i++]=$myrow["CITYNAME"];
	$state=$myrow["STATE"];
}
else{
	$city[$i++]=$myrow["CITYNAME"];
	$state=$myrow["STATE"];
}
}
$cityStr=implode(',',array_values($city));
	fwrite($fp,"\"" . $state . "\"=>\"" . $cityStr . "\",\n");
fwrite($fp,");\n
	break;\n

case \"city_usa\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="SELECT VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
	break;\n
case \"impcountry\":\n
        \$arr=array(\n");
mysql_free_result($result);

$sql="select VALUE,LABEL from COUNTRY_NEW where TOP_COUNTRY='Y' order by TOP_COUNTRY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
        break;\n

case \"country\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="select VALUE,LABEL from COUNTRY_NEW order by ALPHA_ORDER";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
	break;\n

case \"isdcode\":\n
        \$arr=array(\n");
mysql_free_result($result);

$sql="select ISD_CODE as VALUE,LABEL from COUNTRY_NEW order by ALPHA_ORDER";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
        break;\n

case \"religion\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="select VALUE,LABEL from RELIGION order by SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}

fwrite($fp,");\n
	break;\n
case \"community\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="select VALUE,LABEL from MTONGUE order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"reg_community\":\n
	\$arr=array(\n");
mysql_free_result($result);

$sql="select VALUE,LABEL from MTONGUE where REG_DISPLAY<>'N' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n

case \"mtongue_region\":\n
        \$arr=array(\n");
mysql_free_result($result);

$sql="SELECT GROUP_CONCAT(VALUE ORDER BY SORTBY_NEW SEPARATOR ',') AS VALS,REGION FROM newjs.MTONGUE GROUP BY REGION ORDER BY REGION DESC";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                        fwrite($fp,"\"" . $myrow["REGION"] . "\"=>\"" . $myrow["VALS"] . "\",\n");
        }

fwrite($fp,");\n
        break;\n

case \"mtongue_region_registration\":\n
        \$arr=array(\n");
mysql_free_result($result);

$sql="SELECT GROUP_CONCAT(VALUE ORDER BY SORTBY_NEW SEPARATOR ',') AS VALS,REGION FROM newjs.MTONGUE WHERE REG_DISPLAY!='N' GROUP BY REGION ORDER BY REGION DESC";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
                        fwrite($fp,"\"" . $myrow["REGION"] . "\"=>\"" . $myrow["VALS"] . "\",\n");
        }

fwrite($fp,");\n
        break;\n

case \"education\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL,GROUPING from EDUCATION_LEVEL_NEW";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
			$grouping[$myrow["GROUPING"]][] = $myrow["VALUE"];
	}

fwrite($fp,");\n
	break;\n
case \"education_grouping_mapping_to_edu_level_new\":\n
	\$arr=array(\n");
foreach($grouping as $k=>$v)
{
	$vv = implode(",",$v);
	fwrite($fp,"\"" . $k . "\"=>\"" . $vv . "\",\n");
}
unset($grouping);
unset($vv);


fwrite($fp,");\n
	break;\n
case \"income_level\":\n
	\$arr=array(\n");
	mysql_free_result($result);



$sql="select VALUE,LABEL from INCOME where VISIBLE <> 'N' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n

case \"degree_grouping\":\n
        \$arr=array(\n");
        mysql_free_result($result);
//OLD_VALUE less than 5 is for ug degree
$sql="SELECT IF (OLD_VALUE <=4, 'UG', 'PG') AS GRP1, VALUE FROM EDUCATION_LEVEL_NEW";
        $result=mysql_query($sql);

        while($myrow=mysql_fetch_array($result))
        {
		if($myrow["GRP1"] == 'UG')
			$UG[]=$myrow["VALUE"];
		else
			$PG[]=$myrow["VALUE"];
        }
	fwrite($fp,"\"UG\"=>\" " . implode(" , ",$UG) . " \",\n");
	fwrite($fp,"\"PG\"=>\" " . implode(" , ",$PG) . " \",\n");


fwrite($fp,");\n
        break;\n


case \"degree_ug\":\n
	\$arr=array(\n");
	mysql_free_result($result);
//OLD_VALUE less than 5 is for ug degree
$sql="select VALUE,LABEL from EDUCATION_LEVEL_NEW  where OLD_VALUE<5 order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}


fwrite($fp,");\n
	break;\n
case \"degree_pg\":\n
	\$arr=array(\n");
	mysql_free_result($result);
//old_value 0 selected for others. OLD_VALUE 5 and 6 for pg degrees
$sql="select VALUE,LABEL from EDUCATION_LEVEL_NEW  where OLD_VALUE>=5 OR OLD_VALUE = 0 order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fp,");\n
	break;\n
case \"education_label\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from EDUCATION_LEVEL order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"family_background\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from FAMILY_BACK order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"mother_occupation\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from MOTHER_OCC order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"caste_small\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,SMALL_LABEL from CASTE";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["SMALL_LABEL"] . "\",\n");
	}
        
fwrite($fp,");\n
	break;\n
case \"caste_without_religion\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL,SMALL_LABEL from CASTE";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
            if(strpos($myrow["SMALL_LABEL"],':')){
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . str_replace("-","",$myrow["SMALL_LABEL"]) . "\",\n");
            }
            else{
                $replacedStr = strstr($myrow["LABEL"],": ");
                if($myrow["SMALL_LABEL"] != "Others" && $replacedStr){
                  fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . str_replace(": ","",$replacedStr) . "\",\n");}
                else{
                  fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");}
            }
	}

fwrite($fp,");\n
	break;\n
case \"sect_hindu\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from SECT where PARENT_RELIGION=1 ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"sect_muslim\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from SECT where PARENT_RELIGION=2 ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"sect_sikh\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from SECT where PARENT_RELIGION=4 ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"sect_jain\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from SECT where PARENT_RELIGION=9 ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"sect\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from SECT ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"sect_buddhist\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from SECT where PARENT_RELIGION=7 ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"community_small\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,SMALL_LABEL from MTONGUE";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["SMALL_LABEL"]=='Rajasthani')
			$myrow["SMALL_LABEL"]="Rajasthani/Marwari"; 
		 fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["SMALL_LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"reg_community_small\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,SMALL_LABEL from MTONGUE where REG_DISPLAY<>'N' ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["SMALL_LABEL"]=='Rajasthani')
			$myrow["SMALL_LABEL"]="Rajasthani/Marwari"; 
		 fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["SMALL_LABEL"] . "\",\n");
	}

fwrite($fp,");\n
	break;\n
case \"city\":\n
	\$arr=array(\n");
	mysql_free_result($result);

 $sql="SELECT VALUE,LABEL FROM newjs.CITY_NEW WHERE TYPE!='STATE' ORDER BY SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fp,");\n
	break;\n
case \"nakshatra_matchastro\":\n
	\$arr=array(\n");

	mysql_free_result($result);

//added by anand
$statement = "select NAME,VALUE from newjs.NAKSHATRA_MATCHASTRO";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['NAME']."\" => ".$row['VALUE'].",\n");
}
fwrite($fp,");\n
	break;\n
case \"rashi_matchastro\":\n
	\$arr=array(\n");

	mysql_free_result($result);
$statement = "select NAME,VALUE from newjs.RASHI_MATCHASTRO";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['NAME']."\" => ".$row['VALUE'].",\n");
}
fwrite($fp,");\n
	break;\n
case \"nakshatra\":\n
	\$arr=array(\n");

	mysql_free_result($result);

$statement = "select OTHERS,VALUE from newjs.NAKSHATRA";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['VALUE']."\" => \"".$row['OTHERS']."\",\n");
}

fwrite($fp,");\n
	break;\n
case \"rashi\":\n
	\$arr=array(\n");

	mysql_free_result($result);
$statement = "select LABEL,VALUE from newjs.RASHI";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['VALUE']."\" => \"".$row['LABEL']."\",\n");
}
fwrite($fp,");\n
	break;\n
case \"lincome\":\n
	\$arr=array(\n");

	mysql_free_result($result);

$statement="select MIN_LABEL,MIN_VALUE from newjs.INCOME where TYPE='RUPEES' AND VISIBLE='Y' ORDER BY SORTBY";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['MIN_VALUE']."\" => \"".$row['MIN_LABEL']."\",\n");
}
fwrite($fp,");\n
	break;\n
case \"hincome\":\n
	\$arr=array(\n");

	mysql_free_result($result);

$statement="select MAX_LABEL,MAX_VALUE from newjs.INCOME where TYPE='RUPEES' AND VISIBLE='Y' ORDER BY SORTBY";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['MAX_VALUE']."\" => \"".$row['MAX_LABEL']."\",\n");
}
fwrite($fp,");\n
	break;\n
case \"lincome_dol\":\n
	\$arr=array(\n");

	mysql_free_result($result);

$statement="select MIN_LABEL,MIN_VALUE from newjs.INCOME where TYPE='DOLLARS' AND VISIBLE='Y' ORDER BY SORTBY";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['MIN_VALUE']."\" => \"".$row['MIN_LABEL']."\",\n");
}
fwrite($fp,");\n
	break;\n
case \"hincome_dol\":\n
	\$arr=array(\n");

	mysql_free_result($result);

$statement="select MAX_LABEL,MAX_VALUE from newjs.INCOME where TYPE='DOLLARS' AND  VISIBLE='Y' ORDER BY SORTBY";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['MAX_VALUE']."\" => \"".$row['MAX_LABEL']."\",\n");
}
fwrite($fp,");\n
	break;\n
case \"sunsign\":\n
	\$arr=array(\n");

	mysql_free_result($result);

$statement="select LABEL,VALUE from newjs.SUNSIGN";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	fwrite($fp,"\"".$row['VALUE']."\" => \"".$row['LABEL']."\",\n");
}
/*
fwrite($fp,");\n
	break;\n

case \"income_plus4\":\n
	\$arr=array(\n");
$statement="SELECT DISTINCT(VALUE),TYPE FROM newjs.INCOME";
$result = mysql_query($statement);
while ($row = mysql_fetch_array($result))
{
	$value=$row["VALUE"];
	$type=$row["TYPE"];
	$income_drop_plus4=plus4_income($value,$type,$db);
	fwrite($fp,"\"".$value."\" => \"".$income_drop_plus4."\",\n");
}*/
fwrite($fp,");\n
	break;\n

case \"personality\":\n
	\$arr=array(\n");

	mysql_free_result($result);
//added by anand


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
	$rel_drop_minireg=array(
		"1"=>"Bride for Self",
		"2"=>"Bride for Son",
		"6"=>"Bride for Brother",
		"4"=>"Bride for Friend/Relative/Niece/Others",
		"1D"=>"Groom for Self",
		"2D"=>"Groom for Daughter",
		"6D"=>"Groom for Sister",
		"4D"=>"Groom for Friend/Relative/Niece/Others",
		);
	$rel_drop_edit = array(
		"1" => "Self",
		"2" => "Parent",
		"3" => "Sibling",
		"4" => "Relative/Friend",
		"5" => "Marriage Bureau",
		"6" => "Other",
	);	
foreach($personality_atr as $key=>$val)
	fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;\n
case \"hobbies\":\n
	\$arr=array(\n");
foreach($hobbies_arr as $key=>$val)
	fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;\n
case \"marital_status\":\n
	\$arr=array(\n");
foreach($mstatus_arr as $key=>$val)
	fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;\n
case \"marital_status_ascii\":\n
        \$arr=array(\n");
foreach($mstatus_arr as $key=>$val)
        fwrite($fp,"\"".ord($key)."\" => \"".$val."\",\n");
fwrite($fp,");\n
        break;\n
//Income data starts from here
case \"income_data\":\n
	\$arr=array(\n");

$sql ="SELECT * FROM newjs.INCOME  ORDER BY SORTBY";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
	fwrite($fp,"$i=>array(\"VALUE\"=>".$row['VALUE'].",\"SORTBY\"=>".$row['SORTBY'].",\"MIN_VALUE\"=>".$row['MIN_VALUE'].",\"MAX_VALUE\"=>".$row['MAX_VALUE'].",\"MAPPED_MIN_VAL\"=>".$row['MAPPED_MIN_VAL'].",\"MAPPED_MAX_VAL\"=>".$row['MAPPED_MAX_VAL'].",\"TYPE\"=>'".$row['TYPE']."',\"VISIBLE\"=>'".$row['VISIBLE']."',\"LABEL\"=>'".$row['LABEL']."',\"MIN_LABEL\"=>'".$row['MIN_LABEL']."',\"MAX_LABEL\"=>'".$row['MAX_LABEL']."',\"TRENDS_SORTBY\"=>'".$row['TRENDS_SORTBY']."'),\r\n");
    $i++;
}
mysql_free_result($result);
fwrite($fp,");\n
	break;\n
case \"relationship\":\n
	\$arr=array(\n");
foreach($rel_drop_sugar as $key=>$val)
	fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;
case \"relationship_edit\":\n
	\$arr=array(\n");
foreach($rel_drop_edit as $key=>$val)
	fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;
case \"relationship_minireg\":\n
	\$arr=array(\n");
foreach($rel_drop_minireg as $key=>$val)
	fwrite($fp,"\"".$key."\" => \"".$val."\",\n");


//Added By Lavesh
fwrite($fp,");\n
        break;\n
case \"education_grouping\":\n
        \$arr=array(\n");
$sql="select VALUE,LABEL from EDUCATION_GROUPING ORDER BY SORTBY";
$result=mysql_query($sql) or die(mysql_error().$sql);
while($myrow=mysql_fetch_array($result))
{       
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}       

fwrite($fp,");\n
        break;\n
case \"occupation_grouping\":\n
        \$arr=array(\n");
$sql="select VALUE,LABEL from newjs.OCCUPATION_GROUPING ORDER BY SORTBY";
$result=mysql_query($sql) or die(mysql_error().$sql);
while($myrow=mysql_fetch_array($result))
{
        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}


fwrite($fp,");\n
        break;\n
case \"wellKnownColleges\":\n
        \$arr=array(\n");

$sql="select VALUE,LABEL from newjs.KNOWN_COLLEGES";
$result=mysql_query($sql) or die(mysql_error().$sql);;
while($myrow=mysql_fetch_array($result))
	fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"".$myrow["LABEL"]."\",\n");

fwrite($fp,");\n
        break;\n

case \"caste_clusters_breadcrumb\":\n
        \$arr=array(\n");
mysql_free_result($result);

$sql="SELECT count(*) c, SUBSTRING(LABEL,POSITION(':'IN LABEL ) +1, LENGTH(LABEL)) as SEARCH_CLUSTER_LABEL FROM  CASTE WHERE REG_DISPLAY='' GROUP by SEARCH_CLUSTER_LABEL HAVING c>1";
$result=mysql_query($sql);
while($myrow=mysql_fetch_array($result))
{
	$duplicate_search_cluster_lavel[] = trim($myrow["SEARCH_CLUSTER_LABEL"]," ");
}
$sql="select VALUE,LABEL,SUBSTRING(LABEL,POSITION(':'IN LABEL ) +1, LENGTH(LABEL)) as SEARCH_CLUSTER_LABEL from CASTE";
$result=mysql_query($sql);
while($myrow=mysql_fetch_array($result))
{
	$myrow["SEARCH_CLUSTER_LABEL"] = trim($myrow["SEARCH_CLUSTER_LABEL"]," ");
	if(in_array($myrow["SEARCH_CLUSTER_LABEL"],$duplicate_search_cluster_lavel))
	{
	        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"".$myrow["LABEL"]."\",\n");
	}
	else
	        fwrite($fp,"\"" . $myrow["VALUE"] . "\"=>\"".$myrow["SEARCH_CLUSTER_LABEL"]."\",\n");
}
unset($duplicate_search_cluster_lavel);

$sql = "SELECT MIN_VALUE,VALUE FROM newjs.INCOME";
$result = mysql_query($sql);
while($myrow = mysql_fetch_assoc($result))
{
	$min_value = $myrow["MIN_VALUE"];
	$val1 = $myrow["VALUE"];

	$sql1="SELECT MIN_VALUE,VALUE FROM newjs.INCOME";
	$result1=mysql_query($sql1) or die(mysql_error());
	while($row1=mysql_fetch_assoc($result1))
	{
		$min = $row1["MIN_VALUE"];
		$val2 = $row1["VALUE"];
		if($min==$min_value)
			$sameIncome[$val1][] = $val2;
		elseif($min > $min_value)
			$lessIncome[$val1][] = $val2;
		else
			$gr8Income[$val1][] = $val2;
	}
}

/*
fwrite($fp,");\n
        break;\n
case \"same_income_levels\":\n
        \$arr=array(\n");
foreach($sameIncome as $k=>$v)
{
	$v = "array(".implode(",",$v).")";
	fwrite($fp,"\"" . $k . "\"=>".$v.",\n");
}
fwrite($fp,");\n
        break;\n
case \"less_income_levels\":\n
        \$arr=array(\n");
foreach($lessIncome as $k=>$v)
	fwrite($fp,"\"" . $k . "\"=>\"".$v."\",\n");

fwrite($fp,");\n
        break;\n
case \"more_income_levels\":\n
        \$arr=array(\n");
foreach($gr8Income as $k=>$v)
	fwrite($fp,"\"" . $k . "\"=>\"".$v."\",\n");
unset($sameIncome);
unset($lessIncome);
unset($gr8Income);
*/
//Added By Lavesh


//Add Enteried of arrays.php here. It will be added into FieldLabel class
//Do add unit Test for that in test/unit/utils/FieldLabelTest.php and check if everything is fine

$otherArrays=array(

            "gender" => array
                (
                    "M" => "Male",
                    "F" => "Female",
                ),

            "keyword_type" => array
                (
                    "AND" => "All",
                    "OR" => "Any",
		    "NOT" => "None",
                ),

            "flagval" => array
                (
					"subcaste" => 0,
					"citybirth" => 1,
					"gothra" => 2,
          "diocese" => 2,
					"nakshatra" => 3,
					"messenger_id" => 4,
					"yourinfo" => 5,
					"familyinfo" => 6,
					"spouse" => 7,
					"contact" => 8,
					"education" => 9,
					"phone_res" => 10,
					"phone_mob" => 11,
					"email" => 12,
					"job_info" => 13,
					"father_info" => 14,
					"sibling_info" => 15,
					"parents_contact" => 16,
					"username" => 17,
					"name" => 18,
					"ancestral_origin" => 19,
					"phone_owner_name" => 20,
					"mobile_owner_name" => 21,
					"fav_food" => 22,
					"fav_tvshow" => 23,
					"fav_movie" => 24,
					"fav_book" => 25,
					"fav_vac_dest" => 26,
					"company_name" => 27,
					"linkedin_url" => 28,
					"fb_url" => 29,
					"blackberry" => 30,
					"pg_college" => 31,
					"school" => 32,
					"college" => 33,
					"profile_handler_name" => 34,
					"gothra_maternal" => 35,
					"alt_mobile_owner_name" => 36,
					"alt_messenger_id" => 37,
					"other_ug_degree" => 38,
					"other_pg_degree" => 39,
                    "sum" => "1099511627775"
                ),

            "photoval" => array
                (
                    "mainphoto" => "1",
                    "albumphoto1" => "2",
                    "albumphoto2" => "4",
                    "thumbnail" => "8",
                    "profilephoto" => "16",
                    "sum" => "31",
                ),

            "duplicationFieldsVal" => array
                (
                    "photos" => "0",
                    "gender" => "1",
                    "religion" => "2",
                    "mtongue" => "3",
                    "caste" => "4",
                    "country_res" => "5",
                    "city_res" => "6",
                    "mstatus" => "7",
                    "height" => "8",
                    "income" => "9",
                    "edu_level_new" => "10",
                    "dtofbirth" => "11",
                    "citybirth" => "12",
                    "btime" => "13",
                    "password" => "14",
                    "subcaste" => "15",
                    "occupation" => "16",
                    "school" => "17",
                    "college" => "18",
                    "pg_college" => "19",
                    "company_name" => "20",
                    "email" => "21",
                    "messenger_id" => "22",
                    "phone_mob" => "23",
                    "phone_res" => "24",
                    "alt_mobile" => "25",
		    		"name" => "26",
                    "sum" => "134217727",
                ),

            "duplicationCheck" => array
                (
                    "CRAWLER" => " 1 , 2 , 3 , 4 , 5 , 6 , 7 , 8 , 9 , 10 , 11 , 12 , 13 , 14 , 15 , 16 , 17 , 18 , 19 , 20 , 21 , 22 , 26 ",
                    "PHONE" => " 23 , 24 , 25 ",
                    "PHOTO" => " 0 ",
                ),


        "crawlerDuplicationScreeningFields" => array(12,15,17,18,19,20,21,22,26),

        "crawlerDuplicationFixedFields"  => array(1,2,3,4,5,6,7,8,9,10,11,13,14,16),

        "phoneDuplicationScreeningFields" => array(23,24),

        "phoneDuplicationFixedFields" => array(25),

	"allHindiMtongues" => array(10,19,33,7,13,28),

	"allMarriedMstatus" => array('M','S','D','O','W','A'),

            "relation" => array
                (
                    "1" => "Self",
                    "2" => "Parent",
                    "3" => "Sibling",
                    "4" => "Relative/Friend",
                    "5" => "Marriage Bureau",
                    "6" => "Other",
                ),

            "family_values" => array
                (
                    "4" => "Orthodox",
                    "1" => "Conservative",
                    "2" => "Moderate",
                    "3" => "Liberal",
                ),

            "family_type" => array
                (
                    "1" => "Joint Family",
                    "2" => "Nuclear Family",
                    "3" => "Others",
                ),

            "family_status" => array
                (
                    "3" => "Rich/Affluent",
                    "2" => "Upper Middle Class",
                    "1" => "Middle Class",
                ),

            "manglik" => array
                (
                    "D" => "Don't know",
                    "M" => "Yes",
                    "A" => "Angshik (partial manglik)",
                    "N" => "No",
                ),

            "manglik_label" => array
                (
                    "M" => "Manglik",
                    "N" => "Non Manglik",
                    "D" => "Don't know",
                    "A" => "Angshik (partial manglik)",
                ),

            "mstatus" => array
                (
                    "N" => "Never Married",
                    "M" => "Married",
                    "S" => "Awaiting Divorce",
                    "D" => "Divorced",
                    "W" => "Widowed",
                    "A" => "Annulled",
                ),
			"sibling" => array
			(
				"0"=>"0",
				"1"=>"1",
				"2"=>"2",
				"3"=>"3",
				"4"=>"3+",
			),
            "children" => array
                (
                    "N" => "No",
                    "YT" => "Yes, living together",
                    "YS" => "Yes, living separately",
                ),

            "bodytype" => array
                (
                    "1" => "Slim",
                    "2" => "Average",
                    "3" => "Athletic",
                    "4" => "Heavy",
                ),

            "complexion" => array
                (
                    "1" => "Very Fair",
                    "2" => "Fair",
                    "3" => "Wheatish",
                    "4" => "Wheatish Brown",
                    "5" => "Dark",
                ),

            "smoke" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                    "O" => "Occasionally",
                ),

            "diet" => array
                (
                    "V" => "Vegetarian",
                    "N" => "Non Vegetarian",
                    "J" => "Jain",
                    "E" => "Eggetarian",
                ),

            "drink" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                    "O" => "Occasionally",
                ),

            "rstatus" => array
                (
                    "1" => "Citizen",
                    "2" => "Permanent Resident",
                    "3" => "Work Permit",
                    "4" => "Student Visa",
                    "5" => "Temporary Visa",
                ),

            "handicapped" => array
                (
                    "N" => "None",
                    "1" => "Physically Handicapped from birth",
                    "2" => "Physically Handicapped due to accident",
                    "3" => "Mentally Challenged from birth",
                    "4" => "Mentally Challenged due to accident",
                ),
			 "handicapped_mobile"=>array(
			"N" => "None",
			"1" => "Physically - From birth",
			"2" => "Physically - Due to accident",
			"3" => "Mentally - From birth",
			"4" => "Mentally - Due to accident",
			),
            "messenger_channel" => array
                (
                    "1" => "Yahoo",
                    "2" => "MSN",
                    "3" => "Skype",
                    "5" => "ICQ",
                    "6" => "Google Talk",
                    "7" => "Rediff Bol",
                ),
	    "privacy_option" => array
		(
                    "Y" => "Show to All Paid Members",
                    "C" => "Show to only Members I Accept / Express Interest In",
                    "N" => "Hide from All",
                ),

            "residency_status" => array
                (
                    "1" => "Citizen",
                    "2" => "Permanent Resident",
                    "3" => "Work Permit",
                    "4" => "Student Visa",
                    "5" => "Temporary Visa",
                ),

            "blood_group" => array
                (
                    "1" => "A+",
                    "2" => "A-",
                    "3" => "B+",
                    "4" => "B-",
                    "5" => "AB+",
                    "6" => "AB-",
                    "7" => "O+",
                    "8" => "O-",
                ),

            "nature_handicap" => array
                (
                    "1" => "Cripple",
                    "2" => "Hearing Impaired",
                    "3" => "Visually Impaired",
                    "4" => "Speech Impaired",
                    "5" => "Others",
                ),

            "work_status" => array
                (
                    "1" => "Not Working",
                    "2" => "Employed",
                    "3" => "Entrepreneur",
                    "4" => "Consultant",
                    "5" => "Student",
                    "6" => "Academia",
                    "7" => "Defence",
                    "8" => "Independent Worker/Freelancer",
                ),
            "photo_privacy" => array
                (
                    "A" => "Visible to All",
                    "C" => "Visible to contacted and accepted members",
                ),

            "havephoto_array" => array
                (
                    "Y" => "With Photo",
                    "N" => "No Photo",
                ),

            "namaz" => array
                (
                    "1" => "5 times",
                    "2" => "Only jummah",
                    "3" => "Not regular",
                    "4" => "During ramadan",
                    "5" => "None",
                ),

            "fasting" => array
                (
                    "1" => "Ramadan & Sunnah",
                    "2" => "Ramadan",
                    "3" => "None",
                ),

            "umrah_hajj" => array
                (
                    "1" => "Umrah/Hajj",
                    "2" => "Umrah",
                    "3" => "None",
                ),

            "quran" => array
                (
                    "1" => "Daily",
                    "2" => "Occasionally",
                    "3" => "On Fridays",
                    "4" => "None",
                ),

            "sunnah_beard" => array
                (
                    "1" => "Always",
                    "2" => "After Nikah",
                    "3" => "None",
                ),

            "sunnah_cap" => array
                (
                    "1" => "Always",
                    "2" => "During prayer",
                    "3" => "Occasionally",
                    "4" => "Only at functions",
                    "5" => "None",
                ),

            "sampraday" => array
                (
                    "1" => "Murthipujak",
                    "2" => "Sthanakwas",
                    "3" => "Terapanth",
                ),

            "number_owner" => array
                (
                    "1" => "Bride",
                    "2" => "Groom",
                    "3" => "Parent",
                    "4" => "Son",
                    "5" => "Daughter",
                    "6" => "Sibling",
                    "7" => "Other",
                ),

            "maththab" => array
                (
                    "1" => "Hanafi",
                    "2" => "Hanbali",
                    "3" => "Maliki",
                    "4" => "Shafi'I",
                    "5" => "Ismaili",
                    "6" => "Ithna ashariyyah",
                    "7" => "Zaidi",
                    "8" => "Dawoodi Bohra",
                ),

            "maththab_shia" => array
                (
                    "5" => "Ismaili",
                    "6" => "Ithna- ashariyyah",
                    "7" => "Zaidi",
                    "8" => "Dawoodi Bohra",
                ),

            "maththab_sunni" => array
                (
                    "1" => "Hanafi",
                    "2" => "Hanbali",
                    "3" => "Maliki",
                    "4" => "Shafi'I",
                ),

            "subscription" => array
                (
                    "F" => "Of Paid members only",
                    "70" => "Of Paid members only",
                    "D" => "Whose contact information is visible",
                    "68" => "Whose contact information is visible",
                    "O" => "Of Match point members only",
                    "79" => "Of Match point members only",
                    "Q" => "Of Match point & contact information is visible members only",
                    "81" => "Of Match point & contact information is visible members only",
                ),

            "original_subscription" => array
                (
                    "70" => "F",
                    "68" => "D",
                    "79" => "O",
                ),

            "income_map" => array
                (
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
                    
                ),

            "live_with_parents" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                    "D" => "Not Applicable",
                ),

            "career_after_marriage" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                    "D" => "Undecided",
                ),

            "hiv" => array
                (
                    "Y" => "Positive",
					"N" =>"Negative",
                ),
            "hiv_edit" => array
                (
                    "Y" => "Yes",
					"N" =>"No",
                ),

            "baptised" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "read_bible" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "offer_tithe" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "spreading_gospel" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "speak_urdu" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "zakat" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "hijab" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "hijab_marriage" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                    "D" => "Not Decided",
                ),
			"income_grouping"=>array('1'=>'Income in Indian Rupee','2'=>'Income in US Dollars'),
			"income_grouping_mapping"=> array('1'=>'15,2,3,4,5,6,16,17,18,20,22,23,24,25,26,27','2'=>'8,9,10,11,12,13,21,14'),
            "working_marriage" => array
                (
                    "Y" => "Yes",
                    "N" => "Prefer a Housewife",
                ),

            "amritdhari" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "cut_hair" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "trim_beard" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "wear_turban" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
					"O" => "Occasionally",
                ),

            "clean_shaven" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "zarathushtri" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),

            "mtongue_region_label" => array
                (
                    "4" => "North",
                    "3" => "West",
                    "2" => "South",
                    "1" => "East",
                    "0" => "Others",
                ),

            "parents_zarathushtri" => array
                (
                    "Y" => "Yes",
                    "N" => "No",
                ),
			"thalassemia" =>array
				(
					"O"=>"Major",
					"M"=>"Minor",
					"N"=>"No",
				),
			"open_to_pet" => array(
				"Y"=>"Yes",
				"N"=>"No",
			),
			"own_house" => array(
				"Y"=>"Yes",
				"N"=>"No",
			),
			"have_car" => array(
				"Y"=>"Yes",
				"N"=>"No",
			),
			"going_abroad"=> array(
				"Y"=>"Yes",
				"N"=>"No",
				"U"=>"Undecided",
			),
			"id_proof_typ"=>array(
				"V"=>"Voter ID",
				"D"=>"Drivers licence",
				"U"=>"UID",
				"P"=>"Passport",
				"N"=>"PAN No",
			),
			"user_last_activity_array" => array(
			"1" => "Active in last week", 
			"2" => "Active in last month", 
			"3" => "Active in last 2 months", 
			#"4" => "Active >2 months ago",
			),
			"india_nri_array" => array(
			"1" => "India",
			"2" => "NRI"
			),
			"viewed_array" => array(
			"V" => "Viewed",
			"N" => "Not Viewed"
			),
			"horoscope_cluster_array" => array(
			"Y" => "With Horoscope",
			),
			"photo_cluster_array" => array(
			"Y" => "With Photo",
			),
			"allHindiMtongues" => array(10,19,33,7,13,28),
			"allHindiRelatedMtongues" =>array(
			"0" => "27,10,19,33,7,13,28",
			"1" => "20,34",
			"2" => "5,6,25",
			),
			"delhiNcrCities" => array('DE00','UP25','HA03','HA02','UP12','UP47','UP48'),
			"delhiNcrStates" =>array("DE","UP","HA"),		
			"allMetros" =>array('DE00','UP25','HA03','HA02','UP12','UP47','UP48','MH04','MH12','MH28','MH29','KA02','WB05','TN02','AP03','MH08'),
			"profileAddedClusters" =>array(
			"1" => 'Last 1 week',
			"2" =>'Last 2 weeks',
			"3" =>'Last month',
			"4" =>'Last 2 months',
			),
			"matchAlertsDateClusters" =>array(
			"1" => 'Last one week',
			"2" =>'Last two weeks',
			"3" =>'Last three weeks',
			"4" =>'Last four weeks',
			),
			"horoscope_match"=>array('Y'=>'Must','N'=>'Not Necessary'),
			"number_owner_male" => array
                (
                    "2" => "Groom",
                    "3" => "Parent",
                    "6" => "Sibling",
                    "7" => "Other",
                ),
      "number_owner_female" => array
                (
                    "1" => "Bride",
                    "3" => "Parent",
                    "6" => "Sibling",
                    "7" => "Other",
                ),
       "number_owner_male_female" => array
                (
                    "1" => "Bride",
                    "2" => "Groom",
                    "3" => "Parent",
                    "6" => "Sibling",
                    "7" => "Other",
                ),
        );

//search
unset($tempVal);
fwrite($fp,");\n
        break;\n
case \"relation_other_search\":\n
        \$arr=array(\n");
foreach($otherArrays["relation"] as $key=>$val)
{
	if(!in_array($key,searchConfig::$clusterOptionsForRelation))
		$tempVal[] = $key;
}
$key = implode(",",$tempVal);
$val = 'Other';
fwrite($fp,"\"".$val."\" => \"".$key."\",\n");
//search

fwrite($fp,");\n
	break;\n
case \"manglik_status_ascii\":\n
        \$arr=array(\n");
foreach($otherArrays["manglik_label"] as $key=>$val)
        fwrite($fp,"\"".ord($key)."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;\n
case \"diet_ascii\":\n
        \$arr=array(\n");
foreach($otherArrays["diet"] as $key=>$val)
        fwrite($fp,"\"".ord($key)."\" => \"".$val."\",\n");
fwrite($fp,");\n
	break;\n
case \"children_ascii_array\":\n
        \$arr=array(\n");
foreach($otherArrays["children"] as $key=>$val)
        fwrite($fp,"\"".ord(strrev($key))."\" => \"".$val."\",\n");



foreach($otherArrays as $key=>$value){
	fwrite($fp,");\n
		break;\n
	case \"$key\":\n
		\$arr=array(\n");
	foreach($value as $key1=>$val1)
		fwrite($fp,"\"$key1\" => \"$val1\",\n");
}

fwrite($fp,");\n
                break;\n
        case \"Fto_Duplicate\":\n
                \$arr=array(\n");
foreach($negativeListFlagArray["Fto_Duplicate"] as $k=>$v)
{
		fwrite($fp,"\"$k\" => \"$v\",\n");
}

fwrite($fp,");\n
        break;\n
case \"astro_privacy\":\n
    \$arr=array(\n");
    
$privacy_arr = array(
  "Y" => "Show to others",
  "N" => "Hide Horoscope from others", 
  "D" => "Hide all Astro-Details from others", 
);

foreach($privacy_arr as $key=>$val)
    fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
    fwrite($fp,");\n
	break;\n


case \"astro_privacy_label\":\n
    \$arr=array(\n");
    
$privacy_arr = array(
  "Y" => "Horoscope shown to others",
  "N" => "Horoscope hidden from others", 
  "D" => "All astro details hidden from others", 
);

foreach($privacy_arr as $key=>$val)
    fwrite($fp,"\"".$key."\" => \"".$val."\",\n");
    fwrite($fp,");\n
	break;\n

case \"eduDppArray\":\n
//	\$arr=array(\n");
$sql="SELECT SQL_CACHE el.VALUE AS VALUE, el.LABEL AS LABEL, el.GROUPING AS GROUPING,eg.LABEL AS GROUP_LABEL FROM EDUCATION_LEVEL_NEW el, EDUCATION_GROUPING eg WHERE el.GROUPING = eg.VALUE ORDER BY eg.SORTBY,el.SORTBY";
$result=mysql_query($sql);

while($myrow=mysql_fetch_array($result))
{
	fwrite($fp,"$"."arr[\"".$myrow['GROUP_LABEL']."\"][\"".$myrow['LABEL']."\"] = \"".$myrow['VALUE']."\";\n");
}

fwrite($fp,"\ndefault:\n
				break;\n
			}\n
			if(\$returnArr)\n
				return \$arr;\n
			else\n
				return \$arr[\$value];\n
			}\n
		}\n
?>\n");
//Entries For Jhobby
//For Hobbies and interests
fwrite($fhobby,"case \"hobbies\":\n
	\$arr=array(\n");

$sql="select VALUE,LABEL from HOBBIES order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_hobby\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='HOBBY' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_interest\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='INTEREST' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_music\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='MUSIC' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_book\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='BOOK' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_movie\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='MOVIE' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_sports\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='SPORTS' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_cuisine\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='CUISINE' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_dress\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='DRESS' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_type\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,TYPE from HOBBIES order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["TYPE"] . "\",\n");
	}
fwrite($fhobby,");\n
	break;\n
case \"hobbies_language\":\n
	\$arr=array(\n");
	mysql_free_result($result);

$sql="select VALUE,LABEL from HOBBIES where TYPE='LANGUAGE' order by SORTBY";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
			fwrite($fhobby,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
	}
//For Hobbied and interests end
fwrite($fhobby,");\ndefault:\n
				break;\n
			}\n
			if(\$returnArr)\n
				return \$arr;\n
			else\n
				return \$arr[\$value];\n
			}\n
		}\n
?>\n");

include_once(JsConstants::$cronDocRoot."/lib/utils/JsSearchLabelsCreater.php");

fclose($fhobby);
fclose($fp);
