<?php

class CopyDistributedPhotoUrlTask extends sfBaseTask
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
			new sfCommandArgument('pictureId', sfCommandArgument::REQUIRED, 'PictureId'),// should be 0 if not profileid
			new sfCommandArgument('profileId', sfCommandArgument::REQUIRED, 'ProfileId'), // should be 0 if pictureid
		));
		
	    $this->namespace        = 'PhotoScreen';
	    $this->name             = 'CopyDistributedPhotoUrl';
	    $this->briefDescription = 'Preprocess all uploaded pics, and resize main pic if required and also store the original uploaded pic';
	    //TODO : Update Description
	    $this->detailedDescription = <<<EOF

	Call it with:

	  [php symfony PhotoScreen:CopyDistributedPhotoUrl pictureId profileId] 
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
		$PICTURE_FOR_SCREEN_NEW = new PICTURE_FOR_SCREEN_NEW;
		if($arguments["profileId"]>0)
			$param["PROFILEID"]=$arguments["profileId"];
		if($arguments["pictureId"]>0)
			$param["PICTUREID"]= $arguments["pictureId"];
		$arrData = $PICTURE_FOR_SCREEN_NEW->get($param);	
		foreach ($arrData as $k=>$v)
		{
			foreach(ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS as $i=>$pic)
			{
				if(!strstr($v[$pic],JsConstants::$photoServerName))
				{
					$temp1  = $v[$pic];
					if($temp1)
					{
						$source = PictureFunctions::getCloudOrApplicationCompleteUrl($temp1);
						$dest = PictureFunctions::getCloudOrApplicationCompleteUrl($temp1,true);
						copy($source,$dest);
						foreach(JsConstants::$photoServerShardingEnums as $server)
						{
							if(strstr($temp1,$server)!=FALSE)	
								$copyUpdatedArr[$pic] = str_replace($server,JsConstants::$photoServerName,$temp1);
						}
					}
				
				}
			}
			if($copyUpdatedArr && $v["PICTUREID"] && $v["PROFILEID"])
				$PICTURE_FOR_SCREEN_NEW->edit($copyUpdatedArr,$v["PICTUREID"],$v["PROFILEID"]);
		}
		
	}
	
}
?>
