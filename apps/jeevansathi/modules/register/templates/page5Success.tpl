<script>
var page = "REG";
var docF = document.form1;
</script>
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
	~include_partial("register/regHeader",[SITE_URL=>$SITE_URL])`
	<div> <span class="lt_gry">Social & Family Background</span> <span class="b">&gt;</span> <span class="lt_gry">Education & Professional Background</span> <span class="b">&gt;</span> Desired Partner's Profile</div>
	 <div class="clr cl_10"></div>
	<h4 class="drk_gry ntxtleft" style="font-size:16px;">Complete these details to receive relevant match recommendations from us</h4>
 
	
	<div class="fl mt_10" style="width:770px;">
		<div class="clr cl_5"></div>
		<div class="main_form_cont pos_rltv" style="width:770px;"> 
~if FTOLiveFlags::IS_FTO_LIVE`
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
			
			<form  id="reg" name="form1" method="post" action="/register/page5" style="margin: 0px" >
						~foreach from=$form item=field`
			~if $field->isHidden()`
				~$field->render()`
			~/if`
		~/foreach`
		<input type="hidden" name="REG_P6" id="REG_P6" value="~$REG_P6`">
<input type='hidden' name='img_url' value="~sfConfig::get('app_img_url')`/profile/images/">
<input type='hidden' name='tieup_source' id='tieup_source' value="">
<input type="hidden" name="record_id" value="~$RECORD_ID`">
			<ul class="form" style="margin-top:5px;">
			<li>
				<h2 class="ndispin">Your Partner Preferences</h2>
				<div id="count" class="fr b">&nbsp;</div>
			</li>
			<li>
				~$form['p_lage']->renderLabel()`
				~$form['p_lage']->render([class=>"sel_sml fl"])` 
				<span class="fl" style="margin:2px 10px;">to</span> 
				~$form['p_hage']->render([class=>"sel_sml fl"])`
				<div id="reg_p_age" style="display:~if $form['p_hage']->hasError()`block~else`none~/if`;clear:both" for='reg_p_hage' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['p_hage']->getError()`</div>
					</div>
			</li>
			<li>
				<label id="mstatus_label" >Marital Status :</label>
				<div class="fl w_202 no_b blk"> 
					<span class="fl f_11">Select Items</span>
					<span class="fr f_11"><a href="#" class="blue" id="partner_mstatus_select_all" >Select All</a></span>
					<div class="clr"></div>
					<div class="gry_bdr pl_2 chk_box_cont">
						<div style="display:none" id="partner_mstatus_div">
							<input type="hidden" name="partner_mstatus_str" id="partner_mstatus_str" ~if $checked_mstatus` value="~$checked_mstatus`" ~else` value="" ~/if`>
							~$hidden_mstatus|decodevar`
						</div>
						<div style="overflow:hidden;" id="partner_mstatus_source_div">
							~$shown_mstatus|decodevar`
						</div>
					</div>
				</div>
				<div class="fl w_202 no_b blk ml_10"> 
					<span class="fl f_11">Selected Items</span>
					<span class="fr f_11"><a href="#" class="blue" id="partner_mstatus_clear_all">Clear All</a></span>
					<div class="clr"></div>
					<div class="gry_bdr pl_2 chk_box_cont">
						<div style="overflow:hidden;" id="partner_mstatus_target_div">
							<div id="partner_mstatus_DM"><label>Doesn't Matter</label></div>
						</div>
					</div>
				</div>
			</li>
			<li>
				~$form['p_lheight']->renderLabel()`
				~$form['p_lheight']->render([class=>"sel_mid fl"])` 
				<span class="fl" style="margin:2px 10px;">to</span> 
				~$form['p_hheight']->render([class=>"sel_mid fl"])`
				<div id="reg_p_height" style="display:~if $form['p_hheight']->hasError()`block~else`none~/if`;clear:both" for='reg_p_height' class='error'>
						<label class="l1">&nbsp;</label>
						<div class="err_msg">~$form['p_hheight']->getError()`</div>
					</div>
			</li>
			<li>
				<label id="mtongue_label" >Mother Tongue :</label>
				<div class="fl w_202 no_b blk"> 
					<span class="fl f_11">Select Items</span>
					<span class="fr f_11">
					<a href="#" class="blue" id="partner_mtongue_select_all">Select All</a>
					</span>
					<div class="clr"></div>
					<input type="hidden" id="partner_mtongue_selected" value="~$mapped_mton|decodevar`" style="display:none;">
					<div class="gry_bdr pl_2 chk_box_cont">
						<div style="display:none" id="partner_mtongue_div">
							<input type="hidden" id="mton_sel" value="~$mapped_mton|decodevar`" >
							<input type="hidden" name="partner_mtongue_str" id="partner_mtongue_str" value="~$mapped_mton|decodevar`">
							~$hidden_mton|decodevar`
						</div>
						<div style="overflow:hidden;" id="partner_mtongue_source_div">
							~$priority_mtongue|decodevar`
							<div class="dhrow"><span style="color: rgb(10, 137, 254);">------</span></div>
							~$shown_mton|decodevar`
						</div>
					</div>
				</div>
				<div class="fl w_202 no_b blk ml_10"> 
					<span class="fl f_11">Selected Items</span>
					<span class="fr f_11">
					<a href="#" class="blue" id="partner_mtongue_clear_all">Clear All</a>
					</span>
					<div class="clr"></div>
					<div class="gry_bdr pl_2 chk_box_cont"> 
						<div style="overflow:hidden;" id="partner_mtongue_target_div">
							<div id="partner_mtongue_DM"><label>All</label></div>
						</div>
					</div>
				</div>
			</li>
			<li>
				<label id="rel_label" >Religion :</label>
				<div class="fl w_202 no_b blk"> 
					<span class="fl f_11">Select Items</span>
					<span class="fr f_11">
					<a href="#" class="blue" id="partner_religion_select_all">Select All</a>
					</span>
					<div class="clr"></div>
					<input type="hidden" id="partner_religion_selected" value="~$checked_religion`" style="display:none;">
					<div class="gry_bdr pl_2 chk_box_cont">
						<div style="display:none" id="partner_religion_div">
						<input type="hidden" name="partner_religion_str" id="partner_religion_str" value="~$checked_religion`">
							<input type="checkbox" name="partner_religion_arr[]" id="partner_religion_DM" value="DM"><label id="partner_religion_label_DM">Any</label><br>
							~$hidden_religion|decodevar`
						</div>
						<div style="overflow:hidden;" id="partner_religion_source_div">
							~if $priority_religion`~$priority_religion|decodevar`~/if`
							<div class="dhrow"><span style="color: rgb(10, 137, 254);">------</span></div>
							~$shown_religion|decodevar`
						</div>
					</div>
				</div>
				<div class="fl w_202 no_b blk ml_10"> 
					<span class="fl f_11">Selected Items</span>
					<span class="fr f_11">
						<a href="#" class="blue" id="partner_religion_clear_all">Clear All</a>
					</span>
					<div class="clr"></div>
					<div class="gry_bdr pl_2 chk_box_cont"> 
						<div style="overflow:hidden;" id="partner_religion_target_div">
							All
						</div>
					</div>
				</div>
			</li>
			<li>
				<div id="caste" style="display:none;float:left">
					<label id="rel_caste" >Caste :</label>
					<div class="fl w_202 no_b blk"> 
						<span class="fl f_11">Select Items</span>
						<span class="fr f_11">
							<a href="#" class="blue" id="partner_caste_select_all">Select All</a>
						</span>
						<div class="clr"></div>
						<input type="hidden" id="partner_caste_selected" value="~$checked_caste|decodevar`" >
						<input type="hidden" name="partner_caste_str" id="partner_caste_str" value="~$checked_caste|decodevar`">
						<div class="gry_bdr pl_2 chk_box_cont">
							<div style="display:none" id="partner_caste_div">&nbsp;</div>
							<div style="overflow:hidden;" id="partner_caste_source_div"></div>
						</div>
					</div>
					<div class="fl w_202 no_b blk ml_10"> 
						<span class="fl f_11">Selected Items</span>
						<span class="fr f_11">
							<a href="#" class="blue" id="partner_caste_clear_all">Clear All</a>
						</span>
						<div class="clr"></div>
						<div class="gry_bdr pl_2 chk_box_cont"> 
							<div style="overflow:hidden;" id="partner_caste_target_div">Any</div>
						</div>
					</div>
				</div>
			</li>
			<li style="padding:5px;width:98%">
				~$form['p_lds']->renderLabel()`
				
				<span style="width: 125px;font-weight:normal;">Select income range</span>
				<div class="clr"></div>
				<div style="height: 80px;" class="scrollbox_adv">
					<div style="margin-bottom: 3px;padding: 3px 3px 3px 6px;">
						<span style="display: none; color: rgb(255, 0, 0);" id="invalidUser">
							<img src="~sfConfig::get('app_img_url')`/profile/images/iconError_16x16.gif"><b>Please make a selection before continuing.</b>
						</span>
					</div>
					<div style="padding: 0pt 0pt 0pt 188px;font-weight:normal;">
						<span style="color:#4B4B4B;font-weight:bold;">Indian Rupees</span>&nbsp;
						~$form['p_lrs']->render([style=>"width:150px"])`  &nbsp;&nbsp;to&nbsp;&nbsp;

						~$form['p_hrs']->render([style=>"width:150px"])`
						
						<div id="reg_p_hrs_err" style="display:~if $form['p_hrs']->hasError()`block~else`none~/if`;clear:both" for='reg_p_hrs' class='error'>
						
						<div class="err_msg" style="width:auto">~$form['p_hrs']->getError()`<BR></div>
					</div>
					</div>
					
					<div style="padding: 20px 0pt 0pt 186px;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span style="color:#4B4B4B;font-weight:bold;">US Dollar</span>&nbsp;
						~$form['p_lds']->render([style=>"width:150px"])` &nbsp;&nbsp;to&nbsp;&nbsp;
						~$form['p_hds']->render([style=>"width:150px"])`
						<div id="reg_p_hds_err" style="display:~if $form['p_hds']->hasError()`block~else`none~/if`;clear:both" for='reg_p_hds' class='error'>
						
						<div class="err_msg" style="width:auto">~$form['p_hds']->getError()`<BR></div>
					</div>
					</div>
					
				</div>
			</li>
                       

			<li>
				<label >Describe your :<br />desired partner</label>
				<span class="lt_gry">Tell us about your expectations & what you’re looking for.</span>
				<div class="clr"></div>
				<label >&nbsp;</label>
				~$form['spouse']->render([style=>"width:342px; height:98px;margin-bottom:1px",cols=>"1",rows=>"1", onkeyup=>"changeCount(this)",class=>"fl",'maxlength'=>'1000'])`
				<br>
				<div class="clr"></div>
				<label >&nbsp;</label>
				Number of characters : <span id="wordCount" class="grn" size="3"  style="border: 1px solid #ffffff;"/></span>
			</li>
			~if $Gender eq 'F'`
				<li> 
					<div class="fl">
						<input type="hidden" name="submitReg5" value="submit"> 
						<input name="submitReg5" value="Save" type="submit" class="nsubbtn" border="0" style="margin-bottom:30px;margin-left:195px; display:inline;"></input>
					</div>
		~if !$RECORD_ID`			<div class="fl" onclick="submit_skip_page('pg6')">
						<a href="#" class="fl ml_10 f_13" style="color:#057ec3;" onclick="return false"><br />          Skip to Next page</a> 
					</div>
					~/if`
				</li>
			~/if`
			~if $Gender eq 'M'`
				<li>
					<div class="fl" style="margin-left:195px;display:inline;">
						<input type="hidden" name="submitReg5" value="submit"> 
						
				<input type="submit" value="Save" class="nsubbtn" border="0" name="submitReg5"/>
					</div>
		                                        ~if !$RECORD_ID && FTOLiveFlags::IS_FTO_LIVE`
                                        <div class="fl"  onclick="submit_skip_page('fto');">
                                                <a href="#" class="fl ml_10 f_13" style="color:#057ec3;" onclick="return false"><br />Skip to Free Trial Offer details</a></div>
~else`
~if !$RECORD_ID` 
<div class="fl" onclick="submit_skip_page('pg6')">
                                                <a href="#" class="fl ml_10 f_13" style="color:#057ec3;" onclick="return false"><br />          Skip to Next page</a>
</div>
~/if`                           
~/if`

				</li>
			~/if`
		</ul>
		<div class="clr"></div>
		</div>
		</form>
	</div>
</div>
<div class="clr cl21"></div>


<script>
	var user_login=0;
	var google_plus=0;
$(document).ready(function(){
	
	fill_default_val();
  fill_details(partner_fields_array);
	
var len=partner_fields_array.length;
for(var i=0;i<len;i++)
restore_checkboxes(partner_fields_array[i]);

});
function SelectedCaste()
{
~foreach from=$cval_array item=field`
$("#partner_caste_displaying_~$field`").trigger("click");	

~/foreach`
}
</script>
