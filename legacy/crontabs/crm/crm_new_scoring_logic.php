<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Scoring.class.php");

	$mysqlObj=new Mysql;
	$parameter = "USERNAME,GENDER,MTONGUE,RELATION,COUNTRY_RES,CITY_RES,ENTRY_DT,DRINK,SMOKE,BTYPE,DIET,MANGLIK,HAVEPHOTO,SHOW_HOROSCOPE,AGE,YOURINFO,FATHER_INFO,SIBLING_INFO,JOB_INFO,ACTIVATED,INCOME,SUBSCRIPTION,LAST_LOGIN_DT,SOURCE";
        global $noOfActiveServers;

        for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
        {
		$myDb=connect_737();
		mysql_query('set session wait_timeout=50000',$myDb);
		$shDbName=getActiveServerName($activeServerId,"slave");
                $shDb=$mysqlObj->connect($shDbName);
		mysql_query('set session wait_timeout=50000',$shDb);
		$sql = "SELECT DISTINCT(PROFILEID) FROM LOGIN_HISTORY WHERE LOGIN_DT>=DATE_SUB(CURDATE(),INTERVAL 60 DAY)";
		$res = mysql_query_decide($sql,$shDb) or die($sql.mysql_error($shDb));
		while($row = mysql_fetch_array($res))
		{
			$pid = $row['PROFILEID'];
			$sqlf = "SELECT ALLOTMENT_AVAIL,TIMES_TRIED FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID=$pid";
                        $resf = mysql_query_decide($sqlf,$myDb) or die($sqlf.mysql_error($myDb));
                        $rowf = mysql_fetch_array($resf);
			// removed online/offline check
			//$sqlfj = "SELECT CRM_TEAM FROM newjs.JPROFILE WHERE PROFILEID=$pid";
                        //$resfj = mysql_query_decide($sqlfj,$myDb) or die($sqlfj.mysql_error($myDb));
                        //$rowfj = mysql_fetch_array($resfj);
			//if($rowf['ALLOTMENT_AVAIL']=='Y' && $rowf['TIMES_TRIED']<3 && $rowfj['CRM_TEAM']=='online')
			if($rowf['ALLOTMENT_AVAIL']=='Y' && $rowf['TIMES_TRIED']<3)
				$pro_arr[] = $pid;
		}
		for($i=0;$i<count($pro_arr);$i++)
		{
			$proid = $pro_arr[$i];
			$sql1 = "SELECT COUNT(*) AS cnt FROM billing.PAYMENT_DETAIL WHERE PROFILEID=$proid AND STATUS='DONE'";
                        $res1 = mysql_query_decide($sql1,$myDb) or die($sql1.mysql_error($myDb));
                        $row1 = mysql_fetch_array($res1);
			if($row1['cnt'])
				$repeat_arr[] = $proid;
			else        
				$first_arr[]= $proid;
		}
		unset($pro_arr);
		$maDb=connect_db();
		mysql_query('set session wait_timeout=50000',$maDb);
		if(count($first_arr)>0)
		{
			for($j=0;$j<count($first_arr);$j++)
			{
				unset($profileid);
				$profileid = $first_arr[$j];
				$scorevars = new Scoring_Variables($profileid,$myDb,$parameter);
				$attparams = new Attribute_Parameters($scorevars,$myDb);
				$behparams = new Behaviour_Parameters($scorevars,$myDb,$shDb);
				$bias_first = profile_bias($attparams,$behparams,'F',$myDb);
				$sql1 = "select SQL_CACHE weight,param from scoring.weight where payment_type='F'";
				$res1 = mysql_query_decide($sql1,$myDb) or die($sql1.mysql_error($myDb));
				while($row1 = @mysql_fetch_array($res1))
				{
					$param=$row1["param"];
					$weight_arr_f["$param"]=$row1["weight"];
				}
				$attscore = profile_score($bias_first,$weight_arr_f,'A','F',$myDb);
				$behscore = profile_score($bias_first,$weight_arr_f,'B','F',$myDb);
				$p_att = exp($attscore)/(1+exp($attscore));
				$p_beh = exp($behscore)/(1+exp($behscore));
				$sql2 = "select SQL_CACHE * from scoring.pop_users where payment_type='F'";
                                $res2 = mysql_query_decide($sql2,$myDb) or die($sql1.mysql_error($myDb));
                                while($row2 = @mysql_fetch_array($res2))
                                {
                                        $param=$row2["param"];
                                        $total=$row2["total"];
					$paid=$row2["paid"];
					if($param == 'T')
					{
						$pop_tot_users=$total;
						$pop_paid_users=$paid;
					}
					else
					{
						$model_pop_tot_users=$total;
						$model_pop_paid_users=$paid;
					}
                                }
				$sfactor = round((($pop_tot_users/$pop_paid_users)/($model_pop_tot_users/$model_pop_paid_users)),2);
				$prob_4paidest=round(((($p_att+2*$p_beh)/3)/$sfactor),3);
				$score=final_cal($prob_4paidest);
				if($score>600)
					$score=600;
				if($score<252)
					$score=252;
				if($score)
				{
					$sql_up = "update incentive.MAIN_ADMIN_POOL set ANALYTIC_SCORE='$score',CUTOFF_DT=now() where PROFILEID='$profileid'";
					mysql_query_decide($sql_up,$maDb) or die($sql_up.mysql_error($maDb));
				}
				unset($scorevars);
                                unset($attparams);
                                unset($behparams);
                                unset($bias_first);
				unset($weight_arr_f);
			}
		}
                unset($first_arr);
		if(count($repeat_arr)>0)
		{
			for($j=0;$j<count($repeat_arr);$j++)
			{
				unset($profileid);
				$profileid = $repeat_arr[$j];
				$scorevars = new Scoring_Variables($profileid,$myDb,$parameter);
				$attparams = new Attribute_Parameters($scorevars,$myDb);
				$behparams = new Behaviour_Parameters($scorevars,$myDb,$shDb);
				$bias_repeat = profile_bias($attparams,$behparams,'R',$myDb);
				$sql1 = "select SQL_CACHE weight,param from scoring.weight where payment_type='R'";
				$res1 = mysql_query_decide($sql1,$myDb) or die($sql1.mysql_error($myDb));
				while($row1 = @mysql_fetch_array($res1))
				{
					$param=$row1["param"];
					$weight_arr_r["$param"]=$row1["weight"];
				}
				$attscore = profile_score($bias_repeat,$weight_arr_r,'A','R',$myDb);
				$behscore = profile_score($bias_repeat,$weight_arr_r,'B','R',$myDb);
				$p_att = exp($attscore)/(1+exp($attscore));
				$p_beh = exp($behscore)/(1+exp($behscore));
				$sql2 = "select SQL_CACHE * from scoring.pop_users where payment_type='R'";
                                $res2 = mysql_query_decide($sql2,$myDb) or die($sql1.mysql_error($myDb));
                                while($row2 = @mysql_fetch_array($res2))
                                {
                                        $param=$row2["param"];
                                        $total=$row2["total"];
                                        $paid=$row2["paid"];
                                        if($param == 'T')
                                        {
                                                $pop_tot_users=$total;
                                                $pop_paid_users=$paid;
                                        }
                                        else
                                        {
                                                $model_pop_tot_users=$total;
                                                $model_pop_paid_users=$paid;
                                        }
                                }
                                $sfactor = round((($pop_tot_users/$pop_paid_users)/($model_pop_tot_users/$model_pop_paid_users)),2);
				$prob_4paidest=round(((($p_att+2*$p_beh)/3)/$sfactor),3);
				$score=final_cal($prob_4paidest);
                                if($score>600) 
                                        $score=600;
                                if($score<252) 
                                        $score=252;
				if($score)
                                {
					$sql_up = "update incentive.MAIN_ADMIN_POOL set ANALYTIC_SCORE='$score',CUTOFF_DT=now() where PROFILEID='$profileid'";
        	                        mysql_query_decide($sql_up,$maDb) or die($sql_up.mysql_error($maDb));
				}
				unset($scorevars);
                                unset($attparams);
                                unset($behparams);
                                unset($bias_repeat);
                                unset($weight_arr_r);
			}
		}
                unset($repeat_arr);
	}
	$sqlc = "SELECT COUNT(*) AS CNT FROM incentive.MAIN_ADMIN_POOL WHERE ANALYTIC_SCORE !=0 AND CUTOFF_DT=CURDATE()";
        $resc = mysql_query_decide($sqlc,$maDb) or die($sqlc.mysql_error($maDb));
        $rowc = @mysql_fetch_array($resc);
	$total_count = $rowc['CNT'];
        $msg="$total_count";
        $to="vibhor.garg@jeevansathi.com,kasa.shirish@naukri.com";
        $bcc="vibhor.garg@jeevansathi.com";
        $sub="Scoring Algo Count";
        $from="From:vibhor.garg@jeevansathi.com";
        $from .= "\r\nBcc:$bcc";
        mail($to,$sub,$msg,$from);
	/**
        * This function is used to return the bias array of the profile using attribute & behaviour parameters depending on payment type.
        */
	function profile_bias($attparams,$behparams,$ptype,$myDb)
	{
		unset($bias_arr);
		$bias_arr["photo"]=$attparams->giveAttributeParameter_bias_single('photo',$attparams->getPHOTO(),$ptype,$myDb);
		$bias_arr["profilepostedby"]=$attparams->giveAttributeParameter_bias_single('profilepostedby',$attparams->getPROFILE_POSTEDBY(),$ptype,$myDb);
		$bias_arr["profile_len"]=$attparams->giveAttributeParameter_bias_single('profile_len',$attparams->getPROFILE_LEN(),$ptype,$myDb);
		$bias_arr["city"]=$attparams->giveAttributeParameter_bias_single('city',$attparams->getCITY(),$ptype,$myDb);
		$bias_arr["community"]=$attparams->giveAttributeParameter_bias_single('community',$attparams->getCOMMUNITY(),$ptype,$myDb);
		$bias_arr["income_gender"]=$attparams->giveAttributeParameter_bias_double('incgen',$attparams->getINCOME_GENDER(),$ptype,$myDb);
		$bias_arr["age_gender"]=$attparams->giveAttributeParameter_bias_double('agegen',$attparams->getAGE_GENDER(),$ptype,$myDb);
		$bias_arr["community_fish"]=$attparams->giveAttributeParameter_bias_double('commfish',$attparams->getCOMMUNITY_FISH(),$ptype,$myDb);
		$bias_arr["fieldsfilled"]=$attparams->giveAttributeParameter_bias_single('fieldsfilled',$attparams->getFIELDSFILLED(),$ptype,$myDb);
		$bias_arr["tenure"]=$attparams->giveAttributeParameter_bias_single('tenure',$attparams->getTENURE(),$ptype,$myDb);
		$bias_arr["intrest_last7"]=$behparams->giveBehaviourParameter_bias_single('intrest_last7',$behparams->getINTREST_LAST7(),$ptype,$myDb);
		$bias_arr["accept_last7"]=$behparams->giveBehaviourParameter_bias_single('accept_last7',$behparams->getACCEPT_LAST7(),$ptype,$myDb);
		$bias_arr["decline_last7"]=$behparams->giveBehaviourParameter_bias_single('decline_last7',$behparams->getDECLINE_LAST7(),$ptype,$myDb);
		$bias_arr["login_last7"]=$behparams->giveBehaviourParameter_bias_single('login_last7',$behparams->getLOGIN_LAST7(),$ptype,$myDb);
		$bias_arr["max_payment_page"]=$behparams->giveBehaviourParameter_bias_single('max_payment_page',$behparams->getMAX_PAYMENT_PAGE(),$ptype,$myDb);
		if($ptype == 'R')
			$bias_arr["time_since_last_pay_memtype"]=$behparams->giveBehaviourParameter_bias_double('time_since_last_pay_memtype',$behparams->getTIME_SINCE_LAST_PAY_MEMTYPE(),$myDb);
		return $bias_arr;
	}
	/**
        * This function is used to return the bias array of the profile using attribute & behaviour parameters depending on payment type.
        */
	function profile_score($bias_arr,$weight_arr,$param_type,$ptype,$myDb)
	{
		$sql = "select intercept from scoring.intercept where param_type='$param_type' AND payment_type='$ptype'";
                $res = mysql_query_decide($sql,$myDb) or die($sql.mysql_error($myDb));
                if($row = @mysql_fetch_array($res))
                        $intercept=$row["intercept"];
		if($param_type== 'A')
		{
			$attscore = $weight_arr["photo"]*$bias_arr["photo"]+$weight_arr["profilepostedby"]*$bias_arr["profilepostedby"]+$weight_arr["profile_len"]*$bias_arr["profile_len"]+$weight_arr["city"]*$bias_arr["city"]+$weight_arr["community"]*$bias_arr["community"]+$weight_arr["income_gender"]*$bias_arr["income_gender"]+$weight_arr["age_gender"]*$bias_arr["age_gender"]+$weight_arr["community_fish"]*$bias_arr["community_fish"]+$weight_arr["fieldsfilled"]*$bias_arr["fieldsfilled"]+$weight_arr["tenure_A"]*$bias_arr["tenure"]+$intercept;
			return $attscore;
		}
		if($param_type== 'B')
		{
			$behscore = $weight_arr["intrest_last7"]*$bias_arr["intrest_last7"]+$weight_arr["accept_last7"]*$bias_arr["accept_last7"]+$weight_arr["decline_last7"]*$bias_arr["decline_last7"]+$weight_arr["login_last7"]*$bias_arr["login_last7"]+$weight_arr["max_payment_page"]*$bias_arr["max_payment_page"]+$weight_arr["tenure_B"]*$bias_arr["tenure"]+$weight_arr["time_since_last_pay_memtype"]*$bias_arr["time_since_last_pay_memtype"]+$intercept;
			return $behscore;
		}
	}
	/**
        * This function is used to return the score of the profile using final probability.The score must be in multiple of 12.
        */
        function final_cal($prob_4paidest)
        {
		$x = 348*($prob_4paidest-0.002)/0.198;
		$qotient = intval($x/12);
		$near_mul = $qotient*12;
		$remainder = $x - $near_mul;
		if($remainder<6)
			$y=$near_mul;
		else
			$y=$near_mul+12;
		$score = 252+$y;
		return $score;
	}
?>
