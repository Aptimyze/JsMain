<?php


	include(dirname(__FILE__).'/../../bootstrap/unit.php');
	include("/var/www/html/branches/version_upgrade/web/msmjs/lib/SendMessage.class.php");
	$t = new lime_test(16, new lime_output_color());
	$new = new SendMessage();
	echo $new->checkMobilePhone("+919810300513");die;
	
		
