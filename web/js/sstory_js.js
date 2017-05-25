function display_success_error(message)
{
	if(message)
	{
		if(message=="photo")
			{
				document.getElementById("error_msg").style.visibility="visible";
	       		        document.getElementById("error_msg_text").innerHTML="The photo should be in .jpg or .gif format and less	than 4 MB!";

				document.getElementById("wd_photo").style.color = "#E40410";
				document.getElementById("stry").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";

			}
			else if(message=="email_invalid")
			{
				document.getElementById("error_msg").style.visibility="visible";
                     	        document.getElementById("error_msg_text").innerHTML="The spouse email entered for spouse is not registered with us!";

				document.getElementById("spse_email").style.color = "#E40410";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
			}
			else if(message=="user_invalid")
			{
				document.getElementById("error_msg").style.visibility="visible";
                 	        document.getElementById("error_msg_text").innerHTML="The spouse userid entered for spouse is not registered with us!";

				document.getElementById("spse_id").style.color = "#E40410";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
			}
			else if(message=="same_gender")
			{
				document.getElementById("error_msg").style.visibility="visible";
                 	        document.getElementById("error_msg_text").innerHTML="User id is of the same gender!";

				document.getElementById("spse_id").style.color = "#E40410";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
			}
			else if(message=="not_compatible")
			{
			        document.getElementById("error_msg").style.visibility="visible";
                 	        document.getElementById("error_msg_text").innerHTML="This is an invalid success story !";
				
				document.getElementById("spse_id").style.color = "#E40410";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
			}
			else if(message=="invalid_date")
			{
				document.getElementById("error_msg").style.visibility="visible";
                                document.getElementById("error_msg_text").innerHTML="This is invalid date !";

                                document.getElementById("wd_dt").style.color = "#E40410";
                                document.getElementById("spse_id").style.color = "#726F6F";
                                document.getElementById("spse_email").style.color = "#726F6F";
                                document.getElementById("wd_photo").style.color = "#726F6F";
                                document.getElementById("stry").style.color = "#726F6F";
                                document.getElementById("spse_name").style.color = "#726F6F";
                                document.getElementById("spse1_name").style.color = "#726F6F";
                                document.getElementById("addr").style.color = "#726F6F";
			}
			else if(message=="verified")
			{
				var parDoc = window.document;
				
         			parDoc.getElementById("mainform").innerHTML="<div id=\"mainform\" class=\"overlay_wrapper_775px\" style=\"background-color:white;\"><div class=\"top\"><div class=\"text white b widthauto\">Thanks!</div><div class=\"fr div_close_button_green\" style=\"cursor:pointer\" onclick=\"top.window.location='/profile/logout.php';$.colorbox.close();return false;\">&nbsp;</div></div><div class=\"scrollbox2 t12 \" style=\"background:white;width:760px\"><div style=\"width:94%\"><p style=\"margin:50px 0 15px;\">Thank you for sharing your success story with us. It will be made live soon. To express our gratitude we shall be sending you a surprise gift.</p><p>Wishing you a very happy married life.</p><p style=\"margin-top:25px;\"><img src=\"IMG_URL/success/images/js_team_img_v1.gif\" alt=\"jeevansathi team\" title=\"jeevsansathi team\"></p></div></div><div class=\"clear\"></div></div>";
			}
		}
}
function disableButton()
{
	if(document.getElementById('main_button'))
	{	document.getElementById('main_button').disabled=true;
		document.getElementById('main_button').className="fs13 b gray";
	}
}
function enableButton()
{
	if(document.getElementById('main_button'))
	{
		document.getElementById('main_button').disabled=false;
		document.getElementById("main_button").className=" btn_view fs13 b cp ";
	}
}

function chu() 
{
	var elements = new Array();
	for (var i=0;i<arguments.length;i++) 
	{
		var element = arguments[i];
		if (typeof element == 'string') 
			element = document.getElementById(element);
		if (arguments.length == 1) 
			return element;
		elements.push(element);
	}
	return elements;
} 

var BoxHeightsNew = 
{
	maxh: 0,
	boxes: Array(),num: 0,op_test: false,

	equalise: function() 
	{
		this.num = arguments.length;
		//alert(this.num);
		for (var i=0;i<this.num;i++) 
			if (!chu(arguments[i]))                                 
			{
				if(i==2)
					this.num = arguments.length-1;
				else
					return;                                 
			}

			this.boxes = arguments;
			this.maxheight();
			for (var i=0;i<this.num;i++) 
				chu(arguments[i]).style.height = this.maxh+"px";
	},
		maxheight: function() 
		{
			var heights = new Array();
			for (var i=0;i<this.num;i++) 
			{
				if (navigator.userAgent.toLowerCase().indexOf('opera') == -1) 
				{
					heights.push(chu(this.boxes[i]).scrollHeight);
				} 
				else 
				{
					heights.push(chu(this.boxes[i]).offsetHeight);
				}
			}
			heights.sort(this.sortNumeric);
			this.maxh = heights[this.num-1];
		},
		sortNumeric: function(f,s) 
		{
			return f-s;
		}
}

function successOnLoad()
{
	//return ;
	BoxHeightsNew.equalise('one','two');BoxHeightsNew.equalise('three','four');BoxHeightsNew.equalise('five','six');BoxHeightsNew.equalise('seven','eight');BoxHeightsNew.equalise('nine','ten');BoxHeightsNew.equalise('eleven','twelve');BoxHeightsNew.equalise('thirteen','fourteen');BoxHeightsNew.equalise('fifteen','sixteen');BoxHeightsNew.equalise('seventeen','eighteen');BoxHeightsNew.equalise('ninteen','twenty');BoxHeightsNew.equalise('twentyone','twentytwo');BoxHeightsNew.equalise('twentythree','twentyfour')
	BoxHeightsNew.equalise('a1','a2');BoxHeightsNew.equalise('a3','a4');BoxHeightsNew.equalise('a5','a6');BoxHeightsNew.equalise('a7','a8');BoxHeightsNew.equalise('a9','a10');BoxHeightsNew.equalise('a11','a12');BoxHeightsNew.equalise('a13','a14');BoxHeightsNew.equalise('a15','a16');BoxHeightsNew.equalise('a17','a18');BoxHeightsNew.equalise('a19','a20');BoxHeightsNew.equalise('a21','a22');BoxHeightsNew.equalise('a23','a24')
	BoxHeightsNew.equalise('b1','b2');BoxHeightsNew.equalise('b3','b4');BoxHeightsNew.equalise('b5','b6');BoxHeightsNew.equalise('b7','b8');BoxHeightsNew.equalise('b9','b10');BoxHeightsNew.equalise('b11','b12');BoxHeightsNew.equalise('b13','b14');BoxHeightsNew.equalise('b15','b16');BoxHeightsNew.equalise('b17','b18');BoxHeightsNew.equalise('b19','b20');BoxHeightsNew.equalise('b21','b22');BoxHeightsNew.equalise('b23','b24');
}
		function trim(inputString) 
		{
		   if (typeof inputString != "string") { return inputString; }
		   var retValue = inputString;
		   var ch = retValue.substring(0, 1);
		   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
		      retValue = retValue.substring(1, retValue.length);
		      ch = retValue.substring(0, 1);
		   }
		   ch = retValue.substring(retValue.length-1, retValue.length);
		   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
		      retValue = retValue.substring(0, retValue.length-1);
		      ch = retValue.substring(retValue.length-1, retValue.length);
		   }
		   while (retValue.indexOf("  ") != -1) {
		      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
		   }
		   return retValue;
		}

		function check_ss()
		{
			if(trim(eval("document.submit_ss.spouse_name.value"))=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please provide spouse name";

				document.getElementById("spse_name").style.color = "#E40410";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				return false;
			}
			else if(trim(eval("document.submit_ss.spouse1_name.value"))=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please provide Your name";

				document.getElementById("spse1_name").style.color = "#E40410";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				return false;
			}
			else if(trim(eval("document.submit_ss.spouse_id.value"))=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please provide spouse userid";
				document.getElementById("spse_id").style.color = "#E40410";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				return false;
			}
			else if(trim(eval("document.submit_ss.spouse_email.value"))=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please provide spouse email";
				document.getElementById("spse_email").style.color = "#E40410";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				return false;
			}
			else if(trim(eval("document.submit_ss.contact_address.value"))=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please provide a contact address for your gift to be delivered";
				document.getElementById("addr").style.color = "#E40410";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				return false;
			}
			else if(eval("document.submit_ss.w_year.value")=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please tell us your wedding date & year";
				document.getElementById("wd_dt").style.color = "#E40410";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				document.getElementById("stry").style.color = "#726F6F";
				return false;

			}
			else if(trim(eval("document.submit_ss.ss_story.value"))=="")
			{
				document.getElementById("error_msg").style.visibility="visible";
				document.getElementById("error_msg_text").innerHTML="Please tell us your story";
				document.getElementById("stry").style.color = "#E40410";
				document.getElementById("wd_dt").style.color = "#726F6F";
				document.getElementById("spse_name").style.color = "#726F6F";
				document.getElementById("spse1_name").style.color = "#726F6F";
				document.getElementById("spse_id").style.color = "#726F6F";
				document.getElementById("spse_email").style.color = "#726F6F";
				document.getElementById("addr").style.color = "#726F6F";
				document.getElementById("wd_photo").style.color = "#726F6F";
				return false;

			}
			document.getElementById("stry").style.color = "#726F6F";
			document.getElementById("wd_dt").style.color = "#726F6F";
			document.getElementById("spse_name").style.color = "#726F6F";
			document.getElementById("spse1_name").style.color = "#726F6F";
			document.getElementById("spse_id").style.color = "#726F6F";
			document.getElementById("spse_email").style.color = "#726F6F";
			document.getElementById("addr").style.color = "#726F6F";
			document.getElementById("wd_photo").style.color = "#726F6F";
			document.submit_ss.submit();

		}

		function hide_error_msg()
		{
			document.getElementById("error_msg").style.visibility="hidden";
		}

		
		

		function close_window()
		{
			window.location.href="~$SITE_URL`/P/logout.php"; // Redirect at logout page After Closing the Layer
		}
		
		function submitForm(clickedName)
		{
			if(clickedName=="Accept")
				document.screen.action = "/operations.php/storyScreening/accept";
			else if(clickedName=="Reject")
				document.screen.action = "/operations.php/storyScreening/reject";
			else if(clickedName=="Hold")	
				document.screen.action = "/operations.php/storyScreening/hold";
			else if(clickedName=="Skip")	
				document.screen.action = "/operations.php/storyScreening/skipStory";	
			document.screen.submit();
		}
