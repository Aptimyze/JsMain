<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


ini_set('max_execution_time',0 );
ini_set('memory_limit',-1);

include ("connect.inc");
$db=connect_db();

$sql="SELECT count(*) as cnt FROM SUCCESS_POOL WHERE EVER_LIVE='N'";
$result=mysql_query($sql,$db);
$row=mysql_fetch_array($result);

if($row['cnt'] < 4)
{
	successfullDie('less than 4');
}	
else
{
	$sql="UPDATE SUCCESS_POOL set CURRENT_LIVE='N' WHERE CURRENT_LIVE='Y'";
	$result=mysql_query($sql,$db);

	$sql2="update SUCCESS_POOL set CURRENT_LIVE='Y', EVER_LIVE='Y' where  EVER_LIVE='N' order by RAND() LIMIT 4";
	$result2=mysql_query($sql2,$db);

}
//Send Mail

$message = "Hello Anurag \n Congratulation ! Your Records are Live Now\nFrom: \nTeam -> Jeevansathi";
$message = wordwrap($message, 70);
mail('Anurag.Gautam@Jeevansathi.com', 'Jeevansathi Succes Stories Live', $message);

?>
