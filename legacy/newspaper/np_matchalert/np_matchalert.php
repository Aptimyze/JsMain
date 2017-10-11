<?php

/*********************************************************************************************
* FILE NAME	: np_matchalert.php
* DESCRIPTION	: Calls functions from np_mail.php and np_mailer.php
* CREATION DATE	: 19 May, 2005
* CREATEDED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include "connect.inc";

include "np_mailer.php";
mainact();
include "np_mail.php";
mainmail();
?>

