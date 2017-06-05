<?php
/*********************************************************************************************
* FILE NAME     : common_func_inc.php
* DESCRIPTION	: Contains common functions for Wedding Gallery
* CREATION DATE : 3 September, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

function get_wedding_category($ID)
{
	$sql="SELECT LABEL FROM wedding_classifieds.CATEGORY WHERE ID='$ID'";
	$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
	$row=mysql_fetch_array($res);
	return $row['LABEL'];
}

?>
