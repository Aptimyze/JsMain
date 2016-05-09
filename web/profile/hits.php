<?php
/*function to direct 10% of hits from a source to home page
function findpage($source)
{
	$date=date("Y-m-d H:i:s");
	$Sdate= $date." 00:00:00";
	$Edate= $date." 23:59:59";
	$sql= "SELECT COUNT(*) AS CNT FROM MIS.HITS WHERE SourceID='$source' AND PageName='Registrationpage'";
	$res= mysql_query_decide($sql) or die(mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$cnt_reg=$row["CNT"];
	
	$sql= "SELECT COUNT(*) AS CNT FROM MIS.HITS_HOME WHERE SourceID='$source' ";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_assoc($res);
        $cnt_home=$row["CNT"];

	if($cnt_reg>=(9*$cnt_home))
		$return=1;
	else
		$return=0;
	return $return;
	
}
// function to save hits from home page in MIS.HITS_HOME
function savehit_home($source)
{
        $date=date("Y-m-d G:i:s");
        if($source!="")
        {
                $sql="INSERT INTO MIS.HITS_HOME(SourceID,Date) VALUES('$source','$date')";
                mysql_query_optimizer($sql);
        }
}
*/
function savehit($source,$pagename)
{
	$ip=FetchClientIP();//Gets ipaddress of user
	if(strstr($ip, ","))    
	{                       
		$ip_new = explode(",",$ip);
		$ip = $ip_new[1];
	}
	$date=date("Y-m-d G:i:s");
	if($source!="" && !stristr($_SERVER['HTTP_USER_AGENT'],"Adsbot-Google"))
	{
		$sql="INSERT INTO MIS.HITS(SourceID,Date,PageName,IPADD) VALUES('$source','$date','$pagename','$ip')";
		mysql_query_optimizer($sql);
	}
}

function savehit_srch($source,$pagename)
{
	$ip=FetchClientIP();//Gets ipaddress of user
	if(strstr($ip, ","))    
	{
		$ip_new = explode(",",$ip);
		$ip = $ip_new[1];
	}
	$date=date("Y-m-d G:i:s");

	// if source is not blank and user-agent is not adsbot-google
	if($source!="" && !stristr($_SERVER['HTTP_USER_AGENT'],"Adsbot-Google"))
	{
		$sql="INSERT INTO MIS.SEARCH_HITS(SourceID,Date,PageName,IPADD) VALUES('$source','$date','$pagename','$ip')";
		mysql_query_decide($sql);
	}
}

?>
