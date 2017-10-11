//this part calculate the offset postion and height of the pink background
var Pinkbgtop,Pinkbghgt;
var abc=0;
$(document).ready(function() {
(function(){
	if($( "#hpblk2" ).length)
	{
		var PinkbgPos = $( "#hpblk2" );
		var Pinkbgoffset = PinkbgPos.offset();
		Pinkbgtop = Pinkbgoffset.top;
		Pinkbghgt = $( "#hpblk2" ).height();		
	}

})();
});

$(window).keypress(function(e) {
    if(e.which == 13) {
        ValidateMobileNumber();
    }
});

function OnScrollChange()
{
  //console.log("mmm");
  
	if(window.scrollY>0)
	{
		$('.js-topnav').addClass('topnavpos2');
	}
	else{$('.js-topnav').removeClass('topnavpos2');}	
	var p = $( ".Widposabs " ).offset();
	if(p!=undefined && p!=null)
	{
		if( (p.top>Pinkbgtop) && (p.top < (Pinkbgtop+Pinkbghgt)) )
		{
			$(".Widposabs").addClass("hlpcl2");
		}
		else
		{
			$(".Widposabs").removeClass("hlpcl2");
		}	
	}
  if($( "#topNavigationBar" ).hasClass( "pos_fix" ))
  {
    $('#hpSearchBar').addClass('hpp11new');
  }
  else
  {
    $('#hpSearchBar').removeClass('hpp11new');
  }
}


function sendLinkRequest(mobile)
    { 
      if(abc==1)
        return false;
      abc=1;
      $("#loader").removeClass("disp-none");
      $("#GetLink").addClass("disp-none");
    $.ajax(
                {       

                      //$("#GetMsgID").off('click');          
                        url: '/common/SendAppLink',
                        data: "mobile="+mobile,
                        //timeout: 5000,
                        success: function(response) 
                        {console.log("dugui");
                          $("#loader").addClass("disp-none");
                          if(response == 1)
                          {
                            
                            $("#SendLink").removeClass("disp-none");
                            $("#MsgText").html("Message has been sent to "+mobile);
                          }
                         else if(response == 0)
                        {
                          
                          $("#SendLink").removeClass("disp-none");
                          //$("#SendIcon").addClass("disp-none");
                          $("#MsgText").html("You cannot send more than 10 SMS to the same number.");
                        }
                        else
                          {
                          $("#loader").addClass("disp-none");
                          $("#GetLink").removeClass("disp-none");
                          abc=0;
                          var ErrorEle =document.getElementById("Error");
                          ErrorMsg="Something went wrong.Please try again.";
                          document.getElementById("Error").innerHTML = ErrorMsg;
                          ErrorEle.innerHTML=ErrorMsg;
                        }
                        },
                        error: function(result) {
                          $("#loader").addClass("disp-none");
                          $("#GetLink").removeClass("disp-none");
                          abc=0;
                          var ErrorEle =document.getElementById("Error");
                          ErrorMsg="Something went wrong.Please try again.";
                          document.getElementById("Error").innerHTML = ErrorMsg;
                          ErrorEle.innerHTML=ErrorMsg;
                        }
                        });
  }



function ValidateMobileNumber(check,event)
{
        var ErrorMsg = "";
          var MobileNumber= document.getElementById("mobile_id").value;
          var ErrorEle =document.getElementById("Error");
        if(MobileNumber.length =='0'){
                 ErrorMsg="Enter Mobile No.";
                document.getElementById("Error").innerHTML = ErrorMsg;
                ErrorEle.innerHTML=ErrorMsg;
               // event.preventDefault();
         return false;
        }
        else if(!checkNum(MobileNumber)){
                ErrorMsg="Please provide a valid mobile number";
               document.getElementById("Error").innerHTML = ErrorMsg;
                ErrorEle.innerHTML=ErrorMsg;
                //event.preventDefault();
        return false;
        }
        else if(MobileNumber.length<10 || MobileNumber.length>10){
                ErrorMsg="Please provide a valid mobile number";
                 ErrorEle.innerHTML=ErrorMsg;
                document.getElementById("Error").innerHTML = ErrorMsg;
               // event.preventDefault();
                return false;
        }
        else{
         // AppSms();
                //document.frm_mob.submit();
               // jsBannerResponse(MobileNumber);
               sendLinkRequest(MobileNumber);
            /*   if(check=="upper")
               document.getElementById("mobileValid").submit();
             else
              document.getElementById("mobileValid1").submit();*/
               return true;
        }
        
        
}
function checkNum(iNumber)
{
    var i;
    for (i=0;i<iNumber.length;i++)
    {
        var c = iNumber.charAt(i);
        if (! checkDigit(c))
            return false
    }

    return true;
}

function checkDigit(c)
{
    return ((c >= "0") && (c <= "9"))
}

$(function(){		
	//start script for browse matrimonial profile by
		$("ul.tabs li").click(function() {				
			var relVal = $(this).attr('rel');	
			$("ul.tabs li").removeClass("active");
			$(this).addClass("active");	
			$('.tab_content').each(function(){
					var getid = $(this).attr('id');
					if ($(this).css("visibility") == "visible") {
        				$(this).fadeOut(200,function(){
							$(this).css('visibility','hidden');
							$('#'+relVal).fadeIn(200,function(){$(this).css('visibility','visible')}) ;
						});
    				} 
			});
		});			
		$(".tab_content").find('.browsebyp ul li.sub_h').each(function(){
			var TempWid = 0;
			var TempWid1 = 0, newLeft =0;
			TempWid = $(this)[0].getBoundingClientRect().width;
			$(this).find('.sub').each(function(){ 				
				TempWid1 = $(this)[0].getBoundingClientRect().width;
				newLeft = Math.abs((TempWid1/2) - (TempWid/2))-4;
				$(this).css('left',-(newLeft));
			});		
		});
		//start script for help widget
		$('.Widposabs').on('click', function(event) {
			var Temp = $('.hlpwhite').css('right');
			if(Temp!="0px")		{
				
				$('.hlpwhite').animate({right:"0px"},200);
			}
			else
			{			
				$('.hlpwhite').animate({right:"-171px"},200);
			}				
		});
		//start script for help widget scroll color change
		$( window ).scroll(function() {
			OnScrollChange();
		});
		//start script for serach by id
		$('#srchbyid').click(function(){			
			 $('.hpoverlay').fadeIn(200,"linear",function(){ $('#srcbyid-layer').fadeIn(300,"linear")});
		});
		$('#cls-srcbyid').click(function(){
			$('#srcbyid-layer').fadeOut(200,"linear",function(){ $('.hpoverlay').fadeOut(200,"linear")});
			
		});
		
		
	
	
	
});

