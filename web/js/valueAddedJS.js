$(document).ready(function()
{
	var total=0;
	var i=1;
	var countChecked=0;
	$(".tuples").hide();
	if(user.currency=="DOL")
		currencyLabel="$";
	else
		currencyLabel="Rs. ";
	$(".carryForms").bind("contextmenu",function()
	{
		return false;
	});
	$(".vam").each(function()
	{

		var id=$(this).attr('id');
		subid=id.substring(0,1);
		var duration="";
		var flag=0;
		var count=0,countNew=0;
		if(selMemDur=="")
			duration=user.memStatus.replace(/[A-Za-z$-]/g, "");
		else
			duration=selMemDur;
		$("#"+id).attr("selectedId","");
		if(duration=="")
		{
			duration='L';
		}
		setVASCheckId(duration,id,subid);
	});
	getLandingScenario();

	//get top 3 addon in first row and rest in hidden div
	/*addonOrder.forEach(function(item,key)
	{
		if(key<3)
			$("#top3").append($("#"+item+"addon"));
		else
			$("#spanmore").append($("#"+item+"addon"));
		$("#"+item+"addon").show();
	});*/

var VASImpression="";
for(var n=0;n<addonOrder.length;n++)
{
	var addonId=addonOrder[n];
	if(n<3)
		$("#top3").append($("#"+addonId+"addon"));
	else
		$("#spanmore").append($("#"+addonId+"addon"));
	if(VASImpression=="")
		VASImpression+=addonId;
	else
		VASImpression+=","+addonId;
	$("[name=VASImpression]").val(VASImpression);
	$("#"+addonId+"addon").show();
}

$(".tuples").each(function()
{
	if($(this).parent().attr("id")=="top3")
	{
		if($(this).find("input").is(":checked"))
		{
			countChecked++;
		}
	}
});


// By default we show all VAS options

showmore();

// if(countChecked >= 3 || user.userType=='7')
// {
// 	$("[name=showAll]").val(1);
// 	showmore();
// }

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
	$(".subs").show();
	event.stopPropagation();
});
	//event on click of Select Plan
	$(".selectLightbox").click(function(event)
	{
		var pos=$(this).offset();
		//locate the position of lightbox
		var id=$(this).parent().parent().parent().parent().attr("id"); 		//get Addon Id
		id=id.substring(0,1);
		var iconIndicTop=0;
		var addOnOffset=$(".mem-tab2-leftcon").offset();
		var checkdId=$("."+id+"Price").attr("id");
		if($(".selectLightbox").html()=="Change"){
			var checked="checked";
		}
		$("#lightboxData").children().remove();
		if(id=="I")
		{
			for(var subMem in vaMem[id])
			{
				var selectId=$("#"+id+"check").attr("selectedId");
				profNo=subMem.substring(1);
				if(selectId==subMem && $("#"+id+"check").prop('checked')==true)
					$("#lightboxData").append("<div><input type='radio' class='vaRadio widthauto' style='border:none;outline;none;height:18px;margin-right:4px;' name='lightboxRadio' id='"+id+profNo+"' value='"+vaMem[id][subMem]["PRICE"]+"' checked='checked'/>"+ profNo+" profiles for "+currencyLabel+vaMem[id][subMem]['PRICE']+"</div>");
				else
					$("#lightboxData").append("<div><input type='radio' class='vaRadio widthauto' style='border:none;outline:none;height:18px;margin-right:4px;'name='lightboxRadio' id='"+id+profNo+"' value='"+vaMem[id][subMem]["PRICE"]+"' />"+ profNo+" profiles for "+currencyLabel+vaMem[id][subMem]['PRICE']+"</div>");
			}
		}
		else
		{
			for(var subMem in vaMem[id])
			{
				var selectId=$("#"+id+"check").attr("selectedId");
				if(vaMem[id][subMem]['DURATION']=="1")
					var monthLabel=" Month";
				else
					var monthLabel=" Months";
				if(selectId==subMem && $("#"+id+"check").prop('checked')==true)
					$("#lightboxData").append("<div><input type='radio' class='vaRadio widthauto' style='border:none;outline:none;height:18px;margin-right:4px;' name='lightboxRadio' id='"+id+vaMem[id][subMem]['DURATION']+"' value='"+vaMem[id][subMem]["PRICE"]+"' checked='checked' />"+ vaMem[id][subMem]['DURATION']+monthLabel+" for "+currencyLabel+vaMem[id][subMem]['PRICE']+"</div>");
				else
					$("#lightboxData").append("<div><input type='radio' class='vaRadio widthauto' style='border:none;outline:none;height:18px;margin-right:4px;' name='lightboxRadio' id='"+id+vaMem[id][subMem]['DURATION']+"' value='"+vaMem[id][subMem]["PRICE"]+"' />"+ vaMem[id][subMem]['DURATION']+monthLabel+" for "+currencyLabel+vaMem[id][subMem]['PRICE']+"</div>");
			}
		}
		if(id!="M"){
			if($("#"+id+"SelOrChange").html()=="Change"){
				if(checkdId!=null)
					$("input[type=radio][id="+checkdId+"]").attr("checked","checked");
			}
		}
		var h=$(".dialog").height();
		var iconIndicTop=h/2-$(".dialog-ico-indicator").height()/2;
		pos.left=pos.left-addOnOffset.left+$(".selectLightbox").width()+$(".dialog-ico-indicator").width();
		pos.top=pos.top-addOnOffset.top-($(".dialog").height()/2);
		$(".dialog-ico-indicator").css("top",iconIndicTop);
		$(".dialog").css("position","absolute");
		$(".dialog").css("left",pos.left);
		$(".dialog").css("top",pos.top);
		$(".dialog").show();
		$('<div id="overlay" />').css(
		{
			position:'fixed'
			, width: '100%'
			, height : '100%'
			, opacity : 0.6
			, background: '#fff'
			, zIndex:9999
			, top: 0
			, left: 0
		}).appendTo(document.body);
	});

$(".Sample").click(function()
{
	var id=$(this).attr("id")+"Sample";
	$("#"+id).css("display","block");
	$("#"+id).css("position","absolute");
	$("#"+id).css("background-color","#FFFFFF");
	$("#"+id).css("z-index","10001");
		//$("#"+id).css("top","-1px");
		//$("#"+id).css("left","149px");
		$('<div id="overlay" />').css(
		{
			position:'fixed'
			, width: '100%'
			, height : '100%'
			, opacity : 0.6
			, background: '#fff'
			, zIndex:9999
			, top: 0
			, left: 0
		}).appendTo(document.body);

	});

$("#greyBoxClose").click(function(){
	$(".dialog").hide();
	$('#overlay').remove();
});
$(document).on("click","#overlay",function()
{
	$(".dialog").hide();
	$(".sampleImage").css("display","none");
	$('#overlay').remove();
});

$('html').click(function()
{
	$("#lightbox").hide();
});
$(document).keypress(function(e)
{
	if($('#overlay'))
	{
		if(e.keyCode==27)
		{
			$(".dialog").hide();
			$(".sampleImage").css("display","none");
			$('#overlay').remove();
		}
	}
});
$(".sample-close").click(function()
{
		//$(this).parent().parent().css("display","none");
		$(".sampleImage").css("display","none");
		$('#overlay').remove();

	});
	//event on change of selection of radiobutton in lightbox
	$("#lightboxData").on("click","input[type=radio][name=lightboxRadio]", function(event)
	{
		checkClick($(this));
		$(".dialog").hide();
		$('#overlay').remove();
	});
	//event on click of value added checkboxes
	$(".vam").click(function clickCheck()
	{
		checkClick($(this));
	});
	function checkClick(el)
	{
		var oldId="";
		var id=el.attr("id");
		var radioId=id;
		var type=el.attr("type");
		var selectedId=id.substring(0,1);
		var value=el.attr("value");
		var selectedPrice,selectedDuration;
		if(type!="checkbox")
		{
			//clicked or unclicked on radiobutton
			el=$("#"+selectedId+"check");
			el.prop("checked","checked");
			selectedPrice=value;
			var properId=id;
			el.attr("selectedId",id);
			selectedDuration=id.substring(1);
			id=id.substring(0,1)+"check";
		}
		else
		{
			//checked or unchecked checkbox
			var selectedCheckBox=el.attr("selectedId");
			var properId=selectedCheckBox;
			var selectedCheckBoxMain=selectedCheckBox.substring(0,1);
			selectedPrice=vaMem[selectedCheckBoxMain][selectedCheckBox]["PRICE"];
			selectedDuration=vaMem[selectedCheckBoxMain][selectedCheckBox]["DURATION"];
		}
		if(selectedDuration=="1")
			var durationLabel=" Month";
		else
			var durationLabel=" Months";
		if(el.is(':checked')||defaultShow==1)
		{
			var element="";
			if(selectedAddon[selectedId]=="")
			{
				if(type!="checkbox")
					value=$("#"+selectedId+"check").prop("value");
				element+=value+",";
				element+=selectedDuration+",";
				element+=selectedPrice+",";
				element+=properId+",";
				if(landingFreebie.indexOf(el.attr("selectedId"))>=0)
					element+="D";
				else
					element+="T";
				addToCart(element);
				oldId="";
			}
			else
			{
				var oldId="";
				$("#"+selectedId+"checkCartDuration").html(selectedDuration+durationLabel+" <a class='removeFromCart' style='cursor:pointer'>[x]</a>");
				$("#"+selectedId+"checkCartPrice").html(selectedPrice);
				oldId=$("#"+selectedId+"checkCartPrice").attr("exactValue");
				$("#"+selectedId+"checkCartPrice").attr("exactValue",properId);
				$("#"+selectedId+"check").attr("selectedId",radioId);
				if(landingFreebie.indexOf(radioId)>=0)
					$("#"+selectedId+"checkCartPrice").attr("importance","D");
				else
					$("#"+selectedId+"checkCartPrice").attr("importance","T");
			}
			for(var subMembership in vaMem[selectedId])
			{
				selectedAddon[selectedId]=subMembership;
				break;
			}
			maintainState(properId,oldId);
			if(selectedId=="I")
			{
				$("."+selectedId+"Price").html("<strong> "+currencyLabel +selectedPrice+" for "+selectedDuration+" profiles</strong>");
				$("#"+selectedId+"checkCartDuration").html(selectedDuration+" Profiles <a class='removeFromCart' style='cursor:pointer'>[x]</a>");
			}
			else if(selectedId=="M")
			{
				$("."+selectedId+"Price").html("<strong> For "+currencyLabel+matriPrice+"</strong>");
			}
			else
			{
				$("."+selectedId+"Price").html("<strong> "+currencyLabel+selectedPrice+" for "+selectedDuration+durationLabel+"</strong>");
			}
			$("."+selectedId+"Price").attr("id",selectedId+selectedDuration);
			if(selectedId!="M")
				$("#"+selectedId+"SelOrChange").html("Change");
		}
		else
		{
			var removeId=$("#"+selectedId+"check").attr("selectedId");
			removeMaintainence(removeId);
			var value=lowestPrices[selectedId];
			var memDur=user.memStatus.replace(/[A-Za-z$-]/g, "");
			if(cartMainMemDuration!='')
				setVASCheckId(cartMainMemDuration,id,selectedId);
			else
				setVASCheckId(memDur,id,selectedId);
			if(selectedId=="M")
				$("."+selectedId+"Price").html("<strong>For "+currencyLabel+ matriPrice+"</strong>");
			else
				$("."+selectedId+"Price").html("<strong>Starts @ "+currencyLabel+ value+"</strong>");
			$("#"+selectedId+"checkCart").parent().remove();
			if(selectedId!="M")
				$("#"+selectedId+"SelOrChange").html("Select Plan");
			selectedAddon[selectedId]="";
		}
		calculate();
	}

	//event on click of cross button in the cart
	$("#cartElements").on("click","a",function()
	{
		var id=$(this).parent().attr("id");
		var mainMemId=id.substring(0,1);
		var mainOrNot=id.substring(0,4);
		var removeId="main";
		if(mainOrNot=="main")
		{
			if(mainMem=="ESP" || mainMem=="NCP")
			{
				for(k=0;k<eSathiSpecials.length;k++)
				{
					$("#"+eSathiSpecials[k]+"check").prop("disabled",false);
					$("#"+eSathiSpecials[k]+"check").prop("checked",false);
					$("."+eSathiSpecials[k]+"Price").css("visibility","visible");
					$("#"+eSathiSpecials[k]+"SelOrChange").css("visibility","visible");
				}
				removeId+=subMem;
			}
			else
			{
				removeId+=subMem;
				$.cookie("subMem","");
				subMem="";
				$(".valueCart").each(function()
				{
					if($(this).attr("importance")=="D")
					{
						id=$(this).attr("id").substring(0,1);
						$("#"+id+"checkCartDuration a").trigger('click');
					}
				});
				landingFreebie="";
			}
			removeMaintainence(removeId);
			$("#mainSubMemId").val("");
		}
		else
		{
			var checkBoxId=id.substring(0,6);
			var memDur=user.memStatus.replace(/[A-Za-z$-]/g, "");
			var checkBoxMain=checkBoxId.substring(0,1);
			if(cartMainMemDuration!='')
				setVASCheckId(cartMainMemDuration,checkBoxId,checkBoxMain);
			else
				setVASCheckId(memDur,checkBoxId,checkBoxMain);
			selectedAddon[mainMemId]="";
			var selectedPrice=lowestPrices[mainMemId];
			$("#"+checkBoxId).prop("checked",false);
			if(mainMemId=="M")
				$("."+mainMemId+"Price").html("<strong> For "+currencyLabel+matriPrice+"</strong>");
			else
				$("."+mainMemId+"Price").html("<strong>Starts @ "+currencyLabel+selectedPrice+"</strong>");
			$("#"+mainMemId+"SelOrChange").html("Select Plan");
			var removeId=$(this).parent().parent().parent().find(".valueCart").attr("exactValue");
			removeMaintainence(removeId);
		}
		$(this).parent().parent().parent().remove();
		calculate();
	});
	//event on click of back button or tab
	$(".carryForms").click(function()
	{
		var i=0,j=0,allMemberships="";
		if($("#mainCartDuration").length>=0)
			$.cookie("subMem",subMem+user.profileid);
		$(".finalCart").each(function()
		{
			var price=$(this).find(".valueCart").html();
			var id=$(this).find(".valueCart").attr("id");
			var imp=$(this).find(".valueCart").attr("importance");
			var mainId=id.substring(0,1);
			if(mainId=="z")
			{
				id=id.substring(1,2);
				var selectedId=id;
				var name="main";
				allMemberships+="main"+$(this).find(".valueCart").attr("exactValue")+",";
				subMem=$(this).find(".valueCart").attr("exactValue");
			}
			else
			{
				var selectedId=selectedAddon[mainId];
				var name=$(this).find("#nameCartElement").html();
				allMemberships+=$(this).find(".valueCart").attr("exactValue")+",";
			}
			var duration=$(this).find("[id$='CartDuration']").html();
			var durationArray=duration.split("Month");
			if(durationArray[0].indexOf("Unlimited")>=0)
				durationArray[0]="L";
		});
		var goTo=$(this).attr('goTo');
		if(goTo=="paymentTab")
		{
			var navigationStringToPayment=$("[name=navigationString]").val();
			var VASImpressionToPayment=$("[name=VASImpression]").val();
			var showAllToPayment=$("[name=showAll]").val();
			$("[name=selectedStringToPayment]").attr("value",allMemberships);
			$("[name=navigationStringToPayment]").attr("value",navigationStringToPayment);
			$("[name=VASImpressionToPayment]").attr("value",VASImpressionToPayment);
			$("[name=showAllToPayment]").attr("value",showAllToPayment);
			$("#allMemberships").attr("value",allMemberships);
			$("form#carryCartForm").submit();
		}
		else if(goTo=="membershipTab")
		{
			$("[name=backSubId]").val(subMem);
			$("[name=selectedString]").val(allMemberships);
			if(subMem=="")
				$("#activeTable").val("");
			$("form#backToMembership").submit();
		}
	});
	//event on click of continue to payment
	$("#continueToPay").click(function()
	{
		var i=0;
		var j=0;
		if($("#mainCartDuration").length>=0)
			$.cookie("subMem",subMem+user.profileid);
		$(".finalCart").each(function()
		{
			var price=$(this).find(".valueCart").html();
			var id=$(this).find(".valueCart").attr("id");
			var imp=$(this).find(".valueCart").attr("importance");
			var mainId=id.substring(0,1);
			if(mainId=="z")
			{
				id=id.substring(1,2);
				var selectedId=id;
				var name="main";
			}
			else
			{
				var selectedId=selectedAddon[mainId];
				var name=$(this).find("#nameCartElement").html();
			}
			var duration=$(this).find("[id$='CartDuration']").html();
			var durationArray=duration.split("Month");
		});
		$("form#carryCartForm").submit();
	});
	//action when user comes from payment page to value added page
	setTimeout(function(){
		if(fromPayment=="1")
		{
			for(var key in cartElements)
			{
				var id=cartElements[key]["ID"];
				var mainId=id.substring(0,1);
				if(cartElements[key]["NAME"]=="main")
				{
					var price=cartElements[key]["PRICE"];
					var duration=cartElements[key]["DURATION"];
					$("#mainCartDuration").html(duration);
					$("[id$='mainCartPrice']").html(price);
				}
				else
				{
					defaultShow=1;
					$("#"+mainId+"check").trigger('click');
					selectedAddon[mainId]=id;
					var price=vaMem[mainId][id]["PRICE"];
					var duration=vaMem[mainId][id]["DURATION"];
					$("#"+mainId+"checkCartDuration").html(duration+" Months");
					$("#"+mainId+"checkCartPrice").html(price);
					$("#"+mainId+"SelectedCheckValue").html("<strong>"+currencyLabel+price+" for "+duration+" Months</strong>");
					calculate();
				}
			}
			defaultShow=0;
		}
	},10);
	$(".btn-close").click(function()
	{
		var closeId=$(this).attr("id");
		if(closeId=="closeSubscription")
			$("#lightbox").hide();
	});
	$("#chooseMembershipTab").click(function()
	{
		$("#fromVAS").val("1");
		$("#backToMembership").submit();
	});


});
function setVASCheckId(duration,id,subid)
{
	if(duration!='L' && duration!='')
	{
		duration=parseInt(duration);
		while(duration)
		{
			var flag=0;
			for(var subMemId in vaMem[subid])
			{
				if(vaMem[subid][subMemId]["DURATION"]==duration)
				{
					$("#"+id).attr("selectedId",subMemId);
					flag=1;
					break;
				}
				else if(subid=="I")
				{
					if(vaMem[subid][subMemId]["DURATION"]==duration*10)
					{
						$("#"+id).attr("selectedId",subMemId);
						flag=1;
						break;
					}
				}
			}
			if(flag==1)
				break;
			duration=duration-1;
		}
	}
	for(var subMem in vaMem[subid])
	{
		if(duration=="L"||duration=="")
			$("#"+id).attr("selectedId",subMem);
		break;
	}
}
function getLandingScenario()
{
	var i=0;
	if($.cookie("subMem")!=subMem+user.profileid)
	{
		$.cookie("subMem",subMem+user.profileid);
		$.cookie("Memberships","");
		if(mainMem=="ESP" || mainMem=="NCP")
		{
			var newEsathi=1;
			handleESP(newEsathi);
		}
		else if(mainMem=="X")
		{

		}
		else
		{
			var mainElement="main,";
			var duration=subMem.substring(1);
			mainElement+=duration+",";
			mainElement+=cartMainMemPrice+",";
			mainElement+=subMem+",";
			mainElement+="T";
			if(subMem)
			{
				addToCart(mainElement);
				maintainState("main"+subMem);
			}
			var cartElement=landingVAS.split(",");
			var mainId;
			for(j=0;j<cartElement.length;j++)
			{
				mainId=cartElement[j].substring(0,1);
				var valueCartElement="";
				if(mainId)
				{
					if(vaMem[mainId][cartElement[j]])
					{
						var name=vaMem[mainId][cartElement[j]]["NAME"];
						var count=name.lastIndexOf("-");
						name=name.substring(0,count);
						if(name=="Matri")
							name="Matri Profile";
						valueCartElement+=name+",";
						valueCartElement+=vaMem[mainId][cartElement[j]]["DURATION"]+",";
						valueCartElement+=vaMem[mainId][cartElement[j]]["PRICE"]+",";
						valueCartElement+=cartElement[j]+",";
						if(landingFreebie.indexOf(cartElement[j])>=0)
							valueCartElement+="D";
						else
							valueCartElement+="T";
						addToCart(valueCartElement);
						maintainState(cartElement[j]);
						checkVAS(valueCartElement);
					}
				}
			}
		}
	}
	//if user did not make any changes
	else
	{
		if(mainMem=="ESP" || mainMem=="NCP")
		{
			var newEsathi=0;
			handleESP(newEsathi);
		}
		else
		{
			var memberships=$.cookie("Memberships");
			var membershipsArray=memberships.split(",");
			for(var l=0;l<membershipsArray.length;l++)
			{
				if(membershipsArray[l].indexOf("main")>=0)
				{
					var mainMembership=membershipsArray[l].substring(4);
					var mainElement="main,";
					var duration=mainMembership.substring(1);
					mainElement+=duration+",";
					mainElement+=cartMainMemPrice+",";
					mainElement+=mainMembership+",";
					mainElement+="T";
					if(duration!='')
						addToCart(mainElement);
				}
				else
				{
					mainId=membershipsArray[l].substring(0,1);
					var valueCartElement="";
					if(mainId)
					{
						if(vaMem[mainId][membershipsArray[l]])
						{
							var name=vaMem[mainId][membershipsArray[l]]["NAME"];
							var count=name.lastIndexOf("-");
							name=name.substring(0,count);
							if(name=="Matri")
								name="Matri Profile";
							valueCartElement+=name+",";
							valueCartElement+=vaMem[mainId][membershipsArray[l]]["DURATION"]+",";
							valueCartElement+=vaMem[mainId][membershipsArray[l]]["PRICE"]+",";
							valueCartElement+=membershipsArray[l]+",";
							if(landingFreebie.indexOf(membershipsArray[l])>=0)
								valueCartElement+="D";
							else
								valueCartElement+="T";
							addToCart(valueCartElement);
							checkVAS(valueCartElement);
						}
					}
				}
			}
		}
	}
	calculate();
}
function maintainState(newId,oldId)
{
	var mem="";
	if($.cookie("Memberships"))
	{
		mem=$.cookie("Memberships");
		if(oldId)
		{
			mem=mem.replace(oldId,newId);
		}
		else
			mem+=","+newId;
	}
	else
		mem+=newId;
	$.cookie("Memberships",mem);
}
function removeMaintainence(removeId)
{
	var mem=$.cookie("Memberships");
	mem=mem.replace(removeId,"");
	$.cookie("Memberships",mem);
}
function handleESP(newEsathi)
{
	var i=0;
	if(cartMainMemDuration)
	{
		for(k=0;k<eSathiSpecials.length;k++)
		{
			$("#"+eSathiSpecials[k]+"check").prop("disabled","true");
			$("#"+eSathiSpecials[k]+"check").prop("checked","checked");
			$("."+eSathiSpecials[k]+"Price").css("visibility","hidden");
			$("#"+eSathiSpecials[k]+"SelOrChange").css("visibility","hidden");
		}
	}
	if(newEsathi)
	{
		var element="main,";
		var duration=subMem.substring(3);
		var price=cartMainMemPrice;
		element+=duration+",";
		element+=price+",";
		element+=subMem+",";
		element+="T";
		maintainState("main"+subMem);
		addToCart(element);
		var cartElement=landingVAS.split(",");
		var mainId;
		for(j=0;j<cartElement.length;j++)
		{
			mainId=cartElement[j].substring(0,1);
			var valueCartElement="";
			if(mainId)
			{
				if(vaMem[mainId][cartElement[j]])
				{
					var name=vaMem[mainId][cartElement[j]]["NAME"];
					var count=name.lastIndexOf("-");
					name=name.substring(0,count);
					if(name=="Matri")
						name="Matri Profile";
					valueCartElement+=name+",";
					valueCartElement+=vaMem[mainId][cartElement[j]]["DURATION"]+",";
					valueCartElement+=vaMem[mainId][cartElement[j]]["PRICE"]+",";
					valueCartElement+=cartElement[j]+",";
					if(landingFreebie.indexOf(cartElement[j])>=0)
						valueCartElement+="D";
					else
						valueCartElement+="T";
					if($.inArray(mainId,eSathiSpecials)>-1&&cartMainMemDuration!='')
					{
						checkVAS(valueCartElement);
					}
					else
					{
						addToCart(valueCartElement);
						maintainState(cartElement[j]);
						checkVAS(valueCartElement);
					}
				}
			}
		}
	}
	else
	{
		var memberships=$.cookie("Memberships");
		var membershipsArray=memberships.split(",");
		var l=0;
		for(l=0;l<membershipsArray.length;l++)
		{
			if(membershipsArray[l].indexOf("main")>=0)
			{
				var mainMembership=membershipsArray[l].substring(4);
				var mainElement="main,";
				var duration=mainMembership.substring(3);
				mainElement+=duration+",";
				mainElement+=cartMainMemPrice+",";
				mainElement+=mainMembership+",";
				mainElement+="T";
				addToCart(mainElement);
			}
			else
			{
				mainId=membershipsArray[l].substring(0,1);
				var valueCartElement="";
				if(mainId)
				{
					if(vaMem[mainId][membershipsArray[l]])
					{
						var name=vaMem[mainId][membershipsArray[l]]["NAME"];
						var count=name.lastIndexOf("-");
						name=name.substring(0,count);
						if(name=="Matri")
							name="Matri Profile";
						valueCartElement+=name+",";
						valueCartElement+=vaMem[mainId][membershipsArray[l]]["DURATION"]+",";
						valueCartElement+=vaMem[mainId][membershipsArray[l]]["PRICE"]+",";
						valueCartElement+=membershipsArray[l]+",";
						if(landingFreebie.indexOf(membershipsArray[l])>=0)
							valueCartElement+="D";
						else
							valueCartElement+="T";
						addToCart(valueCartElement);
						checkVAS(valueCartElement);
					}
				}
			}
		}
	}
	calculate();
}
function addToCart(cartElement)
{
	var label="";
	var selectedDuration="";
	var importance="";
	var elementArray=cartElement.split(',');
	id=elementArray[3].substring(0,1);
	value=elementArray[0];
	importance=elementArray[4];
	var exactValue=elementArray[3];
	var removeLabel="<a class='removeFromCart' style='cursor:pointer'>[x]</a>";
	selectedPrice=elementArray[2];
	if(elementArray[1]=="L")
	{
		durationLabel="Unlimited "+removeLabel;
		label="";
	}
	else if(elementArray[1]=="12")
	{
		durationLabel="12 Months "+removeLabel;
		label="";
	}
	else if(elementArray[1]=="1")
		durationLabel=elementArray[1]+" Month"+removeLabel;
	else if(id=="M")
	{
		durationLabel="One time service "+removeLabel;
		selectedPrice=matriPrice;
	}
	else if(id=="I")
		durationLabel=elementArray[1]+" Profiles "+removeLabel;
	else
	{
		if(fest && value=="main")
		{
			elementArray[1]=parseInt(elementArray[1]);
      if(festDurBanner[id]){
			 var freeFestLabel=festDurBanner[id][elementArray[1].toString()];
      }
			if(freeFestLabel)
			{
				durationLabel=elementArray[1]+" Months "+removeLabel+"<br/><span style='color:#E15404'>"+ freeFestLabel+"</span>";
			}
			else
				durationLabel=elementArray[1]+" Months "+removeLabel;
		}
		else
			durationLabel=elementArray[1]+" Months "+removeLabel;
	}
	if(id=="I")
		durationLabel=elementArray[1]+" Profiles "+removeLabel;
	selectedDuration=durationLabel;
	if(value=="main")
	{
		if(cartMainMemPrice!='')
			var toBeAdded="<div class='finalCart' style='border-top:none'>   <div class='div-left'>  <div id='"+mainMem+"icon' class='mem-"+cartMainMemIcon+" sprte-mem ' style='margin:0;'></div> <div id='mainCartDuration'>"+durationLabel+" </div> </div>  <div id='z"+mainMem+"mainCartPrice' style='width:52px;text-align:right;' exactValue='"+subMem+"' importance='T' class='valueCart fl fs20'>"+cartMainMemPrice+"</div><div style='clear:both'></div> </div>";
		//For Tracking
		var navigationString=$("[name=navigationString]").val();
		if(navigationString=="")
			$("[name=navigationString]").val(subMem);
		else
			$("[name=navigationString]").val(navigationString+","+subMem);
	}
	else
	{
		var toBeAdded=" <div class='finalCart'><div id='"+id+"checkCart' class='div-left'><div id='nameCartElement'>"+ value+"</div><div id='"+id+"checkCartDuration'>"+selectedDuration+"</div>  </div><div id='"+id+"checkCartPrice' style='width:52px;text-align:right;' exactValue='"+exactValue+"' importance='"+importance+"' class='valueCart fl fs20'>"+selectedPrice+"</div><div style='clear:both'></div></div>";
		selectedAddon[id]="1";
		//For Tracking
		var navigationString=$("[name=navigationString]").val();
		if(navigationString=="")
			$("[name=navigationString]").val(exactValue);
		else
			$("[name=navigationString]").val(navigationString+","+exactValue);
	}
	$("#cartElements").append(toBeAdded);
}
function checkVAS(vasElement)
{
	var elementArray=vasElement.split(',');
	id=elementArray[3];
	var properId=id;
	value=elementArray[0];
	if(id.substring(0,1)=="I")
		label=" Profiles";
	else if(elementArray[1]==1)
		label=" Month";
	else
		label=" Months";
	selectedDuration=elementArray[1]+label;
	selectedPrice=elementArray[2];
	id=id.substring(0,1);
	if(id)
	{
		$("#"+id).prop("checked","checked");
		$("#"+id+"check").prop("checked","checked");
		$("#"+id+"check").attr("selectedId",properId);
		if(id=="M")
			$("."+id+"Price").html("<strong> For "+currencyLabel+matriPrice+"</strong>");
		else
			$("."+id+"Price").html("<strong>"+currencyLabel+ selectedPrice+" for "+selectedDuration+"</strong>");
		$("#"+id+"SelOrChange").html("Change");
	}
}
function showmore()
{
	var ele = document.getElementById("spanmore");
	if(ele.style.display == "none") {
		ele.style.display = "block";
		document.getElementById("showlink").innerHTML='Show less';
	}
	else {
		ele.style.display = "none";
		document.getElementById("showlink").innerHTML='Show more';
	}
	$("[name=showAll]").val(1);
}
function calculate()
{
	var vasTotal=0,freebieDiscount=0,discount=0,importance="",unLimitedOrNot="",mainOrNot="",mainPrice=0,discountPercent=0,totalDiscount=0,finalTotal=0;
	var unlimitedVal=0;
  var twelveMonthsVal=0;
  $(".valueCart").each(function()
	{
		importance=$(this).attr("importance");
		mainOrNot=$(this).attr("id").substring(0,1);
		if(importance=="T")
		{
			if(mainOrNot=="z")
			{
				mainPrice=parseFloat($(this).html());
				unLimitedOrNot=$("#mainCartDuration").html();
				if(unLimitedOrNot.indexOf("Unlimited")>=0)
					unlimitedVal=1;
				else
					unlimitedVal=0;
				if(unLimitedOrNot.indexOf("12 Months")>=0)
					twelveMonthsVal=1;
				else
					twelveMonthsVal=0;
			}
			else
				vasTotal+=parseFloat($(this).html());
		}
		else
			freebieDiscount+=parseFloat($(this).html());

	});
	if(discountType=="RENEWAL")
	{
		discountPercent=allDiscounts['RENEWAL'];
	}
	else if(specialActive)
	{
    if(allDiscounts['SPECIAL']){
      discountPercent=parseInt(allDiscounts['SPECIAL']);
    } else {
      discountPercent=0;
    }
	}
	else if(discountActive)
	{
		discountPercent=parseInt(allDiscounts['OFFER']);
	}
	else if(fest>0)
	{
		if(unlimitedVal)
			discountPercent=allDiscounts['FESTIVE']['PL'];
		if(twelveMonthsVal)
			discountPercent=allDiscounts['FESTIVE']['P12'];
	}
	if(mainPrice>0){
		if(discountActive)
			discount=((mainPrice)*discountPercent)/100;
		else
			discount=((mainPrice+vasTotal)*discountPercent)/100;
	}
	finalTotal=mainPrice+vasTotal-discount;
	totalDiscount=freebieDiscount+discount;
	totalDiscount=parseFloat(totalDiscount);
  totalDiscount = totalDiscount.toFixed(2);
  finalTotal = finalTotal.toFixed(2);
	if(totalDiscount<=0)
	{
		$("#cartDiscount").hide();
	}
	else
		$("#cartDiscount").show();
	finalTotal=parseFloat(finalTotal);
	if(finalTotal==0)
		$("#noService").show();
	else
		$("#noService").hide();
	$("#discountValue").html("- "+totalDiscount);
	if(currencyLabel=="$")
		$("#totalCartValue").html(currencyLabel+finalTotal);
	else
		$("#totalCartValue").html("<span style='font-family:WebRupee;'>"+currencyLabel+"</span>"+finalTotal);
}
