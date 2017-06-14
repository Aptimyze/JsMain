<?

//include_once('ProcessingClassInterface.class.php');
//include_once('CacheableInterface/Cache.class.php');
//include_once('CacheableInterface/LRUObjectCache.class.php');

class CrmHandler implements VariableHandler{

  private $__profile_obj;
  private $__cache;
  private $_var_object;

  public function __construct($var_object) {
	  $this->_var_object=$var_object;
    $this->__profile_obj = null;
    $this->__lru = new Cache(LRUObjectCache::getInstance());
  }
  /*
     public function setParams($params = array()) {
     if (isset($params) && is_array($params)) {
     $this->__profile_id = $params['PROFILEID'];
     $this->__token = $params['TOKEN'];
     $this->__profile_obj = $this->__lru->get($this->__profile_id); //new Profile("", $this->__profile_id);
     }
     }*/

  public function getActualValue() {
    $token = strtoupper($this->_var_object->getVariableName());
    $this->__profile_obj = $this->__lru->get($this->_var_object->getParam("profileid"));
    $agentDetails = CommonFunction::getJsCenterDetails($this->__profile_obj->getCITY_RES());
    if (isset($token)) {
    	switch($token)
    	{
    		case "AGENT_NAME":
    			if(is_array($agentDetails))
    			return $agentDetails['AGENT'];
    			break;
    		case "AGENT_CONTACT":
    			if(is_array($agentDetails))
    			return $agentDetails['MOBILE'];
    			break;
    		case "AGENT_ADDRESS":
    			if(is_array($agentDetails))
    			return $agentDetails['LOCALITY'];
    			break;
    }
  }
  }
 }

