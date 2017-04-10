<?php
//include_once(sfConfig::get('sf_lib_dir') . '/vendor/facebook-php-sdk-master/src/facebook.php');
include_once(sfConfig::get('sf_lib_dir') . '/vendor/facebook-sdk-v5/autoload.php');
class fb extends BaseImportPhoto
{
	private $fbsession;
	private $facebook;
	
	public function __construct()
	{
		@session_start();
		$this->facebook=new Facebook\Facebook(array(
                            'app_id' => JsConstants::$fbId,
                            'app_secret' => JsConstants::$fbSecret,
                            'default_graph_version' => 'v2.5'
                        ));
	$this->helper = $this->facebook->getRedirectLoginHelper();

	}
	public function getToken()
	{
		  $this->accessToken = $this->helper->getAccessToken()->getValue();
	}
	/**
	This function is used to authenticate a logged-in facebook user.
	Its sets access_token(with some more info) which maintains user session.
	**/
        private function authenticateUser()
        {
//                if(is_null($this->facebook->getUser()) || $this->facebook->getUser()==0)
		$this->accessToken = $this->helper->getAccessToken();
		$_SESSION['fb_access_token'] = (string) $this->accessToken;
		if(!$this->accessToken)
		{
                        $this->askForLogin();
		}
        }

	
/************************************************/
/************************************************/

/**
This function is used to get a list of details of albums created by the user.
list of album URLs, album names and cover image URLs.
**/
public function getAlbumList()
{
	$this->authenticateUser();
	try {
		$response = $this->facebook->get('/me?fields=albums{photos{picture,source},type,name,cover_photo{picture}}',$this->accessToken);
	}
	  // When Graph returns an error
	catch(Facebook\Exceptions\FacebookResponseException $e) {
		unset($this->final);
		file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/fbUpload",var_export($_SERVER,true)."Graph returned an error:".$e->getMessage()."\n\n\n",FILE_APPEND);
		return;
	}
	  // When validation fails or other local issues
	catch(Facebook\Exceptions\FacebookSDKException $e) {
		unset($this->final);
		file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/fbUpload",var_export($_SERVER,true)."Facebook SDK returned an error:".$e->getMessage()."\n\n\n",FILE_APPEND);
		return;
	}
	$albumData = $response->getGraphAlbum();
	$albumData = json_decode($albumData,true);
	if($this->accessToken)
	{
		if(array_key_exists("albums",$albumData))
		{
			foreach($albumData[albums] as $k=>$albumData1)
			{
				if($albumData1['type'] != 'friends_walls')
				{
					$this->final[$k]['albumId']=$albumData1['id'];
					$albumName = $albumData1['name'];
					if(strlen($albumName)>15)
						$albumName=substr($albumName,0,15)."...";
					$this->final[$k]['albumName']=$albumName;
					$this->final[$k]['photosCountInAlbum']= count($albumData1['photos']);
					$this->final[$k]['coverImageArr']=$albumData1['cover_photo']['picture'];
					foreach($albumData1['photos'] as $albumPhotos)
					{
						$this->final[$k]['allPhotos'][]=$albumPhotos['picture'];
						$this->final[$k]['allPhotosToSave'][]=$albumPhotos['source'];
					}
				}
			}
		}
		else
			$this->final[0]='';
//		$albumObj = new FacebookAlbumsData();
//		$albumObj->insertAlbumsData($photosCountData);
	}
	else
	{
		$this->askForLogin();
		//album error handling
	}
	//-------------- MORE -----------------
	$this->actionFile    = $_SERVER["PHP_SELF"];
	$this->authVariables = "access_token=".$this->accessToken;
	//-------------- MORE -----------------
}

/**
This function is used to get links to all the pics from a specific album.
 * @param  integer           $limit   no of photos to be displayed on a page
 * @param  integer           $skip    no of photos to be skipped
 * @param  integer           $albumId The album's id
**/
public function getPhotos($id,$limit='',$skip='')
{
	$albumObj = new FacebookAlbumsData();
	$photoCount = $albumObj->getAlbumData($id);

	$this->authenticateUser();

	$feedURL = "https://graph.facebook.com/".$id."/photos?access_token=".$this->accessToken;
	$photoData = CommonUtility::sendCurlGetRequest($feedURL);
//		$photoData = file_get_contents($feedURL);
	$count=0;
	$photoDataFull = json_decode($photoData);
	$errorVal = $photoDataFull->error;
	if($errorVal)
		$this->askForLogin();
	else
	{
		$photoData = $photoDataFull->data;
		$pagingLink = $photoDataFull->paging->next;
		$noOfPhotosFetched = sizeof($photoData);

		while(($noOfPhotosFetched < $skip+$limit) && ($noOfPhotosFetched < $photoCount))
		{
			$nextUrl = $pagingLink;
			$nextData = CommonUtility::sendCurlGetRequest($nextUrl);
//			$nextData = file_get_contents($nextUrl);
			$nextData = json_decode($nextData);
			$photoData = array_merge($photoData,$nextData->data);
			$pagingLink = $nextData->paging->next;
			$noOfPhotosFetched = sizeof($photoData);
			if($noOfPhotosFetched == $photoCount)
				break;
		}

		foreach($photoData as $photoData2)
		{
			if($skip>0)
				$skip--;
			else
			{
				$this->thumbnail[]=$photoData2->picture;

				$width=intval($photoData2->width);
				$height=intval($photoData2->height);

				$photo_size = $this->photo_resize($width,$height);

				$this->width[]=$photo_size[2];
				$this->height[]=$photo_size[3];

				$stylePadding="padding:$photo_size[1] $photo_size[0] $photo_size[1] $photo_size[0];border:1px #dddddd solid";

				$this->stylePadding[]=$stylePadding;
				
				$this->saveImage[]=$photoData2->source;
				$count++;
			}
			if($limit && $count==$limit)
				break;
		}
	}
}

/**
This function is called whenever a user signs out while a request is in process.
**/
private function askForLogin()
{
$permissions = array('user_photos');
$loginUrl = $this->helper->getLoginUrl('http://t.j.com/social/import1?importSite=facebook&import=1&popup=1', $permissions);;

        header("Location:{$loginUrl}");
        exit;
//echo "<script> window.open(".$this->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos')).",\"login page\",\"location=1,status=1,scrollbars=1, width=100,height=100\") </script>";
}
}
?>
