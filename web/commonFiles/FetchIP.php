<?php
if(!function_exists("FetchClientIP"))
{
  /*
   * FetchClientIP  :Retreives Client IP
   * 
   * @return IP or Empty String
   * @param void
   */
function FetchClientIP()
{
	$ip = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
  
	$ip=trim(str_replace(" ","",$ip));
	if($ip)
	{
		$ip_new = explode(",",$ip);
		preg_match('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $ip_new[0], $matches);
		
    if(count($matches)>0){//Check for IPv4 through Regex
			return $matches[0];
    }
    else if(inet_pton($ip_new[0])){//Check for IPv6 & IPv4
      return $ip;
    }
	}
	return "";
}
}
?>
