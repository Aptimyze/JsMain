<?PHP
/****************************************************bms_logout.php*********************************************************/
  /*
	*	Created By         :	Abhinav Katiyar
	*	Last Modified By   :	Abhinav Katiyar
	*	Description        :	used to log out a user
	*	Includes/Libraries :	./includes/bms_connect.php
****************************************************************************************************************************/
include ("./includes/bms_connect.php");
logoutBms($id,"");
$msg="you have been logged out";
$smarty->assign("msg",$msg);
$smarty->display("$_TPLPATH/bms_loginpage.htm");

?>
