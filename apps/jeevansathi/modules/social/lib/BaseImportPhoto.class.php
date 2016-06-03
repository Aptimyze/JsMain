<?php
/**
* This Class is parent class of all photo import services. getAlbumList() and getPhotos() methods must be implemented by child class
*/
abstract class BaseImportPhoto
{
	protected $thumbnail = array();
        protected $saveImage = array();
	protected $actionFile;
        protected $albumIdArr;
        protected $albumNameArr;
        protected $coverImageArr;
        protected $photosCountInAlbum;
        protected $authVariables;
	protected $photoWidth=100;
	protected $photoHeight=100;

	abstract protected function getAlbumList();
	abstract protected function getPhotos($id,$limit='',$skip='');

	/**
	  This function is used to get urls of photos of a user to be displyed on a specified page no.
	  @param $id: id of the album whose photos are to be fetched
	  @param $page: page no for which photo urls are to be fetched
	  @param $albumLength: no of photos to be fetched from the album
	**/
	public function getMultiAlbumPhotos($id,$page='',$albumLength='')
	{
		if(!$albumLength)
			$albumLength=20;
		if(!$page)
			$page=1;
		if($page>0)
			$page=$page-1;
		else
			$page=0;

		if(strstr($id,'|'))
			$tempArr1=explode("|",$id);
		else
			$tempArr1[]=$id;

		foreach($tempArr1 as $k=>$v)
		{
			$tempArr2=explode("#",$v);
			$countArr[] = $tempArr2[0];
			$idArr[]    = $tempArr2[1];
		}

		$pageFactor=$page*$albumLength;

		$firstTimeTrue=0;
		foreach((array)$idArr as $k=>$v)
		{
			if($firstTimeTrue)
				$pageFactor=0;
			/*
			echo "<br>";echo "<br><br>";echo "--->>>";echo $countArr[$k].":";echo $pageFactor;echo "<<<---";
			*/
			if($countArr[$k]>$pageFactor)
			{	
				if($albumLength>$countArr[$k])
				{
					$firstTimeTrue++;
					//echo "((".$v."--".$pageFactor."))";
					$this->getPhotos($v,'',$pageFactor);
					$albumLength=$albumLength-$countArr[$k]+$pageFactor;
				}
				else
				{
					$firstTimeTrue++;
					//echo "{{".$v."--".$albumLength."---".$pageFactor."}}";
					$this->getPhotos($v,$albumLength,$pageFactor);
					$albumLength=$albumLength-$countArr[$k]+$pageFactor;
				}
			}
			if($pageFactor>0)
				$pageFactor=abs($pageFactor-$countArr[$k]);
			
			if($albumLength<=0)
				break;
		}
	}


        /* This function returns an array of thumbnail size images */
        public function getThumbnail()
        {
                return $this->thumbnail;
        }

        /* This function returns an array of large size images which will be saved*/
        public function getSaveImage()
        {
                return $this->saveImage;
        }

        /* This function returns an array of album ids */
        public function getAlbumIdArr()
        {
                return $this->albumIdArr;
        }

        /* This function returns an array of album names */
        public function getAlbumNameArr()
        {
                return $this->albumNameArr;
        }

        /* This function returns an array of cover image of each album */
        public function getCoverImageArr()
        {
                return $this->coverImageArr;
        }

        /* This function returns an array of no of photos in each album */
        public function getPhotosCountInAlbum()
        {
                return $this->photosCountInAlbum;
        }

        /* This function returns the file to which the next call has to be made */
        public function getActionFile()
        {
                return $this->actionFile;
        }

        public function getAuthVariables()
        {
                return $this->authVariables;
        }
        public function getstylePadding()
        {
                return $this->stylePadding;
        }
        public function getImageHeight()
        {
                return $this->height;
        }
        public function getImageWidth()
        {
                return $this->width;
        }
        public function photo_resize($width,$height)
        {
		$picFunc=new PictureFunctions();
		$photo_size = $picFunc->photo_resize($width,$height,$this->photoWidth,$this->photoHeight);
		return $photo_size;
        }
	public function getUserIdentity()
	{
		return $this->userIdentity;
	}

}
?>
