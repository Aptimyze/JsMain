<?php
/**
* Author: Lavesh Rawat
* Mysql/File Locks mechanism
*/
class LockingService
{
	private $fp;
	private $fpPath;

        /**
        * This function will get the lock
        * @param lockName name of lock
        * @param timedout lock timedout
        **/
	public function getMysqlLock($lockName,$timedout='1000')
	{
		$MYSQL_LOCK = new MYSQL_LOCK;
		$lock = $MYSQL_LOCK->get($lockName,$timedout);
		return $lock;
	}

        /**
        * This function will release the lock
        * @param lockName name of lock
        **/
	public function releaseMysqlLock($lockName)
	{
		$MYSQL_LOCK = new MYSQL_LOCK;
		$lock = $MYSQL_LOCK->release($lockName);
		return $lock;
	}

	public function getFileLock($file,$timesTried="")
	{
		if(!$timesTried)
			$timesTried=20;
		$file="/tmp/".$file."lock";

		while($timesTried-->0)
		{
			$this->fpPath = $file;
			$this->fp=fopen($file,"w+");
			if($this->fp)
			{
				$gotlock=flock($this->fp,LOCK_EX + LOCK_NB);
			}
			if($gotlock)
				return true;	
			else
				sleep(rand(1,2));
		}
		if(!$gotlock)
			return NULL;
		else
			return true;
		
	}
	public function releaseFileLock()
	{
		flock($this->fp,LOCK_UN);
                fclose($this->fp);
		unlink($this->fpPath);
	}

        public function semgetLock($identifier)
        {
                $key=sem_get($identifier,1);
                sem_acquire($key);
                return $key;
        }

        public function semreleaseLock($key)
        {
                sem_release($key);
        }
}
?>
