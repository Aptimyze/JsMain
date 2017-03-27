<?php
function pagination($whereis,$total_record,$results_to_show,$MORE_URL='')
{
        global $smarty;

        if($total_record >0)
        {
                $show=$total_record%10;


                if(!$show)
                {
                        //$pages=1;
                        $show=10;
                }
                //else
                        //$pages=1;

                //$pages+=intval($total_record/10);
                $pages=ceil($total_record/10);
                if($whereis==1)
                        $prev=0;
                else
                        $prev=$whereis-1;

                if($pages==$whereis)
                        $next=0;
                else
                        $next=$whereis+1;

                pagesarr($whereis,$pages);

		$smarty->assign("whereis",$whereis);
                $smarty->assign("next",$next);
                $smarty->assign("prev",$prev);
                $smarty->assign("pages",$pages);
                $smarty->assign("MORE_URL",$MORE_URL);

        }
}

function pagesarr($n,$l)
{
        global $smarty;
        //1 The current page of the search results is the first populated. (n)
        $arr[]=$n;

        //2 : The 5 pages before and after the current page are populated next, given that these are real numbers. (n-5…n…n+5)
        $start=( ($n-5) < 1 ? 1 : ($n-5) );
        $end=( ($n+5) > $l ? $l : ($n+5) );
        for($i=$start;$i<=$end;$i++)
        {
                $arr[]=$i;
        }

        //3 Next, the first 10 pages and then the last 10 pages are populated. (1…10 | n-5…n…n+5 | last-9…last)
        $start=1;
        $end=( ($l>9) ? 10 : ($l-9) );
        for($i=1;$i<=$end;$i++)//3
        {
                $arr[]=$i;
        }

        $start=( (($l-9)>1) ? ($l-9) : 1);
        $end=$l;
        for($i=$start;$i<=$end;$i++)
        {
                $arr[]=$i;
        }


	//4 Next the space between pages 10 and n-5 is divided into 10 equal parts and 10 page numbers are populated by rounding them off.
        $a=$n-5-10;
        if($a>20)
        {
                $start=$a/10;
                $end=$n-5;
                for($i=$start;$i<$end;$i=$i+$start)
                {
                        $tempK=round(10+$i);
                        if($tempK<=$n-4)
                        {
                                $arr[]=$tempK;
                        }
                }
        }

        //5 The space between pages n+5 and last-9 are divided into 10 equal parts and 10 page numbers are populated by rounding them off
        $a=$l-9-($n+5);
        if($a>20)
        {
                $start=$a/10;
                $end=$l-9;
                for($i=$start;$i<$end;$i=$i+$start)
                {
                        $tempK=round(($n+5)+$i);
                        if($tempK<=$l-8)
                        {
                                $arr[]=$tempK;
                                $arr111[]=$tempK;
                        }
                }
        }
	$arr=array_unique($arr);
        sort($arr);
        $smarty->assign("total_pages",$arr);
}

function get_lead_details_all($resultSet,$leadArr)
{
	$db =connect_slave();
	global $CASTE_DROP;
	global $CITY_DROP;
	global $CITY_INDIA_DROP;
	global $RELIGIONS;
	global $HEIGHT_DROP;
	global $OCCUPATION_DROP;
	global $INCOME_DROP;
	
	if(is_array($leadArr))
	{
		$leads=implode("','",$leadArr);
		$sql="SELECT id_c,posted_by_c as RELATION,gender_c AS GENDER,age_c AS AGE,height_c AS HEIGHT,mother_tongue_c AS MTONGUE,religion_c AS RELIGION,caste_c AS CASTE,income_c AS INCOME,education_c AS EDU_LEVEL_NEW,occupation_c AS OCCUPATION,city_c AS CITY_RES,marital_status_c as MSTATUS,username_c as USERNAME FROM sugarcrm.leads_cstm WHERE id_c IN('$leads')";
		$res=mysql_query_decide($sql,$db) or die("Error while fetching lead details   ".mysql_error($db));
		while($myrow=mysql_fetch_assoc($res))
		{
			/*
			$country_code=$myrow["COUNTRY_RES"];
			if($country_code==128)
			{
				$city=$myrow["CITY_RES"];
				$cityVal=$city;
				$city=$CITY_DROP["$city"];
				$country="USA";
				if($city!="")
					$residence=$city;
				else
					$residence=$country;
			}
			elseif($country_code==51)
			{
				$city=$myrow["CITY_RES"];
				$city=$CITY_INDIA_DROP["$city"];
				if($city)
				{
					$cityVal=$city;
					$residence=$city;
				}
				else
					$residence=$COUNTRY_DROP["$country_code"];
                        }*/

                        $city=$myrow["CITY_RES"];
                        $city=$CITY_INDIA_DROP["$city"];
                        if($city)
	                        $residence=$city;
                        else
        	                $residence=$COUNTRY_DROP["$country_code"];
			
			$caste0=explode("_",$myrow["CASTE"]);
			$caste=$caste0[1];
			$casteVal=$caste;
			$caste=$CASTE_DROP["$caste"];
                        $caste1=explode(":",$caste);

                        $religion='';
                        if($myrow["RELIGION"] && $myrow["RELIGION"]!=8)
                                $religion=$RELIGIONS[$myrow["RELIGION"]];
                        if(trim($caste1[1])=="")
                                $mycaste=$caste1[0];
                        else
                                $mycaste=$caste1[1];

                        $height=$myrow["HEIGHT"];
                        $height=$HEIGHT_DROP["$height"];
			$height1=explode("(",$height);

                        $occupation=$myrow["OCCUPATION"];
			$occupation=$OCCUPATION_DROP["$occupation"];
                        $income=$myrow["INCOME"];
			$mtongueVal=$myrow['MTONGUE'];
			$mtongue1 = label_select("MTONGUE",$myrow['MTONGUE']);
			$mtongue2 = label_select("MTONGUE_S",$myrow['MTONGUE']);
			$edu_leveln=label_select("EDUCATION_LEVEL_NEW",$myrow['EDU_LEVEL_NEW']);
                        $mtongue = $mtongue1[0];
                        $mtongue_s = $mtongue2[0];
                        $edu_level=$edu_leveln[0];
			$mstatusVal=$myrow['MSTATUS'];
			if($myrow['USERNAME'])
				$username =$myrow['USERNAME'];
			else
				$username =$myrow["id_c"];

			$resultSet[$myrow["id_c"]]=array ( "AGE" => $myrow["AGE"],
					"NAME"=>$username,
					"GENDER"=>$myrow["GENDER"],
					"RELATION"=>$myrow["RELATION"],
                                        "HEIGHT" => $height1[0],
                                        "CASTE" => $mycaste,
                                        "RELIGION" => $religion,
                                        "MTONGUE" => $mtongue,
					"MTONGUE_S"=>$mtongue_s,
                                        "OCCUPATION" => $occupation,
                                        "RESIDENCE" => $residence,
                                        "INCOME" => $INCOME_DROP["$income"],
                                        "INCOME_ID" => $income,
                                        "EDUCATION"=>$edu_level,
					"MSTATUS_VAL"=>$mstatusVal,
					"CASTE_VAL"=>$casteVal,
					"MTONGUE_VAL"=>$mtongueVal,
					"CITY_VAL"=>$cityVal
                                );
                }
	}
	return $resultSet;
}
function populateSearchBar($details)
{
	global $smarty;
	global $profileTypeArray;
	$matchTypeArray=array();
	if(is_array($details))
	{
		foreach($details["ALL_MATCHES"] as $key=>$value)
		{
			if($value["PROFILEID"])
				$details["ALLOW_PROFILES"][]=$value["PROFILEID"];
			elseif($value["LEAD_ID"])
				$details["LEADS"][]=$value["LEAD_ID"];
			if($value["MATCH_TYPE"]!='' && !in_array($value["MATCH_TYPE"],$matchTypeArray))
				$matchTypeArray[]=$value["MATCH_TYPE"];
		}
		if(is_array($details["ALLOW_PROFILES"]) || is_array($details["LEADS"]))
			search_pending_records($details);
		if(is_array($matchTypeArray))
		{
			foreach($matchTypeArray as $key=>$value)
				$matchTypes[]=array("VALUE"=>$value,
						"LABEL"=>$profileTypeArray[$value]);
			$smarty->assign("matchTypeArray",$matchTypes);
		}
	}
}
?>
