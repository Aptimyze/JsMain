<?php 
/**
*       Filename        :       mainmenu.php
*       Description     :
*       Created by      :
*       Changed by      :
*       Changed on      :
        Changes         :       New Service added called Eclassified , changes done due to it.
**/
	
	//to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it
	include('connect.inc');
	$smarty->display("what_icons.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
