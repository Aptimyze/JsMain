<?php
class IndividualStories
{
	private $dbObj;
	private $getterAllowed;
	function __construct($dbName='',$sid='',$dbConnect=true)		//KEEP CONSTRUCTOR PARAMS OPTIONAL AS NO PARAM IS PASSED FROM PHOTO TRANSFER CRON
	{
		$this->getterAllowed=array("SID","STORYID","NAME1","NAME2","CASTE","RELIGION","CITY","COUNTRY","OCCUPATION","MTONGUE","HEADING","STORY","STATUS","YEAR","HOME_PIC_URL","MAIN_PIC_URL","FRAME_PIC_URL","SQUARE_PIC_URL");
		$this->defaultArr=array("SID"=>'',"STORYID"=>'',"NAME1"=>'',"NAME2"=>'',"CASTE"=>'',"RELIGION"=>'',"CITY"=>'',"COUNTRY"=>'',"OCCUPATION"=>'',"MTONGUE"=>'',"HEADING"=>'',"STORY"=>'',"STATUS"=>'',"YEAR"=>'',"HOME_PIC_URL"=>'',"MAIN_PIC_URL"=>'',"FRAME_PIC_URL"=>'',"SQUARE_PIC_URL"=>'');
		if($dbConnect)
			$this->dbObj=new NEWJS_INDIVIDUAL_STORIES($dbName);
		if($sid)
		{
			$this->_default($sid);
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
	function _default($sid)
	{
		$resultArr=$this->dbObj->getPictureInfoBySID($sid);
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
	
	/**
	 *get success stories for the right panel
	 *@param
	 *@return array
	 *@uses newjs_INDIVIDUAL_STORIES,
	 *
	 */
	public static function showSuccessPoolStory()
	{
		$keyMemcache = JsMemcache::SUCCESS."_OLD";
		$rightPanelStoryArr = unserialize(JsMemcache::getInstance()->get($keyMemcache));
		//print_r($rightPanelStoryArr);die;
		if(!is_array($rightPanelStoryArr))
		{
			$dbObj = new newjs_INDIVIDUAL_STORIES();
			$rightPanelStoryArr = $dbObj->getRightPanelStory();
			foreach($rightPanelStoryArr as $key=>$val)
			{
				if($rightPanelStoryArr[$key][SQUARE_PIC_URL]==NULL)
				{
					$rightPanelStoryArr[$key][SQUARE_PIC_URL]=$rightPanelStoryArr[$key][MAIN_PIC_URL];
				}
				$rightPanelStoryArr[$key][HOME_PIC_URL]=PictureFunctions::getCloudOrApplicationCompleteUrl($rightPanelStoryArr[$key][HOME_PIC_URL]);
				
				$rightPanelStoryArr[$key][FRAME_PIC_URL]=PictureFunctions::getCloudOrApplicationCompleteUrl($rightPanelStoryArr[$key][FRAME_PIC_URL]);
				if(substr($rightPanelStoryArr[$key][STORY],150,151) !==" "){					
					$rightPanelStoryArr[$key][STORY]=substr($rightPanelStoryArr[$key][STORY],0,strrpos(substr($rightPanelStoryArr[$key][STORY],0,150) , " "))."...";
				}
				else
					$rightPanelStoryArr[$key][STORY]=substr($rightPanelStoryArr[$key][STORY],0,150);
					
				if(strrpos($rightPanelStoryArr[$key][NAME1]," ")>9 || strrpos($rightPanelStoryArr[$key][NAME1]," ")=== false){	
					$rightPanelStoryArr[$key][NAME1]=substr($rightPanelStoryArr[$key][NAME1],0,10);
				}
				else
					$rightPanelStoryArr[$key][NAME1]=substr($rightPanelStoryArr[$key][NAME1],0,strrpos($rightPanelStoryArr[$key][NAME1]," "));
				
				if(strrpos($rightPanelStoryArr[$key][NAME2]," ")>9 || strrpos($rightPanelStoryArr[$key][NAME2]," ")=== false){	
					$rightPanelStoryArr[$key][NAME2]=substr($rightPanelStoryArr[$key][NAME2],0,10);
				}
				else
					$rightPanelStoryArr[$key][NAME2]=substr($rightPanelStoryArr[$key][NAME2],0,strrpos($rightPanelStoryArr[$key][NAME2]," "));
				
			}
			JsMemcache::getInstance()->set($keyMemcache,serialize($rightPanelStoryArr),2591000);
		}

		return $rightPanelStoryArr;
	}
	
	
	/**
	 * 
	 * start transaction.
	 */
	public function startTransaction()
	{
		$this->dbObj->startTransaction();
	}
	/**
	 * 
	 * commit transaction.
	 */
	public function commitTransaction()
	{
		$this->dbObj->commitTransaction();
	}
	
	/*
        This function acts as the wrapper function to update records on newjs.INDIVIDUAL_STORIES table
        @param - parameter array where index is the column name to up updated and value has the values of the column to be updated, id on which update takes place, anot
her id(optional)
        @return - true/false
        */
	public function edit($paramArr=array(),$Id,$Id2='')
        {
                $photoObj=new newjs_INDIVIDUAL_STORIES;
                $status=$photoObj->edit($paramArr,$Id);
                return $status;
        }
}
?>
