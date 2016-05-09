<?php
/**
 * Search Query Factory class 
 * @author Lavesh Rawat
 * @created 2013-08-13
 */
class SearchQueryFactory
{
	/**
	* This function relturn the object of class based on website..
	* @param website site eg J/99.
	* @param querySpecfication criteria specified.
	* @id unique id of search criteria table ( this table is different for each wesbite).
	* @return class object
	*/
        public static function getObject($website,$querySpecfication='',$id='')
        {
		if($website=='J')
	                return new SearchQueryJs($querySpecfication,$id);
		elseif($website=='9')
	                return new SearchQuery99($querySpecfication,$id);
        }
}

