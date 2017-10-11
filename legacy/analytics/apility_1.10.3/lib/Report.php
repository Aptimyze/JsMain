<?php
	function createGenericReportXml(
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array()
	) {
		// client email xml parameters
		$clientEmailsXml = "";
		if (sizeOf($clientEmails) > 0) {
			// we are expecting client emails like this:
			// array("someone@somewhere.xyz", "anyone@anywhere.xyz")
			foreach($clientEmails as $clientEmail) {
				$clientEmailsXml .= "<clientEmails>".trim($clientEmail)."</clientEmails>";
			}
		}

		// prepare soap parameters
		if ($isCrossClient) $isCrossClient = "true"; else $isCrossClient = "false";

		$genericReportXml = "<name>".$name."</name>
											 <aggregationType>".$aggregationType."</aggregationType>
											 <startDay>".$startDay."</startDay>
											 <endDay>".$endDay."</endDay>
											 <crossClient>".$isCrossClient."</crossClient>"
											 .$clientEmailsXml;
		return $genericReportXml;
	}

	function getKeywordReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $adWordsType = "",
	  $campaigns = array(),
	  $keywordStatuses = array(),
	  $keywordType = "",
	  $includeZeroImpression = false
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$keywordStatusesXml = "";
		if (sizeOf($keywordStatuses) > 0) {
			foreach($keywordStatuses as $keywordStatus) {
				$keywordStatusesXml .= "<keywordStatuses>".trim($keywordStatus)."</keywordStatuses>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .= $campaignsXml.$keywordStatusesXml;
		if ($keywordType) $reportSpecificXml .=
		  "<keywordType>".$keywordType."</keywordType>";
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		if ($includeZeroImpression) {
		  $includeZeroImpression = "true";
		}
		else {
		  $includeZeroImpression = "false";
		}
		$reportSpecificXml .=
		  "<includeZeroImpression>".$includeZeroImpression."</includeZeroImpression>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "KeywordReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getUrlReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $adWordsType = "",
	  $campaigns = array()
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .= $campaignsXml;
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "UrlReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getCampaignReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $adWordsType = "",
	  $campaigns = array()
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .= $campaignsXml;
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "CampaignReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getAdImageReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $campaigns = array()
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .= $campaignsXml;
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "AdImageReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getAdGroupReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $adGroupStatuses = array(),
	  $adWordsType = "",
	  $campaigns = array()
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$adGroupStatusesXml = "";
		if (sizeOf($adGroupStatuses) > 0) {
			foreach($adGroupStatuses as $adGroupStatus) {
				$adGroupStatusesXml .=
				  "<adGroupStatuses>".trim($adGroupStatus)."</adGroupStatuses>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .= $campaignsXml.$adGroupStatusesXml;
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "AdGroupReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getAccountReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $adWordsType = ""
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$reportSpecificXml = "";
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "AccountReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getCustomReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $customOptions,
	  $adWordsType = "",
	  $campaigns = array(),
	  $campaignStatuses = array(),
	  $adGroups = array(),
	  $adGroupStatuses = array(),
	  $keywords = array(),
	  $keywordStatuses = array(),
	  $keywordType = "",
	  $includeZeroImpression = false
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$customOptionsXml = "";
		if (sizeOf($customOptions) > 0) {
			foreach($customOptions as $customOption) {
				$customOptionsXml .=
				  "<customOptions>".trim($customOption)."</customOptions>";
			}
		}

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$campaignStatusesXml = "";
		if (sizeOf($campaignStatuses) > 0) {
			foreach($campaignStatuses as $campaignStatus) {
				$campaignStatusesXml .=
				  "<campaignStatuses>".trim($campaignStatus)."</campaignStatuses>";
			}
		}

		$adGroupsXml = "";
		if (sizeOf($adGroups) > 0) {
			foreach($adGroups as $adGroup) {
				$adGroupsXml .= "<adGroups>".trim($adGroup)."</adGroups>";
			}
		}

		$adGroupStatusesXml = "";
		if (sizeOf($adGroupStatuses) > 0) {
			foreach($adGroupStatuses as $adGroupStatus) {
				$adGroupStatusesXml .=
				  "<adGroupStatuses>".trim($adGroupStatus)."</adGroupStatuses>";
			}
		}

		$keywordsXml = "";
		if (sizeOf($keywords) > 0) {
			foreach($keywords as $keyword) {
				$keywordsXml .= "<keywords>".trim($keyword)."</keyword>";
			}
		}

		$keywordStatusesXml = "";
		if (sizeOf($keywordStatuses) > 0) {
			foreach($keywordStatuses as $keywordStatus) {
				$keywordStatusesXml .=
				  "<keywordStatuses>".trim($keywordStatus)."</keywordStatuses>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .=
		  $customOptionsXml.
		  $campaignsXml.
		  $campaignStatusesXml.
		  $adGroupsXml.
		  $adGroupStatusesXml.
		  $keywordsXml.
		  $keywordStatusesXml;
		if ($keywordType) $reportSpecificXml .=
		  "<keywordType>".$keywordType."</keywordType>";
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		if ($includeZeroImpression) {
		  $includeZeroImpression = "true";
		}
		else {
		  $includeZeroImpression = "false";
		}
		$reportSpecificXml .=
		  "<includeZeroImpression>".$includeZeroImpression."</includeZeroImpression>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "CustomReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function getAdTextReportJob(
	  $reportFormat,
	  $sleepTime,
	  $name,
	  $startDay,
	  $endDay,
	  $aggregationType,
	  $isCrossClient = false,
	  $clientEmails = array(),
	  $adWordsType = "",
	  $campaigns = array()
	) {
		$genericReportXml = createGenericReportXml(
		  $name,
		  $startDay,
		  $endDay,
		  $aggregationType,
		  $isCrossClient,
		  $clientEmails
		);

		$campaignsXml = "";
		if (sizeOf($campaigns) > 0) {
			foreach($campaigns as $campaign) {
				$campaignsXml .= "<campaigns>".trim($campaign)."</campaigns>";
			}
		}

		$reportSpecificXml = "";
		$reportSpecificXml .= $campaignsXml;
		if ($adWordsType) $reportSpecificXml .=
		  "<adWordsType>".$adWordsType."</adWordsType>";
		return scheduleReportJob(
		  $genericReportXml,
		  $reportSpecificXml,
		  "AdTextReportJob",
		  $reportFormat,
		  $sleepTime
		);
	}

	function scheduleReportJob(
	  $genericReportXml,
	  $reportSpecificXml,
	  $reportType,
	  $reportFormat,
	  $sleepTime) {
		global $soapClients;
		$someSoapClient = $soapClients->getReportClient();

		$soapParameters = "<scheduleReportJob xmlns='".REPORT_WSDL_URL."'>
													<job xsi:type='".$reportType."'>"
													  .$genericReportXml
													  .$reportSpecificXml."
													</job>
												</scheduleReportJob>";
		// talk to the google servers and schedule report
		$someSchedule = $someSoapClient->call("scheduleReportJob", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":scheduleReportJob()", $soapParameters);
	    return false;
		}
		$soapParameters = "<getReportJobStatus xmlns='".REPORT_WSDL_URL."'>
													<reportJobId>".$someSchedule['scheduleReportJobReturn']."</reportJobId>
											 </getReportJobStatus>";
    // check the status of the scheduled report
		$reportStatus = $someSoapClient->call("getReportJobStatus", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":scheduleReportJob()", $soapParameters);
	    return false;
		}
		// busy waiting till report is finished (or till creation fails)
		while ( (strcmp($reportStatus['getReportJobStatusReturn'], "InProgress") == 0) ||
		        (strcmp($reportStatus['getReportJobStatusReturn'], "Pending") == 0)
		) {
			$reportStatus = $someSoapClient->call("getReportJobStatus", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
	  		pushFault($someSoapClient, $_SERVER['PHP_SELF'].":scheduleReportJob()", $soapParameters);
	    	return false;
			}
			// report failed :(
			if  (strcmp($reportStatus['getReportJobStatusReturn'], "Failed") == 0) {
  			if (!SILENCE_STEALTH_MODE) echo "<br /><b>APIlity PHP library => Warning:</b><br />Sorry, but for some mysterious reason I could not finish your report request.<p>";
  			return false;
			}
			// report succeeded :)
			if (strcmp($reportStatus['getReportJobStatusReturn'], "Completed") == 0) {
				$report = downloadXmlReport($someSchedule['scheduleReportJobReturn']);
				if (strcasecmp($reportFormat, "XML") == 0) {
					return $report;
				}
				else {
					return xml2Csv($report);
				}
  		}
  		// busy waiting with n seconds break
  		sleep($sleepTime);
		}
	}

	function downloadCsvReport($reportId) {
		return xml2Csv(downloadXmlReport($reportId));
	}

	function downloadXmlReport($reportId) {
		global $soapClients;
		$someSoapClient = $soapClients->getReportClient();
		$zipOrNot = '';
		if (!USE_SANDBOX) $zipOrNot = 'Gzip';
		$soapParameters  = "<get".$zipOrNot."ReportDownloadUrl>
		                      <reportJobId>".$reportId."</reportJobId>
		                    </get".$zipOrNot."ReportDownloadUrl>";
		$reportUrl = $someSoapClient->call("get".$zipOrNot."ReportDownloadUrl", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
			pushFault($someSoapClient, $_SERVER['PHP_SELF'].":downloadXmlReport()", $soapParameters);
	 		return false;
		}
		// open connection to the Google server via cURL
		$curlConnection = curl_init();
		curl_setopt($curlConnection, CURLOPT_URL, $reportUrl['get'.$zipOrNot.'ReportDownloadUrlReturn']);
		curl_setopt($curlConnection, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curlConnection, CURLOPT_SSL_VERIFYHOST, FALSE);
		// buffer for downloading report xml
		ob_start();
			curl_exec($curlConnection);
			// buffer report
			// inflate the gzipped report we got
			if (!USE_SANDBOX) $reportXml = gzinflate(substr(ob_get_contents(),10));
			else $reportXml = ob_get_contents();
		ob_end_clean();
		// end buffering
		if (curl_errno($curlConnection)) {
	 		if (!SILENCE_STEALTH_MODE) echo "<br /><b>APIlity PHP library => Warning:</b><br />Sorry, there was a problem while downloading your report. The cURL error message is:<p><font color='maroon'>".curl_error($curlConnection)."</font>";
	 		return false;
	 	}
	 	else curl_close($curlConnection);

		// PHP version is >= 5, i.e. only DOM is avalable
		if (version_compare(phpversion(), "5.0.0", ">=")) {
			$xmlDomDocument = new DOMDocument();
			$xmlDomDocument->loadXML($reportXml);
			$singleRows = $xmlDomDocument->getElementsByTagName("row");

			foreach($singleRows as $row) {
				$currencyUnitsCost = $row->getAttribute("cost") / EXCHANGE_RATE;
				$currencyUnitsCpc = $row->getAttribute("cpc") / EXCHANGE_RATE;
				$currencyUnitsMaxCpc = $row->getAttribute("maxCpc") / EXCHANGE_RATE;
				$currencyUnitsMinCpc = $row->getAttribute("keywordMinCpc") / EXCHANGE_RATE;
				$currencyUnitsCpm = $row->getAttribute("cpm") / EXCHANGE_RATE;
				$row->setAttribute("cost", (String) $currencyUnitsCost);
				$row->setAttribute("cpc", (String) $currencyUnitsCpc);
				$row->setAttribute("maxCpc", (String) $currencyUnitsMaxCpc);
				$row->setAttribute("keywordMinCpc", (String) $currencyUnitsMinCpc);
				$row->setAttribute("cpm", (String) $currencyUnitsCpm);
			}
			// in grandtotal
			$grandtotals = $xmlDomDocument->getElementsByTagName("grandtotal");

			foreach ($grandtotals as $grandtotal) {
				$currencyUnitsCost = $grandtotal->getAttribute("cost") / EXCHANGE_RATE;
				$currencyUnitsCpc = $grandtotal->getAttribute("cpc") / EXCHANGE_RATE;
				$currencyUnitsCpm = $grandtotal->getAttribute("cpm") / EXCHANGE_RATE;
				$grandtotal->setAttribute("cost", (String) $currencyUnitsCost);
				$grandtotal->setAttribute("cpc", (String) $currencyUnitsCpc);
				$grandtotal->setAttribute("cpm", (String) $currencyUnitsCpm);
			}

			// in grandtotal
			$subtotals = $xmlDomDocument->getElementsByTagName("subtotal");

			foreach ($subtotals as $subtotal) {
				$currencyUnitsCost = $subtotal->getAttribute("cost") / EXCHANGE_RATE;
				$currencyUnitsCpc = $subtotal->getAttribute("cpc") / EXCHANGE_RATE;
				$currencyUnitsCpm = $subtotal->getAttribute("cpm") / EXCHANGE_RATE;
				$subtotal->setAttribute("cost", (String) $currencyUnitsCost);
				$subtotal->setAttribute("cpc", (String) $currencyUnitsCpc);
				$subtotal->setAttribute("cpm", (String) $currencyUnitsCpm);
			}

			return $xmlDomDocument->saveXML();
		}
		// PHP version is <5, i.e. only DOM XML is available
		else {
			$xmlDomDocument = domxml_open_mem($reportXml);
			$report = $xmlDomDocument->document_element();
			$table =  $report->first_child();
			$rows = $table->last_child();
			$singleRows = $rows->child_nodes();
			// in rows
			foreach($singleRows as $row) {
				$currencyUnitsCost = $row->get_attribute("cost") / EXCHANGE_RATE;
				$currencyUnitsCpc = $row->get_attribute("cpc") / EXCHANGE_RATE;
				$currencyUnitsMaxCpc = $row->get_attribute("maxCpc") / EXCHANGE_RATE;
				$currencyUnitsMinCpc = $row->get_attribute("keywordMinCpc") / EXCHANGE_RATE;
				$currencyUnitsCpm = $row->get_attribute("cpm") / EXCHANGE_RATE;
				$row->set_attribute("cost", (String) $currencyUnitsCost);
				$row->set_attribute("cpc", (String) $currencyUnitsCpc);
				$row->set_attribute("maxCpc", (String) $currencyUnitsMaxCpc);
				$row->set_attribute("keywordMinCpc", (String) $currencyUnitsMinCpc);
				$row->set_attribute("cpm", (String) $currencyUnitsCpm);
			}
			// in grandtotal
			$totals = $report->last_child();
			$totalsChildren = $totals->child_nodes();
			foreach($totalsChildren as $totalsChild) {
				$currencyUnitsCost = $totalsChild->get_attribute("cost") / EXCHANGE_RATE;
				$currencyUnitsCpc = $totalsChild->get_attribute("cpc") / EXCHANGE_RATE;
				$currencyUnitsCpm = $totalsChild->get_attribute("cpm") / EXCHANGE_RATE;
				$totalsChild->set_attribute("cost", (String) $currencyUnitsCost);
				$totalsChild->set_attribute("cpc", (String) $currencyUnitsCpc);
				$totalsChild->set_attribute("cpm", (String) $currencyUnitsCpm);
			}
			// finished conversion
			return $xmlDomDocument->dump_mem();
		}
	}

	function deleteReport($reportJobId) {
		global $soapClients;
		$someSoapClient = $soapClients->getReportClient();
		$soapParameters = "<deleteReport>
													<reportJobId>".$reportJobId."</reportJobId>
											 </deleteReport>";
		// delete the report on the google servers
	  $someSoapClient->call("deleteReport", $soapParameters);
	  $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":deleteReport()", $soapParameters);
	    return false;
		}
		return true;
	}

	function getAllJobs() {
		global $soapClients;
		$someSoapClient = $soapClients->getReportClient();
		$soapParameters = "<getAllJobs></getAllJobs>";
		// query the google servers for all existing report jobs
		$allReportJobs = $someSoapClient->call("getAllJobs");
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllJobs()", $soapParameters);
	    return false;
		}
		return $allReportJobs['getAllJobsReturn'];
	}

	function xml2Csv($xmlString) {
		// define csv which will hold the csv string
		$csv = "";

		// an XML2CSV transformer
		// using DOM XML for downwards compatibility with PHP4
		//
		// structure of XML report is
		//
		// report
		//  |
		//  |-table
		//  |  |
		//  |  |-columns // contains column elements with the names of the report
		//  |  |         // columns like "campaign", "adgroup", "keyword", ...
		//  |  |
		//  |  |-rows // contains row elements with the specified report data
		//  |
		//  |-totals
		//     |
		//		 |-subtotal
		//     |-grandtotal // contains general statistic information of the report

		// PHP version is >= 5, i.e. only DOM is avalable
		if (version_compare(phpversion(), "5.0.0", ">=")) {
			$xmlDomDocument = new DOMDocument();
			$xmlDomDocument->loadXML($xmlString);

			$singleColumns = $xmlDomDocument->getElementsByTagName("column");
			// get attribute names (i.e. column names)
			$attributeNames = array();
			// first line of the csv report holds the column names
			foreach($singleColumns as $column) {
				$csv .= $column->getAttribute('name')."\t";
				array_push($attributeNames, $column->getAttribute('name'));
			}
			$csv .= "\n";

			// fill columns with report data
			$singleRows = $xmlDomDocument->getElementsByTagName("row");
			foreach($singleRows as $row) {
				foreach($attributeNames as $attributeName) {
					$csv .= $row->getAttribute($attributeName)."\t";
				}
				$csv .= "\n";
			}
			// and done
			return $csv;
		}
		// PHP version is <5, i.e. only DOM XML is available
		else {
			$xmlDomDocument = domxml_open_mem($xmlString);
			$report = $xmlDomDocument->document_element();
			$table =  $report->first_child();
			$totals = $report->last_child();
			$columns = $table->first_child();
			$rows = $table->last_child();
			// might add grandtotal but won't do this at present
			// uncomment the following line to do this anyhow
			//$grandtotal = $totals->first_child();

			$singleColumns = $columns->child_nodes();

			// get attribute names (i.e. column names)
			$attributeNames = array();
			// first line of the csv report holds the column names
			foreach($singleColumns as $column) {
				$csv .= $column->get_attribute('name')."\t";
				array_push($attributeNames, $column->get_attribute('name'));
			}
			$csv .= "\n";

			// fill columns with report data
			$singleRows = $rows->child_nodes();
			foreach($singleRows as $row) {
				foreach($attributeNames as $attributeName) {
					$csv .= $row->get_attribute($attributeName)."\t";
				}
				$csv .= "\n";
			}
			// and done
			return $csv;
		}
	}

	function generateCurlUserAgent() {
		// the google servers return the reports transparently (on the ISO/OSI
		// transport layer) gzipped if the header contains the string "gzip"
		$curlVersion = curl_version();
		// PHP version is >= 5
		if (version_compare(phpversion(), "5.0.0", ">="))	{
			$userAgent =
			  "libcurl/".$curlVersion['version'].$curlVersion['ssl_version'].
			  " libz/".$curlVersion['libz_version'];
		}
		else {
		// PHP version is <5
			$userAgent = $curlVersion;
		}
		return $userAgent;
	}
?>