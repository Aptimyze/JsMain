~include_partial("global/mailerheader")`

<body>
	<table style="max-width:600px; border:1px solid #ebebeb;" align="center" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="600" bgcolor="#f6f6f6">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td height="10" bgcolor="#ffffff"></td>
					</tr>
					<tr>
						<td align="center" bgcolor="#ffffff">
							<table width="94%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
								<tr>
									<td align="left" width="45%">
										<a href="~JsConstants::$siteUrl`" target="_blank" style="color:#f7f6f6;  width: inherit; text-decoration:none;">
											<img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/logo1.png" width="100%" alt="Jeevansathi.com" style="font-family:Tahoma, Geneva, sans-serif; font-size:18px; color:#f14f68; max-width:185px;"/>
										</a>
									</td>
									<td align="right" width="55%">
										<table  border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td align="center" width="25">
													<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/icon1.gif" alt="">
												</td>
												<td align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#625f5f;">
													1-800-419-6299</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td height="15" bgcolor="#ffffff" style="border-bottom:#e7e6e6 solid 1px;"></td>
						</tr>
						<tr>
							<td height="25" align="center" bgcolor="#f8f7f7"><table width="90%" border="0" cellspacing="0" cellpadding="0">				
								<tr>
									<td align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#505050;">Hi,</td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#505050;">Jeevansathi.com has lakhs of profiles that are active daily and every search returns hundreds of results.</td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#505050;">It's very easy for your profile to get lost in this sea of results. Fortunately, now you have a way to shine at the top of these results and get noticed by relevant matches<br /></td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#505050;">Featured profile allows you to do this. You will have increased visibility to the people that are searching for profiles like you. More profile views means more chances of getting contacted by the other party. It's really that simple<br /></td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#505050;">If somebody is searching for a profile like you , we will try to ensure that you are the first one shown to them.
									</td>
								</tr>
								<tr>
									<td height="27"></td>
								</tr>
								<tr>
									<td align="center">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="250" height="38" align="center" bgcolor="#34495e" style="font-family:arial, Geneva, sans-serif; font-size:13px; color:#ffffff;">This is how you will feature on mobiles</td>
											</tr>
											<tr>
												<td align="center" valign="top">
													<table border="0" cellpadding="0" cellspacing="0">
														<tbody>
															<tr>
																<td>
																	<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/arw-dwn.gif" alt="" align="left" height="7" hspace="0" vspace="0" width="17">
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>

									</td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td align="center">
										<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
											<tr>
												<td width="465" bgcolor="#ffffff" style=" border:#e9e8e8 solid 1px;">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td height="10"></td>
															<td></td>
														</tr>
														<tr>
															<td>
																<table border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																	<tr>
																		<td width="10"></td>
																		<td width="130" valign="top" align="left">
																			<img src="~$dataArr->getProfilePicUrl()`" width="100%" style="max-width:115px;" alt="" /></td>
																			<td width="10"></td>
																			<td width="300" align="left" valign="top">
																				<table border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																					<tr>
																						<td width="300">
																							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																								<tr>
																									<td align="left" style="font-family:arial, Geneva, sans-serif; font-size:14px; color:#34495e;">~$dataArr->getUSERNAME()`</td>
																									<td align="right" style="font-family:arial, Geneva, sans-serif; font-size:12px; color:#d9475c;">Featured</td>
																									<td  width="10"></td>
																								</tr>
																							</table></td>
																						</tr>
																						<tr>
																							<td align="left" height="20" style="font-family:arial, Geneva, sans-serif; font-size:12px; color:#000000;">~$dataArr->getAGE()` Years  ~$dataArr->getHEIGHT()`, ~$dataArr->getRELIGION()`,</td>
																						</tr>
																						<tr>
																							<td align="left" height="20" style="font-family:arial, Geneva, sans-serif; font-size:12px; color:#000000;">~$dataArr->getCASTE()`, ~$dataArr->getMTONGUE()`</td>
																						</tr>
																						<tr>
																							<td align="left" height="20" style="font-family:arial, Geneva, sans-serif; font-size:12px; color:#000000;">~$dataArr->getOCCUPATION()`, ~$dataArr->getEDU_LEVEL_NEW()`...</td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																		</table>
																		
																	</td>
																</tr>
																<tr>
																	<td height="10"></td>
																	<td></td>
																</tr>
															</table>
														</td>
													</tr>
												</table></td>
											</tr>
											<tr>
												<td height="30"></td>
											</tr>
											<tr>
												<td align="center">
													<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
														<tbody><tr>
															<td style="font-family:arial, Geneva, sans-serif; font-size:13px; color:#ffffff;" align="center" bgcolor="#34495e" height="38" width="250">This is how you will feature on PC</td>
														</tr>
														<tr>
															<td align="center" valign="top">
																<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																	<tbody>
																		<tr>
																			<td>
																				<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/arw-dwn.gif" alt="" align="left" height="7" hspace="0" vspace="0" width="17">
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</tbody></table>
												</td>
											</tr>
											<tr>
												<td height="10"></td>
											</tr>
											<tr>
												<td align="center">
													<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
														<tr>
															<td align="center" style=" border:#e9e8e8 solid 1px;" bgcolor="#ffffff" width="550">
																<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																	<tr>
																		<td height="10"></td>
																		<td></td>
																		<td></td>
																	</tr>
																	<tr>
																		<td width="10"></td>
																		<td  align="left" valign="top" width="500" >
																			<table border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																				<tr>
																					<td width="158" align="left" valign="top">
																						<img src="~$dataArr->getProfilePicUrl()`" width="100%" style="max-width:158px;" alt="" />
																					</td>
																					<td width="15"></td>
																					<td align="left" width="390">
																						<table border="0" cellspacing="0" width="100%" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																							<tr>
																								<td align="left" valign="top">
																									<table border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																										<tr>
																											<td align="left">
																												<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																													<tr>
																														<td width="80" align="left" style="font-family:arial, Geneva, sans-serif; font-size:18px; color:#34495e;">~$dataArr->getUSERNAME()`</td>
																														<td align="left" width="120"  style="font-family:arial, Geneva, sans-serif; font-size:10px; color:#f1f1f1;">online 2 days ago</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																										<tr>
																											<td align="left" height="30" style="font-family:arial, Geneva, sans-serif; font-size:18px; color:#000000;">
																												<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																													<tr>
																														<td width="100" height="5"></td>
																														<td width="150"></td>
																													</tr>
																													<tr>
																														<td width="100" height="18" align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getAGE()` yr, ~$dataArr->getHEIGHT()`</td>
																														<td width="150" align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getEDU_LEVEL_NEW()`</td>
																													</tr>
																													<tr>
																														<td height="18" align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getRELIGION()`</td>
																														<td align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getOCCUPATION()`</td>
																													</tr>
																													<tr>
																														<td height="18" align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getMTONGUE()`</td>
																														<td align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getINCOME()`</td>
																													</tr>
																													<tr>
																														<td height="18" align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getCASTE()`</td>
																														<td align="left" style="font-family:arial, Geneva, sans-serif; font-size:11px; color:#333366;">~$dataArr->getCITY()`</td>
																													</tr>
																													<tr>
																														<td height="10"></td>
																														<td></td>
																													</tr>
																												</table></td>
																											</tr>
																										</table>
																									</td>
																									<td align="right" valign="top">
																										<table width="40" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"> 
																											<tr>
																												<td height="34" align="center" valign="top">
																													<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/icon3.gif" width="19" height="17" alt="" /></td>
																												</tr>
																												<tr>
																													<td height="34" align="center" valign="top">
																														<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/icon4.gif" width="19" height="18" alt="" /></td>
																													</tr>
																													<tr>
																														<td height="34" align="center" valign="top">
																															<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/icon5.gif" width="19" height="18" alt="" /></td>
																														</tr>
																														<tr>
																															<td height="34" align="center" valign="top">
																																<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/icon6.gif" width="19" height="18" alt="" /></td>
																															</tr>
																														</table>
																													</td>
																												</tr>
																											</table>
																										</td>
																									</tr>
																								</table>
																								
																							</td>
																							<td width="10"></td>
																						</tr>
																						<tr>
																							<td height="10"></td>
																							<td colspan="2" valign="top">
																								<table border="0" cellspacing="0" cellpadding="0" width="100%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																									<tr>
																										<td style="border-top:#d9475c solid 2px;" align="right">
																											<table border="0" cellspacing="0" cellpadding="0">
																												<tr>
																													<td align="right" valign="top">
																														<img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/txt-rgt.gif" height="17" width="14" align="right" alt="" />
																													</td>
																													<td  bgcolor="#d9475c" align="center" width="120" style="font-family:arial, Geneva, sans-serif; font-size:9px; line-height:17px; color:#ffffff;">
																														Featured Profile(4 more)
																													</td>
																												</tr>
																											</table>
																										</td>
																										<td width="10"></td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																						<tr>
																							<td height="10"></td>
																							<td></td>
																							<td></td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td height="27"></td>
																</tr>
																<tr>
																	<td align="center" style="font-family:arial, Geneva, sans-serif; font-size:14px; color:#505050;">To enrol for this service, kindly <a href="~$mailerLinks['MEMBERSHIP_COMPARISON']`~$commonParamaters`?profilechecksum=~$profilechecksum`" target="_blank" style="color:#d9475c; text-decoration:none;"><em>click here</em></a> or call at <1-800-419-6299></td>
																</tr>
																<tr>
																	<td height="20"></td>
																</tr>
															</table></td>
														</tr>
														<tr>
															<td align="center"  bgcolor="#334859">
																<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																	<tbody>
																		<tr>
																			<td height="25"></td>
																		</tr>
																		<tr>
																			<td align="center" style="font-family:arial, Geneva, sans-serif; font-size:12px; color:#94adc1;">
																				<strong>*Featured profile</strong> is available as an add-on after becoming an eRishta, eValue member<br> and is complimentary free of charge with eAdvantage
																			</td>
																		</tr>
																		<tr>
																			<td height="15"></td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td align="center">
																<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
																	<tbody>
																		<tr>
																			<td colspan="3" height="27"></td>
																		</tr>
																		<tr>
																			<td></td>
																			<td>
																				~include_partial("global/mailerJsSignature")`
																			</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td colspan="3" height="24"></td>
																		</tr>
																		<tr>
																			<td></td>
																			<td colspan="3">
																				~include_partial("global/mailerfooter",[mailerParameter=>"matchalertTrack=1",mailerLinks=>$mailerLinks])`
																			</td>
																			<td></td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</body>
