<?
/**
 * @brief This class is used to handle all functionalities related to add/delete/retreive operations of LOG_DELETED_HIDDEN_IDS_FOR_SEARCH.
 * @author Lavesh Rawat
 */
class  LogDeletedHiddenProfilesForSearch
{
        /**
	* This function is to add entry.
        * @param  profileId whose record need to be added
        **/
        public function insertDeletedHiddenRecord($profileId)
        {
		$LOG_DELETED_HIDDEN_IDS_FOR_SEARCH = new LOG_DELETED_HIDDEN_IDS_FOR_SEARCH;
		if(is_array($profileId))
			$arr = $profileId;
		else
			$arr = explode(",",$profileId);
		$LOG_DELETED_HIDDEN_IDS_FOR_SEARCH->insertRecord($arr);
	}

        /**
        * This function will fetch all the reocrds from the table
	* @return array
        **/
        public function get()
        {
		$LOG_DELETED_HIDDEN_IDS_FOR_SEARCH = new LOG_DELETED_HIDDEN_IDS_FOR_SEARCH;
		return $LOG_DELETED_HIDDEN_IDS_FOR_SEARCH->get();
        }

        /**
        * This function will delete all the reocrds from the table where record date is less than $dt
	* @param $dt datetime
        **/
        public function del($dt)
        {
		$LOG_DELETED_HIDDEN_IDS_FOR_SEARCH = new LOG_DELETED_HIDDEN_IDS_FOR_SEARCH;
		$LOG_DELETED_HIDDEN_IDS_FOR_SEARCH->del($dt);
        }
	
}
