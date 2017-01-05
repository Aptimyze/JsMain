<?php
/**
 * @brief This class is search channels interface class
 * @author Reshu Rajput
 * @created 2015-09-01
 */

interface SearchChannelInterface
{

        /* This function will return the configuration object of required channel
        */
        public function __construct($params);

        /* This function will return the channel specific variables
        *@param params : need to be set 
        */
        public function setVariables($params);


	/**
	* This function will give search type corresponding to quick search band.
	*/
	public static function getSearchTypeQuick();

	/**
	* This function will give search type corresponding to matchalerts
	*/
	public static function getSearchTypeMatchalerts();
        
        /**
        * getMembersLookingForMe
        */
        public static function getSearchTypeMembersLookingForMe();
        
        /**
        * getJJMatches
        */
        public static function getSearchTypeJJMatches();
        
        /**
        * getSearchTypeTwoWayMatches
        */
        public static function getSearchTypeTwoWayMatches();
        
        /**
        * getSearchTypeKundliMatches
        */
        public static function getSearchTypeKundliMatches();
        
        
        /**
        * get Education and occupation detailed clusters
        */
        public function eduAndOccClusters($clustersToShow,$params="");
        
        
        /**
	* This function will set the No. Of results for search Page
	*/
	public function getNoOfResults();
	
	/**
	* This function will set the No. Of featured profiles results for search Page
	*/
	public function getFeaturedProfilesCount();
	
	/**
	* This function will set the featured profiles stype for search Page
	*/
	public function getFeaturedProfilesStype();
	
	/**
	* This function will set the channel type
	*/
	public function getChannelType();
         
        /**
         * This fucntion will set the cluster block if page is calling search without cluster
         * @param params : need to be set  
         */
        public function setRequestParameters($params);
} 
?>
