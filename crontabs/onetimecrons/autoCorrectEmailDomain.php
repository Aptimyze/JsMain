<?php

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($curFilePath."../connect.inc");

$myDbSlave=connect_slave();
mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$myDbSlave);

$invalidDomainArr = array("gamil.com"=>"gmail.com","gmai.com"=>"gmail.com","gmil.com"=>"gmail.com","gmal.com"=>"gmail.com","gmaill.com"=>"gmail.com","gmail.co"=>"gmail.com","gail.com"=>"gmail.com","gmail.om"=>"gmail.com","gmali.com"=>"gmail.com","gmail.con"=>"gmail.com","gmail.co.in"=>"gmail.com","gmail.cm"=>"gmail.com","gmail.in"=>"gmail.com","gimal.com"=>"gmail.com","gnail.com"=>"gmail.com","gimail.com"=>"gmail.com","g.mail.com"=>"gmail.com","gmailil.com"=>"gmail.com","gmail.cim"=>"gmail.com","gemail.com"=>"gmail.com","gmall.com"=>"gmail.com","gmail.com.com"=>"gmail.com","gmeil.com"=>"gmail.com","gmsil.com"=>"gmail.com","gmail.comn"=>"gmail.com","gmail.cpm"=>"gmail.com","gimel.com"=>"gmail.com","gmailo.com"=>"gmail.com","gmile.com"=>"gmail.com","fmail.com"=>"gmail.com","yhoo.com"=>"yahoo.com","yaho.com"=>"yahoo.com","yahool.com"=>"yahoo.com","yhaoo.com"=>"yahoo.com","yahoo.co"=>"yahoo.com","yaoo.com"=>"yahoo.com","yhaoo.co.in"=>"yahoo.com","yahoo.com.in"=>"yahoo.co.in","yamil.com"=>"ymail.com","yhoo.in"=>"yahoo.in","yahho.com"=>"yahoo.com","yahoo.com.com"=>"yahoo.com","redifmail.com"=>"rediffmail.com","reddifmail.com"=>"rediffmail.com","reddffmail.com"=>"rediffmail.com","rediffmaill.com"=>"rediffmail.com","rediffmai.com"=>"rediffmail.com","rediffmal.com"=>"rediffmail.com","reddiffmail.com"=>"rediffmail.com","redifffmail.com"=>"rediffmail.com","rediffimail.com"=>"rediffmail.com","rediiffmail.com"=>"rediffmail.com","rediifmail.com"=>"rediffmail.com","rediffmil.com"=>"rediffmail.com","rediffmail.co"=>"rediffmail.com","rediffmail.con"=>"rediffmail.com","rediffmail.cm"=>"rediffmail.com","rediffmial.com"=>"rediffmail.com","redffimail.com"=>"rediffmail.com","rdiffmail.com"=>"rediffmail.com","radiffmail.com"=>"rediffmail.com");

foreach($invalidDomainArr as $key=>$val)
{
	$whereSql[] = " EMAIL LIKE "."'%@$key'"." ";
}
$whereSqlStr = implode("OR",$whereSql);
$date = date("Ymd", strtotime("-5 months"));
$date  = date("Y-m-d",strtotime($date));
$sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE ENTRY_DT > '$date' AND ACTIVATED <> 'D' AND activatedKey=1 AND ($whereSqlStr)";
$res = mysql_query_decide($sql,$myDbSlave) or die(mysql_error($myDbSlave));
mysql_close($myDbSlave);
$count = 0;

while($row = mysql_fetch_array($res))
{
	$myDb = connect_db();
	$email = $row["EMAIL"];
	$emailTrim = trim($email);
	$first = strstr($emailTrim, '@',true);
	$firstLength = strlen($first);
	$emailLength = strlen($emailTrim);
	$domainLength = $emailLength - $firstLength -1;
	$domain = substr("$email",-$domainLength);
	$newMail = $first."@".$invalidDomainArr[strtolower($domain)];
	
	$checkSql = "SELECT EMAIL FROM newjs.JPROFILE WHERE EMAIL = '$email'";
	$resCheck = mysql_query_decide($checkSql,$myDb) or die(mysql_error($myDb));
	if(!$no = mysql_num_rows($resCheck))
		continue;
	
	$count++;
	$sqlUpdate = "UPDATE newjs.JPROFILE SET EMAIL = '$newMail' WHERE EMAIL = '$email'";
	mysql_query_decide($sqlUpdate,$myDb) or cronlogError($newMail,$email);
	
}
mysql_close($myDb);
$suc = $count-$error;
echo "Cron tried to update $count email ids.\nSuccess:".$suc."\nFail: $error\nSee email_error.txt for more details.\n";

function cronlogError($email,$emailOld)
{
	global $error;
	$error++;
	$file = 'email_error.txt';
	if(file_exists($file))
		$str = file_get_contents($file);
	$str .= "'$emailOld'=>'$email' already exists in database.\n";
	file_put_contents($file, $str);
}

?>
