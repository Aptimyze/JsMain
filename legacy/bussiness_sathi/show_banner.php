<?php

/*********************************************************************************************
* FILE NAME     : show_banner.php
* DESCRIPTION   : Displays the banners chosen by the Affiliate
* INCLUDES      : connect.inc
* FUNCTIONS     : connect_db()          : To connect to the database server
* CREATION DATE : 28 June, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
$db=connect_db();

$sql="SELECT SQL_CACHE BANNERID,URL_CODE,TYPE,LANDPAGE FROM affiliate.BANNERS WHERE BANNERID='$bid'";
$res=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed",$sql);
if(mysql_num_rows($res)==1)
{
	$row=mysql_fetch_array($res);
	
/*********************************************************************************************
Changed By	: Shakti Srivastava
Change Date 	: 14 September, 2005
Reason		: To select landpage for a banner depending on the parameter passed to it
*********************************************************************************************/
	if($row['LANDPAGE']=='HOMEPAGE')
	{
		$landpage='HP';
	}
	else if($row['LANDPAGE']=='REGISTRATION')
	{
		$landpage='REG';
	}

	if($row["TYPE"]=='IMG')
	{
		echo "<html><body bgcolor=\"white\"><a href=\"http://www.jeevansathi.com/index.php?source=af".$aid.$bid."&landing=".$landpage."\" target=\"_blank\"><img border=0 src=\"".$row["URL_CODE"]."\"></a></body></html>";
	}
	else if($row["TYPE"]=='HTM')
	{
		$ban=$row["URL_CODE"];
		$ban=str_replace('$aid',$aid,$ban);
		$ban=str_replace('$bid',$bid,$ban);
		echo "<html><body>".$ban."</body></html>";
	}
}
else
{
	echo "Error while retrieving banners";
}

@mysql_close();
?>
