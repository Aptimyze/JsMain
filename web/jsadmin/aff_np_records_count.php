<?php
/****************************************************************************************************************************
*	FILENAME	   : aff_np_records_count.php
*	INCLUDED           : connect.inc 
*			     functions : 
*			     connect_misdb()     : To connect to the slave
*			     connect_master()    : To connect to the master
*			     getname()           : To get the name of the person logged in.
*       DESCRIPTION        : Displays the details of the promotions via Newspaper month and day wise depending upon 
*			     the user's choice.
****************************************************************************************************************************/

include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($checksum))
{	
	$username	= getname($checksum);

        if ($submit)
	{
			
			$mmarr    =  array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$modearr  =  array ('N','A');

			if ($criteria == 'M')                        // To display the data monthwise
			{					
				$mflag = 1;				

				$sql = "SELECT ENTRYBY , MODE , COUNT(*) AS CNT , MONTH(ENTRYTIME) AS mm  FROM jsadmin.AFFILIATE_MAIN WHERE  ENTRYTIME BETWEEN '$myear-01-01' AND '$myear-12-31' AND MODE IN ('N','A')  GROUP BY ENTRYBY ,  mm , MODE";

				$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
				
		        	while ($row = mysql_fetch_array($res))
				{
					$mode	 = $row["MODE"]; 
					$mm 	 = $row["mm"] - 1;
					$user    = $row["ENTRYBY"];

					if (is_array($userarr))
                                        {
                                                if( !in_array($user,$userarr) )
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
				// Query to find the registered and paid members who were converted to via
                                // newspaper affiliate mode

				$conv_sql = "SELECT COUNT(*) AS CNT, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, MONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as mm , jsadmin.AFFILIATE_MAIN.ENTRYBY AS ENTRYBY FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME BETWEEN '$myear-01-01' AND '$myear-12-31' GROUP BY newjs.JPROFILE.SUBSCRIPTION , mm , jsadmin.AFFILIATE_MAIN.ENTRYBY";
                                                                                                                             
                                $conv_res = mysql_query_decide($conv_sql) or die("$sql".mysql_error_js());

				while ($conv_row = mysql_fetch_array($conv_res))
				{	
								
					$sub 		= $conv_row["SUBSCRIPTION"];
					$mm  		= $conv_row["mm"]-1;
					$conv_user 	= $conv_row["ENTRYBY"];
		
					if(is_array($userarr))
                                        {
                                                if(!in_array($user,$userarr))
                                                        $userarr[] = $conv_user;
                                        }
                                        else
                                        {
                                                $userarr[] = $conv_user;
                                        }


					if ($sub!= '')	  		// If the user is just a  registered member
						$i=1;

					if (in_array($conv_user,$userarr))
					{		
						$k = array_search($conv_user,$userarr);

						// for paid members 
						
						$reg_count[$k][$mm]             += $conv_row["CNT"];
                                                $tot_reg_count[$k]              += $conv_row["CNT"];
                                                $grandtot_reg_count[$mm]        += $conv_row["CNT"];
                                                $grandtotal_reg                 += $conv_row["CNT"];                                                                                                                        
                                                $mem_count[$k][$i][$mm]         += $conv_row["CNT"];
                                                $tot_mem_count[$k][$i]          += $conv_row["CNT"];//$mem_count[$k][1][$mm];
                                                $grandtot_mem_count[$i][$mm]    += $conv_row["CNT"];//$mem_count[$mm][$k][1];
                                                $grandtotal_mem[$i]             += $conv_row["CNT"];
					}
				}
			print_r($mem_count);
			echo "<br>";
			print_r($tot_mem_count);

			}
		
			if ($criteria == 'D')  			// To display the data datewise
			{

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

				$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());

				while ($row = mysql_fetch_array($res))
                                {
                                        $mode	 = $row["MODE"];
                                        $dd	 = $row["dd"] - 1;
                                        $user    = $row["ENTRYBY"];

					if (is_array($userarr) )
                                        {
                                                if (!in_array($user,$userarr))
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
				$conv_sql = "SELECT COUNT(*) AS CNT, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, DAYOFMONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as dd , jsadmin.AFFILIATE_MAIN.ENTRYBY AS ENTRYBY FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME";
				
				if ($dmonth<=9)
 					$conv_sql.=" BETWEEN '$dyear-0$dmonth-01' AND '$dyear-0$dmonth-31' ";
				else
					$conv_sql.=" BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' "; 
		
				$conv_sql.=" GROUP BY newjs.JPROFILE.SUBSCRIPTION , dd , jsadmin.AFFILIATE_MAIN.ENTRYBY ";
                                
				$conv_res = mysql_query_decide($conv_sql) or die("$sql".mysql_error_js());

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


                                        if ($sub != '')
                                        	$i = 1;          	
					if (in_array($conv_user,$userarr))
                                        {
                                                $k = array_search($conv_user,$userarr);

						$reg_count[$k][$dd]             += $conv_row["CNT"];
                                                $tot_reg_count[$k]              += $conv_row["CNT"];
                                                $grandtot_reg_count[$dd]        += $conv_row["CNT"];
                                                $grandtotal_reg                 += $conv_row["CNT"];
                         
                                                $mem_count[$k][$i][$dd]          += $conv_row["CNT"];
                                                $tot_mem_count[$k][$i]           += $conv_row["CNT"];//$mem_count[$k][1][$mm]
	                                        $grandtot_mem_count[$i][$dd]     += $conv_row["CNT"];//$mem_count[$mm][$k][1]
                                                $grandtotal_mem[$i]              += $conv_row["CNT"];

                                        }
                                
                                }
			}
			
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
			$smarty->assign("reg_totcount",$reg_totcount);
                        $smarty->assign("mem_totcount",$mem_totcount);
                        $smarty->assign("conv_grandtotal",$conv_grandtotal);
                        $smarty->assign("mem_grandtotal",$mem_grandtotal);

			
			$smarty->assign("mflag",$mflag);
			$smarty->assign("dflag",$dflag);
                        $smarty->assign("myear",$myear);
			$smarty->assign("dyear",$dyear);
			$smarty->assign("dmonth",$dmonth);
                        $smarty->assign("checksum",$checksum);
                        $smarty->assign("name",$name);
			$smarty->assign("username",$username);

                        $smarty->display("aff_np_records_count.htm");

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
			$smarty->assign("checksum",$checksum);
                        $smarty->assign("name",$name);
			$smarty->assign("username",$username);
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

                        $smarty->display("aff_np_records_count.htm");

	}
}
else //user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsconnectError.tpl");
}	
?>
