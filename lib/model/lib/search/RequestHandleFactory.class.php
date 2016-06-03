<?php
/**
 * request factory class to return object of appropriate engine type (eq. sphinx/lucene/mysql-table)
 * @author Lavesh Rawat
 * @created 2012-06-10
 */
class RequestHandleFactory
{
	/*
	* @param responseObj contains information about output type (array/xml/...) and engine used(sphinx/lucene/mysql..)
	* @param SearchParamtersObj 
	*/
	static public function getRequestEngine($responseObj,$SearchParamtersObj)
	{
		if($responseObj->getResponseEngine()=='solr')
			return new SolrRequest($responseObj,$SearchParamtersObj);
	}
}
