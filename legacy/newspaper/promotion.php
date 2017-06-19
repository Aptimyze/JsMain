<?php
/****************************************************************************************************************************
*	FILENAME	   : promotionmis.php
*	INCLUDED           : connect.inc 
*			     functions : 
*			     db_connect()     : To connect to localhost 
*			     dbsql2_connect() : To connect to /tmp/sql2.sock
*			     getname()        : To get the name of the person logged in.
*       DESCRIPTION        : Displays the details of the promotions via Newspaper month and day wise depending upon 
*			     the user's choice.
****************************************************************************************************************************/

	include("connect.inc");

	dbsql2_connect();

	$db1		= db_connect();
	$db2		= dbsql2_connect();
	$username	= getname($cid);

        if ($submit)
	{
			
			$mmarr    =  array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$modearr  =  array ('N','A');
			
			$sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%NP%' OR PRIVILAGE LIKE '%SMSP%'OR PRIVILAGE LIKE '%AFF%' ";
			$res = mysql_query($sql) or die("$sql".mysql_error());
			while ($row=mysql_fetch_array($res))
			{
				$userarr[] = $row["USERNAME"];
		
			}
			
			if ($criteria == 'M')                        // To display the data monthwise
			{					
				$mflag = 1;				

				$sql = "SELECT ENTRYBY , MODE , COUNT(*) AS CNT , MONTH(ENTRYTIME) AS mm  FROM jsadmin.AFFILIATE_MAIN WHERE  ENTRYTIME BETWEEN '$myear-01-01' AND '$myear-12-31' AND MODE IN ('N','A')  GROUP BY ENTRYBY ,  mm , MODE";

				$res = mysql_query($sql) or die("$sql".mysql_error());
			
				$conv_sql = "SELECT COUNT(*) AS CNT, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, MONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as mm , jsadmin.AFFILIATE_MAIN.ENTRYBY AS ENTRYBY FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME BETWEEN '$myear-01-01' AND '$myear-12-31' GROUP BY newjs.JPROFILE.SUBSCRIPTION , mm , jsadmin.AFFILIATE_MAIN.ENTRYBY";

				$conv_res = mysql_query($conv_sql,$db1) or die("$sql".mysql_error());

		        	while ($row = mysql_fetch_array($res))
				{
					$mode	 = $row["MODE"]; 
					$mm 	 = $row["mm"] - 1;
					$user    = $row["ENTRYBY"];

					$i = array_search($mode ,$modearr);

					if (in_array($user,$userarr))
					{
						$k = array_search($user,$userarr);

						$totmodecount[$i]	+= $row["CNT"];
						$totmmcount[$mm]	+= $row["CNT"];
						$mmcount2[$k][$i][$mm]	 = $row["CNT"];
						$modecount2[$k][$mm][$i] = $row["CNT"];
						$totmmcount2[$k][$i]	+= $mmcount2[$k][$i][$mm];
					        $totmodecount2[$k][$mm]	+= $modecount2[$k][$mm][$i]; 
						$total[$k]		+= $row["CNT"];
						$grandtotal		+= $row["CNT"];
					
					}

				}
	
				while ($conv_row = mysql_fetch_array($conv_res))
				{	
								
					$sub = $conv_row["SUBSCRIPTION"];
					$mm  = $conv_row["mm"]-1;
					$conv_user = $conv_row["ENTRYBY"];

					if ($sub == '')	  		// If the user is just a  registered member					
						$i=0;
					else
						$i=1;                   // If the user is a paid member
										
					if (in_array($conv_user,$userarr))
					{		
						$k = array_search($conv_user,$userarr);

						$conv_subcount[$k][$i][$mm]	 = $conv_row["CNT"];
						$conv_mmcount[$k][$mm][$i]	 = $conv_row["CNT"];
						$conv_totsubcount[$k][$i]	+= $conv_subcount[$k][$i][$mm];
						$conv_totmmcount[$k][$mm]	+= $conv_mmcount[$k][$mm][$i];
						$conv_total[$k]			+= $conv_row["CNT"];
					}
					
				}


			}
		
			if ($criteria == 'D')  			// To display the data datewise
			{
				$db2 = dbsql2_connect();

				$dflag = 1;
				$month = $dmonth-1;

				for ($i = 0; $i < 31; $i++)
					$ddarr[$i] = $i + 1;
				
				$sql = "SELECT ENTRYBY , MODE , COUNT(*) AS CNT , DAYOFMONTH(ENTRYTIME) AS dd FROM jsadmin.AFFILIATE_MAIN";
				if ($dmonth<=9)
					$sql.=" WHERE ENTRYTIME BETWEEN '$dyear-0$dmonth-01' AND '$dyear-0$dmonth-31' ";
				else
					$sql.=" WHERE ENTRYTIME BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' ";
				$sql.=" AND MODE IN ('N','A') GROUP BY ENTRYBY , dd , MODE ";

				$res = mysql_query($sql) or die("$sql".mysql_error());

				while ($row = mysql_fetch_array($res))
                                {
                                        $mode	 = $row["MODE"];
                                        $dd	 = $row["dd"] - 1;
                                        $user    = $row["ENTRYBY"];
					$i = array_search($mode ,$modearr);

					if (in_array($user,$userarr))
                                        {
                                                $k = array_search($user,$userarr);

                                                $totmodecount[$i]	+= $row["CNT"];
                                                $totmmcount[$dd]	+= $row["CNT"];
                                                $mmcount2[$k][$i][$dd]	 = $row["CNT"];
                                                $modecount2[$k][$dd][$i] = $row["CNT"];
                                                $totmmcount2[$k][$i]	+= $mmcount2[$k][$i][$dd];
                                                $totmodecount2[$k][$dd]	+= $modecount2[$k][$dd][$i];
                                                $total[$k]		+= $row["CNT"];
                                                $grandtotal		+= $row["CNT"];
                                        }

                                }

				$conv_sql = "SELECT COUNT(*) AS CNT, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, DAYOFMONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as dd , jsadmin.AFFILIATE_MAIN.ENTRYBY AS ENTRYBY FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME";
				
				if ($dmonth<=9)
 					$conv_sql.=" BETWEEN '$dyear-0$dmonth-01' AND '$dyear-0$dmonth-31' ";
				else
					$conv_sql.=" BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' "; 
		
				$conv_sql.=" GROUP BY newjs.JPROFILE.SUBSCRIPTION , dd , jsadmin.AFFILIATE_MAIN.ENTRYBY ";
                                
				$conv_res = mysql_query($conv_sql,$db1) or die("$sql".mysql_error());

				while ($conv_row = mysql_fetch_array($conv_res))
                                {
                                                                                                                             
                                        $sub		= $conv_row["SUBSCRIPTION"];
                                        $dd		= $conv_row["dd"]-1;
                                        $conv_user	= $conv_row["ENTRYBY"];

                                        if ($sub == '')
                                        	$i = 0;
					else
	                                       	$i = 1;
					if (in_array($conv_user,$userarr))
                                        {
                                                $k = array_search($conv_user,$userarr);

                                                $conv_subcount[$k][$i][$dd]	=  $conv_row["CNT"];
                                                $conv_mmcount[$k][$dd][$i]	=  $conv_row["CNT"];
                                                $conv_totsubcount[$k][$i]	+= $conv_subcount[$k][$i][$dd];
                                                $conv_totmmcount[$k][$dd]	+= $conv_mmcount[$k][$dd][$i];
                                                $conv_total[$k]			+= $conv_row["CNT"];
                                        }
                                                                                                                             
                                }
			}

			$smarty->assign("month",$month);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("userarr",$userarr);
                        $smarty->assign("modearr",$modearr);
                        $smarty->assign("yyarr",$yyarr);
                        $smarty->assign("ddarr",$ddarr);
                        $smarty->assign("totmmcount",$totmmcount);
                        $smarty->assign("totmodecount",$totmodecount);
			$smarty->assign("mmcount2",$mmcount2);
                        $smarty->assign("modecount2",$modecount2);
                        $smarty->assign("totmmcount2",$totmmcount2);
                        $smarty->assign("totmodecount2",$totmodecount2);
                        $smarty->assign("total",$total);
                        $smarty->assign("grandtotal",$grandtotal);
			
			$smarty->assign("conv_totmmcount",$conv_totmmcount);
                        $smarty->assign("conv_totsubcount",$conv_totsubcount);
		        $smarty->assign("conv_mmcount",$conv_mmcount);
                        $smarty->assign("conv_subcount",$conv_subcount);
                        $smarty->assign("conv_total",$conv_total);
			
			$smarty->assign("mflag",$mflag);
			$smarty->assign("dflag",$dflag);
                        $smarty->assign("myear",$myear);
			$smarty->assign("dyear",$dyear);
			$smarty->assign("dmonth",$dmonth);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("name",$name);
			$smarty->assign("username",$username);

                        $smarty->display("promotion.htm");

	}
	else
	{
			for ($i = 0; $i < 12; $i++)
                        {
                                $mmarr[$i] = $i + 1;
                        }
                                                                                                                             
                        for ($i = 0; $i < 10; $i++)
                        {
                                $yyarr[$i] = $i + 2005;
                        }

			$smarty->assign("yyarr",$yyarr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("cid",$cid);
                        $smarty->assign("name",$name);
			$smarty->assign("username",$username);

                        $smarty->display("promotion.htm");

	}
?>
