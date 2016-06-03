<?php
                                                                                                 
/****************************************************************************************************************************
*       FILENAME        :       template_preview.php
        CREATED By      :       Gaurav Arora on 12 May 2005
        INCLUDED        :       connect.inc
*                              functions used :authenticated
* *       DESCRIPTION     : this file is used to preview a selected template.
*
****************************************************************************************************************************/


include_once("connect.inc");
//include_once("temp_customise.php");   
$sql="SELECT * FROM BANNER_TEMPLATE WHERE ID='$ID'";
$res=mysql_query_decide($sql);
$row=mysql_fetch_array($res);
$smarty->assign("temp_headline",$row['HEADLINE']);
$smarty->assign("temp_topband",$row['TOP_BAND']);

$smarty->display("../jeevansathi/registration_pg1.htm");

?>
