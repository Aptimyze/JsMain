<?php 
	include_once("profile/connect.inc");
	include_once ($_SERVER['DOCUMENT_ROOT']."/classes/ShortURL.class.php");
        include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	$shortURL = $_GET[id];
	$getURL = new ShortURL();
	$URL = $getURL->getLongURL($shortURL);

	if($URL)
        {
        $URL=parseURLAndLog($URL);
        header("Location:$URL");
        }
            
            else
	{
		header ("$_SERVER[SERVER_PROTOCOL] 301 Moved Permanently");
	        header("Location:$SITE_URL");
	}
        
        
        function parseURLAndLog($url)
        {

                            $newUrl='';
                            $page=explode('?',$url);
                            $page=$page[0];
                            $newUrl.=($page.'?');
                            $page2=$page[1];
                            $pgArr=explode('&',$page2);
                            foreach ($pgArr as $key => $value) 
                            {
                                if(strpos($value,'=')!==false )
                                    {
                                    $tempSplit=explode('=',$value);
                                    $tempArr[$tempSplit[0]]=$tempSplit[1];
                                    }
                                if($tempSplit[0]!='echecksum')  
                                    $newUrl.=($value.'&');
                            }  
                            
                            if(!$tempArr['echecksum'])return $url;
                            $echecksum=$tempArr['echecksum'];
                            $checksum=$tempArr['checksum'];
                            
                            $page=explode('/',$page);
                            $no=count($page);
                            $page=$page[$no-1];
                            
                            
                            if($tempArr['linkFromSMS']=='Y')    
                            $authenticationLoginObj= AuthenticationFactory::getAuthenicationObj(null);
                            if($authenticationLoginObj->decrypt($echecksum,"Y")==$checksum)
                            {
                                    $authenticationLoginObj->setAutologinAuthchecksum($checksum,$url);
                            }
                            else
                            {
                                    $authenticationLoginObj->removeCookies();
                            }

                            $checksum=explode('i',$checksum);
                            $profileid=$checksum[1];
                            (new MIS_ShortUrlLog())->insertEntry($profileid,$page); 
                            
                            
                            return $newUrl;
                            
        }
?>
