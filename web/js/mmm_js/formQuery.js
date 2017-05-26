$(document).ready(function(){
	$(".all").click(function(){
		x = $('input:checkbox[name*='+this.id+']');
		if(x.get(0).checked)
		{	for( var i = 1; i < x.length; i++)
			{
				x.get(i).checked = false;
			}
		}
	});
	$("#form1").submit(function(){ 
					if(!$("#mailer_id").val()) 
					{	alert("Please select Mailer Id");
						return false;
					}
					if($("#upper_limit").val() && !$.isNumeric($("#upper_limit").val()))
					{
						alert("Please enter a number for 'limit on number of results'");
						return false;
					}
	});
	$("input:checkbox[name*=manglik]").click(function(){check("manglik");});
	$("input:checkbox[name*=mstatus]").click(function(){check("mstatus");});
	$("input:checkbox[name*=havechild]").click(function(){check("havechild");});	
	$("input:checkbox[name*=btype]").click(function(){check("btype");});	
	$("input:checkbox[name*=complexion]").click(function(){check("complexion");});	
	$("input:checkbox[name*=smoke]").click(function(){check("smoke");});
	$("input:checkbox[name*=diet]").click(function(){check("diet");});	
	$("input:checkbox[name*=handicapped]").click(function(){check("handicapped");});
	/*$(".notall").click(function(){
		x = $("input:checkbox[name*="+this.id+"]");
		for(var i = 1; i < x.length; i++)
		{
			if(x.get(i).checked)
			{
				x.get(0).checked = false;
				break;
			}
		}
	});*/
});
function check(param)
{
	x = $("input:checkbox[name*="+param+"]");
	for(var i = 1; i < x.length; i++)
	{
		if(x.get(i).checked)
		{
			x.get(0).checked = false;
			break;
		}
	}
}

