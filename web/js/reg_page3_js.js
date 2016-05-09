	function change(click)
	{
		if(click == 1)
		{
			$("#create").css('display',"block");
			$("#upload").css('display',"none");
		}
		if(click == 2)
		{
			$("#create").css('display',"none");
			$("#upload").css('display',"block");
		}
	}
	function feed_astrodata(theURL,winName,features)
	{
			var docF=document.form1;
			if(docF.display_horo.checked)
			{
					if(document.getElementById)
					{
							document.getElementById("frame_show").style.display = 'block';
							document.getElementById("show_hide_buttons").style.display = 'none';
							document.getElementById("hide_compul_field").style.display = 'none';
					}
					document.getElementById('astro_data').style.display="none";
					var astro_detail = document.getElementById('astro_data_fed');
					astro_detail.value='Y';
			}
			else
			{
					if(document.getElementById("frame_show"))
					{
							document.getElementById("frame_show").style.display = 'none';
							document.getElementById("show_hide_buttons").style.display = 'block';
							document.getElementById("hide_compul_field").style.display = 'block';
					}
					document.getElementById('astro_data').style.display="block";
					docF.Country_Birth.disabled=false;
					docF.City_Birth.disabled=false;
					docF.Hour_Birth.disabled=false;
					docF.Min_Birth.disabled=false;
			}
	}
	function showform()
	{
			var docF=document.form1;

			if (document.getElementById)
			{
					if (docF.display_horo.checked)
					{
							docF.Country_Birth.disabled=true;
							docF.City_Birth.disabled=true;
							docF.Hour_Birth.disabled=true;
							docF.Min_Birth.disabled=true;
					}
					else
					{
							docF.Country_Birth.disabled=false;
							docF.City_Birth.disabled=false;
							docF.Hour_Birth.disabled=false;
							docF.Min_Birth.disabled=false;
					}
			}
	}
	function disable_minutes()
	{
			var docF = document.form1;
			if(docF.Hour_Birth.value=="")
					docF.Min_Birth.disabled = true;
			else
					docF.Min_Birth.disabled = false;
	}

	function trim_newline(string)
	{
		return string.replace(/^\s*|\s*$/g, "");
	}

	function trim(inputString) {
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

	function changeCount()
	{
		var docF=document.form1;
		var str=new String();
		str=trim(docF.reg_familyinfo.value);
		str=trim_newline(str);
		document.getElementById("about_family_count").disabled=false;
		if(str.length >= 100)
		{
			document.getElementById("about_family_count").style.color = '#00BB00';
		}
		else
		{
			document.getElementById("about_family_count").style.color = '#00BB00';
		}
		docF.wordcount.value=str.length;
	}
	function validate_name(flag){
			var docF=document.form1;
		var err_i=0;
			var name_of_user=document.getElementById("reg_name_of_user").value;

		if(flag!=2){
			var allowed_chars = /^[a-zA-Z\.\,\s\']+$/;
			var name_of_user_invalid_chars = 0;

			if(name_of_user != "")
			{
				if(!allowed_chars.test(name_of_user) || trim(name_of_user)=='')
					name_of_user_invalid_chars = 1;
			}
			
			if(name_of_user_invalid_chars)
			{
				err_i++;
				if(flag==3)
				{
					if(name_of_user_invalid_chars)
						document.getElementById("reg_name_of_user").focus();
				}
				document.getElementById("reg_name_of_user").style.color="red";
				document.getElementById("name_of_user_submit_err").style.display = "block";
				return false;
			}
			else
			{
				document.getElementById("reg_name_of_user").style.color="#000000";
				document.getElementById("name_of_user_submit_err").style.display = "none";
			}
		}
		return true;
	}
	
	function show_loader()
	{
		document.getElementById("horo_iframe").style.display="none";
		document.getElementById("horo_loader").style.display="inline";
	}

	function horo(a)
	{
		if(a=='OK')
		{
			document.getElementById("horo_loader").style.display="none";
			document.getElementById("horo_section").style.display="none";
			document.getElementById("horo_message").style.display="block";
			document.getElementById("horo_iframe").style.display="none";
			document.getElementById("horo_error_message").style.color="#000000";
		}
		else if(a=="ERROR")
		{
			document.getElementById("horo_loader").style.display="none";
			document.getElementById("horo_iframe").style.display="inline";
			document.getElementById("horo_error_message").style.color="red";
			show_horo_section();
		}
		
	}
	function show_horo_section()
	{
		var site="/profile/horoscope_browse.php"
		document.getElementById("horo_message").style.display="none";
		document.getElementById("horo_section").style.display="inline";
		document.getElementById("horo_iframe").style.display="inline";
		document.getElementById("horo_iframe").src=site;
	}
	function reset_value()
	{
		document.getElementById("horo_iframe").value="";
	}
	
	function populate_married_count(count_for)
	{
		var total_count = $("#reg_t_"+count_for).val();
		var married_count_id = $("#reg_m_" + count_for);

		married_count_id.find('option').remove();
		married_count_id.append('<option value="">Select</option>');
		for(var i=0;i<=total_count;i++)
		{
			if(i>3)
				married_count_id.append('<option value="3+">3+</option>');
			else
				married_count_id.append('<option value="'+i+'">'+i+'</option>');
		}
	}
	
	function married_field_brothers()
	{
		var bro=$('#reg_t_brother').val();
		if(bro>0)
		{
			  $('#married_field').css("display","block");
		}
		else
			  $('#married_field').css("display","none");
	
		populate_married_count('brother');
	}
	function married_field_sisters()
	{
		var sis=$('#reg_t_sister').val();
		if(sis>0)
		{
			  $('#married_field_sis').css("display","block");
		}
		else
			  $('#married_field_sis').css("display","none");
	
		populate_married_count('sister');
	}
	function hide_amrit()
	{
		var gender="~$GENDER`";
		var amrit_val=$('#form1').find('input[name="reg[amritdhari]"]:checked').val();
		if(amrit_val=='Y')
		{
			$('.amrit').css('display','none');
			$('.sikh_radio').prop('checked',false);
		}
		else if(amrit_val=='N')
		{
				$('.amrit').css('display','inline');
			}
	 }
