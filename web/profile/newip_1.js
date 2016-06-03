function activate_cityres()
{
	if(document.getElementById)
	{
	        var docF=document.form1;
        	                                                                                         
	        if(document.getElementById("Country_Residence"))
        	        countryresidence=1;
	        if(document.getElementById("City_India"))
        	        cityindia=1;
	        if(document.getElementById("City_USA"))
        	        cityusa=1;
                                                                                                 
                                                                                                 
		if(docF.Country_Residence.value=="51")
		{
			docF.City_India.disabled = false;
			docF.City_USA.disabled = true;
			docF.City_USA.value = "";
		}
		else if(docF.Country_Residence.value=="128")
		{
			docF.City_India.disabled = true;
			docF.City_India.value = "";
			docF.City_USA.disabled = false;
			if(docF.frommarriagebureau.value!=1)
				document.form1.State_Code.value="";
		}
		else
		{
			docF.City_India.disabled = true;
			docF.City_India.value = "";
			docF.City_USA.disabled = true;
                	docF.City_USA.value = "";
                }
		
		if(docF.City_USA.disabled && docF.City_India.disabled)
		{
			if(docF.frommarriagebureau.value!=1)
				document.form1.State_Code.value="";
		}
		setcitycode();
	}
}
        
function validate()
{
	/*
	if(document.getElementById)
	{
		if(document.getElementById('email_span'))
			document.getElementById('email_span').style.color="#000000";
		if(document.getElementById('pwd1_span'))
			document.getElementById('pwd1_span').style.color="#000000";
		document.getElementById('gender_span').style.color="#000000";
		document.getElementById('mstatus_span').style.color="#000000";
		document.getElementById('religion_span').style.color="#000000";
		document.getElementById('caste_span').style.color="#000000";
		document.getElementById('height_span').style.color="#000000";
		document.getElementById('date_span').style.color="#000000";
		document.getElementById('income_span').style.color="#000000";
		if(document.getElementById('phone_span'))
			document.getElementById('phone_span').style.color="#000000";
		if(document.getElementById('phone_span'))
			document.getElementById('mobile_span').style.color="#000000";
		document.getElementById('country_res_span').style.color="#000000";
		document.getElementById('term_span').style.color="#000000";
		document.getElementById('mtongue_span').style.color="#000000";
	}*/

		var docF=document.form1;
	if(document.form1.frommarriagebureau.value!=1)
        {

		if(docF.Email&&trim(docF.Email.value)=="")
		{
			alert("Please specify Email ID");

			if(document.getElementById)
			//	document.getElementById('email_span').style.color="red";

			docF.Email.focus();
			return false;
		}
															    
		if(!checkemail(docF.Email.value))
		{
			alert(docF.Email.value + " is not a valid Email ID");

			//if(document.getElementById)
			//	document.getElementById('email_span').style.color="red";

			docF.Email.focus();
			return false;
		}

															 
		if(trim(docF.Password1.value)=="")
		{
			alert("Please specify Password");

			//if(document.getElementById)
			//	document.getElementById('pwd1_span').style.color="red";
			docF.Password1.focus();
			return false;
		}
		else if(docF.Password1.value.length < 5 || docF.Password1.value.length > 40)
                {
			alert("The length of Password should be 5-40 characters");
                        //if(document.getElementById)
                                //document.getElementById('pwd1_span').style.color="red";
                        docF.Password1.focus();
                        return false;
                }
	}
		if(docF.Gender.value=="")
		{
			alert("Please specify Your Gender");

			//if(document.getElementById)
			//	document.getElementById('gender_span').style.color="red";

			docF.Gender.focus();
			return false;
		}

		if(docF.Marital_Status.value=="")
		{
			alert("Please specify Marital Status");

			//if(document.getElementById)
			//	document.getElementById('mstatus_span').style.color="red";

			docF.Marital_Status.focus();
			return false;
		}
		
		if(docF.Mtongue.value=="")
                {
                        alert("Please specify Mother Tongue");
                        //if(document.getElementById)
                         //       document.getElementById('mtongue_span').style.color="red";
                        docF.Mtongue.focus();   
 	                     return false;
                }
	 
													
		if(docF.Religion.value=="")
		{
			alert("Please specify Religion");

			//if(document.getElementById)
			//	document.getElementById('religion_span').style.color="red";

			docF.Religion.focus();
			return false;
		}

		if(docF.Caste.value=="")
		{
			alert("Please specify Caste");

			//if(document.getElementById)
			//	document.getElementById('caste_span').style.color="red";

			docF.Caste.focus();
			return false;
		}
		if(docF.Caste.value==0)
		{
			alert("Caste and Religion Can't be Same");

			docF.Caste.focus();
                        return false;	
		}											 

		if(docF.Height.value=="")
		{
			alert("Please specify Height");

			//if(document.getElementById)
			//	document.getElementById('height_span').style.color="red";

			docF.Height.focus();
			return false;
		}

		if(docF.Day.value=="" || docF.Month.value=="" || docF.Year.value=="")
		{
			alert("Please specify Date of birth properly");

			//if(document.getElementById)
		//		document.getElementById('date_span').style.color="red";

			docF.Day.focus();
			return false;
		}
													 
		if(!validate_date(docF.Day.value,docF.Month.value,docF.Year.value))
		{
			alert(docF.Day.value + "/" + docF.Month.value + "/" + docF.Year.value + " is not a valid date");

			//if(document.getElementById)
			//	document.getElementById('date_span').style.color="red";

			docF.Day.focus();
			return false;
		}

		if(docF.Income.value=="")
		{
			alert("Please specify your Annual Income");

			//if(document.getElementById)
			//	document.getElementById('income_span').style.color="red";

			docF.Income.focus();
			return false;
		}


		if(docF.Country_Residence.value=="")
		{
			alert("Please specify Country of Residence");

			//if(document.getElementById)
			//	document.getElementById('country_res_span').style.color="red";

			docF.Country_Residence.focus();
			return false;
		}

		if(docF.Country_Residence.value==51 && docF.City_India.value=="")
		{
			alert("Please specify Current City of Residence");

			//if(document.getElementById)
			//	document.getElementById('city_span').style.color="red";

			docF.City_India.focus();
			return false;
		}

		//if(docF.Country_Residence.value==51 || docF.Country_Residence.value==128)
		if(docF.frommarriagebureau.value!=1&&docF.Country_Residence.value==51)
        	{
			if(docF.State_Code.value=="")
			{       alert("Please specify State Code by selecting City from Current City of Residence field.");
																     
				//if(document.getElementById)
				//{
				//	document.getElementById('phone_span').style.color="red";
				//}                                                                                                                              
				docF.State_Code.focus();
				return false;
			}
        	}
                                                                                                                             


		if(docF.Country_Residence.value==128 && docF.City_USA.value=="")
		{
			alert("Please specify Current City of Residence");

			//if(document.getElementById)
			//	document.getElementById('city_span').style.color="red";

			docF.City_USA.focus();
			return false;
		}
	if(document.form1.frommarriagebureau.value!=1)
        {

		if(trim(docF.Phone.value)=="" && trim(docF.Mobile.value)=="")
        	{
			alert("Please specify Phone No or Mobile No");
			/*if(document.getElementById)
			{
				document.getElementById('phone_span').style.color="red";
				document.getElementById('mobile_span').style.color="red";
			}*/
			docF.Phone.focus();
			return false;
        	}

		if(trim(docF.Phone.value)!="" && docF.Country_Code.value=="")
        	{
                	alert("Please specify Country Code by selecting Country from Country of Residence.");
                                                                                                                             
			/*if(document.getElementById)
			{
				document.getElementById('phone_span').style.color="red";
			}*/
                                                                                                                             
			docF.Phone.focus();
			return false;
        	}
                                                                                                                             
		if(trim(docF.Mobile.value)!="" && docF.Country_Code_Mob && docF.Country_Code_Mob.value=="")
		{
			alert("Please specify Country Code by selecting Country from Country of Residence.");
																     
			/*if(document.getElementById)
			{
				document.getElementById('mobile_span').style.color="red";
			}*/
																     
			docF.Mobile.focus();
			return false;
		}
	}
		
		if(!docF.termscheckbox.checked)
		{
			alert("You have to agree to the terms and conditions before continuing");

			/*if(document.getElementById)
				document.getElementById('term_span').style.color="red";*/

			docF.termscheckbox.focus();
			return false;
		}
		
	return true;
}

function validate_offline()
{	
	var docF=document.form1;
	if(docF.Email&&trim(docF.Email.value)=="")
        {
                alert("Please specify Email ID");

		if(document.getElementById)
			document.getElementById('email_span').style.color="red";

		docF.Email.focus();
                return false;
        }

return true;
}

function togglecheck(cb)
{
	if(cb.checked==true)
		cb.checked=false;
	else
		cb.checked=true;
}
