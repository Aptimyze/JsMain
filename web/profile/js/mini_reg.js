var docF = "";

function dID(arg)
{
        return document.getElementById(arg);
}

function lead_valid()
{
	var docF=document.mini_reg_lead;

        document.getElementById('email_err_red').innerHTML="* Email:";
        document.getElementById('match_err_red').innerHTML="* I am looking for:";
        document.getElementById('dob_err_txt').innerHTML="* Date of Birth of Boy / Girl:";
        document.getElementById('dob_err_txt').innerHTML="* Date of Birth of Boy / Girl:";
        document.getElementById('dob_err_txt').innerHTML="* Date of Birth of Boy / Girl:";
        document.getElementById('mtongue_err_red').innerHTML="* Mother Tongue/Community:";
        document.getElementById('caste_err_red').innerHTML="* Caste:";
        document.getElementById('mobile_err_red').innerHTML="* Mobile No:";
	document.getElementById('mobile_error').style.display="none";
	document.getElementById('email_err').style.display="none";

	
	if( docF.email.value=="" || docF.day.value=="" || docF.month.value=="" || docF.year.value=="" || docF.mtongue.value=="" ||  docF.relationship.value=="" || docF.country_Code.value=="" || docF.mobile.value=="" || docF.caste.value=="")
	{
		if(docF.email.value=="")
			document.getElementById('email_err_red').innerHTML="<font color=red>* Email:</font>";
		if(docF.relationship.value=="")
			document.getElementById('match_err_red').innerHTML="<font color=red>* I am looking for:</font>";
		if(docF.day.value=="")
			document.getElementById('dob_err_txt').innerHTML="<font color=red>* Date of Birth of Boy / Girl:</font>";
		if(docF.month.value=="")
			document.getElementById('dob_err_txt').innerHTML="<font color=red>* Date of Birth of Boy / Girl:</font>";
		if(docF.year.value=="")
			document.getElementById('dob_err_txt').innerHTML="<font color=red>* Date of Birth of Boy / Girl:</font>";
		if(docF.caste.value=="")
			document.getElementById('caste_err_red').innerHTML="<font color=red>* Caste:</font>";
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
	
	if(lstr>40){
	       document.getElementById('email_err_txt').innerHTML="Please check the limit of email address (Max limit: 40 chars)";
	       return false;
	}

	if(lstr<4){
	       document.getElementById('email_err_txt').innerHTML="Please check the limit of email address (Min limit: 4 chars)";
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
	       document.getElementById('email_err_txt').innerHTML="Please enter valid Email-Id";
	       return false;
	}

	if(idLength < '4' && domainName=='rediff' || domainName=='yahoo')
	{
	       document.getElementById('email_err_txt').innerHTML="Please enter valid Email-Id";
	       return false;
	}

	if(isInteger_sul(arrEmail[1].substring(ldot+1))==false){
	    return false
	}
	
	return true					
}

function mobile_number_validate()
{
	var docF=document.mini_reg_lead;
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
		var firstChar=mobile.substring(0,1);
		var lengthm = mobile.length; 	

		if(country_code=='+91' || country_code=='91')
		{
			if(lengthm != 10)
			{
				document.getElementById('mobile_error').style.display="inline";
				document.getElementById('mobile_err_txt').innerHTML="Please type in a valid mobile number";
				return false;
			}
			else 
			{
				if(!(firstChar=='7' || firstChar=='8' || firstChar=='9'))
				{
					document.getElementById('mobile_error').style.display="inline";
					document.getElementById('mobile_err_txt').innerHTML="Please type in a valid mobile number";
					return false;
				}
			}

		}
		else
		{
			if(lengthm <7){
				document.getElementById('mobile_error').style.display="inline";
				document.getElementById('mobile_err_txt').innerHTML="Please type in a valid mobile number (Min limit: 7 chars)";
				return false;
			}
		}
	}
	document.getElementById('mobile_error').style.display="none";
	return true;
}

/* Ajax Call  */

function createNewXmlHttpObject()
{
        var req = false;
        // For Safari, Firefox, and other non-MS browsers
        if(window.XMLHttpRequest)
        {
                try
                {
                        req = new XMLHttpRequest();
                }
                catch (e)
                {
                        req = false;
                }
        }
        // For Internet Explorer on Windows
        else if(window.ActiveXObject)
        {
                try
                {
                        req = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                req = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                req = false;
                        }
                }
        }
        return req;
}

function get()
{
	var value = "email_val=" + encodeURI( document.getElementById("email_val").value ) + "&mobile=" + encodeURI( document.getElementById("mobile").value );
        var req = createNewXmlHttpObject();
	var docF = document.mini_reg_lead;
        var site_url = docF.site_url.value;
        var to_post=site_url+"/profile/mini_reg.php?action=lead_capture&"+value;
        req.open("POST",to_post,true);
        req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        req.send(to_post);
} 

