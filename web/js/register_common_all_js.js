function dID(arg)
{
	return document.getElementById(arg);
}
/*Function to display help box (balloon tip)*/
function show_help(obj)
{
        var focus_name = obj.id;
       /* var i1 = help_arr.length;
        for(var i=0;i<i1;i++)
        {
                if(focus_name == help_arr[i])
                {
                        dID(focus_name+"_help").style.display='block';
                        break;
                }
        }*/
        dID(focus_name+"_help").style.display='block';
}

/*Function to hide help box (balloon tip)*/
function hide_help(obj)
{
        var blur_name = obj.id;
       /* var i1 = help_arr.length
        for(var i=0;i<i1;i++)
        {
                if(blur_name == help_arr[i])
                {
                        dID(blur_name+"_help").style.display='none';
                        break;
                }
        }*/
        dID(blur_name+"_help").style.display='none';
}
function ajax_leadi(type)
{
		if(type=='M'){
			if($('#reg_email').val() && $('#reg_phone_mob_mobile').val())
		var value={"email":$('#reg_email').val(),"mobile":$('#reg_phone_mob_mobile').val(),"source":$('#reg_source').val()};
		}
		if(value){
        var url1="/register/misRegCapturelead";
		$.ajax({
			type: 'POST',
			url: url1,
			data: value,
			success: function(data){
				if(data)
				document.getElementById("leadid").value=data;
				
			}
		});
		}
}	
