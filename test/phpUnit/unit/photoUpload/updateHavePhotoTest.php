<?php
/**
 * Test Case to check updateHavePhotoTest function
 * @author - Akash
 * @Last Modified - 1 Jun 2015
 * @execute - phpunit --bootstrap test/phpUnit/bootstrap.php test/phpUnit/unit/photoUpload/updateHavePhotoTest
 */

/**
 * TEST CASES
 * 1. Photo added- have Photo Updated to U
 * 2. Photo added- have Photo remains U and PhotoDate and screen Updated
 * 3. Photo deleted- Have Photo Updated as underscreening - U
 * 4. Photo deleted- Have Photo Updated as No Photo - N
 * 5. Photo Screened- PhotoScreen Updated to 1
 * 6. Photo to be Screened- PhotoScreen Updated to 0
 */


class updateHavePhotoTest extends PHPUnit_Framework_TestCase
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
        
		
			$arrSqls = array("DELETE FROM `newjs`.`PICTURE_FOR_SCREEN_NEW` WHERE PROFILEID=:PID",
        "DELETE FROM `newjs`.`PICTURE_NEW` WHERE PROFILEID=:PID",
        "UPDATE `newjs`.`JPROFILE` SET HAVEPHOTO='',PHOTOSCREEN='1',PHOTODATE='2015-04-01 18:52:12' WHERE PROFILEID='".$profileid."'"
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
	public function testUpdateHavePhoto(){
		try{
                        $this->PrepareProfileData($this->iProfileID);
		}catch(Exception $e)
		{
			$this->markTestSkipped('Issue in preparing data 1');
		}
                $this->pictureServiceObj->updateHavePhoto('add');
                $this->assertEquals('Y',$this->objProfile[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"]);
	}
        public function testUpdateHavePhoto1(){
                print_r($this->pictureServiceObj);die;
		$this->pictureServiceObj->updateHavePhoto('add');
                $this->assertEquals('U',$this->objProfile[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"]);
		
        }
        public function testUpdateHavePhoto2(){
		$this->pictureServiceObj->updateHavePhoto('del','U');
                $this->assertEquals('U',$this->objProfile[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"]);
		
        }
        public function testUpdateHavePhoto3(){
		$this->pictureServiceObj->updateHavePhoto('del','N');
                $this->assertEquals('N',$this->objProfile[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"]);	
		
        }
        public function testUpdateHavePhoto4(){
		$this->pictureServiceObj->updateHavePhoto('screen','1');
                $this->assertEquals('1',$this->objProfile[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"]);
		
        }
        public function testUpdateHavePhoto5(){
		$this->pictureServiceObj->updateHavePhoto('screen','0');
        	$this->assertEquals('0',$this->objProfile[0]->getDetail("","","PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER")["HAVEPHOTO"]);
        }

}
?>	
