<div class="overlay_wrapper" style="background-color:white;" >

	<!-- TITLE OF POPUP -->
	<div class="top">     	
		<div class="text fs16 white b fl" style="width:375px;" >
			Forgot  Password
		</div>
		<div class="fr div_close_button_green" name="closeLayer" style="cursor:pointer;"  >
			&nbsp;
		</div>
	</div>
	<!-- End -->

	<!-- CONTENT -->
	<div class="mid">
		<div style = "margin-left:23px; margin-top:20px;">
			 <div id="passwordSuccess" style="display:none;" >
				<div>
					<div class="ico_right_1 fl">
						&nbsp;
					</div>
					<div class="fs16">
					An Email & SMS has been sent to you. Please click on the link provided to reset your password.
					</div>
				</div>
				<div >
					<label class="leftcontent">
						&nbsp;
					</label>
					<a href="#" class="mar_left_14">
					</a>
				</div>
			</div>

		        <div id="loginLoader1" style="display:none;">
			        <div style="text-align:center;">
		        		<img src="~$IMG_URL`/images/loader_big.gif">
			        </div>
		        </div>


			<div id="initialLayer" >
				<div class="red_new" id="wrongEmail" style = "display:none;">
					<i class="ico_wrong_1 fl">&nbsp;</i>
					Provided email doesn't exist.
					<div class = "sp15"></div>
				</div>
				<div>
					<div class="fs16">Enter your registered email of Jeevansathi to receive an Email & SMS with the link to reset your password</div>
					<div class = "sp15"></div>
					<label class="fs16" style="width:136px;">
						My registered Email id is
					</label>
					<input type="text" class="textbox-in" id="emailAdd" />
				</div>
				<div class="sp15">
				</div>
				<div >
					<label class="leftcontent">
						&nbsp;
					</label>
					<input type="submit" class="btn_view mar_left_14 fs13 b"  value="Submit" style="width:140px;cursor:pointer;" id="requestPassword" />
				</div>
			</div>
				<div class="sp15"></div>
				<div class = "sp15"></div>
		</div>
	</div>
</div>
<script>
	$("[name='closeLayer']").click
	(
		function()
		{
			$.colorbox.close();
			return false;
		}
	);

	function sendForgotPasswordRequest()
	{
		var emailValue = $("#emailAdd").val();
		if(check_for_email(emailValue))
		{
			complete_url="forgotpassword.php?ajaxValidation=Y&submit_email=1&email="+escape(emailValue);

			before_call_func="before_forgot";
			after_call_func="after_forgot";
			method="POST";
			send_ajax_request(complete_url,before_call_func,after_call_func,method);
		}
		else
		{
			$("#wrongEmail").show();
			$.colorbox.resize();
			return 1;
		}
	}

	$("#requestPassword").click
	(
		function()
		{
			sendForgotPasswordRequest();
		}
	);

	$("#emailAdd").keypress
	(
		function(e)
		{
			if(e.which == 13)
			{
				sendForgotPasswordRequest();
			}
		}
	);

function before_forgot()
{
	$('#loginLoader1').show();
	$('#initialLayer').hide();
	$.colorbox.resize();
	//display loader
}

function after_forgot()
	{
                $('#loginLoader1').hide();
                $('#initialLayer').show();

		//hide loader

		if(result=='A_E')
		{
			$("#initialLayer").html(common_error);
			$.colorbox.resize();
			return 1;
		}
		if(result=='JA')
		{
			top.document.location.href='/profile/retrieve_archived.php';
			return 1;
		}
		if(result=='D1' || result=='E2' || result=='E1')
		{
			if(result=='E1' || result=='E2')
			{
				$("#wrongEmail").show();
			}
			else if(result=='D1')
			{
				$("#passwordSuccess").show();
				$("#initialLayer").hide();
			}
			$.colorbox.resize();
			return 1;
		}
	}

	function check_for_email(emailadd)
	{
		var result = false;
		var theStr = new String(emailadd);
		var index = theStr.indexOf("@");
		if (index > 0)
		{
			var pindex = theStr.indexOf(".",index);
			if ((pindex > index+1) && (theStr.length > pindex+2))
			result = true;
		}
		return result;
	}

</script>
