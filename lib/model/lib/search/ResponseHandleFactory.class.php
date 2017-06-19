<?php
/**
 * Based of search engine type it returns handle(object) of response.
 * @author Lavesh Rawat
 * @created 2012-06-10
 */
class ResponseHandleFactory
{
	/**
	* this function will return response object of one of possible engine type for searching (sphinx/mysql)
	* solr is the only engine we have implemented currently.
	* @param resultType output format like array , xml ....
	* @param engineType (like solr,sphinx,mysql....)
	* @param showAllClustersOptions  containing list of clusters to show in order of display.
	* @return reponse object of the appropriate engine
	*/
	static public function getResponseEngine($resultType,$engineType,$showAllClustersOptions)
	{
		if($engineType=='solr')
			return new SolrResponse($resultType,$showAllClustersOptions);
	}
}
