<?php
chdir(dirname(__FILE__));
include("../includes/bms_connections.php");
//include("../includes/bms_connect.php");
//$dbbms = getConnectionBms();

$sql="Select BannerId as ban,BannerServed as imp from BANNER";
$dt=date("Y-m-d");
$result=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_dailymis",mysql_error($dbbms).$sql);
  
$currdate=date('Y-m-d');
list($yr,$mn,$dt)=explode('-',$currdate);
$prevdate=date('Y-m-d',mktime(0,0,0,$mn,$dt-1,$yr));

if($myrow=mysql_fetch_array($result))
{
	do
	{
     		$ban=$myrow["ban"];
		$imp=$myrow["imp"];
		$banimp=$imp;
		$sql="Select sum(Impressions) as tot from BANNERMIS where BannerId='$ban'";
		$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_dailymis",mysql_error($dbbms).$sql);
      
		if($myr=mysql_fetch_array($res))
		{ 
			$total=$myr["tot"]; 
			$imps=$banimp-$total;
		} 
		else
		{
			$imps=$banimp;
     		}

     		if($imps!=0)
     		{
			$sql="Select * from BANNERMIS where BannerId='$ban' and Date='$prevdate'";
			$res=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_dailymis",mysql_error($dbbms).$sql); 
	
			if($row=mysql_fetch_array($res))
			{
				// for clickable banners when entry for clicks has been made
			        $sql="Update BANNERMIS set Impressions='$imps' where BannerId='$ban' and Date='$prevdate'";
				mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_dailymis",mysql_error($dbbms).$sql);	
			}
			else
			{	
				$sql="Insert into BANNERMIS(BannerId,Date,Impressions) values('$ban','$prevdate','$imps')";
				mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_dailymis",mysql_error($dbbms).$sql); 
			}
		}
	}while($myrow=mysql_fetch_array($result));
}

//Added by lavesh.
/*
$sql1="INSERT INTO bms2.DAILYMIS(BannerId,TO_DT)(SELECT BannerId,'$currdate' FROM bms2.BANNERHEAP)";
mysql_query($sql1,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_dailymis",mysql_error($dbbms).$sql1);
*/
?>
