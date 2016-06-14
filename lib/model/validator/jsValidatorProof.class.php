<?php

class jsValidatorProof extends sfValidatorBase
{
	private $fileType         = array("jpg","JPG","jpeg","JPEG","PDF","pdf");
	const FILE_SIZE 	= 5242880;
	
	protected function configure($arrOptions = array(), $arrMessages = array())
	{
                foreach(ErrorHelp::getErrorArrayByField('FILEVAL') as $key=>$msg)
                        $this->addMessage($key,$msg);  	
		$this->addOption('type',$arrOptions[type]);
		$this->addOption('size',$arrOptions[size]);
		$this->addOption('file',$arrOptions[file]);
	}
	
	protected function doClean($value)
	{
		
		$type = $this->getOption("type");
		$size = $this->getOption("size");
		$file = $this->getOption("file");
                $type = explode('/',$type);
                if (!in_array($type[1],$this->fileType)) {
                  throw new sfValidatorError($this,'err_file_type', array('value' => $type[1]));
                }

		if($size > self::FILE_SIZE )
		{
			throw new sfValidatorError($this,'err_file_size', array('value' => $size));
		}
                return $file;
	}
}
?>
