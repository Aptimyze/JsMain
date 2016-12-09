<?php
/**
 * @brief This class contains the search types related to search
 */
class MyjsSearchTupplesEnums
{
        //These are the enums that are required for Caching of Myjs Tupples.
        public static $DAILYMATCHES ="DDR";
        public static $JUSTJOINED   ="JJR";
        public static $DESIREDPARTNERMATCHES ="PMR";
        public static $VERIFIEDMATCHES ="VMR";
        public static $LASTSEARCH ="LS";

        public static function getListNameForCaching($listName)
        {

        	switch ($listName) {
        		case 'DAILYMATCHES':
        			return self::$DAILYMATCHES;
        		case 'JUSTJOINED':
        			return self::$JUSTJOINED;
        		case 'DESIREDPARTNERMATCHES':
        			return self::$DESIREDPARTNERMATCHES;
        		case 'VERIFIEDMATCHES':
        			return self::$VERIFIEDMATCHES;
        		case 'LASTSEARCH':
        			return self::$LASTSEARCH;
        		
        	}


        }

        
}
?>
