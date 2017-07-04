<?php
/*
 *	Author:Sanyam Chopra
 *	This task will fetch pic url's from slave, convert them into complete Url's and ping each url to read the header 
 *	and check the response and accordingly take action
 */

class PhotoCheckCompleteUrlTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addArguments(array(
		new sfCommandArgument('days', sfCommandArgument::REQUIRED, 'NoOfDays'),
		new sfCommandArgument('picUrl', sfCommandArgument::REQUIRED, 'PicUrl'),
		));


	    $this->namespace        = 'cron';
	    $this->name             = 'PhotoCheckCompleteUrl';
	    $this->briefDescription = 'Checks complete url of a pic to see if there is any error';
	    $this->detailedDescription = <<<EOF
	   This cron will be used to fetch image url's from PICTURE_NEW table and check their http header response code to see if there is an error
	   and accordingly save it along with its corresponding PICTUREID and http_code.
	   Use number(1/2/4/10) for the first parameter and the exact column name in the second argument as in the PICTURE_NEW table (e.g.:MainPicUrl).
	   Use 'AllPicUrl' as the second argument in case we need to select all types of url from PICTURE_NEW.
	   Call it with:
	   [php symfony cron:PhotoCheckCompleteUrl days picUrl]
EOF;
	}
	protected function execute($arguments = array(), $options = array())
	{	
        $flag=0;
        $days = $arguments["days"];
        $completeUrlArray = "";
        //to check if the argument entered in the cron is a valid argument and accordingly assigning it to $picUrl
        if(in_array($arguments["picUrl"],ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS))
        {
        	$picUrl = $arguments["picUrl"];
        }

        elseif($arguments["picUrl"]=="AllPicUrl")
        {
        		$allPicUrlArray=ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS;
        		$picUrl = implode(",",$allPicUrlArray);
        		$flag=1;	
        }

        else
        	{echo("\n \n"."*******invalid argument. Please provide a valid argument *******"."\n \n");die;}

        //To convert $days into proper date-time format
        $requiredDate = date("Y-m-d H:i:s",JSstrToTime('now -'.$days.' days'));
        $picObj = new PICTURE_NEW("newjs_slave");
        $picUrlArray = $picObj->getRequiredUrl($requiredDate,$picUrl);
        if($picUrlArray == "")
        	{echo("No data exists for given date. Please change date and try again"."\n");die;}
        //This loop calls getCloudOrApplicationCompleteUrl of PictureFunctions and creates a complete url and then makes a curl request to check the response and accordingly saves it in the PIC_URL_RESPONSE_CHECK table.
        foreach($picUrlArray as $k=>$v)
        {
        	foreach($v as $k1=>$v1)
        	{	
        		if($k1!='PICTUREID')
        		{	
        			if($v1!="")
        			{		
        				$completeUrl = PictureFunctions::getCloudOrApplicationCompleteUrl($v1);
        				
        				$ch=curl_init();
        				curl_setopt($ch,CURLOPT_URL,$completeUrl);
        				$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,application/json,";
						$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/jpeg,*/*;q=0.9";
						curl_setopt($ch, CURLOPT_HEADER, $header);
						curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
        				curl_setopt($ch,CURLOPT_NOBODY,true);
        				curl_exec($ch);
        				$result=curl_getinfo($ch);
        				curl_close ($ch);
        			}
        			
        			
        		}

        	}
        	if($result["http_code"]=="404")
        		{
        			 $picUrlCheckObj = new PICTURE_PIC_URL_RESPONSE_CHECK();
        			 if($flag==1)
        			 {
        			 	foreach($allPicUrlArray as $value)
        			 	{
        			 		if($v[$value]!="")
        			 			$picUrlCheckObj->insertData($v["PICTUREID"],$value,$v[$value],$result["http_code"]);
        			 	}
        			 	
        			 }
        			 else
        			 {
        			 	if($v[$picUrl]!="")
        			 		$picUrlCheckObj->insertData($v["PICTUREID"],$picUrl,$v[$picUrl],$result["http_code"]);
        			 }
        			 	
        		}
        	
        }

	}
}
