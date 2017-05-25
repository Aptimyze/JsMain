<?
class CONTACT_ENGINE_TEMPLATE_NAME extends TABLE
{
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

        /**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from CONTACT_ENGINE.TEMPLATE_NAME
         * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
         * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
         * @param $fields Columns to query
         * @return results Array according to criteria having incremented index
         * @exception jsException for blank criteria
         * @exception PDOException for database level error handling
         */

        public function getTemplateName($contactType,$profileState,$toBeStatus='',$engineType,$page="", $contactInitiator='',$action="PRE",$checkSenderReceiver='')
        {
                try
                {
//                        $fields = $fields?$fields:$this->getFields();//Get columns to query
                        $sqlSelectDetail = "SELECT TEMPLATE_NAME FROM CONTACT_ENGINE.TEMPLATE_NAME WHERE CONTACT_TYPE=:CONTACT_TYPE AND PROFILE_STATE=:PROFILE_STATE AND ENGINE_TYPE=:ENGINE_TYPE AND ACTION_TYPE=:ACTION_TYPE ";
                        if($toBeStatus)
				$sqlSelectDetail.=" AND TO_BE_STATUS=:TO_BE_STATUS ";
			if($page)
				$sqlSelectDetail.=" AND PAGE=:PAGE ";
				
			if($engineType=="INFO" || $checkSenderReceiver==true)
				$sqlSelectDetail.=" AND SENDER_RECEIVER=:SENDER_RECEIVER ";
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$resSelectDetail->bindValue(":CONTACT_TYPE",$contactType,PDO::PARAM_STR);
			$resSelectDetail->bindValue(":PROFILE_STATE",$profileState,PDO::PARAM_STR);
			$resSelectDetail->bindValue(":ENGINE_TYPE",$engineType,PDO::PARAM_STR);
			if($page)
				$resSelectDetail->bindValue(":PAGE",$page,PDO::PARAM_STR);
			if($engineType=="INFO" || $checkSenderReceiver==true)
				$resSelectDetail->bindValue(":SENDER_RECEIVER",$contactInitiator,PDO::PARAM_STR);
			$resSelectDetail->bindValue(":ACTION_TYPE",$action,PDO::PARAM_STR);
			if($toBeStatus)
				$resSelectDetail->bindValue(":TO_BE_STATUS",$toBeStatus,PDO::PARAM_STR);
                        $resSelectDetail->execute();
                        if($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
				$templateName= $rowSelectDetail['TEMPLATE_NAME'];
			return $templateName;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }
}
?>
