<?php
include("connect.inc");
$db=connect_misdb();


if($Submit)
{
	$date1=$year1."-".$month1."-".$day1;
	$date2=$year2."-".$month2."-".$day2;
	if($options=='SUBSCRIPTION')	
	{
		$table="MIS.CONTACT_BREAKDOWN_SUBSCRIPTION";
		$subscription_value=array(0=>'Evalue',1=>'Eclassified',2=>'Erishta',3=>'Free');

	}
	elseif($options=='MTONGUE')
	{
		$table="MIS.CONTACT_BREAKDOWN_MTONGUE";
		$sql="SELECT DISTINCT VALUE ,SMALL_LABEL FROM newjs.MTONGUE";
	        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	        while($row=mysql_fetch_array($res))
	        {
	                $mtongue_value[$row['VALUE']]=$row['SMALL_LABEL'];
	        }
	}
	elseif($options=='COUNTRY_RES')
	{
		$table="MIS.CONTACT_BREAKDOWN_COUNTRY";
		$country_value['51']="India";
		$country_value['125']="UAE";
		$country_value['128']="USA";
		$country_value['22']="Canada";
		$country_value['126']="UK";
		$country_value['7']="Australia";
		$country_value['82']="New zealand";
		$country_value['70']="Malaysia";
		$country_value['63']="Kuwait";
		$country_value['99']="Saudi Arabia";
		$country_value['103']="Singapore";
		$country_value['80']="Nepal";
		$country_value['88']="Pakistan";
		$country_value['11']="Bangladesh";
		$country_value['52']="Indonesia";
		$country_value['57']="Italy";
		$country_value['42']="Germany";
		$sql="SELECT DISTINCT VALUE ,LABEL FROM newjs.COUNTRY";
	        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
	        {
			if(array_key_exists($row['VALUE'],$country_value));
			else
		                $country_value[$row['VALUE']]=$row['LABEL'];
	        }

	}
	$sql="SELECT SENDER,RECEIVER,COUNT FROM $table where DATE between '$date1' and '$date2'";
	$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	while($myrow=mysql_fetch_array($result))
	{
		$sender=$myrow['SENDER'];
		$receiver=$myrow['RECEIVER'];
		$count=$myrow['COUNT'];
		$info[$sender][$receiver]+=$count;
		$tot_send[$sender]+=$count;
		$tot_receive[$receiver]+=$count;
		$total+=$count;
	}
	

	if(is_array($subscription))
	foreach ($subscription as $key =>$value)
	{
		if($key=='Evalue')
			$key=0;
		elseif($key=='Eclassified')
			$key=1;
		elseif($key=='Erishta')
			$key=2;
		elseif($key=='Free')
			$key=3;
		foreach ($value as $key2 =>$value2)
		{
			if($key2=='Evalue')
				$key2=0;
			elseif($key2=='Eclassified')
				$key2=1;
			elseif($key2=='Erishta')
				$key2=2;
			elseif($key2=='Free')
				$key2=3;
			$subscription_final[$key][$key2]=$value2;
			$sub_send[$key]+=$value2;
                        $sub_receive[$key2]+=$value2;
			$sub_tot+=$value2;

		}
	}

//	print_r($info);
//	print_r($mtongue);	
//	print_r($country_res);	
//	print_r($subscription);	
//	print_r($paid);
//	print_r($subscription_final);	
	$smarty->assign("info",$info);	
	$smarty->assign("options",$options);
	$smarty->assign("mtongue",$mtongue);
	$smarty->assign("mtongue_value",$mtongue_value);
	$smarty->assign("country",$country_res);
	$smarty->assign("country_value",$country_value);
	$smarty->assign("subscription",$subscription_final);
	$smarty->assign("subscription_value",$subscription_value);

	$smarty->assign("total",$total);
	$smarty->assign("tot_send",$tot_send);
	$smarty->assign("tot_receive",$tot_receive);
	$smarty->assign("date1",$date1);
	$smarty->assign("date2",$date2);
	$smarty->assign("flag",'1');
	$smarty->assign("cid",$cid);
	$smarty->display("contact_breakdown.htm");
}
else
{	
	for($i=0;$i<31;$i++)
	{
		$ddarr[$i]=$i+1;
	}
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}

	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("cid",$cid);
	$smarty->display("contact_breakdown.htm");
}
?>
