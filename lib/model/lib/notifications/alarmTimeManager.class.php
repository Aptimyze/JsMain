<?php

/**
 * Description of alarmTimeManager.class.php
 *
 */

class alarmTimeManager {
  public static function getNextTime($alarmCurrentTime,$alarmMaxTime,$alarmMinTime)
  {
        $alarmCurrentArr = explode(":",$alarmCurrentTime);
        $alarmMaxArr = explode(":",$alarmMaxTime);
        if((($alarmMaxArr[0]==$alarmCurrentArr[0]) && ($alarmMaxArr[1]==$alarmCurrentArr[1]) && ($alarmMaxArr[2]==$alarmCurrentArr[2])) ||($alarmCurrentTime==""))
        {
                return $alarmCurrentTime = $alarmMinTime;
        }
        if($alarmCurrentArr[2]<"59")
        {
                $alarmCurrentArr[2]++;
        }
        elseif($alarmCurrentArr[1]<"59")
        {
                $alarmCurrentArr[2]="00";
                $alarmCurrentArr[1]++;

        }
        elseif($alarmCurrentArr[0]<"23")
        {
                $alarmCurrentArr[2]="00";
                $alarmCurrentArr[1]="00";
                $alarmCurrentArr[0]++;
        }
        else
        {
                $alarmCurrentArr[2]="00";
                $alarmCurrentArr[1]="00";
                $alarmCurrentArr[0]="00";
        }
        if(strlen($alarmCurrentArr[2])<2)
                $alarmCurrentArr[2]="0".$alarmCurrentArr[2];
        if(strlen($alarmCurrentArr[1])<2)
                $alarmCurrentArr[1]="0".$alarmCurrentArr[1];
        if(strlen($alarmCurrentArr[0])<2)
                $alarmCurrentArr[0]="0".$alarmCurrentArr[0];

        return  $alarmCurrentTime =  implode(":",$alarmCurrentArr);
  }
  public static function getNextDate($alarmTime='')
  {
        if($alarmTime==''){
                $h =rand(18,19);
                $i =rand(10,59);
                $s =rand(10,59);
                $alarmTime =$h.":".$i.":".$s;
        }
	/*$date  = date("Y-m-d");
	$todayAlarm = $date." ".$alarmTime;
	$currentTime = date("Y-m-d H:i:s");*/

	$alarmTimeObj =new MOBILE_API_ALARM_TIME();
	$alarmMinTime = NotificationEnums::$alarmMinTime;

	$currentIstTime =$alarmTimeObj->getIST();
	$currentIstDate =date("Y-m-d", strtotime($currentIstTime));
	$todayAlarmTime =$currentIstDate." ".$alarmTime;
	$chkAlarmTime =$currentIstDate." ".$alarmMinTime;

	$diff = JsCommon::dateDiffInSec($currentIstTime,$chkAlarmTime);
	if($diff>0){
		return $todayAlarmTime;
	}
	else
	{	
		$tomorrow = date("Y-m-d", JSstrToTime($currentIstDate)+86400);
		return $tomorrow." ".$alarmTime;
	}
  }
	
}
?>
