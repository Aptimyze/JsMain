var AJAXForms = false;
var LastField = null;
var isIE = false;
var emailok=false;
var username="";
var mstatus="";
var gender="";
var pid="123";
var day="";
var month="";
var flag_2nd=true;
var flag_2ndr=true;
var checksum="";
var flag_3rd=true;
var flag_4th=true;
var serveremail=true;
var phonenook=true,mobilenook=true;
var year="";
var hit_source="";
var tieup_source="";
var OS;
var version;
var browser;
var answer="Loading";
window.onerror = trapError;
function trapError(msg, URI, ln)
{
	//alert("Error : \n Msg:"+msg+" \n line: "+ln);
	return true;
}
function assignMS()
{
	document.form1.maritalstatus.value=mstatus;
	document.form1.gender.value=gender;
}

function getmstatus()
{
	return mstatus;
}
if (window.XMLHttpRequest)
{
	AJAXForms = new XMLHttpRequest();
}
function getkey(e)
{
	if (window.event)
	   return window.event.keyCode;
	else if (e)
	   return e.which;
	else
	return null;
}

function findage()
{
	ck=checkgender(document.form1.Year);
	if(ck==false)
		return;
	l=document.form1;
	var curdate = new Date()
	var month = curdate.getMonth();
	var year = curdate.getYear();
	year+=1900;
	var date = curdate.getDate();
	var y=l.Year.value;
	var m=l.Month.value
	var d=l.Day.value;
	var g=l.Gender.value;
	if(m<month)
	{
		c=year-(y-1);
	}
	else
		c=year-(y);
	if(c<21 && g=='M')
	{
		document.getElementById("younger").innerHTML= "<span class=\"red\">Male profiles less than 21 years of age cannot be registered</span><br>";
		return false;
	}
	else if(c<18 && g=='F')
	{
		document.getElementById("younger").innerHTML= "<span class=\"red\">Female profiles less than 18 years of age cannot be registered</span><br>";
		return false;
	}
	else
	{
		document.getElementById("younger").innerHTML= "";
		return true;
	}
}

function Submit1()
{
	l=document.form1;
	if (window.XMLHttpRequest)
        {
                // branch for IE/Windows ActiveX version
        }
        else if (window.ActiveXObject)
        {
                AJAXForms = new ActiveXObject("Microsoft.XMLHTTP");
        }
	//to store into the dbase
	mstatus=l.Marital_Status.value;
	day=l.Day.value;
	month=l.Month.value;
	year=l.Year.value;
	gender=l.Gender.value;
	AJAXForms.onreadystatechange = process4username;
	//alert(l.GET_SMS.value);
	var sms='N',showph='Y',showm='Y',chb1='U',chb2='U',chb3='U';
	if(l.GET_SMS.checked)sms='Y';
	if(l.Showphone.checked)showph='N';
	if(l.Showmobile.checked)showm='N';
	if(l.checkboxalert1.checked)chb1='A';
	if(l.checkboxalert2.checked)chb2='S';
	if(l.checkboxalert3.checked)chb3='S';
	var em=escape(l.Email.value);
	var pass=escape(l.Password1.value);
	var pphone=escape(l.Phone.value);
	var mmobile=escape(l.Mobile.value);
	AJAXForms.open("GET","/profile/ip_new_1.php?camefrom=ajax&Email="+em+"&Password1="+pass+"&Gender="+l.Gender.value+"&Marital_Status="+l.Marital_Status.value+"&Caste="+l.Caste.value+"&Height="+l.Height.value+"&Day="+l.Day.value+"&Month="+l.Month.value+"&Year="+l.Year.value+"&Income="+l.Income.value+"&Phone="+pphone+"&Mobile="+mmobile+"&Country_Residence="+l.Country_Residence.value+"&City_India="+l.City_India.value+"&City_USA="+l.City_USA.value+"&termscheckbox="+l.termscheckbox.value+"&Showphone="+showph+"&Showmobile="+showm+"&checkboxalert1="+chb1+"&checkboxalert2="+chb2+"&checkboxalert3="+chb3+"&tieup_source="+tieup_source+"&hit_source="+hit_source+"&State_Code="+l.State_Code.value+"&Country_Code="+l.Country_Code.value+"&GET_SMS="+sms+"&Mtongue="+l.Mtongue.value);
	AJAXForms.send('null');
	if(flag_2nd)
	{
		launch4("/profile/ip_new_2.php?cfrom=page1","get");
                //flag_2nd=false;
	}
}

function get2ndPage()
{
	if(flag_2nd)
	{
		var someDelay=20000;
		//setTimeout('testing()', someDelay);
		launch("/profile/ip_new_2.php?cfrom=page1","get");
		flag_2nd=false;
	}
	return;
}
function testing()
{
        launch("/profile/ip_new_2.php?cfrom=page1","get");
        return false;
}

function showpage_htm(num)
{
	document.getElementById('my_div').innerHTML = answer;
        if(answer=="Loading")
        {
                if(num==1)launch4("/profile/ip_new_2.php?cfrom=page1","get");
                else if(num==2)launch4("/profile/ip_new_3.php","get");
                else if(num==3)
                        launch4("/profile/ip_new_4.php?gender="+gender+"&maritalstatus="+mstatus,"get")
        }
	window.scrollTo(0,0);
	if(num==2)
		document.form1.Educ_Qualification.focus();
	else if(num==3)
	{
		document.getElementById('Caste_desired2').focus();
	}
	answer="Loading";
}
function Submit2()
{
        l=document.form1;
        if (window.XMLHttpRequest)
        {
                // branch for IE/Windows ActiveX version
        }
        else if (window.ActiveXObject)
        {
                AJAXForms = new ActiveXObject("Microsoft.XMLHTTP");
        }
	radio_value="";
	for (counter = 0; counter < l.radioprivacy.length; counter++)
        {
                if (l.radioprivacy[counter].checked)
                {
                        radio_caste = true;
			radio_value = l.radioprivacy[counter].value;
			break;
                }
        }
	gender=l.Gender.value;
	day=l.Day.value;
	month=l.Month.value;
	year=l.Year.value;
	var infor=escape(l.Information.value);
	//alert("value : "+infor);
	//launch4("ip_new_2.php?cfrom=page2&username="+l.username.value+"&id="+pid+"&Relationship="+l.Relationship.value+"&Gender="+l.Gender.value+"&Year="+l.Year.value+"&Month="+l.Month.value+"&Day="+l.Day.value+"&Education_Level="+l.Education_Level.value+"&Occupation="+l.Occupation.value+"&Information="+infor+"&radioprivacy="+radio_value+"&tieup_source="+tieup_source+"&hit_source="+hit_source,"get");
	AJAXForms.open("GET","/profile/ip_new_2.php?cfrom=page2&username="+l.username.value+"&id="+pid+"&Relationship="+l.Relationship.value+"&Gender="+l.Gender.value+"&Year="+l.Year.value+"&Month="+l.Month.value+"&Day="+l.Day.value+"&Education_Level="+l.Education_Level.value+"&Occupation="+l.Occupation.value+"&Information="+infor+"&radioprivacy="+radio_value+"&tieup_source="+tieup_source+"&hit_source="+hit_source);
	AJAXForms.send('null');
	if(flag_3rd)
        {
                launch4("/profile/ip_new_3.php","get");
        }
}
function get3rdPage()
{
	if(flag_2ndr)
	{
		document.form1.username.value=username;
                document.form1.Day.value=day;
                document.form1.Month.value=month;
                document.form1.Year.value=year;
                document.form1.Gender.value=gender;
		flag_2ndr=false;
	}
	if(flag_3rd)
	{
		launch("/profile/ip_new_3.php","get");
		flag_3rd=false;
	}	
}
function Submit3()
{
	l=document.form1;
	Diet_value=""; 
	for (counter = 0; counter < l.Diet.length; counter++)
        {
                if (l.Diet[counter].checked)                
		{
                        Diet_value = l.Diet[counter].value;
                        break;
                }
        }
	
	Has_Children_value="";
        for (counter = 0; counter < l.Has_Children.length; counter++)
        {
                if (l.Has_Children[counter].checked)
                {
                        Has_Children_value = l.Has_Children[counter].value;
                        break;
                }
        }
	
	display_horo_value="";
        for (counter = 0; counter < l.display_horo.length; counter++)
        {
                if (l.display_horo[counter].checked)
                {
                        display_horo_value = l.display_horo[counter].value;
                        break;
                }
        }

	Parent_City_Same_value="";
        for (counter = 0; counter < l.Parent_City_Same.length; counter++)
        {
                if (l.Parent_City_Same[counter].checked)
                {
                        Parent_City_Same_value = l.Parent_City_Same[counter].value;
                        break;
                }
        }
        if (window.XMLHttpRequest)
        {
                // branch for IE/Windows ActiveX version
        }
        else if (window.ActiveXObject)
        {
                AJAXForms = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //AJAXForms.onreadystatechange = processChange;
	var job=escape(l.Job.value);
	var ftherinfo=escape(l.Father_Info.value);
	var sib=escape(l.Sibling_Info.value);
	var fam=escape(l.Family.value);
	var addr=escape(l.Address.value);
	var cP=escape(l.Parents_Contact.value);
	var edq=escape(l.Educ_Qualification.value);
	AJAXForms.open("GET","/profile/ip_new_3.php?camefrom=page3&id="+pid+"&Complexion="+l.Complexion.value+"&Body_Type="+l.Body_Type.value+"&Phyhcp="+l.Phyhcp.value+"&Rstatus="+l.Residency_status.value+"&Country_Birth="+l.Country_of_Birth.value+"&Educ_Qualification="+edq+"&Smoke="+l.doYouSmoke_field.value+"&Drink="+l.Drink_field.value+"&Diet="+Diet_value+"&Subcaste="+l.Subcaste.value+"&Manglik_Status="+l.Manglik_Status_field.value+"&Has_Children="+Has_Children_value+"&Family_Back="+l.Family_Background_field.value+"&Father_Info="+ftherinfo+"&Sibling_Info="+sib+"&Job="+job+"&City_Birth="+l.City_Birth.value+"&Nakshatram="+l.Nakshatram.value+"&Gothra="+l.Gothra.value+"&Family_Values="+l.Family_Values_field.value+"&Parent_City_Same="+Parent_City_Same_value+"&Address="+addr+"&pincode="+l.pincode.value+"&showAddress="+l.showAddress.value+"&showMessenger="+l.showMessenger.value+"&Parents_Contact="+cP+"&showParentsContact="+l.showParentsContact.value+"&Family="+fam+"&Messenger_ID="+l.Messenger_ID.value+"&Messenger="+l.Messenger.value+"&display_horo="+display_horo_value+"&tieup_source="+tieup_source+"&hit_source="+hit_source+"&Hour_Birth="+l.Hour_Birth.value+"&Min_Birth="+l.Min_Birth.value);
        AJAXForms.send('null');
	if(flag_4th)
        {
                launch4("/profile/ip_new_4.php?gender="+gender+"&maritalstatus="+mstatus,"get");
                flag_4th=false;
        }
}
function get4thPage()
{
	if(flag_4th)
	{
		launch("/profile/ip_new_4.php?gender="+gender+"&maritalstatus="+mstatus,"get");
		flag_4th=false;
	}
}

function Submit4()
{
	l=document.form1;
	if (window.XMLHttpRequest)
        {
                // branch for IE/Windows ActiveX version
        }
        else if (window.ActiveXObject)
        {
                AJAXForms = new ActiveXObject("Microsoft.XMLHTTP");
        }
	Caste_desired_value="";
        for (counter = 0; counter < l.Caste_desired.length; counter++)
        {
                if (l.Caste_desired[counter].checked)
                {
                        Caste_desired_value = l.Caste_desired[counter].value;
                        break;
                }
        }
	Religion_value="";
        for (counter = 0; counter < l.Religion.length; counter++)
        {
                if (l.Religion[counter].checked)
                {
                        Religion_value = l.Religion[counter].value;
                        break;
                }
        }
	Community_value="";
        for (counter = 0; counter < l.Community.length; counter++)
        {
                if (l.Community[counter].checked)
                {
                        Community_value = l.Community[counter].value;
                        break;
                }
        }
	City_value="";
        for (counter = 0; counter < l.City.length; counter++)
        {
                if (l.City[counter].checked)
                {
                        City_value = l.City[counter].value;
                        break;
                }
        }
	Wife_Working_value="";
	Married_Working_value="";
	if(gender=="M")
	{
	        for (counter = 0; counter < l.Wife_Working.length; counter++)
        	{
	                if (l.Wife_Working[counter].checked)
        	        {
                	        Wife_Working_value = l.Wife_Working[counter].value;
                        	break;
		        }
        	}
	}
	else
	{
		for (counter = 0; counter < l.Married_Working.length; counter++)
                {
                        if (l.Married_Working[counter].checked)
                        {
                                Married_Working_value = l.Married_Working[counter].value;
                                break;
                        }
                }
        }
	var spo=escape(l.Spouse.value);
	if(gender=='M' && mstatus!='N')
		window.location="/profile/ip_new_4.php?camefrom=page4&Caste_desired="+Caste_desired_value+"&Religion="+Religion_value+"&Community="+Community_value+"&City="+City_value+"&Wife_Working="+Wife_Working_value+"&checkboxmarital1="+l.checkboxmarital1.value+"&checkboxmarital2="+l.checkboxmarital2.value+"&checkboxmarital3="+l.checkboxmarital3.value+"&checkboxmarital4="+l.checkboxmarital4.value+"&checkboxmarital5="+l.checkboxmarital5.value+"&checkboxmarital6="+l.checkboxmarital6.value+"&checkboxedulevel1="+l.checkboxedulevel1.value+"&checkboxedulevel2="+l.checkboxedulevel2.value+"&checkboxedulevel3="+l.checkboxedulevel3.value+"&checkboxedulevel4="+l.checkboxedulevel4.value+"&Spouse="+spo+"&About_Us="+l.About_Us.value+"&id="+pid+"&gender="+gender+"&checksum="+checksum+"&tieup_source="+tieup_source+"&hit_source="+hit_source;
	if(gender=='M' && mstatus=='N')
		window.location="/profile/ip_new_4.php?camefrom=page4&Caste_desired="+Caste_desired_value+"&Religion="+Religion_value+"&Community="+Community_value+"&City="+City_value+"&Wife_Working="+Wife_Working_value+"&checkboxedulevel1="+l.checkboxedulevel1.value+"&checkboxedulevel2="+l.checkboxedulevel2.value+"&checkboxedulevel3="+l.checkboxedulevel3.value+"&checkboxedulevel4="+l.checkboxedulevel4.value+"&Spouse="+spo+"&About_Us="+l.About_Us.value+"&id="+pid+"&gender="+gender+"&checksum="+checksum+"&tieup_source="+tieup_source+"&hit_source="+hit_source;	
	else if(gender=='F' && mstatus!='N')
                window.location="/profile/ip_new_4.php?camefrom=page4&Caste_desired="+Caste_desired_value+"&Religion="+Religion_value+"&Community="+Community_value+"&City="+City_value+"&Married_Working="+Married_Working_value+"&checkboxmarital1="+l.checkboxmarital1.value+"&checkboxmarital2="+l.checkboxmarital2.value+"&checkboxmarital3="+l.checkboxmarital3.value+"&checkboxmarital4="+l.checkboxmarital4.value+"&checkboxmarital5="+l.checkboxmarital5.value+"&checkboxmarital6="+l.checkboxmarital6.value+"&checkboxedulevel1="+l.checkboxedulevel1.value+"&checkboxedulevel2="+l.checkboxedulevel2.value+"&checkboxedulevel3="+l.checkboxedulevel3.value+"&checkboxedulevel4="+l.checkboxedulevel4.value+"&Spouse="+spo+"&About_Us="+l.About_Us.value+"&id="+pid+"&gender="+gender+"&checksum="+checksum+"&tieup_source="+tieup_source+"&hit_source="+hit_source;
        else
                window.location="/profile/ip_new_4.php?camefrom=page4&Caste_desired="+Caste_desired_value+"&Religion="+Religion_value+"&Community="+Community_value+"&City="+City_value+"&Married_Working="+Married_Working_value+"&checkboxedulevel1="+l.checkboxedulevel1.value+"&checkboxedulevel2="+l.checkboxedulevel2.value+"&checkboxedulevel3="+l.checkboxedulevel3.value+"&checkboxedulevel4="+l.checkboxedulevel4.value+"&Spouse="+spo+"&About_Us="+l.About_Us.value+"&id="+pid+"&gender="+gender+"&checsum="+checksum+"&tieup_source="+tieup_source+"&hit_source="+hit_source;
}
function CheckField(field)
{
	if (window.XMLHttpRequest)
	{
		// branch for IE/Windows ActiveX version
	}
	else if (window.ActiveXObject)
	{
		AJAXForms = new ActiveXObject("Microsoft.XMLHTTP");
	}
	AJAXForms.onreadystatechange = processChange;
	LastField = field.name;
	if(LastField == "username")
	{
		AJAXForms.open("GET", "/profile/check.php?op=username&field=" +field.name+"&value=" + field.value);
		AJAXForms.send('null');
	}
	else if(LastField == "Email")
	{
		AJAXForms.open("GET", "/profile/check.php?op=emailid&field=" +field.name+"&value=" + field.value);
		AJAXForms.send('null');
	}
}
function processChange()
{
	if (AJAXForms.readyState == 4)
	{
		var idname=LastField+"1";//for IE , will give a problem otherwise
		var  res = document.getElementById(idname);
		rz=AJAXForms.responseText;
		k=rz.length;
		if(k>200)
		{
			document.getElementById('Email1').innerHTML="<span class=\"red\">This Email id is not acceptable to the server</span>";
			serveremail=false;
		}
		else
		{
			res.innerHTML = AJAXForms.responseText;
			serveremail=true;
		}
		res.style.visibility = "visible";
		pased=parseData(res.innerHTML);
		if(pased=="")
		{
			emailok=true;
		}
		else
		{
			emailok=false;
		}
	}
}
function process4username()
{
        if (AJAXForms.readyState == 4)
        {
                u=parseData(AJAXForms.responseText);
		separate_username_id(u);
        }
}
function Logger()
{
        if (window.XMLHttpRequest) req = new XMLHttpRequest();
        else if (window.ActiveXObject) req =
                new ActiveXObject("Microsoft.XMLHTTP");
        else return;
        req.open("GET", "/profile/inputprofileError.php");
        //req.onreadystatechange = errorLogged;
        req.send('null');
}
function separate_username_id(u)
{
	username="";
	pid="";
	i=0;j=0,k=0;
	for(i = 0 ; i < u.length ; i++ )
        {
                var cChar = u.charAt(i);
		if(cChar == "|")
			break;
		username+=cChar;
	}
	if(document.form1.username)
	{
		document.form1.username.value=username;
		document.form1.Day.value=day;
		document.form1.Month.value=month;
		document.form1.Year.value=year;
		document.form1.Gender.value=gender;
	}
	else
	{
		//alert("Thankyou for filling the 1st sheet");
	}
	for(j=i+1;j<u.length;j++)
	{
		var cChar = u.charAt(j);
		if(cChar == "|")
                        break;
		pid+=cChar;
	}
	for(k=j+1;k<u.length;k++)
	{
		var cChar = u.charAt(k);
		checksum+=cChar;
	}
	if(checksum=="")
        {
                //error to be logged
                Logger();
        }
	SetCookie('JSLOGIN',checksum,null,'/',null,false);
	return;
}
function SetCookie(name,value,expires,path,domain,secure)
{
  document.cookie = name + "=" + escape (value) +
    ((expires) ? "; expires=" + expires.toGMTString() : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}
function parseData(strIn)
{
	var strOut = "";
	var nikhil=0;
	var once=0;
	for( var i = 0 ; i < strIn.length ; i++ )
	{
		var cChar = strIn.charAt(i);
		if(nikhil==1 && cChar!="<" && cChar!="?")
		{
			strOut+=cChar;
			once=1;
		}
		else
		{
			nikhil=0;
		}
		if(cChar==">")
		{
			nikhil=1;
		}
		if(once==1 && nikhil==0)
			break;
	}
	return strOut;
}

function SubmitPage()
{
	systemP();
	if(browser=="MSIE" && version<6)
		return true;
	if(emailok==false || serveremail==false)
		checkemailn(document.form1.Email);
	t2=findage();
	tf=validate();
	checkno();
	if(tf && t2 && emailok && serveremail && phonenook && mobilenook)
	{
		Submit1();
		showpage_htm(1);
	}
	else
	{
		alert("Errors in the Form..marked in red!");
		setfocus1();
	}
	return false;
}

function setfocus1()
{
	var docF=document.form1;
	if(trim(docF.Email.value)=="")
	{
		docF.Email.focus();
		return;
	}
	if(!checkemail(docF.Email.value))
	{
		docF.Email.focus();
		return;
	}
	if(trim(docF.Password1.value)=="")
	{
		docF.Password1.focus();
		return;
	}
	if(docF.Gender.value=="")
	{
		docF.Gender.focus();
		return;
	}
	if(docF.Marital_Status.value=="")
	{
		docF.Marital_Status.focus();
		return;
	}
	if(docF.Mtongue.value=="")
	{
		docF.Mtongue.focus();
		return;
	}
	if(docF.Religion.value=="")
	{
		docF.Religion.focus();
		return;
	}
	if(docF.Caste.value=="")
	{
			docF.Height.focus();
		return;
		//return false;
	}
	if(docF.Day.value=="" || docF.Month.value=="" || docF.Year.value=="")
	{
			 docF.Day.focus();
		return;
		//return false;
	}
	if(!validate_date(docF.Day.value,docF.Month.value,docF.Year.value))
	{
		docF.Day.focus();
		return;
	}
											 
	if(docF.Income.value=="")
	{
		docF.Income.focus();
		return;
	}
											 
	if(trim(docF.Phone.value)=="" && trim(docF.Mobile.value)=="")
	{
			 docF.Phone.focus();
		return;
		//return false;
	}
	if(docF.Country_Residence.value=="")
	{
											 
		docF.Country_Residence.focus();
		return;
	}
	if(docF.Country_Residence.value==51 && docF.City_India.value=="")
	{
		docF.City_India.focus();
		return;
	}
	if(docF.Country_Residence.value==128 && docF.City_USA.value=="")
	{
		docF.City_USA.focus();
		return;
	}
	if(!docF.termscheckbox.checked)
	{
		docF.termscheckbox.focus();
		return;
	}
	return;
}
function launch(url, method)
{
        document.forms[0].url.value=url;
        document.forms[0].method.value=method;
        open1();
}
function makeObject()
{
	var x;
	if (window.XMLHttpRequest)
        {
                // branch for IE/Windows ActiveX version
		x = new XMLHttpRequest();
        }
        else if (window.ActiveXObject)
        {
                x = new ActiveXObject("Microsoft.XMLHTTP");
        }
	return x;
}
var request = makeObject();
function launch4(url, method)
{
        document.forms[0].url.value=url;        
	document.forms[0].method.value=method;
	if (document.forms[0].url.value == "" || document.forms[0].method.value=="")
        {}
        else
        {
		request.open(''+document.forms[0].method.value+'', ''+document.forms[0].url.value+'');
		request.onreadystatechange = endofform;
		request.send('');
	}
} 
function endofform()
{
        if(request.readyState == 1)
        {
                document.getElementById('my_div').innerHTML = 'Loading.....';
        }
        if(request.readyState == 4)
        {
                answer = request.responseText;
                document.getElementById('my_div').innerHTML = answer;
        }
}
function open1()
{
	if (document.forms[0].url.value == "" || document.forms[0].method.value=="")
	{
	}
	else
	{
		request.open(''+document.forms[0].method.value+'', ''+document.forms[0].url.value+'');
		request.onreadystatechange = parseInfo;
		request.send('');
	}
}
var answer;
function parseInfo()
{
	if(request.readyState == 1)
	{
		//document.getElementById('my_div').innerHTML = 'Loading.....';
		answer= 'Loading.....';
	}
	if(request.readyState == 4)
	{	
		answer = request.responseText;
		//document.getElementById('my_div').innerHTML = answer;
	}	
}
function showpage()
{
	request.onreadystatechange = parseInfo;
	request.send('');
}
var once=true;
function checkemailn(field)
{
	emailadd=field.value;
	//initalized in the beginning 
	if(once)
	{tieup_source=document.form1.tieup_source.value;
	hit_source=document.form1.hit_source.value;
	once=false;}
	var result = false;
  	var theStr = new String(emailadd);
  	var index = theStr.indexOf("@");
  	if (index > 0)
  	{
    		var pindex = theStr.indexOf(".",index);
	    	if ((pindex > index+1) && (theStr.length > pindex+2))
			result = true;
  	}
	if(result == false)
	{
		document.getElementById('Email1').innerHTML="<span class=\"red\">Enter a valid email id please</span>";
	}
	else
	{
		CheckField(field);
	}
}
function checkpassn(field)
{
	passwordn=field.value;
	lengthofp=passwordn.length;
	if(lengthofp<5)
	{
                document.getElementById('Password2').innerHTML="<span class=\"red\">Password too short</span>";
                //emailfocus();
        }
	else if(lengthofp>40)
	{
		document.getElementById('Password2').innerHTML="<span class=\"red\">Password too long</span>";
	        //document.form1.Password.focus();
	}
	else
	{
		document.getElementById('Password2').innerHTML="";
	}
}
function checkbox(field,message)//true or false
{
	fieldname=field.name+"1";
        document.getElementById(fieldname).innerHTML="<span class=\"black\">"+message+"</span>";
}
function checkboxm1(field,message)//true or false
{
        fieldname=field.name+"1";
        document.getElementById('checkbox1').innerHTML="<span class=\"black\">"+message+"</span>";
}
function checkboxm2(field,message)//true or false
{
        fieldname=field.name+"1";
        document.getElementById('checkboxedulevel11').innerHTML="<span class=\"black\">"+message+"</span>";
}
function checkcaste(field,message)
{
	var docF=document.form1;
	r=false;//radio
	for (counter = 0; counter < docF.Caste_desired.length; counter++)
        {
                if (docF.Caste_desired[counter].checked)
                {
                        r = true;
			break;
                }
        }
	display(r,field,message);
}
function checkreligion(field)
{
	var docF=document.form1;
        r=false;//radio
	for (counter = 0; counter < docF.Religion.length; counter++)
        {
                if (docF.Religion[counter].checked)
                {
                        r= true;
                }
        }
	display(r,field);
}
function checkcommunity(field)
{
	var docF=document.form1;
        r=false;//radio
        for (counter = 0; counter < docF.Community.length; counter++)
        {
                if (docF.Community[counter].checked)
                {
                        r= true;
                }
        }
        display(r,field);
}
function checkcommunity(field)
{
        var docF=document.form1;
        r=false;//radio
        for (counter = 0; counter < docF.Community.length; counter++)
        {
                if (docF.Community[counter].checked)
                {
                        r= true;
                }
        }
        display(r,field);
}
function checkcity(field)
{
        var docF=document.form1;
        r=false;//radio
        for (counter = 0; counter < docF.City.length; counter++)
        {
                if (docF.City[counter].checked)
                {
                        r= true;
                }
        }
        display(r,field);
}
function checkwifeworking(field)
{
        var docF=document.form1;
        r=false;//radio
        for (counter = 0; counter < docF.Wife_Working.length; counter++)
        {
                if (docF.Wife_Working[counter].checked)
                {
                        r= true;
                }
        }
        display(r,field);
}
function checkwork(field)
{
        var docF=document.form1;
        r=false;//radio
        for (counter = 0; counter < docF.Married_Working.length; counter++)
        {
                if (docF.Married_Working[counter].checked)
                {
                        r= true;
                }
        }
        display(r,field);
}
function checkgender(field)
{
	n=field.name;
	v=field.value;	
	fieldname=n+"1";
	if(v=="")
	{
		document.getElementById(fieldname).innerHTML="<span class=\"red\">Please Enter The "+n+"</span>";
		return false;
	}
	else
	{
		document.getElementById(fieldname).innerHTML="";
		return true;
	}
}
function checkno()
{
	mobilenumber=document.form1.Mobile.value;
	phonenumber=document.form1.Phone.value;
	document.getElementById('Mobile1').innerHTML="";
	document.getElementById('Phone1').innerHTML="";
	document.getElementById('pm_message').innerHTML="";
	var mn=true;
	var pn=true;
	var ret=false;
	//validation of mobilenumber
	if(!(/^([+]*[0-9]+[ ]*[-]*[0-9]+)+([,]+[+]*[0-9]+[ ]*[-]*[0-9]+)*$/.test(mobilenumber)) && mobilenumber!="")
	{
		mn=false;		
		document.getElementById('Mobile1').innerHTML="<span class=\"red\">Entered mobile number is not valid</msg></span>";
		mobilenook=false;
	}	
	else if(mobilenumber=="")
 	{
		mn=false;
		mobilenook=true;
	}
	else
	{
		document.getElementById('Mobile1').innerHTML="";
		ret=true;
		mobilenook=true;
	}

	if(!(/^([+]*[0-9]+[ ]*[-]*[0-9]+)+([,]+[+]*[0-9]+[ ]*[-]*[0-9]+)*$/.test(phonenumber)) && phonenumber!="")
	{
		pn=false;
		document.getElementById('Phone1').innerHTML="<span class=\"red\">Entered phonenumber number is not valid</msg></span>";
		phonenook=false;
	}
	else if(phonenumber=="")
	{
		pn=false;
		phonenook=true;
	}
	else
	{
		ret=true;
		document.getElementById('Phone1').innerHTML="";
		phonenook=true;
	}
	
	if(ret)
	{
                document.getElementById('pm_message').innerHTML="";
	}
	else
	{
		document.getElementById('pm_message').innerHTML="<span class=\"red\">You must enter phone number or mobile number</span>";
	}
}
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
			//by nikhil
			document.getElementById('City_USA1').innerHTML="";
			docF.City_USA.value = "";
		}
		else if(docF.Country_Residence.value=="128")
		{
			docF.City_India.disabled = true;
			docF.City_India.value = "";
			docF.City_USA.disabled = false;
			docF.State_Code.value="";
			//by nikhil
			document.getElementById('City_India1').innerHTML="";
		}
		else
		{
			docF.City_India.disabled = true;
			docF.City_India.value = "";
			docF.City_USA.disabled = true;
			docF.City_USA.value = "";
			docF.State_Code.value="";
			document.getElementById('City_USA1').innerHTML="";
			document.getElementById('City_India1').innerHTML="";
		}
		inpage();
	}
}
function togglecheck(cb)
{
        if(cb.checked==true)
                cb.checked=false;
        else
                cb.checked=true;
}
function validate()
{
	//1st function
        toreturn=true;
	if(document.getElementById)
	{
		document.getElementById('email_span').style.color="#000000";
		document.getElementById('pwd1_span').style.color="#000000";
		document.getElementById('gender_span').style.color="#000000";
		document.getElementById('mstatus_span').style.color="#000000";
		document.getElementById('religion_span').style.color="#000000";
		document.getElementById('caste_span').style.color="#000000";
		document.getElementById('height_span').style.color="#000000";
		document.getElementById('date_span').style.color="#000000";
		document.getElementById('income_span').style.color="#000000";
		document.getElementById('phone_span').style.color="#000000";
		document.getElementById('mobile_span').style.color="#000000";
		document.getElementById('country_res_span').style.color="#000000";
		document.getElementById('term_span').style.color="#000000";
	}
	var docF=document.form1;
											 
	if(trim(docF.Email.value)=="")
	{
		if(document.getElementById)
		document.getElementById('email_span').style.color="red";
		docF.Email.focus();
		toreturn=false;
	}
	if(!checkemail(docF.Email.value))
	{
		if(document.getElementById)
			document.getElementById('email_span').style.color="red";
			docF.Email.focus();
		toreturn=false;
	}
	
	if(trim(docF.Password1.value)=="")
	{
		if(document.getElementById)
			document.getElementById('pwd1_span').style.color="red";
			docF.Password1.focus();
		toreturn=false;
	}
	if(docF.Gender.value=="")
	{
		if(document.getElementById)
			document.getElementById('gender_span').style.color="red";
			docF.Gender.focus();
		toreturn=false;
	}
	if(docF.Marital_Status.value=="")
	{
		if(document.getElementById)
			document.getElementById('mstatus_span').style.color="red";
											 
			docF.Marital_Status.focus();
		toreturn=false;
	}
	if(docF.Mtongue.value=="")
	{
		if(document.getElementById)
			document.getElementById('mtongue_span').style.color="red";
			docF.Mtongue.focus();
		toreturn=false;
	}
	if(docF.Religion.value=="")
	{
											 
		if(document.getElementById)
		document.getElementById('religion_span').style.color="red";
		docF.Religion.focus();
		toreturn=false;
	}
	if(docF.Caste.value=="")
	{
											 
		if(document.getElementById)
		document.getElementById('caste_span').style.color="red";
		docF.Caste.focus();
		toreturn=false;
	}
	if(docF.Height.value=="")
	{
		if(document.getElementById)
			document.getElementById('height_span').style.color="red";
		docF.Height.focus();
		toreturn=false;
	}
											 
	if(docF.Day.value=="" || docF.Month.value=="" || docF.Year.value=="")
	{
		if(document.getElementById)
			document.getElementById('date_span').style.color="red";
	     
			 docF.Day.focus();
		toreturn=false;
	}
	if(!validate_date(docF.Day.value,docF.Month.value,docF.Year.value))
	{
		if(document.getElementById)
			document.getElementById('date_span').style.color="red";
			docF.Day.focus();
		toreturn=false;
	}
	if(docF.Income.value=="")
	{
		if(document.getElementById)
			document.getElementById('income_span').style.color="red";

			docF.Income.focus();
		toreturn=false;
	}
	if(trim(docF.Phone.value)=="" && trim(docF.Mobile.value)=="")
	{
		//alert("Please specify Phone No or Mobile No");
											 
		if(document.getElementById)
		{
			document.getElementById('phone_span').style.color="red";
			document.getElementById('mobile_span').style.color="red";
		}
			 docF.Phone.focus();
		toreturn=false;
		//return false;
	}
	if(docF.Country_Residence.value=="")
	{
		//alert("Please specify Country of Residence");
											 
		if(document.getElementById)
			document.getElementById('country_res_span').style.color="red";
			docF.Country_Residence.focus();
		toreturn=false;
		//return false;
	}
	if(docF.Country_Residence.value==51 && docF.City_India.value=="")
	{
											 
		if(document.getElementById)
			document.getElementById('city_span').style.color="red";
											 
		toreturn=false;
	}
	if(docF.Country_Residence.value==128 && docF.City_USA.value=="")
	{
		//alert("Please specify City of Current Residence");
											 
		if(document.getElementById)
			document.getElementById('city_span').style.color="red";
	     
			docF.City_USA.focus();
		toreturn=false;
		//return false;
	}
	if(!docF.termscheckbox.checked)
	{
		alert("You have to agree to the terms and conditions before continuing");                                                                                                 
		if(document.getElementById)
			document.getElementById('term_span').style.color="red";

		docF.termscheckbox.focus();
		return false;
	}
	return toreturn;
}
function trim(inputString)
{
	if (typeof inputString != "string")
		{ return inputString; }
	var retValue = inputString;
	var ch = retValue.substring(0, 1);
	while (ch == " ")
	{
		retValue = retValue.substring(1, retValue.length);
		ch = retValue.substring(0, 1);
	}
	ch = retValue.substring(retValue.length-1, retValue.length);
	while (ch == " ")
	{
		retValue = retValue.substring(0, retValue.length-1);
		ch = retValue.substring(retValue.length-1, retValue.length);
	}
	while (retValue.indexOf("  ") != -1)
	{
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
function PopSPEC(thisform,element1,element2) 
{
	var docF=document.form1;
        if(thisform.value != "")
        {
                var c,spec;
                var len_religion = docF.elements[element1].options.length;
                //var len_religion = docF.elements[element1];
                for(var m1=0;m1<len_religion;m1++)
		{
                	if (docF.elements[element1].options[m1].selected == true)
			{
		                c = docF.elements[element1].options[m1].value;
                	}
                }
                docF.elements[element2].options.length = 0;
                var str      =c.split("|X|");
                var spec_val =str[1].split("#");
                for(var k=0;k<spec_val.length;k++)
		{
                	var s = spec_val[k];
	                if(s)
			{
		                var val=s.split("$");
		                var opt = new Option();
                		opt.text=val[1];
		                opt.value=val[0];
                		docF.elements[element2].options[docF.elements[element2].options.length] = opt;
	                }
		}
        }
}
function PopSPEC_offline(thisform,element1,element2)
{
        var docF=document;
        if(thisform.value != "")
        {
                var c,spec;
                var len_religion = docF.getElementById(element1).options.length;
                //var len_religion = docF.elements[element1];
                for(var m1=0;m1<len_religion;m1++) {
                if (docF.getElementById(element1).options[m1].selected == true) {
                c = docF.getElementById(element1).options[m1].value;
                }
                }
                docF.getElementById(element2).options.length = 0;
                var str      =c.split("|X|");
                var spec_val =str[1].split("#");
                for(var k=0;k<spec_val.length;k++) {
                var s = spec_val[k];
                if(s){
                var val=s.split("$");
                var opt = new Option();
                opt.text=val[1];
                opt.value=val[0];
                docF.getElementById(element2).options[docF.getElementById(element2).options.length] = opt;
                }}
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
function write_profile()
{
	var docF=document.form1;
        var str=new String();
        str=docF.Information.value;
	z=str.length;
	if(z==0)
		document.getElementById('write_profile').innerHTML="<span class=\"red\">Please write your profile summary</span><br>";
	else if(z<100)
		document.getElementById('write_profile').innerHTML="<span class=\"red\">You must write a minimum of 100 characters</span><br>";
	else 
		document.getElementById('write_profile').innerHTML="";
}
function changeCount()
{
        var docF=document.form1;
        var str=new String();
        str=docF.Information.value;
        docF.wordcount.value=str.length;
}
function validate2()
{
	t2=findage();
	k=validate_2();
	//alert(k);
	if(k && t2)
	{
		Submit2();
		showpage_htm(2);
	}
	else
	{
		alert("Errors in the form marked in red");
	}
}
function validate_2()
{
	r=true;
        if(document.getElementById)
        {
                document.getElementById('relationship_span').style.color="#000000";
                document.getElementById('gender_span').style.color="#000000";
                document.getElementById('date_span').style.color="#000000";
                document.getElementById('edu_span').style.color="#000000";
                document.getElementById('occ_span').style.color="#000000";
                document.getElementById('info_span').style.color="#000000";
        }
        var docF=document.form1;
                                                                                                 
        if(docF.Relationship.value=="")
        {
		if(document.getElementById)
                        document.getElementById('relationship_span').style.color="red";
		if(r)
	                docF.Relationship.focus();
                r=false;
        }
                                                                                                 
        if(docF.Gender.value=="")
        {
                if(document.getElementById)
                        document.getElementById('gender_span').style.color="red";
                if(r)                                                                            
	                docF.Gender.focus();
                r=false;
        }
                                                                                                 
        /*if(docF.Mtongue.value=="")
        {
                if(document.getElementById)
                        document.getElementById('mtongue_span').style.color="red";
                if(r)                                                                           
	                docF.Mtongue.focus();
                r=false;
 	}*/
                                                                                                 
                                                                                                 
        if(docF.Day.value=="" || docF.Month.value=="" || docF.Year.value=="")
        {
                if(document.getElementById)
                        document.getElementById('date_span').style.color="red";
                if(r)                                                                           
	                docF.Day.focus();
                r=false;
        }
                                                                                                 
        if(!validate_date(docF.Day.value,docF.Month.value,docF.Year.value))
        {
                if(document.getElementById)
                        document.getElementById('date_span').style.color="red";
                if(r)                                                                            
	                docF.Day.focus();
                r=false;
        }
                                                                                                 
        if(docF.Education_Level.value=="")
	{
                //alert("Please specify Highest Degree");
                                                                                                 
                if(document.getElementById)
                        document.getElementById('edu_span').style.color="red";
                if(r)                                                                           
	                docF.Education_Level.focus();
                r=false;
        }
                                                                                                 
                                                                                                 
        if(docF.Occupation.value=="")
        {
                //alert("Please specify Occupation/Profession");
                                                                                                 
                if(document.getElementById)
                        document.getElementById('occ_span').style.color="red";
                                                                                                
                if(r)docF.Occupation.focus();
                r=false;
        }
                                                                                                 
        if(trim(docF.Information.value)=="")
        {
                //alert("Please specify Your Summary Profile");
                                                                                                 
                if(document.getElementById)
                        document.getElementById('info_span').style.color="red";
                if(r)docF.Information.focus();
                r=false;
        }
                                                                                                 
        var iStr=docF.Information.value;
        if(iStr.length < 100)
        {
                //alert("Your Summary Profile should contain a minimum of 100 characters");
                                                                                                 
                if(document.getElementById)
                        document.getElementById('info_span').style.color="red";
                if(r)                                                                          
	                docF.Information.focus();
                r=false;
        }
	return r;
}
//Added By Puneet for astro service for city locator
function callCityLocator(no)
{
        val='/profile/city.php?cityflag=1&frominput=1';
	z=window.open(val,'','height=257,width=411,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,top=0,left=0');
	return;
}
//function validate1(source)
function validate3()
{
	k=validate_3();
	if(k)
	{
		Submit3();
		if(!flag_3rd)
		{
			showpage_htm(3);
		}
	}
	else
		alert("Errors in form..marked in red");
}
var r=true;
function vz(n,v)
{
	fieldname=n+"1";
	if(v=="")
	{
		document.getElementById(fieldname).innerHTML="<span class=\"red\">Please Enter The "+n+"</span>";
		r=false;
		return false;
	}
	else
	{
		document.getElementById(fieldname).innerHTML="";
		return true;
	}
}
function validate_3()
{
        //if(source!='O')
	r=true;
	var d=true;
	var docF=document.form1;
	z=vz(docF.Body_Type.name,docF.Body_Type.value);
	if(!z && d){docF.Body_Type.focus();d=false;}
	z=vz(docF.Complexion.name,docF.Complexion.value);
	if(!z && d){docF.Complexion.focus();d=false;}
	z=vz(docF.doYouSmoke_field.name,docF.doYouSmoke_field.value);
	if(!z && d){docF.doYouSmoke_field.focus();d=false;}
	z=vz(docF.Drink_field.name,docF.Drink_field.value);
	if(!z && d){docF.Drink_field.focus();d=false;}
	z=vz(docF.Manglik_Status_field.name,docF.Manglik_Status_field.value);
	if(!z && d){docF.Manglik_Status_field.focus();d=false;}
	z=vz(docF.Country_of_Birth.name,docF.Country_of_Birth.value);
	if(!z && d){docF.Country_of_Birth.focus();d=false;}
	z=vz(docF.Residency_status.name,docF.Residency_status.value);
	if(!z && d){docF.Residency_status.focus();d=false;}
	z=vz(docF.Family_Background_field.name,docF.Family_Background_field.value);
	if(!z && d){docF.Family_Background_field.focus();d=false;}
	z=vz(docF.Family_Values_field.name,docF.Family_Values_field.value);
	if(!z && d){docF.Family_Values_field.focus();d=false;}
	z=vz(docF.Address.name,docF.Address.value);
	if(!z && d){docF.Address.focus();d=false;}
	z=vz(docF.pincode.name,docF.pincode.value);
	if(!z && d){docF.pincode.focus();d=false;}
	return r;
}

function validate_radio()
{
	var docF=document.form1;
	var result = -1;
	var radio_caste = false;
	var radio_city = false;
	var radio_comm = false;
	var radio_rel = false;
	var radio_work = false;
	var radio_mwork = false;
	for (counter = 0; counter < docF.Caste_desired.length; counter++)
	{
		if (docF.Caste_desired[counter].checked)
		{
			radio_caste = true;
		}
	}
	for (counter = 0; counter < docF.Religion.length; counter++)
        {
                if (docF.Religion[counter].checked)
                {
                        radio_rel = true;
                }
        }
	for (counter = 0; counter < docF.Community.length; counter++)
        {
                if (docF.Community[counter].checked)
                {
                        radio_comm = true;
                }
        }
	for (counter = 0; counter < docF.City.length; counter++)
        {
                if (docF.City[counter].checked)
                {
                        radio_city = true;
                }
        }
	if (docF.Wife_Working)
	{
		for (counter = 0; counter < docF.Wife_Working.length; counter++)
        	{
                	if (docF.Wife_Working[counter].checked)
                	{
                        	radio_work = true;
                	}
        	}
	}
	if (docF.Married_Working)
	{
		for (counter = 0; counter < docF.Married_Working.length; counter++)
        	{
                	if (docF.Married_Working[counter].checked)
                	{
                        	radio_mwork = true;
                	}
        	}
	}
	if (radio_caste == false)
	{
			alert("Please specify whether you are willing to marry outside your caste or not");
			return false;
			result = 1;
	}
	if (radio_rel == false)
        {
                        alert("Please specify whether you are willing to marry outside your religion or not");
                        return false;
                        result = 1;
        }
	if (radio_comm == false)
        {
                        alert("Please specify whether you are willing to marry outside your community or not");
                        return false;
                        result = 1;
        }
	if (radio_city == false)
        {
                        alert("Please specify whether you are willing to marry outside your city or not");
                        return false;
                        result = 1;
        }
	if (docF.Wife_Working)
	if (radio_work == false)
        {
                        alert("Please specify whether you prefer your wife to be a working one or not");
                        return false;
                        result = 1;
        }
	if (docF.Married_Working)
	if (radio_mwork == false)
        {
                        alert("Please specify whether you are willing to marry after your marriage or not");
                        return false;
                        result = 1;
        }
	return true;
}
function Submit4f()
{
	k=Submit_4();
	if(k)
		Submit4();
		//alert("ya, ready for the next page");
}
function Submit_4()
{
		var radio_val = validate_radio();
		if (!radio_val)
			return false;
		var maritalstatus=mstatus;
		docF=document.form1;
		
		if(maritalstatus!='N')
		{
			if(docF.checkboxmarital1.checked==false && docF.checkboxmarital2.checked==false && docF.checkboxmarital3.checked==false && docF.checkboxmarital4.checked==false && docF.checkboxmarital5.checked==false && docF.checkboxmarital6.checked==false)
			{
				alert("Please specify what type of people will you prefer marrying");
				return false;
			}
		}
		if(docF.checkboxedulevel1.checked==false && docF.checkboxedulevel2.checked==false && docF.checkboxedulevel3.checked==false && docF.checkboxedulevel4.checked==false)
		{
			alert("Please specify the educational qualification of your desired spouse");
			return false;
		}
		if(docF.Spouse.value == '')
		{
			alert("Please provide desired characterstics of your spouse");
			return false;
		}
		if(docF.About_Us.value=='')
		{
			alert("Please specify how did you come to know about us");
			return false;
		}
	return true;
}
function validate_offline()
{
        var docF=document.form1;
        if(trim(docF.Email.value)=="")
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
function trim(inputString) {
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " ") {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " ") {
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
function PopSPEC(thisform,element1,element2) 
{
	var docF=document.form1;
        if(thisform.value != "")
        {
                var c,spec;
                var len_religion = docF.elements[element1].options.length;
                for(var m1=0;m1<len_religion;m1++)
		{
                	if (docF.elements[element1].options[m1].selected == true)
			{
		                c = docF.elements[element1].options[m1].value;
                	}
                }
                docF.elements[element2].options.length = 0;
                var str      =c.split("|X|");
                var spec_val =str[1].split("#");
                for(var k=0;k<spec_val.length;k++)
		{
                	var s = spec_val[k];
	                if(s)
			{
		                var val=s.split("$");
		                var opt = new Option();
                		opt.text=val[1];
		                opt.value=val[0];
                		docF.elements[element2].options[docF.elements[element2].options.length] = opt;
	                }
		}
        }
}
function PopSPEC_offline(thisform,element1,element2)
{
        var docF=document;
        if(thisform.value != "")
        {
                var c,spec;
                var len_religion = docF.getElementById(element1).options.length;
                for(var m1=0;m1<len_religion;m1++) {
                if (docF.getElementById(element1).options[m1].selected == true) {
                c = docF.getElementById(element1).options[m1].value;
                }
                }
                docF.getElementById(element2).options.length = 0;
                var str      =c.split("|X|");
                var spec_val =str[1].split("#");
                for(var k=0;k<spec_val.length;k++) {
                var s = spec_val[k];
                if(s){
                var val=s.split("$");
                var opt = new Option();
                opt.text=val[1];
                opt.value=val[0];
                docF.getElementById(element2).options[docF.getElementById(element2).options.length] = opt;
                }}
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
function get_code(x,list)
{
	if(x=='i')
	{
		document.form1.State_Code.value=parselist(list,document.form1.City_India.value);
	}
	else if(x=='c')
	{
		var yyp=parselist(list,document.form1.Country_Residence.value);
		document.form1.Country_Code.value=yyp;
		document.form1.Country_Code_Mob.value=yyp;
	}
}
function parselist(list,cityname)
{
	var flag=true;
	var code="";
	var newnum="";	
	var i=0;
	k=list.indexOf(cityname);
	if(k<0)return;
	for(i=0;i<list.length;i++)
        {
                var cChar = list.charAt(i);
                if(cChar == "|")
                {
                        flag=false;
			if(newnum==cityname)
                                break;
                }
                else if(cChar == ",")
                {
			newnum="";
                        flag=true;
                }
		else if(flag)
                {
                        newnum+=cChar;
                }
        }
	if(i==list.length)return code;
	flag=false;
        k=i;
	for(i = k ; i < list.length ; i++ )        
	{
		
                var cChar = list.charAt(i);
                if(cChar == "|")
                {
			flag=true;
		}
		else if(cChar == ",")
		{
			break;
		}
		else
		{
			if(flag)
			{
				code+=cChar;
			}		
		}
        }
	return code;
}
function systemP()
{
        ua=navigator.userAgent;
        s="MSIE";
        OS=navigator.platform;
        if((i=ua.indexOf(s))>=0)
        {
                version=parseFloat(ua.substr(i+s.length));
                browser=s;
                return;
        }
        s="Netscape6/";
        if((i=ua.indexOf(s))>=0)
        {
                browser=s;
                version=parseFloat(ua.substr(i+s.length));
                return;
        }
        s="Gecko";
        if((i=ua.indexOf(s))>=0)
        {
		version=6.1;
                browser=s;
                return;
        }
}
