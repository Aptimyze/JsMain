var page = "REG";
var docF = document.form1;
var user_login =1;
$(document).ready(function () {
		$('#reg_messenger_id').val('e.g. raj1983, vicky1980 ');
		$('#reg_messenger_id').css('color','gray');
		$("#reg").validate({
		onkeyup : false,
		onfocusout: function(element) {
					$(element).valid(); 
		},
		errorPlacement: function(error, element) {
			if($(element).attr("id")=="reg_messenger_channel")
			{
				id="#messenger_channel_err";
			}
			else
			{
				id="#messenger_id_err";
			}
			$(id).css("display","inline");
			$(id).html(error);
			
		}
		});    	
		
		$("#reg_messenger_id").rules("add", {
				messengerCheck: true,
				messengerPattern: true,
				messengerOneAlpha: true,
				minMesLen: true
				
				
		});
		$('#reg_messenger_id').focus(function(){
		//Check val for messenger
			if($(this).val() == 'e.g. raj1983, vicky1980 '){
				$(this).val('');
				$(this).css('color','black');
			}
		}).blur(function(){
			//check for empty input
			if($(this).val() == ''){
				$(this).val('e.g. raj1983, vicky1980 ');
				$(this).css('color','gray');
			}
			else
			{
				$(this).css('color','black');
			}
		});
		$("#reg_messenger_channel").rules("add", {
				reqChannel : true
				
		});
		$("#reg_handicapped").change(function(){
			
				if(this.value == 1  || this.value == 2){
					$('#nature_handi').css('display','inline');
					
				}
				else
				{
					$('#nature_handi').css('display','none');
					$("#nature_handi").value='';
				}
		});
		
		
	
});

// invalid words in messenger id
jQuery.validator.addMethod("messengerCheck", function(value, element) {
	var invalidId = new Array("no", "none", "messenger id", "messenger", "gmail", "facebook", "gmail.com", "yahoo", "no id", "google", "rediffmail", "rediff", "na", "nil", "any", "good", "non", "yes", "later", "hello", "hindi", "orkut", "skype", "love", "airtel", "nothing", "face book", "i love you", "google talk");
	if(value == 'e.g. raj1983, vicky1980 ')
	{
		var value = '';
		mesIdTrue = 1;
		return true;
	}
	var mes = value.split("@");
	if(jQuery.inArray(mes[0],invalidId) !=  -1)
	{
		mesIdTrue = 0;
		return false;
	}
	else
	{
		mesIdTrue = 1;
		return true;
	}

},$("#err_messenger_invalid").html());


// regex pattern check on messenger
jQuery.validator.addMethod("messengerPattern", function(value, element) {
	if(value == 'e.g. raj1983, vicky1980 ')
		var value = '';
	var mes = value.split("@");
	var mes_regex1 = /^[a-zA-Z0-9._%+-@]+$/;
	if( value !='')
	{
		if(!mes_regex1.test(mes[0]))
		{
			mesIdTrue = 0;
			return false;
		}
		else
		{
			mesIdTrue = 1;
			return true;
		}
	}
	mesIdTrue = 1;
	return true;
},$("#err_messenger_pattern").html());


// one alphabate req pattern check on email
jQuery.validator.addMethod("messengerOneAlpha", function(value, element) {
	if(value == 'e.g. raj1983, vicky1980 ')
		var value = '';
	var mes = value.split("@");
	var mes_regex1 = /.*[a-zA-Z]+.*/;
	if( value !='')
	{
		if(!mes_regex1.test(mes[0]))
		{
			mesIdTrue = 0;
			return false;
		}
		else
		{
			mesIdTrue = 1;
			return true;
		}
	}
	mesIdTrue = 1;
	return true;
},$("#err_messenger_alpha").html());

// min lenghth in messenger id
jQuery.validator.addMethod("minMesLen", function(value, element) {
	if(value == 'e.g. raj1983, vicky1980 ')
		var value = '';
	var mes = value.split("@");
	var len = value.split("@")[0].length;
	if( value !='')
	{
		if(len < 4)
		{
			mesIdTrue = 0;
			return false;
		}
		else
		{
			mesIdTrue = 1;
			return true;
		}
	}
	mesIdTrue = 1;
	return true;
},$("#err_messenger_min").html());

// req messenger channel
jQuery.validator.addMethod("reqChannel", function(value, element) {
	if($("#reg_messenger_id").val() == 'e.g. raj1983, vicky1980 ')
		var val = '';
	var mes = $("#reg_messenger_id").val().split("@");
	if( mes[0] !='');
	{
		if(value == '' && val != '')
			return false;
		else
			return true;
	}
},$("#err_messenger_req").html());
