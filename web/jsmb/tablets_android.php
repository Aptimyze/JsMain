<?php
$fp_towrite=fopen('android_tablets_list.php','w');
if($fp_towrite){
	fwrite($fp_towrite,"<?php \n \$android_tablets = array ( \n");
	$fp=fopen('androids_nontab_pad.txt','r');
	$pattern='/.*;(.*)\).*Appl/';
	if($fp)
		while($line=fgets($fp)){
			preg_match ($pattern ,  $line, $res);
			fwrite($fp_towrite,"'".trim($res[1])."',\n");
		}
fclose($fp);
}
fwrite($fp_towrite,");");
fclose($fp_towrite);
