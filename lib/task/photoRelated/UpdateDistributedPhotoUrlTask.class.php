<?php

class UpdateDistributedPhotoUrlTask extends sfBaseTask
{
	
	private $LIMIT_RECORDS = 3;
	
	/**
	 * @Override
	 * Configure , Symfony method of configure this task
	 * @access protected
	 * 
	 */
	protected function configure()
  	{
		$this->addArguments(array(
			new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'TotalScript'),
			new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'CurrentScript'),
		));
		
	    $this->namespace        = 'PhotoScreen';
	    $this->name             = 'UpdateDistributedPhotoUrl';
	    $this->briefDescription = 'Preprocess all uploaded pics, and resize main pic if required and also store the original uploaded pic';
	    //TODO : Update Description
	    $this->detailedDescription = <<<EOF

	Call it with:

	  [php symfony PhotoScreen:UpdateDistributedPhotoUrl totalScripts currentScript] 
EOF;
	}
	
	
	/**
	 * Symfony execute function, Main Function for executing task
	 * @access public
	 * @params arguments  : Array of required and optional arguements
	 * @params options  : Array of options and optional arguements 
	 */
	public function execute($arguments = array(), $options = array())
	{
		$CONSTSERVER = 'JSPIC1';
		$strToReplace = 'JS/uploads';
		$strToBeReplaced = 'JS/'.$CONSTSERVER.'/uploads';
		$PICTURE_FOR_SCREEN_NEW = new PICTURE_FOR_SCREEN_NEW;
		$param["DISTRIBUTEDCONST"]='%JSPIC%';
		$param["TOTALSCRIPT"]=$arguments["totalScripts"];
		$param["CURRENTSCRIPT"]= $arguments["currentScript"];
		$param["LIMIT"]= $this->LIMIT_RECORDS;
		$arrData = $PICTURE_FOR_SCREEN_NEW->getPreDistributedData($param);	
		foreach ($arrData as $k=>$v)
		{
			unset($copyUpdatedArr);
			foreach(ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS as $i=>$pic)
			{
					if($v[$pic] && strstr($v[$pic],$strToReplace)!==FALSE)
						$copyUpdatedArr[$pic] = str_replace($strToReplace,$strToBeReplaced,$v[$pic]);
			}
			if($copyUpdatedArr && $v["PICTUREID"] && $v["PROFILEID"])
				$PICTURE_FOR_SCREEN_NEW->edit($copyUpdatedArr,$v["PICTUREID"],$v["PROFILEID"]);
		}
		
	}
	
}
?>
