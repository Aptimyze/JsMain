<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	function get_lock($file)
	{
		$fp=fopen($file . ".lock","w+");

		if($fp)
		{
			$gotlock=flock($fp,LOCK_EX + LOCK_NB);
			if(!$gotlock)
			{
				echo "cannot get lock. exiting";
				fclose($fp);
	                        exit;
			}
		}
		else
		{
			echo "cannot get lock. exiting";
			exit;
		}

		return $fp;
	}

	function release_lock($lockpointer)
	{
		flock($lockpointer,LOCK_UN);
		fclose($lockpointer);
	}
?>
