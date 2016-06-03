<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
$db=connect_db();

$flag=0;
$data=authenticated($checksum);

//mysql_close();
$db=connect_737_lan();

$chkprofilechecksum=explode("i",$profilechecksum);
if($chkprofilechecksum[0]==md5($chkprofilechecksum[1]))
{
	$profileid=$chkprofilechecksum[1];
	$profileidp5=$profileid+5;
	$checksump5=md5($profileidp5)."i".$profileidp5;
	
	$sql="SELECT USERNAME,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
	$myrow=mysql_fetch_array($result);


	$username=$myrow['USERNAME'];
	$havephoto=$myrow['HAVEPHOTO'];
    $photodisp=$myrow['PHOTO_DISPLAY'];
    $photoscreen=$myrow['PHOTOSCREEN'];

	if($jsadmin=="yes")
		$havephoto='Y';

	if($havephoto!='Y')
    {
		exit;
    }
    else
    {
		//Symfony Photo Modification - start
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

		$album = SymfonyPictureFunctions::getAlbum($profileid);
		$mainphoto = $album[0];
		$albumphoto1 = $album[1];
		$albumphoto2 = $album[2];
		$prophoto = $album['profile'];
		//Symfony Photo Modification - end

		if(!$albumphoto1)
		{
			$noalb1=1;
		}
		if(!$albumphoto2)
		{
			$noalb2=1;
		}
		//Symfony Photo Modification - end

		if($seq==1)
		{
			$currentphoto="MAINPHOTO";
			$next=$seq+1;
		}
		elseif($seq==2)
        {
			$currentphoto="ALBUMPHOTO1";
			if(!$albumphoto1)
			{
				$currentphoto="ALBUMPHOTO2";
			}
			$next=$seq+1;
			$prev=$seq-1;
		}
		elseif($seq==3)
		{
			$currentphoto="ALBUMPHOTO2";
			$prev=$seq-1;
		}

//Symfony Photo Modification - start
	if($mainphoto)
        {
			$flag=1;
        }
        
	if($albumphoto1)
        {
			$flag=1;
        }
        else 
        	$noalb1=1;
        
	if($albumphoto2)
        {
			$flag=1;
        }
        else 
        	$noalb2=1;
//Symfony Photo Modification - end
                	
		if($noalb1==1 && $noalb2!=1)
		{
			$alb1=1;
			$prev=1;
			$next=2;
		}
		if($noalb1!=1 && $noalb2==1)
		{
			$alb2=1;
			$prev=1;
			$next=2;
		}

		if($noalb1==1 && $noalb2==1)	
		{
			$noalb=1;
		}

		if($photodisp=='H')
		{
			if(isset($data))
			{
				if($data['PROFILEID']==$profileid)
				{
					$flag=1;
				}
			}
		}
		elseif($photodisp=='C')
        {
            if(isset($data))
            {
				//Sharding On Contacts done by Lavesh Rawat
				$contactResult=getResultSet("count(*) as cnt","$data[PROFILEID]","",$profileid,"","'A','C'");
				$cnt=$contactResult[0]['cnt'];
		
				if($cnt>0)
				{
					$flag=1;
				}
				else 
				{
					//Sharding On Contacts done by Lavesh Rawat
					$contactResult=getResultSet("count(*) as cnt",$profileid,"","$data[PROFILEID]","","'I','A','D'");
					$cnt=$contactResult[0]['cnt'];
					if($cnt>0)
					{
						$flag=1;
					}
				}
			}
		}

		// close mysql connection
		//mysql_close($db);
		
		if($flag==1)
		{
			$smarty->assign("seq",$seq);
			$smarty->assign("alb1",$alb1);
			$smarty->assign("alb2",$alb2);
			$smarty->assign("noalb",$noalb);
			$smarty->assign("prev",$prev);
			$smarty->assign("next",$next);
			$smarty->assign("USERNAME",$username);
			$smarty->assign("currentphoto",$currentphoto);
			$smarty->assign("checksump5",$checksump5);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("PROFILECHECKSUM",$profilechecksum);
//			$smarty->assign("version",$version);		

			//Symfony Photo Modification - start	
			$smarty->assign("MAINPHOTO",$mainphoto);		
			$smarty->assign("ALBUMPHOTO1",$albumphoto1);		
			$smarty->assign("ALBUMPHOTO2",$albumphoto2);		
			$smarty->assign("PROPHOTO",$prophoto);		
			//Symfony Photo Modification - end

			//$smarty->display("jeevansathi/album.htm");
			$smarty->display("album1.htm");
		}
	}
}
else
{
	echo "ERROR";
}

// flush the buffer
if($zipIt)
	ob_end_flush();

?>
