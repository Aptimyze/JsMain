<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/SugarScoring.class.php");

	$parameter = "GENDER_C,RELIGION_C,CASTE_C,MOTHER_TONGUE_C,MARITAL_STATUS_C,OCCUPATION_C,EDUCATION_C,INCOME_C,AGE_C,HAVE_PHOTO_C";
	$myDb=connect_slave();
	$myDb_master=connect_db();
	mysql_query('set session wait_timeout=50000',$myDb);
	mysql_query('set session wait_timeout=50000',$myDb_master);
	$process_date = date("Y-m-d",time()-86400);

	$sql1 = "select SQL_CACHE weight,param from sugarcrm.weight where gender='F'";
        $res1 = mysql_query_decide($sql1,$myDb) or die($sql1.mysql_error($myDb));
        while($row1 = @mysql_fetch_array($res1))
        {
        	$param=$row1["param"];
                $weight_arr_f["$param"]=$row1["weight"];
        }
	$sql2 = "select SQL_CACHE weight,param from sugarcrm.weight where gender='M'";
        $res2 = mysql_query_decide($sql2,$myDb) or die($sql1.mysql_error($myDb));
        while($row2 = @mysql_fetch_array($res2))
        {
        	$param=$row2["param"];
                $weight_arr_m["$param"]=$row2["weight"];
        }
        $sql_i = "select intercept,gender from sugarcrm.intercept";
        $res_i = mysql_query_decide($sql_i,$myDb) or die($sql.mysql_error($myDb));
        while($row_i = @mysql_fetch_array($res_i))
	{
        	$gender_i=$row_i["gender"];
		$interceptArr[$gender_i]=$row_i["intercept"];
	}
	
	$sql="(SELECT id FROM sugarcrm.leads WHERE date_entered>='$process_date 00:00:00' and date_entered<'$process_date 23:59:59') UNION (SELECT id FROM sugarcrm.leads WHERE date_modified>='$process_date 00:00:00' and date_modified<'$process_date 23:59:59')";
	//$sql="SELECT id FROM sugarcrm.leads";
	$result = mysql_query_decide($sql,$myDb) or die($sql.mysql_error($myDb));
	while($row = mysql_fetch_array($result))
		$lead_arr[] = $row["id"];
	if(count($lead_arr)>0)
	{
		for($j=0;$j<count($lead_arr);$j++)
		{
			$valid=1;
			$leadid = $lead_arr[$j];
			$scorevars = new Sugar_Variables($leadid,$myDb,$parameter);
			$bias_lead = lead_bias($scorevars,$myDb);
			if($scorevars->getGENDER_C()=='F')
			{
				$weight_arr=$weight_arr_f;
				$min_score=-20;
				$max_score=205;
			}
			elseif($scorevars->getGENDER_C()=='M')
			{
				$weight_arr=$weight_arr_m;
				$min_score=-15.3;
                                $max_score=61.6;
			}
			else
                                $valid=0;
			if($valid)
                        {
				$score = lead_score($bias_lead,$weight_arr,$scorevars->getGENDER_C(),$myDb);
				$new_score = 262 + ((($score-$min_score)/($max_score-$min_score))*(384/12))*12;
				if($new_score!=0)
				{
					$sqli = "UPDATE sugarcrm.leads_cstm SET score_c=$new_score WHERE id_c='$leadid'";
			        	mysql_query_decide($sqli,$myDb_master) or die($sqli.mysql_error($myDb_master));
				}
			}
			unset($leadid);
			unset($scorevars);
			unset($bias_lead);
			unset($weight_arr);
		}
	}
	/**
        * This function is used to return the bias array of the lead using variables depending on gender.
        */
	function lead_bias($scorevars,$myDb)
	{
		unset($bias_arr);
		$bias_arr["RELIGION_C"]=$scorevars->giveVariable_bias('RELIGION_C',$scorevars->RELIGION_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["CASTE_C"]=$scorevars->giveVariable_bias('CASTE_C',$scorevars->CASTE_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["MOTHER_TONGUE_C"]=$scorevars->giveVariable_bias('MOTHER_TONGUE_C',$scorevars->MOTHER_TONGUE_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["MARITAL_STATUS_C"]=$scorevars->giveVariable_bias('MARITAL_STATUS_C',$scorevars->MARITAL_STATUS_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["OCCUPATION_C"]=$scorevars->giveVariable_bias('OCCUPATION_C',$scorevars->OCCUPATION_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["EDUCATION_C"]=$scorevars->giveVariable_bias('EDUCATION_C',$scorevars->EDUCATION_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["INCOME_C"]=$scorevars->giveVariable_bias('INCOME_C',$scorevars->INCOME_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["AGE_C"]=$scorevars->giveVariable_bias('AGE_C',$scorevars->AGE_C,$scorevars->getGENDER_C(),$myDb);
		$bias_arr["HAVE_PHOTO_C"]=$scorevars->giveVariable_bias('HAVE_PHOTO_C',$scorevars->HAVE_PHOTO_C,$scorevars->getGENDER_C(),$myDb);
		return $bias_arr;
	}
	/**
        * This function is used to return the score of the lead using variables depending on gender.
        */
	function lead_score($bias_arr,$weight_arr,$gtype,$myDb)
	{
		global $interceptArr;
		//$sql = "select intercept from sugarcrm.intercept where gender='$gtype'";
                //$res = mysql_query_decide($sql,$myDb) or die($sql.mysql_error($myDb));
                //if($row = @mysql_fetch_array($res))
                //        $intercept=$row["intercept"];
		$intercept=$interceptArr[$gtype];	
		$leadscore = $weight_arr["RELIGION_C"]*$bias_arr["RELIGION_C"]+$weight_arr["CASTE_C"]*$bias_arr["CASTE_C"]+$weight_arr["MOTHER_TONGUE_C"]*$bias_arr["MOTHER_TONGUE_C"]+$weight_arr["MARITAL_STATUS_C"]*$bias_arr["MARITAL_STATUS_C"]+$weight_arr["OCCUPATION_C"]*$bias_arr["OCCUPATION_C"]+$weight_arr["EDUCATION_C"]*$bias_arr["EDUCATION_C"]+$weight_arr["INCOME_C"]*$bias_arr["INCOME_C"]+$weight_arr["AGE_C"]*$bias_arr["AGE_C"]+$weight_arr["HAVE_PHOTO_C"]*$bias_arr["HAVE_PHOTO_C"]+$intercept;
		return $leadscore;
	}
?>
