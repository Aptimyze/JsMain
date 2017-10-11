<?php
/*
 * @brief Declare the interface 'RequestHandleInterface' which defines list of fucntions to be implemented for any engine used to peform search. 
 * @author Lavesh Rawat
 * @created 2012-06-10
 */
interface RequestHandleInterface
{
	public function getResults($results_cluster='',$clustersToShow,$currentPage='',$cachedSearch='',$loggedInProfileObj='');
	public function getGroupingResults($grpField,$grpLimit='',$grpSort='',$grpRows='',$loggedInProfileObj='');
}
