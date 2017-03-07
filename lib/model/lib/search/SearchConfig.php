<?php
/**
 * @brief This class contains the configurable paramters used in class
 * @author Lavesh Rawat
 * @created 2012-06-10
 */
class SearchConfig
{
	const _nullValueAttributeLabel= "NS";
	const _noneValueHandicapped= "N";
	const _doesntMatterValue= "0";
        public static $maxSaveSearchesAllowed = 5;
	public static $limitOfViewedFeatureProfile = 3;
	public static $profilesPerPage = 25;
	public static $matchMaxLimit = 5000;//overwriting limit set in matchalert
	public static $profilesPerPageOnApp = 6;
	public static $profilesPerPageOnWapSite = 20;
	public static $featuredProfilesCount = 5;
	public static $suggestedAlgoCount = 15;
	public static $ap_send_eoi_count = 100;
	public static $summary_profile_count = 20;
	public static $summary_profile_count_all = 500;
	public static $premium_dummy_user_search_count = 100;
	public static $bandhan_key = "1b1dc65ff77c5ee910dd6b6fbd02bbd7";
	public static $doctorsrepublic_key = "a3bbe1efc30ffb90d860a5c1c8f83f2c";
	public static $featureProfileCache = 1;		//either 1 or 0. 1 for memcache and 0 for table
	public static $solrSearchCache = "memcache";	//either "memcache" or "table" 
	public static $onlineSearchFlag = "O";
	public static $backendSaveDpp = "B";		//DPP Save from backend - showstat link
	public static $filteredRemove = 1;		//Remove Filtered profiles from search
	public static $moreClusters_alwaysShow = array('EDUCATION_GROUPING','OCCUPATION_GROUPING');
	public static $possibleSearchParamters = 'GENDER,CASTE,SUBCASTE,MTONGUE,LAGE,HAGE,MSTATUS,LHEIGHT,HHEIGHT,HAVEPHOTO,MANGLIK,HAVECHILD,OCCUPATION,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,HANDICAPPED,HIV,RELATION,COUNTRY_RES,CITY_RES,RELIGION,EDU_LEVEL,EDU_LEVEL_NEW,HIJAB_MARRIAGE,SPEAK_URDU,SAMPRADAY,ZARATHUSHTRI,HOROSCOPE,AMRITDHARI,CUT_HAIR,MATHTHAB,NATURE_HANDICAP,WEAR_TURBAN,LIVE_PARENTS,INCOME,EDUCATION_GROUPING,NEWSEARCH_CLUSTERING,LAST_ACTIVITY,OCCUPATION_GROUPING,INDIA_NRI,STATE,CITY_INDIA,MARRIED_WORKING,GOING_ABROAD,ONLINE,CASTE_GROUP,VIEWED,SORT_LOGIC,SEARCH_TYPE,NoRelaxParams,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL,CASTE_DISPLAY,WIFE_WORKING,WORK_STATUS,KEYWORD,KEYWORD_TYPE,PROFILE_ADDED,LAST_LOGIN_DT,HIV_IGNORE,MANGLIK_IGNORE,MSTATUS_IGNORE,HANDICAPPED_IGNORE,LVERIFY_ACTIVATED_DT,HVERIFY_ACTIVATED_DT,MATCHALERTS_DATE_CLUSTER,KUNDLI_DATE_CLUSTER,NATIVE_STATE';
	public static $minAcceptedGunaScore = 17;
	/* jpartner*/ /*last removed : PARTNER_BTYPE AS BTYPE,PARTNER_COMP AS COMPLEXION */
	public static $dppSearchParamters = 'GENDER,PARTNER_CASTE AS CASTE,PARTNER_MTONGUE AS MTONGUE,LAGE,HAGE,PARTNER_MSTATUS AS MSTATUS,LHEIGHT,HHEIGHT,HAVEPHOTO,PARTNER_MANGLIK AS MANGLIK,CHILDREN AS HAVECHILD,PARTNER_OCC AS OCCUPATION,PARTNER_DIET AS DIET,PARTNER_SMOKE AS SMOKE,PARTNER_DRINK AS DRINK,HANDICAPPED,HIV,PARTNER_RELATION AS RELATION,PARTNER_COUNTRYRES AS COUNTRY_RES,PARTNER_CITYRES AS CITY_RES,PARTNER_RELIGION AS RELIGION,PARTNER_ELEVEL_NEW AS EDU_LEVEL_NEW,HIJAB_MARRIAGE,SPEAK_URDU,SAMPRADAY,ZARATHUSHTRI,HOROSCOPE,AMRITDHARI,CUT_HAIR,MATHTHAB,NHANDICAPPED AS NATURE_HANDICAP,WEAR_TURBAN,LIVE_PARENTS,PARTNER_INCOME AS INCOME,EDUCATION_GROUPING,LAST_ACTIVITY,OCCUPATION_GROUPING,INDIA_NRI,STATE,CITY_INDIA,MARRIED_WORKING,GOING_ABROAD,CASTE_GROUP,VIEWED,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL';

	public static $possibleMembersLookingForMeSearchParameters = "PARTNER_MTONGUE,PARTNER_CASTE,PARTNER_RELIGION,PARTNER_COUNTRYRES,PARTNER_BTYPE,PARTNER_COMP,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_OCC,PARTNER_LAGE,PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,PARTNER_MSTATUS,PARTNER_CITYRES,PARTNER_DRINK,PARTNER_SMOKE,PARTNER_DIET,PARTNER_HANDICAPPED,PARTNER_MANGLIK";

	/* For Store */
	public static $integerSearchParameters = array('ID','LAGE','HAGE','LHEIGHT','HHEIGHT','ROW_COUNT','RANK_ID','PROFILEID','RECORDCOUNT','PAGECOUNT','ORIGINAL_SID','CASTE_MAPPING','PAGE','DPP');
	public static $noQuotesInJpartner = array('GENDER','HAVEPHOTO','CHILDREN','LINCOME','HINCOME','LINCOME_DOL','HINCOME_DOL','HOROSCOPE');
	/* For Store */

	/* jpartner*/

	//For Solr
	public static $searchResultsCountForAutoRelaxation = 20;
	public static $searchResultsCountForBroadeningDivisionLimit = 50;
	public static $searchResultsCountForBroadeningLimit = 100;
	public static $limitCheckedClustersOptions = 5; //actial limit is 6 (-1)
	public static $limitUncheckedClustersOptions = 4; //actial limit is 5 (-1)
        public static $gtalkOnline = 45;//minutes
        public static $jsOnline = 60;//minutes

	public static $searchWhereParameters = 'CASTE,SUBCASTE,MTONGUE,OCCUPATION,BTYPE,RELATION,COUNTRY_RES,RELIGION,EDU_LEVEL,EDU_LEVEL_NEW,HOROSCOPE,MATHTHAB,INCOME,MSTATUS,HAVEPHOTO,MANGLIK,HAVECHILD,DIET,SMOKE,DRINK,COMPLEXION,HANDICAPPED,HIV,HIJAB_MARRIAGE,SPEAK_URDU,SAMPRADAY,ZARATHUSHTRI,AMRITDHARI,CUT_HAIR,NATURE_HANDICAP,WEAR_TURBAN,LIVE_PARENTS,CITY_RES,LAST_ACTIVITY,EDUCATION_GROUPING,OCCUPATION_GROUPING,INDIA_NRI,STATE,CITY_INDIA,MARRIED_WORKING,GOING_ABROAD,CASTE_GROUP,WIFE_WORKING,WORK_STATUS,KEYWORD,KEYWORD_TYPE,PROFILE_ADDED,PHOTO_DISPLAY,PRIVACY,NATIVE_STATE,LAST_LOGIN_SCORE,SUBSCRIPTION,PHOTO_VISIBILITY_LOGGEDIN';
	public static $searchRangeParameters = 'AGE,HEIGHT,ENTRY_DT,VERIFY_ACTIVATED_DT';
	
	public static $advanceSearchMoreParameters = "LIVE_PARENTS,SUBCASTE,MATHTHAB,HIJAB_MARRIAGE,SPEAK_URDU,SAMPRADAY,ZARATHUSHTRI,AMRITDHARI,CUT_HAIR,WEAR_TURBAN,WORK_STATUS,BTYPE,DIET,SMOKE,DRINK,COMPLEXION,NATURE_HANDICAP,KEYWORD,KEYWORD_TYPE,ENTRY_DT";

	public static $membersLookingForMeWhereParameters = 'PARTNER_MTONGUE,PARTNER_CASTE,PARTNER_RELIGION,PARTNER_COUNTRYRES,PARTNER_BTYPE,PARTNER_COMP,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_OCC,PARTNER_MSTATUS,PARTNER_CITYRES,PARTNER_DRINK,PARTNER_SMOKE,PARTNER_DIET,PARTNER_HANDICAPPED,PARTNER_MANGLIK';
	public static $membersLookingForMeRangeParameters = 'PARTNER_LAGE,PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT';
        public static $featuredProfileParams = 'LAST_LOGIN_DT';
	public static $textBasedSearchParameters = "KEYWORD_SEARCH_FIELD";
	public static $featureProfileWhereParameters = "FEATURE_PROFILE";
	//For Solr
	
	public static $dont_all_labels = array("'DONT_MATTER'","DONT_MATTER");
	
	/* Search Cluster Info */
	public static $doesntMatterLabel = 'Doesn\'t Matter';
	public static $allLabel = 'All';
	public static $clusterOptionsForRelation = array(1,2,3);
	public static $clusterOptionsForHandicapped = array(1,2,3,4);
	public static $sliderClusters = array('HEIGHT','INCOME','AGE');
	public static $clustersWithDoesntMatter = array('HOROSCOPE','HANDICAPPED','HIV','MARRIED_WORKING','HAVECHILD');
	public static $clustersWithAny = array('HANDICAPPED');
	/* Search Cluster Info */
        public static $matchAlertCacheLifetime =300;
	/**
         * Db to be called in all search requests
         */
        public static $searchDbName = "newjs_masterRep";
        public static $jsBoostSubscription = "N"; // JsBoost subscription value
        
	/*
	* List The Array name corresponding to SEARCH_MALE/FEMALE fields used in cluster
	*/
        public static function fieldMapArrayLabelMapping()
        {
		//need to remove one of them
                $sphinx_to_label_mapping = array(
                        "RELIGION" => "religion",
                        "RELATION" => "relation",
                        "EDUCATION_GROUPING" => "education_grouping",
                        "EDU_LEVEL_NEW" => "education",
                        "OCCUPATION_GROUPING" => "occupation_grouping",
                        "OCCUPATION" => "occupation",
                        "HAVECHILD" => "children",
                        "MTONGUE" => "community_small",
                        "MSTATUS"  => "marital_status",
                        "CASTE" => "caste_clusters_breadcrumb",
                        "CASTE_GROUP" => "caste_clusters_breadcrumb",
                        "MANGLIK" => "mangliks_label", // label change for string change from 'dont know' to 'not filled in'
                        "DIET" => "diet",
                        "LAST_ACTIVITY" => "user_last_activity_array",
                        "INDIA_NRI" => "india_nri_array",
                        "CITY_INDIA" => "city_india",
                        "STATE" => "city_india",
                        "HOROSCOPE" => "horoscope_cluster_array",
                        "HANDICAPPED" => "handicapped",
                        "HIV" => "hiv_edit",
                        "MARRIED_WORKING" => "career_after_marriage",
                        "GOING_ABROAD" => "going_abroad",
                        "HAVEPHOTO" => "photo_cluster_array",
			"VIEWED" => "viewed_array",
			"HEIGHT" => "height_without_meters",
			"INCOME" => "hincome",
			"INCOME_DOL" => "hincome_dol",
			"COUNTRY_RES" => "country",
			"PROFILE_ADDED" => "profileAddedClusters",
			"MATCHALERTS_DATE_CLUSTER" => "matchAlertsDateClusters",
			"LIVE_PARENTS" => "live_with_parents",
			"MATHTHAB" => "maththab",
			"HIJAB_MARRIAGE" => "hijab_marriage",
			"SPEAK_URDU" => "speak_urdu",
			"SAMPRADAY" => "sampraday",
			"ZARATHUSHTRI" => "zarathushtri",
			"AMRITDHARI" => "amritdhari",
			"CUT_HAIR" => "cut_hair",
			"WEAR_TURBAN" => "wear_turban",
			"WORK_STATUS" => "work_status",
			"BTYPE" => "bodytype",
			"SMOKE" => "smoke",
			"DRINK" => "drink",
			"COMPLEXION" => "complexion",
			"NATURE_HANDICAP" => "nature_handicap",
			"KEYWORD_TYPE" => "keyword_type",
                        "NATIVE_STATE" => "city_india",
                      );
                return $sphinx_to_label_mapping;
        }

	/*
	* List The text of cluster corresponding to SEARCH_MALE/FEMALE fields used in cluster
	*/
        public static function clusterLabelMapping($religion='')
        {
		$search_labels = FieldMap::getFieldLabel("search_clusters",1,1);
		$solr_labels = FieldMap::getFieldLabel("solr_clusters",1,1);
		foreach($solr_labels as $k=>$v)
			$clusterLabelMappingArray[$v]=$search_labels[$k];

		if(strstr($religion,'2') || strstr($religion,'3') ||strstr($religion,'7'))
			$clusterLabelMappingArray["CASTE"] ='Sect';

                return $clusterLabelMappingArray;
        }

	/*
	* List options of LAST_ACTIVITY cluster
	*/
	public static function clustersOptionsOfSpecialClusters($clusterName)
	{
		$temp=SearchConfig::fieldMapArrayLabelMapping();
		$arr = FieldMap::getFieldLabel($temp[$clusterName],'',1);
		foreach($arr as $k=>$v)
			${$clusterName."_CLUSTERS"}[] = $v;
		return ${$clusterName."_CLUSTERS"};
	}

	/* For Search UI */

        public static $searchDisplayFields = 'HEIGHT,USERNAME,PROFILEID,RELIGION,CASTE,MTONGUE,EDU_LEVEL_NEW,INCOME,OCCUPATION,AGE,LAST_LOGIN_DT,ENTRY_DT,SUBSCRIPTION,CITY_RES,COUNTRY_RES,MSTATUS,COLLEGE,PG_COLLEGE,COMPANY_NAME,NATIVE_CITY,NATIVE_STATE,ANCESTRAL_ORIGIN,NAME_OF_USER';

        public static $searchDisplayDecoratedFields = 'HEIGHT,RELIGION,CASTE,MTONGUE,EDU_LEVEL_NEW,OCCUPATION,INCOME,CITY_RES,COUNTRY_RES';
	public static function decoratedMappingSearchDisplay()
	{
               $decoratedMappingSearchDisplay = array(
                        "RELIGION" => "religion",
                        "MTONGUE" => "community_small",
                        "CASTE" => "caste_small",
                        "HEIGHT" => "height_without_meters",
                        "EDU_LEVEL_NEW" => "education",
                        "OCCUPATION" => "occupation",
                        "INCOME" => "income_map",
                        "CITY_RES" => "city",
 //                       "COUNTRY_RES" => "country",
                      );
                return $decoratedMappingSearchDisplay;
	}
        public static $fieldsDisplayedInSearchTuple = array(
                                                        "AGE" => "Age",
                                                        "DECORATED_HEIGHT" => "Height",
                                                        "DECORATED_RELIGION" => "Religion",
                                                        "DECORATED_CASTE" => "Caste",
                                                        "DECORATED_MTONGUE" => "Community",
                                                        "DECORATED_EDU_LEVEL_NEW" => "Education",
                                                        "DECORATED_OCCUPATION" => "Occupation",
                                                        "DECORATED_INCOME" => "Income",
                                                        "DECORATED_CITY_RES" => "Location",
//							"DECORATED_COUNTRY_RES" => "Country",
                                                        );  //added by prinka
	/* For Search UI */

	public static $advanceSearchMtongueHardCodeArray = array(19=>41,30=>70,36=>71);		//Same mtongue in different regions have some hardcoded values in advance search. This array keeps the mapping. Array index has the original vale and array value has the hardcoded value
        
        public static function getSearchDb($params = array()){
                return self::$searchDbName;
        }
}
