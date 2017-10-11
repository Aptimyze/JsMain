<?php
/****************************************************************************************************************************
*	FILENAME	   : aff_np_records_count.php
*	INCLUDED           : connect.inc 
*			     functions : 
*			     db_connect()     : To connect to localhost 
*			     dbsql2_connect() : To connect using /tmp/sql2.sock
*			     getname()        : To get the name of the person logged in.
*       DESCRIPTION        : Displays the details of the promotions via Newspaper month and day wise depending upon 
*			     the user's choice.
****************************************************************************************************************************/

	require_once("connect.inc");

	//dbsql2_connect();

	$db=db_connect();
	$db2= dbsql2_connect();
	//$username	= getname($cid);

        if ($submit)
	{
			
			$mmarr    =  array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			
			if ($sourcegrp == 'all')
			{
				$srcgrp		=  1;
				$src		= 'MODE';
				$modearr  	=  array ('N','A');
				$srcarr	  	=  $modearr;
			}

			if ($sourcegrp == 'NPPR')
				 $srcgrp       =  2; 
			
			elseif($sourcegrp == 'AFFL')
				$srcgrp         =  3;

			if ($srcgrp == 2)
			{
				$src = 'SOURCE';
				$npsql  = "SELECT LABEL , VALUE FROM jsadmin.AFFL_SOURCE  WHERE GROUPNAME = '$sourcegrp' ORDER BY SORTBY";
                                $npres  = mysql_query($npsql,$db) or die("$npsql".mysql_error());

                                while($nprow= mysql_fetch_array($npres))
                                {
                                     $modearr[] = $nprow["VALUE"];
                                     $srcarr[]  = $nprow["LABEL"];
                                }
			}
			if ($srcgrp == 3)
			{
				$src = 'SOURCE';
                                $npsql  = "SELECT SourceID,SourceName FROM MIS.SOURCE  WHERE GROUPNAME = 'Affiliate'";
                                $npres  = mysql_query($npsql,$db) or die("$npsql".mysql_error());
                                                                                                                             
                                while($nprow= mysql_fetch_array($npres))
                                {
                                     $modearr[] = $nprow["SourceID"];
                                     $srcarr[]  = $nprow["SourceName"];
                                }

			}

			/*if ($sourcegrp == 'NPPR')
			{
                                $srcgrp         =  2;
				$npsql  = "SELECT LABEL , VALUE FROM jsadmin.NEWSPPR_SOURCE ORDER BY SORTBY WHERE GROUPNAME=$sourcegrp";
                                $npres  = mysql_query($npsql,$db) or die("$npsql".mysql_error());
                                while($nprow= mysql_fetch_array($npres))
                                {				
                                     $modearr[] = $nprow["VALUE"];
				     $srcarr[]	= $nprow["LABEL"];
                                }
				

			}
			elseif ($sourcegrp == 'AFFL')
                        {
				$srcgrp = 3;
				$src    = 'SOURCE';
                                                                                                                             
                                $npsql  = "SELECT LABEL , VALUE FROM jsadmin.AFFL_SOURCE ORDER BY SORTBY";
                                $npres  = mysql_query($npsql,$db) or die("$npsql".mysql_error());

                                while($nprow= mysql_fetch_array($npres))
                                {
                                     $modearr[] = $nprow["VALUE"];
                                     $srcarr[]  = $nprow["LABEL"];
                                }
				
				
                        }*/

			if ($criteria == 'M')                        // To display the data monthwise
			{	
				$mflag = 1;

				for ($i = 0; $i < 12; $i++)
					$montharr[$i]= $i + 1;

				$sql = "SELECT ENTRYBY , $src , COUNT(*) AS CNT , MONTH(ENTRYTIME) AS mm  FROM jsadmin.AFFILIATE_MAIN WHERE  ENTRYTIME BETWEEN '$myear-01-01' AND '$myear-12-31'";

				if ($srcgrp == 1)
                                        $sql.=" AND MODE IN ('N','A')  GROUP BY ENTRYBY ,  mm , $src ";

				elseif ($srcgrp == 2)
					$sql.=" AND MODE IN ('N')  GROUP BY ENTRYBY ,  mm , $src ";

				elseif ($srcgrp == 3)
					$sql.=" AND MODE IN ('A')  GROUP BY ENTRYBY ,  mm , $src "; 

				$res = mysql_query($sql,$db2) or die(mysql_error());				
				
				// Query to find the registered and paid members who were converted to via 
				// newspaper affiliate mode

				$conv_sql = "SELECT COUNT(*) AS CNT, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, MONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as mm , jsadmin.AFFILIATE_MAIN.ENTRYBY AS ENTRYBY , jsadmin.AFFILIATE_MAIN.$src AS $src FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL ";
				if ($srcgrp == 1)
                                        $conv_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE IN ('N','A')";                               
                                elseif ($srcgrp == 2)
                                        $conv_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE ='N' ";
                                                                                                                             
                                elseif ($srcgrp == 3)
                                        $conv_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE ='A'";

				$conv_sql.= " AND jsadmin.AFFILIATE_MAIN.ENTRYTIME BETWEEN '$myear-01-01' AND '$myear-12-31' GROUP BY newjs.JPROFILE.SUBSCRIPTION , mm , jsadmin.AFFILIATE_MAIN.ENTRYBY ";

				$conv_res = mysql_query($conv_sql,$db) or die("$conv_sql".mysql_error());

		        	while ($row = mysql_fetch_array($res))
				{
					$mode	 	= $row["$src"]; 
					$mm 	 	= $row["mm"] - 1;
					$user 	 	= $row["ENTRYBY"];
					
					if(is_array($userarr))
					{
						if(!in_array($user,$userarr))
							$userarr[] = $user;	
					}
					else
					{
						$userarr[] = $user;
					}
					
					$i = array_search($mode ,$modearr);

					if (in_array($user,$userarr))
					{
						$k = array_search($user,$userarr);

						$totmodecount[$i]	+= $row["CNT"];   // modewise count
						$totmmcount[$mm]	+= $row["CNT"];   // monthwise count
						$mmcount2[$k][$i][$mm]	 = $row["CNT"];
						$modecount2[$k][$mm][$i] = $row["CNT"];
						$totmmcount2[$k][$i]	+= $mmcount2[$k][$i][$mm]; // total mode count
					        $totmodecount2[$k][$mm]	+= $modecount2[$k][$mm][$i]; // total month count
						$total[$k]		+= $row["CNT"];
						$grandtotal		+= $row["CNT"];
					}
				}
				while ($conv_row = mysql_fetch_array($conv_res))
				{
								
					$sub 		= $conv_row["SUBSCRIPTION"];
					$mm  		= $conv_row["mm"]-1;
					$conv_user 	= $conv_row["ENTRYBY"];
					$regmembers[]= $conv_row["MEMBERS"];

					if(is_array($userarr))
                                        {
                                                if(!in_array($user,$userarr))
                                                        $userarr[] = $conv_user;
                                        }
                                        else
                                        {
                                                $userarr[] = $conv_user;
                                        }
                                                                                                                             
                                        $i = array_search($mode ,$modearr);


					if($sub!='')	  		// If the user is just a  registered member
					{
						$i=1;
						$paidmembers = $conv_row["USERNAME"];
					}				
					if (in_array($conv_user,$userarr))
					{	
						$k = array_search($conv_user,$userarr);

						$reg_count[$k][$mm]		+= $conv_row["CNT"];
						$tot_reg_count[$k]		+= $conv_row["CNT"];
						$grandtot_reg_count[$mm] 	+= $conv_row["CNT"];
						$grandtotal_reg			+= $conv_row["CNT"];
						
						
						$mem_count[$k][$i][$mm]  	+= $conv_row["CNT"];
                                                $tot_mem_count[$k][$i]	        += $conv_row["CNT"];//$mem_count[$k][1][$mm];
                                                $grandtot_mem_count[$i][$mm]    += $conv_row["CNT"];//$mem_count[$mm][$k][1]
						$grandtotal_mem[$i]	        += $conv_row["CNT"];

					}
					
				}

			}
		
			if ($criteria == 'D')  			// To display the data datewise
			{
				//$db2 = dbsql2_connect();

				$dflag = 1;
				$month = $dmonth-1;

				for ($i = 0; $i < 31; $i++)
					$ddarr[$i] = $i + 1;

				$sql = "SELECT ENTRYBY , $src , COUNT(*) AS CNT , DAYOFMONTH(ENTRYTIME) AS dd FROM jsadmin.AFFILIATE_MAIN";
				if ($dmonth<=9)
					$sql.=" WHERE ENTRYTIME BETWEEN '$dyear-0$dmonth-01' AND '$dyear-0$dmonth-31' ";
				else
					$sql.=" WHERE ENTRYTIME BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' ";

				if($srcgrp == 1)
                                        $sql.=" AND MODE IN ('N','A')  GROUP BY ENTRYBY , dd , $src ";
                                                                                                                             
                                elseif($srcgrp == 2)
                                        $sql.=" AND MODE IN ('N')  GROUP BY ENTRYBY ,  dd , $src ";
                                                                                                                             
                                elseif($srcgrp == 3)
					$sql.=" AND MODE IN ('A')  GROUP BY ENTRYBY ,  dd , $src ";

				$res = mysql_query($sql,$db2) or die("$sql".mysql_error());

				while ($row = mysql_fetch_array($res))
                                {
                                        $mode	 = $row["$src"];
                                        $dd	 = $row["dd"] - 1;
                                        $user    = $row["ENTRYBY"];

					if(is_array($userarr))
                                        {
                                                if(!in_array($user,$userarr))
                                                        $userarr[] = $user;
                                        }
                                        else
                                        {
                                                $userarr[] = $user;
                                        }


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

				//Alok: change the query to use some key			
				$conv_sql = "SELECT COUNT(*) AS CNT, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, DAYOFMONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as dd , jsadmin.AFFILIATE_MAIN.ENTRYBY AS ENTRYBY , jsadmin.AFFILIATE_MAIN.$src AS $src FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME";
				
				if ($dmonth<=9)
 					$conv_sql.=" BETWEEN '$dyear-0$dmonth-01' AND '$dyear-0$dmonth-31' ";
				else
					$conv_sql.=" BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' "; 

				if($srcgrp == 1)
                                        $conv_sql.=" AND MODE IN ('N','A')";
                                                                                                                             
                                elseif($srcgrp == 2)
                                        $conv_sql.=" AND MODE IN ('N')";
                                                                                                                             
                                elseif($srcgrp == 3)
                                        $conv_sql.=" AND MODE IN ('A')";

		
				$conv_sql.=" GROUP BY newjs.JPROFILE.SUBSCRIPTION , dd , jsadmin.AFFILIATE_MAIN.ENTRYBY ";
                                
				$conv_res = mysql_query($conv_sql,$db) or die("$conv_sql".mysql_error());

				while ($conv_row = mysql_fetch_array($conv_res))
                                {
                                                                                                                             
                                        $sub		= $conv_row["SUBSCRIPTION"];
                                        $dd		= $conv_row["dd"]-1;
                                        $conv_user	= $conv_row["ENTRYBY"];

					if(is_array($userarr))
                                        {
                                                if(!in_array($user,$userarr))
                                                        $userarr[] = $conv_user;
                                        }
                                        else
                                        {
                                                $userarr[] = $conv_user;
                                        }


                                        if($sub!='')
	                                  	$i = 1;
					  
					if (in_array($conv_user,$userarr))
                                        {
                                                $k = array_search($conv_user,$userarr);

                                               /* $conv_subcount[$k][$i][$dd]	+=  $conv_row["CNT"];
                                                $conv_mmcount[$k][$dd][$i]	+=  $conv_row["CNT"];
                                                $conv_totsubcount[$k][$i]	+= $conv_row["CNT"];//$conv_subcount[$k][$i][$dd];
                                                $conv_totmmcount[$k][$dd]	+= $conv_row["CNT"];//$conv_mmcount[$k][$dd][$i];
                                                $conv_total[$k]			+= $conv_row["CNT"];

						$reg_totcount[$dd]              += $conv_row["CNT"];
                                                $mem_totcount[$dd]              += $conv_row["CNT"];//$conv_totmmcount[$k][$dd];
                                                $conv_grandtotal                += $conv_row["CNT"];
						$mem_grandtotal			+= $conv_row["CNT"];//$mem_totcount[$dd];
						*/
						$reg_count[$k][$dd]             += $conv_row["CNT"];
                                                $tot_reg_count[$k]              += $conv_row["CNT"];
                                                $grandtot_reg_count[$dd]        += $conv_row["CNT"];
                                                $grandtotal_reg                 += $conv_row["CNT"];
                                                                                                                             
                                                                                                                             
                                                $mem_count[$k][$i][$dd]          += $conv_row["CNT"];
                                                $tot_mem_count[$k][$i]           += $conv_row["CNT"];
                                                $grandtot_mem_count[$i][$dd]     += $conv_row["CNT"];//$mem_count[$mm][$k][1]
                                                $grandtotal_mem[$i]              += $conv_row["CNT"];

                                        }
                                					
                                }
			}
			$smarty->assign("regmembers",$regmembers);
			$smarty->assign("paidmembers",$paidmembers);
			$smarty->assign("reg_count",$reg_count);
                        $smarty->assign("tot_reg_count",$tot_reg_count);
			$smarty->assign("mem_count",$mem_count);
                        $smarty->assign("tot_mem_count",$tot_mem_count);
                        $smarty->assign("grandtot_mem_count",$grandtot_mem_count);
			$smarty->assign("grandtotal_mem",$grandtotal_mem); 	
			$smarty->assign("grandtot_reg_count",$grandtot_reg_count);
                        $smarty->assign("grandtotal_reg",$grandtotal_reg);

			$smarty->assign("month",$month);
                        $smarty->assign("mmarr",$mmarr);
			$smarty->assign("montharr",$montharr);
			$smarty->assign("dayarr",$dayarr);
                        $smarty->assign("userarr",$userarr);
                        $smarty->assign("modearr",$modearr);
                        $smarty->assign("yyarr",$yyarr);
                        $smarty->assign("ddarr",$ddarr);
			$smarty->assign("srcarr",$srcarr);
                        $smarty->assign("totmmcount",$totmmcount);
                        $smarty->assign("totmodecount",$totmodecount);
			$smarty->assign("mmcount2",$mmcount2);
                        $smarty->assign("modecount2",$modecount2);
                        $smarty->assign("totmmcount2",$totmmcount2);
                        $smarty->assign("totmodecount2",$totmodecount2);
                        $smarty->assign("total",$total);
                        $smarty->assign("grandtotal",$grandtotal);
			
			$smarty->assign("srcgrp",$srcgrp);	
			$smarty->assign("mflag",$mflag);
			$smarty->assign("dflag",$dflag);
                        $smarty->assign("myear",$myear);
			$smarty->assign("dyear",$dyear);
			$smarty->assign("dmonth",$dmonth);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("name",$name);
			$smarty->assign("username",$username);

                        $smarty->display("affiliatemis.htm");

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
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

                        $smarty->display("affiliatemis.htm");

	}
?>
