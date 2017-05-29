<div class="overlay_wrapper_775px" style="background-color:white;" >
	<!-- TITLE OF POPUP -->
	<div class="top">     	
		<div class="text white b widthauto" >
			Login to continue
		</div>
		<div class="fr div_close_button_green" name="closeLoginLayer" style="cursor:pointer" >
			&nbsp;
		</div>
	</div>
	<!-- End -->

	<!-- CONTENT -->
	<div id="login_aft_loader" class="t12 mid" style="display: none;height:243px;">
		<div style="margin:12px 0 0 0px;text-align:center;margin-top:160px;">
			<img src="~sfConfig::get('app_site_url')`/images/loader_big.gif">
			<br>
			Logging you in...
		</div>
	</div>
	<div class="mid" id="loginRegForm" >
		<form id="searchLogin" method="post" target="iframe_login" onsubmit="return searchLoginValidation()">
		<div class="textblock">
			<div class="red_new" id="ajaxErrorMsg" style="display:none;" >
			</div>
			<div class="red_new" id="errorMsg" style="display:none;" >
				<div class="ico_wrong_1 fl">
					&nbsp;
				</div>
				<span id="loginErrorRegPage" >
					Invalid email or password
				</span>
			</div>

			<div class="sp5">
			</div>
			<div class="fl fs16 b">
				<label class=" width100 ">Existing User Login
				</label>
			</div>
			<div class="sp15">
				&nbsp;
			</div>
			<div class="sp5">
				&nbsp;
			</div>
			<div class="fl">
				<label class="leftcontent" style="text-align:left">
					Email
				</label>
			</div>
			<div class="sp5">
			</div>
			<input type="hidden" id="nextAction" value="~$nextAction`" />
			<div>
				<input type="text" class="textbox-in mar_left_0" name="username" style="margin-left:0px" />
			</div>
			<div class="red_new" style="display:none" id="invalidEmail" >
				Invalid Email
			</div>
			<div class="sp15">
			</div>
			<div >
				<label class="leftcontent" style="text-align:left">
					Password
				</label>
				<div class="sp5">
				</div>
				<input type="password" class="textbox-in mar_left_0" name="password" style="margin-left:0px" />
			</div>
			<div class="red_new" style="display:none" id="invalidPassword" >
				Invalid Password
			</div>
			<div class="sp15">
			</div>
			<div > 
				<input type="checkbox" class="chbx fl " name="rememberme" checked />
				&nbsp;Remember me
			</div>
			<div class="sp15">
			</div>
			<div >
				<input type="submit" class="btn_view  fs13 b "  value="Login" style="width:70px;cursor:pointer;" name="loginUser" />
			</div>
			<div class="sp15">
			</div>
			<div >
				<a href="#" name="forgotPassword" >
					Forgot Email or password?
				</a>
			</div>
		</div>
		</form>
		<form name="mini_reg_lead" action="~sfConfig::get('app_site_url')`/profile/registration_new.php?source=~if $sourcePage eq 'successStory'`ss_reg~else`~$sourcePage`~/if`&mini_reg=1" method="post" enctype="multipart/form-data">
		<div >     
			<div id="grey_right_box">
				<div class="fl fs16 b">
					<div class="red_new fs12 " style="font-weight:normal;display:none;" id="regErrorMsg" >
						<div class="ico_wrong_1 fl">
							&nbsp;
						</div>
						<span id="incompleteRegForm" >
							Fill in all the details to continue
						</span>
					</div>
					<div class="sp12">
					</div>
					<label class=" width100 ">
						New User Register on Jeevansathi.com
					</label>
				</div>
				<input type="hidden" name="site_url" value="~sfConfig::get('app_site_url')`" />
				<div class="right fr" style="margin-right:10px">
					<span class="red_new">
						*
					</span>
					Mandatory
				</div>
				<div class="sp15">
				</div>
				<div class="sp12">
				</div>
				<div class="fl">
					<label class="leftcontent">
						<span class="red_new">
							*
						</span>
						Email :
					</label>
					<input type="text" class="textbox-in w230 mar_left_10" id="email_val" name="email" />
				</div>
				<div class="fl mar_top_10">
					<label class="leftcontent">
						<span class="red_new">
							*
						</span> 
						Mobile No :
					</label>
					<input type="text" class="textbox-in  w230  mar_left_10" id="mobile" name="mobile" maxlength="12" />
				</div>
				<div class="fl mar_top_10">
					<label class="leftcontent">
						<span class="red_new">
							*
						</span> 
						I am Looking for :
					</label>
					<select name="relationship" id="relationship_val" class="textbox-in  w230  mar_left_10" >
					<option value="" selected="selected">Please Select</option>
					<option value="1">Bride for Self</option>
					<option value="2">Bride for Son</option>
					<option value="6">Bride for Brother</option>
					<option value="4">Bride for Friend/Relative/Niece/Others</option>
					<option value="1D">Groom for Self</option>
					<option value="2D">Groom for Daughter</option>
					<option value="6D">Groom for Sister</option>
					<option value="4D">Groom for Friend/Relative/Niece/Others</option>
					</select>
				</div>
				<div class="fl mar_top_10">
					<label class="leftcontent">
						<span class="red_new">
							*
						</span> 
						Date of Birth of boy/girl :
					</label>
					<select name="day" id="day" class="w68   mar_left_10" >
						<option selected value="">
							Day
						</option>
						~section name=dayNo start=1 loop=32 step=1`
						<option value=~$smarty.section.dayNo.index` >
							~$smarty.section.dayNo.index` 
						</option>
						~/section`
					</select>
					<select class="w68   mar_left_10" name="month" id="month">
						<option selected value="">Month</option>
						<option value="1">Jan</option>
						<option value="2">Feb</option>
						<option value="3">Mar</option>
						<option value="4">Apr</option>
						<option value="5">May</option>
						<option value="6">Jun</option>
						<option value="7">Jul</option>
						<option value="8">Aug</option>
						<option value="9">Sep</option>
						<option value="10">Oct</option>
						<option value="11">Nov</option>
						<option value="12">Dec</option>
					</select>
					<select class="w68   mar_left_10" name="year" id="year" >
						<option selected value="">
							Year
						</option>
						~section name=year max=52 loop=1995 step=-1`
						<option value=~$smarty.section.year.index` >
							~$smarty.section.year.index` 
						</option>
						~/section`
					</select>
				</div>
				<div class="fl mar_top_10">
					<label class="leftcontent" >
						<span class="red_new">
							*
						</span> 
						MotherTongue/Community :
					</label>
					<select class="textbox-in  w230  mar_left_10" name="mtongue" id="mtongue" >
						<option value="" selected="selected">Please Select</option>
						~foreach from=$MtongueDropdownForTemplate item=value key=kk`
						<optgroup label="&nbsp;"></optgroup>
						<optgroup label="~$value['LABEL']`">
							~foreach from=$value['VALUES'] item=value1 key=kk1`
								<option value="~$kk1`">~$value1`</option>
							~/foreach`
						</optgroup>
						~/foreach`
					</select>
				</div>

			</div>
			<br><br>
			<div class="fs12 mar_left_10 " style="width:770px; color:#505050">
				<span id="clickMe"></span>
				&nbsp;&nbsp;&nbsp;&nbsp;Clicking on &#39;Register free&#39; button means that you accept 
				<a href="#" onclick="javascript:window.open('~sfConfig::get('app_site_url')`/profile/disclaimer.php?text=1','mywindow','scrollbars=yes,width=500');return false;" target="_blank">
					terms and conditions 
				</a>
				<br>&nbsp;&nbsp;&nbsp;&nbsp;of Jeevansathi.com
				<br><br><br>
				<div class="txt_center">
					<input type="submit" class="btn_view  fs13 b "  value="Register free" style="width:140px;cursor:pointer;" id="miniRegForm" />
				</div>
				<br><br><br>
			</div>
		</div>
		</form>
	</div>
</div>
<div id="Hidden_iFrame">
	<iframe id="iframe_login"  style="display:none"  name="iframe_login">
	</iframe>
</div>

<script>
	var pageSource="~$sourcePage`";
	searchPageLoginLayer=1;
	
	bindAllClicks();
	function bindAllClicks()
	{
		$("[name='closeLoginLayer']").unbind('click', closeLoginLayer).click(closeLoginLayer);
		$("[name='forgotPassword']").unbind('click', forgotPassword).click(forgotPassword);
		$("[name='password']").unbind('keypress', LogincheckEnter).keypress(LogincheckEnter);
		$("[name='username']").unbind('keypress').keypress(LogincheckEnter);
	}

	$("#mobile").blur
	(
		function()
		{
			javascript:get();
		}
	);

	$("#email_val").keypress
	(
		function(e)
		{
			if(e.which == 13)
			{
				submitRegistrationForm();
			}
		}
	);

	$("#miniRegForm").click
	(
		function submitRegistrationForm()
		{
			if(!ifLeadValid())
			{
				$("#regErrorMsg").show();
				$('#clickMe').trigger('click');
				return false;
			}
			else
			{
				$("#regErrorMsg").hide();
				$("form:mini_reg_lead").submit();
			}
			return false;
return 0;
		}
	);
</script>
