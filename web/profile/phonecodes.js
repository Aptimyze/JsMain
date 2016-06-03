function get_code(x,list)
{
	try{
		if(x=='i')
		{
			document.form1.State_Code.value=parselist(list,document.form1.City_India.value);
			//added by sriram for adding "Other" option in City Drop down
                        if(document.form1.City_India.value=='Other')
                                document.form1.State_Code.value=parselist(list,document.form1.State_India.value);
		}
		if(x=='c')
		{
			var yyp=parselist(list,document.form1.Country_Residence.value);
			document.form1.Country_Code.value=yyp;
			document.form1.Country_Code_Mob.value=yyp;
		}
		if(x=='u')
		{
			//document.form1.State_Code.value=parselist(list,document.form1.City_USA.value);
		}
	}catch(e)
	{	
		//alert(e.message);
	}
}

function parselist(list,cityname)
{
try{
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
        k=i;
	flag=false;
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
	}catch(e)
	{
		//alert(e.message)
	}
}
