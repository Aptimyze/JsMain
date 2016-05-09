<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<form name="generateCouponCode" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/couponInterface" id="generateCouponCode" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center">
				<td colspan="2" style="background-color:lightblue" height="30">
					<font size=3>Coupon Code :: </font><font size=4 style="color:green">~$generatedCode`</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
			<span style="font-weight:bold;">
				The Coupon Code will expire on :: ~$expDateFormat`
			</span>
			<span style="font-weight:bold;padding-left:20px;">
				Coupon Usage Limit :: ~$usageLimit`
			</span>
		</div>
		<br>
		<table width=100% align=center>
			<tr class=formhead style="background-color:LightSteelBlue" height="25">
				<td width=~$durPerc`% align=center>Services/Durations</td>
				~foreach from=$serviceDurations key=k item=v`
					<td width=~$durPerc`% align=center>~$v`</td>
				~/foreach`
			</tr>
			~foreach from=$serviceNames key=k item=v`
			<tr height="50">
				<td align=center style="background-color:lightblue;font-size:18px;">~$v`</td>
				~foreach from=$serviceArray key=mainID item=servArr`
					~if $mainID eq $k`
						~foreach from=$servArr key=kk item=vv`
							<td align=center style="background-color:lightblue">
								~if $serviceDiscArr[$kk] neq '0'`
									<span style="text-align:center;font-size:20px;color:green;">~$serviceDiscArr[~$kk`]`%</span>
								~else`
									<span style="text-align:center;font-size:16px;color:#000;">~$serviceDiscArr[~$kk`]`%</span>
								~/if`
							</td>
						~/foreach`
					~/if`
				~/foreach`
			</tr>
			~/foreach`
		</table>
		<br>
		<div style="margin:0 auto;text-align:center;">
			<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/couponInterface?name=~$name`&cid=~$cid`" style="font-size:22px;">Click Here to Generate another Coupon Code</a>
		</div>
	</form>
	<script type="text/javascript">
		function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
		// To disable f5
		/* jQuery < 1.7 */
		$(document).bind("keydown", disableF5);
		/* OR jQuery >= 1.7 */
		$(document).on("keydown", disableF5);
	</script>
</body>
</html>
