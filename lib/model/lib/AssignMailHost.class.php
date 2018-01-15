<?php
class AssignMailHost
{
        public static $maxNumberArray = array("1"=>"5000",
                                                "2"=>"10000",
                                                "3"=>"15000",
                                                "4"=>"15000",
                                                "5"=>"25000",
                                                "6"=>"25000",
                                                "7"=>"50000",
                                                "8"=>"50000",
                                                "9"=>"100000",
                                                "10"=>"100000",
                                                "11"=>"100000",
                                                "12"=>"150000",
                                                "13"=>"150000",
                                                "14"=>"300000",
                                                "15"=>"300000",
                                                "16"=>"500000",
                                                "17"=>"500000",
                                                "18"=>"650000",
                                                "19"=>"650000",
                                                "20"=>"650000",
                                                "21"=>"900000",
                                                "22"=>"900000",
                                                "23"=>"1100000",
                                                "24"=>"1100000",
                                                "25"=>"1500000",
                                                "26"=>"1500000"
                                                );
        public static $shiftedFromArray = array("info@jeevansathi.com","register@jeevansathi.com","payments@jeevansathi.com","verify@jeevansathi.com","membership@jeevansathi.com","visitoralert@jeevansathi.com","contacts@jeevansathi.com");
		public static function getMailHost($from,$to)
		{
			$date = date("Y-m-d");

                        $date1 = "2016-01-13 00:00:00";

                        $date2    = date("Y-m-d H:i:s");

                        if($from!="matchalert@jeevansathi.com")
                        {
                                $date1="2016-01-01 00:00:00";
                        }

                        $diff = abs(JsCommon::dateDiff($date1,$date2));

			$transactionalMailerCount = JsMemcache::getInstance()->get("transactionIpCount".$date);

			if($transactionalMailerCount=='')
			{
				$transactionalMailerCount = 0;
			}

                        $randomNumber = rand(0,800000);

                        $maxNumber = self::$maxNumberArray[$diff];
                        if($diff>26)
			{
                                $maxNumber = self::$maxNumberArray[26];
			}

			$match = ($randomNumber<=$maxNumber)?true:false;

			$allowedMail = in_array($to,JsConstants::$mailAllowedArray)?true:false;

                        if($from=="matchalert@jeevansathi.com")
                        {
 				if($match || $allowedMail)
					return self::getNewMailHost($from,$to,$randomNumber);
                        }

                        elseif(in_array($from,self::$shiftedFromArray))
                        {
				$transactionalAllowed = ($transactionalMailerCount<=$maxNumber)?true:false;
				if(($match && $transactionalAllowed ) || $allowedMail)
				{
					JsMemcache::getInstance()->set("transactionIpCount".$date, $transactionalMailerCount+1);
					return self::getNewMailHost($from,$to,$randomNumber);
				}
                        }

			return self::getOldMailHost($from,$to,$randomNumber);
		}
		public static function getNewMailHost($from,$to,$randomNumber)
		{
			$date = date("Y-m-d");
				file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/Matched".$date.".txt",$to."(".$from.":".$randomNumber.")\n",FILE_APPEND);
			return JsConstants::$newMailHost.";".JsConstants::$localHostIp;
		}
		public static function getOldMailHost($from,$to,$randomNumber)
		{
			$date = date("Y-m-d");
			if(in_array($from,self::$shiftedFromArray)||$from=="matchalert@jeevansathi.com")
			{
				$oldMailerCount = JsMemcache::getInstance()->get("oldMailerCount".$date);
				if($oldMailerCount=='')	$oldMailerCount=0;
				file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/noMatch".$date.".txt",$to."(".$from.":".$randomNumber.")\n",FILE_APPEND);
				JsMemcache::getInstance()->set("oldMailerCount".$date, $oldMailerCount+1);
			}
			else
				file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/otherFroms".$date.".txt",$to."(".$from.":".$randomNumber.")\n",FILE_APPEND);
			return JsConstants::$mailHost.";".JsConstants::$localHostIp;
		}
}
