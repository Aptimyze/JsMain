<?php

/* This class performs image manipulation for cropper */
class ImageManipulator
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var resource
     */
    protected $image;

    /**
     * Image manipulator constructor
     * 
     * @param string $file OPTIONAL Path to image file or image data as string
     * @return void
     */
    public function __construct($file = null)
    {
        ini_set('memory_limit', '1024M');
        if (null !== $file) 
        {
            $this->setImageString($file);
        }
    }

    /**
     * Set image resource from string file
     * 
     * @param string $file
     * @return image
     * @throws RuntimeException
     */
    public function setImageString($file)
    {
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
        list ($this->width, $this->height) = getimagesize($file);
        $pictureFunobj = new PictureFunctions();        
        $this->image = $pictureFunobj->createImage($file);

        unset($pictureFunobj);
        if(!$this->image)
        {
		$trace = debug_backtrace();
		$szType = getimagesize($file);
		file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/img.txt","file:".$file."formattype:".var_export($szType,true)."trace:".var_export($trace,true)."\n\n\n",FILE_APPEND);

            throw new RuntimeException('Cannot create image from data in ImageManipulator');
        }
        return $this;
    }

    /**
     * Resamples the current image
     *
     * @param int  $width                New width
     * @param int  $height               New height
     * @param bool $replaceImageFlag(replace file with current image or not),$constrainProportions Constrain current image proportions when resizing
     * @return Image
     * @throws RuntimeException
     */
    public function resample($width, $height, $replaceImageFlag = true, $constrainProportions = true)
    {
        if (!is_resource($this->image)) {
            throw new RuntimeException('No image set in ImageManipulator');
        }
        if ($constrainProportions) {
            if ($this->height >= $this->width) {
                $width  = round($height / $this->height * $this->width);
            } else {
                $height = round($width / $this->width * $this->height);
            }
        }
        $temp = imagecreatetruecolor($width, $height);
        imagecopyresampled($temp, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
        if($replaceImageFlag==true)
            return $this->_replace($temp);
        else
            return $temp;
    }
    
    
    /**
     * Crop image
     * 
     * @param int|array $x1 Top left x-coordinate of crop box or array of coordinates
     * @param int       $y1 Top left y-coordinate of crop box
     * @param int       $w width of crop box
     * @param int       $h height of crop box
     * @param bool pathProvided(whether file is path or resource)
     * @return Image
     * @throws RuntimeException
     */
    public function crop($originalFile,$x1, $y1, $w, $h,$pathProvided=true)
    {
        if($pathProvided==true)
            $this->setImageString($originalFile);
        else
            $this->_replace($originalFile);
        $x1 = max(round($x1), 0);
        $y1 = max(round($y1), 0);
        $w = min($this->width,max(round($w),0));
        $h = min($this->height,max(round($h),0));
        
        $temp = imagecreatetruecolor($w, $h);
        imagecopy($temp, $this->image, 0, 0, $x1, $y1, $w, $h);        
        return $temp;
    }
	
	/**
     * Resize passed image to new dimensions
     * 
     * @param $originalFile(to be resized),$newDimensionArr array of width and height,bool pathProvided(whether file is path or resource)
     * @return Image
     */
    public function resize($originalFile,$newDimensionArr,$pathProvided=true)
    {
        if($pathProvided==true)
            $this->setImageString($originalFile);
        else
            $this->_replace($originalFile);
        $newWidth = intval($newDimensionArr["w"]);
        $newHeight = intval($newDimensionArr["h"]);
        $temp = $this->resample($newWidth,$newHeight,false,false);
        return $temp;
    }
    
    
    
    /**
     * Replace current image resource with a new one
     * 
     * @param resource $res New image resource
     * @return Image
     * @throws UnexpectedValueException
     */
    public function _replace($res)
    {
        if (!is_resource($res)) {
            throw new UnexpectedValueException('Invalid resource in ImageManipulator');
        }
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
        $this->image = $res;
        $this->width = imagesx($res);
        $this->height = imagesy($res);
        return $this->image;
    }
    
    /**
     * Save current image to file
     * 
     * @param string $file(to be saved),$saveUrl(path to new file),$type
     * @return void
     * @throws RuntimeException
     */
    public function save($file,$saveUrl, $type = "jpeg")
    {
        try
        {
            $pictureFunobj = new PictureFunctions();
            $pictureFunobj->storeResizedImage($file,$saveUrl,$type);
            unset($pictureFunobj);
            chmod($saveUrl,0777);
        } catch (Exception $ex) {
            throw new RuntimeException('Error in ImageManipulator saving image file to ' . $saveUrl);
        }
    }

    /**
     * Returns the GD image resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->image;
    }

    /**
     * Get current image resource width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get current image height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}
