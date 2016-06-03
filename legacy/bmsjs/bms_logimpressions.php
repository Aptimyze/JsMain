<?php
include("./includes/bms_connect.php"); 
header("Pragma: no-cache");
header("Cache-Control: no-cache , must-revalidate");

 if($banlist and trim($banlist)!=''){
     $sql="Update bms2.BANNERHEAP set BannerServed=(BannerServed+1),BannerCount=(BannerCount+1) where BannerId IN ($banlist)";
    mysql_query($sql,$dbbms) or logErrorBms("bms_logimpressions.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");

    //$sql="Update bms2.BANNERHEAPCOPY set BannerServed=(BannerServed+1),BannerCount=(BannerCount+1) where BannerId IN ($banlist)";
    //mysql_query($sql,$dbbms) or logErrorBms("bms_logimpressions.php:1: <br><!--$sql(".mysql_error($dbbms).")-->:".mysql_errno($dbbms),$sql,"continue","YES");

 }  

	mysql_close();
?>
