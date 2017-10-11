<?php
/**
 * Declare the interface 'SortStrategyInterface' which defines list of functions to be implemented for any sorting class of search. 
 * @author Lavesh Rawat
 * @package Search
 * @subpackage Sort
 * @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
 * @since 2012-07-27
*/
interface SortStrategyInterface
{
	/**
	* sorting expression.
	* @access public
	*/
	public function getSortString();
}
