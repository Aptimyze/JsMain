<?php
/**
* This class contains the search types related to search
* @author : Lavesh Rawat
* @package Search
* @subpackage Sort
* @copyright 2013 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2013-12-01
*/
class SearchSortTypesEnums
{
        const popularSortFlag = 'P';
        const relevanceSortFlag = 'T';
        const dateSortFlag = 'O';
     	const featureSortFlag = 'FP';
        const ntimesSortFlag = 'S';
        const newMatchesMailer = 'NP';
        const justJoinedSortFlag = 'V';
        const matchAlertFlag = 'MA'; 
        const kundliAlertFlag = 'KA'; 
        const viewAttemptFlag = 'VA'; 
        const SortByTrendsScore = 'DT'; 
        const FullDppWithReverseFlag = 'DR'; 
        const SortByLoginDate = 'LT';
        const SortByVisitorsTimestamp = 'VT';
        const SortByBroaderDppScore = 'BD'; 
        
        // unified match alerts sort types
        const SortByStrictTrends = 'ST'; 
        const SortByStrictNonTrends = 'SNT';
        const SortByPhotoForAP = 'PAP';
        
        const SortByRelaxedTrends = 'RT'; 
        const SortByRelaxedNonTrends = 'RNT'; 
}
?>
