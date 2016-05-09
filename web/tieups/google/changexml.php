<?php
	ini_set('max_execution_time','0');

	$file=JsConstants::$docRoot."/tieups/google/jeevan.xml";
	$file1=JsConstants::$docRoot."/tieups/google/jeevan1.xml";

	$fp=fopen($file,"rb");
	if($fp)
	{
		$fp1=fopen($file1,"wb");
		if($fp1)
		{
			while(!feof($fp))
			{
				$contents=fread($fp,8192);
				$contents=check_ascii($contents);
				fwrite($fp1,$contents);
			}
			
			fclose($fp1);
			fclose($fp);
		}
	}

	function check_ascii($contents)
	{
		$len=strlen($contents);

		$str="";
		$i=0;
		while($i<$len)
		{
			$ch=$contents{$i};
			if((ord($ch)<127 && ord($ch)>31) || ord($ch)==9 || ord($ch)==10)
				$str.=$contents{$i};
			$i++;
		}

		return $str;
	}

?>
