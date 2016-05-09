<?php
/**
 * @class LoggedInAgent
 * @brief contains get, set methods for logged-in agent
 * @author Ankita Gupta
 */

class LoggedInAgent extends Agent{

	private static $instance;
	public $PSWRDS;

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database name to which the connection would be made
     * @param $agentid - agentid of logged-in agent
     */
    public function __construct($dbname="", $agentid=""){
            $this->PSWRDS = jsadmin_PSWRDS::getInstance($dbname);
            if(!$agentid)  $this->setagentid(sfContext::getInstance()->getRequest()->getAttribute('agentid'));
	else $this->setagentid($agentid);
    }

    /**
     * @fn getInstance
     * @brief fetches the current instance of the class
     * @param $dbName - Database name to which the connection would be made
     * @param $agentid - agentid of logged-in agent
     * @return instance of the last object. If required agent object is not present then returns new object.
     */
    public static function getInstance($dbName="",$agentid="")
    {
            if(isset(self::$instance))
            {
                    //If different instance is required
                    if($agentid && (self::$instance->getagentid() != $agentid)){
                            $class = __CLASS__;
                            self::$instance = new $class($dbName,$agentid);
                    }
            }
            else
            {
                    $class = __CLASS__;
                    self::$instance = new $class($dbName,$agentid);
            }
            return self::$instance;
    }

    /**
     * @fn getDetail
     * @brief fetches agent detail. sets the detail to agent Object.
     * @param $value Query criteria value
     * @param $criteria Query criteria column
     * @param $fields Columns to query
     * @param $effect RAW or DECORATED; 
              Use RAW for getting direct results from Jagent, 
              Use DECORATED for getting results to display
     * @return agent detail array;
     */

    public function getDetail($value="", $criteria="", $fields="", $effect="RAW"){
            
            if($fields=="")
                $fields = $this->fieldsArray;
          
            if(!$criteria){ $criteria = 'RESID'; if(!$value) $value=$this->getagentID();} 
            $this->$criteria=$value;              
            $res = $this->PSWRDS->get($value, $criteria, $fields);//Fetches results from Jagent          
            $detail = $this->setDetail($res, $effect);//Sets agent detail to the object           
            return $detail;
    }
}
?>