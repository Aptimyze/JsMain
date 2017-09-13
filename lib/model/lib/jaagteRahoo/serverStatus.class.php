<?php
class ServerStatus
{
	private $serverUrls;
	private $highThreshold, $midThreshold,$messages;
        public function __construct()
	{
		$this->serverUrls = array(61=>"http://10.10.18.61/server-status",
					62=>"http://10.10.18.62/server-status",
					63=>"http://10.10.18.63/server-status",
					65=>"http://10.10.18.65/server-status",
					85=>"http://10.10.18.85/server-status",
					73=>"http://10.10.18.88/server-status",
					72=>"http://10.10.18.72/server-status");
		$this->highThreshold = 0;
		$this->midThreshold = 10;
		$this->messages = array("high"=>":-( Khatam hai ye",
					"mid" => "Marne wala h",
					"low" => "khush h");
	}
	public function getStatus()
	{
		foreach($this->serverUrls as $k=>$urlHit)
		{
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $urlHit );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			$DOM = new DOMDocument;
			$DOM->loadHTML($output);

			$nodes = $DOM->getElementsByTagName("dt");
			$idleNum = null;
			foreach($nodes as $n=>$node)
			{
				if($line= $node->nodeValue)
				{
					if(strpos($line,"worker"))
					{
						$arr = explode(" ",$line);
						if(array_key_exists(0,$arr))
							$idle = $arr[0];
						else
							$idle = $line;

						$idleNum = 200- $idle;
					}
				}
			}
			if($idleNum==null)
			{
				$idleNum = 0;
			}
			$idleWorker[$k]['idle']=$idleNum;
			if($idleNum<=$this->highThreshold)
			{
				$idleWorker[$k]['flag']=0;
				$idleWorker[$k]['message']=$this->messages['high'];
			}
			elseif($idleNum<=$this->midThreshold)
			{
				$idleWorker[$k]['flag']=1;
				$idleWorker[$k]['message']=$this->messages['mid'];
			}
			else
			{
				$idleWorker[$k]['flag']=2;
				$idleWorker[$k]['message']=$this->messages['low'];
			}
		}
		return $idleWorker;
	}
}
