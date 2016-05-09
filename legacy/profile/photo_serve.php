<?php
mail("kumar.anand@jeevansathi.com,lavesh.rawat@jeevansathi.com","web/profile/photo_serve.php called",$_SERVER);
die;
header('Content-type: image/jpeg');
include_once("config.php");
readfile("$PHOTO_URL/profile/photo_serve.php?version=$version&profileid=$profileid&camefrom=$camefrom&photo=$photo&messenger_js_yes=$messenger_js_yes&jsval=$jsval");
exit;

	
        header("Cache-Control: private");
        $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
        $mtime = time();

        $gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

        // if both the dates are same send not modified header and exit. No need to send further output
        if ($if_modified_since == $gmdate_mod)
        {
                header("HTTP/1.0 304 Not Modified");
                header("Expires: " . gmdate('D, d M Y H:i:s', time()+(3600*24)) . " GMT");
                exit;
        }
        // tell the browser the last modified time so that next time when the same file is requested we get to know the modified time
        else
        {
                header("Last-Modified: $gmdate_mod");
                header("Expires: " . gmdate('D, d M Y H:i:s', time()+(3600*24)) . " GMT");
        }
	

if($photo=="THUMBNAIL" && $camefrom!="CHAT" && !$jsval)
{
	/* THUMBNAIL photo request from my profile(edit page)
	*/
        
	include("connect_db.php");
        $db=connect_737_ro();

        $fieldname = array("MAINPHOTO","ALBUMPHOTO1","ALBUMPHOTO2","THUMBNAIL","PROFILEPHOTO");
        $flagError = false;

        list($CHECKSUM,$profileid) = explode("i",$profileid);
        if($CHECKSUM != md5($profileid))
                $flagError = true;
        elseif(!in_array($photo,$fieldname))
                $flagError = true;

        $profileid -= 5;//we pass 5 more to profileid, so decrement to get the actual profileid
        //$CHECKSUM=md5($profileid);

        $x=intval(intval($profileid)/1000) . "/" . $CHECKSUM;

        if(!headers_sent())
                header('Content-type: image/jpeg');

	/* Removed section of code
        $sql = "select COUNT(*) AS CNT from newjs.PICTURE_FOR_SCREEN where PROFILEID=$profileid";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row["CNT"]>0){
		$myflag=@readfile("http://ser4.jeevansathi.com/thumbnails/".$x.".jpg");
	}
        else
        {
                $sql = "select THUMBNAIL from newjs.PICTURE where PROFILEID=$profileid";
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);
                if($row["THUMBNAIL"]=='Y'){
			$myflag=@readfile("http://ser4.jeevansathi.com/thumbnails/".$x.".jpg");
		}
                else
                        @readfile("images/no_photo.jpg");
        }
        if(!$myflag)
                @readfile("images/no_photo.jpg");
	*/

        $sql = "select $photo from  PICTURE  where PROFILEID=$profileid";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[$photo]=='Y')
        {
		$sql = "select $photo DATA from  PICTURE_UPLOAD  where PROFILEID=$profileid AND $photo<>''";
		$result=mysql_query($sql);
		if(mysql_num_rows($result)>0)
		{
			$ro=mysql_fetch_array($result);
			echo $ro["DATA"];
		}	
		else{
	        	$myflag=@readfile("/usr/local/photos/".strtolower($photo)."/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg");
                	if(!$myflag)
                	{
				@readfile("images/no_photo.jpg");
                        }
               }
        }
        else
        	@readfile("images/no_photo.jpg");

}
else
{
        include("connect_db.php");
        $db=connect_737_ro();

        $fieldname = array("MAINPHOTO","ALBUMPHOTO1","ALBUMPHOTO2","THUMBNAIL","PROFILEPHOTO");
        if(!$messenger_js_yes)
        {
                list($CHECKSUM,$profileid) = explode("i",$profileid);
                $flagError = false;

                if($CHECKSUM != md5($profileid))
                {
                        $flagError = true;
                }
                elseif(!in_array($photo,$fieldname))
                {
                        $flagError = true;
                }

                $profileid -= 5;//we pass 5 more to profileid, so decrement to get the actual profileid
        }

        if($photo == "THUMBNAIL" && $camefrom=="CHAT")
        {
                $sql="SELECT PHOTO_DISPLAY from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
                $result=mysql_query($sql);
                $myrow= mysql_fetch_row($result);

                header('Content-type: image/jpeg');


                if($myrow[0]=="H")
                {
                        readfile("images/photo_hidden_60x60.jpg");
                }
                elseif($myrow[0]=="C")
                {

                        //Sharding On Contacts done by Lavesh Rawat
                        $contactResult=getResultSet("count(*) as cnt",$profileid,"",$myprofileid);
                        $rescount=$contactResult[0]['cnt'];

                        if($rescount[0] == 0)
                        {
                                //Sharding On Contacts done by Lavesh Rawat
                                $contactResult=getResultSet("count(*) as cnt",$myprofileid,"",$profileid);
                                $rescount=$contactResult[0]['cnt'];

                                if($rescount[0] > 0)
                                        $showimage=1;
                                else
                                        $showimage=0;
                        }
                        else
                                $showimage=1;

                        if($showimage==1)
                        {
                                $sql = "select $photo from  PICTURE  where PROFILEID=$profileid";
                                $result=mysql_query($sql);
                                $row=mysql_fetch_array($result);

                                if($row[$photo]=='Y')
                                {

	                                $sql = "select $photo DATA from  PICTURE_UPLOAD  where PROFILEID=$profileid and $photo<>''";
                                        $result=mysql_query($sql);
                                        if(mysql_num_rows($result)>0)
                                        {
        	                                $ro=mysql_fetch_array($result);
                                                echo $ro["DATA"];
                                        }
					else	
                                        	$myflag=readfile("/usr/local/photos/".strtolower($photo)."/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg");
                                }
                                else
                                        readfile("images/no_photo.jpg");
                        }
                        else
                                readfile("images/photo_protected.jpg");
                }
                else
                {

                        $sql = "select $photo from  PICTURE  where PROFILEID=$profileid";
                        $result=mysql_query($sql);
                        $row=mysql_fetch_array($result);

                        if($row[$photo]=='Y')
                        {
				$sql = "select $photo DATA from  PICTURE_UPLOAD  where PROFILEID=$profileid and $photo<>''";
				$result=mysql_query($sql);
				if(mysql_num_rows($result)>0)
				{
					$ro=mysql_fetch_array($result);
					echo $ro["DATA"];
				}
				else
					$myflag=readfile("/usr/local/photos/".strtolower($photo)."/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg");
                        }
                        else
                                readfile("images/no_photo.jpg");
                }

                exit;
        }
        else
        {
		/* ALBUMPHOTO1,ALBUMPHOTO2 in edit my profile page
		 * MAINPHOTO,ALBUMPHOTO1,ALBUMPHOTO2 in photo layer
		 * PROFILEPHOTO in viewprofile page
		 * PROFILEPHOTO in similar search result (on hover case to show big image)   	
		*/
                if(!$flagError)
                {
                        $sql = "select $photo DATA from PICTURE where PROFILEID=$profileid";
			/*
                        if(0)//$photo == "THUMBNAIL")
                                $sql .= " THUMBNAIL ";
                        else
                        {
                                        $sql .= " PICTURE ";
                        }
                        $sql .= " where PROFILEID=$profileid";
			*/
                        $result = mysql_query($sql);
                        if(@mysql_num_rows($result) > 0)
                        {
                                $ro = mysql_fetch_array($result);

                                // We'll be outputting a JPEG
                                header('Content-type: image/jpeg');

                                if($ro["DATA"])
                                {
					if($ro["DATA"] == 'Y')	
                                        {
	                                        $sql = "select $photo DATA from  PICTURE_UPLOAD  where PROFILEID='$profileid' AND $photo<>''";
                                                $result=mysql_query($sql);
                                                mysql_close($db);
                                                if(mysql_num_rows($result)>0)
                                                {
            	                                    $ro=mysql_fetch_array($result);
                                                    echo $ro["DATA"];
                                                }
                                                else
                                                {
                 	                               @readfile("/usr/local/photos/".strtolower($photo)."/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg");
                                                }
                                        }
                                }
                                elseif($photo=="THUMBNAIL")
                                {
                                        readfile("images/click_for_photo.gif");
                                }
                                else
                                        readfile("images/no_photo.gif");
                        }
                        else


                        {
                                header('Content-type: image/gif');
                                if($photo=="THUMBNAIL")
                                        readfile("images/nophotoimage.gif");
                                else
                                        readfile("images/no_photo.gif");
                        }
                }
        }
}
?>

