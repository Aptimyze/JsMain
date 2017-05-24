<?php
include_once('includes/bms_connect.php');
$sql_city="TRUNCATE TABLE bms2.CITY_ADS";
mysql_query($sql_city);
$sql_mtongue="TRUNCATE TABLE bms2.MTONGUE_ADS";
mysql_query($sql_mtongue);
$sql_caste="TRUNCATE TABLE bms2.CASTE_ADS";
mysql_query($sql_caste);
if(!$p)
	$p=1;
$table_kw=array('CITY_INDIA','CITY_USA','MTONGUE','CASTE');
for($i=0;$i<4;$i++)
{
	$sql="SELECT LABEL,VALUE FROM newjs.$table_kw[$i]";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		if($i==3)
		{
			if(ereg(":",$row['LABEL']))
			{
				$caste_label=explode(":",$row['LABEL']);
				$keyword=$caste_label[1];
			}
			else
				$keyword=$row['LABEL'];
		}
		else
			$keyword=$row['LABEL'];
		$path="http://www.google.com/search?client=jeevansathi&q=".rawurlencode($keyword)."&output=xml&hl=en&adsafe=high&num=0&ad=w8&adpage=$p&adtest=off&ip=198.65.112.205&useragent=Mozilla%2F4%2E51+%5Ben%5D+%28Win98%3B+U%29";
		$text="";
		$fp = @fopen($path,"r");
		while($txt = @fread($fp,1024))
		$text .= $txt;
		@fclose($fp);
		$parser = xml_parser_create();// Create an XML parser
		xml_parse_into_struct($parser,$text,$vals,$index);//Parse the result set into array
		xml_parser_free($parser);
		$no_of_ads=0;
		while($index['AD'][$no_of_ads])         //Store the start and end index of an ad
		{         
			$no_of_ads++; 
		} 
		$value=$row['VALUE'];
		if($i==0 || $i==1)
			$sql1="INSERT INTO bms2.CITY_ADS VALUES('','$value','$keyword',$no_of_ads)";
		else
			$sql1="INSERT INTO bms2.".$table_kw[$i]."_ADS VALUES('','$value','$keyword',$no_of_ads)";
		mysql_query($sql1);
	}
}
?>
