<?php 
/*
  
  $pagelen = No of jobs shown in page // default 50
  $totalrec = No of record found for search
  $curpage = which page is shown currently
  $linkno = how many link is shown on page
  $link = link goes for numbers
*/

function pagelink($pagelen,$totalrec,$curpage,$linkno,$link,$scriptname="",$searchchecksum="",$newsearch="",$inf_checksum="",$castemapping="",$crmback="",$ordering="",$savesearch="",$label_select_no='',$from_index="",$viewall="",$hide_search_bar="",$save_search_redirect="",$seo_string="")
{
	global $smarty;

	/*IMPORTANT*/
	/*WHEN CREATING A LINK, VARIABLE $hide_search_bar WILL ALWAYS BE AT THE END for #accepted_members to work*/
	//if($hide_search_bar)
		$hide_search_bar="y#accepted_members";
	// set the links to be shown on each page to be five
	$linkno=10;

	$totalpage = $totalrec / $pagelen;
	$totalpage = ceil($totalpage);
	$curpage = round($curpage);
	$mid_linkno=abs(floor($linkno/2));
	$startwith=$curpage - $mid_linkno -1;
	if($startwith <= 0)
		$startwith=0;
	if(($startwith+$linkno) >= $totalpage)
		$startwith=$totalpage-$linkno;
	if($startwith <= 0)
		$startwith=0;

/*
	$startwith = $curpage / $linkno;
	$startwith = abs(floor($startwith - 0.1));
	$startwith = $startwith * $linkno;
*/
	if($totalpage > ($startwith + $linkno))
	{
	  	$totallinkshow = $linkno;
	  	if($totalpage < ($startwith + ($linkno * 2)))
	    	$lastlink = $startwith + $totalpage;
	  	else
	    	$lastlink = $startwith + $linkno + $totallinkshow;
	}
	else if($totalpage < ($startwith + ($linkno * 2)))
	{
	  	$totallinkshow = $totalpage - $startwith;
	  	$lastlink = "";
	} 
	else
	{
	  	$totallinkshow = $totalpage - $startwith;
	  	$lastlink = $startwith + $linkno + $totallinkshow;
	} 

	$prevwith=$startwith-9;
	//$linkvar = "Page : <strong>";
	if($startwith && !($startwith % $linkno))
	{
	 	if($seo_string)
			$linkvar .= "&nbsp;<a HREF=\"/matrimonial-matrimony-$seo_string-$ordering-".($startwith-1)*$pagelen."\">&lt;&lt;</a>&nbsp;";
		else
			$linkvar .= "&nbsp;<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($startwith-1)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">&lt;&lt;</a>&nbsp;";
	 }

	$linkvar.="</b>&lt;<b>";
	//Modified By lavesh
	if($curpage > 1)
	{
	 	if($seo_string)
			$linkvar .= "&nbsp;<a HREF=\"/matrimonial-matrimony-$seo_string-$ordering-".($curpage-2)*$pagelen."\">Previous</a>&nbsp;";
		else
			$linkvar .= "<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($curpage-2)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">Previous</a> ";
	}
        else
                $linkvar.="Previous ";

	for($i=1;$i<=$totallinkshow;$i++)
	{
		$nos = $startwith+$i;

		//line added By lavesh
		if($i==1)
			$linkvar .= "&nbsp;";
		//if($nos != $curpage)
		// $linkvar .= ($nos-1)*$pagelen;
		//else
		if($nos != $curpage)
		{	
			if($seo_string)
				$linkvar .= "&nbsp;<a HREF=\"/matrimonial-matrimony-$seo_string-$ordering-".($nos-1)*$pagelen."\">";
		 	else
				$linkvar .= "<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">";
		}
		else
		 	$linkvar .= "&nbsp;";

/*		if($i == $totallinkshow && $lastlink)
		 $linkvar .= "($nos - $lastlink)>></a></span>&nbsp;";
		else*/
		
		//4 lines added By lavesh 
		if($i == $totallinkshow)
			$linkvar .= "$nos</a>&nbsp;";
		elseif($curpage==$i)
			$linkvar .= "$nos&nbsp;|&nbsp;";
		else
		 	$linkvar .= "$nos</a>&nbsp;|&nbsp;";
	}
	
	if($lastlink)
	{
		$nos = $startwith+$i;
		if($seo_string)
			$linkvar .= "&nbsp;<a HREF=\"/matrimonial-matrimony-$seo_string-$ordering-".($nos-1)*$pagelen."\">";
		else
			$linkvar .= "&nbsp;<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($nos-1)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">";
		//$linkvar .= "($nos - $lastlink)</strong></a></span>&nbsp;";
		$linkvar .= "</a>&nbsp;";
	}
	

	$recordid = explode("i",$searchchecksum);
	if(count($recordid) > 1)
		$searchrecid = $recordid[1];
	else
		$searchrecid = $recordid[0];
	if($searchrecid!="")         
	{
		$sql = "Update newjs.SEARCHQUERY set PAGECOUNT = if(PAGECOUNT <= $curpage , '$curpage',PAGECOUNT) where ID ='$searchrecid'";
		//$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","Tampering with URL in search","ShowErrTemplate");
	}

	//Commented By lavesh
	/*if($curpage > 1)
		$linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=".($curpage-2)*$pagelen."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering\">Previous</a></span>";
	else 
		$linkvar.="Previous";
		
	$linkvar.="</font></strong> <strong>|";

	$linkvar.="<font color=\"#999999\">";*/
		
	if($curpage < $totalpage)
	{	
		if($seo_string)
			$linkvar .= "&nbsp;<a HREF=\"/matrimonial-matrimony-$seo_string-$ordering-".$curpage*$pagelen."\">Next</a>";
		else
			$linkvar .= "<a HREF=\"$scriptname?checksum=$link&searchchecksum=$searchchecksum&j=". $curpage*$pagelen ."&newsearch=$newsearch&inf_checksum=$inf_checksum&castemapping=$castemapping&crmback=$crmback&searchorder=$ordering&label_select_no=$label_select_no&savesearch=$savesearch&from_index=$from_index&viewall=$viewall&save_search_redirect=$save_search_redirect&hide_search_bar=$hide_search_bar\">Next</a>";
	}
	else 
		$linkvar.="Next";

	$linkvar.="&gt;";
	if($totalpage==0)
		$linkvar="";
	//echo "<br>linkvar : ".$linkvar;	
	$smarty->assign("PAGELINK",$linkvar);
	return $linkvar;
}//end function pagelink

function condition($keyword,$field1,$flag,$searchfield){

	//global $searchfield;
	//echo "<!-- flag ".$searchfield."-->";
	$strlength = 2;
	if (strpos($keyword,"~")){
		$eachkey=explode("~",$keyword);
		$cond=false;
		foreach($eachkey as $k){
			if (strlen($k)>$strlength) $cond=true;
		$keyword=str_replace("~"," ",$keyword);
		if ($cond && !$flag)
			$condition="match ($field1) against ('\"$keyword\"' in boolean mode)";
		else
			$condition="CONCAT_WS(\" \", $searchfield) like '% ".trim($keyword)." %'" ;	
			//$condition="CONCAT_WS(\" \", $field1) REGEXP \"[[:<:]]".$keyword."[[:>:]]\"" ;	
		}
		}

	else { 
		if (!$flag && strlen($keyword)>$strlength)
			$condition="match ($field1) against (\"$keyword\")";
		else 
			$condition="CONCAT_WS(\" \", $searchfield) like '% ".trim($keyword)." %'"     ;	
	}

	return $condition;
}

function whereclause($phrase,$fields,$type,$searchfield){
	//echo "Phrase : ".$phrase;
	$i=0;$fieldsval='';
	$strlength = 2;
	while($i<count($fields))
	{
	 $fieldsval .= $fields[$i].",";
	 $i=$i+1;
	}

	$field = substr($fieldsval,0,strlen($fieldsval)-1);
	
	$glossery = array(".net" => " net ",
			  ".NET" => " NET ",
			  "c++" => "c ++ ",
			  "C++" => "C ++ ",
			  "vc++" => "vc ++ ",
			  "VC++" => "VC ++ ",
			  "as/400" => "as / 400 ",
			  "AS/400" => "AS / 400 ",
			  "asp.net" => "asp . net ",
			  "vb.net" => "vb . net ",
			  "c#" => "c#",
			  "C#" => "C#",
			  "S/W" => "S / W ",
			  "s/w" => "s / w ",
			  "next" => "next",
			  "pro*c" => "pro*c",
			  "pro*cobol" => "pro*cobol");
	
	$exclude=array("we","to","on","and","the","see","an","is","yet","be","us","our","at","has","are","it","you","of","in","for","or","any","not","as","its","by","my","use","was","can","etc","no","me","had","at","all","who","his","own","ltd","new","pvt","top","<br","exp","one","two",">","he","if","but","go");

	if ($type=="all"){
		$all=explode(" ",$phrase);
		foreach($all as $a){
		$flag=0;
		if(isset($glossery[$a])){
			$flag = 1;
			$a = $glossery["$a"];
		}
		if (strlen($a)>$strlength && !$flag)
			$fulltextp=$fulltextp." +".$a;
		else if(strlen(trim($a)) and (!in_array($a,$exclude)))
			$strsearchp="CONCAT_WS(\" \", $searchfield) like '% ".trim($a)." %'"." and ".$strsearchp;
			
		if (isset($fulltextp) and !isset($strsearchp))
			$whereclause="match ($field) against ('$fulltextp' in boolean mode)";
		elseif (!isset($fulltextp) and isset($strsearchp))
			$whereclause=$strsearchp." 1=1";
		else
			$whereclause="match ($field) against ('$fulltextp' in boolean mode) and $strsearchp"." 1=1";
		}
	}
	
	elseif ($type=="any"){
			$any=explode(" ",$phrase);
			foreach($any as $a){
			$flag=0;
			if(isset($glossery[$a])){
				$flag = 1;
				$a = $glossery["$a"];
			}
			if (strlen($a)>$strlength && !$flag)
				$fulltextp=$fulltextp." ".$a;
			else if(strlen(trim($a)) and (!in_array($a,$exclude)))
				$strsearchp="CONCAT_WS(\" \", $searchfield) like '% ".trim($a)." %'"." or ".$strsearchp;
			if (isset($fulltextp) and !isset($strsearchp))
				$whereclause="match ($field) against ('$fulltextp')";
			elseif (!isset($fulltextp) and isset($strsearchp))
				$whereclause=$strsearchp." 1=2";
			else
				$whereclause="match ($field) against ('$fulltextp') or $strsearchp"." 1=2";
			}
	}
	
	
	elseif ($type=="exact"){
			$eachkey=explode(" ",$phrase);
			$cond=false;
			foreach($eachkey as $k){
			$flag=0;
			if (strlen($k)>$strlength) $cond=true;
			if(isset($glossery[$k])){
				$flag = 1;
				$temp = $glossery["$k"];
				$phrase = str_replace("$k","$temp",$phrase);
				
			}
			}
			if ($cond && !$flag)
				$whereclause="match ($field) against ('\"$phrase\"' in boolean mode)";
			else
				$whereclause="CONCAT_WS(\" \", $searchfield) like '% ".trim($phrase)." %'" ;	
	}


	elseif ($type=="bool") {
	
		$phrase=str_replace("'","\"",$phrase);
		$piece=explode("\"",$phrase);
  		$i=1;
 		while(isset($piece[$i])){
		 	$piece[$i]=ereg_replace(" ","~",$piece[$i]);
		 	$i=$i+2;
		}
 		
 		$phrase=implode($piece," ");
 		$phrase=str_replace("(","( ",$phrase);
 		$phrase=str_replace(")"," )",$phrase);	
 		$keywords=explode(" ",$phrase);
		$eachwhere=array();
		$i=0;	
		foreach ($keywords as $k){
			$flag=0;
		 	if(isset($glossery[$k])){
				$flag = 1;
				$k = $glossery["$k"];
			}
			if ($k=="and" or $k=="or" or $k=="not" or $k=="(" or $k==")" or (!$flag and strpos($k," ")) or strlen($k)==0)
		 		$eachwhere[$i]=$k;
		 	else{
		 		$eachwhere[$i]=condition($k,$field,$flag,$searchfield);
		  	}
				 	
			$whereclause=$whereclause." ".$eachwhere[$i];
		 	$i++;	 
	 	}
	 	
	 	
}

return $whereclause;

}

#echo whereclause('"are sal man" and (mnc or fmcg) or "pharma"  and not chutiyapa',"cv","boolean") ;
//echo whereclause("mys php lin","cv,post","exact");

?>
