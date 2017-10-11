<?PHP

/************************************************************************************************************************
*    FILENAME           : bms_editbannertype.php
*    DESCRIPTION        : Edit banner type(image,flash......) deveopment cost .
*    CREATED BY         : lavesh
*    Live On            : 20 july 2007
***********************************************************************************************************************/

include ("./includes/bms_connect.php");
$ip=FetchClientIP();
$data=authenticatedBms($id,$ip,"banadmin");
global $dbbms;

if($data)
{
	if($submit)
	{
                if(trim($bannertype)=='')
                {
			$smarty->assign("error",1);			
                }
                else
                {
                        $btype=ltrim(stripslashes($bannertype),"'");
                        $btype_arr=explode("#",$btype);
                        $btype=$btype_arr[0];	
			if($btype)
			{
				$sql="UPDATE bms2.BANNER_COST SET Description='". addslashes(stripslashes($banner_details))."',Cost='$amount' WHERE  Type='$btype'";
				mysql_query($sql,$dbbms) or die(mysql_error());
				$smarty->assign("confirmation",1);
				$smarty->assign("bannertype",$btype);
				$confirm_yes=1;
			}
			else
			{
				$smarty->assign("error",1);
			}
                }
	}

	if(!$confirm_yes)
	{
		$sql="SELECT Type,Description,Cost FROM bms2.BANNER_COST ";
                $res=mysql_query($sql,$dbbms) or die(mysql_error());
                while($myrow=mysql_fetch_array($res))
		{
			$type=$myrow['Type'];
			$description=$myrow['Description'];
			$cost=$myrow['Cost'];
			$values.=$type."#";
	                $bannertype_arr[]=array(
                                "type" => $type,
                                "description" => $description,
                                "cost" => $cost
                                );
		}
		$smarty->assign("bannertype_arr",$bannertype_arr);
		$smarty->assign("values",$values);

	}
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->display("./$_TPLPATH/bms_editbannertype.htm");
}
else
	TimedOutBms();
?>
