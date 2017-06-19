<?php
/**
** This Class will provide methods related to test.JPROFILE_FOR_DUPLICATION table.
 * @author Reshu Rajput
 * @created 2013-06-28
*/

class JprofileForDuplication 
{

	/** 
        This function is used to delete the entries from test.JPROFILE_FOR_DUPLICATION table having last  login date before the one provided
	* in CrawlerConfig class
        * * @return result count of deleted entries
        */
        public function del()
        {
		$JprofileForDuplicationObj = new test_JPROFILE_FOR_DUPLICATION("newjs_slave");
		$noOfMonths = CrawlerConfig::$greaterThanConditions["LAST_LOGIN_DT"];
                $dateValue = date("Y-m-d", JSstrToTime("- $noOfMonths month",JSstrToTime(date("Y-m-d"))));
		$result= $JprofileForDuplicationObj->del($dateValue);
		return $result;
        }

}
