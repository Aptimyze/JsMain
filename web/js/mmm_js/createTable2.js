function trim(inputString) {
   // Removes leading and trailing spaces from the passed string. Also removes
   // consecutive spaces and replaces it with one space. If something besides
   // a string is passed in (null, custom object, etc.) then return the input.
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " ") { // Check for spaces at the beginning of the string
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " ") { // Check for spaces at the end of the string
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) { // Note that there are two spaces in the string - look for multiple spaces within the string
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); // Again, there are two spaces in each of the strings
   }
   return retValue; // Return the trimmed string back to the user
} // Ends the "trim" function

function isDigit (c)
    {
             return ((c >= "0") && (c <= "9"))
      }

   function isInteger(s)
      {
            var i;
       
                   if(s.length==0 || s==null)
       {
          
                 return false;
       }
       else
       {
                 for (i=0;i<s.length;i++)
                 {
                    var c = s.charAt(i);
                                  if (!isDigit(c))
               {
                   
                   return false;
               }
                 }
       }
                   return true;
      } 





function validate(num)
{
	
/* num variable consist of sum of total records which needs to be splitted
*/	
var sv1=trim(document.form1.sv1.value);

if(!isInteger(sv1))
{sv1=0;}

var sv2=trim(document.form1.sv2.value);
if(!isInteger(sv2))
{sv2=0;}
var sp1=trim(document.form1.sp1.value);
if(!isInteger(sp1))
{sp1=0;}
var sp2=trim(document.form1.sp2.value);
if(!isInteger(sp2))
{sp2=0;}

	if((sp1)||(sp2))
	{
		var sum=0;
		 sum=eval(sp1)+eval(sp2);
		if(sum!=100)
		{
		alert("Sum is "+sum +" Please enter the sum of both percentage equlas to 100 ");
		return false;
		}
		else
		{
		return true;
		}
	}
	else if((sv1)||(sv2))
	{
	   var sum2=0;
	   sum2=eval(sv1)+eval(sv2);
		if(sum2!=num)
		{
		alert("You entered "+sum2 +"where as total no of records are  "+num + "\n So please  re enter the values of both the values");
		return false;
		}
		else
		{
		alert("sum is"+num);
		return true;
		}
	}
	else
	{
		alert("Please enter valid values of sv1,sv2 or sp1,sp2 ");
		return false;
	}
	
	
}

