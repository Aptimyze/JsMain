<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="RDX Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'authentication required';
    exit;
}
if( $_SERVER['PHP_AUTH_USER'] != "rdx" || $_SERVER['PHP_AUTH_PW'] != "sarstats")
	die("authentication failure");
exec("ls -t /var/log/sa/sa?? | cut -c 15-16",$dates);
?>
<html>
<head>
<title> Resdex Stats </title>
</head>
<body>
<h2>Resdex Stats</h2>
<form name=selectfields action=redirect.php method=get>
Date:
<select name=date>
	<?php
	foreach($dates as $date)
		echo "<option value=$date>$date</option>\n";
	?>
</select>
<br><br>Type:
<select name=type>
<option value=u>CPU</option>
<option value=r>memory+swap</option>
<option value=q>queue+load</option>
<option value=B>Paging</option>
<option value=c>procs creation</option>
<option value=d>block device</option>
<option value=W>swapping</option>
<!-- <option value=b>I/O</option> -->
<!-- <option value=w>Context switches</option> -->
</select>
<br><br>Server
<select name=server>
<option value=linuxcp10258>10258(apache-resdex.naukri.com)</option>
<option value=linuxcp10078>10078(apache-w24.naukri.com,insta)</option>
<option value=linuxcp10305>10305(apache-w35.naukri.com,insta)</option>
<option value=linuxcp10273>10273(w33.naukri.com,ecelerity)</option>
<option value=lfvscp10017>10017(mysql-STATUS Master)</option>
<option value=linuxcp10056>10056(mysql-STATUS Master Failover)</option>
<option value=linuxcp10084>10084(mysql-newrdx master 1)</option>
<option value=linuxcp10057>10057(mysql-master master 2)</option>
<option value=linuxcp10210>10210(mysql+java-Commander,lucene,simcv)</option>
<option value=linuxcp10236>10236(mysql-lucene)</option>
<option value=linuxcp10237>10237(mysql-clientprofile,lucene,simcv slave)</option>
<option value=linuxcp10198>10198(mysql-usage,simlog,simcv,rdxlogs)</option>
<option value=lfvscp10016>10016(mysql-resman5 slave,preview)</option>
<option value=linuxcp10064>10064(search - upto 1 yr)</option>
<option value=linuxcp10067>10067(search - upto 1 yr)</option>
<option value=linuxcp10068>10068(search - upto 1 yr)</option>
<option value=linuxcp10069>10069(search - all)</option>
<option value=linuxcp10073>10073(search - all)</option>
<option value=linuxcp10307>10307(search - upto 1 yr + SRCHSESS)</option>
<option value=linuxcp10327>10327(search - upto 1 yr + resman5 slave)</option>
</select>
<br><br>
<input type=submit name=showsar value="Get Stats">
</form>
</body>
</html>
