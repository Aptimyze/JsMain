$(document).ready(function() 
{
privacybind();
});
function privacybind()
{
$(function()
{ var visibleAllOrNot;	
        $('#visibleAll').bind("click",function() 
        {
		 $('#check1').css("visibility","hidden");
                 $('#checkLoader1 img').css("visibility","visible");
		 $('#checkLoader1').css("visibility","visible"); 
                 visibleAllOrNot =1;
                 privacy("A");                 
        });
        $('#visibleLimit').click(function() 
        {
		 $('#check2').css("visibility","hidden");
		 $('#checkLoader2 img').css("visibility","visible"); 
		 $('#checkLoader2').css("visibility","visible"); 
		 visibleAllOrNot =2;
		 privacy("C");
        });
        
       
        var randomnumber=Math.floor(Math.random()*11111);
		
		function privacy(option)
		{ 
		$.ajax(
                {          				
                        url: '/profile/change_photo_privacy.php',
                        data: "photo_display="+option+"&rnumber="+randomnumber,
                        //timeout: 5000,
                        success: function(response) 
                        { 
				CommonErrorHandling(response);
                                if(response == 'C')
                                {
                                        $('#check1').css("visibility","hidden");
                                        $('#check2').css("visibility","visible"); 
                                        $('#checkLoader2').css("visibility","hidden");                   
                                        $('#checkLoader2 img').css("visibility","hidden");                   
				}
                                else if(response == 'A')
                                {
                                        $('#check1').css("visibility","visible");
                                        $('#checkLoader1').css("visibility","hidden");
                                        $('#check2').css("visibility","hidden");          
                                        $('#checkLoader1 img').css("visibility","hidden");                   
                                }       
			},
			error: function(result) {
				$('#checkLoader1').css("visibility","hidden");
				$('#checkLoader2').css("visibility","hidden");
				$('#checkLoader1 img').css("visibility","hidden");                   
				$('#checkLoader2 img').css("visibility","hidden");                   
				$('#check'+visibleAllOrNot).css("visibility","hidden");
				$('#checkLoader'+visibleAllOrNot).css("visibility","hidden");
			}
				});
		}
});
}
