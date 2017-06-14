<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
chdir("../P");
include("../P/connect.inc");
include("../P/contact.inc");
$parameters=array("MTONGUE","HEIGHT","CASTE","MANGLIK","COUNTRY","EDUCATION_LEVEL_NEW","OCCUPATION","INCOME","MSTATUS","AGE","AGE_DIFF");

$db=connect_slave();
$dbM=connect_db();

$count=0;
$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
list($year1,$month1,$day1)=explode('-',$today);
$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";
ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,25920000);
ini_set(log_errors_max_len,0);

$timestamp=mktime(0, 0, 0, date("m")-6,date("d")-6,date("Y"));
$inactivityDate6Month=date("Y-m-d",$timestamp);

$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
list($year1,$month1,$day1)=explode('-',$today);
$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";
$mysqlObj=new Mysql;


$sql="SELECT PROFILEID,USERNAME,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,INCOME,MSTATUS,GENDER FROM newjs.JPROFILE WHERE LAST_LOGIN_DT >= DATE_SUB( now() , INTERVAL 6 MONTH)";
//$sql.=" limit 40";
$res_main=mysql_query($sql,$db)or die(mysql_error($db).$sql);
$counter=0;
while($row1=mysql_fetch_array($res_main))
{
        $my_profileid=$row1['PROFILEID'];
        $my_gender=$row1['GENDER'];
        $my_age=$row1['AGE'];
        $my_inc=get_income_sortby_ankit($row1["INCOME"]);
        $my_height=$row1['HEIGHT'];
        $counter++;

	$sql_check="SELECT (ACC_BY_ME + ACC_ME + NOT_REP + DEC_BY_ME + DEC_ME) AS SUM FROM CONTACTS_STATUS WHERE PROFILEID ='$my_profileid'";
	$res_check=mysql_query($sql_check,$db)or die(mysql_error($db).$sql_check);
	$row_check=mysql_fetch_array($res_check);
	if($row_check[0]>9 || !$row_check)
		Spam_Checker($my_profileid,$row1['USERNAME'],$db,$parameters,$my_gender,$dbM,$my_age,$my_inc,$my_height,$inactivityDate6Month);
}


function Spam_Checker($my_profileid,$username,$db,$parameters,$my_gender,$dbM,$myage,$myincome,$myheight,$inactivityDate6Month)
{
	//echo "--->>".$my_profileid."<<<----\n\n";
	global $today;

	$trendsTable="twowaymatch.TRENDS_FOR_SPAM";
	$trendsTableWithInitailValue="twowaymatch.TRENDS_FOR_SPAM_WITH_INITIAL_VALUE";

	$multiplayFactor=100;
	unset($factor_or_3);
	unset($laveshFactor);
	$contactsInTrainingPeriod=0;
	$factor_or_3["A_I_C"]=0;
	$factor_or_3["D"]=0;
	unset($needInitialValue);
	unset($tempcontactResult);

	$time_clause="TIME>='$inactivityDate6Month'";

	$row0['PROFILEID']=$my_profileid;
	$sendersIn=$my_profileid;
	$receiversIn=$my_profileid;

	$tempcontactResult=getResultSet("SENDER,TYPE",'','',$receiversIn,'',"'A','D','C'",'',$time_clause,'','','','','',"Y");
	$contactResult=getResultSet("RECEIVER,TYPE",$sendersIn,'','','',"'I','A','D','C'",'',$time_clause,'','','','','',"Y");
	$contactsInTrainingPeriod=count($contactResult)+count($tempcontactResult);
	if($contactsInTrainingPeriod<10)
		return;

	if($contactResult)
	{
		unset($receiverIdsI);
		foreach($contactResult as $key=>$value)
		{
			$receiverIdsI.="'".$contactResult[$key]['RECEIVER']."',";
		}
		unset($contactResult);
	}

	$receiverIdsI=substr($receiverIdsI,0,-1);
	if($receiverIdsI)
	{
		$sql4="select PROFILEID,AGE,MTONGUE,HEIGHT,CASTE,MANGLIK,CASE MANGLIK WHEN 'M' THEN 'M' WHEN 'A' THEN 'A' ELSE 'N' END 'NEWMANGLIK',COUNTRY_RES,CASE COUNTRY_RES WHEN 51 THEN 51 ELSE 0 END 'NEWCOUNTRY_RES' ,EDU_LEVEL_NEW,OCCUPATION,INCOME,MSTATUS ,CASE MSTATUS WHEN 'N' THEN 'N' ELSE 'M' END 'NEWMSTATUS' from  newjs.JPROFILE where PROFILEID IN ($receiverIdsI)";
		$res4=mysql_query($sql4,$db)or die(mysql_error()) ;
		while($row4=mysql_fetch_array($res4))
		{
			$factor_or_3["A_I_C"]+=1;
			$names[]=$row4['PROFILEID'];
			$caste[]=$row4['CASTE'];
			$community[]=$row4['MTONGUE'];
			$income_var=$row4['INCOME'];
			$income_vars[]=$row4['INCOME'];
			$country[]=$row4['NEWCOUNTRY_RES'];
			$education[]=$row4['EDU_LEVEL_NEW'];
			$occupation[]=$row4['OCCUPATION'];
			$mstatus[]=$row4['NEWMSTATUS'];
			$age[]=$row4['AGE'];
			$manglik[]=$row4['NEWMANGLIK'];
			$income[]=getTrendsBucket($myincome,get_income_sortby_ankit($income_var));
			$height[]=getTrendsBucket($myheight,$row4['HEIGHT']);
			$age_diff[]=getTrendsBucket($myage,$row4['AGE']);
		}
		unset($NUMI);

		foreach($income as $key=>$value)
		{
			$NUMI['INCOME'][$income[$key]]++;
		}

		foreach($occupation as $key=>$value)
		{
			$NUMI['OCCUPATION'][$occupation[$key]]++;
		}

		foreach($mstatus as $key=>$value)
		{
			$NUMI['MSTATUS'][$mstatus[$key]]++;
		}

		foreach($age as $key=>$value)
		{
			$NUMI['AGE'][$age[$key]]++;
		}

		foreach($height as $key=>$value)
		{
			$NUMI['HEIGHT'][$height[$key]]++;
		}

		foreach($manglik as $key=>$value)
		{
			$NUMI['MANGLIK'][$manglik[$key]]++;
		}

		foreach($country as $key=>$value)
		{
			$NUMI['COUNTRY'][$country[$key]]++;
		}

		foreach($education as $key=>$value)
		{
			$NUMI['EDUCATION_LEVEL_NEW'][$education[$key]]++;
		}

		foreach($caste as $key=>$value)
		{
			$NUMI['CASTE'][$caste[$key]]++;
		}

		foreach($community as $key=>$value)
		{
			$NUMI['MTONGUE'][$community[$key]]++;
		}
		foreach($age_diff as $key=>$value)
		{
			$NUMI['AGE_DIFF'][$age_diff[$key]]++;
		}
	}
	unset($caste);
	unset($community);
	unset($income);
	unset($country);
	unset($education);
	unset($occupation);
	unset($mstatus);
	unset($age);
	unset($height);
	unset($manglik);
	unset($receiversIn);
	unset($sendersIdsA);
	unset($SendersIn);
	unset($pplWhomIAccepted);
	unset($pplWhomIDeclined);
	unset($age_diff);
	unset($receiverIdsI);

	//$receiversIn=$row0["PROFILEID"];
	//$contactResult=getResultSet("SENDER,TYPE",'','',$receiversIn,'',"'A','D','C'",'',$time_clause,'','','','','',"Y");//get for sender
	$contactResult=$tempcontactResult;
	if($contactResult)
	{
		foreach($contactResult as $key=>$value)
		{
			if($contactResult[$key]['TYPE']=='A' || $contactResult[$key]['TYPE']=='C')
				$pplWhomIAccepted.="'".$contactResult[$key]['SENDER']."',";
			else
				$pplWhomIDeclined.="'".$contactResult[$key]['SENDER']."',";
		}
		unset($contactResult);
	}
	$pplWhomIAccepted=substr($pplWhomIAccepted,0,-1);

	if($pplWhomIAccepted)
	{
		$sql4="select AGE,PROFILEID,AGE,MTONGUE,HEIGHT,CASTE,MANGLIK,CASE MANGLIK WHEN 'M' THEN 'M' WHEN 'A' THEN 'A' ELSE 'N' END 'NEWMANGLIK',COUNTRY_RES,CASE COUNTRY_RES WHEN 51 THEN 51 ELSE 0 END 'NEWCOUNTRY_RES' ,EDU_LEVEL_NEW,OCCUPATION,INCOME,MSTATUS ,CASE MSTATUS WHEN 'N' THEN 'N' ELSE 'M' END 'NEWMSTATUS' from  newjs.JPROFILE where PROFILEID IN ($pplWhomIAccepted)";
		$res4=mysql_query($sql4,$db)or die(mysql_error()) ;
		while($row4=mysql_fetch_array($res4))
		{
			$factor_or_3["A_I_C"]+=1;
			$caste[]=$row4['CASTE'];
			$community[]=$row4['MTONGUE'];
			$income_var=$row4['INCOME'];
			$country[]=$row4['NEWCOUNTRY_RES'];
			$education[]=$row4['EDU_LEVEL_NEW'];
			$occupation[]=$row4['OCCUPATION'];
			$mstatus[]=$row4['NEWMSTATUS'];
			$age[]=$row4['AGE'];
			$manglik[]=$row4['NEWMANGLIK'];
			$income[]=getTrendsBucket($myincome,get_income_sortby_ankit($income_var));
			$height[]=getTrendsBucket($myheight,$row4['HEIGHT']);
			$age_diff[]=getTrendsBucket($myage,$row4['AGE']);
		}
		unset($NUMA);
		foreach($income as $key=>$value)
		{
			$NUMA['INCOME'][$income[$key]]++;
		}
		foreach($occupation as $key=>$value)
		{
			$NUMA['OCCUPATION'][$occupation[$key]]++;
		}

		foreach($mstatus as $key=>$value)
		{
			$NUMA['MSTATUS'][$mstatus[$key]]++;
		}

		foreach($age as $key=>$value)
		{
			$NUMA['AGE'][$age[$key]]++;
		}

		foreach($height as $key=>$value)
		{
			$NUMA['HEIGHT'][$height[$key]]++;
		}

		foreach($manglik as $key=>$value)
		{
			$NUMA['MANGLIK'][$manglik[$key]]++;
		}

		foreach($country as $key=>$value)
		{
			$NUMA['COUNTRY'][$country[$key]]++;
		}

		foreach($education as $key=>$value)
		{
			$NUMA['EDUCATION_LEVEL_NEW'][$education[$key]]++;
		}

		foreach($caste as $key=>$value)
		{
			$NUMA['CASTE'][$caste[$key]]++;
		}

		foreach($community as $key=>$value)
		{
			$NUMA['MTONGUE'][$community[$key]]++;
		}
		foreach($age_diff as $key=>$value)
		{
			$NUMA['AGE_DIFF'][$age_diff[$key]]++;
		}
	}
	unset($caste);
	unset($community);
	unset($income);
	unset($country);
	unset($education);
	unset($occupation);
	unset($mstatus);
	unset($age);
	unset($height);
	unset($manglik);
	unset($sendersIdsA);
	unset($sendersIdsD);
	unset($SendersIn);
	unset($age_diff);
	unset($receiverIdsI);
	$pplWhomIDeclined=substr($pplWhomIDeclined,0,-1);

	if($pplWhomIDeclined)
	{
		$sql4="select PROFILEID,AGE,MTONGUE,HEIGHT,CASTE,MANGLIK,CASE MANGLIK WHEN 'M' THEN 'M' WHEN 'A' THEN 'A' ELSE 'N' END 'NEWMANGLIK',COUNTRY_RES,CASE COUNTRY_RES WHEN 51 THEN 51 ELSE 0 END 'NEWCOUNTRY_RES' ,EDU_LEVEL_NEW,OCCUPATION,INCOME,MSTATUS ,CASE MSTATUS WHEN 'N' THEN 'N' ELSE 'M' END 'NEWMSTATUS' from  newjs.JPROFILE where PROFILEID IN ($pplWhomIDeclined)";
		unset($res4);
		unset($row4);
		$res4=mysql_query($sql4,$db)or die(mysql_error()) ;
		while($row4=mysql_fetch_array($res4))
		{
			$factor_or_3["D"]+=1;
			$caste[]=$row4['CASTE'];
			$community[]=$row4['MTONGUE'];
			$income_var=$row4['INCOME'];
			$country[]=$row4['NEWCOUNTRY_RES'];
			$education[]=$row4['EDU_LEVEL_NEW'];
			$occupation[]=$row4['OCCUPATION'];
			$mstatus[]=$row4['NEWMSTATUS'];
			$age[]=$row4['AGE'];
			$manglik[]=$row4['NEWMANGLIK'];
			$income[]=getTrendsBucket($myincome,get_income_sortby_ankit($income_var));
			$height[]=getTrendsBucket($myheight,$row4['HEIGHT']);
			$age_diff[]=getTrendsBucket($myage,$row4['AGE']);
		}
		unset($NUMD);

		foreach($income as $key=>$value)
		{
			$NUMD['INCOME'][$income[$key]]++;
		}
		foreach($occupation as $key=>$value)
		{
			$NUMD['OCCUPATION'][$occupation[$key]]++;
		}

		foreach($mstatus as $key=>$value)
		{
			$NUMD['MSTATUS'][$mstatus[$key]]++;
		}
		foreach($age_diff as $key=>$value)
		{
			$NUMD['AGE_DIFF'][$age_diff[$key]]++;
		}

		foreach($age as $key=>$value)
		{
			$NUMD['AGE'][$age[$key]]++;
		}

		foreach($height as $key=>$value)
		{
			$NUMD['HEIGHT'][$height[$key]]++;
		}

		foreach($manglik as $key=>$value)
		{
			$NUMD['MANGLIK'][$manglik[$key]]++;
		}

		foreach($country as $key=>$value)
		{
			$NUMD['COUNTRY'][$country[$key]]++;
		}

		foreach($education as $key=>$value)
		{
			$NUMD['EDUCATION_LEVEL_NEW'][$education[$key]]++;
		}

		foreach($caste as $key=>$value)
		{
			$NUMD['CASTE'][$caste[$key]]++;
		}

		foreach($community as $key=>$value)
		{
			$NUMD['MTONGUE'][$community[$key]]++;
		}
	}
	unset($temparray);
	unset($bias_string);

	//---initial value need as (i+a)/d < 3 ----
	unset($needInitialValue);
	if($factor_or_3["A_I_C"] && $factor_or_3["D"]>0)
		$laveshFactor=ceil($factor_or_3["D"]/$factor_or_3["A_I_C"]);
	if($laveshFactor>3)
		$needInitialValue=1;

	foreach($parameters as $key=>$value)
	{
		if(!$NUMA[$parameters[$key]])
			$NUMA[$parameters[$key]][]=0;
		if(!$NUMI[$parameters[$key]])
			$NUMI[$parameters[$key]][]=0;
		if(!$NUMD[$parameters[$key]])
			$NUMD[$parameters[$key]][]=0;
		$temparray=array();
		unset($temparray);

		foreach($NUMI[$parameters[$key]] as $sskey=>$i)
		{
			if($sskey || $value=='COUNTRY')
				$temparray[]=$sskey;
		}
		foreach($NUMA[$parameters[$key]] as $sskey=>$i)
		{
			if(!$temparray) 
				$temparray=array();
			if(!in_array($sskey,$temparray))
				$temparray[]=$sskey;
		}
		//NOT SURE NEED TO BE ADDED IF ONLY DEL
		foreach($NUMD[$parameters[$key]] as $sskey=>$i)
		{
			if(!$temparray)
				$temparray=array();
			if(!in_array($sskey,$temparray))
				$temparray[]=$sskey;
		}
		//NOT SURE NEED TO BE ADDED IF ONLY DEL
		$bias_string[$parameters[$key]]="|";
		unset($valsum);
		foreach($temparray as $sskey=>$i)
		{
			$denominator=(3*($NUMI[$parameters[$key]][$i]+$NUMA[$parameters[$key]][$i])+$NUMD[$parameters[$key]][$i]+20);
			$bias[$parameters[$key]][$i]=(3*($NUMI[$parameters[$key]][$i]+$NUMA[$parameters[$key]][$i])-$NUMD[$parameters[$key]][$i])/$denominator;
			$bias[$parameters[$key]][$i]=round($bias[$parameters[$key]][$i],2);
			//if($bias[$parameters[$key]][$i]>0)
				$valsum=$valsum+$bias[$parameters[$key]][$i];
			//else
				//$bias[$parameters[$key]][$i]=0;
		}
		foreach($temparray as $sskey=>$i)
		{
			//if($bias[$parameters[$key]][$i]>0)
			//{
				//$percent=round(($bias[$parameters[$key]][$i]/$valsum)*100);
				$percent=$bias[$parameters[$key]][$i]*$multiplayFactor;
				$bias_string[$parameters[$key]]=$bias_string[$parameters[$key]].$i."#".$percent."|";
			//}
		}//change ends
		if($bias_string[$parameters[$key]]=="|")
			$bias_string[$parameters[$key]]=0;
	}
	foreach($parameters as $key=>$value)
	{
		foreach($NUMI[$parameters[$key]] as $sskey=>$i)
		{
			$totalI[$parameters[$key]]+=$NUMI[$parameters[$key]][$sskey];
		}
		foreach($NUMA[$parameters[$key]] as $sskey=>$i)
		{
			$totalA[$parameters[$key]]+=$NUMA[$parameters[$key]][$sskey];
		}
	}
	foreach($parameters as $key=>$value)
	{
		unset($temparray);
		foreach($NUMI[$parameters[$key]] as $sskey=>$i)
		{
			$temparray[]=$sskey;
		}
		foreach($NUMA[$parameters[$key]] as $sskey=>$i)
		{
			if(!in_array($sskey,$temparray))
				$temparray[]=$sskey;
		  
		}
		foreach($temparray as $sskey=>$i)
		{
			$denominator=($totalI[$parameters[$key]]+$totalA[$parameters[$key]]+1);
			$NUMI[$parameters[$key]][$i]+$NUMA[$parameters[$key]][$i]."  ";
			$val1=($NUMI[$parameters[$key]][$i]+$NUMA[$parameters[$key]][$i])/$denominator;
			$val2=$val1*$val1;
			$Weight[$parameters[$key]]+=$val2;
		}
	}
	$weight_age_difference=round($Weight['AGE_DIFF'],2);
	$weight_caste=round($Weight['CASTE'],2);
	$weight_mtongue=round($Weight['MTONGUE'],2);
	$weight_age=round($Weight['AGE'],2);
	$weight_income=round($Weight['INCOME'],2);
	$weight_height=round($Weight['HEIGHT'],2);
	$weight_mstatus=round($Weight['MSTATUS'],2);
	$weight_country=round($Weight['COUNTRY'],2);
	$weight_manglik=round($Weight['MANGLIK'],2);
	$weight_education=round($Weight['EDUCATION_LEVEL_NEW'],2);
	$weight_occupation=round($Weight['OCCUPATION'],2);
	unset($bias_string['MANGLIK']);
	$bias_string['MANGLIK']['A']=$bias['MANGLIK']['A']*$multiplayFactor;
	$bias_string['MANGLIK']['N']=$bias['MANGLIK']['N']*$multiplayFactor;
	$bias_string['MANGLIK']['M']=$bias['MANGLIK']['M']*$multiplayFactor;
	unset($bias_string['MSTATUS']);
	$bias_string['MSTATUS']['M']=$bias['MSTATUS']['M']*$multiplayFactor;
	$bias_string['MSTATUS']['N']=$bias['MSTATUS']['N']*$multiplayFactor;
	unset($bias_string['COUNTRY']);
	$bias_string['COUNTRY'][51]=$bias['COUNTRY'][51]*$multiplayFactor;
	$bias_string['COUNTRY'][0]=$bias['COUNTRY'][0]*$multiplayFactor;

	$sql="SELECT I_VAL,I_VAL_CALCULATED_DATE FROM $trendsTable WHERE PROFILEID='$my_profileid'";
	$res_ival=mysql_query($sql,$dbM)or die(mysql_error($dbM).$sql);
	$row_ival=mysql_fetch_array($res_ival);
	$i_val_date=$row_ival["I_VAL_CALCULATED_DATE"];
	$i_val=$row_ival["I_VAL"];

	$insert_query="REPLACE INTO $trendsTable(PROFILEID, USERNAME, GENDER,W_CASTE, CASTE_VALUE_PERCENTILE  , W_MTONGUE, MTONGUE_VALUE_PERCENTILE , W_AGE, AGE_VALUE_PERCENTILE , W_INCOME, INCOME_VALUE_PERCENTILE , W_HEIGHT, HEIGHT_VALUE_PERCENTILE , W_EDUCATION, EDUCATION_VALUE_PERCENTILE , W_OCCUPATION, OCCUPATION_VALUE_PERCENTILE , W_CITY , CITY_VALUE_PERCENTILE , W_MSTATUS, MSTATUS_N_P, MSTATUS_M_P, W_MANGLIK, MANGLIK_M_P , MANGLIK_N_P,MANGLIK_A_P, W_NRI , NRI_M_P , NRI_N_P ,ENTRY_DT,AGE_BUCKET,I_VAL,I_VAL_CALCULATED_DATE) values ('$my_profileid' , '".stripslashes($username)."' , '$my_gender[$t]' ,'".$weight_caste."' , '".$bias_string['CASTE']."' , '".$weight_mtongue."', '".$bias_string['MTONGUE']."' , '".$weight_age_difference."' , '".$bias_string['AGE']."' , '".$weight_income."' , '".$bias_string['INCOME']."' , '".$weight_height."' , '".$bias_string['HEIGHT']."' , '".$weight_education."' , '".$bias_string['EDUCATION_LEVEL_NEW']."' , '".$weight_occupation."' , '".$bias_string['OCCUPATION']."' , '".$weight_city."' , '".$city_value_percentile_string."' , '".$weight_mstatus."' , '".$bias_string['MSTATUS']['N']."' , '".$bias_string['MSTATUS']['M']."' , '".$weight_manglik."' , '".$bias_string['MANGLIK']['M']."' , '".$bias_string['MANGLIK']['N']."' , '".$bias_string['MANGLIK']['A']."' , '".$weight_country."' , '".$bias_string['COUNTRY'][51]."' , '".$bias_string['COUNTRY'][0]."' ,'$today','".$bias_string['AGE_DIFF']."','$i_val','$i_val_date') ";
	mysql_query($insert_query,$dbM)or die(mysql_error($dbM).$nsert_query);

	if($needInitialValue==1 && ($i_val_date=='0000-00-00' || $i_val_date==''))
	{
		$receiversIn=$my_profileid;
		unset($arr);
		unset($contactResult);
		$contactResult=getResultSet("SENDER",'','',$receiversIn,'',"'I','A','D','C'",'','','','TIME DESC','','','20',"Y");
		if(count($contactResult)==20)
		{
			foreach($contactResult as $k=>$v)
			{
				$senderid=$v["SENDER"];
				$receiverid=$receiversIn;
				$arr[]=calculate_spam_score($senderid,$receiverid,$dbS,'Y');
			}
		}
		if($arr)
		{
			sort($arr);
			$i_val=$arr[16];
			$i_val_date=$today;
			if($i_val<0)
			{
				$insert_query="UPDATE $trendsTable SET I_VAL='$i_val',I_VAL_CALCULATED_DATE='$today' WHERE PROFILEID='$my_profileid'";
				mysql_query($insert_query,$dbM)or die(mysql_error($dbM).$insert_query);
			}
		}
	}
	unset($NUMI);
	unset($NUMA);
	unset($NUMD);
	unset($Weight);
	unset($bias);
	unset($totalI);
	unset($totalD);
}
?>
