<?php
/**
* This function will check the health status of HaProxy.
*/
class HaProxy{
	private $url;
	private $username;
	private $password;

	public function __construct(){
               $this->url ="172.10.11.25:8082/js/";
		$this->username = "readjs";
		$this->password = "password@234";
	}

	function validate(){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $this->url);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$DOM = new DOMDocument;
		$DOM->loadHTML($output);

		$finder = new DomXPath($DOM);
		$classname="active_down";
		$nodes = $finder->query("//*[contains(@class, '$classname')]");
		$servers = null;
		foreach($nodes as $k=>$node)
		{
			if($y= $node->childNodes->item(0)->nodeValue)
				$servers[]=$y;
		}	
		return $servers;
	}
}
