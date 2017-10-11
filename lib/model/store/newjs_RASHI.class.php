<?php
class NEWJS_RASHI extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getRashi($val)
        {
			try 
			{
				if($val)
				{ 
          $arrRashi=FieldMap::getFieldLabel("rashi",'',1);
          
          if($val) {
            return array('LABEL'=>$arrRashi[$val]);
          }
					return array();
				}
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
}
?>
