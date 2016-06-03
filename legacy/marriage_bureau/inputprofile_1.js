var r=true;
var freeorpaid_value="";
function copyintopayee(v)
{
	l=document.form1;
	if(v.value=='N'&&l.p_name.value==l.c_name.value&&l.p_tel.value==l.c_tel.value&&l.p_mob.value==l.c_mob.value)
	{
		l.p_name.value="";
                l.p_tel.value="";
                l.p_mob.value="";
	}
	else if(v.value=='Y')
	{
		l.p_name.value=l.c_name.value;
		l.p_tel.value=l.c_tel.value;
		l.p_mob.value=l.c_mob.value;
	}
}
function check(field)
{
	try{
	n=field.name;v=field.value;fieldname=n+"1";
	}catch(e){alert("message: "+e.message+"\nfiled not exitsing= "+field);}
        colorname=n+"7";
	try{
	z=check1(fieldname,colorname,v);
	}catch(e){alert("message: "+e.message+"\nfiled not exitsing= "+n);}
}
function check1(fieldname,colorname,v)
{
        if(v=="")
        {
                document.getElementById(fieldname).innerHTML="<span class=\"smallred\">Please Enter. This is a compulsory field </span>";
                if(document.getElementById(colorname))
                {
                        document.getElementById(colorname).style.color="red";
                }
		r=false;
                return false;
        }
        else
        {
                document.getElementById(fieldname).innerHTML="";
                if(document.getElementById(colorname))
                        document.getElementById(colorname).style.color="black";
                return true;
        }
}
function ccy()
{
	l=document.form1;
	for (counter = 0; counter < l.freeorpaid.length; counter++)
                if (l.freeorpaid[counter].checked)
                        freeorpaid_value = l.freeorpaid[counter].value;
	check1('freeorpaid1','freeorpaid7',freeorpaid_value);
}
function validate()
{
	l=document.form1;
	r=true;yy=0;
	check(l.nameofbureau);
	check(l.address);
	check(l.city);
	check(l.tel1);
	if(r)yy=150;
	checkemail(l.email.value);
	if(r)yy=200;
	check(l.c_name);
	check(l.c_designation);
	if(r){yy=500;}
	checkno();
	if(!(document.form1.ccd&&document.form1.ccd.value==1))
	{
		if(r){yy=900;}
		ccy();
		if(r){yy=1400;}
		cu();//check username
		check(l.password);
		if(l.password.value != l.password_re.value)
		{
			document.getElementById('password1').innerHTML="<span class=\"smallred\">The password and reentered password did not match</span>";
			if(document.getElementById('password7'))
			{
				document.getElementById('password7').style.color="red";
			}
			r=false;
		}
	}
	if(r)
		return true;
	else
	{
		window.scrollTo(0,yy);
		alert("There were Errors in the form, they have been marked in red,\nplease correct and resubmit");
		return false;
	}
}
function checkno()
{
        mobilenumber=document.form1.c_mob.value;
        phonenumber=document.form1.c_tel.value;
        document.getElementById('c_mob1').innerHTML="";
        document.getElementById('c_tel1').innerHTML="";
        document.getElementById('pm_message').innerHTML="";
        var mn=true;var pn=true;var ret=false;
        //validation of mobilenumber
        if(!(/^([+]*[0-9]+[ ]*[-]*[0-9]+)+([,]+[+]*[0-9]+[ ]*[-]*[0-9]+)*$/.test(mobilenumber)) && mobilenumber!="")
        {mn=false;document.getElementById('c_mob1').innerHTML="<span class=\"smallred\">Entered mobile number is not valid</msg></span>";mobilenook=false;}
        else if(mobilenumber==""){mn=false;mobilenook=true;}
        else {document.getElementById('c_mob1').innerHTML="";ret=true;mobilenook=true;}
        if(!(/^([+]*[0-9]+[ ]*[-]*[0-9]+)+([,]+[+]*[0-9]+[ ]*[-]*[0-9]+)*$/.test(phonenumber)) && phonenumber!="")
                {pn=false;document.getElementById('c_tel1').innerHTML="<span class=\"smallred\">Entered phonenumber number is not valid</msg></span>";phonenook=false;}
        else if(phonenumber==""){pn=false;phonenook=true;}
        else {ret=true;document.getElementById('c_tel1').innerHTML="";  phonenook=true;}
        if(ret)
	{
                document.getElementById('pm_message').innerHTML="";
		document.getElementById('c_tel7').style.color="black";
	}
        else
	{
                document.getElementById('pm_message').innerHTML="<span class=\"smallred\">You must enter phone number or mobile number</span>";
		r=false;
	}
}
function checkemail(emailadd)
{
	if(emailadd=="")
	{
		document.getElementById('email1').innerHTML="";
                document.getElementById('email7').style.color="black";
		return true;
	}
        if((/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/.test(emailadd)))
	{
		document.getElementById('email1').innerHTML="";
                document.getElementById('email7').style.color="black";
	}
        else
	{
		document.getElementById('email1').innerHTML="<span class=\"smallred\">Entered Email Id is invalid</span>";
		document.getElementById('email7').style.color="red";
		r=false;
                return false;
	}
}
function cu()//check username availablity
{
        l=document.form1;
        var specialchar=new Array('#','\'','"','\\','/',' ','!','@','$','%','^','&','*','?','<','>','+','|');
        ff=specialchar.length;
        for(i=0;i<ff;i++)
                if(l.username.value.indexOf(specialchar[i])>0)
                {
			if(specialchar[i]==" ")specialchar[i]="blankspace"; 
			document.getElementById('username1').innerHTML="<span class=\"smallred\">Entered username is invalid, it contains a special character<br>"+specialchar[i]+"</span>";
                document.getElementById('username7').style.color="red";
			r=false;
                        return;
                }
        if(l.username&&l.username.value.length<3)
        {
		document.getElementById('username1').innerHTML="<span class=\"smallred\">The USERNAME needs to be of more than 2 characters</span>";
                document.getElementById('username7').style.color="red";
                r=false;
		return;
        }
	document.getElementById('username1').innerHTML="";
        document.getElementById('username7').style.color="black";
}
