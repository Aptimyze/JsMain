<?php
class DialerHandler
{
        public function __construct($db_js, $db_js_157, $db_dialer,$db_master=''){
		$this->db_js 		=$db_js;
		$this->db_js_157 	=$db_js_157;
		$this->db_dialer 	=$db_dialer;
		$this->db_master 	=$db_master;
        }
	public function getRenewalEligibleProfiles($x,$campaign_name)
	{
		$sql = "SELECT PROFILEID FROM incentive.RENEWAL_IN_DIALER WHERE PROFILEID%10=$x AND ELIGIBLE!='N' AND CAMPAIGN_TYPE='$campaign_name'";
		$res = mysql_query($sql,$this->db_js_157) or die("$sql".mysql_error($this->db_js));
		while($row = mysql_fetch_array($res))
			$eligible_array[] = $row["PROFILEID"];
		return $eligible_array;
	}
	public function getRenewalInEligibleProfiles($x,$campaign_name)
	{
		$sql = "SELECT PROFILEID FROM incentive.RENEWAL_IN_DIALER WHERE PROFILEID%10=$x AND ELIGIBLE='N' AND CAMPAIGN_TYPE='$campaign_name'";
		$res = mysql_query($sql,$this->db_js_157) or die("$sql".mysql_error($this->db_js));
		while($row = mysql_fetch_array($res))
			$ignore_array[] = $row["PROFILEID"];
		return $ignore_array;
	}
	public function getRenewalDiscountArray($profiles_array)
	{
		$vd_profiles = array();	
		$profileid_str = @implode(",",$profiles_array);
		if($profileid_str){
			$sql_vd="select PROFILEID,DISCOUNT from billing.RENEWAL_DISCOUNT WHERE PROFILEID IN ($profileid_str)";
			$res_vd = mysql_query($sql_vd,$this->db_js_157) or die("$sql_vd".mysql_error($this->db_js));
			while($row_vd = mysql_fetch_array($res_vd)){
				$pid = $row_vd["PROFILEID"];
				$vd_profiles[$pid] = $row_vd["DISCOUNT"];
			}
		}
		return $vd_profiles;
	}
	public function getLoginWithin15Days($profiles_array)
	{
		$profileid_str = @implode(",",$profiles_array);
		if($profileid_str){
			$sql = "SELECT PROFILEID,LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PROFILEID IN ($profileid_str)";
			$res = mysql_query($sql,$this->db_js) or die("$sql".mysql_error($this->db_js));
			while($row = mysql_fetch_array($res))
			{
				$pid = $row["PROFILEID"];
				if($row["LAST_LOGIN_DT"]>=@date('Y-m-d',time()-14*86400))
					$loginWithin15Days[$pid] = 1;
				else
					$loginWithin15Days[$pid] = 0;
			}
		}
		else
			$loginWithin15Days = array();
		return $loginWithin15Days;
	}
	public function getAllotedProfiles($profiles_array)
	{
		$profileid_str = @implode(",",$profiles_array);
		if($profileid_str){
			$sql = "SELECT PROFILEID,ALLOTED_TO from incentive.MAIN_ADMIN WHERE PROFILEID IN ($profileid_str)";
			$res = mysql_query($sql,$this->db_js) or die("$sql".mysql_error($this->db_js));
			while($row = mysql_fetch_array($res)){
				$pid = $row["PROFILEID"];
				$alloted_array[$pid] = $row["ALLOTED_TO"];
			}
		}
		else
			$alloted_array = array();
		return $alloted_array;
	}
	public function getScoreArray($profiles_array)
	{
		$profileid_str = @implode(",",$profiles_array);
		if($profileid_str){
			$sql = "SELECT PROFILEID,ANALYTIC_SCORE from incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($profileid_str)";
			$res = mysql_query($sql,$this->db_js) or die("$sql".mysql_error($this->db_js));
			while($row = mysql_fetch_array($res)){
				$pid = $row["PROFILEID"];
				$score_array[$pid] = $row["ANALYTIC_SCORE"];
			}
		}
		else    
			$score_array = array();
		return $score_array;
	}
        public function getPaidProfilesArray($profiles_array)
        {
                $profileid_str = @implode(",",$profiles_array);
                if($profileid_str){
                        $sql = "SELECT PROFILEID,MAX(EXPIRY_DT) EXPIRY_DT from billing.SERVICE_STATUS WHERE PROFILEID IN ($profileid_str) group by PROFILEID";
                        $res = mysql_query($sql,$this->db_js) or die("$sql".mysql_error($this->db_js));
                        while($row = mysql_fetch_array($res)){
                                $pid = $row["PROFILEID"];
                                $paid_array[$pid] = $row["EXPIRY_DT"];
                        }
                }
                else
                        $paid_array = array();
                return $paid_array;
        }
	public function stop_non_eligible_profiles($campaign_name,$x,$ignore_array,$discount_profiles)
	{
		$squery1 = "SELECT easycode,PROFILEID,Dial_Status,DISCOUNT_PERCENT FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 AND PROFILEID%10=$x";
		$sresult1 = mssql_query($squery1,$this->db_dialer) or $this->logerror($squery1,$this->db_dialer);
		while($srow1 = mssql_fetch_array($sresult1))
		{
			$ecode = $srow1["easycode"];
			$proid = $srow1["PROFILEID"];
			$vd_discount_dialer = $srow1["DISCOUNT_PERCENT"];
			$updateStr='';
			$vdDiscount =0;
			$updateArr =array();
			if(in_array($proid,$ignore_array)){
				if($srow1["Dial_Status"]!=9)
					$updateArr[] ="Dial_Status=0,DNC_Status=''";

				if(array_key_exists($proid,$discount_profiles))
					$vdDiscount = $discount_profiles[$proid];
				if($vdDiscount != $vd_discount_dialer)
					$updateArr[] ="DISCOUNT_PERCENT=$vdDiscount";
				
				if(count($updateArr)>0){
					$updateStr =implode(",",$updateArr);
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET $updateStr WHERE easycode='$ecode'";
					mssql_query($query1,$this->db_dialer) or $this->logerror($query1,$this->db_dialer);

					$log_query = "INSERT into test.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$updateStr',now(),'STOP')";
					mysql_query($log_query,$this->db_js_157) or die($log_query.mysql_error($this->db_js_157));
				}
			}
		}
	}

	public function update_data_of_eligible_profiles($campaign_name,$x,$eligible_array,$discount_profiles,$allotedArray,$scoreArray,$paidProfiles)
	{
		$squery2 = "SELECT easycode,PROFILEID,easy.dbo.ct_$campaign_name.AGENT,old_priority,DISCOUNT_PERCENT,SCORE,Dial_Status,EXPIRY_DT FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 AND PROFILEID%10=$x";
		$sresult2 = mssql_query($squery2,$this->db_dialer) or $this->logerror($squery2,$this->db_dialer);
		while($srow1 = mssql_fetch_array($sresult2))
		{
			$dialer_data["initialPriority"]	=$srow1["old_priority"];
			$ecode 				= $srow1["easycode"];
			$proid 				= $srow1["PROFILEID"];
			$dialer_data["profileid"] 	= $srow1["PROFILEID"];
			$dialer_data["allocated"] 	= $srow1["AGENT"];
			$dialer_data["discount"] 	= $srow1["DISCOUNT_PERCENT"];
			$dialer_data["analytic_score"] 	= $srow1["SCORE"];
			$dialer_data["dial_status"] 	= $srow1["Dial_Status"];
			$dialer_data['expiryDt'] 	= $srow1["EXPIRY_DT"];	

			if(in_array($proid,$eligible_array)){
				$jp_condition_str	=$this->data_comparision($dialer_data,$campaign_name,$ecode,$discount_profiles,$allotedArray,$scoreArray,$paidProfiles);
				$jp_condition_arr	=explode("*",$jp_condition_str);
				$jp_condition_arr0 	=$jp_condition_arr[0];
				$jp_condition_arr1 	=$jp_condition_arr[1];
				if($jp_condition_arr0!='ignore'){
					$query1 = "UPDATE easy.dbo.ct_$campaign_name SET $jp_condition_arr0 WHERE easycode='$ecode'";
					mssql_query($query1,$this->db_dialer) or $this->logerror($query1,$this->db_dialer);
					$ustr = str_replace("'","",$jp_condition_arr0);
					$log_query = "INSERT into test.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$ustr',now(),'UPDATE')";
					mysql_query($log_query,$this->db_js_157) or die($log_query.mysql_error($this->db_js_157));

				}
				if($jp_condition_arr1){
					$query2 = "UPDATE easy.dbo.ph_contact SET $jp_condition_arr1 WHERE code='$ecode' AND priority <=6";
					mssql_query($query2,$this->db_dialer) or $this->logerror($query2,$this->db_dialer);
					$ustr1 = str_replace("'","",$jp_condition_arr1);
					$log_query = "INSERT into test.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$ustr1',now(),'UPDATE')";
					mysql_query($log_query,$this->db_js_157) or die($log_query.mysql_error($this->db_js_157));
				}

				$sql_chk="select AGENT from easy.dbo.ct_$campaign_name where easycode='$ecode'";
				$sresult_chk = mssql_query($sql_chk,$this->db_dialer) or $this->logerror($sql_chk,$this->db_dialer);
				$row_chk= mssql_fetch_array($sresult_chk);
				if(!$row_chk["AGENT"]){
					$query_ph2 = "UPDATE easy.dbo.ph_contact SET Agent=NULL WHERE code='$ecode'";
					mssql_query($query_ph2,$this->db_dialer) or $this->logerror($query_ph2,$this->db_dialer);
					$log_query = "INSERT into test.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','Agent=NULL',now(),'UPDATE')";
					mysql_query($log_query,$this->db_js_157) or die($log_query.mysql_error($this->db_js_157));
				}
			}
			unset($dialer_data);
		}
	}
	public function data_comparision($dialer_data,$campaign_name,$ecode,$discount_profiles,$allotedArray,$scoreArray,$paidProfiles)
	{
		$profileid = $dialer_data["profileid"];
		$update_str =array();

		//DISCOUNT_PERCENT
		$vd_percent=0;
		if(array_key_exists($profileid,$discount_profiles))
			$vd_percent=$discount_profiles[$profileid];
		if($vd_percent!=$dialer_data['discount'])
			$update_str[] ="DISCOUNT_PERCENT='$vd_percent'";

		//ANALYTIC_SCORE
		$score='';
		if(array_key_exists($profileid,$scoreArray))
			$score=$scoreArray[$profileid];
		if($score!=$dialer_data['analytic_score'] && $score!='')
			$update_str[]="SCORE='$score'";

		// Update Expiry Date
		$expiryDt ='0000-00-00';
                if(array_key_exists($profileid,$paidProfiles))
                        $expiryDt =$paidProfiles[$profileid];
                if($expiryDt!=$dialer_data['expiryDt'])
                        $update_str[] ="EXPIRY_DT='$expiryDt'";
                
		//AGENT & Dial_Status
		$alloted_to = '';
		if(array_key_exists($profileid,$allotedArray))
			$alloted_to = $allotedArray[$profileid];

		if($alloted_to!=$dialer_data['allocated'])
		{
			if($alloted_to){
				$update_str[]="easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
				if($dialer_data["dial_status"]!='9')
					$update_str[]="Dial_Status='2',DNC_Status=''";
			}
			else{
				$query_ph1 = "UPDATE easy.dbo.ph_contact SET Agent=NULL WHERE code='$ecode'";
				mssql_query($query_ph1,$this->db_dialer) or $this->logerror($query_ph1,$this->db_dialer);

				$log_query = "INSERT into test.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaign_name','Agent=NULL',now(),'UPDATE')";
				mysql_query($log_query,$this->db_js_157) or die($log_query.mysql_error($this->db_js_157));

				$update_str[] ="easy.dbo.ct_$campaign_name.AGENT=''";
				if($dialer_data["dial_status"]!='9')
					$update_str[] ="Dial_Status='1',DNC_Status=''";
			}
		}
		elseif($dialer_data['allocated']!='' && $dialer_data['dial_status']!='2' && $dialer_data["dial_status"]!='9'){
			$update_str[] ="Dial_Status='2',DNC_Status=''";
		}
		elseif(!$alloted_to && $dialer_data['dial_status']!='1' && $dialer_data["dial_status"]!='9'){
			$update_str[] ="Dial_Status='1',DNC_Status=''";
		}

		//INITIAL PRIORITY UPDATE 
		$priority=0;
		if($score>=81 && $score<=100)
			$priority='5';
		elseif($score>=61 && $score<=80)
			$priority='4';
		elseif($score>=41 && $score<=60)
			$priority='3';
		elseif($score>=21 and $score<=40)
			$priority='2';
		elseif($score>=11 and $score<=20)
			$priority='1';
		elseif($score>=1 and $score<=10)
			$priority='0';
		
		if($priority!=$dialer_data['initialPriority']){
			$update_str[] 	="old_priority='$priority'";
			$update_strPri 	="*priority='$priority'";
		}
		if(count($update_str)>0){
			$update_str1 =@implode(",",$update_str);
			$update_str1 =$update_str1.$update_strPri;
			unset($update_str);
			return $update_str1;
		}
		else
			return "ignore";
	}
        public function getProfilesForCampaign($tableName, $csvEntryDate='',$campaignName='',$startDt='',$endDt='',$ID='')
        {
		$tableName =trim($tableName);
		if($campaignName=='OB_JS_PAID')
			$sql ="SELECT * FROM incentive.$tableName WHERE CSV_ENTRY_DATE='$csvEntryDate'";
		elseif($campaignName=='OB_JS_RCB')
			$sql ="SELECT * FROM incentive.$tableName WHERE ID>'$ID' ORDER BY ID";
		elseif($campaignName=='JS_RENEWAL' || $campaignName=='OB_RENEWAL_MAH')
			$sql ="SELECT * FROM incentive.$tableName WHERE CSV_ENTRY_DATE='$csvEntryDate' AND CAMPAIGN_TYPE='$campaignName' ORDER BY PRIORITY DESC,ANALYTIC_SCORE DESC,LAST_LOGIN_DATE DESC";
		else
			$sql ="SELECT * FROM incentive.$tableName WHERE CSV_ENTRY_DATE='$csvEntryDate' ORDER BY PRIORITY DESC,ANALYTIC_SCORE DESC,LAST_LOGIN_DATE DESC";
                $res = mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));
                while($row = mysql_fetch_assoc($res)){
			$dataArr[] =$row;
		}
                return $dataArr;
        }
        public function addProfileinCampaign($dataArr,$campaignName='')
        {
		if(count($dataArr)>0){
			foreach($dataArr as $key=>$value){
				$fieldsArr[] =$key;
				$valuesArr[] ="'".$value."'";
			} 
			$fieldsStr =implode(",",$fieldsArr);
			$valuesStr =implode(",",$valuesArr);
			if($campaignName=='OB_JS_RCB')
				$table ='easy.dbo.tbl_lead_table_OB_JS_RCB';
			else
				$table ='easy.dbo.tbl_lead_table_JS';
			$squery ="insert into ".$table."(".$fieldsStr.") VALUES($valuesStr)";
                	//$squery ="insert into easy.dbo.tbl_lead_table_JS($fieldsStr) VALUES($valuesStr)";
			$result =mssql_query($squery,$this->db_dialer) or $this->logerror($squery,$this->db_dialer);
			//die;
		}
        }
        public function formatDataSet($campaignName, $dataArr,$csvEntryDate)
        {
		if($campaignName=='JS_RENEWAL' || $campaignName=='OB_RENEWAL_MAH')
			$discountField ='DISCOUNT_PERCENT';
		else
			$discountField ='VD_PERCENT';

		$fieldNameArr =array('DataID'=>'DataID','Campaign'=>'Campaign','CreateTimeStamp'=>'CreateTimeStamp','UpdateTimeStamp'=>'UpdateTimeStamp','StatusCode'=>'StatusCode','PROFILEID'=>'PROFILEID','priority'=>'PRIORITY','SCORE'=>'ANALYTIC_SCORE','Old_priority'=>'OLD_PRIORITY','DIAL_STATUS'=>'DIAL_STATUS','AGENT'=>'AGENT','VD_PERCENT'=>"$discountField",'LAST_LOGIN_DATE'=>'LAST_LOGIN_DATE','PHONE_NO1'=>'PHONE_NO1','PHONE_NO2'=>'PHONE_NO2','PHOTO'=>'PHOTO','DATE_OF_BIRTH'=>'DOB','MSTATUS'=>'MSTATUS','EVER_PAID'=>'EVER_PAID','GENDER'=>'GENDER','POSTEDBY'=>'POSTEDBY','NEW_VARIABLE'=>'NEW_VARIABLE1','EOI'=>'NEW_VARIABLE2','TOTAL_ACCEPTANCES'=>'NEW_VARIABLE3','Phone1'=>'PHONE_NO1','Phone2'=>'PHONE_NO2','LEAD_ID'=>'LEAD_ID','CSV_ENTRY_DATE'=>'CSV_ENTRY_DATE','EXPIRY_DT'=>'EXPIRY_DT');
		if($campaignName=='OB_JS_PAID'){
			$fieldNameArr1 =array('USERNAME'=>'USERNAME','MEMBERSHIP'=>'MEMBERSHIP','ADDON'=>'ADDON','PAYMENT_DATE'=>'PAYMENT_DT');
			$fieldNameArr =array_merge($fieldNameArr,$fieldNameArr1);
		}
		else if($campaignName=='OB_JS_RCB'){
			unset($fieldNameArr['EXPIRY_DT']);
			$fieldNameArr1 =array('USERNAME'=>'USERNAME','COUNTRY'=>'COUNTRY','ID'=>'ID','PREFERRED_TIME_IST'=>'PREFERRED_TIME_IST');
			$fieldNameArr =array_merge($fieldNameArr,$fieldNameArr1);	
		}
		if($campaignName=='OB_JS_PAID')
			$dateFieldsArr =array();
		else
			$dateFieldsArr =array("LAST_LOGIN_DATE","DOB");
		$phoneFieldsArr	=array("PHONE_NO1","PHONE_NO2","PHONE_NO3","PHONE_NO4");

		$dataArr['DataID'] 		=$campaignName."-".$csvEntryDate;
		$dataArr['Campaign'] 		=$campaignName;
		$dataArr['CreateTimeStamp'] 	=date('Y-m-d H:i:s');
		$dataArr['UpdateTimeStamp']     ='';
		$dataArr['StatusCode']		=0;

		foreach($fieldNameArr as $key=>$key1){
			if(in_array($key1,$dateFieldsArr)){
				$field =$dataArr[$key1];		
				$field =$this->fetchIST($field);
				$field =date("d/m/y",strtotime($field));
				$dataArr[$key1] =$field;
			}
			if($key!='Phone1' && $key!='Phone2'){
			if(in_array($key1,$phoneFieldsArr)){
				$field =$dataArr[$key1];
				if($field){
					$field ='0'.$field;
				}
				$dataArr[$key1] =$field;	
			}}
			$dataSet[$key] =$dataArr[$key1];	
		}	
		return $dataSet;
        }
        public function getCampaignStatus($campaignName,$csvEntryDate)
        {
                $sql = "SELECT STATUS FROM incentive.CAMPAIGN_STATUS_LOG WHERE CAMPAIGN_NAME='$campaignName' AND ENTRY_DATE='$csvEntryDate' limit 1";
                $res = mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));
                if($row = mysql_fetch_assoc($res))
                        $status =$row['STATUS'];
                return $status;
        }
        public function setCampaignStatus($campaignName,$csvEntryDate,$status=0) 
        {
                $sql = "REPLACE INTO incentive.CAMPAIGN_STATUS_LOG(`CAMPAIGN_NAME`,`ENTRY_DATE`,`STATUS`) VALUES('$campaignName','$csvEntryDate','$status')";
                mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));
        }
        public function getDialerCampaignRecords($campaignName,$csvEntryDate)
        {
                $squery = "select count(1) cnt from easy.dbo.tbl_lead_table_JS WHERE Campaign='$campaignName' AND CSV_ENTRY_DATE='$csvEntryDate'";
		$sresult =mssql_query($squery,$this->db_dialer) or $this->logerror($squery,$this->db_dialer);
                if($srow = mssql_fetch_array($sresult)){
			$cnt =$srow['cnt'];
		}
		return $cnt;
        }
        public function getCampaignRecordsForDuration($campaignName,$startDate,$endDate='')
        {
		if($campaignName=='OB_JS_RCB')
			$squery = "select count(1) cnt from easy.dbo.tbl_lead_table_OB_JS_RCB WHERE Campaign='$campaignName' AND CSV_ENTRY_DATE>='$startDate'";		
		else
	                $squery = "select count(1) cnt from easy.dbo.tbl_lead_table_OB_JS_RCB WHERE Campaign='$campaignName' AND CSV_ENTRY_DATE>'$startDate'";
                $sresult =mssql_query($squery,$this->db_dialer) or $this->logerror($squery,$this->db_dialer);
                if($srow = mssql_fetch_array($sresult)){
                        $cnt =$srow['cnt'];
                }
                return $cnt;
        }
	public function getLastHandledDate($processId)
	{
		$sql="SELECT DATE from incentive.LAST_HANDLED_DATE WHERE SOURCE_ID='$processId'";
		$res =mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));			
                if($row = mysql_fetch_assoc($res))
                        $date =$row['DATE'];
                return $date;
	}
        public function getLastHandledID($processId)
        {
                $sql="SELECT HANDLED_ID from incentive.LAST_HANDLED_DATE WHERE SOURCE_ID='$processId'";
                $res =mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));
                if($row = mysql_fetch_assoc($res))
                        $id =$row['HANDLED_ID'];
                return $id;
        }
	public function updateLastHandledDate($processId, $dateSet)
	{
		$sql="update incentive.LAST_HANDLED_DATE SET DATE='$dateSet' WHERE SOURCE_ID='$processId'";
		mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));
	}
        public function updateLastHandledID($processId, $id)
        {
                $sql="update incentive.LAST_HANDLED_DATE SET HANDLED_ID='$id' WHERE SOURCE_ID='$processId'";
                mysql_query($sql,$this->db_master) or die("$sql".mysql_error($this->db_master));
        }
        public function fetchIST($time)
        {
                $ISTtime=strftime("%Y-%m-%d %H:%M",strtotime("$time + 10 hours 30 minutes"));
                return $ISTtime;
        }
        public function getLeadIdSuffix()
        {
                $sql = "select LEAD_ID_SUFFIX from incentive.LARGE_FILE ORDER BY ENTRY_DT DESC LIMIT 1";
                $res = mysql_query($sql,$this->db_js_157) or die("$sql".mysql_error($this->db_js_157));
                if($row = mysql_fetch_array($res))
                        $leadIdSuffix = $row["LEAD_ID_SUFFIX"];
                return $leadIdSuffix;
        }
	public function logerror($sql="",$db="",$ms='')
	{
		$today=@date("Y-m-d H:m:i");
		$filename="logerror.txt";
		if(is_writable($filename)){
			if (!$handle = fopen($filename, 'a')){
				echo "Cannot open file ($filename)";
				exit;
			}
			if(!$ms)
				fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mssql_get_last_message(). " \t $today");
			else
				fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mysql_error(). " \t $today");
			fclose($handle);
		}
		else
			echo "The file $filename is not writable";
	}
}
?>
