<?
function get_clicktale_footer() 
{
	$pId=30973;
	$ratio=0.0025;
	$clickUrl='http://s.clicktale.net/WRb.js';
	$current_url = get_base_url();
	$sid = session_id() ;
	/*
	if(!$sid)
		session_start();
	$sid = session_id() ;
	*/
	//if the session id is set, and not included in the current URL,
	//append it to the current URL
	if( !strstr( $current_url, session_name().'=$sid' ) && !empty($sid) ) 
	{
		$fetch_from = "ClickTaleFetchFrom='$current_url" ;
		if( strstr( $current_url, '?' ) )
		$fetch_from .= "&" ;
		else
		$fetch_from .= "?" ;
		$fetch_from .= session_name()."=$sid';" ;
	}
	else
		$fetch_from = "";

	return "<!-- ClickTale Bottom part -->
	<div id='ClickTale' style='display: none;'></div>
	<script src='$clickUrl/WRb.js' type='text/javascript'></script>
	<script type='text/javascript'>
	if(typeof ClickTale=='function') {
	$fetch_from 
	ClickTale($pId,$ratio);
	}
	</script>
	<!-- ClickTale end of Bottom part -->" ;  // update your values here based on the script generated. 
	// Note the parameters to ClickTale call and the url. Keep other syntax and the line "$fetch_from" as-is.
}

function get_base_url() 
{
	if( $_SERVER['SERVER_PORT'] == "443" )
		$abs_path = "https://" ;
	else
		$abs_path = "http://" ;

	$abs_path .= $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']; //$_SERVER["REQUEST_URI"] ;
	if( !empty($_SERVER['QUERY_STRING']) )
		$abs_path .= '?' . $_SERVER['QUERY_STRING'];
	return $abs_path ;
}    

function get_clicktale_header() 
{
	return "<!-- ClickTale Top part -->
	<script type='text/javascript'>
	var WRInitTime=(new Date()).getTime();
	</script>
	<!-- ClickTale end of Top part -->" ;
}

//$smarty->assign("clicktaleFoot",get_clicktale_footer());
//$smarty->assign("clicktaleHead",get_clicktale_header());
echo get_clicktale_header(); // call after <BODY>
//echo ":::::::::::::::::::::::::";
//echo get_clicktale_footer(); // call before </BODY>
?>
