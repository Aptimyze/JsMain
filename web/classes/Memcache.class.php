<?php
/** 
* All the Memcache related operations are peformed through this class.
* It will decrease database load as data will be fetched from memory.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/

class UserMemcache extends Memcache 
{
	private $memConns = array();

	public function __construct()
	{
		$this->memdbHost=JsConstants::$memcache[HOST];
		$this->memport=JsConstants::$memcache[PORT];
		$this->memCompression=0;
		$this->memExpiry=3600; 
	}

	public function memConnect($memdbHost="",$portName="")
	{
		if(!$memdbHost)
			$memdbHost=$this->memdbHost;
		if(!$portName)
			$portName=$this->memport;


		if(!isset($this->memConns[$memdbHost]))
		{
			if($memdbHost==JsConstants::$localHostIp)
                                $this->memConns[$memdbHost]=$this->connect('unix:///tmp/memcached.sock',0);
                        else
                                $this->memConns[$memdbHost]=$this->connect($memdbHost,$portName);
		}
		$this->activeMemcache= $this->memConns[$memdbHost];
		return $this->activeMemcache;
	}

	public function logServerProfileMapping($profileid,$myDbId,$memdbHost="",$portName="")
	{
		$memcacheObj=$this->memConnect($memdbHost,$portName);
		if($memcacheObj)
		{
			$profiledDbServer=$profileid."DbServer";
			$this->set($profiledDbServer,$myDbId);
		}
	}

	public function getServerProfileMapping($profileid,$memdbHost="",$portName="")
	{
		$memcacheObj=$this->memConnect($memdbHost,$portName);
		if($memcacheObj)
		{
			$profiledDbServer=$profileid."DbServer";
			return $this->get($profiledDbServer);
		}
		return 0;
	}
	public function getDataFromMem($key)
	{
		$memcacheObj=$this->memConnect($memdbHost,$portName);
		if($memcacheObj)
		{
			return $this->get($key);

		}
		return '';
	}
	public function setDataToMem($value,$key_val=0,$time=3600)
	{
		$memcacheObj=$this->memConnect($memdbHost,$portName);
		$total_time=$time;
                if($memcacheObj)
                {
			$key=md5($value);
			if($key_val)
				$key=$key_val;
			if(!$key_val)
				if(!$this->get($key))
	                        	$this->set($key,$value,0,$total_time);
			if($key_val)
				$this->set($key,$value,0,$total_time);
			return $key;
                }
		return -1;
	}
	public function setlock($key)
	{
		$act_key=intval($key);
		if($act_key)
		{
			$rem=$act_key%100;
			$file_name="temp_$rem.txt";
			$fp = fopen("/tmp/$file_name", "w");
			@chmod("/tmp/$file_name",0777);
			if(flock($fp, LOCK_EX))
				return $fp;
		}
	}
	public function releaselock($fp)
	{
		if($fp)
			flock($fp, LOCK_UN);
	}

}

?>
