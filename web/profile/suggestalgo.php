<?php
/**********************************************************************************************************************
*    FILENAME           : suggestalog.php
*    DESCRIPTION        : This is Suggestion Tool for the "Jeevansathi Gothra".It will Suggest Similar Gothra's respect to Input provided by users.
*    ALGORITHM          : It is using Edit Distance Algorithm for finding the Similarity among 2 words.
*    Instruction        : Please make sure that you have filled the array i.e -> "$words=array(   )",Put whole worldlist in array.
			  For User -> Please fill your Input in between the " $input = '' ".
*    CREATED BY         : Anurag Gautam
*    Date               : 29th July 2008
***********************************************************************************************************************/
//include_once("connect.inc");

$input = urlencode(check_for_valid_chars($gotra));

$db=connect_db();

$sql = "SELECT SQL_CACHE GOTHRA FROM newjs.GOTHRA_LIST";
$result = mysql_query($sql) or logError("1",$sql);

$i=0;
unset($ans);

while($row=mysql_fetch_array($result))
{
	$ans[$i] = $row['GOTHRA'];
	$i++;
}

$shortest = -1;

foreach ($ans as $word)
{
	$lev = levenshtein($input, $word);
	if ($lev == 0)
	{
		$closest = $word;
		$shortest = 0;
		break;
	}

	if ($lev <= $shortest || $shortest < 0)
	{
		$closest  = $word;
		$shortest = $lev;
		$a[]=$closest;
	}
}

$size = sizeof($a) - 1;
for($i=$size-3;$i<$size+1;$i++)
	$got_val[]=$a[$i];

$count = 0;
for($i=0;$i<count($ans);$i++)
{
	if(preg_match("/^$input/",$ans[$i]))
	{
		$res[] = $ans[$i];
		$count++;
	}
}

$res_string1 = $res;
$res_string1[] = $got_val[3];
$res_string1[] = $got_val[2];
$res_string1[] = $got_val[1];

//sort($res_string1);
if(count($res_string1)){
$res_string1 = array_unique($res_string1);
echo @implode("|#|",$res_string1);
}else echo "";

function check_for_valid_chars($value)
{
	//$pattern = "/^[a-zA-Z\"]*$/";
	$pattern = "/^[a-zA-Z]/";
	if(preg_match($pattern,$value))
		return addslashes(stripslashes($value));
	else
		die;
}

?>
