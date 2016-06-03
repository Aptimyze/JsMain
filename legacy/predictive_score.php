<?php
ini_set('max_execution_time','0');
include("connect.inc");
include("arrays.php");
$db=connect_slave();

$INTERCEPT=2.8481319510583400000;
$DAYS_REG=0.0151468209132166000;
$ACCEPTANCE=-0.0478457798951135000;
$LOGIN_CNT=0.0505118855091331000;
$PAID_CONTACTS=0.0315693466426211000;
$PROFILE_LENGTH=-0.0010050695339369000;
$SEARCHES=-0.0157094351248361000;
$INITIATED=-0.0070392406864277600;
$AGE=-0.0359185469777785000;
$MANGLIK['blank']=0.1495981424518710000;
$MANGLIK['YES']=-0.0829916182015052000;
$PHOTO_N=0.8157076627528530000;

$RELATION[4]=0.1157394303664360000;
$RELATION[5]=0.2455667897448290000;
$RELATION[6]=-0.3728169680907870000;
$RELATION[2]=-1.1932701067579600000;
$RELATION[1]=-0.5345100510381580000;
$RELATION["blank"]=2.9333589856564700000;
$GENDER["F"]=-0.66;
$GENDER["M"]=0.55;
$GENDER_AGE["F"]=-0.01;
$GENDER_AGE["M"]=-0.03;



$INCOME_PT=array(12=>-0.518502391658739,13=>0.216365304675116,14=>0.136403179718798,9=>-0.0722901198869459,10=>-0.599302143535545,11=>-0.669758436168531,15=>0.43386699322048,3=>0.488586947502854,18=>-0.746977889580652,4=>0.117507045988333,5=>-0.121465370398595,6=>-0.306041217909249,16=>-0.720474656480202,2=>0.910683818049562,17=>-0.72610198978494,8=>0.946231063459484);

$AGE_INCOME=array(12=>0.012609521822956700,13=>-0.002184927075648990,14=>0.036992284944293900,9=>0.016123476732593700,10=>0.026337267484525700,11=>0.019130758226702000,15=>-0.014124327213029300,3=>-0.020694260898343800,18=>0.003590525271444420,4=>-0.024120806159825700,5=>-0.016911836061640000,6=>-0.009031139516009460,16=>-0.008559475268619090,2=>-0.023600693651105700,17=>-0.006412791202788720,8=>0.026710524053011900);

//$db=connect_slave();
//mysql_select_db_js("newjs");

$sql="CREATE TABLE `TEMP_PREDICTIVE` (
 `PROFILEID` mediumint(11) NOT NULL default '0',
 `PROB` float default NULL,
 `SCORE` SMALLINT default '0',
 PRIMARY KEY  (`PROFILEID`)
)";
mysql_query_decide($sql);

$table=array("1"=>"INCOME");
foreach($table as $key=>$val)
{
	$sql="select SQL_CACHE LABEL,VALUE from $val";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_assoc($res))
		$TABLE[$val][$row['VALUE']]=$row['LABEL'];

}
unset($table);
	
	

$sql="select DATEDIFF(now(),ENTRY_DT) as DAYS_REG,length(concat(YOURINFO,FAMILYINFO,JOB_INFO,SIBLING_INFO,FATHER_INFO,SPOUSE)) as PROFILE_LENGTH,ENTRY_DT,USERNAME,PROFILEID,HAVEPHOTO,AGE,GENDER,MANGLIK,INCOME,RELATION from newjs.JPROFILE";

$res=mysql_query_decide($sql) or die(mysql_error_js());

while($data=mysql_fetch_array($res))
{
	


	if($data['MANGLIK']=='M')
		$MANGLIK_TYPE='YES';
	else 
		$MANGLIK_TYPE='blank';
		
	if($RELATION[$data['RELATION']]=="")
		$RELATION_TYPE='blank';
	else 
		$RELATION_TYPE=$data['RELATION'];
			
	
	$data['PAID']=0;
	$sql="select PROFILEID from newjs.USER_STARTS_PAYING where PROFILEID=".$data['PROFILEID'];
	$res2=mysql_query_decide($sql) or die(mysql_error_js());
	if($row=mysql_fetch_array($res2))
	{
		$data['PAID']=1;
	}
	
		
	
	$sql="select count(*) as cnt from MIS.SEARCHQUERY where PROFILEID=".$data['PROFILEID'];
	$res1=mysql_query_decide($sql) or die(mysql_error_js());	
	$row=mysql_fetch_row($res1);
	$data['SEARCHES']=$row[0];


	if($data['HAVEPHOTO']=='N')
		$data['PHOTO']=$PHOTO_N;
	else
		$data['PHOTO']=0;
	
	$profileid=$data['PROFILEID'];
	
	//To find the acceptance and initiated contact of particular user
    $acc_me="select count(*) as cnt from newjs.CONTACTS where SENDER=$profileid and TYPE='A'";
    $acc_other="select count(*) as cnt from newjs.CONTACTS where RECEIVER=$profileid and TYPE='A'";
    $ini_me="select count(*) as cnt from newjs.CONTACTS where SENDER=$profileid and TYPE='I'";
    $ini_other="select count(*) as cnt from newjs.CONTACTS where RECEIVER=$profileid and TYPE='I' ";

    $res_acc_me=mysql_query_decide($acc_me) or die(mysql_error_js());
    $res_acc_other=mysql_query_decide($acc_other) or die(mysql_error_js());
    $res_ini_me=mysql_query_decide($ini_me) or die(mysql_error_js());
    $res_ini_other=mysql_query_decide($ini_other) or die(mysql_error_js());

    $row_acc_me=mysql_fetch_row($res_acc_me);
    $row_acc_other=mysql_fetch_row($res_acc_other);
    $row_ini_me=mysql_fetch_row($res_ini_me);
    $row_ini_other=mysql_fetch_row($res_ini_other);

    $data['ACCEPTANCE']=intval($row_acc_me[0])+intval($row_acc_other[0]);
    $data['INITIATED']=intval($row_ini_me[0])+intval($row_ini_other[0]);
    //Fetching accepted contacts and initiated contacts ends here


	$sql6 = "SELECT COUNT(*)  AS CNT FROM newjs.CONTACTS c , newjs.JPROFILE j WHERE j.PROFILEID=c.SENDER AND c.RECEIVER = '$profileid' AND c.TYPE='I' AND j.SUBSCRIPTION <> ''";
	$res6 = mysql_query_decide($sql6) or  die(mysql_error_js());
    	$row6 = mysql_fetch_array($res6);
	$PAID_INITITATE_CNT = $row6['CNT'];

	$sql7 = "SELECT COUNT(*)  AS CNT FROM newjs.CONTACTS c , newjs.JPROFILE j WHERE j.PROFILEID=c.SENDER AND c.RECEIVER = '$profileid' AND c.TYPE='A' AND j.SUBSCRIPTION <> ''";
	       $res7 = mysql_query_decide($sql7) or  die(mysql_error_js());
	       $row7 = mysql_fetch_array($res7);
	       $PAID_RECEIVED_ACCPT_CNT = $row7['CNT'];

	$sql8 = "SELECT COUNT(*)  AS CNT FROM newjs.CONTACTS c , newjs.JPROFILE j WHERE j.PROFILEID=c.RECEIVER AND c.SENDER = '$profileid' AND c.TYPE='A' AND j.SUBSCRIPTION <> ''"; 
	$res8 = mysql_query_decide($sql8) or die(mysql_error_js());
	$row8 = mysql_fetch_array($res8);
	        $PAID_SENT_ACCPT_CNT = $row8['CNT'];
	
	$data['PAID_CONTACTS']=intval($PAID_INITITATE_CNT)+intval($PAID_RECEIVED_ACCPT_CNT)+intval($PAID_SENT_ACCPT_CNT);

	$pid=$data['PROFILEID'];
	$sql1 = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid'";
	$res1 = mysql_query_decide($sql1) or  die(mysql_error_js());
	$row1 = mysql_fetch_row($res1);
	$data['LOGIN_COUNT']=$row1[0];
	
	$sql1 = "SELECT TOTAL_COUNT FROM newjs.LOGIN_HISTORY_COUNT WHERE PROFILEID = '$pid'";
	$res1 = mysql_query_decide($sql1) or  die(mysql_error_js());
	$row1 = mysql_fetch_row($res1);
	
	$data['LOGIN_COUNT']+=$row1[0];
	
	
	//Getting the User score .
	$sql_score="select SCORE from incentive.MAIN_ADMIN_POOL where PROFILEID='".$data['PROFILEID']."'";
	$res_score=mysql_query_decide($sql_score) or die(mysql_error_js());
	if($row_score=mysql_fetch_row($res_score))
	{
		 $score=$row_score[0];
	}
	else
 	{
		$source=$data['SOURCE'];
		$entry_dt=$data["ENTRY_DT"];
		$photo_dt=$data["PHOTODATE"];
	
		// query to find the first date in an interval of 30 days when the user logged in
		$login_cnt=$data['LOGIN_COUNT'];
	
		// query to find the count of contacts initiated
		$sql_init_cnt ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' ";
		$res4_i = mysql_query_decide($sql_init_cnt) or die(mysql_error_js());
		$row4_i = mysql_fetch_array($res4_i);
		$INITIATE_CNT= $row4_i['CNT4'];
	
		// query to find the count of contacts accepted
		
		$sql_accpt_cnt="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER='$pid' and TYPE='A' ";
		$result=mysql_query_decide($sql_accpt_cnt) or die(mysql_error_js());
		$myrow_a=mysql_fetch_array($result);
		$ACCEPTANCE_MADE = $myrow_a["CNT"];
		$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;
	
		$PROFILELENGTH =$data['PROFILE_LENGTH'];
		
		$score = calc_user_score($data['AGE'],$data['GENDER'],$PROFILELENGTH , $data['HAVEPHOTO'], $data['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
		
		
	}

	$data['SCORE']=$score;


//         $sql2 ="SELECT COUNT(*) AS CNT2 FROM newjs.CONTACTS WHERE SENDER = '$pid'";
//         $res2 = mysql_query_decide($sql2) or die(mysql_error_js());
//         $row2 = mysql_fetch_array($res2);
//         $data['CONTACT_INITIATED']=$row2['CNT2'];
	//print_r($data);
	unset($data['CITY_RES']);
	unset($data['COUNTRY_RES']);
	unset($data['MTONGUE']);
	unset($data['SHOW_HOROSCOPE']);
	unset($data['CONTACT_INITIATED']);
	unset($data['HAVEPHOTO']);
	unset($data['ENTRY_DT']);
	unset($data['PHOTODATE']);

	if($data['ACCEPTANCE']<=0 || $data['ACCEPTANCE']=="")
			$data['ACCEPTANCE']=0;
		
		if($INCOME_PT[$data['INCOME']]=="")
			$INCOME_PT[$data['INCOME']]=0;
		
		if($AGE_INCOME[$data['INCOME']]=="")
			$AGE_INCOME[$data['INCOME']]=0;
			
			//echo $INTERCEPT ."--<BR>".$data['DAYS_REG']*$DAYS_REG ."DAYS REG--<BR>".$data['ACCEPTANCE']*$ACCEPTANCE ."ACCEPTANCE--<BR>".$data['LOGIN_CNT']*$LOGIN_CNT ."LOGIN_CNT--<BR>".$data['PAID_CONTACTS']*$PAID_CONTACTS ."PAID_CONTACTS--<BR>".$data['PROFILE_LENGTH']*$PROFILE_LENGTH ."PROFILE_LENGTH--<BR>".$data['SEARCHES']*$SEARCHES ."SEARCHES--<BR>".$data['INITIATED']*$INITIATED ."INITIATED--<BR>".$data['AGE']*$AGE ."AGE--<BR>".$MANGLIK[$MANGLIK_TYPE] ."MANGLIK--<BR>".$data['PHOTO'] ."PHOTO--<BR>".$RELATION[$RELATION_TYPE] ."RELATION--<BR>".$GENDER[$data['GENDER']] ."GENDER--<BR>".($data['AGE']-28.1545)*$GENDER_AGE[$data['GENDER']] ."AGE_GENDER--<BR>". $INCOME_PT[$data['INCOME']] ."INCOME--<BR>".($data['AGE']-28.1545)*$AGE_INCOME[$data['INCOME']]."age income";
			
		$total=$INTERCEPT +$data['DAYS_REG']*$DAYS_REG +$data['ACCEPTANCE']*$ACCEPTANCE +$data['LOGIN_CNT']*$LOGIN_CNT +$data['PAID_CONTACTS']*$PAID_CONTACTS +$data['PROFILE_LENGTH']*$PROFILE_LENGTH +$data['SEARCHES']*$SEARCHES +$data['INITIATED']*$INITIATED +$data['AGE']*$AGE +$MANGLIK[$MANGLIK_TYPE] +$data['PHOTO'] +$RELATION[$RELATION_TYPE] +$GENDER[$data['GENDER']] +($data['AGE']-28.1545)*$GENDER_AGE[$data['GENDER']] + $INCOME_PT[$data['INCOME']] +($data['AGE']-28.1545)*$AGE_INCOME[$data['INCOME']];
		//echo 1*600/$total."<BR>";
	  $prob=1/(1+exp($total))	;
	  $prob=$prob*600;
	  
	  //$prob=round($prob,7);if($prob==0)
	  	//echo "HI";
	  $sql="insert into TEMP_PREDICTIVE(PROFILEID,PROB,SCORE) values('$profileid','$prob','$score')";
	  mysql_query_decide($sql);
	unset($data);
	
}
function get_value($table,$val)	
{

	global $TABLE;
	return $TABLE[$table][$val];
/*	$sql="select SQL_CACHE LABEL from newjs.$table where VALUE='$val'";
	$get_res=mysql_query_decide($sql);
	$row=mysql_fetch_row($get_res);
	return $row[0];*/
}
function status($val)
{
	$arr_status=array("A"=>"Activated","Y"=>"Activated","N"=>"Going for screening","U"=>"Under Screening","H"=>"Profile hide","D"=>"Deleted");
	return ($arr_status[$val]);
}
function calc_user_score($age,$gender,$profilelength,$photo,$postedby,$reg_dt,$login_cnt,$contact_cnt)
{
        $user_score = 0;

        if ($gender == 'F')
        {
                if ($age >= 28)
                {
                        $user_score += 300;
                }
                elseif ($age >= 23 && $age <= 27)
                {
                        $user_score += 200;
                }
                elseif ($age <= 22)
                {
                        $user_score += 100;
                }
        }
        else
        {
                if ($age >= 31)
                {
                        $user_score += 200;
		}
                elseif ($age >= 25 && $age <= 30)
                {
                        $user_score += 150;
                }
                elseif ($age < 25)
                {
                        $user_score += 25;
                }
        }

        if ($profilelength >= 1000)
        {
                $user_score += 75;
        }
        elseif ($profilelength >= 600 && $profilelength < 1000)
        {
                $user_score += 50;
        }
        elseif ($profilelength < 600)
        {
                $user_score += 0;
        }                                        

        if ($photo == 'Y')
                $user_score += 200;
        else//if ($photo ==  'N')
                $user_score += 100;

        if ($postedby == '2' || $postedby == '3')
        {
                $user_score+= 250;
        }
        elseif ($postedby == '1')
        {
                $user_score+= 150;
        }
        else
                $user_score+= 25;

	$today_ts = mktime(23,59,59,date("m"),date("d"),date("Y"));
	//$today_ts = mktime(23,59,59,date("m"),date("d")-1,date("Y"));

	list($yy,$mm,$dd)=explode("-",substr($reg_dt,0,10));
	
        $regn_ts = mktime(0,0,0,$mm,$dd,$yy);

	$days_diff = intval(($today_ts - $regn_ts)/(24*60*60));

	if ($days_diff > 30)
		$days_in_sys = 30;
	else
		$days_in_sys = $days_diff;

        if ($days_in_sys && $login_cnt)
	{
		$login_freq = $login_cnt/$days_in_sys;
		if ($login_freq >= 0.4)
		{
			$user_score+=250;
		}
		elseif ($login_freq >= 0.2 && $login_freq < 0.4)
		{
			$user_score+=150;
		}
		else
			$user_score+=0;
        }

	if ($days_in_sys && $contact_cnt)
	{
		$contact_freq = $contact_cnt/$days_in_sys;
        	if ($contact_freq >= 0.8)
        	{
                	$user_score+= 175;
        	}
        	elseif ($contact_freq >=0.2 && $contact_freq < 0.8)
        	{
                	$user_score+= 125;
        	}
        	else
                	$user_score+= 0;
	}

	$user_score = round(($user_score*600)/1250);

        return $user_score;
}


?>
