<?php
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
                            'default_graph_version' => 'v2.9'
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
	$k=0;
	if($this->accessToken)
	{
		if(array_key_exists("albums",$albumData))
		{
			foreach($albumData[albums] as $k1=>$albumData1)
			{
				if($albumData1['type'] != 'friends_walls' && array_key_exists('photos',$albumData1))
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
					$k++;
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
This function is called whenever a user signs out while a request is in process.
**/
private function askForLogin()
{
$permissions = array('user_photos');
$loginUrl = $this->helper->getLoginUrl(JsConstants::$siteUrl.'/social/import1?importSite=facebook&import=1&popup=1', $permissions);;

        header("Location:{$loginUrl}");
        exit;
//echo "<script> window.open(".$this->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos')).",\"login page\",\"location=1,status=1,scrollbars=1, width=100,height=100\") </script>";
}
public function getPhotos($id,$limit='',$skip='')
{
}
}
?>
