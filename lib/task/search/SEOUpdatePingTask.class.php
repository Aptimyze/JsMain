<?php
/*
 * Author: Smarth Katyal
 * This cron is used to send update ping GET request to Google's CDN to update its cache when AMP pages are updated.
*/
class SEOUpdatePingTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('URL1', sfCommandArgument::OPTIONAL, 'My argument'),
            ));
	$this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	    ));

	    $this->namespace        = 'cron';
	    $this->name             = 'SEOUpdatePing';
	    $this->briefDescription = 'UpdatePing to update AMP cached pages';
	    $this->detailedDescription = <<<EOF
            This cron sends update ping GET request to google to cache the fresh amp page
            Call it with:

            [php symfony cron:SEOUpdatePing ALL/URL1] 
            Pass the argument as ALL if all the URLs need to be updated or provide URL1 from newjs.SEO table if particular URL to be updated.
            Example url: "/matrimonials/arora-matrimonial/?amp=1"
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $url1 = $arguments["URL1"];
        $url1 = $this->replaceAllSpaces($url1);
        $startTime = time();
        print_r("CRON Start Time: ". $startTime . "\n");
        if($url1 && $url1 != 'ALL'){
            $url1 = "https://cdn.ampproject.org/update-ping/c/jeevansathi.com". url1;
            $ch = curl_init($url1);
            curl_setopt($ch, CURLOPT_HEADER, false);    // we dont want headers
            curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);       //Setting timeout as 5 seconds
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            print_r("URL: " . $url1. " :: ");
            print_r("HTTP Code:" . $httpcode . "\n");
            curl_close($ch);
        }
        else{	
            $seoObj = new newjs_SEO();
            $url1Array = $seoObj->getURL1List();
            foreach ($url1Array as $url1 => $detail){
                $url = $this->replaceAllSpaces($detail);
                if($url){
                    $url1 = "https://cdn.ampproject.org/update-ping/c/jeevansathi.com". $url . "?amp=1";
                    $ch = curl_init($url1);
                    curl_setopt($ch, CURLOPT_HEADER, false);    // we dont want headers
                    curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
                    curl_setopt($ch, CURLOPT_HTTPGET, true);     //setting request as GET
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);        //Connection timeout as 5 seconds
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);       //Setting read timeout as 5 seconds
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
                    $output = curl_exec($ch);
                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    print_r("URL: " . $url1. " :: ");
                    print_r("HTTP Code:" . $httpcode . "\n");
                    curl_close($ch);
                }
            }
	}
        $endTime = time();
        print_r("CRON End Time: ". $endTime. "\n");
        print_r("Total Time: ". ($endTime - $startTime) . "seconds");
    }

    /*
    * replace multiple spaces if present.
    */
    public function replaceAllSpaces($pid)
    {
	return str_replace(' ','',$pid);
    }
}
