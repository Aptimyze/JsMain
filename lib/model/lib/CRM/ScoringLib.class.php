<?php

/* This class consists of common helper functions used for related actions. */
/**
 *
 * @author smarth.katyal
 */
class ScoringLib {
    
    /*
     * This function will return the analytic score of a particular profile ID.
     * If it is available in Redis, it will return from redis, 
     * Else it will query from DB and set in Redis and return
     * It takes a single profileid as input
     * It returns the analytics score.
     */
    public static function getAnalyticScore($profileid){
        if($profileid =="")
            return "Empty ProfileID";
        $memcacheObj = JsMemcache::getInstance();
        $memCacheKey = "AS_". $profileid;
        $analyticScore = $memcacheObj->get($memCacheKey);
        if($analyticScore == ''){//Key not set, fetch from db and set the key with TTL
            $mainAdminPoolObj = new incentive_MAIN_ADMIN_POOL();
            $analyticScore = $mainAdminPoolObj->getAnalyticScore($profileid);
            $memcacheObj->set("$memCacheKey", $analyticScore, 86400);
            return $analyticScore;
            //Calculate TTL
            //Set TTL such that ResetScore from redis at 6:00PM when cron executes to calculate new score
            //$timenow = date("H:i:s");
            //$timelater =  date('H:i:s',strtotime("+1 day"));
            //print_r("\nTimeNow: ". $timenow);
            //print_r("\nTimeLater: ". $timelater);
            //$ttl = strtotime($timelater) - strtotime($timenow);
            //print_r("\n TTL1: ". $ttl."\n");
            //if($ttl<=0){
            //    $ttl = 86400 + $ttl;
            //}
            //print_r("\nTTL2: ". $ttl . "\n");
        }
        return $analyticScore;
        
    }
}

?>