"use strict";
//namespace for chat application
var chatAppPc = chatAppPc || {};
chatAppPc.screenHeight = $(window).height();

//default values
chatAppPc.defaultv = {
				container: "#chatOpenPanel",
				incontainer: '.js-openOutPanel',
				minPanelclass : '.js-minpanel',
				listPanelId: '#js-lsitingPanel',
				tab1class: '.showtab1',
				tab2class: '.showtab2',
				listPanelInnerId : '#nchatDivs',
};

//html for login screen
chatAppPc.loginChatPanel = '<div class="pos_fix chatbg wid20p chatpos1 nz20 js-openOutPanel"><div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBar"></i> </div><div> <img src="../../../images/jspc/chat/chat-profile-pic.jpg" class="chatmt1"/> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Login to Chat</button></div></div>';

//html for mimimize chat
chatAppPc.minChatPanel = '<div class="nchatbg1 nchatw2 nchatp6 pos_fix colrw nchatmax js-minpanel cursp"><ul class="nchatHor clearfix f13 fontreg"> <li>      <div class="pt5 pr10">ONLINE MATCHES</div></li><li><div class="bg_pink disp-tbl txtc nchatb"><div class="vmid disp-cell">2</div></div></li><li class="pl10"> <i class="nchatspr nchatopen"></i> </li></ul></div>';

chatAppPc.chatLoginHeader = '<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBar"></i> </div><div class="fl"> <img src="images/chat-profile-small.jpg" class="nchatp4"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7 jschk1"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>';

chatAppPc.TabsOpt = '<div class="clearfix"><ul class="nchattab1 clearfix fontreg"><li id="tab1" class="active pos-rel" style="width:53%"><p>ONLINE MATCHES</p><div class="showlinec"></div></li><li id="tab2" class="pos-rel" style="width:46%"><p>ACCEPTED</p><div class="showlinec"></div></li></ul></div>  <div id="nchatDivs" class="nchatscrollDiv" style="height:300px"><div class="showtab1 js-htab"> </div><div class="showtab2 js-htab disp-none"></div></div>';

chatAppPc.Tab1Data = '<div><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">Shortlisted Members</p></div><ul class="chatlist"><li class="clearfix profileIcon" id="profile_XXYT5763"> <img id="pic_XXYT5763" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5763</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li></ul></div><div><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">Interst Recieved</p></div><ul class="chatlist"><li class="clearfix profileIcon" id="profile_XXYT5764"> <img id="pic_XXYT5764" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5764</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li></ul></div> <div><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">Desired Partner Matches</p></div><ul class="chatlist"><li class="clearfix profileIcon" id="profile_XXYT5765"> <img id="pic_XXYT5765" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5765</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5766"> <img id="pic_XXYT5766" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5766</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5767"> <img id="pic_XXYT5767" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5767</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5768"> <img id="pic_XXYT5768" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5768</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5769"> <img id="pic_XXYT5769" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5769</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5770"> <img id="pic_XXYT5770" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5770</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5771"> <img id="pic_XXYT5771" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5771</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5772"> <img id="pic_XXYT5772" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5772</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5773"> <img id="pic_XXYT5773" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5773</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5774"> <img id="pic_XXYT5774" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5774</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5775"> <img id="pic_XXYT5775" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5775</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5776"> <img id="pic_XXYT5776" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5776 -last</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li></ul></div>';

chatAppPc.Tab2Data ='<div><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">Desired Partner Matches</p></div><ul class="chatlist"><li class="clearfix profileIcon" id="profile_XXYT5777"> <img id="pic_XXYT5777" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5777</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5778"> <img id="pic_XXYT5778" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5778</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5779"> <img id="pic_XXYT5779" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5779</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div> </li><li class="clearfix profileIcon" id="profile_XXYT5780"> <img id="pic_XXYT5780" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5780</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5781"> <img id="pic_XXYT5781" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5781</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5782"> <img id="pic_XXYT5782" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5782</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5783"> <img id="pic_XXYT5783" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5783</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5784"> <img id="pic_XXYT5784" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5784</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5785"> <img id="pic_XXYT5785" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5785</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5786"> <img id="pic_XXYT5786" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5786</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5787"> <img id="pic_XXYT5787" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5787</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5788"> <img id="pic_XXYT5788" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5788 -last</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li></ul></div>';

chatAppPc.HoverBoxDiv = '<div class="pos_fix info-hover fontlig vishid nz21"><div class="nchatbdr3 f13"> <img src="images/chathover.jpg" class="vtop"/><div class="nchatgrad padall-10"><ul class="listnone lh22"><li>34 Years, 5\' 9\" Sikh</li><li>Sikh: Arora Punjabi</li><li>MBA/PGDM, B.Com</li><li>Advertising Professional</li><li>Rs. 15 - 20lac, New Delhi</li></ul><p class="txtc nc-color2 pt10 hgt18"><span class="disp-none">You accepted her interest</span></p></div><button class="bg_pink fullwid lh50 brdr-0 txtc colrw cursp">Send Interest</button></div></div>';





chatAppPc.cmfunc = {
	getHeight: function(){		
		return ($(window).height());
	},
	
	
	appendLoginHTML : function(){
		try
		{
			$(chatAppPc.defaultv.container).append(chatAppPc.loginChatPanel);			
		}
		catch(e)
		{
			console.log('the append html error:'+ e);	
		}
	},
	
	
	checkWidth:function(){
		if($(window).width()<1254)
		{
			return true
		}
		else
		{
			return false
		}		
	},
	minimizeChat:function(){
		try
		{
			if(chatAppPc.cmfunc.checkWidth())
			{
				$(chatAppPc.defaultv.incontainer).fadeOut('slow',function(){   $(chatAppPc.defaultv.container).append(chatAppPc.minChatPanel).fadeIn('slow');    });
			}
			else
			{
				$(chatAppPc.defaultv.incontainer).animate({right:'-100%'},1000); 
				$('body').animate({width:'100%'},300,function(){  $(chatAppPc.defaultv.container).append(chatAppPc.minChatPanel).fadeIn('slow');   });
			}			
		}
		catch(e)
		{
			console.log('chat exception minimizeChat:'+ e)
		}
	},
	maximizieChat:function(param){
		try
		{
			if(chatAppPc.cmfunc.checkWidth())
			{
				console.log('1');
				
			}
			else
			{
				$(chatAppPc.defaultv.minPanelclass).fadeOut('slow',function(){ 
				    $(chatAppPc.defaultv.minPanelclass).remove();				
					$('body').animate({width:'80%'},{duration:400,queue:false});
					$(chatAppPc.defaultv.incontainer).animate({right:'0'},{duration:400,queue:false});
					
				});
				
			}
		}
		catch(e)
		{
			console.log('chat exception maximizieChat:'+ e);
		}
	},
	calHoverPos: function(param1, param2, param3) {
		try
		{
			//hoverNewTop, hoverDivHgt
			var e = param1;
			var hoverbtm, newTop, hgtHiddenBelow, hgtVisible;
			hoverbtm = (parseInt(param2 + param3));
			hoverbtm = parseInt(hoverbtm/2);
			
			if (hoverbtm < chatAppPc.screenHeight) 
			{
				param2 = (Math.round(param2 / 2))-10;
				newTop = param2;
				if( (newTop+param3) > chatAppPc.screenHeight )
				{				
					hgtVisible = chatAppPc.screenHeight - param2;
					hgtHiddenBelow = param3 - hgtVisible;
					newTop = param2 - hgtHiddenBelow;
				}
			}
			else 
			{
				hgtVisible = chatAppPc.screenHeight - param2;
				hgtHiddenBelow = param3 - hgtVisible;
				newTop = param2 - hgtHiddenBelow;
			}
			
			return newTop;
		}
		catch(e){
			console.log('the error in calHoverPos:' +e)
		}
    },
	chatScrollHght: function() {

        var totalHgt = chatAppPc.cmfunc.getHeight();
        var remHgt = parseInt(totalHgt) - 140;
		console.log($(chatAppPc.defaultv.listPanelInnerId));
        $(chatAppPc.defaultv.listPanelInnerId).css('height', remHgt);

    },
	afterLoginPanel: function(){
		try
		{
			if($(chatAppPc.defaultv.listPanelId).length == 0)
			{
				$(chatAppPc.defaultv.incontainer).append('<div class="fullwid fontlig nchatcolor" id="js-lsitingPanel"/> ');
				$(chatAppPc.defaultv.listPanelId).append(chatAppPc.chatLoginHeader);
				//start:adding the tab panel now
				$(chatAppPc.defaultv.listPanelId).append(chatAppPc.TabsOpt);
				//start:add tab data 
				$(chatAppPc.defaultv.tab1class).append(chatAppPc.Tab1Data);	
				$(chatAppPc.defaultv.tab2class).append(chatAppPc.Tab2Data);	
				chatAppPc.cmfunc.chatScrollHght();
				$(chatAppPc.defaultv.listPanelInnerId).mCustomScrollbar({ theme: "light"  });	
			}
			else
			{
				console.log('element exist');	
			}
		}
		catch(e)
		{
			console.log('after login panel error:'+ e);
		}
	
	},
	toggleLogOutDiv:function(){
		try
		{
			$('#js-chattopH').toggleClass('disp-none');				
		}
		catch(e)
		{
			console.log('toggleLogOutDiv error:'+ e);
		}
 	},
	logOutChat: function()
	{
		 chatAppPc.cmfunc.toggleLogOutDiv();
		$('#js-lsitingPanel').fadeOut('slow',function(){ $('#js-chattopH').toggleClass('nchatbg2');	$('#js-loginPanel').fadeIn('slow')   });	
	},
	chatTabs1: function(param){
		$('ul.nchattab1 li').removeClass('active');
		$('#'+param).addClass('active');
		$('.js-htab').fadeOut('slow',function(){ $('.show'+param).fadeIn('slow')});		
		
	},
	
	scrollDown: function(elem, removeBorder) {
        if (removeBorder) {
            elem.animate({
                bottom: "-350px"
            }, function() {
                $(this).remove();
            });
        } else {
            elem.animate({
                bottom: "-307px"
            }, function() {
				$(elem.find(".nchatic_2")[0]).hide();
				$(elem.find(".nchatic_3")[0]).hide();
                elem.find(".onlinePerson").hide();
                elem.find(".pinkBubble2").show();
                elem.find(".chatBoxBar").addClass("cursp");
				elem.find(".downBarPic").addClass("downBarPicMin");
				elem.find(".downBarUserName").addClass("downBarUserNameMin");
            });
        }
    },
	 textAreaAdjust: function(o) {
        o.style.height = "1px";
        o.style.height = (o.scrollHeight - 16) + "px";
        var height = 250 - (o.scrollHeight - 44);
        if (height > 195) {
            $(o).closest("div").parent().find(".chatMessage").css("height", height);
        } else {
            $(o).css("overflow", "auto");
        }
    },
	 scrollUp: function(elem) {
        elem.animate({
            bottom: "0px"
        }, function() {
			$(elem.find(".nchatic_2")[0]).show();
			$(elem.find(".nchatic_3")[0]).show();
            elem.find(".onlinePerson").show();
            elem.find(".pinkBubble2").hide();
            elem.find(".chatBoxBar").removeClass("cursp");
			elem.find(".downBarPic").removeClass("downBarPicMin");
			elem.find(".downBarUserName").removeClass("downBarUserNameMin");
        });
    },
	 bindMinimize: function(elem) {
        $(elem).off("click").on("click", function(e) {
			e.stopPropagation();
            chatAppPc.cmfunc.scrollDown($(this).closest(".chatBox"), false);
        });
    },
   bindMaximize: function(elem, userId) {
        $(elem).off("click").on("click", function() {
            chatAppPc.cmfunc.scrollDown($(".extraPopup"), false);
            setTimeout(function() {
                $(".extraChats").css("padding-top", "0px");
            }, 100); 
            chatAppPc.cmfunc.scrollUp($("#chatBox_" + userId)); 
        });
    },
    bindClose: function(elem) {
        $(elem).off("click").on("click", function() {
            chatAppPc.cmfunc.scrollDown($(this).closest(".chatBox"), true);
            if ($(".extraNumber")) {
                var value = parseInt($(".extraNumber").text().split("+")[1]);
                var bodyWidth = $("body").width();
                var divWidth = ($(".chatBox").length - 1) * 250;
                if (value == 1 && divWidth < bodyWidth) {
                    $(".extraChats, .extraPopup").remove();
                } else if (value > 1) {
                    $(".extraNumber").text("+" + (value - 1));
                }
            }
        });
    },
    bindBlock: function(elem) {
        $(elem).off("click").on("click", function() {
            chatAppPc.cmfunc.scrollDown($(this).closest(".chatBox"), true);
            if ($(".extraNumber")) {
                var value = parseInt($(".extraNumber").text().split("+")[1]);
                var bodyWidth = $("body").width();
                var divWidth = ($(".chatBox").length - 1) * 250;
                if (value == 1 && divWidth < bodyWidth) {
                    $(".extraChats, .extraPopup").remove();
                } else if (value > 1) {
                    $(".extraNumber").text("+" + (value - 1));
                }
            }
        });
    },
    bindSendChat: function(userId) {
        $("#chatBox_" + userId).find('textarea').keyup(function(e) {
            if (e.keyCode == 13 && !e.shiftKey) {
                var text = $(this).val();
			
				$(this).val("").css("height", "24px");
                if (text.length > 1) {
					var superParent = $(this).parent().parent();
                    $(superParent).find(".chatMessage").css("height", "250px").append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div class="talkText">' + text + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
					var len = $(superParent).find(".talkText").length;
					var height = $($(superParent).find(".talkText")[len-1]).height();
					$($(superParent).find(".nchatic_8")[len-1]).css("margin-top",height);
					var scrollBox = $("#chatBox_" + userId).find('.chatMessage');
					var height = ($(".rightBubble").length+$(".leftBubble").length)*40;
					$(scrollBox).animate({scrollTop: height}, 500);
                }
            }
        });
    },
    bindExtraPopupUserClose: function(elem) {
        $(elem).off("click").on("click", function() {
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1];
            $("#chatBox_" + username).remove();
            $(this).closest(".extraChatList").remove();
            var value = parseInt($(".extraNumber").text().split("+")[1]);
            if (value == 1) {
                $(".extraChats , .extraPopup").remove();
            } else {
                $(".extraNumber").text("+" + (value - 1));
            }
        });
    },
    addDataExtraPopup: function(data) {
        $(".extraPopup").append('<div id="extra_' + data + '" class="extraChatList pad8_new"><div class="extraUsername cursp colrw disp_ib pad8_new fontlig f14">' + data + '</div><div class="pinkBubble vertM scir disp_ib padall-10"><span class="noOfMessg f13 pos-abs">1</span></div><i class="nchatspr nchatic_4 cursp disp_ib mt6 ml10"></i></div>');
    },
    appendChatBox: function(userId) {
       
	    $("#chatBottomPanel").prepend('<div class="chatBox z1000 btm0 brd_new fr mr7 fullhgt wid240 pos-rel disp_ib" id="chatBox_' + userId + '"><div class="chatBoxBar fullwid hgt57 bg5 pos-rel fullwid"></div><div class="chatArea fullwid fullhgt"><div class="messageArea f13 bg13 fullhgt"><div class="chatMessage scrolla pos_abs fullwid" style="height: 250px;"></div></div><div class="chatInput brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" onkeyup="chatAppPc.cmfunc.textAreaAdjust(this)" style="width: 220px;"  class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Write message" /></div></div></div>');
		
        $("#pic_" + userId).clone().appendTo("#chatBox_" + userId + " .chatBoxBar");
		
        $("#chatBox_" + userId + " #pic_" + userId).addClass("downBarPic cursp");
		
        $("#chatBox_" + userId + " .chatBoxBar").append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig">' + userId + '<div class="onlinePerson f11 opa50 mt4">Father is online</div></div></div>');
		
        $("#chatBox_" + userId + " .chatBoxBar .downBarText").append('<div class="iconBar cursp fr padallf_2 disp_ib opa40"><i class="nchatspr nchatic_3"></i><i class="nchatspr nchatic_2 ml10 mr10"></i><i class="nchatspr nchatic_1 mr10"></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">1</span></div>');
    },
    chatPanelsBox: function(param) {
        var heightPlus = false;
        var bodyWidth = $("body").width();
		 $("body").append("<div id='chatBottomPanel' class='btmNegtaive pos_fix calhgt2 nz20 fontlig'></div>");
		 var bottomPanelWidth = $(window).width() - $('.js-openOutPanel').width();
         $("#chatBottomPanel").css('width',bottomPanelWidth);

		
		
        //opening main div
        if ($("#chatBottomPanel").css("bottom") == "-300px") {
            $("#chatBottomPanel").css("bottom","0px");
        }
        var userId = param;
        if ($("#chatBox_" + userId).length == 0) {
            //check for more than fit divs (creating extra popup)
            var bodyWidth = $("#chatBottomPanel").width();
            var divWidth = ($(".chatBox").length + 1) * 250;
            if (divWidth > bodyWidth) {
                if ($(".extraChats").length == 0) {
                    $("#chatBottomPanel").append('<div class="extraChats pos_abs nchatbtmNegtaive wid30 hgt43 bg5"><div class="extraNumber cursp colrw opa50">+1</div><div><div class="extraPopup pos_abs l0 nchatbtmNegtaive wid153 bg5"><div>');
                    $(".extraChats").css("left", $('#chatBottomPanel').width() - $('.chatBox').length * 250 - 32);
                    chatAppPc.cmfunc.scrollUp($(".extraChats"));
                    //adding data in extra popup 
                    var len = $(".chatBox").length
                    var data = $($(".chatBox")[len - 1]).attr("id").split("_")[1];
                    chatAppPc.cmfunc.addDataExtraPopup(data);

                    //binding extra chat small icon click to view popup
                    $(".extraNumber").off("click").on("click", function() {
                        var len = $(".chatBox").length;
                        var value = parseInt($(".extraNumber").text().split("+")[1]);
						
                        var position = len - value - 1;
                        chatAppPc.cmfunc.scrollDown($($('.chatBox')[position]), false);
                            $(".extraPopup").animate({
                                bottom: "48px"
                            });
                            setTimeout(function() {
                                $(".extraChats").css("padding-top", "11px");
                            }, 300);
                    });
                } else {
                    var value = parseInt($(".extraNumber").text().split("+")[1]) + 1;
                    //adding username of user in extra popup 
                    var len = $(".chatBox").length + 1;
                    var data = $($(".chatBox")[len - value - 1]).attr("id").split("_")[1];
                    chatAppPc.cmfunc.addDataExtraPopup(data);
                    $(".extraNumber").text("+" + value);
                }
                chatAppPc.cmfunc.bindExtraPopupUserClose($(".nchatic_4"));
                //binding username click in extra chat popup
                $('body').on('click', '.extraUsername', function() {
                    //showing clicked popup
                    chatAppPc.cmfunc.scrollDown($(".extraPopup"), false);
                    setTimeout(function() {
                        $(".extraChats").css("padding-top", "0px");
                    }, 100);
                    var username = $(this).closest(".extraChatList").attr("id").split("_")[1];
                    var originalElem = $("#chatBox_" + username);
                    $("#chatBox_" + username).clone().prependTo("#chatBottomPanel");
                    //var htmlStr = $("#chatBox_" + username).prop('outerHTML');
                    originalElem.remove();
                    $(this).closest(".extraChatList").remove();
                    //$("#chatBottomPanel").prepend(htmlStr);
                    chatAppPc.cmfunc.scrollUp($("#chatBox_" + username));
                    chatAppPc.cmfunc.bindMinimize($("#chatBox_" + username).find(".nchatic_2"));
                    chatAppPc.cmfunc.bindMaximize($("#chatBox_" + username).find(".chatBoxBar"), username);
                    chatAppPc.cmfunc.bindClose($("#chatBox_" + username).find(".nchatic_1"));
                    chatAppPc.cmfunc.bindBlock($("#chatBox_" + username).find(".nchatic_3"));
                    chatAppPc.cmfunc.bindSendChat(username);

                    //adding data in extra popup 
                    var len = $(".chatBox").length;
                    var value = parseInt($(".extraNumber").text().split("+")[1]);
                    var data = $($(".chatBox")[len - 1 - (value - 1)]).attr("id").split("_")[1];
                    var dataAdded = false;
                    $(".extraChatList").each(function(index, element) {
                        var id = $(this).attr("id").split("_")[1];
                        if (id == data) {
                            dataAdded = true;
                        }
                    });
                    if (dataAdded == false) {
                        chatAppPc.cmfunc.addDataExtraPopup(data);
                        chatAppPc.cmfunc.bindExtraPopupUserClose($("#extra_" + data).find(".nchatic_4"));
                    }
                });
            }
            //appending chat box in bottom panel
            chatAppPc.cmfunc.appendChatBox(userId);

            //binding buttons
            chatAppPc.cmfunc.bindMaximize($("#chatBox_" + userId).find(".chatBoxBar"), userId);
            chatAppPc.cmfunc.bindMinimize($("#chatBox_" + userId).find(".nchatic_2"));
            chatAppPc.cmfunc.bindClose($("#chatBox_" + userId).find(".nchatic_1"));
            chatAppPc.cmfunc.bindBlock($("#chatBox_" + userId).find(".nchatic_3"));
            chatAppPc.cmfunc.bindSendChat(userId);

        } else {
            //showimg chat box present in extra chat panel on click from right panel
            $(".extraChatList").each(function(index, element) {
                var id = $(this).attr("id").split("_")[1];
                if (id == param) {
                    //show clicked popup
                    chatAppPc.cmfunc.scrollDown($(".extraPopup"), false);
                    setTimeout(function() {
                        $(".extraChats").css("padding-top", "0px");
                    }, 100);
                    var username = $(this).closest(".extraChatList").attr("id").split("_")[1];
                    var originalElem = $("#chatBox_" + username);
                    $("#chatBox_" + username).clone().prependTo("#chatBottomPanel");
                    originalElem.remove();
                    $(this).closest(".extraChatList").remove();
                    //binding buttons
                    chatAppPc.cmfunc.bindMinimize($("#chatBox_" + username).find(".nchatic_2"));
                    chatAppPc.cmfunc.bindMaximize($("#chatBox_" + username).find(".chatBoxBar"), username);
                    chatAppPc.cmfunc.bindClose($("#chatBox_" + username).find(".nchatic_1"));
                    chatAppPc.cmfunc.bindBlock($("#chatBox_" + username).find(".nchatic_3"));
                    chatAppPc.cmfunc.bindSendChat(username);

                    //adding data in extra popup 
                    var len = $(".chatBox").length;
                    var value = parseInt($(".extraNumber").text().split("+")[1]);
                    var data = $($(".chatBox")[len - 1 - (value - 1)]).attr("id").split("_")[1];
                    chatAppPc.cmfunc.addDataExtraPopup(data);
                    chatAppPc.cmfunc.bindExtraPopupUserClose($("#extra_" + data).find(".nchatic_4"));
                }
            });
        }
        //animation on chatbox slideup
        $("#chatBox_" + userId).find(".pinkBubble2").hide();
        //scrolling down side popup on adding new chatbox
        if ($(".extraChats").length > 0 && $(".extraPopup ").css("bottom") != "-300px") {
            chatAppPc.cmfunc.scrollDown($(".extraPopup "), false);
            setTimeout(function() {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
        }
    },
	
}

Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};


$(function(){	
	// setting the height of the chat panel accroding to screen height
	$('.js-openOutPanel').css('height',chatAppPc.cmfunc.getHeight());	
	
	//capturing the click of elemts added during run time
	
	$('#chatOpenPanel').on('click','.js-minChatBar',function() { chatAppPc.cmfunc.minimizeChat(); });	
	
	$('#chatOpenPanel').on('click', '.js-minpanel', function() {  chatAppPc.cmfunc.maximizieChat();  });	
	
	$('#chatOpenPanel').on('click', '.js-LogoutPanel', function() {  chatAppPc.cmfunc.toggleLogOutDiv();  });
	
	$('#chatOpenPanel').on('click','.jschatLogOut',function(){ chatAppPc.cmfunc.logOutChat();  });
	
	$('#chatOpenPanel').on('click','.jsChatLogAsIn',function(){ chatAppPc.cmfunc.togLoagAs();   });
	
	$('#chatOpenPanel').on('click','ul.nchattab1 li',function(){ chatAppPc.cmfunc.chatTabs1($(this).attr('id')  )  });
	
	$('#chatOpenPanel').on('click','.profileIcon',function(){ 
		//$('.info-hover').css({'visibility': 'hidden'}); chatAppPc.cmfunc.chatPanelsBox($(this).attr('id').split("_")[1]);   
		chatAppPc.cmfunc.chatPanelsBox($(this).attr('id').split("_")[1]);   
	});
	
	
	
	
	

	$(document).on({
		mouseenter: function () {			
			try
			{
				if($('.info-hover').length == 0)
				{
			 		$('#chatOpenPanel').append(chatAppPc.HoverBoxDiv);	
				}
			  	var hoverNewTop = $(this)[0].getBoundingClientRect().top;	
				var hoverDivHgt = Math.round($('.info-hover')[0].getBoundingClientRect().height);		  	
			 	if(chatAppPc.cmfunc.checkWidth())
			  	{
					var shiftright = 245;
			  	}
			  	else
			  	{
					var shiftright = Math.round($('.js-openOutPanel')[0].getBoundingClientRect().width);
			  	}
			 	//console.log('hoverNewTop->'+hoverNewTop+' hoverDivHgt->'+ hoverDivHgt+' shiftright->'+shiftright);
				 $('.info-hover').css({
					'top': chatAppPc.cmfunc.calHoverPos(this, hoverNewTop, hoverDivHgt),
					'visibility': 'visible',
					'right': shiftright
            	});
				
				 $('.info-hover').hover(
        function() {
            $(this).css('visibility', 'visible');
        },
        function() {
            $(this).css('visibility', 'hidden');
        }
    );
	
			}
			catch(e)
			{
				console.log('the hover error:'+ e);
			}
		},
		mouseleave: function () {
			 $('.info-hover').css({
                'visibility': 'hidden'
            });
		}
	}, "ul.chatlist li");

	
	//Drop down for "Log as"
	$('.js-ncdwn1').click(function(){chatAppPc.cmfunc.closeDrop();});
	
	//on clicking login to chat
	$('#js-chatLogin').click(function(){
		//use converse client to connect to openfire to create chat session
		require(['converse'], function (converse) {	
			initiateChat();	
		});
		$('#js-loginPanel').fadeOut('fast',function(){ $('#js-lsitingPanel').css('display','block'); chatAppPc.cmfunc.afterLoginPanel();   });		
	});
	
	
	
});

//intialize the html of chat
(function(){	
	//setting the html of the login 
    chatAppPc.cmfunc.appendLoginHTML();
})();




