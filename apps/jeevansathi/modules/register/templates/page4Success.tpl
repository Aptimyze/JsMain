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

<form name=form1 id="reg" action='/register/page4'  method="POST" style="margin:0;padding:0">
		~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
<input type="hidden" name="record_id" value="~$sf_request->getParameter('record_id')`" />
<input type="hidden" name="country_residence" value="~$COUNTRY_RESI`" />
<input type="hidden" name="sem" value="~$sem`" />
<input type="hidden" name="img_url" value="~$SITE_URL`/profile/images">

<p class="tp_bg sprtereg"></p>
<div class="reg_cont">
  ~include_partial("register/regHeader",[SITE_URL=>$SITE_URL])`
	<div> <span class="lt_gry">Social & Family Background</span> <span class="b">&gt;</span> Education & Professional Background <span class="b">&gt;</span> <span class="lt_gry">Desired Partners’ Profile </span></div>
	<div class="clr cl_10"></div>
	<h4 class="drk_gry ntxtleft">Enter these details to receive maximum responses from others</h4>
  
	
  <div class="fl mt_10" style="width:770px;">
    <div class="clr cl_5"></div>
    <div class="main_form_cont pos_rltv" style="width:770px;"> 
~if $IS_FTO_LIVE`
	<div class="fto-notification-box box3">
		<p style="color:#bc001d">Complete the form and 
			 Get Jeevansathi Paid Membership for <span style="font-family:WebRupee; color:#bc001d">&#8377;</span><span style=" text-decoration: line-through; color:#000 "><span style="color:#bc001d">1100</span></span><strong> FREE</strong> 
		</p>
		<div style=" height:5px"></div>
		<p>See e-mail IDs / Phone numbers of people you like.</p>
	</div>
	<div style="width:100%" class="fl">
			<div class="fto-arrow-down sprtereg">&nbsp;</div>
	</div>
	<div class="clr cl_10"></div>
~/if`


      <ul class="form" style="margin-top:5px;">
		<li>
		  <h2>Your Education &amp; Professional Details</h2>
		</li>
		<li>
		  ~$form['education']->renderLabel()`
		  <span class="lt_gry">Write about your educational qualifications, place of study etc.</span>
		  <div class="clr"></div>
		  <label>&nbsp;</label>
		  ~$form['education']->render(['maxlength'=>'1000','class'=>'w304 h52'])`
		  ~$form['education']->renderError()`
		</li>
		<li>
		  ~$form['work_status']->renderLabel()`
		  ~$form['work_status']->render(['class'=>'w245'])`
		  ~$form['work_status']->renderError()`
		</li>
		<li class="bot_bdr">
		  ~$form['job_info']->renderLabel()`
		  <span class="lt_gry">Write about your current and past work experience.</span>
		  <div class="clr"></div>
		  <label>&nbsp;</label>
		  ~$form['job_info']->render(['maxlength'=>'1000','class'=>'w304 h52'])`
		  ~$form['job_info']->renderError()`
		</li>

		</ul>
		<div class="clr"></div>
		<ul class="form" style="margin-top:5px;">
		<li>
		  <h2>Additional Details About You</h2>
		</li>
		<li>
		 ~$form['blood_group']->renderLabel()`
		  ~$form['blood_group']->render(['class'=>'w64'])`
		  ~$form['blood_group']->renderError()`
		</li>
		<li>
		 ~$form['hiv']->renderLabel()`
		  ~$form['hiv']->render()`
		  ~$form['hiv']->renderError()`
		<li>
		  ~$form['handicapped']->renderLabel()`
		  ~$form['handicapped']->render(['class'=>'w245'])`
		  ~$form['handicapped']->renderError()`
		</li>
		<li >
			<div id="nature_handi" style="display:none">
			  ~$form['nature_handicap']->renderLabel()`
			  ~$form['nature_handicap']->render(['class'=>'w245'])`
			  ~$form['nature_handicap']->renderError()`
			</div>
		</li>
        <li>
          <label>Spoken languages :</label>
          <div class="fl w_202 no_b blk"> <span class="fl f_11">Select Items</span><span class="fr f_11"><a id="language_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blue" style="cursor:pointer">Select All</a></span>
            <div class="clr"></div>
		<div class="lf scrollbox3 t12">
		         <div style="display:none" id="language_div">
                	<input type="hidden" name="language_str" id="language_str" value="~$LANGUAGEstr`">
	                ~foreach from=$LANGUAGE item=val`
        	        <input type="checkbox" value="~$val.VALUE`" name="language_arr[]" id="language_~$val.VALUE`"><label id="language_label_~$val.VALUE`">~$val.LABEL`</label><br>
                	~/foreach`
		        </div>
			<div class="clear all"></div>
	        	<div id="language_source_div" class="gry_bdr pl_2 chk_box_cont">
        	        	~foreach from=$LANGUAGE item=val`
        	        	
	                	&nbsp;<input id="language_displaying_~$val.VALUE`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$val.VALUE`" name="language_displaying_label_arr[]"><label id="language_displaying_label_~$val.VALUE`" >&nbsp;~$val.LABEL`</label><br>
		                ~/foreach`
        		</div>
		</div>

          </div>
          <div class="fl w_202 no_b blk ml_10"> <span class="fl f_11">Selected Items</span><span class="fr f_11"><a id="language_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blue" style="cursor:pointer">Clear All</a></span>
            <div class="clr"></div>
		<div  id="language_target_div"  class="gry_bdr pl_2 chk_box_cont">
			<div id="language_DM"><label>Doesn't Matter</label></div>
		</div>
          </div>
        </li>
        <li>
			<div>
			~$form['messenger_id']->renderLabel()`
			~$form['messenger_id']->render(['maxlength'=>'50','maxlength'=>'255','style'=>'width:215px;'])`
			
          <span style="margin:0 5px;">@</span>
			
			~$form['messenger_channel']->render(['class'=>'w112','style'=>'height:21px;'])`
			</div>
			<div class="clr clr5"></div>
			<div style="padding-left: 0px;float:left" for='reg_messenger_id' class='error'>
			<label class="l1">&nbsp;</label>
			<div class="err_msg" style="display:~if $form['messenger_id']->hasError()`inline~else`none~/if`;width:200px"  id="messenger_id_err" >~$form['messenger_id']->getError()`</div>
			</div>
			
			<div id="messenger_channel_err" style="display:~if $form['messenger_channel']->hasError()`inline~else`none~/if`;padding-left: 53px;float:left" for='reg_messenger_channel' class='error'>
			<div class="err_msg"  style="width:232px">~$form['messenger_channel']->getError()`</div>
			</div>
 
      <!-- Error code 4 specifies that it is a messenger channel error -->
	  

          <div class="clr cl_5"></div>
          <label>&nbsp;</label>
          ~$form['showmessenger']->render()`
		  ~$form['showmessenger']->renderError()` </li>
        <li>
            ~$form['contact']->renderLabel()`
			~$form['contact']->render(['maxlength'=>'1000','class'=>'w304 h52'])`
			~$form['contact']->renderError()`
          <div class="clr cl_5"></div>
          <label>&nbsp;</label>
			~$form['showaddress']->render()`
			~$form['showaddress']->renderError()`
          </li>
        <li> <div class="fl">

	<input type="hidden" name="submit_page4" value="Submit">
	<input name=Submit_pg4 type="submit" value="Save & Continue" class="nsubbtn" border="0" style="margin-bottom:30px;margin-left:195px; display:inline;" ></input> </div>
~if !$RECORD_ID`
<div class="fl"><a href="#" onclick="return submit_skip()" class="fl ml_10 f_13" style="color:#057ec3;"><br />
          Skip to Next page</a> </div> ~/if`</li>
      </ul>
      <div class="clr"></div>
<i class="d_b_r h_w_6 sprtereg cur_btm_left"></i><i class="d_b_l h_w_6 sprtereg cur_btm_right"></i>
    </div>
  </div>
</div>
<div class="clr cl21"></div>
</form>
<Script>
	$(document).ready(function(){
			var hoin = new Array("language");
			fill_details(hoin);
	});
function submit_skip()
{
	document.form1.action = document.form1.action+'?skip_to_next_page5=1';document.form1.submit();
	return false;
}
</Script>

