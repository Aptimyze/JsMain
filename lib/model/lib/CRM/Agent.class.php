<?php
/**
 * @class Agent
 * @brief contains get, set methods for individual profile registered on Jeevansathi
 * @author Ankita Gupta
 */

class Agent{
	protected $AGENTID;
	private $USERNAME;
	private $ACTIVE;
	public $PSWRDS; //PSWRDS Object
	private $PRIVILAGE;
	protected $fieldsArray="ACTIVE,PRIVILAGE";

	/**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database name to which the connection would be made
     * @param $agentid - Unique agentid of agent
     */
    public function __construct($dbname="", $agentid=""){
		$this->PSWRDS = jsadmin_PSWRDS::getInstance($dbname);
		if($agentid)	$this->agentid=$agentid;
    }
    /**
     * @fn getInstance
     * @brief fetches the current instance of the class
     * @param $dbName - Database name to which the connection would be made
     * @param $profileid - Unique profileid of profile
     * @return instance of the last object. If required profile object is not present then returns new object.
     */
    public static function getInstance($dbName="",$profileid="")
    {
        if(isset(self::$instance))
		{
			if($profileid && (self::$instance->getAGENTID() != $profileid)){
				$class = __CLASS__;
				self::$instance = new $class($dbName,$profileid);
			}
		}
		else
        {
                $class = __CLASS__;
                self::$instance = new $class($dbName,$profileid);
        }
        return self::$instance;
    }
    /**
         * @fn getDetail
         * @brief fetches profile detail. sets the detail to Agent Object.
         * @param $value Query criteria value
         * @param $criteria Query criteria column
         * @param $fields Columns to query
         * @param $effect RAW or DECORATED; 
		  Use RAW for getting direct results from PSWRDS, 
		  Use DECORATED for getting results to display
         * @return Agent detail array;
         */
        
	public function getDetail($value="", $criteria="", $fields="", $effect="RAW"){
		if(!$criteria){ $criteria = 'RESID'; if(!$value) $value=$this->AGENTID;} 
		$this->$criteria=$value;
		$res = $this->PSWRDS->get($value, $criteria, $fields);//Fetches results from PSWRDS
        $detail = false;
        if(is_array($res))
        {
            $detail = $this->setDetail($res, $effect);//Sets profile detail to the object
            $this->fieldsArray=array_keys($detail);
        }
		return $detail;
	}
	/**
         * @fn setDetail
         * @brief sets the detail to Agent Object as per the effect.
         * @param $res Key-value pair of columns and data-value.
         * @param $effect RAW or DECORATED; 
         * @return Agent detail array;
         */
	public function setDetail($res, $effect="DECORATED"){
		if($res){
			foreach($res as $field=>$value){
				$this->$field=$value;
			}
		}
		return $res;
	}
	function setAGENTID($AGENTID) { $this->AGENTID = $AGENTID; }
	function getAGENTID() {
		return $this->AGENTID; 
	}
	function setUSERNAME($USERNAME) { 
		$this->USERNAME = $USERNAME; 
	}
	function getUSERNAME() {
		return $this->USERNAME;
	}
	function setACTIVE($ACTIVE) { 
		$this->ACTIVE = $ACTIVE; 
	}
	function getACTIVE() {
		return $this->ACTIVE;
	}
	function setPRIVILAGE($PRIVILAGE) { 
		$this->PRIVILAGE = $PRIVILAGE; 
	}
	function getPRIVILAGE() {
		return $this->PRIVILAGE;
	}
	
}
?>
