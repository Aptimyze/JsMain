<?php
//include ("connect.inc");
//extract_csv("transcripts.csv");
function extract_csv($path,$filezip,$file)
{
	chdir($path);
	echo shell_exec("/usr/bin/unzip -o $path$filezip");
//	$cmp = "\"Start\",\"Duration\",\"Operator\",\"email\",\"ESQ q1\",\"ESQ q2\",\"PCQ q1\",\"PCQ q2\",\"OSQ q1\",\"OSQ q2\",\"OSQ q3\",\"OSQ q4\",\"DisconnectedBy\",\"Browser\",\"Host IP\"\n";
	$cmp = "\"Start\",\"Duration\",\"Operator\"";
        $handle=fopen($path.$file,"r");
        if($handle == "ERROR")
        {
                echo "ERROR opening file transcripts.csv";
                exit;
        }
        $fd = fopen($path."new.csv", "w+") or die("Cannot open a file new.csv.");
	$flag=0;
	while(!feof($handle))
	{
		$strtemp=fgets($handle);
//		$val= strcmp($cmp,$strtemp); 
		$val= strncmp($cmp,$strtemp,strlen($cmp)); 
		if($val==0)
		{
			$t=1;
			$flag++;
		}
		if($flag==1)
		{
			if($val!=0 || $t==1)
			{
		                fputs($fd,$strtemp) or die("Cannot put on file new.csv .");
			}
		}
	}
        fclose($fd) or die("Cannot close the file new.csv.");
	fclose($handle);
//	$fd = fopen($path."new.csv", "r");
//	$cnt=0;
/*	while(!feof($fd))
        {
		$cnt++;
	}
	echo "kush".$cnt."kush";
	fclose($fd);
*/

}
	
	
?>
