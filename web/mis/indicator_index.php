<?php
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if($cid)
	$data=authenticated($cid);
else if($checksum)
	$data=authenticated($checksum);

if(isset($data))
{
	$mtharr=array(	"01"=>"January",
			"02"=>"February",
			"03"=>"March",
			"04"=>"April",
			"05"=>"May",
			"06"=>"June",
			"07"=>"July",
			"08"=>"August",
			"09"=>"September",
			"10"=>"October",
			"11"=>"November",
			"12"=>"December");

	$hd=opendir("/usr/local/indicators/");
                                                                                                                            
	while(($filen=readdir($hd))!==false)
	{
		if($filen!="." && $filen!="..")
		{
			$temp=explode("_",$filen);
			if(count($temp)>1)
			{
				$tempname=explode(".",$temp[1]);
				if(array_key_exists($temp[0],$mtharr))
					$filearr[$tempname[0]][$temp[0]]=$filen;
			}
			else
			{
				$tempyr=explode(".",$temp[0]);
				$yearmis[$tempyr[0]][]=$filen;
			}
		}
	}
                                                                                                                            
	closedir($hd);

//	sort($filearr);

	if($cid)
		$smarty->assign("cid",$cid);
	elseif($checksum)
		$smarty->assign("cid",$checksum);
	$smarty->assign("mtharr",$mtharr);
	$smarty->assign("filearr",$filearr);
	$smarty->assign("yearmis",$yearmis);
	$smarty->display("indicator_index.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
