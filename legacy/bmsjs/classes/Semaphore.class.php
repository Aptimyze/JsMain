<?php
/** 
* All the Semaphore related operations are peformed through this class.
* Only one processes can acquire the semaphore at a time and .Therfore it will help to manage access to a shared resource.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/

class Semaphore
{
	private $key;
	private $identifier;
	
	/**
	* Get a lock on file lock. 
	* Semaphore is based on file as only one process can have exclusive excess to file.
	* @param int identifier key used for entering Semaphore.
	* @return file-pointer
	*/		
	public function getLock($identifier)
	{
		$fp =@fopen("/tmp/lock$identifier.txt", "r");
		if(!$fp)
		{
			$fp = fopen("/tmp/lock$identifier.txt", "w+");
			chmod("/tmp/lock$identifier.txt", 0755);
		}
		if (flock($fp, LOCK_EX))
			return $fp;
	}

	/**
	* releases semaphore by relasing lock on file
	* @param void $fp key associated with file locked by above process to eneter semaphore 
	*/

	public function releaseLock($fp)
	{
		flock($fp, LOCK_UN);
	}


	/**
	* Get a lock on variable key , a process attempting to acquire a semaphore(lock on variable key)  will wait untill released
	* @deprecated Not to use this ---as this there is limit of 128 semaphore. 
	* @param int identifier key used for entering Semaphore
	* @return int
	*/
	public function semgetLock($identifier)
	{
	        $key=sem_get($identifier,1);
	        sem_acquire($key);
        	return $key;
	}

	/**
	* releases semaphore . 
	* @deprecated Not to use this ---as this there is limit of 128 semaphore. 
	* @param void $key key associated with semaphore acquired by process having identifier as $identifier
	*/
	public function semreleaseLock($key)
	{
		@sem_release($key);
	}

}
?>
