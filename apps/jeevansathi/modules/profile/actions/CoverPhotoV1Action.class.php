<?php
/*
 * API for cover photo
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nitish
 */

class CoverPhotoV1Action extends sfActions{
    
    public function execute($request){
        $loginData = $request->getAttribute("loginData");
        $profileid = $loginData["PROFILEID"];
        //Allow only for logged in users
        if($profileid){
            //If the api is called to save cover photo
            if($request->getParameter("saveCover")){
                $coverid = $request->getParameter("coverid");
                $msg = $this->saveCover($profileid, $coverid);
            }
        }
        echo $msg;
        die;
    }
    
    /*
     * Function to save cover photo
     * @input: profileid, coverid
     * @output: message depending on what is done.
     */
    public function saveCover($profileid, $coverid){
        //Enter below only if profileid and coverid is provided
        if($profileid && $coverid){
            $arrAllowedCatId = CoverPhotoMap::getFieldLabel("valid_photo_id", "", 1);
            $catId = substr($coverid, 0, 2);
            //Only if the category id of the given coverid matches the allowed photo category ids
            if(in_array($catId, $arrAllowedCatId)){
                $coveridUrl = CoverPhotoMap::getFieldLabel($catId, $coverid);
                //If the given coverid exeists in the database
                if($coveridUrl){
                    $coverPhotoServiceObj = new CoverPhotoService();
                    $res = $coverPhotoServiceObj->addCoverPhoto($profileid, $coverid);
                    if($res){
                        $msg = "Success";
                    }
                    else{
                        $msg = "Failure";
                    }
                }
            }
            else{
                $msg = "Invalid Photoid";
            }
        }
        else{
            $msg = "Profileid or coverid is blank";
        }
        return $msg;
    }
    
}
