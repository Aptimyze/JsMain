<?php
include_once("connect.inc");

//include("../profile/connect.inc");	//done by Shakti for JSIndicator 24 Nov, 2005

$db=connect_misdb();

if(authenticated($cid) || $JSIndicator)
{
	 if($outside)
        {
                $CMDGo='Y';
		if(!$today)
			$today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
        }



	if($CMDGo)
	{
		$smarty->assign("flag",1);
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		//if($month<10)
		//	$month="0".$month;

		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";

		// query changed by Shobha Kumari on 2005.12.26 to show unique initial count as well
		$sql="SELECT ICOUNT,GENDER,ACOUNT,DCOUNT,CCOUNT,UICOUNT,URICOUNT,SCCOUNT,SMCOUNT,DAYOFMONTH(CONTACT_DT) as dd,UNIQUE_M_A,UNIQUE_F_A,UNIQUE_MARR,NDGCOUNT,NNACOUNT,NACOUNT,REJCOUNT,TEMP_CONTACTS,TEMP_CONTACTS_DELIVERED,AUTO_CONTACTS FROM MIS.DAY_CONTACT_COUNT WHERE CONTACT_DT BETWEEN '$st_date' AND '$end_date' GROUP BY dd,GENDER";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if(mysql_num_rows($res))
		{
			while($row=mysql_fetch_array($res))	
			{
				$dd=$row['dd']-1;
				if($gender=$row['GENDER'])
				{
	//				if($gender=='M')
	//					$i=0;
					if($gender=='F')
					{
						$fng[$dd]=$row['NDGCOUNT'];
                                                $ficnt[$dd]=$row['ICOUNT'];
                                                $fngnac[$dd]=$row['NNACOUNT'];
                                                $facnt[$dd]=$row['ACOUNT'];
                                                $fngac[$dd]=$row['NACOUNT'];
                                                $fdcnt[$dd]=$row['DCOUNT'];
						$frcnt[$dd]=$row['REJCOUNT'];
                                                $fccnt[$dd]=$row['CCOUNT'];
                                                $fcnt1[$dd]=$row['ICOUNT']+$row['ACOUNT']+$row['DCOUNT']+$row['CCOUNT'];
						$fcnto[$dd]=$row['NNACOUNT']+$row['NACOUNT']+$row['NDGCOUNT']+$row['REJCOUNT'];

                                                $fng1+=$row['NDGCOUNT'];
                                                $fngnac1+=$row['NNACOUNT'];
                                                $fngac1+=$row['NACOUNT'];
						$frcnt1+=$row['REJCOUNT'];

                                                $fcnti1+=$row['ICOUNT'];
                                                $fcnta1+=$row['ACOUNT'];
                                                $fcntd1+=$row['DCOUNT'];
                                                $fcntc1+=$row['CCOUNT'];
                                                $ftot+=$row['ICOUNT']+$row['ACOUNT']+$row['DCOUNT']+$row['CCOUNT'];
						$ftoto+=$row['NNACOUNT']+$row['NACOUNT']+$row['NDGCOUNT']+$row['REJCOUNT'];
						$fi_nac[$dd]=$ficnt[$dd]+$fngnac[$dd];
						$fa_ac[$dd]=$facnt[$dd]+$fngac[$dd];
						$fa_d[$dd]=$fdcnt[$dd]+$frcnt[$dd];
						$fi_nac1+=$ficnt[$dd]+$fngnac[$dd];
						$fa_ac1+=$facnt[$dd]+$fngac[$dd];
						$fa_d1+=$fdcnt[$dd]+$frcnt[$dd];
	

						// added for unique initial contact for females
						$fuicnt[$dd]=$row['UICOUNT'];
						$fcntui1+=$row['UICOUNT']; // total unique contacts for females
						
						$furicnt[$dd]=$row['URICOUNT'];
						$fcnturi1+=$row['URICOUNT']; // total unique receivers females

						//added for same caste count initiated by females.
						$f_samecaste_count[$dd] = $row['SCCOUNT'];

						//total same caste contact initiated by females
						$f_total_samecaste += $row['SCCOUNT'];

						//added for same mtongue count initiated by females.
						$f_samemtongue_count[$dd] = $row['SMCOUNT'];

						//total same mtongue contact initiated by females
						$f_total_samemtongue += $row['SMCOUNT'];

						//Female Auto Contacts
						$fAutoContacts[$dd]+=$row["AUTO_CONTACTS"];
						$fTotalAutoContacts+=$row["AUTO_CONTACTS"];
					}
					$UMA[$dd]=$row['UNIQUE_M_A'];
					$UFA[$dd]=$row['UNIQUE_F_A'];
					$UMP[$dd]=$row['UNIQUE_MARR'];

					//Automated contacts tracking
					$autoContacts[$dd]+=$row["AUTO_CONTACTS"];
					$totalAutoContacts+=$row["AUTO_CONTACTS"];

					$tempContactsMade[$dd] = $row["TEMP_CONTACTS"];
					$tempContactsDelivered[$dd] = $row["TEMP_CONTACTS_DELIVERED"];
					// unique initial count (both for males and females)
					$uicnt[$dd]+=$row['UICOUNT'];
					// unique initial receiver count (both for males and females)
					$uricnt[$dd]+=$row['URICOUNT'];

					//total same caste contacts (both for males and females)
					$samecaste_count[$dd] += $row['SCCOUNT'];
					//total same mtongue contacts (both for males and females)
					$samemtongue_count[$dd] += $row['SMCOUNT'];

					$ng[$dd]+=$row['NDGCOUNT'];
					$ngnac[$dd]+=$row['NNACOUNT'];
					$ngac[$dd]+=$row['NACOUNT'];
					$ngrej[$dd]+=$row['REJCOUNT'];
					$totng+=$row['NDGCOUNT'];
					$totnac+=$row['NNACOUNT'];
					$totac+=$row['NACOUNT'];
					$totrej+=$row['REJCOUNT'];

					
					$icnt[$dd]+=$row['ICOUNT'];
					$acnt[$dd]+=$row['ACOUNT'];
					$dcnt[$dd]+=$row['DCOUNT'];
					$ccnt[$dd]+=$row['CCOUNT'];
					$toti1+=$row['ICOUNT'];
					$tota1+=$row['ACOUNT'];
					$totd1+=$row['DCOUNT'];
					$totc1+=$row['CCOUNT'];

					$i_nac[$dd]=$icnt[$dd]+$ngnac[$dd];
					$a_ac[$dd]=$acnt[$dd]+$ngac[$dd];
					$d_rej[$dd]=$dcnt[$dd]+$ngrej[$dd];

					$tot_i_nac+=$i_nac[$dd];
					$tot_a_ac+=$a_ac[$dd];
					$tot_d_rej+=$d_rej[$dd];

					// total unique initial contact
					$totui1+=$row['UICOUNT'];
					// total unique receiver initial contact
					$toturi1+=$row['URICOUNT'];

					// total same caste initial contact
					$total_samecaste += $row['SCCOUNT'];
					// total same mtonge initial contact
					$total_samemtongue += $row['SMCOUNT'];

					$tot1[$dd]+=$row['ICOUNT']+$row['ACOUNT']+$row['DCOUNT']+$row['CCOUNT'];
					$toto[$dd]+=$row['NDGCOUNT']+$row['NNACOUNT']+$row['NACOUNT']+$row['REJCOUNT'];
					$totg+=$row['ICOUNT']+$row['ACOUNT']+$row['DCOUNT']+$row['CCOUNT'];
					$totog+=$row['NDGCOUNT']+$row['NNACOUNT']+$row['NACOUNT']+$row['REJCOUNT'];
				}
			}
		}
		
/*************************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       24 November, 2005
                        Reason          :       This was needed for stopping further execution of this script whenever
                                        :       indicator_mis.php was used to obtain data
*************************************************************************************************************************/
                if($JSIndicator==1)
                {
                        return;
                }
/**************************************End of Addition********************************************************************/	
		for($i=0;$i<31;$i++)
		{
			//condition added to find same caste initial contact percent for females
			if($samecaste_count[$i])
			{
				$f_samecaste_perc[$i] = $f_samecaste_count[$i]/$samecaste_count[$i] * 100;
                                $f_samecaste_perc[$i] = round($f_samecaste_perc[$i],1);
			}
			// condition ends here

			//condition added to find same mtongue initial contact percent for females
			if($samemtongue_count[$i])
			{
				$f_samemtongue_perc[$i] = $f_samemtongue_count[$i]/$samemtongue_count[$i] * 100;
                                $f_samemtongue_perc[$i] = round($f_samemtongue_perc[$i],1);
			}
			// condition ends here

			// condition added to find unique initial count percent for females
			if($uicnt[$i])
			{
				$uiper[$i]=$fuicnt[$i]/$uicnt[$i] * 100;
                                $uiper[$i]=round($uiper[$i],1);
			}
			// condition ends here
			// condition added to find unique receivers of initial count percent for females
			if($uricnt[$i])
			{
				$uriper[$i]=$furicnt[$i]/$uricnt[$i] * 100;
                                $uriper[$i]=round($uriper[$i],1);
			}
			if($icnt[$i])
			{
				$iper[$i]=$ficnt[$i]/$icnt[$i] * 100;
				$iper[$i]=round($iper[$i],1);
			}
			if($ngnac[$i])
			{
				$oiper[$i]=$fngnac[$i]/$ngnac[$i] *100;
				$oiper[$i]=round($oiper[$i],1);
			}
			$sum_i[$i]=$ficnt[$i]+$fngnac[$i];
			$tot_id[$i]=$icnt[$i]+$ngnac[$i];
			if($tot_id[$i]==0)
				$per_id[$i]=0;
			else
			{
				$per_id[$i]=$sum_i[$i]/$tot_id[$i] *100;
				$per_id[$i]= round($per_id[$i],1);
			}
			if($acnt[$i])
			{
				$aper[$i]=$facnt[$i]/$acnt[$i] * 100;
				$aper[$i]=round($aper[$i],1);
			}
			if($ngac[$i])
			{
				$oaper[$i]=$fngac[$i]/$ngac[$i] *100;
				$oaper[$i]=round($oaper[$i],1);
			}
			$sum_a[$i]=$facnt[$i]+$fngac[$i];
			$tot_ad[$i]=$acnt[$i]+$ngac[$i];
			if($tot_ad[$i]==0)
				$per_ad[$i]=0;
			else
			{
				$per_ad[$i]=$sum_a[$i]/$tot_ad[$i] *100;
				$per_ad[$i]=round($per_ad[$i],1);			
			}
			if($ng[$i])
			{
				$ngper[$i]=$fng[$i]/$ng[$i] *100;
				$ngper[$i]=round($ngper[$i],1);
			}
			if($dcnt[$i])
			{
				$dper[$i]=$fdcnt[$i]/$dcnt[$i] * 100;
				$dper[$i]=round($dper[$i],1);
			}
			if($ngrej[$i])
                        {
                                $orper[$i]=$frcnt[$i]/$ngrej[$i] *100;
                                $orper[$i]=round($orper[$i],1);
                        }
                        $sum_r[$i]=$fdcnt[$i]+$frcnt[$i];
                        $tot_rd[$i]=$dcnt[$i]+$ngrej[$i];
			if($tot_rd[$i]==0)
				$per_rd[$i]=0;
			else
			{
				$per_rd[$i]=$sum_r[$i]/$tot_rd[$i] *100;
				$per_rd[$i]=round($per_rd[$i],1);
			}
			if($ccnt[$i])
			{
				$cper[$i]=$fccnt[$i]/$ccnt[$i] * 100;
				$cper[$i]=round($cper[$i],1);
			}
			if($tot1[$i])
			{
				$per1[$i]=$fcnt1[$i]/$tot1[$i] * 100;
				$per1[$i]=round($per1[$i],1);
			}
			if($toto[$i])
			{
				$pero[$i]=$fcnto[$i]/$toto[$i] *100;
				$pero[$i]=round($pero[$i],1);
			}
			if($autoContacts[$i] && $fAutoContacts[$i])
			{
				$fAutoContactsPrcntg[$i]=$fAutoContacts[$i]/$autoContacts[$i]*100;
				$fAutoContactsPrcntg[$i]=round($fAutoContactsPrcntg[$i],1);
			}
			$per_day[$i]=$per1[$i]+$pero[$i];
			$tot_day[$i]=$tot1[$i]+$toto[$i];
		}
		//find percentage of total same caste contacts initiated.
		if($total_samecaste)
		{
			$total_samecaste_perc = $f_total_samecaste/$total_samecaste * 100;
			$total_samecaste_perc = round($total_samecaste_perc,1);
		}

		//find percentage of total same caste contacts initiated.
		if($total_samemtongue)
		{
			$total_samemtongue_perc = $f_total_samemtongue/$total_samemtongue * 100;
			$total_samemtongue_perc = round($total_samemtongue_perc,1);
		}

		// find percentage of total unique contact for females
		if ($totui1)
		{
			$perui1=$fcntui1/$totui1 * 100;
                        $perui1=round($perui1,1);
		}
		if ($toturi1)
		{
			$peruri1=$fcnturi1/$toturi1 * 100;
                        $peruri1=round($peruri1,1);
		}
		if($toti1)
		{
			$peri1=$fcnti1/$toti1 * 100;
			$peri1=round($peri1,1);
		}
		if($totnac)
		{
			$peroi1=$fngnac1/$totnac * 100;
			$peroi1=round($peroi1,1);
		}
		$tot_i_nac=$toti1+$totnac;
		$per_i_nac_t=$fcnti1+$fngnac1;
		if($tot_i_nac==0)
			$per_i_nac=0;
		else
		{
			$per_i_nac=($per_i_nac_t/$tot_i_nac) *100;
			$per_i_nac= round($per_i_nac,1); 
		}	
		if($tota1)
		{
			$pera1=$fcnta1/$tota1 * 100;
			$pera1=round($pera1,1);
		}
		if($totac)
		{
			$peroa1=$fngac1/$totac *100;
			$peroa1=round($peroa1,1);
		}
		$tot_a_ac=$tota1+$totac;
		$per_a_ac_t=$fcnta1+$fngac1;
                if($tot_a_ac==0)
                        $per_a_ac=0;
                else
		{
                        $per_a_ac=($per_a_ac_t/$tot_a_ac) *100;
			$per_a_ac= round($per_a_ac,1);
		}
		if($totd1)
		{
			$perd1=$fcntd1/$totd1 * 100;
			$perd1=round($perd1,1);
		}
		if($totrej)
                {
                        $peror1=$frcnt1/$totrej *100;
                        $peror1=round($peror1,1);
                }
                $tot_d_rej=$totd1+$totrej;
		$per_d_rej_t=$fcntd1+$frcnt1;
                if($tot_d_rej==0)
                        $per_d_rej=0;
                else
		{
                        $per_d_rej=($per_d_rej_t/$tot_d_rej) *100;
			$per_d_rej= round($per_d_rej,1);
		}
		if($totc1)
		{
			$perc1=$fcntc1/$totc1 * 100;
			$perc1=round($perc1,1);
		}
		if($totng)
		{
			$perng1=$fng1/$totng *100;
			$perng1=round($perng1,1);
		}
		if($totg)
		{
			$perg=$ftot/$totg * 100;
			$perg=round($perg,1);
		}
		if($totog)
		{
			$perog=$ftoto/$totog * 100;
			$perog=round($perog,1);
		}
		if($totalAutoContacts && $fTotalAutoContacts)
		{
			$fTotalAutoContactsPrcntg=$fTotalAutoContacts/$totalAutoContacts*100;
			$fTotalAutoContactsPrcntg=round($fTotalAutoContactsPrcntg,1);
		}
		
		$tot=$totg+$totog;
		$tot_per_t=$ftot+$ftoto;
                if($tot==0)
                        $tot_per=0;
                else
		{
                        $tot_per=($tot_per_t/$tot) *100;
			$tot_per= round($tot_per,1);
		}
		$smarty->assign("per_id",$per_id);
		$smarty->assign("tot_id",$tot_id);
		$smarty->assign("oiper",$oiper);
		$smarty->assign("ngnac",$ngnac);
		$smarty->assign("per_i_nac",$per_i_nac);
		$smarty->assign("tot_i_nac",$tot_i_nac);
		$smarty->assign("peroi1",$peroi1);
		$smarty->assign("totnac",$totnac);
		$smarty->assign("per_ad",$per_ad);
		$smarty->assign("tot_ad",$tot_ad);
		$smarty->assign("oaper",$oaper);
		$smarty->assign("ngac",$ngac);
		$smarty->assign("per_a_ac",$per_a_ac);
		$smarty->assign("tot_a_ac",$tot_a_ac);
		$smarty->assign("peroa1",$peroa1);
		$smarty->assign("totac",$totac);

		$smarty->assign("per_rd",$per_rd);
                $smarty->assign("tot_rd",$tot_rd);
                $smarty->assign("orper",$orper);
                $smarty->assign("ngrej",$ngrej);
                $smarty->assign("per_d_rej",$per_d_rej);
                $smarty->assign("tot_d_rej",$tot_d_rej);
                $smarty->assign("peror1",$peror1);
                $smarty->assign("totrej",$totrej);

		$smarty->assign("perog",$perog);
		$smarty->assign("totog",$totog);
		$smarty->assign("tot_per",$tot_per);
		$smarty->assign("tot",$tot);
		$smarty->assign("pero",$pero);
		$smarty->assign("toto",$toto);
		$smarty->assign("per_day",$per_day);
		$smarty->assign("tot_day",$tot_day);
		

		$smarty->assign("ngper",$ngper);
		$smarty->assign("ng",$ng);
		$smarty->assign("perng1",$perng1);
		$smarty->assign("totng",$totng);
			

		$smarty->assign("uicnt",$uicnt);
		$smarty->assign("uricnt",$uricnt);
		$smarty->assign("icnt",$icnt);
		$smarty->assign("acnt",$acnt);
		$smarty->assign("dcnt",$dcnt);
		$smarty->assign("ccnt",$ccnt);

		$smarty->assign("totui1",$totui1);
		$smarty->assign("toturi1",$toturi1);
		$smarty->assign("toti1",$toti1);
		$smarty->assign("tota1",$tota1);
		$smarty->assign("totd1",$totd1);
		$smarty->assign("totc1",$totc1);
		$smarty->assign("totg",$totg);
		$smarty->assign("tot1",$tot1);
		$smarty->assign("UMP",$UMP);
		$smarty->assign("UMA",$UMA);
		$smarty->assign("UFA",$UFA);
		$smarty->assign("uiper",$uiper);
		$smarty->assign("uriper",$uriper);
		$smarty->assign("iper",$iper);
		$smarty->assign("aper",$aper);
		$smarty->assign("dper",$dper);
		$smarty->assign("cper",$cper);
		$smarty->assign("tempContactsMade",$tempContactsMade);
		$smarty->assign("tempContactsDelivered",$tempContactsDelivered);
		$smarty->assign("cper",$cper);

		$smarty->assign("perui1",$perui1);
		$smarty->assign("peruri1",$peruri1);
		$smarty->assign("peri1",$peri1);
		$smarty->assign("pera1",$pera1);
		$smarty->assign("perd1",$perd1);
		$smarty->assign("perc1",$perc1);
		$smarty->assign("perg",$perg);
		$smarty->assign("per1",$per1);

		//Added by sriram.
		$smarty->assign("f_samecaste_perc",$f_samecaste_perc);
		$smarty->assign("f_samemtongue_perc",$f_samemtongue_perc);
		$smarty->assign("samecaste_count",$samecaste_count);
		$smarty->assign("samemtongue_count",$samemtongue_count);
		$smarty->assign("total_samecaste_perc",$total_samecaste_perc);
		$smarty->assign("total_samemtongue_perc",$total_samemtongue_perc);
		$smarty->assign("total_samecaste",$total_samecaste);
		$smarty->assign("total_samemtongue",$total_samemtongue);
		//Addition ends Here.

		//Auto Contacts
		$smarty->assign("autoContacts",$autoContacts);
		$smarty->assign("fAutoContactsPrcntg",$fAutoContactsPrcntg);
		$smarty->assign("totalAutoContacts",$totalAutoContacts);
		$smarty->assign("fTotalAutoContactsPrcntg",$fTotalAutoContactsPrcntg);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
                $smarty->display("daily_contact_count.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("daily_contact_count.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
