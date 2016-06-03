<?php
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

//MAKE CONNECTION TO MASTER AND SLAVE
include(JsConstants::$docRoot."/profile/connect.inc");
include_once("../../lib/model/lib/Flag.class.php");
$myDb_Slave = connect_slave();
$myDb_Master = connect_db();
function getCityData()
{
        global  $myDb_Slave;

        $sql = "SELECT LABEL, VALUE FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = '51'";
        $result = mysql_query($sql,$myDb_Slave);

        while($row = mysql_fetch_assoc($result))
        {
                $arrResult[strtolower(trim($row['LABEL']))] = $row['VALUE'];
        }

        return $arrResult;
}

function getCountryData()
{
        global  $myDb_Slave;

        $sql = "SELECT LABEL, VALUE FROM newjs.COUNTRY_NEW ";

        $result = mysql_query($sql,$myDb_Slave);

        while($row = mysql_fetch_assoc($result))
        {
                $arrResult[strtolower(trim($row['LABEL']))] = $row['VALUE'];
        }

        return $arrResult;
}
function mapNativePlaceData($iProfileID='')
{
        global $myDb_Slave,$myDb_Master;

        $arrCityMap     = getCityData();
        $arrCountryMap  = getCountryData();

		$sql = "SELECT PROFILEID, ANCESTRAL_ORIGIN,SCREENING FROM newjs.JPROFILE WHERE ANCESTRAL_ORIGIN<>''";
        $result = mysql_query($sql,$myDb_Slave);

        while($row = mysql_fetch_assoc($result))
        {
				
                $szValue        = trim($row['ANCESTRAL_ORIGIN']);
                $bMarkScreen	= false;
                $szState        = '';
                $szCity         = '0';
                $iProfileID 	= $row['PROFILEID'];
                $szCountry      = '';
                $screeningFlag = $row['SCREENING'];
                //$data_temp = "$iProfileID"." - $szValue"." - $screeningFlag \n ";
				//echo  $data_temp;
                if($szValue && strlen($arrCountryMap[strtolower($szValue)]) !=0)
                {
                        $szCountry      = $arrCountryMap[strtolower($szValue)];
                        $szCity         = '';
                        $szState        = '';
                        $bMarkScreen	= true;
                }
                else if($szValue && strlen($arrCityMap[strtolower($szValue)]) !=0 )
                {
                        $szCity         = $arrCityMap[strtolower($szValue)];
                        $szState        = substr($szCity,0,2);
                        if($szCity === $szState)//Case : State is present in open text field
							$szCity 	= '';
						$szCountry      = 51;
						$bMarkScreen	= true;
                }

				$sqlInsert = "INSERT INTO newjs.NATIVE_PLACE (PROFILEID,NATIVE_COUNTRY,NATIVE_STATE,NATIVE_CITY) VALUES ('$iProfileID','$szCountry','$szState','$szCity')";
				
                mysql_query($sqlInsert,$myDb_Master);
                
                if($bMarkScreen)
                {
					$screeningFlag = Flag::setFlag("ANCESTRAL_ORIGIN", $screeningFlag);
					$sqlupdate = "UPDATE newjs.JPROFILE SET `ANCESTRAL_ORIGIN`='',`SCREENING`='$screeningFlag' WHERE PROFILEID='$iProfileID'";
					//echo "$sqlupdate \n";
					mysql_query($sqlupdate,$myDb_Master);
				}
        }
}

function EndScript($st_Time='')
{
        $end_time = microtime(TRUE);
        $var = memory_get_usage(true);

         if ($var < 1024)
                $mem =  $var." bytes";
         elseif ($var < 1048576)
                $mem =  round($var/1024,2)." kilobytes";
         else
                $mem = round($var/1048576,2)." megabytes";


        echo $mem ."\n";
        echo $end_time - $st_Time;
        die;

}

$st_Time = microtime(TRUE);
mapNativePlaceData();
EndScript($st_Time);
mysql_close($myDb_Slave);
mysql_close($myDb_Master);
die;

?>
