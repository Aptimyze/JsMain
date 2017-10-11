<?php
  function getXmlReport(
    $name,
    $selectedReportType,
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false 
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

    // aggregation types
    $aggregationTypesXml = '';
    if (sizeOf($aggregationTypes > 0)) {
      foreach ($aggregationTypes as $aggregationType) {
        $aggregationTypesXml .= "<aggregationTypes>".$aggregationType."</aggregationTypes>";  
      }
    }

    // campaign ids
    $campaignsXml = '';
    if (sizeOf($campaigns > 0)) {
      foreach ($campaigns as $campaign) {
        $campaignsXml .= "<campaigns>".$campaign."</campaigns>";  
      }
    }

    // campaign statuses
    $campaignStatusesXml = '';
    if (sizeOf($campaignStatuses > 0)) {
      foreach ($campaignStatuses as $campaignStatus) {
        $campaignStatusesXml .= "<campaignStatuses>".$campaignStatus."</campaignStatuses>";  
      }
    }

    // adgroup ids
    $adGroupsXml = '';
    if (sizeOf($adGroups > 0)) {
      foreach ($adGroups as $adGroup) {
        $adGroupsXml .= "<adGroups>".$adGroup."</adGroups>";  
      }
    }

    // adgroup statuses
    $adGroupStatusesXml = '';
    if (sizeOf($adGroupStatuses > 0)) {
      foreach ($adGroupStatuses as $adGroupStatus) {
        $adGroupStatusesXml .= "<adGroupStatuses>".$adGroupStatus."</adGroupStatuses>";  
      }
    }

    // keyword ids
    $keywordsXml = '';
    if (sizeOf($keywords > 0)) {
      foreach ($keywords as $keyword) {
        $keywordsXml .= "<keywords>".$keyword."</keywords>";  
      }
    }

    // keyword statuses
    $keywordStatusesXml = '';
    if (sizeOf($keywordStatuses > 0)) {
      foreach ($keywordStatuses as $keywordStatus) {
        $keywordStatusesXml .= "<keywordStatuses>".$keywordStatus."</keywordStatuses>";  
      }
    }    

    // cross client or not
    if ($isCrossClient) $isCrossClient = "true"; else $isCrossClient = "false";

    // compile first parts of xml
    $reportXml = "<name>".$name."</name>
                  <selectedReportType>" . $selectedReportType . "</selectedReportType>" .
                  $aggregationTypesXml . "
                  <startDay>".$startDay."</startDay>
                  <endDay>".$endDay."</endDay>" .
                  $campaignsXml . 
                  $campaignStatusesXml . 
                  $adGroupsXml . 
                  $adGroupStatusesXml . 
                  $keywordsXml . 
                  $keywordStatusesXml . "
                  <crossClient>".$isCrossClient."</crossClient>".
                  $clientEmailsXml;

    // selected columns
    $selectedColumnsXml = "";
    if (sizeOf($selectedColumns) > 0) {
      foreach($selectedColumns as $selectedColumn) {
        $selectedColumnsXml .=
          "<selectedColumns>".trim($selectedColumn)."</selectedColumns>";
      }
    }
    $reportXml .=  $selectedColumnsXml;
    
    // keyword type
    if ($keywordType) $reportXml .=
      "<keywordType>".$keywordType."</keywordType>";
      
    // adwords type  
    if ($adWordsType) $reportXml .=
      "<adWordsType>".$adWordsType."</adWordsType>";
      
    // include zero impression  
    if ($includeZeroImpression) {
      $includeZeroImpression = "true";
    }
    else {
      $includeZeroImpression = "false";
    }
    $reportXml .=
      "<includeZeroImpression>".$includeZeroImpression."</includeZeroImpression>";     
    
    // finalize xml
    $reportXml = "<job xsi:type='DefinedReportJob'>".
                   $reportXml . "
                 </job>";                 
      
    return scheduleReportJob($reportXml, $sleepTime, $validateFirst);
  }
  
  function getTsvReport(
    $name,
    $selectedReportType,
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false 
) {
    return xml2Tsv(
      getXmlReport(
        $name,
        $selectedReportType,
        $startDay,
        $endDay,
        $selectedColumns,
        $aggregationTypes,
        $campaigns,
        $campaignStatuses,
        $adGroups,
        $adGroupStatuses,
        $keywords,
        $keywordStatuses,
        $adWordsType,
        $keywordType,
        $isCrossClient,
        $clientEmails,
        $includeZeroImpression,
        $sleepTime,
        $validateFirst
      )
    );
  }
  
  function getKeywordXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'Keyword',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getKeywordTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'Keyword',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getAccountStructureXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'Structure',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getAccountStructureTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'Structure',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getCreativeXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'Creative',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getCreativeTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'Creative',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }

  function getReachAndFrequencyXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'ReachAndFrequency',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }

  function getReachAndFrequencyTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'ReachAndFrequency',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }

  function getUrlXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'Url',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }

  function getUrlTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'Url',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }

  function getCampaignXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'Campaign',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }

  function getCampaignTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'Campaign',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getAdGroupXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'AdGroup',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getAdGroupTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'AdGroup',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }
  
  function getAccountXmlReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getXmlReport(
      $name,
      'Account',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst
    );
  }

  function getAccountTsvReport(
    $name, 
    $startDay,
    $endDay,
    $selectedColumns,
    $aggregationTypes,
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = '',
    $keywordType = '',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    $sleepTime = 30,
    $validateFirst = false   
  ) {
    return getTsvReport(
      $name,
      'Account',
      $startDay,
      $endDay,
      $selectedColumns,
      $aggregationTypes,
      $campaigns,
      $campaignStatuses,
      $adGroups,
      $adGroupStatuses,
      $keywords,
      $keywordStatuses,
      $adWordsType,
      $keywordType,
      $isCrossClient,
      $clientEmails,
      $includeZeroImpression,
      $sleepTime,
      $validateFirst 
    );
  }  

  function scheduleReportJob($reportXml, $sleepTime, $validateFirst) {
    if ($validateFirst) {
      if (!validateReportJob($reportXml)) return false;
    }    
    
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getReportClient();

    $soapParameters = "<scheduleReportJob xmlns='".REPORT_WSDL_URL."'>".    
                         $reportXml ."
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
        return downloadXmlReport($someSchedule['scheduleReportJobReturn']);
      }
      // busy waiting with n seconds break
      sleep($sleepTime);
    }
  }

  function downloadTsvReport($reportId) {
    return xml2Tsv(downloadXmlReport($reportId));
  }

  function downloadXmlReport($reportId) {
    $soapClients = &APIlityClients::getClients();
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
      if (!USE_SANDBOX) $reportXml = gzinflate(substr(ob_get_contents(), 10));
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
    $soapClients = &APIlityClients::getClients();
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
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getReportClient();
    $soapParameters = "<getAllJobs></getAllJobs>";
    // query the google servers for all existing report jobs
    $allReportJobs = $someSoapClient->call("getAllJobs", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllJobs()", $soapParameters);
      return false;
    }
    return $allReportJobs['getAllJobsReturn'];
  }

  function xml2Tsv($xmlString) {
    // define tsv which will hold the tsv string
    $tsv = "";

    // an XML2TSV transformer
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
    //     |-subtotal
    //     |-grandtotal // contains general statistic information of the report

    // PHP version is >= 5, i.e. only DOM is avalable
    if (version_compare(phpversion(), "5.0.0", ">=")) {
      $xmlDomDocument = new DOMDocument();
      $xmlDomDocument->loadXML($xmlString);

      $singleColumns = $xmlDomDocument->getElementsByTagName("column");
      // get attribute names (i.e. column names)
      $attributeNames = array();
      // first line of the tsv report holds the column names
      foreach($singleColumns as $column) {
        $tsv .= $column->getAttribute('name')."\t";
        array_push($attributeNames, $column->getAttribute('name'));
      }
      $tsv .= "\n";

      // fill columns with report data
      $singleRows = $xmlDomDocument->getElementsByTagName("row");
      foreach($singleRows as $row) {
        foreach($attributeNames as $attributeName) {
          $tsv .= $row->getAttribute($attributeName)."\t";
        }
        $tsv .= "\n";
      }
      // and done
      return $tsv;
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
      // first line of the tsv report holds the column names
      foreach($singleColumns as $column) {
        $tsv .= $column->get_attribute('name')."\t";
        array_push($attributeNames, $column->get_attribute('name'));
      }
      $tsv .= "\n";

      // fill columns with report data
      $singleRows = $rows->child_nodes();
      foreach($singleRows as $row) {
        foreach($attributeNames as $attributeName) {
          $tsv .= $row->get_attribute($attributeName)."\t";
        }
        $tsv .= "\n";
      }
      // and done
      return $tsv;
    }
  }

  function validateReportJob($reportXml) {
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getReportClient();
    $soapParameters = "<validateReportJob xmlns='".REPORT_WSDL_URL."'>".
                         $reportXml."
                       </validateReportJob>";
    // query the google servers for all existing report jobs
    $validation = $someSoapClient->call("validateReportJob", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":validateReportJob()", $soapParameters);
      return false;
    }
    return true;    
  }

  function generateCurlUserAgent() {
    // the google servers return the reports transparently (on the ISO/OSI
    // transport layer) gzipped if the header contains the string "gzip"
    $curlVersion = curl_version();
    // PHP version is >= 5
    if (version_compare(phpversion(), "5.0.0", ">="))  {
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