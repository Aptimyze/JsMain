<?php
include_once(sfConfig::get('sf_lib_dir') . '/vendor/Zend/Loader.php');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_Photos');
Zend_Loader::loadClass('Zend_Gdata_Photos_UserQuery');
Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumQuery');
Zend_Loader::loadClass('Zend_Gdata_Photos_PhotoQuery');
Zend_Loader::loadClass('Zend_Gdata_App_Extension_Category');

class picasa extends BaseImportPhoto
{
        private $client;

	public function __construct()
	{
		$this->processPageLoad();
	}

	/**
	 * Returns the path to the current script
	 * @return string Current script path
	 */
	private function getCurrentScript()
	{
	    //global $_SERVER;
	    return $_SERVER["PHP_SELF"];
	}

	/**
	 * Returns the full URL of the current page
	 * @return string Current URL
	 */
	private function getCurrentUrl()
	{
		global $_SERVER;

		/* Filter php_self to avoid a security vulnerability. */
		$php_request_uri = htmlentities(substr($_SERVER['REQUEST_URI'], 0,strcspn($_SERVER['REQUEST_URI'], "\n\r")), ENT_QUOTES);
		if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')
		{
			$protocol = 'https://';
		} 
		else 
		{
			$protocol = 'http://';
		}
		$host = $_SERVER['HTTP_HOST'];
		if ($_SERVER['SERVER_PORT'] != '' &&(($protocol == 'http://' && $_SERVER['SERVER_PORT'] != '80') ||($protocol == 'https://' && $_SERVER['SERVER_PORT'] != '443'))) 
		{
			$port = ':' . $_SERVER['SERVER_PORT'];
		} 
		else 
		{
			$port = '';
		}
		return $protocol . $host . $port . $php_request_uri;
	}

	/**
	 * Returns the AuthSub URL which the user must visit to authenticate requests
	 * from this application.
	 * Uses getCurrentUrl() to get the next URL which the user will be redirected
	 * to after successfully authenticating with the Google service.
	 * @return string AuthSub URL
	 */
	private function getAuthSubUrl()
	{
	    $next = $this->getCurrentUrl();
	    $scope = 'http://picasaweb.google.com/data';
	    $secure = false;
	    $session = true;
	    return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure,$session);
	}

	/**
	 * Outputs a request to the user to login to their Google account, including
	 * a link to the AuthSub URL.
	 * Uses getAuthSubUrl() to get the URL which the user must visit to authenticate
	 * @return void
	 */
	private function requestUserLogin($linkText)
	{
	    $authSubUrl = $this->getAuthSubUrl();
	    //echo "<a href=\"{$authSubUrl}\">{$linkText}</a>";
		header("Location: ".$authSubUrl);
		die;
	}

	/**
	 * Returns a HTTP client object with the appropriate headers for communicating
	 * with Google using AuthSub authentication.
	 * Uses the $_SESSION['sessionToken'] to store the AuthSub session token after
	 * it is obtained.  The single use token supplied in the URL when redirected
	 * after the user succesfully authenticated to Google is retrieved from the
	 * $_GET['token'] variable.
	 * @return Zend_Http_Client
	 */
	private function getAuthSubHttpClient()
	{
		global $_SESSION, $_GET;
		if (!isset($_SESSION['sessionToken']) && isset($_GET['token'])) 
		{
			$_SESSION['sessionToken'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
		}
		$this->client = Zend_Gdata_AuthSub::getHttpClient($_SESSION['sessionToken']);
	}

	/**
	 * Processes loading of this sample code through a web browser.  Uses AuthSub
	 * authentication and outputs a list of a user's albums if succesfully
	 * authenticated.
	 * @return void
	 */
	private function processPageLoad()
	{
		global $_SESSION, $_GET;

		if (!isset($_SESSION['sessionToken']) && !isset($_GET['token'])) 
		{
			$this->requestUserLogin('Please login to your Google Account.');
		} 
		else 
		{
			$this->getAuthSubHttpClient();
		}
	}

	/**
	 * Outputs an HTML list, with each list item representing an
	 * album in the user's feed.
	 * @return void
	 */
	public function getAlbumList()
	{
		$user="default";
		$photos = new Zend_Gdata_Photos($this->client);

		$query = new Zend_Gdata_Photos_UserQuery();
		$query->setUser($user);

		$userFeed = $photos->getUserFeed(null, $query);
		$this->userIdentity = $userFeed->getTitle()->text;
		foreach ($userFeed as $entry) 
		{
			if ($entry instanceof Zend_Gdata_Photos_AlbumEntry) 
			{
				if($entry->gphotoNumPhotos->text)
				{
					$thumb = $entry->getMediaGroup()->getThumbnail();
					$this->albumIdArr[]    = $entry->getGphotoId();
					$albumName = $entry->getTitle();
	                                if(strlen($albumName)>15)
	                                        $albumName=substr($albumName,0,15)."...";
					
	                                $this->albumNameArr[]=$albumName;
					$this->coverImageArr[] = $thumb[0]->getUrl();
					$this->photosCountInAlbum[]=$entry->gphotoNumPhotos->text;
				}
			}
	                //-------------- MORE -----------------
			global $_SERVER;
			$actionFile=$_SERVER["REQUEST_URI"];
			$this->actionFile    = $actionFile;
	                $this->authVariables = "command=retrieveAlbumFeed&user=".$userFeed->getTitle();
        	        //-------------- MORE -----------------

		}

	}

	 /**
	 * Outputs an HTML list, with each list item representing a
	 * photo in the user's album feed.
	 * @param  integer           $limit   no of photos to be displayed on a page
	 * @param  integer           $skip    no of photos to be skipped
	 * @param  integer 	     $albumId The album's id
	 * @return void
	 */
	public function getPhotos($albumId,$limit='',$skip='')
	{
		$user="default";

		$photos = new Zend_Gdata_Photos($this->client);

		$query = new Zend_Gdata_Photos_AlbumQuery();
		$query->setUser($user);
		$query->setAlbumId($albumId);

		$albumFeed = $photos->getAlbumFeed($query);
		$count=0;
		foreach ($albumFeed as $entry) 
		{
			if ($entry instanceof Zend_Gdata_Photos_PhotoEntry) 
			{
                                if($skip>0)
                                        $skip--;
                                else
				{
					$thumb = $entry->getMediaGroup()->getThumbnail();
					$savee = $entry->getMediaGroup()->getContent();
					$this->thumbnail[]=$thumb[1]->getUrl();
					$this->saveImage[]=$savee[0]->getUrl();

                                        $width=intval($thumb[1]->getWidth());
                                        $height=intval($thumb[1]->getHeight());

					$photo_size = $this->photo_resize($width,$height);
                                        
                                        $this->width[]=$photo_size[2];
                                        $this->height[]=$photo_size[3];

                                        $stylePadding="padding:$photo_size[1] $photo_size[0] $photo_size[1] $photo_size[0];border:1px #dddddd solid";

                                        $this->stylePadding[]=$stylePadding;
					$count++;
				}
                                if($limit && $count==$limit)
                                        break;
			}
		}
	}
}
?>
