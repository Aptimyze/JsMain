<?php
class SuccessStories
{
	private $dbObj;
	private $getterAllowed;
	function __construct($dbName='',$id='')		//KEEP CONSTRUCTOR PARAMS OPTIONAL AS NO PARAM IS PASSED FROM PHOTO TRANSFER CRON
	{
		$this->getterAllowed=array("ID","NAME_H","NAME_W","NAME","WEDDING_DATE","USERNAME","CONTACT_DETAILS","EMAIL","COMMENTS","DATETIME","UPLOADED","USERNAME_H","USERNAME_W","SKIP_COMMENTS","EMAIL_W","SEND_EMAIL","PIC_URL");
		$this->defaultArr =array("ID"=>'',"NAME_H"=>'',"NAME_W"=>'',"NAME"=>'',"WEDDING_DATE"=>'',"USERNAME"=>'',"CONTACT_DETAILS"=>'',"EMAIL"=>'',"COMMENTS"=>'',"DATETIME"=>'',"UPLOADED"=>'',"USERNAME_H"=>'',"USERNAME_W"=>'',"SKIP_COMMENTS"=>'',"EMAIL_W"=>'',"SEND_EMAIL"=>'',"PIC_URL"=>'');
		$this->dbObj=new NEWJS_SUCCESS_STORIES($dbName);
		if($id)
		{
			$this->_default($id);
		}
	}
	function FetchSkipped()
	{
		
	}
	function UpdateGetVar($resultArr)
	{
		$this->defaultArr = array_merge($this->defaultArr,$resultArr);
		foreach($this->defaultArr as $key=>$val)
                {       $fncName="set$key";
                        $this->$fncName($val);
                }
	}
	function _default($id)
	{
		$resultArr=$this->dbObj->fetchStoryById($id);
		$this->UpdateGetVar($resultArr);
	}
	public function ReplaceRecord()
	{
		$this->dbObj->ReplaceRecord($this);
	}
	public function UpdateRecord()
	{
		$this->dbObj->UpdateRecord($this);
	}
	public function __call($name,$arg)
	{
		$temp=str_replace("get","",$name);
		if($temp!=$name)
			return $this->get($temp);
		else
		{
			$temp=str_replace("set","",$name);
			$this->set($temp,$arg[0]);
		}
	}
	public function set($name, $value)
	{
		if(in_array($name,$this->getterAllowed))
			$this->$name=$value;
		else
			throw new JsException("","$name  is not allowd");
		
	}
	public function get($name)
	{
		if(in_array($name,$this->getterAllowed))
		{
			return $this->$name;
		}
		else
			throw new JsException("","$name  is not allowd");
	}

	/*
	This function acts as the wrapper function to update records on newjs.SUCCESS_STORIES table
	@param - parameter array where index is the column name to up updated and value has the values of the column to be updated, id on which update takes place, another id(optional)
	@return - true/false
	*/
	public function edit($paramArr=array(),$Id,$Id2='')
        {
                $photoObj=new NEWJS_SUCCESS_STORIES;
                $status=$photoObj->edit($paramArr,$Id);
                return $status;
        }

     /**
	 * 
	 * start transaction 
	 */
	public function startTransaction()
	{
		$this->dbObj->startTransaction();
	}
	/**
	 * 
	 * commit transaction
	 */
	public function commitTransaction()
	{
		$this->dbObj->commitTransaction();
	}
}
?>
