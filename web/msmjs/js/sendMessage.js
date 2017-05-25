function checkFileExtension(form)
{
var file = form.uploadedFile;
if (file.value.indexOf(".csv") == -1) {
alert("Please upload the csv file");
return false;
}
}

function useFileOptions(obj)
{
	val = obj.value;
	document.getElementById('messageWidget').style.display='block';
	if(val=='upload')
	{
		document.getElementById('uploadedFileCol').style.display='block';
		document.getElementById('messageCol').style.display='none';
	}
	else if(val=='sendSms')
	{
		document.getElementById('uploadedFileCol').style.display='none';
		document.getElementById('messageCol').style.display='block';
	}
	else
	{
                document.forms[0].action = 'setMessage.php';
                document.forms[0].submit();     		
	}
}

function mylogout(cid)
{
	parent.location="logout.php?cid="+cid;
}


function validateSendMessageForm(form)
{
	if(form.message.value=="")
	{
		alert('Please set the message');
		return false;
	}
	else if(form.limit.value=="")
	{
		alert("Please set the limit");
		return false;
	}
        else if(form.fromMobile.value=="")
        {
                alert("Please set the mobile number");
                return false;
        }	
	else if(form.title.value=="")
	{
                alert("Please set the title");
                return false;
	}
	else
		return true;
}

function imposeMaxLength(object, MaxLen)
{
	return (object.value.length <= MaxLen);
}

function ManglikSelected(obj)
{
	var docF=document.form1;
	
        if(obj.checked)
        {
                if (obj.name=="Manglik_Status1")
                {
                        docF.Manglik_Status2.checked=false;
                        docF.Manglik_Status3.checked=false;
                        docF.Manglik_Status4.checked=false;
                }
                if (obj.name=="Manglik_Status2")
                {
                        docF.Manglik_Status1.checked=false;
			docF.Manglik_Status3.checked=false;
			docF.Manglik_Status4.checked=false;
                }
                if (obj.name=="Manglik_Status3")
                {
                        docF.Manglik_Status1.checked=false;
                        docF.Manglik_Status2.checked=false;
			docF.Manglik_Status4.checked=false;
                }
		if (obj.name=="Manglik_Status4")
                {
                        docF.Manglik_Status1.checked=false;
                        docF.Manglik_Status2.checked=false;
                        docF.Manglik_Status3.checked=false;
                }
        }
}

function MaritalSelected(obj)
{
	var docF=document.form1;
	
        if(obj.checked)
        {
                if (obj.name=="Marital_Status1")
                {
                        docF.Marital_Status2.checked=false;
                        docF.Marital_Status3.checked=false;
                        docF.Marital_Status4.checked=false;
			docF.Marital_Status5.checked=false;
			docF.Marital_Status6.checked=false;
                }
                if (obj.name=="Marital_Status2")
                {
			docF.Marital_Status1.checked=false;
                }
                if (obj.name=="Marital_Status3")
                {
			docF.Marital_Status1.checked=false;
                }
		if (obj.name=="Marital_Status4")
                {
			docF.Marital_Status1.checked=false;
                }
		if (obj.name=="Marital_Status5")
                {
                        docF.Marital_Status1.checked=false;
                }
		if (obj.name=="Marital_Status6")
                {
                        docF.Marital_Status1.checked=false;
                }
        }
}


function BtypeSelected(obj)
{
	var docF=document.form1;
	
	if(obj.checked)
	{
		if (obj.name=="Body_Type1")
		{
			docF.Body_Type2.checked=false;
	                docF.Body_Type3.checked=false;
        	        docF.Body_Type4.checked=false;
                	docF.Body_Type5.checked=false;
		}
		if (obj.name=="Body_Type2")
	        {
        	        docF.Body_Type1.checked=false;
	        }
		if (obj.name=="Body_Type3")
	        {
        	        docF.Body_Type1.checked=false;
                	docF.Body_Type2.checked=true;
	        }
		if (obj.name=="Body_Type4")
	        {
        	        docF.Body_Type1.checked=false;
                	docF.Body_Type2.checked=true;
			docF.Body_Type3.checked=true;
	        }
		if (obj.name=="Body_Type5")
	        {
        	        docF.Body_Type1.checked=false;
                	docF.Body_Type2.checked=true;
	                docF.Body_Type3.checked=true;
			docF.Body_Type4.checked=true;
	        }
	}
}

function ComplexionSelected(obj)
{
	var docF=document.form1;
	
        if(obj.checked)
        {
                if (obj.name=="Complexion1")
                {
                        docF.Complexion2.checked=false;
                        docF.Complexion3.checked=false;
                        docF.Complexion4.checked=false;
                        docF.Complexion5.checked=false;
			docF.Complexion6.checked=false;
                }
                if (obj.name=="Complexion2")
                {
                        docF.Complexion1.checked=false;
                }
                if (obj.name=="Complexion3")
                {
                        docF.Complexion1.checked=false;
                        docF.Complexion2.checked=true;
                }
                if (obj.name=="Complexion4")
                {
                        docF.Complexion1.checked=false;
                        docF.Complexion2.checked=true;
                        docF.Complexion3.checked=true;
                }
                if (obj.name=="Complexion5")
                {
                        docF.Complexion1.checked=false;
                        docF.Complexion2.checked=true;
                        docF.Complexion3.checked=true;
                        docF.Complexion4.checked=true;
                }
		if (obj.name=="Complexion6")
                {
                        docF.Complexion1.checked=false;
                        docF.Complexion2.checked=true;
                        docF.Complexion3.checked=true;
                        docF.Complexion4.checked=true;
			docF.Complexion5.checked=true;
                }

        }
}

function SmokeSelected(obj)
{
	var docF=document.form1;
	
        if(obj.checked)
        {
                if (obj.name=="Smoke1")
                {
                        docF.Smoke2.checked=false;
                        docF.Smoke3.checked=false;
                        docF.Smoke4.checked=false;
                }
                if (obj.name=="Smoke2")
                {
                        docF.Smoke1.checked=false;
                }
		if (obj.name=="Smoke3")
                {
                        docF.Smoke1.checked=false;
                }
		if (obj.name=="Smoke4")
                {
			docF.Smoke1.checked=false;
                        docF.Smoke3.checked=true;
                }
	}	
}

function DrinkSelected(obj)
{
	var docF=document.form1;
	
        if(obj.checked)
        {
                if (obj.name=="Drink1")
                {
                        docF.Drink2.checked=false;
                        docF.Drink3.checked=false;
                        docF.Drink4.checked=false;
                }
                if (obj.name=="Drink2")
                {
                        docF.Drink1.checked=false;
                }
		if (obj.name=="Drink3")
                {
                        docF.Drink1.checked=false;
                }
		if (obj.name=="Drink4")
                {
			docF.Drink1.checked=false;
                        docF.Drink3.checked=true;
                }
	}	
}

function ContactSelected(obj)
{
alert("kush");	
}
function validate()
{	
	var docF=document.form1;
	
        for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "Caste[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select Caste");
		return false;
        }
        
        if(!docF.Manglik_Status1.checked && !docF.Manglik_Status2.checked && !docF.Manglik_Status3.checked && !docF.Manglik_Status4.checked )
        {
        	alert("Please select Manglik Status");
        	return false;
        }
        
        for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "Mtongue[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select Mother Tongue");
		return false;
        }
        
        if(!docF.Marital_Status1.checked && !docF.Marital_Status2.checked && !docF.Marital_Status3.checked && !docF.Marital_Status4.checked && !docF.Marital_Status5.checked && !docF.Marital_Status6.checked)
        {
        	alert("Please select Marital Status");
        	return false;
        }
        
        if(!docF.Body_Type1.checked && !docF.Body_Type2.checked && !docF.Body_Type3.checked && !docF.Body_Type4.checked && !docF.Body_Type5.checked)
        {
        	alert("Please select Body Type");
        	return false;
        }
        
        if(!docF.Complexion1.checked && !docF.Complexion2.checked && !docF.Complexion3.checked && !docF.Complexion4.checked && !docF.Complexion5.checked)
        {
        	alert("Please select Complexion");
        	return false;
        }
        
        if(!docF.Smoke1.checked && !docF.Smoke2.checked && !docF.Smoke3.checked && !docF.Smoke4.checked)
        {
        	alert("Please select Smoking habits");
        	return false;
        }
        
        if(!docF.Drink1.checked && !docF.Drink2.checked && !docF.Drink3.checked && !docF.Drink4.checked)
        {
        	alert("Please select Drinking habits");
        	return false;
        }
        
	for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "Occupation[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select Occupation");
		return false;
        }
        
        for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "City_India[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select City of Residence");
		return false;
        }
        
        for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "Country_Residence[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select Country of Residence");
		return false;
        }
        
        for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "Rstatus[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select Residency Status");
		return false;
        }
        
        for(var i=0;i<docF.elements.length;i++)
        {
        	var e=docF.elements[i];
        	
                if(e.name == "Education_Level[]")
                        break;
        }

        if(e.selectedIndex == -1)
        {
		alert("Please select Education Level");
		return false;
        }

	return true;
}
