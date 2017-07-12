<?php

class jsValidatorDivorcedProof extends sfValidatorBase
{
	private $fileType         = array("jpg","JPG","jpeg","JPEG","PDF","pdf");
	const FILE_SIZE 	= 5242880;
	
	protected function configure($arrOptions = array(), $arrMessages = array())
	{
                foreach(ErrorHelp::getErrorArrayByField('FILEVAL') as $key=>$msg)
                        $this->addMessage($key,$msg);  	
		$this->addOption('name',$arrOptions[type]);
		$this->addOption('size',$arrOptions[size]);
		$this->addOption('file',$arrOptions[file]);
		$this->addOption('mstatus',$arrOptions[mstatus]);
	}
	
	protected function doClean($value)
	{
		
		$mstatus = $this->getOption("mstatus");
                if($mstatus == ""){
                        throw new sfValidatorError($this, ErrorHelp::$ERR_REQUIRED[mstatus], array('value' => $mstatus));
                }
                $mstatus_loggedIn = LoggedInProfile::getInstance()->getGENDER();
                if($mstatus == "D" && $mstatus_loggedIn !="D"){
                        $name = $this->getOption("name");
                        $size = $this->getOption("size");
                        $file = $this->getOption("file");
                        if($name == "" || $file["error"] != 0){
                                throw new sfValidatorError($this, ErrorHelp::$ERR_REQUIRED[mstatus_proof], array('value' => $name));
                        }
                        $name = explode('.',$name);
                        if (!in_array($name[1],$this->fileType)) {
                          throw new sfValidatorError($this,'err_file_type', array('value' => $name[1]));
                        }

                        if($size > self::FILE_SIZE )
                        {
                                throw new sfValidatorError($this,'err_file_size', array('value' => $size));
                        }
                }
                return $file;
	}
}
?>
