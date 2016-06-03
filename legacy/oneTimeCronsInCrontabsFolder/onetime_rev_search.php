<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include_once("connect.inc");
$db=connect_db();
echo "start";
$sql="SELECT PARTNER_CASTE,PROFILEID FROM SEARCH_MALE_REV WHERE PARTNER_CASTE<>''";
$res=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$pid=$row['PROFILEID'];
	$caste=get_all_caste_str($row['PARTNER_CASTE']);
	if($caste!=$row['PARTNER_CASTE'])
	{	
		$sql_up="UPDATE SEARCH_MALE_REV SET PARTNER_CASTE=\"$caste\" WHERE PROFILEID=$pid";
		mysql_query($sql_up) or die(mysql_error());
		$counter++;
	}
}
echo "DONE";
echo $counter;
$counter=0;

$sql="SELECT PARTNER_CASTE,PROFILEID FROM SEARCH_FEMALE_REV WHERE PARTNER_CASTE<>''";
$res=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$pid=$row['PROFILEID'];
	$caste=get_all_caste_str($row['PARTNER_CASTE']);
	if($caste!=$row['PARTNER_CASTE'])
	{
		$sql_up="UPDATE SEARCH_FEMALE_REV SET PARTNER_CASTE=\"$caste\" WHERE PROFILEID=$pid";
		mysql_query($sql_up) or die(mysql_error());
		$counter++;
	}
}
echo "DONE";
echo $counter;

function get_all_caste_str($caste)
{
        $insert_caste=$caste;
        $castesql="select SQL_CACHE VALUE,PARENT,ISALL,ISGROUP,GROUPID from CASTE where VALUE in ($insert_caste)";
        $casteResult=mysql_query_decide($castesql) or logError($ERROR_STRING,$castesql,"ShowErrTemplate");
        while($casterow=mysql_fetch_array($casteResult))
        {
                if($casterow["ISALL"]=="Y")
                {
                        $castesql="select SQL_CACHE VALUE from CASTE where PARENT='" . $casterow["PARENT"] . "'";
                        $totalCaste=mysql_query_decide($castesql) or logError($ERROR_STRING,$castesql,"ShowErrTemplate");

                        while($totalCasterow=mysql_fetch_array($totalCaste))
                        {
                                $Caste_arr[]=$totalCasterow["VALUE"];
                        }
                }
                elseif($casterow["ISGROUP"]=="Y")
                {
                        $castesql="select SQL_CACHE VALUE from CASTE where GROUPID='" . $casterow["GROUPID"] . "'";
                        $totalCaste=mysql_query_decide($castesql) or logError($ERROR_STRING,$castesql,"ShowErrTemplate");

                        while($totalCasterow=mysql_fetch_array($totalCaste))
                        {
                                $Caste_arr[]=$totalCasterow["VALUE"];
                        }
                }
                else
                        $Caste_arr[]=$casterow["VALUE"];
        }

        if(is_array($Caste_arr))
        {
                $Caste_arr=array_unique($Caste_arr);
                $caste_str="'".implode("','",$Caste_arr)."'";
                return $caste_str;
	}
        else
                return "";
}

?>
