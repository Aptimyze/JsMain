<?php
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
define('sugarEntry',true);
$path=JsConstants::$docRoot;
require_once("$path/profile/connect.inc");
require_once("$path/sugarcrm/include/utils/Jscreate_lead.php");
$db_js=connect_db();
mysql_select_db("sugarcrm",$db_js);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_js);
$csvFileName = $argv[1];

include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$csvToSugarObj = new csvToSugar;
$csvToSugarObj->execute($csvFileName);

class csvToSugar
{
  public function execute($csvFileName)
  {
	$rowCount = 0;
	$file = fopen($csvFileName, 'r');
		
	while($row=fgets($file))
	{  
		if($rowCount!=0)
		{

	    		$rowArr = str_getcsv($row,"\t");
			if(count($rowArr)<=1)
				$rowArr = str_getcsv($row);
                        foreach ($rowArr as $key=>$value)
                        {
                                $decodedWord = mb_convert_encoding ($value, "UTF-8", "UTF-16BE");
                                $this->csvArr[$rowCount-1][] = $decodedWord;
                        }
		}
		else
		{
	    		$rowArr = str_getcsv($row,"\t");
			if(count($rowArr)<=1)
				$rowArr = str_getcsv($row);
			foreach ($rowArr as $key=>$value) 
			{
				$decodedLine = mb_convert_encoding ($value, "UTF-8", "UTF-16BE");
				$fieldsArr= str_getcsv($decodedLine,"\t");
				$this->fieldsArr[] = $fieldsArr[0];
			}
			$this->setIndexes();
			if($this->emailIndex=='' && $this->phoneIndex=='')
			{
				foreach ($rowArr as $key=>$value) 
				{
					$decodedLine = mb_convert_encoding ($value, "UTF-8", "UTF-16LE");
					$fieldsArr= str_getcsv($decodedLine,"\t");
					$this->fieldsArr[] = $fieldsArr[0];
				}
				$this->setIndexes();
			}
		}
		$rowCount++;
	}
	if(is_array($this->csvArr))
	{
		$this->filterData();
		$this->createLeadData();
		$this->createLead();
		fclose($file);
	}
  }
  public function setIndexes()
  {
	foreach($this->fieldsArr as $k=>$v)
	{
		if($v=="email")
			$this->emailIndex = $k;
		if($v=="phone_number")
			$this->phoneIndex = $k;
	}
  }
  public function manipulatePhone($phone)
  {
	$phone = preg_replace("/[^0-9]/","",$phone);
	if(strlen($phone)>=12 && substr($phone,0,2)==91)
		$phone  = substr($phone,2);
	return $phone;
  }
  public function filterData()
  {
	$this->sanatizeEmail();
	$this->sanatizePhone();
	$this->generateFieldArr("EMAIL");
	$this->filterJProfileData("EMAIL");
	$this->generateFieldArr("PHONE_MOB");
	$this->filterJProfileData("PHONE_MOB");
	$this->generateFieldArr("EMAIL");
	$this->filterLeads("EMAIL");
	$this->generateFieldArr("PHONE_MOB");
	$this->filterLeads("PHONE_MOB");
  }
  public function sanatizeEmail()
  {
        foreach($this->csvArr as $k=>$v)
        {
		if($v[$this->emailIndex])
		{
			if($x= filter_var($v[$this->emailIndex], FILTER_VALIDATE_EMAIL))
				$this->csvArr[$k][$this->emailIndex]=$x;
			else
				unset($this->csvArr[$k]);
		}
	}
	$this->csvArr = array_values($this->csvArr);
  }
  public function sanatizePhone()
  {
        foreach($this->csvArr as $k=>$v)
        {
		if($v[$this->phoneIndex])
		{
			$this->csvArr[$k][$this->phoneIndex]=$this->manipulatePhone($v[$this->phoneIndex]);
		}
	}
  }
  public function generateFieldArr($field)
  {
	unset($this->csvFieldArr);
	unset($this->fieldStr);
        foreach($this->csvArr as $k=>$v)
        {
		if($field=="EMAIL")
		{
			if($v[$this->emailIndex])
			{
				$this->csvFieldArr[$v[$this->emailIndex]] = $k;
			}
		}
		else if($field=="PHONE_MOB")
		{
			if($v[$this->phoneIndex])
			{
				$this->csvFieldArr[$v[$this->phoneIndex]] = $k;
			}
		}
		if(is_array($this->csvFieldArr))
			$this->fieldStr = implode(",",array_keys($this->csvFieldArr));
        }
  }
  public function unsetCsvArrFields($queryMatchedData)
  {
        foreach($queryMatchedData as $k=>$v)
        {
                unset($this->csvArr[$this->csvFieldArr[$v]]);
        }
	$this->csvArr = array_values($this->csvArr);
  }
  public function filterJProfileData($field)
  {
        $valueArr[$field]=$this->fieldStr;
        $profileObj = new JPROFILE();
        $matchedFieldInfo= $profileObj->getArray($valueArr,'','',$field);
	foreach($matchedFieldInfo as $k=>$v)
	{
		$matchedField[]=$v[$field];
	}
	$this->unsetCsvArrFields($matchedField);
  }
  public function filterLeads($field)
  {
	if($field=="EMAIL")
	{
		$sugarLeadsObj = new sugarcrm_email_addresses;
		$matchedField = $sugarLeadsObj->getLeadsWithEmails($this->fieldStr);
	}
	else if($field=="PHONE_MOB")
	{
		$sugarLeadsObj = new sugarcrm_leads;
		$matchedField = $sugarLeadsObj->getLeadsWithPhone($this->fieldStr);
	}
	$this->unsetCsvArrFields($matchedField);
  }
  public function createLeadData()
  {
	if(!is_array($this->csvArr))
		return;
	$cityArr = FieldMap::getFieldLabel("city_india",'',true);
	$cityArr = array_flip($cityArr);
	foreach($this->csvArr as $k=>$v)
	{
                if($v[$this->emailIndex]=='')
                        continue;
		foreach($this->fieldsArr as $key=>$value)
		{
			switch($value)
			{
				case "full_name"://full name
					$value = ucwords(strtolower(trim($v[$key])));
					$this->leadDataArr[$k]['last_name']=$value;
					break;
				case "email":
					$this->leadDataArr[$k]['email']=$v[$key];
					break;
				case "city":
					$city = ucwords(strtolower(trim($v[$key])));
					if(array_key_exists($city,$cityArr))
						$this->leadDataArr[$k]['city_c']=$cityArr[$city];
					break;
				case "date_of_birth":
					if($v[$key])
					{
						$dob = date('Y-m-d', strtotime($v[$key]));
						$this->leadDataArr[$k]['date_birth_c']=$dob;
					}
					break;
				case "gender":
					$genderCsv = strtolower(trim($v[$key]));
					if($genderCsv=="male"||$genderCsv=="m")
						$gender = "M";
					elseif($genderCsv=="female"||$genderCsv=="f")
						$gender = "F";
					$this->leadDataArr[$k]['gender_c']=$gender;
					break;
				case "phone_number":
					$this->leadDataArr[$k]['phone_mobile']=$v[$key];
					break;
			}
		}
	}
  }
  public function createLead()
  {
	if(is_array($this->leadDataArr))
	{
		foreach($this->leadDataArr as $k=>$v)
		{
			if($v['email'] && !in_array($v['email'],$emailArr))
			{
				$v['opt_in_c']='1';
				$v['status']='13';
				$v['disposition_c']='24';
				$v['js_source_c']='fb_leads';
				jscreate_lead($v);
				$emailArr[] = $v['email'];
			}
		}
		$emailAddressObj = new sugarcrm_email_addresses;
		$leadIdArr = $emailAddressObj->getEmailAddressLeadId($emailArr);	
		$leadsObj = new sugarcrm_leads;
		$campaign_id = "a97e7b56-c809-291a-57eb-57d90bf41daa";//id for FB_Leads_2016 campaign
		$leadsObj->updateLeadCampaign($leadIdArr,$campaign_id);
		$leadsCstmObj = new sugarcrm_leads_cstm; 
		$sourceid = '12';
		$leadsCstmObj->updateLeadSource($leadIdArr,$sourceid);
	}
  }
}
