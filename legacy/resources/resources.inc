<?php
/***********************************************************************************************
 FILENAME       : resources.inc
 DESCRIPTION    : Defines the link number to start from each page, 
		  and number of links to show on each page	
 CREATED BY     : Rahul Tara
 DATE           : 13 May, 2005
 Copyright 2005, InfoEdge India Pvt. Ltd.
***********************************************************************************************/

$PAGE_LEN = 30;         // Number of links to be shown on each page


/***********************************************************************************************
 FUNCTION NAME : get_page_start
 DESCRIPTON    : Determines the set of records to be shown on each page
 RETURNS       : Returns the record number from which we start fetching links depending upon
		 the page number received as parameter

***********************************************************************************************/
	
function get_page_start($page_num)
{
	$MAINPAGE_START = 0;    // Show first 20 links starting from 0th record for main page
	$PAGE1_START = 30;      // Show links starting from 20th record for page 1
	$PAGE2_START = 60;     // Show links starting from 40th record for page 2
	$PAGE3_START = 90;     // Show links starting from 60th record for page 3

	
	if($page_num == 0)
		return $MAINPAGE_START;
	elseif($page_num == 1)
		return $PAGE1_START;
	elseif($page_num == 2)
		return $PAGE2_START;
	elseif($page_num == 3)
		return $PAGE3_START;
}
?>
