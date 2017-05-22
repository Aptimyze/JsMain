<?php
	chdir(dirname(__FILE__));
//	$_SERVER['DOCUMENT_ROOT']="/home/nikhil/download/svn-live/realsvn/branches/sms/";
	include_once("connect.inc");
	//Sharding+Combining 
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	

	// common include file
	include_once("flag.php");
	connect_slave81();
	$sql="select PROFILEID from matchalerts.DVD_PROFILES";
	$sql="select PROFILEID from matchalerts.DVD_PROFILES where SENT!='Y'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_assoc($res))
	{
		connect_slave81();
		$receiver=$row['PROFILEID'];
		$sql="select RECEIVER,MATCHES from matchalerts.DvDLogs where RECEIVER='$receiver' order by `DATE` desc limit 1";
		$res_rec=mysql_query_decide($sql) or die(mysql_error_js());
		if($row_rec=mysql_fetch_assoc($res_rec))
		{
			$dvd_profileid=$row_rec['RECEIVER'];
			$matches=$row_rec['MATCHES'];
			connect_db();
			create_csv($dvd_profileid,$matches);
		}
	}
	connect_db();
	$sql="update matchalerts.DVD_PROFILES set SENT='Y'";
	mysql_query_decide($sql) or die(mysql_error_js());
	
	
function mynl2br($text) {
	$text=htmlspecialchars(htmlspecialchars_decode($text,ENT_QUOTES),ENT_QUOTES);
   return strtr($text, array("\r\n" => ' ', "\r" => ' ', "\n" => ' ',"\"" => '&quot;',"'"=>'&#039'));
} 
function create_csv($dvd_profileid,$matches)
{
	//$matches=1644437;
	include("arrays.php");
	include("dropdowns.php");
	global $db;
	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;
	$jpartnerObj_logged=new Jpartner;
	
	$NEW_INCOME_MAP=$INCOME_DROP;
	$NEW_INCOME_MAP[9]=$NEW_INCOME_MAP[16];
	$NEW_INCOME_MAP[8]="Rs.0 - 5,00";
	$NEW_INCOME_MAP[10]=$NEW_INCOME_MAP[18];
	$NEW_INCOME_MAP[11]=$NEW_INCOME_MAP[20];
	$NEW_INCOME_MAP[12]=$NEW_INCOME_MAP[22];
	$NEW_INCOME_MAP[13]=$NEW_INCOME_MAP[23];
	$NEW_INCOME_MAP[21]=$NEW_INCOME_MAP[23];
	$NEW_INCOME_MAP[14]=$NEW_INCOME_MAP[23];
	$NEW_INCOME_MAP["1"]="Rs.0 - 50,000";

	//Used for ordering income
	$INCOME_ORDERING["1"]=1;
	$INCOME_ORDERING["2"]=2;
	$INCOME_ORDERING["3"]=3;
	$INCOME_ORDERING["4"]=4;
	$INCOME_ORDERING["5"]=5;
	$INCOME_ORDERING["6"]=6;
	$INCOME_ORDERING["7"]=0;
	$INCOME_ORDERING["15"]=0;
	$INCOME_ORDERING["16"]=16;
	$INCOME_ORDERING["17"]=17;
	$INCOME_ORDERING["18"]=18;
	$INCOME_ORDERING["20"]=20;
	$INCOME_ORDERING["22"]=22;
	$INCOME_ORDERING["23"]=23;
	$INCOME_ORDERING["8"]=6;
	$INCOME_ORDERING["9"]=16;
	$INCOME_ORDERING["10"]=18;
	$INCOME_ORDERING["11"]=20;
	$INCOME_ORDERING["12"]=22;
	$INCOME_ORDERING["13"]=23;
	$INCOME_ORDERING["21"]=23;
	$INCOME_ORDERING["14"]=23;
		//$db1=$mysqlObj->connect("11Master");
		//mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db1);
		$db1s=$mysqlObj->connect("11Slave");
		mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db1s);

		//$db2=$mysqlObj->connect("211");
		//mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db2);
		$db2s=$mysqlObj->connect("211Slave");
		mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db2s);

		//$db3=$mysqlObj->connect("303Master");
		//mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db3);
		$db3s=$mysqlObj->connect("303Slave");
		mysql_query_decide('set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000',$db3s);
		
		
		$sql="select * from newjs.JPARTNER where PROFILEID IN($matches)";
		$result=mysql_query_decide($sql,$db1s) or die(mysql_error($db1s));
		while($row=mysql_fetch_assoc($result))
			$partner_d[$row[PROFILEID]]=$row;

		$sql="select * from newjs.JPARTNER where PROFILEID IN($matches)";
		$result=mysql_query_decide($sql,$db2s) or die(mysql_error($db2s));
		while($row=mysql_fetch_assoc($result))
			$partner_d[$row[PROFILEID]]=$row;
			
		$sql="select * from newjs.JPARTNER where PROFILEID IN($matches)";
		$result=mysql_query_decide($sql,$db3s) or die(mysql_error($db3s));
		while($row=mysql_fetch_assoc($result))
			$partner_d[$row[PROFILEID]]=$row;
		//P_AGEHEIGHT"-@#@-"P_RELIGION"-@#@-"P_MTONGUE"-@#@-"P_CASTE"-@#@-"P_EDUCATION"-@#@-"P_INCOME"-@#@-"P_MANGLIK"-@#@-"P_MSTATUS
		
		foreach($partner_d as $key=>$val)
		{
			$str="".$val[LAGE]." to ".$val[HAGE]." years";
			$height1=explode("&",$HEIGHT_DROP[$val[LHEIGHT]]);
			$height2=explode("&",$HEIGHT_DROP[$val[HHEIGHT]]);

			$h1=$height1[0];
			$h2=$height2[0];
			if($val[LHEIGHT]==37)
			{
				$h1="7";
			}
			if($val[HHEIGHT]==37)
                        {       
                                $h2="7' plus";
				$str.="/$h1\" to $h2";
                        }
			else
				$str.="/$h1\" to $h2\"";

			$PDATA[$key][P_AGEHEIGHT]=$str;

			$PARTNER_DATA=display_format_new($val["PARTNER_RELIGION"]);
			$cnt=count($PARTNER_DATA);
			$arr_to_look=$RELIGIONS;
			if($cnt>=2)
				$mes="".$arr_to_look[$PARTNER_DATA[0]].", ".$arr_to_look[$PARTNER_DATA[1]]."";
			if($cnt>2)
				$mes=$mes." and ".($cnt-2)." others";
			if($cnt==0)
				$mes="-";
			if($cnt==1)
				$mes="".$arr_to_look[$PARTNER_DATA[0]]."";
			$PDATA[$key][P_RELIGION]=$mes;
			
			$PARTNER_DATA=display_format_new($val[PARTNER_MTONGUE]);
			$arr_to_look=$MTONGUE_DROP_SMALL;
			$cnt=count($PARTNER_DATA);
			$mes="";
			if($cnt==2)
				$mes="".$arr_to_look[$PARTNER_DATA[0]]." and ".$arr_to_look[$PARTNER_DATA[1]];
			elseif($cnt>1)
				$mes="".$arr_to_look[$PARTNER_DATA[0]]." and ".($cnt-1)." others";
			elseif($cnt==0)
				$mes="-";
			elseif($cnt==1)
				$mes="".$arr_to_look[$PARTNER_DATA[0]]."";
			$PDATA[$key][P_MTONGUE]=$mes;


			$PARTNER_DATA=display_format_new($val[PARTNER_CASTE]);

			$mes="";			
			$arr_to_look=$CASTE_DROP_SMALL;
			$cnt=count($PARTNER_DATA);
			$caste_value1=trim($arr_to_look[$PARTNER_DATA[0]],'-');
			$caste_value2=trim($arr_to_look[$PARTNER_DATA[1]],'-');
			if($cnt==2)
				$mes="".$caste_value1." and ".$caste_value2;
			elseif($cnt>1)
				$mes="".$caste_value1." and ".($cnt-1)." others";
			elseif($cnt==0)
				$mes="-";
			elseif($cnt==1)
				$mes="".$caste_value1."";

			$PDATA[$key][P_CASTE]=$mes;

			
			$PARTNER_DATA=display_format_new($val[PARTNER_ELEVEL_NEW]);
			@sort($PARTNER_DATA);
			$arr_to_look=$EDUCATION_LEVEL_DROP;
			$cnt=count($PARTNER_DATA);
			if($cnt>2)
				$mes=$arr_to_look[$PARTNER_DATA[0]]." to ".$arr_to_look[$PARTNER_DATA[$cnt-1]];
			elseif($cnt==2)
				$mes=$arr_to_look[$PARTNER_DATA[0]].", ".$arr_to_look[$PARTNER_DATA[1]];
			elseif($cnt==1)
				$mes=$arr_to_look[$PARTNER_DATA[0]];
			else
				$mes="-";
			$PDATA[$key][P_EDUCATION]=$mes;

			$mes="";
			
			$PARTNER_DATA=display_format_new($val[PARTNER_INCOME]);
			//print_r($PARTNER_DATA);
			$cnt=count($PARTNER_DATA);
	//		$cnt=$PARTNER_DATE;
			if($cnt==1)
			{
				$mes=$NEW_INCOME_MAP[$PARTNER_DATA[0]];
			}
			else
			{
				$pincome=array();
				for($i=0;$i<$cnt;$i++)
				{
					$pincome[]=$INCOME_ORDERING[$PARTNER_DATA[$i]];
				}
				
				sort($pincome);
				$pincome=array_unique($pincome);
				if(count($pincome)==1)
					$mes=$NEW_INCOME_MAP[$pincome[0]];

				foreach($pincome as $i_key=>$i_val)
				{
					if($i_val!="")
					$t_p[]=$i_val;
				}
				$pincome=$t_p;
				unset($t_p);
				if($pincome)
				{
					$first=explode(" ",$NEW_INCOME_MAP[$pincome[0]]);
					$second=explode(" ",$NEW_INCOME_MAP[$pincome[count($pincome)-1]]);
					
					if($second[1]=="and")
						$mes=$first[0]." and  above";
					else
						$mes=$first[0]." - ".$second[2];
				}
			}
			if($cnt==0)
				$mes="-";

			$PDATA[$key][P_INCOME]=$mes;
		
					$PARTNER_DATA=display_format_new($val[PARTNER_MANGLIK]);
					$arr_to_look=$MANGLIK;
			$cnt=count($PARTNER_DATA);
	//                $cnt=$PARTNER_DATE;
					if($cnt>1)
							$mes="".$arr_to_look[$PARTNER_DATA[0]]." and ".($cnt-1)." others";
					if($cnt==0)
							$mes="-";
					if($cnt==1)
							$mes="".$arr_to_look[$PARTNER_DATA[0]]."";
					$PDATA[$key][P_MANGLIK]=$mes;

			$PARTNER_DATA=display_format_new($val[PARTNER_MSTATUS]);
			$arr_to_look=$MSTATUS;
			$cnt=count($PARTNER_DATA);
			$MS_M1="Never Married, Married Earlier";
			$MS_M2="Married Earlier";
			$MS_M3="Never Married";
			$mes="";
			if(in_array("N",$PARTNER_DATA))
			{
				
				if($cnt>2)
					$mes=$MS_M1;
				elseif($cnt==2)
					$mes="".$arr_to_look[$PARTNER_DATA[0]].", ".$arr_to_look[$PARTNER_DATA[1]];
				elseif($cnt==1)
					$mes=$MS_M3;
				elseif($cnt<=0)
					$mes="-";
			}
			else
			{
				if($cnt>2)
					$mes=$MS_M2;
				elseif($cnt==2)
					$mes="".$arr_to_look[$PARTNER_DATA[0]].", ".$arr_to_look[$PARTNER_DATA[1]];
				elseif($cnt==1)
					$mes="".$arr_to_look[$PARTNER_DATA[0]]."";
				elseif($cnt<=0)
                                        $mes="-";
			}
	//                $cnt=$PARTNER_DATE;
			$PDATA[$key][P_MSTATUS]=$mes;
		}	
		@mysql_close();
		connect_db();
		
		//Symfony Photo Modification - start
		include_once("SymfonyPictureFunctions.class.php");
		$ALBUMTEMP=SymfonyPictureFunctions::getAlbum_nonSymfony($matches,'');
//                $ALBUMTEMP=SymfonyPictureFunctions::getPhotoUrls_nonSymfony($matches,'MainPicUrl');
                foreach($ALBUMTEMP as $k=>$v)
                {
                        $ALBUM[$k]['PROFILEPHOTO'] =   $v['PROFILEPHOTO'];
                        $ALBUM[$k]['ALBUMPHOTO1'] = $v['ALBUMPHOTO1'];
                        $ALBUM[$k]['ALBUMPHOTO2'] = $v['ALBUMPHOTO2'];
                }
		unset($ALBUMTEMP);
		//Symfony Photo Modification - end


	$sql="select * from newjs.JPROFILE where PROFILEID=$dvd_profileid";
	$res=mysql_query_decide($sql) or die(mysql_error());
	$row=mysql_fetch_array($res);
	if($row)
	{
		$MAIN=$row;
	}
	//$sql="select * from newjs.JPROFILE where PROFILEID IN('585375','756683','2192182  	','2648984','2459704','2157936','1597086','1323583','236000')";
	$sql="select * from newjs.JPROFILE where PROFILEID IN($matches)";
	$res=mysql_query_decide($sql) or die(mysql_error());
	@mysql_close();

	//$str_csv='"PROFILEID"-@#@-"AGE"-@#@-"HEIGHT"-@#@-"EDUCATION"-@#@-"RELIGION"-@#@-"CASTE"-@#@-"MTONGUE"-@#@-"SUBCASTE"-@#@-"GOTRA"-@#@-"MSTATUS"-@#@-"POSTEDBY"-@#@-"EDUCATION"-@#@-"LOCATION"-@#@-"INCOME"-@#@-"ABT_FAMILY"-@#@-"ABT_YOURSELF"-@#@-"ABT_DESIRED"-@#@-"P_AGEHEIGHT"-@#@-"P_RELIGION"-@#@-"P_MTONGUE"-@#@-"P_CASTE"-@#@-"P_EDUCATION"-@#@-"P_INCOME"-@#@-"P_MANGLIK"-@#@-"P_MSTATUS"-@#@-"S1"-@#@-"S2"-@#@-"S3"-@#@-"S4"-@#@-"S5"-@#@-"S6"';
	$head_csv='PROFILEID@#@USERNAME@#@AGE@#@HEIGHT@#@RELIGION@#@MTONGUE@#@CASTE@#@SUBCASTE@#@GOTRA@#@MSTATUS@#@POSTEDBY@#@EDUCATION@#@OCCUPATION@#@LOCATION@#@INCOME@#@ABT_FAMILY@#@ABT_YOURSELF@#@ABT_DESIRED@#@P_AGEHEIGHT@#@P_RELIGION@#@P_MTONGUE@#@P_CASTE@#@P_EDUCATION@#@P_INCOME@#@P_MANGLIK@#@P_MSTATUS@#@S1@#@S2@#@S3@#@S4@#@S5@#@S6@#@HAVEPHOTO@#@ALB@#@ALBS@#@CASTE_NAME@#@SHOW_SUBCASTE_GOTRA';
	$arr=explode("@#@",$head_csv);
	$fp = fopen("../dvd/FinalWork/csvs/$dvd_profileid.csv", 'w');
	fputcsv($fp, $arr,",",'"');
	$profile_cnt=0;
	
	while($row=mysql_fetch_assoc($res))
	{
		
		$profileid=$row['PROFILEID'];
		$username=$row['USERNAME'];
		$s1_inc_array=array("1","2","3","4","5","6","0");
		$s2_inc_array=array("16","17");
		$s3_inc_array=array("18","20","22","23");
		
		/* For Income 0-5 */


		if(in_array($INCOME_ORDERING[$row['INCOME']],$s1_inc_array))
			$s1=1;
		else
			$s1=0;

		if($row['INCOME']=="")
			$s1=1;
		/* For Income 5-10 */

			if(in_array($INCOME_ORDERING[$row['INCOME']],$s2_inc_array))
			$s2=1;
		else
			$s2=0;

		/* For Income 10+ */

			if(in_array($INCOME_ORDERING[$row['INCOME']],$s3_inc_array))
			$s3=1;
		else
			$s3=0;

		// Need to validate the values of array ??

		$s4_edu_array=array(1,2,3,4,5,6,25,26);
		$s5_edu_array=array(7,27,11,12,13,14,15,16,17,18,19,20);	
		$s6_edu_array=array(9,1,2,3,4,5,6,7,8,10,22,27,11,12,13,14,15,16,17,18,19,20,21,23,24,25,26);

		/* For Bachelors */ 

			if(in_array($row['EDU_LEVEL_NEW'],$s4_edu_array))
			$s4=1;
		else
			$s4=0;

		/* For Masters */

			if(in_array($row['EDU_LEVEL_NEW'],$s5_edu_array))
			$s5=1;
		else
			$s5=0;

		/* For Profession */

			if(in_array($row['EDU_LEVEL_NEW'],$s6_edu_array))
			$s6=1;
		else
			$s6=0; 

		$age=$row['AGE']." years";

		$height=$row["HEIGHT"];
			$height1=explode("&",$HEIGHT_DROP["$height"]);
		$height=$height1[0];

		$education=$EDUCATION_LEVEL_NEW_DROP[$row["EDU_LEVEL_NEW"]];

		$religion=$RELIGIONS[$row["RELIGION"]];

		$caste=$CASTE_DROP[$row['CASTE']];
		$mtongue = $MTONGUE_DROP_SMALL[$row["MTONGUE"]];

		$AGE[]=$row['AGE'];
		$HEIGHT[]=$row['HEIGHT'];
		$CASTE[$row['CASTE']]++;
		$MT[$row['MTONGUE']]++;
		$MN[$row['MANGLIK']]++;	
		$ALL_REG[$row['RELIGION']]++;

		$subcaste="";
			if(isFlagSet("SUBCASTE",$row["SCREENING"]))
					$subcaste=$row["SUBCASTE"];
		$gothra="";
		if(isFlagSet("GOTHRA",$row["SCREENING"]))
			$gothra=$row["GOTHRA"];

		$mstatus=$MSTATUS[$row["MSTATUS"]];

		$postedby=$RELATIONSHIP[$row["RELATION"]];

		$occupation=$OCCUPATION_DROP[$row["OCCUPATION"]];
		
		$country_res=$row["COUNTRY_RES"];
		$country_res=$COUNTRY_DROP["$country_res"];

		$city_res_val = $row["CITY_RES"];
		$city_res="";
		if($city_res_val)
		{
				$sql_ci = "SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res_val'";
				$res_ci = mysql_query_optimizer($sql_ci);
				$row_ci = mysql_fetch_array($res_ci);
				$city_res = $row_ci['LABEL'];
		}
		$location="";
		if($country_res)
			$location=$country_res;
		if($city_res)
			if($location!="")
				$location=$city_res.", ".$location;

		$income=$INCOME_DROP[$row["INCOME"]];

		$abt_family="";
		if(isFlagSet("FAMILYINFO",$row["SCREENING"]) && $row["FAMILYINFO"]!="")
		{
			$abt_family=mynl2br($row["FAMILYINFO"],false);
		}
		else if(isFlagSet("JOB_INFO",$row["SCREENING"])&& $row["JOB_INFO"]!="" )
		{
			$abt_family=mynl2br($row["JOB_INFO"],false);
		}
		else if(isFlagSet("EDUCATION",$row["SCREENING"])&& $row["EDUCATION"]!="")
		{
			$abt_family=mynl2br($row["EDUCATION"],false);
		}
	        $abt_yourself="";	
		if(isFlagSet("YOURINFO",$row["SCREENING"]))
		{
			$abt_yourself=mynl2br(trim($row["YOURINFO"]),false);
		}
		$abt_desired ="";
		if(isFlagSet("SPOUSE",$row["SCREENING"]))
		{
			$abt_desired =mynl2br($row["SPOUSE"],false);
		}
		$havephoto='N';
		  //Photo checks
		  $alb1='N';
		  $alb2='N';

		if($row['HAVEPHOTO']=='Y' && $row['PHOTO_DISPLAY']=='A')
		{
			$havephoto='Y';
			$photoscreen=$row['PHOTOSCREEN'];
			$albumphoto1=$ALBUM[$profileid]['ALBUMPHOTO1'];
			$albumphoto2=$ALBUM[$profileid]['ALBUMPHOTO2'];

			//Symfony Photo Modification - start
			 if($albumphoto1)
			{
				$alb1=$albumphoto1;
			}
			 if($albumphoto2)
			{
				$alb2=$albumphoto2;
			}
			$havephoto = $ALBUM[$profileid]['PROFILEPHOTO'];
			//Symfony Photo Modification - end
			 
		}
		
		//Setting value -9 if value in not required..
		$no_rel=array(5,6,7,8);
		$sec_rel=array(2,3);
		$cas_rel=array(1,4,9);
		$caste_name="";
		if(in_array($row['RELIGION'],$no_rel))
			$caste_name="";
		elseif(in_array($row['RELIGION'],$sec_rel))
			$caste_name="Sect";
		elseif(in_array($row['RELIGION'],$cas_rel))
			$caste_name="Caste";
			
		$show_caste_gotra=0;
		if($row['RELIGION']==1)
			$show_caste_gotra=1;
		//	$str_csv='"PROFILEID"-@#@-"USERNAME"-@#@-"AGE"-@#@-"HEIGHT"-@#@-"RELIGION"-@#@-"MTONGUE"-@#@-"CASTE"-@#@-"SUBCASTE"-@#@-"GOTRA"-@#@-"MSTATUS"-@#@-"POSTEDBY"-@#@-"EDUCATION"-@#@-"OCCUPATION"-@#@-"LOCATION"-@#@-"INCOME"-@#@-"ABT_FAMILY"-@#@-"ABT_YOURSELF"-@#@-"ABT_DESIRED"-@#@-"P_AGEHEIGHT"-@#@-"P_RELIGION"-@#@-"P_MTONGUE"-@#@-"P_CASTE"-@#@-"P_EDUCATION"-@#@-"P_INCOME"-@#@-"P_MANGLIK"-@#@-"P_MSTATUS"-@#@-"S1"-@#@-"S2"-@#@-"S3"-@#@-"S4"-@#@-"S5"-@#@-"S6"';
		$P_AGEHEIGHT=$PDATA[$profileid][P_AGEHEIGHT];
		$P_RELIGION=$PDATA[$profileid][P_RELIGION];
		$P_MTONGUE=$PDATA[$profileid][P_MTONGUE];
		$P_CASTE=$PDATA[$profileid][P_CASTE];
		$P_EDUCATION=$PDATA[$profileid][P_EDUCATION];
		$P_INCOME=$PDATA[$profileid][P_INCOME];
		$P_MANGLIK=$PDATA[$profileid][P_MANGLIK];
		$P_MSTATUS=$PDATA[$profileid][P_MSTATUS];
		$each_str=''.$profileid.'@#@'.$username.'@#@'.$age.'@#@'.$height.'@#@'.$religion.'@#@'.$mtongue.'@#@'.$caste.'@#@'.$subcaste.'@#@'.$gothra.'@#@'.$mstatus.'@#@'.$postedby.'@#@'.$education.'@#@'.$occupation.'@#@'.$location.'@#@'.$income.'@#@'.$abt_family.'@#@'.$abt_yourself.'@#@'.$abt_desired.'@#@'.$P_AGEHEIGHT.'@#@'.$P_RELIGION.'@#@'.$P_MTONGUE.'@#@'.$P_CASTE.'@#@'.$P_EDUCATION.'@#@'.$P_INCOME.'@#@'.$P_MANGLIK.'@#@'.$P_MSTATUS.'@#@'.$s1.'@#@'.$s2.'@#@'.$s3.'@#@'.$s4.'@#@'.$s5.'@#@'.$s6.'@#@'.$havephoto.'@#@'.$alb1.'@#@'.$alb2.'@#@'.$caste_name.'@#@'.$show_caste_gotra.'';
	//echo $each_str;
	//echo "\n";

	$str_csv[]=mynl2br($each_str);
		//$arr=explode("-@#@-",$str_csv);
		//fputcsv($fp, $arr,"-",'"');
	//	$str_csv.='"'.$profileid.'"-@#@-"'.$username.'"-@#@-"'.$age.'"-@#@-"'.$height.'"-@#@-"'.$education.'"-@#@-"'.$s1.'"-@#@-"'.$s2.'"-@#@-"'.$s3.'"-@#@-"'.$s4.'"-@#@-"'.$s5.'"-@#@-"'.$s6.'"-@#@-"'.$religion.'"-@#@-"'.$caste.'"-@#@-"'.$mtongue.'"-@#@-"'.$subcaste.'"-@#@-"'.$gotra.'"-@#@-"'.$mstatus.'"-@#@-"'.$postedby.'"-@#@-"'.$education.'"-@#@-"'.$location.'"-@#@-"'.$income.'"-@#@-"'.$abt_family.'"-@#@-"'.$abt_yourself.'"-@#@-"'.$abt_desired.'"-@#@-"P_AGEHEIGHT"-@#@-"P_RELIGION"-@#@-"P_MTONGUE"-@#@-"P_CASTE"-@#@-"P_EDUCATION"-@#@-"P_INCOME"-@#@-"P_MANGLIK"-@#@-"P_MSTATUS"';
		
		$profile_cnt++;
	}

	//Fetching message for Homepage. 
	sort($AGE);
	sort($HEIGHT);
	@arsort($CASTE);
	@arsort($ALL_REG);
	if($MAIN['GENDER']=='M')
		$str="Female";
	else
		$str="Male";
	$top="";

	$cnt=count($ALL_REG);
	foreach($ALL_REG as $key=>$val)
	{
		if($top=="")
			$top=$RELIGIONS[$key];
		elseif($cnt<=2)
			$top=$top." and ".$RELIGIONS[$key];
		
	}
	if($cnt>2)
	{
		$str.=", $top and ".($cnt-2)." other religions";
	}
	elseif($top!="")
               $str.=", $top";

	$top="";
	$cnt=count($CASTE);
	$rel_arr=array("1","2","3","4","9");
	$pass=0;
	if(in_array($MAIN['RELIGION'],$rel_arr))
	{
		$pass=1;
		foreach($CASTE as $key=>$val)
		{
			if($top=="")
				$top=$CASTE_DROP[$key];
			else if($cnt<=2)
				$top=$top." and ".$CASTE_DROP[$key];		
		}
		if($cnt>2)
		{
			$str.=", $top and $cnt other castes";
		}
		elseif($top!="")
			$str.=", $top";
	}

	$cnt=count($AGE)-1;
	if($pass)
		$str.="BREAK, Age $AGE[0]-$AGE[$cnt] years";
	else
		$str.=", Age $AGE[0]-$AGE[$cnt] yearsBREAK";

	$cnt=count($HEIGHT)-1;
	$height1=$HEIGHT[0];
	$height2=$HEIGHT[$cnt];
	$height1=explode("&",$HEIGHT_DROP["$height1"]);
	$height2=explode("&",$HEIGHT_DROP["$height2"]);
	$h1=$height1[0];
	$h2=$height2[0];

	$str.=", $h1 to $h2";

	if($MAIN['RELIGION']=="1")
	if(count($MN)==1)
			if($MN['Y'])
				$str.=", Manglik";
			else
				$str.=", Non Manglik";
	$cnt=count($MT);
	$top="";
	@arsort($MT);
	foreach($MT as $key=>$val)
	{
			if($top=="")
				$top=$MTONGUE_DROP_SMALL[$key];
			else if($cnt<=2)
				$top=$top." and ".$MTONGUE_DROP_SMALL[$key];
	}
	if($cnt>2)
	{
			$str.=", $top and ".($cnt-1)." others";
	}
	elseif($top!="")
		$str.=", $top";
	$gender='M';
	if($MAIN["GENDER"]=='M')
		$gender='F';

	$own_csv=mynl2br(''.$MAIN['PROFILEID'].'@#@'.$MAIN['USERNAME'].'@#@'.$gender.'@#@'.$str.'@#@'.$profile_cnt.'');

	$arr=explode("@#@",$own_csv);
	fputcsv($fp, $arr,",",'"');
	foreach($str_csv as $key=>$val)
	{
		$arr=explode("@#@",$val);
		fputcsv($fp, $arr,",",'"');
	}	

	fclose($fp);
}
function display_format_new($str)
{
	if($str)
	{
		$str=trim($str,"'");
		//$str=strstr($str, array("\r\n" => ' ', "\r" => ' ', "\n" => ' '));
		$arr=explode("','",$str);
		return $arr;
	}
	
}
?>
