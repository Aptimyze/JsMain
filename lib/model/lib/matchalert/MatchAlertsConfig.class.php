<?php
/*This class is used to handle the matchalerts*/
class MatchAlertsConfig
{
	public $timeRangeStart = 1;
	public $timeRangeEnd = 9;
        private $solrUrl = array('0'=>'solrServerProxyUrl','1'=>"solrServerLoggedOut",'2'=>"solrServerProxyUrl1");
        
        public $instanceNonPeak = 19;
        public $instancePeak = 9;
        
        public static $dppCountCacheTime = 864000;
        public static $DPP_HAVEPHOTO_CHECK_COUNTER = 1500;
        public function isMatchAlertsForNonPeakHour(){
                $nonPeak = CommonUtility::runFeatureInDaytime($this->timeRangeStart, $this->timeRangeEnd);
                return $nonPeak;
        }
        
        public function changeSolrForNonPeak(){
                $nonPeak = CommonUtility::runFeatureInDaytime($this->timeRangeStart, $this->timeRangeEnd);
                if($nonPeak == true){
                        $random = rand(1, 1000000);
                        if($random%999999<=1){
                                $varNAme = $this->solrUrl[0];
                        }else{
                                $varNAme = $this->solrUrl[2];
                        }
                }else{
                        $varNAme = $this->solrUrl[0];
                }
                return JsConstants::$$varNAme;
        }

}
?>
