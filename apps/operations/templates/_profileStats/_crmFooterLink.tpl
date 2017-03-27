<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class=mediumblack height="2">
			<div align="center">
				~if $linkArr.BILLING eq 'Y'`
				<tr class=fieldsnew>
					<td align=center>
						<a href="~sfConfig::get('app_site_url')`/billing/search_user.php?cid=~$cid`&pid=~$profileid`&criteria=uname&phrase=~$username`" target="_blank"><font size='2'>Click here to enter billing details </font></a><br>
					</td>
				</tr>
				~/if`
				~if $linkArr.ARAMEX eq 'Y'`
				<tr class=fieldsnew>
					<td align=center>
						<a href="~sfConfig::get('app_site_url')`/crm/paymentcontact_crm.php?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here to enter PickUp details </font></a><br>
					</td>
				</tr>
				<tr class=fieldsnew>
					<td align=center>
						<a href="~sfConfig::get('app_site_url')`/crm/easy_bill_link.php?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here for Easy Bill request </font></a><br>
					</td>
				</tr>
				<tr class=fieldsnew>
					<td align=center>
						<img border="0" align='absmiddle' title="Blue Dart COD" src="~sfConfig::get('app_site_url')`/jsadmin/temp_images/blue_dart_logo.gif"><a href="~sfConfig::get('app_site_url')`/crm/bluedart_pickup_form.php?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here for Blue Dart COD request </font></a><br>
					</td>
				</tr>
				~/if`
				~if $linkArr.ARAMEX eq 'Y' || $linkArr.OFFLINE_EXCLUSIVE eq 'Y'`
				<tr class=fieldsnew>
					<td align=center>
						<a href="~sfConfig::get('app_site_url')`/crm/generate_ivr_code.php?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here to generate IVR code </font></a><br>
					</td>
				</tr>
				~/if`
				<tr class=fieldsnew>
					<td align=center>
						<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/editDppInterface?profileChecksum=~$checksum`&cid=~$cid`" target="_blank"><font size='2'>Edit desired partner profile for this user</font></a><br>
					</td>
				</tr>
				<!-- <tr class=fieldsnew>
					<td align=center>
								<a href="~sfConfig::get('app_site_url')`/search/partnermatches?checksum=~$checksum`&echecksum=~$echecksum`&profileChecksum=~$checksum`" target="_blank"><font size='2'>View Your Partner Matches</font></a><br>
							</td>
				</tr>-->
				~if $set_filter or $isAlloted`
				<tr class=fieldsnew>
					<td align=center>
						<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/editDppInterface?profileChecksum=~$checksum`&cid=~$cid`" target="_blank"><font size='2'>Click here to set filter for this user</font></a><br>
					</td>
					<tr>
						~/if`
						<!-- ADDED  -->
						<!--
						<tr class=fieldsnew>
							<td align=center>
									<a href="~sfConfig::get('app_site_url')`/crm/mail_to_users.php?cid=~$cid`&profileid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here to send mail to this user</font></a><br>
								</td>
						</tr>-->
						~if $online_payment or $isAlloted`
						<tr class=fieldsnew>
							<td align=center>
								<a href="~sfConfig::get('app_site_url')`/crm/online_pickup.php?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here for online payment request</font></a><br>
							</td>
						</tr>
						~/if`
						~if $isAlloted`
						<tr class=fieldsnew>
							<td align=center>
								<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/crmSmsFunctionalityInterface?cid=~$cid`&profileid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Send SMS to this profile user</font></a><br>
							</td>
						</tr>
						~/if`
						<!-- ADDED  -->
						<!--
						<tr class=fieldsnew>
							<td align=center>
									<a href="~sfConfig::get('app_site_url')`/crm/phone_number_validation.php?cid=~$cid`&profileid=~$profileid`" target="_blank"><font size='2'>Click here to verify Contact Number(s) </font></a><br>
								</td>
						</tr>-->
						<tr class="fieldsnew">
							<td align="center">
								<a href="~sfConfig::get('app_site_url')`/crm/show_matchalert.php?cid=~$cid`&pid=~$profileid`&crmback=admin" target="_blank"><font size='2'>Click here to view Daily Recommendations sent to this user</font></a><br>
							</td>
						</tr>
						~if $isAlloted`
						<tr class="fieldsnew">
							<td align="center">
								<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/documentCollection?cid=~$cid`&pid=~$profileid`&username=~$username`&crmback=admin" target="_blank"><font size='2'>Send document collection receipt</font></a><br>
							</td>
						</tr>
						~/if`
						<tr class="fieldsnew">
							<td align="center">
								<a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/dppMatchesShowStats?cid=~$cid`&pid=~$profileid`&username=~$username`" target="_blank"><font size='2'>Click here to View Dpp Matches</font></a><br>
							</td>
						</tr>

					</div>
				</td>
			</tr>
		</table>