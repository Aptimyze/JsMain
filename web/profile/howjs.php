<?php
        //to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

        include("connect.inc");

        $lang=$_COOKIE["JS_LANG"];

        $db=connect_db();
        $data=authenticated($checksum);
        if($data)
                login_relogin_auth($data);
        /******************************CODE ADDED FOR BMS*************************************/
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);
        /****************************************************************************************/
        include_once("sphinx_search_function.php");//to be tested later
        savesearch_onsubheader($data["PROFILEID"]);//to be tested later
        $smarty->assign("CHECKSUM",$checksum);
        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
        $smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
        $smarty->assign("head_tab",'my jeevansathi');
        $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
        //$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
        rightpanel($data);
        $smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
        $smarty->display("how_js_works.htm");
        // flush the buffer
        if($zipIt)
                ob_end_flush();
?>

