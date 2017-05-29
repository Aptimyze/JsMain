if(typeof SSL_SITE_URL=="undefined")
		var SSL_SITE_URL="https://"+top.location.host;

	function closeLoginLayer()
	{
		$.colorbox.close();
	}

	function forgotPassword()
	{
		var url = SITE_URL+"/static/forgotPasswordLayer";
		$.colorbox({href:url});
		return false;
	}

	function loginUser()
	{
		//call tracking function if tracking is enabled.
		if(typeof(jsLogin_layer)!='undefined')
		{
			if(jsLogin_layer)
			{
				//Forecefully calling jsb9onUnloadTracking
				if(typeof(jsb9onUnloadTracking)=='function')
					jsb9onUnloadTracking();
				jsLogin_layer=0;
			}
		}

		//Disable the fetched password message is enabled.
		var email_val_layer=$("[name='username']").val();
		var password_val=$("[name='password']").val();

		if($("[name='rememberme']").is(':checked'))
			remember_val='Y';
		else
			remember_val='N';
		
		if(!checkemail(email_val_layer))
		{
			if($("#loginError").length > 0)
			{
				$("#loginError").html("Invalid email or password");
				$("#errorMsg").show();
			}
			if($("#invalidEmail").length >0)
			{
				$("#invalidEmail").html("Email is invalid.");
				$("#invalidEmail").show();
				$("#errorMsg").hide();
				$("#invalidPassword").hide();
			}
			$("[name='username']").focus();
			if(email_val_layer && password_val)
        		{
		                if(email_val_layer && password_val)
        			{
					em_before_login();
					loginUrl=SSL_SITE_URL+"/static/verifyAuth?username="+email_val_layer+"password="+password_val;
					$("#searchLogin").attr('action',loginUrl);
	                        	/*$.post( "/static/verifyAuth", { "username": email_val_layer, "password": password_val })
                                	  .done(function( data ) {
					em_after_login();
                        	            if(data)
                	                    {
						    $("#invalidEmail").html(data);
						    $("#loginError").html(data);
						
						}
		                          });*/
		                          return true;
                		}
		        }

			return false;
		}

		if(password_val=="")
		{
			if($("#loginError").length > 0)
			{
				$("#loginError").html("Invalid email or password");
				$("#errorMsg").show();
			}
			if($("#invalidPassword").length >0)
			{
				$("#invalidPassword").html("Password is invalid.");
				$("#invalidPassword").show();
				$("#errorMsg").hide();
				$("#invalidEmail").hide();
			}
			$("[name='password']").focus();
			return false;
		}

//		var complete_url="~sfConfig::get('app_site_url')`/profile/login.php";
	
		
		loginUrl=SSL_SITE_URL+"/profile/login.php?ajaxValidation=1";
		if(typeof(pageSource)=='undefined')
			pageSource="blank";
		if(pageSource=='successStory')
			loginUrl=SSL_SITE_URL+"/successStory/login";
//alert(complete_url);

		var loginData = "username="+escape(email_val_layer)+"&password="+encodeURIComponent(password_val)+"&rememberme="+escape(remember_val)+"&page="+pageSource+"&ajaxValidation=1";

		if($("#loginError").length > 0 || ($("#loginContent").length))
		{
			$('#loginLoader').show();
			$('#loginContent').hide();
		}
		if($("#login_aft_loader").length > 0 || ($("#loginRegForm").length))
		{
			$('#login_aft_loader').show();
			$('#loginRegForm').hide();
		}
		$("#searchLogin").attr('action',loginUrl);
		return true;	
	}
	function em_before_login()
	{
		if($("#loginError").length > 0 || ($("#loginContent").length))
                {
                        $('#loginLoader').show();
                        $('#loginContent').hide();
                }
                if($("#login_aft_loader").length > 0 || ($("#loginRegForm").length))
                {
                        $('#login_aft_loader').show();
                        $('#loginRegForm').hide();
                }
	}
	function em_after_login()
	{
		if($("#loginError").length > 0 || ($("#loginContent").length))
                {
                        $('#loginLoader').hide();
                        $('#loginContent').show();
                }
                if($("#login_aft_loader").length > 0 || ($("#loginRegForm").length))
                {
                        $('#login_aft_loader').hide();
                        $('#loginRegForm').show();
                }
	}
	function before_login()
	{
		//display loader

	}

	function after_login(result)
	{
		//hide loader
		//A_E --> error because of query failure
		//N --> Wrong username/password
		//O --> Stopping offline login
		//Y --> succesfully login
		//YI --> incomplete profile.
		
		if(result=='A_E' || result=='N' || result=='O')
		{
			if($("#loginError").length > 0 || ($("#loginContent").length))
			{
				$('#loginLoader').hide();
				$('#loginContent').show();
			}
			if($("#login_aft_loader").length > 0 || ($("#loginRegForm").length))
			{
				$('#login_aft_loader').hide();
				$('#loginRegForm').show();
			}
			jsLogin_layer=1;
			if(typeof(jsb9eraseCookie)=='function')
				jsb9eraseCookie("jsb9Track");
		}

		if(result=='A_E')
		{
			common_error = common_error + "<br><br>";
			$("#ajaxErrorMsg").html(common_error);
			$("#ajaxErrorMsg").show();
			$("#errorMsg").hide();
			return 1;
		}
		else if(result=='N')
		{
			if($("#loginError").length > 0)
			{
				$("#loginError").html("Email & Password do not match");
				$("#errorMsg").show();
			}
			if($("#loginErrorRegPage").length > 0)
			{
				$("#loginErrorRegPage").html("Email & Password do not match");
				$("#invalidEmail").hide();
				$("#invalidPassword").hide();
				$("#errorMsg").show();
			}
			$("[name='username']").focus();
			return 1;
		}
		else if (result=='O')
		{
			if($("#loginError").length > 0)
			{
				$("#loginError").html("Profile Inactive");
				$("#errorMsg").show();
			}
			if($("#loginErrorRegPage").length > 0)
			{
				$("#loginErrorRegPage").html("Profile Inactive");
				$("#invalidEmail").hide();
				$("#invalidPassword").hide();
				$("#errorMsg").show();
			}
			return 1;
		}
		else if(result=='Y' || result=='YI')
		{
			var address_url=window.top.location.href;
			var temp_url=window.location.href;
			if(pageSource=="successStory")
                        {
                              	$.colorbox({href:"/successStory/layer?width=700"});
                                return 1;
                        }
			else if(pageSource=="MemChsPlan"||pageSource=="MemChsVAS"||pageSource=="MemPymtOpt"||pageSource=="membershipMain")
			{
				address_url="/membership/jspc";
				window.top.location = address_url;
                                return 1;
			}
			else if(pageSource.indexOf("MemJSEx")>-1)
                        {
				jsExcRadioSel="X"+pageSource.substring(7);
                                var nextAction="/membership/jspc?displayPage=3&mainMem=X&mainMemDur="+jsExcRadioSel.replace('X','');;
                        }
			else if((address_url.indexOf("success_stories")!=-1 || address_url.indexOf("successStory")!=-1 || temp_url.indexOf("success_stories")!=-1) && $("#nextAction").val()=="")
			{
				window.top.location="/success/success_stories.php";
				return 1;
			}
			else if(address_url.indexOf("intermediate.php")!=-1 || address_url.indexOf("login.php")!=-1)
			{
				address_url = SITE_URL+"/search/perform?searchId="+searchId+"&currentPage="+currentPage;
				window.top.location = address_url;
				return 1;
			}
			else if($("#nextAction").val() === undefined)
			{
				var nextAction = "/search/perform?searchId="+searchId+"&currentPage="+currentPage;
			}
			else if($("#nextAction").val() != '')
			{
				var nextAction = $("#nextAction").val();
			}
			else
			{
				var nextAction = "/search/perform?searchId="+searchId+"&currentPage="+currentPage;
			}
			window.location = SITE_URL+nextAction;
			return 1;
		}
		
	}

	function checkEmailFormat(str)
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

	function ifLeadValid()
	{
		var emptyField = '';

		$('[name="mini_reg_lead"] input[type="text"]').each
		(
			function()
			{
				if($("#"+this.id).val()=='')
				{
					$("#"+this.id).select();
					emptyField = 1;
					return false;
				}
			}
		);

		if(emptyField == 1)
			return false;

		if(!checkEmailFormat($("#email_val").val()))
		{
			$("#email_val").select();
			return false;
		}
		if(!checkIntegers_sul($("#mobile").val()))
		{
			$("#mobile").select();
			return false;
		}

		$('[name="mini_reg_lead"]  select').each
		(
			function()
			{
//				pri = ($("#"+this.id +" option:selected").text());
				if($("#"+this.id).val()=='')
				{
					$("#"+this.id).select();
					emptyField = 1;
					return false;
				}
			}
		);

		if(emptyField == 1)
			return false;
		else
			return true;
	}
	function LogincheckEnter(e)
        {
                if(e.which == 13)
                {
                        loginUser();
			return true;
                }
        }
        


function searchLoginValidation()
{
	return loginUser();
}
