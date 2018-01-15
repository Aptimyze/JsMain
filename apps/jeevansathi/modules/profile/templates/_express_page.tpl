~if !$ERROR_MESSAGE`
<div class="lf" style="padding:0px 0px;width:375px;position:relative;z-index:10;visibility:~if $SHOW_CONTACT eq ''`visible~else`hidden~/if`;  ~if $SHOW_CONTACT || $CALL_TAB_SEL` display:none ~else` display:block; ~/if`" id="show_express">
	<form id='contact_engine' name=fr1 style="margin:0px;padding:0px">
	<input type="hidden" name="countlogic" value="1">
	<input type="hidden" name="clicksource" value="~$CLICKSOURCE`">
	<input type="hidden" name="matchalert_mis_variable" value="~$matchalert_mis_variable`">
	<input type="hidden" name="CURRENTUSERNAME" value="~$CURRENTUSERNAME`">
	<input type="hidden" name="crmback" value="~$crmback`">
	<input type="hidden" name="inf_checksum" value="~$inf_checksum`">
	<input type="hidden" name="cid" value="~$cid`">
	<input type="hidden" name="suggest_profile" value="~$suggest_profile`">
	<input type="hidden" name="pr_view" value="~$pr_view`">
	<input type="hidden" name="stype" value="~$STYPE`">
	<input type="hidden" name="checksum" value="~$CHECKSUM`">
	<input type="hidden" name="profilechecksum" value="~$PROFILECHECKSUM`">
	<input type="hidden" name="viewed_profile" id="viewed_profile" value="~$viewed_profile`">
	<input type="hidden" name="filter_profile" id="filter_profile" value="~$FILTERED`">
	<input type="hidden" name="TextAllow" value="~$TEXTALLOW`">
	
	~if $TYPE eq 'I' && ( $ALLOW_ACCEPT_DECLINE eq '' || $ALLOW_ACCEPT_DECLINE eq '0')`
	<input type='hidden' name=status value='I'>
	~/if`
	~if $TYPE eq ''`
	<input type='hidden' name=status value='I'>
	~/if`
	~if $TYPE eq 'D'`
	<input type='hidden' name=status value='A'>
	~/if`
	~if $TYPE eq 'C'`
	<input type='hidden' name=status value='A'>
	~/if`
	~if $FROM_ALBUM`<div><img src="~$IMG_URL`/images/gup.gif"></img></div>~/if`
	<div class="cont_hr_bg" style="padding:1px 0 0 10px;width:385px;height:300px" id="main_layer_dp">
		<div style="width:357px; padding-top:7px;">
			<div class="dark_orange t14 b">~if !$FROM_ALBUM`~$CONTACT_HEADLINE` ~else`<div style="float:left;width:80%">~$CONTACT_HEADLINE`</div><a onclick="return hide_exp_layer()" href="#" class="fr crs b">[x]</a>~/if`</div>
			<div class="sp5"></div>
			<div class="t12">~$CONTACT_MESSAGE|decodevar`</div>
			<div class="sp5"></div>
			~if $TEMP_CONTACT eq 0 && $LATEST_ACC_DEC eq ''`
			~if $ALLOW_ACCEPT_DECLINE`
			<div class="t12">
				<input type="radio" name="status" value="A" style="border:none;" onclick="javascript:check_dropdown('accept')" checked="checked">Accept &nbsp;&nbsp;&nbsp;&nbsp;</input><input type="radio" name="status" value="D" style="border:none;" onclick="javascript:check_dropdown('reject')"~if $search_decline` checked ~/if`>Not Interested</input>
			</div>
			<div class="sp5"></div>
			~/if`
			<div class="lf" style="margin-left:210px;display:inline">
				<select name="draft_name" id="message_id" class="set_90" onChange="javavscript:change_message(this.value)" ~if !$LOGIN || $NUDGE_STATUS` style="visibility:hidden" ~/if` >
					<option value=''>Write new message</option>
				</select>
			</div>
			<div class="sp5"></div>
			<div class="sp5"></div>
			<textarea id='text_id' name="custmessage" style="width:354px;height:~if $PAID eq 0 AND $ALLOW_MES_WRITE eq 0` 60px~else`76px~/if`;font-family:tahoma; font-size:11px; padding:3px;" cols="10" rows="3" class="pd" ~if $PAID eq 0 AND $ALLOW_MES_WRITE eq 0` disabled >~$DEFAULT_MESSAGE`~else` >~/if`</textarea>
			~/if`
			~if !$LOGIN`
			<div class="sp5"></div>
			<div class="dark_orange t11 b">To express interest in this profile</div>
		</div>
		<div class="sp8"></div>
		<div class="sp8"></div>
		<div class="sp8"></div>
		<div style="text-align:center"> <strong class="t14">New user?</strong>&nbsp;&nbsp;<input name="Submit" id="multi_button" type="button" class="b green_btn en_btn_clr_alb" value="Register Now" style="width:130px;" onclick="Javascript:redirect('~$SITE_URL`/profile/registration_new.php?source=dp_layer')" ></div>
		<div class="sp5"></div>
		<div class="sp8"></div>
	
		<div style="text-align:center"> <strong>Existing User -   <strong><a href='~$SITE_URL`/profile/login.php?SHOW_LOGIN_WINDOW=1' class='thickbox'   style="color:#3b6c99;">Login Here</a></strong></strong>
		</div>
		~else`
		~if $TYPE eq 'A'`
			<div class="sp5"></div>
			<div class="fl mt_15 f_11"><span id="errorSpan" style=color:red></span></div>
		~/if`
		</div>
		<div class="sp8"></div><div class="sp8"></div><div class="sp8"></div>
		~if $TEMP_CONTACT eq 0 && $LATEST_ACC_DEC eq ''`
		<div style="text-align:center; top: ~if $TYPE eq 'A'`190px;~else` 210px;~/if` left: 60px;" ><input name="Submit" id="multi_button" type="button" class="b ~if $ENABLE_BUTTON` gray_btn ~else` green_btn  en_btn_clr_alb~/if`" value="~$BUTTON_NAME`" style="width:150px;" ~if $ENABLE_BUTTON eq '1'` disabled ~/if` ~if !$LOGIN` onclick="$.colorbox({href:'~$SITE_URL`/profile/login.php?SHOW_LOGIN_WINDOW=1'});" ~else` onclick="javascript:submit_form('Submit')"~/if`>
		</div>
		~if $CALL_ACCESS &&  $REMOVE_ACCESS eq 1`
		<div class="sp8"></div>
		<b style="font-size: 14px;">or <a class="blink b" onclick="javascript:call_now_layer()" style="cursor:pointer">Call this user now</a></b>
		<div class="sp5"></div>
		~/if`
		~/if`
		
		~if $TEMP_CONTACT eq 0 && $LATEST_ACC_DEC eq ''`
		~if !$PROMPT_TO_PAY && $LOGIN`
		~if $BUY_MESSAGE_EOI`
		<div class="sp8"></div>
		<div class="t12" style="float:left;font-size:11px;width:345px">~$BUY_MESSAGE_EOI|decodevar` </div>
		~/if`
		<div class="sp8"></div>
		<div class="t14" style="font-size:16px;text-align:center"> <a href="~$SITE_URL`/profile/mem_comparison.php?from_source=~if $TAB_NAME eq 'Express Interest'`Express_interest_tab~else`write_message_tab~/if`" class="blink b">Become a Paid Member Now</a></div>
		~/if`
		~/if`
		~/if`
		<div class="sp5"></div>
		~if $TYPE eq 'A' && !$no_interest`
		<div class="sp8"></div><div class="sp8"></div><div class="sp8"></div>
		<div class="rf t11 b" style="padding-right:17px;"><a  href="#" class="blink" onclick="javascript:for_confirmation('DECLINE','~$WHO`','~$PROFILENAME`')">Not interested</a> in this member?</div>
		~/if`
		<br />
	</div>
	<div><img src="~$IMG_URL`/images/gdown.gif"></div>
	</form>
	
</div>

<div class="lf" style="padding:0px 0px;width:375px;position:relative;z-index:0;display:~if $SHOW_CONTACT`inline;~else`none~/if`" id="show_contact">
	<div class="cont_hr_bg" style="padding:1px 0 0 10px;width:385px;height:300px" id="call_directly">
		<div class="sp8"></div>
		~if $shift_mes`
		~$shift_mes|decodevar`
		~else`
		<div style="width:357px; padding-top:7px;">
		~if $showContactDetail`
		~include_partial("logoutcontactenginepage",[RANDOMNUMBER=>$RANDOMNUMBER,showRegisterPage=>$showRegisterPage,showTollFree=>$showTollFree,yearArray=>$yearArray,dayArray=>$dayArray,mtongue=>$mtongue,seo_community_js=>$seo_community_js])`
		~else`
		
			~if $CONTACT_LOCKED eq 1`
			<div class="dark_orange t14 b">Contact Details</div>
			<div class="sp5"></div>
			<ul class="con_list" ~if $LATEST_ACC_DEC` style="height:170px"~/if`~if !$LATEST_ACC_DEC && $SHOW_CALLNOW_LINK` style="height:225px"~/if` >
					~if $SHOW_MOBILE`
				<li>Mobile no.~if $MOB_PROFILENAME` of  ~$MOB_PROFILENAME` (~$MOB_RELATION_NAME`)~/if`<br>
					<b>~$SHOW_MOBILE|decodevar`</b> ~if $VERIFIED_MOB`(Verified)~/if`</li>
					~/if`
					~if $ALT_MOBILE`
	                              <li>Alternative Mobile no.~if $ALT_MOBILE_LABEL`~$ALT_MOBILE_LABEL`~/if`<br>
        	                        <b>~$ALT_MOBILE|decodevar`</b></li>
                	                ~/if`
					~if $PHONE_NO`
				<li>Landline no.~if $PHONE_PROFILENAME` of  ~$PHONE_PROFILENAME` (~$PHONE_RELATION_NAME`)~/if`<br>
					<b>~$PHONE_NO|decodevar`</b> ~if $VERIFIED_LANDLINE`(Verified)~/if`</li>
					~/if`
				<li>~$SHOW_CALLNOW_LINK|decodevar`	</li>
					~if $TIME_TO_CALL_START`
				<li>Suitable time to call<br>
					<b>~$TIME_TO_CALL_START` to ~$TIME_TO_CALL_END`</b></li>
					~/if`
					~if $SHOW_ADDRESS`
				<li>Address<br>
					<b>~$SHOW_ADDRESS|decodevar`</b></li>
					~/if`
				~if $SHOW_PARENTS_ADDRESS`
				<li>Parent's address<br>
					<b>~$SHOW_PARENTS_ADDRESS|decodevar`</b></li>
					~/if`
					~if $EMAIL_ID`
					<li>Email ID<br>
					<b>~$EMAIL_ID`</b>
					</li>
					~/if`
					~if $SHOW_MESSENGER`
				<li>Messenger ID<br>
					<b>~$SHOW_MESSENGER`</b></li>
					~/if`
			</ul>
			~else`
			~if $CONTACT_LOCKED eq 0`
			~if $MOB_NOT_VERI && $LANDLINE_NOT_VERI`
			~if $CALL_DIRECT`
			<div class="lf b t11" style=" bottom: 0px; left: 10px;">Please <a class="blink" style="pointer:cursor" onclick="javascript:{close_all_con_layer();$.colorbox({href:'~$SITE_URL`/profile/myjs_verify_phoneno.php'});}" >Verify your number</a> before using this feature
			</div>
			~else`
			<div class="lf b t11 " style=" bottom: 0px; left: 10px;">Contact details are locked because you have not verified your phone number(s). <a onclick="javascript:{close_all_con_layer();$.colorbox({href:'~$SITE_URL`/profile/myjs_verify_phoneno.php'});}" class="blink" style="pointer:cursor">Click here</a> to know how to verify your contact details and unlock
			</div>
			~/if`
			~else`
			~if $CON_DET_MES`
			<div class="lf" style=" bottom: 0px; left: 10px;">~$CON_DET_MES|decodevar`
			</div>
			~/if`
			~/if`
			~/if`
			<div class="sp5"></div>
			<ul class="con_list_locked" style="background:url(~$IMG_URL`/img_revamp/locked.gif) no-repeat 78% 37%; margin-top:12px; height:170px;">
			<li>Mobile no.<Br></li>
			<li>Landline no.<Br></li>
			<li>Suitable time to call<Br></li>
			<li>Address<Br></li>
			<li>Parent's address<Br></li>
			<li>Email ID<Br></li>
			</ul>
			<div class="sp5">
			</div>
			~/if`
	
			<div style="border-style: none none solid; border-color: rgb(182, 182, 182); border-width: 1px;float: left; padding-top: 2px; padding-bottom: 4px;width:340px;height:1px"></div>
	
			~if $LATEST_ACC_DEC`
			<div class="sp5"></div>
			<div class="t12 lf">~$LATEST_ACC_DEC|decodevar`</div>
			~else`
			~if $CONTACT_LOCKED eq 0`
			~if $MOB_NOT_VERI && $LANDLINE_NOT_VERI`
			~else`
			~if $TYPE eq 'D' && $WHO eq 'SENDER' && $CALL_DIRECT neq 1`
				
			<div class="t11 b lf"><BR>~$CONTACT_HEADLINE`
			</div>
			~/if`
			~/if`
			~/if`
			~/if`
			~if $CONTACT_LOCKED neq 0`
			~if $PHONE_NO || $SHOW_MOBILE`
			<div class="sp5"></div>
			<div class="t11 lf">~if !$SHOW_CALLNOW_LINK`<a class="thickbox" href="~$SITE_URL`/profile/report_invalid_phone.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`"   >Report Invalid telephone number</a>~/if`
			</div>
			~/if`
			~/if`
		~/if`	
			~if $TYPE eq 'A' && !$no_interest`
                <div class="sp8"></div><div class="sp8"></div><div class="sp8"></div>
                <div class="rf t11 b" style="padding-right:17px;"><a  href="#" class="blink" onclick="javascript:{show_layer('show_express','show_contact','expr_layer','con_layer',1,'show_callnow','callnow_layer');for_confirmation('DECLINE','~$WHO`','~$PROFILENAME`')}">Not interested</a> in this member?</div>
                ~/if`
			
		</div>
		~/if`
		
		<div class="sp8"></div>
		<br />
	</div>

	<div><img src="~$IMG_URL`/images/gdown.gif"></div>
</div>
<div class="lf lyr_callnow" style="padding:0px 0px;width:375px;position:relative;z-index:0;display:none" id="show_callnow">
	<div class="cont_hr_bg" style="padding:1px 0 0 10px;width:385px;height:300px" id="main_layer_callnow">
	</div>
	<div><img src="~$IMG_URL`/images/gdown.gif"></div>
</div>
~else`
<div class="lf" style="padding:1px 0px;width:375px;position:relative;z-index:0;">


<div class="cont_hr_bg" style="padding:1px 0 0 10px;width:385px;height:300px" id="EXP_LAYER_ERR">
<div style="width:357px; padding-top:7px;"><div class="dark_orange t14 b">~$ERROR_MESSAGE|decodevar`</div>



</div>

<div class="sp5">
</div>


 
</div><div><img src="~$IMG_URL`/images/gdown.gif"></div>
</div>
~/if`

<script id='script_of_dp'>
	var selid=dID("message_id");
	var textid=dID("text_id");
	var buttonid=dID("multi_button");
	
	
	var stype="";
	if(dID("STYPE"))
		stype=dID("STYPE").value;
	var MES = new Array(); 
	var DEC=new Array();
	var temp="";
	var result;
	var pattern1 = /\#n\#/g;
	dp_login='~$LOGIN`';
	~foreach from=$DRA_MES item=message key=id`
	temp="~$message`";
	MES['~$id`']=temp.replace(pattern1,"\n");
	~/foreach`
	var declineSel="";
	var acceptSel="";
	~foreach from=$DECLINE_OPTION item=message key=id`
	declineSel+="<option value='~$id`'>"+check_special_chars("~$message`")+"</option>";
	~/foreach`
	
	~foreach from=$ACCEPT_OPTION item=message key=id`
	acceptSel+="<option value='~$id`'>"+check_special_chars("~$message`")+"</option>";
	~/foreach`
	
	~if $TYPE eq 'I' && $WHO eq ''`
		dp_accept=1;
	~else`
		dp_accept=0;
	~/if`
	
	~if $ALLOW_ACCEPT_DECLINE && $PAID eq 0 && $ALLOW_MES_WRITE eq 0`
		dp_selid=1;
	~else`
		dp_selid=0;
	~/if`
	dp_status="~$STATUS`";
	
	dp_removeText="~$REMOVE_TEXTAREA`";
	dp_removeText="";
	
	dp_disableAll="~$DISABLE_ALL`";
	
	dp_type="~$TYPE`";
	dp_viewprofile="~$VIEWPROFILE`";
	
	dp_allowAcceptDecline="~$ALLOW_ACCEPT_DECLINE`";
	
	dp_who="~$WHO`";
	
	dp_navigator="~$NAVIGATOR_LINK`";
	
	dp_imgurl="~$IMG_URL`";
	
	dp_tempContact="~$TEMP_CONTACT`";
	
	dp_searchDecline="~$search_decline`";
	
	dp_login="~$LOGIN`";
	
~if $ERROR_MESSAGE`
	dpContactEngineError=1;
~else`
	dpContactEngineError=0;
~/if`

</script>
<script src="~$IMG_URL`/min/?f=/js/~$contact_engine_js`"></script>
