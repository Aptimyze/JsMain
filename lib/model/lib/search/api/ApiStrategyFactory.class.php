<?php
/**
* Api Factory class 
* @author Lavesh Rawat
* @package Search
* @subpackage Api1
* @copyright 2013 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2013-12-01
*/
class SearchApiStrategyFactory
{
	/**
	* If we have multiple version running for api, then based on input parameters, it will return object of associated api.
	* @access public
	* @static
        * @param float $version 
        * @param SolrResponse $responseObj search-results.
        * @param string $results_orAnd_cluster (default all results, other possible value 'onlyClusters','onlyResults')
	* @return SearchApiStrategyV1
	*/
	static public function getApiStrategy($version,$responseObj,$results_orAnd_cluster='')
	{
		return new SearchApiStrategyV1($responseObj,$results_orAnd_cluster);
	}
}
