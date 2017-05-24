<?php
mail("kumar.anand@jeevansathi.com,lavesh.rawat@jeevansathi.com","web/profile/photo_serve100.php called",$_SERVER);
	
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
	

        include("connect_db.php");

        $db=connect_737_ro();

        list($CHECKSUM,$profileid) = explode("i",$profileid);
        $flagError = false;

        if($CHECKSUM != md5($profileid))
        {
                $flagError = true;
        }
        elseif($photo!='PROFILEPHOTO')
        {
                $flagError = true;
        }
        $profileid -= 5;//we pass 5 more to profileid, so decrement to get the actual profileid

        if(!$flagError)
        {
                $sql="SELECT PHOTO_DISPLAY from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
                $result=mysql_query($sql);
                $myrow= mysql_fetch_row($result);
                if($myrow[0]=="H" && !$jsval)
                {
                        $type='gif';
                        $input_image="images/no_photo.gif";
                }
                else
                {
                        $sql = "select PROFILEPHOTO DATA from PICTURE where PROFILEID=$profileid";
                        $result = mysql_query($sql);
                        if(mysql_num_rows($result) > 0)
                        {
                                $ro = mysql_fetch_array($result);

                                // We'll be outputting a JPEG
                                $type='jpeg';

                                if($ro["DATA"])
                                {
                                        //readfile("thumbnails/".intval(intval($profileid)/1000) . "/" . md5($profileid+5)".jpg");
                                        if($ro["DATA"] == 'Y')
                                        {
						$input_image='';
						$sql = "select $photo DATA from  PICTURE_UPLOAD  where PROFILEID=$profileid and $photo<>''";
                                                $result=mysql_query($sql);
                                                //mysql_close($db);
                                                if(mysql_num_rows($result)>0)
                                                {       
                	                                $ro=mysql_fetch_array($result);
                                                        $input_image= $ro["DATA"];
                                                        $blob='1';
                                                 }
						if($input_image=='')
						{				
                                                	$memcache= new Memcache();
                                                	$memcache->connect("127.0.0.1",11211);

                                                	if(!$photodata=$memcache->get("/usr/local/photos/thumbnail2/$profileid"))
                                                	{
                                                	        //trigger_error("mem_miss",E_USER_WARNING);
                                                	        if(file_exists("/usr/local/photos/thumbnail2/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg"))
                                                	        {
                                                        		header('Content-type: image/jpeg');
                                                        		echo $photodata=@file_get_contents("/usr/local/photos/thumbnail2/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg");
                                                        		$memcache->set("/usr/local/photos/thumbnail2/$profileid",$photodata,0,3600*24);
                                                        		//@readfile("/usr/local/photos/thumbnail2/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg");
                                                        		exit;
                                                        	}

                                                        	$input_image="/usr/local/photos/".strtolower($photo)."/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg";
                                                        	if(!file_exists($input_image))
                                                        	{
                                                        		$input_image='';
									/*
                                                        		$sql = "select $photo DATA from  PICTURE_UPLOAD  where PROFILEID=$profileid and $photo<>''";
                                                        		$result=mysql_query($sql);
                                                        		//mysql_close($db);
                                                        		if(mysql_num_rows($result)>0)
                                                        		{
                                                                		$ro=mysql_fetch_array($result);
                                                                		$input_image= $ro["DATA"];
                                                                		$blob='1';

                                                                		$blob='1';
                                                        		}
									*/		
                                                        	}	
                                                	}
                                                	else
                                                	{
                                                	        //trigger_error("mem_hit",E_USER_WARNING);
                                                	        header('Content-type: image/jpeg');
                                                	        echo $photodata;
                                                	        exit;
                                                	}
						}
                                        }
                                }
                                else
                                {
                                        $type="gif";
                                        $input_image= "images/no_photo.gif";
                                }
                        }
                        else
                        {
                                $type="gif";
                                $input_image= "images/no_photo.gif";
                        }
                }
                if($input_image=='')
                {
                        $type="gif";
                        $input_image= "images/no_photo.gif";
                }
                get_smallsize($input_image,$type,$blob);
        }

function get_smallsize($input_image,$type='jpeg',$blob='0')
{
        $size=array('150','200');
        $thumb_width=100;
        $thumb_height=133;

        if($type=='gif')
                $gif=1;
        $thumbnail = ImageCreateTrueColor( $thumb_width, $thumb_height );

        // Create a new image from file 
        if($gif)
        {
                header("Content-type: image/gif");
                $src_img = ImageCreateFromGIF( $input_image );
        }
        elseif($blob)
        {
                header("Content-type: image/jpeg");
                $src_img = imagecreatefromstring($input_image);

        }
        else
        {
                header("Content-type: image/jpeg");
                $src_img = ImageCreateFromJPEG($input_image);
        }
        // Create the resized image
        imagecopyresampled( $thumbnail, $src_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1] );

        // Save the image as resized.jpg
        if($gif)
                ImageGIF( $thumbnail );
        else
                ImageJPEG( $thumbnail );
        // Clear the memory of the tempory image 
        ImageDestroy( $thumbnail);

}
?>

