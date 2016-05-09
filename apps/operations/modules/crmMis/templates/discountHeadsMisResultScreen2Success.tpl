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
                <td colspan="2" style="background-color:PeachPuff"><font size=2>Agent: ~$agent`</font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2>Duration: ~$displayDate`</font></td>
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
            <td width=4% align=center>Billing Date</td>
            <td width=4% align=center>Username</td>
            <td width=4% align=center>Membership Plan + Addon Service(s)  Purchased</td>
            <td width=4% align=center>Membership Standard Price (MSP)</td>
            <td width=4% align=center>Sum of all AddOn(s) Standard Price (ASP)</td>
            <td width=4% align=center>Renewal Period?</td>
            <td width=4% align=center>Membership Renewal Price (MRP = 85% of MSP)</td>
            <td width=4% align=center>Addon Renewal Price (ARP = 85% of ASP)</td>
            <td width=4% align=center>Total Discount (TD)</td>
            <td width=4% align=center>Standard Renewal Discount (SRD)</td>
            <td width=4% align=center>Extra Renewal Discount (ERD)</td>
            <td width=4% align=center>Discount on Add-on Services (AD)</td>
            <td width=4% align=center>Add-On given Free (AF)</td>
            <td width=4% align=center>Discount on Membership Plan (MD)</td>
            <td width=4% align=center>Membership Plan Duration for Free (MF)</td>
        </tr>
    	~foreach from=$res[$agent] key=k item=uu name=info`
       		<tr class=formhead style="background-color:~$background_color[$uu.USERNAME]`">
			<td width=4% align=center>~$smarty.foreach.info.index+1`</td>
			<td width=4% align=center>~$uu.BILLING_DT`</td>
			<td width=4% align=center>~$uu.USERNAME`</td>
			<td width=4% align=center>~$uu.SERVICES_PURCHASED`</td>
			<td width=4% align=center>~$uu.MEMBERSHIP_STD_PRICE|round|round`</td>
			<td width=4% align=center>~$uu.ADDON_STD_PRICE|round|round`</td>
			<td width=4% align=center>~$uu.IS_RENEWAL_PERIOD`</td>
			<td width=4% align=center>
				~if $uu.MEMBERSHIP_RENEWAL_PRICE and $uu.MEMBERSHIP_RENEWAL_PRICE neq "N/A"` 
					~$uu.MEMBERSHIP_RENEWAL_PRICE|round|round` 
				~else`
					 ~$uu.MEMBERSHIP_RENEWAL_PRICE` 
				~/if`
			</td>
			<td width=4% align=center>
				~if $uu.ADDON_RENEWAL_PRICE and $uu.ADDON_RENEWAL_PRICE neq "N/A"` 
					~$uu.ADDON_RENEWAL_PRICE|round|round` 
				~else` 
					~$uu.ADDON_RENEWAL_PRICE` 
				~/if`
			</td>
		
			<td width=4% align=center>~$uu.TOTAL_DISCOUNT|round|round`</td>
	
			<td width=4% align=center>
				~if $uu.STD_RENEWAL_DISCOUNT and $uu.STD_RENEWAL_DISCOUNT neq "N/A"` 
					~$uu.STD_RENEWAL_DISCOUNT|round|round` 
				~else` 
					~$uu.STD_RENEWAL_DISCOUNT` 
				~/if`
			</td>
			<td width=4% align=center>
				~if $uu.EXTRA_RENEWAL_DISCOUNT and $uu.EXTRA_RENEWAL_DISCOUNT neq "N/A"` 
					~$uu.EXTRA_RENEWAL_DISCOUNT|round|round` 
				~else` 
					~$uu.EXTRA_RENEWAL_DISCOUNT` 
				~/if`
			</td>
			<td width=4% align=center>
				~if $uu.ADDON_DISCOUNT and $uu.ADDON_DISCOUNT neq "N/A"` 
					~$uu.ADDON_DISCOUNT|round|round` 
				~else` 
					~$uu.ADDON_DISCOUNT` 
				~/if`
			</td>
			<td width=4% align=center>
				~if $uu.ADDON_FREE and $uu.ADDON_FREE neq "N/A"` 
					~$uu.ADDON_FREE|round|round` 
				~else` 
					~$uu.ADDON_FREE` 
				~/if`
			</td>
			<td width=4% align=center>
				~if $uu.MEMBERSHIP_DISCOUNT and $uu.MEMBERSHIP_DISCOUNT neq "N/A"` 
					~$uu.MEMBERSHIP_DISCOUNT|round|round` 
				~else` 
					~$uu.MEMBERSHIP_DISCOUNT` 
				~/if`
			</td>
			<td width=4% align=center>
				~if $uu.MEMBERSHIP_FREE and $uu.MEMBERSHIP_FREE neq "N/A"` 
					~$uu.MEMBERSHIP_FREE|round|round` 
				~else` 
					~$uu.MEMBERSHIP_FREE` 
				~/if`
			</td>
		</tr>
    	~/foreach`
</table>
</body>
</html>
