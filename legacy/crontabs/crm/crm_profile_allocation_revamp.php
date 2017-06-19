<?php
        $curFilePath = dirname(__FILE__)."/";
        include_once("/usr/local/scripts/DocRoot.php");

	//////////////////////////////////
        $start_time=date("Y-m-d H:i:s");
        mail("vibhor.garg@jeevansathi.com,manoj.rana@naukri.com","Pre-allocation Started At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
        /////////////////////////////////

	/* live section config */
	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
        include ("$docRoot/crontabs/connect.inc");
        include("allocate_functions_revamp.php");
        include($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");
        include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");


	$mysqlObj=new Mysql;
	$db 	=connect_db();
	$db_dnc =connect_dnc();

	//define values to be used globally.
	$fixedAllotedNo 	='70';
	$MAX_ALLOCATE_TOTAL 	=$fixedAllotedNo;
	$last20Days     	=date("Y-m-d H:i:s",time()-20*86400);
	$exclude_mtongue 	="3,16,17,31";

	$sqlCitySel="SELECT VALUE,STATE FROM incentive.LOCATION WHERE SPECIAL_CITY='Y'";
	$resCitySel=mysql_query($sqlCitySel,$db) or die("$sqlCitySel".mysql_error());
	while($rowCitySel=mysql_fetch_array($resCitySel))
	{	
		$city=$rowCitySel['VALUE'];
		$citySelArr[$city]=$rowCitySel['STATE'];
	}
	//truncate profile allocation table.
	$sql="TRUNCATE TABLE incentive.PROFILE_ALLOCATION_TECH";
	mysql_query($sql,$db) or die("$sql".mysql_error());

	global $userarr,$total_executives,$ALLOCATE,$level_stat;

	/*********************************************************** JS Premium Outsourced ***********************************************************/
        $l=0;
        $sql_pre_os_agents= "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%ExcWFH%' AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
        $res_pre_os_agents = mysql_query($sql_pre_os_agents,$db) or die("$sql_pre_os_agents".mysql_error());
        $total_executives = mysql_num_rows($res_pre_os_agents);

        while($row_pre_os_agents = mysql_fetch_array($res_pre_os_agents))
        {
                $userarr[$l]['NAME']=$row_pre_os_agents['USERNAME'];
                $userarr[$l]['ALLOTED']=0;
                $l++;
        }
	$startDt=date('Y-m-d',time()-5*30*86400);	
	$endDt	=date('Y-m-d',time()-1*30*86400);
	$time3Yr=date('Y-m-d',time()-36*30*86400);
	$level_stat=0;
	$sql = "SELECT PROFILEID,ISD FROM newjs.JPROFILE WHERE ACTIVATED='Y' AND INCOMPLETE='N' AND LAST_LOGIN_DT>='$startDt' AND LAST_LOGIN_DT<='$endDt' AND ENTRY_DT >='$time3Yr 00:00:00'";
	pre_all($sql,2);
	unset($userarr);

	$db = connect_db();
	/************************************************************LEVEL-0 (JS Premium)**************************************************************/
	$l=0;
        $sql_pre_agents= "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%EXCPRM%' AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
        $res_pre_agents = mysql_query($sql_pre_agents,$db) or die("$sql_pre_agents".mysql_error());
        $total_executives = mysql_num_rows($res_pre_agents);

        while($row_pre_agents = mysql_fetch_array($res_pre_agents))
        {
                $userarr[$l]['NAME']=$row_pre_agents['USERNAME'];
                $userarr[$l]['ALLOTED']=0;
                $l++;
        }
        $day2 = date('Y-m-d',time()-2*86400);
	$level_stat=0;
        $sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED IN('Y','H') AND INCOMPLETE='N' AND ENTRY_DT >='$day2 00:00:00' AND ENTRY_DT <='$day2 23:59:59' AND INCOME IN(13,14,17,18,19,20,21,22,23) AND MTONGUE NOT IN($exclude_mtongue)";
        pre_all($sql,1);
	unset($userarr);
	
	$db = connect_db();	
	/************************************************************LEVEL-1****************************************************************************/
	/*$sql_branches = "SELECT LABEL,VALUE FROM incentive.SUB_LOCATION";
	$res_branches = mysql_query($sql_branches,$db) or die("$sql_branches".mysql_error());
	while ($row_branches = mysql_fetch_array($res_branches))
	{
		$branch = strtoupper($row_branches['LABEL']);
		$value = $row_branches['VALUE'];
		$l=0;
		$ALLOCATE=0;

		//query to find executives for each sub-location
		$sql_center = "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%PRALL%' and UPPER(PSWRDS.SUB_CENTER)='$branch' AND ACTIVE='Y'";
		$res_center = mysql_query($sql_center,$db) or die("$sql_center".mysql_error());
		while($row_center = mysql_fetch_array($res_center))
		{
			$uname = $row_center['USERNAME'];
			$sql_allocate = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = '$uname'";
                        $res_allocate = mysql_query ($sql_allocate,$db) or die("$sql_allocate".mysql_error());
                        if($row_allocate = mysql_fetch_array($res_allocate))
                                $uallot = $row_allocate['cnt'];
			else
				$uallot	= 0;
			if($uallot < $fixedAllotedNo)
			{
				$userarr[$l]['NAME'] = $uname;
				$userarr[$l]['ALLOTED'] = $uallot;
				$ALLOCATE = $ALLOCATE + $uallot;
				$l++;
			}
		}

		$total_executives = count($userarr);
		$level_stat = $ALLOCATE;
		// query to find pincodes/profiles which are covered by a particular sub-location
		if($total_executives)
                {
			$sql_pincodes = "SELECT PINCODE FROM incentive.BRANCH_PINCODE WHERE SUB_LOCATION = '$value'";
			$res_pincodes = mysql_query ($sql_pincodes,$db) or die("$sql_pincodes".mysql_error());
			while($row_pincodes = mysql_fetch_array($res_pincodes))
				$pinarr[] = $row_pincodes['PINCODE'];
			if(is_array($pinarr))
				$pin_str = "'".implode ("','",$pinarr)."'";
		}
		if($pin_str)
		{
			$db_slave = connect_slave();
			$sql_jp = "SELECT PROFILEID from newjs.JPROFILE WHERE PINCODE IN ($pin_str)";
                        $res_jp = mysql_query($sql_jp,$db_slave) or die("$sql_jp".mysql_error());
                        while($row_jp = mysql_fetch_array($res_jp))
			{
				$proarr[] = $row_jp['PROFILEID'];
			}
			if(is_array($proarr))
                        	$pro_str = "'".implode ("','",$proarr)."'";
		}

		if($ALLOCATE < $MAX_ALLOCATE_TOTAL*$total_executives)
		{
			if($pro_str)
			{
				$sql = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($pro_str) AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=30 AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC";
			}
			else
				$sql = "";
			if($sql)
				pre_all($sql);
		}
		unset($pinarr);
		unset($proarr);
		unset($userarr);
	}

	$db = connect_db();*/
	/************************************************************LEVEL-2****************************************************************************/
	$sql_branches = "SELECT VALUE FROM incentive.LOCATION ORDER BY ID DESC";
	$res_branches = mysql_query($sql_branches,$db) or die("$sql_branches".mysql_error());
	while ($row_branches = mysql_fetch_array($res_branches))
	{
		$value = $row_branches['VALUE'];
		$l=0;
		$ALLOCATE=0;
		// query to find centers which are covered by a particular location
                $sql_centers = "SELECT LABEL FROM incentive.SUB_LOCATION WHERE PRIORITY = '$value'";
                $res_centers = mysql_query ($sql_centers,$db) or die("$sql_centers".mysql_error());
		while($row_centers = mysql_fetch_array($res_centers))
                        $center_arr[] = $row_centers['LABEL'];
                if(is_array($center_arr))
                        $center_str = "'".implode ("','",$center_arr)."'";

		//query to find executives for each location
		if($center_str)
		{
			$sql_center = "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%PRALL%' and UPPER(PSWRDS.SUB_CENTER) IN ($center_str) AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
			$res_center = mysql_query($sql_center,$db) or die("$sql_center".mysql_error());
			while($row_center = mysql_fetch_array($res_center))
			{
				$uname = $row_center['USERNAME'];
	                        $sql_allocate = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = '$uname'";
        	                $res_allocate = mysql_query ($sql_allocate,$db) or die("$sql_allocate".mysql_error());
                	        if($row_allocate = mysql_fetch_array($res_allocate))
                        	        $uallot = $row_allocate['cnt'];
	                        else
        	                        $uallot = 0;    
                	        if($uallot < $fixedAllotedNo)
                        	{
                                	$userarr[$l]['NAME'] = $uname;
	                                $userarr[$l]['ALLOTED'] = $uallot;
        	                        $ALLOCATE = $ALLOCATE + $uallot;
                	                $l++;
                        	}
			}
		}

		$total_executives = count($userarr);
		$level_stat = $ALLOCATE;
		if($ALLOCATE < $MAX_ALLOCATE_TOTAL*$total_executives)
		{
			if($value)
                        {
				if(array_key_exists("$value",$citySelArr))
                                        $scoreLowerLimit =1;
                                else
                                        $scoreLowerLimit =30;
                                $sql = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES='$value' AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>='$scoreLowerLimit' AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC";
			}
			else
				$sql = "";
			pre_all($sql);
		}
		unset($center_str);
		unset($center_arr);
		unset($userarr);
	}

	$db = connect_db();
	/************************************************************LEVEL-3****************************************************************************/
	$sql_branches = "SELECT VALUE FROM incentive.BRANCH_STATE ORDER BY ID DESC";
	$res_branches = mysql_query($sql_branches,$db) or die("$sql_branches".mysql_error());
	while ($row_branches = mysql_fetch_array($res_branches))
	{
		$state_br = $row_branches['VALUE'];
		$l=0;
		$ALLOCATE=0;

		$sql_states1 = "SELECT VALUE FROM incentive.LOCATION WHERE STATE = '$state_br'";
                $res_states1 = mysql_query ($sql_states1,$db) or die("$sql_states1".mysql_error());
                while($row_states1 = mysql_fetch_array($res_states1))
                        $cityarr_sl[] = $row_states1['VALUE'];
                if(is_array($cityarr_sl))
                        $city_str_sl = "'".implode ("','",$cityarr_sl)."'";

		// query to find cities which are covered by a particular state but do not have branches
		if($city_str_sl)
                {
			$sql_cities = "SELECT VALUE FROM incentive.LOCATION_CITY WHERE STATE='$state_br' AND VALUE NOT IN ($city_str_sl)";
			$res_cities = mysql_query ($sql_cities,$db) or die("$sql_cities".mysql_error());
			while($row_cities = mysql_fetch_array($res_cities))
				$cityarr[] = $row_cities['VALUE'];
			if(is_array($cityarr))
                        	$city_str = "'".implode ("','",$cityarr)."'";

			// New Data set 
			if(in_array("$state_br",$citySelArr))
				$citiesNewArray[$state_br] =$city_str;	

			// query to find centers which are covered by a particular state
			if($city_str_sl)
                	{	
        	       		$sql_center = "SELECT LABEL FROM incentive.SUB_LOCATION WHERE PRIORITY IN ($city_str_sl)";
		                $res_center = mysql_query ($sql_center,$db) or die("$sql_center".mysql_error());
				while($row_center = mysql_fetch_array($res_center))
                		        $centerarr[] = $row_center['LABEL'];
			}
		}
                if(is_array($centerarr))
                        $center_str = "'".implode ("','",$centerarr)."'";

		//query to find executives for each location
		if($center_str)
		{
			$sql_center = "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%PRALL%' and UPPER(PSWRDS.SUB_CENTER) IN ($center_str) AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
			$res_center = mysql_query($sql_center,$db) or die("$sql_center".mysql_error());
			while($row_center = mysql_fetch_array($res_center))
			{
				$uname = $row_center['USERNAME'];
				$unameArr[] = $uname;		
        	                $sql_allocate = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = '$uname'";
	                        $res_allocate = mysql_query ($sql_allocate,$db) or die("$sql_allocate".mysql_error());
                	        if($row_allocate = mysql_fetch_array($res_allocate))
                        	        $uallot = $row_allocate['cnt'];
	                        else
        	                        $uallot = 0;
                	        if($uallot < $fixedAllotedNo)
                        	{
	                                $userarr[$l]['NAME'] = $uname;
        	                        $userarr[$l]['ALLOTED'] = $uallot;
                	                $ALLOCATE = $ALLOCATE + $uallot;
                        	        $l++;
	                        }
			}
		}

		$total_executives = count($userarr);
		$level_stat = $ALLOCATE;
		if($ALLOCATE < $MAX_ALLOCATE_TOTAL*$total_executives)
		{
			if($city_str)
                        {
                                $sql = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=30 AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC";
			}
			else
				$sql = "";
			pre_all($sql);
		}
		unset($cityarr_sl);
		unset($city_str_sl);
		unset($city_str);
		unset($center_str);
		unset($centerarr);
		unset($cityarr);
		unset($userarr);
	}

        $db = connect_db();
        /************************************************************LEVEL-4****************************************************************************
	* Considering indian states which do not have franchise location.
	*/
	$restStateArr =getRestIndiaStates();
	foreach($restStateArr as $state_key=>$state_val)
	{
                $l=0;
                $ALLOCATE=0;

                // query to find cities which are covered by a particular state
                $sql_cities = "SELECT VALUE FROM incentive.LOCATION_CITY WHERE STATE='$state_val'";
                $res_cities = mysql_query ($sql_cities,$db) or die("$sql_cities".mysql_error());
                while($row_cities = mysql_fetch_array($res_cities))
                        $cityarr[] = $row_cities['VALUE'];
                if(is_array($cityarr))
                        $city_str = "'".implode ("','",$cityarr)."'";

                //executives for each location
		$unameArr =array_unique($unameArr);
                foreach($unameArr as $key_uname=>$uname)
                {
                 	$sql_allocate = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO ='$uname'";
                 	$res_allocate = mysql_query ($sql_allocate,$db) or die("$sql_allocate".mysql_error());
                        if($row_allocate = mysql_fetch_array($res_allocate))
                        	$uallot = $row_allocate['cnt'];
                        else
                        	$uallot = 0;
                        if($uallot < $fixedAllotedNo)
                        {
                        	$userarr[$l]['NAME'] = $uname;
                        	$userarr[$l]['ALLOTED'] = $uallot;
                        	$ALLOCATE = $ALLOCATE + $uallot;
                        	$l++;
                        }
		}

                $total_executives = count($userarr);
                $level_stat = $ALLOCATE;
                if($ALLOCATE < $MAX_ALLOCATE_TOTAL*$total_executives)
                {
                        if($city_str)
                        {
                                $sql = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=30 AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC";
                        }
                        else
                                $sql = "";
                        pre_all($sql);
                }
                unset($cityarr_sl);
                unset($city_str_sl);
                unset($city_str);
                unset($center_str);
                unset($centerarr);
                unset($cityarr);
                unset($userarr);
        }

	$db = connect_db();	
	/************************************************************LEVEL-5****************************************************************************/
	$sql_branches = "SELECT VALUE FROM incentive.BRANCH_STATE ORDER BY ID DESC";
	$res_branches = mysql_query($sql_branches,$db) or die("$sql_branches".mysql_error());
	while ($row_branches = mysql_fetch_array($res_branches))
	{
		$state_br = $row_branches['VALUE'];
		$l=0;
		$ALLOCATE=0;

		$sql_states1 = "SELECT VALUE FROM incentive.LOCATION WHERE STATE = '$state_br'";
                $res_states1 = mysql_query ($sql_states1,$db) or die("$sql_states1".mysql_error());
                while($row_states1 = mysql_fetch_array($res_states1))
                        $cityarr_sl[] = $row_states1['VALUE'];
                if(is_array($cityarr_sl))
                        $city_str_sl = "'".implode ("','",$cityarr_sl)."'";

		// query to find cities which are covered by a particular state where we have branches
		if($city_str_sl)
		{
			$sql_cities = "SELECT VALUE FROM incentive.LOCATION_CITY WHERE STATE='$state_br' AND VALUE IN ($city_str_sl)";
			$res_cities = mysql_query ($sql_cities,$db) or die("$sql_cities".mysql_error());
			while($row_cities = mysql_fetch_array($res_cities))
				$cityarr[] = $row_cities['VALUE'];
                	if(is_array($cityarr))
                        	$city_str = "'".implode ("','",$cityarr)."'";
		}

		//executives for each location
                $unameArr =array_unique($unameArr);
                foreach($unameArr as $key_uname=>$uname)
                {
      	                $sql_allocate = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = '$uname'";
                        $res_allocate = mysql_query ($sql_allocate,$db) or die("$sql_allocate".mysql_error());
               	        if($row_allocate = mysql_fetch_array($res_allocate))
                       	        $uallot = $row_allocate['cnt'];
                        else
       	                        $uallot = 0;
               	        if($uallot < $fixedAllotedNo)
                       	{
                                $userarr[$l]['NAME'] = $uname;
       	                        $userarr[$l]['ALLOTED'] = $uallot;
               	                $ALLOCATE = $ALLOCATE + $uallot;
                       	        $l++;
                        }
		}

		$total_executives = count($userarr);
		$level_stat = $ALLOCATE;
		if($ALLOCATE < $MAX_ALLOCATE_TOTAL*$total_executives)
		{
			if($city_str)
                        {
                                $sql = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=30 AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC";
			}
			else
				$sql = "";
			pre_all($sql);
		}
		unset($cityarr_sl);
		unset($city_str_sl);
		unset($city_str);
		unset($center_str);
		unset($centerarr);
		unset($cityarr);
		unset($userarr);
	}

        $db = connect_db();
        /************************************************************LEVEL-6****************************************************************************/
        foreach($citySelArr as $city_br_key=>$state_br_val)
        {
                $l=0;
                $ALLOCATE=0;

                // find cities which are covered by a particular state
		$city_str =$citiesNewArray[$state_br_val];

                // query to find centers which are covered by a particular city 
                $sql_center = "SELECT LABEL FROM incentive.SUB_LOCATION WHERE PRIORITY='$city_br_key'";
                $res_center = mysql_query ($sql_center,$db) or die("$sql_center".mysql_error());
                while($row_center = mysql_fetch_array($res_center))
                	$centerarr[] = $row_center['LABEL'];
                if(is_array($centerarr))
                        $center_str = "'".implode ("','",$centerarr)."'";

                //query to find executives for each location
                if($center_str)
                {
                        $sql_center = "SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%PRALL%' and UPPER(PSWRDS.SUB_CENTER) IN ($center_str) AND ACTIVE='Y' AND LAST_LOGIN_DT>='$last20Days'";
                        $res_center = mysql_query($sql_center,$db) or die("$sql_center".mysql_error());
                        while($row_center = mysql_fetch_array($res_center))
                        {
                                $uname = $row_center['USERNAME'];
                                $sql_allocate = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = '$uname'";
                                $res_allocate = mysql_query ($sql_allocate,$db) or die("$sql_allocate".mysql_error());
                                if($row_allocate = mysql_fetch_array($res_allocate))
                                        $uallot = $row_allocate['cnt'];
                                else
                                        $uallot = 0;
                                if($uallot < $fixedAllotedNo)
                                {
                                        $userarr[$l]['NAME'] = $uname;
                                        $userarr[$l]['ALLOTED'] = $uallot;
                                        $ALLOCATE = $ALLOCATE + $uallot;
                                        $l++;
                                }
                        }
                }

                $total_executives = count($userarr);
                $level_stat = $ALLOCATE;
                if($ALLOCATE < $MAX_ALLOCATE_TOTAL*$total_executives)
                {
                        if($city_str)
                        {
                                $sql = "SELECT PROFILEID,CITY_RES FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($city_str) AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=1 AND ANALYTIC_SCORE<30 ORDER BY ANALYTIC_SCORE DESC";
                        }
                        else
                                $sql = "";
                        pre_all($sql);
                }
                unset($city_str);
                unset($center_str);
                unset($centerarr);
                unset($cityarr);
                unset($userarr);
        }
	/* All levels(level 0 to 6) are complete  */
 
	//////////////////////////////////
        $end_time=date("Y-m-d H:i:s");
        mail("vibhor.garg@jeevansathi.com,manoj.rana@naukri.com","Pre-allocation Completed At $end_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
        /////////////////////////////////

	function pre_all($sql,$level0='0')
	{
		global $userarr,$total_executives,$MAX_ALLOCATE_TOTAL,$db,$db_slave,$level_stat,$fixedAllotedNo,$exclude_mtongue;
		$n=0;
		$db_slave = connect_slave();
		if($sql)
			$res = mysql_query($sql,$db_slave) or die("$sql".mysql_error($db_slave));
		if($res)
		{
			$db = connect_db();
			$MAX_ALLOCATE = $MAX_ALLOCATE_TOTAL;
			while($row = mysql_fetch_array($res))
			{
				$profileid 	= $row['PROFILEID'];

				$sql_jp = "SELECT PHONE_WITH_STD,PHONE_RES,PHONE_MOB,ISD,STD,COUNTRY_RES,HAVE_JCONTACT,MOB_STATUS,LANDL_STATUS from newjs.JPROFILE WHERE ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND PROFILEID ='$profileid' AND SUBSCRIPTION=''";
				if(!$level0){
					$city_profile   = $row['CITY_RES'];
					 $sql_jp.=" AND ENTRY_DT < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND LAST_LOGIN_DT>=DATE_SUB(CURDATE(), INTERVAL 40 DAY) AND MTONGUE NOT IN ($exclude_mtongue)";
				}
				elseif($level0==2){
					$indianNo =isIndianNo($row['ISD']);
					if($indianNo)
						$sql_jp.=" AND (INCOME IN('7','16','17','18','20','22','23') OR FAMILY_INCOME IN('20','22','23')) AND MTONGUE NOT IN($exclude_mtongue)";
				}
				$res_jp = mysql_query($sql_jp,$db) or die("$sql_jp".mysql_error($db));
				if($row_jp = mysql_fetch_array($res_jp))
				{	
					/* Added code for DNC Changes */
					$permanent_excluded=0;
					if(!$level0 || ($level0==2 && $indianNo))
					{	
						$phoneNumStack  =array();
						$haveJContact   =$row_jp['HAVE_JCONTACT'];
						$phone_res 	=$row_jp['PHONE_WITH_STD'];
						if(!$phone_res && $row_jp['PHONE_RES']){
							$phone_res =$row_jp['STD'].$row_jp['PHONE_RES'];	
						}					
						if($phone_res){
							$phone_res =phoneNumberCheck($phone_res);
							if($phone_res)
								array_push($phoneNumStack,"$phone_res");
						}
						$phone_mob 	=$row_jp['PHONE_MOB'];
						if($phone_mob){
							$phone_mob =phoneNumberCheck($phone_mob);
							if($phone_mob)
								array_push($phoneNumStack,"$phone_mob");
						}
						$phone_alternate=getOtherPhoneNums($profileid);
						if($phone_alternate){
							$phone_alternate =phoneNumberCheck($phone_alternate);
							if($phone_alternate)
								array_push($phoneNumStack,"$phone_alternate");
						}
						$DNCArray =checkDNC($phoneNumStack);
						$isDNC	  =$DNCArray['STATUS'];
	
						if($haveJContact=='Y' || $phone_alternate || $row_jp['MOB_STATUS']=='Y' || $row_jp['LANDL_STATUS']=='Y')
							$phoneVerified ='Y';
						else
							$phoneVerified ='N';	
					
					}
					/* Added code for DNC Changes */
					if(check_profile($profileid))
					{	
						if($isDNC && !$level0)
						{
							//PARMANENT EXCLUSION RULE
							$permanent_excluded=0;
							$excl_dnc_dt=date('Y-m-d',time()-30*86400);
							$excl_rest_dt=date('Y-m-d',time()-7*86400);
							$excl_ni_dt=date('Y-m-d',time()-45*86400);

							//disposition
							$sql_history="SELECT ENTRY_DT,DISPOSITION FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
							$res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error());
							if($row_history = mysql_fetch_array($res_history))
							{
								$profile_type = 'O';// profile has been handled once
								if(($row_history["ENTRY_DT"]>=$excl_rest_dt && ($row_history["DISPOSITION"]=='AA' || $row_history["DISPOSITION"]=='CF' || $row_history["DISPOSITION"]=='SEQ')) || ($row_history["DISPOSITION"]=='NI' && $row_history["ENTRY_DT"]>=$excl_ni_dt) || $row_history["DISPOSITION"]=='D' || ($row_history["DISPOSITION"]=='DNC' && $row_history["ENTRY_DT"]>=$excl_dnc_dt))
									$permanent_excluded=1;
							}
							else
								$profile_type = 'N';// new profile

						}
						if($level0==1)
						{	
							 //PARMANENT EXCLUSION RULE
							$permanent_excluded=0;
							$day2 = date('Y-m-d',time()-2*86400);
							//disposition
							$sql_history="SELECT PROFILEID FROM incentive.HISTORY WHERE PROFILEID='$profileid' AND ENTRY_DT>'$day2'";
							$res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error());
							if($row_history = mysql_fetch_array($res_history))
									$permanent_excluded=1;
							$profile_type='N';
						}
						if(!$isDNC && !$level0 )
							$permanent_excluded=1;

						if($level0==2 && $indianNo && (!$isDNC && $phoneVerified!='Y'))
							$permanent_excluded=1;
		
						//setting
						$sql_al = "SELECT MEMB_CALLS,OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID='$profileid'";
						$res_al = mysql_query($sql_al,$db) or die("$sql_al".mysql_error());
						if($row_al = mysql_fetch_array($res_al))
						{
							if($row_al["MEMB_CALLS"]=='U' || $row_al["OFFER_CALLS"]=='U')
								$permanent_excluded=1;	
						}

						$go=1;
						if(($row_jp['PHONE_RES'] || $row_jp['PHONE_MOB'] || $phone_alternate ) && !$level0)
						{
							$go=0;
							if(isIndianNo($row_jp['ISD']))
								$go=1;
						}

						if($userarr[$total_executives-1]['ALLOTED'] < $MAX_ALLOCATE && !profile_allocated($profileid) && $go && !$permanent_excluded)
						{		
							while($userarr[$n]['ALLOTED'] > $fixedAllotedNo)
							{
								$n++;
								if($n == $total_executives)
									$n = 0;
							}
							$user_value = $userarr[$n]['NAME'];
							//allocate the profile.
							if($user_value !='')
							{
									$sql_ins = "INSERT IGNORE INTO incentive.PROFILE_ALLOCATION_TECH (PROFILEID, ALLOTED_TO , ALLOT_DT,STATUS,PROFILE_TYPE) VALUES('$profileid','$user_value',now(),'N','$profile_type')";
									mysql_query($sql_ins,$db) or die("$sql_ins".mysql_error);
									$userarr[$n]['ALLOTED']++;
									$level_stat++;
									$n++;
									if($n == $total_executives)
										$n = 0;
							}
						}
					}
				}
				if($level_stat==$total_executives*$MAX_ALLOCATE)
        	                        break;
			}
		}
	}

	function isIndianNo($num){
		if($num && ($num==91 || $num=='0091' || $num=='+91'))
			return 1;	
		else
			return 0;
	}	
?>
