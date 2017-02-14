<?php
/**
* @author Reshu Rajput
* @created 03 March, 2015
*/
class search_LATEST_SEARCHQUERY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

       	public function insertOrReplace($paramArr=array())
	{
		foreach($paramArr as $key=>$val)
                        ${$key} = $val;
                        
                if(!$ID && !$SEARCH_CHANNEL)
                        throw new jsException("","ID & SEARCH_CHANNEL are BLANK IN insertOrReplace() of search_LATEST_SEARCHQUERY.class.php");
                //setting state and city which user has selected without mapping
                $memObject=JsMemcache::getInstance();
                if($stateToSet = $memObject->get('stateToSet-'.$ID))
                    $state = "'".$stateToSet."'";
                else
                    $state = 'STATE';
                if($cityToSet = $memObject->get('cityToSet-'.$ID))
                    $city = "'".$cityToSet."'";
                else
                    $city = 'CITY_RES';

		try
		{       // * is used in place of all fields to ensure sync between search_query and latest_searchquery tables
			$sql = "REPLACE INTO search.LATEST_SEARCHQUERY(ID, GENDER, CASTE, MTONGUE, LAGE, HAGE, HAVEPHOTO, MANGLIK, MSTATUS, HAVECHILD, LHEIGHT, HHEIGHT, BTYPE, COMPLEXION, DIET, SMOKE, DRINK, HANDICAPPED, OCCUPATION, COUNTRY_RES, $city, EDU_LEVEL, KEYWORD, DATE, ONLINE, SORT_LOGIC, INCOME, ROW_COUNT, RANK_ID, PROFILEID, SEARCH_TYPE, SUBSCRIPTION, RECORDCOUNT, PAGECOUNT, EDU_LEVEL_NEW, KEYWORD_TYPE, CASTE_DISPLAY, RELATION, NEWSEARCH_CLUSTERING, OCCUPATION_GROUPING, EDUCATION_GROUPING, RELIGION, ORIGINAL_SID, CASTE_MAPPING, HOROSCOPE, SPEAK_URDU, HIJAB_MARRIAGE, SAMPRADAY, ZARATHUSHTRI, AMRITDHARI, CUT_HAIR, MATHTHAB, WORK_STATUS, HIV, NATURE_HANDICAP, LIVE_PARENTS, SUBCASTE, WEAR_TURBAN, LINCOME, HINCOME, LINCOME_DOL, HINCOME_DOL, LAST_ACTIVITY, CASTE_GROUP, INDIA_NRI, $state, CITY_INDIA, MARRIED_WORKING, GOING_ABROAD, VIEWED, NoRelaxParams, WIFE_WORKING, PROFILE_ADDED, LAST_LOGIN_DT, ISEARCH_PROFILEID, TRACKING_COOKIE_ID, MANGLIK_IGNORE, MSTATUS_IGNORE, HIV_IGNORE, HANDICAPPED_IGNORE, MATCHALERTS_DATE_CLUSTER, KUNDLI_DATE_CLUSTER, LVERIFY_ACTIVATED_DT, HVERIFY_ACTIVATED_DT, NATIVE_STATE, SEARCH_CHANNEL) SELECT ID, GENDER, CASTE, MTONGUE, LAGE, HAGE, HAVEPHOTO, MANGLIK, MSTATUS, HAVECHILD, LHEIGHT, HHEIGHT, BTYPE, COMPLEXION, DIET, SMOKE, DRINK, HANDICAPPED, OCCUPATION, COUNTRY_RES, $city, EDU_LEVEL, KEYWORD, DATE, ONLINE, SORT_LOGIC, INCOME, ROW_COUNT, RANK_ID, PROFILEID, SEARCH_TYPE, SUBSCRIPTION, RECORDCOUNT, PAGECOUNT, EDU_LEVEL_NEW, KEYWORD_TYPE, CASTE_DISPLAY, RELATION, NEWSEARCH_CLUSTERING, OCCUPATION_GROUPING, EDUCATION_GROUPING, RELIGION, ORIGINAL_SID, CASTE_MAPPING, HOROSCOPE, SPEAK_URDU, HIJAB_MARRIAGE, SAMPRADAY, ZARATHUSHTRI, AMRITDHARI, CUT_HAIR, MATHTHAB, WORK_STATUS, HIV, NATURE_HANDICAP, LIVE_PARENTS, SUBCASTE, WEAR_TURBAN, LINCOME, HINCOME, LINCOME_DOL, HINCOME_DOL, LAST_ACTIVITY, CASTE_GROUP, INDIA_NRI, $state, CITY_INDIA, MARRIED_WORKING, GOING_ABROAD, VIEWED, NoRelaxParams, WIFE_WORKING, PROFILE_ADDED, LAST_LOGIN_DT, ISEARCH_PROFILEID, TRACKING_COOKIE_ID, MANGLIK_IGNORE, MSTATUS_IGNORE, HIV_IGNORE, HANDICAPPED_IGNORE, MATCHALERTS_DATE_CLUSTER, KUNDLI_DATE_CLUSTER, LVERIFY_ACTIVATED_DT, HVERIFY_ACTIVATED_DT, NATIVE_STATE, :SEARCH_CHANNEL AS SEARCH_CHANNEL FROM newjs.SEARCHQUERY AS S WHERE S.ID= :ID" ;
			$res = $this->db->prepare($sql);
			$res->bindParam(":ID", $ID, PDO::PARAM_INT);
			$res->bindParam(":SEARCH_CHANNEL",$SEARCH_CHANNEL, PDO::PARAM_STR);
        	        $res->execute();	
			return $dt;
		}
                catch(Exception $e)
                {
			jsException::nonCriticalError("search_LATEST_SEARCHQUERY (1)-->.$sql".$e);
                        //throw new jsException($e);
                }
	}

        public function update($paramArr=array())
	{
		if(!$paramArr["ID"])
                        throw new jsException("","ID is BLANK IN update() of search_LATEST_SEARCHQUERY.class.php");
                try
		{       
			$sql = "UPDATE search.LATEST_SEARCHQUERY SET CITY_RES=:CITY_RES,CITY_INDIA=:CITY_RES,STATE=:STATE  WHERE ID= :ID AND SEARCH_TYPE='A'" ;
			$res = $this->db->prepare($sql);
			$res->bindParam(":ID", $paramArr["ID"], PDO::PARAM_INT);
			$res->bindParam(":CITY_RES",$paramArr["CITY_RES"], PDO::PARAM_STR);
                        $res->bindParam(":CITY_INDIA",$paramArr["CITY_INDIA"], PDO::PARAM_STR);
                        $res->bindParam(":STATE",$paramArr["STATE"], PDO::PARAM_STR);
        	        $res->execute();
			return $dt;
		}
                catch(Exception $e)
                {
			jsException::nonCriticalError("search_LATEST_SEARCHQUERY (2)-->.$sql".$e);
                        //throw new jsException($e);
                }
	}
        
	public function getSearchQuery($paramArr=array(),$fields="*")
	{
		foreach($paramArr as $key=>$val)
                        ${$key} = $val;

                if(!$PROFILEID && !$SEARCH_CHANNEL)
                        throw new jsException("","PROFILEID & SEARCH_CHANNEL are BLANK IN getSearchQuery() of search_LATEST_SEARCHQUERY.class.php");

		try
		{
			$sql = "SELECT $fields FROM search.LATEST_SEARCHQUERY WHERE PROFILEID=:PROFILEID AND SEARCH_CHANNEL=:SEARCH_CHANNEL";
                        if($SEARCH_TYPE)
                                $sql .= " AND SEARCH_TYPE=:SEARCH_TYPE";
                        else
                                $sql .= " AND SEARCH_TYPE!='".SearchTypesEnums::Advance."'";
			$res = $this->db->prepare($sql);
			$res->bindParam(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			$res->bindParam(":SEARCH_CHANNEL",$SEARCH_CHANNEL, PDO::PARAM_STR);
                        if($SEARCH_TYPE)
                                $res->bindParam(":SEARCH_TYPE",$SEARCH_TYPE, PDO::PARAM_STR);
                	$res->execute();
	                $row = $res->fetch(PDO::FETCH_ASSOC);
	                return $row;
		}
                catch(Exception $e)
                {
			jsException::nonCriticalError("search_LATEST_SEARCHQUERY (3)-->.$sql".$e);
                }
	}
}
?>
