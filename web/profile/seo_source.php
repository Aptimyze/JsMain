<?php

include('connect.inc');

$db=connect_db();

$sql="SELECT SOURCE FROM newjs.COMMUNITY_PAGES WHERE ID > '684'";
$res=mysql_query($sql,$db);
while($row=mysql_fetch_array($res))
{
	$src=$row['SOURCE'];
 	$sql_9="INSERT INTO MIS.SOURCE (SourceID,SourceName,GROUPNAME,ACTIVE) VALUES ('$src','$src','SEO_COM_PAGE_L1','Y')";
	mysql_query($sql_9,$db);
}

/*$sql="SELECT ID,TYPE FROM newjs.COMMUNITY_PAGES WHERE LEVEL='1' ";
$res=mysql_query($sql,$db);
while($row=mysql_fetch_array($res)){
	$id=$row['ID'];
	$type=$row['TYPE'];
	$append=substr($type,0,2);	
	$src="L1_".$append."_".$id;
 	$sql_1="UPDATE newjs.COMMUNITY_PAGES SET SOURCE='$src' WHERE ID='$id' AND TYPE='$type'";
	mysql_query($sql_1,$db);

 	$sql_9="INSERT INTO MIS.SOURCE (SourceID,SourceName,GROUPNAME,ACTIVE) VALUES ('$src','$src','SEO_COM_PAGE_L1','Y')";
	mysql_query($sql_9,$db);

}

$sql_2="SELECT ID,PARENT_TYPE FROM newjs.COMMUNITY_PAGES_MAPPING";
$res_2=mysql_query($sql_2,$db);
while($row=mysql_fetch_array($res_2)){
	$id_1=$row['ID'];
	$type_1=$row['PARENT_TYPE'];
	$append_1=substr($type_1,0,2);	
	$src_1="L2_".$append_1."_".$id_1;
 	$sql_3="UPDATE newjs.COMMUNITY_PAGES_MAPPING SET SOURCE='$src_1' WHERE ID='$id_1' AND PARENT_TYPE='$type_1'";
	mysql_query($sql_3,$db);

 	$sql_10="INSERT INTO MIS.SOURCE (SourceID,SourceName,GROUPNAME,ACTIVE) VALUES ('$src_1','$src_1','SEO_COM_PAGE_L2','Y')";
	mysql_query($sql_10,$db);
}

$sql_1="SELECT SOURCE FROM newjs.COMMUNITY_PAGES_MAPPING";
$res_1=mysql_query($sql_1,$db);
while($row_1=mysql_fetch_array($res_1))
{
	$src_1=$row_1['SOURCE'];
 	$sql_9="INSERT INTO MIS.SOURCE (SourceID,SourceName,GROUPNAME,ACTIVE) VALUES ('$src_1','$src_1','SEO_COM_PAGE_L2','Y')";
	mysql_query($sql_9,$db);
}*/

?>
