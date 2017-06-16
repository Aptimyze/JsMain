<?php
        $symfonyFilePath = JsConstants::$cronDocRoot;

        include_once($symfonyFilePath.'/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php');
        include_once($symfonyFilePath.'/config/ProjectConfiguration.class.php');
        if(strstr($_SERVER["PHP_SELF"],"operations.php") || strstr($_SERVER["PHP_SELF"],"operations_dev.php"))
                $app = "operations";
        else
                $app = "jeevansathi";
	if(JsConstants::$whichMachine=="local")
                $configuration =ProjectConfiguration::getApplicationConfiguration($app, 'dev',true);
        elseif(JsConstants::$whichMachine=="test")
                $configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', false);
        else
                $configuration =ProjectConfiguration::getApplicationConfiguration($app, 'prod', false);

        if(!sfContext::hasInstance())
        sfContext::createInstance($configuration);

        //THESE VARIABLES ARE USED IN SOME FILES SO KEEPING THEM
        //getting config path
        $sfProjectConfiguration=new sfProjectConfiguration;
        $symfonyConfigPath=$sfProjectConfiguration->getRootDir()."/config";
        //getting config path

        //start: including app.yml
        $appDotYml = sfYaml::load ("$symfonyFilePath/apps/operations/config/app.yml");
        $jeevansathiAppDotYml = sfYaml::load ("$symfonyFilePath/apps/jeevansathi/config/app.yml");
        //end: including app.yml

	//$url=sfConfig::get("app_site_url")."/billing/misc_salesInvoice_printbill.php?billid=$billid&receiptid=$receiptid&invoiceType=JS&cid=$cid";
		if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'https://www.jeevansathi.com'){
			$SITE_URL = 'https://crm.jeevansathi.com';
		} else {
			$SITE_URL = JsConstants::$siteUrl;
		}
		$url="$SITE_URL/billing/misc_salesInvoice_printbill.php?billid=$billid&receiptid=$receiptid&invoiceType=JS&cid=$cid";
        $bill=PdfCreation::PdfFile($url);

?>



