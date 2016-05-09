<?php
/****************************************************************************************************************************
*	FILENAME	: seo_matrimonialdisplay.php
*	INCLUDED        : (1)  connect.inc 
*			       functions used :
*			       authenticated($checksum) : for the authentication of the user	
*			       label_select()           : generates label of a particular value from its respective table.
*
*			  (2)  display_result.inc
*			       functions used:
*			       pagelink($pagelen,$totalrec,$curpage,$linkno,$link,$scriptname,$searchchecksum,$flag) :
*			       This function displays total (totalrec) records pagewise with 'pagelen' number of records show				    on 'curpage'alongwith 'linkno' of links.
* 
*	DESCRIPTION     : Shows all the records of the table newjs.MATRIMONIAL with the option of editing any of the record
*
****************************************************************************************************************************/ 
	include("connect.inc");
	include("../profile/display_result.inc");
                                                                                                                             
        $PAGELEN=10;     // gives maximum limit of records that are shown on each page     
        $LINKNO=10;      // maximum no of links  shown on a particular page
   

        if(!$j )
                $j = 0;

        $sno=$j+1;

	if($checksum)
		$data = $checksum;
	elseif($cid)
		$data = $cid;
	if(authenticated($data))
	{

		$i = 0;

                $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM newjs.MATRIMONIAL WHERE GENDER='M' LIMIT $j,$PAGELEN";
                $res = mysql_query_decide($sql);
	
		$csql = "Select FOUND_ROWS()";
		$cres = mysql_query_decide($csql) or die(mysql_error_js());
		$crow = mysql_fetch_row($cres);
		$TOTALREC = $crow[0];
		
		$commarr = array("MTONGUE"  => "MTONGUE",
				 "COUNTRY"  => "COUNTRY",
			         "CITY"     => "CITY_INDIA",				
			         "RELIGION" => "CASTE");
		
		while ($row = mysql_fetch_array($res))
		{
			$label = label_select($commarr[$row["FLD"]],$row["COMMUNITY"],'newjs');
	
			$matri[$i]["SNO"] = $sno;
			$matri[$i]["ID"] = $row["ID"];
			$matri[$i]["gender"] = $row["GENDER"];
			$matri[$i]["fld"] = $row["FLD"];
                        $matri[$i]["community"] = $label[0];
			$matri[$i]["title"] = $row["TITLE"];
			$matri[$i]["description"] = $row["DESCRIPTION"];
			$matri[$i]["keyword"] = $row["KEYWORD"];
			$matri[$i]["name"] = $row["NAME"];
			$matri[$i]["logo_string"] = $row["LOGO_STRING"];
			$matri[$i]["name_of_com"] = $row["NAME_OF_COMMUNITY"];
/***********************************************************************************************************
	ADDED BY	: SHAKTI SRIVASTAVA
	ADDITION DATE	: 23 JUNE,2005
	REASON		: To display two more fields, viz, SOURCE & CAPTION, that were added for SEO
************************************************************************************************************/
			$matri[$i]["source"] = $row["SOURCE"];
			$matri[$i]["caption"] = $row["CAPTION"];

			$i++;
			$sno++;
		}
		
		if ($j)
			$cPage = ($j/$PAGELEN) + 1;            //gives the number of records on each page      
		else
			$cPage = 1;


		pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"seo_matrimonialdisplay.php",'','');

                $no_of_pages=ceil($TOTALREC/$PAGELEN);
                                                                                                                             
                $smarty->assign("COUNT",$TOTALREC);		//calculates the total number of pages
                $smarty->assign("CURRENTPAGE",$cPage);
                $smarty->assign("NO_OF_PAGES",$no_of_pages);
		$smarty->assign("matri",$matri);		
		$smarty->assign("cid",$data);
		$smarty->assign("name",$name);
		$smarty->assign("username",$username);

		$smarty->display("seo_matrimonialdisplay.htm");

}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
