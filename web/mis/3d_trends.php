<?php
/*      Filename        :	3d TRENDS MIS.
*       Description     :  	3D TRENDS
*/

include("connect.inc");
$db=connect_misdb();
//if(authenticated($cid))
if(1)
{
	if($CMDGo)
	{	
		$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
        	include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
        	include_once($_SERVER['DOCUMENT_ROOT']."/commonFiles/incomeCommonFunctions.inc");
	        $mysqlObj=new Mysql;
		$sql1="SELECT PROFILEID,CASTE,MTONGUE,GENDER,MANGLIK from newjs.JPROFILE where USERNAME='$username'";
		$res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
		if($row1=mysql_fetch_array($res1))
		{
			$sqlTrend="SELECT * FROM twowaymatch.TRENDS WHERE PROFILEID='$row1[PROFILEID]'";
			$resTrend=mysql_query_decide($sqlTrend) or die("$sqlTrend".mysql_error_js());
			$rowTrend=mysql_fetch_assoc($resTrend);
			if($rowTrend["CASTE_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["CASTE_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$casteTrend[$value]=$trend;
					
				}
			}
			if($rowTrend["MTONGUE_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["MTONGUE_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$mtongueTrend[$value]=$trend;

				}
			}
			if($rowTrend["AGE_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["AGE_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$ageTrend[$value]=$trend;

				}
			}
			if($rowTrend["INCOME_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["INCOME_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$incomeTrend[$value]=$trend;

				}
			}
			if($rowTrend["HEIGHT_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["HEIGHT_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$heightTrend[$value]=$trend;

				}
			}
			if($rowTrend["EDUCATION_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["EDUCATION_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$educationTrend[$value]=$trend;

				}
			}
			if($rowTrend["OCCUPATION_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["OCCUPATION_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$occupationTrend[$value]=$trend;

				}
			}
			if($rowTrend["CITY_VALUE_PERCENTILE"])
			{
				$tempArray=explode("|",$rowTrend["CITY_VALUE_PERCENTILE"]);
				foreach($tempArray as $value)
				{
					list($value,$trend)=explode("#",$value);
					$cityTrend[$value]=$trend;

				}
			}
			$marriedTrend=$rowTrend["MSTATUS_M_P"];
			$notMarriedTrend=$rowTrend["MSTATUS_N_P"];
			$manglikTrend=$rowTrend["MANGLIK_M_P"];
			$nonManglikTrend=$rowTrend["MANGLIK_N_P"];
			$nriTrend=$rowTrend["NRI_M_P"];
			$nonNriTrend=$rowTrend["NRI_N_P"];

			$my_caste=$row1['CASTE'];
			$my_mtongue=$row1['MTONGUE'];
			$my_gender=$row1['GENDER'];
			$my_manglik=$row1['MANGLIK'];

			$sql2="select RECEIVER,TYPE from  newjs.CONTACTS where SENDER='" . $row1["PROFILEID"] . "' and (TYPE='A' OR TYPE='I') ";
			$sql2="select RECEIVER,TYPE from  newjs.CONTACTS where SENDER='" . $row1["PROFILEID"] . "'";
			$myDbName=getProfileDatabaseConnectionName($row1["PROFILEID"],'slave',$mysqlObj);
			$myDb=$mysqlObj->connect($myDbName) or die("error in connection $myDbName");
			$res2=mysql_query_decide($sql2,$myDb) or die("$sql2".mysql_error_js($myDb));
			while($row2=mysql_fetch_array($res2))
			{
                        	$profileid_str.="'".$row2['RECEIVER']."',";
                
				if($row2['TYPE']=='I')
					$initiated++;
				else
					$accepted++;
			}
			$profileid_str=substr($profileid_str,0,-1);
			
				
			if($profileid_str)
			{
				$sql4="select AGE,HEIGHT,CASTE,MTONGUE,MANGLIK,OCCUPATION,COUNTRY_RES,CITY_RES,INCOME,GENDER,COUNTRY_BIRTH,RELIGION,MSTATUS,EDU_LEVEL_NEW,COUNTRY_BIRTH,DIET from  newjs.JPROFILE where PROFILEID IN ($profileid_str)"; 
				$res4=mysql_query_decide($sql4,$db) or die("$sql4".mysql_error_js($db));
				while($row4=mysql_fetch_array($res4))
				{
					$caste[]=$row4['CASTE'];
					$mtongue[]=$row4['MTONGUE'];	
					$income[]=$row4['INCOME'];	
					$country[]=$row4['COUNTRY_RES'];	
					$countrybirth[]=$row4['COUNTRY_BIRTH'];	
					$city[]=$row4['CITY_RES'];	
					$education[]=$row4['EDU_LEVEL_NEW'];	
					$occupation[]=$row4['OCCUPATION'];	
					$mstatus[]=$row4['MSTATUS'];	
					$age[]=$row4['AGE'];	
					$height[]=$row4['HEIGHT'];
					$manglik[]=$row4['MANGLIK'];
					$diet[]=$row4['DIET'];
				}
			}		
			
			if($views)
			{
				$db_211 = connect_211_slave();
				if($profileid_str)	
					$sql3="select VIEWED from  newjs.VIEW_LOG where VIEWER='" . $row1["PROFILEID"] . "' AND VIEWER NOT IN ($profileid_str) LIMIT 0,$viewscount";
				else
					$sql3="select VIEWED from  newjs.VIEW_LOG where VIEWER='" . $row1["PROFILEID"] . "' LIMIT 0,$viewscount";
				$res3=mysql_query_decide($sql3,$db_211) or die("$sql3".mysql_error_js($db_211));
				while($row3=mysql_fetch_array($res3))
				{
					$view_str.="'".$row3['VIEWED']."',";
				}
				$view_str=substr($view_str,0,-1);
				//mysql_close($db_211);

				if($view_str)
				{
					$sql5="select AGE,HEIGHT,CASTE,MTONGUE,OCCUPATION,COUNTRY_RES,CITY_RES,INCOME,GENDER,COUNTRY_BIRTH,RELIGION,MSTATUS,EDU_LEVEL_NEW,COUNTRY_BIRTH from  newjs.JPROFILE where PROFILEID IN ($view_str)"; 
					$res5=mysql_query_decide($sql5,$db) or die("$sql5".mysql_error_js($db));
					while($row5=mysql_fetch_array($res5))
					{
						$viewed++;
						$castev[]=$row5['CASTE'];
						$mtonguev[]=$row5['MTONGUE'];	
						$incomev[]=$row5['INCOME'];	
						$countryv[]=$row5['COUNTRY_RES'];	
						$countrybirthv[]=$row5['COUNTRY_BIRTH'];	
						$cityv[]=$row5['CITY_RES'];	
						$educationv[]=$row5['EDU_LEVEL_NEW'];	
						$occupationv[]=$row5['OCCUPATION'];	
						$mstatusv[]=$row5['MSTATUS'];	
						$agev[]=$row5['AGE'];	
						$heightv[]=$row5['HEIGHT'];
						$manglikv[]=$row5['MANGLIK'];
					}
				}
			}
	
			echo 	'<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<meta name="description" content="">
				<meta name="keywords" content="">
				<title>Jeevansathi Matrimonials- My Jeevansathi Account</title>
				<link rel="stylesheet" href="css/comm_style.css" type="text/css">
				<style type="text/css">
				.psts{ float:left;line-height:20px; width:65%}
				.baro{width:200px; border:1px solid #C5C5C5; line-height:20px}
				.barin{ background-color:#99CC00; background-image:url(http://ser4.jeevansathi.com/profile/images/bar_complete.gif)}</style><body>';
	
                        echo    '<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
                                        <tr class="formhead">
                                                <td align="center">Viewed</td>
                                                <td align="center">Accepted</td>
                                                <td align="center">Initiated</td>
                                        </tr>
                                        <tr class="fieldsnew">
                                                <td align="center" height="25">'.$viewed.'</td>
                                                <td align="center" height="25">'.$accepted.'</td>
                                                <td align="center" height="25">'.$initiated.'</td>
                                	</tr>
                        	</table>';


			//MTONGUE
			if(is_array($mtongue) || is_array($mtonguev))
			{
				$mtongue_east=array('5','4','6','21','22','23','24','25','29','32');
				$mtongue_west=array('8','9','11','12','19','20');
				$mtongue_north=array('7','10','13','14','15','27','28','30','33');
				$mtongue_south=array('2','3','16','17','18','26','31','34');
				$mtongue_hindi_all=array('7','10','13','14','28','33','19');
				$mtongue_punjabi_delhi=array('10','27');
				$mtongue_delhi_up_bihar=array('7','10','19','33');
				$mtongue_pahari_delhi_up=array('10','14','33');
				$mtongue_konkani_marathi=array('20','34');
				
				$count=0;
				$countv=0;
				$weight=0;

				//if( $my_mtongue==10 || $my_mtongue==19 || $my_mtongue==33 || $my_mtongue==35 || $my_mtongue==36 || $my_mtongue==37)
					//$hindi_mtongue=1;
				
				/*echo '<br> mtongue is <br>';
				print_r($mtongue);
				echo '<br> mtonguev is <br>';
				print_r($mtonguev);*/
				
				$count=count($mtongue);
				$countv=count($mtonguev);
				
				if($count)
					$weight=($count + $countv)/$count;
						
				$mvalue=array();
				$mvaluev=array();
				
				//echo '<br> weight is <br>';
				//echo $weight;
				
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$mtongue[$i]]++;
				}
				//echo '<br> mtongue value is <br>';
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$mtonguev[$i]]++;
				}
				//echo '<br> mtonguev value is <br>';
				//print_r($mvaluev);
				
				$sql="SELECT DISTINCT VALUE ,SMALL_LABEL FROM newjs.MTONGUE";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					if( (is_array($mtongue) && in_array($row['VALUE'],$mtongue))  || (is_array($mtonguev) && in_array($row['VALUE'],$mtonguev)) )
					{	
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						
						$mtongue_field[$row['VALUE']]=array("small_label"=>$row['SMALL_LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						
						//if( $hindi_mtongue==1 && ($row['VALUE']==10 ||  $row['VALUE']==19 || $row['VALUE']==33 || $row['VALUE']==35 || $row['VALUE']==36 || $row['VALUE']==37) )
							//$mtongue_trend['Hindi_Delhi']+=$mtongue_field[$row['VALUE']]['percent'];
						if(in_array($row['VALUE'],$mtongue_east))
						{	
							$mtongue_trend['R3 East']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['R3 East'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_west))
						{	
							$mtongue_trend['R2 West']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['R2 West'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_north))
						{	
							$mtongue_trend['R1 North']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['R1 North'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_south))
						{	
							$mtongue_trend['R4 South']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['R4 South'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_hindi_all))
						{	
							$mtongue_trend['C1 Hindi/All']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['C1 Hindi/All'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_punjabi_delhi))
						{	
							$mtongue_trend['C4 Punjabi/Delhi']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['C4 Punjabi/Delhi'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_delhi_up_bihar))
						{	
							$mtongue_trend['C2 Delhi/UP/Bihar']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['C2 Delhi/UP/Bihar'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_pahari_delhi_up))
						{	
							$mtongue_trend['C3 Pahari/Delhi/UP']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['C3 Pahari/Delhi/UP'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if(in_array($row['VALUE'],$mtongue_konkani_marathi))
						{	
							$mtongue_trend['C5 Konkani/Marathi']+=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['C5 Konkani/Marathi'][$row['VALUE']]=$row['SMALL_LABEL'];
						}
						if($row['VALUE']==1)
						{
							$mtongue_trend['C6 '. $row['SMALL_LABEL']]=$mtongue_field[$row['VALUE']]['percent'];
							$mtongue_label['C6 '. $row['SMALL_LABEL']][$row['VALUE']]=$row['SMALL_LABEL'];
						}
					}
				}
				ksort($mtongue_trend);
				ksort($mtongue_label);
				/*echo 'mtongue percentage is '.$percentage;
				echo '<br>';*/
				//print_r($mtongue_field);
				//print_r($mtongue_trend);

				
				echo '<br>';	
				echo '<br>';	
				
				echo '	<table width=100% border=0 align="center">';
				if(is_array($mtongue_label))
				{
					foreach($mtongue_label as $key => $val)
					{	
						echo    '<tr class="fieldsnew">
							 <td align="center"> '.$key.' </td>';
						foreach($val as $key1 => $val1)
						{
							echo '<td align="center">'.$val1.'</td>';
						}
						echo    '</tr>';
					}
				}
				echo '</table>';
				
			
				echo '<br>';	
				echo '	<table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Mtongue Stats, Weight ['.$rowTrend["W_MTONGUE"].']</td></tr>
						<tr class="formhead" width="100%"><td align="center" colspan=14>Mtongue Stats, Trend Must % '.$mtonguep_m.' , Highly Desire % '.$mtonguep_hd.'  , Desired % '.$mtonguep_d.' else Cosmopolitian </td></tr>
					</table>';
				echo '	<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Mtongue</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Importance</td>
						</tr>';
				
				$cosmo_flag=1;	
				for($i=0;$i<=9;$i++)
					$mtongue_pri[$i]=array();
				foreach($mtongue_field as $key => $val) 
				{
					if (list($inner_key, $inner_val) = each($val)) 
					{
						echo 	'<tr class="fieldsnew">
								<td align="center" height="25">'.$mtongue_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$mtongue_field[$key]['percent'].'</td>';
								if($mtongueTrend[$key])
									echo '<td align="center" height="25">'.$mtongueTrend[$key].'</td>';
								else
									echo '<td align="center" height="25">No trend percentile</td>';
							if($mtongue_field[$key]['percent']>=$mtonguep_m)
							{	
								if(!in_array($key,$mtongue_pri[1]))
									$mtongue_pri[1][]=$key;
								echo '<td align="center" height="25">Must</td>';
								$cosmo_flag=0;
							}
							elseif($mtongue_field[$key]['percent']>=$mtonguep_hd)
							{	
								if(!in_array($key,$mtongue_pri[3]))
									$mtongue_pri[3][]=$key;
								echo '<td align="center" height="25">Highly Desired</td>';
								$cosmo_flag=0;
							}
							elseif($mtongue_field[$key]['percent']>=$mtonguep_d)
							{	
								if(!in_array($key,$mtongue_pri[7]))
									$mtongue_pri[7][]=$key;
								echo '<td align="center" height="25">Desired</td>';
								$cosmo_flag=0;
							}
							else	
								echo '<td align="center" height="25">No</td>';
						
						echo	'</tr>';	
					}
				}
				if($cosmo_flag)		
					echo '<tr class="formhead" width="100%"><td align="center" colspan=14>Mtongue Stats, Trend is Cosmopolitian </td></tr>';
				
				echo '</table>';
			
				echo '<br>';
					
				echo	'<table width=100% border=0 align="center">
						<tr class="formhead" width="100%"><td align="center" colspan=14>Mtongue Stats, Trend % '.$mtonguep.' </td></tr>
						<tr class="formhead" width="100%"><td align="center" colspan=14>Mtongue Stats, Trend Must % '.$mtonguep_m.' , Highly Desired % '.$mtonguep_hd.' , Desired % '.$mtonguep_d.' else Cosmopolitian </td></tr>
					</table>';
				
				echo 	'<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Mtongue</td>
							<td align="center">Percentage</td>
							<td align="center">Trend</td>
							<td align="center">Importance</td>
						</tr>';
				
				$cosmo_flag=1;	
				foreach($mtongue_trend as $key => $val)
				{
					echo	'<tr class="fieldsnew">
							<td align="center" height="25">'.$key.'</td>
							<td align="center" height="25">'.$val.'</td>';
							if($val>=$mtonguep)
								echo '<td align="center" height="25">Yes</td>';
							else
								echo '<td align="center" height="25">No</td>';
							
							if($val>=$mtonguep_m)
							{	
								if(is_array($mtongue_label))
								{
									foreach($mtongue_label as $key2 => $val2)
									{	
										if($key==$key2)
										foreach($val2 as $key3 => $val3)
										{
											if(strstr($key,'C') && !in_array($key3,$mtongue_pri[2]))
													$mtongue_pri[2][]=$key3;
											elseif(strstr($key,'R') && !in_array($key3,$mtongue_pri[5]))
													$mtongue_pri[5][]=$key3;
										}
									}
								}
								echo '<td align="center" height="25">Must</td>';
								$cosmo_flag=0;
							}
							elseif($val>=$mtonguep_hd)
							{	
								if(is_array($mtongue_label))
								{
									foreach($mtongue_label as $key2 => $val2)
									{	
										if($key==$key2)
										foreach($val2 as $key3 => $val3)
										{
											if(strstr($key,'C') && !in_array($key3,$mtongue_pri[4]))
												$mtongue_pri[4][]=$key3;
											elseif(strstr($key,'R') && !in_array($key3,$mtongue_pri[6]))
												$mtongue_pri[6][]=$key3;
										}
									}
								}
								echo '<td align="center" height="25">Highly Desired</td>';
								$cosmo_flag=0;
							}
							elseif($val>=$mtonguep_d)
							{	
								if(is_array($mtongue_label))
								{
									foreach($mtongue_label as $key2 => $val2)
									{	
										if($key==$key2)
										foreach($val2 as $key3 => $val3)
										{
											if(strstr($key,'C') && !in_array($key3,$mtongue_pri[8]))
												$mtongue_pri[8][]=$key3;
											elseif(strstr($key,'R') && !in_array($key3,$mtongue_pri[9]))
												$mtongue_pri[9][]=$key3;
										}
									}
								}
								echo '<td align="center" height="25">Desired</td>';
								$cosmo_flag=0;
							}
							else	
								echo '<td align="center" height="25">No</td>';
					echo 	'</tr>';
				}
				
				if($cosmo_flag)		
					echo '<tr class="formhead" width="100%"><td align="center" colspan=14>Mtongue Stats, Trend is Cosmopolitian </td></tr>';
				echo 	'</table>';
			
			
				$mtongue_final['M']=array();
				$mtongue_final['HD']=array();
				$mtongue_final['D']=array();	
				for($i=1;$i<=9;$i++)
				{
					//echo "i is ".$i;
					$flag_break=0;
					foreach($mtongue_pri[$i] as $key => $val)
					{	
						if($flag_break==1)
							break;
						//echo '<br>';
						//echo "key ".$key." val ".$val;
						for($j=1;$j<=$i-1;$j++)
						{	
							if(in_array($val,$mtongue_pri[$j]))
							{	
								//echo "<br>in flag break<br>";
								$flag_break=1;
								break;
							}
							//else
								//echo "<br>flag escaped<br>";
						}	
					}
				
					if($flag_break==0)	
					{	
						foreach($mtongue_pri[$i] as $key => $val)
						{	
							//print_r($mtongue_pri);
							//echo '<br>';
							//echo "key ".$key." val ".$val;
								
							if( (!in_array($val,$mtongue_final['M'])) && (!in_array($val,$mtongue_final['HD'])) && (!in_array($val,$mtongue_final['D'])) )
							{	
								//echo 'inserting in final and i is '.$i.'<br>';
								if($i==1 || $i==2 || $i==5)
									$mtongue_final['M'][]=$val;
								if($i==3 || $i==4 || $i==6)
									$mtongue_final['HD'][]=$val;
								if($i==7 || $i==8 || $i==9)
									$mtongue_final['D'][]=$val;
							}
							
							/*if( (!in_array($val,$mtongue_final['M'])) && ($i==1 || $i==2 || $i==5)  && !(count($mtongue_pri[1])>=1 && $i==2) && !( (count($mtongue_pri[1])>=1 || count($mtongue_pri[2])>=1)  && $i==5) )
									$mtongue_final['M'][]=$val;
							if( (!in_array($val,$mtongue_final['HD'])) && ($i==3 || $i==4 || $i==6) && !(count($mtongue_pri[3])>=1 && $i==4) && !( (count($mtongue_pri[3])>=1 || count($mtongue_pri[4])>=1)  && $i==6) )
									$mtongue_final['HD'][]=$val;
							if( (!in_array($val,$mtongue_final['D'])) && ($i==7 || $i==8 || $i==9)  && !(count($mtongue_pri[7])>=1 && $i==8) && !( (count($mtongue_pri[7])>=1 || count($mtongue_pri[8])>=1)  && $i==9) )
									$mtongue_final['D'][]=$val;*/
						}
					}
					//echo '<br>';
				}
				
				echo '<br>';	
				
				echo '	<table width=100% border=0 align="center">';
				if(is_array($mtongue_final))
				{
					foreach($mtongue_final as $key => $val)
					{	
						echo    '<tr class="fieldsnew">
							 <td align="center"> '.$key.' </td>';
						foreach($val as $key1 => $val1)
						{
							echo '<td align="center">'.$mtongue_field[$val1]['small_label'].'</td>';
						}
						echo    '</tr>';
					}
				}
				echo '</table>';
			//print_r($mtongue_trend);
			//print_r($mtongue_label);
			//print_r($mtongue_pri);
			//print_r($mtongue_final);
			}

			//CASTE
			if(is_array($caste) || is_array($castev))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				$percentage=0;
				
				$rel_caste=get_all_caste($my_caste);
				$sql_rel_caste="select REL_CASTE from newjs.CASTE_COMMUNITY where PARENT_CASTE ='$my_caste'";
				$sql_rel_caste_result=mysql_query_decide($sql_rel_caste);
				while($myrow_sql_rel_caste_result=mysql_fetch_array($sql_rel_caste_result))
				{
					$rel_caste[]=$myrow_sql_rel_caste_result["REL_CASTE"];
				}
				if(!is_array($rel_caste))
					$rel_caste=Array();
				
				/*echo '<br>caste is <br>';
				print_r($caste);
				echo '<br>castev is <br>';
				print_r($castev);*/
				
				$count=count($caste);
				$countv=count($castev);
				if($count)
                                        $weight=($count + $countv)/$count; 
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$caste[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$castev[$i]]++;
				}
				//print_r($mvaluev);
				$sql="SELECT DISTINCT VALUE ,SMALL_LABEL FROM newjs.CASTE";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					//if(is_array($caste) && in_array($row['VALUE'],$caste))
					if( (is_array($caste) && in_array($row['VALUE'],$caste))  || (is_array($castev) && in_array($row['VALUE'],$castev)) )
					{	
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						$caste_field[$row['VALUE']]=array("small_label"=>$row['SMALL_LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						if(in_array($row['VALUE'],$rel_caste))
						{	
							$caste_trend['Related_Castes']+=$caste_field[$row['VALUE']]['percent'];
							$rel_caste_used[]=$row['VALUE'];
						}
						else
							$caste_trend[$row['SMALL_LABEL']]+=$caste_field[$row['VALUE']]['percent'];
					}
				}
				
				if($caste_field[$my_caste]['percent']>=$castep)
					$my_caste_must=1;
				elseif($caste_field[$my_caste]['percent']>=$castep_hd)
					$my_caste_hd=1;
				elseif($caste_field[$my_caste]['percent']>=$castep_d)
					$my_caste_d=1;
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($caste_field);
				//print_r($caste_trend);
			
				echo '<br><br>';	
				echo '	<table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Caste Stats, Weight ['.$rowTrend["W_CASTE"].']</td></tr>
						<tr class="formhead" width="100%"><td align="center" colspan=14>Must % '.$castep.' ,Highly Desired % '.$castep_hd.' ,Desired % '.$castep_d.'</td></tr>
					</table>';
				echo '	<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Caste</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Importance</td>
						</tr>';
				
				for($i=0;$i<=6;$i++)
					$caste_pri[$i]=array();
				foreach($caste_field as $key => $val) 
				{
					if (list($inner_key, $inner_val) = each($val)) 
					{
						echo 	'<tr class="fieldsnew">
								<td align="center" height="25">'.$caste_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$caste_field[$key]['percent'].'</td>';
								if($casteTrend[$key])
									echo '<td align="center" height="25">'.$casteTrend[$key].'</td>';
								else
									echo '<td align="center" height="25">no trend percentile</td>';
								if($caste_field[$key]['percent']>=$castep)
								{	
									echo '<td align="center" height="25">Must</td>';
									$caste_pri[1][]=$key;
								}
								elseif($caste_field[$key]['percent']>=$castep_hd)
								{	
									echo '<td align="center" height="25">Highly Desired</td>';
									$caste_pri[2][]=$key;
								}
								elseif($caste_field[$key]['percent']>=$castep_d)
								{	
									echo '<td align="center" height="25">Desired</td>';
									$caste_pri[5][]=$key;
								}
								else
									echo '<td align="center" height="25">No Trend</td>';
						echo	'</tr>';	
					}
				}
				
				echo '</table>';
			
				echo '<br>';
					
				echo	'<table width=100% border=0 align="center">
						<tr class="formhead" width="100%"><td align="center" colspan=14>Caste Stats, Trend % '.$castep.'</td></tr>
						<tr class="formhead" width="100%"><td align="center" colspan=14>Must % '.$castep.' ,Highly Desired % '.$castep_hd.' ,Desired % '.$castep_d.'</td></tr>
					</table>';
				
				echo 	'<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Caste</td>
							<td align="center">Percentage</td>
							<td align="center">Trend</td>
							<td align="center">Importance</td>
						</tr>';
																     
				foreach($caste_trend as $key => $val)
				{
					if($key=='Related_Castes')
					{	
						$my_caste_str=', but persons caste ';
						$my_caste_str.=$caste_field[$my_caste]['small_label'];
						if($my_caste_must)
							$my_caste_str.=', is Must ('.$caste_field[$my_caste]['percent'].') ';
						elseif($my_caste_hd)
							$my_caste_str.=', is Highly Desired ('.$caste_field[$my_caste]['percent'].')';
						elseif($my_caste_d)
							$my_caste_str.=', is Desired ('.$caste_field[$my_caste]['percent'].')';
						else
							$my_caste_str.=' ,is ('.$caste_field[$my_caste]['percent'].')';
					}
					else
						$my_caste_str='';
					
					echo	'<tr class="fieldsnew">
							<td align="center" height="25">'.$key.'</td>
							<td align="center" height="25">'.$val.'</td>';
							if($val>=$castep)
								echo '<td align="center" height="25">Yes</td>';
							else
								echo '<td align="center" height="25">No</td>';
							if($val>=$castep)
							{	
								echo '<td align="center" height="25">Must  '.$my_caste_str.'</td>';
								if($key=='Related_Castes')
									$caste_pri[3]=$rel_caste_used;
							}
							elseif($val>=$castep_hd)
							{	
								echo '<td align="center" height="25">Highly Desired  '.$my_caste_str.' </td>';
								if($key=='Related_Castes')
									$caste_pri[4]=$rel_caste_used;
							}
							elseif($val>=$castep_d)
							{	
								echo '<td align="center" height="25">Desired  '.$my_caste_str.'</td>';
								if($key=='Related_Castes')
									$caste_pri[6]=$rel_caste_used;
							}
							else
								echo '<td align="center" height="25">No Trend '.$my_caste_str.'</td>';
					echo 	'</tr>';
				}
				
				echo 	'</table>';
				//print_r($caste_pri);
				
				$caste_final['M']=array();
				$caste_final['HD']=array();
				$caste_final['D']=array();	
				for($i=1;$i<=6;$i++)
				{
					//echo "i is ".$i;
					$flag_break=0;
					/*foreach($caste_pri[$i] as $key => $val)
					{	
						if($flag_break==1)
							break;
						//echo '<br>';
						//echo "key ".$key." val ".$val;
						for($j=1;$j<=$i-1;$j++)
						{	
							if(in_array($val,$caste_pri[$j]))
							{	
								//echo "<br>in flag break<br>";
								$flag_break=1;
								break;
							}
							//else
								//echo "<br>flag escaped<br>";
						}	
					}*/
				
					if($flag_break==0)	
					{	
						foreach($caste_pri[$i] as $key => $val)
						{	
							//print_r($caste_pri);
							//echo '<br>';
							//echo "key ".$key." val ".$val;
								
							//if( (!in_array($val,$caste_final['M'])) && (!in_array($val,$caste_final['HD'])) && (!in_array($val,$caste_final['D'])) )
							{	
								//echo 'inserting in final and i is '.$i.'<br>';
								if( (!in_array($val,$caste_final['M'])) && ($i==1 || $i==3)  && !(count($caste_pri[1])>=1 && $i==3) )
									$caste_final['M'][]=$val;
								if( (!in_array($val,$caste_final['HD'])) && ($i==2 || $i==4) && !(count($caste_pri[2])>=1 && $i==4) )
									$caste_final['HD'][]=$val;
								if( (!in_array($val,$caste_final['D'])) && ($i==5 || $i==6)  && !(count($caste_pri[5])>=1 && $i==6) )
									$caste_final['D'][]=$val;
							}
						}
					}
					//echo '<br>';
				}
				
				echo '<br>';	
				
				
                                echo '  <table width=100% border=0 align="center">';
                                if(is_array($rel_caste_used))
                                {
					echo    '<tr class="fieldsnew">';
					echo 	'<td align="center"> Related Caste Used</td>';
                                        foreach($rel_caste_used as $key => $val)
                                        {
						echo '<td align="center">'.$caste_field[$val]['small_label'].'</td>';
                                        }
					echo    '</tr>';
                                }
                                echo '</table>';
				
				echo '<br>';	

				echo '	<table width=100% border=0 align="center">';
				if(is_array($caste_final))
				{
					foreach($caste_final as $key => $val)
					{	
						echo    '<tr class="fieldsnew">
							 <td align="center"> '.$key.' </td>';
						foreach($val as $key1 => $val1)
						{
							echo '<td align="center">'.$caste_field[$val1]['small_label'].'</td>';
						}
						echo    '</tr>';
					}
				}
				echo '</table>';	
				
				/*echo 'caste final is';
				print_r($caste_final);
				echo '<br>';
				echo 'array diff on m and hd is<br>';
				print_r(array_diff($caste_final['M'],$caste_final['HD']));	
				echo 'array diff on m and d is<br>';
				print_r(array_diff($caste_final['M'],$caste_final['D']));	
				echo 'array diff on hd and d is<br>';
				print_r(array_diff($caste_final['HD'],$caste_final['D']));*/	
			
				$array_diff_m_hd=array_diff($caste_final['M'],$caste_final['HD']);
				if(count($array_diff_m_hd)>=1 && count($array_diff_m_hd)!=count($caste_final['M']))
				{
					$caste_final['HD']=array();
					foreach ($array_diff_m_hd as $key => $value)
					{
						$key2=array_search($value,$caste_final['M']);
						$caste_final['M'][$key2]='';
						$caste_final['HD'][]=$value;
					}
				}
				
				$array_diff_m_d=array_diff($caste_final['M'],$caste_final['D']);
				if(count($array_diff_m_d)>=1 && count($array_diff_m_d)!=count($caste_final['M']))
				{
					$caste_final['HD']=array();
					$caste_final['D']=array();
					foreach ($array_diff_m_d as $key => $value)
					{
						$key2=array_search($value,$caste_final['M']);
						$caste_final['M'][$key2]='';
						$caste_final['HD'][]=$value;
					}
				}
				
				$array_diff_hd_d=array_diff($caste_final['HD'],$caste_final['D']);
				if(count($array_diff_hd_d)>=1 && count($array_diff_hd_d)!=count($caste_final['HD']))
				{
					$caste_final['D']=array();
					foreach ($array_diff_hd_d as $key => $value)
					{
						$key2=array_search($value,$caste_final['HD']);
						$caste_final['HD'][$key2]='';
						$caste_final['D'][]=$value;
					}
				}
				
				echo '<br>';	
				
				//echo 'caste final is';
				//print_r($caste_final);

				echo '	<table width=100% border=0 align="center">';
				if(is_array($caste_final))
				{
					foreach($caste_final as $key => $val)
					{	
						echo    '<tr class="fieldsnew">
							 <td align="center"> '.$key.' </td>';
						foreach($val as $key1 => $val1)
						{
							echo '<td align="center">'.$caste_field[$val1]['small_label'].'</td>';
						}
						echo    '</tr>';
					}
				}
				echo '</table>';	
			}                                                                                                     
	

                        //OCCUPATION
                        if(is_array($occupation) || is_array($occupationv))
			{
				$count=0;
                                $countv=0;
                                $weight=0;

				$non_working=array('16','18','31','36','40','41','42');
				$percentage=0;
				/*echo 'occupation is <br>';
				print_r($occupation);
				echo 'occupationv is <br>';
				print_r($occupationv);*/
				$count=count($occupation);
				$countv=count($occupationv);
                                if($count)
                                        $weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$occupation[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$occupationv[$i]]++;
				}
				//print_r($mvaluev);
				$sql="SELECT DISTINCT VALUE ,LABEL FROM newjs.OCCUPATION";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					//if(is_array($occupation) && in_array($row['VALUE'],$occupation))
					if( (is_array($occupation) && in_array($row['VALUE'],$occupation))  || (is_array($occupationv) && in_array($row['VALUE'],$occupationv)) )
					{
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						$occupation_field[$row['VALUE']]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						if(in_array($row['VALUE'],$non_working))
						{	
							$occupation_trend['NON_WORKING']+=$occupation_field[$row['VALUE']]['percent'];
							$non_working_arr[]=$row['LABEL'];
						}
						else
						{	
							$occupation_trend['WORKING']+=$occupation_field[$row['VALUE']]['percent'];
							$working_arr[]=$row['LABEL'];
						}
					}
				}
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($occupation_field);
				//print_r($occupation_trend);


				echo '<br><br>';	
				echo '	<table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Occupation Stats, Weight ['.$rowTrend["W_OCCUPATION"].']</td></tr>
					</table>';
				echo '	<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Occupation</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
						</tr>';
				
				foreach($occupation_field as $key => $val) 
				{
					if (list($inner_key, $inner_val) = each($val)) 
					{
						echo 	'<tr class="fieldsnew">
								<td align="center" height="25">'.$occupation_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$occupation_field[$key]['percent'].'</td>';
						if($occupationTrend[$key])
							echo '<td align="center" height="25">'.$occupationTrend[$key].'</td>';
						else
							echo '<td align="center" height="25">No trend percentile</td>';
						echo	'</tr>';	
					}
				}
				
				echo '</table>';
			
				echo '<br>';
					
				echo	'<table width=100% border=0 align="center">
						<tr class="formhead" width="100%"><td align="center" colspan=14>Occupation Stats, Trend % '.$occupationp.'</td></tr>';
				
				if(is_array($non_working_arr))
				{
					echo    '<tr class="fieldsnew">
						 <td align="center">Non Working includes: </td>';
					
					foreach($non_working_arr as $key => $val)
					{
						echo '<td align="center">'.$val.'</td>';
					}
					
					echo	'</tr>';
				}
				
				if(is_array($working_arr))
				{
					echo    '<tr class="fieldsnew">
						 <td align="center">Working includes: </td>';
					
					foreach($working_arr as $key => $val)
					{
						echo '<td align="center">'.$val.'</td>';
					}
					
					echo	'</tr>';
				}
				
				echo	'</table>';
				
				echo 	'<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Occupation</td>
							<td align="center">Percentage</td>
							<td align="center">Trend</td>
						</tr>';
																     
				foreach($occupation_trend as $key => $val)
				{
					echo	'<tr class="fieldsnew">
							<td align="center" height="25">'.$key.'</td>
							<td align="center" height="25">'.$val.'</td>';
							if($val>=$occupationp)
								echo '<td align="center" height="25">Yes</td>';
							else
								echo '<td align="center" height="25">No</td>';
					echo 	'</tr>';
				}
				
				echo 	'</table>';
				
						
				if($my_gender=='M')
				{	
					echo	'<table width=100% border=0 align="center">'; 
					echo	'<tr class="formhead" width="100%"><td align="center" colspan=14>Occupation Stats, Trend Non Working:Must % '.$occupationp_bg_nw_m.', Non Working:Highly Desired % '.$occupationp_bg_nw_hd.', Non Working:Desired % '.$occupationp_bg_nw_d.',</td></tr>';
					//echo	'<tr class="formhead" width="100%"><td align="center" colspan=14>Occupation Stats, Trend Working:Must % '.$occupationp_bg_w_m.', Working:Highly Desired % '.$occupationp_bg_w_hd.', Working:Desired % '.$occupationp_bg_w_d.',</td></tr>';
					echo 	'</table>';
                               
					echo    '<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
                                                <tr class="formhead">
							<td align="center">Working/Non Working</td>
                                                        <td align="center">Importance</td>
                                                </tr>';
						
					echo '<tr class="formhead">
						<td align="center">Non Working</td>';
					
					if($occupation_trend['NON_WORKING']>=$occupationp_bg_nw_m)
						echo '<td align="center">Must</td>';
					elseif($occupation_trend['NON_WORKING']>=$occupationp_bg_nw_hd)
						echo '<td align="center">Highly Desired</td>';
					elseif($occupation_trend['NON_WORKING']>=$occupationp_bg_nw_d)
						echo '<td align="center">Desired</td>';
					else	
						echo '<td align="center">No Trend</td>';
						
					echo '</tr>';
					
					//added myself
					/*echo '<tr class="formhead">
						<td align="center">Working</td>';
					if($occupation_trend['WORKING']>=$occupationp_bg_w_m)
						echo '<td align="center">Must</td>';
					elseif($occupation_trend['WORKING']>=$occupationp_bg_w_hd)
						echo '<td align="center">Highly Desired</td>';
					elseif($occupation_trend['WORKING']>=$occupationp_bg_w_d)
						echo '<td align="center">Desired</td>';
					else	
						echo '<td align="center">No Trend</td>';
						
					echo '</tr>';*/
					//added myself ensds
					
					echo 	'</table>';
				}
			}                                                                                                                             

                        //EDUCATION
                        if(is_array($education) || is_array($educationv))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				
				$professional=array('3','4','6','7','8','10','13','14','16','17','18','19','20','21');
				$percentage=0;
				/*echo 'education is <br>';
				print_r($education);
				echo 'educationv is <br>';
				print_r($educationv);*/
				$count=count($education);
				$countv=count($educationv);
                                if($count)
                                        $weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$education[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$educationv[$i]]++;
				}
				//print_r($mvaluev);
				$sql="SELECT DISTINCT VALUE ,LABEL FROM newjs.EDUCATION_LEVEL_NEW";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					//if(is_array($education) &&  in_array($row['VALUE'],$education) )
					if( (is_array($education) && in_array($row['VALUE'],$education))  || (is_array($educationv) && in_array($row['VALUE'],$educationv)) )
                                        {
                                                //$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
                                                $percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);					
						$education_field[$row['VALUE']]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						if(in_array($row['VALUE'],$professional))
						{	
							$education_trend['PROFESSIONAL']+=$education_field[$row['VALUE']]['percent'];
							$professional_arr[]=$row['LABEL'];
						}
						else
						{	
							$education_trend['NON_PROFESSIONAL']+=$education_field[$row['VALUE']]['percent'];
							$non_professional_arr[]=$row['LABEL'];
						}
					}
				}
				if( (is_array($education) && in_array('0',$education)) || ( is_array($educationv) && in_array('0',$educationv)) )
				{
					//$percent=substr(($mvalue[0]*100)/$count,0,5);
					$percent=substr( (( $mvalue[0]*$weight + $mvaluev[0])/($count*$weight + $countv))*100,0,5);					
					$education_field[0]=array("small_label"=>'NOT FILLED UP',"cnt"=>$mvalue[0],"percent"=>$percent);
					$education_trend['NOT FILLED UP']+=$education_field[0]['percent'];
				}	
				
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($education_field);
				//print_r($education_trend);

				
				echo '<br><br>';	
				echo '	<table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Education Stats, Weight ['.$rowTrend["W_EDUCATION"].']</td></tr>
					</table>';
				echo '	<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Education</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
						</tr>';
				
				foreach($education_field as $key => $val) 
				{
					if (list($inner_key, $inner_val) = each($val)) 
					{
						echo 	'<tr class="fieldsnew">
								<td align="center" height="25">'.$education_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$education_field[$key]['percent'].'</td>';
						if($educationTrend[$key])
								echo '<td align="center" height="25">'.$educationTrend[$key].'</td>';
						else
								echo '<td align="center" height="25">No trend percentile</td>';
						echo	'</tr>';	
					}
				}
				
				echo '</table>';
			
				echo '<br>';
					
				echo	'<table width=100% border=0 align="center">
						<tr class="formhead" width="100%"><td align="center" colspan=14>Education Stats, Trend % '.$educationp.'</td></tr>';
                                
				if(is_array($professional_arr))
				{
					echo    '<tr class="fieldsnew">
						 <td align="center">Professional includes: </td>';
					
					foreach($professional_arr as $key => $val)
					{
						echo '<td align="center">'.$val.'</td>';
					}
					echo    '</tr>';
				}
				
				if(is_array($non_professional_arr))
				{
					echo    '<tr class="fieldsnew">
						 <td align="center">Non Professional includes: </td>';
					
					foreach($non_professional_arr as $key => $val)
					{
						echo '<td align="center">'.$val.'</td>';
					}
					echo    '</tr>';
				}
				
				echo 	'</table>';
				
				echo 	'<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Education</td>
							<td align="center">Percentage</td>
							<td align="center">Trend</td>
						</tr>';
																     
				foreach($education_trend as $key => $val)
				{
					echo	'<tr class="fieldsnew">
							<td align="center" height="25">'.$key.'</td>
							<td align="center" height="25">'.$val.'</td>';
							if($val>=$educationp)
								echo '<td align="center" height="25">Yes</td>';
							else
								echo '<td align="center" height="25">No</td>';
					echo 	'</tr>';
				}
				
				echo 	'</table>';
				
				echo	'<table width=100% border=0 align="center">';
						
				if($my_gender=='M')
					echo	'<tr class="formhead" width="100%"><td align="center" colspan=14>Education Stats, Trend Non Professional:Highly Desired % '.$educationp_bg_np_hd.', Professional:Highly Desired % '.$educationp_bg_p_hd.'</td></tr>';
				else
				{	
					echo	'<tr class="formhead" width="100%"><td align="center" colspan=14>Education Stats, Trend Non Professional:Must % '.$educationp_gb_np_m.', Non Professional:Highly Desired % '.$educationp_gb_np_hd.' </td></tr>';
					//echo	'<tr class="formhead" width="100%"><td align="center" colspan=14>Education Stats, Professional:Must % '.$educationp_gb_p_m.' , Professional: Highly Desired % '.$educationp_gb_p_hd.' </td></tr>';
				}
				echo '</table>';
                               
				echo    '<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
                                                <tr class="formhead">
							<td align="center">Professional/Non Professional</td>
                                                        <td align="center">Importance</td>
                                                </tr>';
                                                if($my_gender=='F')
						{
							echo '<tr class="formhead">
								<td align="center">Non Professional</td>';
                                                        if($education_trend['NON_PROFESSIONAL']>=$educationp_gb_np_m)
								echo '<td align="center">Must</td>';
                                                        elseif($education_trend['NON_PROFESSIONAL']>=$educationp_gb_np_hd)
								echo '<td align="center">Highly Desired</td>';
                                                        //elseif($education_trend['NON_PROFESSIONAL']>=$educationp_gb_np_d)
							//	echo '<td align="center">Desired</td>';
							else	
								echo '<td align="center">No Trend</td>';
                                                	
							echo '</tr>';
							
							/*echo '<tr class="formhead">
								<td align="center">Professional</td>';
                                                        if($education_trend['PROFESSIONAL']>=$educationp_gb_p_m)
								echo '<td align="center">Must</td>';
                                                        elseif($education_trend['PROFESSIONAL']>=$educationp_gb_p_hd)
								echo '<td align="center">Highly Desired</td>';
							else	
								echo '<td align="center">No Trend</td>';
                                                	
							echo '</tr>';*/
						}
						else
						{
							echo '<tr class="formhead">';
                                                        if($education_trend['NON_PROFESSIONAL']>=$educationp_bg_np_hd)
							{	
								echo '<td align="center">Non Professional</td>';
								echo '<td align="center">Highly Desired</td>';
                                                        }
							elseif($education_trend['PROFESSIONAL']>=$educationp_bg_p_hd)
							{	
								echo '<td align="center">Professional</td>';
								echo '<td align="center">Highly Desired</td>';
                                                        }
							else	
							{	
								echo '<td align="center">No Trend</td>';
								echo '<td align="center">No Trend</td>';
                                                	}
							echo '</tr>';
						}
					
				echo 	'</table>';
			}                                                                                                     
                       
			
			//CITY
                        if(is_array($city) || is_array($cityv))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				
				$percentage=0;
				/*echo 'city is <br>';
				print_r($city);
				echo 'cityv is <br>';
				print_r($cityv);*/
				$count=count($city);
				$countv=count($cityv);
                                if($count)
                                        $weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$city[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$cityv[$i]]++;
				}
				//print_r($mvaluev);
				$sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE =51 OR COUNTRY_VALUE =128 ORDER BY SORTBY";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					//if(is_array($city)  && in_array($row['VALUE'],$city) )
					if( (is_array($city)  && in_array($row['VALUE'],$city)) || (is_array($cityv)  && in_array($row['VALUE'],$cityv)) )
					{
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						$city_field[$row['VALUE']]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
					}
				}
				if( (is_array($city) && in_array('',$city)) || ( is_array($cityv) && in_array('',$cityv)) )
				{
					//$percent=substr(($mvalue[0]*100)/$count,0,5);
					$percent=substr( (( $mvalue['']*$weight + $mvaluev[''])/($count*$weight + $countv))*100,0,5);					
					$city_field['']=array("small_label"=>'NOT FILLED UP',"cnt"=>$mvalue[''],"percent"=>$percent);
				}	
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($city_field);
				
				echo '<br><br>';	
				echo '	<table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>City Stats, Trend % '.$cityp.', Weight ['.$rowTrend["W_CITY"].']</td></tr>
					</table>';
				echo '	<table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">City</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Trend</td>
						</tr>';
				
				foreach($city_field as $key => $val) 
				{
					if (list($inner_key, $inner_val) = each($val)) 
					{
						echo 	'<tr class="fieldsnew">
								<td align="center" height="25">'.$city_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$city_field[$key]['percent'].'</td>';
						if($cityTrend[$key])
							echo '<td align="center" height="25">'.$cityTrend[$key].'</td>';
						else
							echo '<td align="center" height="25">No Trend percentile</td>';
											
								if($city_field[$key]['percent']>=$cityp)
									echo '<td align="center" height="25">Yes</td>';
								else
									echo '<td align="center" height="25">No</td>';
						echo	'</tr>';	
					}
				}
				
				echo '</table>';
			}



			//MSTATUS
                        if(is_array($mstatus) || is_array($mstatusv))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				
				$percentage=0;
				//echo 'mstatus is <br>';
				//print_r($mstatus);
				$count=count($mstatus);
				$countv=count($mstatusv);
                                if($count)
                                        $weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$mstatus[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$mstatusv[$i]]++;
				}
				//print_r($mvalue);
				
				$MSTATUS=array("N" => "Never Married","W" => "Widowed","D" => "Divorced","S" => "Separated","O" => "Other");	
				foreach ($MSTATUS as $key => $value)
				{
					//if( is_array($mstatus) && in_array($key,$mstatus))
					if( (is_array($mstatus) && in_array($key,$mstatus)) || (is_array($mstatusv) && in_array($key,$mstatusv))  )
					{
						//$percent=substr(($mvalue[$key]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$key]*$weight + $mvaluev[$key])/($count*$weight + $countv))*100,0,5); 						
						$mstatus_field[$key]=array("small_label"=>$value,"cnt"=>$mvalue[$key],"percent"=>$percent);
						if($key!='N')
							$married_earler_percent+=$percent;
						elseif($key=='N')
							$never_married_percent+=$percent;
					}
				}
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($mstatus_field);
				
				echo '<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Mstatus Stats, Trend % '.$mstatusp.', Weight ['.$rowTrend["W_MSTATUS"].']</td></tr>
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Mstatus</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Trend</td>
						</tr>';
																     
				foreach($mstatus_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" height="25">'.$mstatus_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$mstatus_field[$key]['percent'].'</td>';
								if($mstatus_field[$key]['small_label']=='Never Married')
								{
									if($notMarriedTrend)
										echo '<td align="center" height="25">'.$notMarriedTrend.'</td>';
									else
										echo '<td align="center" height="25">No Trend percentile</td>';
								}
								elseif($mstatus_field[$key]['small_label']=='Widowed')
								{
									if($marriedTrend)
										echo '<td align="center" height="25" rowspan="3">'.$marriedTrend.'</td>';
									else
										echo '<td align="center" height="25" rowspan="3">No Trend percentile</td>';
								}
								if($mstatus_field[$key]['percent']>=$mstatusp)
                                                                        echo '<td align="center" height="25">Yes</td>';
                                                                else
                                                                        echo '<td align="center" height="25">No</td>';
						echo    '</tr>';
					}
				}
				
				echo '<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Married Earlier, Trend Must % '.$mstatusm.'  ,Highly Desired % '.$mstatusm_hd.'  ,Desired % '.$mstatusm_d.'</td></tr>';
				//echo '<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Never Married, Trend Must % '.$mstatusnm.' </td></tr>';
				echo 		'<tr class="formhead">
							<td align="center">Married Earlier/Never Married</td>
							<td align="center">Importance</td>
						</tr>';
				
				echo    	'<tr class="fieldsnew">';
							echo '<td align="center" height="25">Married Earler: '.$married_earler_percent.'</td>';
						if($married_earler_percent>=$mstatusm)
							echo '<td align="center" height="25">Must</td>';
						elseif($married_earler_percent>=$mstatusm_hd)
							echo '<td align="center" height="25">Highly Desired</td>';
						elseif($married_earler_percent>=$mstatusm_d)
							echo '<td align="center" height="25">Desired</td>';
						else
							echo '<td align="center" height="25">No Trend</td>';
				echo    	'</tr>';
				
				/*echo    	'<tr class="fieldsnew">';
							echo '<td align="center" height="25">Never Married</td>';
						if($never_married_percent>=$mstatusnm)
							echo '<td align="center" height="25">Must</td>';
						else
							echo '<td align="center" height="25">No Trend</td>';
				echo    	'</tr>';*/
																     
				echo '</table>';
			}


			//DIET
                        if(is_array($diet) || is_array($dietv))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				
				$percentage=0;
				//echo 'diet is <br>';
				//print_r($diet);
				$count=count($diet);
				$countv=count($dietv);
                                if($count)
                                        $weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$diet[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$dietv[$i]]++;
				}
				//print_r($mvalue);
				
				$DIET=array("V" => "VEG","N" => "NON VEG","J" => "JAIN","" => "Doesnt Matter");	
				foreach ($DIET as $key => $value)
				{
					//if( is_array($diet) && in_array($key,$diet))
					if( (is_array($diet) && in_array($key,$diet)) || (is_array($dietv) && in_array($key,$dietv))  )
					{
						//$percent=substr(($mvalue[$key]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$key]*$weight + $mvaluev[$key])/($count*$weight + $countv))*100,0,5); 						
						$diet_field[$key]=array("small_label"=>$value,"cnt"=>$mvalue[$key],"percent"=>$percent);
						/*if($key!='N')
							$married_earlier_percent+=$percent;
						elseif($key=='N')
							$never_married_percent+=$percent;*/
					}
				}
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($diet_field);
				
	
				echo '<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Diet Stats, Trend % '.$dietp.'</td></tr>
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Diet</td>
							<td align="center">Percentage</td>
							<td align="center">Trend</td>
						</tr>';
																     
				foreach($diet_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" height="25">'.$diet_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$diet_field[$key]['percent'].'</td>';
								if($diet_field[$key]['percent']>=$dietp)
									echo '<td align="center" height="25">Yes</td>';
								else
									echo '<td align="center" height="25">No</td>';
						echo    '</tr>';
					}
				}
				
				/*echo '<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Diet, Trend Must % '.$dietm.'  ,Highly Desired % '.$dietm_hd.'  ,Desired % '.$dietm_d.'</td></tr>';
				
				echo 		'<tr class="formhead">
							<td align="center">Married Earlier/Never Married</td>
							<td align="center">Importance</td>
						</tr>';
				
				echo    	'<tr class="fieldsnew">';
							echo '<td align="center" height="25">Married Earler: '.$married_earlier_percent.'</td>';
						if($married_earlier_percent>=$dietm)
						{	
							echo '<td align="center" height="25">Must</td>';
							$married_earlier_str='M';
							$n1++;
						}
						elseif($married_earlier_percent>=$dietm_hd)
						{	
							echo '<td align="center" height="25">Highly Desired</td>';
							$married_earlier_str='H';
							$n2++;
						}
						elseif($married_earlier_percent>=$dietm_d)
						{	
							echo '<td align="center" height="25">Desired</td>';
							$married_earlier_str='D';
							$n3++;
						}
						else
							echo '<td align="center" height="25">No Trend</td>';
				echo    	'</tr>';*/
				
				echo '</table>';
			}


			
			//MANGLIK
                        if(is_array($manglik) || is_array($manglikv))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				
				$percentage=0;
				//echo 'manglik is <br>';
				//print_r($manglik);
				$count=count($manglik);
				$countv=count($manglikv);
                                if($count)
                                        $weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$manglik[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$manglikv[$i]]++;
				}
				//print_r($mvalue);
			
				$MANGLIK=array("M" => "Manglik","N" => "Non Manglik","D" => "Don't know/Not Applicable",""=>"Not filled");
	
				foreach ($MANGLIK as $key => $value)
				{
					//if( is_array($manglik) && in_array($key,$manglik))
					if( (is_array($manglik) && in_array($key,$manglik)) || (is_array($manglikv) && in_array($key,$manglikv))  )
					{
						//$percent=substr(($mvalue[$key]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$key]*$weight + $mvaluev[$key])/($count*$weight + $countv))*100,0,5); 						
						$manglik_field[$key]=array("small_label"=>$value,"cnt"=>$mvalue[$key],"percent"=>$percent);
					}
				}
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($manglik_field);
				
				echo '<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Manglik Stats, Trend % '.$manglikp.', Weight ['.$rowTrend["W_MANGLIK"].']</td></tr>
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Manglik</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Trend</td>
						</tr>';
																     
				foreach($manglik_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" height="25">'.$manglik_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$manglik_field[$key]['percent'].'</td>';
								if($manglik_field[$key]['small_label']=='Manglik')
                                                                {
                                                                        if($nonManglikTrend)
                                                                                echo '<td align="center" height="25">'.$nonManglikTrend.'</td>';
                                                                        else
                                                                                echo '<td align="center" height="25">No Trend percentile</td>';
                                                                }
                                                                elseif($manglik_field[$key]['small_label']=='Non Manglik')
                                                                {
                                                                        if($manglikTrend)
                                                                                echo '<td align="center" height="25" rowspan="3">'.$manglikTrend.'</td>';
                                                                        else
                                                                                echo '<td align="center" height="25" rowspan="3">No Trend percentile</td>';
                                                                }
								if($manglik_field[$key]['percent']>=$manglikp)
                                                                        echo '<td align="center" height="25">Yes</td>';
                                                                else
                                                                        echo '<td align="center" height="25">No</td>';
						echo    '</tr>';
					}
				}
				
				if($my_manglik=='N' || $my_manglik=='D' || $my_manglik=='M')
				{
					echo	'<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Manglik Stats </td></tr>
						<tr class="formhead">
							<td align="center">Manglik</td>
							<td align="center">Importance</td>
						</tr>';
					echo    '<tr class="fieldsnew">';
							if($manglik_field['M']['percent']==0)
							{	
								echo '<td align="center" height="25">Non Manglik</td>';
								echo '<td align="center" height="25">Must</td>';
							}
							elseif($manglik_field['N']['percent']==0)
							{	
								echo '<td align="center" height="25">Manglik</td>';
								echo '<td align="center" height="25">Must</td>';
							}
							else
							{	
								echo '<td align="center" height="25">No Trend</td>';
								echo '<td align="center" height="25">No Trend</td>';
							}
					echo    '</tr>';
				}												     
				echo '</table>';
			}



                        //AGE
                        if(is_array($age) || is_array($agev))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
	
				$percentage=0;
				/*echo 'age is <br>';
				print_r($age);
				echo 'agev is <br>';
				print_r($agev);*/
				$count=count($age);
				$countv=count($agev);
                                
				if($count)
					$weight=($count + $countv)/$count;
				//echo '<br> weight is '.$weight;	
				$mvalue=array();
				$mvaluev=array();
				$mvalue_mod=array();
				for($i=0;$i<$count;$i++)
				{
					$age_std[]=$age[$i];
					$mvalue[$age[$i]]++;
				}
				//$count_mvalue=count($mvalue);
				//print_r($mvalue);
				
				for($i=0;$i<$countv;$i++)
				{
					$age_std[]=$agev[$i];
					$mvaluev[$agev[$i]]++;
				}
				//print_r($mvaluev);
				
				//for($i=0;$i<count($mvalue);$i++)
                                foreach ( $mvalue as $key => $value)
				{
					//$mvalue_mod[$age[$i]]=$mvalue[$age[$i]];
					$mvalue_mod[$key]=$mvalue[$key];
                                }
				//echo '<br>mvalue mod is  is <br>';
                                //print_r($mvalue_mod);
                                                                                                                             
                                //for($i=0;$i<count($mvaluev);$i++)
                                foreach ( $mvaluev as $key => $value)
                                {
					//$mvalue_mod[$agev[$i]]+=(float)($mvaluev[$agev[$i]]/$weight);
					if($wieght)
						$mvalue_mod[$key]+=(float)($mvaluev[$key]/$weight);
					else
						$mvalue_mod[$key]+=$mvaluev[$key];
				}
				//echo '<br>mvalue mod is  is <br>';
                                //print_r($mvalue_mod);
				
				//$mode=calculate_mode($mvalue);
				$mode=calculate_mode($mvalue_mod);
													     
				//foreach ($mvalue as $key => $value)
				$flag_small=0;
                                for($key=18;$key<=70;$key++)
                                {
                                        if( (is_array($age) && in_array($key,$age)) || (is_array($agev) && in_array($key,$agev))  )
                                        {
                                                if($flag_small==0)
                                                {
                                                        $age_low=$key;
                                                        $flag_small=1;
                                                }
                                                $age_high=$key;
                                        }
                                }
				
				for($key=$age_low;$key<=$age_high;$key++)
				{
					//$total_age+=$key;
					//$percent=substr(($mvalue[$key]*100)/$count,0,5);
					//if( (is_array($age) && in_array($key,$age)) || (is_array($agev) && in_array($key,$agev))  )
					{	
						$percent=substr( (( $mvalue[$key]*$weight + $mvaluev[$key])/($count*$weight + $countv))*100,0,5);
						$age_field[$key]=array("small_label"=>$key,"cnt"=>$mvalue[$key],"percent"=>$percent);
						$age_graph[$key]=$percent*10;
					}
				}
				
				//$mean=$total_age/$count_mvalue;
				//if(count($age)>1)
				//	$std=standard_deviation($age);
				if(count($age_std)>1)
					$std=standard_deviation($age_std);
				else
					$std=0;
				//echo 'age mode is '.$mode.' age mean is '.$mean.' std is '.$std;	
				/*echo 'caste percentage is '.$percentage;
				echo '<br>';*/
				//print_r($age_field);
				ksort($age_field);
				//print_r($age_field);
			      	//echo '<br> age low is '.$age_low.' age high is '.$age_high; 
				
				$max_age_percent=0;
				if($age_high-3<=$age_low)
					$high=$age_low;
				else
					$high=$age_high-3;
				
				for($i=$age_low;$i<=$high;$i++)
				{
					$temp_precent=0;
					for($j=$i;$j<($i+4);$j++)
					{
						$temp_precent+=$age_field[$j]['percent'];	
					}
					if($temp_precent>$max_age_percent)
					{
						$max_age_percent=$temp_precent;
						$age_trend=$i;		
					}
				}
			      	//echo '<br> age trend is '.$age_trend ; 
				


				echo '<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Age Stats, Trend = Mode +- ('.$agep.' * Standard Deviation), Mode is '.$mode.', Standard Deviation is '.$std.', Weight ['.$rowTrend["W_AGE"].']</td></tr>
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Age Stats, New Trend is combination of BEST 4 , '.$max_age_percent.'%</td></tr>
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Age</td>
							<td align="center">Graph</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Trend</td>
							<td align="center">Importance</td>
						</tr>';
																     
				foreach($age_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" age="25">'.$age_field[$key]['small_label'].'</td>
								<td align="left" height="25"><img src="http://ser4.jeevansathi.com/profile/images/bar_complete.gif" width="'.$age_graph[$key].'" height="13"></td>
								<td align="center" age="25">'.$age_field[$key]['percent'].'</td>';
								if($ageTrend[$key])
									echo '<td align="center" height="25">'.$ageTrend[$key].'</td>';
								else
									echo '<td align="center" height="25">No Trend percentile</td>';
								//if($key==$mode || ($key>=$mode && $key<=round($mode+$agep*$std)) || ($key<=$mode && $key>=round($mode-$agep*$std)) )
								if($key>=$age_trend && $key<=$age_trend+3 )
								{	
									echo '<td align="center" age="25">Yes</td>';
                                                                        if($max_age_percent>=$agep_m)
										echo '<td align="center" height="25">Must</td>';
                                                                        elseif($max_age_percent>=$agep_hd)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_age_percent>=$agep_d)
										echo '<td align="center" height="25">Desired</td>';
								}
                                                                elseif($key>=($age_trend-2) && $key<=$age_trend)
                                                                {
                                                                        echo '<td align="center" height="25">No</td>';
                                                                        if($max_age_percent>=$agep_m)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_age_percent>=$agep_hd)
										echo '<td align="center" height="25">Desired</td>';
                                                                        else
										echo '<td align="center" height="25">No</td>';
                                                                }
                                                                elseif($key>=($age_trend+3) && $key<=$age_trend+5)
                                                                {
                                                                        echo '<td align="center" height="25">No</td>';
                                                                        if($max_age_percent>=$agep_m)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_age_percent>=$agep_hd)
										echo '<td align="center" height="25">Desired</td>';
                                                                        else
										echo '<td align="center" height="25">No</td>';
                                                                }
                                                                else
                                                                {
                                                                        echo '<td align="center" height="25">No</td>';
                                                                        echo '<td align="center" height="25">No</td>';
                                                                }
						
						echo    '</tr>';
					}
				}
																     
				echo '</table>';
			}



                        //HEIGHT
                        if(is_array($height) || is_array($heightv))
			{
                                $count=0;
                                $countv=0;
                                $weight=0;
				
				$percentheight=0;
				/*echo 'height is <br>';
				print_r($height);
				echo 'heightv is <br>';
				print_r($heightv);*/
				$count=count($height);
				$countv=count($heightv);
                                                                                                                             
                                if($count)
                                        $weight=($count + $countv)/$count;
                                //echo '<br> weight is '.$weight;
				
				$mvalue=array();
				$mvaluev=array();
				$mvalue_mod=array();
				for($i=0;$i<$count;$i++)
				{
                                        $height_std[]=$height[$i];
					$mvalue[$height[$i]]++;
				}
				//print_r($mvalue);
				//$count_mvalue=count($mvalue);
				for($i=0;$i<$countv;$i++)
				{
                                        $height_std[]=$heightv[$i];
					$mvaluev[$heightv[$i]]++;
				}
				//print_r($mvaluev);
                                
				//for($i=0;$i<count($mvalue);$i++)
                                foreach ( $mvalue as $key => $value)
				{
                                        //$mvalue_mod[$height[$i]]=$mvalue[$height[$i]];
					$mvalue_mod[$key]=$mvalue[$key];
                                }
				//echo '<br>mvalue mod is  is <br>';
                                //print_r($mvalue_mod);
                                                                                                                             
                                //for($i=0;$i<count($mvaluev);$i++)
                                foreach ( $mvaluev as $key => $value)
                                {
					//$mvalue_mod[$heightv[$i]]+=(float)($mvaluev[$heightv[$i]]/$weight);
					if($weight)
						$mvalue_mod[$key]+=(float)($mvaluev[$key]/$weight);
					else
						$mvalue_mod[$key]+=$mvaluev[$key];
				}
				//echo '<br>mvalue mod is  is <br>';
                                //print_r($mvalue_mod);
	
				//$mode=calculate_mode($mvalue);
				$mode=calculate_mode($mvalue_mod);
			     
                                $flag_small=0;
                                for($key=1;$key<=32;$key++)
                                {
                                        if( (is_array($height) && in_array($key,$height)) || (is_array($heightv) && in_array($key,$heightv))  )
                                        {
                                                if($flag_small==0)
                                                {
                                                        $height_low=$key;
                                                        $flag_small=1;
                                                }
                                                $height_high=$key;
                                        }
                                }
			      	//echo '<br> height low is '.$height_low.' height high is '.$height_high; 
  
	 
				$sql="SELECT DISTINCT VALUE ,LABEL FROM newjs.HEIGHT";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					//if( (is_array($height) && in_array($row['VALUE'],$height)) ||  (is_array($heightv) && in_array($row['VALUE'],$heightv)))
					if($row['VALUE']>=$height_low && $row['VALUE']<=$height_high)
					{
						//$total_height+=$row['VALUE'];
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						$height_field[$row['VALUE']]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						$height_graph[$row['VALUE']]=$percent*10;
					}
				}
				//$mean=$total_height/$count_mvalue;
				//if(count($height)>1)
				//	$std=standard_deviation($height);
				if(count($height_std)>1)
					$std=standard_deviation($height_std);
				else
					$std=0;

				//echo 'height mode is '.$mode.' height mean is '.$mean.' std is '.$std;	
				/*echo 'caste percentheight is '.$percentheight;
				echo '<br>';*/
				//print_r($height_field);
                                
				$max_height_percent=0;
                                
				if($height_high-3<=$height_low)
                                        $high=$height_low;
                                else
                                        $high=$height_high-3;
                                
				for($i=$height_low;$i<=$high;$i++)
                                {
                                        $temp_precent=0;
                                        for($j=$i;$j<($i+4);$j++)
                                        {
                                                $temp_precent+=$height_field[$j]['percent'];
                                        }
                                        if($temp_precent>$max_height_percent)
                                        {
                                                $max_height_percent=$temp_precent;
                                                $height_trend=$i;
                                        }
                                }
                                //echo '<br> height trend is '.$height_trend ;

			       
				echo '<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Height Stats, Trend = Mode +- ('.$heightp.' * Standard Deviation), Mode is '.$height_field[$mode]['small_label'].', Standard Deviation is '.$std.', Weight ['.$rowTrend["W_HEIGHT"].']</td></tr>
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Height Stats, New Trend is combination of BEST 4 , '.$max_height_percent.'%</td></tr>
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Height</td>
							<td align="center">Graph</td>
							<td align="center">Percentage</td>
							<td align="center"><Trend percentile</td>
							<td align="center">Trend</td>
							<td align="center">Importance</td>
						</tr>';
																     
				foreach($height_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" height="25">'.$height_field[$key]['small_label'].'</td>
								<td align="left" height="25"><img src="http://ser4.jeevansathi.com/profile/images/bar_complete.gif" width="'.$height_graph[$key].'" height="13"></td>
								<td align="center" height="25">'.$height_field[$key]['percent'].'</td>';
								if($heightTrend[$key])
                                                                        echo '<td align="center" height="25">'.$heightTrend[$key].'</td>';
                                                                else
                                                                        echo '<td align="center" height="25">No Trend percentile</td>';
								//if($key==$mode || ($key>=$mode && $key<=round($mode+$heightp*$std))   ||  ($key<=$mode && $key>=round($mode-$heightp*$std)) )
								if($key>=$height_trend && $key<=$height_trend+3 )
								{	
									echo '<td align="center" height="25">Yes</td>';
                                                                        if($max_height_percent>=$heightp_m)
										echo '<td align="center" height="25">Must</td>';
                                                                        elseif($max_height_percent>=$heightp_hd)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_height_percent>=$heightp_d)
										echo '<td align="center" height="25">Desired</td>';
                                                                }
								elseif($key>=($height_trend-2) && $key<=$height_trend)
                                                                {
									echo '<td align="center" height="25">No</td>';
                                                                        if($max_height_percent>=$heightp_m)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_height_percent>=$heightp_hd)
										echo '<td align="center" height="25">Desired</td>';
                                                                        else
										echo '<td align="center" height="25">No</td>';
								}
                                                                elseif($key>=($height_trend+3) && $key<=$height_trend+5)
                                                                {       
                                                                        echo '<td align="center" height="25">No</td>';
                                                                        if($max_height_percent>=$heightp_m)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_height_percent>=$heightp_hd)
										echo '<td align="center" height="25">Desired</td>';
                                                                        else
										echo '<td align="center" height="25">No</td>';
                                                                }
                                                                else
                                                                {       
                                                                        echo '<td align="center" height="25">No</td>';
                                                                        echo '<td align="center" height="25">No</td>';
                                                                }

						echo    '</tr>';
					}
				}
																     
				echo '</table>';
			}



                        //SALARY
                        if(is_array($income) || is_array($incomev))
			{
			        $count=0;
                                $countv=0;
                                $weight=0;
	
				$percentincome=0;
				/*echo 'income is <br>';
				print_r($income);
				echo 'incomev is <br>';
				print_r($incomev);*/
				$count=count($income);
				$countv=count($incomev);
				
				if($count)
                                        $weight=($count + $countv)/$count;

				for($i=0;$i<$count;$i++)
				{
					/*
					no income value is 0
					mapping incomes
					25000$ => 2-3 L
					25-50000$ => 3-4 L
					50-75000$ => 4-5 L
					75-1L $   => 7.5 -10 L
					1-1.5L $  => 10 L +
					1.5-2L $  => 10 L +
					2L+ $ => 10L +

					5-7 L to value 7
					7.5 -10 L to value 8
					10 +l to value 9
					*/
					
					$income[$i]=getTrendsSortBy($income[$i]);
				}	
				
				for($i=0;$i<$countv;$i++)
				{
					$incomev[$i]=getTrendsSortBy($incomev[$i]);
				}
				
				/*echo '<br>new income is <br>';
				print_r($income);
				echo '<br>new incomev is <br>';
				print_r($incomev);*/
				
				$mvalue=array();
				$mvaluev=array();
				$mvalue_mod=array();
				
				for($i=0;$i<$count;$i++)
				{
                                        $income_std[]=$income[$i];
					$mvalue[$income[$i]]++;
				}
				/*echo '<br>mvalue is <br>';
				print_r($mvalue);*/
				
				for($i=0;$i<$countv;$i++)
				{
                                        $income_std[]=$incomev[$i];
					$mvaluev[$incomev[$i]]++;
				}
				/*echo '<br>mvaluev is <br>';
				print_r($mvaluev);*/
				
				//for($i=0;$i<count($mvalue);$i++)
                                foreach ( $mvalue as $key => $value)
				{
                                        //echo $mvalue[$income[$i]].' and income[i] is '.$income[$i].'<br>';
					//$mvalue_mod[$income[$i]]=$mvalue[$income[$i]];
					$mvalue_mod[$key]=$mvalue[$key];
                                }
				/*echo '<br>mvalue mod is  is <br>';
                                print_r($mvalue_mod);*/
                                                                                                                             
                                //for($i=0;$i<count($mvaluev);$i++)
                                foreach ( $mvaluev as $key => $value)
                                {
					//$mvalue_mod[$incomev[$i]]+=(float)($mvaluev[$incomev[$i]]/$weight);
					if($weight)
						$mvalue_mod[$key]+=(float)($mvaluev[$key]/$weight);
                                	else
						$mvalue_mod[$key]+=$mvaluev[$key];
				}
				/*echo '<br>mvaluev mod  is <br>';
                                print_r($mvalue_mod);*/
				
				$sql="SELECT DISTINCT VALUE ,LABEL FROM newjs.INCOME";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$row['VALUE']=getTrendsSortBy($row['VALUE']);
					$row['LABEL']=getTrendsSortByRupeeLabel($row['VALUE']);
				/*
					if($row['VALUE']==4)
						$row['LABEL']='Rs. 2,00,001 - 3,00,000';
					if($row['VALUE']==5)
						$row['LABEL']='Rs. 3,00,001 - 4,00,000';
					if($row['VALUE']==6)
						$row['LABEL']='Rs. 4,00,001 - 5,00,000';
					if($row['VALUE']==7)
						$row['LABEL']='Rs. 5,00,001 - 7,50,000';
					if($row['VALUE']==8)
						$row['LABEL']='Rs. 7,50,001 - 10,00,000';
					if($row['VALUE']==9)
						$row['LABEL']='Rs. 10,00,000 and above';
	*/
					//if(is_array($income) && in_array($row['VALUE'],$income))
					{
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						$income_field[$row['VALUE']]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						$income_graph[$row['VALUE']]=$percent*10;
					}
				}
				
				//echo 'caste percentincome is '.$percentincome;
				//echo '<br> income field <br>';
				//print_r($income_field);
				
				//echo '<br> income field after ksort <br>';
				ksort($income_field);
				//print_r($income_field);
			      	//if(count($income)>1)
                                //        $std=standard_deviation($income);
			      	if(count($income_std)>1)
                                        $std=standard_deviation($income_std);
                                else
                                        $std=0;
                                
				$max_income_percent=0;
                                
				for($i=0;$i<=6;$i++)
                                {
                                        $temp_precent=0;
                                        for($j=$i;$j<($i+4);$j++)
                                        {
                                                $temp_precent+=$income_field[$j]['percent'];
                                        }
                                        if($temp_precent>$max_income_percent)
                                        {
                                                $max_income_percent=$temp_precent;
                                                $income_trend=$i;
                                        }
                                }
                                //echo '<br> income trend is '.$income_trend ;

				
				$mode=calculate_mode($mvalue_mod);
				echo '	<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Income Stats, Trend = Mode +- ('.$incomep.' * Standard Deviation), Mode is '.$income_field[$mode]['small_label'].', Standard Deviation is '.$std.', Weight ['.$rowTrend["W_INCOME"].']</td></tr>
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Income Stats, New Trend is combination of BEST 4, '.$max_income_percent.'%</td></tr>
						<!--tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Income Stats, Trend  +-'.$incomep.' of the Mode, Mode is '.$income_field[$mode]['small_label'].'</td></tr-->
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Income</td>
							<td align="center">Graph</td>
							<td align="center">Percentage</td>
							<td align="center">Trend percentile</td>
							<td align="center">Trend</td>
							<td align="center">Importance</td>
						</tr>';
																     
				foreach($income_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" income="25">'.$income_field[$key]['small_label'].'</td>                                                   
								<td align="left" height="25"><img src="http://ser4.jeevansathi.com/profile/images/bar_complete.gif" width="'.$income_graph[$key].'" height="13"></td>
								<td align="center" income="25">'.$income_field[$key]['percent'].'</td>';
								if($incomeTrend[$key])
                                                                        echo '<td align="center" height="25">'.$incomeTrend[$key].'</td>';
                                                                else
                                                                        echo '<td align="center" height="25">No Trend percentile</td>';
								//if($key==$mode || ($key>=$mode && $key<=round($mode+$incomep*$std))   ||  ($key<=$mode && $key>=round($mode-$incomep*$std)) )
								if($key>=$income_trend && $key<=$income_trend+3)
								{	
									echo '<td align="center" height="25">Yes</td>';
                                                                        if($max_income_percent>=$incomep_m)
										echo '<td align="center" height="25">Must</td>';
                                                                        elseif($max_income_percent>=$incomep_hd)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_income_percent>=$incomep_d)
										echo '<td align="center" height="25">Desired</td>';
								}
								elseif($key>=($income_trend-2) && $key<=$income_trend)
								{	
									echo '<td align="center" height="25">No</td>';
                                                                        if($max_income_percent>=$incomep_m)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_income_percent>=$incomep_hd)
										echo '<td align="center" height="25">Desired</td>';
                                                                        else
										echo '<td align="center" height="25">No</td>';
								}
								elseif($key>=($income_trend+3) && $key<=$income_trend+5)
								{	
									echo '<td align="center" height="25">No</td>';
                                                                        if($max_income_percent>=$incomep_m)
										echo '<td align="center" height="25">Highly Desired</td>';
                                                                        elseif($max_income_percent>=$incomep_hd)
										echo '<td align="center" height="25">Desired</td>';
                                                                        else
										echo '<td align="center" height="25">No</td>';
								}
								else
								{	
									echo '<td align="center" height="25">No</td>';
									echo '<td align="center" height="25">No</td>';
								}
						echo    '</tr>';
					}
				}
				echo '</table>';
				
				/*if($key==$mode || ($key>=($mode-$incomep) && $key<=$mode) || ($key<=($mode+$incomep) && $key>=$mode) )
					echo '<td align="center" income="25">Yes</td>';
				else
					echo '<td align="center" income="25">No</td>';*/
			}


                        //COUNTRY
                        if(is_array($country) || is_array($countryv))
			{
                                $count=0;
                                $countv=0;
				$weight=0;
				
				$percentcountry=0;
				//echo 'country is <br>';
				//print_r($country);
				$count=count($country);
				$countv=count($countryv);
                                if($count)
					$weight=($count + $countv)/$count;
				
				$mvalue=array();
				$mvaluev=array();
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$country[$i]]++;
				}
				//print_r($mvalue);
				for($i=0;$i<$countv;$i++)
				{
					$mvaluev[$countryv[$i]]++;
				}
				//print_r($mvalue);
				$sql="SELECT DISTINCT VALUE ,LABEL FROM newjs.COUNTRY";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					if( (is_array($country)  && in_array($row['VALUE'],$country)) ||  (is_array($countryv)  && in_array($row['VALUE'],$countryv)))
					{
						//$percent=substr(($mvalue[$row['VALUE']]*100)/$count,0,5);
						$percent=substr( (( $mvalue[$row['VALUE']]*$weight + $mvaluev[$row['VALUE']])/($count*$weight + $countv))*100,0,5);
						
						$country_field[$row['VALUE']]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent);
						if($row['VALUE']!=51)
							$percent_nri+=$percent;
					}
				}
				/*echo 'caste percentcountry is '.$percentcountry;
				echo '<br>';*/
				//print_r($country_field);
			       
				echo '<br><br>';
				echo '  <table width=100% border=0 align="center">
						<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Country Stats, Trend % '.$countryp.'</td></tr>
					</table>';
				echo '  <table width="100%"  border="1" cellspacing="0" cellpadding="0" class="mediumblack">
						<tr class="formhead">
							<td align="center">Country</td>
							<td align="center">Percentage</td>
							<td align="center">Trend</td>
						</tr>';
																     
				foreach($country_field as $key => $val)
				{
					if (list($inner_key, $inner_val) = each($val))
					{
						echo    '<tr class="fieldsnew">
								<td align="center" height="25">'.$country_field[$key]['small_label'].'</td>
								<td align="center" height="25">'.$country_field[$key]['percent'].'</td>';
								if($country_field[$key]['percent']>=$countryp)
									echo '<td align="center" height="25">Yes</td>';
								else
									echo '<td align="center" height="25">No</td>';
						echo    '</tr>';
					}
				}
					
				echo '<tr class="formhead" width="100%" bgcolor="#efefef"><td align="center" colspan=14>Nri, Trend Must % '.$countryn_m.'  ,Highly Desired % '.$countryn_hd.'  ,Desired % '.$countryn_d.', Weight ['.$rowTrend["W_NRI"].']</td></tr>';
				echo 		'<tr class="formhead">
							<td align="center">NRI</td>
							<td align="center">Importance</td>
						</tr>';
				
				echo    	'<tr class="fieldsnew">';
							echo '<td align="center" height="25">'.$percent_nri.'</td>';
						if($percent_nri>=$countryn_m)
							echo '<td align="center" height="25">Must</td>';
						elseif($percent_nri>=$countryn_hd)
							echo '<td align="center" height="25">Highly Desired</td>';
						elseif($percent_nri>=$countryn_d)
							echo '<td align="center" height="25">Desired</td>';
						else
							echo '<td align="center" height="25">No</td>';
				echo    	'</tr>';
				
				echo '</table>';
			}

			echo '</body></html>';
			$smarty->assign("total",$total);
			$smarty->assign("type",$type);
			$smarty->assign("compatibility",$compatibility);
			$smarty->assign("percentage",$percentage);
			$smarty->assign("flag",'1');
		}
	}
			
	
	$smarty->assign("username",$username);
	$smarty->assign("cid",$cid);
	$smarty->display("3d_trends.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsconnectError.tpl");

}

function calculate_mode($nums)
{
	$max=0;
	foreach ($nums as $key => $value)
	{
		if($value>$max)
		{	
			$max=$value;
			$max_key=$key;	
		}
	}
	return ($max_key);	
}

function standard_deviation($std) 
{
	$total;
    	while(list($key,$val) = each($std))
        {
        	$total += $val;
        }
    	reset($std);
    	$mean = $total/count($std);
        
    	while(list($key,$val) = each($std))
        { 
        	$sum += pow(($val-$mean),2);
        } 
    	$var = sqrt($sum/(count($std)-1));
	return $var; 
}

function get_all_caste($caste)
{
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
        return get_all_caste_revamp_js_db($caste,'',1);
        //REVAMP JS_DB_CASTE
}


?>
