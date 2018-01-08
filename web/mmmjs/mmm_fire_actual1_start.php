<?
// connection string
$db=@mysql_connect(MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT],MysqlDbConstants::$alerts[USER],MysqlDbConstants::$alerts[PASS]) or die("In connect at connecting db");
@mysql_select_db("mmmjs",$db);

$sql = "select MAILER_ID from MAIN_MAILER where S1_FIRE='Y'";
$result = mysql_query($sql) or die($sql." : ".mysql_error());
while($row=mysql_fetch_array($result))
{
        $mailerid_fire[]=$row[MAILER_ID];
}

if($mailerid_fire)
{
        foreach($mailerid_fire as $mailer_id)
        {
		$sql="select TABLE_NAME from MAILER_SERVER where MAILER_ID=$mailer_id";
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res);
		if(substr($row['TABLE_NAME'],-2)=="s1")
			passthru(JsConstants::$php5path." -q ".JsConstants::$alertDocRoot."/mmmjs/mmm_fire_actual1.php $mailer_id 1 > output1.txt &");
		else
			passthru(JsConstants::$php5path." -q ".JsConstants::$alertDocRoot."/mmmjs/mmm_fire_actual1.php $mailer_id 2 > output2.txt &");
	}
}
?>
