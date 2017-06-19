<?php
/**
 * Test Case to check profilePicTest function
 * @author - Akash
 * @Last Modified - 2 Jun 2015
 * @execute - phpunit --bootstrap test/phpUnit/bootstrap.php test/phpUnit/unit/photoUpload/profilePicTest
 */

/**
 * TEST CASES
 * 1. No Picture Exist 
 * 2. Already existing NonScreenedPic profilepic set as ProfilePic
 * 3. Other NonScreenedPic set as ProfilePic
 * 4. NonScreened Pic is already screened and selected as profile Pic
 * 5. NonScreened Pic is selected as profile Pic
 * 6. Screened Pic Set as profilePic
 * 7. Screened Pic Set as profilePic
 * 8. NonScreenedPic set as ProfilePic
 * 9. Screened Pic Set as profilePic
 * 10. Screened TO NonScreened Pic Set as profilePic
 */


class profilePicTest extends PHPUnit_Framework_TestCase
{
	private $objTable = null;
        private $nonScreenedAlbum = null;
        private $screenedAlbum = null;
        private $album = null;
        private $pictureServiceObj = null;
        private $iProfileID = 144111;
        private $objProfile = null;
        private function PrepareProfileData($iProfileID=null,$step=1){
		
		if(!$iProfileID)
			$iProfileID = 144111;
        
		
                $arrSqls = array("REPLACE INTO `newjs`.`PICTURE_FOR_SCREEN_NEW` VALUES (9668357, :PID, 7, NULL, NULL, 0x323031352d30322d32342031313a34323a3538, 'JS/uploads/NonScreenedImages/newMainPic/9/687/9668357iifbdbba153a5d51bfbd46fde0019d5b24iid0ca0949b6e0a222a0bb879eaf609059.jpg', NULL, NULL, 'http://mrevamp2.jeev.com/uploads/NonScreenedImages/thumbnail96/9/687/9668357iifbdbba153a5d51bfbd46fde0019d5b24iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '', '', '', '', 'JS/uploads/NonScreenedImages/mainPic/9/687/9668357iifbdbba153a5d51bfbd46fde0019d5b24iid0ca0949b6e0a222a0bb879eaf609059.jpg', '0100000', 'jpeg', 0)",
                                "REPLACE INTO `newjs`.`PICTURE_FOR_SCREEN_NEW` VALUES (9668543, :PID, 0, NULL, NULL, 0x323031352d30332d31372031363a34333a3333, 'JS/uploads/NonScreenedImages/mainPic/9/687/9668543ii8528adfe80a3d115079406da1875516fiid0ca0949b6e0a222a0bb879eaf609059.jpeg', NULL, NULL, 'JS/uploads/NonScreenedImages/thumbnail96/9/687/9668543ii8528adfe80a3d115079406da1875516fiid0ca0949b6e0a222a0bb879eaf609059.jpeg', '', '', '', '', '', '0000000', 'jpeg', 0)",
                                "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667345, :PID, 0, NULL, NULL, 0x323031352d30312d30362031333a34323a3239, '', '', '', 'JS/uploads/ScreenedImages/thumbnail96/9/686/9667345iia236f41a99fc1ed802a61919de315482iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', 'JS/uploads/ScreenedImages/searchPic/9/686/9667345iia236f41a99fc1ed802a61919de315482iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '', '', 'JS/uploads/ScreenedImages/profilePic235/9/686/9667345iia236f41a99fc1ed802a61919de315482iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '', '', '')",
                                "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667283, :PID, 1, NULL, NULL, 0x323031352d30312d30352031353a33363a3335, 'JS/uploads/ScreenedImages/newMainPic/9/685/9667283ii768f64b62dbea477358e90901f3342d6iid0ca0949b6e0a222a0bb879eaf609059.jpeg', NULL, '', 'JS/uploads/ScreenedImages/thumbnail96/9/685/9667283ii768f64b62dbea477358e90901f3342d6iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', '', '', '', '', '', 'JS/uploads/ScreenedImages/mainPic/9/685/9667283ii768f64b62dbea477358e90901f3342d6iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '')",
                                "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667282, :PID, 2, NULL, NULL, 0x323031352d30312d30352031353a33363a3335, 'JS/uploads/ScreenedImages/newMainPic/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/profilePic/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/thumbnail/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/thumbnail96/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', 'JS/uploads/ScreenedImages/searchPic/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/mobileAppPic/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/profilePic120/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/profilePic235/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/profilePic450/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'JS/uploads/ScreenedImages/mainPic/9/685/9667282ii9c6c00117629a88c73d00d6164a08853iid0ca0949b6e0a222a0bb879eaf609059.jpeg', '')",
                                "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667346, :PID, 3, NULL, NULL, 0x323031352d30312d30362031333a34323a3239, '', NULL, '', 'JS/uploads/ScreenedImages/thumbnail96/9/686/9667346ii1b49b938f96748a8cf535953b925d8dbiid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', '', '', '', '', '', '', '')",
                                "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667347, :PID, 4, NULL, NULL, 0x323031352d30312d30362031333a34323a3239, '', NULL, '', 'JS/uploads/ScreenedImages/thumbnail96/9/686/9667347ii4f93e55ab06bb6214408ed7ceb72f41diid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', '', '', '', '', '', '', '')",
                                "REPLACE INTO `newjs`.`PICTURE_NEW` VALUES (9667348, :PID, 5, NULL, NULL, 0x323031352d30312d30362031333a34323a3239, '', NULL, '', 'JS/uploads/ScreenedImages/thumbnail96/9/686/9667348ii3e896eed5981e549b631c22d8e3b69ceiid0ca0949b6e0a222a0bb879eaf609059.jpeg', 'jpeg', '', '', '', '', '', '', '')",
                                    "UPDATE `newjs`.`JPROFILE` SET HAVEPHOTO='Y',PHOTOSCREEN='0',PHOTODATE='2015-04-01 18:52:12' WHERE PROFILEID='".$profileid."'"
                                );
		
		$objTable = new StoreTable;
		foreach($arrSqls as $sql)
		{
			$prepStatement = $objTable->getDBObject()->prepare($sql);
			if(strpos($sql,':PID')!==false)
			{
				$prepStatement->bindValue(':PID',$iProfileID,PDO::PARAM_INT);
				$prepStatement->execute();
			}
		}
                //Create Profile Object
		$this->objProfile = LoggedInProfile::getInstance('newjs_master',$iProfileID);
		$this->objProfile->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER");
		$this->pictureServiceObj = new PictureService($objProfile);
                //$album = $this->pictureServiceObj->getAlbum();
		$this->nonScreenedAlbum = $this->pictureServiceObj->getNonScreenedPhotos('album');
		$this->screenedAlbum = $this->pictureServiceObj->getScreenedPhotos('album');
		$this->album = $this->pictureServiceObj->getAlbum();
                
		
	}
	public function testProfilePic(){
		$noProfilePic="";
                $this->assertEquals('1',$this->pictureServiceObj->setProfilePic($noProfilePic));
	}
        public function testProfilePic1(){
                try{
                        $this->PrepareProfileData($this->iProfileID);
		}catch(Exception $e)
		{
			$this->markTestSkipped('Issue in preparing data 1');
		}
                
                $this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->nonScreenedAlbum[0]));
		
        }
        public function testProfilePic2(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->screenedAlbum[0]));
		
        }
        public function testProfilePic3(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->nonScreenedAlbum[0]));	
		
        }
        public function testProfilePic4(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->nonScreenedAlbum[1]));
		
        }
        public function testProfilePic5(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->screenedAlbum[1]));
        }
        public function testProfilePic6(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->nonScreenedAlbum[0]));
        }
        public function testProfilePic7(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->screenedAlbum[0]));
        }
        public function testProfilePic8(){
		$this->assertEquals('1',$this->pictureServiceObj->setProfilePic($this->nonScreenedAlbum[0]));
        }

}
?>	
