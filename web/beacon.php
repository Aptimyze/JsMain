<?php

//When you have to re-run this tracking move all the db query to stores. 
return true;
if(!$_COOKIE[boomerang_nojs])
{
@setcookie("boomerang_nojs","1",time()+ (60*60*24*365),"/","");
}
else
{
	renderImage();
	die;
}


include(JsConstants::$docRoot."/profile/connect.inc");
$db=connect_db();
$html5Data = extractHtml5data($_REQUEST);
saveHtml5data($html5Data);
$css3data = extractCSSdata($_REQUEST);
saveCSS3data($css3data);
renderImage();

function extractHtml5data($array)
{	
	$resultArray = array();

	$resultArray['bandwidth'] = isset($array['bw'])?$array['bw']:"0";
	$resultArray['bandwidth_err'] = isset($array['bw_err'])?$array['bw_err']:"0";
	$resultArray['ip_address'] = isset($array['ip_address'])?$array['ip_address']:ip2long(CommonFunction::getIP());
	$resultArray['latency'] = isset($array['lat'])?$array['lat']:"0";
	$resultArray['latency_err'] = isset($array['lat_err'])?$array['lat_err']:"0";
	$resultArray['ref_url'] = isset($array['r'])?$array['r']:"0";
	$resultArray['current_url'] = isset($array['u'])?$array['u']:"0";
	$resultArray['perceived_loadtime_page'] = isset($array['t_done'])?$array['t_done']:"0"; // Perceived load time of the page.
	$resultArray['time_head_page_ready'] = isset($array['t_page'])?$array['t_page']:"0"; // Time taken from the head of the page to page_ready.
	$resultArray['time_head_page_first_byte'] = isset($array['t_resp'])?$array['t_resp']:"0"; // Time taken from the user initiating the request to the first byte of the response.
	
	$other_timers = isset($array['t_other'])?$array['t_other']:"0"; 
	$b  = explode("|", $other_timers);
	foreach ($b as $c)
	{
		if(strstr($c, 't_head'))
		{
			$tmp = explode(",", $c);
			$result['t_head'] = $tmp[0];
		}

		if(strstr($c, 't_body'))
		{
			$tmp = explode(",", $c);
			$result['t_body'] = $tmp[0];
		}
	}
	$resultArray['t_head'] = isset($result['t_head'] )?$result['t_head'] :"0";
	$resultArray['t_body'] = isset($result['t_body'] )?$result['t_body'] :"0";

	$resultArray['logged_at'] = date("Y-m-d H:i:s");
	$resultArray['session_id'] = isset($array['session_id'])?$array['session_id']:"";
	$resultArray['user_id'] = isset($array['userid'])?$array['userid']:"0";
	$resultArray['user_agent'] = isset($array['user_agent'])?$array['user_agent']:$_SERVER['HTTP_USER_AGENT'];
	$resultArray['server_p_time'] = isset($array['server_p_time'])?$array['server_p_time']:"0";
	$resultArray['hml5applicationcache'] = ($array['hml5applicationcache'] == 'true')?"1":"0";                        
	$resultArray['hml5canvas'] = ($array['hml5canvas'] == 'true')?"1":"0";    
	$resultArray['hml5frmdate'] = ($array['hml5frmdate'] == 'true')?"1":"0";
	$resultArray['hml5frmsautofocus'] = ($array['hml5frmsautofocus'] == 'true')?"1":"0";
	$resultArray['hml5geolocation'] = ($array['hml5geolocation'] == 'true')?"1":"0";
	$resultArray['hml5history'] = ($array['hml5history'] == 'true')?"1":"0";
	$resultArray['hml5localstorage'] = ($array['hml5localstorage'] == 'true')?"1":"0";
	$resultArray['hml5video'] = ($array['hml5video'] == 'true')?"1":"0";
	$resultArray['hml5webworkers'] = ($array['hml5webworkers'] == 'true')?"1":"0"; 
	$resultArray['isHTML5Site'] = isset($array['isHTML5Site'])?$array['isHTML5Site']:"0";
	$resultArray['activity_type'] = isset($array['activity_type'])?$array['activity_type']:"request";
    $resultArray['activity_type_value'] = isset($array['activity_type_value'])?$array['activity_type_value']:"";
            
	$resultArray['isHTML5Site'] = isset($array['isHTML5Site'])?$array['isHTML5Site']:"0";
	$resultArray['noscript'] = isset($array['noscript'])?$array['noscript']:"0";
	$resultArray['sw'] = isset($array['sw'])?$array['sw']:"-1";
	$resultArray['pd'] = isset($array['pd'])?$array['pd']:"-1";
	$resultArray['po'] = isset($array['po'])?$array['po']:"-1";
	return $resultArray;
    }
    function extractCSSdata($array)
    {
            $resultArray = array();
            $resultArray['logged_at'] = date("Y-m-d H:i:s");
            $resultArray['session_id'] = isset($array['session_id'])?$array['session_id']:"-1|-1";
            $resultArray['user_id'] = isset($array['userid'])?$array['userid']:"0";
            $resultArray['user_agent'] = isset($array['user_agent'])?$array['user_agent']:$_SERVER['HTTP_USER_AGENT'];
            $resultArray['mediaqueries'] = ($array['mediaqueries'] == 'true')?"1":"0";
            $resultArray['fontface'] = ($array['fontface'] == 'true')?"1":"0";
            $resultArray['backgroundsize'] = ($array['backgroundsize'] == 'true')?"1":"0";
            $resultArray['borderimage'] = ($array['borderimage'] == 'true')?"1":"0";
            $resultArray['borderradius'] = ($array['borderradius'] == 'true')?"1":"0";
            $resultArray['boxshadow'] = ($array['boxshadow'] == 'true')?"1":"0";
            $resultArray['flexbox'] = ($array['flexbox'] == 'true')?"1":"0";
            $resultArray['opacity'] = ($array['opacity'] == 'true')?"1":"0";
            $resultArray['cssanimations'] = ($array['cssanimations'] == 'true')?"1":"0";
            $resultArray['cssgradients'] = ($array['cssgradients'] == 'true')?"1":"0";
            $resultArray['cssreflections'] = ($array['cssreflections'] == 'true')?"1":"0";
            $resultArray['csstransforms'] = ($array['csstransforms'] == 'true')?"1":"0";
            $resultArray['csstransitions'] = ($array['csstransitions'] == 'true')?"1":"0";
            $resultArray['activity_type'] = isset($array['activity_type'])?$array['activity_type']:"request";
            return $resultArray;
    }
    
	function renderImage()
	{
		header( 'Content-type: image/gif' );
		// 1x1 transparent GIF
		echo chr(71).chr(73).chr(70).chr(56).chr(57).chr(97).
		chr(1).chr(0).chr(1).chr(0).chr(128).chr(0).
		chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).
		chr(33).chr(249).chr(4).chr(1).chr(0).chr(0).
		chr(0).chr(0).chr(44).chr(0).chr(0).chr(0).chr(0).
		chr(1).chr(0).chr(1).chr(0).chr(0).chr(2).chr(2).
		chr(68).chr(1).chr(0).chr(59);
	}
	
	function saveHtml5data($array)
	{
		$sql = "INSERT IGNORE INTO MIS.MOBILE_ACTIVITY_LOG(session_id,user_id,activity_type,activity_type_value,user_agent,current_url,ref_url,perceived_loadtime_page,time_head_page_ready,boomr_pageid,t_head,t_body,server_p_time,bandwidth,bandwidth_err,latency,latency_err,hml5applicationcache,hml5canvas,hml5frmdate,hml5frmsautofocus,hml5geolocation, hml5history,hml5localstorage,hml5video,hml5webworkers,ip_address,logged_at,noscript,SW,PD,PO) VALUES ('$array[session_id]',$array[user_id],'$array[activity_type]','$array[activity_type_value]','$array[user_agent]','$array[current_url]','$array[ref_url]',$array[perceived_loadtime_page],$array[time_head_page_ready],'$array[boomr_pageid]',$array[t_head],$array[t_body],$array[server_p_time],$array[bandwidth],$array[bandwidth_err],$array[latency],$array[latency_err],$array[hml5applicationcache],$array[hml5canvas],$array[hml5frmdate],$array[hml5frmsautofocus],$array[hml5geolocation], $array[hml5history],$array[hml5localstorage],$array[hml5video],$array[hml5webworkers],$array[ip_address],'$array[logged_at]',$array[noscript],'$array[sw]','$array[pd]','$array[po]')";
		
		mysql_query_decide($sql);// or die("2 $sql".mysql_error_js());
	}
	
	function saveCSS3data($array)
	{
		$sql = "INSERT IGNORE INTO MIS.MOBILE_ACTIVITY_CSSLOG(session_id,user_id,activity_type,user_agent,mediaqueries,fontface,borderimage,borderradius,boxshadow,flexbox,opacity,cssanimations,cssgradients,cssreflections,csstransforms,csstransitions,logged_at) VALUES ('$array[session_id]',$array[user_id],'$array[activity_type]','$array[user_agent]',$array[mediaqueries],$array[fontface],$array[borderimage],$array[borderradius],$array[boxshadow],$array[flexbox],$array[opacity],$array[cssanimations],$array[cssgradients],$array[cssreflections],$array[csstransforms],$array[csstransitions],'$array[logged_at]')";
		
		mysql_query_decide($sql);// or die("2 $sql".mysql_error_js());
	}
?>
