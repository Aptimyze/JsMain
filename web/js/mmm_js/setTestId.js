$(document).ready(function(){
	$("#form2").submit(function(){ 
					if(!$("#site").val()) 
					{	alert("Please select 'Site'");
						return false;
					}
					if($("#emailIdsP").val() == '')
					{
						alert("Please enter email ids");
						return false;
					}
	});
	$("#form3").submit(function(){ 
					if(!$("#mailer_id").val()) 
					{	alert("Please select 'mailer id'");
						return false;
					}
					/*if($("#emailIdsT").val() == '')
					{
						alert("Please enter email ids");
						return false;
					}*/
	});
});
