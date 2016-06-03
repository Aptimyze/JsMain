<?php
chdir(dirname(__FILE__));
//$_CRONPATH="/usr/local/apache/htdocs/saurabh/bms";

//include("$_CRONPATH/includes/bms_connections.php");
include("../includes/bms_connections.php");
  $maillist="lavesh.rawat@jeevansathi.com";
  
  $date=date("Y-m-d");
  
  $sql="Select b.BannerId,z.ZoneName,r.RegName,c.CampaignName from bms2.BANNER b,bms2.REGION r,bms2.ZONE z,bms2.CAMPAIGN c where b.BannerStatus='ready' and b.BannerStartDate<='$date' and z.ZoneId=b.ZoneId and r.RegId=z.RegId and c.CampaignId=b.CampaignId";
  $result=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_makelive",mysql_error($dbbms).$sql);  
  
  if($myrow=mysql_fetch_array($result))
  {
  	$sendstring="<Table border=1><TR><TH align=center>BannerId</TH><TH>Zone/Region</TH><TH>Campaign</TH></TR>";
  	do 
  	{
  		if($banstr)
  		{
  			$banstr.=",".$myrow["BannerId"];
  		}
  		else
  		{
  			$banstr=$myrow["BannerId"];
  		}
  		$sendstring.="<TR><TD align=center>".$myrow["BannerId"]."</TD><TD align=center>".$myrow["ZoneName"]."/".$myrow["RegName"]."</TD><TD align=center>".$myrow["CampaignName"]."</TD></TR>";
  	}while($myrow=mysql_fetch_array($result));
  }
  
  $sql="Update bms2.BANNER set BannerStatus='live' where BannerStatus='ready' and BannerStartDate<='$date'";
  mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in bms_makelive",mysql_error($dbbms).$sql);

  if($sendstring)
  {
	 $banarr=explode(",",$banstr);

	 for($i=0;$i<count($banarr);$i++)
	 {
	 	makeBannerHeapEntry($banarr[$i]);
	 }
	 
	 	
  	 sendMail($maillist,$sendstring);  	  
  }
  
  function sendMail($email,$output)
  {
	global $dirname,$date,$dates;
   	$announce_subject = "Banners that went live on $date";
   	$announce_from_email = "bmsjs@jeevansathi.com";
	$announce_from_name = "Webmaster";
	$announce_to_email = $email;
	$announce_bcc="lavesh.rawat@jeevansathi.com";

	$MP = "/usr/sbin/sendmail -N failure -t";
	$MP .= " -f $announce_from_email";
	$fd = popen($MP,"w");
	fputs($fd, "To: $announce_to_email\n");
   	fputs($fd, "Bcc: $announce_bcc\n");
   	fputs($fd, "From: $announce_from_email \n");
   	fputs($fd, "Subject: $announce_subject \n");
   	fputs($fd, "X-Mailer: PHP3\n");
   	fputs($fd, "Content-type: text/html; charset=us-ascii \n");
   	fputs($fd, "Content-Transfer-Encoding: 7bit \n");
   	fputs($fd, "\n\n");
   	fputs($fd, "$output");
   	fputs($fd, "\r\n.\r\n");
   	$rcode=pclose($fd);
   	return $rcode;
  }

/* 		makes the entry of a banner in the banner heap table(only called when the banner is made live)	  
		input: bannerid
		output:none
*/
function makeBannerHeapEntry($bannerid)
{
	global $dbbms;


	$sqlexists="select * from bms2.BANNERHEAP where BannerId='$bannerid'";
	$resexists=mysql_query($sqlexists,$dbbms);
	if(mysql_num_rows($resexists))
	{
		$sql="update bms2.BANNERHEAP set BannerCount=0 where BannerId='$bannerid'";
	}
	else
	{
		$sql="insert into bms2.BANNERHEAP values('','$bannerid','0','0')";
	}
	$res=mysql_query($sql,$dbbms);


// To create a copy of BANNERHEAP TABLE
        $sqlexists="select * from bms2.BANNERHEAPCOPY where BannerId='$bannerid'";
        $resexists=mysql_query($sqlexists,$dbbms);
        if(mysql_num_rows($resexists))
        {
                $sql="update bms2.BANNERHEAPCOPY set BannerCount=0 where BannerId='$bannerid'";
        }
        else
        {
                $sql="insert into bms2.BANNERHEAPCOPY values('','$bannerid','0','0')";
        }
        $res=mysql_query($sql,$dbbms);
	
	
	$sqlcount="select b.BannerId as bannerid from bms2.BANNER a, bms2.BANNER b where a.ZoneId=b.ZoneId and a.BannerPriority=b.BannerPriority and a.BannerDefault=b.BannerDefault and a.BannerId='$bannerid'";
	$rescount=mysql_query($sqlcount,$dbbms);
	while($myrow=mysql_fetch_array($rescount))
	{
		$str.="'$myrow[bannerid]',";
		$count++;
	}
	$str=substr($str,0,-1);
	$str="(".$str.")";

	$sqlreplace="update bms2.BANNERHEAP set BANNERCOUNT=0 where BannerId in $str";
	$rescount=mysql_query($sqlreplace,$dbbms);
	$sqlreplace="update bms2.BANNERHEAPCOPY set BANNERCOUNT=0 where BannerId in $str";
        $rescount=mysql_query($sqlreplace,$dbbms);

	if($count>1)
	{
		$msg="count-->".$count;
		$msg.="<br>";
		$msg.="query is".$sqlreplace;

		mail('lavesh.rawat@jeevansathi.com','URGENT-bms_makelive.php',$msg);	
	}
} 
?>
