<?php
chdir(dirname(__FILE__));
include('connect.inc');
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include('../profile/arrays.php');
//include(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
include(JsConstants::$docRoot."/classes/sendmail.php");
$db_slave=connect_slave();
mysql_select_db('sugarcrm',$db_slave);
mysql_query("set session wait_timeout=10000",$db_slave);

global $smarty,$_SERVER,$CITY_INDIA_DROP,$COUNTRY_DROP,$EDUCATION_LEVEL_NEW_DROP,$HEIGHT_DROP,$OCCUPATION_DROP,$CASTE_DROP,$MTONGUE_DROP,$CITY_USA_DROP,$INCOME_DROP,$RELIGIONS,$CITY_DROP,$INCOME;
$all_hindi=array(10,19,33,7,28,13,41);

$news_arr=array (
  0 => '',
  1 => 'Times Of India',
  2 => 'Hindustan Times',
  3 => 'Amar Ujala',
  4 => 'Dianik Jagran',
  5 => 'The Hindu',
  6 => 'Rajasthan Patrika',
  7 => 'Punjab Kesari',
  8 => 'The Tribune',
  9 => 'Sakaal',
  10 => 'Nai Dunia',
  11 => 'Lok Satta',
  12 => 'Gujarat Samachar',
  13 => 'Mumbai Samachar',
  14 => 'Lokmat',
  15 => 'Sandesh',
  16 => 'Hitwada',
  17 => 'Malayalam Manorama',
  18 => 'Divya Bhaskar',
  19 => 'Dainik Bhaskar',
  20 => 'New Indian Express',
  21 => 'Deccan Conical',
  22 => 'Deccan Herald',
  23 => 'Telegraph',
  24 => 'Hindu',
  25 => 'Anando Bazaar Patrika',
  26 => 'Inquilab',
  27 => 'Urdu Times',
);
$db=connect_db();
mysql_query("set session wait_timeout=10000",$db);
mysql_select_db('sugarcrm',$db);
if($argv[1])
{
	$id=trim($argv[1],"'");
	$sql= "SELECT a.id,c.email_address_id as email,campaign_id,response_ad_c,age_c,gender_c,height_c,posted_by_c,marital_status_c,religion_c,caste_c,mother_tongue_c,education_c,occupation_c,income_c,manglik_c,city_c,lead_source,edition_date_c,assigned_user_id,type_c from leads as a, leads_cstm as b,email_addr_bean_rel as c where a.id=b.id_c and a.id=c.bean_id and c.bean_module='Leads' and a.deleted<>1 and converted<>1 and do_not_email_c<>1 and c.primary_address=1 and c.deleted<>1 and c.id<>'' and a.status<>'6' and a.id='$id'";
$res=mysql_query($sql,$db) or die(mysql_error1($sql,$db));
}
else
{
	$sql= "SELECT a.id,c.email_address_id as email,campaign_id,response_ad_c,age_c,gender_c,height_c,posted_by_c,marital_status_c,religion_c,caste_c,mother_tongue_c,education_c,occupation_c,income_c,manglik_c,city_c,lead_source,edition_date_c,assigned_user_id,type_c from leads as a, leads_cstm as b,email_addr_bean_rel as c where a.id=b.id_c and a.id=c.bean_id and c.bean_module='Leads' and a.deleted<>1 and converted<>1 and do_not_email_c<>1 and c.primary_address=1 and c.deleted<>1 and c.id<>'' and a.status<>'6'";
$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
}
while($row= mysql_fetch_array($res))
{
	$emailid=$row['email'];
	$sql_email="SELECT email_address from email_addresses where id='$emailid' AND opt_out <>1";
	$res_email=mysql_query($sql_email,$db) or die(mysql_error1($sql_email,$db));
	$row_email=mysql_fetch_assoc($res_email);
	$email=$row_email['email_address'];
	if($email=='')
		continue;
	$age=$row['age_c'];
	$gender=$row['gender_c'];
	$height=$row['height_c'];
	$relation=$row['posted_by_c'];
	$mstat=$row['marital_status_c'];
	$religion=$row['religion_c'];
	$caste=$row['caste_c'];
	$mton=$row['mother_tongue_c'];
	$edu=$row['education_c'];
	$occ=$row['occupation_c'];
	$income=$row['income_c'];
	$manglik=$row['manglik_c'];
	$city_c=$row['city_c'];
	$source=$row['lead_source'];
	$ad=$row['response_ad_c'];
	$newscode=$row['type_c'];
	$newspaper=$news_arr[$newscode];
	$news_date=$row['edition_date_c'];	
	$campaign=$row['campaign_id'];
	$lead=$row['id'];
	$offline_id='';	
	$smarty->assign("offline",0);
	$smarty->assign('bt_line','');
	$smarty->assign('lead',$lead);
	if($gender=='')
		continue;
	$news_info='';
	if($ad)
	{
		if(!$campaign)
			continue;
		$sql_camp="SELECT username_c,newspaper_c,edition_c from campaigns as a,campaigns_cstm as b where a.id=b.id_c and a.id='$campaign'";
		$res_camp=mysql_query($sql_camp,$db) or die(mysql_error1($sql_camp,$db));
		$row_camp=mysql_fetch_assoc($res_camp);
		$offline_id=$row_camp['username_c'];
		if(!$offline_id)
			continue;
//		$news_info="$offline_id";
		$camp_news=$news_arr[$row_camp['newspaper_c']];
		if($camp_news)
			$news_info=" in ".$camp_news;
		if($row_camp['edition_c'])
			$news_info.="  on ".$row_camp['edition_c'];
		$news_info1=$news_info;
		$news_info=$offline_id.$news_info1;
		$source=9;
	}
	elseif($source!='4' && $source!=1 && $source!='2')
		continue;
	if($source=='4')
		if($newspaper=='' && $news_date=='')
			continue;
	$exe_id=$row['assigned_user_id'];
	$sql_exe="SELECT first_name,last_name,phone_mobile,phone_work,address_city,address_street,address_state,address_country,address_postalcode from users where id='$exe_id'";
	$res_exe=mysql_query($sql_exe,$db_slave) or die(mysql_error1($sql_exe,$db_slave));
	$row_exe=mysql_fetch_assoc($res_exe);
	if($row_exe['first_name']!='')
		$exe=$row_exe['first_name']." ".$row_exe['last_name'];
	else
		$exe='our executive';
	if($row_exe['phone_mobile'] || $row_exe['phone_work'])
	{
		$exe_info=$exe." at ";
		if($row_exe['phone_mobile'])
		 	$exe_info.="(M) ".$exe_ph=$row_exe['phone_mobile'];
		if($row_exe['phone_work'])
			if($row_exe['phone_mobile'])
				$exe_info.=", ".$row_exe['phone_work'];
			else
				$exe_info.=$row_exe['phone_work'];
	}
	
	elseif($exe=='our executive')
		continue;
	else
		$exe_info=$exe;
	if($row_exe['address_street'])
		$address[]=$row_exe['address_street'];
	if($row_exe['address_city']!='')
	{
		$loc="at ".$row_exe['address_city'];
		$address[]=$row_exe['address_city'];
	}
	else
		$loc="";
	if($row_exe['address_state'])
		$address[]=$row_exe['address_state'];
	if(is_array($address))
	{
		$address_str=implode(", ",$address);
		if($row_exe['address_postalcode'])
                	$address_str.=" - ".$row_exe['address_postalcode'];
	}
	else
		$address_str='-';
	if($exe_ph=='')
		$exe_ph='-';	
	$smarty->assign('exe_name',$exe);
	$smarty->assign('exe_mob',$exe_ph);
	$smarty->assign('address_str',$address_str);
	$sql_exe_email="select email_address from email_addr_bean_rel as r,email_addresses as e where e.id=r.email_address_id and bean_id='$exe_id' and bean_module='Users' and e.deleted<>1 and r.deleted<>1";
	$res_exe_email=mysql_query($sql_exe_email,$db_slave) or die(mysql_error1($sql_exe_email,$db_slave));
	$row_exe_email=mysql_fetch_assoc($res_exe_email);
	$reply=$row_exe_email['email_address'];
	if($reply)
		$reply.=",kumar.sanjeev@jeevansathi.com";
	else
		$reply="kumar.sanjeev@jeevansathi.com";
	if($gender=='M')
	{
		$table="SEARCH_FEMALE";
		//Age
		$lage=$age-5;
		if($lage<18)
			$lage='18';
		$hage=$age;
		//Height
		$lheight=$height-7;
		if($lheight<1)
			$lheight=1;
		$hheight=$height;
		if($relation=='3' || $relation=='5')
		{
			$by1=$by='your brother';
			$by2="your brother's";
		}
		elseif($relation=='2')
		{
			$by1=$by='your son';
			$by2="your son's";
		}
		elseif($relation=='4')
		{
			$by1=$by='your relative/friend';
			$by2="your relative/friend's";
		}
		else	
		{
			$by='yours';
			$by1='you';
			$by2='your';
		}
		//Default value for Income is set of all incomes less than boy's income 
		if($income)
		{
			$search_income=$INCOME[$income]['LESS'];

			// If boy's income is lowest, default value is lowest income and 'Not working'
			if($search_income=="'15'")
				$DPP['Income']="'1','15'";
			// If boy's not working default value is 'Not working'
			elseif($search_income=='')
				$search_income="'15'";
		}
		else
			$search_income="";

	}
	else
	{
		$table="SEARCH_MALE";
		$lage=$age;
		if($lage<21)
			$lage='21';
		$hage=$age+5;
		//Height
		$lheight=$height;
		$hheight=$height+7;
		if($relation=='3' || $relation=='5')
		{
			$by1=$by='your sister';
			$by2="your sister's";
		}
		elseif($relation=='2')
		{
			$by1=$by='your daughter';
			$by2="your daughter's";
		}
		elseif($relation=='4')
		{
                        $by1=$by='your relative/friend';
			$by2="your relative/friend's";
		}
		else
		{
			$by='yours';
			$by2='your';
			$by1='you';
		}
		if($income)
		{
			$search_income=$INCOME[$income]['MORE'];
			// If girl's income is highest, default value is highest income
			if($search_income=='')
				$search_income="'14'";
		}
		else
			$search_income="";
	}
	$smarty->assign("GENDER",$gender);
	
	$sql_repeat="SELECT MATCHES FROM LEAD_MATCHES_LOG WHERE LEAD='$lead'";
	$res_repeat=mysql_query($sql_repeat,$db_slave) or die(mysql_error1($sql_repeat,$db_slave));
	while($row_repeat=mysql_fetch_array($res_repeat))
	{
		if($row_repeat['MATCHES']!='')
			$matches[]=$row_repeat['MATCHES'];
	}
	$matches_str=@implode(",",$matches);
	unset($matches);
	$city=$city_c;
	$j=0;
	if($city=='')
	{
		$city='7'; // hook to show default city when lead has no city
	}
		$sql_map="SELECT CENTRE FROM CITY_CONTACT_MAPPING WHERE CITY='$city'";
		$res_map=mysql_query($sql_map,$db_slave) or die(mysql_error1($sql_map,$db_slave));
		while($row_map=mysql_fetch_array($res_map))
		{
			$centre_arr=explode(",",$row_map['CENTRE']);
			$centre_str="'".implode("','",$centre_arr)."'";
			$sql_contact="SELECT EXECUTIVE,ADDRESS,NAME,PHONE FROM CENTRE_INFO WHERE ID IN ($centre_str)";
			$res_contact=mysql_query($sql_contact,$db_slave) or die(mysql_error1($sql_contact,$db_slave));
			while($row_contact= mysql_fetch_array($res_contact))
			{
				$contact[$j]['PERS']=$row_contact['EXECUTIVE'];
				$contact[$j]['ADD']=$row_contact['ADDRESS'];
				$contact[$j]['NAME']=$row_contact['NAME'];
				$contact[$j]['TEL']=$row_contact['PHONE'];
				if($contact[$j]['TEL']=='')
					$contact[$j]['TEL']='1-800-419-6299';
				$j++;
			}
		}
		/*$part=substr($city,0,2);
		if($part=='DE')
			$part='DE00';	
		$sql_contact="SELECT NAME,CONTACT_PERSON, ADDRESS, PHONE, MOBILE FROM newjs.CONTACT_US WHERE STATE_VAL='$part'";
		$res_contact=mysql_query($sql_contact,$db_slave) or die(mysql_error1($sql_contact,$db_slave));
		while($row_contact= mysql_fetch_array($res_contact))
		{
			$contact[$j]['NAME']=$row_contact['NAME'];
			$contact[$j]['PERS']=$row_contact['CONTACT_PERSON'];
			$contact[$j]['ADD']=$row_contact['ADDRESS'];
			$contact[$j]['TEL']=$row_contact['PHONE'];
			$contact[$j]['MOB']=$row_contact['MOBILE'];
			$j++;
		}*/
	$smarty->assign("j",$j);
	$smarty->assign("ind1",-1);
	$smarty->assign("rows",ceil($j/2));
	$smarty->assign("CONTACT",$contact);
	unset($contact);	
	$smarty->assign("walkin",'');
	$smarty->assign('first_offline','');
if($source=='9' and $matches_str=='')
{
	$sql_off="SELECT PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,COUNTRY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,SUBSCRIPTION,MTONGUE,SUBCASTE,GOTHRA,NAKSHATRA,FATHER_INFO,SIBLING_INFO,RELIGION,PHOTOSCREEN,EDUCATION,JOB_INFO,FAMILY_VALUES,MOTHER_OCC,T_BROTHER,T_SISTER,FAMILY_TYPE,FAMILY_STATUS,M_BROTHER,M_SISTER FROM newjs.JPROFILE WHERE USERNAME='$offline_id'";
	$res_off=mysql_query($sql_off,$db_slave) or die(mysql_error1($sql_off,$db_slave));
	$row_off=mysql_fetch_assoc($res_off);
	$off_pid=$row_off['PROFILEID'];

	$off_info['photochecksum']=md5($off_pid + 5)."i".($off_pid + 5);
	$off_info['photochecksum_new'] = intval(intval($off_pid)/1000) . "/" . md5($off_pid+5);
	$off_info['profilechecksum']=md5($off_pid)."i".$off_pid;
	$off_info['profileid']=$off_pid;
	$off_info['photo']=$row_off['HAVEPHOTO'];
	$off_info['username']=$row_off['USERNAME'];
	$off_info['yourinfo']=$row_off["YOURINFO"];
	$off_info['familyinfo']=$row_off["FAMILYINFO"];
	$off_info['spouseinfo']=$row_off["SPOUSE"];
	$off_info['screening']=$row_off["SCREENING"];
	$off_info['age']=$row_off['AGE'];
	$off_info['edu_info']=$row_off['EDUCATION'];
	$off_info['job_info']=$row_off['JOB_INFO'];
	$off_info['mtongue']=$MTONGUE_DROP[$row_off['MTONGUE']];
	$height=$HEIGHT_DROP[$row_off['HEIGHT']];
	$height1=explode(" (",$height);
	$off_info['height']=$height1[0];
	$jj=0;
	
	if($row_off['FAMILY_VALUES'] && $FAMILY_VALUES[$row_off['FAMILY_VALUES']])
	{
		$off_tab_info[$jj]['label']='Family Values';
		$off_tab_info[$jj]['value']=$FAMILY_VALUES[$row_off['FAMILY_VALUES']];
		$jj++;
	}
	if($row_off['MOTHER_OCC'] && $MOTHER_OCC_DROP[$row_off['MOTHER_OCC']])
	{
		$off_tab_info[$jj]['label']='Mother';
		$off_tab_info[$jj]['value']=$MOTHER_OCC_DROP[$row_off['MOTHER_OCC']];	
		$jj++;
	}
	if($row_off['FAMILY_TYPE'] && $FAMILY_TYPE[$row_off['FAMILY_TYPE']])
	{
		$off_tab_info[$jj]['label']='Family Type';
		$off_tab_info[$jj]['value']=$FAMILY_TYPE[$row_off['FAMILY_TYPE']];
		$jj++;
	}
	if($row_off['T_BROTHER'])
	{
		$bro=$row_off['T_BROTHER'];
		if($row_off['T_BROTHER'] >1)
			$bro.=" brothers";
		else
			$bro.=" brother"; 
		if($row_off['M_BROTHER'])
			$bro.=" of which ".$row_off['M_BROTHER']." married";
		$off_tab_info[$jj]['label']='Brother(s)';
		$off_tab_info[$jj]['value']=$bro;
		$jj++;
	}
	if($row_off['FAMILY_STATUS'] && $FAMILY_STATUS[$row_off['FAMILY_STATUS']])
	{
		$off_tab_info[$jj]['label']='Family Status';
		$off_tab_info[$jj]['value']=$FAMILY_STATUS[$row_off['FAMILY_STATUS']];
		$jj++;
	}
	if($row_off['T_SISTER'])
        {
                $sis=$row_off['T_SISTER'];
                if($row_off['T_SISTER'] >1)
                        $sis.=" sisters";
                else
                        $sis.=" sisther";
                if($row_off['M_SISTER'])
                        $sis.=" of which ".$row_off['M_SISTER']." married";
		$off_tab_info[$jj]['label']='Sister(s)';
		$off_tab_info[$jj]['value']=$sis;
		$jj++;
        }
	if($row_off['FATHER_OCC'])
        {
                $off_tab_info[$jj]['label']='FATHER';
                $off_tab_info[$jj]['value']=$FAMILY_BACK_DROP[$row_off['FATHER_OCC']];
                $jj++;
        }
	if($row_off['LIVE_WITH_PARENTS'])
	{

		$off_tab_info[$jj]['label']='Living with Parents';
		if($row_off['LIVE_WITH_PARENTS']=='Y')
			$off_tab_info[$jj]['value']='Yes';
		if($row_off['LIVE_WITH_PARENTS']=='N')
			$off_tab_info[$jj]['value']='No';
		
	}
	$ii=ceil($jj/2);
	$smarty->assign("ii",$ii);

	if($row_off['CASTE']!='162')
	{
		$castes=explode(':',$CASTE_DROP[$row_off['CASTE']]);
		if($castes[1])
			$off_info['caste']=$castes[1];
		elseif($castes[0])
			$off_info['caste']=$castes[0];
	}
	$off_info['occupation']=$OCCUPATION_DROP[$row_off['OCCUPATION']];
	$off_info['city_res']=$row_off['CITY_RES'];
	if($off_info['city_res']=='')
	$off_info['country']=$row_off['COUNTRY_RES'];
	$off_info['privacy']=$row_off['PRIVACY'];
	$off_info['photo_display']=$row_off['PHOTO_DISPLAY'];
	$off_info['income']=$INCOME_DROP[$row_off['INCOME']];
	$off_info['edu']=$EDUCATION_LEVEL_NEW_DROP[$row_off['EDU_LEVEL_NEW']];
	$off_info['subs']=$row_off['SUBSCRIPTION'];
	$off_info['subcaste'] = $row_off["SUBCASTE"];
	$off_info['nakshatra']=$row_off["NAKSHATRA"];
	$off_info['gothra']=$row_off["GOTHRA"];
	$off_info['religion']=$RELIGIONS[$row_off["RELIGION"]];
	$off_info['yourinfo']=substr($off_info['yourinfo'] . " " . $off_info['familyinfo'] . " " . $off_info['spouseinfo'],0,300);
	$off_info['city_res']=$CITY_DROP[$off_info['city_res']];
		if($off_info['city_res']=='')
		{
			$off_info['city_res']=$CITY_INDIA_DROP[$off_info['city_res']];
		}
		if($off_info['city_res']=='')
			$off_info['city_res']=$COUNTRY_DROP[$off_info['country']];
	
	$smarty->assign("offline",$off_info);
	$smarty->assign("off_tab_info",$off_tab_info);
	unset($off_info);
	$lead_line="We received $by2 bio data in response to our matrimonial advertisement / letter. The ad was posted by Jeevansathi.com Match Point on behalf of <b>our client ( client Id: $offline_id )</b>$news_info1.<br><br>The particulars of $by match the particulars of our client ".$offline_id." . <font color='green'>The detailed bio data of our client is presented below </font><br><br>To proceed further or to <b>contact our client - $offline_id</b>, kindly visit our office at the address mentioned below or call <b>$exe_info</b>. Once you visit our office with the bio data and photo of $by, we will take your written consent to share your details with our clients. Besides that, we will also give two contact details of our clients you are interested in for FREE.<br><br>";
	$sql_pid="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$offline_id'";
	$res_pid=mysql_query($sql_pid,$db_slave) or die(mysql_error1($sql_pid,$db_slave));
	$row_pid=mysql_fetch_assoc($res_pid);

	$pids=$row_pid['PROFILEID'];
	if($pids)
	{
	$smarty->assign("DATA",'');
	$smarty->assign('first_offline',1);
	$sql_log="INSERT INTO LEAD_MATCHES_LOG(LEAD,DATE,MATCHES,EXE) VALUES ('$lead',now(),'$pids','$exe_id')";
	$res_log=mysql_query($sql_log,$db) or die(mysql_error1($sql_log,$db));
	$mid=mysql_insert_id($db);
	$smarty->assign('mid',$mid);
	$unsubscribe="$SITE_URL/sugarcrm/unsubscribe.php?id=$lead&source=lma";
	$smarty->assign('unsubscribe',$unsubscribe);
	$smarty->assign('lead_line',$lead_line);
	$msg = $smarty->fetch("lead_match_alert.htm");
	$from = "matchalert@jeevansathi.com";
	$subject="Jeevansathi.com has matches for you";
	//echo $msg;
	$sent=sendmail($from,$email,$msg,$subject,'Jeevansathi.com',$reply);
//			mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","leads matchalert",$msg);die;
	continue;
	}
	else
		continue;

}
	$sql_sel="SELECT PROFILEID FROM newjs.$table WHERE (SUBSCRIPTION LIKE '%O%' OR SUBSCRIPTION LIKE '%D%' ) ";
	if($matches_str!='' && $matches_str!=',')
		 $sql_sel.=" AND PROFILEID NOT IN ($matches_str) ";
	if($caste!='')
	{
		$caste_ar=explode('_',$caste);
		$caste1=$caste_ar[1];
		$sql_w[]="CASTE=$caste1";
	}
	if($mstat!='')
	{
		if($mstat=='N')
			$sql_w[]="MSTATUS='N'";
		else
			$sql_w[]="MSTATUS IN ('S','D','O','W','A')";
	}
	if($mton!='')
	{
		$sql_w[]="MTONGUE=$mton";
	}
	if($age)
	{
		$sql_w[]="AGE>=$lage AND AGE<=$hage";
	}
	if($height)
	{
		$sql_w[]="HEIGHT>=$lheight AND HEIGHT<=$hheight";
	}
	if($search_income!='')
		$sql_w[]="INCOME IN ($search_income)";
			
	if(is_array($sql_w))
		$sql_fin=$sql_sel." AND ".@implode(' AND ',$sql_w);
	$sql_fin.=" ORDER BY LAST_LOGIN_DT DESC LIMIT 3";
	unset($sql_w);
//echo "<BR>****".$sql_fin;
	$res_sel=mysql_query($sql_fin,$db_slave) or die(mysql_error1($sql_fin,$db_slave));
	if(!mysql_num_rows($res_sel))
	{
		if($age)
		{
			$sql_w[]="AGE>=$lage AND AGE<=$hage";
		}
		if($height)
		{
			$sql_w[]="HEIGHT>=$lheight AND HEIGHT<=$hheight";
		}

		if($caste)
		{
			//$castes_arr=get_all_caste($caste1);
			$castes_arr=get_castes($caste1);
			$castes=implode(",",$castes_arr);
			if($castes)
				$sql_w[]="CASTE IN ($castes)";
		}
		if($mton)
		{
			if(in_array($mton,$all_hindi))
			{
				$sql_w[]="MTONGUE IN (10,19,33,7,28,13,41)";
			}
		}
		if(!(($gender=='M' && $age>45 && $mstat=='N') or ($gender=='F' && $age>40 && $mstat=='N')))
		{
		if($mstat!='')
		{
			if($mstat=='N')
				$sql_w[]="MSTATUS='N'";
			else
				$sql_w[]="MSTATUS IN ('S','D','O','W','A')";
		}
		}
		if($search_income!='')
	                $sql_w[]="INCOME IN ($search_income)";
		if(is_array($sql_w))
			$sql_fin=$sql_sel." AND ".@implode(' AND ',$sql_w);
		else
			$sql_fin=$sql_sel;
		$sql_fin.=" ORDER BY LAST_LOGIN_DT DESC LIMIT 3";
		unset($sql_w);
//echo "<BR>".$sql_fin;
		$res_sel=mysql_query($sql_fin,$db_slave) or die(mysql_error1($sql_fin,$db_slave));
	}
	if(mysql_num_rows($res_sel))
	{
		while($row_sel= mysql_fetch_array($res_sel))
		{
			$pid_arr[]=$row_sel['PROFILEID'];
		}
		$pids=implode(",",$pid_arr);
		unset($pid_arr);
		$i=0;
		$sql_info="SELECT PROFILEID,USERNAME,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,COUNTRY_RES,HAVEPHOTO,YOURINFO,FAMILYINFO,SPOUSE,SCREENING,PRIVACY,PHOTO_DISPLAY,INCOME,EDU_LEVEL_NEW,SUBSCRIPTION,MTONGUE,SUBCASTE,GOTHRA,NAKSHATRA,FATHER_INFO,SIBLING_INFO,RELIGION,PHOTOSCREEN FROM newjs.JPROFILE WHERE PROFILEID IN ($pids)";
		$res_info=mysql_query($sql_info,$db_slave) or die(mysql_error1($sql_info,$db_slave));
		while($row_info=mysql_fetch_array($res_info))
		{
			$pid=$row_info['PROFILEID'];

			$arr[$i]['photochecksum']=md5($pid + 5)."i".($pid + 5);
			$arr[$i]['photochecksum_new'] = intval(intval($pid)/1000) . "/" . md5($pid+5);
			$arr[$i]['profilechecksum']=md5($pid)."i".$pid;
			$arr[$i]['profileid']=$pid;
			$arr[$i]['photo']=$row_info['HAVEPHOTO'];
			$arr[$i]['username']=$row_info['USERNAME'];
			$arr[$i]['yourinfo']=$row_info["YOURINFO"];
			$arr[$i]['familyinfo']=$row_info["FAMILYINFO"];
			$arr[$i]['spouseinfo']=$row_info["SPOUSE"];
			$arr[$i]['screening']=$row_info["SCREENING"];
			$arr[$i]['age']=$row_info['AGE'];
			$arr[$i]['mtongue']=$MTONGUE_DROP[$row_info['MTONGUE']];
			$height=$HEIGHT_DROP[$row_info['HEIGHT']];
			$height1=explode(" (",$height);
			$arr[$i]['height']=$height1[0];
			if($row_info['CASTE']!='162')
			{
				$castes=explode(':',$CASTE_DROP[$row_info['CASTE']]);
				if($castes[1])
					$arr[$i]['caste']=$castes[1];
				elseif($castes[0])
					$arr[$i]['caste']=$castes[0];
			}
			$arr[$i]['occupation']=$OCCUPATION_DROP[$row_info['OCCUPATION']];
			$arr[$i]['city_res']=$row_info['CITY_RES'];
			if($arr[$i]['city_res']=='')
				$arr[$i]['country']=$row_info['COUNTRY_RES'];
			$arr[$i]['privacy']=$row_info['PRIVACY'];
			$arr[$i]['photo_display']=$row_info['PHOTO_DISPLAY'];
			$arr[$i]['income']=$INCOME_DROP[$row_info['INCOME']];
			$arr[$i]['edu']=$EDUCATION_LEVEL_NEW_DROP[$row_info['EDU_LEVEL_NEW']];
			$arr[$i]['subs']=$row_info['SUBSCRIPTION'];
			$arr[$i]['subcaste'] = $row_info["SUBCASTE"];
			$arr[$i]['nakshatra']=$row_info["NAKSHATRA"];
			$arr[$i]['religion']=$RELIGIONS[$row_info["RELIGION"]];
			$arr[$i]['yourinfo']=substr($arr[$i]['yourinfo'] . " " . $arr[$i]['familyinfo'] . " " . $arr[$i]['spouseinfo'],0,300);
			$arr[$i]['city_res']=$CITY_DROP[$arr[$i]['city_res']];
				if($arr[$i]['city_res']=='')
				{
					$arr[$i]['city_res']=$CITY_INDIA_DROP[$arr[$i]['city_res']];
				}
				if($arr[$i]['city_res']=='')
					$arr[$i]['city_res']=$COUNTRY_DROP[$arr[$i]['country']];
				$i++;
		}
			/*if($city_c)
			{
				$city=$CITY_DROP[$city_c];
				if($city=='')
					$city=$CITY_DROP[$city_c];
			}
			else
				$city='';*/
			$smarty->assign("DATA",$arr);
			unset($arr);
			switch($source)
			{
				case 4:
					if($news_date)
						$news="$newspaper dated $news_date";
					else
						$news=$newspaper;
					 $lead_line="This is in reference to your advertisement in $news. Please find below the bio data and photo of some of our esteemed members. If the particulars match your requirements, please provide us with similar details of $by  along with photos as it would help us in taking matters further.<br><br> To proceed further or to meet our clients in person, kindly visit our office at the address mention below or call $exe_info. Once you visit our office with the bio data and photo of $by, we will take your written consent to share your details with our clients. Besides that, we will also give two contact details of our clients you are interested in for FREE.<br><br>We have also provided bio data and photographs of other clients based on your desired partner profile.";
					break;
				case 1: $lead_line="This is in reference to your telephonic conversation with $exe during which you provided your details and the details of your desired partner. We are pleased to inform you that we have short listed certain profiles that match your criteria.<br><br>We hope that you would like these profiles and to proceed further or to meet our clients in person, kindly visit our office at the address mention below or call $exe_info. Once you visit our office with the bio data and photo of $by, we will take your written consent to share your details with our clients. Besides that, we will also give two contact details of our clients you are interested in for FREE.<br><br>We have also provided bio data and photographs of other clients based on your desired partner profile.";
					break;
				case 2: $lead_line="Thanks for visiting our centre $loc.<br><br>At Jeevansathi.com, we are dedicated towards providing a perfect match to $by1 and would like to share with you certain member profiles, which match your desired partner criteria.<br><br>We hope that you would like these profiles and to proceed further or to meet our clients in person, kindly visit our office at the address mention below or call $exe_info. Once you visit our office with the bio data and photo of $by, we will take your written consent to share your details with our clients. Besides that, we will also give two contact details of our clients you are interested in for FREE.<br><br>We have also provided bio data and photographs of other clients based on your desired partner profile.";
					$sql_walk="SELECT leads.*,leads_cstm.* FROM leads LEFT JOIN leads_cstm ON leads.id = leads_cstm.id_c WHERE leads.id = '$lead'";
					$res_walk=mysql_query($sql_walk,$db_slave) or die(mysql_error1($sql_walk,$db_slave));
					$row_walk=mysql_fetch_assoc($res_walk);
					/*$walk_info['photochecksum']=md5($off_pid + 5)."i".($off_pid + 5);
					$walk_info['photochecksum_new'] = intval(intval($off_pid)/1000) . "/" . md5($off_pid+5);
					$walk_info['profilechecksum']=md5($off_pid)."i".$off_pid;
					$walk_info['profileid']=$off_pid;
					$walk_info['photo']=$row_walk['HAVEPHOTO'];
				//	$walk_info['username']=$row_walk['last_name'];*/
					$walk_info['age']=$row_walk['age_c'];
					$walk_info['mtongue']=$MTONGUE_DROP[$row_walk['mother_tongue_c']];
					$height=$HEIGHT_DROP[$row_walk['height_c']];
					$height1=explode(" (",$height);
					$walk_info['height']=$height1[0];
					$caste_arr=explode("_",$row_walk['caste_c']);
					$caste=$caste_arr[1];
					if($caste!='162')
					{
						$castes=explode(':',$CASTE_DROP[$caste]);
						if($castes[1])
							$walk_info['caste']=$castes[1];
						elseif($castes[0])
							$walk_info['caste']=$castes[0];
					}
					$walk_info['occupation']=$OCCUPATION_DROP[$row_walk['occupation_c']];
					$walk_info['city_res']=$row_walk['city_c'];
					//$walk_info['privacy']=$row_walk['PRIVACY'];
					//$walk_info['photo_display']=$row_walk['PHOTO_DISPLAY'];
					$walk_info['income']=$INCOME_DROP[$row_walk['income_c']];
					$walk_info['edu']=$EDUCATION_LEVEL_NEW_DROP[$row_walk['education_c']];
					$walk_info['religion']=$RELIGIONS[$row_walk["religion_c"]];
					$walk_info['yourinfo']='';
					$walk_info['city_res']=$CITY_DROP[$walk_info['city_res']];
						if($walk_info['city_res']=='')
						{
							$walk_info['city_res']=$CITY_INDIA_DROP[$walk_info['city_res']];
						}
						if($walk_info['city_res']=='')
							$walk_info['city_res']=$COUNTRY_DROP[$walk_info['city_res']];

					$smarty->assign("walkin",$walk_info);
					unset($walk_info);

					break;
				case 9:
						$lead_line="We received $by2 bio data in response to our matrimonial advertisement / letter. The ad was posted by Jeevansathi.com Match Point on behalf of $news_info.<br><br>We think the following profiles may also interest you.";
						$smarty->assign('first_offline','N');
					$bt_line="To get the contact details or to meet any of the profiles above in person, kindly visit our office at the address mentioned below or call $exe_info. Once you visit our office with the bio data and photo of $by, we will take your written consent to share your details with our clients. Besides that, we will also give two contact details of our clients you are interested in for FREE.<br>"; 
					$smarty->assign('bt_line',$bt_line);
					break;
			}
			$sql_log="INSERT INTO LEAD_MATCHES_LOG(LEAD,DATE,MATCHES,EXE) VALUES ('$lead',now(),'$pids','$exe_id')";
			$res_log=mysql_query($sql_log,$db) or die(mysql_error1($sql_log,$db));
			$mid=mysql_insert_id($db);
			$smarty->assign('mid',$mid);
			$unsubscribe="$SITE_URL/sugarcrm/unsubscribe.php?id=$lead&source=lma";
			$smarty->assign('unsubscribe',$unsubscribe);
			$smarty->assign('lead_line',$lead_line);
			$msg = $smarty->fetch("lead_match_alert.htm");
			$from = "matchalert@jeevansathi.com";
			$subject="Jeevansathi.com has matches for you";
                        //echo $msg;
			//mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","leads matchalert",$msg);die;
                        $sent=sendmail($from,$email,$msg,$subject,'Jeevansathi.com',$reply);

		}
}

function get_castes($insert_caste)
{
	global $db_slave;
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
        return get_all_caste_revamp_js_db($insert_caste,$db_slave,1);
        //REVAMP JS_DB_CASTE
}

function mysql_error1($sql,$db)
{
	echo $msg=$sql."\n".mysql_error($db);
	mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Error in leads matchalert",$msg);
}
?>
