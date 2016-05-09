function typecheck(a)
{
		
                                                                                                                             
                if(document.form1.Relationship[a].value!=1)
                        document.getElementById("writebywhom").innerHTML="Write About "+gender_type;
                else
                        document.getElementById("writebywhom").innerHTML="Write About yourself";
                                                                                                                             
                                                                                                                             
}
function changegender(a)
{
	var val=document.getElementById("writebywhom").innerHTML;
	if(val.indexOf("your")==-1)
		document.getElementById("writebywhom").innerHTML="Write About "+a;
	else
		document.getElementById("writebywhom").innerHTML="Write About yourself";
	 gender_type=a;
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

function validate_date(day,month,year)
{
	// since jan equals one and not zero, hence thirteen elements in the array.  
	var no_of_days_in_month = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	
	if (month >= 1 && month <= 12 && day >=  1 && day <= 31 && year >= 0)
	{
		//Handling february, special case. 
		if (month == 2)
		{
			if ( (year%4==0 && year%100 != 0) || year%400 == 0 )
				no_of_days_in_month[month]=29
		}

		if (day >= 1 && day <= no_of_days_in_month[month])
			return true;
		else
			return false;
	}
	else
		return false;

}

function PopSPEC(thisform,element1,element2,registration_page) 
{	
	var docF=document.form1;

	//added by sriram for enabling the caste dropdown
	var dont_display;
	var div_id = document.getElementById('caste_span_visible');
	if(registration_page=="Y")
	{
		if(typeof(div_id) != "undefined")
			document.getElementById('caste_span_visible').style.display="block";
	}
	//added by sriram for enabling the caste dropdown

	var len_el=docF.elements.length;
	for(i=0;i<len_el;i++)
        {
                if(docF.elements[i].name=="Religion")
                        {element1=i;}
                if(docF.elements[i].name=="Caste")
                        {element2=i;}
        }
	if(thisform.value != "")
	{		
		var c,spec;
		var len_religion = docF.elements[element1].options.length;
		for(var m1=0;m1<len_religion;m1++) {
		if (docF.elements[element1].options[m1].selected == true) {
		c = docF.elements[element1].options[m1].value;
		}
		}
		docF.elements[element2].options.length = 0;

		var str      =c.split("|X|");
		var spec_val =str[1].split("#");


		for(var k=-1;k<spec_val.length;k++) 
		{

			if(k==-1)
			{
				var s = spec_val[0];
				var val=s.split("$");
				var opt = new Option();
				opt.text=val[1];
				opt.value=val[0];

				var s="$Select any One Option";
			}
			else
			{
				var s = spec_val[k];
			}

			if(!((k==-1) && ((opt.value==153)||(opt.value==148)||(opt.value==1)||(opt.value==162))))
			{
				if(s)
				{
					var val=s.split("$");
					var opt = new Option();
					opt.text=val[1];
					opt.value=val[0];

					if(k==0)
					{
						if((opt.value==14)||(opt.value==149)||(opt.value==154)||(opt.value==173)||(opt.value==2))
						{
							opt.disabled=true;	
							opt.style.color = "graytext"; 
							opt.value=0;
							if(registration_page=='Y')
								dont_display = 1;
						}
					}
					else
						dont_display = 0;

					if(!dont_display)
						docF.elements[element2].options[docF.elements[element2].options.length] = opt;
				}
			}
		}
		//added by sriram to disable caste dropdown when no caste is there to select.
		if(registration_page=="Y")
		{
			if(typeof(spec_val[1])=="undefined" && typeof(div_id) != "undefined")
				document.getElementById('caste_span_visible').style.display="none";
		}
	}

	return true;
}

function checkemail(emailadd)
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
function validate_phone_mobile(phone,mobile)
{
        if((trim(phone)=="") && (trim(mobile)==""))
        {
		alert("Please specify Mobile or phone no. ");
                return "PM";
        }
	else
        {
                if(trim(phone)!="")
                {
                        if(trim(phone).length < 6)
                        {
                                alert("The length of Phone no. should be atleast 6 digits.");
                                return "P";
                        }
                        var x = phone;
                        var filter  = /^[0-9]+$/;
                        if (!filter.test(x))
                        {
                                alert("Please specify a valid Phone no. Only Numbers are allowed.");
                                return "P";
                        }
                }
                if(trim(mobile)!="")
                {
                        if(trim(mobile).length < 10)
                        {
                                alert("The length of Mobile no. should be atleast 10 digits.");
                                return "M";
                        }
                        var x = mobile;
                        var filter  = /^[0-9]+$/;
                        if (!filter.test(x))
                        {
                                alert("Please specify a valid Mobile no. Only Numbers are allowed.");
				return "M";
                        }
		}
	}
	return "OK";
}
