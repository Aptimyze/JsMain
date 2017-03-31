<?php

/**
 * This task gets all the profiles for which EOI needs to be sent and send EOI on their behalf.
 * 
 * @package    jeevansathi
 * @author     Hemant Agrawal
 */
class WapSiteMapNewTask extends sfBaseTask
{
	private $errorMsg;
	public function Showtime($mes)
	{
		$time=time();
		echo "\n---$mes-->".($time-$this->showTime);
		$this->showTime=$time;
	}
  protected function configure()
  {
	  $this->showTime=time();
$this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
     ));
    $this->namespace        = 'cron';
    $this->name             = 'WebSiteMapNew';
    $this->briefDescription = 'Web site map';
    $this->detailedDescription = <<<EOF
The [WebSiteMap|INFO] task does things.
Call it with:

  [php symfony WebSiteMapNew|INFO]
EOF;

//Parameters that are passed as arguments.
	$this->addOptions(array(
    new sfCommandOption('daily', null, sfCommandOption::PARAMETER_REQUIRED, ''),
    new sfCommandOption('mobile', null, sfCommandOption::PARAMETER_REQUIRED, '')
));
  }
	/**
    * set the array errorTypeArr value for the given type
    * @return void
    * @access protected
    */	
	protected function execute($arguments = array(), $options = array())
	{	
		ini_set("memory_limit","-1");
		ini_set('max_execution_time','0');
		$this->Showtime('start');
		
		sfContext::createInstance($this->configuration);
		
		$this->limit=24000;
		$this->daily=$options[daily]?1:0;
		$this->mobile=$options[mobile]?1:0;
		
		$domtree = new DOMDocument('1.0', 'UTF-8');
		$domElement=$this->createRootXML($domtree,"new-sitemapindex");

		if(!$this->daily)
		{
			//Static Pages
			$innerDomEle=$domtree->createElement('new-sitemap');
			$domElement->appendChild($innerDomEle);
			$this->updateMainSiteMap($domtree,$innerDomEle,1);
			$this->setStaticPages();
			$this->IncrementLastIndex();
		
			//Normal community pages.
			$innerDomEle=$domtree->createElement('new-sitemap');
			$domElement->appendChild($innerDomEle);
			$this->updateMainSiteMap($domtree,$innerDomEle,.8);
			$this->setCommunity("N",.8);
			$this->IncrementLastIndex();
			
			//Bride community pages
			$innerDomEle=$domtree->createElement('new-sitemap');
			$domElement->appendChild($innerDomEle);
			$this->updateMainSiteMap($domtree,$innerDomEle,.7);
			$this->setCommunity("B",.7);
			$this->IncrementLastIndex();
			
			//Groom Community pages
			$innerDomEle=$domtree->createElement('new-sitemap');
			$domElement->appendChild($innerDomEle);
			$this->updateMainSiteMap($domtree,$innerDomEle,.7);
			$this->setCommunity("G",.7);
			$this->IncrementLastIndex();
			//$domElement->appendChild($d);
		}
		
		$this->GetAllProfiles("F",$domElement,$domtree);
		$this->GetAllProfiles("M",$domElement,$domtree);
		
		$this->WriteSiteMap($domtree,0);
		
		if($this->daily)
			$this->PingGoogle();
		else
			SendMail::send_email("vivek.rathore@naukri.com,anirban.das@naukri.com,nikhil.dhiman@jeevansathi.com","Jeevansathi.com Sitemap has been Submitted ".date("Y-m-d"),"Jeevansathi.com Sitemap has been Submitted");	
			
		//echo $domtree->saveXML();die;
		// if script completes successfully send mail
		//SendMail::send_email("nikhil.dhiman@jeevansathi.com,hemant.a@jeevansathi.com","$totalContactsMade Auto Contacts sent out for $totalSenders users","Auto Contacts cron completed");
		
		
	}
	
	private function PingGoogle()
	{
		$url=$this->ParentXmlName(1);
		$ping=$this->urlExists($url);
		while(!$ping)
		{
			sleep(10);      //Make a delay of 10 second for another request for submission
			$ping=$this->urlExists($url);
			if($ping_count>=10)
			{
				SendMail::send_email("vivek.rathore@naukri.com,anirban.das@naukri.com,nikhil.dhiman@jeevansathi.com","Daily sitemap of Jeevansathi.com not submiited on ".date("Y-m-d"),"Alert: Jeevansathi.com Daily Sitemap mobile has not been Submitted. Kindly Check.");
				$ping=1;
			}
			$ping_count++;
		}
		
		if($ping)
			SendMail::send_email("vivek.rathore@naukri.com,anirban.das@naukri.com,nikhil.dhiman@jeevansathi.com","Jeevansathi.com Daily Sitemap mobile has been Submitted ".date("Y-m-d"),"Jeevansathi.com Daily Sitemap mobile has been Submitted");
		
	}
	private function setStaticPages()
	{
		$domtree = new DOMDocument('1.0', 'UTF-8');
		
		$domElement=$this->createRootXML($domtree,"urlset");
		$res[]=array("URL"=>"","PRIORITY"=>1);
		
		
		if($this->mobile)
		{
			$res[]=array("URL"=>"/jsmb/register.php","PRIORITY"=>1);
			$res[]=array("URL"=>"/jsmb/jsmb_forgotpassword.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/profile/faq_other.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/search/topSearchBand?isMobile=Y","PRIORITY"=>.5);
			$res[]=array("URL"=>"/profile/disclaimer.php","PRIORITY"=>.5);
			
		}
		else
		{	
			$res[]=array("URL"=>"/profile/mainmenu.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/profile/registration_new.php","PRIORITY"=>1);
			
			$res[]=array("URL"=>"/profile/advance_search.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/success/success_stories.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/profile/mem_comparison.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/profile/site_map.php","PRIORITY"=>.5);
			
			$res[]=array("URL"=>"/profile/forgotpassword.php","PRIORITY"=>.5);
			
			$res[]=array("URL"=>"/profile/faq_main.php","PRIORITY"=>.5);
			$res[]=array("URL"=>"/profile/contact.php","PRIORITY"=>.5);
		}	
		
		
		for($i=0;$i<count($res);$i++)
		{
			$domEle=$domtree->createElement('url');
			$domElement->appendChild($domEle);
			$this->UpdateXML($domtree,$domEle,$res[$i][URL],$res[$i][PRIORITY]);
			
		}
		$this->WriteSiteMap($domtree);
		
		
	}
	/**
	 * Write to disk, 
	 * @param $domtree domElement
	 * @param @whichOne int [0->parent|1-> childs]
	 */
	private function WriteSiteMap($domtree,$whichOne=1)
	{
		$fname=$this->ParentXmlName();
		if($whichOne)
				$fname=$this->SubXmlName();
		$fhandle=fopen($fname,"w+");		
		if($whichOne)
		{		
			
		
			//$gzdata = gzencode($domtree->saveXML());
			$gzdata=$domtree->saveXML();
		}
		else
				$gzdata=$domtree->saveXML();
				
			fwrite($fhandle, $gzdata);
			fclose($fhandle);
			if($whichOne)
			{
				passthru("gzip -f $fname");
			}
			
	}
	private function GetAllProfiles($gender,$domElement,$domtree)
	{
		
		$end_dt=date("Y-m-d 23:59:59", time()-86400);
		$st_dt=date("Y-m-d 00:00:00", time()-86400*3);
		$st_dt_2=date("Y-m-d 23:59:59", time()-86400*4);
		if($gender=="M")
			$obj=new NEWJS_SEARCH_MALE;
		else
			$obj=new NEWJS_SEARCH_FEMALE;
		
		if($this->daily)
			$results=$obj->getDailyProfiles($st_dt,$end_dt);
		else
			$results=$obj->getProfiles($st_dt_2);
		
		$cnt=ceil(count($results)/$this->limit);
		
		for($i=0;$i<$cnt;$i++)
		{
			$index=($i*$this->limit);
			
		//Section profile pages
		$innerDomEle=$domtree->createElement('new-sitemap');
		$domElement->appendChild($innerDomEle);
		$this->updateMainSiteMap($domtree,$innerDomEle,.5);
		$this->setProfiles($index,$results);
		$this->IncrementLastIndex();
		}
			
		
	}
	
		private function updateMainSiteMap($domtree,$dom,$priority=.5)
		{
			$file=	$this->SubXmlName(1);
			$loc=$domtree->createElement('loc',$file);
			$dom->appendChild($loc);
			$date=date("c");
			$dateEle1=$domtree->createElement('lastmod',$date);
			$dom->appendChild($dateEle1);
//			$dateEle2=$domtree->createElement('priority',$priority);
//			$dom->appendChild($dateEle2);
			if($this->IsMobileXML() && false)
			{
				$dateEle3=$domtree->createElement("mobile:mobile");
				$dom->appendChild($dateEle3);
			}	
			
		}

	/** logs sfException
	@param $ex Exception Obj
	*/
	private function setExceptionError($ex)
	{
		$this->errorMsg=" ".$ex->getMessage();
		$this->Showtime("Error ".$this->errorMsg);
	}
	private function setProfiles($index,$profiles)
	{
		
		$domtree = new DOMDocument('1.0', 'UTF-8');
		
		$domElement=$this->createRootXML($domtree,"urlset");
		
		$tempcnt=count($profiles)-$index;
		if($tempcnt>$index)
				$tempcnt=$this->limit;
				
		for($i=0;$i<$this->limit;$i++)
		{
			
		  $pid=$profiles[$index][PROFILEID];
		$index++;
			if($pid)
			{
				$profileObj=new LoggedInProfile("newjs_slave");
				//$add["activatedKey"]=1;
				$profileObj->getDetail($pid, "PROFILEID", "MOD_DT,USERNAME,MTONGUE,RELIGION,CASTE,GENDER,PROFILEID");
				
				
				
				if($profileObj->getUSERNAME())
				{
					$url="/".CommonUtility::CanonicalProfile($profileObj);
					$domEle=$domtree->createElement('url');
					$domElement->appendChild($domEle);
				
					$this->UpdateXML($domtree,$domEle,$url,".5");
				}
			}
			else
			break;
		}
		//echo JsConstants::$docRoot."/sitemap".$this->getLastIndex().".xml";
		$this->WriteSiteMap($domtree);	
		
	}
	private function setCommunity($pageSource,$priority)
	{
		
		$domtree = new DOMDocument('1.0', 'UTF-8');
		
		$domElement=$this->createRootXML($domtree,"urlset");
		
		$cObj=new NEWJS_COMMUNITY_PAGES("newjs_slave");
		$cmObj=new NEWJS_COMMUNITY_PAGES_MAPPING("newjs_slave");
		$res=array_merge($cObj->getURLS($pageSource),$cmObj->getURLS($pageSource));
	
	
		
		for($i=0;$i<count($res);$i++)
		{
			$domEle=$domtree->createElement('url');
			$domElement->appendChild($domEle);
			$this->UpdateXML($domtree,$domEle,$res[$i][URL],$priority);
			
		}
		$this->WriteSiteMap($domtree);
		//die;
		//echo $domtree->saveXML();die;
		
	}
	private function UpdateXML($domtree,$domEle,$url,$priority,$changefreq='weekly')
	{
		
		$domEle->appendChild($domtree->createElement("loc",JsConstants::$ssl_siteUrl.$url));
		$domEle->appendChild($domtree->createElement("lastmod",date("c")));
		$domEle->appendChild($domtree->createElement("priority",$priority));
		$domEle->appendChild($domtree->createElement("changefreq",$changefreq));
		if($this->IsMobileXML())
			$domEle->appendChild($domtree->createElement("mobile:mobile"));
		return $domEle;
	}
	private function IsMobileXML()
	{
		if($this->mobile)
				return true;
	}
	private function ParentXmlName($webpath=0)
	{
		$arr[0][0]="new-sitemap_index.xml";
		$arr[0][1]="new-sitemap_index_daily.xml";
		$arr[1][0]="new-sitemap_index_mobile.xml";
		$arr[1][1]="new-sitemap_index_mobile_daily.xml";
		$path=JsConstants::$docRoot;
		if($webpath)
		$path=JsConstants::$ssl_siteUrl;
		return $path."/".$arr[$this->mobile][$this->daily];
		
	}
	private function SubXmlName($webpath=0)
	{
		$arr[0][0]="new-sitemap".$this->getLastIndex().".xml";
		$arr[0][1]="new-sitemap".$this->getLastIndex()."-daily.xml";
		$arr[1][0]="new-sitemap_mobile".$this->getLastIndex().".xml";
		$arr[1][1]="new-sitemap_mobile".$this->getLastIndex()."-daily.xml";
		$path=JsConstants::$docRoot;
		if($webpath)
		{
			$path=JsConstants::$ssl_siteUrl;
			return $path."/xmlnew/".$arr[$this->mobile][$this->daily].".gz";
		}
		return $path."/xmlnew/".$arr[$this->mobile][$this->daily];
	}
		private function getLastIndex()
	{
		if(!$this->currentIndex)
				$this->currentIndex=1;
		return $this->currentIndex;
	}
	private function IncrementLastIndex()
	{
		$this->currentIndex++;
	}
	private function createRootXML($domtree,$mainXML)
	{
		$domElement = $domtree->createElement($mainXML);
		
		$domAttribute=$domtree->createAttribute("xmlns");
		$domAttribute->value="https://www.sitemaps.org/schemas/sitemap/0.9";
		
		$domElement->appendChild($domAttribute);
		
		
		if($this->IsMobileXML())
		{
			//$domElement=$domtree->createElement("xmlns:mobile");
			$domAttribute2=$domtree->createAttribute("xmlns:mobile");
			$domAttribute2->value="https://www.google.com/schemas/sitemap-mobile/1.0";
			$domElement->appendChild($domAttribute2);
			
		}
			$domtree->appendChild($domElement);
		return $domElement;
	}
	private function urlExists($url)
	{
			if($url==NULL)
					return false;
			$url="www.google.com/webmasters/tools/ping?sitemap=".urlencode($url);	
			//echo $url;return 1;	
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if($httpcode>=200 && $httpcode<300)
			{
							return true;
			}
			else
			{
							return false;
			}
	}

}
