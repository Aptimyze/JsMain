<?php
include('connect.inc');
$db = connect_ddl();
//$sql="LOCK TABLE billing.OFFER_DISCOUNT_TEMP WRITE";
//mysql_query($sql,$db) or die(mysql_error1($sql,$db));

$sql="SELECT * FROM billing.OFFER_DISCOUNT_TEMP";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));
if(mysql_num_rows($res))
{
	//$entry_dt=date('Y-m-d');
	$values ='';
	$sql2= "INSERT IGNORE INTO billing.OFFER_DISCOUNT (PROFILEID,DISCOUNT,SERVICEID,EXPIRY_DT) VALUES ";
	while($row= mysql_fetch_array($res))
	{
		$pid=trim($row['PROFILEID'],'\r');
		$disc=trim($row['DISCOUNT'],'\r');
		$serviceid=trim($row['SERVICEID'],'\r');
		$expiry_dt=trim($row['EXPIRY_DT'],'\r');


			if($i<100)
			{
				if($values!='')
					$values.=", ";
				
				$values.= "( '".$pid."', '".$disc."', '".$serviceid."', '".$expiry_dt."')";
				$i++;
			}
			else
			{
				$sql1=$sql2.$values;
                	        mysql_query($sql1,$db) or die(mysql_error1($sql1,$db));
				$values= "( '".$pid."', '".$disc."', '".$serviceid."', '".$expiry_dt."')";
                	        $i=0;
			}
	}
	if($values!='')
        {
                $sql1=$sql2.$values;
                mysql_query($sql1,$db) or die(mysql_error1($sql1,$db));
        }

}

$sql="TRUNCATE TABLE billing.OFFER_DISCOUNT_TEMP";
mysql_query($sql,$db) or die(mysql_error1($sql,$db));

function mysql_error1($sql,$db)
{
die($sql.mysql_error($db));
        mail("manoj.rana@naukri.com","Error in populating variable discount csv from Manual Allot",mysql_error($db));
}

?>
