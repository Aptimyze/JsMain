<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include ("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

	function get_data($username,$password)
	{
		$fp=fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20REQUESTCREDIT%20SYSTEM%20%22http://127.0.0.1/psms/dtd/requestcredit.dtd%22%20%3E%3CREQUESTCREDIT%20USERNAME=%22$username%22%20PASSWORD=%22$password%22%3E%3C/REQUESTCREDIT%3E&action=credits","rb");
		if(!$fp)
		{
			echo "cannot open url";
			exit;
		}

		$response = '';
                while (!feof($fp))
                {
                        $response.= fread($fp, 4096);
                }
                fclose($fp);

		return $response;
	}

	function parse_sms_xml($response)
	{	
		$dom = new DOMDocument();
		$dom->loadXML($response);
		$credits = $dom->getElementsByTagName('Credit')->item(0)->attributes->getNamedItem("Limit")->nodeValue;
		$used = $dom->getElementsByTagName('Credit')->item(0)->attributes->getNamedItem("Used")->nodeValue;

		return ($credits-$used);
	}

	$accounts[]=array("NAME" => "Priority","USERNAME" => "naukari","PASSWORD" => "na21s8api");
	$accounts[]=array("NAME" => "Promotional","USERNAME" => "jeevansathi","PASSWORD" => "jsapi1103");
	$accounts[]=array("NAME" => "Scrubbing","USERNAME" => "naukriscrub","PASSWORD" => "nauk05scub09");

	for($i=0;$i<count($accounts);$i++)
	{
		$response=get_data($accounts[$i]["USERNAME"],$accounts[$i]["PASSWORD"]);
		$balance=parse_sms_xml($response);

		$str.="<br>Balance in " . $accounts[$i]["NAME"] . " account = $balance. ";

		if($balance<=200000 && $accounts[$i]["NAME"]!="Scrubbing")
			$str.="Need to re-charge. ";
	}

	send_email("vikas@jeevansathi.com,siddharth.chaturvedi@naukri.com,lijuv@naukri.com,rizwan@naukri.com,shakti.singh@naukri.com",$str,"SMS credits");
?>
