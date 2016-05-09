<?php

class profileDisplay{
	
	
	public function getNextPreviousProfile($profileObj,$key,$offset)
	{
		$data = unserialize(JsMemcache::getInstance()->get($key));
		if(count($data)<$offset)
		{
			$profileCommunication = new ProfileCommunication();
			if(MobileCommon::isDesktop())
				$module= "ContactCenterDesktop";
			else
				$module="ContactCenterAPP";
			$config = $profileCommunication->getInboxConfiguration($module,$profileObj);
			$keyArray = explode("_",$key);
			foreach($keyArray as $k=>$v)
			{
				if($k==0)
					continue;
				$infoType.=$v."_";
			}
			$infoType = substr($infoType, 0, -1);
			$pageNo = ceil($offset/$config[$infoType]["COUNT"]);
			$infoTypenav["PAGE"] = $infoType;
			$infoTypenav["NUMBER"]=$pageNo;
			if(JsMemcache::getInstance()->get($key."_COUNT"))
				$totalCount= JsMemcache::getInstance()->get($key."_COUNT");
			else
			{
				$count = $profileCommunication->getCount($module,$profileObj,$infoTypenav);
				$totalCount = $count[$infoType];
			}
			if($totalCount<$offset)
				return null;
			$this->displayObj= $profileCommunication->getDisplay($module,$profileObj,$infoTypenav);
			$data = unserialize(JsMemcache::getInstance()->get($key));
		}
		if(count($data) <$offset)
			return null;
		$i=1;
		foreach($data as $key=>$keyData)
		{
			if($i==$offset)
				break;
			$i++;
		}
		$profileid = $key;
		return JsCommon::createChecksumForProfile($profileid);
	}
}
