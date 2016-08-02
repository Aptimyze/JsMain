<?php
/**
*	FILENAME	:	new_edit_mis.php
*	DESCRIPTION	:	Displays screened profiles stats in new edit category
*	MODIFIED BY	:	Tripti Singh
*	MODIFY DATE	:	8th July, 2006
**/
include("connect.inc");
$db=connect_misdb();
$data=authenticated($checksum);                                                                                                
if(isset($data))
{
	if($SUBMIT)
	{
		$flag=1;
		$sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE
'%NU%'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$num_of_operators=mysql_num_rows($res);	
                if($row=mysql_fetch_assoc($res))	//Select all users of screening
                {	
                        do
                        {
                                $privilage=$row['PRIVILAGE'];
                                $priv=explode("+",$privilage);
				$operators[]=$row['USERNAME'];
			}while($row=mysql_fetch_assoc($res));
                }
		$table="MIS.NEW_EDIT_COUNT";	//Data is to be analyzed from this table
		$operators[]="Total";
		//For displaying total profiles screened in a day
		if($type=='M')
		{
			$mflag = 1; 	// mflag tells us that user has selected month view
			for ($i = 0; $i < 12; $i++)
				$mmarr[$i] = $i + 1; // Array to store months.
			$mmarr[$i] = "Total"; 	// extra index for Total screened profiles
			
			//Count no. of screened profiles of a user in a particular month
			$sql="SELECT SUM(NEW) as NEW,SUM(EDIT) as EDIT, SCREENED_BY, MONTH(SCREEN_DATE) as dd FROM $table WHERE SCREEN_DATE BETWEEN '$myear-01-01' AND '$myear-12-31' GROUP BY SCREENED_BY,dd";
			$res = mysql_query_decide($sql,$db) or die(mysql_error_js());

			if($row = mysql_fetch_array($res))
			{
				do
				{
					$dd = $row['dd']-1;
					$j = array_search($row['SCREENED_BY'],$operators);
					if ($j !== FALSE)
					{
					//Save properties in an array zone wise
						$cnt[$j][$dd]['new'] = $row['NEW'];
						$cnt[$j][$dd]['edit'] = $row['EDIT'];
						$cnt[$j][$dd]['total'] = $row['NEW'] + $row['EDIT'];
						$cnt[$j][12]['new'] += $row['NEW'];
						$cnt[$j][12]['edit'] += $row['EDIT'];
						$cnt[$j][12]['total'] += $row['NEW'] + $row['EDIT'];
						$cnt[$num_of_operators][$dd]['new'] += $row['NEW'];
						$cnt[$num_of_operators][$dd]['edit'] += $row['EDIT'];
						$cnt[$num_of_operators][$dd]['total'] += $row['NEW'] + $row['EDIT'];
						$cnt[$num_of_operators][12]['new']+=$row['NEW'];
						$cnt[$num_of_operators][12]['edit'] += $row['EDIT'];
						$cnt[$num_of_operators][12]['total'] += $row['NEW'] + $row['EDIT'];
					}
				}while($row = mysql_fetch_array($res));
			}
		}// end of $type=='M'
		elseif($type=='D')
		{
			$dflag=1;
			for($i=0;$i<31;$i++)
				$ddarr[$i]=$i+1;
			$ddarr[$i]="Total";
			$sql="SELECT SUM(NEW) as NEW,SUM(EDIT) as EDIT, SCREENED_BY, dayofmonth(SCREEN_DATE) as dd FROM $table WHERE SCREEN_DATE BETWEEN '$dyear-$dmonth-01' AND '$dyear-$dmonth-31' GROUP BY SCREENED_BY,dd";

			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				do
				{
					$dd = $row['dd']-1;
					$j = array_search($row['SCREENED_BY'],$operators);
					if ($j !== FALSE)
					{	
					//Save properties in an array zone wise
						$cnt[$j][$dd]['new'] = $row['NEW'];
						$cnt[$j][$dd]['edit'] = $row['EDIT'];
						$cnt[$j][$dd]['total'] = $row['NEW'] + $row['EDIT'];
						$cnt[$j][31]['new'] += $row['NEW'];
						$cnt[$j][31]['edit'] += $row['EDIT'];
						$cnt[$j][31]['total'] += $row['NEW'] + $row['EDIT'];
						$cnt[$num_of_operators][$dd]['new'] += $row['NEW'];
						$cnt[$num_of_operators][$dd]['edit'] += $row['EDIT'];
						$cnt[$num_of_operators][$dd]['total'] += $row['NEW'] + $row['EDIT'];
						$cnt[$num_of_operators][31]['new']+=$row['NEW'];
						$cnt[$num_of_operators][31]['edit'] += $row['EDIT'];
						$cnt[$num_of_operators][31]['total'] += $row['NEW'] + $row['EDIT'];
					}
				}while($row = mysql_fetch_array($res));
			}
		}
		$smarty->assign("cnt",$cnt);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("flag",$flag);
		$smarty->assign("mflag",$mflag);
		$smarty->assign("dflag",$dflag);
		$smarty->assign("myear",$myear);
		$smarty->assign("myearp1",$myearp1);
		$smarty->assign("dyear",$dyear);
		$smarty->assign("dmonth",$dmonth);
		$smarty->assign("operators",$operators);
												
		$smarty->display("new_edit_mis.htm");
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
                $smarty->assign("CHECKSUM",$checksum);
                $smarty->display("new_edit_mis.htm");
        }
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
