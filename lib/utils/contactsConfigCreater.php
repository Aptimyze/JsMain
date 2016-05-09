<?php

$socialRoot=realpath(dirname(__FILE__)."/../..");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function getContactsForLast1Day($contactType,$dtObject) {

$timeIntervalOf1day= new DateInterval('P1D');
$j=0;
for ($i=3;$i<6;$i++)
{
    
    $contactDbObject=new NEWJS_MESSAGE_LOG(JsDbSharding::getShardNo($i,true));  
    $arr[$j]=$contactDbObject->getContactsBasedOnTimeInterval($contactType,clone $dtObject,$timeIntervalOf1day);
    $j++;

}

return $arr;
}


         function getContactsConfig($arguments = array(), $options = array())
        {

$weeks=2; // no of weeks over which the average is calculated
$timeIntervalOf1day= new DateInterval('P1D');
$intervalOf1Hour = new DateInterval('PT1H');
    $dtObject=new DateTime();
    $h=$dtObject->format('H');
    date_time_set($dtObject,$h,0);

for($i=0;$i<7*$weeks;$i++){

$arr=getContactsForLast1Day('I',clone $dtObject);
foreach ($arr as $key => $value) {
foreach ($value as $key2 => $value2) {
if($key!=($value2['RECEIVER']%3)) continue;	
$temp=new DateTime($value2['DATE']);
$temp->add($intervalOf1Hour);
$day=$temp->format('l');
$hr=$temp->format('H');
if(!$EOIConfig[$day][$hr])$EOIConfig[$day][$hr]=0; 
$EOIConfig[$day][$hr]+=1;
}
}

$dtObject->sub($timeIntervalOf1day);
}
    $dtObject=new DateTime();
    $h=$dtObject->format('H');
    date_time_set($dtObject,$h,0);

for($i=0;$i<7*$weeks;$i++){
$arr=getContactsForLast1Day('A',clone $dtObject);
foreach ($arr as $key => $value) {
foreach ($value as $key2 => $value2) {
if($key!=($value2['RECEIVER']%3)) continue;	
$temp=new DateTime($value2['DATE']);
$temp->add($intervalOf1Hour);
$day=$temp->format('l');
$hr=$temp->format('H');
if(!$ACCEPTconfig[$day][$hr])$ACCEPTconfig[$day][$hr]=0; 
$ACCEPTconfig[$day][$hr]+=1;
}
}

$dtObject->sub($timeIntervalOf1day);

}
foreach ($EOIConfig as $key => $value) {
    foreach ($value as $key2 => $value2) {
        $EOIConfig[$key][$key2]=floor($EOIConfig[$key][$key2]/($weeks));
    }
}
foreach ($ACCEPTconfig as $key => $value) {
    foreach ($value as $key2 => $value2) {
        $ACCEPTconfig[$key][$key2]=floor($ACCEPTconfig[$key][$key2]/($weeks));
    }
}
$returnArr=array($EOIConfig,$ACCEPTconfig);
return $returnArr; 


		}





$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/enums/contactsAverageConfig.class.php","w");
$configArray = getContactsConfig();
$EOIConfig=$configArray[0];
$ACCEPTConfig=$configArray[1];
$outString="<?php \n//created from contactsConfidCreater.php\nclass contactsAverageConfig {\npublic static \$EOIConfig=array(\n";
//$outString='';
//	print('ggggggg');
			foreach ($EOIConfig as $key => $value) {
				$outString.=("'$key'"." => array( ");
				foreach ($value as $key2 => $value2) {
					$outString.=("'$key2'"." => ".$value2." ,\n");
				}
				$outString.=("),\n");
			}
			$outString.=(");\n\n");
// eoiconfig section ends

$outString.=("public static \$ACCEPTConfig=array(\n");
			foreach ($ACCEPTConfig as $key => $value) {
				$outString.=("'$key'"." => array(\n ");
				foreach ($value as $key2 => $value2) {
					$outString.=("'$key2'"." => ".$value2." ,\n");
				}
				$outString.=("),\n");
			}
			$outString.=(");\n\n");
$outString.=("}");

	//print_r($outString); die;


fwrite($fp,$outString);

			
fclose($fp);
?>
