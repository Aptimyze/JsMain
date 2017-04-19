
<style>
.err_msg{width:235px !important;}
.helpFormat{z-index: 110; display: none;}
.helpimg
{  background: url("~sfConfig::get(app_site_url)`/profile/images/registration_revamp/arrow2_new.gif") no-repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
    height: 12px ;
    left: 230px !important;
    position: absolute;
    top: 10px !important;
    width: 16px;
}
.phoneFormat{width:170px;}
input.btnM {
    background-position: -122px -189px;
    border: medium none !important;
    cursor: pointer;
    font-size: 20px;
    height: 41px !important;
    margin: 0 auto;
    width: 118px;
}
.floatLeft{float:left;}
input#termsandconditions{margin:0px !important;}
.genFormat{width:221px !important;height:27px !important;}
.ml_10 {
      margin-left: 17px !important;
}

.chzn-container-single .chzn-single {
    background-color: #FFFFFF !important;
 }
select {
	background-color: #FFFFFF !important;
}
.padding_left{padding-left:133px;}
.eleft{left:-300px;top:-1px;}
.pleft{left:-330px;top:-1px;}
label {
    margin-top: 6px !important;
    padding-right: 6px !important;
}
#gender_err label{display:none;}
@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
	.eleft{left:-170px !important;top:-30px;}
	.pleft{left:-185px !important;top:-30px;}
/* IE10+ specific styles go here */
}
</style>
<!--[if IE 9]>
	<style type="text/css">
	.eleft{left:-170px !important;top:-30px;}
	.pleft{left:-185px !important;top:-30px;}
	</style>
<![endif]-->
<div style="display:none">
~$errMsg|decodevar`
~$defaultMsg|decodevar`
</div>
	<noscript>
		<div style="position:fixed;z-index:1000;width:100%">
			<div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;">
				<b><img src="~sfConfig::get(app_site_url)`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due  to this certain functionalities will not work. 
					<a href="~$SITE_URL`/P/js_help.htm" target="_blank">Click Here</a> , to know how  to enable it.
				</b>
			</div>
		</div>
	</noscript>
    
    <div class="clr cl_5"></div>
	<form id="reg" name="form1" method="post" enctype="multipart/form-data" style="margin: 0px">
		~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
    <input type="hidden" name="customReg" value="~$customReg`">
		<input type="hidden" name="tieup_source" value="~$TIEUP_SOURCE`" >
		<input type="hidden" name="hit_source" value="~$HITSOURCE`" >
		<input type="hidden" name="newip" value="~$NEWIP`" >
		<input type="hidden" name="adnetwork" value="~$adnetwork`" >
		<input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`" >
		<input type="hidden" name="fname" value="~$NAME`" >
		<input type="hidden" name="account" value="~$account`" >
		<input type="hidden" name="campaign" value="~$campaign`" >
		<input type="hidden" name="adgroup" value="~$adgroup`" >
		<input type="hidden" name="keyword" value="~$keyword`">
		<input type="hidden" name="match" value="~$match`" >
		<input type="hidden" name="lmd" value="~$lmd`" >
		<input type="hidden" name="showlogin" value="~$sf_request->getParameter('showlogin')`" >
		<input type="hidden" name="frommarriagebureau" value="~$FROMMARRIAGEBUREAU`" >
		<input type="hidden" name="groupname" value="~$GROUPNAME`" >
		<input type="hidden" name="id" value="~$ID_AFF`" >
		<input type="hidden" name="leadid" id ="leadid" value="~$leadid`"  >
		<input type="hidden" name="secondary_source" value="~$sf_request->getParameter('secondary_source')`" >
		~if $TIEUP_SOURCE eq 'ofl_prof'`
			<input type="hidden" name="email_is_ok" id="email_is_ok" value="1" >
		~else`
			<input type="hidden" name="email_is_ok" id="email_is_ok" value="" >
		~/if`
 		<i class="d_t_l pos_abs h_w_6 sprtereg"></i>
		<i class="d_t_r pos_abs h_w_6 sprtereg p_tr_0"></i>
		~if $IS_FTO_LIVE`
        <div class="fto-notification-box box1">
			 <p style="color:#bc001d">Complete the form and <br>
			 Get Jeevansathi Paid Membership for <span style="font-family:WebRupee;color:#bc001d">&#8377;</span> <span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span>
				 </span>
				 <strong> FREE</strong>
			 </p>
			 <div style=" height:10px"></div>
			 <p>See e-mail IDs / Phone numbers of people you like.</p>
	    </div>			  
		<div class="fl">
			  <div class="fto-arrow-down sprtereg"></div>
			  <div class="fr" style="color:#7e7e7e; margin-top:5px">Field marked <i class="orng">*</i> are compulsory. For offer details refer to Terms &amp; Conditions</div>
		 </div>
		 ~/if`
			<li>
					~if $form['email']->hasError()`~$form['email']->renderLabel(null,['style'=>'color:red'])`~else`~$form['email']->renderLabel()`~/if`
				~$form['email']->render(['maxlength'=>'100','class' => 'txt1 genFormat' ,'onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','tabindex'=>1])`
				<div class="coverhelp cvr_hlp fl">
         			<div id="reg_email_help" class="helpbox pd_5 helpFormat eleft">
					  <div class="helptext blk no_b f11">We would use this email id for all future communication. You would also need this email id to login to the site.
					  <div class="helpimg"></div>
					  </div>
          			</div>
          		</div>
				<div class="clr"></div>
			     <div id="email_err" style="display:~if $form['email']->hasError()`inline~else`none~/if`;" for='reg_email' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg"> ~$form['email']->getError()`</div>
				</div>
			    <div class="clr"></div>
	       	</li>
			<br>
			<li>
				~if $form['password']->hasError()`~$form['password']->renderLabel(null,['style'=>'color:red'])`~else`~$form['password']->renderLabel()`~/if`
				~$form['password']->render(['maxlength'=>'40','class'=>'txt1  genFormat','onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','tabindex'=>2, 'maxlength'=>'40'])`
				<div class="coverhelp cvr_hlp fl">
					<div id="reg_password_help" class="helpbox pd_5 helpFormat pleft" >
						<div class="helptext blk no_b f11">
							<div class="password-strength">
								<p>
									<strong>Password strength:</strong>
									<span id="passwdRating"></span>
								</p>
								<div class="meter" id="passwdBar">
									<span id="strength-bar"></span>
								</div>
							</div>
							The password should be at least 8 characters long.
							<div class="helpimg"></div>
						</div>
					</div>					
				</div>
			     <div id="password_err" style="display:~if $form['password']->hasError()`inline~else`none~/if`;" for='reg_password' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['password']->getError()`</div>
				</div>
				<div class="clr"></div>
		    </li>
			<br>
			<li class="relationship">
				~if $form['relationship']->hasError()`~$form['relationship']->renderLabel(null,['style'=>'color:red'])`~else`~$form['relationship']->renderLabel()`~/if`
				~$form['relationship']->render(['class'=>'sel_lng','tabindex'=>3])`
			    <div id="relationship_err" style="display:~if $form['relationship']->hasError()`inline~else`none~/if`;" for='reg_relationship' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['relationship']->getError()`</div>
				</div>
	       		</li>
			<!-- Relationship Ends Here -->
      		<div class="clr"></div>
			<div class="" style="width:96%">
			<!-- Gender Starts Here -->
				<li id="gender_padding">
				<div id="gender_section">
					<br>
					~if $form['gender']->hasError()`~$form['gender']->renderLabel(null,['style'=>'color:red'])`~else`~$form['gender']->renderLabel()`~/if`
					~$form['gender']->render(['tabindex'=>4])`
					 <div id="gender_err" style="display:~if $form['gender']->hasError()`inline~else`none~/if`;" for='reg[gender]' class='error padding_left'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['gender']->getError()`</div>
					</div>
					<div class="clr"></div>
				</div>
      			</li>
		<!-- Gender Ends Here -->
				<br>
		<!-- DOB Starts Here -->
	        	<li style="padding-bottom:15px;">
				<div id="year_span_id">
					~if $form['dtofbirth']->hasError()`~$form['dtofbirth']->renderLabel(null,['style'=>'color:red'])`~else`~$form['dtofbirth']->renderLabel()`~/if`
					~$form['dtofbirth']->render(['day'=>['onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','class'=>'w57 fl','tabindex'=>5],'month'=>['onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','class'=>'fl ml_10 w68','tabindex'=>6],'year'=>['onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','class'=>'fl ml_10 w62','tabindex'=>7]])`
				</div>
					<div class="coverhelp fl cvr_hlp">
						<div id="reg_dtofbirth_day_help" class="helpbox pd_5 helpFormat" style="left:-560px; top:-2px">
							<div class="blk no_b f11">
								Please remember that the date of birth, once entered cannot be changed.
								<div class="helpimg"></div>
							</div>
						</div>
					 </div>
					 <div class="coverhelp fl cvr_hlp">
						<div id="reg_dtofbirth_month_help" class="helpbox pd_5 helpFormat" style="left:-560px; top:-2px">
							<div class="blk no_b f11">
								Please remember that the date of birth, once entered cannot be changed.
								<div class="helpimg"></div>
							</div>
						</div>
					 </div>
					 <div class="coverhelp fl cvr_hlp">
						<div id="reg_dtofbirth_year_help" class="helpbox pd_5 helpFormat" style="left:-560px; top:-2px">
							<div class="blk no_b f11">
								Please remember that the date of birth, once entered cannot be changed.
								<div class="helpimg"></div>
							</div>
						</div>
					 </div>
					 <div class="clr"></div>
					 <div id="dtofbirth_err" style="display:~if $form['dtofbirth']->hasError()`inline~else`none~/if`;" class="error" for="date_of_birth">
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['dtofbirth']->getError()`</div>
					</div>
					<div class="clr"></div>
				</li>
			
		<!-- DOB Ends Here -->
				
		<!-- Height Starts Here -->
				<li>
					~if $form['height']->hasError()`~$form['height']->renderLabel(null,['style'=>'color:red'])`~else`~$form['height']->renderLabel()`~/if`
					~$form['height']->render(['class'=>'sel_lng','tabindex'=>8])`
					~$form['height']->renderHelp()`
					 <div id="height_err" style="display:~if $form['height']->hasError()`inline~else`none~/if`;" for='reg_height' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['height']->getError()`</div>
					</div>
				  <div class="clr"></div>
				</li>
		
		<!-- Height Ends Here -->
			<br>
			<!-- New Marital Status -->
				<li>
					~if $form['mstatus']->hasError()`~$form['mstatus']->renderLabel(null,['style'=>'color:red'])`~else`~$form['mstatus']->renderLabel()`~/if`
					~$form['mstatus']->render(['class'=>'sel_lng','tabindex'=>9])`
					 <div id="mstatus_err" style="display:~if $form['mstatus']->hasError()`inline~else`none~/if`;" for='reg_mstatus' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['mstatus']->getError()`</div>
					</div>
					<div class="clr"></div>
				</li>
			<!-- Ends Here -->
			<!-- NewHave Children Section Starts Here -->
			<li style="padding:0;">
				<div id="have_child_section" style="display:none;padding-bottom:15px!important; float:left;">
					<br>
					~if $form['havechild']->hasError()`~$form['havechild']->renderLabel(null,['style'=>'color:red'])`~else`~$form['havechild']->renderLabel()`~/if`
					~$form['havechild']->render(['class'=>'sel_lng','tabindex'=>10])`
					 <div id="havechild_err" style="display:~if $form['havechild']->hasError()`inline~else`none~/if`;" for='reg_havechild' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['havechild']->getError()`</div>
					</div>
				</div>
			</li>
			<br>
		<!-- Mother Tongue starts from here -->	
	        <li>
					~if $form['mtongue']->hasError()`~$form['mtongue']->renderLabel(null,['style'=>'color:red'])`~else`~$form['mtongue']->renderLabel()`~/if`
					~$form['mtongue']->render(['class'=>'sel_lng','tabindex'=>11,'onfocus'=>'show_help(this)','onblur'=>'hide_help(this)'])`
					 <div class="coverhelp fl">
					 <div id="reg_mtongue_help" class="helpbox helpFormat" style="left:-352px;width:214px !important;top:-3px;">
								<div class="blk no_b f11">
									This is your identity. Enter the mother tongue/region combination that explains you best.
									<div class="helpimg"></div>
							</div>
						</div>
					  </div>
					 <div id="mtongue_err" style="display:~if $form['mtongue']->hasError()`inline~else`none~/if`;" for='reg_mtongue' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['mtongue']->getError()`</div>
					</div>
					<div class="clr"></div>
			</li>
		<!-- Mother Tongue Ends Here -->        
			<br>
		<!-- Religion Starts Here -->
		<li>
					~if $form['religion']->hasError()`~$form['religion']->renderLabel(null,['style'=>'color:red'])`~else`~$form['religion']->renderLabel()`~/if`
					~$form['religion']->render(['class'=>'sel_lng','tabindex'=>12,'onChange'=>'callReligion(this)'])`
					 <div id="religion_err" style="display:~if $form['religion']->hasError()`inline~else`none~/if`;" for='reg_religion' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~if $form['religion']->hasError()`~$form['religion']->getError()`~else`Please provide a religion.~/if`</div>
					</div>
					<div class="clr"></div>
	    </li>
		
		<!-- Religion Ends Here -->
			
		<!-- Caste Starts Here -->
	        <li id="caste">
				<br>
				<div id="caste_submit_err_label" >
					<span id="caste_section"> 
						<label id="caste_label_muslim" style="display:none" class="l1">Sect<u>*</u> :</label>
						<label id="caste_label_christian" style="display:none" class="l1">Sect<u>*</u> :</label>
						<label id="caste_label_hindu" class="l1">Caste<u>*</u> :</label>
						
							~$form['caste']->render(['class'=>'sel_lng','tabindex'=>13])`
							
						 <div id="caste_err" style="display:~if $form['caste']->hasError()`inline~else`none~/if`;" for='reg_caste' class='error'>
							<label class="l1">&nbsp;</label>
							<div class="err_msg" id="caste_err_msg">~if $form['caste']->hasError()`~$form['caste']->getError()`~else`Please provide a caste.~/if`</div>
						</div>
					</span>
				</div>
	        </li>
	        <br>
		<div class="clr"></div>
		<!-- Caste Ends Here -->
		<!-- City Starts from here -->
				<li id="city_padding">
					<div id="city_res_show_hide" style="width:290px;float:left">
						~if $form['city_res']->hasError()`~$form['city_res']->renderLabel(null,['style'=>'color:red'])`~else`~$form['city_res']->renderLabel()`~/if`
						~$form['city_res']->render(['class'=>'sel_lng','tabindex'=>15,'style'=>'width:155px'])`
						~$form['city_res']->renderHelp()`
						<div id="city_res_err" style="width:240px !important;display:~if $form['city_res']->hasError()`inline~else`none~/if`;" for='reg_city_res' class='error err_msg'><label class="l1" id="splLbl">&nbsp;</label>~if $form['city_res']->hasError()`~$form['city_res']->getError()`~else` Please provide a city.~/if`</div>
					</div>
<!--OutSide India Starts-->
					<div class="fl">
						<label style="width:24px !important;padding-right:0px !important;">
							<input type="checkbox" value="1" name="outside_inda"  class="mr_4" tabindex="14" id="chk_outside_india" style="margin:-5px 4px 0px 0px;">
						</label>
						<div class="fl" style="font-size:10px;margin-top:-2px;width:46px;">Outside India</div>
					</div>
					
<!--OutSide India Ends-->					 					
					<div class="cl_5 clr"></div>
				</li>
				
				<li style="display:none">
				<br>
				~if $form['pincode']->hasError()`~$form['pincode']->renderLabel(null,['style'=>'color:red'])`~else`~$form['pincode']->renderLabel()`~/if`
				~$form['pincode']->render(['class' => 'txt1 genFormat','tabindex'=>16])`
				
				<div class="clr"></div>
			     <div id="pincode_err" style="display:~if $form['pincode']->hasError()`inline~else`none~/if`;" for='reg_pincode' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg"> ~$form['pincode']->getError()`</div>
				</div>
			    <div class="cl_5 clr"></div>
	       	</li>
	<!-- City Ends Here -->
	<!-- Country Living in starts from here -->
			<li id="country" >
				<div class="fl" id="country_hide_show" style="width:290px">
					~if $form['country_res']->hasError()`~$form['country_res']->renderLabel(null,['style'=>'color:red'])`~else`~$form['country_res']->renderLabel()`~/if`
				~$form['country_res']->render(['class'=>'sel_lng','tabindex'=>17,'style'=>'width:155px;float:left'])`
				~$form['country_res']->renderHelp()`
					<div id="country_res_err" style="width:258px !important;display:~if $form['country_res']->hasError()`inline~else`none~/if`;" for='reg_country_res' class='error err_msg'><label class="l1" id="splLbl1">&nbsp;</label>~if $form['country_res']->hasError()`~$form['country_res']->getError()`~else` Please provide a country.~/if`</div>
				</div>
			</li>
		<!-- Country Ends Here -->
		<div class="cl_5 clr"></div>
		</div>
      	<div class="clr"></div>
      	<br>
		<!-- Contact Number Starts Here -->
		<li id="li_add_phone" style="padding-bottom:3px;" >
				~if $form['phone_mob']->hasError()`~$form['phone_mob']->renderLabel(null,['style'=>'color:red'])`~else`~$form['phone_mob']->renderLabel()`~/if`
				~$form['phone_mob']->render(['mobile'=>['class'=>'phoneFormat ml_5 h17 txt2',maxlength=>'10','tabindex'=>19,onblur=>'ajax_leadi(\'M\')','onfocus'=>'show_help(this)','onblur'=>'hide_help(this)'],'isd'=>['class'=>'w36 h17 txt2','maxlength'=>'6','tabindex'=>18]])`

<!--
				~$form['showmobile']->render(['class'=>'mar5left w150','tabindex'=>19])`
-->
					<div class="clr cl_5"></div>
					<div id="verify_message_mobile" style="display:inline">
						
						
					</div>
					<div class="coverhelp fl">
					<div id="reg_phone_mob_mobile_help" class="helpbox helpFormat" style="width:214px;left:-230px;top:-38px;">
							<div class="blk no_b f11">
								You will receive an automated verification call on your number from Jeevansathi.com
								<div class="helpimg"></div>
						</div>
					</div>
					</div>
				 <div id="phon_err" style="padding-bottom:10px;display:~if $form['phone_mob']->hasError()`inline~else`none~/if`;" for='reg_phone_mob_mobile' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="cl5"></div>
					<div class="err_msg">~$form['phone_mob']->getError()`</div>
					<label class="l1">&nbsp;</label><div class="clr5"></div>
				</div>
				<div class="clr cl_5"></div>
				<!-- landline click-->

				<div class="clr"></div>

				<li id="phone_show" style="padding-bottom:0px;display:~if $PHONE_DISPLAY`inline;~else`none~/if`">
						~if $form['phone_res']->hasError()`~$form['phone_res']->renderLabel(null,['for'=>'reg_phone_res_landline','style'=>'color:red'])`~else`~$form['phone_res']->renderLabel(null,['for'=>'reg_phone_res_landline'])`~/if`
					~$form['phone_res']->render(['landline'=>['class'=>'w65 ml_5 h17 txt2','maxlength'=>'10','tabindex'=>22],'isd'=>['class'=>'w36 h17 txt2','maxlength'=>'6','tabindex'=>20],'std'=>['class'=>'w40 ml_5 h17 txt2','maxlength'=>'5','tabindex'=>21]])`
						~$form['showphone']->render(['class'=>'mar5left w150','tabindex'=>23])`
						
						<div id="verify_message_phone" style="display:inline">
							<label>&nbsp;</label>
							
						</div>
						<div class="clr"></div>
						 <div id="phone_res_err" style="display:~if $form['phone_res']->hasError()`inline~else`none~/if`;" for='reg_phone_res_landline' class='error'>
							<label class="l1">&nbsp;</label>
							<div class="err_msg">~$form['phone_res']->getError()`</div>
						</div>
				</li>
			</li>
			<!-- Contact Number Ends Here -->
        	<!-- Aggrement Starts Here -->
				<div class="fl">
						<input type="hidden" value="S" name="memb_mails">
						<input type="hidden" value="S"  name="memb_sms">
						<input type="hidden" value="S" name="memb_ivr">
				</div>
				<div class="cl_5 clr"></div>
<!--

				<div class="cl_5 clr"></div>	
				<div class="cl_5 clr"></div>
				<div class="cl_5 clr"></div>
-->
				
		<!-- Agreement Ends Here -->
		<!-- Terms And Condition Starts Here -->
		<div>
			<div class="floatLeft">
				<div class="chbxl" style="width:235px;font-size:11px;padding-left:10px;padding-top:9px;" >
				  <input type="checkbox" id="termsandconditions" name="termsandconditions" value="Y" checked="true"tabindex="24" /> &nbsp;&nbsp;I have agreed to the 
				<a target="_blank" href="~$SITE_URL`/P/disclaimer.php" class="brown">Terms &amp; Conditions</a> &amp; have read &amp; understood the 
				<a href="~$SITE_URL`/P/privacy_policy.php" target="_blank" class="brown">Privacy Policy</a>. 
				</div>
				 
			</div>
			<!-- Terms And Condition Ends Here -->	
			<!-- Submit Button-->
			<div class="floatLeft">
				<div align="center">
				<input type="hidden" name="submit_page1" value="submit">
				<input type="image" name="submit_pg1" value="submit" id="submit_pg1" src="~sfConfig::get(app_site_url)`/profile/images/registration_revamp_new/joinfree.png" border="0" style="margin-bottom:30px;width:118px;height:41px;border:0px" tabindex="25"/>
				</div>
			</div>
			
			<div class="clr"></div>
		</div>
	    <div id="termsandconditions_err" style="display:none;" for='termsandconditions' class='error'>
		</div>  
			
	      <div class="clr"></div>
	      <i class="d_b_r h_w_6 sprtereg cur_btm_left"></i><i class="d_b_l h_w_6 sprtereg cur_btm_right"></i>

</form>

<div class="clr cl21"></div>

	~if $GROUPNAME eq 'wchutney'`
		<script language="javascript" src="http://www.webchutney.net/chutneytrack/js/iqtracker.js"></script>
		<script language="javascript">
			var clntid = "JVNSTHI";
			trackThisPage();
		</script>
	~/if`

	~if $GROUPNAME eq 'Tyroo_India_JFM08' || $GROUPNAME eq 'Tyroo_NRI_JFM08'`
		<script type="text/javascript" src="http://tq.tyroo.com:8080/acquire/tyr_home.js"></script>
	~/if`
		
	~if $GROUPNAME  eq 'DrivePM_RI_JFM09'`
		<img src="http://switch.atdmt.com/action/Jeevansathi_RI_Landing_Feb09" height="1" width="1">	
	~/if`

	~if $GROUPNAME  eq 'DrivePM_NRI_JFM09'`
		<img src="http://switch.atdmt.com/action/Jeevansathi_NRI_Landing_Feb09" height="1" width="1">
	~/if`

	<script>
	</script>

	~if $TIEUP_SOURCE eq 'default59'`
			<script type="text/javascript">

			var google_conversion_id = 1056682264;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "666666";
			var google_conversion_label = "6cipCLSD5gEQmOLu9wM";
			var google_conversion_value = 0;

			</script>
			<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js"></script>
			<noscript>
				<div style="display:inline;">
					<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1056682264/?label=6cipCLSD5gEQmOLu9wM&amp;guid=ON&amp;script=0"/>
				</div>
			</noscript>
		<!-- Ends Here -->
	~/if`
<script type="text/javascript">
var crazyegg=0;
function crazyEggUserVar(){
if (window.CE2)
  {
    CE2.set(1,'~$TIEUP_SOURCE`');
  }
  else
  {
	if(crazyegg<100)
		setTimeout(arguments.callee, 100);
	crazyegg++;	
  }
}
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName('script')[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0011/8626.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
setTimeout(crazyEggUserVar,100);
</script>
<script type="text/javascript">

var user_login =1;
var mobileIds = [];
var phoneIds = [];
var dobIds = [];
var google_plus=0;
	var religionDefault="~$RELIGION`"?"~$RELIGION`":null;
	var casteDefault="~$CASTE`"?"~$CASTE`":null;
	var cityDefault="~$CITY_RES`"?"~$CITY_RES`":null;
	var countryDefault="~$COUNTRY_RES`"?"~$COUNTRY_RES`":51;
	

(function() {
    try {
        var viz = document.createElement('script');
        viz.type = 'text/javascript';
        viz.async = true;
        viz.src = ('https:' == document.location.protocol ?'https://ssl.vizury.com' : 'http://www.vizury.com')+ '/analyze/pixel.php?account_id=VIZVRM782';

        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(viz, s);
        viz.onload = function() {
            pixel.parse();
        };
        viz.onreadystatechange = function() {
            if (viz.readyState == "complete" || viz.readyState == "loaded") {
                pixel.parse();
            }
        };
    } catch (i) {
    }

})();

$(document).ready(function(){
	
	$("li#gender_padding").hide();
	onRelationShipChange();
	$("#caste").hide();
			
	setTimeout(function(){
		if($("#reg_country_res").val()==51){
				$("#reg_country_res").parent().parent().hide();
			}
			else{
				$("#chk_outside_india").trigger("click");
				k=$("#city_res_show_hide").next();
				z=$("#country");

				k.appendTo(z);
			}
		},1000);	
});
//OutSide India Check
var tempCountry="";
tempCountry=$("#reg_country_res").html();
var tempCity="";
tempCity = $("#reg_city_res").html();
$("#chk_outside_india").bind('click',function(){
	if($("#chk_outside_india").prop("checked"))
	{
		$("#reg_country_res option:selected").remove();
		
		$("#reg_country_res").val("51");
		$("#reg_country_res option:selected").remove();
		$("#reg_country_res").trigger("liszt:updated");
		$("#reg_country_res").parent().parent().show();
		
		$("#reg_city_res").val("");
		$("#reg_city_res").trigger("liszt:updated");
		$("#reg_city_res").parent().parent().hide();
		k=$("#city_res_show_hide").next();
		z=$("#country");

		k.appendTo(z);
	}		
	else
	{
			$("#reg_country_res").html(tempCountry);
			$("#reg_country_res").trigger("liszt:updated");
			$("#reg_city_res").html(tempCity);
			$("#reg_city_res").trigger("liszt:updated");
			$("#reg_city_res").parent().parent().show();
			
			
			$("#reg_country_res").val(51);
			$("#reg_country_res").trigger("liszt:updated");
			$("#reg_country_res").parent().parent().hide();
			k=$("#country").children(":last-child");
		
			$('#city_padding').children(':eq(0)').after(k);
	}		
});
//
//RelationShip

function onRelationShipChange()
{
	var value = $("#reg_relationship").val();
	
	if(value == 1 || value ==4 || value ==5 )
	{
		document.getElementById('reg_gender_F').checked = false;
		document.getElementById('reg_gender_M').checked = false;
		$("li#gender_padding").show();
	}
	else
	{ 
		$("li#gender_padding").hide();
		if(value == 2 || value == 6)
		{
			document.getElementById('reg_gender_F').checked = false;
			document.getElementById('reg_gender_M').checked = true;
		}
		else if(value == '2D' || value == '6D')
		{
			document.getElementById('reg_gender_M').checked = false;
			document.getElementById('reg_gender_F').checked = true;
		}
	}
}

$("#reg_relationship").change(function(){
	onRelationShipChange();
});
function callReligion(drpDown)
{
	var val = drpDown.value;
	$("#caste").hide();
	$("#caste_err_msg").html("Please provide a caste");
	
	if(val == 2 || val == 3 )
	{
		$("#caste_err_msg").html("Please provide a sect");
	}
	
	if(val <=4 || val==9)
	{
		$("#caste").show();
	}
}

function handleLoginLayer()
{
	window.location.href = "/static/logoutPage";
}

$("#mem_login").bind('click',function(){
	window.location.href = "/static/logoutPage";
	});
</script>
