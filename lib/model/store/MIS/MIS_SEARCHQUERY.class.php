<?php
/**
 * @brief This class is store class of searches performed by uses (newjs.SEARCHQUERY)
 */
class MIS_SEARCHQUERY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        public function getUniqueSearchDays($profileid)
        {
                if(!$profileid)
                        throw new jsException("","PROFILEID IS BLANK IN get() of SEARCHQUERY.class.php");
                try
                {
			$sql ="select count(distinct DATE_FORMAT(`DATE`,'%Y-%m-%d')) AS CNT from MIS.SEARCHQUERY where PROFILEID=:PROFILEID";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->execute();
                        if($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $daysCnt = $row['CNT'];
                        }
                        return $daysCnt;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }
	public function performedSearchInLast10Days($profileid,$date)
	{
		if(!$profileid)
                        throw new jsException("","PROFILEID IS BLANK IN get() of SEARCHQUERY.class.php");
		try
                {
                        $sql ="select count(1) AS CNT from MIS.SEARCHQUERY where PROFILEID=:PROFILEID AND DATE>=:DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
                        $res->execute();
                        if($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                if($row['CNT']>0)
					return 1;
				else
					return 0;
                        }
			else
				return 0;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	public function getIdForCorrespondingDateTime($datetime){
		
		if(empty($datetime)){
			throw new jsException("","Datetime not passed in MIS_SEARCHQUERY.class.php");
		}
		try {
			$sql = "SELECT ID FROM MIS.SEARCHQUERY WHERE DATE <=:DATETIME ORDER BY ID DESC LIMIT 1";
			$res = $this->db->prepare($sql);
			$res->bindValue(":DATETIME", $datetime, PDO::PARAM_STR);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)) {
				return $row['ID'];
			}
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}

	}

	public function getMinID(){
		
		try {
			$sql = "SELECT MIN(ID) AS ID FROM MIS.SEARCHQUERY";
			$res = $this->db->prepare($sql);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)) {
				return $row['ID'];
			}
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}

	}

	public function createTempArchivingTable(){
		
		try {
			$sql = "CREATE TABLE MIS.SEARCHQUERY_ARCHIVE_DATA LIKE MIS.SEARCHQUERY";
			$res = $this->db->prepare($sql);
			$res->execute();

			$sql = "ALTER TABLE MIS.SEARCHQUERY_ARCHIVE_DATA AUTO_INCREMENT = 1";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}
		
	}

	public function transferRecordsToTempArchivingTable($startID, $endID){
		
		if(empty($startID) || empty($endID)){
			throw new jsException("","Missing params in transferRecordsToTempArchivingTable function, MIS_SEARCHQUERY.class.php");
		}

		try {
			$sql = "INSERT INTO MIS.SEARCHQUERY_ARCHIVE_DATA (GENDER,CASTE,MTONGUE,LAGE,HAGE,HAVEPHOTO,MANGLIK,MSTATUS,HAVECHILD,LHEIGHT,HHEIGHT,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,HANDICAPPED,OCCUPATION,COUNTRY_RES,CITY_RES,EDU_LEVEL,KEYWORD,DATE,ONLINE,SORT_LOGIC,INCOME,ROW_COUNT,RANK_ID,PROFILEID,SEARCH_TYPE,SUBSCRIPTION,RECORDCOUNT,PAGECOUNT,EDU_LEVEL_NEW,KEYWORD_TYPE,CASTE_DISPLAY,RELATION,NEWSEARCH_CLUSTERING,OCCUPATION_GROUPING,EDUCATION_GROUPING,RELIGION,ORIGINAL_SID,CASTE_MAPPING,HOROSCOPE,SPEAK_URDU,HIJAB_MARRIAGE,SAMPRADAY,ZARATHUSHTRI,AMRITDHARI,CUT_HAIR,MATHTHAB,WORK_STATUS,HIV,NATURE_HANDICAP,LIVE_PARENTS,SUBCASTE,WEAR_TURBAN,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL,LAST_ACTIVITY,CASTE_GROUP,INDIA_NRI,STATE,CITY_INDIA,MARRIED_WORKING,GOING_ABROAD,VIEWED,NoRelaxParams,WIFE_WORKING,PROFILE_ADDED,LAST_LOGIN_DT,ISEARCH_PROFILEID,TRACKING_COOKIE_ID,MANGLIK_IGNORE,MSTATUS_IGNORE,HIV_IGNORE,HANDICAPPED_IGNORE,LVERIFY_ACTIVATED_DT,HVERIFY_ACTIVATED_DT,MATCHALERTS_DATE_CLUSTER,KUNDLI_DATE_CLUSTER,NATIVE_STATE) SELECT GENDER,CASTE,MTONGUE,LAGE,HAGE,HAVEPHOTO,MANGLIK,MSTATUS,HAVECHILD,LHEIGHT,HHEIGHT,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,HANDICAPPED,OCCUPATION,COUNTRY_RES,CITY_RES,EDU_LEVEL,KEYWORD,DATE,ONLINE,SORT_LOGIC,INCOME,ROW_COUNT,RANK_ID,PROFILEID,SEARCH_TYPE,SUBSCRIPTION,RECORDCOUNT,PAGECOUNT,EDU_LEVEL_NEW,KEYWORD_TYPE,CASTE_DISPLAY,RELATION,NEWSEARCH_CLUSTERING,OCCUPATION_GROUPING,EDUCATION_GROUPING,RELIGION,ORIGINAL_SID,CASTE_MAPPING,HOROSCOPE,SPEAK_URDU,HIJAB_MARRIAGE,SAMPRADAY,ZARATHUSHTRI,AMRITDHARI,CUT_HAIR,MATHTHAB,WORK_STATUS,HIV,NATURE_HANDICAP,LIVE_PARENTS,SUBCASTE,WEAR_TURBAN,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL,LAST_ACTIVITY,CASTE_GROUP,INDIA_NRI,STATE,CITY_INDIA,MARRIED_WORKING,GOING_ABROAD,VIEWED,NoRelaxParams,WIFE_WORKING,PROFILE_ADDED,LAST_LOGIN_DT,ISEARCH_PROFILEID,TRACKING_COOKIE_ID,MANGLIK_IGNORE,MSTATUS_IGNORE,HIV_IGNORE,HANDICAPPED_IGNORE,LVERIFY_ACTIVATED_DT,HVERIFY_ACTIVATED_DT,MATCHALERTS_DATE_CLUSTER,KUNDLI_DATE_CLUSTER,NATIVE_STATE FROM MIS.SEARCHQUERY WHERE ID >=:STARTID AND ID <= :ENDID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":STARTID", $startID, PDO::PARAM_INT);
			$res->bindValue(":ENDID", $endID, PDO::PARAM_INT);
			$res->execute();
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}
	}

	public function removeArchivedRecords($startID, $endID){
		
		if(empty($startID) || empty($endID)){
			throw new jsException("","Missing params in transferRecordsToTempArchivingTable function, MIS_SEARCHQUERY.class.php");
		}

		try {
			$sql = "DELETE FROM MIS.SEARCHQUERY WHERE ID >=:STARTID AND ID <= :ENDID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":STARTID", $startID, PDO::PARAM_INT);
			$res->bindValue(":ENDID", $endID, PDO::PARAM_INT);
			$res->execute();
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}
	}

	public function renameTempTableForArchiving($newName){
		
		if(empty($newName)){
			throw new jsException("","No Name specified for new Table in function renameOriginalTableForArchiving, MIS_SEARCHQUERY.class.php");
		}

		try {
			$sql = "RENAME TABLE MIS.SEARCHQUERY_ARCHIVE_DATA TO MIS.{$newName}";
			$res = $this->db->prepare($sql);
			$res->execute();
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}
	}

	public function getFirstInsertedRecordInTempTableDate() {
		try {
			$sql = "SELECT MIN( DATE ) as DATE FROM MIS.SEARCHQUERY_ARCHIVE_DATA LIMIT 1";
			$res = $this->db->prepare($sql);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC)) {
				return $row['DATE'];
			}
		}
		catch (PDOException $e) {
			throw new jsException($e);
		}
	}
}
?>
