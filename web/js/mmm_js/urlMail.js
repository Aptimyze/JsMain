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


function validate()
{

	$err='';
	
	if(trim(document.form1.url.value)=="")
	{
		$err+="Please Enter a url\n";
	}
	if(trim(document.form1.subject.value)=="")
	{
		$err+="Please Enter The subject of mail\n";
	}
	if(document.form1.jswalkin.checked==false)
        {       
		if(trim(document.form1.f_email.value)=="" ) 
		{
			$err+="Please enter the value of From Email\n";
		}
		if (document.form1.f_email.value.indexOf("@")==-1)
		{
			$err+="Please enter a valid Email address\n";
		}
	}
	if($err != "")
	 {
		 alert("You forget to choose following.\n" + $err);
		 return false;
	 }	
	 return true;
}

function validateHour(){

var element = document.getElementById('hour').value;

if(document.getElementById('hour').value == '') return;
document.getElementById('hour').value = document.getElementById('hour').value % 24;
if(document.getElementById('hour').value.length == 1) document.getElementById('hour').value = '0' + document.getElementById('hour').value;


}

function validateMinute(){
var element = document.getElementById('minute').value;

if(document.getElementById('minute').value == '') return;
document.getElementById('minute').value = document.getElementById('minute').value % 60;
if(document.getElementById('minute').value.length == 1) document.getElementById('minute').value = '0' + document.getElementById('minute').value;

}

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if(charCode === 8) return true;
    if (charCode > 47 && charCode < 58)
        return true;
    return false;
}


function validateForm(){

    if(document.getElementById('rl_reminder_date').value == ''){

        alert('Schedule Date is Mandatory');
        return false;

    }

    var dateObj = new Date();
    var month = dateObj.getMonth();month = month + 1;month=month.toString();if(month.length == 1) month = '0' + month;
    var tdate = dateObj.getDate();tdate = tdate.toString();if(tdate.length == 1) tdate = '0' + tdate;
    var todayDate = dateObj.getFullYear() + '/' + month + '/' + tdate;
    var inputDate = document.getElementById('rl_reminder_date').value;
    todayDate = todayDate.replace('/','-');
    inputDate = inputDate.replace('/','-');
    if(inputDate < todayDate){
        alert('Schedule Date Cannot be Of Past!');
        document.getElementById('rl_reminder_date').value = '';
        return false;
    }

    return true;

}

