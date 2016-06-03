<?php
include('connect.inc');
connect_db();
$doc = new DOMDocument();
$doc->preserveWhiteSpace=false;
$doc->load('wurfl.xml');
//$doc->load('wurfl.xml',LIBXML_NOBLANKS|LIBXML_COMPACT);
$xpath = new DOMXpath($doc);
$i=0;
$devices=$xpath->query("//device/group/capability[@name='is_tablet']");
foreach($devices as $device){
	$dev=$device->parentNode->parentNode;
	$ids_with_is_tablet[]=$dev->getAttribute('id');
	if($device->getAttribute('value')=='true'){
		$ids[]=$dev->getAttribute('id');
		echo $user_agents[$dev->getAttribute('id')]=$dev->getAttribute('user_agent');echo "\n";
	}
}
$devices=$doc->getElementsByTagName('device');
foreach($devices as $bl){
	$fall_back= $bl->getAttribute('fall_back');
	if(!in_array($bl->getAttribute('id'),$ids_with_is_tablet)){
	while($fall_back && $fall_back!='root'){

		if(in_array($fall_back,$ids_with_is_tablet)){
			$ids_with_is_tablet[]=$bl->getAttribute('id');
			if(in_array($fall_back,$ids)){
				$ids[]=$bl->getAttribute('id');
		echo		$user_agents[$bl->getAttribute('id')]=$bl->getAttribute('user_agent');echo "\n";
			}
			break;
		}else
			{
				$dev_with_fallback=$xpath->query("//device[@id='$fall_back']");
				$dev_with=$dev_with_fallback->item(0);
				$fall_back= $dev_with->getAttribute('fall_back');
			}
	}
	}
}
var_dump($user_agents);
echo count($user_agents);
die;
/*	if(in_array($fall_back,$ids))
		$user_agents[$bl->getAttribute('id')]=$bl->getAttribute('user_agent');
	else{
		$dev_with_fallback=$xpath->query("//device[@id='$fall_back']");
		$dev_with=$dev_with_fallback->item(0);
		$i=0;
		while($i<1000){//Find node having at least one child;limit depth to 1000
			if(!$dev_with->childNodes->length && $dev_with->hasAttribute('fall_back'))
			{
			$fall_back= $dev_with->getAttribute('fall_back');
			 $dev_with_fallback=$xpath->query("//device[@id='$fall_back']");	 
			$dev_with=$dev_with_fallback->item(0);
			}
			else
				break;
			$i++;
		}
		if(in_array($fall_back,$ids))
			$user_agents[$bl->getAttribute('id')]=$bl->getAttribute('user_agent');

	}
		}
	}
}
}catch(Exception $ex){
	echo $ex->getMessage();
}
var_dump($user_agents);
count($user_agents);
die;
//echo $device->item(0)->getAttribute('id');echo 'yy';die;
mysql_query_decide("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000");
while($i<$devices->length){
	$device=$devices->item($i);
	if($device->hasAttribute('user_agent')){
							$user_agent=$device->getAttribute('user_agent');
   	{
$children=$device->childNodes;
if($children->length>0){
foreach($children as $child){
	is_tablet($child,$user_agent);
}
	unset($children);
//	var_dump($fChild->);die;
	}
else{
	if($device->hasAttribute('fallback')){
		echo $fall=$device->getAttribute('fallback');
		$nod=$devices->getElementById($fall);
		$children=$nod->childNodes;
		die;
	}
}
	}
	}
	$i++;
}
function is_tablet($child,$user_agent){
	if($child->nodeName=='group' && $child->getAttribute('id')=='product_info'){
		foreach($child->childNodes as $ch){
			if($ch->nodeName=='capability')
				if($ch->hasAttribute('name'))
					if($ch->getAttribute('name')=='is_tablet'){
						$is_tablet_defined=true;
						if($ch->getAttribute('value')=='true'){
							if(strpos($user_agent,'DO_NOT_MATCH')===false)
								$sql="insert into newjs.tablets values ('$user_agent')";
							if($sql&&false)mysql_query_decide($sql) or die('problem in query');
							break;
							unset($sql);
						}
					}
		}
		break;
	}
	if(!$is_tablet_defined)
		$is_tablet_undefined_nodes[]=$child->parentNode;
}
 */
