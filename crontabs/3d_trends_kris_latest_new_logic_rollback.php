<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

// THIS FILE IS COMMITED IN HTDOCS/MIS


// 3d trends kris cron new logic, filling up table
// this scripts contains the new changes bocs in earlier scirpt the deviation was calcuated from adjusted percentages but it shoud be done from percentiles.
// cluster changes for age mtongue and caste , and all contacted variables will be stored

$time_ini = microtime_float();

$flag_using_php5=1;
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/commonFiles/incomeCommonFunctions.inc");

global $mysqlObj;
$mysqlObj=new Mysql;

$db=connect_737();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$db2=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);
$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
list($year1,$month1,$day1)=explode('-',$today);
$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";


if(1)
{
        ini_set(max_execution_time,0);
        ini_set(memory_limit,-1);
        ini_set(mysql.connect_timeout,-1);
        ini_set(default_socket_timeout,25920000); 
        ini_set(log_errors_max_len,0);
	
	$mtongue_cluster[]=array('1');//foreign
	$mtongue_cluster[]=array('5','4','6','21','22','23','24','25','29','32');//east
	$mtongue_cluster[]=array('8','9','11','12','19','20');//west
	$mtongue_cluster[]=array('7','10','13','14','15','27','28','30','33');//north
	$mtongue_cluster[]=array('2','3','16','17','18','26','31','34');//south

	$var='AGE';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='CASTE';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='CITY';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='EDUCATION';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='HEIGHT';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='INCOME';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='MANGLIK';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='MSTATUS';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
        $var='MTONGUE';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='OCCUPATION';
	$gender='MALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$arr['NRI']['MALE']=7.54;
	$arr['I']['MALE']=92.46;
	
	$var='AGE';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='CASTE';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='CITY';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='EDUCATION';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='HEIGHT';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='INCOME';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='MANGLIK';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='MSTATUS';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
        $var='MTONGUE';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$var='OCCUPATION';
	$gender='FEMALE';
	$arr[$var][$gender]=input_percent($var,$gender);
	
	$arr['NRI']['FEMALE']=3.41;
	$arr['I']['FEMALE']=96.59;

	$sadaf=0;
	for($t=0;$t<1;$t++)
	{
		$records=0;

		// for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
		// {
		// $myDbName=$slave_activeServers[$serverId];
		// $myDb=$mysqlObj->connect($myDbName);
		// $sql="(SELECT DISTINCT(SENDER) from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TIME between '$date1' and '$date2' and TYPE not in ('C','E') and PROFILEID=SENDER and SERVERID='$serverId') union (SELECT DISTINCT(RECEIVER) from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TIME between '$date1' and '$date2' AND TYPE='A' and PROFILEID=RECEIVER AND SERVERID='$serverId')";
		// $res_main=$mysqlObj->executeQuery($sql,$myDb);
		// while($row0=$mysqlObj->fetchArray($res_main))
		// {
		$chunkSize = 3;
		for ($i=0; $i < $chunkSize-1; $i++) { 
			$sql = "SELECT PROFILEID AS SENDER FROM MIS.3DTRENDS_LOG WHERE PROFILEID % $chunkSize = $i";
			$resFirst = mysql_query($sql, $db2);
			while($row0 = mysql_fetch_array($resFirst))
			{
			$my_profileid=$row0['SENDER'];
			echo $my_profileid."  ";
			$sql_details="SELECT PROFILEID,USERNAME,AGE,HEIGHT,MTONGUE,CASTE,MANGLIK,CITY_RES,COUNTRY_RES,EDU_LEVEL_NEW,OCCUPATION,INCOME,MSTATUS,GENDER FROM newjs.JPROFILE WHERE PROFILEID='$my_profileid'";	
			$res_details=mysql_query($sql_details,$db);
			$row1=mysql_fetch_array($res_details);
			
			$my_gender[$t]=$row1['GENDER'];
			
			if($row1['GENDER']=='F')
				$gender='MALE';
			else
				$gender='FEMALE';
			
			$my_username=$row1['USERNAME'];	
			$my_age=$row1['AGE'];
			$my_height=$row1['HEIGHT'];
			$my_mtongue=$row1['MTONGUE'];
			$my_caste=$row1['CASTE'];
			$my_manglik=$row1['MANGLIK'];
			$my_city=$row1['CITY_RES'];
			$my_country=$row1['COUNTRY_RES'];
			$my_education=$row1['EDU_LEVEL_NEW'];
			$my_occupation=$row1['OCCUPATION'];
			$my_income=$row1['INCOME'];
			$my_income=getTrendsSortBy($my_income);
			$my_mstatus=$row1['MSTATUS'];

			$initiated=0;
			$accepted=0;
			$declined=0;
			
			$profileid_str='';

			$sendersIn=$row1["PROFILEID"];
			$contactResult=getResultSet("RECEIVER,TYPE",$sendersIn,'','','','',"'C','E'",'','','','','','',"Y");
			if(is_array($contactResult))
			{
				foreach($contactResult as $key=>$value)
				{
					$profileid_str.="'".$contactResult[$key]['RECEIVER']."',";
						$initiated++;
					if($contactResult[$key]['TYPE']=='A')
						$accepted++;
					elseif($contactResult[$key]['TYPE']=='D')
						$declined++;

				}
				unset($contactResult);
			}
			$receiversIn=$row1["PROFILEID"];
			$typeIn="'A'";
			$contactResult=getResultSet("SENDER",'','',$receiversIn,'',$typeIn,'','','','','','','',"Y");
			if(is_array($contactResult))
                        {
                                foreach($contactResult as $key=>$value)
                                {
					$profileid_str.="'".$contactResult[$key]['SENDER']."',";

                                }
                                unset($contactResult);
                        }
			/*echo "\n Initiated ".$initiated;
			echo "\n  Accepted  ".$accepted;
			echo  "\n Declined  ".$declined;*/
			
			$profileid_str=substr($profileid_str,0,-1);
			
			if($profileid_str)
			{
				$sql4="select AGE,HEIGHT,CASTE,MTONGUE,MANGLIK,OCCUPATION,COUNTRY_RES,CITY_RES,INCOME,GENDER,MSTATUS,EDU_LEVEL_NEW,COUNTRY_BIRTH from  newjs.JPROFILE where PROFILEID IN ($profileid_str) and activatedKey=1"; 
				$res4=mysql_query($sql4,$db);
				while($row4=mysql_fetch_array($res4))
				{
					$caste[]=$row4['CASTE'];
					$mtongue[]=$row4['MTONGUE'];	
					$income[]=$row4['INCOME'];	
					$country[]=$row4['COUNTRY_RES'];	
					$city[]=$row4['CITY_RES'];	
					$education[]=$row4['EDU_LEVEL_NEW'];	
					$occupation[]=$row4['OCCUPATION'];	
					$mstatus[]=$row4['MSTATUS'];	
					$age[]=$row4['AGE'];	
					$height[]=$row4['HEIGHT'];
					$manglik[]=$row4['MANGLIK'];
				}
			}
			else
				continue;
			
			//MTONGUE
			if(is_array($mtongue) && count($mtongue)>0 )
			{
				$count=0;
				$count=count($mtongue);
				
				$mvalue=array();
				$points=array();
				$points2=array();
				$points3=array();
				
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$mtongue[$i]]++;
				}
				
				$mtongue_total_count=0;

				$sql="SELECT SQL_CACHE DISTINCT VALUE ,SMALL_LABEL FROM newjs.MTONGUE";
				$res=mysql_query($sql,$db);
				while($row=mysql_fetch_array($res))
				{
					$mtongue_total_count++;
					
					if( in_array($row['VALUE'],$mtongue)  )
					{	
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);
						
						$mtongue_field[]=array("small_label"=>$row['SMALL_LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}
				
				array_multisort($points, SORT_DESC, $mtongue_field);
				
				$mtongue_count=0;
				
				foreach ($mtongue_field as $key=>$value)
				{
					$mtongue_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['MTONGUE'][$gender][$value['value']]/100))),2);
					$mtongue_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['MTONGUE'][$gender][$value['value']]/100))),2);
					$mtongue_count++;
				}
				
				foreach ($mtongue_adjusted_percentages as $key=>$value)
				{
					$mtongue_adjusted_percentages_percentile[]=array('percentile'=>round($value/$mtongue_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$mtongue_adjusted_percentages_percentile);
				
				foreach ($mtongue_adjusted_percentages_percentile as $key=>$value)
				{
					$mtongue_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				foreach ($mtongue_adjusted_percentages_percentile as $key=>$value)
				{
					foreach ($mtongue_cluster as $key2 => $value2)
					{
						foreach ($value2 as $key3 => $value3)
						{
							if($value['value']==$value3)
							{
								$cluster_key=$key2;
								break;
							}
						}
					}
					$mtongue_adjusted_percentages_percentile_temp[$cluster_key]['percentile']+=$value['percentile'];
				}
				unset($cluster_key);

				//echo '<br>mtongue asjusted percentile temp<br>';
				//print_r($mtongue_adjusted_percentages_percentile_temp);

				foreach ($mtongue_adjusted_percentages_percentile_temp as $key=>$value)
				{
					$mtongue_adjusted_percentages_percentile_new[]=array('percentile'=>$value['percentile'],'value'=>$key);
					$points3[]=$value['percentile'];

				}
				array_multisort($points3, SORT_DESC,$mtongue_adjusted_percentages_percentile_new);
		
				$i=0;
				foreach ($mtongue_adjusted_percentages_percentile_new as $key=>$value)
				{
					$i++;
					$mtongue_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$mtongue_adjusted_percentages_percentile_mean=round($mtongue_adjusted_percentages_percentile_total_top_three/$i,2);	
				$mtongue_count_new=count($mtongue_adjusted_percentages_percentile_new);		
		
				$mtongue_max_deviation=0;
				if($mtongue_count_new==1)
				{	
					$mtongue_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($mtongue_adjusted_percentages_percentile_new as $key=>$value)
					{
						$deviation=$value['percentile']-$mtongue_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$mtongue_max_deviation)
							$mtongue_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				
				$mtongue_value_percentile_string='|';
				foreach ($mtongue_adjusted_percentages_percentile as $key=>$value)
				{
					$mtongue_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($mtongue_value_percentile_string=='|')
					$mtongue_value_percentile_string='';
			}	
			
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>mtongue field <br>';
			print_r($mtongue_field);
			
			echo '<br>mtongue adjusted percentages <br>';
			print_r($mtongue_adjusted_percentages);
			
			echo '<br>mtongue asjusted percentile<br>';
			print_r($mtongue_adjusted_percentages_percentile);

			echo '<br>mtongue asjusted percentile new<br>';
			print_r($mtongue_adjusted_percentages_percentile_new);
			
			echo '<br>mtongue adjusted percentages percentile mean top three<br>';
			echo $mtongue_adjusted_percentages_percentile_mean;
				
			echo '<br>'.$mtongue_value_percentile_string.'<br>';
			
			echo '<br>mtongue deviation<br>';
			echo $mtongue_max_deviation;*/
			

			//CASTE
			if(is_array($caste) && count($caste)>0)
			{
				$count=0;
				$count=count($caste);

				$mvalue=array();
				$points=array();
				$points2=array();
				$points3=array();
				
				$rel_caste=get_all_caste($my_caste);
				$sql_rel_caste="select SQL_CACHE REL_CASTE from newjs.CASTE_COMMUNITY where PARENT_CASTE ='$my_caste'";
				$sql_rel_caste_result=mysql_query($sql_rel_caste,$db);
				while($myrow_sql_rel_caste_result=mysql_fetch_array($sql_rel_caste_result))
				{
					$rel_caste[]=$myrow_sql_rel_caste_result["REL_CASTE"];
				}
				if(!is_array($rel_caste))
					$rel_caste=Array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$caste[$i]]++;
				}
				
				$caste_total_count=0;

				$sql="SELECT SQL_CACHE DISTINCT VALUE ,SMALL_LABEL FROM newjs.CASTE";
				$res=mysql_query($sql,$db);
				while($row=mysql_fetch_array($res))
				{
					$caste_total_count++;
					
					if( in_array($row['VALUE'],$caste)  )
					{
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);

						$caste_field[]=array("small_label"=>$row['SMALL_LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}

				//print_r($caste_field);
				array_multisort($points, SORT_DESC, $caste_field);
				//print_r($caste_field);
			
				$caste_count=0;
				foreach ($caste_field as $key=>$value)
				{
					$caste_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['CASTE'][$gender][$value['value']]/100))),2);
					$caste_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['CASTE'][$gender][$value['value']]/100))),2);
					$caste_count++;
				}
				
				foreach ($caste_adjusted_percentages as $key=>$value)
				{
					$caste_adjusted_percentages_percentile[]=array('percentile'=>round($value/$caste_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$caste_adjusted_percentages_percentile);
				
				foreach ($caste_adjusted_percentages_percentile as $key=>$value)
				{
					$caste_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				foreach ($caste_adjusted_percentages_percentile as $key=>$value)
				{
					if(in_array($value['value'],$rel_caste))
					{
						$caste_adjusted_percentages_percentile_temp[0]['percentile']+=$value['percentile'];
					}
					else
						$caste_adjusted_percentages_percentile_temp[$value['value']]['percentile']+=$value['percentile'];
				}
				
				/*echo '<br>rel caste is <br>';
				print_r($rel_caste);
				
				echo '<br>casate asjusted percentile temp<br>';
				print_r($caste_adjusted_percentages_percentile_temp);*/

				foreach ($caste_adjusted_percentages_percentile_temp as $key=>$value)
				{
					$caste_adjusted_percentages_percentile_new[]=array('percentile'=>$value['percentile'],'value'=>$key);
					$points3[]=$value['percentile'];

				}
				array_multisort($points3, SORT_DESC,$caste_adjusted_percentages_percentile_new);

					
				$i=0;
				foreach ($caste_adjusted_percentages_percentile_new as $key=>$value)
				{
					$i++;
					$caste_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$caste_adjusted_percentages_percentile_mean=round($caste_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$caste_count_new=count($caste_adjusted_percentages_percentile_new);		
				
				$caste_max_deviation=0;
				if($caste_count_new==1)
				{	
					$caste_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($caste_adjusted_percentages_percentile_new as $key=>$value)
					{
						$deviation=$value['percentile']-$caste_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$caste_max_deviation)
							$caste_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				
				$caste_value_percentile_string='|';
				foreach ($caste_adjusted_percentages_percentile as $key=>$value)
				{
					$caste_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($caste_value_percentile_string=='|')
					$caste_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>caste field <br>';
			print_r($caste_field);
			
			echo '<br>caste adjusted percentages <br>';
			print_r($caste_adjusted_percentages);
			
			echo '<br>caste asjusted percentile<br>';
			print_r($caste_adjusted_percentages_percentile);
			
			echo '<br>caste asjusted percentile new <br>';
			print_r($caste_adjusted_percentages_percentile_new);
			
			echo '<br>caste adjusted percentages percentile mean top three<br>';
			echo $caste_adjusted_percentages_percentile_mean;
				
			echo '<br>'.$caste_value_percentile_string.'<br>';
			
			echo '<br>caste deviation<br>';
			echo $caste_max_deviation;*/
			

			//OCCUPATION
			if(is_array($occupation) && count($occupation)>0 )
			{
				$count=0;
				$count=count($occupation);

				$mvalue=array();
				$points=array();
				$points2=array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$occupation[$i]]++;
				}
				
				$occupation_total_count=0;

				$sql="SELECT SQL_CACHE DISTINCT VALUE ,LABEL FROM newjs.OCCUPATION";
				$res=mysql_query($sql,$db);
				while($row=mysql_fetch_array($res))
				{
					$occupation_total_count++;
					
					if( in_array($row['VALUE'],$occupation)  )
					{
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);

						$occupation_field[]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}

				//print_r($occupation_field);
				array_multisort($points, SORT_DESC, $occupation_field);
				//print_r($occupation_field);
		
				$occupation_count=0;
				foreach ($occupation_field as $key=>$value)
				{
					$occupation_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['OCCUPATION'][$gender][$value['value']]/100))),2);
					$occupation_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['OCCUPATION'][$gender][$value['value']]/100))),2);
					$occupation_count++;
				}

				foreach ($occupation_adjusted_percentages as $key=>$value)
				{
					$occupation_adjusted_percentages_percentile[]=array('percentile'=>round($value/$occupation_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$occupation_adjusted_percentages_percentile);
				
				foreach ($occupation_adjusted_percentages_percentile as $key=>$value)
				{
					$occupation_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				$i=0;
				foreach ($occupation_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$occupation_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$occupation_adjusted_percentages_percentile_mean=round($occupation_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$occupation_max_deviation=0;
				if($occupation_count==1)
				{	
					$occupation_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($occupation_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value['percentile']-$occupation_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$occupation_max_deviation)
							$occupation_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				
				$occupation_value_percentile_string='|';
				foreach ($occupation_adjusted_percentages_percentile as $key=>$value)
				{
					$occupation_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($occupation_value_percentile_string=='|')
					$occupation_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>occupation field <br>';
			print_r($occupation_field);
			
			echo '<br>occupation adjusted percentages <br>';
			print_r($occupation_adjusted_percentages);
			
			echo '<br>occupation asjusted percentile<br>';
			print_r($occupation_adjusted_percentages_percentile);
			
			echo '<br>occupation adjusted percentages percentile mean top three<br>';
			echo $occupation_adjusted_percentages_percentile_mean;
			
			echo '<br>'.$occupation_value_percentile_string.'<br>';
			
			echo '<br>occupation deviation<br>';
			echo $occupation_max_deviation;*/


			//EDUCATION
			if(is_array($education) && count($education)>0)
			{
				$count=0;
				$count=count($education);

				$mvalue=array();
				$points=array();
				$points2=array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$education[$i]]++;
				}
				
				$education_total_count=0;

				$sql="SELECT SQL_CACHE DISTINCT VALUE ,LABEL FROM newjs.EDUCATION_LEVEL_NEW";
				$res=mysql_query($sql,$db) ;
				while($row=mysql_fetch_array($res))
				{
					$education_total_count++;
					
					if( in_array($row['VALUE'],$education)  )
					{
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);

						$education_field[]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}

				//print_r($education_field);
				array_multisort($points, SORT_DESC, $education_field);
				//print_r($education_field);

				$education_count=0;
				foreach ($education_field as $key=>$value)
				{
					$education_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['EDUCATION'][$gender][$value['value']]/100))),2);
					$education_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['EDUCATION'][$gender][$value['value']]/100))),2);
					$education_count++;
				}
		
				foreach ($education_adjusted_percentages as $key=>$value)
				{
					$education_adjusted_percentages_percentile[]=array('percentile'=>round($value/$education_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$education_adjusted_percentages_percentile);
				
				foreach ($education_adjusted_percentages_percentile as $key=>$value)
				{
					$education_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				$i=0;
				foreach ($education_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$education_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$education_adjusted_percentages_percentile_mean=round($education_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$education_max_deviation=0;
				if($education_count==1)
				{	
					$education_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($education_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value['percentile']-$education_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$education_max_deviation)
							$education_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				$education_value_percentile_string='|';
				foreach ($education_adjusted_percentages_percentile as $key=>$value)
				{
					$education_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($education_value_percentile_string=='|')
					$education_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>education field <br>';
			print_r($education_field);
			
			echo '<br>education adjusted percentages <br>';
			print_r($education_adjusted_percentages);
			
			echo '<br>education asjusted percentile<br>';
			print_r($education_adjusted_percentages_percentile);
			
			echo '<br>education adjusted percentages percentile mean top three<br>';
			echo $education_adjusted_percentages_percentile_mean;
			
			echo '<br>'.$education_value_percentile_string.'<br>';
			
			echo '<br>education deviation<br>';
			echo $education_max_deviation;*/

		
			//CITY
			if(is_array($city) && count($city)>0 )
			{
				$count=0;
				$count=count($city);

				$mvalue=array();
				$points=array();
				$points2=array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$city[$i]]++;
				}

				$sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE =51 OR COUNTRY_VALUE =128 ORDER BY SORTBY";
				$res=mysql_query($sql,$db) ;
				while($row=mysql_fetch_array($res))
				{
					if( in_array($row['VALUE'],$city)  )
					{
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);

						$city_field[]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}

				//print_r($city_field);
				array_multisort($points, SORT_DESC, $city_field);
				//print_r($city_field);
				
				$city_total_count=0;
				$city_count=0;
				$city_nri=0;	//57
				$city_india=0;	//214

				foreach ($city_field as $key=>$value)
				{
					$city_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['CITY'][$gender][$value['value']]/100))),2);
					$city_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['CITY'][$gender][$value['value']]/100))),2);
					$city_count++;
					
					if(intval($value['value']))
						$city_nri=1;
					else
						$city_india=1;
				}

				if($city_nri && $city_india)
					$city_total_count=57+214;
				elseif($city_india)
					$city_total_count=214;
				elseif($city_nri)
					$city_total_count=57;
				else	
					$city_total_count=1;

				foreach ($city_adjusted_percentages as $key=>$value)
				{
					$city_adjusted_percentages_percentile[]=array('percentile'=>round($value/$city_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$city_adjusted_percentages_percentile);
				
				foreach ($city_adjusted_percentages_percentile as $key=>$value)
				{
					$city_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				$i=0;
				foreach ($city_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$city_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$city_adjusted_percentages_percentile_mean=round($city_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$city_max_deviation=0;
				if($city_count==1)
				{	
					$city_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($city_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value['percentile']-$city_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$city_max_deviation)
							$city_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				
				$city_value_percentile_string='|';
				foreach ($city_adjusted_percentages_percentile as $key=>$value)
				{
					$city_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($city_value_percentile_string=='|')
					$city_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>city field <br>';
			print_r($city_field);
			
			echo '<br>city adjusted percentages <br>';
			print_r($city_adjusted_percentages);
			
			echo '<br>city asjusted percentile<br>';
			print_r($city_adjusted_percentages_percentile);
			
			echo '<br>city adjusted percentages percentile mean top three<br>';
			echo $city_adjusted_percentages_percentile_mean;
				
			echo '<br>'.$city_value_percentile_string.'<br>';
			
			echo '<br>city deviation<br>';
			echo $city_max_deviation;*/

		
			//COUNTRY
			if(is_array($country) && count($country)>0)
			{
				$count=0;
				$count=count($country);

				$mvalue=array();
				$points=array();
				//$points2=array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$country[$i]]++;
				}
			
				$sql="SELECT SQL_CACHE VALUE , LABEL FROM newjs.COUNTRY_NEW";
				$res=mysql_query($sql,$db) ;
				while($row=mysql_fetch_array($res))
				{       
					if( in_array($row['VALUE'],$country)  )
					{
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);
						$country_field[]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}

				
				//print_r($country_field);
				array_multisort($points, SORT_DESC, $country_field);
				//print_r($country_field);

				foreach ($country_field as $key => $value)
				{
					if($value['value']==51)
						$country_field2['I']=$value['percent'];
					else
						$country_field2['NRI']+=$value['percent'];
				}
				//print_r($country_field2);

				$country_count=0;
				foreach ($country_field2 as $key=>$value)
				{
					$country_adjusted_percentages[$key]=round(($value/sqrt(($arr[$key][$gender]/100))),2);
					$country_adjusted_percentages_total+=round(($value/sqrt(($arr[$key][$gender]/100))),2);
					$country_count++;
				}
				
				foreach ($country_adjusted_percentages as $key=>$value)
				{
					$country_adjusted_percentages_percentile[$key]=round($value/$country_adjusted_percentages_total,2);
				}
				
				foreach ($country_adjusted_percentages_percentile as $key=>$value)
				{
					$country_adjusted_percentages_percentile[$key]*=100;
				}
				
				$i=0;
				foreach ($country_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$country_adjusted_percentages_percentile_total_top_three+=$value;
					if($i==6)
						break;
				}
				
				if($i!=0)
					$country_adjusted_percentages_percentile_mean=round($country_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$country_max_deviation=0;
				if($country_count==1)
				{	
					$country_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($country_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value-$country_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$country_max_deviation)
							$country_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>country field <br>';
			print_r($country_field);
			
			echo '<br>country adjusted percentages <br>';
			print_r($country_adjusted_percentages);
			
			echo '<br>country asjusted percentile<br>';
			print_r($country_adjusted_percentages_percentile);
			
			echo '<br>country adjusted percentages percentile mean top three<br>';
			echo $country_adjusted_percentages_percentile_mean;
			
			echo '<br>country deviation<br>';
			echo $country_max_deviation;*/


			//MSTATUS
			if(is_array($mstatus) && $my_mstatus!='N')
			{
				$count=0;
				$count=count($mstatus);
				
				$mvalue=array();
				$points=array();
				
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$mstatus[$i]]++;
				}
				
				$MSTATUS=array("N" => "Never Married","W" => "Widowed","D" => "Divorced","S" => "Awaiting Divorce","O" => "Other","A"=>"Annulled","M" => "Married");	
				foreach ($MSTATUS as $key => $value)
				{
					if( is_array($mstatus) && in_array($key,$mstatus))
					{
						$percent=substr(($mvalue[$key]*100)/$count,0,5);
						$mstatus_field[]=array("small_label"=>$value,"cnt"=>$mvalue[$key],"percent"=>$percent,"value"=>$key);
						$points[]=$mvalue[$key];
					}
				}
				//print_r($mstatus_field);
				array_multisort($points, SORT_DESC, $mstatus_field);
				//print_r($mstatus_field);
			
				foreach ($mstatus_field as $key => $value)
				{
					if($value['value']=='N')
						$mstatus_field2['N']=$value['percent'];
					else
						$mstatus_field2['M']+=$value['percent'];
				}
				//print_r($mstatus_field2);
			
// there is already an entry for M for married marital status for muslim so add to that.	
				$arr['MSTATUS'][$gender]['M']+=$arr['MSTATUS'][$gender]['W'] + $arr['MSTATUS'][$gender]['D'] + $arr['MSTATUS'][$gender]['S'] + $arr['MSTATUS'][$gender]['O'] + $arr['MSTATUS'][$gender]['A'];

				$mstatus_count=0;
				foreach ($mstatus_field2 as $key=>$value)
				{
					$mstatus_adjusted_percentages[$key]=round(($value/sqrt(($arr['MSTATUS'][$gender][$key]/100))),2);
					$mstatus_adjusted_percentages_total+=round(($value/sqrt(($arr['MSTATUS'][$gender][$key]/100))),2);
					$mstatus_count++;
				}

				
				foreach ($mstatus_adjusted_percentages as $key=>$value)
				{
					$mstatus_adjusted_percentages_percentile[$key]=round($value/$mstatus_adjusted_percentages_total,2);
				}
				
				foreach ($mstatus_adjusted_percentages_percentile as $key=>$value)
				{
					$mstatus_adjusted_percentages_percentile[$key]*=100;
				}
				
				$i=0;
				foreach ($mstatus_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$mstatus_adjusted_percentages_percentile_total_top_three+=$value;
					if($i==6)
						break;
				}
				
				if($i!=0)
					$mstatus_adjusted_percentages_percentile_mean=round($mstatus_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$mstatus_max_deviation=0;
				if($mstatus_count==1)
				{	
					$mstatus_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($mstatus_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value-$mstatus_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$mstatus_max_deviation)
							$mstatus_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
			}
		


			/*echo '<br>';	
			echo '<br>';	

			echo '<br>mstatus field <br>';
			print_r($mstatus_field);
			
			echo '<br>mstatus adjusted percentages <br>';
			print_r($mstatus_adjusted_percentages);
			
			echo '<br>mstatus asjusted percentile<br>';
			print_r($mstatus_adjusted_percentages_percentile);
			
			echo '<br>mstatus adjusted percentages percentile mean top three<br>';
			echo $mstatus_adjusted_percentages_percentile_mean;
			
			echo '<br>mstatus deviation<br>';
			echo $mstatus_max_deviation;*/
			

			//MANGLIK
			if(is_array($manglik) && count($manglik)>0 )
			{
				$count=0;
				
				$count=count($manglik);
				
				$mvalue=array();
				$points=array();
			
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$manglik[$i]]++;
				}
			
				$MANGLIK=array("M" => "Manglik","N" => "Non Manglik","D" => "Don't know/Not Applicable",""=>"Not filled");

				foreach ($MANGLIK as $key => $value)
				{
					if( is_array($manglik) && in_array($key,$manglik))
					{
						$percent=substr(($mvalue[$key]*100)/$count,0,5);
						$manglik_field[]=array("small_label"=>$value,"cnt"=>$mvalue[$key],"percent"=>$percent,"value"=>$key);
						$points[]=$mvalue[$key];
					}
				}
				
				//print_r($manglik_field);
				array_multisort($points, SORT_DESC, $manglik_field);
				//print_r($manglik_field);
				
				foreach ($manglik_field as $key => $value)
				{
					if($value['value']=='M')
						$manglik_field2['M']=$value['percent'];
					else	
						$manglik_field2['N']+=$value['percent'];
				}
				//print_r($manglik_field2);
			       
				$arr['MANGLIK'][$gender]['N']=$arr['MANGLIK'][$gender]['N'] + $arr['MANGLIK'][$gender]['D'] + $arr['MANGLIK'][$gender][''];
	 
				$manglik_count=0;
				foreach ($manglik_field2 as $key=>$value)
				{
					$manglik_adjusted_percentages[$key]=round(($value/sqrt(($arr['MANGLIK'][$gender][$key]/100))),2);
					$manglik_adjusted_percentages_total+=round(($value/sqrt(($arr['MANGLIK'][$gender][$key]/100))),2);
					$manglik_count++;
				}
				
				foreach ($manglik_adjusted_percentages as $key=>$value)
				{
					$manglik_adjusted_percentages_percentile[$key]=round($value/$manglik_adjusted_percentages_total,2);
				}
				
				foreach ($manglik_adjusted_percentages_percentile as $key=>$value)
				{
					$manglik_adjusted_percentages_percentile[$key]*=100;
				}
				
				$i=0;
				foreach ($manglik_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$manglik_adjusted_percentages_percentile_total_top_three+=$value;
					if($i==6)
						break;
				}
				
				if($i!=0)
					$manglik_adjusted_percentages_percentile_mean=round($manglik_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$manglik_max_deviation=0;
				if($manglik_count==1)
				{	
					$manglik_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($manglik_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value-$manglik_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$manglik_max_deviation)
							$manglik_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>manglik field <br>';
			print_r($manglik_field);
			
			echo '<br>manglik adjusted percentages <br>';
			print_r($manglik_adjusted_percentages);
			
			echo '<br>manglik asjusted percentile<br>';
			print_r($manglik_adjusted_percentages_percentile);
			
			echo '<br>manglik adjusted percentages percentile mean top three<br>';
			echo $manglik_adjusted_percentages_percentile_mean;
			
			echo '<br>manglik deviation<br>';
			echo $manglik_max_deviation;*/
			

			//AGE
			if(is_array($age) && count($age)>0 )
			{
				$count=0;
				$count=count($age);

				$mvalue=array();
				$points=array();
				$points2=array();
				$points3=array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$age[$i]]++;
				}
					
				$age_total_count=0;
				
				$flag_small=0;
				for($key=18;$key<=70;$key++)
				{
					$age_total_count++;

					if( in_array($key,$age)  )
					{       
						if($flag_small==0)
						{
							$age_low=$key;
							$flag_small=1;
						}
						$age_high=$key;

						$percent=substr( ($mvalue[$key]/$count)*100,0,5);
						$age_field[]=array("small_label"=>$key,"cnt"=>$mvalue[$key],"percent"=>$percent,"value"=>$key);
						$points[]=$mvalue[$key];
					}
				}
				

				$age_count_new=0;
				$arr_age_grp=array();		
				$my_actual_age=$my_age;		
		
				//echo 'my age is '.$my_age.' ';	
				// 26 -31
		
				if($my_gender[$t]=='M')
				{
					while($my_age>=$age_low)
					{
						foreach ($age_field as $key=>$value)
						{	
							if($value['value']>$my_actual_age)
								//$age_count_new=1;
								$arr_age_grp[$my_actual_age+1][$value['value']]=1;
							if($value['value']<=$my_age && $value['value']>=($my_age-3))
								$arr_age_grp[$my_age][$value['value']]=1;
						}
						$my_age=$my_age-4;
					}	
				}
				else
				{
					while($my_age<=$age_high)
					{
						foreach ($age_field as $key=>$value)
						{	
							if($value['value']<$my_actual_age)
								//$age_count_new=1;
								$arr_age_grp[$my_actual_age-1][$value['value']]=1;
							if($value['value']>=$my_age && $value['value']<=($my_age+3))
								$arr_age_grp[$my_age][$value['value']]=1;
						}
						$my_age=$my_age+4;
					}	
				}
				
				//print_r($arr_age_grp);			
				$age_count_new+=count($arr_age_grp);

				//echo 'age count new is '.$age_count_new;
				//print_r($age_field);
				array_multisort($points, SORT_DESC, $age_field);
				//print_r($age_field);

				$age_count=0;
				foreach ($age_field as $key=>$value)
				{
					$age_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['AGE'][$gender][$value['value']]/100))),2);
					$age_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['AGE'][$gender][$value['value']]/100))),2);
					$age_count++;
				}
		      
				foreach ($age_adjusted_percentages as $key=>$value)
				{
					$age_adjusted_percentages_percentile[]=array('percentile'=>round($value/$age_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$age_adjusted_percentages_percentile);
				
				foreach ($age_adjusted_percentages_percentile as $key=>$value)
				{
					$age_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				foreach ($age_adjusted_percentages_percentile as $key=>$value)
				{
					foreach ($arr_age_grp as $key2 => $value2)
					{	
						foreach ($value2 as $key3 => $value3)
						{
							if($value['value']==$key3)
							{	
								$cluster_key=$key2;
								break;
							}
						}
					}
					$age_adjusted_percentages_percentile_temp[$cluster_key]['percentile']+=$value['percentile'];
				}
				unset($cluster_key);
				
				foreach ($age_adjusted_percentages_percentile_temp as $key=>$value)
				{
					$age_adjusted_percentages_percentile_new[]=array('percentile'=>$value['percentile'],'value'=>$key);	
					$points3[]=$value['percentile'];
					
				}
				array_multisort($points3, SORT_DESC,$age_adjusted_percentages_percentile_new);

				$i=0;
				foreach ($age_adjusted_percentages_percentile_new as $key=>$value)
				{
					$i++;
					$age_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$age_adjusted_percentages_percentile_mean=round($age_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$age_max_deviation=0;
				if($age_count_new==1)
				{	
					$age_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($age_adjusted_percentages_percentile_new as $key=>$value)
					{
						$deviation=$value['percentile']-$age_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$age_max_deviation)
							$age_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				$age_value_percentile_string='|';
				foreach ($age_adjusted_percentages_percentile as $key=>$value)
				{
					$age_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($age_value_percentile_string=='|')
					$age_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>age field <br>';
			print_r($age_field);
			
			echo '<br>age adjusted percentages <br>';
			print_r($age_adjusted_percentages);
			
			echo '<br>age asjusted percentile<br>';
			print_r($age_adjusted_percentages_percentile);
			
			echo '<br>age adjusted percentages percentile mean top three<br>';
			echo $age_adjusted_percentages_percentile_mean;
				
			echo '<br>'.$age_value_percentile_string.'<br>';
			
			echo '<br>age deviation<br>';
			echo $age_max_deviation;

			echo '<br>age adjusted percentile clusters<br>';
			print_r($age_adjusted_percentages_percentile_new);*/
			

			//HEIGHT
			if(is_array($height) && count($height)>0 )
			{
				$count=0;
				$count=count($height);

				$mvalue=array();
				$points=array();
				$points2=array();

				for($i=0;$i<$count;$i++)
				{
					$mvalue[$height[$i]]++;
				}
				
				$height_total_count=0;

				$sql="SELECT SQL_CACHE DISTINCT VALUE ,LABEL FROM newjs.HEIGHT";
				$res=mysql_query($sql,$db);
				while($row=mysql_fetch_array($res))
				{
					$height_total_count++;
					
					if( in_array($row['VALUE'],$height)  )
					{
						$percent=substr( ($mvalue[$row['VALUE']]/$count)*100,0,5);
						$height_field[]=array("small_label"=>$row['LABEL'],"cnt"=>$mvalue[$row['VALUE']],"percent"=>$percent,"value"=>$row['VALUE']);
						$points[]=$mvalue[$row['VALUE']];
					}
				}
				//print_r($height_field);
				array_multisort($points, SORT_DESC, $height_field);
				//print_r($height_field);
				
				$height_count=0;
				foreach ($height_field as $key=>$value)
				{
					$height_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['HEIGHT'][$gender][$value['value']]/100))),2);
					$height_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['HEIGHT'][$gender][$value['value']]/100))),2);
					$height_count++;
				}
			
				foreach ($height_adjusted_percentages as $key=>$value)
				{
					$height_adjusted_percentages_percentile[]=array('percentile'=>round($value/$height_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$height_adjusted_percentages_percentile);
				
				foreach ($height_adjusted_percentages_percentile as $key=>$value)
				{
					$height_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				$i=0;
				foreach ($height_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$height_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$height_adjusted_percentages_percentile_mean=round($height_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$height_max_deviation=0;
				if($height_count==1)
				{	
					$height_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($height_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value['percentile']-$height_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$height_max_deviation)
							$height_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				
				$height_value_percentile_string='|';
				foreach ($height_adjusted_percentages_percentile as $key=>$value)
				{
					$height_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($height_value_percentile_string=='|')
					$height_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>height field <br>';
			print_r($height_field);
			
			echo '<br>height adjusted percentages <br>';
			print_r($height_adjusted_percentages);
			
			echo '<br>height asjusted percentile<br>';
			print_r($height_adjusted_percentages_percentile);
			
			echo '<br>height adjusted percentages percentile mean top three<br>';
			echo $height_adjusted_percentages_percentile_mean;
				
			echo '<br>'.$height_value_percentile_string.'<br>';
			
			echo '<br>height deviation<br>';
			echo $height_max_deviation;*/

			//SALARY
			if( is_array($income) && count($income)>0 )
			{
				$count=0;

				$count=count($income);

				for($i=0;$i<$count;$i++)
				{
					$income[$i]=getTrendsSortBy($income[$i]);
				}	
				
				$mvalue=array();
				$points=array();
				$points2=array();
				
				for($i=0;$i<$count;$i++)
				{
					$mvalue[$income[$i]]++;
				}
				
		
				$sql="SELECT SQL_CACHE count(DISTINCT VALUE) as CNT from newjs.INCOME where VISIBLE='Y'";
				$res=mysql_query($sql,$db);
				$row=mysql_fetch_array($res);
				$income_total_count=$row['CNT'];
				
				foreach($mvalue as $key=>$value)
				{
					if($value>0)
					{
						$percent=substr(($value*100)/$count,0,5);
						$income_field[]=array("small_label"=>getTrendsSortByRupeeLabel($key),"cnt"=>$value,"percent"=>$percent,"value"=>$key);
						$points[]=$value;
					}
				}

				//print_r($income_field);
				array_multisort($points, SORT_DESC, $income_field);
				//print_r($income_field);

				$income_count=0;
				foreach ($income_field as $key=>$value)
				{
					$income_adjusted_percentages[$value['value']]=round(($value['percent']/sqrt(($arr['INCOME'][$gender][$value['value']]/100))),2);
					$income_adjusted_percentages_total+=round(($value['percent']/sqrt(($arr['INCOME'][$gender][$value['value']]/100))),2);
					$income_count++;
				}
		    
				foreach ($income_adjusted_percentages as $key=>$value)
				{
					$income_adjusted_percentages_percentile[]=array('percentile'=>round($value/$income_adjusted_percentages_total,2),'value'=>$key);	
					
					$points2[]=$value;
				}
				array_multisort($points2, SORT_DESC,$income_adjusted_percentages_percentile);
				
				foreach ($income_adjusted_percentages_percentile as $key=>$value)
				{
					$income_adjusted_percentages_percentile[$key]['percentile']*=100;
				}
				
				$i=0;
				foreach ($income_adjusted_percentages_percentile as $key=>$value)
				{
					$i++;
					$income_adjusted_percentages_percentile_total_top_three+=$value['percentile'];
					if($i==6)
						break;
				}
				
				if($i!=0)
					$income_adjusted_percentages_percentile_mean=round($income_adjusted_percentages_percentile_total_top_three/$i,2);	
				
				$income_max_deviation=0;
				if($income_count==1)
				{	
					$income_max_deviation=100;
				}
				else
				{	
					$i=0;
					foreach ($income_adjusted_percentages_percentile as $key=>$value)
					{
						$deviation=$value['percentile']-$income_adjusted_percentages_percentile_mean;
						if($deviation<0)
							$deviation=$deviation*(-1);
						if($deviation>$income_max_deviation)
							$income_max_deviation=$deviation;
						$i++;
						if($i==6)
							break;
					}
				}
				
				$income_value_percentile_string='|';
				foreach ($income_adjusted_percentages_percentile as $key=>$value)
				{
					$income_value_percentile_string.=$value['value'].'#'.$value['percentile']."|";
				}
				if($income_value_percentile_string=='|')
					$income_value_percentile_string='';
			}
		
			/*echo '<br>';	
			echo '<br>';	

			echo '<br>income field <br>';
			print_r($income_field);
			
			echo '<br>income adjusted percentages <br>';
			print_r($income_adjusted_percentages);
			
			echo '<br>income asjusted percentile<br>';
			print_r($income_adjusted_percentages_percentile);
			
			echo '<br>income adjusted percentages percentile mean top three<br>';
			echo $income_adjusted_percentages_percentile_mean;
				
			echo '<br>'.$income_value_percentile_string.'<br>';
			
			echo '<br>income deviation<br>';
			echo $income_max_deviation;*/

			
			if($mtongue_count_new)
				$mtongue_max_deviation=round(sqrt(sqrt($mtongue_total_count/$mtongue_count_new))*($mtongue_max_deviation),2);
			if($caste_count_new)
				$caste_max_deviation=round(sqrt(sqrt($caste_total_count/$caste_count_new))*($caste_max_deviation),2);
			if($age_count_new)
				$age_max_deviation=round(sqrt(sqrt($age_total_count/$age_count_new))*($age_max_deviation),2);
			if($income_count)
				$income_max_deviation=round(sqrt(sqrt($income_total_count/$income_count))*($income_max_deviation),2);
			if($height_count)
				$height_max_deviation=round(sqrt(sqrt($height_total_count/$height_count))*($height_max_deviation),2);
			if($mstatus_count)
				$mstatus_max_deviation=round(sqrt(sqrt(2/$mstatus_count))*($mstatus_max_deviation),2);
			if($country_count)
				$country_max_deviation=round(sqrt(sqrt(2/$country_count))*($country_max_deviation),2);
			if($manglik_count)
				$manglik_max_deviation=round(sqrt(sqrt(2/$manglik_count))*($manglik_max_deviation),2);
			if($education_count)
				$education_max_deviation=round(sqrt(sqrt($education_total_count/$education_count))*($education_max_deviation),2);
			if($occupation_count)
				$occupation_max_deviation=round(sqrt(sqrt($occupation_total_count/$occupation_count))*($occupation_max_deviation),2);
			if($city_count)
				$city_max_deviation=round(sqrt(sqrt($city_total_count/$city_count))*($city_max_deviation),2);

			$total_deviation=$mtongue_max_deviation + $caste_max_deviation + $age_max_deviation + $income_max_deviation + $height_max_deviation + $mstatus_max_deviation + $country_max_deviation + $manglik_max_deviation + $education_max_deviation + $occupation_max_deviation + $city_max_deviation;

			if($total_deviation)
			{
				$weight_mtongue=round(($mtongue_max_deviation/$total_deviation),2);
				$weight_caste=round(($caste_max_deviation/$total_deviation),2);
				$weight_age=round(($age_max_deviation/$total_deviation),2);
				$weight_income=round(($income_max_deviation/$total_deviation),2);
				$weight_height=round(($height_max_deviation/$total_deviation),2);
				$weight_mstatus=round(($mstatus_max_deviation/$total_deviation),2);
				$weight_country=round(($country_max_deviation/$total_deviation),2);
				$weight_manglik=round(($manglik_max_deviation/$total_deviation),2);
				$weight_education=round(($education_max_deviation/$total_deviation),2);
				$weight_occupation=round(($occupation_max_deviation/$total_deviation),2);
				$weight_city=round(($city_max_deviation/$total_deviation),2);
			}
		
			unset($max_score);	
			unset($max_age_score);	
			$weight_ordering=arrangeInDescOrderSortByWeight($weight_mtongue,$weight_caste,$weight_age,$weight_income,$weight_height,$weight_mstatus,$weight_country,$weight_manglik,$weight_education,$weight_occupation,$weight_city);
			
			$max_score = round( $weight_caste * $caste_adjusted_percentages_percentile[0]['percentile'] + $weight_mtongue * $mtongue_adjusted_percentages_percentile[0]['percentile']  + $weight_age * $age_adjusted_percentages_percentile[0]['percentile'] + $weight_income * $income_adjusted_percentages_percentile[0]['percentile'] + $weight_height * $height_adjusted_percentages_percentile[0]['percentile'] + $weight_education * $education_adjusted_percentages_percentile[0]['percentile'] + $weight_occupation * $occupation_adjusted_percentages_percentile[0]['percentile'] + $weight_city * $city_adjusted_percentages_percentile[0]['percentile'],2) ;

                        if($country_adjusted_percentages_percentile['I'] >= $country_adjusted_percentages_percentile['NRI'])
                                $max_score +=round( $weight_country * $country_adjusted_percentages_percentile['I'],2) ;
                        else
                                $max_score +=round( $weight_country * $country_adjusted_percentages_percentile['NRI'],2) ;

                        if($manglik_adjusted_percentages_percentile['M'] >= $manglik_adjusted_percentages_percentile['N'])
                                $max_score +=round( $weight_manglik * $manglik_adjusted_percentages_percentile['M'],2) ;
                        else
                                $max_score +=round( $weight_manglik * $manglik_adjusted_percentages_percentile['N'],2) ;

                        if($mstatus_adjusted_percentages_percentile['M'] >= $mstatus_adjusted_percentages_percentile['N'])
                                $max_score +=round( $weight_mstatus * $mstatus_adjusted_percentages_percentile['M'],2) ;
                        else
                                $max_score +=round( $weight_mstatus * $mstatus_adjusted_percentages_percentile['N'],2) ;

			$max_age_score = round($weight_age*$age_adjusted_percentages_percentile[0]['percentile'],2) ;	
		
			/*echo '<br> total deviation is '.$total_deviation;
			echo '<br> $mtongue_total_count $mtongue_count $mtongue_max_deviation $weight_mtongue is '.$mtongue_total_count.' '.$mtongue_count.' '.$mtongue_max_deviation.' '.$weight_mtongue;
			echo '<br> $caste_total_count $caste_count $caste_max_deviation $weight_caste is '.$caste_total_count.' '.$caste_count.' '.$caste_max_deviation.' '.$weight_caste;
			echo '<br> $age_total_count $age_count $age_max_deviation $weight_age is '.$age_total_count.' '.$age_count.' '.$age_max_deviation.' '.$weight_age;
			echo '<br> $income_total_count $income_count $income_max_deviation $weight_income is '.$income_total_count.' '.$income_count.' '.$income_max_deviation.' '.$weight_income;
			echo '<br> $height_total_count $height_count $height_max_deviation $weight_height is '.$height_total_count.' '.$height_count.' '.$height_max_deviation.' '.$weight_height;
			echo '<br> $mstatus_total_count $mstatus_count $mstatus_max_deviation $weight_mstatus is 2 '.$mstatus_count.' '.$mstatus_max_deviation.' '.$weight_mstatus;
			echo '<br> $country_total_count $country_count $country_max_deviation $weight_country is 2 '.$country_count.' '.$country_max_deviation.' '.$weight_country;
			echo '<br> $manglik_total_count $manglik_count $manglik_max_deviation $weight_manglik is 2 '.$manglik_count.' '.$manglik_max_deviation.' '.$weight_manglik;
			echo '<br> $education_total_count $education_count $education_max_deviation $weight_education is '.$education_total_count.' '.$education_count.' '.$education_max_deviation.' '.$weight_education;
			echo '<br> $occupation_total_count $occupation_count $occupation_max_deviation $weight_occupation is '.$occupation_total_count.' '.$occupation_count.' '.$occupation_max_deviation.' '.$weight_occupation;
			echo '<br> $city_total_count $city_count $city_max_deviation $weight_city is '.$city_total_count.' '.$city_count.' '.$city_max_deviation.' '.$weight_city;*/
		

		 
				$insert_query="UPDATE twowaymatch.TRENDS SET USERNAME='".stripslashes($my_username)."',GENDER='$my_gender[$t]',INITIATED='$initiated',ACCEPTED='$accepted',DECLINED='$declined',W_CASTE='".$weight_caste."',CASTE_VALUE_PERCENTILE='".$caste_value_percentile_string."',W_MTONGUE='".$weight_mtongue."',MTONGUE_VALUE_PERCENTILE='".$mtongue_value_percentile_string."',W_AGE='".$weight_age."',AGE_VALUE_PERCENTILE='".$age_value_percentile_string."',W_INCOME='".$weight_income."',INCOME_VALUE_PERCENTILE='".$income_value_percentile_string."', W_HEIGHT='".$weight_height."',HEIGHT_VALUE_PERCENTILE='".$height_value_percentile_string."',W_EDUCATION='".$weight_education."',EDUCATION_VALUE_PERCENTILE='".$education_value_percentile_string."',W_OCCUPATION='".$weight_occupation."',OCCUPATION_VALUE_PERCENTILE='".$occupation_value_percentile_string."', W_CITY='".$weight_city."',CITY_VALUE_PERCENTILE='".$city_value_percentile_string."',W_MSTATUS='".$weight_mstatus."',MSTATUS_N_P='".$mstatus_adjusted_percentages_percentile['N']."',MSTATUS_M_P='".$mstatus_adjusted_percentages_percentile['M']."',W_MANGLIK='".$weight_manglik."',MANGLIK_M_P='".$manglik_adjusted_percentages_percentile['M']."',MANGLIK_N_P='".$manglik_adjusted_percentages_percentile['N']."', W_NRI='".$weight_country."',NRI_M_P='".$country_adjusted_percentages_percentile['NRI']."',NRI_N_P='".$country_adjusted_percentages_percentile['I']."',MAX_SCORE='".$max_score."',MAX_AGE_SCORE='".$max_age_score."',ENTRY_DT='".$today."',WEIGHT_ORDERING='".$weight_ordering."' WHERE PROFILEID='$my_profileid'";
				mysql_query($insert_query,$db2);
				if(mysql_affected_rows($db2)==0)
				{
					$insert_query=" INSERT IGNORE INTO twowaymatch.TRENDS (PROFILEID, USERNAME, GENDER, INITIATED, ACCEPTED, DECLINED, W_CASTE, CASTE_VALUE_PERCENTILE  , W_MTONGUE, MTONGUE_VALUE_PERCENTILE , W_AGE, AGE_VALUE_PERCENTILE , W_INCOME, INCOME_VALUE_PERCENTILE , W_HEIGHT, HEIGHT_VALUE_PERCENTILE , W_EDUCATION, EDUCATION_VALUE_PERCENTILE , W_OCCUPATION, OCCUPATION_VALUE_PERCENTILE , W_CITY , CITY_VALUE_PERCENTILE , W_MSTATUS, MSTATUS_N_P, MSTATUS_M_P, W_MANGLIK, MANGLIK_M_P, MANGLIK_N_P, W_NRI, NRI_M_P, NRI_N_P , MAX_SCORE , MAX_AGE_SCORE ,ENTRY_DT,WEIGHT_ORDERING) values ('$my_profileid' , '".stripslashes($my_username)."' , '$my_gender[$t]' , '$initiated','$accepted', '$declined', '".$weight_caste."' , '".$caste_value_percentile_string."' , '".$weight_mtongue."', '".$mtongue_value_percentile_string."' , '".$weight_age."' , '".$age_value_percentile_string."' , '".$weight_income."' , '".$income_value_percentile_string."' , '".$weight_height."' , '".$height_value_percentile_string."' , '".$weight_education."' , '".$education_value_percentile_string."' , '".$weight_occupation."' , '".$occupation_value_percentile_string."' , '".$weight_city."' , '".$city_value_percentile_string."' , '".$weight_mstatus."' , '".$mstatus_adjusted_percentages_percentile['N']."' , '".$mstatus_adjusted_percentages_percentile['M']."' , '".$weight_manglik."' , '".$manglik_adjusted_percentages_percentile['M']."' , '".$manglik_adjusted_percentages_percentile['N']."' , '".$weight_country."' , '".$country_adjusted_percentages_percentile['NRI']."' , '".$country_adjusted_percentages_percentile['I']."' , '".$max_score."' , '".$max_age_score."','$today','".$weight_ordering."' ) ";
        	                       mysql_query($insert_query,$db2);
				}
			$records++;

			if($sadaf<=5)
			{
				if(!$sadafFull)
				{
					for($sadafC=0;$sadafC<10;$sadafC++)
					{
						if(!$sadafList[$sadafC])
						{
							$sadafList[$sadafC]=$my_profileid;
							break;
						}
					}
				}
				if($sadafC==9 || $sadafFull)
				{
					$sadafFull=1;
					for($sadafC=0;$sadafC<10;$sadafC++)
					{
						if($sadafC<=8)
							$sadafList[$sadafC]=$sadafList[$sadafC+1];
						else
							$sadafList[$sadafC]=$my_profileid;
					}

				}
				$totalCon=$initiated+$accepted;
				if($my_gender[$t]=='F' && $totalCon>10 && $weight_income==0 && $income_value_percentile_string=='')
				{
					if(is_array($sadafList))
						$sadafString=implode(",",$sadafList);
					mail("sadaf.alam@jeevansathi.com","Trends profile with zero income weight".date('Y-m-d')," $my_profileid   :   $sadafString");
					$sadaf++;
				}
			}
			
			unset($mtongue_field);
			unset($caste_field);
			unset($occupation_field);
			unset($education_field);
			unset($city_field);
			unset($country_field);
			unset($mstatus_field);
			unset($manglik_field);
			unset($age_field);
			unset($income_field);
			unset($height_field);
				
			unset($caste);
			unset($mtongue);
			unset($income);
			unset($country);
			unset($countrybirth);
			unset($city);
			unset($education);
			unset($occupation);
			unset($mstatus);
			unset($age);
			unset($height);
			unset($manglik);
			
			
			unset($my_profileid);
			unset($my_username);
			unset($my_age);
			unset($my_height);
			unset($my_mtongue);
			unset($my_caste);
			unset($my_manglik);
			unset($my_city);
			unset($my_country);
			unset($my_education);
			unset($my_occupation);
			unset($my_income);
			unset($my_mstatus);

			$initiated=0;
			$accepted=0;
			$declined=0;
			$total_deviation=0;	

			unset($weight_mtongue);
			unset($mtongue_count);
			unset($mtongue_field2);
			unset($mtongue_adjusted_percentages);
			unset($mtongue_adjusted_percentages_total);
			unset($mtongue_adjusted_percentages_mean);
			unset($mtongue_max_deviation);
			unset($mtongue_adjusted_percentages_percentile);
			unset($mtongue_adjusted_percentages_percentile_total_top_three);
			unset($mtongue_adjusted_percentages_percentile_mean);
			unset($mtongue_adjusted_percentages_percentile_new);
			unset($mtongue_adjusted_percentages_percentile_temp);
				
		
			unset($weight_caste);
			unset($caste_count);
			unset($caste_field2);
			unset($caste_adjusted_percentages);
			unset($caste_adjusted_percentages_total);
			unset($caste_adjusted_percentages_mean);
			unset($caste_max_deviation);
			unset($caste_adjusted_percentages_percentile);
			unset($caste_adjusted_percentages_percentile_total_top_three);
			unset($caste_adjusted_percentages_percentile_mean);
			unset($caste_adjusted_percentages_percentile_temp);
			unset($caste_adjusted_percentages_percentile_new);
			
			unset($weight_age);
			unset($age_count);
			unset($age_field2);
			unset($age_adjusted_percentages);
			unset($age_adjusted_percentages_total);
			unset($age_adjusted_percentages_mean);
			unset($age_max_deviation);
			unset($age_adjusted_percentages_percentile);
			unset($age_adjusted_percentages_percentile_total_top_three);
			unset($age_adjusted_percentages_percentile_mean);
			unset($age_adjusted_percentages_percentile_temp);
			unset($age_adjusted_percentages_percentile_new);

			unset($weight_income);
			unset($income_count);
			unset($income_field2);
			unset($income_adjusted_percentages);
			unset($income_adjusted_percentages_total);
			unset($income_adjusted_percentages_mean);
			unset($income_max_deviation);
			unset($income_adjusted_percentages_percentile);
			unset($income_adjusted_percentages_percentile_total_top_three);
			unset($income_adjusted_percentages_percentile_mean);

			unset($weight_height);
			unset($height_count);
			unset($height_field2);
			unset($height_adjusted_percentages);
			unset($height_adjusted_percentages_total);
			unset($height_adjusted_percentages_mean);
			unset($height_max_deviation);
			unset($height_adjusted_percentages_percentile);
			unset($height_adjusted_percentages_percentile_total_top_three);
			unset($height_adjusted_percentages_percentile_mean);
			
			unset($weight_mstatus);
			unset($mstatus_count);
			unset($mstatus_field2);
			unset($mstatus_adjusted_percentages);
			unset($mstatus_adjusted_percentages_total);
			unset($mstatus_adjusted_percentages_mean);
			unset($mstatus_max_deviation);
			unset($mstatus_adjusted_percentages_percentile);
			unset($mstatus_adjusted_percentages_percentile_total_top_three);
			unset($mstatus_adjusted_percentages_percentile_mean);
			
			unset($weight_country);
			unset($country_count);
			unset($country_field2);
			unset($country_adjusted_percentages);
			unset($country_adjusted_percentages_total);
			unset($country_adjusted_percentages_mean);
			unset($country_max_deviation);
			unset($country_adjusted_percentages_percentile);
			unset($country_adjusted_percentages_percentile_total_top_three);
			unset($country_adjusted_percentages_percentile_mean);
			
			unset($weight_manglik);
			unset($manglik_count);
			unset($manglik_field2);
			unset($manglik_adjusted_percentages);
			unset($manglik_adjusted_percentages_total);
			unset($manglik_adjusted_percentages_mean);
			unset($manglik_max_deviation);
			unset($manglik_adjusted_percentages_percentile);
			unset($manglik_adjusted_percentages_percentile_total_top_three);
			unset($manglik_adjusted_percentages_percentile_mean);
			
			unset($weight_education);
			unset($education_count);
			unset($education_field2);
			unset($education_adjusted_percentages);
			unset($education_adjusted_percentages_total);
			unset($education_adjusted_percentages_mean);
			unset($education_max_deviation);
			unset($education_adjusted_percentages_percentile);
			unset($education_adjusted_percentages_percentile_total_top_three);
			unset($education_adjusted_percentages_percentile_mean);
			
			unset($weight_occupation);
			unset($occupation_count);
			unset($occupation_field2);
			unset($occupation_adjusted_percentages);
			unset($occupation_adjusted_percentages_total);
			unset($occupation_adjusted_percentages_mean);
			unset($occupation_max_deviation);
			unset($occupation_adjusted_percentages_percentile);
			unset($occupation_adjusted_percentages_percentile_total_top_three);
			unset($occupation_adjusted_percentages_percentile_mean);
			
			unset($weight_city);
			unset($city_count);
			unset($city_field2);
			unset($city_adjusted_percentages);
			unset($city_adjusted_percentages_total);
			unset($city_adjusted_percentages_mean);
			unset($city_max_deviation);
			unset($city_adjusted_percentages_percentile);
			unset($city_adjusted_percentages_percentile_total_top_three);
			unset($city_adjusted_percentages_percentile_mean);
				
		}
		// unset($myDb);
		// unset($myDbName);
		}
	}
        
	$time_end = microtime_float();
        $time = $time_end - $time_ini;
        $time = $time/3600;

        mail("vikas.jayna@jeevansathi.com","Trends table filled up in $time hours ".date('Y-m-d'),date('Y-m-d')." records inserted ".$records);

        echo 'Hours taken '.$time;
        echo " script completed";
}


function get_all_caste($caste)
{
	global $db,$docRoot;
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
	return get_all_caste_revamp_js_db($caste,$db,1);
	//REVAMP JS_DB_CASTE
}

function input_percent($var,$gender)
{
	global $db;
	$sql="SELECT * from twowaymatch.".$var."_".$gender."_PERCENT";	
	$res=mysql_query($sql,$db);
	while($row=mysql_fetch_array($res))
		$arr[$row[$var]]=$row['PERCENT'];	
	return $arr;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function arrangeInDescOrderSortByWeight($weight_mtongue,$weight_caste,$weight_age,$weight_income,$weight_height,$weight_mstatus,$weight_country,$weight_manglik,$weight_education,$weight_occupation,$weight_city)
{
        $default_arr['SUBSCRIPTION']=100;
        $default_arr['RELIGION']=100;
        $default_arr['HAVECHILD']=100;
        $default_arr['MTONGUE']=$weight_mtongue;
        $default_arr['CASTE']=$weight_caste;
        $default_arr['HEIGHT']=$weight_height;
        $default_arr['EDU_LEVEL_NEW']=$weight_education;
        $default_arr['OCCUPATION']=$weight_occupation;
        $default_arr['INCOME']=$weight_income;
        $default_arr['INDIA_NRI']=$weight_country;
        $default_arr['UNMARRIED_MARRIED']=$weight_mstatus;
        $default_arr['MANGLIK']=$weight_manglik;
        $default_arr['DIET']=0;
        $default_arr['RELATION']=0;
        $keys=array_keys($default_arr);
        $values=array_values($default_arr);
        $len=count($keys);
        for($i=$len-2;$i>=0;$i--)
        {
                for($j=0;$j<=$i;$j++)
                {
                        if($values[$j]<$values[$j+1])
                        {
                                $tempval=$values[$j];
                                $values[$j]=$values[$j+1];
                                $values[$j+1]=$tempval;
                                $tempkey=$keys[$j];
                                $keys[$j]=$keys[$j+1];
                                $keys[$j+1]=$tempkey;
                        }
                }
        }
        $final_str=implode(',',$keys);

	        return $final_str;
}


?>