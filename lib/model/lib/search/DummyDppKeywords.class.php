<?php
/**
 * @brief This class is used to DummyDppKeywords.
 * @author Reshu Rajput
 * @created 31 Apr 2016
 */

class DummyDppKeywords
{
	private $pid;
  public function __construct($profileId)
  {
		$this->pid =  $profileId;
  }

	
	public function setDummyDPPKeywords($SearchParamtersObj)
	{ 
			$Dummy_DPP_KEYWORDSObj = new Dummy_DPP_KEYWORDS();
			$params["PROFILEID"]=$this->pid;
			$params["KEYWORD"]= $SearchParamtersObj->getKEYWORD();
			$params["KEYWORD_TYPE"]=$SearchParamtersObj->getKEYWORD_TYPE();
			$Dummy_DPP_KEYWORDSObj->insert($params);
	}

	
	public function getDummyDPPKeywords()
	{
      $Dummy_DPP_KEYWORDSObj = new Dummy_DPP_KEYWORDS();
      $result =  $Dummy_DPP_KEYWORDSObj->select($this->pid);
      return $result;
	}

}
?>
