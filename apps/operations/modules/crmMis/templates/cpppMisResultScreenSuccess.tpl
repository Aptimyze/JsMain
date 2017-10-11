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
                <td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
        </tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>CPPP MIS</font></td>
	</tr>
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:PeachPuff"><font size=2>For the period : ~$displayDate`</font></td>
        </tr>
	</table>
        <table width=100% align=center>
        <tr align="center" style="background-color:SeaShell">
        <td colspan="4"><font size=2>
            Grand total calculations for Average Amount Paid are based on overall website transactions and are not function of the listed sources.
        </td></tr>
        <tr></tr>
        <tr></tr>
        <tr class=formhead style="background-color:LightSteelBlue">
            <td width=4% align=center style="background-color:LightSteelBlue">Source</td>
            <td width=4% align=center style="background-color:LightSteelBlue">No. of Registrations</td>
            <td width=4% align=center style="background-color:LightSteelBlue">No. of Paid Members</td>
            <td width=4% align=center style="background-color:LightSteelBlue">Average Amount Paid(net of tax)</td>
            <td width=4% align=center style="background-color:LightSteelBlue">30-day sales conversion (%)</td>
            <td width=4% align=center style="background-color:LightSteelBlue">90-day sales conversion (%)</td>
        </tr>
       ~foreach from=$srcWiseDataArr key=src item=ss`
        <tr class=formhead>
            <td width=4% align=center><b>~$src`</b></td>
            <td width=4% align=center><b>
                ~if ~$srcWiseDataArr[$src]['REG']``
                    ~$srcWiseDataArr[$src]['REG']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$srcWiseDataArr[$src]['PAID_MEM']``
                    ~$srcWiseDataArr[$src]['PAID_MEM']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$srcWiseDataArr[$src]['AVG_AMT_PAID']``
                    ~$srcWiseDataArr[$src]['AVG_AMT_PAID']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$srcWiseDataArr[$src]['PAID30']``
                    ~$srcWiseDataArr[$src]['PAID30PER']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$srcWiseDataArr[$src]['PAID90']``
                    ~$srcWiseDataArr[$src]['PAID90PER']`
                ~/if`
            </b></td>
        </tr>
        ~/foreach`
        <tr class=formhead style="background-color:PaleGreen">
            <td width=4% align=center><b>GRAND TOTAL</b></td>
            <td width=4% align=center><b>
                ~if ~$totalArr['REG']``
                    ~$totalArr['REG']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$totalArr['PAID_MEM']``
                    ~$totalArr['PAID_MEM']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$totalArr['AVG_AMT_PAID']``
                    ~$totalArr['AVG_AMT_PAID']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$totalArr['PAID30']``
                    ~$totalArr['PAID30PER']`
                ~/if`
            </b></td>
            <td width=4% align=center><b>
                ~if ~$totalArr['PAID90']``
                    ~$totalArr['PAID90PER']`
                ~/if`
            </b></td>
       </tr>

</table>
</body>
</html>
