<?php
class CropperProcess
{
 /*
 * @package jeevansathi
 * @subpackage deletePhoto
 * @author Esha Jain
 * @created 01st March 2016
 */
 /**
 * Class For deleting photo 
 */

        public function __construct($profileObj)
        {
                $this->profileObj = $profileObj;
        }
	public function process($cropImageSource,$cropBoxDimensionsArr,$imgPreviewTypeArr)
	{
                $imageType = PictureFunctions::getImageFormatType($cropImageSource);

                //get pictureid for profile pic uploaded
                $pictureServiceObj=new PictureService($this->profileObj);
                $ProfilePicUrlObj = $pictureServiceObj->getProfilePic();
                $picId = $ProfilePicUrlObj->getPICTUREID();
                unset($pictureObj);

                //crop image to cropbox size and square cut it
                $imgArr = $this->cropPlusSquareCutImage($cropImageSource,$cropBoxDimensionsArr,$imageType);
                //process cropped pic for all new dimensions
                foreach($imgPreviewTypeArr as $key=>$imgType)
                {
                        if($imgType=="imgPreviewMD" || $imgType=="imgPreviewXS") //for rectangular images, use cropped img as source
                                $sourceImage = $imgArr["croppedImg"];
                        else                                                         //for square imgages,use square cut img as source
                                $sourceImage = $imgArr["squareCroppedImg"];

                        //get mapping of picLabel to existing picture size field
                                $picMappingField = ProfilePicturesTypeEnum::$CROPPED_NONSCREENED_PICTURE_FIELD_MAPPING[$imgType];

                                //resize sourceImage and store it in disk and get dbSaveUrl
                                if(is_array($picMappingField))
                                {
                                        foreach($picMappingField as $k=>$v)
                                        {
                                        $profilesUpdate[$picId][$v] = $this->resizePlusStoreCroppedImage($sourceImage,$picId,$profileid,$v,ProfilePicturesTypeEnum::$CROPPED_NONSCREENED_PICTURE_SIZES[$v],$imageType,'nonScreened');
                                        }
                                }
                                else if($picMappingField)
                                {
                                $profilesUpdate[$picId][$picMappingField] = $this->resizePlusStoreCroppedImage($sourceImage,$picId,$profileid,$picMappingField,ProfilePicturesTypeEnum::$CROPPED_NONSCREENED_PICTURE_SIZES[$picMappingField],$imageType,'nonScreened');
                                }
                    }
		return $profilesUpdate;
	}
        /*
        *This function crop source image and cut it into square
        * @param : $cropImageSource,$cropBoxDimensionsArr,$imageType
        * @return : array of croppedImage and squareSizedCroppedImage
        */
        private function cropPlusSquareCutImage($cropImageSource,$cropBoxDimensionsArr,$imageType)
        {
                $manipulator = new ImageManipulator();
        $croppedImage = $manipulator->crop($cropImageSource,$cropBoxDimensionsArr["x"], $cropBoxDimensionsArr["y"], $cropBoxDimensionsArr["w"], $cropBoxDimensionsArr["h"],true);

        //cut cropped image into square size
        if($cropBoxDimensionsArr["w"] != $cropBoxDimensionsArr["h"])
                $squareEdgeLength = min($cropBoxDimensionsArr["w"],$cropBoxDimensionsArr["h"]);
        $squareSizedCroppedImage = $manipulator->crop($croppedImage,0,0,$squareEdgeLength,$squareEdgeLength,false);

        unset($manipulator);
        $imgArr = array("croppedImg"=>$croppedImage,"squareCroppedImg"=>$squareSizedCroppedImage);
        return $imgArr;
        }
        /*
        *This function resizes image and store in disk and add entry in DB
        * @param : $sourceImagePath(original pic to be resized),$picId(main pic id),$profileid,$picMappingField,$newDimensions,$imageType(jpeg/gif/png),$pictureType(nonscreened/screened)
        * @return : $dbSaveUrl
        */
        private function resizePlusStoreCroppedImage($sourceImage,$picId,$profileid,$picMappingField,$newDimensions,$imageType,$pictureType)
        {
                $pictureObj = new NonScreenedPicture();
                $manipulator = new ImageManipulator();

                //get save url for resized pic
                $picSaveUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$picMappingField],$picId,$profileid,$imageType,$pictureType);

                //resize pic to new dimensions
                $newImage = $manipulator->resize($sourceImage,$newDimensions,false);

                //save newImage in disk location($picSaveUrl)
                $manipulator->save($newImage,$picSaveUrl,$imageType);

                //get display url to update entry in DB
                $dbSaveUrl= $pictureObj->getDisplayPicUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$picMappingField],$picId,$profileid,$imageType,$pictureType);
                unset($manipulator);
                unset($pictureObj);
                return $dbSaveUrl;
        }
}
