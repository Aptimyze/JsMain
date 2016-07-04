;
(function($) {
    "use strict";
    try 
	{
        function ChatPlugin(elem, options) {
         
			 var self = this;
			 self.$elem = $(elem);
			  var defaults = {
                container: ".js-openOutPanel",
                minimizeButtonOut: ".js-minChatBarOut",
                minimizeButtonIn: '.js-minChatBarIn',
                maximizeButton: ".js-minpanel",
                logoutPanelToggle: '.js-LogoutPanel',
                loginChatButton: '#js-chatLogin',
                logoutChat: '.jschatLogOut',
                chatTab1: 'ul.nchattab1 li',
                profileIcon: '.profileIcon',
                loginPanelId: '#js-loginPanel',
                listingPanelId: '#js-lsitingPanel',
                toggelPanelId: '#js-chattopH',
                tab1class: '.showtab1',
                tab2class: '.showtab2',
                listPanelInnerId: '#nchatDivs',
                listingLiClass: 'ul.chatlist',
                hoverBoxClass: '.info-hover',
                commonHoverClass: '.profileIcon',
                chatPanelBtmId: '#chatBottomPanel',
                outerEle:'<div class="pos_fix chatbg chatpos1 nz20 js-openOutPanel"></div>',
                loginChatPanel: '<div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarOut"></i> </div><div> <img src="images/chat-profile-pic.jpg" class="chatmt1"/> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Login to Chat</button></div>',
                minChatPanelHTML: '<div class="nchatbg1 nchatw2 nchatp6 pos_fix colrw nchatmax js-minpanel cursp"><ul class="nchatHor clearfix f13 fontreg"> <li>      <div class="pt5 pr10">ONLINE MATCHES</div></li><li><div class="bg_pink disp-tbl txtc nchatb"><div class="vmid disp-cell">2</div></div></li><li class="pl10"> <i class="nchatspr nchatopen"></i> </li></ul></div>',
                chatHeaderHTML: '<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="images/chat-profile-small.jpg" class="nchatp4"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>',
                TabsOpt: '<div class="clearfix"><ul class="nchattab1 clearfix fontreg"><li id="tab1" class="active pos-rel" style="width:53%"><p>ONLINE MATCHES</p><div class="showlinec"></div></li><li id="tab2" class="pos-rel" style="width:46%"><p>ACCEPTED</p><div class="showlinec"></div></li></ul></div>  <div id="nchatDivs" class="nchatscrollDiv" style="height:300px"><div class="showtab1 js-htab"> </div><div class="showtab2 js-htab disp-none"></div></div>',
                Tab1Data: '',
                Tab2Data: '<div><div class="f12 fontreg nchatbdr2"><p class="nchatt1 fontreg pl15">Desired Partner Matches</p></div><ul class="chatlist"><li class="clearfix profileIcon" id="profile_XXYT5777"> <img id="pic_XXYT5777" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5777</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5778"> <img id="pic_XXYT5778" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5778</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5779"> <img id="pic_XXYT5779" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5779</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div> </li><li class="clearfix profileIcon" id="profile_XXYT5780"> <img id="pic_XXYT5780" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5780</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5781"> <img id="pic_XXYT5781" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5781</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5782"> <img id="pic_XXYT5782" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5782</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5783"> <img id="pic_XXYT5783" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5783</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5784"> <img id="pic_XXYT5784" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5784</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5785"> <img id="pic_XXYT5785" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5785</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5786"> <img id="pic_XXYT5786" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5786</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5787"> <img id="pic_XXYT5787" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5787</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li><li class="clearfix profileIcon" id="profile_XXYT5788"> <img id="pic_XXYT5788" src="images/pic1.jpg" class="fl"/><div class="fl f14 fontlig pt15 pl18">XXYT5788 -last</div><div class="fr"><i class="nchatspr nchatic5 mt15"></i></div></li></ul></div>',
                HoverBoxHTML: '<div class="pos_fix info-hover fontlig vishid nz21"><div class="nchatbdr3 f13"> <img src="images/chathover.jpg" class="vtop"/><div class="nchatgrad padall-10"><ul class="listnone lh22"><li>34 Years, 5\' 9\" Sikh</li><li>Sikh: Arora Punjabi</li><li>MBA/PGDM, B.Com</li><li>Advertising Professional</li><li>Rs. 15 - 20lac, New Delhi</li></ul><p class="txtc nc-color2 pt10 hgt18"><span class="disp-none">You accepted her interest</span></p></div><button class="bg_pink fullwid lh50 brdr-0 txtc colrw cursp">Send Interest</button></div></div>',
            };
			
			// mix in the passed-in options with the default options
            self.options = $.extend({}, defaults, options);
			//console.log(self.options);
			init();
			
            function checkAuthentication(){
                $.ajax({
                    url: "/api/v1/chat/chatUserAuthentication",
                    success: function(data){
                        console.log(data.statusCode);
                        if(data.responseStatusCode == "0"){
                            console.log("Login done");
                            createCookie("chatAuth","true");
                            loginChat();
                        }
                        else{
                            console.log(data.responseMessage);
                            console.log("In checkAuthentication failure");
                            eraseCookie("chatAuth");
                        }
                    }
                });
            }
            
			//binding of all click event on the DOM of login screen			
            $(self.options.minimizeButtonOut).on('click', minChatPanel);
            $(self.options.loginChatButton).on('click', checkAuthentication);
			
			//start:this function the screen size
            function checkWidth() 
			{
                if ($(window).width() < 1254)
				{
                    return true
                } 
				else 
				{
                    return false
                }
            };
			//start: this function eturn the screen height of the user
            function getHeight() 
			{
                return ($(window).height());
            };
			//start:it calculate the height available for the listing
            function chatScrollHght() 
			{
                var totalHgt = getHeight();
                var remHgt = parseInt(totalHgt) - 140;
                $(self.options.listPanelInnerId).css('height', remHgt);
            };
			 //start:function to minimize the chat pannel			
            function minChatPanel(e) 
			{
				e.stopPropagation();
                var chk = checkWidth();
				if( ($(self.options.chatPanelBtmId).length != 0) || ($(self.options.chatPanelBtmId).css('display') == 'block')   )
				{
					$(self.options.chatPanelBtmId).css('display','none');
				}
                if (chk) 
				{
                    console.log('a1');
				     $(self.options.container).fadeOut('slow');
					 $(elem).append(self.options.minChatPanelHTML).fadeIn();
					  $(self.options.maximizeButton).on('click', maxChatPanel);		
					
                } 
				else 
				{
                    $(self.options.container).animate({right: '-100%'}, 1000);
                    $('body').animate({width: '100%'}, 300, function() {
                        $(elem).append(self.options.minChatPanelHTML).fadeIn();   
						 $(self.options.maximizeButton).on('click', maxChatPanel);		                   
                    });
                }	
            };
			 //start:maximize the chat panel			
            function maxChatPanel(e) 
			{
                e.stopPropagation();
                var chk = checkWidth();
				console.log('max in12');				
				$(self.options.maximizeButton).fadeOut('slow', function() {
                      $(self.options.maximizeButton).remove();
                });
				if (chk) 
				{
					$(self.options.container).fadeIn('slow');
                } 
				else 
				{
                    $(function() {
                        $("body").animate({width: '80%'}, {duration: 400,queue: false});
                        $(self.options.container).animate(
							{right: '0'}, 
							{duration: 400,queue: false,complete: function(){      
								if(  ($(self.options.chatPanelBtmId).length!=0) || ($(self.options.chatPanelBtmId).css('display') == 'none')    )
								{
									$(self.options.chatPanelBtmId).fadeIn('slow');
								}
							
							}}
						);
                    });
                }
            };
			//function to add divs
            function addListingDivs(){
                $(self.options.container).append('<div class="fullwid fontlig nchatcolor" id="js-lsitingPanel"/> ');
                $('#js-lsitingPanel').append('<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="images/chat-profile-small.jpg" class="nchatp4"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>');
                $(self.options.listingPanelId).append(self.options.TabsOpt);
                $(self.options.tab1class).append(self.options.Tab1Data);
                $(self.options.tab2class).append(self.options.Tab2Data);
                //add hover box HTML
                $(elem).append(self.options.HoverBoxHTML);
                //calling height function for calculating the height of listing panel
                chatScrollHght();
                //rebind the min chat window again as new dom has been added
                $(self.options.minimizeButtonIn).on('click', minChatPanel);
                //on click of name show the panel with logout option
                $(self.options.logoutPanelToggle).on('click', togglePanel);
                //logoutChat
                $(self.options.logoutChat).on('click', logOutChat);
                //tabs clicked: passing the id of the tab to the function
                $(self.options.chatTab1).on('click', function() {
                    TabClick($(this));
                });
                $(self.options.listPanelInnerId).mCustomScrollbar({
                    theme: "light"
                });
                $(self.options.listingLiClass).on('mouseenter mouseleave', 'li', setHoverPosition);
                $(elem).on('click','.profileIcon', chatPanelsBox);
            }

			 //start:on click og login button this function is called from $(self.options.loginChatButton).on('click', loginChat);
            function loginChat() 
			{
                console.log("in login chat");
				initiateChatConnection();	
            };
			//start: this function add HTML structure once user has logged in chat,invoked when data for listing has been generated			
            self.addListingBody = function()
			{    console.log("add listing body");
                console.log($(self.options.listingPanelId).length);
                console.log("***");
                if ($(self.options.listingPanelId).length == 0) 
                {
                    console.log("inside if");
                    if(readCookie('chatAuth') == 'true'){
                        console.log($(self.options.loginPanelId).css('display'));
                        if($(self.options.loginPanelId).css('display') == 'block'){
                            $(self.options.loginPanelId).fadeOut('slow', function() {
                                addListingDivs();
                            });
                        }
                        else{
                            addListingDivs();
                        }
                         
                    }
                    else{
                        
                        $(self.options.loginPanelId).fadeOut('slow', function() {
                            addListingDivs();
                        });
                    }
                } 
                else 
                {
                    $(self.options.loginPanelId).fadeOut('slow', function() {
                        $(self.options.listingPanelId).fadeIn('slow');
                    })
                }
				
            }
			//start:function to calculate the position of hover box
			function setHoverPosition(event) 
			{
                if (event.type == 'mouseenter') 
				{
                    var hoverNewTop = $(this)[0].getBoundingClientRect().top;
                    var hoverDivHgt = Math.round($('.info-hover')[0].getBoundingClientRect().height);
                    if (checkWidth()) {
                        var shiftright = 245;
                    } else {
                        var shiftright = Math.round($('.js-openOutPanel')[0].getBoundingClientRect().width);
                    }
                    $(self.options.hoverBoxClass).css({
                        'top': calHoverPos(this, hoverNewTop, hoverDivHgt),
                        'visibility': 'visible',
                        'right': shiftright
                    });
                    $(self.options.hoverBoxClass).hover(
                        function() {
                            $(this).css('visibility', 'visible');
                        },
                        function() {
                            $(this).css('visibility', 'hidden');
                        }
                    );
                } 
				else 
				{
                    $(self.options.hoverBoxClass).css({
                        'visibility': 'hidden'
                    });
                }
            };
			//start:this function calculate the postion of hover box
            function calHoverPos(param1, param2, param3) 
			{
                try {
                    var e = param1;
                    var hoverbtm, newTop, hgtHiddenBelow, hgtVisible;
                    hoverbtm = (parseInt(param2 + param3));
                    hoverbtm = parseInt(hoverbtm / 2);
                    if (hoverbtm < getHeight()) {
                        param2 = (Math.round(param2 / 2)) - 10;
                        newTop = param2;
                        if ((newTop + param3) > getHeight) {
                            hgtVisible = getHeight() - param2;
                            hgtHiddenBelow = param3 - hgtVisible;
                            newTop = param2 - hgtHiddenBelow;
                        }
                    } else {
                        hgtVisible = getHeight() - param2;
                        hgtHiddenBelow = param3 - hgtVisible;
                        newTop = param2 - hgtHiddenBelow;
                    }
                    return newTop;
                } catch (e) {
                    console.log('the error in calHoverPos:' + e)
                }
            };
			function togglePanel() 
			{
                $(self.options.toggelPanelId).toggleClass('disp-none');
            };
			function logOutChat() 
			{
                togglePanel();
                console.log("In logoutCHat function");
                eraseCookie('chatAuth');
                if($(self.options.loginPanelId).length == 0){
                    $(self.options.container).append(self.options.loginChatPanel);
                }
                $(self.options.listingPanelId).fadeOut('slow', function() {
                    $(self.options.loginPanelId).fadeIn('slow');
                })
            };
            function TabClick(param) {
                $(self.options.chatTab1).removeClass('active');
                //console.log(param);
                $('#' + param.attr('id')).addClass('active');
                $('.js-htab').fadeOut('slow', function() {
                    $('.show' + param.attr('id')).fadeIn('slow')
                });
            };
			//start:function for chat boxes-------------------------------
			     function scrollDown(elem, removeBorder) 
			{
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
    		};
			 function textAreaAdjust(o) 
			 {
				o.style.height = "1px";
				o.style.height = (o.scrollHeight - 16) + "px";
				var height = 250 - (o.scrollHeight - 44);
				if (height > 195) {
					$(o).closest("div").parent().find(".chatMessage").css("height", height);
				} else {
					$(o).css("overflow", "auto");
				}
			};
	        function scrollUp(elem) 
			{
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
			};
			function bindMinimize(elem) 
			{
				$(elem).off("click").on("click", function(e) {
					e.stopPropagation();
					scrollDown($(this).closest(".chatBox"), false);
				});
			};
		    function bindMaximize(elem, userId) 
			{
				$(elem).off("click").on("click", function() {
					scrollDown($(".extraPopup"), false);
					setTimeout(function() {
						$(".extraChats").css("padding-top", "0px");
					}, 100); 
					scrollUp($("#chatBox_" + userId)); 
				});
			};
			function bindClose(elem) 
			{
				$(elem).off("click").on("click", function() {
					scrollDown($(this).closest(".chatBox"), true);
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
			};
			function bindBlock(elem) 
			{
				$(elem).off("click").on("click", function() {
					scrollDown($(this).closest(".chatBox"), true);
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
			};
			function bindSendChat(userId) 
			{
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
			};
			function bindExtraPopupUserClose(elem) 
			{
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
			};
			function addDataExtraPopup(data) 
			{
				$(".extraPopup").append('<div id="extra_' + data + '" class="extraChatList pad8_new"><div class="extraUsername cursp colrw disp_ib pad8_new fontlig f14">' + data + '</div><div class="pinkBubble vertM scir disp_ib padall-10"><span class="noOfMessg f13 pos-abs">1</span></div><i class="nchatspr nchatic_4 cursp disp_ib mt6 ml10"></i></div>');
			};
			function appendChatBox(userId) 
			{
			   
				$("#chatBottomPanel").prepend('<div class="chatBox z1000 btm0 brd_new fr mr7 fullhgt wid240 pos-rel disp_ib" id="chatBox_' + userId + '"><div class="chatBoxBar fullwid hgt57 bg5 pos-rel fullwid"></div><div class="chatArea fullwid fullhgt"><div class="messageArea f13 bg13 fullhgt"><div class="chatMessage scrolla pos_abs fullwid" style="height: 250px;"></div></div><div class="chatInput brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" onkeyup="textAreaAdjust(this)" style="width: 220px;"  class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Write message" /></div></div></div>');
				
				$("#pic_" + userId).clone().appendTo("#chatBox_" + userId + " .chatBoxBar");
				
				$("#chatBox_" + userId + " #pic_" + userId).addClass("downBarPic cursp");
				
				$("#chatBox_" + userId + " .chatBoxBar").append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig">' + userId + '<div class="onlinePerson f11 opa50 mt4">Father is online</div></div></div>');
				
				$("#chatBox_" + userId + " .chatBoxBar .downBarText").append('<div class="iconBar cursp fr padallf_2 disp_ib opa40"><i class="nchatspr nchatic_3"></i><i class="nchatspr nchatic_2 ml10 mr10"></i><i class="nchatspr nchatic_1 mr10"></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">1</span></div>');
			};
			function createChatBox()
			{
				$(self.options.chatPanelBtmId).append('<div class="extraChats pos_abs nchatbtmNegtaive wid30 hgt43 bg5"><div class="extraNumber cursp colrw opa50">+1</div><div><div class="extraPopup pos_abs l0 nchatbtmNegtaive wid153 bg5"><div>');
								$(".extraChats").css("left", $(self.options.chatPanelBtmId).width() - $('.chatBox').length * 250 - 32);
								scrollUp($(".extraChats"));
								//adding data in extra popup 
								var len = $(".chatBox").length
								var data = $($(".chatBox")[len - 1]).attr("id").split("_")[1];
								addDataExtraPopup(data);
			
								//binding extra chat small icon click to view popup
								$(".extraNumber").off("click").on("click", function() {
									var len = $(".chatBox").length;
									var value = parseInt($(".extraNumber").text().split("+")[1]);
									
									var position = len - value - 1;
									scrollDown($($('.chatBox')[position]), false);
										$(".extraPopup").animate({
											bottom: "48px"
										});
										setTimeout(function() {
											$(".extraChats").css("padding-top", "11px");
										}, 300);
								});
				
			};
			function chatPanelsBox( ) 
			{
				try
				{
					console.log('a');
					var param = $(this).attr('id').split("_")[1];
					var heightPlus = false;
					var bodyWidth = $("body").width();
					 $(elem).append("<div id='chatBottomPanel' class='btmNegtaive pos_fix calhgt2 nz20 fontlig'></div>");
					 var bottomPanelWidth = $(window).width() - $('.js-openOutPanel').width();
					 $(self.options.chatPanelBtmId).css('width',bottomPanelWidth);
					//opening main div
					if ($(self.options.chatPanelBtmId).css("bottom") == "-300px") {
						$(self.options.chatPanelBtmId).css("bottom","0px");
					}
					var userId = param;
					if ($("#chatBox_" + userId).length == 0) {
						//check for more than fit divs (creating extra popup)
						var bodyWidth = $(self.options.chatPanelBtmId).width();
						var divWidth = ($(".chatBox").length + 1) * 250;
						if (divWidth > bodyWidth) 
						{
							if ($(".extraChats").length == 0) 
							{
								createChatBox();
							} 
							else 
							{
								var value = parseInt($(".extraNumber").text().split("+")[1]) + 1;
								//adding username of user in extra popup 
								var len = $(".chatBox").length + 1;
								var data = $($(".chatBox")[len - value - 1]).attr("id").split("_")[1];
								addDataExtraPopup(data);
								$(".extraNumber").text("+" + value);
							}
							bindExtraPopupUserClose($(".nchatic_4"));
							//binding username click in extra chat popup
							$('.extraUsername').on('click',extraUserNameBox );
						}
						//appending chat box in bottom panel
						appendChatBox(userId);
			
						//binding buttons
						bindMaximize($("#chatBox_" + userId).find(".chatBoxBar"), userId);
						bindMinimize($("#chatBox_" + userId).find(".nchatic_2"));
						bindClose($("#chatBox_" + userId).find(".nchatic_1"));
						bindBlock($("#chatBox_" + userId).find(".nchatic_3"));
						bindSendChat(userId);
			
					} else {
						//showimg chat box present in extra chat panel on click from right panel
						$(".extraChatList").each(function(index, element) {
							var id = $(this).attr("id").split("_")[1];
							if (id == param) {
								//show clicked popup
								scrollDown($(".extraPopup"), false);
								setTimeout(function() {
									$(".extraChats").css("padding-top", "0px");
								}, 100);
								var username = $(this).closest(".extraChatList").attr("id").split("_")[1];
								var originalElem = $("#chatBox_" + username);
								$("#chatBox_" + username).clone().prependTo("#chatBottomPanel");
								originalElem.remove();
								$(this).closest(".extraChatList").remove();
								//binding buttons
								bindMinimize($("#chatBox_" + username).find(".nchatic_2"));
								bindMaximize($("#chatBox_" + username).find(".chatBoxBar"), username);
								bindClose($("#chatBox_" + username).find(".nchatic_1"));
								bindBlock($("#chatBox_" + username).find(".nchatic_3"));
								bindSendChat(username);
			
								//adding data in extra popup 
								var len = $(".chatBox").length;
								var value = parseInt($(".extraNumber").text().split("+")[1]);
								var data = $($(".chatBox")[len - 1 - (value - 1)]).attr("id").split("_")[1];
								addDataExtraPopup(data);
								bindExtraPopupUserClose($("#extra_" + data).find(".nchatic_4"));
							}
						});
					}
					//animation on chatbox slideup
					$("#chatBox_" + userId).find(".pinkBubble2").hide();
					//scrolling down side popup on adding new chatbox
					if ($(".extraChats").length > 0 && $(".extraPopup ").css("bottom") != "-300px") {
						scrollDown($(".extraPopup "), false);
						setTimeout(function() {
							$(".extraChats").css("padding-top", "0px");
						}, 100);
					}
				}
				catch(e)
				{
					console.log('the chat box err:'+ e);
				}
			};
			
			//end:function for chat boxes-------------------------------
			 
			 
            // initialize this plugin
            function init() 
			{
				try
				{
                    $(elem).append(self.options.outerEle);
                    if(!(readCookie("chatAuth") == "true")){
                        $(self.options.container).append(self.options.loginChatPanel);
                    }
					//check for width
					if(checkWidth())
					{
						$(self.options.container).css({'width': 260,'display':'none'});
						$(elem).append(self.options.minChatPanelHTML);
						$(self.options.maximizeButton).on('click', maxChatPanel);
					}
					else
					{
						$('body').css('width','80%');
						$(self.options.container).addClass('wid20p');
					}
					$(self.options.container).css('height', getHeight());
                    if(readCookie("chatAuth") == "true"){
                        console.log("checking cookie");
                        loginChat();
                    }
				}
				catch(e)
				{
					console.log('init error'+ e)
				}
            }
        };

        // attach the plugin to jquery namespace
        $.fn.chatplugin = function(options) {
			var PubObj = $(this).data('chatplugin');
            this.each(function() {
                // prevent multiple instantiation
                if (!$(this).data('chatplugin')) {
					 PubObj = new ChatPlugin(this, options);
                    $(this).data('chatplugin', PubObj);
				}
            });
			
			return PubObj;
        };
		
		$.fn.setLoginCallback = function(callback) {
			$(this).data('chatplugin').options.onLogin = callback;
			console.log("in chat");
			console.log(callback);
		}

		$.fn.getChatPluginOption = function(key) {
			return $(this).data('chatplugin').options[key];
		}
		$.fn.setChatPluginOption = function(key,value) {
			$(this).data('chatplugin').options[key] = value;
		}
    } 
	catch (e) 
	{
        console.log('the err2:' + e)
    }




})(jQuery);