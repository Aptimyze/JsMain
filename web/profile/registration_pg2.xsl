<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:param name="SITE_URL" />
<xsl:param name="IMG_URL" />
<xsl:param name="LIVE_CHAT_URL" />
<xsl:param name="MORE" />
<xsl:param name="CITY_RES" />
<xsl:param name="CITY_LABEL" />
<xsl:param name="RES_STATUS" />
<xsl:param name="SHOW_RESIDENT_STATUS" />
<xsl:param name="DIET" />
<xsl:param name="DRINK" />
<xsl:param name="SMOKE" />
<xsl:param name="BLOOD_GROUP" />
<xsl:param name="HIV" />
<xsl:param name="BTYPE" />
<xsl:param name="WEIGHT" />
<xsl:param name="COMPLEXION" />
<xsl:param name="HANDICAPPED" />
<xsl:param name="SPOKEN_LANGUAGES" />
<xsl:param name="MESSENGER_ID" />
<xsl:param name="MESSENGER_CHANNEL" />
<xsl:param name="SHOWMESSENGER" />
<!--<xsl:param name="ORKUT_USERNAME" />-->
<xsl:param name="CONTACT" />
<xsl:param name="SHOWADDRESS" />
<xsl:param name="FAMILY_VALUES" />
<xsl:param name="FAMILY_TYPE" />
<xsl:param name="FAMILY_STATUS" />
<xsl:param name="FATHER_OCC" />
<xsl:param name="MOTHER_OCC" />
<xsl:param name="T_BROTHERS" />
<xsl:param name="M_BROTHERS" />
<xsl:param name="T_SISTERS" />
<xsl:param name="M_SISTERS" />
<xsl:param name="LIVE_WITH_PARENTS" />
<xsl:param name="FAMILY_INFO" />
<xsl:param name="EDUCATION" />
<xsl:param name="WORK_STATUS" />
<xsl:param name="MARRIED_WORKING" />
<xsl:param name="JOB_INFO" />
<xsl:param name="SUBCASTE" />
<xsl:param name="GOTHRA" />
<xsl:param name="ANCESTRAL_ORIGIN" />
<xsl:param name="MANGLIK" />
<xsl:param name="NAKSHATRA" />
<xsl:param name="RASHI" />
<xsl:param name="HOROSCOPE_MATCH" />
<xsl:param name="HOROSCOPE" />
<xsl:param name="PHOTO_DISPLAY" />
<xsl:param name="YOURINFO" />
<xsl:param name="SPOUSE" />
<xsl:param name="MATHTHAB" />
<xsl:param name="NAMAZ" />
<xsl:param name="ZAKAT" />
<xsl:param name="FASTING" />
<xsl:param name="UMRAH_HAJJ" />
<xsl:param name="QURAN" />
<xsl:param name="SUNNAH_BEARD" />
<xsl:param name="SUNNAH_CAP" />
<xsl:param name="HIJAB" />
<xsl:param name="HIJAB_MARRIAGE" />
<xsl:param name="WORKING_MARRIAGE" />
<xsl:param name="DIOCESE" />
<xsl:param name="BAPTISED" />
<xsl:param name="READ_BIBLE" />
<xsl:param name="OFFER_TITHE" />
<xsl:param name="SPREADING_GOSPEL" />
<xsl:param name="AMRITDHARI" />
<xsl:param name="CUT_HAIR" />
<xsl:param name="TRIM_BEARD" />
<xsl:param name="WEAR_TURBAN" />
<xsl:param name="CLEAN_SHAVEN" />
<xsl:param name="ZARATHUSHTRI" />
<xsl:param name="PARENTS_ZARATHUSHTRI" />
<xsl:param name="SAMPRADAY" />
<xsl:param name="CASTE_SEL" />
<xsl:param name="RELIGION_ETHNICITY_SHOW" />
<xsl:param name="GENDER" />
<xsl:param name="SCRIPT_NAME" />
<xsl:param name="HTTP_REFERER" />
<xsl:param name="REMOTE_HOST" />
<xsl:param name="RFR" />
<xsl:param name="GROUPNAME" />
<xsl:param name="SHOW_GOOGLE" />
<xsl:param name="MBCHECKSUM" />
<xsl:param name="CHECKSUM" />
<xsl:param name="SOURCE" />
<xsl:param name="TIEUP_SOURCE" />
<xsl:param name="HITSOURCE" />
<xsl:param name="FROMMARRIAGEBUREAU" />
<xsl:param name="PROFILEID" />
<xsl:param name="YEAR_OF_BIRTH" />
<xsl:param name="MONTH_OF_BIRTH" />
<xsl:param name="DAY_OF_BIRTH" />
<xsl:param name="USERNAME" />
<xsl:param name="PASSWORD" />
<xsl:param name="FULL_NAME" />
<xsl:param name="MTONGUE" />
<xsl:param name="PROFILE_PERCENT" />
<xsl:param name="PARTNER_CITYRES_STR" />
<xsl:param name="PARTNER_DIET_STR" />
<xsl:param name="PARTNER_DRINK_STR" />
<xsl:param name="PARTNER_SMOKE_STR" />
<xsl:param name="PARTNER_HANDICAPPED_STR" />
<xsl:param name="SPOKEN_LANGUAGES_STR" />
<xsl:param name="title" />
<xsl:param name="title1" />
<xsl:param name="title2" />
<xsl:param name="photodisplay" />
<xsl:param name="thumbphoto" />
<xsl:param name="thumbphoto1" />
<xsl:param name="albumphoto1" />
<xsl:param name="albumphoto2" />
<xsl:param name="photodisplay" />
<xsl:param name="margin" />
<xsl:param name="profilephoto" />
<xsl:param name="PROFILE_SCORE" />
<xsl:param name="NEXT" />
<xsl:template match="/registrationPage2">
<xsl:variable name="btnDoneSelection" select="buttonLabels/doneSelection" />
<xsl:variable name="btnNext" select="buttonLabels/next" />
<xsl:variable name="btnBack" select="buttonLabels/back" />
<xsl:variable name="btnFinish" select="buttonLabels/finishRegistration" />
<xsl:variable name="btnCancel" select="buttonLabels/regCancel" />
<html>
	<head>
		<title><xsl:value-of select="title" /></title>
		<meta name="description" content="Register for Free in Jeevansathi.com. Create your matrimonial profile and find your dream life partner. Join Jeevansathi.com today, the leading India matrimonials website in India. Search matrimonials, matrimony profiles, NRI bride and groom from our online matrimonial and matchmaking services."/>
                <meta name="keywords" content="Jeevansathi.com, Indian matrimony, India, matrimony, matrimonial, matrimonials, matrimony services, online matrimonials, Indian marriage, match making, matchmaking, matchmaker, match maker, marriage bureau , matchmaking services, matrimonial profiles, bride, groom, matrimony classified."/>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="{$SITE_URL}/profile/css/registration_new.css" type="text/css" rel="stylesheet" />
		<link href="{$SITE_URL}/profile/css/common_new.css" type="text/css" rel="stylesheet" />

		<link href="{$SITE_URL}/profile/css/thickbox.css" type="text/css" rel="stylesheet" />
		
		<link href="http://www.google.com/uds/modules/elements/transliteration/api.css" type="text/css" rel="stylesheet"/>
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		
		<script language="JavaScript" src="http://ser6.jeevansathi.com/jspellhtml2k4/jspell.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/behaviour.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/transliteration.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/registration.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/gadget_as.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/jquery_pt.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/thickbox_pt.js"></script>		
		<script type="text/javascript" src="{$SITE_URL}/profile/js/revampCommon.js"></script>
		<!--script type="text/javascript" src="{$SITE_URL}/profile/js/registration_pg2.js"></script-->
		<script type="text/javascript" src="{$SITE_URL}/profile/js/registration_ajax.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/autosuggest.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/gadget.js"></script>
		<!--script type="text/javascript" src="{$SITE_URL}/profile/js/upload_photo.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/show_hide_div.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/prototype.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/dragdrop.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/cropper.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/builder.js"></script-->


		<style>
			.graylayer{filter: alpha(opacity=20);filter: progid:DXImageTransform.Microsoft.Alpha(opacity=20);-moz-opacity: .20;-khtml-opacity: .20;opacity: .20; margin:auto;}
			.iframetrans{filter: alpha(opacity=0);filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0);-moz-opacity: .0;-khtml-opacity: .0;opacity: .0; margin:auto;}
		</style>
		<style>
			.transTip{display:none;};
		</style>
	</head>
	<body id="page2_body">
		<div style="position: relative">
		<div id="gray_layer" style="margin: auto; background: rgb(0, 0, 0) none repeat scroll 0%; position: absolute; left: 0pt; top:0pt;bottom: 0pt; z-index: 100; display: none; width: 100%; height: 500%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" class="graylayer"></div>
		<form name="form1" method="post" style="margin:0px" action="{$SITE_URL}/profile/registration_pg2.php" id="reg_page2_form">
		<input type="hidden" name="site_url" value="{$SITE_URL}" />
		<input type="hidden" name="img_url" value="{$IMG_URL}" />
		<input type="hidden" name="script_name" value="{$SCRIPT_NAME}" />
		<input type="hidden" name="http_referer" value="{$HTTP_REFERER}" />
		<input type="hidden" name="remote_host" value="{$REMOTE_HOST}" />
		<input type="hidden" name="rfr" value="{$RFR}" />
		<input type="hidden" name="groupname" value="{$GROUPNAME}" />
		<input type="hidden" name="profile_score" value="{$PROFILE_SCORE}" />
		<input type="hidden" name="mbchecksum" value="{$MBCHECKSUM}" />
		<input type="hidden" name="checksum" value="{$CHECKSUM}" />
		<input type="hidden" name="source" value="{$SOURCE}" />
		<input type="hidden" name="tieup_source" value="{$TIEUP_SOURCE}" />
		<input type="hidden" name="hit_source" value="{$HITSOURCE}" />
		<input type="hidden" name="frommarriagebureau" value="{$FROMMARRIAGEBUREAU}" />
		<input type="hidden" name="username" value="{$USERNAME}" />
		<input type="hidden" name="password" value="{$PASSWORD}" />
		<input type="hidden" name="profileid" value="{$PROFILEID}" />
		<input type="hidden" name="mtongue" value="{$MTONGUE}" />
		<input type="hidden" name="edit" />
		<div id="container">
			<noscript>
				<div align="center" style="font-family:verdana,Arial;font-size:14px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5; position:fixed">
					<b>
						<img src="{$IMG_URL}/error.gif" width="23" height="20" />
						<xsl:value-of select="disabledJavascript/part1"/>
						<a href="{$SITE_URL}/P/js_help.htm" target="_blank">
							<xsl:value-of select="disabledJavascript/part2"/>
						</a>
						<xsl:value-of select="disabledJavascript/part3"/>
					</b>
				</div>
			</noscript>
			<div class="topbg">
				<div style="width:742px;padding:5px;margin: auto;">
					<div class="fl"></div>
					<div class="fr maroon b">
						<img src="{$IMG_URL}/icon_chat.gif" hspace="2" align="top" />
						<!-- <a href="{$LIVE_CHAT_URL}" id="live_help">
							<xsl:value-of select="liveHelp" />
						</a> -->
					</div>
				</div>
			</div>
			<div id="top">
				<div class="bten fl">
					<a href="{$SITE_URL}">
						<img border="0" alt="Matrimonials" src="{$IMG_URL}/Matrimonial.gif"/>
					</a>
					<br/>
					<xsl:value-of select="tagLine" />
				</div>
			</div>
			<div class="spacer">&#160;</div>
			<div id="banner"></div>
			<div class="spacer" style="line-height:10px;">&#160;</div>
			
			<div style="width: 758px;" class="fl">
			<div class="fl"><img src="{$IMG_URL}/bl_lf_curve.gif" /></div>
			<div class="fl bl_bg_curve">
				<div class="b t16" style="color:#87c759">
					<img src="{$IMG_URL}/confirm.gif" align="absmiddle" style="margin-right:5px" />
					<xsl:value-of select="stepsOfRegistration/step3/part1" />
					<input type="button" class="t16" value="{$FIRST_NAME}" style="background:none;border:none;cursor:text;color:#87C759 !important;font-size:16px;font-weight:bold;height:23px;padding: 0px 0px 0px 3px;overflow:hidden;_height:19px; _margin-left:-12px;" />
					<span style="_margin-left:-15px;"><xsl:value-of select="stepsOfRegistration/step3/part2" /></span>

				</div>
			</div>
			<div class="fr"><img src="{$IMG_URL}/bl_rf_curve.gif" /></div>
			<div class="spacer1">&#160;</div>
			<div class="fl redh_bg b"><xsl:value-of select="stepsOfRegistration/step3/part3" /></div>
			</div>
			<div class="spacer1">&#160;</div>
			<div class="spacer1">&#160;</div>
			<div class="spacer1">&#160;</div>
			
			<div class="fl">
				<div class="step2">
					<span class="t23"><xsl:value-of select="stepsOfRegistration/step1/part1" /></span>
					<span class="t16">&#160;<xsl:value-of select= "stepsOfRegistration/step1/part2" /></span>
				</div>
				<div class="step1">
					<span class="t23">
						<img src="{$IMG_URL}/org_arrow_down.jpg" align="top" />
						&#160;<xsl:value-of select="stepsOfRegistration/step2/part1" />
					</span>
					<span class="t16">
						&#160;<xsl:value-of select= "stepsOfRegistration/step2/part2" />
					</span>
				</div>
				<div class="fr">
					<xsl:variable name="profilePercent_var" select="$PROFILE_PERCENT" />
					<table width="165"  border="0" cellspacing="0" cellpadding="0" class="pgbarborder">
						<tr>
							<td>
								<table id="profile_percent_image_div" width="{$profilePercent_var}%" border="0" cellspacing="0" cellpadding="0">
									<tr>
    										<td class="pgbar">&#160;</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<xsl:value-of select="profilePercent/part1" />&#160;
					<span id="profile_percent_span">
						<xsl:value-of select="$PROFILE_PERCENT" />&#37;
					</span>
					&#160;<xsl:value-of select="profilePercent/part2" />
				</div>
			</div>
			<div class="spacer"></div>
			<!--  start content part -->
			<div id="wrapper">
				<div class="spacer1">&#160;</div>
				<div id="pagecont">
					<div class="collapse_s2">
						<a href="" id="personal_details" onclick="return false;" style="text-decoration:none">
						<div class="opentab" id="personal_details_tab_div">
							<div style="padding:7px 0px 0px 10px;">
								
								<span style="cursor:pointer;font-weight:bold;color:#000000" id="personal_details_span">
									<xsl:value-of select="tabs/personalDetails" />
								</span>
							</div>
						</div>
						</a>
						<div class="cp2_right"></div>
					</div> 
					<div style="float:left; width:100%; padding:0px; margin:0px;">
					<div class="tab">
<!--The onclick event on following links has been written inline to prevent error if clicked before behaviour is applied -->
						<div class="spacer1">&#160;</div>
						<a href="" id="family_details" onclick="return false;" style="text-decoration:none">
						<div class="closetab" id="family_details_tab_div">
							<div style="padding:7px 0px 0px 8px;">
								<span href="" style="cursor:pointer;font-weight:bold;color:#797979" id="family_details_span">
									<xsl:value-of select="tabs/familyDetails" />
								</span>
							</div>
						</div>
						</a>
						<div class="spacer1">&#160;</div>
						<a href=""  id="education_profession" onclick="return false;" style="text-decoration:none">
						<div class="closetab" id="education_profession_tab_div">
							<div style="padding:2px 0px 0px 8px;">
								<span style="cursor:pointer;font-weight:bold;color:#797979" id="education_profession_span">
									<xsl:value-of select="tabs/educationProfessionalDetails" />
								</span>
							</div>
						</div>
						</a>
						<input type="hidden" name="reg2" id="reg2" value="{$NEXT}"/>
						<xsl:if test="$RELIGION_ETHNICITY_SHOW = '1'">
							<div class="spacer1">&#160;</div>
							<a href=""  id="religion_ethnicity" onclick="return false;" style="text-decoration:none">
							<div class="closetab" id="religion_ethnicity_tab_div">
								<div style="padding:8px 0px 0px 8px;">
									<span href="" style="cursor:pointer;font-weight:bold;color:#797979" id="religion_ethnicity_span">
										<xsl:value-of select="tabs/religionEthnicity" />
									</span>
								</div>
							</div>
							</a>
						</xsl:if>
						<!-- Earlier Photo Upload tab was there Inserted-->
						<div class="spacer1">&#160;</div>
						<a href="" id="about_myself" onclick="return false;" style="text-decoration:none">
						<div class="closetab" id="about_myself_tab_div">
							<div style="padding:8px 0px 0px 8px;">
								<span style="cursor:pointer;font-weight:bold;color:#797979" id="about_myself_span">
									<xsl:value-of select="tabs/moreAboutMySelf" />
								</span>
							</div>
						</div>
						</a>
						<div class="spacer1">&#160;</div>
					</div>  
					<div id="divcontent">
						<div id="personal_details_section">
							<!-- Start Personal Details -->
							<div class="spacer1">&#160;</div>
							<xsl:if test="$SHOW_RESIDENT_STATUS = '1'">
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/residentStatus" /> :
									</div>
									<div class="l2">
										<select name="residency_status" id="residencey_status" class="textbox" size="1" style="width:204px;">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/residentStatus">
												<xsl:variable name="res_var" select="@value" />
												<xsl:choose>
													<xsl:when test="$RES_STATUS = $res_var">
														<option value="{$res_var}" selected="yes">
															<xsl:value-of select="." />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="{$res_var}">
															<xsl:value-of select="." />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:for-each>
										</select>
									</div>
								</div>
							</xsl:if>
							<xsl:if test="$SHOW_RESIDENT_STATUS = '2'">
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/residentStatus" /> :
									</div>
									<div class="l2">
										<select name="residency_status" id="residencey_status" class="textbox" size="1" style="width:204px;">
											<xsl:for-each select="populate/residentStatus">
												<xsl:variable name="res_var" select="@value" />
												<xsl:choose>
													<xsl:when test="$RES_STATUS = $res_var">
														<option value="{$res_var}" selected="yes">
															<xsl:value-of select="." />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="{$res_var}">
															<xsl:value-of select="." />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:for-each>
										</select>
									</div>
								</div>
							</xsl:if>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/diet" /> :
								</div>
								<div class="l2">
									<div id="diet_section">
										<xsl:for-each select="populate/diet">
											<xsl:variable name="diet_var" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$DIET = $diet_var">
													<input type="radio" class="inputbottom" name="diet" value="{$diet_var}" checked="yes" id="diet"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="diet" value="{$diet_var}" id="diet"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." />
										</xsl:for-each>
									</div>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/drink" /> :
								</div>
								<div class="l2">
									<div id="drink_section">
										<xsl:for-each select="populate/drink">
											<xsl:variable name="drink_var" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$DRINK = $drink_var">
													<input type="radio" class="inputbottom" name="drink" value="{$drink_var}" checked="yes" id="drink"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="drink" value="{$drink_var}" id="drink"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." />
										</xsl:for-each>
									</div>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/smoke" /> :
								</div>
								<div class="l2">
									<div id="smoke_section">
										<xsl:for-each select="populate/smoke">
											<xsl:variable name="smoke_var" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$SMOKE = $smoke_var">
													<input type="radio" class="inputbottom" name="smoke" value="{$smoke_var}" checked="yes" id="smoke"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="smoke" value="{$smoke_var}" id="smoke"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." />
										</xsl:for-each>
									</div>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/bloodGroup" /> :
								</div>
								<div class="l2">
									<select class="textbox" name="blood_group" size="1" style="width:204px;" id="blood_group">
										<option value="" selected="">
											<xsl:value-of select="tagLabels/pleaseSelect" />
										</option>
										<xsl:for-each select="populate/bloodGroup">
											<xsl:variable name="bg_var" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$BLOOD_GROUP = $bg_var">
													<option value="{$bg_var}" selected="yes">
														<xsl:value-of select="." />
													</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="{$bg_var}">
														<xsl:value-of select="." />
													</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</xsl:for-each>
									</select>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/hiv" /> :
								</div>
								<div class="l2">
									<xsl:choose>
										<xsl:when test="$HIV = 'Y'">
											<input type="radio" class="inputbottom" value="Y" name="hiv" checked="yes" />
											<xsl:value-of select="labels/yes" />
											<input type="radio" class="inputbottom" checked="checked" value="N" name="hiv" />
											<xsl:value-of select="labels/no" />
										</xsl:when>
										<xsl:otherwise>
											<input type="radio" class="inputbottom" value="Y" name="hiv" />
											<xsl:value-of select="labels/yes" />
											<input type="radio" class="inputbottom" checked="checked" value="N" name="hiv" />
											<xsl:value-of select="labels/no" />
										</xsl:otherwise>
									</xsl:choose>
									<div class="coverhelp">
										<div class="helpbox extraHelpbox" id="hiv_help">
											<div class="helptext">
												<xsl:value-of select="help/hiv"/>
												<div class="helpimg"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/bodyType" /> :
								</div>
								<div class="l2">
									<xsl:for-each select="populate/bodyType">
										<xsl:variable name="bt_var" select="@value" />
										<xsl:choose>
											<xsl:when test="$BTYPE = $bt_var">
												<input type="radio" class="inputbottom" name="body_type" value="{$bt_var}" checked="yes" id="body_type"/>
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" name="body_type" value="{$bt_var}" id="body_type"/>
											</xsl:otherwise>
										</xsl:choose>
										<xsl:value-of select="." />
									</xsl:for-each>
								</div>
							</div>
							<div class="spacer1"></div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/weight" /> :
								</div>
								<div class="l2">
									<input type="text" name="weight" size="4" maxlength="3" class="textbox" value="{$WEIGHT}" id="weight"/>
									&#160;<xsl:value-of select="labels/kgs" />
									<xsl:variable name="weightStringError" select="alert/weightStringError" />
									<input type="hidden" name="weight_string_error" value="{$weightStringError}" />
									<xsl:variable name="weightInvalidError" select="alert/weightInvalidError" />
									<input type="hidden" name="weight_invalid_error" value="{$weightInvalidError}" />
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/complexion" /> :
								</div>
								<div class="l2">
									<xsl:for-each select="populate/complexion">
										<xsl:variable name="comp_var" select="@value" />
										<xsl:choose>
											<xsl:when test="$COMPLEXION = $comp_var">
												<input type="radio" class="inputbottom" name="complexion" value="{$comp_var}" checked="yes" id="complexion"/>
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" name="complexion" value="{$comp_var}" id="complexion"/>
											</xsl:otherwise>
										</xsl:choose>
										<xsl:value-of select="." />
									</xsl:for-each>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/physicallyMentally" /> :
								</div>
								<div class="l2">
									<div id="handicap_section">
										<xsl:for-each select="populate/handicap">
											<xsl:variable name="handicap_var" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$HANDICAPPED = $handicap_var">
													<input type="radio" class="inputbottom" name="handicapped" value="{$handicap_var}" checked="yes" id="handicapped"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="handicapped" value="{$handicap_var}" id="handicapped"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." /><br />
										</xsl:for-each>
									</div>
								</div>
							</div>
							<div id="handicap_nature" style="display:none">
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/natureOfHandicap" /> :
									</div>
									<div class="l2">
										<select class="textbox" name="nature_of_handicap" size="1" style="width:204px;" id="nature_of_handicap">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/natureHandicap">
												<xsl:variable name="handicap_var" select="@value" />
												<option value="{$handicap_var}">
													<xsl:value-of select="." />
												</option>
											</xsl:for-each>
										</select>
									</div>
								</div>
							</div>
							<div id="partner_handicapped_section" style="display:none">
								<div class="spacer1">&#160;</div>
								<div class="l1"></div>
								<div class="l2">
									<div class="lgreen_border" style="width:420px; margin-left:3px;">
									 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
									  <div style="padding:2px 0 0 5px;">
									   <xsl:value-of select="labels/partnerChallenged" />
									  </div>
									 </div>
									 <div style="padding:5px;width:98%">
									  <div class="fl" style="width:48%">
									   <div style="padding: 0pt 5px 0pt 2px;">
									    <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
									    <div class="fr"><a href="" id="partner_handicapped_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
									   </div>
									   <div class="fl scrollbox">
									    <div style="display:none" id="partner_handicapped_div">
									     <input type="hidden" name="partner_handicapped_str" id="partner_handicapped_str" value="{$PARTNER_HANDICAPPED_STR}" />
									     <input type="checkbox" name="partner_handicapped_arr[]" id="partner_handicapped_DM" value="DM" />
									     <label id="partner_handicapped_label_DM">
									      <xsl:value-of select="tagLabels/doesntMatter" />
									     </label>
									     <xsl:for-each select="populate/handicap">
									      <xsl:variable name="handicap_var" select="@value" />
									      <input type="checkbox" name="partner_handicapped_arr[]" id="partner_handicapped_{$handicap_var}" value="{$handicap_var}"/>
									      <label id="partner_handicapped_label_{$handicap_var}">
									       <xsl:value-of select="." />
									      </label>
									      <br />
									     </xsl:for-each>
									    </div>
									    <div style="overflow:hidden;" id="partner_handicapped_source_div">
									     <xsl:for-each select="populate/handicap">
									      <xsl:variable name="handicap_var" select="@value" />
									      <input type="checkbox" class="chbx checkboxalign" name="partner_handicapped_displaying_arr[]" id="partner_handicapped_displaying_{$handicap_var}" value="{$handicap_var}"/>
									      <label id="partner_handicapped_displaying_label_{$handicap_var}">
									       <xsl:value-of select="." />
									      </label>
									      <br />
									     </xsl:for-each>
									     <br />
									    </div>
									   </div>
									  </div>
									  <div class="fr" style="width:48%">
									   <div style="padding: 0pt 5px 0pt 2px;">
									    <div class="fl"><xsl:value-of select="gadgetLabel/removeItem" /></div>
									    <div class="fr"><a href="" id="partner_handicapped_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
									   </div>
									   <div class="fl scrollbox">
									    <div style="overflow:hidden;" id="partner_handicapped_target_div">
									     <div id="partner_handicap_link_DM">
							       		      <label>
							        		<xsl:value-of select="tagLabels/doesntMatter" />
							       		      </label>
							      		     </div>
									    </div>
									   </div>
									  </div>
									  <div style="height:10px; clear:both;"></div>
									 </div>
									</div>
								</div>
							</div>
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="l1">
									<xsl:value-of select="labels/spokenLanguages" /> :
								</div>
								<div class="l2">
									<div class="lgreen_border1" style="width:420px; margin-left:3px;">
									 <div class="cp2_right" style="color:#4d4e46;height:22px;font:bold 12px arial">
									  <div style="padding:2px 0 0 5px;">
									   <xsl:value-of select="labels/selectSpokenLanguages" />
									  </div>
									 </div>
									 <div style="padding:5px;width:98%">
									  <div class="fl" style="width:48%">
									   <div style="padding: 0pt 5px 0pt 2px;">
									    <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
									    <div class="fr"><a href="" id="spoken_languages_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
									   </div>
									   <div class="fl scrollbox">
									    <div style="display:none" id="spoken_languages_div">
									     <input type="hidden" name="spoken_languages_str" id="spoken_languages_str" value="{$SPOKEN_LANGUAGES_STR}" />
									     <xsl:for-each select="populate/spokenLanguages">
									      <xsl:variable name="spokenVal" select="@value" />
									      <input type="checkbox" name="spoken_languages_arr[]" id="spoken_languages_{$spokenVal}" value="{$spokenVal}"/>
									      <label id="spoken_languages_label_{$spokenVal}">
									       <xsl:value-of select="." />
									      </label>
									      <br />
									     </xsl:for-each>
									    </div>
									    <div style="overflow:hidden;" id="spoken_languages_source_div">
									     <xsl:for-each select="populate/spokenLanguages">
									      <xsl:variable name="spokenVal" select="@value" />
									       <input type="checkbox" class="chbx checkboxalign" name="spoken_languages_displaying_arr[]" id="spoken_languages_displaying_{$spokenVal}" value="{$spokenVal}"/>
									       <label id="spoken_languages_displaying_label_{$spokenVal}">
										<xsl:value-of select="." />
									       </label>
									       <br />
									      </xsl:for-each>
									      <br />
									     </div>
									    </div>
									   </div>
									   <div class="fr" style="width:48%">
									    <div style="padding: 0pt 5px 0pt 2px;">
									     <div class="fl"><xsl:value-of select="gadgetLabel/removeItem" /></div>
									     <div class="fr"><a href="" id="spoken_languages_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
									    </div>
									    <div class="fl scrollbox">
									     <div style="overflow:hidden;" id="spoken_languages_target_div">
									     </div>
									    </div>
									   </div>
									   <div style="height:10px; clear:both;"></div>
									  </div>
									 </div>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/messengerId" /> :
									</div>
									<div class="l2">
										<input class="textbox" type="text" style="width: 120px;" value="" name="messenger_id" id="messenger_id"/>
										@
										<select name="messenger_channel" class="textbox" size="1" id="messenger_channel">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/messengerChannel">
												<xsl:variable name="messenger_var" select="@value" />
												<option value="{$messenger_var}">
													<xsl:value-of select="." />
												</option>
											</xsl:for-each>
										</select> 
										<!--.<xsl:value-of select="labels/com" />-->
									</div>
								</div>
								<div class="spacer1"></div>
								<div class="row">
									<div class="l1">&#160;</div>
									<div class="l2">
										<xsl:choose>
											<xsl:when test="$SHOWMESSENGER = 'N'">
												<input type="radio" class="inputbottom" value="Y" name="showmessenger" id="showmessenger"/>
												<xsl:value-of select="labels/show" /><br />
												<input type="radio" class="inputbottom" value="N" name="showmessenger" checked="yes" id="showmessenger"/>
												<xsl:value-of select="labels/dontshow" /><br />
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" value="Y" name="showmessenger" id="showmessenger" checked="yes"/>
												<xsl:value-of select="labels/show" /><br />
												<input type="radio" class="inputbottom" value="N" name="showmessenger" id="showmessenger"/>
												<xsl:value-of select="labels/dontshow" /><br />
											</xsl:otherwise>
										</xsl:choose>
									</div>
								</div>
<!--
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/orkutUsername" /> :
									</div>
									<div class="l2">
										<xsl:variable name="or_username" select="vanishingLabels/orkut" />
										<xsl:choose>
											<xsl:when test="$ORKUT_USERNAME = ''">
												<input type="text" size="10" name="orkut_username" id="orkut_username" value="{$or_username}" class="textbox" />
											</xsl:when>
											<xsl:otherwise>
												<input type="text" size="10" name="orkut_username" id="orkut_username" value="{$ORKUT_HANDLE}" class="textbox" />
											</xsl:otherwise>
										</xsl:choose>
										<div class="coverhelp">
											<div class="helpbox extraHelpbox" id="orkut_username_help" style="_top:-4px">
												<div class="helptext">
													<xsl:value-of select="help/orkutUsername"/>
													<div class="helpimg"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
-->
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/postalAddress" /> :
									</div>
									<div class="l2">
										<textarea class="textbox" onclick="javascript:colorchange()" name="contact_address" id="contact_address" style="height:40px;width:237px; max-width:237px; max-height:40px"><xsl:value-of select="$CONTACT"/></textarea>
									</div>
								</div>
								<div class="spacer1"></div>
								<div class="row">
									<div class="l1">&#160;</div>
									<div class="l2">
										<xsl:choose>
											<xsl:when test="$SHOWADDRESS = 'N'">
												<input type="radio" class="inputbottom" name="showaddress" value="Y" id="showaddress"/>
												<xsl:value-of select="labels/show" /><br />
												<input type="radio" class="inputbottom" name="showaddress" value="N" checked="yes" id="showaddress"/>
												<xsl:value-of select="labels/dontshow" />
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" name="showaddress" value="Y" id="showaddress" checked="yes"/>
												<xsl:value-of select="labels/show" /><br />
												<input type="radio" class="inputbottom" name="showaddress" value="N" id="showaddress"/>
												<xsl:value-of select="labels/dontshow" />
											</xsl:otherwise>
										</xsl:choose>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/cityLiving" /> :
									</div>
									<div class="l2">
										<label id="city" class="textbox" style="width:204px;">
											<xsl:value-of select="$CITY_LABEL" />
										</label>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/country" /> :
									</div>
									<div class="l2">
										<label id="country_residence" style="width:204px">
											<xsl:value-of select="populate/country" />
										</label>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">&#160;</div>
									<div class="l2" style="float:right; text-align:right; padding-right:8px;">
										<input type="button" id="personal_details_next" value="{$btnNext}" class="button"/>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<!-- End Personal Details -->
							</div>
							<div class="spacer1">&#160;</div>
							<!-- start Personal details -->
							<div id="family_details_section" style="display:none">
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/familyValues" /> :
									</div>
									<div class="l2">
										<xsl:for-each select="populate/familyValues">
											<xsl:variable name="fam_val" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$FAMILY_VALUES = $fam_val">
													<input type="radio" class="inputbottom" value="{$fam_val}" name="family_values" checked="yes" id="family_values"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" value="{$fam_val}" name="family_values" id="family_values"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." />
										</xsl:for-each>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/familyType" /> :
									</div>
									<div class="l2">
										<xsl:for-each select="populate/familyType">
											<xsl:variable name="fam_type" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$FAMILY_TYPE = $fam_type">
													<input type="radio" class="inputbottom" value="{$fam_type}" name="family_type" checked="yes" id="family_type"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" value="{$fam_type}" name="family_type" id="family_type"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." />
										</xsl:for-each>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/familyStatus" /> :
									</div>
									<div class="l2">
										<xsl:for-each select="populate/familyStatus">
											<xsl:variable name="fam_status" select="@value" />
											<xsl:choose>
                                                                                                <xsl:when test="$FAMILY_STATUS = $fam_status">
													<input type="radio" class="inputbottom" value="{$fam_status}" name="family_status" checked="yes" id="family_status"/>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" value="{$fam_status}" name="family_status" id="family_status"/>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:value-of select="." />
										</xsl:for-each>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/fatherOccupation" /> :
									</div>
									<div class="l2">
										<select class="textbox" name="father_occupation" size="1" style="width:160px;" id="father_occupation">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/fatherOccupation">
												<xsl:variable name="fatherOcc" select="@value" />
												<xsl:choose>
													<xsl:when test="$FATHER_OCC = $fatherOcc">
														<option value="{$fatherOcc}">
															<xsl:value-of select="." selected="yes" />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="{$fatherOcc}">
															<xsl:value-of select="." />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:for-each>
										</select>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/motherOccupation" /> :
									</div>
									<div class="l2">
										<select class="textbox" name="mother_occupation" size="1" style="width:160px;" id="mother_occupation">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/motherOccupation">
												<xsl:variable name="motherOcc" select="@value" />
												<xsl:choose>
													<xsl:when test="$MOTHER_OCC = $motherOcc">
														<option value="{$motherOcc}" selected="yes">
															<xsl:value-of select="." />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="{$motherOcc}">
															<xsl:value-of select="." />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:for-each>
										</select>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/brothers/part1" /> :
									</div>
									<div class="l2">
										<select class="textbox" name="brothers" id="brothers" size="1" style="width:110px;">
											<xsl:choose>
                                                                                                <xsl:when test="$T_BROTHERS = ''">
													<option value="" selected="yes">
														<xsl:value-of select="tagLabels/pleaseSelect" />
													</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="">
														<xsl:value-of select="tagLabels/pleaseSelect" />
													</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:choose>
                                                                                                <xsl:when test="$T_BROTHERS = '0'">
													<option value="0" selected="yes">0</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="0">0</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:choose>
                                                                                                <xsl:when test="$T_BROTHERS = '1'">
													<option value="1" selected="yes">1</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="1">1</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:choose>
                                                                                                <xsl:when test="$T_BROTHERS = '2'">
													<option value="2" selected="yes">2</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="2">2</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:choose>
                                                                                                <xsl:when test="$T_BROTHERS = '3'">
													<option value="3" selected="yes">3</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="3">3</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:choose>
                                                                                                <xsl:when test="$T_BROTHERS = '4'">
													<option value="4" selected="yes">3+</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="4">3+</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</select>
										<div id="brothers_married_section" style="display:none">
											&#160;&#160;&#160;&#160;&#160;&#160;&#160;
											<xsl:value-of select="labels/brothers/part2" /> :
											<select class="textbox" size="1" name="married_brothers" style="width:110px;" id="married_brothers">
												<xsl:choose>
													<xsl:when test="$M_BROTHERS = ''">
														<option value="" selected="yes">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_BROTHERS = '0'">
														<option value="0" selected="yes">0</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="0">0</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_BROTHERS = '1'">
														<option value="1" selected="yes">1</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="1">1</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_BROTHERS = '2'">
														<option value="2" selected="yes">2</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="2">2</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_BROTHERS = '3'">
														<option value="3" selected="yes">3</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="3">3</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_BROTHERS = '4'">
														<option value="4" selected="yes">3+</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="4">3+</option>
													</xsl:otherwise>
												</xsl:choose>
											</select>
										</div>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/sisters/part1" /> :
									</div>
									<div class="l2">
										<select class="textbox" name="sisters" id="sisters" size="1" style="width:110px;">
											<xsl:choose>
                                                                                                <xsl:when test="$T_SISTERS = ''">
													<option value="" selected="yes">
														<xsl:value-of select="tagLabels/pleaseSelect" />
													</option>
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<option value="">
														<xsl:value-of select="tagLabels/pleaseSelect" />
													</option>
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<xsl:choose>
												<xsl:when test="$T_SISTERS = '0'">
													<option value="0" selected="yes">0</option>
												</xsl:when>
												<xsl:otherwise>
													<option value="0">0</option>
												</xsl:otherwise>
											</xsl:choose>
											<xsl:choose>
												<xsl:when test="$T_SISTERS = '1'">
													<option value="1" selected="yes">1</option>
												</xsl:when>
												<xsl:otherwise>
													<option value="1">1</option>
												</xsl:otherwise>
											</xsl:choose>
											<xsl:choose>
												<xsl:when test="$T_SISTERS = '2'">
													<option value="2" selected="yes">2</option>
												</xsl:when>
												<xsl:otherwise>
													<option value="2">2</option>
												</xsl:otherwise>
											</xsl:choose>
											<xsl:choose>
												<xsl:when test="$T_SISTERS = '3'">
													<option value="3" selected="yes">3</option>
												</xsl:when>
												<xsl:otherwise>
													<option value="3">3</option>
												</xsl:otherwise>
											</xsl:choose>
											<xsl:choose>
												<xsl:when test="$T_SISTERS = '4'">
													<option value="4" selected="yes">3+</option>
												</xsl:when>
												<xsl:otherwise>
													<option value="4">3+</option>
												</xsl:otherwise>
											</xsl:choose>
										</select>
										<div id="sisters_married_section" style="display:none">
											&#160;&#160;&#160;&#160;&#160;&#160;&#160;
											<xsl:value-of select="labels/sisters/part2" /> :
											<select class="textbox" name="married_sisters" size="1" style="width:110px;" id="married_sisters">
												<xsl:choose>
													<xsl:when test="$M_SISTERS = ''">
														<option value="" selected="yes">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_SISTERS = '0'">
														<option value="0" selected="yes">0</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="0">0</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_SISTERS = '1'">
														<option value="1" selected="yes">1</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="1">1</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_SISTERS = '2'">
														<option value="2" selected="yes">2</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="2">2</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_SISTERS = '3'">
														<option value="3" selected="yes">3</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="3">3</option>
													</xsl:otherwise>
												</xsl:choose>
												<xsl:choose>
													<xsl:when test="$M_SISTERS = '4'">
														<option value="4" selected="yes">3+</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="4">3+</option>
													</xsl:otherwise>
												</xsl:choose>
											</select>
										</div>
									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/liveWithParents" /> :
									</div>
									<div class="l2">
										<xsl:choose>
											<xsl:when test="$LIVE_WITH_PARENTS = 'NA'">
												<input type="radio" class="inputbottom" value="Y" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" value="N" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/no" />
												<input type="radio" class="inputbottom" value="NA" name="live_with_parents" checked="yes" id="live_with_parents"/>
												<xsl:value-of select="labels/notApplicable" />
											</xsl:when>
											<xsl:when test="$LIVE_WITH_PARENTS = 'N'">
												<input type="radio" class="inputbottom" value="Y" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" value="N" name="live_with_parents" checked="yes" id="live_with_parents"/>
												<xsl:value-of select="labels/no" />
												<input type="radio" class="inputbottom" value="NA" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/notApplicable" />
											</xsl:when>
											<xsl:when test="$LIVE_WITH_PARENTS = 'Y'">
												<input type="radio" class="inputbottom" value="Y" name="live_with_parents" checked="yes" id="live_with_parents"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" value="N" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/no" />
												<input type="radio" class="inputbottom" value="NA" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/notApplicable" />
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" value="Y" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" value="N" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/no" />
												<input type="radio" class="inputbottom" value="NA" name="live_with_parents" id="live_with_parents"/>
												<xsl:value-of select="labels/notApplicable" />
											</xsl:otherwise>
										</xsl:choose>
									</div>
								</div>
								<!-- About Family Section starts from here -->
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/writeAboutFamily" /> :
									</div>
									<div id='translControl' style="position: relative;">
										<input type="checkbox" id="checkboxId"></input>
										<select onchange="javascript:languageChangeHandler(languageDropDown1);" Id="languageDropDown1" title='languageDropDown1' target='about_family'>
											<option value="hi">हिन्दी</option>
											<option value="ta">தமிழ்</option>
											<option value="te">తెలుగు</option>
											<option value="kn">ಕನ್ನಡ</option>
											<option value="ml">മലയാളം</option>
										</select>
										<div id="aboutfamily_help" class="helpbox" style="display:block;left: 290px; top: -15px;_left:135px;">
											<div class="helptext">
												<xsl:value-of select="labels/aboutFamily"/>
												    <div style="position:absolute; top:0;right:3px">
													<a href="javascript:void(0);" id="close_help_af" class="b blink">[x]</a>
												    </div>
												    <div class="helpimg" style="top:25px;"></div>
											</div>
										</div>
									</div>
									<div id="errorDiv"></div>
									<div class="row">
											 <div class="l1"></div>
											<div class="l2">
						<!--# Changes Done for the About Family Section by Anurag -->
											<div class="lf">
												<textarea class="" rows="13" cols="5" name="about_family" id="about_family" style="overflow-y:scroll;overflow-x:auto;height:107px;width:400px;margin-top:12px;">
													<xsl:choose>
														<xsl:when test="$FAMILY_INFO != ''">	
															<xsl:value-of select="$FAMILY_INFO" />
														</xsl:when>
														<xsl:otherwise>
															<xsl:value-of select="help/writeAboutYourFamily"/>
														</xsl:otherwise>
													 </xsl:choose>
												</textarea>
											</div>
											<div class="transTip" >
												<div class="spacer1">&#160;</div>
												<div class="lf green b">
													<xsl:value-of select="labels/trans/tipPart1"/>
												</div>
												<div class="lf mar_left_10">
													<xsl:value-of select="labels/trans/tipPart2"/>
													<br></br>
													<xsl:value-of select="labels/trans/tipPart3"/>
													<a href="{$MORE}" id="more">
														<xsl:value-of select="labels/trans/tipPart4"/>
													</a>
												</div>
											</div>
												<xsl:variable name="aboutFamilyDefault" select="help/writeAboutYourFamily" />
												<input type="hidden" name="about_family_default" value="{$aboutFamilyDefault}"/>
												<div id="spellcheck1" style="display:inline;">
													<div class="spacer1">&#160;</div>
													<img src="{$IMG_URL}/spell-check.gif" onClick="spellcheckxx();return false;" style="cursor:pointer;" />
												</div>	
												
												<div class="coverhelp fl" style="left:265px; top:0px">
													<div class="helpbox" id="about_family_help" style="top:-100px;width:140px;">
														<div class="helptext" style="width:130px;">
															<xsl:value-of select="help/writeAboutFamily"/>
															<div class="helpimg" style="top:90px;"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
										</div>
										<div class="spacer1">&#160;</div>
											<div class="row">
											<div class="l1"></div>
												<div class="l2" style="float:right; text-align:right; padding-right:8px;">
												<input type="button" id="family_details_back" value="{$btnBack}" class="button"/>&#160;
												<input type="button" id="family_details_next" value="{$btnNext}" class="button"/>
											</div>
										</div>
									</div>
							
							<!-- End Personal Details -->
							<div class="spacer1">&#160;</div>
							<!-- Start Education Details -->
							<div id="education_profession_section" style="display:none">
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/aboutEducation" /> :
									</div>
									<div class="l2">  
					<!--# Changes Done for the About Education Section by Anurag -->
										<xsl:variable name="aboutYourEducationDefault" select="help/aboutYourEducation" />
										<input  type="hidden" name="about_education_default" value="{$aboutYourEducationDefault}" />
								<xsl:choose>
									<xsl:when test="$EDUCATION != ''">
										<input class="grey" type="textbox" style="width:330px;color:#989491;font-size:11px" value="{$EDUCATION}" name="about_education" id="about_education" autocomplete="off"/> 
									</xsl:when>
									<xsl:otherwise>
										<input class="grey" type="textbox" style="width:330px;color:#989491;font-size:11px" value="{$aboutYourEducationDefault}" name="about_education" id="about_education" autocomplete="off"/> 

									</xsl:otherwise>
								</xsl:choose> 
										<div class="coverhelp fl" style="top: 0px; left: 335px;">
											<div class="helpbox" id="about_education_help" style="top:-20px;width:150px;" >
												<div class="helptext" style="width:140px">
													<xsl:value-of select="help/aboutEducation"/>
													<div class="helpimg"></div>
												</div>
											</div>
										</div>


									</div>
								</div>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/workStatus" /> :
									</div>
									<div class="l2">
										<select class="textbox" name="work_status" size="1" style="width:220px;" id="work_status">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/workStatus">
												<xsl:variable name="work" select="@value" />
												<xsl:choose>
													<xsl:when test="$WORK_STATUS = $work">
														<option value="{$work}">
															<xsl:value-of select="." />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="{$work}">
															<xsl:value-of select="." />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:for-each>
										</select> 
									</div>
								</div>
								<xsl:if test="$GENDER = 'F'">
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/workAfterMarriage" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$MARRIED_WORKING = 'Y'">
													<input type="radio" class="inputbottom" name="married_working" value="Y" checked="yes" id="married_working"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="married_working" value="N" id="married_working"/>
													<xsl:value-of select="labels/no" />
													<input type="radio" class="inputbottom" name="married_working" value="D" id="married_working"/>
													<xsl:value-of select="labels/undecided" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$MARRIED_WORKING = 'N'">
													<input type="radio" class="inputbottom" name="married_working" value="Y" id="married_working"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="married_working" value="N" checked="yes" id="married_working"/>
													<xsl:value-of select="labels/no" />
													<input type="radio" class="inputbottom" name="married_working" value="D" id="married_working"/>
													<xsl:value-of select="labels/undecided" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$MARRIED_WORKING = 'D'">
													<input type="radio" class="inputbottom" name="married_working" value="Y" id="married_working"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="married_working" value="N" id="married_working"/>
													<xsl:value-of select="labels/no" />
													<input type="radio" class="inputbottom" name="married_working" value="D" checked="yes" id="married_working"/>
													<xsl:value-of select="labels/undecided" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="married_working" value="Y" id="married_working"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="married_working" value="N" id="married_working"/>
													<xsl:value-of select="labels/no" />
													<input type="radio" class="inputbottom" name="married_working" value="D" id="married_working"/>
													<xsl:value-of select="labels/undecided" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
											<div class="coverhelp fl" style="left:170px; top:0px">
												<div class="helpbox" id="married_working_help" style="top:-20px;width:150px;">
													<div class="helptext" style="width:140px">
														<xsl:value-of select="help/workAfterMarriage"/>
														<div class="helpimg"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</xsl:if>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/aboutWork" /> :
									</div>
									<div class="l2">
					<!--# Changes Done for the About Work Section by Anurag -->
										<xsl:variable name="aboutYourWorkDefault" select="help/aboutYourWork" />
										<input type="hidden" name="about_work_default" value="{$aboutYourWorkDefault}" />
										<xsl:choose>
											<xsl:when test="$JOB_INFO != ''">
												<input class="grey" type="textbox" style="width:330px;color:#989491;font-size:11px" value="{$JOB_INFO}" name="about_work" id="about_work" autocomplete="off"/>
											</xsl:when>
											<xsl:otherwise>
												<input class="grey" type="textbox" style="width:330px;color:#989491;font-size:11px" value="{$aboutYourWorkDefault}" name="about_work" id="about_work" autocomplete="off" />
											</xsl:otherwise>
										</xsl:choose>
										<div class="coverhelp fl" style="top: 0px; left: 335px;">
											<div class="helpbox" id="about_work_help" style="top:-55px; width:150px">
												<div class="helptext" style="width:140px">
													<xsl:value-of select="help/aboutWork"/>
													<div class="helpimg" style="top:40px"></div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="spacer1">&#160;</div>
								<br />
								<br />
								<br />
								<div class="row">
									<div class="l1"></div>
									<div class="l2" style="float:right; text-align:right; padding-right:8px;">
										<input type="button" id="education_profession_back" value="{$btnBack}" class="button"/>&#160;
										<input type="button" id="education_profession_next" value="{$btnNext}" class="button"/>
									</div>
								</div>
							</div>
							<!-- End Edu Details -->
							<div class="spacer1">&#160;</div>
							<!-- start religion details -->
							<div id="religion_ethnicity_section" style="display:none">
								<xsl:if test="$CASTE_SEL = 'HINDU'">
								<div id="hindu_section">
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/subcaste" /> :
										</div>
										<div class="l2" style="position:relative">
											<input class="textbox" type="textbox" style="width: 204px;" value="{$SUBCASTE}" autocomplete="off" name="subcaste" id="subcaste"/>
											<div id="subcaste_results" class="autosuggestresults">
											</div>
											<iframe id="subcaste_results_iframe" class="autosuggestiframe iframetrans"></iframe>
											<div class="coverhelp">
												<div class="helpbox extraHelpbox" id="subcaste_help" style="width:200px">
													<div class="helptext">
														<xsl:value-of select="help/subcaste"/>
														<div class="helpimg"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/gotra" /> :
										</div>
										<div class="l2" style="position:relative">
											<input class="textbox" type="textbox" style="width: 204px;" value="{$GOTHRA}" name="gotra" id="gotra" autocomplete="off"/> 
											<div id="gotra_results" class="autosuggestresults">
											</div>
											<iframe id="gotra_results_iframe" class="autosuggestiframe iframetrans"></iframe>
											<div class="coverhelp">
												<div class="helpbox extraHelpbox" id="gotra_help" style="width:200px">
													<div class="helptext">
														<xsl:value-of select="help/gotra"/>
														<div class="helpimg"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/ancestralOrigin" /> :
										</div>
										<div class="l2">
											<input class="textbox" type="textbox" style="width: 204px;" value="{$ANCESTRAL_ORIGIN}" name="ancestral_origin" id="ancestral_origin"/> 
											<div class="coverhelp">
												<div class="helpbox extraHelpbox" id="ancestral_origin_help" style="width:200px">
													<div class="helptext">
														<xsl:value-of select="help/ancestralOrigin"/>
														<div class="helpimg"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:choose>
												<xsl:when test="($MTONGUE = '3') or ($MTONGUE = '16')">
													<xsl:value-of select="labels/kujaDosham" /> :
												</xsl:when>
												<xsl:when test="($MTONGUE = '17') or ($MTONGUE = '31')">
													<xsl:value-of select="labels/chovvaDosham" /> :
												</xsl:when>
												<xsl:otherwise>
													<xsl:value-of select="labels/manglik" /> :
												</xsl:otherwise>
											</xsl:choose>
										</div>
										<div class="l2">
											<select class="textbox" name="manglik" size="1" style="width:209px;" id="manglik">
												<xsl:choose>
													<xsl:when test="$MANGLIK = 'D'">
														<option value="">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
														<option value="D" selected="yes">
															<xsl:value-of select="tagLabels/dontKnow" />
														</option>
														<option value="Y">
															<xsl:value-of select="labels/yes" />
														</option>
														<option value="N">
															<xsl:value-of select="labels/no" />
														</option>
													</xsl:when>
													<xsl:when test="$MANGLIK = 'Y'">
														<option value="">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
														<option value="D">
															<xsl:value-of select="tagLabels/dontKnow" />
														</option>
														<option value="Y" selected="yes">
															<xsl:value-of select="labels/yes" />
														</option>
														<option value="N">
															<xsl:value-of select="labels/no" />
														</option>
													</xsl:when>
													<xsl:when test="$MANGLIK = 'N'">
														<option value="">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
														<option value="D">
															<xsl:value-of select="tagLabels/dontKnow" />
														</option>
														<option value="Y">
															<xsl:value-of select="labels/yes" />
														</option>
														<option value="N" selected="yes">
															<xsl:value-of select="labels/no" />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="" selected="yes">
															<xsl:value-of select="tagLabels/pleaseSelect" />
														</option>
														<option value="D">
															<xsl:value-of select="tagLabels/dontKnow" />
														</option>
														<option value="Y">
															<xsl:value-of select="labels/yes" />
														</option>
														<option value="N">
															<xsl:value-of select="labels/no" />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</select> 
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/nakshatra" /> :
										</div>
										<div class="l2">
											<select class="textbox" name="nakshatra" size="1" style="width:209px;" id="nakshatra">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/nakshatra">
													<xsl:variable name="nak" select="@value" />
													<xsl:choose>
														<xsl:when test="$NAKSHATRA = $nak">
															<option value="{$nak}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$nak}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select> 
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/rashi" /> :
										</div>
										<div class="l2">
											<select class="textbox" name="rashi" size="1" style="width:209px;" id="rashi">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/rashi">
													<xsl:variable name="rash" select="@value" />
													<xsl:choose>
														<xsl:when test="$RASHI = $rash">
															<option value="{$rash}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$rash}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select> 
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/horoscopeMatch" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$HOROSCOPE_MATCH = 'N'">
													<input type="radio" class="inputbottom" name="horoscope_match" value="M" id="horoscope_match"/>
													<xsl:value-of select="labels/must" />
													<input type="radio" class="inputbottom" name="horoscope_match" value="N" checked="yes" id="horoscope_match"/>
													<xsl:value-of select="labels/notNecessary" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$HOROSCOPE_MATCH = 'M'">
													<input type="radio" class="inputbottom" name="horoscope_match" value="M" checked="yes" id="horoscope_match"/>
													<xsl:value-of select="labels/must" />
													<input type="radio" class="inputbottom" name="horoscope_match" value="N" id="horoscope_match"/>
													<xsl:value-of select="labels/notNecessary" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="horoscope_match" value="M" id="horoscope_match"/>
													<xsl:value-of select="labels/must" />
													<input type="radio" class="inputbottom" name="horoscope_match" value="N" id="horoscope_match"/>
													<xsl:value-of select="labels/notNecessary" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/horoscope" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$HOROSCOPE = 'Y'">
													<input type="radio" class="inputbottom" name="horoscope" value="Y" checked = "yes" id="horoscope"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="horoscope" value="N" id="horoscope"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$HOROSCOPE = 'N'">
													<input type="radio" class="inputbottom" name="horoscope" value="Y" id="horoscope"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="horoscope" value="N" checked="yes" id="horoscope"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="horoscope" value="Y" id="horoscope"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="horoscope" value="N" id="horoscope"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose><br></br><br></br> 
											<div id="horoscope_frame" style="display:none">
												<iframe vspace="0" hspace="0" marginheight="0" marginwidth="0" width="400" height="350" frameborder="0" scrolling="no" src="http://jeevansathi.matchstro.com/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?BirthPlace?JS_UniqueID={$PROFILEID}&amp;JS_Year={$YEAR_OF_BIRTH}&amp;JS_Month={$MONTH_OF_BIRTH}&amp;JS_Day={$DAY_OF_BIRTH}"></iframe>
											</div>
										</div>
									</div>
								</div>
								</xsl:if>
								<xsl:if test="$CASTE_SEL = 'CHRISTIAN'">
								<div id="christian_section">
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/diocese" /> :
										</div>
										<div class="l2" style="position:relative">
											<input class="textbox" type="textbox" style="width: 204px;" value="{$DIOCESE}" autocomplete="off" name="diocese" id="diocese"/>
											<div id="diocese_results" class="autosuggestresults">
											</div>
											<iframe id="diocese_results_iframe" class="autosuggestiframe iframetrans"></iframe>
											<div class="coverhelp">
												<div class="helpbox extraHelpbox" id="diocese_help" style="width:200px">
													<div class="helptext" style="width:195px">
														<xsl:value-of select="help/diocese"/>
														<div class="helpimg"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/baptised" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$BAPTISED = 'Y'">
													<input type="radio" class="inputbottom" name="baptised" value="Y" checked="yes" id="baptised"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="baptised" value="N" id="baptised"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$BAPTISED = 'N'">
													<input type="radio" class="inputbottom" name="baptised" value="Y" id="baptised"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="baptised" value="N" checked="yes" id="baptised"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="baptised" value="Y" id="baptised"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="baptised" value="N" id="baptised"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/readBible" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$READ_BIBLE = 'Y'">
													<input type="radio" class="inputbottom" name="read_bible" value="Y" checked="yes" id="read_bible"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="read_bible" value="N" id="read_bible"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$READ_BIBLE = 'N'">
													<input type="radio" class="inputbottom" name="read_bible" value="Y" id="read_bible"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="read_bible" value="N" checked="yes" id="read_bible"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="read_bible" value="Y" id="read_bible"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="read_bible" value="N" id="read_bible"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/offerTithe" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$OFFER_TITHE = 'Y'">
													<input type="radio" class="inputbottom" name="offer_tithe" value="Y" checked="yes" id="offer_tithe"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="offer_tithe" value="N" id="offer_tithe"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$OFFER_TITHE = 'N'">
													<input type="radio" class="inputbottom" name="offer_tithe" value="Y" id="offer_tithe"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="offer_tithe" value="N" checked="yes" id="offer_tithe"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="offer_tithe" value="Y" id="offer_tithe"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="offer_tithe" value="N" id="offer_tithe"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/spreadingGospel" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$SPREADING_GOSPEL = 'Y'">
													<input type="radio" class="inputbottom" name="spreading_gospel" value="Y" checked="yes" id="spreading_gospel"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="spreading_gospel" value="N" id="spreading_gospel"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$SPREADING_GOSPEL = 'N'">
													<input type="radio" class="inputbottom" name="spreading_gospel" value="Y" id="spreading_gospel"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="spreading_gospel" value="N" checked="yes" id="spreading_gospel"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="spreading_gospel" value="Y" id="spreading_gospel"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="spreading_gospel" value="N" id="spreading_gospel"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
								</div>
								</xsl:if>
								<xsl:if test="$CASTE_SEL = 'MUSLIM'">
								<div id="muslim_section">
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/maththab" /> :
										</div>
										<div class="l2">
											<select name="maththab" size="1" class="textbox" style="width:209px;" id="maththab">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/maththab">
													<xsl:variable name="maththab_val" select="@value" />
													<xsl:choose>
														<xsl:when test="$MATHTHAB = $maththab_val">
															<option value="{$maththab_val}" selected="yes">
																<xsl:value-of disable-output-escaping="yes" select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$maththab_val}">
																<xsl:value-of disable-output-escaping="yes" select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/namaz" /> :
										</div>
										<div class="l2">
											<select name="namaz" size="1" class="textbox" style="width:209px;" id="namaz">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/namaz">
													<xsl:variable name="namaz_val" select="@value" />
													<xsl:choose>
														<xsl:when test="$NAMAZ = $namaz_val">
															<option value="{$namaz_val}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$namaz_val}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/zakat" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$ZAKAT = 'Y'">
													<input type="radio" class="inputbottom" name="zakat" value="Y" checked="yes" id="zakat"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="zakat" value="N" id="zakat"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$ZAKAT = 'N'">
													<input type="radio" class="inputbottom" name="zakat" value="Y" id="zakat"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="zakat" value="N" checked="yes" id="zakat"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="zakat" value="Y" id="zakat"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="zakat" value="N" id="zakat"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/fasting" /> :
										</div>
										<div class="l2">
											<select name="fasting" size="1" class="textbox" style="width:209px;" id="fasting">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/fasting">
													<xsl:variable name="fast" select="@value" />
													<xsl:choose>
														<xsl:when test="$FASTING = $fast">
															<option value="{$fast}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$fast}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/umrahHajj" /> :
										</div>
										<div class="l2">
											<select name="umrah_hajj" size="1" class="textbox" style="width:209px;" id="umrah_hajj">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/umrahHajj">
													<xsl:variable name="hajj" select="@value" />
													<xsl:choose>
														<xsl:when test="$UMRAH_HAJJ = $hajj">
															<option value="{$hajj}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$hajj}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/quran" /> :
										</div>
										<div class="l2">
											<select name="quran" size="1" class="textbox" style="width:209px;" id="quran">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/quran">
													<xsl:variable name="quran" select="@value" />
													<xsl:choose>
														<xsl:when test="$QURAN = $quran">
															<option value="{$quran}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$quran}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									<xsl:if test="$GENDER = 'M'">
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/sunnahBeard" /> :
										</div>
										<div class="l2">
											<select name="sunnah_beard" size="1" class="textbox" style="width:209px;" id="sunnah_beard">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/sunnahBeard">
													<xsl:variable name="beard" select="@value" />
													<xsl:choose>
														<xsl:when test="$SUNNAH_BEARD = $beard">
															<option value="{$beard}">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$beard}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="row">
										<div class="l1">
											<xsl:value-of select="labels/sunnahCap" /> :
										</div>
										<div class="l2">
											<select name="sunnah_cap" size="1" class="textbox" style="width:209px;" id="sunnah_cap">
												<option value="">
													<xsl:value-of select="tagLabels/pleaseSelect" />
												</option>
												<xsl:for-each select="populate/sunnahCap">
													<xsl:variable name="cap" select="@value" />
													<xsl:choose>
														<xsl:when test="$SUNNAH_CAP = $cap">
															<option value="{$cap}" selected="yes">
																<xsl:value-of select="." />
															</option>
														</xsl:when>
														<xsl:otherwise>
															<option value="{$cap}">
																<xsl:value-of select="." />
															</option>
														</xsl:otherwise>
													</xsl:choose>
												</xsl:for-each>
											</select>
										</div>
									</div>
									</xsl:if>
									<xsl:if test="$GENDER = 'M'">
										<div class="spacer1">&#160;</div>
										<div class="row">
											<div class="l1">
												<xsl:value-of select="labels/hijab" /> :
											</div>
											<div class="l2">
												<xsl:choose>
													<xsl:when test="$HIJAB = 'Y'">
														<input type="radio" class="inputbottom" name="hijab" value="Y" checked="yes" id="hijab"/>
														<xsl:value-of select="labels/mustAfterNikah" />
														<input type="radio" class="inputbottom" name="hijab" value="N" id="hijab"/>
														<xsl:value-of select="labels/notNecessary" />
													</xsl:when>
													<xsl:when test="$HIJAB = 'N'">
														<input type="radio" class="inputbottom" name="hijab" value="Y" id="hijab"/>
														<xsl:value-of select="labels/mustAfterNikah" />
														<input type="radio" class="inputbottom" name="hijab" value="N" checked="yes" id="hijab"/>
														<xsl:value-of select="labels/notNecessary" />
													</xsl:when>
													<xsl:otherwise>
														<input type="radio" class="inputbottom" name="hijab" value="Y" id="hijab"/>
														<xsl:value-of select="labels/mustAfterNikah" />
														<input type="radio" class="inputbottom" name="hijab" value="N" id="hijab"/>
														<xsl:value-of select="labels/notNecessary" />
													</xsl:otherwise>
												</xsl:choose>
											</div>
										</div>
									</xsl:if>
									<xsl:if test="$GENDER = 'F'">
										<div class="spacer1">&#160;</div>
										<div class="row">
											<div class="l1">
												<xsl:value-of select="labels/hijabMarriage" /> :
											</div>
											<div class="l2">
												<xsl:choose>
													<xsl:when test="$HIJAB_MARRIAGE = 'Y'">
														<input type="radio" class="inputbottom" name="hijab_marriage" value="Y" checked="yes" id="hijab_marriage"/>
														<xsl:value-of select="labels/willing" />
														<input type="radio" class="inputbottom" name="hijab_marriage" value="N" id="hijab_marriage"/>
														<xsl:value-of select="labels/notWilling" />
													</xsl:when>
													<xsl:when test="$HIJAB_MARRIAGE = 'N'">
														<input type="radio" class="inputbottom" name="hijab_marriage" value="Y" id="hijab_marriage"/>
														<xsl:value-of select="labels/willing" />
														<input type="radio" class="inputbottom" name="hijab_marriage" value="N" checked="yes" id="hijab_marriage"/>
														<xsl:value-of select="labels/notWilling" />
													</xsl:when>
													<xsl:otherwise>
														<input type="radio" class="inputbottom" name="hijab_marriage" value="Y" id="hijab_marriage"/>
														<xsl:value-of select="labels/willing" />
														<input type="radio" class="inputbottom" name="hijab_marriage" value="N" id="hijab_marriage"/>
														<xsl:value-of select="labels/notWilling" />
													</xsl:otherwise>
												</xsl:choose>
											</div>
										</div>
									</xsl:if>
									<xsl:if test="$GENDER = 'M'">
										<div class="spacer1">&#160;</div>
										<div class="row">
											<div class="l1">
												<xsl:value-of select="labels/workingMarriage" /> :
											</div>
											<div class="l2">
												<xsl:choose>
													<xsl:when test="$WORKING_MARRIAGE = 'Y'">
														<input type="radio" class="inputbottom" name="working_marriage" value="Y" checked="yes" id="working_marriage"/>
														<xsl:value-of select="labels/can" />
														<input type="radio" class="inputbottom" name="working_marriage" value="N" id="working_marriage"/>
														<xsl:value-of select="labels/preferHouseWife" />
													</xsl:when>
													<xsl:when test="$WORKING_MARRIAGE = 'N'">
														<input type="radio" class="inputbottom" name="working_marriage" value="Y" id="working_marriage"/>
														<xsl:value-of select="labels/can" />
														<input type="radio" class="inputbottom" name="working_marriage" value="N" checked="yes" id="working_marriage"/>
														<xsl:value-of select="labels/preferHouseWife" />
													</xsl:when>
													<xsl:otherwise>
														<input type="radio" class="inputbottom" name="working_marriage" value="Y" id="working_marriage"/>
														<xsl:value-of select="labels/can" />
														<input type="radio" class="inputbottom" name="working_marriage" value="N" id="working_marriage"/>
														<xsl:value-of select="labels/preferHouseWife" />
													</xsl:otherwise>
												</xsl:choose>
											</div>
										</div>
									</xsl:if>
								</div>
								</xsl:if>
								<xsl:if test="$CASTE_SEL = 'JAIN'">
								<div id="jain_section">
									<div class="spacer1">&#160;</div>
									<div class="l1">
										<xsl:value-of select="labels/sampraday" /> :
									</div>
									<div class="l2">
										<select name="sampraday" size="1" class="textbox" style="width:209px;" id="sampraday">
											<option value="">
												<xsl:value-of select="tagLabels/pleaseSelect" />
											</option>
											<xsl:for-each select="populate/sampraday">
												<xsl:variable name="sam" select="@value" />
												<xsl:choose>
													<xsl:when test="$SAMPRADAY = $sam">
														<option value="{$sam}" selected="yes">
															<xsl:value-of select="." />
														</option>
													</xsl:when>
													<xsl:otherwise>
														<option value="{$sam}">
															<xsl:value-of select="." />
														</option>
													</xsl:otherwise>
												</xsl:choose>
											</xsl:for-each>
										</select>
									</div>
								</div>
								</xsl:if>
								<xsl:if test="$CASTE_SEL = 'SIKH'">
								<div id="sikh_section">
									<div class="spacer1">&#160;</div>
									<div class="l1">
										<xsl:value-of select="labels/amritdhari" /> :
									</div>
									<div class="l2">
										<div id="amritdhari_section">
											<xsl:choose>
                                                                                                <xsl:when test="$AMRITDHARI = 'Y'">
													<input type="radio" class="inputbottom" name="amritdhari" value="Y" checked="yes" id="amritdhari"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="amritdhari" value="N" id="amritdhari"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$AMRITDHARI = 'N'">
													<input type="radio" class="inputbottom" name="amritdhari" value="Y" id="amritdhari"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="amritdhari" value="N" checked="yes" id="amritdhari"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="amritdhari" value="Y" id="amritdhari"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="amritdhari" value="N" id="amritdhari"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<div id="cut_hair_section" style="display:none">
										<div class="spacer1">&#160;</div>
										<div class="l1">
											<xsl:value-of select="labels/cutHair" /> :
										</div>
										<div class="l2">
											<xsl:choose>
                                                                                                <xsl:when test="$CUT_HAIR = 'Y'">
													<input type="radio" class="inputbottom" name="cut_hair" value="Y" checked="yes" id="cut_hair"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="cut_hair" value="N" id="cut_hair"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:when test="$CUT_HAIR = 'N'">
													<input type="radio" class="inputbottom" name="cut_hair" value="Y" id="cut_hair"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="cut_hair" value="N" checked="yes" id="cut_hair"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:when>
                                                                                                <xsl:otherwise>
													<input type="radio" class="inputbottom" name="cut_hair" value="Y" id="cut_hair"/>
													<xsl:value-of select="labels/yes" />
													<input type="radio" class="inputbottom" name="cut_hair" value="N" id="cut_hair"/>
													<xsl:value-of select="labels/no" />
                                                                                                </xsl:otherwise>
                                                                                        </xsl:choose>
										</div>
									</div>
									<xsl:if test="$GENDER = 'M'">
										<div id="sikh_males_only_section" style="display:none">
											<div class="spacer1">&#160;</div>
											<div class="l1">
												<xsl:value-of select="labels/trimBeard" /> :
											</div>
											<div class="l2">
												<xsl:choose>
													<xsl:when test="$TRIM_BEARD = 'Y'">
														<input type="radio" class="inputbottom" name="trim_beard" value="Y" checked="yes" id="trim_beard"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="trim_beard" value="N" id="trim_beard"/>
														<xsl:value-of select="labels/no" />
													</xsl:when>
													<xsl:when test="$TRIM_BEARD = 'N'">
														<input type="radio" class="inputbottom" name="trim_beard" value="Y" id="trim_beard"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="trim_beard" value="N" checked="yes" id="trim_beard"/>
														<xsl:value-of select="labels/no" />
													</xsl:when>
													<xsl:otherwise>
														<input type="radio" class="inputbottom" name="trim_beard" value="Y" id="trim_beard"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="trim_beard" value="N" id="trim_beard"/>
														<xsl:value-of select="labels/no" />
													</xsl:otherwise>
												</xsl:choose>
											</div>
											<div class="spacer1">&#160;</div>
											<div class="l1">
												<xsl:value-of select="labels/wearTurban" /> :
											</div>
											<div class="l2">
												<xsl:choose>
													<xsl:when test="$WEAR_TURBAN = 'Y'">
														<input type="radio" class="inputbottom" name="wear_turban" value="Y" checked="yes" id="wear_turban"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="wear_turban" value="N" id="wear_turban"/>
														<xsl:value-of select="labels/no" />
														<input type="radio" class="inputbottom" name="wear_turban" value="O" id="wear_turban"/>
														<xsl:value-of select="labels/ocasionally" />
													</xsl:when>
													<xsl:when test="$WEAR_TURBAN = 'N'">
														<input type="radio" class="inputbottom" name="wear_turban" value="Y" id="wear_turban"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="wear_turban" value="N" checked="yes" id="wear_turban"/>
														<xsl:value-of select="labels/no" />
														<input type="radio" class="inputbottom" name="wear_turban" value="O" id="wear_turban"/>
														<xsl:value-of select="labels/ocasionally" />
													</xsl:when>
													<xsl:when test="$WEAR_TURBAN = 'O'">
														<input type="radio" class="inputbottom" name="wear_turban" value="Y" id="wear_turban"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="wear_turban" value="N" id="wear_turban"/>
														<xsl:value-of select="labels/no" />
														<input type="radio" class="inputbottom" name="wear_turban" value="O" checked="yes" id="wear_turban"/>
														<xsl:value-of select="labels/ocasionally" />
													</xsl:when>
													<xsl:otherwise>
														<input type="radio" class="inputbottom" name="wear_turban" value="Y" id="wear_turban"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="wear_turban" value="N" id="wear_turban"/>
														<xsl:value-of select="labels/no" />
														<input type="radio" class="inputbottom" name="wear_turban" value="O" id="wear_turban"/>
														<xsl:value-of select="labels/ocasionally" />
													</xsl:otherwise>
												</xsl:choose>
											</div>
											<div class="spacer1">&#160;</div>
											<div class="l1">
												<xsl:value-of select="labels/cleanShaven" /> :
											</div>
											<div class="l2">
												<xsl:choose>
													<xsl:when test="$CLEAN_SHAVEN = 'Y'">
														<input type="radio" class="inputbottom" name="clean_shaven" value="Y" checked="yes" id="clean_shaven"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="clean_shaven" value="N" id="clean_shaven"/>
														<xsl:value-of select="labels/no" />
													</xsl:when>
													<xsl:when test="$CLEAN_SHAVEN = 'N'">
														<input type="radio" class="inputbottom" name="clean_shaven" value="Y" id="clean_shaven"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="clean_shaven" value="N" checked="yes" id="clean_shaven"/>
														<xsl:value-of select="labels/no" />
													</xsl:when>
													<xsl:otherwise>
														<input type="radio" class="inputbottom" name="clean_shaven" value="Y" id="clean_shaven"/>
														<xsl:value-of select="labels/yes" />
														<input type="radio" class="inputbottom" name="clean_shaven" value="N" id="clean_shaven"/>
														<xsl:value-of select="labels/no" />
													</xsl:otherwise>
												</xsl:choose>
											</div>
										</div>
									</xsl:if>
								</div>
								</xsl:if>
								<xsl:if test="$CASTE_SEL = 'PARSI'">
								<div id="parsi_section">
									<div class="spacer1">&#160;</div>
									<div class="l1">
										<xsl:value-of select="labels/zarathushtri" /> :
									</div>
									<div class="l2">
										<xsl:choose>
											<xsl:when test="$ZARATHUSHTRI = 'Y'">
												<input type="radio" class="inputbottom" name="zarathushtri" value="Y" checked="yes" id="zarathushtri"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" name="zarathushtri" value="N" id="zarathushtri"/>
												<xsl:value-of select="labels/no" />
											</xsl:when>
											<xsl:when test="$ZARATHUSHTRI = 'N'">
												<input type="radio" class="inputbottom" name="zarathushtri" value="Y" id="zarathushtri"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" name="zarathushtri" value="N" checked="yes" id="zarathushtri"/>
												<xsl:value-of select="labels/no" />
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" name="zarathushtri" value="Y" id="zarathushtri"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" name="zarathushtri" value="N" id="zarathushtri"/>
												<xsl:value-of select="labels/no" />
											</xsl:otherwise>
										</xsl:choose>
									</div>
									<div class="spacer1">&#160;</div>
									<div class="l1">
										<xsl:value-of select="labels/parentsZarathushtri" /> :
									</div>
									<div class="l2">
										<xsl:choose>
											<xsl:when test="$PARENTS_ZARATHUSHTRI = 'Y'">
												<input type="radio" class="inputbottom" name="parents_zarathushtri" value="Y" checked="yes" id="parents_zarathushtri"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" name="parents_zarathushtri" value="N" id="parents_zarathushtri"/>
												<xsl:value-of select="labels/no" />
											</xsl:when>
											<xsl:when test="$PARENTS_ZARATHUSHTRI = 'N'">
												<input type="radio" class="inputbottom" name="parents_zarathushtri" value="Y" id="parents_zarathushtri"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" name="parents_zarathushtri" value="N" checked="yes" id="parents_zarathushtri"/>
												<xsl:value-of select="labels/no" />
											</xsl:when>
											<xsl:otherwise>
												<input type="radio" class="inputbottom" name="parents_zarathushtri" value="Y" id="parents_zarathushtri"/>
												<xsl:value-of select="labels/yes" />
												<input type="radio" class="inputbottom" name="parents_zarathushtri" value="N" id="parents_zarathushtri"/>
												<xsl:value-of select="labels/no" />
											</xsl:otherwise>
										</xsl:choose>
									</div>
								</div>
								</xsl:if>
								<div class="spacer1">&#160;</div>
								<div class="row">
									<div class="l1"></div>
									<div class="l2" style="float:right; text-align:right; padding-right:8px;">
										<input type="button" id="religion_ethnicity_back" value="{$btnBack}" class="button"/>&#160;
										<input type="button" id="religion_ethnicity_next" value="{$btnNext}" class="button"/>
									</div>
								</div>
							</div>
							<!-- End religion Details -->
								<!--Earlier Photo Upload section was there -->
							<!-- Start Photo Upload -->

							<!-- End Photo Upload -->

							<div class="spacer1">&#160;</div>
							<!-- Start more -->
							<div id="about_myself_section" style="display:none">
								<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/writeAboutYourSelf" /> :
									</div>
									<div id='translControl' style="position: relative;">
										<input type="checkbox" id="checkboxId2"></input>
										<select onchange="javascript:languageChangeHandler(languageDropDown2);" Id="languageDropDown2" title='languageDropDown2' target='about_yourself'>
											<option value="hi">हिन्दी</option>
											<option value="ta">தமிழ்</option>
											<option value="te">తెలుగు</option>
											<option value="kn">ಕನ್ನಡ</option>
											<option value="ml">മലയാളം</option>
										</select>
										<div id="aboutyourself_help" class="helpbox" style="display: block;left: 285px; top: -25px; float:left; _left:135px;">
											<div class="helptext">
												<xsl:value-of select="labels/aboutFamily"/>
												    <div style="position:absolute; top:0;right:3px">
													<a href="javascript:void(0);" id="close_help_yf" class="b blink">[x]</a>
												    </div>
												    <div class="helpimg" style="top:25px;"></div>
											</div>
										</div>
									</div>
									<div id="errorDiv"></div>
									<div class="row">
										<div class="l1"></div>
										<div class="l2">
												<div class="lf">
													<textarea name="about_yourself" row="50" cols="120" id="about_yourself" style="overflow:scroll;width:410px;height:148px;margin-top:12px;">
														<xsl:choose>
															<xsl:when test="$YOURINFO != ''">
																<xsl:value-of select="$YOURINFO" />
															</xsl:when>
															<xsl:otherwise>
																<xsl:value-of select="help/writeAboutYourSelf"/>
															</xsl:otherwise>
														</xsl:choose>
													</textarea>
												</div>
												<xsl:variable name="aboutYourSelfDefault" select="help/writeAboutYourSelf" />
												<input type="hidden" name="about_yourself_default" value="{$aboutYourSelfDefault}" />
												<div>
													<div class="spacer1">&#160;</div>
													<xsl:value-of select="labels/numberCharacters" /> :
													&#160;
													<div id="about_yourself_count" style="display:inline;color:#FF0000;width:25px">
													</div>
													<div style="margin: -14px 0pt 0pt 214px;">
														<xsl:value-of select="labels/newTip" />
													</div>
												</div>
												<div class="transTip">
													<div class="spacer1">&#160;</div>	
													<div class="lf green b">
														<xsl:value-of select="labels/trans/tipPart1"/>
													</div>
													<div class="lf mar_left_10">
														<xsl:value-of select="labels/trans/tipPart2"/>
														<br></br>
														<xsl:value-of select="labels/trans/tipPart3"/>
														<a href="{$MORE}" id="more1">
															<xsl:value-of select="labels/trans/tipPart4"/>
														</a>
													</div>
												</div>
												<div id="spellcheck2" style="display:inline;">
													<div class="spacer1">&#160;</div>
													<img src="{$IMG_URL}/spell-check.gif" onClick="spellcheckxx();return false;" style="cursor:pointer;"/>
												</div>
											</div></div>
										</div>
							<!-- End of About Yourself Section -->
							<div class="spacer1">&#160;</div>
							<!-- Start of About Desired Partner Section -->
							<div class="row">
									<div class="l1">
										<xsl:value-of select="labels/aboutDesiredPartner" /> :
									</div>
									<div id='translControl' style="position: relative;">
										<input type="checkbox" id="checkboxId3"></input>
										<select onchange="javascript:languageChangeHandler(languageDropDown3);" Id="languageDropDown3" title='languageDropDown3' target='about_desired_partner'>
											<option value="hi">हिन्दी</option>
											<option value="ta">தமிழ்</option>
											<option value="te">తెలుగు</option>
											<option value="kn">ಕನ್ನಡ</option>
											<option value="ml">മലയാളം</option>
										</select>
										<div id="aboutpartner_help" class="helpbox" style="display:block;left: 285px; top: -8px; _left:135px;">
											<div class="helptext">
												<xsl:value-of select="labels/aboutFamily"/>
												    <div style="position:absolute; top:0;right:3px">
													<a href="javascript:void(0);" id="close_help_pr" class="b blink">[x]</a>
												    </div>
												    <div class="helpimg" style="top:25px;"></div>
											</div>
										</div>
									</div>
									<div id="errorDiv"></div>
									<div class="row">
											<div class="l1"></div>
											<div class="l2">
												<div class="lf">
													<textarea rows="5" cols="12" name="about_desired_partner" id="about_desired_partner" onclick="javascript:colorchange()" style="overflow:scroll;width:410px;height:120px;margin-top:13px;"><xsl:value-of select="$SPOUSE" />
													</textarea>
												</div>
												<div class="transTip" >
													<div class="spacer1">&#160;</div>
													<div class="lf green b">
														<xsl:value-of select="labels/trans/tipPart1"/>
													</div>
													<div class="lf mar_left_10">
														<xsl:value-of select="labels/trans/tipPart2"/>
														<br></br>
														<xsl:value-of select="labels/trans/tipPart3"/>
														<a href="{$MORE}" id="more2">
															<xsl:value-of select="labels/trans/tipPart4"/>
														</a>
													</div>
												</div>
												<div id="spellcheck3" style="display:inline;">
													<div class="spacer1">&#160;</div>
													<div class="spacer1">&#160;</div>
													<img src="{$IMG_URL}/spell-check.gif" onClick="spellcheckxx();return false;" style="cursor:pointer;"/>
												</div>	
											</div>
										</div>
										<div class="spacer1">&#160;</div>
										<div class="row">
											<div class="r1"></div>
											<div class="l2" style="float:right; text-align:right; padding-right:8px;">
										<input type="button" id="about_myself_back" value="{$btnBack}" class="button"/>
									</div>
								</div>
							</div>
							<!-- End more -->
						</div>
					</div>
					</div>
					<div class="spacer" style="line-height:25px;">&#160;</div>
					<div class="row" style="display:none;" id="finish_registration_button">
						<div class="r1"></div>
						<div class="r2" style="text-align:right;" >
							<input type="hidden" name="submit_pg2_hidden" value="1"/>
							<input type="submit" style="cursor: pointer;" name="submit_pg2" id="submit_pg2" class="submitbg" value="{$btnFinish}" />
						</div>
					</div>
					<div class="spacer" style="line-height:25px;">&#160;</div>
				</div>
				<!-- Start Photo Rightnavbar -->
				<!-- End rightnavbar  -->
			</div>
			<!-- End content part -->
		</div>
		</form>

		<!-- Page 2 Tracking Starts from here -->

		<xsl:choose>
			<xsl:when test="$GROUPNAME = 'wchutney'">
				<script language="JavaScript" src="http://www.webchutney.net/chutneytrack/js/iqtracker.js"></script>
				<script language="JavaScript">
					var clntid = "JVNSTHI";
					trackThisPage();
				</script>
			</xsl:when>
			<xsl:when test="$GROUPNAME = 'mediaturf'">
				<!--Enhance Conversion Tracking (Lead) -->
				<img src="http://c.enhance.com/t?cid=1059840&amp;filltype=5 " width="1" height="1" border="0" />
			</xsl:when>
			<xsl:when test="($GROUPNAME = 'Tyroo_India_JFM08') or ($GROUPNAME = 'Tyroo_NRI_JFM08')">
				<script type="text/javascript" src="http://tq.tyroo.com:8080/acquire/tyr_aqui.js"></script>
			</xsl:when>
			<xsl:when test="$GROUPNAME = 'Integrid_CPA_08'">
				<!-- Advertiser '91  Naukri',  Include user in segment 'Jeevansathi' – DO NOT MODIFY THIS PIXEL IN ANY WAY -->
				<img src="http://ad.adserverplus.com/pixel?id=99680&amp;t=2" width="1" height="1" />
				<!-- End of segment tag -->
			</xsl:when>
			<xsl:when test="$GROUPNAME = 'Google NRI US'">
				<!-- Google Code for lead Conversion Page -->
				<script language="JavaScript" type="text/javascript">
					var google_conversion_id = 1046502896;
					var google_conversion_language = "en_US";
					var google_conversion_format = "1";
					var google_conversion_color = "666666";
					if (1)
					{
						var google_conversion_value = 1;
					}
					var google_conversion_label = "lead";
				</script>
				<script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js"></script>
				<noscript>
					<img height="1" width="1" border="0" src="https://www.googleadservices.com/pagead/conversion/1046502896/imp.gif?value=1&amp;label=lead&amp;script=0" />
				</noscript>
			</xsl:when>
		</xsl:choose>
		<xsl:if test="$SOURCE = 'DGM_amj_08_cpa'">
			<img height="1" width="3" src="http://www.s2d6.com/x/?x=s&amp;h=17773&amp;o=ORDERID&amp;s=0.00" alt="" />
		</xsl:if>

		<!-- Advertiser 'Naukri.com',  Include user in segment 'Jeevansathi registration' - DO NOT MODIFY THIS PIXEL IN ANY WAY -->
                        <img src="http://ads.komli.com/pixel?id=56601&amp;t=2" width="1" height="1"/>
                <!-- End of segment tag -->

		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
		<script type="text/javascript">
			_uacct = "UA-179986-1";
			urchinTracker();
		</script>

		<!-- Page 2 Tracking Ends Here , Rest Tracking's are available in the File : registartion_tracking.htm -->
	
		<div class="confirmation_layer_div" id="confirmation_layer">
		<div class="pink" style="width:530px;">
			<div class="layertopbg">
			<div class="fl pd"></div>
			<div class="fr pd b t12">
				<a class="blink" href="" id="close">Close [x]</a></div>
			</div>
			<div class="sp12"></div>
			<div class="lf" style="padding:6px; width:100%">
				<div class="lf">
					<img src="{$IMG_URL}/notification2.gif" hspace="16" vspace="0" align="left" />
				</div>
				<div class="lf b" style="padding-top:6px; width:90%">
					<!--<xsl:variable name="alert_val" select="alert/aboutYourSelf" />
					<input type="hidden" name="alert_about_yourself" value="{$alert_val}" />-->
					<xsl:value-of select="alert/aboutYourSelf/part1" />
					<!--MESSAGE-->
				</div>
				<div class="spacer1">&#160;</div>
				<div class="spacer1">&#160;</div>
				<div class="lf">
					<img src="{$IMG_URL}/warning_img.gif" hspace="10" vspace="0" align="left" />
				</div>
				<div class="lf b" style="padding-top:6px; width:88%">
					<xsl:value-of select="alert/aboutYourSelf/part2" />
				</div>
			</div>
			<div class="sp12"></div>
			<div style="margin:auto; text-align:center;">
				<input type="button" class="b green_btn" id="cancel" name="cancel" value="{$btnCancel}" style="width:165px;" />
				&#160;&#160;<a href="" id="continue" class="blink"><xsl:value-of select="buttonLabels/regContinue"/></a>
			</div>
			<div class="sp2"></div>
		</div>
		</div>
		</div>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/registration_pg2.js"></script>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>
