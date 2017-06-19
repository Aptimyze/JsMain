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
                <td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/operations.php/crmMis/discountHeadsMis?cid=~$cid`">Click To Select Different Range</a></font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2><a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">MainPage</a></font></td>
        </tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>Result For Discount Heads MIS</font></td>
	</tr>
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:PeachPuff"><font size=2>Duration: ~$displayDate`</font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2>Txn Currency = ~$cur_type`</font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2>All numbers below are in INR</font></td>
        </tr>
	</table>
	<br>
        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
            <td width=4% align=center>S.No.</td>
            <td width=4% align=center>Executives Name</td>
            <td width=4% align=center>Total Discount (TD)</td>
            <td width=4% align=center>Standard Renewal Discount (SRD)</td>
            <td width=4% align=center>Extra Renewal Discount (ERD)</td>
            <td width=4% align=center>Discount on Add-on Services (AD)</td>
            <td width=4% align=center>Add-On given Free (AF)</td>
            <td width=4% align=center>Discount on Membership Plan (MD)</td>
            <td width=4% align=center>Membership Duration for Free (MF)</td>
        </tr>
	~$i=1`
    	~foreach from=$res key=k item=uu`
		~if $uu.TOTAL_DISCOUNT|round neq 0`
       		<tr class=formhead style="background-color:~$background_color[$uu.USERNAME]`">
			<td width=4% align=center>~$i++`</td>
			<td width=4% align=center><a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/discountHeadsMisResultScreen2?cid=~$cid`&agent=~$k`&startDate=~$start_dt`&endDate=~$end_dt`&curType=~$cur_type`&displayDate=~$displayDate`" style = "text-decoration:underline; color:#0000FF;">~$k`</a></td>
			<td width=4% align=center>~$uu.TOTAL_DISCOUNT|round`</td>
			<td width=4% align=center>~$uu.STD_RENEWAL_DISCOUNT|round`</td>
			<td width=4% align=center>~$uu.EXTRA_RENEWAL_DISCOUNT|round`</td>
			<td width=4% align=center>~$uu.ADDON_DISCOUNT|round`</td>
			<td width=4% align=center>~$uu.ADDON_FREE|round`</td>
			<td width=4% align=center>~$uu.MEMBERSHIP_DISCOUNT|round`</td>
			<td width=4% align=center>~$uu.MEMBERSHIP_FREE|round`</td>
		</tr>
		~/if`
    	~/foreach`
</table>
</body>
</html>
