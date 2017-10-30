<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
	<head>
		<script language="JavaScript">
		<!--
		function MM_openBrWindow(theURL,winName,features)
		{
		window.open(theURL,winName,features);
		}
		function loadForm()
		{
			document.form1.submit();
		}
		//-->
		</script>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>JeevanSathi</title>
		</meta>
		<script src="~sfConfig::get('app_img_url')`/min/?f=/js/tracking_js.js"></script>
	</head>
	~if get_slot('optionaljsb9Key')|count_characters neq 0`
	~JsTrackingHelper::getHeadTrackJs()`
	~/if`
	<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		~include_partial('global/header')`
		<table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
			<tr width=100% border=1>
				<td width="25%" class="formhead" align="center"><a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcessList?cid=~$cid`">Go back</a></td>
			</tr>
		</table>
		~if $subMethod eq 'FOLLOWUP'`
		<form action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess" method="post">
			<table width=80% align="CENTER" >
				<tr align="CENTER" class="formhead">
					<td align="center" width="30%"> From :
						<select name="yy1">
							<option value="">-</option>
							~foreach from=$dateDropdown.yyarr item=year key=k`
							<option value="~$year`" ~if $yy1 eq $year` selected ~/if`>~$year`</option>
							~/foreach`
						</select>-
						<select name="mm1">
							<option value="">-</option>
							~foreach from=$dateDropdown.mmarr item=month key=k`
							<option value="~$month`" ~if $mm1 eq $month` selected ~/if`>~$month`</option>
							~/foreach`
						</select>-
						<select name="dd1">
							<option value="">-</option>
							~foreach from=$dateDropdown.ddarr item=day key=k`
							<option value="~$day`" ~if $dd1 eq $day` selected ~/if`>~$day`</option>
							~/foreach`
						</select>
					</td>
					<td align="center" width="30%"> To :
						<select name="yy2">
							<option value="">-</option>
							~foreach from=$dateDropdown.yyarr item=year key=k`
							<option value="~$year`" ~if $yy2 eq $year` selected ~/if`>~$year`</option>
							~/foreach`
						</select>-
						<select name="mm2">
							<option value="">-</option>
							~foreach from=$dateDropdown.mmarr item=month key=k`
							<option value="~$month`" ~if $mm2 eq $month` selected ~/if`>~$month`</option>
							~/foreach`
						</select>-
						<select name="dd2">
							<option value="">-</option>
							~foreach from=$dateDropdown.ddarr item=day key=k`
							<option value="~$day`" ~if $dd2 eq $day` selected ~/if`>~$day`</option>
							~/foreach`
						</select>
					</td>
					<td>
						<input type="hidden" name="cid" value="~$cid`">
						~if $subMethod eq 'FOLLOWUP'`
						<input type="hidden" name="flag" value="F">
						~else`
						<input type="hidden" name="flag" value="FF">
						~/if`
						<input type="submit" name="submit" value="Go">
					</td>
				</tr>
			</table>
		</form>
		~/if`
		<table width=100% align="CENTER">
			<tr align="CENTER">
				<td class="formhead" colspan="100%" height="23">
					<b>Profile List</b>
				</td>
			</tr>
			<tr align="CENTER">
				<td class="formhead" colspan="100%" height="23">
					<b>
					<font color="red">Page ~$currentPage` of ~$totalPages`</font>
					</b>
				</td>
			</tr>
			<tr align="CENTER">
				<td class="label" width=5% height="20"><b>Serial Number</b></td>
				<td class="label" width=5% height="20"><b>Status</b></td>
				<td class="label" width=9% height="20"><b>Name</b></td>
				<td class="label" width=9% height="20"><b>User Name</b></td>
				~if $subMethod eq 'RENEWAL_NOT_DUE'`
				<td class="label" width=9% height="20"><b>Date of Payment / Expiry</b></td>
				<td class="label" width=10% height="20"><b>Pack Purchased with Duration and Add Ons</b></td>
				<td class="label" width=5% height="20"><b>No of EOIs sent</b></td>
				<td class="label" width=5% height="20"><b>No of direct contacts viewed post purchase</b></td>
				<td class="label" width=10% height="20"><b>Photo</b></td>
				<td class="label" width=10% height="20"><b>Address</b></td>
				~elseif $subMethod eq 'NEW_FAILED_PAYMENT'`
				<td class="label" width=9% height="20"><b>Date of Latest Payment Try</b></td>
				<td class="label" width=10% height="20"><b>Services Selected</b></td>
				<td class="label" width=5% height="20"><b>Net Amount</b></td>
				<td class="label" width=5% height="20"><b>Discount</b></td>
				<td class="label" width=10% height="20"><b>Payment Option Selected</b></td>
				<td class="label" width=10% height="20"><b>Phone Nos</b></td>
				<td class="label" width=10% height="20"><b>City</b></td>
				~else`
				<td class="label" width=10% height="20"><b>Phone No</b></td>
				~if $subMethod eq 'FTA'`
				<td class="label" width=10% height="20"><b>Photo</b></td>
				<td class="label" width=10% height="20"><b>Number of Photo Requests Received</b></td>
				<td class="label" width=10% height="20"><b>Phone Verified</b></td>
				<td class="label" width=10% height="20"><b>Number of Sent EoIs</b></td>
				<td class="label" width=10% height="20"><b>Posted By</b></td>
				~/if`
				~if $subMethod eq 'NEW_PROFILES' || $subMethod eq 'FIELD_SALES'`
				<td class="label" width=10% height="20"><b>Mark Phone No - <br> Wrong / Unreachable</b></td>
				~/if`
				~if $subMethod neq 'HANDLED' && $subMethod neq 'FAILED_PAYMENT' && $subMethod neq 'PAYMENT_HITS'`
				~if $subMethod neq 'FTA'`
				<td class="label" width=5% height="20"><b>Age</b></td>
				~/if`
				<td class="label" width=5% height="20"><b>Gender</b></td>
				~elseif $subMethod eq 'FAILED_PAYMENT'`
				<td class="label" width=10% height="20"><b>No. of time payment tried</b></td>
				~else if $subMethod eq 'HANDLED'`
				<td class="label" width=10% height="20"><b>Allocation Date</b></td>
				<td class="label" width=10% height="20"><b>Last Handled Date</b></td>
				~/if`
				<td class="label" width=10% height="20"><b>Registration Date and Time (in IST)</b></td>
				~if $subMethod neq 'FTA'`
				<td class="label" width=10% height="20"><b>Last Online Date</b></td>
				<td class="label" width=10% height="20"><b>City</b></td>
				~/if`
				~if $subMethod eq 'FAILED_PAYMENT' or $subMethod eq 'PAYMENT_HITS' or $subMethod eq 'FOLLOWUP' or $subMethod eq 'SUB_EXPIRY' or $subMethod eq 'HANDLED' or $subMethod eq 'FFOLLOWUP'`
				<td class="label" width=10% height="20"><b>Address</b></td>
				~/if`
				~if $subMethod eq 'SUB_EXPIRY' || $subMethod eq 'UPSELL' || $subMethod eq 'RENEWAL'`
				<td class="label" width=10% height="20"><b>Service Expiry Date</b></td>
				~else if $subMethod eq 'FOLLOWUP' || $subMethod eq 'NEW_PROFILES' || $subMethod eq 'HANDLED' || $subMethod eq 'FIELD_SALES'`
				~if $subMethod eq 'NEW_PROFILES' || $subMethod eq 'FOLLOWUP'`
				<td class="label" width="10%" height="20"><b>Ever Paid</b></td>
				~/if`
				<td class="label" width=10% height="20"><b>Discount</b></td>
				~/if`
				~if $subMethod eq 'FIELD_SALES'`
				<td class="label" width=10% height="20"><b>Pincode</b></td>
				<td class="label" width=10% height="20"><b>Address</b></td>
				~/if`
				~/if`
			</tr>
			~if $profilesArr`
			~assign var=num value=$serialNo+1`
			~foreach from=$profilesArr item=profiles key=k`
			~if $subMethod eq 'NEW_PROFILES'`
			<tr ~if $profiles.PROFILE_TYPE eq 'O'` class=label ~else` class=fieldsnew ~/if` align="CENTER"> <!-- fieldnew:white-->
			~else`
			<tr ~if stristr($profiles.SUBSCRIPTION,'F') || stristr($profiles.SUBSCRIPTION,'D')` class=label ~else` class=fieldsnew ~/if` align="CENTER">
				~/if`
				<td height="21" width="5%" align=center> ~$num++` </td>
				<td class="status" height="21" width="5%" align=center id="~$k`"> ~$profiles.STATUS` </td>
				<td height="21" width="5%" align=left>~if $profiles.NAME eq ''` --NA-- ~else` ~$profiles.NAME` ~/if` </td>
				<td height="21" width="10%" align="left">
					<a href="#" onclick="MM_openBrWindow('~sfConfig::get('app_site_url')`/operations.php/crmAllocation/agentAllocation?name=~$agentName`&username=~$profiles.USERNAME`&profileid=~$profiles.PROFILEID`&cid=~$cid`&subMethod=~$subMethod`&orders=~$orders`&pchecksum=~$profiles.CHECKSUM`','','width=800,height=600,scrollbars=yes'); return false;">~$profiles.USERNAME`<br>~$profiles.EMAIL`</a>
					~if $profiles.ACTIVATED eq 'D'` <font color=red> D </font> ~/if`
				</td>
				~if $subMethod eq 'RENEWAL_NOT_DUE'`
				<td height="21" width="10%" >~$profiles.ACTIVATED_ON`<br>~$profiles.EXPIRY_DT`</td>
				<td height="21" width="10%" >~$profiles.SERVICE_PURCHASE`</td>
				<td height="21" width="10%" >~if $profiles.EOI_SENT`~$profiles.EOI_SENT`~else`0~/if`</td>
				<td height="21" width="10%" >~if $profiles.CONTACT_VIEWED`~$profiles.CONTACT_VIEWED`~else`0~/if`</td>
				<td height="21" width="5%" >
					~if $profiles.HAVEPHOTO eq 'Y'` Yes ~else if $profiles.HAVEPHOTO eq 'U'` Scrn ~else` No ~/if`
				</td>
				<td height="21" width="10%" >~$profiles.ADDRESS`</td>
				~else`
				~if $subMethod eq 'NEW_FAILED_PAYMENT'`
				<td height="21" width="10%" >~$profiles.ENTRY_DT`</td>
				<td height="21" width="10%" >~$profiles.SERVICES`</td>
				<td height="21" width="10%" >
					~if $profiles.NET_AMOUNT`
					~$profiles.NET_AMOUNT`  ~if $profiles.CURRENCY eq DOL`$~else`Rs~/if`
					~/if`
				</td>
				<td height="21" width="10%" >
					~if $profiles.DISCOUNT`
					~$profiles.DISCOUNT`  ~if $profiles.CURRENCY eq DOL`$~else`Rs~/if`
					~/if`
				</td>
				<td height="21" width="10%" >~$profiles.PAYMENT_OPTION_SELECTED`</td>
				~/if`
				<td height="21" width="10%" align=left>
					~if $profiles.RES_NO neq '' || $profiles.ALTERNATE_NO neq ''` R: ~/if`
					~if $profiles.RES_NO neq ''`
					<font class=blue> ~$profiles.RES_NO` </font>
					~/if`
					~if $profiles.RES_NO neq '' && $profiles.ALTERNATE_NO neq ''` , ~/if`
					~if $profiles.ALTERNATE_NO neq ''`
					<font class=blue>~$profiles.ALTERNATE_NO` </font>
					~/if`
					<br>
					~if $profiles.MOB_NO neq '' || $profiles.ALT_MOBILE neq ''` M: ~/if`
					~if $profiles.MOB_NO neq ''`
					<font class=red> ~$profiles.MOB_NO` </font>
					~/if`
					~if $profiles.MOB_NO neq '' && $profiles.ALT_MOBILE neq ''` , ~/if`
					~if $profiles.ALT_MOBILE neq ''`
					<font class=red>~$profiles.ALT_MOBILE` </font>
					~/if`
					
				</td>
				~if $subMethod eq 'FTA'`
				<td height="21" width="5%">
					~if $profiles.HAVEPHOTO eq 'Y'` Yes ~else if $profiles.HAVEPHOTO eq 'U'` Scrn ~else` No ~/if`
				</td>
				<td height="21" width="5%">~if $profiles.PHOTO_REQ_REC`~$profiles.PHOTO_REQ_REC`~else`0~/if`</td>
				<td width=10% height="20">
					~if $profiles.MOB_STATUS eq 'Y' || $profiles.LANDL_STATUS eq 'Y' || $profiles.ALT_MOB_STATUS eq 'Y'` Yes ~else` No~/if`
				</td>
				<td width=10% height="20">~if $profiles.EOI_SENT`~$profiles.EOI_SENT`~else`0~/if` </td>
				<td width=10% height="20">~$profiles.RELATION`</td>
				~/if`
				~if $subMethod eq 'NEW_PROFILES' || $subMethod eq 'FIELD_SALES'`
				<td height="21" width="5%">
					<a href="#" onclick="MM_openBrWindow('~sfConfig::get('app_site_url')`/crm/invalid_phone.php?username=~$profiles.USERNAME`&profileid=~$profiles.PROFILEID`&cid=~$cid`&flag=~$subMethod`','','width=200,height=200,scrollbars=yes'); return false;"class="class4">Click here</a>
				</td>
				~/if`
				~if $subMethod neq 'HANDLED' && $subMethod neq 'FAILED_PAYMENT' && $subMethod neq 'PAYMENT_HITS' && $subMethod neq 'NEW_FAILED_PAYMENT'`
				~if $subMethod neq 'FTA'`
				<td width=5% height="20">~$profiles.AGE`</td>
				~/if`
				<td height="21" width="5%" align=center>~$profiles.GENDER`</td>
				~elseif $subMethod eq 'FAILED_PAYMENT'`
				<td height="21" width="10%" align=center>~$profiles.TIMES_TRIED`</td>
				~else if $subMethod eq 'HANDLED'`
				<td height="21" width="10%" align=center>~$profiles.ALLOT_TIME`</td>
				<td height="21" width="10%" align=center>~$profiles.CONVINCE_TIME`</td>
				~/if`
				~if $subMethod neq 'NEW_FAILED_PAYMENT'`
				<td height="21" width="10%" align=center>~$profiles.ENTRY_DT`</td>
				~/if`
				~if $subMethod neq 'FTA'`
				~if $subMethod neq 'NEW_FAILED_PAYMENT'`
				<td height="21" width="10%" align=center>~$profiles.LAST_LOGIN_DT`</td>
				~/if`
				<td height="21" width="10%" align=center>~$profiles.CITY_INDIA`</td>
				~/if`
				~if $subMethod eq 'FAILED_PAYMENT' or $subMethod eq 'PAYMENT_HITS' or $subMethod eq 'FOLLOWUP' or $subMethod eq 'SUB_EXPIRY' or $subMethod eq 'HANDLED' or $subMethod eq 'FFOLLOWUP'`
				<td height="21" width="10%" align=center>~$profiles.ADDRESS`</td>
				~/if`
				~if $subMethod eq 'SUB_EXPIRY' || $subMethod eq 'UPSELL' || $subMethod eq 'RENEWAL'`
				<td height="21" width="10%" align=center>~$profiles.EXPIRY_DT`</td>
				~else if $subMethod eq 'FOLLOWUP' || $subMethod eq 'NEW_PROFILES' || $subMethod eq 'HANDLED' || $subMethod eq 'FIELD_SALES'`
				~if $subMethod eq 'NEW_PROFILES' || $subMethod eq 'FOLLOWUP'`
				<td height="20" width="10%">~$profiles.EVER_PAID`</td>
				~/if`
				<td height="21" width="10%" align=center>~$profiles.DISCOUNT`</td>
				~/if`
				~if $subMethod eq 'FIELD_SALES'`
				<td height="21" width="10%" align=center>~$profiles.PINCODE`</td>
				<td height="21" width="10%" align=center>~$profiles.ADDRESS`</td>
				~/if`
				~/if`
			</tr>
			~/foreach`
			~else`
			<tr>
				<td  align="center" colspan=100% class=fieldsnew>
					<font color="red">
					There are no records to show
					</font>
				</td>
			</tr>
			~/if`
			<tr bgcolor="#fbfbfb">
				<td colspan="100%" height="21"></td>
			</tr>
			<tr>
				<td colspan="100%" height="21">&nbsp;</td>
			</tr>
		</table>
		<table>
			<tr bgcolor="#fbfbfb">
				<td colspan="100%" height="21">
					~$pageLinkVar|decodevar`
				</td>
			</tr>
		</table>
		<br><br>
		~include_partial('global/footer')`
		~if get_slot('optionaljsb9Key')|count_characters neq 0`
		~JsTrackingHelper::setJsLoadFlag(1)`
		~/if`
	</body>
	<script src="~sfConfig::get('app_img_url')`/min/?f=/js/timetracker_js.js"></script>
	~if get_slot('optionaljsb9Key')|count_characters neq 0`
	~JsTrackingHelper::getTailTrackJs(0,true,2,"https://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
	~/if`
	<script>
	var objtnm = new tnm();
	objtnm.tnmPageId="~get_slot('optionaljsb9Key')`";
	$(document).ready(function(){
	window.onload = function () {objtnm.init();}
	window.onunload = function() { objtnm.LogCatch.call(objtnm);}
	});
	</script>
	<script>
		var update_online_status = function (onlineProfiles) {
            $(".status").each(function(){
                var value = $.trim($(this).text());
		        if(value == "ONLINE"){
                    $(this).text("OFFLINE");
				}
            });
            var profilesArr = $.parseJSON(onlineProfiles);
            profilesArr.forEach(function (profile,index) {
                $("#" + profile).text("ONLINE");
            });
        };

        var get_online_profiles = function() {
            var profilesArr = "~$jsonProfilesArr`";
            var url = "/operations.php/crmApi/ApiGetOnlineProfilesV1/";
            $.ajax({
                type: 'POST',
                url: url,
                data:{
                    profilesid: profilesArr
                },
                success: function(data) {
                    update_online_status(data);
                }
            });
        };

        setInterval(get_online_profiles,~CommonConstants::$REFRESH_INTERVAL_RATE`);
	</script>
</html>
