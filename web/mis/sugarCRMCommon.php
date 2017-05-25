<?php
//Common functions that can be useful for sugarCRM related MIS's

function getSugarUser($id)
{
	if($id)
	{
		$sql="SELECT user_name FROM sugarcrm.users WHERE id='$id'";
		$res=mysql_query_decide($sql) or die("Error while fetching user name  ".$sql."  ".mysql_error_js());
		if($row=mysql_fetch_assoc($res))
			return $row["user_name"];
		else
			return null;
		
	}
	else
		return null;
}

function getCampaignName($id)
{
        if($id)
        {
                $sql="SELECT name FROM sugarcrm.campaigns WHERE id='$id'";
                $res=mysql_query_decide($sql) or die("Error while fetching campaign name  ".$sql."  ".mysql_error_js());
                if($row=mysql_fetch_assoc($res))
                        return $row["name"];
                else
                        return null;
                
        }
        else
                return null;
}

function getCampaignNewsPaperName($id)
{
	if($id)
        {
                $sql="SELECT newspaper_c FROM sugarcrm.campaigns_cstm WHERE id_c='$id'";
                $res=mysql_query_decide($sql) or die("Error while fetching campaign newspaper name  ".$sql."  ".mysql_error_js());
                $row=mysql_fetch_assoc($res);
		if($row["newspaper_c"])
                        return $row["newspaper_c"];
                else
                        return null;

        }
        else
                return null;
}

function displayDate($dt)
{
	$dt=trim($dt);
	if($dt)
	{
		$dtString=explode(" ",$dt);
		if(is_array($dtString) && count($dtString)==2)
		{
			$dtArr=explode("-",$dtString[0]);
			if(is_array($dtArr) && count($dtArr)==3)
			{
				$displayDate=$dtArr[2]."-".$dtArr[1]."-".$dtArr[0];
				return $displayDate;
			}
			else
				return null;
		}
		else
			return null;
		
	}
	else
		return null;
}
?>
