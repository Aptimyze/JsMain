<div class='overlay_wrapper' style='background-color:white;' >
	<div class='top'>
		<div class='text fs16 white b fl' style="width:375px;">
			Forward this Profile
		</div>
		<div class='fr div_close_button_green' style="cursor:pointer;" >
			&nbsp;
		</div>
	</div> 
	<div class='mid' id="successBlock" >
	</div>
	<div class='mid' id="forwardBlock" >
		<div style="padding:20px;">
			<div class="width100 fl">
				<div class="fl" id="emailError" style="display:none;" >
					<i class="ico_wrong_1 fl"></i>
					<span class="red_new">Please provide a valid email address.</span>
				</div>
				<div class="fr">
					<span class="red_new">*</span>
					Mandatory fields
				</div>
			</div> 
			<div class="sp5">
			</div>
			<div class='fs14 b widthauto fl'>
				From
			</div>
			<div class='sp15'>
			</div>
			<div class='fl' style='width:175px;'>
				Your name 
				<br>
				<input type='text' style='width:175px;' id="viewerName" />
			</div>
			<div class='fl' style='width:175px; margin-left:18px; display:inline'>
				<span class='red_new'>
					*
				</span>
				Your Email ID
				<br>
				<input type='text'  style='width:175px;' value="~$loggedInEmail`" ~if $loggedInEmail neq ''` disabled ~/if` id="viewerEmail" > </input>
			</div>
			<div class='sp15'>
			</div>
			<div class='sp5'>
			</div>
			<div class='fs14 b widthauto fl'>
				To
			</div> 
			<div class='sp15'>
			</div>
			<div class='fl' style='width:175px;'>
				Receiver&apos;s name 
				<br>
				<input type='text' style='width:175px;' id="receiverName" />
			</div>
			<div class='fl' style='width:175px; margin-left:18px; display:inline'>
				<span class='red_new'>
					*
				</span>
				Receiver&apos;s Email ID
				<br>
				<input type='text'  style='width:175px;' id="receiverEmail" />
			</div>
			<div class='sp5'>
			</div>
			<div>
				<p>
					<textarea rows='0' cols='0'  style='width:370px; height:113px' id="message" >
					</textarea>
				</p>
				<p>
					&nbsp;
				</p>
				<p>
					<span class='fs14'>
						<input type='button' value='Send' class='btn_view b' style='margin-left:117px;cursor:pointer;' name="submitForwardForm" />
					</span>
				</p>
			</div>
		</div>
	</div>
</div>
<script>
	$(".div_close_button_green").click
	(
		function()
		{
			$.colorbox.close();
		}
	);
	$("[name='submitForwardForm']").click
	(
		function()
		{
			if($("#receiverEmail").val() == '')
			{
				$("#emailError").show();
				$("#receiverEmail").focus();
//				alert("Please provide a valid email address");
				return false;
			}
			if($("#viewerEmail").val() == '')
			{
				$("#emailError").show();
//				alert("Please provide a valid email address");
				return false;
			}
			else
			{
				if(checkemail_sul($("#receiverEmail").val()) == false || checkemail_sul($("#viewerEmail").val()) == false)
				{
					$("#receiverEmail").focus();
					$("#emailError").show();
//					alert("Please provide a valid email address");
					return false;
				}
				$("#forwardBlock").hide();
				$("#successBlock").html("<div style='height:190px' id='all_Content'><div align='center' style='text-align:center;margin-top:110px;'><img src=\"~sfConfig::get('app_img_url')`/img_revamp/loader_small.gif\"><div style='margin-top:15px;font-size:15px;margin-left:15px;'> Sending...</div><div></div>");
				$.ajax
				(
					{
						url: "/profile/forward_profile.php?name="+escape($("#viewerName").val())+"&email="+escape($("#viewerEmail").val())+"&fname[]="+escape($("#receiverName").val())+"&femail[]="+escape($("#receiverEmail").val())+"&message="+escape($("#message").val())+"&ajax_error=2&invitation=1&send=1&profilechecksum=~$forwardedProfileChecksum`",
						success: function(response)
						{
							if(response == 'bye')
							{
								$("#successBlock").html("<div style='padding:20px'>\
										<div class='fs14'>\
											<i class=' ico_right_1 fl'></i>\
											Profile has been sent successfully.\
										</div>\
										<div class='sp15'>\
										</div>\
										<div class='fs14 b'>\
										</div>\
										<div class='sp15'>\
										</div>\
										<div class='fs14 fl'>\
											<br><br><br><br>\
										</div>\
									</div>");
							}
							else
							{
//								alert(response);
								$("#forwardBlock").show();
								$("#successBlock").hide();
							}
						}
					}
				);
			}
			return false;
		}
	);

	function checkemail_sul(str)
	{
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		var lastdot=str.lastIndexOf(dot)

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr)
		{
			return false;
		}
		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr || str.substring(lastdot+1)=="")
		{
			return false;
		}

		if (str.indexOf(at,(lat+1))!=-1)
		{
			return false;
		}

		if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot)
		{
			return false;
		}

		if (str.indexOf(dot,(lat+2))==-1)
		{
			return false;
		}

		if (str.indexOf(" ")!=-1)
		{
			return false;
		}

		if(CharsInBag_sul(str)==false)
		{
			return false;
		}

		if(lstr>40)
		{
//			alert("Please check the limit of email address (Max limit: 40 chars)");
			return false;
		}

		if(lstr<4)
		{
//			alert("Please check the limit of email address (Min limit: 4 chars)");
			return false;
		}

		var arrEmail=str.split("@")
		var ldot=arrEmail[1].indexOf(".")
		var idLength=arrEmail[0].length

		/* Adding Check for Gmail */

		var domainNameFull=arrEmail[1].split(".")
		var domainName=domainNameFull[0].slice(".")

		if(idLength < '6' && domainName=='gmail')
		{
//			alert("Please enter valid Email-Id");
			return false;
		}

		if(idLength < '4' && (domainName=='rediff' || domainName=='yahoo'))
		{
//			alert("Please enter valid Email-Id");
			return false;
		}

		if(isInteger_sul(arrEmail[1].substring(ldot+1))==false)
		{
			return false;
		}

		return true;		
	}

</script>
