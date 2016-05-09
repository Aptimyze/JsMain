<?php

	$regEntryDt ='2013-06-10';
	$profileReg2Dt='2013-06-11';

        if(strtotime($regEntryDt)<=strtotime($profileReg2Dt))
		$eligibleProfile='manoj';
	else
		$eligibleProfile= "singh";

echo $eligibleProfile;


?>
