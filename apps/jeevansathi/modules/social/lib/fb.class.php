<?php
include_once(sfConfig::get('sf_lib_dir') . '/vendor/facebook-php-sdk-master/src/facebook.php');

class fb extends BaseImportPhoto
{
	private $fbsession;
	private $facebook;
	
	public function __construct()
	{
		$this->facebook=new Facebook(array(
                            'appId' => JsConstants::$fbId,
                            'secret' => JsConstants::$fbSecret,
                            'cookie' => true
                        ));

	}

	/**
	This function is used to authenticate a logged-in facebook user.
	Its sets access_token(with some more info) which maintains user session.
	**/
        private function authenticateUser()
        {
                if(is_null($this->facebook->getUser()) || $this->facebook->getUser()==0)
                        $this->askForLogin();
		$this->accessToken = $this->facebook->getAccessToken();
        }

	
/************************************************/
	public function getAllAlbumPhotos($id)
	{
                $albumObj = new FacebookAlbumsData();
                $photoCount = $albumObj->getAlbumData($id);
                $this->authenticateUser();
                $feedURL = "https://graph.facebook.com/".$id."/photos?access_token=".$this->accessToken;
		$feedURL.="&limit=100";
                $photoData = CommonUtility::sendCurlGetRequest($feedURL);
                $photoDataFull = json_decode($photoData);
		$photoData = $photoDataFull->data;
		$nextUrl = $photoDataFull->paging->next;
		if($nextUrl)
		{
                        $nextData = CommonUtility::sendCurlGetRequest($nextUrl);
                        $nextData = json_decode($nextData);
                        $photoData = array_merge($photoData,$nextData->data);
		}
		$k=0;
		foreach($photoData as $photoData2)
		{
			$arr[$k]["display"] = $photoData2->picture;
			$arr[$k]["save"] = $photoData2->source;
			$k++;
		}
		return $arr;
	}
/************************************************/

/**
This function is used to get a list of details of albums created by the user.
list of album URLs, album names and cover image URLs.
**/
public function getAlbumList()
{
	$this->authenticateUser();
	$feedURL = "https://graph.facebook.com/".$this->facebook->getUser()."/albums?access_token=".$this->accessToken;
	$this->userIdentity = $this->facebook->getUser();
	$albumData = CommonUtility::sendCurlGetRequest($feedURL);

	$albumD = json_decode($albumData);

	if(!$albumD->error)
	{
		$jsonIterator = new RecursiveIteratorIterator(
		new RecursiveArrayIterator(json_decode($albumData, TRUE)),
		RecursiveIteratorIterator::SELF_FIRST);
		foreach($jsonIterator as $albumData1)
		{
			$nextUrl = $albumD->paging->next;

			while($nextUrl != '')
			{
				$nextData = CommonUtility::sendCurlGetRequest($nextUrl);

				$jsonIteratorNext = new RecursiveIteratorIterator(
				new RecursiveArrayIterator(json_decode($nextData, TRUE)),
				RecursiveIteratorIterator::SELF_FIRST);
				foreach($jsonIteratorNext as $albumDataNext)
				{
					$albumData1 = array_merge($albumData1,$albumDataNext);
					$nextUrl = $nextData->paging->next;
					if($nextUrl == '')
						break;
				}
			}
			foreach($albumData1 as $albumData2)
			{
				if($albumData2['count'] != 0 && $albumData2['type'] != 'app' && $albumData2['type'] != 'friends_walls')
				{
					$this->albumIdArr[]=$albumData2['id'];
					$albumName = $albumData2['name'];
					if(strlen($albumName)>15)
						$albumName=substr($albumName,0,15)."...";
					$this->albumNameArr[]=$albumName;
					//?type= thumbnail small album
					$this->photosCountInAlbum[]=$albumData2['count'];
					$photosCountData[$albumData2['id']] = $albumData2['count'];
					$this->coverImageArr[]="https://graph.facebook.com/".$albumData2['id']."/picture?type=album&access_token=".$this->accessToken;
				}
			}
			break;
		}
		$albumObj = new FacebookAlbumsData();
		$albumObj->insertAlbumsData($photosCountData);
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
//echo "<script> window.open(".$this->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos')).",\"login page\",\"location=1,status=1,scrollbars=1, width=100,height=100\") </script>";
	header("Location:{$this->facebook->getLoginUrl(array('scope' => 'user_photos'))}");
	exit;
}
}
?>
