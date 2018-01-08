<?php
	include("profile/config.php");
	include_once("correct_file_name.php");
	$arr=explode('?',$_SERVER['REQUEST_URI']);
	$script_name=$arr[0];
	if(substr($script_name,-3)!=".js")
	{
		header('HTTP/1.0 403 Forbidden');
		exit;
	}

	//Getting real name of js file
	$script_name=convert_files_name($script_name,"js");
	$file=$_SERVER['DOCUMENT_ROOT'] . $script_name;
		
	if(!file_exists($file))
	{
		header('HTTP/1.0 404 Not Found');
		exit;
	}

	$content=file_get_contents($file);

	header("Content-type: application/x-javascript");
        //to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

        header("Cache-Control: private");
	$if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
        $mtime = filemtime($file);

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
        if(!$IMG_URL) $IMG_URL = sfConfig::get('app_img_url');

	echo str_replace('IMG_URL',$IMG_URL,$content);		
?>
