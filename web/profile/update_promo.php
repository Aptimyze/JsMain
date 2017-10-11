<?
include("connect.inc");
connect_db();
mysql_select_db_js('newjs');

$sql="SELECT RESIDENCE,MOBILE,PROFILEID from PROMOTIONAL_MAIL where PROFILEID!=-1 and (MOBILE=2147483647 or RESIDENCE=2147483647)";

$res=mysql_query_decide($sql);
while($row=mysql_fetch_array($res))
{
	$phone='';
	$PROFILEID=$row['PROFILEID'];
	           	                                                                                           
                                                                                                                            
	$sql1="select PROFILEID,PHONE_MOB,PHONE_RES from JPROFILE where PROFILEID='$PROFILEID'";
	$res1=mysql_query_decide($sql1);
	if($row1=mysql_fetch_array($res1))
	{
		if($row['MOBILE']=="2147483647" )
        	        $phone="MOBILE='".$row1['PHONE_MOB']."'";
	        if($row['RESIDENCE']=="2147483647")
        	{
		if($phone!="")
			$phone.=" ,RESIDENCE='".$row1['PHONE_RES']."'";       
		else
			$phone="RESIDENCE='".$row1['PHONE_RES']."'";                                                                                                                       
        	 }
	 
	$sql_update="update PROMOTIONAL_MAIL set $phone where PROFILEID=$PROFILEID";
	mysql_query_decide($sql_update);
	}
}
	


?>

