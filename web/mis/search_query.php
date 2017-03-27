<?php
//include("connect.inc");		commented by shakti
include_once("connect.inc");
include("../profile/arrays.php");
//search_query.php
//MADE BY PUNEET MAKKAR TO KNOW THE NO OF USERS doing different type of searches 
$db=connect_misdb();

if(authenticated($cid) || $JSIndicator==1)
{
	if($CMDGo)
	{
		$smarty->assign("flag",1);

		if($day)
		{
			$st_date=$year."-".$month."-".$day." 00:00:00";
			$end_date=$eyear."-".$emonth."-".$eday." 23:59:59";
		}
		else
		{
			$st_date=$year."-".$month."-01 00:00:00";
			$end_date=$eyear."-".$emonth."-31 23:59:59";
		}

		$sql="SELECT COUNT(*) as count ,SEARCH_TYPE  FROM MIS.SEARCHQUERY  WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY SEARCH_TYPE";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		
		while($row=mysql_fetch_array($res))
		{
			$count[]=$row['count'];
			$searchtypes[]=$row['SEARCH_TYPE'];
		}

		$total=0;
		for($i=0;$i<count($count);$i++)
			$total+=$count[$i];
		$smarty->assign("searchtypes",$searchtypes);
		$smarty->assign("total",$total);
		$smarty->assign("count",$count);

/*************************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       20 December, 2005
                        Reason          :       This was needed for stopping further execution of this script whenever
                                        :       indicator_mis.php was used to obtain data
*************************************************************************************************************************/
                if($JSIndicator==1)
                {
                        return;
                }
/**************************************End of Addition********************************************************************/ 	
	
		if(!$field)
		{
			$sql="SELECT COUNT(*) AS cnt ,PROFILEID FROM MIS.SEARCHQUERY  WHERE DATE BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$sone=0;
			$stwofive=0;
			$sfiveten=0;
			$sten=0;
			while($row=mysql_fetch_array($res))
			{	
				if($row['PROFILEID']==0)
					$logout=$row['cnt'];
				else
				{
					if($row['cnt']==1)
						$sone+=1;
					elseif($row['cnt']>1 && $row['cnt']<6)
						$stwofive+=1;
					elseif($row['cnt']>5 && $row['cnt']<11)
						$sfiveten+=1;
					elseif($row['cnt']>10)
						$sten+=1;	
				}
			
			}
			$stotal=$sone+$stwofive+$sfiveten+$sten;	
			$smarty->assign("stotal",$stotal);
			if($stotal!=0)
			{	$sonep=substr(($sone*100)/($stotal),0,5);	
				$stwofivep=substr(($stwofive*100)/($stotal),0,5);	
				$sfivetenp=substr(($sfiveten*100)/($stotal),0,5);	
				$stenp=substr(($sten*100)/($stotal),0,5);	
			}
			/***********************************************************************/
			
			$sql="select CASTE,MTONGUE,WITHPHOTO,MANGLIK,MSTATUS,CHILDREN,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,HANDICAPPED,OCCUPATION,COUNTRY_RES,CITY_RES,RES_STATUS,EDU_LEVEL,KEYWORD,PHOTOBROWSE,ONLINE,FRESHNESS,INCOME,LAGE,LHEIGHT FROM MIS.SEARCHQUERY WHERE DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				if($row['CASTE']!='')
					$caste++;
				if($row['MTONGUE']!='')
					$mtongue++;
				if($row['WITHPHOTO']!='')
					$withphoto++;
				if($row['MANGLIK']!='')
					$manglik++;
				if($row['MSTATUS']!='')
					$mstatus++;
				if($row['CHILDREN']!='')
					$children++;
				if($row['BTYPE']!='')
					$btype++;
				if($row['COMPLEXION']!='')
					$complexion++;
				if($row['DIET']!='')
					$diet++;
				if($row['SMOKE']!='')
					$smoke++;
				if($row['DRINK']!='')
					$drink++;
				if($row['HANDICAPPED']!='')
					$handicapped++;
				if($row['OCCUPATION']!='')
					$occupation++;
				if($row['COUNTRY_RES']!='')
					$country_res++;
				if($row['CITY_RES']!='')
					$city_res++;
				if($row['RES_STATUS']!='')
					$res_status++;
				if($row['EDU_LEVEL']!='')
					$edu_level++;
				if($row['KEYWORD']!='')
					$keyword++;
				if($row['PHOTOBROWSE']!='')
					$photobrowse++;
				if($row['ONLINE']!='')
					$online++;
				if($row['FRESHNESS']!='')
					$freshness++;
				if($row['INCOME']!='')
					$income++;
				if($row['LAGE']!='0')
					$age++;
				if($row['LHEIGHT']!='0')
					$height++;
			}	
			if($total!='0')
			{
				$castep=substr(($caste*100)/$total,0,5);	
				$mtonguep=substr(($mtongue*100)/$total,0,5);	
				$withphotop=substr(($withphoto*100)/$total,0,5);	
				$manglikp=substr(($manglik*100)/$total,0,5);	
				$mstatusp=substr(($mstatus*100)/$total,0,5);	
				$childrenp=substr(($children*100)/$total,0,5);	
				$btypep=substr(($btype*100)/$total,0,5);	
				$complexionp=substr(($complexion*100)/$total,0,5);	
				$dietp=substr(($diet*100)/$total,0,5);	
				$smokep=substr(($smoke*100)/$total,0,5);	
				$drinkp=substr(($drink*100)/$total,0,5);	
				$handicappedp=substr(($handicapped*100)/$total,0,5);	
				$occupationp=substr(($occupation*100)/$total,0,5);	
				$country_resp=substr(($country_res*100)/$total,0,5);	
				$city_resp=substr(($city_res*100)/$total,0,5);	
				$res_statusp=substr(($res_status*100)/$total,0,5);	
				$edu_levelp=substr(($edu_level*100)/$total,0,5);	
				$keywordp=substr(($keyword*100)/$total,0,5);	
				$photobrowsep=substr(($photobrowse*100)/$total,0,5);	
				$onlinep=substr(($online*100)/$total,0,5);	
				$freshnessp=substr(($freshness*100)/$total,0,5);	
				$incomep=substr(($income*100)/$total,0,5);	
                                $agep=substr(($age*100)/$total,0,5);
                                $heightp=substr(($height*100)/$total,0,5);
			}
			/***********************************************************************/
			
			/*
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE CASTE!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$caste=$row['cnt'];
			if($total!=0)
				$castep=substr(($caste*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE MTONGUE!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$mtongue=$row['cnt'];
			if($total!=0)
				$mtonguep=substr(($mtongue*100)/$total,0,5);	
			
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE WITHPHOTO!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$withphoto=$row['cnt'];
			if($total!=0)
				$withphotop=substr(($withphoto*100)/$total,0,5);	
					
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE MANGLIK!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$manglik=$row['cnt'];
			if($total!=0)
				$manglikp=substr(($manglik*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE MSTATUS!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$mstatus=$row['cnt'];
			if($total!=0)
				$mstatusp=substr(($mstatus*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE CHILDREN!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$children=$row['cnt'];
			if($total!=0)
				$childrenp=substr(($children*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE BTYPE!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$btype=$row['cnt'];
			if($total!=0)
				$btypep=substr(($btype*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE COMPLEXION!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$complexion=$row['cnt'];
			if($total!=0)
				$complexionp=substr(($complexion*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE DIET!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$diet=$row['cnt'];
			if($total!=0)
				$dietp=substr(($diet*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE SMOKE!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$smoke=$row['cnt'];
			if($total!=0)
				$smokep=substr(($smoke*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE DRINK!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$drink=$row['cnt'];
			if($total!=0)
				$drinkp=substr(($drink*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE HANDICAPPED!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$handicapped=$row['cnt'];
			if($total!=0)
				$handicappedp=substr(($handicapped*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE OCCUPATION!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$occupation=$row['cnt'];
			if($total!=0)
				$occupationp=substr(($occupation*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE COUNTRY_RES!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$country_res=$row['cnt'];
			if($total!=0)
				$country_resp=substr(($country_res*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE CITY_RES!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$city_res=$row['cnt'];
			if($total!=0)
				$city_resp=substr(($city_res*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE RES_STATUS!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$res_status=$row['cnt'];
			if($total!=0)
				$res_statusp=substr(($res_status*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE EDU_LEVEL!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$edu_level=$row['cnt'];
			if($total!=0)
				$edu_levelp=substr(($edu_level*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE KEYWORD!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$keyword=$row['cnt'];
			if($total!=0)
				$keywordp=substr(($keyword*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE PHOTOBROWSE!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$photobrowse=$row['cnt'];
			if($total!=0)
				$photobrowsep=substr(($photobrowse*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE ONLINE!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$online=$row['cnt'];
			if($total!=0)
				$onlinep=substr(($online*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE FRESHNESS!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$freshness=$row['cnt'];
			if($total!=0)
				$freshnessp=substr(($freshness*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE INCOME!='' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$income=$row['cnt'];
			if($total!=0)
				$incomep=substr(($income*100)/$total,0,5);	
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE LAGE!='0' AND DATE BETWEEN '$st_date' AND '$end_date'";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        $row=mysql_fetch_array($res);
                        $age=$row['cnt'];
                        if($total!=0)
                                $agep=substr(($age*100)/$total,0,5);
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE LHEIGHT!='0' AND DATE BETWEEN '$st_date' AND '$end_date'";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        $row=mysql_fetch_array($res);
                        $height=$row['cnt'];
                        if($total!=0)
                                $heightp=substr(($height*100)/$total,0,5);
			*/
		}		

		@mysql_ping_js($db);

		if($field=='gender')
		{ 	$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE GENDER='M' AND DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_array($res);
			$genderm=$row['cnt'];
			if($total!=0)
			{	$mpercent=substr(($genderm*100)/$total,0,5);
				$fpercent=substr((($total-$genderm)*100)/$total,0,5);
				$smarty->assign("mpercent",$mpercent);
				$smarty->assign("fpercent",$fpercent);
			}
			$smarty->assign("genderm",$genderm);
			$smarty->assign("genderf",$total-$genderm);
		
		}
		@mysql_ping_js($db);

		if($field=='mtongue')
		{	
			$sql="select MTONGUE FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        for($i=1;$i<=34;$i++)
				$mvalue[$i]=0; 
			
			while($row=mysql_fetch_array($res))
			{
				if(strstr($row['MTONGUE'],','))
				{	
					$marr=explode(',',$row['MTONGUE']);		
					for($i=0;$i<count($marr);$i++)
					{	$tmp=$marr[$i];	
						$mvalue[$tmp]=$mvalue[$tmp]+1;	
					}
				}
				else
					$mvalue[$row['MTONGUE']]=$mvalue[$row['MTONGUE']]+1;
			
			}
			for($c=0;$c<=count($mvalue);$c++)
				$mtotal+=$mvalue[$c];
			@mysql_ping_js($db);

			$sql="SELECT SMALL_LABEL FROM newjs.MTONGUE";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        $j=1;
			while($row=mysql_fetch_array($res))
			{	 
				if($mtotal!=0)
				$onefield[]=array("small_label"=>$row['SMALL_LABEL'],"cnt"=>$mvalue[$j],"percent"=>substr(($mvalue[$j]*100)/$mtotal,0,5));
				$j++;
			}
		}
	
		@mysql_ping_js($db);

		if($field=='caste')
                {
                        $sql="select CASTE FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['CASTE'],','))
                                {
                                        $marr=explode(',',$row['CASTE']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['CASTE']]=$mvalue[$row['CASTE']]+1;
                                                                                                                             
                        }
                        //print_r($mvalue);
			@mysql_ping_js($db);

			$sql="SELECT LABEL,VALUE FROM newjs.CASTE";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {	$castearr[]=$row['VALUE'];
				$castelab[]=$row['LABEL'];
			}
			for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=221;$k++)
			{	$key = array_search($k, $castearr);
				//if($key!='')
				if($mtotal!=0) 
					$onefield[]=array("small_label"=>$castelab[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
			}
		}
		
		@mysql_ping_js($db);

		if($field=='occupation')
                {
                        $sql="select OCCUPATION FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['OCCUPATION'],','))
                                {
                                        $marr=explode(',',$row['OCCUPATION']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['OCCUPATION']]=$mvalue[$row['OCCUPATION']]+1;
                                                                                                                             
                        }
                        //print_r($mvalue);
			@mysql_ping_js($db);

                        $sql="SELECT LABEL,VALUE FROM newjs.OCCUPATION";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       $castearr[]=$row['VALUE'];
                                $castelab[]=$row['LABEL'];
                        }
                        for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=42;$k++)
                        {       $key = array_search($k, $castearr);
                                //if($key!='')
				if($mtotal!=0) 
                                        $onefield[]=array("small_label"=>$castelab[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='country_res')
                {
                        $sql="select COUNTRY_RES FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['COUNTRY_RES'],','))
                                {
                                        $marr=explode(',',$row['COUNTRY_RES']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['COUNTRY_RES']]=$mvalue[$row['COUNTRY_RES']]+1;
                                                                                                                             
                        }
                        //print_r($mvalue);
			@mysql_ping_js($db);

                        $sql="SELECT LABEL,VALUE FROM newjs.COUNTRY";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       $castearr[]=$row['VALUE'];
                                $castelab[]=$row['LABEL'];
                        }
                        for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=136;$k++)
                        {       $key = array_search($k, $castearr);
                                //if($key!='')
                               	if($mtotal!=0)   
					$onefield[]=array("small_label"=>$castelab[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
	
		@mysql_ping_js($db);

		if($field=='res_status')
                {
                        $sql="select RES_STATUS FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['RES_STATUS'],','))
                                {
                                        $marr=explode(',',$row['RES_STATUS']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['RES_STATUS']]=$mvalue[$row['RES_STATUS']]+1;
                                                                                                                             
                        }
 			for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];
                      
                        for($k=1;$k<=5;$k++)
                        {       $key = array_search($RSTATUS[$k], $RSTATUS);
                                if($key!='')
                                if($mtotal!=0) 
				$onefield[]=array("small_label"=>$RSTATUS[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
	
		@mysql_ping_js($db);

		if($field=='edu_level')
                {
                        $sql="select EDU_LEVEL FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['EDU_LEVEL'],','))
                                {
                                        $marr=explode(',',$row['EDU_LEVEL']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['EDU_LEVEL']]=$mvalue[$row['EDU_LEVEL']]+1;
                                                                                                                             
                        }
			@mysql_ping_js($db);

                        $sql="SELECT LABEL,VALUE FROM newjs.EDUCATION_LEVEL";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       $castearr[]=$row['VALUE'];
                                $castelab[]=$row['LABEL'];
                        }
                       	for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=6;$k++)
                        {       $key = array_search($k, $castearr);
				//if($key!='')
                                if($mtotal!=0)         
					$onefield[]=array("small_label"=>$castelab[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
			//print_r($onefield);
                                     
                }
		
		@mysql_ping_js($db);

		if($field=='income')
                {
                        $sql="select INCOME FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['INCOME'],','))
                                {
                                        $marr=explode(',',$row['INCOME']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['INCOME']]=$mvalue[$row['INCOME']]+1;
                                                                                                                             
                        }
                        //print_r($mvalue);
                        for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];
			@mysql_ping_js($db);

			$sql="SELECT LABEL,VALUE FROM newjs.INCOME";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       $castearr[]=$row['VALUE'];
                                $castelab[]=$row['LABEL'];
                        }
                        for($k=1;$k<=18;$k++)
                        {       $key = array_search($k, $castearr);
                                //if($key!='')
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$castelab[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		@mysql_ping_js($db);

                if($field=='manglik')
                {
                        $sql="select MANGLIK FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['MANGLIK']=='M')
                                	$mvalue[1]=$mvalue[1]+1;
                                elseif($row['MANGLIK']=='N')
					$mvalue[2]=$mvalue[2]+1;
				elseif($row['MANGLIK']=='D')
					$mvalue[3]=$mvalue[3]+1;          	                                                                     }
                       	for($c=0;$c<=3;$c++)
                                $mtotal+=$mvalue[$c];
                                                                                                      
                        $MANGLIK=array("1" => "Manglik","2" => "Non Manglik","3" => "Don't know");
			for($k=1;$k<=3;$k++)
                        {       
                        	if($mtotal!=0) 
					$onefield[]=array("small_label"=>$MANGLIK[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='mstatus')
                {
                        $sql="select MSTATUS FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['MSTATUS']=='N')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['MSTATUS']=='W')                                         
					$mvalue[2]=$mvalue[2]+1;                                 
				elseif($row['MSTATUS']=='D')                                         
					$mvalue[3]=$mvalue[3]+1;
				elseif($row['MSTATUS']=='S')
                                        $mvalue[4]=$mvalue[4]+1; 
				elseif($row['MSTATUS']=='O')
                                        $mvalue[5]=$mvalue[5]+1;                                                                                     }
                        $MSTATUS=array("1" => "Never Married","2" => "Widowed","3" => "Divorced","4" => "Separated","5" => "Other");
        		for($c=0;$c<=5;$c++)
                                $mtotal+=$mvalue[$c];
                
			for($k=1;$k<=5;$k++)
                        {
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$MSTATUS[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='btype')
                {
                        $sql="select BTYPE  FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['BTYPE'],','))
                                {
                                        $marr=explode(',',$row['BTYPE']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['BTYPE']]=$mvalue[$row['BTYPE']]+1;
                                                                                                                             
                        }
			for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];
                                                                                                                             
                        for($k=1;$k<=4;$k++)
                        {       $key = array_search($BODYTYPE[$k], $BODYTYPE);
                                if($key!='')
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$BODYTYPE[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='complexion')
                {
                        $sql="select COMPLEXION FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['COMPLEXION'],','))
                                {
                                        $marr=explode(',',$row['COMPLEXION']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['COMPLEXION']]=$mvalue[$row['COMPLEXION']]+1;
                                                                                                                             
                        }
			for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];
                                                                                                                             
                        for($k=1;$k<=5;$k++)
                        {       $key = array_search($COMPLEXION[$k], $COMPLEXION);
                                //if($key!='')
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$COMPLEXION[$key],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }

		@mysql_ping_js($db);

		if($field=='diet')
                {
                        $sql="select DIET FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['DIET']=='V')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['DIET']=='N')
                                        $mvalue[2]=$mvalue[2]+1;
                                elseif($row['DIET']=='J')
                                        $mvalue[3]=$mvalue[3]+1;
                        }
			
			$DIET=array("1" => "Vegetarian","2" => "Non Vegetarian","3" => "Jain");
			for($c=0;$c<=3;$c++)
                                $mtotal+=$mvalue[$c];
			for($k=1;$k<=3;$k++)
                        {
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$DIET[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
	
		@mysql_ping_js($db);

		if($field=='smoke')
                {
                        $sql="select SMOKE FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['SMOKE']=='Y')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['SMOKE']=='N')
                                        $mvalue[2]=$mvalue[2]+1;
                                elseif($row['SMOKE']=='0')
                                        $mvalue[3]=$mvalue[3]+1;
                        }	
			$SMOKE=array("1" => "Yes","2" => "No","3" => "Occasionally");
		        for($c=0;$c<=3;$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=3;$k++)
                        {
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$SMOKE[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='drink')
                {
                        $sql="select DRINK FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['DRINK']=='Y')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['DRINK']=='N')
                                        $mvalue[2]=$mvalue[2]+1;
                                elseif($row['DRINK']=='0')
                                        $mvalue[3]=$mvalue[3]+1;
                        }
                        $DRINK=array("1" => "Yes","2" => "No","3" => "Occasionally");
	                for($c=0;$c<=3;$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=3;$k++)                         
			{
                               if($mtotal!=0) 
				 $onefield[]=array("small_label"=>$DRINK[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='handicapped')
                {
                        $sql="select HANDICAPPED FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['HANDICAPPED']=='N')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['HANDICAPPED']=='1')
                                        $mvalue[2]=$mvalue[2]+1;
                                elseif($row['HANDICAPPED']=='2')
                                        $mvalue[3]=$mvalue[3]+1;
				elseif($row['HANDICAPPED']=='3')
                                        $mvalue[4]=$mvalue[4]+1;
				elseif($row['HANDICAPPED']=='4')
                                        $mvalue[5]=$mvalue[5]+1;
			}
                         $HANDICAPPED=array("1" => "None","2" => "Physically Handicapped from birth","3" => "Physically Handicapped due to accident","4" => "Mentally Challenged from birth","5" => "Mentally Challenged due to accident");
			for($c=0;$c<=5;$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=5;$k++)
                        {
                                if($mtotal!=0) 
					$onefield[]=array("small_label"=>$HANDICAPPED[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
	
		@mysql_ping_js($db);

		if($field=='children')
                {
                        $sql="select CHILDREN FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['CHILDREN']=='N')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['CHILDREN']=='1')
                                        $mvalue[2]=$mvalue[2]+1;
                                elseif($row['CHILDREN']=='2')
                                        $mvalue[3]=$mvalue[3]+1;
                                elseif($row['CHILDREN']=='3')
                                        $mvalue[4]=$mvalue[4]+1;
                        }
			$CHILDREN=array("1" => "No","2" => "Yes, living together","3" => "Yes, living separately","4" => "Yes");
                        for($c=0;$c<=4;$c++)
                                $mtotal+=$mvalue[$c];
			for($k=1;$k<=4;$k++)
                        {
                           if($mtotal!=0) 
					     $onefield[]=array("small_label"=>$CHILDREN[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
		
		@mysql_ping_js($db);

		if($field=='withphoto')
                {
                        $sql="select WITHPHOTO FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if($row['WITHPHOTO']=='Y')
                                        $mvalue[1]=$mvalue[1]+1;
                                elseif($row['WITHPHOTO']=='N')
                                        $mvalue[2]=$mvalue[2]+1;
                        }
                        $PHOTO=array("1" => "Yes","2" => "NO");
                        for($c=0;$c<=2;$c++)
                                $mtotal+=$mvalue[$c];

			for($k=1;$k<=2;$k++)
                        {
                               if($mtotal!=0) 
				 $onefield[]=array("small_label"=>$PHOTO[$k],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                }
                                                                                                                             
		@mysql_ping_js($db);

		if($field=='age')
		{
			$sql="select LAGE,HAGE FROM  MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {	//if($row['LAGE']!=0 || $row['HAGE']!=0)
				for($i=$row['LAGE'];$i<=$row['HAGE'];$i++)
					$mvalue[$i]=$mvalue[$i]+1;
			}
			for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];

			for($k=18;$k<=70;$k++)
			{
                        	if($mtotal!=0) 
					$onefield[]=array("small_label"=>$k,"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
			}
		
		}
	
		@mysql_ping_js($db);

		if($field=='height')
                {
                        $sql="select LHEIGHT,HHEIGHT FROM  MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       //if($row['LAGE']!=0 || $row['HAGE']!=0)
                                for($i=$row['LHEIGHT'];$i<=$row['HHEIGHT'];$i++)
                                        $mvalue[$i]=$mvalue[$i]+1;
                        }
                        $sql="SELECT LABEL,VALUE FROM newjs.HEIGHT";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        for($c=0;$c<=count($mvalue);$c++)
                                $mtotal+=$mvalue[$c];

			while($row=mysql_fetch_array($res))
                        {       //$castearr[]=$row['VALUE'];
                                $castelab[]=$row['LABEL'];
			}
			for($k=1;$k<=32;$k++)
                        {
				if($mtotal!=0) 
				$onefield[]=array("small_label"=>$castelab[$k-1],"cnt"=>$mvalue[$k],"percent"=>substr(($mvalue[$k]*100)/$mtotal,0,5));
                        }
                                                                                                                             
                }

		@mysql_ping_js($db);

		if($field=='city_res')
                {
                        $sql="select CITY_RES FROM MIS.SEARCHQUERY WHERE  DATE BETWEEN '$st_date' AND '$end_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        //for($i=1;$i<=221;$i++)
                          //      $mvalue[$i]=0;
                        while($row=mysql_fetch_array($res))
                        {
                                if(strstr($row['CITY_RES'],','))
                                {
                                        $marr=explode(',',$row['CITY_RES']);
                                        for($i=0;$i<count($marr);$i++)
                                        {       $tmp=$marr[$i];
                                                $mvalue[$tmp]=$mvalue[$tmp]+1;
                                        }
                                }
                                else
                                        $mvalue[$row['CITY_RES']]=$mvalue[$row['CITY_RES']]+1;
                                                                                                                             
                        }
			@mysql_ping_js($db);

                        //print_r($mvalue);
                        $sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       $castearr[]=$row['VALUE'];
                                $castelab[]=$row['LABEL'];
                        }
                        $sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {       $usaarr[]=$row['VALUE'];
                                $usalab[]=$row['LABEL'];
                        }
			for($c=0;$c<count($castearr);$c++)
                                $mtotali+=$mvalue[$castearr[$c]];

			for($c=0;$c<count($usaarr);$c++)
                                $mtotalu+=$mvalue[$usaarr[$c]];
			
			for($k=0;$k<count($castearr);$k++)
                        {       $key = array_search($castearr[$k], $castearr);
                                //if($key!='')
                                if($mtotali!=0)
			         $onefield[]=array("small_label"=>$castelab[$key],"cnt"=>$mvalue[$castearr[$k]],"percent"=>substr(($mvalue[$castearr[$k]]*100)/$mtotali,0,5));
                        }
                	for($k=0;$k<count($usaarr);$k++)
                        {       $key = array_search($usaarr[$k], $usaarr);
                                //if($key!='')
                         if($mtotalu!=0)
		                $usafield[]=array("small_label"=>$usalab[$key],"cnt"=>$mvalue[$usaarr[$k]],"percent"=>substr(($mvalue[$usaarr[$k]]*100)/$mtotalu,0,5));
                        }
	
			$smarty->assign("usafield",$usafield);
		}
	
		$smarty->assign("onefield",$onefield);
		
		if(!$field)
		{	$smarty->assign("caste",$caste);
			$smarty->assign("castep",$castep);
			$smarty->assign("mtongue",$mtongue);
			$smarty->assign("mtonguep",$mtonguep);
			$smarty->assign("withphoto",$withphoto);
			$smarty->assign("withphotop",$withphotop);
			$smarty->assign("manglikp",$manglikp);
			$smarty->assign("manglik",$manglik);
			$smarty->assign("mstatusp",$mstatusp);
			$smarty->assign("mstatus",$mstatus);
			$smarty->assign("childrenp",$childrenp);
			$smarty->assign("children",$children);
			$smarty->assign("btypep",$btypep);
			$smarty->assign("btype",$btype);
			$smarty->assign("complexionp",$complexionp);
			$smarty->assign("complexion",$complexion);
			$smarty->assign("dietp",$dietp);
			$smarty->assign("diet",$diet);
			$smarty->assign("smokep",$smokep);
			$smarty->assign("smoke",$smoke);
			$smarty->assign("drinkp",$drinkp);
			$smarty->assign("drink",$drink);
			$smarty->assign("handicappedp",$handicappedp);
			$smarty->assign("handicapped",$handicapped);
			$smarty->assign("occupationp",$occupationp);
			$smarty->assign("occupation",$occupation);
			$smarty->assign("country_resp",$country_resp);
			$smarty->assign("country_res",$country_res);
			$smarty->assign("city_resp",$city_resp);
			$smarty->assign("city_res",$city_res);
			$smarty->assign("res_statusp",$res_statusp);
			$smarty->assign("res_status",$res_status);
			$smarty->assign("edu_levelp",$edu_levelp);
			$smarty->assign("edu_level",$edu_level);
			$smarty->assign("keywordp",$keywordp);
			$smarty->assign("keyword",$keyword);
			$smarty->assign("photobrowsep",$photobrowsep);
			$smarty->assign("photobrowse",$photobrowse);
			$smarty->assign("onlinep",$onlinep);
			$smarty->assign("online",$online);
			$smarty->assign("freshnessp",$freshnessp);
			$smarty->assign("freshness",$freshness);
			$smarty->assign("incomep",$incomep);
			$smarty->assign("income",$income);
			$smarty->assign("height",$height);
			$smarty->assign("heightp",$heightp);
			$smarty->assign("age",$age);
			$smarty->assign("agep",$agep);
			
			$smarty->assign("ltotal",$total-$logout);
			$smarty->assign("logout",$logout);
			$smarty->assign("sone",$sone);
			$smarty->assign("sonep",$sonep);
			$smarty->assign("stwofive",$stwofive);
			$smarty->assign("stwofivep",$stwofivep);
			$smarty->assign("sfiveten",$sfiveten);
			$smarty->assign("sfivetenp",$sfivetenp);
			$smarty->assign("sten",$sten);
			$smarty->assign("stenp",$stenp);
		}	
		
		$smarty->assign("field",$field);
		$smarty->assign("day",$day);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("eday",$eday);
		$smarty->assign("eyear",$eyear);
		$smarty->assign("emonth",$emonth);
		$smarty->assign("cid",$cid);
		$smarty->display("search_query.htm");
	}
	
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("search_query.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
