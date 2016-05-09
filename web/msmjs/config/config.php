<?php
	/*********Smarty****************/
	include(JsConstants::$smartyDir);
	$msmjsRoot = JsConstants::$alertDocRoot."/msmjs";
	$smarty=new Smarty;
	$smarty->template_dir=$msmjsRoot."/templates";
	$smarty->compile_dir=$msmjsRoot."/templates_c";
	$smarty->assign($siteUrl,JsConstants::$siteUrl);
	/***********Ends here************/

	/*********mySql connection*********/
	include_once($msmjsRoot."/lib/Utility.class.php");	
	$db=@mysql_connect(MysqlDbConstants::$alerts['HOST'].':'.MysqlDbConstants::$alerts['PORT'],MysqlDbConstants::$alerts['USER'],MysqlDbConstants::$alerts['PASS']) or logError("In connect at connecting db","");
	@mysql_select_db("mmmjs",$db);
	/*********Ends here****************/

?>
