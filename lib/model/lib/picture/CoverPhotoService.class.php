<?php

/**
 * Description of CoverPhotoService
 *
 * @author nitish
 */
class CoverPhotoService {
    
    /*Function to get Cover Photo Url
     * @input: profileid
     * @output: url of the cover photo for the profileid, if the cover photo is set, else a default cover photo url.
     */
    
    public function getCoverPhotoURL($profileid){
        $coverPhotoObj = new COVER_PHOTO();
        $photoId = $coverPhotoObj->selectCoverPhoto($profileid);
        if($photoId){
            $label = substr($photoId, 0, 2);
            $url = CoverPhotoMap::getFieldLabel($label, $photoId);
        }
        else{
            $url = PictureStaticVariablesEnum::$defaultCoverPhotoUrl;
        }
        return $url;
    }
    
    /*Function to call store of cover photo and add the the cover photo in db
     * @input: profileid, coverphotoid
     * @output: success or failure depending on whether the cover photo is saved or not.
     */
    public function addCoverPhoto($profileid, $photoid){
        $coverPhotoObj = new COVER_PHOTO();
        $res = $coverPhotoObj->insertCoverPhoto($profileid, $photoid);
        if($res)
            return true;
        return false;
    }
}
