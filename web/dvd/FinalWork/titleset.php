<?
function create_titleset($profile_arr,$temp,$search_num)
{
	global $profileid_costumer;
	$titleset="<titleset><menus><pgc><pre>jump title 2;</pre></pgc></menus><titles>";	
	$cnt=count($profile_arr);
	//if($cnt==6)
		//$cnt=1;
	$count=intval(($cnt-1)/6)+1;
	$no_of_prof=count($profile_arr);
	///for search pages
	$profile_start=$count+2; //Where 15 is the button that will come on search screen.
	$titleset.="<pgc><post> jump title 2;</post><vob file=\"Main/help.mpg\" pause=\"15\"/></pgc>";
	
	//echo "\n".$profile_start."--".$no_of_prof;
	if(!file_exists("search/".$profileid_costumer."_search_".$temp."_0_6.mpg"))
	{
		$titleset.="<pgc><post>call vmgm menu 1;</post><vob file=\"Main/no_search_res.mpg\" pause=\"3\"/></pgc>";
	}	
	else
	{
		for($i=0;$i<$count;$i++)	
		{
				
				$profileid=$profile_arr[$i];
				
				$first_button="jump title ".check_profile_exist($profile_start,$count,$no_of_prof,$i);
				
				$second_button="jump title ".check_profile_exist($profile_start+1,$count,$no_of_prof,$i);
				
				$third_button="jump title ".check_profile_exist($profile_start+2,$count,$no_of_prof,$i);
				
				$fourth_button="jump title ".check_profile_exist($profile_start+3,$count,$no_of_prof,$i);
				
				$fifth_button="jump title ".check_profile_exist($profile_start+4,$count,$no_of_prof,$i);
				
				$sixth_button="jump title ".check_profile_exist($profile_start+5,$count,$no_of_prof,$i);
				$profile_start=$profile_start+6;
				$seventh_button="call vmgm menu 1";
				if($i!=0)
				$eigth_button="jump title ".($i+1);
				else
				{
					$eigth_button="jump title ".($count+1);
				}
				if(($i+1)==$count)
					$nineth_button="jump title 2";
				else
					$nineth_button="jump title ".($i+3);
					
				//Help button	
				$tenth_button="jump chapter 2";
				
				//$titleset.="<pgc><button> ".$first_button." ;</button> <button> ".$second_button."; </button> <button> ".$third_button."; </button> <button> ".$fourth_button."; </button><button> ".$fifth_button."; </button><button> ".$sixth_button."; </button><button> ".$seventh_button."; </button><button> ".$eigth_button."; </button><button> ".$nineth_button."; </button><button> ".$tenth_button."; </button><vob file=\"search/".$profileid_costumer."_search_".$temp."_".$i."_6.mpg\" pause=\"3\" /></pgc>";
				$titleset.="<pgc><button> ".$first_button." ;</button> <button> ".$second_button."; </button> <button> ".$third_button."; </button> <button> ".$fourth_button."; </button><button> ".$fifth_button."; </button><button> ".$sixth_button."; </button><button> ".$seventh_button."; </button><button> ".$eigth_button."; </button><button> ".$nineth_button."; </button><button> ".$tenth_button."; </button><vob file=\"search/".$profileid_costumer."_search_".$temp."_".$i."_6.mpg\" pause=\"inf\" /><vob file=\"Main/help.mpg\"  pause=\"15\" /><post>jump chapter 1;</post></pgc>";
		}
		$start_from=$count+1;
		for($i=0;$i<$no_of_prof;$i++)	
		{
			
				$start_from=$count+2+$i;
				$profileid=$profile_arr[$i];
				$first_button="jump chapter 1";
				$second_button="jump chapter 2";
				$third_button="jump chapter 3";
				$fourth_button="jump chapter 4";
				
				//Dummy button
				$fifth_button="jump chapter 5";
				//$sixth_button="jump chapter 1";
				if($i%5==0 && $i!=0)
					$sixth_button="jump title ".(intval(($i+1)/6)+1);
				else
					$sixth_button="jump title ".(intval(($i+1)/6)+2);
				
				$seventh_button="call vmgm menu 1";
				if($i==0)
					$eight_button="jump title ".(intval(($i+1)/6)+2);
				else
					$eight_button="jump title ".($start_from-1);
				$last=$i+1;
				if($last==$no_of_prof)
					$ninth_button="jump title ".(intval(($i+1)/6)+2);
				else
					$ninth_button="jump title ".($start_from+1);
					
				//Help button
				$tenth_button="jump chapter 6";
				$titleset.="<pgc><button> ".$first_button." ;</button> <button> ".$second_button."; </button> <button> ".$third_button."; </button> <button> ".$fourth_button."; </button><button> ".$fifth_button."; </button><button> ".$sixth_button."; </button><button> ".$seventh_button."; </button><button> ".$eight_button."; </button><button> ".$ninth_button."; </button><button> ".$tenth_button.";</button>";
				for($tm=1;$tm<6;$tm++)
				{
					
					$titleset.="<vob file=\"profile/".$profileid."_4_".$tm.".mpg\" pause=\"inf\" />";
				}
				$titleset.="<vob file=\"Main/help.mpg\"  pause=\"15\" /><post>jump chapter 1;</post>";
				$titleset.="</pgc>";
		}
		$titleset.="<pgc><post> call vmgm menu 1;</post><vob file=\"Main/help.mpg\" pause=\"15\"/></pgc>";
		
	}		
	$titleset.="</titles></titleset>";
	return $titleset;
	
}
function check_profile_exist($profile_start,$search_num,$no_of_prof,$i)
{
	
	
	if(($search_num+$no_of_prof+1)>=$profile_start)
		return ($profile_start);
	else
		return ($i+2);
}
