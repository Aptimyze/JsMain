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
					<font size=3>Inbound Sales Campaign MIS</font>
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
		<form name="submitDetails" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/inboundSalesCampaignMis" id="submitDetails" method="POST">
			~if $errorMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsg`</div>
			~/if`
			~if $successMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:green">~$successMsg`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:left;padding:20px;padding-left:30%;font-size:12px;">
				<span style="font-weight:bold;">
					Select Report Format : &nbsp;
					<select name="selectedRange" >
						~foreach from=$reportDropDown key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
					<br><br>
					Select Year : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="selectedYear" >
						~foreach from=$yearDropDown key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
					<br><br>
					Select Month : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="selectedMonth" >
						~foreach from=$monthDropDown key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
					(only required if Day View is selected in Report Format)
					<br><br>
					Select Campaign :  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="campaignSelection" >
						~foreach from=$campaignDropDown key=k item=v`
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
			~if $selectedRange eq 'D'`
				<table width=100% align=center>
					~foreach from=$campaignData key=name item=date`
						<tr class=formhead style="background-color:LightGrey">
							<th colspan=31 style="font-size:14px;padding:7px;" align=center><span>Campaign ~$name`</span></td>
						</tr>
						<tr class=formhead style="background-color:LightSteelBlue;line-height:20px;">
		                ~foreach from=$dateDropDown key=k item=label`
		                	<td align=center>~$label`</td>
		                ~/foreach`
			        	</tr>
			        	<tr class=formhead style="background-color:LightOrange;font-weight:500;font-size:12px;line-height:2">
			        	~foreach from=$dateDropDown key=kk item=vv`
			        		~assign var=tempFlag value=0`
			        		~foreach from=$date key=kkk item=vvv`
					        	~if $vv eq $kkk`
					        		~assign var=tempFlag value=1`
					        		<td align=center>~$vvv`</td>
					        	~/if`
					        ~/foreach`
					        ~if $tempFlag eq 0`
					        	<td align=center>0</td>
					        ~/if`
					    ~/foreach`
					    </tr>
		        	~/foreach`
				</table>
			~else if $selectedRange eq 'Q'`
				<table width=100% align=center>
					~foreach from=$campaignData key=name item=quarter`
						<tr class=formhead style="background-color:LightGrey">
							<th colspan=4 style="font-size:14px;padding:7px;" align=center><span>Campaign ~$name`</span></td>
						</tr>
						<tr class=formhead style="background-color:LightSteelBlue;line-height:20px;">
		                ~foreach from=$quarterArr key=k item=label`
		                	<td align=center>~$label`</td>
		                ~/foreach`
			        	</tr>
			        	<tr class=formhead style="background-color:LightOrange;font-weight:500;font-size:12px;line-height:2">
			        	~foreach from=$quarterArr key=kk item=vv`
			        		~assign var=tempFlag value=0`
			        		~foreach from=$quarter key=kkk item=vvv`
					        	~if $vv eq $kkk`
					        		~assign var=tempFlag value=1`
					        		<td align=center>~$vvv`</td>
					        	~/if`
					        ~/foreach`
					        ~if $tempFlag eq 0`
					        	<td align=center>0</td>
					        ~/if`
					    ~/foreach`
					    </tr>
		        	~/foreach`
				</table>
			~else if $selectedRange eq 'M'`
				<table width=100% align=center>
					~foreach from=$campaignData key=name item=month`
						<tr class=formhead style="background-color:LightGrey">
							<th colspan=12 style="font-size:14px;padding:7px;" align=center><span>Campaign ~$name`</span></td>
						</tr>
						<tr class=formhead style="background-color:LightSteelBlue;line-height:20px;">
		                ~foreach from=$monthDropDown key=k item=label`
		                	<td align=center>~$label`</td>
		                ~/foreach`
			        	</tr>
			        	<tr class=formhead style="background-color:LightOrange;font-weight:500;font-size:12px;line-height:2">
			        	~foreach from=$monthDropDown key=kk item=vv`
			        		~assign var=tempFlag value=0`
			        		~foreach from=$month key=kkk item=vvv`
					        	~if $vv eq $kkk`
					        		~assign var=tempFlag value=1`
					        		<td align=center>~$vvv`</td>
					        	~/if`
					        ~/foreach`
					        ~if $tempFlag eq 0`
					        	<td align=center>0</td>
					        ~/if`
					    ~/foreach`
					    </tr>
		        	~/foreach`
				</table>
			~/if`
		~/if`
		<script type="text/javascript">
			function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
			//$(document).bind("keydown", disableF5);
			//$(document).on("keydown", disableF5);
		</script>
	</body>
</html>
