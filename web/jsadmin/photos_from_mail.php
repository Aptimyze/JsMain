<?php
//script to parse the mails in photos@jeevansathi.com
 
$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;

include(JsConstants::$docRoot."/jsadmin/connect.inc");
include(JsConstants::$docRoot."/jsadmin/lock.php");
include_once(JsConstants::$docRoot."/profile/SymfonyPictureFunctions.class.php");


$lock=get_lock(__FILE__);
$MDpath="mimeDecode.php";
//$Pearpath=JsConstants::$pearPath;
//ini_set("include_path", "$Pearpath");
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);

include ("$MDpath");

$hdMail=JsConstants::$imageMail;
$server_name="mail.infoedge.com";
$popHost="mail.infoedge.com";
$popPort=110;
$popUser=JsConstants::$imageMailUser;
$popPass=JsConstants::$imageMailPassword;
$popConn=0;
$gotMail=false;
$socket ="Off";
global $subject,$from,$description,$attachment,$path;

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

if(popConnect())
{
	getMessages();
}
function getMessages()
{
        global $popHost, $popPort, $popConn, $gotMail, $subject, $from, $description, $attachment,$path;

        $numMessages= checkForMail();

	$path=sfConfig::get("sf_upload_dir")."/MailImages";
        if ($gotMail)
        {
                for ($i= 0; $i < $numMessages && $i<=30; $i++)
	        {
			echo "\n".$i. " " . $numMessages;
                      	$attachment=array();
                        $from="";
                        $subject="";
                        $description="";
			$email= getmail($i +1);
                        $msg= mimeDecode($email);
                        if (isset ($msg))
                        {
                            getfromsub($msg);
                            getTicketContent($msg);
                        }
			echo " Writing Record.";
                        writeRecord();
			deleteMessage($i +1);
                }

        }
}
popDisconnect();
release();

function getfromsub($msg)
{
	global $from,$subject;
        $head= $msg->headers;
        $from= getFrom($head);
        $uname= explode("@", $from);
        $subject= textCleaner($head['subject']);

}
function getTicketContent($msg)
{
        global $description, $time,$noThreads,$attachment;
        if(isset($msg->parts) && is_array($msg->parts))
        foreach ($msg->parts as $part)
        {
                if (($part->ctype_primary == 'text') and ($part->ctype_secondary == 'plain'))
                {
                        $description= $part->body;
                }
                if ($part->ctype_primary == 'image')
                {
                        $enc= $part->headers['content-transfer-encoding'];
                        $fileName= fileNameCleaner("image.".$part->ctype_secondary);
                        //$fileDest= $path."/".$attach."/".$fileName;
                        $file=writeFile($enc,$part->body);
                        $attachment[]= array ("filename" => $fileName, "filetype" => $part->ctype_primary."/".$part->ctype_secondary,"content" => $file);
			
                }
		 elseif (isset ($part->disposition) and ($part->disposition == 'attachment'))
                {
                        if (isset ($part->ctype_parameters['filename']))
                        {
                                $fileName= $part->ctype_parameters['filename'];
                        }
                        elseif (isset ($part->ctype_parameters['name']))
                        {
                                $fileName= $part->ctype_parameters['name'];
                        }
                        else
                        {
                                $fileName= "not_named";
                        }
                        $enc= $part->headers['content-transfer-encoding'];
                        $fileName= fileNameCleaner($fileName);
                        //$fileDest= $path."/".$attach."/".$fileName;
                        $file=writeFile($enc, $part->body);
                        $attachment[]= array ("filename" => $fileName, "filetype" => $part->ctype_primary."/".$part->ctype_secondary,"content" => $file);
                }
                elseif (isset ($part->disposition) and ($part->disposition == 'inline'))
                {
                        if (isset ($part->ctype_parameters['filename']))
                        {
                                $fileName= $part->ctype_parameters['filename'];
                        }
                        elseif (isset ($part->ctype_parameters['name']))
                        {
                                $fileName= $part->ctype_parameters['name'];
                        }
			   else
                        {
                                $fileName= "not_named";
                        }
                        $enc= $part->headers['content-transfer-encoding'];
                        $fileName= fileNameCleaner($fileName);
                        //$fileDest= $path."/".$fileName;
                        $file=writeFile($enc,$part->body);
                        $attachment[]= array ("filename" => $fileName, "filetype" => $part->ctype_primary."/".$part->ctype_secondary,"content" => $file);
			//continue;
                }
                if (isset ($part->parts) and (is_array($part->parts)))
                {
                 	getTicketContent($part);
                }
        }
        
        $description= textCleaner($description);
	
}
function fileNameCleaner($fileName)
{
        $mtime= explode(" ", microtime());
        $mtime= $mtime[1].substr($mtime[0], 5, 3);

        $fileName= preg_replace('/[^a-zA-Z0-9\.\$\%\'\`\-\@\{\}\~\!\#\(\)\&\_\^]/', '', str_replace(array (' ', '%20'), array ('_', '_'), $fileName));
        $fileName= str_replace("'", "", $fileName);
        $fileName= $mtime."_".$fileName;

        return $fileName;

}
function writeFile($enc,$body)
{
        
        if ($enc == 'base64')
        {
                $file=base64_decode($body);
	}
        else
        {
                $file= $body;
	}
        return $file;
}

function buildTicket($msg)
{
        global $time;
        $head= $msg->headers;
        $from= getFrom($head);
        $uname= explode("@", $from);
        $subject= textCleaner($head['subject']);


        if ($msg->ctype_primary == "text")
        {
                $tkt['Description']= $msg->body;
        }

        return $tkt;
}
function textCleaner($text)
{
        $text= str_replace("'", "", $text);
        $text= str_replace("=20", "\n", $text);
        return $text;
}

function getFrom($head)
{
	$regex= '\<*[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,6})\>*';
       /* if (array_key_exists('reply-to', $head) and ereg($regex, $head['reply-to']))
        {
                $from= $head['reply-to'];
        }
        elseif (array_key_exists('return-path', $head) and ereg($regex, $head['return-path']))
        {
                $from= $head['return-path'];
        }
        elseif (array_key_exists('from', $head) and ereg($regex, $head['from']))
        {
                $from= $head['from'];
        }*/
	$from=$head['from'];
        $from= ereg_replace("^(.*)<", "", $from);
        $from= str_replace(array ("<", ">", " "), "", $from);

        /*if (array_key_exists('cc', $head) and ereg($regex, $head['cc'])){
                $cc = $head['cc'];
        }*/
	$cc=$head['cc'];
        $cc= ereg_replace("^(.*)<", "", $cc);
        $cc= str_replace(array ("<", ">", " "), "", $cc);

        return $from.",".$cc;
}

/* function popDisconnect - Disconnects the pop3 session. */
function popDisconnect()
{
        global $popConn;
        fputs($popConn, "QUIT\r\n");
        $output= fgets($popConn, 128);
        print "$output\n";
        fclose($popConn);
        $popConn= 0;
}

function mimeDecode($email)
{
        $p['include_bodies']= true;
        $p['include_headers']= true;
        $p['decode_headers']= true;
        $p['crlf']= "\r\n";
        $p['input']= $email;

        $msg= Mail_mimeDecode :: decode($p);
        return $msg;
}
function checkForMail()
{
        global $popHost, $popPort, $popConn, $gotMail;

        fputs($popConn, "STAT\r\n");
        $output= fgets($popConn, 128);
        $ack= strtok($output, " "); // Bleed off +OK
        $numMessages= strtok(" "); // Get what we wanted

        print "Ack: $ack, Num Messages: $numMessages \n";

        if ($numMessages > 0)
        {
                print "***New mail***\n";
                $gotMail= true;
        }
        else
        {
                print "***No mail***\n";
                $gotMail= false;
        }
        return $numMessages;
}
function popConnect()
{
        global $popHost, $popPort, $popUser, $popPass, $popConn;

        $popConn= fsockopen($popHost, $popPort, $errno, $errstr, 30);
        if (!$popConn)
        {
                print "Connect Failed: $errstr($errno)\n";
                return 0;
        }
        else
        {
                $output= fgets($popConn, 128);
                print "$output\n";
                fputs($popConn, "USER $popUser\r\n");
                $output= fgets($popConn, 128);
                print "$output\n";
                fputs($popConn, "PASS $popPass\r\n");
                $output= fgets($popConn, 128);
                print "$output\n";
                return 1;
        }
}

function getmail($num)
{
        global $popConn;
        $message= "";
        fputs($popConn, "RETR $num\r\n");

        $output= fgets($popConn, 512);
        if (strtok($output, "+OK"))
        {
                while (!ereg("^\.\r\n", $output))
                {
                        $output= fgets($popConn, 512);
                        $message .= $output;
                }
                return $message;
        }
}
function deleteMessage($message)
{
        global $popConn;
        fputs($popConn, "DELE $message\r\n");
	echo " deleted $message";
}
function writeRecord()
{
	global $attachment,$from,$description,$subject,$path;
	$message=str_replace("=0A","\n",$description);
	$message = str_replace("*","",$message);
	$subject = str_replace("*","",$subject);
        $now=date("Y-m-d H:i:s");
	/*$message=ereg_replace("=0A","\n",str_replace("\"","'",$description)) ;
	$message=nl2br($message);*/
	$from=substr($from,0,strlen($from)-1);
	$attachexe=0;
	foreach($attachment as $key=>$value)
	{
		  if((strstr($value["filetype"],"pif"))||(strstr($value["filetype"],"scr"))||(strstr($value["filetype"],"eml")))                            
                  continue;
                  if(strstr($value["filetype"],"image")|| strstr($value["filetype"],"msword"))
		  { 
                  	$attachexe=1;
			break;
		  }
	}
	
	if($attachexe==1) 
		$sql="INSERT INTO jsadmin.PHOTOS_FROM_MAIL(SENDER,MESSAGE,SUBJECT,DATE,ATTACHMENT) values('$from','$message','$subject','$now','Y')";
	else 
		$sql="INSERT INTO jsadmin.PHOTOS_FROM_MAIL(SENDER,MESSAGE,SUBJECT,DATE) values('$from','$message','$subject','$now')";
	mysql_ping_js();
	$result=mysql_query_decide($sql) or release('1');
	$id=mysql_insert_id_js();

		
	foreach($attachment as $key=>$value)
	{
			if((strstr($value["filetype"],"pif"))||(strstr($value["filetype"],"scr"))||(strstr($value["filetype"],"eml")))
			{
				$flag=false;
				continue;
			}
			if(strstr($value["filetype"],"image"))
			$flag=true;
			elseif(strstr($value["filetype"],"msword"))
			$flag=true;
			else
			{
				$flag=false;		
				continue;
			}
			if($flag)
			{
				$name=$value["filename"];
				$content=$value["content"];
				$type=addslashes($value["filetype"]);
				$fp=fopen("$path/$name","wb");
				if($fp)
                		{
                        		fwrite($fp,$value["content"]);
			      		fclose($fp);
					unset($fp);
					$size=filesize("$path/$name");
				}
				if(strstr($type,"image"))
				{
					$i= strpos($name,".");
					$usename=substr($name,0,$i);
					$usename.=".jpg";
					$type="image/jpeg";
					passthru("/usr/bin/convert -quality 85 $path/".$name." $path/".$usename."");
					passthru("rm $path/$name");
				}
				else
				$usename=$name;
				//$usecontent=addslashes($content);	
				$usecontent='';
				$sql="INSERT INTO jsadmin.PHOTO_ATTACHMENTS(MAILID,FILENAME,CONTENT,FILETYPE) values('$id','$usename','$usecontent','$type')";
				mysql_ping_js();
                                $result=mysql_query_decide($sql) or release('1');
				unset($usename);
				unset($usecontent);
			}	
			
	}
}
function release($mysql="")
{
	global $lock;
	if($mysql==1)
	echo mysql_error_js();
	release_lock($lock);
	successfullDie();
}
?>
