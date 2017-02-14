<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class viewProfileOptimization{
    private static $_instance;
    private static $sender;
    private static $receiver;

    private function __construct()
    {
	$this->setStatus();
    }
    
    public static function getInstance($sender='',$receiver)
    {
        if ((!isset(self::$_instance) || !self::$_instance) && $receiver) {
	    self::$sender = $sender;
	    self::$receiver = $receiver;
            self::$_instance = new viewProfileOptimization();
        }
        
        if (isset(self::$_instance)) {
            return self::$_instance;
        } else {
            jsException::log("Object cannot be instantiated.");
        }
        
    }
    private function setStatus(){
        if(self::$sender){
            $ignore=new IgnoredProfiles("newjs_master");
            //he ignored
            $otherIgnored = $ignore->ifIgnored(self::$receiver,self::$sender,"byMe");
            if($ignore->ifIgnored(self::$sender,self::$receiver,"byMe")){
                    $ignore=1; //I Ignored
            }
            elseif($otherIgnored)
            {
                    $ignore=2; // He Ignored
            }
            else{
                    $ignore=0;
            }
            $this->statusArr["Ignore"] = $ignore;
            $this->statusArr["IgnoreFilter"] = $otherIgnored;
            $bookmark= new NEWJS_BOOKMARKS("newjs_masterRep");
            $this->statusArr["isBookmarked"] = $bookmark->isBookmarked(self::$sender,self::$receiver);
        }
            $fsoObj = ProfileFSO::getInstance("newjs_masterRep");
            $this->statusArr["fsoStatus"] = $fsoObj->check(self::$receiver);
            $hobbyObj=new JHOBBYCacheLib("newjs_masterRep");
            $this->statusArr["hobbies"] = $hobbyObj->getUserHobbies(self::$receiver);
    }
    
    public function getIgnoreProfileStatus(){
	return $this->statusArr["Ignore"];
    }
    public function getIgnoreFilter(){
        return $this->statusArr["IgnoreFilter"];
    }
    public function getBookmarkStatus(){
        return $this->statusArr["isBookmarked"];
    }
    public function getFsoStatus(){
        return $this->statusArr["fsoStatus"];
    }
    public function getHobbiesForUser(){
        return $this->statusArr["hobbies"];
    }
    
    public static function destroy(){
      if(isset(self::$_instance))
        self::$_instance = null;
    }
}
