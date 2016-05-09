var user_login =1;
var attr="You may consider answering these questions:\n1. How would you describe yourself?\n2. What kind of food/movies/books/music you like?\n3. Do you enjoy activities like traveling, music, sports etc?\n4. Where have you lived most of your life till now?\n5. Where do you wish to settle down in future?";

jQuery.validator.setDefaults({
		errorElement: "div"
					});
$(document).ready(function () {
	$("#reg").validate({
		onkeyup : false,
		onfocusout: function(element) {
					$(element).valid(); 
		}
	}); 
	$("#reg_yourinfo").rules("add",{
	  required : true,
	  minlength:100,
	  checkyourinfo: true,
	  messages:
	  {
		required: $("#yourinfo_required").html(),
		minlength : $("#yourinfo_required").html(),
	  }
	});     	
	if(!$("#reg_yourinfo").val())
        {
                if(!(navigator.userAgent.indexOf('Opera Mini') > -1))
			$("#reg_yourinfo").val(attr);
                $("#reg_yourinfo").css("color","#a7a7a7");
	}
                
	$("#reg_yourinfo").bind("focus",function(){ var input = $(this);   if (input.val() == attr) {     input.val('');this.style.color='#000';  } });
	$("#reg_yourinfo").bind("blur",function() {
		var input = $(this);   if (input.val() == '' || input.val() == attr) {   input.val(attr); this.style.color='#a7a7a7';
			}
       });
		
});
jQuery.validator.addMethod("checkyourinfo",function(value,element)
{

        if($("#reg_yourinfo").val()==attr)
                return false;
        return true;
},$("#yourinfo_required").html());

function aboutFieldCount()
{
	var str = new String();
	str = trim($('#reg_yourinfo').val());
	str = trim_newline(str);
	
	if(str.length >= 100)
	{
		$("#about_yourself_count").css('color','#00BB00');
	}
	else
	{
		$("#about_yourself_count").css('color','#FF0000');
	}
	$('#about_yourself_count').html(str.length);
}
function trim(inputString) {
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) {
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
   }
   return retValue;
}
function trim_newline(string){
        return string.replace(/^\s*|\s*$/g, "");
}
aboutFieldCount();
