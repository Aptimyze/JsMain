
var attr="You may consider answering these questions: \n \n 1. How would you describe yourself?\n 2. What kind of food/movies/books/music you like? \n 3. Do you enjoy activities like traveling, music, sports etc? \n 4. Where have you lived most of your life till now?\n 5. Where do you wish to settle down in future?";
$(document).ready(function () {
	$("#reg").validate({
		onkeyup : false,
		onfocusout: function(element) {
					$(element).valid(); 
		},
		  errorElement: "div",
		  errorPlacement: function(error, element) {
		   var var_name=element.attr('name');
		   var var_name_str=var_name.substring(var_name.lastIndexOf("[")+1,var_name.lastIndexOf("]"));
		   var err_name_str=var_name_str+'_err';
		   var for_name_str='reg_'+var_name_str;
			$('#'+err_name_str).css('display','inline');
			$('#'+err_name_str).attr('for',for_name_str);
			$('#'+err_name_str).html(error.html());
		  	;
		  },
		    	
		highlight: function(element, errorClass, validClass) {
			$(element.form).find("label[for="+ element.id +"]")
			        .css("color","red");
	    },
		unhighlight: function(element, errorClass, validClass) {
	        $(element.form).find("label[for=" + element.id + "]")
	        .css("color","");
		}
	}); 	
	$("#reg_yourinfo").rules("add",{
	  required : true,
	  minlength:100,
          checkyourinfo :true,
	  messages:
	  {
		required: $("#yourinfo_required").html(),
		minlength : $("#yourinfo_required").html(),
	  }
	}); 
	$("#reg_edu_level_new").rules("add",{
	  required : true,
	  messages:
	  {
		required: $("#edu_level_new_required").html(),
	  }
	}); 
	$("#reg_occupation").rules("add",{
	  required : true,
	  messages:
	  {
		required: $("#occupation_required").html(),
	  }
	}); 
	$("#reg_income").rules("add",{
	  required : true,
	  messages:
	  {
		required: $("#income_required").html(),
	  }
	}); 
	if(!$("#reg_yourinfo").val())
	{
		$("#reg_yourinfo").val(attr);
		$("#reg_yourinfo").css("color","#a7a7a7");
		//$("#reg_yourinfo").css("font-size","11px");
	}

	$("#reg_yourinfo").bind("focus",function(){ var input = $(this);   if (input.val() == attr) {     input.val('');this.style.color='#000';  } });
	$("#reg_yourinfo").bind("blur",function() {   

	var input = $(this);   if (input.val() == '' || input.val() == attr) {   input.val(attr); this.style.color='#a7a7a7';
} 
});  	
});


// about yourself field count display

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

function trim_newline(string){
	return string.replace(/^\s*|\s*$/g, "");
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


jQuery.validator.addMethod("checkyourinfo",function(value,element)
{
	
        if($("#reg_yourinfo").val()==attr)
                return false;
        return true;
},$("#yourinfo_required").html());

        function showDegreeFields()
        {
		$("#other_pg_degree").hide();
		$("#other_ug_degree").hide();
		var highestDegree = $("#reg_edu_level_new").val();
		var inug = $.inArray(highestDegree,ugArr);
		$("#reg_college").val('');
		$("#reg_addMoreUgDegree").val('');
		$("#reg_pg_college").val('');
		$("#reg_degree_ug").val('');
		$("#reg_degree_pg").val('');
		$("#reg_addMorePgDegree").val('');
		$("#reg_other_pg_degree").val('');
		$("#reg_other_ug_degree").val('');
		if(inug!=-1)
		{
			$("#college").hide().css("padding-bottom","");
			$("#addMoreUgDegree").hide();
			$("#pg_college").hide();
			$("#degree_ug").hide();
			$("#degree_pg").hide();
			$("#addMorePgDegree").hide();
			return;
		}
		var inbachelor = $.inArray(highestDegree,bachelorArr);
		if(inbachelor!=-1)
		{
			$("#college").show().css("padding-bottom","12px");
			$("#addMoreUgDegree").show();
			$("#pg_college").hide();
			$("#degree_ug").hide();
			$("#degree_pg").hide();
			$("#addMorePgDegree").hide();
			return;
		}
		var inmaster = $.inArray(highestDegree,pgDegreeArr);
		if(inmaster!=-1)
		{
			$("#college").show().css("padding-bottom","12px");
			$("#addMoreUgDegree").show();
			$("#pg_college").show();
			$("#degree_ug").show();
			$("#degree_pg").hide();
			$("#addMorePgDegree").show();
			return;
		}
		var inPhd = $.inArray(highestDegree,phdArr);
		if(inPhd!=-1)
		{
			$("#college").show().css("padding-bottom","8px");
			$("#addMoreUgDegree").show();
			$("#pg_college").show();
			$("#degree_ug").show();
			$("#degree_pg").show();
			$("#addMorePgDegree").show();
			return;
		}
		if(inug==-1 && inmaster==-1 && inPhd==-1 && inbachelor==-1)
		{
			$("#college").hide();
			$("#addMoreUgDegree").hide();
			$("#pg_college").hide();
			$("#degree_ug").hide();
			$("#degree_pg").hide();
			$("#addMorePgDegree").hide();
		}
        }
function adjustGap()
{
	if($("#addMorePgDegree").css("display")=="none"&&$("#addMoreUgDegree").css("display")=="none")
	{
		$("#college").css("padding-bottom","");
		$("#other_pg_degree").css("padding-bottom","");
	}
}
function showOtherPgDegree()
{
	$("#addMorePgDegree").hide();
	$("#other_pg_degree").show();
	$("#college").css("padding-bottom","");
	if($("#addMoreUgDegree").css("display")!="none")
		$("#other_pg_degree").css("padding-bottom","8px");
}

function showOtherUgDegree()
{
	$("#addMoreUgDegree").hide();
	$("#other_ug_degree").show();
	adjustGap();
}
