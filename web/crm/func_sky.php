<?php
function get_phone_no($exe)
{
        $sql_no="SELECT ALIASE_NAME,PHONE_NO from incentive.EXECUTIVES where EXE_NAME='$exe'";
        $result_no = mysql_query_decide($sql_no);
        $row_exec=mysql_fetch_array($result_no);
        if(!$row_exec['ALIASE_NAME'])
        {
                $row_exec['ALIASE_NAME']='rajeev';
                $row_exec['PHONE_NO']='0120-5303116';
        }
        return $row_exec;
}
         
if(!function_exists("get_date_format"))
{                                                                                                                    
	function get_date_format($dt)
	{
        	$date_time_arr = explode(" ",$dt);
        	$time_arr=explode(":",$date_time_arr[1]);
        	$date_arr = explode("-",$date_time_arr[0]);
        	$date_val = date("d/m/y H:i:s",mktime($time_arr[0],$time_arr[1],$time_arr[2],$date_arr[1],$date_arr[2],$date_arr[0]));
        	return $date_val;
        }
}
function send_email_xls($email,$Cc,$Bcc,$msg,$subject,$from,$attach,$filename="")
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $email\n");
        fputs($fd, "Cc: $Cc\n");
        fputs($fd, "Bcc: $Bcc\n");
        fputs($fd, "From: $from \n");
        fputs($fd, "Subject: $subject \n");
        fputs($fd, "Content-Type: multipart/MIME; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "\n--$boundry\r\n");
        fputs($fd, "$msg\r\n");
        $attach=ereg_replace("\n","\r\n",$attach);
        fputs($fd, "\n--$boundry\r\n");
        fputs ($fd, "Content-Type: application/vnd.ms-excel\r\n");
        fputs ($fd, "Content-Disposition: attachment; filename=$filename\r\n\r\n");
        fputs($fd,$attach);
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}

function send_email_plain($email,$Cc,$Bcc,$msg,$subject,$from,$attach)
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $email\n");
        fputs($fd, "Cc: $Cc\n");
        fputs($fd, "Bcc: $Bcc\n");
        fputs($fd, "From: $from \n");
        fputs($fd, "Subject: $subject \n");
	if($attach)
	{
		fputs($fd, "Content-type: multipart/MIME; boundary=$boundry \n");
		fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
		fputs($fd, "\r\n");
		fputs($fd, "This is a multi-part message in MIME format\r\n\r\n");
		fputs($fd, "\n--$boundry\r\n\n");
		fputs($fd, "$msg \n\n");
		$attach=ereg_replace("\n","\r\n",$attach);
		fputs($fd, "\n--$boundry\r\n");
		fputs ($fd, "Content-Type: text/plain \r\n");
		fputs ($fd, "Content-Disposition: attachment; filename=\"sky.txt\"\r\n\r\n");
		fputs($fd,$attach);
	}
	else
	{
		fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
		fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
		fputs($fd, "\r\n");
		fputs($fd, "$msg\r\n");
	}
	$p=pclose($fd);
        return $p;
}

function send_mail($email,$Cc,$Bcc,$msg,$subject,$from)
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
	$from_name="Jeevansathi.com";
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $email\n");
	fputs($fd, "Cc: $Cc\n");
        fputs($fd, "Bcc: $Bcc\n");
        fputs($fd, "From: $from_name <$from> \n");
        fputs($fd, "Subject: $subject \n");
        fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "$msg\r\n");
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}

function send_mail_custom($email,$Cc,$Bcc,$msg,$subject,$from,$type)
{
        $boundry = "b".md5(uniqid(time()));
        $MP = "/usr/sbin/sendmail -t  ";
        $spec_envelope = 1;
        if($spec_envelope)
        {
                $MP .= " -N never -R hdrs -f $from";
        }
	$from_name="Jeevansathi.com";
        $fd = popen($MP,"w");
        fputs($fd, "X-Mailer: PHP3\n");
        fputs($fd, "MIME-Version:1.0 \n");
        fputs($fd, "To: $email\n");
	fputs($fd, "Cc: $Cc\n");
        fputs($fd, "Bcc: $Bcc\n");
        fputs($fd, "From: $from_name <$from> \n");
        fputs($fd, "Subject: $subject \n");
        fputs($fd, "Content-Type: $type; boundary=$boundry\n");
        fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
        fputs($fd, "$msg\r\n");
        fputs($fd, "\r\n . \r\n");
        $p=pclose($fd);
        return $p;
}




?>
