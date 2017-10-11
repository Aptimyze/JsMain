$(document).ready(function(){
	$("#textExample").verticaltabs({speed: 0,slideShow: false,activeIndex: 1});
	$("#imageExample").verticaltabs({speed: 0,slideShow: true,slideShowSpeed: 3000,activeIndex: 0,playPausePos: "topRight"});
});
$(document).ready(function(){
	$("#textExample").verticaltabs({speed: 0,slideShow: false,activeIndex: 1});
	$("#imageExample").verticaltabs({speed: 0,slideShow: true,slideShowSpeed: 3000,activeIndex: 0,playPausePos: "topRight"});
});
$(document).ready(function()
{
	$(".mainTable").hide();
	$("#hoverChoose").hide();
	$("#memDetailsContainer").hide();
	$(".carryForms").bind("contextmenu",function()
	{
		return false;
	});

	var extraVal =$("#extraSpace").val();
	if(extraVal==1)
		$(".extraSp").html("<font class='fs24' style='padding-left:57px;color:#000000'><span>&nbsp;</span></font>");

	getLandingScenario(user);
	$(".main").click(function()
	{
		if(user.profileid!='')
		{
			var planId=$(this).attr("id");
			$("#memDetailsContainer").show();
			selectHoverDivLocation(planId);
			$(".mainTable").hide();
			$("#"+planId+"Table").show();
			$("#hoverChoose").show();
			//For Tracking
			var checkedMem=$("input[name='"+planId+"PriceRadio']:checked").val();
                        if(checkedMem)
                        {
                                checkedMem=checkedMem.replace("main","");
                                var navigationString=$("[name=navigationString]").val();
                                if(navigationString=="")
                                        $("[name=navigationString]").val(checkedMem);
                                else
                                        $("[name=navigationString]").val(navigationString+","+checkedMem);
                        }
			//Tracking Code Ends
			$("[name=continueMainId]").val(planId);	
			$.cookie("changedMainMem","1");
		}
		else
		{
			$.colorbox({href:SITE_URL+"/static/registrationLayer?pageSource=MemChsPlan"});
		}
	});
	$(".carryForms").click(function()
	{
		if(user.profileid!='')
		{
			var continueMem=$("[name=continueMainId]").val();
			var clickedId=$(this).attr("id");
			if(clickedId=="jsExclusive")
			{
				$("form#js-exclusive").submit();
			}
			else if(clickedId=="paymentOptionTab")
			{
				if(continueMem)
				{
					var checkedMem=$("input[name='"+continueMem+"PriceRadio']:checked").val();
					if(checkedMem)
						$("[name=continueMainSubId]").val(checkedMem.substring(4));
				}
				$("form#masterToPayment").submit();
			}
			else if(clickedId=="valueAddedTab" || clickedId=="continueMain")
			{
				var checkedMem=$("input[name='"+continueMem+"PriceRadio']:checked").val();
				if(checkedMem)
					$("[name=continueMainSubId]").val(checkedMem.substring(4));
				$("form#mainMemForm").submit();
			}
		}
		else
		{
			var forwardTo=$(this).attr("id");
			if(forwardTo=="valueAddedTab")
			{
				var pageSrc="MemChsVAS";
				$.colorbox({href:SITE_URL+"/static/registrationLayer?pageSource="+pageSrc});
			}
			else if(forwardTo=="paymentOptionTab")
			{
				var pageSrc="MemPymtOpt";
				$.colorbox({href:SITE_URL+"/static/registrationLayer?pageSource="+pageSrc});
			}
			else if(forwardTo=="jsExclusive")
			{
				var selectedExc=$("#jsExcRadioSel").val();
				if(selectedExc.substring(1)=="3")
					var pageSrc="MemJSEx3";
				else if(selectedExc.substring(1)=="6")
          var pageSrc="MemJSEx6";
        else
					var pageSrc="MemJSEx12";
				$.colorbox({href:SITE_URL+"/static/registrationLayer?pageSource="+pageSrc});
			}
		}
	});
	$(".showVAS").click(function(event)
	{
		var text=$("#showHideVas").html();
		if(text=="Close")
		{
			$("#showHideVas").html("Details");
			$("#discountBar").show();
			$("#showHideImage").css("background-position","1px -43px");
			$("#openCloseVAS").hide();
		}
		else if(text=="Details")
		{
			$("#discountBar").hide();
			$("#showHideImage").css("background-position","1px -21px");
			$("#showHideVas").html("Close");
			$("#openCloseVAS").show();
		}
	});
	$(".ques-icn").click(function(event)
        {
		var id=$(this).attr("id");
		var subid=id.substring(0,1);
		$("#vasBenefits").css("visibility","visible");
		$("#vasContent").html($("#"+subid+"hiddenDiv").attr("value"));
		var offset = $(this).offset();
		var height=$("#vasBenefits").height();
		var width=$("#vasBenefits").width();
		offset.left-=width/2;
		offset.top-=height;
		$("#vasBenefits").offset(offset);
        });
	$(".close-layer").click(function()
	{
		$("#vasBenefits").css("visibility","hidden");
	});
	$("#subscriptions").click(function(event)
        {
		var w=$("#lightbox").width();
		var h=$("#lightbox").height();
		if(h>150)
			height=h/3 +10;
		else
			height=h/3;
		$("#pointIndicator").css({top:height});
		$("#lightbox").css({top:-height})
                $("#lightbox").show();
		event.stopPropagation();
        });
	$('html').click(function()
        {
                $("#lightbox").hide();
        });
	$(".btn-close").click(function()
	{
		$("#lightbox").hide();
	});
	$("input:radio[class=widthauto]").click(function()
	{
		var selectedMembrshp=$(this).attr("id");
		var navigationString=$("[name=navigationString]").val();
                if(navigationString=="")
                        $("[name=navigationString]").val(selectedMembrshp);
                else
                        $("[name=navigationString]").val(navigationString+","+selectedMembrshp);
		membershipSelect(selectedMembrshp);	
	});

	$("input:radio[name=r1]").click(function()
	{
		var id=$(this).attr("id");
		var price='';
		$("#jsExcRadioSel").val(id);
    $("#exclusivePricesDiv div").each(function(){
      if(this.id == id){
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    // $.post("/membership/jsExcPrices",{ 'id' : id,'price':user.currency },function(response){
    //  var res = response.split(",")
    //  $("#excPrice").html(res[0]);
    //  $("#excOfferPrice").html(res[1]);
    // });
  });

});
function selectHoverDivLocation(planId)
{
	/*Changes for the positioning of the hover choose plan table*/
	var chooseOffsetLeft,left,tblOffset;
	if(planId!='' && planId!='X')
	{
		chooseOffsetLeft=$("#"+planId).offset().left;
		tblOffset=$("#servcCols").offset().left;
		left=(chooseOffsetLeft-tblOffset)+117;
		$("#hoverChoose").css("left",left+"px");
	}
}
function getLandingScenario(user)
{
	var membership=user.memStatus;
	var usrType=user.userType;
  if(usrType!=1){
    membership = "P";
  }
	var activeTable=$("#activeTable").val();
	if(membership || activeTable)
	{
		var mainTable;
		if(activeTable=="Table")
			activeTable="";
		if(activeTable)
		{
			mainTable=activeTable.substring(0,1);
		}
		else 
			mainTable=membership.substring(0,1);
		if(mainTable=="E")
			mainTable="ESP";
    if(mainTable=="N")
      mainTable="NCP";
		if(serviceInactive!=true && mainTable !='X' && mainTable !="")
		{ 
			$("#memDetailsContainer").show();
			$("#"+mainTable+"Table").show();
			selectHoverDivLocation(mainTable);
			$("#hoverChoose").show();
		}
		$("[name=continueMainId]").val(mainTable);
		var checkedMem=$("input[name='"+mainTable+"PriceRadio']:checked").val();
		if(checkedMem)
			$("[name=continueMainSubId]").val(checkedMem.substring(4));
		if($("#selMemArray").val()==0)
		{
			membershipSelect(membership);
		}
	}
}
function membershipSelect(membership)
{
	var membrshpMonth="";
	var membrshpServ="";
	var membrshpRadio="";
        var i=0;
	var match=membership.match(/\d+/g);
	if(match!=null)
	{
		membrshpServ=membership.replace(/\d+/g, '');
		membrshpMonth=membership.replace(/[A-Za-z$-]/g, "");
		if(membrshpServ=="ESP" && membrshpMonth=="12")
			membrshpMonth="L";
	}
	else
	{ 
		membrshpMonth=membership.slice(membership.length-1);
		membrshpServ=membership.slice(0,membership.length-1);
	}
	$("#selMemDur").val(membrshpMonth);
	for(i=0;i<4;i++)
	{
		if(i==0)
			membrshpServ="P";
		else if(i==1)
			membrshpServ="D";
		else if(i==2)
			membrshpServ="C";
    else if(i==3)
      membrshpServ="NCP";
		else
			membrshpServ="ESP";
		
		membrshpRadio=membrshpServ+membrshpMonth;
		id=$('input:radio[name="'+membrshpServ+'PriceRadio"]:checked').prop("id");
		if(id!=membrshpRadio)
			$("#"+id).prop('checked',false);
		
		if($("#"+membrshpRadio).val())
		{
			$("#"+membrshpRadio).prop('checked',true);
       		}
       		else if(popular[membrshpServ]!=null)
		{
			membrshpRadio=popular[membrshpServ];
			$("#"+membrshpRadio).prop('checked',true);
       		}
	}
}

var Accordion1 = new Spry.Widget.Accordion("Accordion1");
