<?php 
include("connect.inc");

/**** THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE**********************/
                                                                                                 
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
                                                                                                 
$smarty->assign("cid",$cid);
                                                                                                 
/****************************AUTHENTICATION ROUTINE ENDS HERE*********************************/

	$startdate = mktime(0,0,0,date("m")-1,date("d"),date("Y"));
	$enddate = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$today=date("Y-m-d");
																     
	$dates = array();
	for($time = $enddate ; $time >= $startdate ; $time -= 86400)
		$dates[] = array("DATE"=>date("d-m-y",$time),"DISP"=>date("jS F (D)",$time));
?>
	<html>
	<body>
	<center>
	<h4>Graph</h4>
	<form name="form1" method="get" action="graph.php">
		<select name="sid" size=1>
			<option value=0>Select</option>
			<option value=1>linux11638.dn.net</option>
			<option value=2>linux11600.dn.net</option>
			<option value=3>linux11195.dn.net</option>
			<option value=4>linux11862.dn.net</option>
			<option value=5>linux10082.dn.net</option>
			<option value=6>linux10081.dn.net</option>
		</select>
		<select name="type" size=1>
			<option value=6>MailQ</option>
		</select>
		<select name="date" size=1>
		<?php for($i=0;$i<count($dates);$i++) echo "<option value=".$dates[$i]["DATE"].">".$dates[$i]["DISP"]."</option>\n" ?>
		</select>
	<input type="submit" name="graph" value="GO">
	</form>
	</center>
	</body>
	</html>
