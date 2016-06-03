<?php

include("connect.inc");
$db=connect_db();

$sql="SELECT SQL_CACHE * from twowaymatch.TRENDS";
//$sql="SELECT SQL_CACHE * from twowaymatch.TRENDS WHERE PROFILEID=144111";
$res_main=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res_main))
{
	$weight_mtongue=$row['W_MTONGUE'];
	$weight_caste=$row['W_CASTE'];
	$weight_age=$row['W_AGE'];
	$weight_income=$row['W_INCOME'];
	$weight_height=$row['W_HEIGHT'];
	$weight_mstatus=$row['W_MSTATUS'];
	$weight_country=$row['W_NRI'];
	$weight_manglik=$row['W_MANGLIK'];
	$weight_education=$row['W_EDUCATION'];
	$weight_occupation=$row['W_OCCUPATION'];
	$weight_city=$row['W_CITY'];
	$pid=$row["PROFILEID"];

	$weight_ordering=arrangeInDescOrderSortByWeight($weight_mtongue,$weight_caste,$weight_age,$weight_income,$weight_height,$weight_mstatus,$weight_country,$weight_manglik,$weight_education,$weight_occupation,$weight_city);

	$sqlu='UPDATE twowaymatch.TRENDS SET  WEIGHT_ORDERING ="'.$weight_ordering.'" WHERE PROFILEID='.$pid;
	mysql_query($sqlu) or die(mysql_error());

	unset($weight_mtongue);
	unset($weight_caste);
	unset($weight_age);
	unset($weight_income);
	unset($weight_height);
	unset($weight_mstatus);
	unset($weight_country);
	unset($weight_manglik);
	unset($weight_education);
	unset($weight_occupation);
	unset($weight_city);
}
function arrangeInDescOrderSortByWeight($weight_mtongue,$weight_caste,$weight_age,$weight_income,$weight_height,$weight_mstatus,$weight_country,$weight_manglik,$weight_education,$weight_occupation,$weight_city)
{
	$default_arr['RELIGION']=100;
	$default_arr['HAVECHILD']=100;
	$default_arr['MTONGUE']=$weight_mtongue;
	$default_arr['CASTE']=$weight_caste;
	$default_arr['HEIGHT']=$weight_height;
	$default_arr['EDU_LEVEL_NEW']=$weight_education;
	$default_arr['OCCUPATION']=$weight_occupation;
	$default_arr['INCOME']=$weight_income;
	$default_arr['INDIA_NRI']=$weight_country;
	$default_arr['UNMARRIED_MARRIED']=$weight_mstatus;
	$default_arr['MANGLIK']=$weight_manglik;
	$default_arr['DIET']=0;
	$default_arr['RELATION']=0;
	$keys=array_keys($default_arr);
	$values=array_values($default_arr);
	$len=count($keys);
	for($i=$len-2;$i>=0;$i--)
	{
		for($j=0;$j<=$i;$j++)
		{
			if($values[$j]<$values[$j+1])
			{
				$tempval=$values[$j];
				$values[$j]=$values[$j+1];
				$values[$j+1]=$tempval;
				$tempkey=$keys[$j];
				$keys[$j]=$keys[$j+1];
				$keys[$j+1]=$tempkey;
			}
		}
	}

	/*for($i=0;$i<$len;$i++)
	{
		$sorted_arr[$keys[$i]]=$values[$i];
	}*/
	
	$final_str=implode(',',$keys);
		
        return $final_str;
}

?>
