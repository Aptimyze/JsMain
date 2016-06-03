<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
		<style type="text/css">
			td {
				font-size: 12px;
			    max-width: 100px;
			    min-width: 0;
			    width: 8.33%;
			    word-wrap: break-word;
			}
		</style>
	</head>
	<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Apple Payments Logging MIS</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		~if $flag eq '0'`
		<form name="submitDetails" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/applePaymentsLoggingMis" id="submitDetails" method="POST">
			~if $errorMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsg`</div>
			~/if`
			~if $successMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:green">~$successMsg`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Select Year for which data is required : &nbsp;
					<select name="selectedYear" >
						~foreach from=$yearDropDown key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
					Select Month for which data is required : &nbsp;
					<select name="selectedMonth" >
						~foreach from=$monthDropDown key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
				</span>
			</div>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;width:25%;" type="submit" name="submit" value="Submit">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
			<br>
			<br>
		</form>
		~/if`
		~if $flag eq '1'`
		<table width=100% align=center>
			~foreach from=$serviceNames key=k item=name`
			<tr class=formhead style="background-color:LightGrey">
				<th colspan=12 style="font-size:14px;padding:7px;" align=center><span>~$name`</span>&nbsp;&nbsp;&nbsp;<span style="font-weight: 300;font-style: italic;">(INR Count : ~if $currencyCount.$k.RS`~$currencyCount.$k.RS`~else`0~/if`, INR Value(100%) : ~if $currencyCount.$k.RS_VAL`~$currencyCount.$k.RS_VAL`~else`0~/if`, INR Value(70%) : ~if $currencyCount.$k.RS_VAL_70`~$currencyCount.$k.RS_VAL_70`~else`0~/if` || USD Count : ~if $currencyCount.$k.DOL`~$currencyCount.$k.DOL`~else`0~/if`, USD Value(100%) : ~if $currencyCount.$k.DOL_VAL`~$currencyCount.$k.DOL_VAL`~else`0~/if`, USD Value(70%) : ~if $currencyCount.$k.DOL_VAL_70`~$currencyCount.$k.DOL_VAL_70`~else`0~/if`)</span></td>
			</tr>
			<tr class=formhead style="background-color:LightSteelBlue;line-height:20px;">
                <td align=center>Order Date Time</td>
                <td align=center>Payment Date Time</td>
                <td align=center>Order ID</td>
                <td align=center>Bill ID</td>
                <td align=center>Profile ID</td>
                <td align=center>Username</td>
                <td align=center>Main Service ID</td>
                <td align=center>Currency</td>
                <td align=center>Transaction Amount(100%)</td>
                <td align=center>Transaction Amount(70%)</td>
                <td align=center>User Email</td>
                <td align=center>IP Address</td>
        	</tr>
        	~foreach from=$paidDetailsArr key=key item=value`
	        	~if $k eq $key`
	        	~foreach from=$value key=kk item=vv`
	        	<tr class=formhead style="background-color:LightOrange;font-weight:500;font-size:12px;line-height:2">
        			<td align=center>~$vv.0`</td>
        			<td align=center>~$vv.1`</td>
        			<td align=center>~$vv.2`</td>
        			<td align=center>~$vv.3`</td>
        			<td align=center>~$vv.4`</td>
        			<td align=center>~$vv.5`</td>
        			<td align=center>~$vv.6`</td>
        			<td align=center>~$vv.7`</td>
        			<td align=center>~$vv.8`</td>
        			<td align=center>~$vv.9`</td>
        			<td align=center>~$vv.10`</td>
        			<td align=center>~$vv.11`</td>
	        	</tr>
	        	~/foreach`
	        	~/if`
        	~/foreach`        
        	~/foreach`
		</table>
		~/if`
		<script type="text/javascript">
			function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
			//$(document).bind("keydown", disableF5);
			//$(document).on("keydown", disableF5);
		</script>
	</body>
</html>
