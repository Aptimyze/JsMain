$(document).ready(function () {
$("#reg").validate({
	onkeyup : false,
			onfocusout: function(element) {
				$(element).valid(); 
	},
	});
if($('#reg_edu_level_new').length ==1)
$('#reg_edu_level_new').rules("add",{
	required:true,
	messages:
	{
		required:$('#edu_level_new_required').html()
	}    
});
if($('#reg_occupation').length ==1)
$('#reg_occupation').rules("add",{
	required:true,
	messages:
	{
		required:$('#occupation_required').html()
	}    
});
if($('#reg_income').length ==1)
$('#reg_income').rules("add",{
	required:true,
	messages:
	{
		required:$('#income_required').html()
	}    
});
if($('#reg_city_res').length ==1)
$('#reg_city_res').rules("add",{
        required:true,
        messages:
        {
                required:$('#city_res_required').html()
        }
});
if($('#reg_pincode').length ==1)
$("#reg_pincode").rules("add",{
                  blankCheck : true,
                  digits : true,
                  minlength:6,
                  maxlength:6,
                  pinInitials : true,
                  messages:
                  {
			blankCheck: $("#err_pin_req").html(),
                        pinInitials : SetPinIniMes,
                        minlength : $("#err_pin_invalid").html(),
                        maxlength : $("#err_pin_invalid").html(),
                        digits :  $("#err_pin_invalid").html(),
                  }
                });
});
var ArrayPincode={'DE00':{0:["1100","2013","1220","2010","1210","1245"],1:4,2:"Please provide a pincode that belongs to Delhi"},"MH04":{0:["400","401","410","421","416"],1:3,2:"Please provide a pincode that belongs to Mumbai"},"MH08":{0:["410","411","412","413"],1:3,2:"Please provide a pincode that belongs to Pune"}};
function SetPinIniMes()
{
        $("#err_pin_delhi span").html("<small></small>"+ArrayPincode[$("#reg_city_res").val()][2]);
        return $("#err_pin_delhi").html();
}

function checkPinInitials(pin)
{
        var city_pin=$("#reg_city_res").val();

        var initial = pin.substring(0,ArrayPincode[city_pin][1]);

        if(jQuery.inArray(initial,ArrayPincode[city_pin][0])==-1)
        {
                return false;
        }
        return true;

}

jQuery.validator.addMethod("pinInitials",function(value,element)
{
        var validPin = true;
        if((ArrayPincode[$("#reg_city_res").val()]))
                var checkPin = true;
        else
                var checkPin = false;

        if(checkPin)
                var validPin = checkPinInitials(value);
        return (validPin);
},SetPinIniMes);

jQuery.validator.addMethod("blankCheck",function(value,element)
{
	if(ArrayPincode[$("#reg_city_res").val()] && value == '')
        	return false;
        return true;
},$("#err_pin_req").html());
$("#reg_city_res").bind("change",function(){callPincode();});
$(document).ready(function(){callPincode();});
function callPincode()
{
	if($("#reg_pincode").val())
                $("#reg_pincode").trigger("blur");

        if(ArrayPincode[$("#reg_city_res").val()])
                $("#reg_pincode").parent().css("display","inline");
        else
                $("#reg_pincode").parent().css("display","none");

}


