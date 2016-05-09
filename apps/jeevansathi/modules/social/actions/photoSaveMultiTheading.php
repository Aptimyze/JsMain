<?php
        ini_set("max_execution_time","0");
        chdir(dirname(__FILE__));
        include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
        require_once(sfConfig::get('sf_app_dir')."/modules/social/lib/importUploadTracking.class.php");
        $id=$_SERVER['argv'][1];
        $pid=$_SERVER['argv'][2];
        $importSite=$_SERVER['argv'][3];
        $PHOTO_URL=new PHOTO_URL;
        $profileObj=LoggedInProfile::getInstance('newjs_master',$pid);
        $pictureServiceObj=new PictureService($profileObj);
        $picLink=$PHOTO_URL->mapURL($pid,$id);
        $size=$pictureServiceObj->saveAlbum($picLink[0],"import",$pid,$importSite);
        if(is_array($size) && $size[1]>0)
                $PHOTO_URL->deleteId($id);
        die;
?>
