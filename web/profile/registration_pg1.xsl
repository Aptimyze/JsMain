<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:param name="SITE_URL" />
<xsl:param name="IMG_URL" />
<xsl:param name="LIVE_CHAT_URL" />
<xsl:param name="RELATIONSHIP" />
<xsl:param name="EMAIL" />
<xsl:param name="FNAME_USER" />
<xsl:param name="LNAME_USER" />
<xsl:param name="USERNAME" />
<xsl:param name="GENDER" />
<xsl:param name="DAY" />
<xsl:param name="MONTH" />
<xsl:param name="YEAR" />
<xsl:param name="CURRENT_DATE" />
<xsl:param name="MSTATUS" />
<xsl:param name="ANNULLED_DAY" />
<xsl:param name="ANNULLED_MONTH" />
<xsl:param name="ANNULLED_YEAR" />
<xsl:param name="HAS_CHILDREN" />
<xsl:param name="HEIGHT" />
<xsl:param name="COUNTRY_CODE" />
<xsl:param name="COUNTRY_RESIDENCE" />
<xsl:param name="STATE_CODE" />
<xsl:param name="CITY_RESIDENCE" />
<xsl:param name="CITIZENSHIP" />
<xsl:param name="PHONE" />
<xsl:param name="PHONE_NUMBER_OWNER" />
<xsl:param name="PHONE_OWNER_NAME" />
<xsl:param name="SHOWPHONE" />
<xsl:param name="MOBILE" />
<xsl:param name="MOBILE_NUMBER_OWNER" />
<xsl:param name="MOBILE_OWNER_NAME" />
<xsl:param name="SHOWMOBILE" />
<xsl:param name="TIME_TO_CALL_START" />
<xsl:param name="TIME_TO_CALL_END" />
<xsl:param name="START_AM_PM" />
<xsl:param name="END_AM_PM" />
<xsl:param name="OCCUPATION" />
<xsl:param name="RELIGION" />
<xsl:param name="CASTE" />
<xsl:param name="MATCH_ALERT" />
<xsl:param name="PROMO" />
<xsl:param name="SERVICE_MESSAGES" />
<xsl:param name="TIEUP_SOURCE" />
<xsl:param name="HITSOURCE" />
<xsl:param name="NEWIP" />
<xsl:param name="CHECKBOXALERT1" />
<xsl:param name="CHECKBOXALERT2" />
<xsl:param name="ADNETWORK" />
<xsl:param name="ACCOUNT" />
<xsl:param name="CAMPAIGN" />
<xsl:param name="ADGROUP" />
<xsl:param name="KEYWORD" />
<xsl:param name="MATCH" />
<xsl:param name="LMD" />
<xsl:param name="SHOWLOGIN" />
<xsl:param name="FROMMARRIAGEBUREAU" />
<xsl:param name="GROUPNAME" />
<xsl:param name="ID_AFF" />
<xsl:param name="PARTNER_MSTATUS_STR" />
<!--<xsl:param name="PARTNER_DEGREE_STR" />-->
<xsl:param name="PARTNER_INCOME_STR" />
<xsl:param name="PARTNER_MTONGUE_STR" />
<xsl:param name="PARTNER_RELIGION_STR" />
<xsl:param name="PARTNER_CASTE_STR" />
<xsl:param name="LINK_ALREADY_EXISTS" />
<xsl:template match="/registrationPage1">
<xsl:variable name="btnDoneSelection" select="buttonLabels/doneSelection" />
<xsl:variable name="btnSubmit" select="buttonLabels/submit" />
<xsl:variable name="btnSubmitSmall" select="buttonLabels/submitSmall"/>
<html>
	<head>
		<meta name="description" content="Register for Free in Jeevansathi.com. Create your matrimonial profile and find your dream life partner. Join Jeevansathi.com today, the leading India matrimonials website in India. Search matrimonials, matrimony profiles, NRI bride and groom from our online matrimonial and matchmaking services."/>
		<meta name="keywords" content="Jeevansathi.com, Indian matrimony, India, matrimony, matrimonial, matrimonials, matrimony services,online matrimonials, Indian marriage, match making, matchmaking, matchmaker, match maker, marriage bureau , matchmaking services, matrimonial profiles, bride, groom, matrimony classified."/>
		<title><xsl:value-of select="title" /></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="{$SITE_URL}/profile/css/registration_new.css" type="text/css" rel="stylesheet" />
		<link href="{$SITE_URL}/profile/css/common_new.css" type="text/css" rel="stylesheet" />
		<link href="{$SITE_URL}/profile/css/thickbox.css" type="text/css" rel="stylesheet" />

		<script type="text/javascript" src="{$SITE_URL}/profile/js/registration_ajax.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/behaviour.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/registration.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/gadget_as.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/registration_pg1.js"></script>
		<script type="text/javascript" src="{$SITE_URL}/profile/js/gadget.js"></script>
		
                <!--script type="text/javascript" src="{$SITE_URL}/profile/js/jquery_pt.js"></script-->
                <!--script type="text/javascript" src="{$SITE_URL}/profile/js/thickbox_pt.js"></script-->
		
		<script>
			<![CDATA[
			]]>
		</script>
		<style>
			.graylayer{filter: alpha(opacity=20);filter: progid:DXImageTransform.Microsoft.Alpha(opacity=20);-moz-opacity: .20;-khtml-opacity: .20;opacity: .20; margin:auto;}
			.suberr{display:none}
			.suberrRethrow{display:block}
			.suberrmsg{padding-left:170px; font:10px; color:#e3373b;}
			.partsuberrmsg{padding-left:215px; font:10px; color:#e3373b;}
			.annulledcover{position:relative;display:none;}
			.annulledlayer{border:1px solid #b4c96b; position:absolute;width:180px;z-index:100; left: 235px;top: -130px;background-color:#c7d790;}
			* html .annulledlayer{border:1px solid #b4c96b; position:absolute;width:180px;z-index:100; left: 290px;top: -130px;background-color:#c7d790;}
			*:first-child+html .annulledlayer{border:1px solid #b4c96b; position:absolute;width:180px;z-index:100; left: 290px;top: -130px;background-color:#c7d790;}
			.annulledbox{font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;width:180px; float:left;}
			#hintbox {border:1px solid #000000; position:absolute;top:8px;visibility:hidden;width:150px;z-index:100;}
			#new_ {font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;}
	
			div.row3 {float:left;margin:3px 0;width:97%;}
			div.row3 label {color:#726F6F;float:left;font-weight:bold;padding-right:6px;text-align:right;width:126px;}
			.red{color:#e40410;}
			div.row4{float:left;width:96%; margin:5px 0px}

		</style>
		<style>
			.partner_image_female{display:inline;}
			.partner_image_male{display:inline;}
		</style>
	</head>
	<body>
<script type="text/javascript">
var WRInitTime=(new Date()).getTime();
</script>
<img src="http://ser4.jeevansathi.com/profile/images/zero.gif" id="checkit" style="display:none"/>
		<noscript>
			<div style="position:fixed;z-index:1000">
				<div align="center" style="font-family:verdana,Arial;font-size:14px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;">
					<b>
						<img src="{$IMG_URL}/error.gif" width="23" height="20" />
						<xsl:value-of select="disabledJavascript/part1"/>&#160;
						<a href="{$SITE_URL}/P/js_help.htm" target="_blank">
							<xsl:value-of select="disabledJavascript/part2"/>
						</a>
					&#160;<xsl:value-of select="disabledJavascript/part3"/>
					</b>
				</div>
			</div>
		</noscript>
		<div id="container" style="height:200%">
			<form name="form1" method="post" action="{$FORM_ACTION}" style="margin: 0px">
			<input type="hidden" name="site_url" value="{$SITE_URL}" />
			<input type="hidden" name="img_url" value="{$IMG_URL}" />
			<input type="hidden" name="tieup_source" value="{$TIEUP_SOURCE}" />
			<input type="hidden" name="hit_source" value="{$HITSOURCE}" />
			<input type="hidden" name="newip" value="{$NEWIP}" />
			<input type="hidden" name="adnetwork" value="{$ADNETWORK}" />
			<input type="hidden" name="account" value="{$ACCOUNT}" />
			<input type="hidden" name="campaign" value="{$CAMPAIGN}" />
			<input type="hidden" name="adgroup" value="{$ADGROUP}" />
			<input type="hidden" name="keyword" value="{$KEYWORD}" />
			<input type="hidden" name="match" value="{$MATCH}" />
			<input type="hidden" name="lmd" value="{$LMD}" />
			<input type="hidden" name="showlogin" value="{$SHOWLOGIN}" />
			<input type="hidden" name="frommarriagebureau" value="{$FROMMARRIAGEBUREAU}" />
			<input type="hidden" name="groupname" value="{$GROUPNAME}" />
			<input type="hidden" name="id" value="{$ID_AFF}" />
			<input type="hidden" name="current_date" value="{$CURRENT_DATE}" />
			<xsl:variable name="doesntMatterForJavascript" select="dropdowns/doesntMatter" />
			<input type="hidden" name="doesnt_matter_for_javascript" value="{$doesntMatterForJavascript}" />
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

			<div class="ben_reg lf t11"><img src="{$IMG_URL}/h.jpg" class="lf" /><div class="weds lf mar_top_6"><img src="{$IMG_URL}/cou.jpg" class="lf" /><div class="lf mar_left_10" style="color:#fff; padding-left:2px;"><b>Amit weds Tabassum</b> <br /><br />We would like to thank Jeevansathi.com<br />through which we met and got married. <br />It is really a very useful web site for <br />life-partner search.</div></div><div class="lf" style="color:#ffe400; margin:10px;"><b>Benefits of Registration</b><br /><br />View Unlimited Profiles<br />Express Interest in members<br />Receive matches through Email<br />Get Contacted Directly</div></div>
			<div class="spacer" style="line-height:16px;">&#160;</div>
			<div class="fl">
				<div class="step1">
					<img src="{$IMG_URL}/org_arrow_down.jpg" align="top" />
					&#160;<span class="t23"><xsl:value-of select="stepsOfRegistration/step1/part1" /></span>
					&#160;<span class="t16"><xsl:value-of select= "stepsOfRegistration/step1/part2" /></span>
				</div>
				<div style="color:#bcbcbc;float:left;width:320px">
					&#160;<span class="t23"><xsl:value-of select="stepsOfRegistration/step2/part1" /></span>
					&#160;<span class="t16"><xsl:value-of select= "stepsOfRegistration/step2/part2" /></span>
				</div>
			</div>
			<div class="spacer">&#160;</div>
			<div style="width:750px; padding:10px 0px;">
			 <div class="y">
			  <div class="y_top">
			   <div class="y_bot">
			    <div class="y_lft">
			     <div class="y_rgt">
			      <div class="y_b_l">
			       <div class="y_b_r">
				<div class="y_t_l">
				 <div class="y_t_r">
				  <div style="padding: 5px 10px; height:27px;">
				   <div class="fl">
				    <span class="ortext t15">
				     <xsl:value-of select="welcomeText/line1" />
				    </span>
				    <span class="t13">
				     &#160;<xsl:value-of select="welcomeText/line2" />
				    </span>
				   </div>
				  </div>
				 </div>
				</div>
			       </div>
			      </div>
			     </div>
			    </div>
			   </div>
			  </div>
			 </div>
			</div>
			<!-- start registration -->
			<div class="spacer1">&#160;</div>
			<div class="spacer" style="float:left; padding:1px 2px;">
				<xsl:value-of select="messages/mandatory" />
			</div>
			<div class="spacer1">&#160;</div>
			<div class="spacer1">&#160;</div>
			<div class="gray_bg">
				<div class="fl"><img src="{$IMG_URL}/sr_top_left.gif" /></div>
				<div class="fl sr_top_bg b" style="width:740px;">
					<div style="padding:3px 0 0 5px;"><xsl:value-of select="labels/looking" />&#160;&#160;&#160;
						<span class="orange"><xsl:value-of select="labels/tip" /></span>
					</div>
				</div>
				<div class="fl"><img src="{$IMG_URL}/sr_top_right.gif" /></div>
				<div id="relationship" class="fl orange_border" style="width:732px; padding:8px">
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '1'">
							<input class="inputbottom" type="radio" value="1" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="1" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/self" />
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '4'">
							<input class="inputbottom" type="radio" value="4" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="4" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/friend" />
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '2'">
							<input class="inputbottom" type="radio" value="2" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="2" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/son" />
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '2D'">
							<input class="inputbottom" type="radio" value="2D" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="2D" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/daughter" />
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '6'">
							<input class="inputbottom" type="radio" value="6" checked = "yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="6" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/brother" />
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '6D'">
							<input class="inputbottom" type="radio" value="6D" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="6D" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/sister" />
					<!--
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '3'">
							<input class="inputbottom" type="radio" value="3" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="3" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/father" />
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '3D'">
							<input class="inputbottom" type="radio" value="3D" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="3D" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/mother" />
					-->
					<xsl:choose>
						<xsl:when test="$RELATIONSHIP = '5'">
							<input class="inputbottom" type="radio" value="5" checked="yes" name="relationship" />
						</xsl:when>
						<xsl:otherwise>
							<input class="inputbottom" type="radio" value="5" name="relationship" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/marriageBureau" />
				</div>
				<div class="clear"></div>
				<div class="fl"><img src="{$IMG_URL}/sr_bottom_left.gif" /></div>
				<div class="fl sr_bottom_bg" style="width:740px;"><img src="{$IMG_URL}/spacer.gif" height="1" /></div>
				<div class="fl"><img src="{$IMG_URL}/sr_bottom_right.gif" /></div>
			</div>
			<div class="spacer">&#160;</div>
			<div id="rest_of_page" style="position: relative">
				<div id="gray_layer" style="margin: auto; background: rgb(0, 0, 0) none repeat scroll 0%; position: absolute; left: 0pt; top: 0pt; bottom: 0pt; z-index: 100; display: none; width: 98%; height: 100%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" class="graylayer"></div>
				<div class="nothighlight" id="email_section">
					<div class="fl"><img src="{$IMG_URL}/sr_top_left.gif" /></div>
					<div class="fl sr_top_bg b" style="width:740px;">
						<div style="padding:3px 0 0 5px;"><xsl:value-of select="labels/loginDetails" /></div>
					</div>
					<div class="fl"><img src="{$IMG_URL}/sr_top_right.gif" /></div>
					<div class="fl orange_border" style="width:732px; padding:8px">
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/email" /> :
						</div>
						<div class="r2">
							<input class="textbox" type="text" style="width: 204px; white-space:pre; word-spacing:1px;" value="{$EMAIL}" name="email" id="email" maxlength="255" autocomplete="off" />
							<input type="hidden" name="email_is_ok" id="email_is_ok" value="" />
							<div class="coverhelp">
								<div id = "email_message_ok" style="display:none; width:100px">
									<!--This div is filled using javascript-->
								</div>
								<div class="helpbox" id="email_help">
									<div class="helptext">
										<xsl:value-of select="help/email"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<div id = "email_message_er" class="suberr">
						<!--This div is filled using javascript-->
					</div>
					<xsl:variable name="emailRethrowCheck" select="rethrowPage/email"/>
					<xsl:choose>
						<xsl:when test="$emailRethrowCheck = '1'">
							<div id="email_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/email/invalid" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$emailRethrowCheck = '2'">
							<div id="email_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/email/alreadyExists" /><xsl:value-of select="$LINK_ALREADY_EXISTS" disable-output-escaping="yes"/>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$emailRethrowCheck = '3'">
							<div id="email_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/email/jeevansathiInvalid" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$emailRethrowCheck = '4'">
							<div id="email_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/email/blockedDomainName" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="email_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/email/blank" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/password" /> :
						</div>
						<div class="r2">
							<input class="textbox" type="password" style="width: 204px;" value="" name="password" id="password" maxlength="16"/>
							<div class="coverhelp">
								<div class="helpbox passwordExtra" id="password_help">
									<div class="helptext">
										<xsl:value-of select="help/password"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<xsl:variable name="passwordRethrowCheck" select="rethrowPage/password" />
					<xsl:choose>
						<xsl:when test="$passwordRethrowCheck = '1'">
							<div id="password_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="password_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/password/error1" />
									</div>
									<div id="password_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error2" />
									</div>
									<div id="password_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$passwordRethrowCheck = '2'">
							<div id="password_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="password_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error1" />
									</div>
									<div id="password_error2" style="display:inline">
										<xsl:value-of select="submitErrorMessages/password/error2" />
									</div>
									<div id="password_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$passwordRethrowCheck = '3'">
							<div id="password_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="password_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error1" />
									</div>
									<div id="password_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error2" />
									</div>
									<div id="password_error3" style="display:inline">
										<xsl:value-of select="submitErrorMessages/password/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="password_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="password_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/password/error1" />
									</div>
									<div id="password_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error2" />
									</div>
									<div id="password_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/password/error3" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/confirmPassword" /> :
						</div>
						<div class="r2">
							<input class="textbox" type="password" style="width: 204px;" value="" name="confirm_password" id="confirm_password" maxlength="16"/>
							<div class="coverhelp">
								<div class="helpbox passwordExtra" id="confirm_password_help">
									<div class="helptext">
										<xsl:value-of select="help/confirmPassword"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<xsl:variable name="confirmPasswordRethrowCheck" select="rethrowPage/confirmPassword" />
					<xsl:choose>
						<xsl:when test="$confirmPasswordRethrowCheck = '1'">
							<div id="confirm_password_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="confirm_password_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error1" />
									</div>
									<div id="confirm_password_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error2" />
									</div>
									<div id="confirm_password_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$confirmPasswordRethrowCheck = '2'">
							<div id="confirm_password_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="confirm_password_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error1" />
									</div>
									<div id="confirm_password_error2" style="display:inline">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error2" />
									</div>
									<div id="confirm_password_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$confirmPasswordRethrowCheck = '3'">
							<div id="confirm_password_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="confirm_password_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error1" />
									</div>
									<div id="confirm_password_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error2" />
									</div>
									<div id="confirm_password_error3" style="display:inline">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="confirm_password_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="confirm_password_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error1" />
									</div>
									<div id="confirm_password_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error2" />
									</div>
									<div id="confirm_password_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/confirmPassword/error3" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					</div>
					<div class="clear"></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_left.gif" /></div>
					<div class="fl sr_bottom_bg" style="width:740px;"><img src="{$IMG_URL}/spacer.gif" height="1" /></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_right.gif" /></div>
				</div>
				<div class="spacer">&#160;</div>
				<div class="nothighlight" id="basicInfo_section">
					<div class="fl"><img src="{$IMG_URL}/sr_top_left.gif" /></div>
					<div class="fl sr_top_bg b" style="width:740px;">
						<div style="padding:3px 0 0 5px;">
							<div id="self_basicInfo" style="display:block">
								<xsl:value-of select="labels/basicInfo/self" />
							</div>
							<div id="friend_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/friend" />
							</div>
							<div id="son_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/son" />
							</div>
							<div id="daughter_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/daughter" />
							</div>
							<div id="brother_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/brother" />
							</div>
							<div id="sister_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/sister" />
							</div>
							<div id="father_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/father" />
							</div>
							<div id="mother_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/mother" />
							</div>
							<div id="marriageBureau_basicInfo" style="display:none">
								<xsl:value-of select="labels/basicInfo/marriageBureau" />
							</div>
						</div>
					</div>
					<div class="fl"><img src="{$IMG_URL}/sr_top_right.gif" /></div>
					<div class="fl orange_border" style="width:732px; padding:8px">
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/fullName" /> :
						</div>
						<div class="r2">
							<input class="textbox" type="textbox" style="width: 97px;" value="{$FNAME_USER}" name="fname_user" id="fname_user" maxlength="127"/>
							&#160;<input class="textbox" type="textbox" style="width: 98px;" value="{$LNAME_USER}" name="lname_user" id="lname_user" maxlength="127"/> 
							<div class="coverhelp">
								<div class="helpbox" id="fname_user_help">
									<div class="helptext">
										<xsl:value-of select="help/fullName"/>
										<div class="helpimg"></div>
									</div>  
								</div>
								<div class="helpbox" id="lname_user_help">
									<div class="helptext">
										<xsl:value-of select="help/fullName"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<xsl:variable name="fullNameRethrowCheck" select="rethrowPage/fullName" />
					<xsl:choose>
						<xsl:when test="$fullNameRethrowCheck = '1'">
							<div id="fname_lname_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="fname_error1" style="dispaly:inline">
										<xsl:value-of select="submitErrorMessages/fullName/error1" />
									</div>
									<div id="fname_error2" style="dispaly:none">
										<xsl:value-of select="submitErrorMessages/fullName/error2" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$fullNameRethrowCheck = '2'">
							<div id="fname_lname_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="fname_error1" style="dispaly:none">
										<xsl:value-of select="submitErrorMessages/fullName/error1" />
									</div>
									<div id="fname_error2" style="dispaly:inline">
										<xsl:value-of select="submitErrorMessages/fullName/error2" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="fname_lname_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="fname_error1" style="dispaly:inline">
										<xsl:value-of select="submitErrorMessages/fullName/error1" />
									</div>
									<div id="fname_error2" style="dispaly:none">
										<xsl:value-of select="submitErrorMessages/fullName/error2" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/displayName" /> :
						</div>
						<div class="r2">
							<input class="textbox" type="textbox" style="width: 204px;" value="{$USERNAME}" name="username" id="username" maxlength="16" autocomplete="off"/>
							<input type="hidden" name="username_is_ok" id="username_is_ok" value="" />
							<div class="coverhelp">
								<div id = "username_message_ok" style="display:none;width:100px">
									<!--This div is filled using javascript -->
								</div>
								<div class="helpbox" id="username_help">
									<div class="helptext">
										<xsl:value-of disable-output-escaping="yes" select="help/displayName"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<div id = "username_message_er" class="suberr">
						<!--This div is filled using javascript -->
					</div>
					<xsl:variable name="usernameRethrowCheck" select="rethrowPage/username" />
					<xsl:choose>
						<xsl:when test="$usernameRethrowCheck = '1'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/characterStarting" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '2'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/minimumCharacters" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '3'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/obscene" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '4'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/continuousNumerics" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '5'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/domainNameUsage" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '6'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/specialCharacters" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '7'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/alreadyExists" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$usernameRethrowCheck = '8'">
							<div id="username_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/sameEmail/sameAsEmail" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="username_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/username/blank" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="gender_section">
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1">
								<xsl:value-of select="labels/gender" /> :
							</div>
							<div class="r2">
								<xsl:choose>
									<xsl:when test="$GENDER = 'M'">
										<input class="inputbottom" type="radio" value="M" checked="yes" name="gender"/>
										<xsl:value-of select="labels/male" />
										<input class="inputbottom" type="radio" value="F" name="gender"/>
										<xsl:value-of select="labels/female" />
									</xsl:when>
									<xsl:when test="$GENDER = 'F'">
										<input class="inputbottom" type="radio" value="M" name="gender"/>
										<xsl:value-of select="labels/male" />
										<input class="inputbottom" type="radio" value="F" checked="yes" name="gender"/>
										<xsl:value-of select="labels/female" />
									</xsl:when>
									<xsl:otherwise>
										<input class="inputbottom" type="radio" value="M" name="gender"/>
										<xsl:value-of select="labels/male" />
										<input class="inputbottom" type="radio" value="F" name="gender"/>
										<xsl:value-of select="labels/female" />
									</xsl:otherwise>
								</xsl:choose>
							</div>
						</div>
						<xsl:variable name="genderRethrowCheck" select="rethrowPage/gender" />
						<xsl:choose>
							<xsl:when test="$genderRethrowCheck = '1'">
								<div id="gender_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/gender" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="gender_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/gender" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/dtOfBirth" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="day" id="day">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/day" />
								</option>
								<xsl:for-each select="populate/days">
									<xsl:variable name="day_var" select="." />
									<xsl:choose>
										<xsl:when test="$day_var = $DAY">
											<option value="{$day_var}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$day_var}">
												<xsl:value-of select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select>&#160;
							<select class="textbox" size="1" name="month" id="month">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/month" />
								</option>
								<xsl:for-each select="populate/months">
									<xsl:variable name="month_var" select="@value" />
									<xsl:choose>
										<xsl:when test="$month_var = $MONTH">
											<option value="{$month_var}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$month_var}">
												<xsl:value-of select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select>&#160;
							<span id="year_span_id">
							<select class="textbox" size="1" name="year" id="year" style="width:60px">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/year" />
								</option>
								<xsl:for-each select="populate/years">
									<xsl:variable name="year_var" select="." />
									<xsl:choose>
										<xsl:when test="$year_var = $YEAR">
											<option value="{$year_var}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$year_var}">
												<xsl:value-of select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select>
							</span>
							<div class="coverhelp">
								<div class="helpbox dobextra" id="day_help">

									<div class="helptext">
										<xsl:value-of select="help/dtOfBirth"/>
										<div class="helpimg"></div>
									</div>  
								</div>
								<div class="helpbox dobextra" id="month_help">
									<div class="helptext">
										<xsl:value-of select="help/dtOfBirth"/>
										<div class="helpimg"></div>
									</div>  
								</div>
								<div class="helpbox dobextra" id="year_help">
									<div class="helptext">
										<xsl:value-of select="help/dtOfBirth"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
						<xsl:variable name="dtOfBirthRethrowCheck" select="rethrowPage/dtOfBirth" />
						<xsl:choose>
							<xsl:when test="$dtOfBirthRethrowCheck = '1'">
								<div id="day_month_year_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="dob_error1" style="display:inline">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error1" />
										</div>
										<div id="dob_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error2" />
										</div>
										<div id="dob_error3" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error3" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:when test="$dtOfBirthRethrowCheck = '2'">
								<div id="day_month_year_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="dob_error1" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error1" />
										</div>
										<div id="dob_error_2" style="display:inline">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error2" />
										</div>
										<div id="dob_error3" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error3" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:when test="$dtOfBirthRethrowCheck = '3'">
								<div id="day_month_year_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="dob_error1" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error1" />
										</div>
										<div id="dob_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error2" />
										</div>
										<div id="dob_error3">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error3" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="day_month_year_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="dob_error1" style="display:inline">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error1" />
										</div>
										<div id="dob_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error2" />
										</div>
										<div id="dob_error3" style="display:none">
											<xsl:value-of select="submitErrorMessages/dtOfBirth/error3" />
										</div>
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div id="partner_age" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="r1"></div>
						<div class="r2">
						<div class="fl">
						<div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
							<div class="partner_image_female">
								<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
							</div>
							<div class="partner_image_male">
								<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
							</div>
							<div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
						</div>
						<div class="fl lgreen_border" style="width:420px">
							<div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
								<div style="padding:2px 0 0 5px;">
									<b><xsl:value-of select="labels/partnerAge" /></b>
								</div>
							</div>
							<div style="padding:5px;width:98%;background-color: #f2f8d4;">
								<span id="lage_span_id">
								<select class="textbox" size="1" name="lage" id="lage">
									<option value=""><xsl:value-of select="tagLabels/min" /></option>
								</select>
								</span>&#160;
								<xsl:value-of select="labels/to" />&#160;
								<span id="hage_span_id">
								<select class="textbox" size="1" name="hage" id="hage">
									<option value=""><xsl:value-of select="tagLabels/max" /></option>
								</select>
								</span>&#160;
								<xsl:value-of select="labels/yrs" />
							</div>
						</div>
						</div></div>
						<xsl:variable name="partnerAgeRethrowCheck" select="rethrowPage/partnerAge" />
						<xsl:choose>
							<xsl:when test="$partnerAgeRethrowCheck = '1'">
								<div id="partner_age_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="partner_age_error1" style="display:inline">
											<xsl:value-of select="submitErrorMessages/partnerAge/error1" />
										</div>
										<div id="partner_age_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/partnerAge/error2" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:when test="$partnerAgeRethrowCheck = '2'">
								<div id="partner_age_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="partner_age_error1" style="display:none">
											<xsl:value-of select="submitErrorMessages/partnerAge/error1" />
										</div>
										<div id="partner_age_error2" style="display:inline">
											<xsl:value-of select="submitErrorMessages/partnerAge/error2" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_age_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="partner_age_error1" style="display:inline">
											<xsl:value-of select="submitErrorMessages/partnerAge/error1" />
										</div>
										<div id="partner_age_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/partnerAge/error2" />
										</div>
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/maritalStatus" /> :
						</div>
						<div class="r2">
							<div id ="mstatus_section">
								<div>
									<xsl:for-each select="populate/maritalStatus">
										<xsl:variable name="mstatus_var" select="@value" />
										<xsl:choose>
											<xsl:when test="$MSTATUS = $mstatus_var">
												<input class="inputbottom" type="radio" value="{$mstatus_var}" name="mstatus" checked="yes"/>
											</xsl:when>
											<xsl:otherwise>
												<input class="inputbottom" type="radio" value="{$mstatus_var}" name="mstatus"/>
											</xsl:otherwise>
										</xsl:choose>
										<xsl:value-of select="." />&#160;
									</xsl:for-each>

									<!-- Seperated Married due to check in female cases -->
									<div id="mstatus_married_field" style="display:inline">
										<xsl:for-each select="newpopulate/maritalStatus">
											<xsl:variable name="mstatus_var" select="@value" />
											<xsl:choose>
												<xsl:when test="$MSTATUS = $mstatus_var">
													<input class="inputbottom" type="radio" value="{$mstatus_var}" name="mstatus" checked="yes"/>
												</xsl:when>
												<xsl:otherwise>
													<input class="inputbottom" type="radio" value="{$mstatus_var}" name="mstatus"/>
												</xsl:otherwise>
											</xsl:choose>
											<xsl:value-of select="." />&#160;
										</xsl:for-each>
									</div>
								</div>

								<div id="married_down_arrow" class="fl" style="padding-left:485px;display:none;">
									<img src="{$IMG_URL}/icon_down_errow.gif" />
								</div>
								<div id="awaiting_divorce_down_arrow" class="fl" style="padding-left:128px;display:none;">
									<img src="{$IMG_URL}/icon_down_errow.gif" />
								</div>
								<div id="divorced_down_arrow" class="fr" style="padding-right:255px;display:none;">
									<img src="{$IMG_URL}/icon_down_errow.gif" />
								</div>
								<div id="annulled_down_arrow" class="fr" style="padding-right:141px;display:none;">
									<img src="{$IMG_URL}/icon_down_errow.gif" />
								</div>
							</div>
						</div>
					</div>
					<xsl:variable name="mstatusRethrowCheck" select="rethrowPage/mstatus" />
					<xsl:choose>
						<xsl:when test="$mstatusRethrowCheck = '1'">
							<div id="mstatus_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<div id="mstatus_error1" style="display:inline">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/mstatus/error1" />
									</div>
									<div id="mstatus_error2" style="display:none">
										<div class="graybox" style="width:500px;font-weight:bold;color:#000000;">
											<xsl:value-of select="submitErrorMessages/mstatus/error2" />
										</div>
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$mstatusRethrowCheck = '2'">
							<div id="mstatus_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg"> 
									<div id="mstatus_error1" style="display:none">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/mstatus/error1" />
									</div>
									<div id="mstatus_error2" style="display:inline">
										<div class="graybox" style="width:500px;font-weight:bold;color:#000000;">
											<xsl:value-of select="submitErrorMessages/mstatus/error2" />
										</div>
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="mstatus_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<div id="mstatus_error1" style="display:inline">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/mstatus/error1" />
									</div>
									<div id="mstatus_error2" style="display:none;">
										<div class="graybox lf" style="width:473px;margin-left:2px;font-weight:bold;color:#000000;">
											<div class="lf">
												<xsl:value-of select="submitErrorMessages/mstatus/error2" />
											</div>
											<div class="b blink rf" id="edit_married" style="display:none;cursor: pointer;" onclick="edit_mstatus()">
											     <xsl:value-of select="labels/mstatusDetails/heading/editMarried" />
											</div>

										</div>
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="mstatusDetailsRethrowCheck" select="rethrowPage/mstatusDetails" />
					<xsl:choose>
						<xsl:when test="$mstatusDetailsRethrowCheck = '1'">
							<div id="annulled_reason_actual_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/annulled" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="annulled_reason_actual_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/annulled" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
<div class="spacer"></div>
<div id="mstatus_details_layer" style="display:none;">
	<div id="edit_mstatus_details" style="display:none">
		<div class="row">
			<div class="r1"></div>
			<div class="r2">
				<div class="graybox" style="width:500px;font-weight:bold;color:#000000;" id="mstatus_details">
				</div>
				<xsl:variable name="annulledBy" select="labels/mstatusDetails/annulledBy" />
				<input type="hidden" name="annulled_by" value="{$annulledBy}" />
				<xsl:variable name="divorcedBy" select="labels/mstatusDetails/divorcedBy" />
				<input type="hidden" name="divorced_by" value="{$divorcedBy}" />
				<xsl:variable name="on" select="labels/mstatusDetails/on" />
				<input type="hidden" name="on" value="{$on}" />
				<xsl:variable name="edit" select="labels/mstatusDetails/edit" />
				<input type="hidden" name="edit" value="{$edit}" />
			</div>
		</div>
	</div>
	<div class="spacer"></div>
	<div id="edit_await_divorce_details" style="display:none">
		<div class="row">
			<div class="r1"></div>
			<div class="r2 graybox" style="width:479px;font-weight:bold;color:#000000;display:none" id="edit_ad">
					<div class="lf">
						<xsl:value-of select="labels/mstatusDetails/awaitDivorce" />
					</div>
					<div class="b blink rf" id="edit_married" style="cursor: pointer;" onclick="edit_mstatus()">
					     <xsl:value-of select="labels/mstatusDetails/heading/editAwaitingDivorce" />
					</div>
			</div>
		</div>
	</div>
	<div id="fill_mstatus_details" style="display:none">
		<div class="row">
			<div class="r1"></div>
			<div class="r2">
				<div style="width:473px;" class="graybox" id="mstatus_details_box">
					<div id="mstatus_details_error_img" style="display:none;">
						<img src="{$IMG_URL}/alert.gif" hspace="3" align="absbottom" />&#160;
					</div>
					<span id="married_heading_div" style="font-weight:bold;dipslay:none;">
						<xsl:value-of select="labels/mstatusDetails/heading/married" />
					</span>
					<span id="awaiting_divorce_heading_div" style="font-weight:bold;dipslay:none;">
						<xsl:value-of select="labels/mstatusDetails/heading/awaitingDivorce" />
					</span>
					<span id="divorced_heading_div" style="font-weight:bold;dipslay:none;">
						<xsl:value-of select="labels/mstatusDetails/heading/divorced" />
					</span>
					<span id="annulled_heading_div" style="font-weight:bold;dipslay:none;">
						<xsl:value-of select="labels/mstatusDetails/heading/annulled" />
					</span>
					<div style="margin:8px 2px 5px 2px; padding:5px; background-color:#FFFFFF;">
						<div id="court_div" style="display:none;">
							<div class="row1">
								<label style="width:220px">
									<span style="color:#ff0000;">*</span>
									<span id="annulled_court_div" style="display:none;">
										&#160;<xsl:value-of select="labels/mstatusDetails/court/annulled" />
									</span>
									<span id="divorced_court_div" style="display:none;">
										&#160;<xsl:value-of select="labels/mstatusDetails/court/divorced" />
									</span>
								</label>:&#160;
								<input type="text" name="court" id="court" style="width:100px; height:14px;" />
							</div>
						</div>
						<div id="mstatus_date_div" style="display:none;">
							<div class="row1">
								<label style="width:220px">
									<span style="color:#ff0000;">*</span>
									<span id="annulled_date_div" style="display:none">
										&#160;<xsl:value-of select="labels/mstatusDetails/date/annulled" />
									</span>
									<span id="divorced_date_div" style="display:none">
										&#160;<xsl:value-of select="labels/mstatusDetails/date/divorced" />
									</span>
								</label>:&#160;
								<select class="textbox" size="1" name="mstatus_day">
									<option value="" selected="">
										<xsl:value-of select="tagLabels/day" />
									</option>
									<xsl:for-each select="populate/days">
										<xsl:variable name="day_var" select="." />
										<xsl:choose>
											<xsl:when test="$day_var = $ANNULLED_DAY">
												<option value="{$day_var}" selected="yes">
													<xsl:value-of select="." />
												</option>
											</xsl:when>
											<xsl:otherwise>
												<option value="{$day_var}">
													<xsl:value-of select="." />
												</option>
											</xsl:otherwise>
										</xsl:choose>
									</xsl:for-each>
								</select>&#160;
								<select class="textbox" size="1" name="mstatus_month">
									<option value="" selected="">
										<xsl:value-of select="tagLabels/month" />
									</option>
									<xsl:for-each select="populate/months">
										<xsl:variable name="month_var" select="@value" />
										<xsl:choose>
											<xsl:when test="$month_var = $ANNULLED_MONTH">
												<option value="{$month_var}" selected="yes">
													<xsl:value-of select="." />
												</option>
											</xsl:when>
											<xsl:otherwise>
												<option value="{$month_var}">
													<xsl:value-of select="." />
												</option>
											</xsl:otherwise>
										</xsl:choose>
									</xsl:for-each>
								</select>&#160;
								<span id="mstatus_year_span_id">
								<select class="textbox" size="1" name="mstatus_year">
									<option value="" selected="">
										<xsl:value-of select="tagLabels/year" />
									</option>
									<xsl:for-each select="populate/annulledYears">
										<xsl:variable name="year_var" select="." />
										<xsl:choose>
											<xsl:when test="$year_var = $ANNULLED_YEAR">
												<option value="{$year_var}" selected="yes">
													<xsl:value-of select="." />
												</option>
											</xsl:when>
											<xsl:otherwise>
												<option value="{$year_var}">
													<xsl:value-of select="." />
												</option>
											</xsl:otherwise>
										</xsl:choose>
									</xsl:for-each>
								</select>
								</span>
							</div>
						</div>
						<div class="row1">
							<label style="width:156px">
								<span id="married_reason_div" style="display:none;">
									<span style="color:#ff0000;">*</span>
									&#160;<xsl:value-of select="labels/mstatusDetails/reason/married" />
								</span>
								<span id="awaiting_divorce_reason_div" style="display:none;">
									<span style="color:#ff0000;">*</span>
									&#160;<xsl:value-of select="labels/mstatusDetails/reason/awaitingDivorce" />
								</span>
								<span id="divorced_reason_div" style="display:none;">
									<xsl:value-of select="labels/mstatusDetails/reason/divorced" />
								</span>
								<span id="annulled_reason_div" style="display:none;">
									<xsl:value-of select="labels/mstatusDetails/reason/annulled" />
								</span>
							</label>
							<xsl:variable name="marriedDefault" select="labels/mstatusDetails/reasonDefault/married" />
							<input type="hidden" name="married_default" value="{$marriedDefault}" />
							<xsl:variable name="awaitingDivorceDefault" select="labels/mstatusDetails/reasonDefault/awaitingDivorce" />
							<input type="hidden" name="awaiting_divorce_default" value="{$awaitingDivorceDefault}" />
							<xsl:variable name="divorcedDefault" select="labels/mstatusDetails/reasonDefault/divorced" />
							<input type="hidden" name="divorced_default" value="{$divorcedDefault}" />
							<xsl:variable name="annulledDefault" select="labels/mstatusDetails/reasonDefault/annulled" />
							<input type="hidden" name="annulled_default" value="{$annulledDefault}" />
							<div style="vertical-align:top; float:left;">:&#160;
								<textarea rows="5" cols="10" name="mstatus_reason" id="mstatus_reason" style="width:230px; height:40px;vertical-align:top"></textarea>
							</div>
						</div>
						<div class="sp2"></div>
					</div>
					<div class="fl" style="padding-left:180px;">
						<input type="button" class="gray_btn" name="mstatus_details_submit" id="mstatus_details_submit" value="{$btnSubmitSmall}" />
					</div>
					<div class="fr">
						<span style="color:#ff0000; font-weight:bold">
							*&#160;<xsl:value-of select="labels/mstatusDetails/mandatory" />
						</span>
					</div>
					<div class="sp2"></div>
				</div>
				<div class="spacer"></div>
			</div>
		</div>
	</div>
</div>
					<div id="partner_mstatus" style="display:none">
						<div class="spacer1">&#160;</div>
							<div class="r1"></div>
							<div class="r2">
							<div class="fl">
							<div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
								<div class="partner_image_female">
									<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
								</div>
								<div class="partner_image_male">
									<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
								</div>
								<div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
							</div>
							<div class="fl lgreen_border" style="width:420px;">
							 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
							  <div style="padding:2px 0 0 5px;">
							   <xsl:value-of select="labels/partnerMstatus" />
							  </div>
							 </div>
							 <div style="padding:5px;width:98%;background-color: #f2f8d4;">
							  <div class="fl" style="width:48%">
							  <div style="padding: 0pt 5px 0pt 2px;">
							   <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
							   <div class="fr"><a href="" id="partner_mstatus_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
							  </div>
							    <div class="fl scrollbox" style="background-color: #ffffff">
							     <div style="display:none" id="partner_mstatus_div">
							      <input type="hidden" name="partner_mstatus_str" id="partner_mstatus_str" value="{$PARTNER_MSTATUS_STR}" />
							      <input type="checkbox" value="DM" name="partner_mstatus_arr[]" id="partner_mstatus_DM" checked="yes"/>
							      <label id="partner_mstatus_label_DM">
							       <xsl:value-of select="dropdowns/doesntMatter" />
							      </label>
							      <br />
							      <xsl:for-each select="populate/maritalStatus">
							       <xsl:variable name="mstatus_var" select="@value" />
							       <input type="checkbox" name="partner_mstatus_arr[]" id="partner_mstatus_{$mstatus_var}" value="{$mstatus_var}"/>
							       <label id="partner_mstatus_label_{$mstatus_var}">
								<xsl:value-of select="." />
							       </label>
							       <br />
							      </xsl:for-each>
							     </div>
							     <div style="overflow:hidden;" id="partner_mstatus_source_div">
							     <!-- <input type="checkbox" class="chbx checkboxalign" name="partner_mstatus_displaying_arr[]" id="partner_mstatus_displaying_DM" value="DM"/>
							      <label id="partner_mstatus_displaying_label_DM">
							       <xsl:value-of select="dropdowns/doesntMatter" />
							      </label>
							      <br />-->
							      <xsl:for-each select="populate/maritalStatus">
							      <xsl:variable name="mstatus_var" select="@value" />
							       <input type="checkbox" class="chbx checkboxalign" name="partner_mstatus_displaying_arr[]" id="partner_mstatus_displaying_{$mstatus_var}" value="{$mstatus_var}"/>
							       <label id="partner_mstatus_displaying_label_{$mstatus_var}">
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
							     <div class="fr"><a href="" id="partner_mstatus_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
							    </div>
							    <div class="fl scrollbox" style="background-color: #ffffff">
							     <div style="overflow:hidden;" id="partner_mstatus_target_div">
							      <div id="partner_mstatus_link_DM">
							       <label>
							        <xsl:value-of select="dropdowns/doesntMatter" />
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
						<xsl:variable name="partnerMstatusRethrowCheck" select="rethrowPage/partnerMstatus" />
						<xsl:choose>
							<xsl:when test="$partnerMstatusRethrowCheck = '1'">
								<div id="partner_mstatus_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerMstatus" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_mstatus_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerMstatus" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div id="have_child_section" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1">
								<xsl:value-of select="labels/haveChildren" /> :
							</div>
							<div class="r2">
								<xsl:choose>
									<xsl:when test="$HAS_CHILDREN = 'YT'">
										<input class="inputbottom" type="radio" value="N" name="has_children"/>
										<xsl:value-of select="labels/no" />&#160;
										<input class="inputbottom" type="radio" value="YT" checked="yes" name="has_children"/>
										<xsl:value-of select="labels/yes" />,&#160;<xsl:value-of select="labels/livingTogether" />&#160;
										<input class="inputbottom" type="radio" value="YS" name="has_children"/>
										<xsl:value-of select="labels/yes" />,&#160;<xsl:value-of select="labels/livingSeparately" />&#160;
									</xsl:when>
									<xsl:when test="$HAS_CHILDREN = 'YS'">
										<input class="inputbottom" type="radio" value="N" name="has_children"/>
										<xsl:value-of select="labels/no" />&#160;
										<input class="inputbottom" type="radio" value="YT" name="has_children"/>
										<xsl:value-of select="labels/yes" />,&#160;<xsl:value-of select="labels/livingTogether" />&#160;
										<input class="inputbottom" type="radio" value="YS" checked="yes" name="has_children"/>
										<xsl:value-of select="labels/yes" />,&#160;<xsl:value-of select="labels/livingSeparately" />&#160;
									</xsl:when>
									<xsl:otherwise>
										<input class="inputbottom" type="radio" value="N" name="has_children"/>
										<xsl:value-of select="labels/no" />&#160;
										<input class="inputbottom" type="radio" value="YT" name="has_children"/>
										<xsl:value-of select="labels/yes" />,&#160;<xsl:value-of select="labels/livingTogether" />&#160;
										<input class="inputbottom" type="radio" value="YS" name="has_children"/>
										<xsl:value-of select="labels/yes" />,&#160;<xsl:value-of select="labels/livingSeparately" />&#160;
									</xsl:otherwise>
								</xsl:choose>
							</div>
						</div>
						<xsl:variable name="hasChildrenRethrowCheck" select="rethrowPage/hasChildren" />
						<xsl:choose>
							<xsl:when test="$hasChildrenRethrowCheck = '1'">
								<div id="has_children_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/haveChildren" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="has_children_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/haveChildren" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/height" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="height" style="width:204px;" id="height">
								<option value="">
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/height">
									<xsl:variable name="height_var" select="@value" />
									<xsl:choose>
										<xsl:when test="$HEIGHT = $height_var">
											<option value="{$height_var}" selected="yes">
												<xsl:value-of disable-output-escaping="yes" select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$height_var}">
												<xsl:value-of disable-output-escaping="yes" select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select> 
						</div>
					</div>
					<xsl:variable name="heightRethrowCheck" select="rethrowPage/height" />
					<xsl:choose>
						<xsl:when test="$heightRethrowCheck = '1'">
							<div id="height_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/height" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="height_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/height" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="partner_height" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="r1"></div>
						<div class="r2">
						<div class="fl">
						<div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
							<div class="partner_image_female">
								<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
							</div>
							<div class="partner_image_male">
								<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
							</div>
							<div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
						</div>
						<div class="fl lgreen_border" style="width:420px">
							<div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
								<div style="padding:2px 0 0 5px;">
									<b><xsl:value-of select="labels/partnerHeight" /></b><br />
								</div>
							</div>
							<div style="padding:5px;width:98%;background-color: #f2f8d4;">
								<span id="lheight_span_id">
								<select class="textbox" size="1" name="lheight" id="lheight">
									<option value="">
										<xsl:value-of select="tagLabels/min" />
									</option>
								</select>
								</span>&#160;
								<xsl:value-of select="labels/to" />&#160;
								<span id="hheight_span_id">
								<select class="textbox" size="1" name="hheight" id="hheight">
									<option value="">
										<xsl:value-of select="tagLabels/max" />
									</option>
								</select>
								</span>&#160;
							</div>
						</div>
						</div></div>
						<xsl:variable name="partnerHeightRethrowCheck" select="rethrowPage/partnerHeight" />
						<xsl:choose>
							<xsl:when test="$partnerHeightRethrowCheck = '1'">
								<div id="partner_height_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="partner_height_error1" style="display:inline">
											<xsl:value-of select="submitErrorMessages/partnerHeight/error1" />
										</div>
										<div id="partner_height_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/partnerHeight/error2" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:when test="$partnerHeightRethrowCheck = '2'">
								<div id="partner_height_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="partner_height_error1" style="display:none">
											<xsl:value-of select="submitErrorMessages/partnerHeight/error1" />
										</div>
										<div id="partner_height_error2" style="display:inline">
											<xsl:value-of select="submitErrorMessages/partnerHeight/error2" />
										</div>
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_height_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<div id="partner_height_error1" style="display:inline">
											<xsl:value-of select="submitErrorMessages/partnerHeight/error1" />
										</div>
										<div id="partner_height_error2" style="display:none">
											<xsl:value-of select="submitErrorMessages/partnerHeight/error2" />
										</div>
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/country" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="country_residence" id="country_residence" style="width:204px;" >
								<option value="">
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/country">
									<xsl:value-of disable-output-escaping="yes" select="." />
								</xsl:for-each>
							</select>
						</div>
					</div>
					<xsl:variable name="countryRethrowCheck" select="rethrowPage/countryResidence" />
					<xsl:choose>
						<xsl:when test="$countryRethrowCheck = '1'">
							<div id="country_residence_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/countryResidence" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="country_residence_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/countryResidence" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="citizenship_show_hide" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1">
								<xsl:value-of select="labels/citizenship" /> :
							</div>
							<div class="r2">
								<select class="textbox" size="1" name="citizenship" id="citizenship" style="width:204px;">
									<option value=""><xsl:value-of select="tagLabels/pleaseSelect" /></option>
									<xsl:for-each select="populate/citizenship">
										<xsl:variable name="citizenshipVar" select="@value" />
										<option value="{$citizenshipVar}">
											<xsl:value-of select="." />
										</option>
									</xsl:for-each>
								</select>
								<div class="coverhelp">
									<div class="helpbox" id="citizenship_help" style="top:-15px; _top:-0px;">
										<div class="helptext">
											<xsl:value-of select="help/citizenship"/>
											<div class="helpimg"></div>
										</div>  
									</div>
								</div>
							</div>
						</div>
						<xsl:variable name="citizenshipRethrowCheck" select="rethrowPage/citizenship" />
						<xsl:choose>
							<xsl:when test="$citizenshipRethrowCheck = '1'">
								<div id="citizenship_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/citizenship" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="citizenship_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/citizenship" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div id="city_res_show_hide">
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1">
								<xsl:value-of select="labels/city" /> :
							</div>
							<div class="r2">
								<input type="hidden" name="city_residence_selected" value="{$CITY_RESIDENCE}" />
								<div id="city_india_visible" style="display:block">
									<select class="textbox" size="1" name="city_residence" id="city_residence" style="width:204px;" >
										<option value="">
											<xsl:value-of select="tagLabels/pleaseSelect" />
										</option>
									</select>
								</div>
							</div>
						</div>
						<xsl:variable name="cityRethrowCheck" select="rethrowPage/city" />
						<xsl:choose>
							<xsl:when test="$cityRethrowCheck = '1'">
								<div id="city_residence_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/cityResidence" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="city_residence_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/cityResidence" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/contactNumber" /> :
						</div>
						<div class="r2">
							<span style="color:#E3373B; display:none" id="contact_number_error">
								<xsl:value-of select="messages/contactNumberMessage" />
							</span>
							<span id="contact_number_noerror">
								<xsl:value-of select="messages/contactNumberMessage" />
							</span>
						</div>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
						</div>
						<div class="r2">
							<xsl:value-of select="labels/mobileNumber" />&#160;
							<input class="textbox" type="text" maxlength="5" style="width: 30px;" value="{$COUNTRY_CODE}" name="country_code_mob" id="country_code_mob" readonly="yes"/>
							<xsl:variable name="vanishMobile" select="vanishingLabels/mobileNumber" />
							<xsl:choose>
								<xsl:when test="$MOBILE &gt; 0">
									&#160;<input class="textbox" type="text" maxlength="11" value="{$MOBILE}" style="width:160px; " name="mobile" id="mobile" />
								</xsl:when>
								<xsl:otherwise>
									&#160;<input class="textbox" type="text" maxlength="11" value="{$vanishMobile}" style="width:160px; " name="mobile" id="mobile" />
								</xsl:otherwise>
							</xsl:choose>
							&#160;<xsl:value-of select="labels/of"/>&#160;
							<select class="textbox" size="1" name="mobile_number_owner" id="mobile_number_owner" style="width:90px;">
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '1'">
										<option value="1" selected="yes"><xsl:value-of select="labels/bride"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="1"><xsl:value-of select="labels/bride"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '2'">
										<option value="2" selected="yes"><xsl:value-of select="labels/groom"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="2"><xsl:value-of select="labels/groom"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '3'">
										<option value="3" selected="yes"><xsl:value-of select="labels/parent"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="3"><xsl:value-of select="labels/parent"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '4'">
										<option value="4" selected="yes"><xsl:value-of select="labels/son"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="4"><xsl:value-of select="labels/son"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '5'">
										<option value="5" selected="yes"><xsl:value-of select="labels/daughter"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="5"><xsl:value-of select="labels/daughter"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '6'">
										<option value="6" selected="yes"><xsl:value-of select="labels/sibling"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="6"><xsl:value-of select="labels/sibling"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$MOBILE_NUMBER_OWNER = '7'">
										<option value="7" selected="yes"><xsl:value-of select="labels/other"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="7"><xsl:value-of select="labels/other"/></option>
									</xsl:otherwise>
								</xsl:choose>
							</select>&#160;
							<xsl:value-of select="labels/whoseName"/>
							&#160;<input class="textbox" type="text" value="{$MOBILE_OWNER_NAME}" name="mobile_owner_name" id="mobile_owner_name" style="width:85px; "/>
							<br />
							<div style="padding-left:62px; padding-top:5px">
								<xsl:choose>
									<xsl:when test="$SHOWMOBILE='N'">
										<select name="showmobile" class="textbox">
											<option value="">
												<xsl:value-of select="labels/selectViewingOption" />
											</option>
											<option value="Y">
												<xsl:value-of select="labels/show" />
											</option>
											<option value="N" selected="yes">
												<xsl:value-of select="labels/dontshow" />
											</option>
										</select>
									</xsl:when>
									<xsl:when test="$SHOWMOBILE='Y'">
										<select name="showmobile" class="textbox">
											<!--option value="">
												<xsl:value-of select="labels/selectViewingOption" />
											</option-->
											<option value="Y" selected="yes">
												<xsl:value-of select="labels/show" />
											</option>
											<option value="N">
												<xsl:value-of select="labels/dontshow" />
											</option>
										</select>
									</xsl:when>
									<xsl:otherwise>
										<select name="showmobile" class="textbox">
											<option value="" selected="yes">
												<xsl:value-of select="labels/selectViewingOption" />
											</option>
											<option value="Y">
												<xsl:value-of select="labels/show" />
											</option>
											<option value="N">
												<xsl:value-of select="labels/dontshow" />
											</option>
										</select>
									</xsl:otherwise>
								</xsl:choose>
							</div>
						</div>
					</div>
					<div id="login_link_mob" style="display:none;color:#000000" class="suberrmsg">
					<img src="{$IMG_URL}/ic_information_small.gif" style="vertical-align:bottom;" />&#160;
						Mobile Number already exists,Please <a href="{$SITE_URL}" target="_blank">click here</a> to login to the profile.
					</div>
					<xsl:variable name="countryCodeMobileRethrowCheck" select="rethrowPage/countryCodeMobile" />
					<xsl:choose>
						<xsl:when test="$countryCodeMobileRethrowCheck = '1'">
							<div id="country_code_mobile_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/countryCodeMobile/error1" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$countryCodeMobileRethrowCheck = '2'">
							<div id="country_code_mobile_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/countryCodeMobile/error2" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="country_code_mobile_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/countryCodeMobile/error2" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="mobileRethrowCheck" select="rethrowPage/mobile" />
					<xsl:choose>
						<xsl:when test="$mobileRethrowCheck = '1'">
							<div id="mobile_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobile/error1" />
									</div>
									<div id="mobile_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error2" />
									</div>
									<div id="mobile_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$mobileRethrowCheck = '2'">
							<div id="mobile_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error1" />
									</div>
									<div id="mobile_error2" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobile/error2" />
									</div>
									<div id="mobile_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$mobileRethrowCheck = '3'">
							<div id="mobile_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error1" />
									</div>
									<div id="mobile_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error2" />
									</div>
									<div id="mobile_error3" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobile/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="mobile_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobile/error1" />
									</div>
									<div id="mobile_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error2" />
									</div>
									<div id="mobile_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobile/error3" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="mobileOwnerNameRethrowCheck" select="rethrowPage/mobileOwnerName" />
					<xsl:choose>
						<xsl:when test="$mobileOwnerNameRethrowCheck = '1'">
							<div id="mobile_owner_name_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_owner_name_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobileOwnerName/error1" />
									</div>
									<div id="mobile_owner_name_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobileOwnerName/error2" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$mobileOwnerNameRethrowCheck = '2'">
							<div id="mobile_owner_name_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_owner_name_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobileOwnerName/error1" />
									</div>
									<div id="mobile_owner_name_error2" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobileOwnerName/error2" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="mobile_owner_name_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="mobile_owner_name_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/mobileOwnerName/error1" />
									</div>
									<div id="mobile_owner_name_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/mobileOwnerName/error2" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="showmobileRethrowCheck" select="rethrowPage/showmobile" />
					<xsl:choose>
						<xsl:when test="$showmobileRethrowCheck = '1'">
							<div id="showmobile_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/showmobile" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="showmobile_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/showmobile" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
						</div>
						<div class="r2">
							<xsl:value-of select="labels/phoneNumber" />&#160;
							<input class="textbox" type="text" maxlength="5" style="width: 30px;" value="{$COUNTRY_CODE}" name="country_code" id="country_code" readonly="yes"/>
							<xsl:variable name="vanishStd" select="vanishingLabels/std" />
							<xsl:choose>
								<xsl:when test="$STATE_CODE &gt; 0">
									&#160;<input class="textbox" type="text" maxlength="10" style="width: 40px;" value="{$STATE_CODE}" name="state_code" id="state_code" />
								</xsl:when>
								<xsl:otherwise>
									&#160;<input class="textbox" type="text" maxlength="10" style="width: 40px;" value="{$vanishStd}" name="state_code" id="state_code" />
								</xsl:otherwise>
							</xsl:choose>
							<xsl:variable name="vanishPhone" select="vanishingLabels/phoneNumber" />
							<xsl:choose>
								<xsl:when test="$PHONE &gt; 0">
									&#160;<input class="textbox" type="text" maxlength="11" value="{$PHONE}" name="phone" style="width:113px;" id="phone" />
								</xsl:when>
								<xsl:otherwise>
									&#160;<input class="textbox" type="text" maxlength="11" value="{$vanishPhone}" name="phone" style="width:113px;" id="phone" />
								</xsl:otherwise>
							</xsl:choose>
							&#160;<xsl:value-of select="labels/of"/>&#160;
							<select class="textbox" size="1" name="phone_number_owner" id="phone_number_owner" style="width:90px;">
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '1'">
										<option value="1" selected="yes"><xsl:value-of select="labels/bride"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="1"><xsl:value-of select="labels/bride"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '2'">
										<option value="2" selected="yes"><xsl:value-of select="labels/groom"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="2"><xsl:value-of select="labels/groom"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '3'">
										<option value="3" selected="yes"><xsl:value-of select="labels/parent"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="3"><xsl:value-of select="labels/parent"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '4'">
										<option value="4" selected="yes"><xsl:value-of select="labels/son"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="4"><xsl:value-of select="labels/son"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '5'">
										<option value="5" selected="yes"><xsl:value-of select="labels/daughter"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="5"><xsl:value-of select="labels/daughter"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '6'">
										<option value="6" selected="yes"><xsl:value-of select="labels/sibling"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="6"><xsl:value-of select="labels/sibling"/></option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="$PHONE_NUMBER_OWNER = '7'">
										<option value="7" selected="yes"><xsl:value-of select="labels/other"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="7"><xsl:value-of select="labels/other"/></option>
									</xsl:otherwise>
								</xsl:choose>
							</select>&#160;
							<xsl:value-of select="labels/whoseName"/>
							&#160;<input class="textbox" type="text" value="{$PHONE_OWNER_NAME}" name="phone_owner_name" id="phone_owner_name" style="width:85px; "/>
							<br />
							<div style="padding-left:62px; padding-top:5px">
								<xsl:choose>
									<xsl:when test="$SHOWPHONE='N'">
										<select name="showphone" class="textbox">
											<option value="">
												<xsl:value-of select="labels/selectViewingOption" />
											</option>
											<option value="Y">
												<xsl:value-of select="labels/show" />
											</option>
											<option value="N" selected="yes">
												<xsl:value-of select="labels/dontshow" />
											</option>
										</select>
									</xsl:when>
									<xsl:when test="$SHOWPHONE='Y'">
										<select name="showphone" class="textbox">
											<!--option value="">
												<xsl:value-of select="labels/selectViewingOption" />
											</option-->
											<option value="Y" selected="yes">
												<xsl:value-of select="labels/show" />
											</option>
											<option value="N">
												<xsl:value-of select="labels/dontshow" />
											</option>
										</select>
									</xsl:when>
									<xsl:otherwise>
										<select name="showphone" class="textbox">
											<option value="" selected="yes">
												<xsl:value-of select="labels/selectViewingOption" />
											</option>
											<option value="Y">
												<xsl:value-of select="labels/show" />
											</option>
											<option value="N">
												<xsl:value-of select="labels/dontshow" />
											</option>
										</select>
									</xsl:otherwise>
								</xsl:choose>
							</div>
						</div>
					</div>
					<xsl:variable name="countryCodeRethrowCheck" select="rethrowPage/countryCode" />
					<xsl:choose>
						<xsl:when test="$countryCodeRethrowCheck = '1'">
							<div id="country_code_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/countryCode/error1" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$countryCodeRethrowCheck = '2'">
							<div id="country_code_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/code/error2" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="country_code_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;&#160;
									<xsl:value-of select="submitErrorMessages/countryCode/error1" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="stateCodeRethrowCheck" select="rethrowPage/stateCode" />
					<xsl:choose>
						<xsl:when test="$stateCodeRethrowCheck = '1'">
							<div id="state_code_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;&#160;
									<xsl:value-of select="submitErrorMessages/stateCode/error1" />
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$stateCodeRethrowCheck = '2'">
							<div id="state_code_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/stateCode/error2" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="state_code_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/stateCode/error1" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="login_link_phone" style="display:none;color:#000000" class="suberrmsg">
					<img src="{$IMG_URL}/ic_information_small.gif" style="vertical-align:bottom;" />&#160;
						Phone Number already exists , Please <a href="{$SITE_URL}" target="_blank">click here </a>to login to the profile.
					</div>
					<xsl:variable name="phoneRethrowCheck" select="rethrowPage/phone" />
					<xsl:choose>
						<xsl:when test="$phoneRethrowCheck = '1'">
							<div id="phone_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="phone_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phone/error1" />
									</div>
									<div id="phone_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error2" />
									</div>
									<div id="phone_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$phoneRethrowCheck = '2'">
							<div id="phone_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<div id="phone_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error1" />
									</div>
									<div id="phone_error2" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phone/error2" />
									</div>
									<div id="phone_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$phoneRethrowCheck = '3'">
							<div id="phone_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="phone_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error1" />
									</div>
									<div id="phone_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error2" />
									</div>
									<div id="phone_error3" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phone/error3" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="phone_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="phone_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phone/error1" />
									</div>
									<div id="phone_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error2" />
									</div>
									<div id="phone_error3" style="display:none">
										<xsl:value-of select="submitErrorMessages/phone/error3" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="phoneOwnerNameRethrowCheck" select="rethrowPage/phoneOwnerName" />
					<xsl:choose>
						<xsl:when test="$phoneOwnerNameRethrowCheck = '1'">
							<div id="phone_owner_name_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="phone_owner_name_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phoneOwnerName/error1" />
									</div>
									<div id="phone_owner_name_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/phoneOwnerName/error2" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:when test="$phoneOwnerNameRethrowCheck = '2'">
							<div id="phone_owner_name_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="phone_owner_name_error1" style="display:none">
										<xsl:value-of select="submitErrorMessages/phoneOwnerName/error1" />
									</div>
									<div id="phone_owner_name_error2" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phoneOwnerName/error2" />
									</div>
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="phone_owner_name_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<div id="phone_owner_name_error1" style="display:inline">
										<xsl:value-of select="submitErrorMessages/phoneOwnerName/error1" />
									</div>
									<div id="phone_owner_name_error2" style="display:none">
										<xsl:value-of select="submitErrorMessages/phoneOwnerName/error2" />
									</div>
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:variable name="showphoneRethrowCheck" select="rethrowPage/showphone" />
					<xsl:choose>
						<xsl:when test="$showphoneRethrowCheck = '1'">
							<div id="showphone_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/showphone" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="showphone_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/showphone" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/suitableTimeToCall" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="time_to_call_start" id="time_to_call_start">
								<xsl:for-each select="populate/timeToCall">
									<xsl:variable name="ttc" select="." />
									<xsl:choose>
										<xsl:when test="$TIME_TO_CALL_START = $ttc">
											<option value="{$ttc}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:when test="$ttc = '9'">
											<option value="{$ttc}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$ttc}">
												<xsl:value-of select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select>&#160;
							<select class="textbox" size="1" name="start_am_pm" id="start_am_pm">
								<xsl:choose>
									<xsl:when test="$START_AM_PM='PM'">
										<option value="AM"><xsl:value-of select="labels/am"/></option>
										<option value="PM" selected="yes"><xsl:value-of select="labels/pm"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="AM" selected="yes"><xsl:value-of select="labels/am"/></option>
										<option value="PM"><xsl:value-of select="labels/pm"/></option>
									</xsl:otherwise>
								</xsl:choose>
							</select>
							&#160;<xsl:value-of select="labels/to"/>&#160;
							<select class="textbox" size="1" name="time_to_call_end" id="time_to_call_end">
								<xsl:for-each select="populate/timeToCall">
									<xsl:variable name="ttc" select="." />
									<xsl:choose>
										<xsl:when test="$TIME_TO_CALL_END = $ttc">
											<option value="{$ttc}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:when test="$ttc = '9'">
											<option value="{$ttc}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$ttc}">
												<xsl:value-of select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select>&#160;
							<select class="textbox" size="1" name="end_am_pm" id="end_am_pm">
								<xsl:choose>
									<xsl:when test="$END_AM_PM='AM'">
										<option value="AM" selected="yes"><xsl:value-of select="labels/am"/></option>
										<option value="PM"><xsl:value-of select="labels/pm"/></option>
									</xsl:when>
									<xsl:otherwise>
										<option value="AM"><xsl:value-of select="labels/am"/></option>
										<option value="PM" selected="yes"><xsl:value-of select="labels/pm"/></option>
									</xsl:otherwise>
								</xsl:choose>
							</select>
							<div class="coverhelp">
								<div class="helpbox" id="time_to_call_start_help">
									<div class="helptext">
										<xsl:value-of select="help/suitableTimeToCall"/>
										<div class="helpimg"></div>
									</div>  
								</div>
								<div class="helpbox" id="start_am_pm_help">
									<div class="helptext">
										<xsl:value-of select="help/suitableTimeToCall"/>
										<div class="helpimg"></div>
									</div>  
								</div>
								<div class="helpbox" id="time_to_call_end_help">
									<div class="helptext">
										<xsl:value-of select="help/suitableTimeToCall"/>
										<div class="helpimg"></div>
									</div>  
								</div>
								<div class="helpbox" id="end_am_pm_help">
									<div class="helptext">
										<xsl:value-of select="help/suitableTimeToCall"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<xsl:variable name="suitableTimeToCallCheck" select="rethrowPage/suitableTimeToCall" />
					<xsl:choose>
						<xsl:when test="$suitableTimeToCallCheck = '1'">
							<div id="time_to_call_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/suitableTimeToCall" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="time_to_call_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/suitableTimeToCall" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					</div>
					<div class="clear"></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_left.gif" /></div>
					<div class="fl sr_bottom_bg" style="width:740px;"><img src="{$IMG_URL}/spacer.gif" height="1" /></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_right.gif" /></div>
				</div>
				<div class="spacer">&#160;</div>
				<div class="nothighlight" id="religionEthnicity_section">
					<div class="fl"><img src="{$IMG_URL}/sr_top_left.gif" /></div>
					<div class="fl sr_top_bg b" style="width:740px;">
						<div style="padding:3px 0 0 5px;">
							<div id="self_religionEthnicity" style="display:block">
								<xsl:value-of select="labels/religionEthnicity/self" />
							</div>
							<div id="friend_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/friend" />
							</div>
							<div id="son_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/son" />
							</div>
							<div id="daughter_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/daughter" />
							</div>
							<div id="brother_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/brother" />
							</div>
							<div id="sister_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/sister" />
							</div>
							<div id="father_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/father" />
							</div>
							<div id="mother_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/mother" />
							</div>
							<div id="marriageBureau_religionEthnicity" style="display:none">
								<xsl:value-of select="labels/religionEthnicity/marriageBureau" />
							</div>
						</div>
					</div>
					<div class="fl"><img src="{$IMG_URL}/sr_top_right.gif" /></div>
					<div class="fl orange_border" style="width:732px; padding:8px">
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/community" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="mtongue" id="mtongue">
								<option value="" selected="yes" >
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/community">
									<xsl:value-of disable-output-escaping="yes" select="." />
								</xsl:for-each>
							</select>
							<div class="coverhelp">
								<div class="helpbox" id="mtongue_help" style="top:-15px; _top:0px;">
									<div class="helptext">
										<xsl:value-of select="help/community"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div>
						</div>
					</div>
					<xsl:variable name="mtongueRethrowCheck" select="rethrowPage/mtongue" />
					<xsl:choose>
						<xsl:when test="$mtongueRethrowCheck = '1'">
							<div id="mtongue_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/community" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="mtongue_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/community" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="partner_mtongue" style="display:none">
						<div class="spacer1">&#160;</div>
						
						<div class="row">
						<div class="r1"></div>
						<div class="r2">
						<div class="fl">
						<div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
							<div class="partner_image_female">
								<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
							</div>
							<div class="partner_image_male">
								<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
							</div>
							<div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
						</div>
						
							<div class="fl lgreen_border" style="width:420px;">
							 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
							  <div style="padding:2px 0 0 5px;">
							   <xsl:value-of select="labels/partnerCommunity" />
							  </div>
							 </div>
							 <div style="padding:5px;width:98%;background-color: #f2f8d4;">
							  <div class="fl" style="width:48%">
							  <div style="padding: 0pt 5px 0pt 2px;">
							   <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
							   <div class="fr"><a href="" id="partner_mtongue_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
							  </div>
							   <div class="fl scrollbox" style="background-color: #ffffff">
							    <div style="display:none;" id="partner_mtongue_div">
							     <input type="hidden" name="partner_mtongue_str" id="partner_mtongue_str" value="{$PARTNER_MTONGUE_STR}" />
							     <xsl:for-each select="populate/partnerCommunityActual">
							      <xsl:variable name="comVal" select="@value" />
							      <input type="checkbox" name="partner_mtongue_arr[]" id="partner_mtongue_{$comVal}" value="{$comVal}"/>
							      <label id="partner_mtongue_label_{$comVal}">
							       <xsl:value-of disable-output-escaping="yes" select="." />
							      </label>
							      <br />
							     </xsl:for-each>
							    </div>
							    <div style="overflow:hidden;" id="partner_mtongue_source_div">
							     <xsl:for-each select="populate/partnerCommunity">
							      <xsl:variable name="comVal" select="@value" />
                                                              <xsl:choose>
                                                               <xsl:when test="$comVal = '####'">
                                                                <span style="color:#0a89fe;">
                                                                 <xsl:value-of select="."/>
                                                                </span>
                                                                <div class="clear" style="line-height:5px;">&#160;</div>
                                                               </xsl:when>
                                                               <xsl:otherwise>
                                                                <xsl:if test="$comVal != 'DM'">
							        <input type="checkbox" class="chbx checkboxalign" name="partner_mtongue_displaying_arr[]" id="partner_mtongue_displaying_{$comVal}" value="{$comVal}"/>
							        <label id="partner_mtongue_displaying_label_{$comVal}">
							         <xsl:value-of disable-output-escaping="yes" select="." />
							        </label>
							        <br />
							        </xsl:if>
							       </xsl:otherwise>
							      </xsl:choose>
							     </xsl:for-each>
							     <br />
							    </div>
							   </div>
							  </div>
							  <div class="fr" style="width:48%">
							   <div style="padding: 0pt 5px 0pt 2px;">
							    <div class="fl"><xsl:value-of select="gadgetLabel/removeItem" /></div>
							    <div class="fr"><a href="" id="partner_mtongue_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
							   </div>
							    <div class="fl scrollbox" style="background-color: #ffffff">
							     <div style="overflow:hidden;" id="partner_mtongue_target_div">
							      <div id="partner_mstatus_DM">
							       <label><xsl:value-of select="tagLabels/doesntMatter" /></label>
							      </div>
							    </div>
							   </div>
							  </div>
							  <div style="height:10px; clear:both;"></div>
							 </div>
							</div>
						</div>
						</div></div>
						<xsl:variable name="partnerMtongueRethrowCheck" select="rethrowPage/partnerMtongue" />
						<xsl:choose>
							<xsl:when test="$partnerMtongueRethrowCheck = '1'">
								<div id="partner_mtongue_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerCommunity" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_mtongue_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerCommunity" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/religion" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="religion" id="religion" style="width:204px;">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/religion">
									<xsl:value-of disable-output-escaping="yes" select="." />
								</xsl:for-each>
							</select> 
						</div>
					</div>
					<xsl:variable name="religionRethrowCheck" select="rethrowPage/religion" />
					<xsl:choose>
						<xsl:when test="$religionRethrowCheck = '1'">
							<div id="religion_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/religion" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="religion_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/religion" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="partner_religion" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1"></div>
							<div class="r2">
							<div class="fl">
							<div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
							<div class="partner_image_female">
								<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
							</div>
							<div class="partner_image_male">
								<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
							</div>
								<div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
							</div>
							<div class="fl lgreen_border" style="width:420px;">
							 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
							  <div style="padding:2px 0 0 5px;">
							   <xsl:value-of select="labels/partnerReligion" />
							  </div>
							 </div>
							 <div style="padding:5px;width:98%;background-color: #f2f8d4">
							  <div class="fl" style="width:48%">
							   <div style="padding: 0pt 5px 0pt 2px;">
							    <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
							    <div class="fr"><a href="" id="partner_religion_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
							   </div>
							   <div class="fl scrollbox" style="background-color: #FFFFFF">
							    <div style="display:none" id="partner_religion_div">
							     <input type="hidden" name="partner_religion_str" id="partner_religion_str" value="{$PARTNER_RELIGION_STR}" />
							     <xsl:for-each select="populate/partnerReligion">
							      <xsl:variable name="relVal" select="@value" />
							      <input type="checkbox" name="partner_religion_arr[]" id="partner_religion_{$relVal}" value="{$relVal}"/>
							      <label id="partner_religion_label_{$relVal}">
							       <xsl:value-of disable-output-escaping="yes" select="." />
							      </label>
							      <br />
							     </xsl:for-each>
							    </div>
							    <div style="overflow:hidden;" id="partner_religion_source_div">
							     <xsl:for-each select="populate/partnerReligion">
							      <xsl:variable name="relVal" select="@value" />
							      <xsl:if test="$relVal != 'DM'">
							      <input type="checkbox" class="chbx checkboxalign" name="partner_religion_displaying_arr[]" id="partner_religion_displaying_{$relVal}" value="{$relVal}"/>
							      <label id="partner_religion_displaying_label_{$relVal}">
							       <xsl:value-of disable-output-escaping="yes" select="." />
							      </label>
							      <br />
							      </xsl:if>
							     </xsl:for-each>
							     <br />
							    </div>
							   </div>
							  </div>
							  <div class="fr" style="width:48%">
							   <div style="padding: 0pt 5px 0pt 2px;">
							    <div class="fl"><xsl:value-of select="gadgetLabel/removeItem" /></div>
							    <div class="fr"><a href="" id="partner_religion_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
							   </div>
							    <div class="fl scrollbox" style="background-color: #FFFFFF">
							     <div style="overflow:hidden;" id="partner_religion_target_div">
							      <div id="partner_religion_DM">
							       <label><xsl:value-of select="tagLabels/doesntMatter" /></label>
							      </div>
							    </div>
							   </div>
							  </div>
							  <div style="height:10px; clear:both;"></div>
							 </div>
							</div>
						</div>
						</div></div>
						<xsl:variable name="partnerReligionRethrowCheck" select="rethrowPage/partnerReligion" />
						<xsl:choose>
							<xsl:when test="$partnerReligionRethrowCheck = '1'">
								<div id="partner_religion_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerReligion" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_religion_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerReligion" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div id="speak_urdu_id" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1">
								<xsl:value-of select="labels/speakUrdu" /> :
							</div>
							<div class="r2">
							       <input type="checkbox" name="speak_urdu" value="Y"/>
							</div>
						</div>
					</div>
					<div id="caste_section">
						<!--xsl:variable name="noneForJavascript" select="dropdowns/none" />
						<input type="hidden" name="none_for_javascript" value="{$noneForJavascript}" /-->
						<div class="spacer1">&#160;</div>
						<div class="row">
							<div class="r1">
								<span id="caste_label_muslim" style="display:none">
									<xsl:value-of select="labels/maththab" /> :
								</span>
								<span id="caste_label_christian" style="display:none">
									<xsl:value-of select="labels/denomination" /> :
								</span>
								<span id="caste_label_hindu">
									<xsl:value-of select="labels/caste" /> :
								</span>
							</div>
							<div class="r2">
								<input type="hidden" name="caste_selected" value="{$CASTE}" />
								<div id="caste_dropdown" style="display:inline">
								<select class="textbox" size="1" name="caste" id="caste" style="width:204px;">
									<!--This portion of code i replaced using javascript-->
									<option value="" selected="">
										<xsl:value-of select="tagLabels/pleaseSelect" />
									</option>
									<xsl:for-each select="populate/caste">
										<xsl:variable name="caste_var" select="@value" />
										<xsl:choose>
											<xsl:when test="$CASTE=$caste_var">
												<option value="{$caste_var}" selected="yes">
													<xsl:value-of select="." />
												</option>
											</xsl:when>
											<xsl:otherwise>
												<option value="{$caste_var}">
													<xsl:value-of select="." />
												</option>
											</xsl:otherwise>
										</xsl:choose>
									</xsl:for-each>
								</select> 
								</div>
							</div>
						</div>
						<xsl:variable name="casteRethrowCheck" select="rethrowPage/caste" />
						<xsl:choose>
							<xsl:when test="$casteRethrowCheck = '1'">
								<div id="caste_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<span id="caste_error_muslim" style="display:none">
											<xsl:value-of select="submitErrorMessages/caste/muslim" />
										</span>
										<span id="caste_error_christian" style="display:none">
											<xsl:value-of select="submitErrorMessages/caste/christian" />
										</span>
										<span id="caste_error_hindu" style="display:inline">
											<xsl:value-of select="submitErrorMessages/caste/hindu" />
										</span>
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="caste_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="suberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<span id="caste_error_muslim" style="display:none">
											<xsl:value-of select="submitErrorMessages/caste/muslim" />
										</span>
										<span id="caste_error_christian" style="display:none">
											<xsl:value-of select="submitErrorMessages/caste/christian" />
										</span>
										<span id="caste_error_hindu" style="display:inline">
											<xsl:value-of select="submitErrorMessages/caste/hindu" />
										</span>
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
						<!-- commenting because we dont want entry from user for the caste field -->
						<!--div id="caste_entry_section" style="display:none">
							<div class="spacer1">&#160;</div>
							<div class="row">
								<div class="r1">
									<span id="caste_entry_label_muslim" style="display:none">
										<xsl:value-of select="labels/maththabEntry" /> :
									</span>
									<span id="caste_entry_label_christian" style="display:none">
										<xsl:value-of select="labels/denominationEntry" /> :
									</span>
									<span id="caste_entry_label_hindu">
										<xsl:value-of select="labels/casteEntry" /> :
									</span>
								</div>
								<div class="r2">
									<input class="textbox" type="text" name="caste_entry" id="caste_entry" style="width:200px" />
								</div>
							</div>
							<xsl:variable name="casteEntryRethrowCheck" select="rethrowPage/casteEntry" />
							<xsl:choose>
								<xsl:when test="$casteEntryRethrowCheck = '1'">
									<div id="caste_entry_submit_err" class="suberrRethrow">
										<div class="spacer1">&#160;</div>
										<div class="suberrmsg">
											<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
											<span id="caste_entry_error_muslim" style="display:none">
												<xsl:value-of select="submitErrorMessages/casteEntry/muslim" />
											</span>
											<span id="caste_entry_error_christian" style="display:none">
												<xsl:value-of select="submitErrorMessages/casteEntry/christian" />
											</span>
											<span id="caste_entry_error_hindu" style="display:inline">
												<xsl:value-of select="submitErrorMessages/casteEntry/hindu" />
											</span>
										</div>
									</div>
								</xsl:when>
								<xsl:otherwise>
									<div id="caste_entry_submit_err" class="suberr">
										<div class="spacer1">&#160;</div>
										<div class="suberrmsg">
											<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
											<span id="caste_entry_error_muslim" style="display:none">
												<xsl:value-of select="submitErrorMessages/casteEntry/muslim" />
											</span>
											<span id="caste_entry_error_christian" style="display:none">
												<xsl:value-of select="submitErrorMessages/casteEntry/christian" />
											</span>
											<span id="caste_entry_error_hindu" style="display:inline">
												<xsl:value-of select="submitErrorMessages/casteEntry/hindu" />
											</span>
										</div>
									</div>
								</xsl:otherwise>
						</xsl:choose>
						</div -->
						<div id="partner_caste" style="display:none">
							<xsl:variable name="doesntMatter_var" select="tagLabels/doesntMatter" />
							<input type="hidden" name="doesnt_matter_for_javascript" value="{$doesntMatter_var}" />
							<div class="spacer1">&#160;</div>

							<div class="row">
                                                                <div class="r1"></div>
                                                                <div class="r2">
                                                                <div class="fl">
                                                                <div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
									<div class="partner_image_female" >
										<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
									</div>
									<div class="partner_image_male">
										<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
									</div>
                                                                        <div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
                                                                </div>
								<div id="caste_loader" style="display:none;margin-left:72px">
									<img src="{$IMG_URL}/loader_small.gif" style="vertical-align:bottom;" />	
								</div>
								<div id="lgreen_caste" class="fl lgreen_border" style="width:420px;display:block">
								 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
								  <div style="padding:2px 0 0 5px;">
								   <span id="partner_caste_label_muslim" style="display:none">
								    <xsl:value-of select="labels/partnerMaththab"/>
								   </span>
								   <span id="partner_caste_label_christian" style="display:none">
								    <xsl:value-of select="labels/partnerDenomination"/>
								   </span>
								   <span id="partner_caste_label_hindu">
								    <xsl:value-of select="labels/partnerCaste"/>
								   </span>
								  </div>
								 </div>
								 <div style="padding:5px;width:98%;background-color: #f2f8d4;">
								  <div class="fl" style="width:48%">
								   <div style="padding: 0pt 5px 0pt 2px;">
								    <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
								    <div class="fr"><a href="" id="partner_caste_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
								   </div>
								   <div class="fl scrollbox" style="background-color: #FFFFFF">
								    <input type="hidden" name="partner_caste_str" id="partner_caste_str" value="{$PARTNER_CASTE_STR}" />
								    <div style="display:none" id="partner_caste_div">
								     <xsl:for-each select="populate/partnerCaste">
								      <xsl:variable name="casVal" select="@value" />
								      <input type="checkbox" name="partner_caste_arr[]" id="partner_caste_{$casVal}" value="{$casVal}"/>
								      <label id="partner_caste_label_{$casVal}">
								       <xsl:value-of disable-output-escaping="yes" select="." />
								      </label>
								      <br />
								     </xsl:for-each>
								    </div>
								    <div style="overflow:hidden;" id="partner_caste_source_div">
								     <xsl:for-each select="populate/partnerCaste">
								      <xsl:variable name="casVal" select="@value" />
								      <xsl:if test="$casVal != 'DM'">
								      <input type="checkbox" class="chbx checkboxalign" name="partner_caste_displaying_arr[]" id="partner_caste_displaying_{$casVal}" value="{$casVal}"/>
								      <label id="partner_religion_displaying_label_{$casVal}">
								       <xsl:value-of disable-output-escaping="yes" select="." />
								      </label>
								      <br />
								      </xsl:if>
								     </xsl:for-each>
								     <br />
								    </div>
								   </div>
								  </div>
								  <div class="fr" style="width:48%">
								   <div style="padding: 0pt 5px 0pt 2px;">
								    <div class="fl"><xsl:value-of select="gadgetLabel/removeItem" /></div>
								    <div class="fr"><a href="" id="partner_caste_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
								   </div>
								    <div class="fl scrollbox" style="background-color: #FFFFFF">
								     <div style="overflow:hidden;" id="partner_caste_target_div">
								      <div id="partner_caste_DM">
								       <label><xsl:value-of select="tagLabels/doesntMatter" /></label>
								      </div>
								    </div>
								   </div>
								  </div>
								  <div style="height:10px; clear:both;"></div>
								 </div>
								</div>
							</div>
							</div></div>
							<xsl:variable name="partnerCasteRethrowCheck" select="rethrowPage/partnerCaste" />
							<xsl:choose>
								<xsl:when test="$partnerCasteRethrowCheck = '1'">
									<div id="partner_caste_submit_err" class="suberrRethrow">
										<div class="spacer1">&#160;</div>
										<div class="partsuberrmsg">
											<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
											<span id="partner_caste_error_muslim" style="display:none">
												<xsl:value-of select="submitErrorMessages/partnerCaste/muslim" />
											</span>
											<span id="partner_caste_error_christian" style="display:none">
												<xsl:value-of select="submitErrorMessages/partnerCaste/christian" />
											</span>
											<span id="partner_caste_error_hindu" style="display:inline">
												<xsl:value-of select="submitErrorMessages/partnerCaste/hindu" />
											</span>
										</div>
									</div>
								</xsl:when>
								<xsl:otherwise>
									<div id="partner_caste_submit_err" class="suberr">
										<div class="spacer1">&#160;</div>
										<div class="partsuberrmsg">
											<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
											<span id="partner_caste_error_muslim" style="display:none">
												<xsl:value-of select="submitErrorMessages/partnerCaste/muslim" />
											</span>
											<span id="partner_caste_error_christian" style="display:none">
												<xsl:value-of select="submitErrorMessages/partnerCaste/christian" />
											</span>
											<span id="partner_caste_error_hindu" style="display:inline">
												<xsl:value-of select="submitErrorMessages/partnerCaste/hindu" />
											</span>
										</div>
									</div>
								</xsl:otherwise>
							</xsl:choose>
						</div>
					</div>
					</div>
					<div class="clear"></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_left.gif" /></div>
					<div class="fl sr_bottom_bg" style="width:740px;"><img src="{$IMG_URL}/spacer.gif" height="1" /></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_right.gif" /></div>
				</div>
				<div class="spacer">&#160;</div>
				<div class="nothighlight" id="educationCareer_section">
					<div class="fl"><img src="{$IMG_URL}/sr_top_left.gif" /></div>
					<div class="fl sr_top_bg b" style="width:740px;">
						<div style="padding:3px 0 0 5px;">
							<div id="self_educationCareer" style="display:block">
								<xsl:value-of select="labels/educationCareer/self" />
							</div>
							<div id="friend_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/friend" />
							</div>
							<div id="son_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/son" />
							</div>
							<div id="daughter_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/daughter" />
							</div>
							<div id="brother_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/brother" />
							</div>
							<div id="sister_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/sister" />
							</div>
							<div id="father_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/father" />
							</div>
							<div id="mother_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/mother" />
							</div>
							<div id="marriageBureau_educationCareer" style="display:none">
								<xsl:value-of select="labels/educationCareer/marriageBureau" />
							</div>
						</div>
					</div>
					<div class="fl"><img src="{$IMG_URL}/sr_top_right.gif" /></div>
					<div class="fl orange_border" style="width:732px; padding:8px">
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/highestDegree" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="degree" id="degree" style="width:204px;">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/highestDegree">
									<xsl:value-of  disable-output-escaping="yes" select="." />
								</xsl:for-each>
							</select> 
						</div>
					</div>
					<xsl:variable name="degreeRethrowCheck" select="rethrowPage/degree" />
					<xsl:choose>
						<xsl:when test="$degreeRethrowCheck = '1'">
							<div id="degree_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/degree" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="degree_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/degree" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<!--<div id="partner_degree" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="r1"></div>
						<div class="r2">
							<div class="lgreen_border" style="width:360px;">
							 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
							  <div style="padding:2px 0 0 5px;">
							   <xsl:value-of select="labels/partnerHighestDegree" />
							  </div>
							 </div>
							 <div style="padding:5px;width:98%">
							  <div class="fl" style="width:48%">
							   <xsl:value-of select="gadgetLabel/selectItem" />&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;<a href="" id="partner_degree_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a>
							    <div class="fl scrollbox">
							     <div style="display:none" id="partner_degree_div">
							      <input type="hidden" name="partner_degree_str" id="partner_degree_str" value="{$PARTNER_DEGREE_STR}" />
							      <xsl:for-each select="populate/partnerHighestDegree">
							       <xsl:variable name="degVal" select="@value" />
							       <input type="checkbox" name="partner_degree_arr[]" id="partner_degree_{$degVal}" value="{$degVal}"/>
							       <label id="partner_degree_label_{$degVal}">
								<xsl:value-of select="." />
							       </label>
							       <br />
							      </xsl:for-each>
							     </div>
							     <div style="overflow:hidden;" id="partner_degree_source_div">
							      <xsl:for-each select="populate/partnerHighestDegree">
							      <xsl:variable name="degVal" select="@value" />
							       <input type="checkbox" class="chbx checkboxalign" name="partner_degree_displaying_arr[]" id="partner_degree_displaying_{$degVal}" value="{$degVal}"/>
							       <label id="partner_degree_displaying_label_{$degVal}">
								<xsl:value-of select="." />
							       </label>
							       <br />
							      </xsl:for-each>
							      <br />
							     </div>
							    </div>
							   </div>
							   <div class="fr" style="width:48%">
							    <xsl:value-of select="gadgetLabel/removeItem" />&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;<a href="" id="partner_degree_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a>
							    <div class="fl scrollbox">
							     <div style="overflow:hidden;" id="partner_degree_target_div">
							     </div>
							    </div>
							   </div>
							   <div style="height:10px; clear:both;"></div>
							  </div>
							 </div>
						</div>
						<xsl:variable name="partnerDegreeRethrowCheck" select="rethrowPage/partnerDegree" />
						<xsl:choose>
							<xsl:when test="$partnerDegreeRethrowCheck = '1'">
								<div id="partner_degree_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerDegree" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_degree_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerDegree" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>-->
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/workArea" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="occupation" style="width:204px;" id="occupation">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/workArea">
									<xsl:variable name="workArea_var" select="@value" />
									<xsl:choose>
										<xsl:when test="$OCCUPATION = $workArea_var">
											<option value="{$workArea_var}" selected="yes">
												<xsl:value-of select="." />
											</option>
										</xsl:when>
										<xsl:otherwise>
											<option value="{$workArea_var}">
												<xsl:value-of select="." />
											</option>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</select>
							<div class="coverhelp">
								<div class="helpbox" id="occupation_help" style="top:-15px; left:40px;">
									<div class="helptext">
										<xsl:value-of select="help/workArea"/>
										<div class="helpimg"></div>
									</div>  
								</div>
							</div> 
						</div>
					</div>
					<xsl:variable name="occupationRethrowCheck" select="rethrowPage/occupation" />
					<xsl:choose>
						<xsl:when test="$occupationRethrowCheck = '1'">
							<div id="occupation_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/occupation" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="occupation_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/occupation" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div class="spacer1">&#160;</div>
					<div class="row">
						<div class="r1">
							<xsl:value-of select="labels/annualIncome" /> :
						</div>
						<div class="r2">
							<select class="textbox" size="1" name="income" style="width:204px;" id="income">
								<option value="" selected="">
									<xsl:value-of select="tagLabels/pleaseSelect" />
								</option>
								<xsl:for-each select="populate/annualIncome">
									<xsl:value-of disable-output-escaping="yes" select="." />
								</xsl:for-each>
							</select> 
						</div>
					</div>
					<xsl:variable name="incomeRethrowCheck" select="rethrowPage/income" />
					<xsl:choose>
						<xsl:when test="$incomeRethrowCheck = '1'">
							<div id="income_submit_err" class="suberrRethrow">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/income" />
								</div>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div id="income_submit_err" class="suberr">
								<div class="spacer1">&#160;</div>
								<div class="suberrmsg">
									<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
									<xsl:value-of select="submitErrorMessages/income" />
								</div>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<div id="partner_income" style="display:none">
						<div class="spacer1">&#160;</div>
						<div class="r1"></div>
			                        <div class="fl lgreen_border" style="width:44px;text-align:center;font-size:10px;">
							<div class="partner_image_female">
								<img src="{$IMG_URL}/female_prof_icon.gif" align="top" />
							</div>
							<div class="partner_image_male">
								<img src="{$IMG_URL}/male_prof_icon.gif" align="top" />
							</div>
							<div class="fl" style="color:#4d4e46"><xsl:value-of select="labels/partnerPhoto"/></div>
						</div>
						<div class="r2" style="width:51%; padding:0;">
                        
							<div class="lgreen_border" style="width:420px;">
							 <div class="gadget_top_bg" style="color:#4d4e46; font:bold 12px arial">
							  <div style="padding:2px 0 0 5px;">
							   <xsl:value-of select="labels/partnerAnnualIncome" />
							  </div>
							 </div>
							 <div style="padding:5px;width:98%;background-color: #f2f8d4;">
							  <div class="fl" style="width:48%">
							   <div style="padding: 0pt 5px 0pt 2px;">
							    <div class="fl"><xsl:value-of select="gadgetLabel/selectItem" /></div>
							    <div class="fr"><a href="" id="partner_income_select_all" class="blink"><xsl:value-of select="gadgetLabel/selectAll" /></a></div>
							   </div>
							    <div class="fl scrollbox" style="background-color: #FFFFFF">
							     <div style="display:none" id="partner_income_div">
							      <input type="hidden" name="partner_income_str" id="partner_income_str" value="{$PARTNER_INCOME_STR}" />
							      <xsl:for-each select="populate/partnerAnnualIncome">
							       <xsl:variable name="incVal" select="@value" />
							       <input type="checkbox" name="partner_income_arr[]" id="partner_income_{$incVal}" value="{$incVal}"/>
							       <label id="partner_income_label_{$incVal}">
								<xsl:value-of select="." />
							       </label>
							       <br />
							      </xsl:for-each>
							     </div>
							     <div style="overflow:hidden;" id="partner_income_source_div">
							      <xsl:for-each select="populate/partnerAnnualIncome">
							       <xsl:variable name="incVal" select="@value" />
							       <xsl:if test="$incVal != 'DM'">
							       <input type="checkbox" class="chbx checkboxalign" name="partner_income_displaying_arr[]" id="partner_income_displaying_{$incVal}" value="{$incVal}"/>
							       <label id="partner_income_displaying_label_{$incVal}">
								<xsl:value-of select="." />
							       </label>
							       <br />
							       </xsl:if>
							      </xsl:for-each>
							      <br />
							     </div>
							    </div>
							   </div>
							   <div class="fr" style="width:48%">
							    <div style="padding: 0pt 5px 0pt 2px;">
							     <div class="fl"><xsl:value-of select="gadgetLabel/removeItem" /></div>
							     <div class="fr"><a href="" id="partner_income_clear_all" class="blink"><xsl:value-of select="gadgetLabel/clearAll" /></a></div>
							    </div>
							    <div class="fl scrollbox" style="background-color: #FFFFFF">
							     <div style="overflow:hidden;" id="partner_income_target_div">
							      <div id="partner_income_DM">
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
						<xsl:variable name="partnerIncomeRethrowCheck" select="rethrowPage/partnerIncome" />
						<xsl:choose>
							<xsl:when test="$partnerIncomeRethrowCheck = '1'">
								<div id="partner_income_submit_err" class="suberrRethrow">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerIncome" />
									</div>
								</div>
							</xsl:when>
							<xsl:otherwise>
								<div id="partner_income_submit_err" class="suberr">
									<div class="spacer1">&#160;</div>
									<div class="partsuberrmsg">
										<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
										<xsl:value-of select="submitErrorMessages/partnerIncome" />
									</div>
								</div>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					</div>
					<div class="clear"></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_left.gif" /></div>
					<div class="fl sr_bottom_bg" style="width:740px;"><img src="{$IMG_URL}/spacer.gif" height="1" /></div>
					<div class="fl"><img src="{$IMG_URL}/sr_bottom_right.gif" /></div>
				</div>
				<div class="spacer" style="line-height:22px;">&#160;</div>
				<div style="display:inline;padding-left:4px;"><xsl:value-of select="labels/likeToReceive" /></div>
				<div class="r2">
					<xsl:choose>
						<xsl:when test="$MATCH_ALERT = 'N'">
							<input type="checkbox" name="match_alerts" id="match_alerts" value="A"/>
						</xsl:when>
						<xsl:otherwise>
							<input type="checkbox" name="match_alerts" id="match_alerts" checked="yes" value="A"/>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/matchAlerts" />
					<xsl:choose>
						<xsl:when test="$PROMO = 'N'">
							<input type="checkbox" name="promo" id="promo" value="S"/>
						</xsl:when>
						<xsl:otherwise>
							<input type="checkbox" name="promo" id="promo" checked="yes" value="S"/>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/promo" />
					<xsl:choose>
						<xsl:when test="$SERVICE_MESSAGES = 'N'">
							<input type="checkbox" name="service_messages" id="service_messages" value="S" />
						</xsl:when>
						<xsl:otherwise>
							<input type="checkbox" name="service_messages" id="service_messages" checked="yes" value="S" />
						</xsl:otherwise>
					</xsl:choose>
					<xsl:value-of select="labels/serviceMessages" />
				</div>
				<div class="spacer1">&#160;</div>
				<div class="r2">
					<input type="checkbox" name="termsandconditions" value="Y" checked="yes" id="termsandconditions" />
					<xsl:value-of select="labels/termsConditions/part1" />&#160;
					<a href="{$SITE_URL}/profile/disclaimer.php" class="redlink" target="_blank">
						<xsl:value-of select="labels/termsConditions/part2" />
					</a>
					&#160;<xsl:value-of select="labels/termsConditions/part3" />&#160;
					<a href="{$SITE_URL}/profile/privacy_policy.php" class="redlink" target="_blank">
						<xsl:value-of select="labels/termsConditions/part4" />
					</a>.
				</div>
				<xsl:variable name="termsandconditionsRethrowCheck" select="rethrowPage/termsandconditions" />
				<xsl:choose>
					<xsl:when test="$termsandconditionsRethrowCheck = '1'">
						<div id="termsandconditions_submit_err" class="suberrRethrow">
							<div class="spacer1">&#160;</div>
							<div class="suberrmsg" style="padding-left:0px">
								<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
								<xsl:value-of select="submitErrorMessages/termsandconditions" />
							</div>
						</div>
					</xsl:when>
					<xsl:otherwise>
						<div id="termsandconditions_submit_err" class="suberr">
							<div class="spacer1">&#160;</div>
							<div class="suberrmsg" style="padding-left:0px">
								<img src="{$IMG_URL}/alert.gif" style="vertical-align:bottom;" />&#160;
								<xsl:value-of select="submitErrorMessages/termsandconditions" />
							</div>
						</div>
					</xsl:otherwise>
				</xsl:choose>
				<div class="spacer" style="line-height:25px;">&#160;</div>
				<div class="row" style="text-align: center;">
					<div>
						<input type="submit" name="submit_pg1" id="submit_pg1" class="submitbg" value="{$btnSubmit}" style="cursor:pointer"/>
					</div>
				</div>
				<div class="spacer" style="line-height:20px;">&#160;</div>
				<!-- end registration -->
			</div>
			</form>
		</div>
		<xsl:choose>
			<xsl:when test="$GROUPNAME = 'wchutney'">
				<script language="javascript" src="http://www.webchutney.net/chutneytrack/js/iqtracker.js"></script>
				<script language="javascript">
					var clntid = "JVNSTHI";
					trackThisPage();
				</script>
			</xsl:when>
			<xsl:when test="($GROUPNAME = 'Tyroo_India_JFM08') or ($GROUPNAME = 'Tyroo_NRI_JFM08')">
				<script type="text/javascript" src="http://tq.tyroo.com:8080/acquire/tyr_home.js"></script>
			</xsl:when>
		</xsl:choose>
		<script type="text/javascript" src="http://www.google-analytics.com/urchin.js"></script>
		<script type="text/javascript">
			_uacct = "UA-179986-1";
			urchinTracker();
		</script>
		<script type="text/javascript">
		function logerror_on_submit(value)
		{
		        document.getElementById("checkit").src="submit_hit_try.php?type="+value;
		}

		</script>
		<div id="ClickTaleDiv" style="display: none;"></div>
		<script src="http://s.clicktale.net/WRb.js" type="text/javascript"></script>
		<script type="text/javascript">
		if(typeof ClickTale=='function') ClickTale(30337,0.02,"www");
		</script>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>
