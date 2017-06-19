function sul_valid()
{
	var docF=document.lead_sulekha;

        document.getElementById('email_err_red').innerHTML="* Email:";
        document.getElementById('match_err_red').innerHTML="* Looking for match for:";
        document.getElementById('gender_err_txt').innerHTML="* Gender:";
        document.getElementById('dob_err_txt').innerHTML="* Date of Birth:";
        document.getElementById('dob_err_txt').innerHTML="* Date of Birth:";
        document.getElementById('dob_err_txt').innerHTML="* Date of Birth:";
        document.getElementById('religion_err_txt').innerHTML="* Religion:";
        document.getElementById('mtongue_err_red').innerHTML="* Mother Tongue/Community:";
        document.getElementById('mobile_err_red').innerHTML="* Mobile No:";
	document.getElementById('mobile_error').style.display="none";

	for (var i=0; i < docF.gender.length; i++)
	{
		if (docF.gender[i].checked)
		{
			var gen_val = docF.gender[i].value;
		}
	}
	if( docF.email.value=="" || docF.day.value=="" || docF.month.value=="" || docF.year.value=="" || docF.mtongue.value=="" || docF.religion_val.value=="" ||  docF.relationship.value=="" || gen_val==undefined || docF.country_Code.value=="" || docF.mobile.value=="")
	{
		if(docF.email.value=="")
			document.getElementById('email_err_red').innerHTML="<font color=red>* Email:</font>";
		if(docF.relationship.value=="")
			document.getElementById('match_err_red').innerHTML="<font color=red>* Looking for match for:</font>";
		if(gen_val==undefined)
			document.getElementById('gender_err_txt').innerHTML="<font color=red>* Gender:</font>";	
		if(docF.day.value=="")
			document.getElementById('dob_err_txt').innerHTML="<font color=red>* Date of Birth:</font>";
		if(docF.month.value=="")
			document.getElementById('dob_err_txt').innerHTML="<font color=red>* Date of Birth:</font>";
		if(docF.year.value=="")
			document.getElementById('dob_err_txt').innerHTML="<font color=red>* Date of Birth:</font>";
		if(docF.religion.value=="")
			document.getElementById('religion_err_txt').innerHTML="<font color=red>* Religion:</font>";
		if(docF.mtongue.value=="")
			document.getElementById('mtongue_err_red').innerHTML="<font color=red>* Mother Tongue/Community:</font>";
		if(docF.mobile.value=="")
			document.getElementById('mobile_err_red').innerHTML="<font color=red>* Mobile No:</font>";	

		document.getElementById('common_error_sul').style.display="inline";
		return false;
	}
        else if(docF.email.value)
        {
                var email_id=document.getElementById('email_val').value;
                if(!checkemail_sul(email_id))
                {
                      document.getElementById('common_error_sul').style.display="none";
                      document.getElementById('email_err_red').innerHTML="<font color=red>* Email:</font>";
                      document.getElementById('email_err').style.display="inline";
                      document.getElementById('email_val').focus();
                      return false;
                }
                else
                      document.getElementById('email_err').style.display="none";
        }

	if(docF.country_Code.value || docF.mobile.value){
		if(!mobile_number_validate()){
			if(docF.email.value=="")
				document.getElementById('common_error_sul').style.display="inline";
			else
				document.getElementById('common_error_sul').style.display="none";
			document.getElementById('mobile_err_red').innerHTML="<font color=red>* Mobile No:</font>";
			return false;
		}
	}	

	document.getElementById('common_error_sul').style.display="none";
	document.getElementById('mobile_error').style.display="none";
	return true;
}

function find_gender_sul()
{
	var docF=document.lead_sulekha;
	var relation=document.getElementById('relationship_val').value;

	if(relation=='2' || relation=='6')
	{
		for (var i=0; i < docF.gender.length-1; i++)
		{
			docF.gender[i].checked=true;
		}
	}
        else if(relation=='2D' || relation=='6D')
	{
		for (var i=1; i < docF.gender.length; i++)
		{
			docF.gender[i].checked=true;
		}
	}
	else
	{
		for (var i=0; i < docF.gender.length; i++)
		{
			docF.gender[i].checked=false;
		}
	}
}

var bugchars = '!#$^&*()+|}{[]?><`%:;/,=~"\'';
function CharsInBag_sul(s)
{
    var i;
    var lchar="";
    for (i = 0; i < s.length; i++)
    {   
        var c = s.charAt(i);
		if(i>0)lchar=s.charAt(i-1);
        if (bugchars.indexOf(c) != -1 || (lchar=="." && c==".")) 
	  return false;
    }
    return true;
}

function isInteger_sul(s)
{ 
    var i;
    for (i = 0; i < s.length; i++)
    {   
        var c = s.charAt(i);
        if ((c >= "0") && (c <= "9") && (c != "."))
	 return false;
    }
    return true;
}

function checkIntegers_sul(no)
{
    var i;
    for (i = 0; i < no.length; i++)
    {
        var c = no.charAt(i);
	if(isNaN(c)) 
		return false;
    }
    return true;
}

function checkemail_sul(str)
{
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		var lastdot=str.lastIndexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}
                if(lstr>40){
                       document.getElementById('email_err_txt').innerHTML="Please check the limit of email address (Max limit: 40 chars)";
                       return false;
                }
                if(lstr<4){
                       document.getElementById('email_err_txt').innerHTML="Please check the limit of email address (Min limit: 4 chars)";
                       return false;
                }

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false
		}
		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr || str.substring(lastdot+1)==""){
		    return false
		}
		 
		 if (str.indexOf(at,(lat+1))!=-1){
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false
		 }

		 if(CharsInBag_sul(str)==false){
		    return false
		 }

		 var arrEmail=str.split("@")
		 var ldot=arrEmail[1].indexOf(".")
		 if(isInteger_sul(arrEmail[1].substring(ldot+1))==false){
		    return false
		 }
 		 return true					
}

function mobile_number_validate()
{
	var docF=document.lead_sulekha;
	country_code =docF.country_Code.value;
	mobile =docF.mobile.value;

	if(country_code!=''){
		lengthcc = country_code.length;				
		if(lengthcc <2){	
			document.getElementById('mobile_error').style.display="inline";
			document.getElementById('mobile_err_txt').innerHTML="Please type in a valid isd code (Min limit: 2 chars)";
			return false;
		}
		strF =country_code.substring(0,1);
		if(strF=='+')
			strL =country_code.substring(1,lengthcc);
		else
			strL =country_code;
		if( ((strF !='+') && (checkIntegers_sul(strL)==false)) || (checkIntegers_sul(strL)==false) ){	
			document.getElementById('mobile_error').style.display="inline";
			document.getElementById('mobile_err_txt').innerHTML="Please type in a valid isd code";
			return false;
		}
	}
	if(mobile!=''){
		if(checkIntegers_sul(mobile)==false){
			document.getElementById('mobile_error').style.display="inline";
			document.getElementById('mobile_err_txt').innerHTML="Please type in a valid mobile number";
			return false;
		}	
		lengthm = mobile.length; 	
		if(lengthm <8){
			document.getElementById('mobile_error').style.display="inline";
			document.getElementById('mobile_err_txt').innerHTML="Please type in a valid mobile number (Min limit: 8 chars)";
			return false;
		}
	}
	document.getElementById('mobile_error').style.display="none";
	return true;
}

