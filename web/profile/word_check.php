<?php
$chat_size=4;
$level=$argv[17];

for($i=0;$i<$chat_size*$chat_size;$i++)
{
	
	$first=$i/$chat_size;
	$second=$i%$chat_size;
	$k=$i+1;
	$words[$first][$second]=$argv[$k];
	
}

//print_r($words);die;
$z[-1]="";
$already_used[0][0]=0;
for($i=0;$i<$chat_size;$i++)
{
	for($j=0;$j<$chat_size;$j++)
	{
		$w=g_str($i,$j,$words[$i][$j],$level,0,"");		
		unset($already_used);
		$already_used[0][0]=0;
	
	}
}
for($start=0;$start<3;$start++)
{
	unset($already_used);
	$already_used[0][0]=0;

	$words=rotate($words,$chat_size);
	$actual[$start]=count($z);
	for($i=0;$i<$chat_size;$i++)
	{
	        for($j=0;$j<$chat_size;$j++)
        	{
			$w=g_str($i,$j,$words[$i][$j],$level,0,"");
	                unset($already_used);
                	$already_used[0][0]=0;

        	}
	}
}
$z=array_unique($z);
echo count($z);
for($i=0;$i<count($z);$i++)
{
	$val=$z[$i];
	//echo $val."--";
	$k=SHELL_EXEC("grep -e '^$val$' /usr/share/dict/words");
	//if($val=='send')
	//{	echo "bye".$k."000";
	//die;}
	if($k!="")
	{
		if($i==$actual[0])
		echo "---------first round over ---------";
		if($i==$actual[1])
		echo "---------second round over ---------";
		if($i==$actual[2])
		echo "---------third round over ---------";
		echo $data="---- $val  ---";
	}
}

function rotate($array,$size)
{
	$st=$size-1;
	for($i=0;$i<$size;$i++)
	{
		for($j=0;$j<$size;$j++)
		{
			$words[$j][$st]=$array[$i][$j];
		}
		$st=$st-1;
	}
	return $words;
}
function g_str($i,$j,$word,$level,$reached,$sequence)
{
	
	global $already_used;
	global $words;
	global $z;
	global $chat_size;
//	if(strstr($word,"toil"))
//		echo $word;	
	//echo "+++".$already_used[$i][$j]."+++\n";
	//echo "$i-->$j-->$already_used[$i][$j]-->$word-->$reached";
	if(strstr($sequence,",$i-$j,"))
	{
		return '';
	}
	if($i<0 || $j<0 || $j>=$chat_size || $i>=$chat_size)
		return '';
	$sequence.=",$i-$j,";
	$reached++;
	if($level>$reached)
	{
//		echo 1;
		$fr=$i-1;
		$sr=$j-1;
		g_str($i-1,$j-1,$word.$words[$i-1][$j-1],$level,$reached,$sequence);
		$fr=$i-1;
                $sr=$j;
		g_str($i-1,$j,$word.$words[$i-1][$j],$level,$reached,$sequence);
		$fr=$i-1;
                $sr=$j+1;
		g_str($i-1,$j+1,$word.$words[$i-1][$j+1],$level,$reached,$sequence);
		$fr=$i;
                $sr=$j+1;
		g_str($i,$j+1,$word.$words[$i][$j+1],$level,$reached,$sequence);
		$fr=$i+1;
                $sr=$j+1;
		g_str($i+1,$j+1,$word.$words[$i+1][$j+1],$level,$reached,$sequence);
		$fr=$i+1;
                $sr=$j;
		g_str($i+1,$j,$word.$words[$i+1][$j],$level,$reached,$sequence);
		$fr=$i+1;
                $sr=$j-1;
		g_str($i+1,$j-1,$word.$words[$i+1][$j-1],$level,$reached,$sequence);
		$fr=$i;
                $sr=$j-1;
		g_str($i,$j-1,$word.$words[$i][$j-1],$level,$reached,$sequence);
	}
	if($level<=$reached)
		if(strlen($word)>=$level)
		{//echo "---".$word;
			////if(strtolower($word)=='toilet')
			//	die('hi');
			if(!in_array($word,$z))
				$z[]=$word;
		}
	return '';
	
}
?>
