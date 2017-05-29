<?php
global $status_disposition_list;
$status_disposition_list=array(
			13=>array(24),
			26=>array(30,1,29,2,3,31),
			24=>array(23),
			12=>array(6),
			14=>array(7,8,9,10,11),
			45=>array(12,13,14,16,32),
			17=>array(17,18),
			11=>array(19),
			46=>array(20,21,25,22),
			32=>array(26,27,28),
			);

global $dialer_allowed_status,$non_dialer_allowed_status,$dialer_allowed_disposition,$non_dialer_allowed_disposition;
$dialer_allowed_status=array(13,26,24,12,14,45,17,46);
$non_dialer_allowed_status=array(13,26,24,12,14,45,17,11,46);
$dialer_allowed_disposition=array(1,2,3,6,7,8,9,10,11,12,13,14,16,17,20,21,22,23,24,25,29,30,32);
$non_dialer_allowed_disposition=array(1,2,3,6,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,24,25,29,30,32);


global $status_allowed_transition;
$status_allowed_transition=array(
			13=>array(
				24=>array(
                		        12=>array(6),
		                        14=>array(7,8,9,10,11),
                		        45=>array(12,13,14,15,16,32),
		                        17=>array(17,18),
                		        11=>array(19),
		                        46=>array(20,21,22,25)
					)
				),
			26=>array(
				30=>array(
					26=>array(30,3)
					),
				1=>array(
					26=>array(1),
					),
				2=>array(
					26=>array(2)	
					),
				3=>array(
					26=>array(3)
					),
				29=>array(
					26=>array(29)
					),
				31=>array(
					26=>array(31)
					)
				),
			24=>array(
				23=>array(
					12=>array(6),
					14=>array(7,8,9,10,11),
					45=>array(12,13,14,15,16,32),
					17=>array(17,18),
					11=>array(19),
					46=>array(21,22,25)		
					)
				),
			12=>array(
				6=>array(
					12=>array(6),
					14=>array(7,8,9,10,11),
					45=>array(12,13,14,15,32),
					11=>array(19),
					46=>array(20,21,22,25)
					)
				),
			14=>array(
				7=>array(
					12=>array(6),
					14=>array(7,8,9,10,11),
					11=>array(19),
					45=>array(12,13,14,15,32),
					46=>array(20,21,22,25)
					)
				)
				);

function getStatusDisposition($status,$dialer=1)
{
	global $status_disposition_list;
	if($status && is_numeric($status))
	{
		$dialerCheck=0;
		if($dialer==1)
                	global $dialer_allowed_disposition,$dialer_allowed_status;
		elseif($dialer==2)
                	global $non_dialer_allowed_disposition,$non_dialer_allowed_status;
		if($dialer==1)
		{
			$dialerCheck=1;
			$statusCheckArray="dialer_allowed_status";
			$disCheckArray="dialer_allowed_disposition";
		}
		elseif($dialer==2)
		{
			$dialerCheck=1;
			$statusCheckArray="non_dialer_allowed_status";
			$disCheckArray="non_dialer_allowed_disposition";
		}
		if(!$dialerCheck || in_array($status,$$statusCheckArray))
		{
			$returnArray=array();
			if(is_array($status_disposition_list[$status]))
			{
				foreach($status_disposition_list[$status] as $disposition)
				{
					if(!$dialerCheck || in_array($disposition,$$disCheckArray))
						$returnArray[]=$disposition;
				}			
			}
			if(is_array($returnArray) && count($returnArray))
				return $returnArray;
			else
				return null;
		}
		else
			return null;
	}
	return null;	
}

function getAllowedStatusAndDispositionTransition($status,$disposition,$dialer=1)
{
	global $status_allowed_transition;
	if($dialer==1)
		global $dialer_allowed_status,$dialer_allowed_disposition;
	elseif($dialer==2)
		global $non_dialer_allowed_status,$non_dialer_allowed_disposition;

	$dialerCheck=0;
	if($status && is_numeric($status) && $disposition && is_numeric($disposition))
	{
		if(is_array($status_allowed_transition[$status][$disposition]))
		{
			if($dialer==1)
			{
				$dialerCheck=1;
				$statusCheckArray="dialer_allowed_status";
				$disCheckArray="dialer_allowed_disposition";
			}
			elseif($dialer==2)
			{
				$dialerCheck=1;
				$statusCheckArray="non_dialer_allowed_status";
				$disCheckArray="non_dialer_allowed_disposition";
			}
			$returnArray=array();
			foreach($status_allowed_transition[$status][$disposition] as $statuses=>$statDisArray)
			{
				if(!$dialerCheck || in_array($statuses,$$statusCheckArray))
				{
					foreach($statDisArray as $dis)
					{
						if(!$dialerCheck || in_array($dis,$$disCheckArray))
							$returnArray[$statuses][]=$dis;
					}					
				}
				
			}
			if(is_array($returnArray) && count($returnArray))
				return $returnArray;
			else
				return null;
		}
		else
			return null;
	}
	return null;
}

function checkAllowedTransition($currentStatus,$currentDisposition,$newStatus,$newDisposition)
{
	global $status_allowed_transition;
	if($currentStatus && $currentDisposition && $newStatus && $newDisposition)
	{
		if(array_key_exists($currentStatus,$status_allowed_transition))
		{
			if(is_array($status_allowed_transition[$currentStatus][$currentDisposition]))
			{
				if(array_key_exists($newStatus,$status_allowed_transition[$currentStatus][$currentDisposition]))
				{
					if(in_array($newDisposition,$status_allowed_transition[$currentStatus][$currentDisposition][$newStatus]))
						return true;
					else
					{
						echo "new disp not present";
						return false;
					}
				}
				else
				{
					echo "new status not present";
					return false;
				}
			}
			else 
			{
				echo "old disp not present";
				return false;
			}
		}
		else
		{
			echo "old status not present";
			return false;
		}
	}
}
function checkProfile($idString,$disposition)
{
	if($idString && $disposition)
	{
		$idString=addslashes($idString);
	        $db = DBManagerFactory::getInstance();
        	$sql="SELECT PROFILEID,ACTIVATED,INCOMPLETE FROM newjs.JPROFILE WHERE USERNAME=\"$idString\"";
	        $res=$db->query($sql,true);
        	if($row=$db->fetchByAssoc($res))
		{
			if($disposition=='20' && $row["ACTIVATED"]!='D')
				return 4;
			if($disposition=='21' && $row["ACTIVATED"]!='Y')
				return 5;
			if($disposition=='25' && ($row["INCOMPLETE"]!='Y' || $row["ACTIVATED"]=='D'))
				return 6;
			return 3;
		}
	        else
        		return "2";
	}
	else
		return "1";
}
?>
