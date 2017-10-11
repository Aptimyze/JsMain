<?php
/*********************************************************************************************
* FILE NAME   : 	editdesiredprofile.php
* DESCRIPTION : 	file to save and edit new fields of desired partner profile
* MODIFY DATE        : 30 July, 2005
* MODIFIED BY        : Gaurav Arora
* REASON             : New fields added in inputprofile to save desired partner profile causing this script to edit those fields.
  
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

if($mbureau=="bureau1")
	header("Location: $SITE_URL/profile/advance_search.php?checksum=$checksum&mbureau=$mbureau&FLAG=partner");
else
	header("Location: $SITE_URL/profile/advance_search.php?FLAG=partner&checksum=$checksum");
exit;
?>
