<?php
/*********************************************************************************************
* FILE NAME     : common_func_inc.php
* DESCRIPTION   : Contains common functions for Wedding Gallery
* CREATION DATE : 3 September, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
function get_category($ID)
{
        $sql="SELECT SQL_CACHE LABEL FROM wedding_classifieds.CATEGORY WHERE ID='$ID'";
        $res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
        $row=mysql_fetch_array($res);
        return $row['LABEL'];
}

function populate_head()
{
	global $smarty;

	$sql="SELECT SQL_CACHE ID,LABEL FROM wedding_classifieds.CATEGORY WHERE DISPLAY='Y'";
	$res=mysql_query_decide($sql) or logError("Error while populating category ".mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$category_head[]=array(	"ID"=>$row['ID'],
					"LABEL"=>$row['LABEL']);
	}
	$smarty->assign("category",$category_head);
	
	$sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
	$res=mysql_query_decide($sql) or logError("Error while populating city ".mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$city_head[]=array(	"VALUE"=>$row['VALUE'],
					"LABEL"=>$row['LABEL']);
	}
	$smarty->assign("city",$city_head);	
}

function populate_left()
{
	global $smarty;
                                                                                                                            
        $sql="SELECT SQL_CACHE ID,LABEL FROM wedding_classifieds.CATEGORY WHERE DISPLAY='Y'";
        $res=mysql_query_decide($sql) or logError("Error while populating category ".mysql_error_js(),$sql);
        while($row=mysql_fetch_array($res))
        {
                $category_left[]=array( "ID"=>$row['ID'],
                                        "LABEL"=>$row['LABEL']);
        }
        $smarty->assign("category",$category_left);
}

/*
function send_email($to,$msg,$from,$subject)
{
	if($to=="" || $msg=="" || $from=="" || $subject=="")
	{
		$errorstring="echo \"" ."\nMESSAGE:".$msg."\nTO:".$to."\nFROM:".$from."\nSUBJECT:".$subject."\n";
	        $errorstring.="\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/wedding/logerror.txt";
	        passthru($errorstring);

	}
	else
	{

		$boundry = "b".md5(uniqid(time()));
		$MP = "/usr/sbin/sendmail -t";
		$MP .= " -N never -R hdrs -f $from";
		$fd = popen($MP,"w");
		fputs($fd, "X-Mailer: PHP3\n");
		fputs($fd, "MIME-Version:1.0 \n");
		fputs($fd, "To: $to\n");
		fputs($fd, "From: $from \n");
		fputs($fd, "Subject: $subject \n");
		fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
		fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
		fputs($fd, "$msg\r\n");
		$p=pclose($fd);
		return $p;
	}
}
*/
/*********************************************************************************************
* FUNCTION NAME : validation
* DESCRIPTION   : To validate all fields
* CREATION DATE : 9 September, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
function validation()
{
        global $NAME,$CONTACTNUMBER,$CONTACTADDRESS,$EMAIL,$REQUIREMENT,$smarty;
        $err=0;
        $NAME=trim($NAME);
        $EMAIL=trim($EMAIL);
        $CONTACTNUMBER=trim($CONTACTNUMBER);
        $CONTACTADDRESS=trim($CONTACTADDRESS);
        $REQUIREMENT=trim($REQUIREMENT);
        if($NAME == '')
        {
                $err++;
                $e_name=1;
        }
        if(!isEmail($EMAIL))
        {
                $err++;
                $e_email=1;
        }
        if($CONTACTNUMBER == '' || checkphone($CONTACTNUMBER))
        {
                $err++;
                $e_contactnumber=1;
        }
        if($CONTACTADDRESS == '')
        {
                $err++;
                $e_contactaddress=1;
        }
        if($REQUIREMENT == '')
	{
                $err++;
                $e_requirement=1;
        }
        if(!$err)
        {
                return 1;//normal return...
        }
        else
        {
                $error=array(   "err"=>$err,
                                "name"=>$e_name,
                                "email"=>$e_email,
                                "contactnumber"=>$e_contactnumber,
                                "contactaddress"=>$e_contactaddress,
                                "requirement"=>$e_requirement,
                            );
                $smarty->assign("error",$error);
                return 0;
        }
}
/********************************************************************************************
* FUNCTION NAME : isEmail
* DESCRIPTION   : To check if an email id is valid or not
* CREATION DATE : 9 September, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
function isEmail($str)
{
        $ext=0;
        $ext1=0;
        $a=strlen($str);
        for ($i=0;$i<$a;$i++)
        {
                if ($str[$i]=='@')
                {
                        $ext1++;
                        $ext = substr(strrchr($str, '@'), 1);
                }
        }
        $ext2=substr(strrchr($ext,'.'),1);
        if ($ext1==1 && $ext!=='' && $ext2!='')
                return 1;//valid e-id
        else
                return 0; //invalid e-id
}


?>
