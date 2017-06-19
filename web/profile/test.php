<?

$new = urlencode("<a href='test'>Test,</a>");
echo $new; // &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;

$arr[8]=1;
$arr[12]=4;
$arr[11]=3;
$arr[34]=5;
$arr[23]=10;
$arr[4]=10;
asort($arr);
print_r($arr);
$temp=$arr;
$val=array_pop($arr);
echo $val." ---";
echo " ".array_search($val,$temp);
print_r($arr);
$temp=$arr;
echo '----'.$val=array_pop($arr);
echo "niki".array_search($val,$temp);

//echo array_pop($arr);
//echo array_pop($arr);
?>
