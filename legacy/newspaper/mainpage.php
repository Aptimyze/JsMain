<?php
include("connect.inc");

dbsql2_connect();
$data=authenticated($cid);

if(isset($data))
{
        $privilage=getprivilage($cid);
        $priv=explode("+",$privilage);
	
	if(in_array('NP',$priv))
		$mode = N;
/*	if(in_array('SMSP',$priv))
		$mode = S;
	if(in_array('AFFP',$priv))
		$mode = AFFP;
*/
	if(in_array('NP',$priv))
        {
		$linkarr[]="<a href=\"$SITE_URL/emailvalidation.php?name=$user&cid=$cid\">Modified Form For Newspaper Input</a>";
		$linkarr[]="<a href=\"newsppr_promotion.php?name=$user&cid=$cid&mode=$mode\">Input form for Newspaper </a>";
		$linkarr[]="<a href=\"showpromotiondetails.php?name=$user&cid=$cid\">User's Work details</a>";
        }

        if(in_array('NPA',$priv))
        {
               	$linkarr[]="<a href=\"affiliatemis.php?name=$user&cid=$cid\">Newspaper Records MIS</a>";         
		$linkarr[]="<a href=\"affiliatemis.php?name=$user&cid=$cid\">Promotions MIS at the server end </a><font color=brown>(This link is not working properly)</brown>";

//		$linkarr[]="<a href=\"http://192.168.2.206/jeevansathi/jsadmin/serv_promotionmis.php?name=$user&cid=$cid\">Promotions MIS at the server end </a><font color=brown>(This link is not working properly)</brown>";
	}

	$linkarr[]="<a href=\"change_passwd.php?name=$user&cid=$cid\">Change your password</a>";
	$smarty->assign("linkarr",$linkarr);
        $smarty->assign("name",$user);
        $smarty->assign("cid",$cid);
	$smarty->assign("mode",$mode);     
        $smarty->display("mainpage.htm");
}
else
{
        $smarty->assign("username",$user);
        $smarty->display("jsconnectError.tpl");
}
?>
