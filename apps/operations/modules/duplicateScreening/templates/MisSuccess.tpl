<html>
<head>
   <title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css"><style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br><br>
~if $flag eq 'DUP_pair' and $show eq 1`
 <table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead" width="100%"><td align="center" colspan=14><a href="../../jsadmin/mainpage.php?name=~$exec`&cid=~$cid`">MainPage</a></td></tr>
        <tr class="formhead">
        <td colspan=100% align="center">&nbsp;<b>Probabale Duplicate Screening Process Efficiency MIS</b></td>
        </tr>
        <tr class="formhead">
        <td colspan=100% align="center">&nbsp;Duration : ~$date1` to ~$date2`</td>
        </tr>
        <tr class="formhead">
        <td align="center">&nbsp;Date</td>
        <td align="center">&nbsp;Number of Profile Pairs Identified</td>
        <td align="center">&nbsp;Number of Profile Pairs Screened</td>
        <td align="center">&nbsp;Within 1 day</td>
        <td align="center">&nbsp;Within 2 days</td>
        <td align="center">&nbsp;Within 3 days</td>
        </tr>
        ~foreach from=$report key=day item=type`
        <tr class="formhead">
        <td align="center">&nbsp;~$day`</td>
        <td align="center">&nbsp;~if $type.cnt`~$type.cnt`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.total`~$type.total`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.total_1Day`~$type.total_1Day`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.total_2Day`~$type.total_2Day`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.total_3Day`~$type.total_3Day`~else`0~/if`</td>
        </tr>
        ~/foreach`
  </table>
<br>
~else if ($flag eq 'IE_exec' or $flag eq 'IE_sup') and $show eq 1`
 <table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
	<tr class="formhead" width="100%"><td align="center" colspan=14><a href="../../mis/mainpage.php?name=~$exec`&cid=~$cid`">MainPage</a></td></tr>
	~if $flag eq 'IE_exec'`
	<tr class="formhead">
        <td colspan=100% align="center">&nbsp;<b>Probabale Duplicate Identification Efficiency MIS of Executives</b></td>
        </tr>
	~elseif $flag eq 'IE_sup'`
	<tr class="formhead">
        <td colspan=100% align="center">&nbsp;<b>Probabale Duplicate Identification Efficiency MIS of Supervisor</b></td>
        </tr>
	~/if`
        <tr class="formhead">
        <td colspan=100% align="center">&nbsp;Duration : ~$date1` to ~$date2`</td>
        </tr>
	<tr class="formhead">
	<td align="center">&nbsp;Date</td>
	<td align="center">&nbsp;Number of Profile Pairs Screened</td>
        <td align="center">&nbsp;Number of Duplicates</td>
        <td align="center">&nbsp;Number of Not Duplicates</td>
	<td align="center">&nbsp;Number of Can't Say</td>
        </tr>
	~foreach from=$report key=day item=type`
	<tr class="formhead">
	<td align="center">&nbsp;~$day`</td>
        <td align="center">&nbsp;~if $type.total`~$type.total`~else`0~/if`</td>
	<td align="center">&nbsp;~if $type.dup`~$type.dup`~else`0~/if`</td>
	<td align="center">&nbsp;~if $type.nodup`~$type.nodup`~else`0~/if`</td>
	<td align="center">&nbsp;~if $type.prob`~$type.prob`~else`0~/if`</td>
        </tr>
	~/foreach`
  </table>
<br>
~else if $flag eq 'SE_exec' and $show eq 1`
<table width="100%" align="center" border=0 cellspacing=4 cellpadding=5>
        <tr class="formhead" width="100%"><td align="center" colspan=14><a href="../../mis/mainpage.php?name=~$exec`&cid=~$cid`">MainPage</a></td></tr>
	<tr class="formhead">
        <td colspan=100% align="center">&nbsp;<b>Probabale Duplicate Screening Executive Efficiency MIS</b></td>
        </tr>
        <tr class="formhead">
        <td colspan=100% align="center">&nbsp;Duration : ~$date1` to ~$date2`</td>
        </tr>
        <tr class="formhead">
        <td align="center">&nbsp;Executive</td>
        <td align="center">&nbsp;Number of Profile Pairs Screened</td>
        <td align="center">&nbsp;Number of Duplicates</td>
        <td align="center">&nbsp;Number of Not Duplicates</td>
        <td align="center">&nbsp;Number of Can't Say</td>
        </tr>
	~foreach from=$report key=day item=type`
        <tr class="formhead">
        <td align="center">&nbsp;~$day`</td>
        <td align="center">&nbsp;~if $type.total`~$type.total`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.dup`~$type.dup`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.nodup`~$type.nodup`~else`0~/if`</td>
        <td align="center">&nbsp;~if $type.prob`~$type.prob`~else`0~/if`</td>
        </tr>
        ~/foreach`
  </table>
<br>
~else`
<br>

<table width=80% border=0 align="center" cellpadding=><tr class="formhead" width="100%"><td align="center" colspan=14><a href="../../mis/mainpage.php?name=~$exec`&cid=~$cid`">MainPage</a></td></tr></table>
<h4><center> Select your date range</center></h4>
<form method=post action="~sfConfig::get('app_site_url')`/operations.php/duplicateScreening/Mis?name=~$exec`&cid=~$cid`&flag=~$flag`">
	<table width=80% border=0 align="center" cellpadding=5>
	<tr>
	<td colspan=2 align=center class="formhead">FROM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<select name="day">
	~foreach from=$ddarr item=d`
                <option value=~$d` ~if $d eq 1` selected ~/if`>~$d`</option>
	~/foreach`
        </select>
	<select name="month">
	~foreach from=$mmarr item=m`
                <option value=~$m` ~if $m eq $month` selected ~/if`>~$m`</option>
	~/foreach`
        </select> - 
	<select name="year">
	~foreach from=$yyarr item=y`
        	<option value=~$y` ~if $y eq $year` selected ~/if`>~$y`</option>
	~/foreach`
        </select>&nbsp;Year
	</td>
	</tr>
	<tr>
        <td colspan=2 align="center" class="formhead">TO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<select name="day2">
	~foreach from=$ddarr item=d`
                <option value=~$d`  ~if $d eq $day` selected ~/if`>~$d`</option>
	~/foreach`
        </select>
        <select name="month2">
	~foreach from=$mmarr item=m`
                <option value=~$m`  ~if $m eq $month` selected ~/if`>~$m`</option>
	~/foreach`
        </select> -
        <select name="year2">
	~foreach from=$yyarr item=y`
                <option value=~$y`  ~if $y eq $year` selected ~/if`>~$y`</option>
	~/foreach`
        </select>&nbsp;Year
        </td>
	</tr>
	<tr>
	<td align=center><br><input type="submit" name="Show" value="    Go     ">
            <br>
            <br>
          </td>
	</tr>
	</table>
	</form>
~/if`
</body>
</html>
