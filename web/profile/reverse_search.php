<?php

/****
*       MODIFIED BY          :  Gaurav Arora
*       DATE OF MODIFICATION :  4 April 2005
*       MODIFICATION         :  INCOME added in SELECT query in function reverse_search
****/
	
	function reverse_search($profileid)
	{
		$sql="select GENDER,CASTE,MANGLIK,MTONGUE,MSTATUS,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,RES_STATUS,BTYPE,DIET,COMPLEXION,HANDICAPPED,AGE,FAMILY_BACK,HAVECHILD,SMOKE,DRINK,INCOME from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		
		$result=mysql_query_decide($sql) or logError("Due to an error your request could not be processed. Please try after a couple of minutes.",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		
		// searchgender will be the same as the gender in my Jprofile record as the JPARTNER table will be having the same gender for opposite gender
		$searchGender=$myrow["GENDER"];
			
		//$searchReligion=$myrow["RELIGION"];

		//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
		$searchCaste=$myrow["CASTE"];
		$searchCasteArr = getcasteparent_revamp_js_db($searchCaste);
		$searchCaste=implode("','",$searchCasteArr);
		$searchCaste = "'".$searchCaste."'";
		//REVAMP JS_DB_CASTE
		
		if($myrow["MANGLIK"]=="M")
			$searchManglik="M";
		elseif($myrow["MANGLIK"]=="N")
			$searchManglik="N";
		elseif($myrow["MANGLIK"]=="D")
			$searchManglik="D";
		else 
			$searchManglik="";
			
		$searchMtongue=$myrow["MTONGUE"];
		$searchMstatus=$myrow["MSTATUS"];
		$searchOccupation=$myrow["OCCUPATION"];
		$searchCountryres=$myrow["COUNTRY_RES"];
		$searchCityres=$myrow["CITY_RES"];
		$searchHeight=$myrow["HEIGHT"];
		$searchEdulevel=$myrow["EDU_LEVEL"];
		$searchResstatus=$myrow["RES_STATUS"];
		$searchBtype=$myrow["BTYPE"];
		$searchComp=$myrow["COMPLEXION"];
/****
*       ADDED BY          :  Gaurav Arora
*       DATE OF ADDITION  :  4 April 2005
*       ADDITION          :  INCOME added in $searchIncome to include INCOME in search
****/
		$searchIncome=$myrow["INCOME"];
//	
		
		if($myrow["HANDICAPPED"]=="1" || $myrow["HANDICAPPED"]=="2" || $myrow["HANDICAPPED"]=="3" || $myrow["HANDICAPPED"]=="4")
		{
			$searchHandicapped="'Y',''";
		}
		else 
		{
			$searchHandicapped="'N',''";
		}
		
		$searchAge=$myrow["AGE"];
		$searchFamilyback=$myrow["FAMILY_BACK"];
		
		if($myrow["HAVECHILD"]=="Y" || $myrow["HAVECHILD"]=="YT" || $myrow["HAVECHILD"]=="YS")
			$searchChildren="'Y',''";
		else 
			$searchChildren="'N',''";
			
		$searchDiet=$myrow["DIET"];
		$searchSmoke=$myrow["SMOKE"];
		$searchDrink=$myrow["DRINK"];
		
		// distinct is necessary in the below query, otherwise duplicate profileid's would result
		$sql="select distinct PROFILEID from JPARTNER";
		// if children is chosen blank, it will be 0
		$where="GENDER='$searchGender' and CHILDREN in ($searchChildren) and LAGE <= '$searchAge' and HAGE >= '$searchAge' and LHEIGHT <= '$searchHeight' and HHEIGHT >= '$searchHeight' and HANDICAPPED in ($searchHandicapped) and DELETED='N'";
		
		if($searchCaste!="")
		{
			$sql.=" left join PARTNER_CASTE on (JPARTNER.PARTNERID=PARTNER_CASTE.PARTNERID)";
			$where.=" and (CASTE in ($searchCaste) or CASTE is null)";
		}
		
		if($searchManglik!="")
		{
			$sql.=" left join PARTNER_MANGLIK on (JPARTNER.PARTNERID=PARTNER_MANGLIK.PARTNERID)";
			$where.=" and (MANGLIK = '$searchManglik' or MANGLIK is null)";
		}
		
		if($searchMtongue!="")
		{
			$sql.=" left join PARTNER_MTONGUE on (JPARTNER.PARTNERID=PARTNER_MTONGUE.PARTNERID)";
			$where.=" and (MTONGUE='$searchMtongue' or MTONGUE is null)";
		}
		
		if($searchMstatus!="")
		{
			$sql.=" left join PARTNER_MSTATUS on (JPARTNER.PARTNERID=PARTNER_MSTATUS.PARTNERID)";
			$where.=" and (MSTATUS='$searchMstatus' or MSTATUS is null)";
		}
		
		if($searchOccupation!="")
		{
			$sql.=" left join PARTNER_OCC on (JPARTNER.PARTNERID=PARTNER_OCC.PARTNERID)";
			$where.=" and (OCC='$searchOccupation' or OCC is null)";
		}
		
		if($searchCountryres!="")
		{
			$sql.=" left join PARTNER_COUNTRYRES on (JPARTNER.PARTNERID=PARTNER_COUNTRYRES.PARTNERID)";
			$where.=" and (COUNTRYRES='$searchCountryres' or COUNTRYRES is null)";
		}
		
		if($searchCityres!="")
		{
			$sql.=" left join PARTNER_CITYRES on (JPARTNER.PARTNERID=PARTNER_CITYRES.PARTNERID)";
			if(!is_numeric($searchCityres))
			{
				// if city is an indian city and is not a state, then the search should be such that we look for those records which are looking for that state or that city. On the other hand if somebody specifies a state then, the reverse search can take place only on that state. The situation will be opposite for a normal search.
				if(strlen($searchCityres)!=2)
				{
					$searchCityres="$searchCityres','" . substr($searchCityres,0,2);
				}
			}
				
			$where.=" and (CITYRES in ('$searchCityres') or CITYRES is null)";
		}
		
		if($searchEdulevel!="")
		{
			$sql.=" left join PARTNER_ELEVEL on (JPARTNER.PARTNERID=PARTNER_ELEVEL.PARTNERID)";
			$where.=" and (ELEVEL='$searchEdulevel' or ELEVEL is null)";
		}
		
		if($searchResstatus!="")
		{
			$sql.=" left join PARTNER_RES_STATUS on (JPARTNER.PARTNERID=PARTNER_RES_STATUS.PARTNERID)";
			$where.=" and (RES_STATUS='$searchResstatus' or RES_STATUS is null)";
		}
		
		if($searchBtype!="")
		{
			$sql.=" left join PARTNER_BTYPE on (JPARTNER.PARTNERID=PARTNER_BTYPE.PARTNERID)";
			$where.=" and (BTYPE='$searchBtype' or BTYPE is null)";
		}
		
		if($searchComp!="")
		{
			$sql.=" left join PARTNER_COMP on (JPARTNER.PARTNERID=PARTNER_COMP.PARTNERID)";
			$where.=" and (COMP='$searchComp' or COMP is null)";
		}
		
		/*
		if($searchFamilyback!="" && $searchFamilyback!="0")
		{
			$sql.=" left join PARTNER_FBACK on (JPARTNER.PARTNERID=PARTNER_FBACK.PARTNERID)";
			$where.=" and (FBACK='$searchFamilyback' or FBACK is null)";
		}*/
		
		if($searchDiet!="" && $searchDiet!="0")
		{
			$sql.=" left join PARTNER_DIET on (JPARTNER.PARTNERID=PARTNER_DIET.PARTNERID)";
			$where.=" and (DIET='$searchDiet' or DIET is null)";
		}
		
		if($searchSmoke!="" && $searchSmoke!="0")
		{
			$sql.=" left join PARTNER_SMOKE on (JPARTNER.PARTNERID=PARTNER_SMOKE.PARTNERID)";
			$where.=" and (SMOKE='$searchSmoke' or SMOKE is null)";
		}
		
		if($searchDrink!="" && $searchDrink!="0")
		{
			$sql.=" left join PARTNER_DRINK on (JPARTNER.PARTNERID=PARTNER_DRINK.PARTNERID)";
			$where.=" and (DRINK='$searchDrink' or DRINK is null)";
		}

/****
*       ADDED BY          :  Gaurav Arora
*       DATE OF ADDITION  :  4 April 2005
*       ADDITION          :  code to add INCOME in search. this will be included only when a GIRL is searching her match.
****/
                if($searchGender=="M")
                {
                        if($searchIncome!="")
                        {
				if($searchIncome=='16' || $searchIncome=='17' || $searchIncome=='18')
					$searchIncome="7";
                                $sql.=" left join PARTNER_INCOME on (JPARTNER.PARTNERID=PARTNER_INCOME.PARTNERID)";
                                $where.=" and (INCOME='$searchIncome' or INCOME is null)";
                        }
                }
// end of code to add INCOME in search. this will be included only when a GIRL is searching her match.

		
		return $sql . " where " . $where;
	}
?>
