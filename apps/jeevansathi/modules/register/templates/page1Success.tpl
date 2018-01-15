<script>
var user_login =1;
var mobileIds = [];
var phoneIds = [];
var dobIds = [];
</script>
<div style="display:none">
~$errMsg|decodevar`
~$defaultMsg|decodevar`
</div>
	<noscript>
		<div style="position:fixed;z-index:1000;width:100%">
			<div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;">
				<b><img src="~$IMG_URL`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due  to this certain functionalities will not work. 
					<a href="~$SITE_URL`/P/js_help.htm" target="_blank">Click Here</a> , to know how  to enable it.
				</b>
			</div>
		</div>
	</noscript>
	<p class="tp_bg sprtereg"></p>
	<div class="reg_cont">
	~include_partial("register/regHeader",[SITE_URL=>$SITE_URL,toll=>1,page=>1])`
	~if $REDIRECT_URL`
  	<div class="clr cl21"></div>
	<h4 class="drk_gry">Please login / Register to view ~$VIEW_USERNAME`'s profile</h4>
	
	<div class="login_bar mt_10 pos_rltv">
		<i class="d_t_l pos_abs h_w_6 sprtereg"></i>
		<i class="d_t_r pos_abs h_w_6 sprtereg p_tr_0"></i>

		<div style="width:745px; padding:15px 10px 0;">
			<form  name="form2" action="~$SITE_URL`/profile/login.php" method="post" style="margin:0;padding:0">
				<input type="hidden" name="REQUESTEDURL" value="~$REDIRECT_URL`">
				<input type="hidden" name="METHOD" value="GET">
				<input type="hidden" name="REGISTER" value=1>
				<input type="hidden" name="view_username" value="~$VIEW_USERNAME`">
				<input type="hidden" name="source" value="~$TIEUP_SOURCE`">
				<div class="fl f_17 orng b">Member's Login &gt;&gt; 
				</div>
				<div class="fl" style="margin-top:2px;">
					<div class="fl">
						<label><b>User Id / Email Id :</b></label>
						<input type="text" class="txt1" name=username></input>
					</div>

					<div class="fl" style="margin-left:5px;">
						<label><b>Password :</b></label>
						<span class="fl ml_5">
							<input type="password" class="txt1" name=reg_password></input>
						</span>
					</div>
					<div class="fl ml_5">
						<label style="margin:0">
						  <input type="checkbox" name="rememberme" value="Y" style="border:0px">
						  Remember Me
						</label>
					</div>
				</div>
				<input type="submit" name="Submit" value="Login" class="fr" >
			</form>
       		</div>
		~if $LOGIN_ERR`
		<div style="float:left;color: red; text-align: center; width: 100%;margin-left:-38px"><img
		src="http://www.jeevansathi.com/profile/images/registration_new/alert.gif" style="vertical-align:
		bottom;"/>Invalid username/password</div>
		~/if`
  	</div>
	~/if`

	<div class="clr cl_5"></div>	
		~if !$IS_FTO_LIVE`
	<div class="mt_10" style="_margin-top:-1px;text-align:left;" >
		 <h3 class="drk_gry" style="font-size:18px;">
			 Connect with Men, Women & their Families for Marriage. Create your Account for Free!
		 </h3>
		 
	 </div>
		 ~/if`
	<div class="fl mt_10" style="width:539px;_margin-top:-1px;" >
		<div class="fr drk_gry mt_6"> All fields are mandatory </div>
        <div class="clr cl_5"></div>
	<form id="reg" name="form1" action="~$SITE_URL`/register/page1" method="post" enctype="multipart/form-data" style="margin: 0px">
		~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
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
		<input type="hidden" name="source" value="~$source`" >
		<input type="hidden" name="affiliateid" value="~$sf_request->getParameter('affiliateid')`"/>
		<input type="hidden" name="id" value="~$ID_AFF`" >
		<input type="hidden" name="leadid" id ="leadid" value="~$leadid`"  >
		<input type="hidden" name="secondary_source" value="~$sf_request->getParameter('secondary_source')`" >
		~if $TIEUP_SOURCE eq 'ofl_prof'`
			<input type="hidden" name="email_is_ok" id="email_is_ok" value="1" >
		~else`
			<input type="hidden" name="email_is_ok" id="email_is_ok" value="" >
		~/if`
    	<div class="main_form_cont pos_rltv" style="width:538px;float:left" >
 		
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
		<ul class="form" style="margin-top:5px;" id="email_section">
			<li>
			    <h2>Account Details</h2>
			</li>
			<li>
				~if $form['email']->hasError()`~$form['email']->renderLabel(null,['style'=>'color:red'])`~else`~$form['email']->renderLabel()`~/if`
				~$form['email']->render(['maxlength'=>'100','class' => 'txt1' ,'onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','tabindex'=>1])`
				<div class="coverhelp cvr_hlp fl">
         			<div id="reg_email_help" class="helpbox pd_5" style="left: 15px; z-index: 110; display: none;">
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

			<li>
				~if $form['password']->hasError()`~$form['password']->renderLabel(null,['style'=>'color:red'])`~else`~$form['password']->renderLabel()`~/if`
				~$form['password']->render(['maxlength'=>'40','class'=>'txt1','onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','tabindex'=>2, 'maxlength'=>'40'])`
				<div class="coverhelp cvr_hlp fl">
					<div id="reg_password_help" class="helpbox pd_5" style="left: 15px; z-index: 110; display: none;">
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

			<li>
				~if $form['relationship']->hasError()`~$form['relationship']->renderLabel(null,['style'=>'color:red'])`~else`~$form['relationship']->renderLabel()`~/if`
				~$form['relationship']->render(['class'=>'sel_lng','tabindex'=>3])`
			    <div id="relationship_err" style="display:~if $form['relationship']->hasError()`inline~else`none~/if`;" for='reg_relationship' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['relationship']->getError()`</div>
				</div>
	       		</li>
			<!-- Relationship Ends Here -->
     		</ul>

		<!-- Email Section Ends Here-->

      		<div class="clr"></div>
			<div style="width:96%">
    		<ul class="form mt_10" id="basicInfo_section">
				<li>
					<h2 id="personal_heading">Basic Profile Details</h2>
				</li>
				
			<!-- Gender Starts Here -->
				<li id="gender_padding">
				<div id="gender_section">
					~if $form['gender']->hasError()`~$form['gender']->renderLabel(null,['style'=>'color:red'])`~else`~$form['gender']->renderLabel()`~/if`
					~$form['gender']->render(['tabindex'=>4])`
					 <div id="gender_err" style="display:~if $form['gender']->hasError()`inline~else`none~/if`;" for='reg[gender]' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['gender']->getError()`</div>
					</div>
				</div>
      			</li>
		<!-- Gender Ends Here -->
		<!-- DOB Starts Here -->
	        	<li style="padding-bottom:21px;">
				<div id="year_span_id">
					~if $form['dtofbirth']->hasError()`~$form['dtofbirth']->renderLabel(null,['style'=>'color:red'])`~else`~$form['dtofbirth']->renderLabel()`~/if`
					~$form['dtofbirth']->render(['day'=>['onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','class'=>'w57 fl','tabindex'=>5],'month'=>['onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','class'=>'fl ml_10 w68','tabindex'=>6],'year'=>['onfocus'=>'show_help(this)','onblur'=>'hide_help(this)','class'=>'fl ml_10 w62','tabindex'=>7]])`
				</div>
					<div class="coverhelp fl cvr_hlp">
						<div id="reg_dtofbirth_day_help" class="helpbox pd_5" style="left: 13px; display: none;">
							<div class="blk no_b f11">
								Please remember that the date of birth, once entered cannot be changed.
								<div class="helpimg"></div>
							</div>
						</div>
					 </div>
					 <div class="coverhelp fl cvr_hlp">
						<div id="reg_dtofbirth_month_help" class="helpbox pd_5" style="left: 13px; display: none;">
							<div class="blk no_b f11">
								Please remember that the date of birth, once entered cannot be changed.
								<div class="helpimg"></div>
							</div>
						</div>
					 </div>
					 <div class="coverhelp fl cvr_hlp">
						<div id="reg_dtofbirth_year_help" class="helpbox pd_5" style="left: 13px; display: none;">
							<div class="blk no_b f11">
								Please remember that the date of birth, once entered cannot be changed.
								<div class="helpimg"></div>
							</div>
						</div>
					 </div>
					 <div id="dtofbirth_err" style="display:~if $form['dtofbirth']->hasError()`inline~else`none~/if`;" class="error" for="date_of_birth">
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['dtofbirth']->getError()`</div>
					</div>
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
		<!-- Country Living in starts from here -->
				<li>
					~if $form['country_res']->hasError()`~$form['country_res']->renderLabel(null,['style'=>'color:red'])`~else`~$form['country_res']->renderLabel()`~/if`
					~$form['country_res']->render(['class'=>'sel_lng','tabindex'=>9])`
					~$form['country_res']->renderHelp()`
					 <div id="country_res_err" style="display:~if $form['country_res']->hasError()`inline~else`none~/if`;" for='reg_country_res' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['country_res']->getError()`</div>
					</div>
				</li>
		<!-- Country Ends Here -->

		<!-- City Starts from here -->
				<li id="city_padding">
					<div id="city_res_show_hide">
						~if $form['city_res']->hasError()`~$form['city_res']->renderLabel(null,['style'=>'color:red'])`~else`~$form['city_res']->renderLabel()`~/if`
						~$form['city_res']->render(['class'=>'sel_lng','tabindex'=>10])`
						~$form['city_res']->renderHelp()`
					 <div id="city_res_err" style="display:~if $form['city_res']->hasError()`inline~else`none~/if`;" for='reg_city_res' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~if $form['city_res']->hasError()`~$form['city_res']->getError()`~else` Please provide a city.~/if`</div>
					</div>
					</div>
				</li>
				<li style="display:none">
				~if $form['pincode']->hasError()`~$form['pincode']->renderLabel(null,['style'=>'color:red'])`~else`~$form['pincode']->renderLabel()`~/if`
				~$form['pincode']->render(['class' => 'txt1','tabindex'=>11])`
				
				<div class="clr"></div>
			     <div id="pincode_err" style="display:~if $form['pincode']->hasError()`inline~else`none~/if`;" for='reg_pincode' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg"> ~$form['pincode']->getError()`</div>
				</div>
			    <div class="clr"></div>
	       	</li>

			</ul>
		<div class="clr"></div>
		</div>
      	<div class="clr"></div>
		<!-- City Ends Here -->

		<ul class="form mt_10" style="_margin-top:10px;">
			   	
		

			<!-- New Marital Status -->
				<li>
					~if $form['mstatus']->hasError()`~$form['mstatus']->renderLabel(null,['style'=>'color:red'])`~else`~$form['mstatus']->renderLabel()`~/if`
					~$form['mstatus']->render(['class'=>'sel_lng','tabindex'=>12])`
					 <div id="mstatus_err" style="display:~if $form['mstatus']->hasError()`inline~else`none~/if`;" for='reg_mstatus' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['mstatus']->getError()`</div>
					</div>
					<div class="clr"></div>
				</li>
			<!-- Ends Here -->
			<!-- New Have Children Section Starts Here -->
			<li style="padding:0;">
				<div id="have_child_section" style="display:none;padding-bottom:15px!important; float:left;">
					~if $form['havechild']->hasError()`~$form['havechild']->renderLabel(null,['style'=>'color:red'])`~else`~$form['havechild']->renderLabel()`~/if`
					~$form['havechild']->render(['class'=>'sel_lng','tabindex'=>13])`
					 <div id="havechild_err" style="display:~if $form['havechild']->hasError()`inline~else`none~/if`;" for='reg_havechild' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['havechild']->getError()`</div>
					</div>
				</div>
			</li>
		
		<!-- Mother Tongue starts from here -->	
	        <li>
					~if $form['mtongue']->hasError()`~$form['mtongue']->renderLabel(null,['style'=>'color:red'])`~else`~$form['mtongue']->renderLabel()`~/if`
					~$form['mtongue']->render(['class'=>'sel_lng','tabindex'=>14,'onfocus'=>'show_help(this)','onblur'=>'hide_help(this)'])`
					 <div class="coverhelp fl">
					 <div id="reg_mtongue_help" class="helpbox pd_5" style="left: 240px; display: none;">
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
			</li>
		<!-- Mother Tongue Ends Here -->        

		<!-- Religion Starts Here -->
		<li>
					~if $form['religion']->hasError()`~$form['religion']->renderLabel(null,['style'=>'color:red'])`~else`~$form['religion']->renderLabel()`~/if`
					~$form['religion']->render(['class'=>'sel_lng','tabindex'=>15])`
					 <div id="religion_err" style="display:~if $form['religion']->hasError()`inline~else`none~/if`;" for='reg_religion' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~if $form['religion']->hasError()`~$form['religion']->getError()`~else`Please provide a religion.~/if`</div>
					</div>
	    </li>
		
		<!-- Religion Ends Here -->
			
		<!-- Caste Starts Here -->
	        <li>
				<div id="caste_submit_err_label" >
					<span id="caste_section"> 
						<label id="caste_label_muslim" style="display:none" class="l1">Sect :</label>
						<label id="caste_label_christian" style="display:none" class="l1">Sect :</label>
						<label id="caste_label_hindu" class="l1">Caste :</label>
						
							~$form['caste']->render(['class'=>'sel_lng','tabindex'=>16])`
							
						 <div id="caste_err" style="display:~if $form['caste']->hasError()`inline~else`none~/if`;" for='reg_caste' class='error'>
							<label class="l1">&nbsp;</label>
							<div class="err_msg" id="caste_error_msg">~if $form['caste']->hasError()`~$form['caste']->getError()`~else`Please provide a caste.~/if`</div>
						</div>
					</span>
				</div>
	        </li>
		</ul>
		<div class="clr"></div>
		<!-- Caste Ends Here -->

		<!-- Ends Here -->
		<ul class="form mt_10" style="_margin-top:10px;">
    		
	
			<!-- Contact Number Starts Here -->
			<li id="li_add_phone" style="padding-bottom:3px;" >
					~if $form['phone_mob']->hasError()`~$form['phone_mob']->renderLabel(null,['style'=>'color:red'])`~else`~$form['phone_mob']->renderLabel()`~/if`
					~$form['phone_mob']->render(['mobile'=>['class'=>'w110 ml_5 h17 txt2',maxlength=>'10','tabindex'=>18,onblur=>'ajax_leadi(\'M\')'],'isd'=>['class'=>'w36 h17 txt2','maxlength'=>'6','tabindex'=>17]])`

					~$form['showmobile']->render(['class'=>'mar5left w150','tabindex'=>19])`
						<div class="clr cl_5"></div>
						<div id="verify_message_mobile" style="display:inline">
							
							
						</div>
						<div class="clr cl_5"></div>
						
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
						~$form['phone_res']->render(['landline'=>['class'=>'w65 ml_5 h17 txt2','maxlength'=>'10','tabindex'=>23],'isd'=>['class'=>'w36 h17 txt2','maxlength'=>'6','tabindex'=>21],'std'=>['class'=>'w40 ml_5 h17 txt2','maxlength'=>'5','tabindex'=>22]])`
							~$form['showphone']->render(['class'=>'mar5left w150','tabindex'=>24])`
							
							<div id="verify_message_phone" style="display:inline">
								<label>&nbsp;</label>
								
							</div>
							<div class="clr"></div>
							 <div id="phone_res_err" style="display:~if $form['phone_res']->hasError()`inline~else`none~/if`;" for='reg_phone_res_landline' class='error'>
								<label class="l1">&nbsp;</label>
								<div class="err_msg">~$form['phone_res']->getError()`</div>
							</div>
					</li>
					
					<li>
						 <!--start:line-->
                <div class="fl" style="padding-left:175px;">
                	<div>
                    	<div class="fl">
                        	<div class="ftblc_phntxt fl ncolgrey">
                            	Phone number verification is mandatory 
                            </div>
                            <div class="fl posrel_phn" id="phn_popup">
                            	<a>
                                    <div class="helpicon_phn"></div>
                                    <!--start:popup-->
                                    <div class="popup_phn">
                                        <div class="phnpopbrdr posrel_phn">
                                            <div class="popphnarowpos">
                                                <div class="popphnarow"></div>
                                            </div>
                                            <div class="phnbrpad5">
                                            To provide a secure experience & have genuine members in Jeevansathi, we have made phone verification mandatory for all. After completing your registration, you may verify your number by giving a missed call on the phone number (toll free) mentioned in the verification screen.
                                            </div>
                                        </div>                                
                                    </div> 
                                    <!--end:popup--> 
                                </a>                        
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <!--end:line-->
                    <div class="clr"></div>
                </div>
                <div id="add_phone" style="float: left; padding-top:5px;padding-left:175px;color:blue;text-decoration:underline;cursor:pointer;display:~if $PHONE_DISPLAY`none~else`inline~/if`;">
							Add Landline Number
					</div>
					<div id="remove_phone" style="float: left;display:~if $PHONE_DISPLAY`inline~else`none~/if`;padding-left: 175px;padding-top:5px;color:blue;text-decoration:underline;cursor:pointer;">
							Remove Landline Number
					</div>
					</li>
			<!--landline end-->
			<!-- Contact Number Ends Here -->
		</ul>
		<style>
			.chbxl {float:left; font-size:11px;  text-align:left; padding-right:5px;
			color:#231f20;font-weight:normal;}
		</style>
		<style>
			ul.formnew li label{float:left; font-size:11px; width:190px; text-align:left; padding-right:5px;
			color:#231f20;font-weight:normal;}
		</style>
        	<!-- Aggrement Starts Here -->
				<div class="cl_5 clr"></div>
				<div class="cl_5 clr"></div>
		<ul style="margin-left:10px;width:480px;_margin-top:0px;" class="formnew">
			<div class="cl_5 clr"></div><div class="cl_5 clr"></div><div class="cl_5 clr"></div>
			<li style="display:none">
				<div class="fl">
					<label>
						<input type="checkbox" value="S" name="service_email"  class="mr_4" tabindex="24" checked>Receive email alerts
					</label>
					
					<label>
						<input type="checkbox" value="S" name="promo_email" class="mr_4" tabindex="25" checked >Receive promotional mails
					</label>
					
				</div>
				<div class="cl_5 clr"></div>
				<div class="cl_5 clr"></div>
			</li>
			<li style="display:none">
				<div class="fl">
					<label>
						<input type="checkbox" value="S" name="service_sms" class="mr_4" tabindex="26" checked>Receive SMS alerts
					</label>
					
					<label>
						<input type="checkbox" value="S" name="service_call" class="mr_4" tabindex="27" checked>Receive membership calls
					</label>
					
				</div>
				<div class="cl_5 clr"></div>
	
			</li>
		
        	<!-- Aggrement Starts Here -->
		
			<li>
			

				<div class="fl">
						<input type="hidden" value="S" name="memb_mails">
						<input type="hidden" value="S"  name="memb_sms">
						<input type="hidden" value="S" name="memb_ivr">
				</div>
				<div class="cl_5 clr"></div>
				<div class="chbxl" style="color:rgb(94, 82, 82)">
				We may reach out to you to explain the website, and our membership benefits through service calls, sms and emails.
				</div>
				<div class="cl_5 clr"></div>
				<div class="cl_5 clr"></div>
				
					 <div class="chbxl">
				          	  <input type="checkbox" id="termsandconditions" name="termsandconditions" value="Y" checked="true"/> &nbsp;&nbsp;I have agreed to the 
						  <a target="_blank" href="~$SITE_URL`/P/disclaimer.php" class="brown">Terms &amp; Conditions</a> and have read and understood the 
						  <a href="~$SITE_URL`/P/privacy_policy.php" target="_blank" class="brown">Privacy Policy</a>. 
					  </div>
							 <div id="termsandconditions_err" style="display:none;" for='termsandconditions' class='error'>
							</div>
				<div class="cl_5 clr"></div>	
				<div class="cl_5 clr"></div>
				<div class="cl_5 clr"></div>
				
			</li>
			<div class="cl_5 clr"></div>
			<div class="cl_5 clr"></div>
		</ul>
		<!-- Agreement Ends Here -->
		
	      <div align="center" style="padding-bottom:20px;">
				<input type="hidden" name="submit_page1" value="submit">
				<input type="submit" name="submit_pg1" value="Submit" id="submit_pg1" class="nsubbtn" border="0"/>
	      </div>
	      <div class="clr"></div>
	      <i class="d_b_r h_w_6 sprtereg cur_btm_left"></i><i class="d_b_l h_w_6 sprtereg cur_btm_right"></i>
   </div>
</form>
</div>
<!-- Side Success Story option starts from here -->

<div id="xy" style="width:235px; margin-top:35px;float:left;" class="fr">

	 ~include_partial("register/rightpanel")`
	
</div>

<!-- Ends Here -->

</div>
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
	~if $pixelcode`
	~$pixelcode|decodevar`
	~/if`
	<script>
		//onload_events();
		//Behaviour.apply();
	</script>

	~if $TIEUP_SOURCE eq 'default59'`
		<!-- Changes done as per Mantis 5339 -->
			<!-- Google Code for Default_Page1 Remarketing List -->
			<script type="text/javascript">
			/* <![CDATA[ */
			var google_conversion_id = 1056682264;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "666666";
			var google_conversion_label = "6cipCLSD5gEQmOLu9wM";
			var google_conversion_value = 0;
			/* ]]> */
			</script>
			<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js"></script>
			<noscript>
				<div style="display:inline;">
					<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1056682264/?label=6cipCLSD5gEQmOLu9wM&amp;guid=ON&amp;script=0"/>
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
</script>
