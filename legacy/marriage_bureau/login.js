var r;
var forfocus=true;
function check(field)
{
	n=field.name;v=field.value;fieldname=n+"1";
        colorname=n+"7";
	z=check1(fieldname,colorname,v);
	return z;
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
		forfocus=false;
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
function validate()
{
	r=true;
	forfocus=true;
	l=document.form1;
	if(!(check(l.username)))l.username.focus();
	if(!(check(l.password))&&forfocus)l.password.focus();
	if(r)
		return true;
	else
	{
		window.scrollTo(0,0);
		return false;
	}
}
