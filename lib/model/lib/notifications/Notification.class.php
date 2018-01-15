<?php
/**
 * class NotificationScheduler
 * 
 */
abstract class Notification
{
  public $variablesMaxlength = array();
  private $notificationSettingClass;
  public $messageDelimiters;
  public $notifications;
  public $casteDetail;
  public $cityDetail;
  public $countryDetail;

  public function __construct()
  {
	  include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	  $this->casteDetail = $CASTE_DROP;
	  $this->cityDetail = $CITY_INDIA_DROP;
	  $this->countryDetail = $COUNTRY_DROP;
  }
  public function getNotifications() { return $this->notifications;}
  public function setNotifications($notifications) { $this->notifications= $notifications;}
  public function getMaxLength($variableName)
  {
	  if($this->variablesMaxlength[$variableName])
		return $this->variablesMaxlength[$variableName];
	  return false;
  }
  public function getNotificationSettingClass(){ return $this->notificationSettingClass;}
  public function setNotificationSettingClass($notificationSettingClass){$this->notificationSettingClass= $notificationSettingClass;}
  public function getNotificationSettings($valueArray)
  {
	  $notificationSettingsObj = new $this->notificationSettingClass();
	  $settings =  $notificationSettingsObj->getArray($valueArray,'','','*','','NOTIFICATION_KEY ASC');
	  return $returnSettings = $this->organiseNotificationSettings($settings);
  }
  public function getNotificationDetail($valueArray)
  {
          $notificationSettingsObj = new $this->notificationSettingClass();
          $settings =  $notificationSettingsObj->getArray($valueArray,'','','*','','ID','1');
          return $settings;
  }
  abstract public function organiseNotificationSettings($settings);
  public function getNotificationBreakup($notificationMessage)
  {
	  $notificationBreakup = array($notificationMessage);
	  foreach($this->messageDelimiters as $delimiter)
	  {
		  $newNotificationBreakup = array();
		  foreach($notificationBreakup as $stringToSplit)
		  {
			  $tempBreakup = explode($delimiter, $stringToSplit);
			  foreach($tempBreakup as $temp)
			  {
				  $newNotificationBreakup[]=$temp;
			  }
		  }
		  $notificationBreakup=$newNotificationBreakup;
	  }
	  //return $notificationBreakup;
	  if (is_numeric($notificationBreakup[0]))
	  {
		  $flagPosition = "VARIABLE";        $nextPosition = "STATIC";
	  }
	  else
	  {
		  $flagPosition = "STATIC";        $nextPosition = "VARIABLE";
	  }
	  foreach ($notificationBreakup as $key => $value)
	  {
		  if ($key % 2 == 0)
			$return[$flagPosition][] = $value;
		  else
            $return[$nextPosition][] = $value;
	  }
      $return["flagPosition"]   = $flagPosition;
      return $return;
  }
  public function mergeNotification($arr1, $arr2)
  {
        $mrgMsg = $arr1[0];
        $cnt    = 0;
        foreach ($arr2 as $key => $value) {
            $mrgMsg .= $value;
            $cnt++;
            $mrgMsg .= $arr1[$cnt];
        }
        return $mrgMsg;
    }

  public function getVariableValue($variable, $details)
  {
	  $maxlength = $this->getMaxLength($variable);
	  $maxlength = ($maxlength)? $maxlength:200;
	  switch($variable)
	  {
	  case "USERNAME_SELF":
			  return strlen($details["SELF"]["USERNAME"])<=$maxlength ? $details["SELF"]["USERNAME"] : substr($details["SELF"]["USERNAME"],0,$maxlength-2) . "..";
          case "USERNAME_OTHER_1":
              if($details["NAME_OF_USER"]){
                  $username = $details["NAME_OF_USER"];
              }
              else{
                  $username = $details['OTHER'][0]["USERNAME"];
              }
              return strlen($username)<=$maxlength ? $username : substr($username,0,$maxlength-2) . "..";
          case "USERNAME_OTHER_2":
              return strlen($details['OTHER'][1]["USERNAME"])<=$maxlength ? $details['OTHER'][1]["USERNAME"] : substr($details['OTHER'][1]["USERNAME"],0,$maxlength-2) . "..";
          case "DISCOUNT":
              return $details["SELF"]["DISCOUNT"];
          case "MESSAGE_RECEIVED":
              return $details["MESSAGE_RECEIVED"];
          case "MESSAGE":
	      return $details["SELF"]["MESSAGE"];	
	  case "EDATE":
		return $details["SELF"]['EDATE'];
	  case "UPTO":
		return $details["SELF"]['UPTO'];
          case "MATCH_COUNT":
              $count = $details["MATCH_COUNT"];
	      if($count<10)
			return $count;
	      elseif($count<100)
			return $return =($count-($count%10))."+";
	      elseif($count<1000)
			return $return =($count-($count%100))."+";
	      else
			return $return =floor($count/1000)."k+";
    case "CONTACTS_COUNT":
      $count = $details["CONTACTS_COUNT"];
      return $count;
	  case "MATCHALERT_COUNT":
			return $details["MATCHALERT_COUNT"];
          case "EOI_COUNT":
			  return $details['EOI_COUNT'];
          case "AGE_OTHER_1":
              return $details['OTHER'][0]["AGE"];
          case "VISITOR_COUNT":
			  return $details["VISITOR_COUNT"];
	  case "CASTE_OTHER_1":
		$html = $this->casteDetail[$details['OTHER'][0]["CASTE"]];
		if(strstr($html,": "))
		{
			$first = strpos($html, ': ');
			$casteValue = substr($html, $first+2);
		}
		else 
			$casteValue = $html;
		return strlen($casteValue)<=$maxlength ? $casteValue : substr($casteValue,0,$maxlength-2)."..";
	  case "CITY_RES_OTHER_1":
		if($details['OTHER'][0]["COUNTRY_RES"]=="51"){
            if(substr($details['OTHER'][0]["CITY_RES"], -2) == "OT"){
                $CITY_RES= $this->cityDetail[substr($details['OTHER'][0]["CITY_RES"],0,2)];
            }
            else{
                $CITY_RES= $this->cityDetail[$details['OTHER'][0]["CITY_RES"]];		
            }
        }
		else
			$CITY_RES= $this->countryDetail[$details['OTHER'][0]["COUNTRY_RES"]];
		return strlen($CITY_RES)<=$maxlength ? $CITY_RES : substr($CITY_RES,0,$maxlength-2)."..";
	  }
  }
  public function getDate($days)
  {
        if ($days == 0) {
            $timestamp = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        } else
            {
            $hrs                = $days * 24;
            $timestamp = mktime(date("H") - $hrs, date("i"), date("s"), date("m"), date("d"), date("Y"));
        }
        return $dateformat     = date("Y-m-d", $timestamp);
  }
  public function getProfileApplicableForNotification($profiles,$notificationKey)
  {
          $applicableProfiles=array();
          $notifications = $this->getNotifications();
          foreach($notifications[$notificationKey] as $k=>$notificationKeyDetails)
                $timeCriteria = $notificationKeyDetails['TIME_CRITERIA'];
          $smsTempTableObj = new newjs_SMS_TEMP_TABLE;
          $varArray['PROFILEID']=implode(",",$profiles);
          if($timeCriteria!='')
          {
                  $dateformat = $this->getDate($timeCriteria);
                  $varArray['LAST_LOGIN_DT']=$dateformat;
                  //$greaterThan['LAST_LOGIN_DT']=$dateformat." 00:00:00";
                  //$lessThan['LAST_LOGIN_DT']=$dateformat." 23:59:59";
          }
          $profiles = $smsTempTableObj->getArray($varArray,'',$greaterThan,$fields="PROFILEID",$lessThan);
          foreach($profiles as $k=>$v)
                $applicableProfiles[] = $v['PROFILEID'];
          return $applicableProfiles;
  }
}
?>
