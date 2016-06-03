<?php
//include_once(sfConfig::get('sf_lib_dir') . '/vendor/phpflickr/auth.php');
//include_once(sfConfig::get('sf_lib_dir') . '/vendor/phpflickr/getToken.php');
include_once(sfConfig::get('sf_lib_dir') . '/vendor/phpflickr/phpFlickr.php');
class fl extends BaseImportPhoto
{
        private $flickr;

        public function __construct()
        {
                $this->flickr=new phpFlickr(JsConstants::$flickrKey,JsConstants::$flickrSecret);
		$this->authenticate(JsConstants::$flickrKey,JsConstants::$flickrSecret);
        }

	private function authenticate($api_key,$api_secret)
	{
		$default_redirect        = "/";
		$permissions             = "read";
		$path_to_phpFlickr_class = "./";

		ob_start();
		unset($_SESSION['phpFlickr_auth_token']);

		if ( isset($_SESSION['phpFlickr_auth_redirect']) && !empty($_SESSION['phpFlickr_auth_redirect']) ) 
		{
			$redirect = $_SESSION['phpFlickr_auth_redirect'];
			unset($_SESSION['phpFlickr_auth_redirect']);
		}

		$f = new phpFlickr($api_key, $api_secret);
		if (empty($_GET['frob'])) 
		{
			$f->auth($permissions, false);
		} 
		else 
		{
			$f->auth_getToken($_GET['frob']);
		}

		$responseForUsername = unserialize($f->response);
		$this->userIdentity = $responseForUsername['auth']['user']['nsid'];
		if (empty($redirect)) 
		{
			@header("Location: " . $default_redirect);
		} 
		else 
		{
			header("Location: " . $redirect);
		}
	}

/*	This function is used to call the function to get a list of pics for the given setIDs
	@param $id: the set id for which the pics are to be fetched
	@param $limit: no of photos to be fetched from that album
        @param $skip: no of photos to be skipped fro the album
	@return: the array of pics belonging to the set id.

*/
	public function getPhotos($id,$limit='',$skip='')
	{
		if($id=='notInSet')
        	{	
        	        $pic = $this->getPicsNotInSet($limit,$skip);
	        }
		else
		{
			$pic = $this->getPicsFromSet($id,$limit,$skip);
		}
		return $pic;
	}

	/**
	This function is used to set a list of the set ids and cover pics of all the sets created by the user.
	**/
	public function getAlbumList($actionfile='')
	{
		$setList = $this->flickr->photosets_getList();
		foreach($setList as $k=>$a)
		{
			foreach((array)$a as $g)
			{
				if($g['id'])
				{
					$this->setId[$g['id']]=$g['id'];
					$this->coverPicArr[$g['id']]=$g;
					$this->albumIdArr[]=$g['id'];
				}
			}
		}
		$this->getCoverPics();

	}

	/**
	This  function is used to set a list of thumbnail sized and large sized pics belonging to the given setIDs
	@param $setId - setId from which pics are to be fetched.
	@param $limit: no of photos to be fetched from that album
        @param $skip: no of photos to be skipped fro the album
	@param $actionfile - name of the php file form which this function was called.
	**/

	private function getPicsFromSet($setId,$limit='',$skip='')
	{
		$photos_url = $this->flickr->urls_getUserPhotos();
		foreach((array)$setId as $val)
		{
		        $pic[]=$this->flickr->photosets_getPhotos($val);
		}
		$count=0;
		foreach((array)$pic as $pic)
        	{
                	foreach((array)$pic['photoset']['photo'] as $rt)
                	{
				if($skip>0)
                                        $skip--;
                                else
				{
	                	        $link[]= "<img border='0' alt='title' "."src=" . $this->flickr->buildPhotoURL($rt, "Thumbnail"). ">";
					$this->thumbnail[]=$this->flickr->buildPhotoURL($rt, "Thumbnail");
					$this->saveImage[]=$this->flickr->buildPhotoURL($rt, "Large");

					$size=$this->flickr->photos_getSizes($rt['id']);
					$width=intval($size[1]['width']);
					$height=intval($size[1]['height']);

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
	
	/**
	This function is used to set the details of the first pic of each set that a user has.
	@param $actionfile - name of the php file from which this function was called.
	**/
	private function getCoverPics()
	{
		$laveshrawat='';
		$photos_url = $this->flickr->urls_getUserPhotos();
		foreach((array)$this->setId as $val)
		{
			$pic[$laveshrawat++]['photoset'][]=$this->coverPicArr[$val];
		}
		foreach($pic as $picloop)
        	{
                	foreach((array)$picloop['photoset'] as $rt)
                	{
				if($rt["primary"] && $rt['photos']>0)
				{
					$rt['id']=$rt["primary"];

//					$this->albumIdArr[]    = $rt['id'];
					$albumName=$rt['title'];
					if(strlen($albumName)>15)
                                        	$albumName=substr($albumName,0,15)."...";
                                	$this->albumNameArr[]=$albumName;
					$this->photosCountInAlbum[]  = $rt['photos']; 
					$this->coverImageArr[] = $this->flickr->buildPhotoURL($rt, "Small");
				}
                	        //break;
                	}
        	}

		//-------------- MORE -----------------
		$actionFile=$_SERVER["PHP_SELF"];
		$this->actionFile    = $actionFile;
		$this->authVariables = "";
		//-------------- MORE -----------------

		$pics2 = $this->flickr->photos_getNotInSet();
		$last=sizeof($this->albumIdArr);
		if($pics2['photos']['total'])
		{
			$this->albumIdArr[]='notInSet';
			$this->albumNameArr[]  ='My Pictures';
			$this->photosCountInAlbum[]  =$pics2['photos']['total'];
			$this->coverImageArr[] = $this->flickr->buildPhotoURL($pics2['photos']['photo'][0], "Small");
		}
	}

	/* 
	This function sets a list of thumbnail sized and large sized photos which do not belong to any set
	@param $limit: no of photos to be fetched from that album
        @param $skip: no of photos to be skipped fro the album
	*/

	private function getPicsNotInSet($limit='',$skip='')
	{
		$photos_url = $this->flickr->urls_getUserPhotos();
		$pics2=$this->flickr->photos_getNotInSet();
		$count=0;
		foreach($pics2['photos']['photo'] as $v)
		{
			if($skip>0)
				$skip--;
			else
			{
				$links[]="<img border='0' alt='title' "."src=" . $this->flickr->buildPhotoURL($v, "Small"). ">";
				$this->thumbnail[]=$this->flickr->buildPhotoURL($v, "Small");
				$this->saveImage[]=$this->flickr->buildPhotoURL($v, "Large");

				$size=$this->flickr->photos_getSizes($v['id']);
				$width=intval($size[1]['width']);
				$height=intval($size[1]['height']);

				$photo_size = $this->photo_resize($width,$height);
                                        
                                $this->width[]=$photo_size[2];
                                $this->height[]=$photo_size[3];

                                $stylePadding="padding:$photo_size[1] $photo_size[0] $photo_size[1] $photo_size[0];border:1px #dddddd solid";
				$this->stylePadding[]=$stylePadding;
				$count++;

				if($limit && $count==$limit)
					break;
			}
		}
	}
}
