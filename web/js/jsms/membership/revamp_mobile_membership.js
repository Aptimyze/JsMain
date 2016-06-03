// function to handle orientation change
window.onorientationchange = function() {
	var orientation = window.orientation;
	switch(orientation) {
		case 0: window.location.reload();
		break;
		case 90: window.location.reload();
		break;
		case -90: window.location.reload();
		break;
	}
};

function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}

function clearOverlay(){
  $("#callOvrTwo").hide();
  $("#callOvrOne").hide();
  e.preventDefault();
}

//revamp mobile membership page one ticker
function updateTimeSpan(countdown) {
	var theSpan = document.getElementById('timeLeft');
	var d = new Date(countdown);
	var t = new Date();
	var ms;
	var s, m, h;
	// get the difference between right now and expiry date
	ms = d - t;
	// get the days between now and then
	d = parseInt(ms / (1000 * 60 * 60 * 24));
	//ms -= (d * 1000 * 60 * 60 * 24);
	// get hours
	h = parseInt(ms / (1000 * 60 * 60));
	ms -= (h * 1000 * 60 * 60);
	// get minutes
	m = parseInt(ms / (1000 * 60));
	ms -= (m * 1000 * 60);
	// get seconds
	s = parseInt(ms / 1000);
	if(h<=0 && m<=0 && s<=0){
    theSpan.innerHTML = "<div class='dispibl fontreg'><span style='font-size:50px'>00</span><span class='f11'>H</span></div><div class='dispibl padl20'><span style='font-size:50px'>00</span><span class='f11'>M</span></div><div class='dispibl padl20'><span style='font-size:50px'>00</span><span class='f11'>S</span></div>";
	} else {
    h = leftPad(h, 2);
    m = leftPad(m, 2);
    s = leftPad(s, 2);
		theSpan.innerHTML = "<div class='dispibl fontreg'><span style='font-size:50px'>"+h+"</span><span class='f11'>H</span></div><div class='dispibl padl20'><span style='font-size:50px'>"+m+"</span><span class='f11'>M</span></div><div class='dispibl padl20'><span style='font-size:50px'>"+s+"</span><span class='f11'>S</span></div>";
    setTimeout(function(){updateTimeSpan(countdown)}, 100);
	}
}

//shimmer effect common to all pages in revamp mobile membership
function shimmerEffect(page,text){
	// requestAnim shim layer by Paul Irish
	window.requestAnimFrame = (function(){
		return  window.requestAnimationFrame       ||
		window.webkitRequestAnimationFrame ||
		window.mozRequestAnimationFrame    ||
		window.oRequestAnimationFrame      ||
		window.msRequestAnimationFrame     ||
		function(/* function */ callback, /* DOMElement */ element){
			window.setTimeout(callback, 1000 / 60);
		};
	})();
	if(page == 'mem_pageOne'){
		Shimmer.settings({
			'canvas': 'shimmer',
			'text' : 'Browse Plans',
			'font': '24px Roboto Light, LaneNarrow',
			'animations': ['slide','slide','slide','slide']
		});
	} else if(page == 'mem_pageTwo'){
		Shimmer.settings({
			'canvas': 'shimmer',
			'text' : 'Select Duration',
			'font': '24px Roboto Light, LaneNarrow',
			'animations': ['slide','slide','slide','slide']
		});
	} else if(page == 'mem_pageThree'){
		Shimmer.settings({
			'canvas': 'shimmer',
			'text' : 'Continue',
			'font': '24px Roboto Light, LaneNarrow',
			'animations': ['slide','slide','slide','slide']
		});
	} else if(page == 'mem_pageFour'){
		Shimmer.settings({
			'canvas': 'shimmer',
			'text' : text,
			'font': '24px Roboto Light, LaneNarrow',
			'animations': ['slide','slide','slide','slide']
		});
	}
	function animate() {
		requestAnimFrame( animate );
		Shimmer.on();
	};
	animate();
}

//transition effect when scrolling through service plans in page 2 of revamp mobile membership
function pageTwoPlanTransition(){
	var serviceWidth = $(window).width();
	var serviceHeight = $(window).height();
	var calcWid = (serviceWidth)+'px';
	if($('li.active .holder').hasClass('wid33p_a')){
		$('li.active .holder').addClass('fullwid');
		$('li.active .holder').removeClass('wid33p_a mt60');
		$('li.active .opa70').fadeIn();
	} else {
		$('li.active .opa70').hide(100);
		$('li.active .holder').addClass('wid33p_a mt60').fadeIn(10);
		$('li.active .holder').removeClass('fullwid').fadeIn(10);
		$('li.active .middle').removeClass('mt60').fadeIn(10);
	}
}

//function to reset page dimensions according to device, page 2 of revamp mobile membership
function resetPageTwoHeight(){
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	var serviceName = $('#servicename').height();
	var bottomButton= $('#bot_opt').height();
	var vasHeight = windowHeight - (serviceName + bottomButton);
	$('#vas_srv').css('height',vasHeight);
	$('li #vas_srv').css('height',vasHeight);
	var vasSliderHeight = $('#vas_slide').height();
	var vasHeight1 = windowHeight - (serviceName + vasSliderHeight);
	$('#sevice_sel').css('height',vasHeight1);
}

//function to format numbers in display as comma seperated
function commaSeparateNumber(val){
	val = val.replace(',', '');
	var array = val.split('');
	var index = -3;
	while (array.length + index > 0) {
		array.splice(index, 0, ',');
		index -= 4;
	}
	return array.join('');
};

//function to reset page dimensions according to device, page 3 of revamp mobile membership
function resetPageThreeHeight(){
	$('.contact .frame').css('height', '60px');
	$('.contact .frame').css('line-height', '60px');
	$('.duration .frame').css('height', '80px');
	$('.duration .frame').css('line-height', '80px');
	var diff = $('#continue').offset().top - $('#durationText').offset().top;
	if(diff < 60){
		diff = 60;
	}
	$("#durationText").css('padding-bottom',diff);
	var contentHeight = $('#content').height();
	var continueHeight = $('#continue').height();
	var buttonHeight = $("#durationText").height();
	var windowHeight = $(window).height();
  	var serviceNameHeight = $("#servicename").height();
  	var priceWrapHeight = $("#priceWrap").height();
  	var contactHeight = $("#contact").height();
  	var durationWrapHeight = $("#durationWrap").height();
	if(contentHeight > windowHeight){
		$('#content').css('height',serviceNameHeight+priceWrapHeight+contactHeight+durationWrapHeight+buttonHeight);
	} else {
		$('#content').css('height',windowHeight);
	}
}

//function to update prices sliders, etc on swipe, page 3 of revamp mobile membership
function updatePageThreePrices(position,price_top,price_bottom,durationText,duration_id){
	var temp;
	var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',')
	if(price_top[position]){
		$('#priceTop #webRupee').show();
		$('#priceTop #value').animateNumber({ number: price_top[position],
			numberStep: comma_separator_number_step},1);
	} else {
		$('#priceTop #webRupee').hide();
		$('#priceTop #value').empty();
	}
	if(price[position]){
		$('#finalPrice').animateNumber({ number: price[position],
			numberStep: comma_separator_number_step});
		$('#cartPrice #value').animateNumber({ number: price[position],
			numberStep: comma_separator_number_step},1);
		$('#durationText').html(durationText[position]);
		$('#mainMemDur').val(duration_id[position]);
		createCookie('mainMemDur',duration_id[position]);
	}
	if(price_bottom[position]){
		$('#priceBottom #saveTxt').show();
		$('#priceBottom #webRupee').show();
		$('#priceBottom #value').animateNumber({ number: price_bottom[position],
			numberStep: comma_separator_number_step},1);
	} else {
		$('#priceBottom #saveTxt').hide();
		$('#priceBottom #webRupee').hide();
		$('#priceBottom #value').empty();
	}
}

//appending common function which will convert a number to its respective comma seperated format
$.fn.digits = function(){
	return this.each(function(){
		$(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
	})
}

//function to minimize slider, page 4 of revamp mobile memebership
function minimizeBigSlider(){
	updateSkipText();
	$('.vasbigslider').hide();
  //e.preventDefault();
}

//function to process when a VAS plan is selected from bigvas slider, page 4 of revamp mobile membership
function processVAS(name,duration,text,price,id,main,discount_given){
 $("#vasBigSlide").css("pointer-events","none");
 $("#vasOptions .vOpt").css("pointer-events","none");
	if($('#'+main).find('div.color13').length == 0){
		// fresh click
		$('#'+id).removeClass('mem_bgw').addClass('color13 bg4', 100);
		$("<div id="+id+" class='vasid color7 mem_brdr3 pad20'><div class='f19 fontreg'>"+name+"<div class='fr'><span class='strike color2'>"+vasStandardPrices[id]+"</span>&nbsp;&nbsp;<span id='top_cart_price'>"+price+"</span></div></div><div class='f14 fontlig color7 lh30'><div class='fl'>"+duration+"&nbsp;"+text+"</div><div class='clr'></div></div></div>").insertBefore('#sevice_sel #disclaimer');
		$("#discountTextVal").text(discountTextVal+parseInt(discount_given));
    discountTextVal+=parseInt(discount_given);
    $('#'+id+' #top_cart_price').digits();
		updatePageFourPrices('add',price);
		// $('#minimizeVas i').removeClass('mem-spite mem-downar').text('Review');
    setTimeout(function() {
      $("#vasBigSlide").css("pointer-events","auto");
      $("#vasOptions .vOpt").css("pointer-events","auto");
    }, 500);
	} else if($('#vasBigSlide li.active').find('#'+id).hasClass('color13')) {
		// click on already active element
		$('#vasBigSlide li.active').find('.color13').removeClass('color13 bg4', 100).addClass('mem_bgw');
		$('#sevice_sel').find('#'+id).remove();
		$('#'+id+' #top_cart_price').digits();
    $("#discountTextVal").text(discountTextVal-parseInt(discount_given));
    discountTextVal-=parseInt(discount_given);
		updatePageFourPrices('subtract',price);
    setTimeout(function() {
      $("#vasBigSlide").css("pointer-events","auto");
      $("#vasOptions .vOpt").css("pointer-events","auto");
    }, 500);
	} else {
		// selecting another element from same VAS, with an already active element
		$('#vasBigSlide li.active').find('.color13').removeClass('color13 bg4', 100).addClass('mem_bgw');
		var oldID = $('#vasBigSlide li.active').find('.color13').attr('id');
		var oldPrice = parseInt($('#sevice_sel').find('#'+oldID+' #top_cart_price').text().replace(',',''));
		$('#sevice_sel').find('#'+oldID).remove();
		$('#'+id).removeClass('mem_bgw').addClass('color13 bg4',100);
		$("<div id="+id+" class='vasid color7 mem_brdr3 pad20'><div class='f19 fontreg'>"+name+"<div class='fr'><span class='strike color2'>"+vasStandardPrices[id]+"</span>&nbsp;&nbsp;<span id='top_cart_price'>"+price+"</span></div></div><div class='f14 fontlig color7 lh30'><div class='fl'>"+duration+"&nbsp;"+text+"</div><div class='clr'></div></div></div>").insertBefore('#sevice_sel #disclaimer');
		$('#'+id+' #top_cart_price').digits();
		var calcPrice = parseInt(price) - parseInt(oldPrice);
		if(calcPrice > 0){
      updatePageFourPrices('add',calcPrice);
		} else if(calcPrice < 0){
      updatePageFourPrices('subtract',Math.abs(calcPrice));
		}
    if($('#sevice_sel').find('div.vasid').length > 0 && cookieflag != 1){
      var tempPrice = mainDiscountVal;
      $('#sevice_sel div.vasid').each(function(){
        tempPrice += parseInt(vasServices[$(this).attr('id')]);
      });
      $("#discountTextVal").text(tempPrice);
      discountTextVal = tempPrice;
    }
    setTimeout(function() {
      $("#vasBigSlide").css("pointer-events","auto");
      $("#vasOptions .vOpt").css("pointer-events","auto");
    }, 500);
	}
}

function updateReviewButtonStatus(){
  $("#proceed_text").text("Review Order");
	// if($('#sevice_sel').find('div.vasid').length > 0){
	// 	$('#minimizeVas i').removeClass('mem-spite mem-downar').text('Review');
	// 	$("#proceed_text").text("Pay Now");
	// } else {
	// 	$('#minimizeVas i').text('').addClass('mem-spite mem-downar');
	// 	$("#proceed_text").text("Skip to Payment");
	// }
}

function updateAddRemoveButton(display){
	if(readCookie('vasImpression')){
		var preSelectedVas = readCookie('vasImpression');
    historyStoreObj.push(minimizeBigSlider,"#overlay");
		if(preSelectedVas != ''){
			$(".vasbottomslider").hide();
			$("#selVasText div").text("Add/Remove Value Added Services");
			resetServSelHeight();
		} else {
			$(".vasbottomslider").show();
			$("#selVasText div").text(vasText);
			resetServSelHeight();
		}
	}
	if(display == 'show'){
		$(".vasbottomslider").show();
		$("#selVasText div").text(vasText);
		resetServSelHeight();
	}
}

function resetServSelHeight(){
	var vasHeight = $('#vas_slider').height();
	var servHeight = $('#servicename').height();
	var bottomHeight = $('#bottomCheckout').height();
	var servSelHeight = $('#sevice_sel').height();
	var winHeight = $(window).height();
	$('#sevice_sel').css('height',(winHeight - vasHeight - servHeight - bottomHeight - 26));
}
//function to update prices in cart, etc on selection, page 4 of revamp mobile membership
function updatePageFourPrices(operation,price){
	var total = parseInt($('#final_cart_price').text().replace(',',''));
	var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',')
	if(operation == 'add'){
		total += parseInt(price);
		$('#vasBigSlide li.active #vasOptions').prepend("<div class='animateThis fullwid fontreg posabs fb' style='padding-right:25px;margin:50px 0px;font-size:30px;'>+ "+price+"</div>")
		$('.animateThis').animate({'margin-top':'0px','opacity':'0',}, 500);
	} else if(operation == 'subtract'){
		total -= parseInt(price);
		$('#vasBigSlide li.active #vasOptions').prepend("<div class='animateThis fullwid fontreg posabs fb' style='padding-right:25px;margin:50px 0px;font-size:30px;'>- "+price+"</div>")
		$('.animateThis').animate({'margin-top':'0px','opacity':'0',}, 500);
	}
	$('#final_cart_price').animateNumber({ number: total,
		numberStep: comma_separator_number_step},1);

	setTimeout(function() {
		$('.animateThis').remove();
	}, 500);
	updateReviewButtonStatus();	
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function setVASCookie(){
  var vasImpression = new Array();

  if($('#sevice_sel').find('div.vasid').length > 0){
    $('#sevice_sel div.vasid').each(function(){
      vasImpression.push($(this).attr('id'));
    });
    eraseCookie('vasImpression');
    createCookie('vasImpression',vasImpression.join(","),1);
    updateAddRemoveButton();
  } else {
  	eraseCookie('vasImpression');
  	updateAddRemoveButton('show');
  }
  $('#vasImpression').val(readCookie('vasImpression'));
}

function updateSkipText() {
	if($("#proceed_text").text() == 'Review Order') {
		$("#proceed_text").text("Pay Now");
	}	
}