<?php
include ("connect.inc");
$data = authenticated ( $cid );

$message = "";
$forward = "jsadmin_msg.tpl";

if ($data) {
	if(strstr($type,"OPS")==FALSE)
		$dbObj = new newjs_OBSCENE_WORDS ();
	else
		$dbObj = new jsadmin_OPS_OBSCENE_WORDS();
		
	
	if ($addBuzzword) {
		if (empty ( $buzzword )) {
			$message = "Empty field ! No word entered.";
		} else if ($dbObj->isPresentObsceneWord ( $buzzword )) {
			$message = "This buzzword already exists. Try adding another one.<br>";
		} else {
			$dbObj->addObsceneWord ( $buzzword );
			$message = "'$buzzword' added to Buzzword List.<br>";
		}
	} else if ($deleteBuzzword) {
		if (empty ( $deleteWords )) {
			$message = "No word(s) selected.<br>";
		} else {
			for($i = 0; $i < count ( $deleteWords ); $i ++) {
				$dbObj->deleteObsceneWord ( $deleteWords [$i] );
				$wordListStr .= ', \'' . $deleteWords [$i] . '\'';
			}
			$wordListStr = ltrim ( $wordListStr, ', ' );
			$message = "$wordListStr removed from Buzzword List.<br>";
		}
	} else { // View Buzzword List
		$wordList = $dbObj->getObsceneWord ( "ASC" );
		$forward = "addRemoveBuzzwords.htm";
	}
	$message .= "<br><a href=\"addRemoveBuzzwords.php?cid=$cid&type=$type\">$type Add / Remove buzzword</a>";
} else // user timed out
{
	$message = "Your session has been timed out<br>  ";
	$message .= "<a href=\"index.htm\">";
	$message .= "Login again </a>";
}
$smarty->assign ( "MSG", $message );
$smarty->assign ( "type", $type);
$smarty->assign ( "wordList", $wordList );
$smarty->display ( $forward );

