<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>JeevanSathi</title>
		</meta>
	</head>
	~include_partial('global/header')`
	<table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
		<tr width=100% border=1>
			<td width="25%" class="formhead" align="center">Outbound Module</td>
		</tr>
	</table>
	<br>
	~if $allocatedSuccessfully`
	<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width=760 align="CENTER" >
			<tr>
				<td height="23" class="formhead" align="center">
					Entry for <font color="blue">~$username`</font> is done.
				</td>
				<tr>
					<td height="23" class="formhead" align="center">
						<a href="" onclick= "window.close()">Close Window</a>
					</td>
				</tr>
			</table>
			<script language='JavaScript'>
			opener.location.reload(true);
			</script>
			~else`
			~if $details.WAS_PAID`
			<table width=760 align="CENTER">
				<tr>
					<td>
						<center class=red>This member has some payment history</center>
						<center class=label><a href="~sfConfig::get('app_site_url')`/billing/search_user.php?cid=~$cid`&phrase=~$username`&criteria=uname" target="_blank">Click here to see the billing details of this member</a></center>
						<br>
					</td>
				</tr>
			</table>
			~/if`
			<center class=red> ~$details.ACTIVE_STATUS_MESSAGE|decodevar`</center>
			~if $orders eq 'Y' && $orderDetails`
			<table width=90% align=center>
				<tr class=label>
					<td align=center>OrderID</td>
					<td align=center>Date</td>
					<td align=center>Payment Mode</td>
					<td align=center>Service</td>
				</tr>
				~foreach from=$orderDetails item=orderDetailsVal key=orderDetailsKey`
				<tr class=label>
					<td align=center>~$orderDetailsVal.ORDERID`</td>
					<td align=center>~$orderDetailsVal.ENTRY_DT`</td>
					<td align=center>~$orderDetailsVal.PAYMODE`</td>
					<td align=center>~$orderDetailsVal.SERVICE`</td>
				</tr>
				~/foreach`
			</table>
			<br>
			~/if`
			<form name=insertForm method="post" action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/agentAllocation" onsubmit="return validate();">
				<input type=hidden name=orders value="~$orders`">
				<input type=hidden name=subMethod value="~$subMethod`">
				<input type=hidden name=cid value="~$cid`">
				<input type=hidden name=profileid value="~$profileid`">
				<input type=hidden name=username value="~$username`">
				<input type=hidden name=email value="~$details.EMAIL`">
				<input type=hidden name=resPhone value="~$details.PHONE_WITH_STD`">
				<input type=hidden name=mobPhone value="~$details.PHONE_MOB`">
				<input type=hidden name=name value="~$agentName`">
				<table width=90% align="center" cellspacing=2 cellpadding=1 border=0>
					<tr>
						<td class=label align="right" colspan="2"><a href="#" onclick="openBrWindow('~sfConfig::get('app_site_url')`/crm/do_not_call.php?username=~$username`&profileid=~$profileid`&cid=~$cid`','','width=200,height=200,scrollbars=yes'); return false;">Remove this profile from calling FOREVER</a></td>
					</tr>
					~if $checkDetails eq 'Y'`
					<tr>
						<td class=label align="left" width=30%>
							<font color="red"> Please enter the details
						</td>
					</tr>
					~/if`
					<tr>
						<td class=label align="left" width=30%> Name</td>
						<td class=fieldsnew width=70%>~if $details.CRM_NAME eq ''` --NA-- ~else` ~$details.CRM_NAME`  ~/if` </a></td>
					</tr>
					<tr>
						<td class=label align="left" width=30%> Username</td>
						<td class=fieldsnew width=70%> ~$username` </a></td>
					</tr>
					<tr>
						<td class=label align="left" width=30%> Email</td>
						<td class=fieldsnew width=70%> ~$details.EMAIL` </a></td>
					</tr>
                                        <tr>
                                                <td class=label align="left" width=30%> ISD No.</td>
                                                <td class=fieldsnew> ~$details.ISD` </td>
                                        </tr>
					<tr>
						<td class=label align="left" width=30%> Phone No. (Residence)</td>
						<td class=fieldsnew> ~$details.PHONE_WITH_STD` </td>
					</tr>
					<tr>
						<td class=label width=30%> Phone No. (Mobile) </td>
						<td class=fieldsnew> ~$details.PHONE_MOB` </td>
					</tr>
					<tr>
						<td class=label width="30%">
						CRM FollowUp No.</td>
						<td class=fieldsnew><input type="text" name="alternatePhone" value="~if $alternatePhone`~else`~$details.ALTERNATE_NO`~/if`" class="textbox"></td>
					</tr>
					<tr>
		                <td class=label width="30%">Discount Negotiation Percentage(%)</td>
		                <td class=fieldsnew><input type="text" name="discountNegVal" value="" class="textbox discountNegVal"></td>
		            </tr>
					<tr>
						<td class=label width="30%">
							<input type="hidden" name="paidChecked" value="~$paidProfile`">
						Follow Up </td>
						~if $paidProfile eq ''`
						<input type="hidden" name="follow" value="F">
						<td width="28" class= "fieldsnew"><input type="checkbox" name="follows" value="F" checked disabled></td>
						~else`
						<td width="28" class= "fieldsnew"><input type="checkbox" name="follow" value="F"></td>
						~/if`
					</tr>
					<tr>
						<td class=label>
							~if $checkDetails eq "Y"`
							<font color="red"> Follow Up Time~if $paidProfile eq ''`*~/if`<font> ~else` Follow Up Time~if $paidProfile eq ''`*~/if`
							~/if`
						</td>
						<td class=fieldsnew>
							<select name="follow_date" class="textbox">
								~$followupDate.follow_time|decodevar`
							</select> at Hrs.
							<select name="follow_hour" class="textbox">
								~$followupDate.hour|decodevar`
							</select> Min.
							<select name="follow_min" class="textbox">
								~$followupDate.min|decodevar`
							</select>
						</td>
					</tr>
					<tr>
						<td class=label width="30%">
							~if $checkDetails eq "Y"`<font color="red"> Disposition*</font>~else` Disposition*~/if`
						</td>
						<td class=fieldsnew>
							<select name="willPay" class="textbox" onChange="PopSPEC(this,'willPay','reason');">
								<option value="" selected>Select any one option</option>
								~$willPay|decodevar`
							</select>&nbsp;&nbsp;Validation*:
							<select name="reason" class="textbox">
								<option  value="" selected>Select any One Option</option>
								~$reason`
							</select>
						</td>
					</tr>
					<tr>
						<td width="30%" class="label"> ~if $checkDetails eq "Y"`<font color="red"> Comments*<font> ~else` Comments*
						~/if`</td>
						<td class=fieldsnew height="26">
							<textarea name="comments"  class="textbox" cols="55" rows="3" >~$comments`</textarea>
						</td>
					</tr>
					<tr>
						<td class=label width="30%" height="2">&nbsp;</td>
						<td colspan="2" height="2" class=fieldsnew>&nbsp;
							<!--<input type="hidden" name="subs_expiry" value="~$subs_expiry`">-->
							<input type="submit" name="submit" value="submit">
						</td>
					</tr>
					<tr>
						<td colspan=2 align=center><br><hr></td>
					</tr>
				</form>
				<tr>
					<table width=100% align="CENTER" class="fieldsnew">
						~if $history.show_IM eq 'Y'`
						<tr align="CENTER" bgcolor="#fgfgfg">
							<td class="formhead" colspan="4" height="23"><b><font size="5" color="green">Incentive Multiplier : ~$history.IM`</font></b></td>
						</tr>
						~/if`
						<tr align="CENTER">
							<td class="formhead" colspan="4" height="23"><b><font size="3" color="black">History</font></b></td>
						</tr>
						<tr align="CENTER">
							<td class="label" width=5% height="20"><b>S.No.</b></td>
							<td class="label" width="15" height="20"><b>Handled By</b></td>
							<td class="label" width=20% height="21"><b>Date</b></td>
							<td class="label" width=15% height="21"><b>Mode</b></td>
						</tr>
						~foreach from=$history item=historyRow key=k`
						<tr align="CENTER" bgcolor="#fbfbfb" >
							<td height="20" align="CENTER" width="5%">~$historyRow.SNO`</td>
							<td height="20" width="15%">~$historyRow.NAME`</td>
							<td height="21" width="20%">~$historyRow.DATE`</td>
							<td height="21" width="15%" align="LEFT">~$historyRow.MODE`</td>
						</tr>
						~if $historyRow.COMMENTS neq ''`
						<tr bgcolor="#fgfgfg" >
							<td height="21" align="CENTER" width="5%">&nbsp;</td>
							<td width=20% height="21"><b>Comments</b></td>
							<td height="21" colspan="4">~$historyRow.COMMENTS`</td>
						</tr>
						~/if`
						<tr class="label">
							<td height="21" align="CENTER" width="5%" colspan=7>&nbsp;</td>
						</tr>
						~/foreach`
					</table>
				</tr>
				<tr>
					<td colspan=2 align=center><br><hr></td>
				</tr>
				<tr>
					<td colspan=2 align=center>
						~$pmsg|decodevar`
						<td>
						</tr>
					</table>
					<table width=100% align="center">
						<tr class=fieldsnew>
							<td align=center><a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/editDppInterface?profileChecksum=~$profileChecksum`&cid=~$cid`" target="_blank">Edit desired partner profile for this user</a><br><br></td>
						</tr>
						<!--<tr class=fieldsnew>
								<td align=center><a href="~sfConfig::get('app_site_url')`/search/partnermatches?checksum=~$checksum`&echecksum=~$echecksum`&profileChecksum=~$profileChecksum`" target="_blank">View Your Partner Matches</a><br><br></td>
						</tr>-->
						<tr class=fieldsnew>
							<td align=center><a href="~sfConfig::get('app_site_url')`/crm/mail_to_users.php?cid=~$cid`&profileid=~$profileid`&username=~$username`" target="_blank">Click here to send mail to this user</a><br><br></td>
						</tr>
						~if $set_filter or $ISALLOTED eq "Y"`
						<tr class=fieldsnew>
							<td align=center><a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/editDppInterface?profileChecksum=~$profileChecksum`&cid=~$cid`" target="_blank">Click here to set filter for this user</a><br><br></td>
						</tr>
						~/if`
						~if $online_payment or $ISALLOTED eq "Y"`
						<tr class=fieldsnew>
							<td align=center><a href="~sfConfig::get('app_site_url')`/crm/online_pickup.php?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank">Click here for online payment request </a><br><br></td>
						</tr>
						~/if`
						<tr class=fieldsnew>
							<td align=center><a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/crmSmsFunctionalityInterface?cid=~$cid`&profileid=~$profileid`&username=~$username`" target="_blank">Send SMS to this profile user</a><br><br></td>
						</tr>
						<tr class=fieldsnew>
							<td align=center><a href="~sfConfig::get('app_site_url')`/crm/phone_number_validation.php?cid=~$cid`&profileid=~$profileid`" target="_blank">Click here to verify Contact Number(s) </a></td>
						</tr>
						~if $ISALLOTED eq "Y"`
						<tr class="fieldsnew">
							<td align="center"><a href="~$SITE_URL`/operations.php/crmInterface/documentCollection?cid=~$cid`&pid=~$pid`&username=~$USERNAME`&crmback=admin" target="_blank">Send document collection receipt</a></td>
						</tr>
						~/if`
					</table>
					~/if`
					<br><br>
					~include_partial('global/footer')`
				</body>
				<script type="text/javascript">
	              $(document).ready(function(){
	                $(".discountNegVal").bind('focusout', function(){
	                  var floatVal = /^\s*(\+|-)?((\d+(\d+)?)|(\d+))\s*$/;
	                  var value  = $(this).val();
	                  if(floatVal.test(value)){
	                    if(value > 100 || value <= 0){
                    	  alert("Discount cannot be greater than 100%! or less than/equal to 0 & should contain decimal number");
	                      $(this).val('');
	                    }
	                  } else if (value == '') {
	                    $(this).val('');
	                  } else {
	                    alert('Please enter a numeric value!');
	                    $(this).val('');
	                  }
	                });
	                $(".discountNegVal").bind('focusin', function() {
	                  $(window).keydown(function(event){
	                    if(event.keyCode == 13) {
	                      event.preventDefault();
	                      return false;
	                    }
	                  });
	                });
	              });
	            </script>
			</html>
