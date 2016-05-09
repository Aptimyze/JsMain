$(document).ready(function(){
	
bindPhoneVerificationLinks();


})


function bindPhoneVerificationLinks(){

	
	$("body").on('click','#phone_mob_statusView',function () {
		if($("#phone_mob_statusView").html()!='Verify') return;
		var mainMobile=$('#mobileView').html();
		mainMobile=mainMobile.trim();
		var phoneArray=mainMobile.split('-');
		var isd=phoneArray[0];
		if(isd.indexOf('+')==0) isd=isd.substring(1);
		var phone=phoneArray[1];
		phone = phone.replace(/^0+/, '');
		$("#hiddenPhoneMain").attr('saved',phone);
		$("#hiddenIsd1").attr('saved',isd);
		$("#hiddenPhoneMain").val(phone);
		$("#hiddenIsd1").val(isd);
		showOtpLayer('hiddenIsd1','hiddenPhoneMain','phone_mob_statusView');


	});


	$('body').on('click',"#alt_mob_statusView",function () {
		if($("#alt_mob_statusView").html()!='Verify') return;
		var mainMobile=$('#alt_mobileView').html();
		mainMobile=mainMobile.trim();
		var phoneArray=mainMobile.split('-');
		var isd=phoneArray[0];
		if(isd.indexOf('+')==0) isd=isd.substring(1);
		var phone=phoneArray[1];
		phone = phone.replace(/^0+/, '');
		$("#hiddenPhoneOther").attr('saved',phone);
		$("#hiddenIsd2").attr('saved',isd);
		$("#hiddenPhoneOther").val(phone);
		$("#hiddenIsd2").val(isd);
		showOtpLayer('hiddenIsd2','hiddenPhoneOther','alt_mob_statusView');


	});

	
	$("body").on('click',"#phone_res_statusView",function () {
		if($("#phone_res_statusView").html()!='Verify') return;
		var mainMobile=$('#landlineView').html();
		mainMobile=mainMobile.trim();
		var phoneArray=mainMobile.split('-');
		var isd=phoneArray[0];
		if(isd.indexOf('+')==0) isd=isd.substring(1);
		var phone=phoneArray[1]+phoneArray[2];
		phone = phone.replace(/^0+/, '');
		$("#hiddenLandline").attr('saved',phone);
		$("#hiddenIsd3").attr('saved',isd);
		$("#hiddenLandline").val(phone);
		$("#hiddenIsd3").val(isd);
		showOtpLayer('hiddenIsd3','hiddenLandline','phone_res_statusView','Y');


	});



} 