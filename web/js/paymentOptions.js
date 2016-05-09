$(document).ready(function(){
	$("#textExample").verticaltabs({speed: 0,slideShow: false,activeIndex: 0});
	$("#imageExample").verticaltabs({speed: 0,slideShow: true,slideShowSpeed: 3000,activeIndex: 0,playPausePos: "topRight"});
});
$(document).ready(function(){
	$("#textExample").verticaltabs({speed: 0,slideShow: false,activeIndex: 0});
	$("#imageExample").verticaltabs({speed: 0,slideShow: true,slideShowSpeed: 3000,activeIndex: 0,playPausePos: "topRight"});
});

function testForMainMembership(mainMembership){
  var userType = window.user.userType.toString();
	var url = '/membership/membershipMaster';
  var content = "<div class='center f22' style='padding: 5px; background-color:#fb6464;color:#fff;'>Alert</div>"+
                "<div class='f16' style='padding:20px'>To proceed you need to purchase a Main Membership Plan along with the Additional Services you have selected</div><hr>"+
                "<a class='center f15' href='/membership/membershipMaster' style='background-color:#00b200; color:#fff; padding: 10px; float: right; margin: 7px; width: 100px;'>OK</a>";
  if(userType == "5" || userType == "6"){
		return true;
	} else {
		if(mainMembership){
			var actualMemID = mainMembership.replace(/\d/g,''); 
      actualMemID = actualMemID.replace(/L+/g,''); 
			if(actualMemID == "P" || actualMemID == "C" || actualMemID == "ESP" || actualMemID == "X" || actualMemID == "NCP"){
				return true;
			} else {
				$.colorbox({html: content, href:url, overlayClose:false, escKey:false});
        //alert("To proceed you need to purchase a main Membership Plan along with the Additional Services you have selected.");
			}
		} else {
      $.colorbox({html: content, href:url, overlayClose:false, escKey:false});
			//alert("To proceed you need to purchase a main Membership Plan along with the Additional Services you have selected.");
		}
    //window.location.href = '/membership/membershipMaster';
	}
}

$(document).ready(function()
{
	if(curDate<10)
		curDate=curDate.substring(1);
	if(curMon<10)
		curMon=curMon.substring(1);

	if(user.currency=="DOL")
		currencyLabel="USD ";
	else
		currencyLabel="Rs. ";
	var netBank=0;
	$(".creditVisa").prop("checked","checked");
	$(".creditLink").click(function()
	{
		$(".creditVisa").prop("checked","checked");
		netBank=0;
	});
	$(".debitLink").click(function()
	{
		$(".debitVisa").prop("checked","checked");
		netBank=0;
	});
	$(".paypalLink").click(function()
	{
		$(".payPalOption").prop("checked","checked");
		netBank=0;
	});
	$(".cashCardLink").click(function()
	{
		$(".itzCashCard").prop("checked","checked");
		netBank=0;
	});

	$(".carryForms").bind("contextmenu",function()
	{
		return false;
	});
	getLandingScenario();
	$(".carryForms").click(function()
	{
		if(fromBackend>0)
		{
			//do nothing
			//disable links
		}
		else
		{
			var i=0,j=0,allMemberships="";
			$.cookie("subMem",subMem+user.profileid);
			$(".finalCart").each(function()
			{
				var price=$(this).find(".valueCart").html();
				var id=$(this).find(".valueCart").attr("id");
				var imp=$(this).find(".valueCart").attr("importance");
				if(id.substring(0,1)=="z")
					allMemberships+="main"+$(this).find(".valueCart").attr("exactValue")+",";
				else
					allMemberships+=$(this).find(".valueCart").attr("exactValue")+",";
			});
			$.cookie("Memberships",allMemberships);
			if($(this).attr("goTo")=="chooseMem")
			{
				$("#allMembershipsToMain").val(allMemberships);
				$("form#backToMembership").submit();
			}
			else if($(this).attr("goTo")=="chooseValue")
			{
				var navigationString=$("[name=navigationString]").val();
				var track_total_vas=$("[name=track_total]").val();
				var track_discount_vas=$("[name=track_discount]").val();
				$("[name=track_discount_vas]").val(track_discount_vas);
				$("[name=track_total_vas]").val(track_total_vas);
				$("#navigationStringToVas").val(navigationString);
				$("[name=allMembershipsToVAS]").val(allMemberships);
				$("form#backToValueAddedForm").submit();
			}
		}
	});
	// tracking for Payment Tab 3 Hits (for failed payments)
	// Main membership/VAS check is removed from tracking
	//if(mainMem){
		var track_discount      =$("[name=track_discount]").val();
		var track_total         =$("[name=track_total]").val();
		var track_memberships   =$("[name=track_memberships]").val();
		var trackType           ='F';
		data1 ={"track_total":track_total,"track_discount":track_discount,"track_memberships":track_memberships,"ajax_error":2,"Submit":1,"trackType":trackType};
		url =SITE_URL+"/membership/PaymentOptionsTracking";
		$.ajax({
			type: 'POST',
			url: url,
			data: data1,
			success:function(data){
				response = data;
			}
		}
		);
	//}
	// tracking ends
	//
	$(".cashForm").click(function()
	{
		$("#makePaymentButton").css("display","none");
		if($("#depositType").val()!="courier" && $("#depositType").val()!='')
		{
			$("#chequeForm #cashSubmit").css("display","inline-block");
		}else{
			$("#chequeForm #cashSubmit").css("display","none");
		}
		if($("#totalCartValue").clone().children().remove().end().text() == "0"){
			$("#chequeForm #cashSubmit").addClass("mem-btn-grey-payment");
		}
		$(".secure_tran").css("display","none");
		$("#chequeForm #cashSubmit").val("Submit");
		$("#chequeForm #cashSubmit").css("width","130px");
		var trackId=$(this).attr("trackid");
		var navigationString=$("[name=navigationString]").val();
		if(navigationString=="")
			$("[name=navigationString]").val(trackId);
		else
			$("[name=navigationString]").val(navigationString+","+trackId);
	});
	$(".requestCashForm").click(function()
	{
		if($("#requestSubmit").val()=='')
		{
			$("#textExample #cashSubmit").css("display","inline-block");
			$(".secure_tran").css("display","none");
		}
		else
		{
			$("#textExample #cashSubmit").css("display","none");
			$(".secure_tran").css("display","none");
		}
		$("#makePaymentButton").css("display","none");
		$("#textExample #cashSubmit").val("Submit Request");
		if($("#totalCartValue").clone().children().remove().end().text() == 0){
			$("#textExample #cashSubmit").css("cursor","default");
			$("#textExample #cashSubmit").prop('disabled', 'disabled');
			$("#textExample #cashSubmit").removeClass('cont-btn-green');
			$("#textExample #cashSubmit").addClass('mem-btn-grey-payment');
		}
		$("#textExample #cashSubmit").css("width","190px");
		var trackId=$(this).attr("trackid");
		var navigationString=$("[name=navigationString]").val();
		if(navigationString=="")
			$("[name=navigationString]").val(trackId);
		else
			$("[name=navigationString]").val(navigationString+","+trackId);
	});

$(".pay_at_branches").click(function()
{
	$("#makePaymentButton").css("display","none");
	$("#cashSubmit").css("display","none");
	$(".secure_tran").css("display","none");
	var trackId=$(this).attr("trackid");
	var navigationString=$("[name=navigationString]").val();
	if(navigationString=="")
		$("[name=navigationString]").val(trackId);
	else
		$("[name=navigationString]").val(navigationString+","+trackId);
});
$(".nonForm").click(function()
{
	$("#makePaymentButton").css("display","inline-block");
	$("#cashSubmit").css("display","none");
	$(".secure_tran").css("display","block");
	var trackId=$(this).attr("trackid");
	var navigationString=$("[name=navigationString]").val();
	if(navigationString=="")
		$("[name=navigationString]").val(trackId);
	else
		$("[name=navigationString]").val(navigationString+","+trackId);

});
$(".netBankMain").click(function()
{
	netBank=1;
})
$(document).on("click",".seeCheque",function()
{
	var total_price=$("#totalCartValue").html().replace ( /[^\d.]/g, '' );
	if(total_price.substring(0,1)==".")
		total_price=total_price.substring(1);
	var amt_in_words=toWords(total_price);
	$("#amt_words").html(amt_in_words);
	$("#tot_price").html(total_price);

	var activePos=$(".mem-tab3-leftcon").offset();
	var seeChequePos=$(this).offset();
	if($(this).attr("class").indexOf("dropSampleCheque")>=0)
		$(".sample-indicator").css("top","139px");
	else
		$(".sample-indicator").css("top","126px");
	var left=seeChequePos.left-activePos.left+$(this).width()+$(".sample-indicator").width();
	var top=seeChequePos.top-activePos.top-$(".sampleCheque").height()/2+$(".sample-indicator").height()/2;

	$(".sampleCheque").css("position","absolute");
	$(".sampleCheque").css("left",left);
	$(".sampleCheque").css("top",top);
	$(".sampleCheque").css("visibility","visible");

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
$("#form3").on("change","#xa",function()
{
	if($(this).val()!='-1')
	{
		$("#xb").attr("disabled","disabled");
		$("#xb_error").html('');
	}
	else
		$("#xb").removeAttr("disabled");
});

$(".btn-close").click(function()
{
	$(this).parent().parent().css("visibility","hidden");
	$('#overlay').remove();
});

$(document).on("click","#overlay",function()
{
	$(".sampleCheque").css("visibility","hidden");
	$(".postLightbox").css("visibility","hidden");
	$('#overlay').remove();
});
$(document).keypress(function(e)
{
	if($('#overlay'))
	{
		if(e.keyCode==27)
		{
			$(".sampleCheque").css("visibility","hidden");
			$(".postLightbox").css("visibility","hidden");
			$('#overlay').remove();
		}
	}
});

$("#makePaymentButton").click(function()
{
	var allFinalMemberships="";
	var mainMemberships="";
	var mainMembership="";
	$("#card_option").val('');
	$("#net_banking_cards").val('');
  var finalCartLength = $(".finalCart").length;
	$(".finalCart").each(function(index)
	{
		var price=$(this).find(".valueCart").html();
		var id=$(this).find(".valueCart").attr("id");
		var imp=$(this).find(".valueCart").attr("importance");
		if(id.substring(0,1)=="z")
			mainMembership=$(this).find(".valueCart").attr("exactValue");
		if(index === finalCartLength - 1){
      allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+"";
    } else {
      allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+",";
    }
	});

	// Code added to prevent users from selecting only ADDON in cart
	if(!testForMainMembership(mainMembership)){
		return false;
	}

	var CURRENCY=user.currency;
	var redirectTo;
	var selectedOption=$('input[name=paymode]:checked').attr("id");
	var selectedCard=$('input[name=paymode]:checked').attr("selectedCard");
	var selVal=$('input[name=paymode]:checked').val();
	$("#paymode").val(selVal);
	if(selectedOption=='r6' && netBank!=1)
		redirectTo="/profile/pg/transecute/chequedrop.php";
	else if(selectedOption=='r5' && netBank!=1)
		redirectTo="revamp_easy_bill.php";
	else if(selectedOption=='r7' && netBank!=1)
		redirectTo="/profile/pg/order_paypal.php";
	else if(selectedOption=='r1' && netBank!=1)
	{
		redirectTo="/profile/pg/order_payseal.php";
	}
	else if(selectedOption=='r2' && netBank!=1)
	{
		redirectTo="/profile/pg/orderonline.php";
	}
	else if(selectedOption=='r3' && netBank!=1)
		redirectTo="/profile/pg/orderonline.php";
	else if(selectedOption=='r4' && netBank!=1)
	{
		$("#card_option").val("CCRD");
		$("#CCRDType").val(selectedCard);
		redirectTo="/profile/pg/orderonline.php";

	}
  else if(selectedOption=='paytm' && netBank!=1)
  {
    redirectTo="/profile/pg/order_paytm.php";
  }
	else if(selectedOption=='r8' && netBank!=1)
		redirectTo="/profile/pg/orderonline.php";
	else if(selectedOption=='r10' && netBank!=1)
		redirectTo="/profile/pg/order_payseal.php";
	else
		redirectTo="/profile/pg/orderonline.php";
	$("#makePaymentForm").attr("action",redirectTo);
	$("#service_main").attr("value",allFinalMemberships);
	if(backendId>0)
		$("[name=discSel]").val(backendCheckSum);
	if(mainMembership!="")
		$("#service").attr("value",mainMembership);
	$("#type").attr("value",CURRENCY);
	var navigationString=$("[name=navigationString]").val();
	var track_discount=parseFloat($("[name=track_discount]").val());
	var track_total=parseFloat($("[name=track_total]").val());
	var track_memberships=$("[name=track_memberships]").val();
	$.post("/membership/PaymentOptionsTracking",{ 'navigationString' : navigationString,'track_discount':track_discount,'track_total':track_total,'track_memberships':track_memberships},function(response){
	});
	setTimeout(function(){$("#makePaymentForm").submit();},200);
	netBank=0;
});
$(".membershipMaster").click(function()
{
	$("#backToMembership").submit();
});
$(".netBank").click(function()
{
	var allFinalMemberships="";
	var mainMemberships="";
	var mainMembership="";
	var redirectTo="";
  var finalCartLength = $(".finalCart").length;
	$(".finalCart").each(function(index)
	{
		var price=$(this).find(".valueCart").html();
		var id=$(this).find(".valueCart").attr("id");
		var imp=$(this).find(".valueCart").attr("importance");
		if(id.substring(0,1)=="z")
			mainMembership=$(this).find(".valueCart").attr("exactValue");
		if(index === finalCartLength - 1){
      allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+"";
    } else {
      allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+",";
    }
	});

	// Code added to prevent users from selecting only ADDON in cart
	if(!testForMainMembership(mainMembership)){
		return false;
	}

	var total_price=$("#totalCartValue").html().replace ( /[^\d.]/g, '' );
	if(total_price.substring(0,1)==".")
		total_price=total_price.substring(1);

	if(parseFloat(total_price)<1)
		return false;
	var CURRENCY=user.currency;
	$("#card_option").val('netBanking');
	$("#net_banking_cards").val($(this).attr("value"));
	redirectTo="/profile/pg/orderonline.php";
	$("#makePaymentForm").attr("action",redirectTo);
	$("#service_main").attr("value",allFinalMemberships);
	if(mainMembership!="")
		$("#service").attr("value",mainMembership);
	$("#type").attr("value",CURRENCY);
	if(backendId>0)
		$("[name=discSel]").val(backendCheckSum);

	$("#makePaymentForm").submit();

});
$("#city").change(function(){
	var city_value=$(this).val();
	var html="";
	$.post("/membership/CityBranches",{ 'city_value' : city_value},function(response){
		var data=$.parseJSON(response);
		$("#nearByBranches").html('');
		$.each(data,function(key,value){
			html +="<span><h3>"+value.NAME+"</h3></span><div class='fl'><div class='tit-label'>CONTACT</div><div class='fl' id='contact'>"+value.CONTACT_PERSON+"</div></div><div class='fl'><div class='tit-label'>ADDRESS</div><div class='fl w250' id='address'>"+value.ADDRESS+"</div></div><div class='fl fullwidth'><div class='tit-label'>PHONE</div><div class='fl' id='phone'>"+value.PHONE+"</div></div><div class='fl'><div class='tit-label'>MOBILE</div><div class='fl'>"+value.MOBILE+"</div></div><br/>";
		});
		$("#nearByBranches").append(html);
	});
});

$("input[type=radio][name=deposit]").click(function()
{
	var showDiv=$(this).attr("id");
	var html="";
	var CURRENCY=user.currency;
	var priceDiv='';
	var total_price=$("#totalCartValue").html().replace ( /[^\d.]/g, '' );
	if(total_price.substring(0,1)==".")
		total_price=total_price.substring(1);
	if(currencyLabel=='USD ')
		priceDiv="<span>"+currencyLabel+"</span>"+total_price;
	else
		priceDiv="<span style='font-family:WebRupee'>Rs. </span>"+total_price;

	if(showDiv == "transfer")
	{

		$("#staticDiv").css("display","none");
		$("#chequeForm").html('');
		$("#depositType").val("transfer");
		html="<div class='fl'><pre>";
		html="<div>";
		html+="<div style='float:left;width:80px'>A/c Name  </div><div style='float:left'>: Jeevansathi Internet Services <br></div><div style='clear:both'></div>";
		html+="<div style='float:left;width:80px;'>A/c No.   </div><div style='float:left'>: 003705010255 </div><div style='clear:both'></div>";
		html+="<div style='float:left;width:80px'>Bank      </div><div style='float:left'>: ICICI Bank </div><div style='clear:both'></div>";
		html+="<div style='float:left;width:80px;'>Branch    </div><div style='float:left'>: Preet Vihar, New Delhi - 110096 </div><div style='clear:both'></div>";
		html+="<div style='float:left;width:80px'>IFSC Code </div><div style='float:left'>: ICIC0000037</div><div style='clear:both'></div></div><div class='sp10'></div><div class='b'>After transferring the amount, please enter the details below.</div> <div id='mem-tab3-form'><div><label>Transaction No. </label> : <input type='text' id='z' name='cdnum' class='required'/><br/><font class='err mar95left' id='z_error'></font></div><div><label>Date</label>:";
		html+=" <select class='w40 chequeDate' id='ya' name='cd_day'>";
		for(var i=0;i<days.length;i++)
		{
			var selected='';
			if(days[i]==curDate)
				selected="selected";
			html+="<option "+selected+" value="+days[i]+">"+days[i]+"</option>";
		}
		html+="</select> <select class='w40 chequeDate' style='margin:0px 8px' id='yb' name='cd_month'>";
		for(var i=0;i<months.length;i++)
		{
			var selected='';
			if(months[i]==curMon)
				selected="selected";
			html+="<option "+selected+" value="+months[i]+">"+months[i]+"</option>";
		}
		html+="</select><select class='w55 chequeDate' id='yc' name='cd_year'>";
		for(var i=0;i<year.length;i++)
		{
			var selected='';
			if(year[i]==curYear)
				selected="selected";
			html+="<option "+selected+" value="+year[i]+">"+year[i]+"</option>";
		}
		html+="</select><br><font id='date_error' class='err mar70left'></font></div><div><label>Bank Name   </label> : <select name='Bank' id='xa'>";
		for(var i=0;i<banks.length;i++)
		{
			html+="<option>"+banks[i]+"</option>";
		}
		html+="<option value='-1'>Other</option>";

		html+="</select></div><div><label>If Other  </label> : <input type='text' id='xb' class='required' name='OBANK' disabled='disabled'/><br/><font class='err mar95left' id='xb_error'></font></div><div><label>City </label> : <input type='text' id='w' class='required' name='cd_city'/><br/><font id='w_error' class='err mar95left'></font></div><div><label>Mobile number </label> : <span><input type='text' class='required' name='MOB_NO' id='v' value='"+phone_mob+"'/><br /><font class='err mar95left' id='v_error'><br /></font></span></div><div><label>Amount  </label> :  <strong>"+priceDiv+"</strong></div> <div><label>Comments  </label> :<span> <textarea rows='5' cols='10' name='COMMENTS'></textarea></span></div> <div class='center mt_10 fullwidth'><input type='button' style='cursor: pointer;width: 130px; height: 50px;display:inline-block' class='cont-btn-green' value='Submit' name='cashSubmit' id='cashSubmit'></div> </div></div>";
			//$("#chequeForm").html(html);
		}
		else if(showDiv == "drop")
		{

			$("#staticDiv").css("display","none");
			$("#chequeForm").html('');
			$("#depositType").val("drop");
			html="<div class='fl'><div><div style='width:80px;float:left'>A/c Name  </div><div style='float:left'>: Jeevansathi Internet Services </div><div style='clear:both'></div><div style='width:80px;float:left'>A/c No.   </div><div style='float:left'>: 003705010255 </div><div style='clear:both'></div></div>";
			html+="<a id='seeCheque' class='seeCheque b fs12 dropSampleCheque' style='cursor:pointer'><br /><u>See Sample cheque</u></a></pre><div class='sp10'></div><div id='mem-tab3-form'><div><label>Cheque No. </label> : <input type='text' class='required' name='cdnum' id='z'/><br/><font class='err mar95left' id='z_error'></font></div><div><label>Date</label> : ";
			html+=" <select class='w40 chequeDate' id='dropDay' name='cd_day'>";
			for(var i=0;i<days.length;i++)
			{
				var selected='';
				if(days[i]==curDate)
					selected="selected";
				html+="<option "+selected+" value="+days[i]+">"+days[i]+"</option>";
			}
			html+="</select> <select class='w40 chequeDate' id='dropMonth' style='margin:0px 8px' name='cd_month'>";
			for(var i=0;i<months.length;i++)
			{
				var selected="";
				if(months[i]==curMon)
					selected="selected";
				html+="<option "+selected+" value="+months[i]+">"+months[i]+"</option>";
			}
			html+="</select><select class='w55 chequeDate' id='dropYear' name='cd_year'>";
			for(var i=0;i<year.length;i++)
			{
				var selected="";
				if(year[i]==curYear)
					selected="selected";
				html+="<option "+selected+" value="+year[i]+">"+year[i]+"</option>";
			}
			html+="</select><br><font id='date_error' class='err mar50left'></font></div> <div><label>Bank Name   </label> : <select name='Bank' id='xa'>";
			for(var i=0;i<banks.length;i++)
			{
				html+="<option>"+banks[i]+"</option>";
			}
			html+="<option value='-1'>Other</option>";

			html+="</select><div><label>If Other  </label> : <input type='text' name='OBANK' id='xb' class='required' disabled='disabled'/><br/><font id='xb_error' class='err mar95left'></font></div><div><label>Cheque City    </label> : <input type='text' class='required' name='cd_city' id='w'/><br/><font id='w_error' class='err mar95left'></font></div><div><label>Mobile number </label> : <span><input type='text' class='required' id='v' name='MOB_NO' value='"+phone_mob+"'/><br /><font class='err mar95left' id='v_error'></font></span></div><div><label>Amount  </label> :  <strong>"+priceDiv+"</strong></div><div><label>Comments  </label> :<span> <textarea rows='5' cols='10' name='COMMENTS'></textarea><br /></span></div> <div class='center mt_10' style='width:100%;'><input type='button' id='cashSubmit' name='cashSubmit' value='Submit' class='cont-btn-green' style='cursor:pointer;width:130px;height:50px;display:inline-block;'/></div></div> </div></div>";
			//$("#chequeForm").html(html);

		}
		else if(showDiv=="courier")
		{
			var allFinalMemberships="";
			var mainMembership="";
			$("#depositType").val("courier");
      var finalCartLength = $(".finalCart").length;
			$(".finalCart").each(function(index)
			{
				var price=$(this).find(".valueCart").html();
				var id=$(this).find(".valueCart").attr("id");
				var imp=$(this).find(".valueCart").attr("importance");
				if(id.substring(0,1)=="z")
					mainMembership=$(this).find(".valueCart").attr("exactValue");
				if(index === finalCartLength - 1){
          allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+"";
        } else {
          allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+",";
        }
			});

			$.post("/profile/pg/transecute/chequedrop.php",{submitType:"courier",checksum:checksum,service_main:allFinalMemberships},function(response)
			{
			});
			$("#cashSubmit").css("display","none");
			$("#chequeForm").html('');
			$("#staticDiv").css("display","block");
			html="<div>";
			html+="<div>Payable to <strong>&quot;Jeevansathi Internet Services&quot;</strong><br /><a id='seeCheque' class='seeCheque b fs12 ' style='cursor:pointer'><u>See Sample cheque</u></a></div>";
			html+="<div class='sp15'></div>";
			html+="<div>Please mention <strong> "+username+" </strong> or  <strong> "+email+" </strong><br/ >on the back of the cheque.</div>";
			html+="<div class='sp15'></div>";
			html+="<div> Post your Cheque for <strong>FREE</strong> in an envelop addressed to <br /><strong>Post No. 201951</strong> and drop it in any post box.<br />You don't need to paste any stamp. <br />";
			html+="<a class='howToPost b fs12' style='cursor:pointer'><u>How to Post for FREE</u></a></div>";
			html+="<div class='sp15'></div>";
			html+="<div><strong>OR send your cheque to</strong> <br />Jeevansathi Client Relations,<br />B - 8, Sector - 132, <br />Noida - 201301<br />Phone : +91-120-4393500<br /></div></div>";
		}
		$("#chequeForm").html(html);
		 // $("#staticDiv").css("display","block");
		 $("#chequeForm").css("visibility","visible");
		 if(total_price == 0){
		 	$("#chequeForm #cashSubmit").css("cursor","default");
		 	$("#chequeForm #cashSubmit").prop('disabled', 'disabled');
		 	$("#chequeForm #cashSubmit").removeClass('cont-btn-green');
		 	$("#chequeForm #cashSubmit").addClass('mem-btn-grey-payment');
		 }
		});


$("#form3").on("click",".howToPost",function()
{
	$(".postLightbox").css("visibility","visible");
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
$(document).on("click","#cashSubmit",function()
{
	var allFinalMemberships='';
	var mainMembership='';
	var CURRENCY=user.currency;
  var finalCartLength = $(".finalCart").length;
	$(".finalCart").each(function(index)
	{
		var price=$(this).find(".valueCart").html();
		var id=$(this).find(".valueCart").attr("id");
		var imp=$(this).find(".valueCart").attr("importance");
		if(id.substring(0,1)=="z")
			mainMembership=$(this).find(".valueCart").attr("exactValue");
	  if(index === finalCartLength - 1){
      allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+"";
    } else {
      allFinalMemberships+=$(this).find(".valueCart").attr("exactValue")+",";
    }
	});

	// Code added to prevent users from selecting only ADDON in cart
	if(!testForMainMembership(mainMembership)){
		return false;
	}

	if($(".activeTab").attr("id")=="pickup")
	{
		$("#submitType_form1").val('Submit Request');
		$("#service_main_form1").attr("value",allFinalMemberships);
		if(mainMembership!="")
			$("#service_form1").attr("value",mainMembership);
		$("#type_form1").attr("value",CURRENCY);
			//$("#city_form1").val($("#city_res").val());
			$("#ReqService").val(allFinalMemberships);

			if(validate('h','f','g','j','',''))
			{
				if(validateFreePickDate())
				{
					$.post("/profile/pg/transecute/chequedrop.php",$('form#form1').serialize(),function(response)
					{
						$("#requestSubmit").val('Request Submitted');
						$("form#form1").html(response);
						$("#cashSubmit").css("display","none");
					});
				}
			}
		}
		else
		{
			$("#submitType_form3").val('Submit Cheque');
			$("#service_main_form3").attr("value",allFinalMemberships);
			if(mainMembership!="")
				$("#service_form3").attr("value",mainMembership);
			$("#type_form3").attr("value",CURRENCY);

			if(validate('v','z','w','','xa','xb'))
			{
				if($.isNumeric($("#z").val()))
				{
					if(checkDateDuration())
					{
						$("#z_error").html("");
						$.post("/profile/pg/transecute/chequedrop.php",$('form#form3').serialize(),function(response)
						{
							$("#chequeForm").html(response);
							$("#cashSubmit").css("display","none");
						});
					}
					else
					{
						$("#z_error").html("Please enter a valid field value");
						$("#z").val("");
					}
				}

			}
		}
	});
function validate(f1,f2,f3,f4,f5,f6)
{
	var flag=true;
	var val=$("#"+f1).val();
	flag=validateMob(val,f1);
	if($("#"+f2).val()=="")
	{
		$("#"+f2+"_error").html('Please fill the field<br/>');
		flag=false;
	}
	else
		$("#"+f2+"_error").html('');
	if(f5!='')
	{
		if($("#"+f5).val()=="-1")
		{
			if($("#"+f6).val()=="")
			{
				$('#'+f6+'_error').html('Please select a Bank or fill the Other bank field<br/>');
				flag=false;
			}
		}
		else
			$("#"+f6+"_error").html('');
	}
	if($("#"+f3).val()=="")
	{
		$("#"+f3+"_error").html('Please fill the field<br/>');
		flag=false;
	}
	else
		$("#"+f3+"_error").html('');
	if(f4!='')
	{
		if($("#"+f4).val()=="")
		{
			$("#"+f4+"_error").html('Please fill the field<br/>');
			flag=false;
		}
		else
			$("#"+f4+"_error").html('');
	}

	return flag;

}
$("#form3").on("change",".required",function()
{
	if($(this).val()=="")
		$("#"+$(this).attr("id")+"_error").html('Please fill the above field');
	else if($(this).attr("id")=="v" || $(this).attr("id")=="h")
		validateMob($(this).val(),$(this).attr("id"));
	else if($(this).attr("id")=="z")
	{
		if(!$.isNumeric($(this).val()))
		{
			$("#z_error").html("Please enter a valid field value");
			$("#z").html("");
		}
		else
			$("#z_error").html("");
	}

	else
		$("#"+$(this).attr("id")+"_error").html('');
});
$(".required").on("change",function()
{
	if($(this).val()=="")
		$("#"+$(this).attr("id")+"_error").html('Please fill the above field');
	else if($(this).attr("id")=="h")
		validateMob($(this).val(),$(this).attr("id"));
	else if($(this).attr("id")=="g")
		validatePhone($(this).val(),$(this).attr("id"));
	else
		$("#"+$(this).attr("id")+"_error").html('');
});
$(".afterTom").on("change",function()
{
	validateFreePickDate();
});
$("#form3").on("change",".chequeDate",function()
{
	checkDateDuration();
});
function checkDateDuration()
{
	var flag=true;
	if($("#depositType").val()=="transfer")
	{
		var toTimeStamp=new Date().getTime();
		var fromTimeStamp=new Date().getTime() - (30* 24 * 60 * 60 * 1000);
		var selectedTimeStamp=new Date(parseFloat($("#yc").val()),parseFloat($("#yb").val())-1,parseFloat($("#ya").val())).getTime();
		if(selectedTimeStamp<fromTimeStamp)
		{
			$("#date_error").html("Transaction Date cannot be older than 1 month");
			flag=false;
		}
		else if(selectedTimeStamp>toTimeStamp)
		{
			$("#date_error").html("Transaction Date cannot be greater than today's date");
			flag=false;
		}
		else
			$("#date_error").html('');

	}
	else if($("#depositType").val()=="drop")
	{
		var toTimeStamp=new Date().getTime() + (30 * 24 * 60 * 60 * 1000);
		var fromTimeStamp=new Date().getTime() - (3*30* 24 * 60 * 60 * 1000);
		var selectedTimeStamp=new Date(parseFloat($("#dropYear").val()),parseFloat($("#dropMonth").val())-1,parseFloat($("#dropDay").val())).getTime();
		if(selectedTimeStamp<fromTimeStamp)
		{
			$("#date_error").html("Cheque/DD date should not be older than 3 months");
			flag=false;
		}
		else if(selectedTimeStamp>toTimeStamp)
		{
			$("#date_error").html("Post dated cheque should not be dated beyond 1 month");
			flag=false;
		}
		else
			$("#date_error").html('');
	}
	return flag;
}

function validateFreePickDate()
{
	var dayVal=parseFloat($("#k").val());
	var monVal=parseFloat($("#l").val());
	var yearVal=parseFloat($("#m").val());
	var curDayPlusTwo=parseFloat(curDate)+2;
	var curMonth=parseFloat(curMon);
	var currntYear=parseFloat(curYear);
	var flag=true;
	if( dayVal < curDayPlusTwo && monVal < curMonth && yearVal < currntYear)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else if(dayVal < curDayPlusTwo && monVal < curMonth)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else if(dayVal < curDayPlusTwo)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else if(monVal < curMonth)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else if(yearVal < currntYear)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else if(dayVal < curDayPlusTwo && yearVal < currntYear)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else if(monVal < curMonth && yearVal < currntYear)
	{
		$("#pref_date_error").html("Preferred date should be day after tommorrow or after that");
		flag=false;
	}
	else
	{
		$("#pref_date_error").html('');
	}
	return flag;
}


$("#cartElements").on("click","a",function()
{
	var id=$(this).parent().attr("id");
	var mainMemId=id.substring(0,1);
	var mainOrNot=id.substring(0,4);
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
		}
		else
		{
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
		$("#mainSubMemId").val("");
	}
	else
	{
		var checkBoxId=id.substring(0,6);
		$("#"+checkBoxId).prop("checked",false);
		$("."+mainMemId+"Price").html("<strong>Starts @ Rs. "+selectedPrice+"</strong>");
		$("#"+mainMemId+"SelOrChange").html("Select Plan");
	}
	$(this).parent().parent().parent().remove();
	calculate();
	reInitCoupon();
});

});
function getLandingScenario()
{
	var i=0;
	if(mainMem!="")
	{
		if(mainMem=="ESP" || mainMem=="NCP")
			handleESP();
		else if(mainMem=="X")
		{
			var element="main,";
			var duration=subMem.substring(1);
			var price=exclusiveInfo[subMem];
			element+=duration+",";
			element+=price+",";
			element+=subMem+",";
			element+="T";
			addToCart(element);
		}
		else
		{
			var element="main,";
			var duration=subMem.substring(1);
			var price=cartMainMemPrice;
			element+=duration+",";
			element+=price+",";
			element+=subMem+",";
			element+="T";
			addToCart(element);
		}
	}
	if(landingVAS)
	{
		var cartElement=landingVAS.split(",");
		var mainId;
		if(landingVAS.indexOf("main")>=0)
			var k=1;
		else
			var k=0;
		for(j=k;j<cartElement.length;j++)
		{
			mainId=cartElement[j].substring(0,1);
			var valueCartElement="";
			if(cartElement[j].indexOf("X")>=0)
			{
				var element="main,";
				subMem=cartElement[j];
				var duration=subMem.substring(1);
				var price=exclusiveInfo[subMem];
				element+=duration+",";
				element+=price+",";
				element+=subMem+",";
				element+="T";
				addToCart(element);
				mainId="";
			}
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
				}
			}
		}
	}
	calculate();
}
function calculate()
{
	var vasTotal=0,freebieDiscount=0,discount=0,importance="",unLimitedOrNot=0,mainOrNot="",mainPrice=0,discountPercent=0,totalDiscount=0,finalTotal=0,track_memberships="";
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
		if(track_memberships=="")
			track_memberships=$(this).attr("exactValue");
		else
			track_memberships+=","+$(this).attr("exactValue");

	});
	$("[name=track_memberships]").val(track_memberships);
	if(discountType=="RENEWAL")
	{
		discountPercent=allDiscounts['RENEWAL'];
	}
	else if(specialActive)
	{
    if(allDiscounts['SPECIAL']){
      discountPercent=parseFloat(allDiscounts['SPECIAL']);
    } else {
      discountPercent=0;
    }
	}
	else if(discountActive)
	{
		discountPercent=parseFloat(allDiscounts['OFFER']);
	}
	else if(fest>0)
	{
		if(unlimitedVal)
                        discountPercent=allDiscounts['FESTIVE']['PL'];
                if(twelveMonthsVal)
                        discountPercent=allDiscounts['FESTIVE']['P12'];
		if(mainMem=="X")
			discountPercent =0;
	}
  
	if(mainPrice>0){
		if(discountActive)
			discount=((mainPrice)*discountPercent)/100;
		else
			discount=((mainPrice+vasTotal)*discountPercent)/100;
	}
	finalTotal=mainPrice+vasTotal-discount;
	totalDiscount=freebieDiscount+discount;
	if(fromBackend)
	{
		$(".carryForms").css('cursor','default');
		totalDiscount=((mainPrice+vasTotal)*discountBackend)/100;
		finalTotal=mainPrice+vasTotal-totalDiscount;
	}
	if(totalDiscount<=0)
	{
		$("#cartDiscount").hide();
	}
	else
		$("#cartDiscount").show();
	totalDiscount=parseFloat(totalDiscount);
	finalTotal=parseFloat(finalTotal);
  totalDiscount = totalDiscount.toFixed(2);
  finalTotal = finalTotal.toFixed(2);
	if(finalTotal==0)
	{
		$("#noService").show();
		$("#makePaymentButton").prop("disabled","disabled");
		$("#cashSubmit").prop("disabled","disabled");
		$("#makePaymentButton").css("cursor","default");
		$("#cashSubmit").css("cursor","default");
		$("#makePaymentButton").addClass("mem-btn-grey-payment");
		$("#cashSubmit").addClass("mem-btn-grey-payment");
	}
	else
		$("#noService").hide();
	$("#discountValue").html("- "+totalDiscount);
	$("[name=track_discount]").val(totalDiscount);
	$("[name=track_total]").val(finalTotal);
	if(currencyLabel=="USD ")
		$("#totalCartValue").html(currencyLabel+finalTotal);
	else
		$("#totalCartValue").html("<span style='font-family:WebRupee;'>"+currencyLabel+"</span>"+finalTotal);
}
function handleESP(newEsathi)
{
	var i=0;
	var element="main,";
	var duration=subMem.substring(3);
	var price=cartMainMemPrice;
	element+=duration+",";
	element+=price+",";
	element+=subMem+",";
	element+="T";
	addToCart(element);
	if(newEsathi)
	{
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
					var name=vaMem[mainId][cartElement[j]]["NAME"].split("-");
					valueCartElement+=name[0]+",";
					valueCartElement+=vaMem[mainId][cartElement[j]]["DURATION"]+",";
					valueCartElement+=vaMem[mainId][cartElement[j]]["PRICE"]+",";
					valueCartElement+=mainId+",";
					if(landingFreebie.indexOf(cartElement[j])>=0)
						valueCartElement+="D";
					else
						valueCartElement+="T";
					if($.inArray(mainId,eSathiSpecials)>-1)
					{

					}
					else
					{
						addToCart(valueCartElement);
					}
				}
			}
		}
	}
	else
	{
		while($.cookie("element"+i))
		{
			var cartElement=$.cookie("element"+i);
			var elementArray=cartElement.split(',');
			if($.inArray(elementArray[3],eSathiSpecials)>-1)
			{

			}
			else
			{
				if(elementArray[3]!="z")
					addToCart(cartElement);
			}
			i++;
		}
	}
	calculate();
}
function toWords(s) {
	var th = ['', ' Thousand', ' Million', ' Billion', ' Trillion', ' Quadrillion', ' Quintillion'];
	var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
	var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
	var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
	s = s.toString();
	s = s.replace(/[\, ]/g, '');
	if (s != parseFloat(s)) return 'not a number';
	var x = s.indexOf('.');
	if (x == -1) x = s.length;
	if (x > 15) return 'too big';
	var n = s.split('');
	var str = '';
	var sk = 0;
	for (var i = 0; i < x; i++) {
		if ((x - i) % 3 == 2) {
			if (n[i] == '1') {
				str += tn[Number(n[i + 1])] + ' ';
				i++;
				sk = 1;
			} else if (n[i] != 0) {
				str += tw[n[i] - 2] + ' ';
				sk = 1;
			}
		} else if (n[i] != 0) {
			str += dg[n[i]] + ' ';
			if ((x - i) % 3 == 0) str += 'Hundred ';
			sk = 1;
		}
		if ((x - i) % 3 == 1) {
			if (sk) str += th[(x - i - 1) / 3] + ' ';
			sk = 0;
		}
	}
	if (x != s.length) {
		var y = s.length;
		str += 'Point ';
		for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ';
	}
str +='Only ';
if (str == 'Only '){
	str = 'Zero Only';
}
return str.replace(/\s+/g, ' ');
}
function validateMob(val,id)
{
	regEx=/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
	if(!regEx.test( val ))
	{
		$('#'+id+"_error").html('Please enter a valid Mobile Number<br>');
		return false;
	}
	else
	{
		$('#'+id+"_error").html('');
		return true;
	}
}

function validatePhone(val,id)
{
	regEx=/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{6})$/;
	if(!regEx.test( val ))
	{
		$('#'+id+"_error").html('Please enter a valid Phone Number<br>');
		return false;
	}
	else
	{
		$('#'+id+"_error").html('');
		return true;
	}
}


function addToCart(cartElement)
{
	var label="";
	var selectedDuration="";
	var importance="";
	if(fromBackend>0)
		var removeLabel="";
	else
		var removeLabel=" <a class='removeFromCart' onclick='reInitCoupon(); return false;' style='cursor:pointer'>[x]</a>";
	var elementArray=cartElement.split(',');
	id=elementArray[3].substring(0,1);
	value=elementArray[0];
	importance=elementArray[4];
	var exactValue=elementArray[3];
	selectedPrice=elementArray[2];
	if(elementArray[1]=="L")
	{
		durationLabel="Unlimited"+removeLabel;
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
		durationLabel="One time service"+removeLabel;
		selectedPrice=matriPrice;
	}
	else
	{
		if(fest>0 && value=="main" && id!='X')
		{
			elementArray[1]=parseFloat(elementArray[1]);
			var freeFestLabel=festDurBanner[id][elementArray[1].toString()];
			if(freeFestLabel)
			{
				durationLabel=elementArray[1]+" Months"+removeLabel+"<br/><span style='color:#E15404'>"+ freeFestLabel+"</span>";
			}
			else
				durationLabel=elementArray[1]+" Months"+removeLabel;
		}
		else
			durationLabel=elementArray[1]+" Months"+removeLabel;
	}
	if(id=="I")
		durationLabel=elementArray[1]+" Profiles"+removeLabel;
	if(value=="main")
	{
		var toBeAdded="<div class='finalCart' style='border-top:none'>   <div class='div-left'>  <div id='"+mainMem+"icon' class='mem-"+cartMainMemIcon+" sprte-mem ' style='margin:0;'></div> <div id='mainCartDuration'>"+durationLabel+"</div> </div>  <div id='z"+mainMem+"mainCartPrice' style='width:52px;text-align:right;' importance='T' exactValue='"+exactValue+"' class='valueCart fl fs20'>"+selectedPrice+"</div> <div style='clear:both' ></div></div>";
	}
	else
	{
		var toBeAdded=" <div class='finalCart'><div id='"+id+"checkCart' class='div-left'><div id='nameCartElement'>"+ value+"</div><div id='"+id+"checkCartDuration'>"+durationLabel+"</div>  </div><div id='"+id+"checkCartPrice' style='width:52px;text-align:right;' exactValue='"+exactValue+"' importance='"+importance+"' class='valueCart fl fs20'>"+selectedPrice+"</div><div style='clear:both' ></div></div>";
	}
	$("#cartElements").append(toBeAdded);
}

function reInitCoupon(){
	$("#coupFailure").hide();
	$("#coupSuccess").hide();
	var countMain = 0;
	$(".finalCart").each(function(){
		var id = $(this).find(".valueCart").attr("id");
		if(id.substring(0,1)=="z"){
			countMain = 1;
		}
	});
	if(countMain == 0 || fromBackend > 0){
		$("#coupon_code").prop('disabled', true).hide();
		$("input[name=couponCodeVal]").val('');
	} else {
		$("#applyCoupon").addClass('coup-apply-btn-orange');
		$("#applyCoupon").removeClass('mem-btn-grey-payment');
		$("#applyCoupon").prop('disabled', false);
		$("#couponVal").prop('disabled', false);
		$("#couponVal").val('');
		$("#coupText").show();
		$("#coupon_code").prop('disabled', false).show();
		$("input[name=couponCodeVal]").val('');
	}
}

function applyCoupon(){
	if($(".finalCart").length > 0){
		var mainMembership = "";
		var price=$(".finalCart").find(".valueCart").html();
		var id=$(".finalCart").find(".valueCart").attr("id");
		var couponCode = $.trim($("#couponVal").val());
		var discountVal = 0;
		var originalDiscount = 0;
		if(user.currency=="DOL"){
			currencyLabel="USD ";
		} else {
			currencyLabel="Rs. ";
		}
		if(id.substring(0,1)=="z"){
			mainMembership = $(".finalCart").find(".valueCart").attr("exactValue");
		}
		if(mainMembership != "undefined" && mainMembership != ""){
			params ={"serviceid":mainMembership,"couponCode":couponCode};
			url =SITE_URL+"/membership/validateCoupon";
			var discount;
			$.ajax({
				type: 'POST',
				url: url,
				data: params,
				success:function(data){
					response = data;
					var total_price = $("#totalCartValue").html().replace( /[^\d.]/g,'');
					if(total_price.substring(0,1)=="."){
						total_price = total_price.substring(1);
					}
					if(response == "0" || response == 0){
						$("#coupFailure").text("Coupon code entered is not valid").show();
						$("input[name=couponCodeVal]").val('');
					} else if(response == "INVDUR") {
						$("#coupFailure").text("Coupon code is no longer valid").show();
						$("input[name=couponCodeVal]").val('');
					} else if(response == "LIMEXP") {
						$("#coupFailure").text("Coupon code has exceeded the maximum usage").show();
						$("input[name=couponCodeVal]").val('');
					} else if(response > 0 && parseFloat(total_price) > 0){
						if(parseFloat($.trim($("#discountValue").text().replace('- ',''))) != 0){
							originalDiscount = parseFloat($.trim($("#discountValue").text().replace('- ','')));
						}
						discountVal = parseFloat(Math.floor(total_price*(response/100)));
						total_price -= parseFloat(discountVal);
						$("#discountValue").text("- "+(discountVal+originalDiscount));
						$("#cartDiscount").show();
						$("#coupFailure").hide();
						$("#coupSuccess").text("Coupon Successfully Applied").show();
						//$("#coupon_code").prop('disabled', true).hide();
						$("#applyCoupon").removeClass('coup-apply-btn-orange');
						$("#applyCoupon").addClass('mem-btn-grey-payment');
						$("#applyCoupon").prop('disabled', true);
						$("#couponVal").prop('disabled', true);
						$("input[name=couponCodeVal]").val(couponCode);
						if(currencyLabel=="USD "){
							$("#totalCartValue").html(currencyLabel+total_price);
						} else {
							$("#totalCartValue").html("<span style='font-family:WebRupee;'>"+currencyLabel+"</span>"+total_price);
						}
					}
					$("#coupText").hide();
				}
			});
		}
	}
}

$(document).ready(function(e){
	reInitCoupon();
})
