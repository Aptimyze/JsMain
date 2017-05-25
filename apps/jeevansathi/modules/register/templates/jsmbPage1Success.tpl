<script>var appPromo=1;</script>
<div style="display:none">
~$errMsg|decodevar`
</div>
<div class="bodyCon">
  <section class="pageHdCont">
    <p class="pageHd">Register your Profile<span>All Fields are mandatory</span></p>
  </section>
	<form id="reg" name="form1" action="" method="post" enctype="multipart/form-data" style="margin: 0px">
		~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		<input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`" >
		<input type="hidden" name="fname" value="~$NAME`" >
		<input type="hidden" name="account" value="~$account`" >
		<input type="hidden" name="campaign" value="~$campaign`" >
		<input type="hidden" name="adgroup" value="~$adgroup`" >
		<input type="hidden" name="keyword" value="~$keyword`">
		<input type="hidden" name="match" value="~$match`" >
		<input type="hidden" name="lmd" value="~$lmd`" >
		<input type="hidden" name="groupname" value="~$GROUPNAME`">
		<input type="hidden" name="secondary_source" value="~$sf_request->getParameter('secondary_source')`">
  <section class="wrap">
         <article class="formRow">
			~$form['email']->renderLabel(null,['class'=>"lblStyl"])`
			~$form['email']->render(['maxlength'=>'100'])`
			 <div id="email_err" style="display:~if $form['email']->hasError()`block~else`none~/if`;" for='reg_email' class='error'>
				<span class="err_msg">
				<small></small>
				~$form['email']->getError()`
				</span>
			</div>
			<div class="clr"></div>
      </article>
      <article class="formRow">
			~$form['password']->renderLabel("Password~if $operaMini` (Min 8 characters)~/if`",['class'=>"lblStyl"])`
			<div style="position:relative">
			~$form['password']->render(['maxlength'=>'40'])`
			<span class="showhide" id="showHide" style="display: block; background: white;">Show</span></div>
			 <div id="password_err" style="display:~if $form['password']->hasError()`block~else`none~/if`;" class='error' for='reg_password'>
				<span class="err_msg">
				<small></small>
				~$form['password']->getError()`
				</span>
			</div>
			<div class="clr"></div>
      </article>
      <article class="formRow num">
			~$form['phone_mob']->renderLabel(null,['class'=>"lblStyl"])`
			<div class="mbNum">
			~$form['phone_mob']->render(['mobile'=>['class'=>'num2','maxlength'=>'10'],'isd'=>['class'=>'num1','maxlength'=>'6']])`
			</div>
			<div class="clr cl_5"></div>
		 <div id="phone_mob_err" style="display:~if $form['phone_mob']->hasError()`block~else`none~/if`;" for='reg_phone_mob_mobile' class='error'>
			<span class="err_msg">	
			<small></small>
			~$form['phone_mob']->getError()`
			</span>
		</div>
      </article>
      <article class="formRow">
		~$form['relationship']->renderLabel(null,['class'=>"lblStyl"])`
		~$form['relationship']->render(['class'=>"mob"])`
		<div style="clear:both"></div>
		<div id="relationship_err" style="display:~if $form['relationship']->hasError()`block~else`none~/if`;" for='reg_relationship' class='error'>
			<span class="err_msg">	
			<small></small>
			~$form['relationship']->getError()`
			</span>
		</div>
		<div style="clear:both"></div>
      </article>
      <article class="formRow grid-col-3" id="gender_section">
					~$form['gender']->renderLabel(null,['class'=>"lblStyl"])`
					~$form['gender']->render(['class'=>'gender_style'])`
					 <div id="gender_err" style="display:~if $form['gender']->hasError()`block~else`none~/if`;" for='reg[gender]' class='error'>
					<span class="err_msg">	
					<small></small>
						~$form['gender']->getError()`
					</span>
					</div>
      </article>
      <article class="formRow">
					~$form['dtofbirth']->renderLabel(null,['class'=>"lblStyl"])`
					<div class="birth">
					~$form['dtofbirth']->render(['day'=>['class'=>'mob w57 sel_sml fl birth','not100'=>3],'month'=>['onfocus'=>'show_help(this)','not100'=>3,'style'=>'width:100%;'],'month'=>['class'=>'mob sel_sml fl ml_10 w62 birth','not100'=>3],'year'=>['class'=>'mob sel_sml fl ml_10 w57 birth_last','not100'=>3,'style'=>'margin:0px']])`
					</div>
					<div style="clear:both"></div>
					 <div id="dtofbirth_err" style="display:~if $form['dtofbirth']->hasError()`block~else`none~/if`;" class="error" for="date_of_birth">
				<span class="err_msg"><small></small>
					~$form['dtofbirth']->getError()`
				</span>
					</div>
				<div style="clear:both"></div>
      </article>
      <article class="formRow">
		~$form['mtongue']->renderLabel(null,['class'=>"lblStyl"])`
		~$form['mtongue']->render(['class'=>"mob"])`
		<div style="clear:both"></div>
		 <div id="mtongue_err" style="display:~if $form['mtongue']->hasError()`block~else`none~/if`;" for='reg_mtongue' class='error'>
				<span class="err_msg"><small></small>
			~$form['mtongue']->getError()`</span>
		</div>
		<div style="clear:both"></div>
      </article>
  </section>
<section class="terms" style="color:rgb(94, 82, 82)">
 We may reach out to you to explain the website, and our membership benefits through service calls, sms and emails.
                                </section>
  <section class="terms">
  By creating profile, I accept <a class="t_c" href="~$SITE_URL`/P/disclaimer.php">terms &amp; Conditions</a>
  </section>
  <section class="wrapper">
    <input name="jsmbPage1_submit" type="submit" class="btnM" value="Continue"/>
  </section>
  </form>
</div>

