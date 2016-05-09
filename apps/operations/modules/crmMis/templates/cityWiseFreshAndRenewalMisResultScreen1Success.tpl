<html>
<head>
   	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table width="100%" align="center">
		<tr>
			<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
		</tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/operations.php/crmMis/cityWiseFreshAndRenewalMis?cid=~$cid`">Click To Select Different Range</a></font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
        </tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>City Wise Fresh &amp; Renewal MIS</font></td>
	</tr>
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:PeachPuff"><font size=2>Financial Year : ~$displayDate`</font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2>~$selectedSales` :: All numbers below are in INR</font></td>
        </tr>        
	</table>
	<br>
        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
            <td width=4% align=center style="background-color:LightSalmon">City</td>
            ~foreach from=$labelArr key=k item=uu`
                <td width=4% align=center>~$uu`</td>
            ~/foreach`
            <td width=4% align=center style="background-color:PaleGreen">Total</td>
        </tr>
       ~foreach from=$saleArr key=k item=city`
        <tr class=formhead>
            <td width=4% align=center><b>~$k`</b></td>
            	~foreach from=$indexArr key=ii item=i`
                	<td width=4% align=center>~if $city[$i]` ~$city[$i]` ~/if`</td>
		~/foreach`
		<td width=4% align=center>~if $city['TOTAL']` ~$city['TOTAL']` ~/if`</td>
        </tr>
        ~/foreach`
</table>
</body>
</html>
