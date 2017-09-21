<?php
/**
 * PhotoScreen_PreproccessTask
 * Symfony Task Class
 * @package Symfony Task
 * @subpackage PhotoScreen
 */
/*
 * Author: Kunal Verma
 * Created: 29th Sept, 2014
 * This cron is used to preprocess the images which are uploaded by user and in photo screening 
 * This cron will also resize the main pic url to default width and height and will store the 
 * uploaded original pic under OriginalPicUrl column
*/

class PhotoScreen_PreproccessTask extends sfBaseTask
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * Store Object Of Non Screened Picture 
	 * @access private
	 * @var Object 
	 */
	private $m_objNonScreenedPicture ;
	
	/**
	 * Boolean Variable For enabled/disable debug logs 
	 * @access private
	 * @var Boolean 
	 */
	private $m_bDebug = false;
	
	/**
	 * Boolean Variable For enabled/disable debug logs 
	 * @access private
	 * @var Boolean 
	 */
	const LIMIT_RECORDS = 1;
	
	/**
	 * Object of Picture Function Utlilty Class
	 * @access private
	 * @var Boolean 
	 */
	private $m_objPicFunction;
	
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
	    $this->name             = 'PreprocessOperation';
	    $this->briefDescription = 'Preprocess all uploaded pics, and resize main pic if required and also store the original uploaded pic';
	    //TODO : Update Description
	    $this->detailedDescription = <<<EOF
	This cron runs every 5-10 min to get non screened images and resize the original pic in default width and height,  resize the main pic url to default width and height and will store the 
    uploaded original pic under OriginalPicUrl column.
	Call it with:

	  [php symfony PhotoScreen:PreprocessOperation totalScripts currentScript] 
EOF;
	}
	
	/**
	 * initVariables
	 * Initializing all member variables and constants
	 * @access private
	 */
	private function initVariables()
	{
		if($this->m_bDebug)
		{
			$this->logSection('Debug :', 'Start Point');
		}
		$this->DEFAULT_WIDTH 		= ProfilePicturesTypeEnum::$MAIN_PIC_MAX_SIZE["w"];
		$this->DEFAULT_HEIGHT 		= ProfilePicturesTypeEnum::$MAIN_PIC_MAX_SIZE["h"];
		$this->m_objPicFunction 	= new PictureFunctions();

	}
        
	/**
	 * Image getting corrupted due to image jeevansathi.com in url
         * This cron replaces jeevansathi.com with JS
	 * @access private
	 */
	private function UpdateForCorruptPrevent()
	{
                //Prevent Image corruption
                $pictureForScreenNewObj = new PICTURE_FOR_SCREEN_NEW();
                $pictureForScreenNewObj->UpdatePresentImageCorrupt();
        }       
	/**
	 * Symfony execute function, Main Function for executing task
	 * @access public
	 * @params arguments  : Array of required and optional arguements
	 * @params options  : Array of options and optional arguements 
	 */
	public function execute($arguments = array(), $options = array())
	{
		
		  if(CommonUtility::hideFeaturesForUptime())
                      successfullDie();
        $LockingService = new LockingService;
		ini_set('memory_limit','1024M');	
		ini_set("gd.jpeg_ignore_warning", 1);
		error_reporting(E_ALL & ~E_NOTICE);
		$this->initVariables();
                $this->UpdateForCorruptPrevent();
		if(PictureFunctions::IfUsePhotoDistributed('X'))
		{
			$matchToBeArr = JsConstants::$photoServerShardingEnums;
	
			/** copyyyyyyyyyyyyyyyy **/
			$PICTURE_FOR_SCREEN_NEW = new PICTURE_FOR_SCREEN_NEW;
			$arrDataForCopy = $PICTURE_FOR_SCREEN_NEW->getPreProcessData(array("MainPicUrl"=>"%".JsConstants::$photoServerName."%"));
			foreach($arrDataForCopy as $k=>$pid)
			{
				$paramArr["PROFILEID"] = $pid; 
				$others = 'JSPIC';
				$carr = $PICTURE_FOR_SCREEN_NEW->get($paramArr);
				if(is_array($carr))
				{
					$copyMe = '0';
					foreach($carr as $cakk => $cavv)
					{
						$serverName = PictureFunctions::getNameIfUsePhotoDistributed($cavv["MainPicUrl"]);
						$orderMinFinder[$cavv["ORDERING"]] = $serverName;
					}
					ksort($orderMinFinder);
					
					$minOrderingPhotoServer = $orderMinFinder[0];
					if($minOrderingPhotoServer == JsConstants::$photoServerName)
						$copyMe = 1;
					if($copyMe)
					{
						$abcArr = array( 'MainPicUrl','ProfilePicUrl','ThumbailUrl','Thumbail96Url','ProfilePic120Url','ProfilePic235Url','ProfilePic450Url','OriginalPicUrl','MobileAppPicUrl');
						foreach($carr as $cakk => $cavv)
						{
							unset($copyUpdatedArr);
							if(!strstr($cavv["MainPicUrl"],JsConstants::$photoServerName))
							{
								foreach($abcArr as $avk => $av1)
								{	
									$temp1  = $cavv[$av1];
									if($temp1)
									{
										$source = PictureFunctions::getCloudOrApplicationCompleteUrl($temp1);
										$dest = PictureFunctions::getCloudOrApplicationCompleteUrl($temp1,true);
										copy($source,$dest);
										foreach($matchToBeArr as $abcde)
										{
											if(strstr($temp1,$abcde)!=FALSE)	
												$copyUpdatedArr[$av1] = str_replace($abcde,JsConstants::$photoServerName,$temp1);
										}
									}
								}
							}
							if($copyUpdatedArr && $cavv["PICTUREID"] && $cavv["PROFILEID"])
							{
								$PICTURE_FOR_SCREEN_NEW = new PICTURE_FOR_SCREEN_NEW;
								$PICTURE_FOR_SCREEN_NEW->edit($copyUpdatedArr,$cavv["PICTUREID"],$cavv["PROFILEID"]);
							}
						}
					}
				}
				}
			
			
			
		}
		$arrPictures =  $this->getPictures($arguments);
		if( !$arrPictures || !count($arrPictures))
		{
			$this->logSection('Debug :', 'No data found');
		}
		if(is_array($arrPictures))
		{
			foreach($arrPictures as $key=>$arrData)
			{
				if($this->m_bDebug)
				{
					$this->logSection("ProfileId : ",$arrData['PROFILEID']);
					$this->logSection("PictureId : ",$arrData['PICTUREID']);
				}
				$iPicId = $arrData['PICTUREID'];
				if(!is_array($arrDataForCopy) || in_array($arrData['PROFILEID'],$arrDataForCopy))
				{
					$this->m_objProfile	= new Profile("",$arrData['PROFILEID']);
					$this->m_objProfile->getDetail("","","HAVEPHOTO");
					$arrData['PROFILE_TYPE'] = $this->getProfileType($this->m_objProfile->getHAVEPHOTO());
					$szType = $this->m_objPicFunction->getImageFormatType($arrData['MainPicUrl']);
					
					//////////////////////////////////////////////////////////////
					//Move Main Pic To Orginial Pic Directory
					$this->moveOriginalPic($iPicId,$arrData['PROFILEID'],$szType,$arrData['MainPicUrl']);
					//If Required, Resize Main Pic and Store into same MainPicUrl
					$this->resizePic($iPicId,$arrData['PROFILEID'],$arrData['MainPicUrl']);
					//detail image property
					$googleVisionObj = new GoogleVisionApi();
					$googleVisionObj->getPictureDetails($arrData['MainPicUrl']);
					//Update Store
					$this->updateStore($iPicId,$arrData);
					//Track This in Master Log
					$this->trackPhotoScreenMasterLog($arrData);
				}
				else if(!$this->m_bDebug)
				{
					$this->logSection('Info', 'No data found in copy array');
				}
			}
		}
		else if(!$this->m_bDebug)
		{
			$this->logSection('Info', 'No data found');
		}
	}
	
	/**
	 * Move Original Pic
	 * Function for moving original pic from MainPicUrl to OriginalPicUrl
	 * @access private
	 * @params iPicId  			: Picture Id
	 * @params iProfileId  		: Profile Id
	 * @params szType  			: Picture format(or type like jpeg,gif)
	 * @params szMainPicUrls  	: MainPicUrl, Path of existing original uploaded pic
	 */
	private function moveOriginalPic($iPicId,$iProfileId,$szType,$szMainPicUrls)
	{
		$this->szOriginalPicUrl_ForActualStorage = $this->m_objNonScreenedPicture->getSaveUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR['OriginalPicUrl'],$iPicId,$iProfileId,$szType);

		$this->szOriginalPicUrl_ForDb = $this->m_objNonScreenedPicture->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR['OriginalPicUrl'],$iPicId,$iProfileId,$szType);
		
		$this->szMainPicUrl_ForActualStorage = $this->m_objNonScreenedPicture->getSaveUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR['MainPicUrl'],$iPicId,$iProfileId,$szType);

		$this->szMainPicUrl_ForDb = $this->m_objNonScreenedPicture->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR['MainPicUrl'],$iPicId,$iProfileId,$szType);
		$bStatus  = $this->m_objPicFunction->moveImage($szMainPicUrls,$this->szOriginalPicUrl_ForActualStorage);
		
		if($this->m_bDebug)
		{
			$this->logSection("MoveOriginalPic : Old MainPicUrl : ",$szMainPicUrls);
			$this->logSection("MoveOriginalPic : New MainPicUrl_ForDb : ",$this->szMainPicUrl_ForDb);	
			$this->logSection("MoveOriginalPic : New MainPicUrl_ActualStorage : ",$this->szMainPicUrl_ForActualStorage);	
			$this->logSection("MoveOriginalPic : OriginalPicUrl_ForDb : ",$this->szOriginalPicUrl_ForDb);
			$this->logSection("MoveOriginalPic : OriginalPicUrl_ActualStorage : ",$this->szOriginalPicUrl_ForActualStorage);
		}
	}

	/**
	 * Resize Pic
	 * Function for resizing original pic with default width and height(if reuired) and to store as MainPicUrl
	 * @access private
	 * @params iPicId  			: Picture Id
	 * @params iProfileId  		: Profile Id
	 * @params szMainPicUrls  	: MainPicUrl, Path of existing original uploaded pic
	 */	
	private function resizePic($iPicId,$iProfileId,$szMainPicUrl)
	{
		$szNewPicUrls 	= "";
		$arrImageInfo 	= getimagesize($szMainPicUrl);

		$iWidth 		= $arrImageInfo[0];
		$iHeight 		= $arrImageInfo[1];
		
		$dDefaultRatio 	= $this->DEFAULT_WIDTH / $this->DEFAULT_HEIGHT;
		$dImageRatio	= $iWidth / $iHeight;
		
		if($dImageRatio > $dDefaultRatio)
		{
			$iNewHeight = (($iHeight / $iWidth) * $this->DEFAULT_WIDTH);
			$iNewWidth 	= $this->DEFAULT_WIDTH;
		}
		else
		{
			$iNewWidth 	= (($iWidth / $iHeight) * $this->DEFAULT_HEIGHT);
			$iNewHeight = $this->DEFAULT_HEIGHT;
		}
	
		if($iNewHeight && $iNewWidth)		
		{   
			if($this->m_bDebug)
			{
				$this->logSection("Resize : ","resampling image"); 
			}
			$szType			= $this->m_objPicFunction->getImageFormatType($szMainPicUrl);
			$image 			= $this->createImage($szMainPicUrl);
			$image_p 		= imagecreatetruecolor($iNewWidth,$iNewHeight);
			$bImageCreated 	= imagecopyresampled($image_p, $image, 0,0,0,0,$iNewWidth,$iNewHeight, $iWidth,$iHeight);
			
			if($bImageCreated)
			{	if($this->m_bDebug)
				{
					$this->logSection('Store image : ', $szType);
				}
				$this->storeResizedImage($image_p,$this->szMainPicUrl_ForActualStorage,$szType);
				return;
			}
		}
		//If Resampling not needed then store existing Pic onto new Url
		$this->m_objPicFunction->moveImage($szMainPicUrl,$this->szMainPicUrl_ForActualStorage);
		chmod($szMainPicUrl,0777);
                chmod($this->szMainPicUrl_ForActualStorage,0777);
	}

	/**
	 * Get Picture 
	 * Function for getting data from database(or store)
	 * @access private
	 * @params arguments  : Array of required and optional arguements
	 */		
	private function getPictures($arguments)
	{
		$totalScripts = $arguments["totalScripts"]; // total no of scripts
		$currentScript = $arguments["currentScript"]; // current script number

		$this->m_objNonScreenedPicture= new NonScreenedPicture();

		$arrPictures = $this->m_objNonScreenedPicture->getFreshUploadePictures($totalScripts,$currentScript);
		return $arrPictures;
	}

	/**
	 * Create Image
	 * Function for creating image from given path
	 * @access private
	 * @params szPath	  	: Path of image
	 */	
	private function createImage($szPath)
	{
		$szType = $this->m_objPicFunction->getImageFormatType($szPath);
		if($szType == "gif")
		{
			$image = imagecreatefromgif($szPath);
		}
		else if($szType == "jpeg")
		{
			$image = imagecreatefromjpeg($szPath);
		}
		
		return $image;
	}

	/**
	 * Store Resized Image
	 * Function for creating image from given path
	 * @access private
	 * @params new_image		: Raw image returned by imagecreatetruecolor()
	 * @params szStoragePath	: Path of image
	 * @params szType			: Pic Format
	 */	
	private function storeResizedImage($new_image,$szStoragePath,$szType)
	{
		if($szType == "gif")
		{
			imagegif($new_image, $szStoragePath);
		}
		else if($szType == "jpeg")
		{
			imagejpeg($new_image, $szStoragePath,90);
		}
		chmod($szStoragePath,0777);
	}

	/**
	 * Update Store 
	 * Function for updating store(or db)
	 * @access private
	 * @params iPicId			: Picture Id
	 * @params arrData			: Array of data(or info) for give picture id
	 */		
	private function updateStore($iPicId,$arrData)
	{	
		if($this->m_bDebug)
		{
			$this->logSection('UpdateStore : szOriginalPicUrl ',$this->szOriginalPicUrl_ForDb);
			$this->logSection('UpdateStore : MainPicUrl ',$this->szMainPicUrl_ForDb);
		}
		//Calculate new screen bits for this picture id
		$objPicService = new PictureService($this->m_objProfile);
		$arrUpdate = array();
		$arrUpdate[$iPicId] = array("OriginalPicUrl"=>$this->szOriginalPicUrl_ForDb,"MainPicUrl"=>$this->szMainPicUrl_ForDb);
		
		$objPicService->setPicProgressBit("RESIZE",$arrUpdate);
	}

	/**
	 * Track Photo Screen Master Log
	 * Function for updating photo screen master log
	 * @access private
	 * @params arrData			: Array of data(or info) for give picture id
	 */		
	private function trackPhotoScreenMasterLog($arrData)
	{
		$objMasterTrack = new JsPhotoScreen_Track_MasterLogs(
						$arrData['PROFILEID'],
						PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_PREPROCESS_CRON,
						$arrData['PROFILE_TYPE'],
						$arrData['UPDATED_TIMESTAMP']);
		$objMasterTrack->trackThis();
	}
	/**
	 * getProfileType
	 * Function for deciding the profile type as per the have photo value
	 * @access private
	 * @params cHavePhoto		: Char Value represting have photo status 
	 */	
	private function getProfileType($cHavePhoto)
	{
		$cPhotoType = '';
		if($cHavePhoto == PictureStaticVariablesEnum::$HAVE_PHOTO_STATUS['YES'])
		{
			$cPhotoType = 'E';//Photo Type = EDIT
		}
		else // In case of Underscreening, or blank value or No value
		{
			$cPhotoType = 'N';//Photo Type = NEW
		}
		return $cPhotoType;
	}
}
?>
