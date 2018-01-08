<?php
include("connect.inc");

$date = date("Y-m-d");

if($response_type=='o')
{
	$sql="select MAILER_ID from MAIL_UNSUBSCRIBE_STANDARD where DATE='$date' and MAILER_ID='$mailer_id'";
	$result=mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		$sql="UPDATE MAIL_UNSUBSCRIBE_STANDARD SET OPEN_COUNT=(OPEN_COUNT+1) WHERE MAILER_ID ='$mailer_id' AND DATE='$date'";
		mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
	}
	else
	{
		$sql="INSERT INTO MAIL_UNSUBSCRIBE_STANDARD(DATE,MAILER_ID,OPEN_COUNT) VALUES('$date','$mailer_id',1)";
		mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
	}
}
elseif($response_type=='i')
{
	$sql="select EMAIL from MAIL_OPEN_INDIVIDUAL_STANDARD where DATE='$date' and MAILER_ID='$mailer_id' AND EMAIL='$email'";
	$result=mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		$sql="UPDATE MAIL_OPEN_INDIVIDUAL_STANDARD SET OPEN_COUNT=(OPEN_COUNT+1) WHERE MAILER_ID ='$mailer_id' AND DATE='$date' AND EMAIL='$email'";
		mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
	}
	else
	{
		$sql="INSERT INTO MAIL_OPEN_INDIVIDUAL_STANDARD(DATE,MAILER_ID,EMAIL,OPEN_COUNT) VALUES('$date','$mailer_id','$email',1)";
		mysql_query($sql) or die( " SQL :$sql  \n Error : ".mysql_error());
	}
}
	// We'll be outputting a GIF
        header('Content-type: image/gif');

        // The GIF source is in given Url
        readfile($_SERVER["DOCUMENT_ROOT"] . '/mmmjs/images/transparent_img.gif');
?>
