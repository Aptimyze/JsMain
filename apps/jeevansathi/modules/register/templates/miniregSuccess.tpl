<script>
var mobileIds = [];
</script>
<div style="display:none">
~$errMsg|decodevar`
~$defaultMsg|decodevar`
</div>
	<div class="min_reg_cnt">
		  <a href="~$SITE_URL`"><h1 class="lgo" style="margin-left: 25px"></h1></a>
		  <p class="fr"> venture</p>
			<a href="https://www.naukri.com"><i class="nkri_lgo fr sprte_mini"></i></a>
		  <p class="fr">a </p>
		  <p class="clr"></p>
		  <div class="tp sprte_mini"> <i class="l sprte_mini"></i>
			    <p class="fl">
			    	~$HEADING|decodevar`
			    </p>
	    		    <i class="r sprte_mini"></i> 
		  </div>
          <div class="mid_cont">
			  ~if $IS_FTO_LIVE`
			  <div style="width:910px; margin-top:20px" class="fto-notification-box1">
				   <p style="color:#bc001d">Complete the form and 
				       Get Jeevansathi Paid Membership for <span style="font-family:WebRupee;color:#bc001d">R</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span><strong> FREE</strong> 
					   </p>
					   <div style=" height:5px"></div>
					   <p>See e-mail IDs &amp; Phone numbers of people you like.</p>
			 </div>
			 <div style="padding: 0 15px; width: 930px;" class="fl">
				 <div class="fto-arrow-down sprte1">&nbsp;</div>
				 <div class="fr" style="color:#7e7e7e; margin-top:5px">Field marked <i style="color:#ffaf00;">*</i> are compulsory. For offer details refer to Terms &amp; Conditions
				 </div>
			 </div>
			 ~/if`
	    	<div class="frm">

		 <form id='minireg' name="reg" action="~sfConfig::get('app_site_url')`/register/minireg" method="post" enctype="multipart/form-data">
  			~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		
			<ul class="miniform">
			<li> 
				~$form['email']->renderLabel()`
				~$form['email']->render(['class'=>'w222'])`
				<div id="email_err" style="display:~if $form['email']->hasError()`inline~else`none~/if`;" for='reg_email' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg"> ~$form['email']->getError()`</div>
				</div>
	 		</li>
			<div class="clr"></div>
			
	       <li> 
				~$form['phone_mob']->renderLabel()`
				~$form['phone_mob']->render(['mobile'=>['class'=>'ml_5','maxlength'=>'10','style'=>'width:178px;height:17px;',onblur=>'ajaxLeadCapture()'],'isd'=>['style'=>'width:36px;height:17px;','maxlength'=>'6']])`
				 <div id="phone_mob_err" style="display:~if $form['phone_mob']->hasError()`inline~else`none~/if`;" for='reg_phone_mob_mobile' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['phone_mob']->getError()`</div>
				</div>
	 		</li>
        	<li> 
				~$form['relationship']->renderLabel()`
				~$form['relationship']->render(['class'=>'w222 f11'])`
				<div id="relationship_err" style="display:~if $form['relationship']->hasError()`inline~else`none~/if`;" for='reg_relationship' class='error'>
					<label class="l1">&nbsp;</label>
					<div class="err_msg">~$form['relationship']->getError()`</div>
				</div>
	 		</li>
			<li> 
				~$form['dtofbirth']->renderLabel()`
				~$form['dtofbirth']->render(['day'=>['style'=>'width:71px;font:11px;'],'month'=>['style'=>'width:71px;font:11px;'],'year'=>['style'=>'width:71px;font:11px;']])`
				<div id="dtofbirth_err" style="display:~if $form['dtofbirth']->hasError()`inline~else`none~/if`;" class="error" for="date_of_birth">
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['dtofbirth']->getError()`</div>
				</div>
	 		</li>
			<li> 
				~if $form['mtongue']->hasError()`~$form['mtongue']->renderLabel(null,['style'=>'color:red'])`~else`~$form['mtongue']->renderLabel()`~/if`
				~$form['mtongue']->render(['class'=>'w222 f11'])`
				<div id="mtongue_err" style="display:~if $form['mtongue']->hasError()`inline~else`none~/if`;" for='reg_mtongue' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['mtongue']->getError()`</div>
				</div>
	 		</li>
 			</ul>
        		
            <p class="clr"></p>
            <div> <b style="float:left; width:155px;">&nbsp;</b><input type="submit" name="submit_mini" id="submit_mini" value="" class="fl sprte_mini reg_btn" style="width:162px;height:40px;border:0px;"/>
			        <br /><br /></div>
        	</form>
        	<p>
			<b class="f_13" style="width:auto;margin-left: 27px;"><br />Clicking on register free button means that you accept <a href="~$SITE_URL`/P/disclaimer.php" target="_blank">terms and conditions</a>
			</b>
		</p>
            <p class="clr"></p>
		<p style="text-align:center;padding:8px 0px 15px 0px;">Existing user - <a href="/profile/login.php?SHOW_LOGIN_WINDOW=1" >Login Here</a></p>
	        <p class="clr"></p>
    		</div>
                <div class="tst_mnl sprte_mini"><p class="l_qte sprte_mini"></p><blockquote>~$STORY`</blockquote><p class="r_qte sprte_mini"></p>
		</div>  		     
	       <p class="clr_15"></p>
	       <p class="phto ml_27" style="padding-bottom:0;">
			<b class="sprte_mini">&nbsp;</b>
			<u>Photo Protection<br />Features</u>
	       </p>
	       <p class="lck" style="padding:0 24px 3px 24px;">
			<b class="sprte_mini">&nbsp;</b>
			<u>Exclusive Privacy Options</u>
	       </p>
		<p class="phne" style="padding:0 0 0 24px; border:none;">
			<b class="sprte_mini">&nbsp;</b>
			<u>Verified Contact Numbers<br /><br /><br />
			</u>
	       </p>
		<p class="coup_img" style="position:realtive;background:url(~$COUP_IMAGE`) no-repeat;"></p>
	  	</div>
          	<p class="clr"></p>
		<p class="mid_b_c_l sprte_mini" style="float:left; margin:-20px 0 0; position:relative;"></p>
		<p class="mid_b_c_r sprte_mini" style="float:right; margin:-20px 13px 0 0; display:inline;"></p>
	</div>
	<p class="clr_15"></p>
	

	~$FOOT`

<noscript>
	<div style="position:fixed;z-index:1000;width:100%"><div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;"><b><img src="~$IMG_URL`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due to this certain functionalities will not work. <a href="~$SITE_URL`/P/js_help.htm" target="_blank">Click Here</a> , to know how to enable it.</b></div></div>
</noscript>

