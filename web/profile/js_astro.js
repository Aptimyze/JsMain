
function formfocus()
	{
	document.form.tCountry.focus();
	}
//***********
function state_disable()
 {
  if(document.form.tCountry.options[document.form.tCountry.selectedIndex].text == "United States of America") 
  {
   document.form.tState.disabled=false;
	return null;
  }
 else
  {
  document.form.tState.value="";
  document.form.tState.disabled=true;
   }
  return null; 
 }
//**************
function chklen()
{
 var field=document.form.tCity.value;
 if (field =="")
       {
       alert("Please enter city!");
       document.form.tCity.focus();
       return false;
       }
 else if ((field.indexOf("_")!=-1)||(field.indexOf("*")!=-1)||(field.indexOf("!")!=-1)||(field.indexOf("@")!=-1)||(field.indexOf("$")!=-1)||(field.indexOf("%")!=-1)||(field.indexOf("^")!=-1)||(field.indexOf("#")!=-1)||(field.indexOf("=")!=-1)||(field.indexOf("-")!=-1)||(field.indexOf("[")!=-1)||(field.indexOf("]")!=-1)||(field.indexOf("{")!=-1)||(field.indexOf("}")!=-1)||(field.indexOf("+")!=-1)||(field.indexOf("~")!=-1)||(field.indexOf("?")!=-1)||(field.indexOf("|")!=-1)||(field.indexOf(">")!=-1)||(field.indexOf("<")!=-1))
        { 
        alert("Invalid city");
		document.form.tCity.focus();
      	return(false);
		}

var	l=field.length;
    for(i=0;i<l;i++)
    {
       if (field.charAt(i)>="0" && field.charAt(i)<="9")
           {
             alert("Invalid city");
             document.form.tCity.focus();
             return false;
       }
     }
		
 for (var i=0;i < field.length;i++)
    {
    if (field.substring(i,i+1) != " ")
       {
       return true;
       }
       {
        alert("Invalid city!");
        document.form.tCity.focus();
        return false;
       }
      }
       return true;
       }

//*******
 function dropdown()
 {
 if(document.form.tCountry.value=="")
 {
 alert("Please select Country")
 document.form.tCountry.focus();
 return false;
 }
 if (document.form.tCountry.options[document.form.tCountry.selectedIndex].text == "United States of America" && document.form.tState.value=="")
 {
 alert("Please select any American state")
 document.form.tState.focus();
 return false;
 }
 return true;
 }
//********
function chkpost()
 {
if(dropdown() && chklen())
  {
  document.form.submit()
  return true;
  }
  return false;
}
