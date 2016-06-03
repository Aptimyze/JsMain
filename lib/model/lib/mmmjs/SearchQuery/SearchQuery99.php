<?php
/**
* This class will perform operations related to crteria specified for mailer..
*/
class SearchQuery99
{

	private $searchQuerySpecs;
	private $limitMaxInsertion=20000;

        /*
        * constructor
        * @param specs criteria specified.
        * @param id mailerid(unique) of a search.
        */
        public function __construct($specs='',$id='')
        {
                $this->searchQuerySpecs = $specs;
                if(!$specs && $id)
                        $this->searchQuerySpecs = $this->showMailerSpecs($id,'toLowerCase');
        }


	 /**
        * This function will count the number of profiles available based on search criteria specified.
        * @param specs criteria specified.
        */
        public function getExpectedMailsCount()
        {
                try
                {
                        $cnt = $this->getQuery();
			if($this->searchQuerySpecs['recipient_type'] == 'B')
				$upper_limit = 'buyer_upper_limit';
			else
				$upper_limit = 'seller_upper_limit';
                        if($cnt > $this->searchQuerySpecs[$upper_limit] && $this->searchQuerySpecs[$upper_limit])
                                $cnt = $this->searchQuerySpecs[$upper_limit];
                        return $cnt;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }

	/**
        * This function return the count of the query based on all search parameters specified.
        */

	private function getQuery()
        {
		$mailerspec = new mmmjs_99_execution('99_master');
		$type = $this->searchQuerySpecs['recipient_type'];
                $count = $mailerspec->getQueryAndResultBySearchCriteria($type,$this->searchQuerySpecs,'COUNT(DISTINCT(PROFILEID)) as cnt','','',$this->searchQuerySpecs['mailer_id']);
                return $count[0]['cnt'];
        }


	/**
        * Logs the search criteia in the table.
        */
        public function logSearchCriteria()
        {
                $mailer_spec = new mmmjs_99_execution;
                $mailer_spec->insertEntry($this->searchQuerySpecs);
//		$this->getDumpResult($this->searchQuerySpecs['mailer_id']);
        }

	 public function createDump($id,$testCase='')
        {
                try
                {
                        if($testCase)
                                $this->dumpForTesting=1;

                        //$mailerspec = SearchQueryFactory::getObject('9','',$id);
                        //$mailerspec->getMysqlDumpData('PROFILEID, EMAIL, NAME, PHONE');
			$this->getMysqlDumpData('DISTINCT(PROFILEID), EMAIL, NAME, PHONE');
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function getMysqlDumpData($fields)
        {
                $mailerspec = new mmmjs_99_execution('99_master');
		$type = $this->searchQuerySpecs['recipient_type'];
		if($type == 'B')
			$upper_limit = $this->searchQuerySpecs['buyer_upper_limit'];
		else
			$upper_limit = $this->searchQuerySpecs['seller_upper_limit'];
		
		if($this->dumpForTesting==1)
			$upper_limit=1;

		$limit_start = 0;
		$limit = $this->limitMaxInsertion;
		$mmmjs_MAIN_MAILER =  new mmmjs_MAIN_MAILER;
                $query = $mmmjs_MAIN_MAILER->getSubQuery($this->searchQuerySpecs['mailer_id']);
		if($query == '') return;
		do{
			if($upper_limit != ''){
				$temp = $limit_start + $limit;
				if($upper_limit <= $temp){
					$limit = $upper_limit - $limit_start;
					$break = 1;
				}
			}
			if($limit != 0) {
				$sql = "Select $fields FROM $query LIMIT $limit_start,$limit";
				$dumpData = $mailerspec->executeFetchedQuery($sql);
			}
				//$dumpData = $mailerspec->getQueryAndResultBySearchCriteria($type,$this->searchQuerySpecs,$fields,$limit_start,$limit);
			if(!empty($dumpData)){
				$id = $this->searchQuerySpecs['mailer_id'];
				$mailer_spec_js = new mmmjs_99_execution();
                		$mailer_spec_js->PopulateDumpData($dumpData,$id);
			}
			if($break == 1)
				break;
			$limit_start += $limit;
		}while(count($dumpData) > 0);
        }

	public function showMailerSpecs($id,$toLowerCase='')
        {
                try
                {
                        $mailer_spec = new mmmjs_99_execution(); // no need to send 99_master as we retrieve search specs from mmmjs Database.
                        $res = $mailer_spec->retrieveEntry($id);
                        if(!$toLowerCase)
                                return $res;
			if(!$res)
				return NULL;
                        foreach($res as $k=>$v)
                                if($k!='ID')
                                        $res1[strtolower($k)] = $v;
                        return $res1;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

}

?>
