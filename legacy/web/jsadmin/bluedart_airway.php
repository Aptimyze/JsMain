<?php

include('connect.inc');
$db = connect_db();

$sql="SELECT * FROM billing.BLUEDART_AIRWAY_TMP";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));
if(mysql_num_rows($res))
{
	$sql2= "INSERT IGNORE INTO billing.BLUEDART_AIRWAY (AIRWAY_NUMBER,ENTRY_DT) VALUES";
	while($row= mysql_fetch_array($res))
	{
		$air=trim($row['AIRWAY_NUMBER'],'\r');
		$edate=trim($row['ENTRY_DT'],'\r');
		if($values!='')
			$values.=", ";
		$values.= "('".$air."', '".$edate."')";
	}

	if($values!='')
        {
                $sql1=$sql2.$values;
                mysql_query($sql1,$db) or die(mysql_error1($sql1,$db));
        }

}

$sql="TRUNCATE TABLE billing.BLUEDART_AIRWAY_TMP";
mysql_query($sql,$db) or die(mysql_error1($sql,$db));

function mysql_error1($sql,$db)
{
	die($sql.mysql_error($db));
        send_mail("anurag.gautam@jeevansathi.com","Error in BlueDart Populating csv :: Table BLUEDART_AIRWAY",mysql_error($db));
}

?>
