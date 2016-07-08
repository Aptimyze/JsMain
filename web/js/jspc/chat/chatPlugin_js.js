"use strict";

var JsChat = function() {
    this._construct(this, arguments);
};
//start:prototype
JsChat.prototype = {

    _mainID: "#chatOpenPanel",
    _parendID: ".js-openOutPanel",
    _loginbtnID: "#js-chatLogin",
    _loginPanelID: '#js-loginPanel',
    _minPanelId: ".js-minpanel",
    _listingPanelID: "#js-lsitingPanel",
    _chatBottomPanelID: '#chatBottomPanel',
    _minChatBarOut: '.js-minChatBarOut',
    _maxChatBarOut: '.js-minpanel',
    _tabclass: 'ul#TabData li',
    _toggleLogoutDiv: '.js-LogoutPanel',
    _toggleID: '#js-chattopH',
    _logoutChat: '.jschatLogOut',
    _minChatBarIn: '.js-minChatBarIn',
    _scrollDivId: '#nchatDivs',
    _listingClass: 'ul.chatlist',
    _listingTabs:{},

    _construct: function() {

        if (arguments[1][0].loginStatus)
            this._loginStatus = arguments[1][0].loginStatus;
        //configurable main div
        if (arguments[1][0].mainID)
            this._mainID = arguments[1][0].mainID;
        if (arguments[1][0].listData)
            this._listData = arguments[1][0].listData;
        if (arguments[1][0].listingTabs)
            this._listingTabs = arguments[1][0].listingTabs;
    },
    //start:get screen height
    _getHeight: function() {
        return ($(window).height());
    },
    //start:the check width function
    _checkWidth: function() {
        if ($(window).width() < 1254) {
            return true
        } else {
            return false
        }
    },
    //start:check login status
    checkLoginStatus: function() {
        console.log('check status');
        if (this._loginStatus == "Y") {
            return true;
        } else {
            return false;
        }
    },
    //start:maximize html
    _maximizeChatPanel: function() {
		var curEle = this;
        console.log('in max');
        console.log($(this._maxChatBarOut));
        $(this._maxChatBarOut).fadeOut('slow', function() {
            $(this).remove();
        });
        if (this._checkWidth()) {
            console.log('screen size less than 1024');
        } else {
            $("body").animate({
                width: '80%'
            }, {
                duration: 400,
                queue: false
            });
            $(this._parendID).animate({
                right: '0'
            }, {
                duration: 400,
                queue: false
            });
			setTimeout(function(){$(curEle._chatBottomPanelID).show(); }, 400);		
        }
    },
    //start:minimize html
    minimizedPanelHTML: function() {
        console.log('min html');
        var minChatPanel = '<div class="nchatbg1 nchatw2 nchatp6 pos_fix colrw nchatmax js-minpanel cursp"><ul class="nchatHor clearfix f13 fontreg"> <li>      <div class="pt5 pr10">ONLINE MATCHES</div></li><li><div class="bg_pink disp-tbl txtc nchatb"><div class="vmid disp-cell">2</div></div></li><li class="pl10"> <i class="nchatspr nchatopen"></i> </li></ul></div>';
        $(this._mainID).append(minChatPanel);
    },
    //start:minimize html
    _minimizeChatOutPanel: function() {
        var curEle = this;
		$(curEle._chatBottomPanelID).hide();
        if (this._checkWidth()) {

        } else {
            $(this._parendID).animate({
                right: '-100%'
            }, 1000);
            $("body").animate({
                "width": "100%",
            }, {
                duration: 300,
                complete: this.minimizedPanelHTML()
            });
        }
        $(this._maxChatBarOut).click(function() {
            curEle._maximizeChatPanel();
        });
    },
    //start:chat tabs click
    _chatTabs: function(param) {
        $('ul.nchattab1 li').removeClass('active');
        $('#' + param).addClass('active');
        $('.js-htab').fadeOut('slow').promise().done(function() {
            $('.show' + param).fadeIn('slow')
        });
    },
    
    onLogoutPreClick: null,
    
    //start:log out from chat
    logOutChat: function() {
        var curEleRef = this;
        $(curEleRef._toggleID).toggleClass('disp-none');
        console.log("In logout Chat");
        console.log(curEleRef._loginStatus);
        if (curEleRef._loginStatus == 'N') {
            $(curEleRef._listingPanelID).fadeOut('slow', function() {
                if($(this._loginPanelID).length == 0){
                    console.log("Length is 0 of login panel");
                    curEleRef.addLoginHTML();
                }
                else{
                    $(curEleRef._loginPanelID).fadeIn('slow');   
                }
            });
        } else {
            $(curEleRef._listingPanelID).fadeOut('slow', function() {
                curEleRef.addLoginHTML();
            });
        }
    },
    //start:addChatTop function
    _addChatTop: function(param) {
        var curEleRef = this;
        var chatHeaderHTML = '<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="images/chat-profile-small.jpg" class="nchatp4"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>';
        $(curEleRef._listingPanelID).append(chatHeaderHTML);


        $(curEleRef._toggleLogoutDiv).off("click").on("click",function() {
            console.log($(this));
            $(curEleRef._toggleID).toggleClass('disp-none');
        });

        $(curEleRef._logoutChat).click(function() {
            if( curEleRef.onLogoutPreClick  && typeof (curEleRef.onLogoutPreClick) == "function" ){
                console.log("in if");
                curEleRef.onLogoutPreClick();
            }
            curEleRef.logOutChat();
        });

        $(curEleRef._minChatBarIn).click(function() {

            $(curEleRef._minimizeChatOutPanel());
        });



    },
    //start:set height for the listing scroll div
    _chatScrollHght: function() {
        console.log('cal scroll div');
        var totalHgt = this._getHeight();
        var remHgt = parseInt(totalHgt) - 140;
        console.log(remHgt);
        console.log(this._scrollDivId);
        $(this._scrollDivId).css('height', remHgt);
    },
    //start:add tab
    addTab: function() {

        //this script is same as old one shared eariler need to be reworked as discussed
        console.log('in tab');
        var obj = this._listingTabs;
        var curEle = this;
        var TabID;
        var TabsOpt = '<div class="clearfix"><ul class="nchattab1 clearfix fontreg" id="TabData">';
        for (var key in obj) {
            TabID = key;
            if (!obj.hasOwnProperty(key)) continue;
            var objin = obj[key];
            TabsOpt += "<li id=\"" + TabID + "\" class=\"pos-rel ";
            if (TabID == 'tab1') {
                TabsOpt += "active\">";
            } else {
                TabsOpt += "\">";
            }
            TabsOpt += "<p>" + objin["tab_name"] + "</p><div class=\"showlinec\"></div></li>";
        }
        TabsOpt += '</ul></div><div id="nchatDivs" class="nchatscrollDiv">';
        TabsOpt += '<div class="showtab1 js-htab"> ';
        for (var i = 0; i < obj["tab1"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab1"]["groups"][i]["group_name"] + "</p></div>";
            TabsOpt += "<ul class=\"chatlist\"></ul></div>";

        }
        TabsOpt += '</div>';
        TabsOpt += '<div class="showtab2 js-htab disp-none">';
        for (var i = 0; i < obj["tab2"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab2"]["groups"][i]["group_name"] + "</p></div>";
            TabsOpt += "<ul class=\"chatlist\"></ul></div>";

        }
        TabsOpt += '</div>';
        TabsOpt += '</div>';
        $(this._listingPanelID).append(TabsOpt);

        $(this._tabclass).click(function() {

            curEle._chatTabs($(this).attr('id'));
        })
    },
    //start:addlisting
    addListingInit: function(data) {
        console.log("in addListingInit");
        console.log(data);
        var elem = this;
        for (var key in data) {
            var runID = '';
            var res = '';
            runID = data[key]["rosterDetails"]["jid"];
            var res = runID.split("@");
            runID = res[0];
            $.each(data[key]["rosterDetails"]["groups"], function(index, val) {
                var List = '',status = data[key]["rosterDetails"]["chat_status"],username = data[key]["rosterDetails"]["fullname"];
                List += '<li class=\"clearfix profileIcon\"';
                List += "id=\"" + (runID + val) + "\" >";
                List += "<img id=\"pic_" + runID + "_" +val + "\" src=\"images/pic1.jpg\" class=\"fl\">";
                List += '<div class="fl f14 fontlig pt15 pl18">';
                List += data[key]["rosterDetails"]["fullname"];
                List += '</div>';
                List += '</li>';
                $('div.' + val + ' ul').append(List);
				//bind click on listing]
                $("#" + username + val).on("click", function() {
                    elem._chatPanelsBox(username,status);
                });
            });
        }
        console.log("setting mCustomScrollbar");
        elem._chatScrollHght();
        $(elem._scrollDivId).mCustomScrollbar({
            theme: "light"
        });
    },

    //scrolling down chat box
    _scrollDown: function(elem, removeBorder) {
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
                elem.find(".onlineStatus").hide();
				if(elem.find(".pinkBubble2 span").html() != 0) {
					elem.find(".pinkBubble2").show();
				}
                elem.find(".chatBoxBar").addClass("cursp");
                elem.find(".downBarPic").addClass("downBarPicMin");
                elem.find(".downBarUserName").addClass("downBarUserNameMin");
            });
        }
    },

    //adjusting text area on input by user
    _textAreaAdjust: function(o) {
        o.style.height = "1px";
        o.style.height = (o.scrollHeight - 16) + "px";
        var height = 250 - (o.scrollHeight - 44);
        if (height > 195) {
            $(o).closest("div").parent().find(".chatMessage").css("height", height);
        } else {
            $(o).css("overflow", "auto");
        }
    },

    //scrolling up chat box
    _scrollUp: function(elem) {
        elem.animate({
            bottom: "0px"
        }, function() {
            $(elem.find(".nchatic_2")[0]).show();
            $(elem.find(".nchatic_3")[0]).show();
            elem.find(".onlineStatus").show();
            elem.find(".pinkBubble2").hide();
			elem.find(".pinkBubble2 span").html("0");
            elem.find(".chatBoxBar").removeClass("cursp");
            elem.find(".downBarPic").removeClass("downBarPicMin");
            elem.find(".downBarUserName").removeClass("downBarUserNameMin");
        });
    },

    //bind clicking minimize icon
    _bindMinimize: function(elem) {
        var curElem = this;
        $(elem).off("click").on("click", function(e) {
            e.stopPropagation();
            curElem._scrollDown($(this).closest("chat-box"), false);
        });
    },

    //bind clicking maximize chat box
    _bindMaximize: function(elem, userId) {
        var curElem = this;
        $(elem).off("click").on("click", function() {
            curElem._scrollDown($(".extraPopup"), false);
            setTimeout(function() {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
            curElem._scrollUp($('chat-box[user-id="' + userId + '"]'));
        });
    },

    //bind clicking close icon
    _bindClose: function(elem) {
        var curElem = this;
        $(elem).off("click").on("click", function() {
            curElem._scrollDown($(this).closest("chat-box"), true);
            if ($(".extraNumber")) {
                var value = parseInt($(".extraNumber").text().split("+")[1]);
                var bodyWidth = $("body").width();
                var divWidth = ($("chat-box").length - 1) * 250;
                if (value == 1 && divWidth < bodyWidth) {
                    $(".extraChats, .extraPopup").remove();
                } else if (value > 1) {
                    $(".extraNumber").text("+" + (value - 1));
                }
            }
        });
    },

    //bind clicking block icon
    _bindBlock: function(elem) {
        var curElem = this;
        $(elem).off("click").on("click", function() {
            curElem._scrollDown($(this).closest("chat-box"), true);
            if ($(".extraNumber")) {
                var value = parseInt($(".extraNumber").text().split("+")[1]);
                var bodyWidth = $("body").width();
                var divWidth = ($("chat-box").length - 1) * 250;
                if (value == 1 && divWidth < bodyWidth) {
                    $(".extraChats, .extraPopup").remove();
                } else if (value > 1) {
                    $(".extraNumber").text("+" + (value - 1));
                }
            }
        });
    },

    //sending chat
    _bindSendChat: function(userId) {
        $('chat-box[user-id="' + userId + '"] textarea').keyup(function(e) {
            if (e.keyCode == 13 && !e.shiftKey) {
                var text = $(this).val();
                $(this).val("").css("height", "24px");
                if (text.length > 1) {
                    var superParent = $(this).parent().parent();
					console.log($(superParent).find(".talkText").length);
                    $(superParent).find(".chatMessage").css("height", "250px").append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id="text_'+userId+'_'+($(superParent).find(".talkText").length+1)+'" class="talkText">' + text + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
                    var len = $(superParent).find(".talkText").length,height = $($(superParent).find(".talkText")[len - 1]).height();
                    $($(superParent).find(".talkText")[len - 1]).next().css("margin-top", height);
                    var scrollBox = $('chat-box[user-id="' + userId + '"] .chatMessage');
                    var height = ($(".rightBubble").length + $(".leftBubble").length) * 40;
                    $(scrollBox).animate({
                        scrollTop: height
                    }, 500);
                }
            }
        });
    },

    //binding click on extra popup username listing
    _bindExtraUserNameBox: function() {
        var curElem = this;
        $('body').on('click', '.extraUsername', function() {
            curElem._scrollDown($(".extraPopup"), false);
            setTimeout(function() {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1],originalElem = $('chat-box[user-id="' + username + '"]'),status = $("chat-box[user-id='" + username + "'] .chatBoxBar .onlineStatus").html(),chatHtml = $(originalElem).find(".chatMessage").html();
            curElem._appendChatBox(username,status);
            $(originalElem).remove();
			$("chat-box[user-id='" + username + "'] .chatMessage").html(chatHtml);
            $(this).closest(".extraChatList").remove();
            setTimeout(function() {
                curElem._scrollUp($('chat-box[user-id="' + username + '"]'));
            }, 700);

            //adding data in extra popup 
            var len = $("chat-box").length,
                value = parseInt($(".extraNumber").text().split("+")[1]),
                finalVar = len - 1 - (value - 1),
                data = $($("chat-box")[finalVar]).attr("user-id"),
                dataAdded = false;
            $(".extraChatList").each(function(index, element) {
                var id = $(this).attr("id").split("_")[1];
                if (id == data) {
                    dataAdded = true;
                }
            });
            if (dataAdded == false) {
                curElem._addDataExtraPopup(data);
                curElem._bindExtraPopupUserClose($("#extra_" + data + " .nchatic_4"));
            }
        });
    },

    //binding close button on extra popup username listing
    _bindExtraPopupUserClose: function(elem) {
        $(elem).off("click").on("click", function() {
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1];
            $('chat-box[user-id="' + username + '"]').remove();
            $(this).closest(".extraChatList").remove();
            var value = parseInt($(".extraNumber").text().split("+")[1]);
            if (value == 1) {
                $(".extraChats , .extraPopup").remove();
            } else {
                $(".extraNumber").text("+" + (value - 1));
            }
        });
    },

    //adding data in extra popup
    _addDataExtraPopup: function(data) {
        $(".extraPopup").append('<div id="extra_' + data + '" class="extraChatList pad8_new"><div class="extraUsername cursp colrw minWid65 disp_ib pad8_new fontlig f14">' + data + '</div><div class="pinkBubble vertM scir disp_ib padall-10"><span class="noOfMessg f13 pos-abs">1</span></div><i class="nchatspr nchatic_4 cursp disp_ib mt6 ml10"></i></div>');
		$("#extra_"+data+" .pinkBubble span").html($('chat-box[user-id="' + data + '"] .chatBoxBar .pinkBubble2 span').html());	
		if($("#extra_"+data+" .pinkBubble span").html() ==0) {
			$("#extra_"+data+" .pinkBubble").hide();
		}
    },

    //append chat box on page
    _appendChatBox: function(userId,status) {
        $("#chatBottomPanel").prepend('<chat-box status-user="'+status+'" user-id="' + userId + '"></chat-box>');
    },

    //create side panel of extra chat
    _createChatBox: function() {
        var curElem = this;
        $(curElem._chatBottomPanelID).append('<div class="extraChats pos_abs nchatbtmNegtaive wid30 hgt43 bg5"><div class="extraNumber cursp colrw opa50">+1</div><div><div class="extraPopup pos_abs l0 nchatbtmNegtaive wid153 bg5"><div>');
        $(".extraChats").css("left", $(curElem._chatBottomPanelID).width() - $('chat-box').length * 250 - 32);
        curElem._scrollUp($(".extraChats"));
        //adding data in extra popup 
        var len = $("chat-box").length - 1,
            data = $($("chat-box")[len]).attr("user-id");
        this._addDataExtraPopup(data);
        //binding extra chat small icon click to view popup
        $(".extraNumber").off("click").on("click", function() {
            var len = $("chat-box").length,
                value = parseInt($(".extraNumber").text().split("+")[1]),
                position = len - value - 1;
            curElem._scrollDown($($('chat-box')[position]), false);
            $(".extraPopup").animate({
                bottom: "48px"
            });
            setTimeout(function() {
                $(".extraChats").css("padding-top", "11px");
            }, 300);
        });
    },

    //appending chat box
    _chatPanelsBox: function(userId,status) {
        var curElem = this,
            heightPlus = false,
            bodyWidth = $("body").width();
        if ($(curElem._chatBottomPanelID).length == 0) {
            $("body").append("<div id='chatBottomPanel' class='btmNegtaive pos_fix calhgt2 nz20 fontlig'></div>");
        }
        var bottomPanelWidth = $(window).width() - $(curElem._parendID).width();
        $(curElem._chatBottomPanelID).css('width', bottomPanelWidth);
        if ($(curElem._chatBottomPanelID).css("bottom") == "-300px") {
            $(curElem._chatBottomPanelID).css("bottom", "0px");
        }

        if ($('chat-box[user-id="' + userId + '"]').length == 0) {
            var bodyWidth = $(curElem._chatBottomPanelID).width(),
                divWidth = ($("chat-box").length + 1) * 250;
            if (divWidth > bodyWidth) {
                if ($(".extraChats").length == 0) {
                    curElem._createChatBox();
                } else {
                    var value = parseInt($(".extraNumber").text().split("+")[1]) + 1,
                        len = $("chat-box").length + 1,
                        data = $($("chat-box")[len - value - 1]).attr("user-id");
                    curElem._addDataExtraPopup(data);
                    $(".extraNumber").text("+" + value);
                }
                curElem._bindExtraPopupUserClose($(".nchatic_4"));
                curElem._bindExtraUserNameBox();
            }
            curElem._appendChatBox(userId,status);

        } else {
            $(".extraChatList").each(function(index, element) {
                var id = $(this).attr("id").split("_")[1];
                if (id == userId) {
                    curElem._scrollDown($(".extraPopup"), false);
                    setTimeout(function() {
                        $(".extraChats").css("padding-top", "0px");
                    }, 100);
                    var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                        originalElem = $('chat-box[user-id="' + username + '"]'),
                        len = $("chat-box").length,
                        value = parseInt($(".extraNumber").text().split("+")[1]),
                        data = $($("chat-box")[len - 1 - value]).attr("user-id"),chatHtml = $(originalElem).find(".chatMessage").html();
                    curElem._appendChatBox(username,status);
                    originalElem.remove();
					$("chat-box[user-id='" + username + "'] .chatMessage").html(chatHtml);
                    $(this).closest(".extraChatList").remove();
                    curElem._addDataExtraPopup(data);
                    curElem._bindExtraPopupUserClose($("#extra_" + data + " .nchatic_4"));
                }
            });
        }
        if ($(".extraChats").length > 0 && $(".extraPopup ").css("bottom") != "-300px") {
            curElem._scrollDown($(".extraPopup "), false);
            setTimeout(function() {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
        }
    },

    //creating prototype for chat-box custom element
    _createPrototypeChatBox: function() {
        var elem = this,
            chatBoxProto = Object.create(HTMLElement.prototype),
            userId,status;
        chatBoxProto.attachedCallback = function() {
            this.innerHTML = '<div class="chatBoxBar fullwid hgt57 bg5 pos-rel fullwid"></div><div class="chatArea fullwid fullhgt"><div class="messageArea f13 bg13 fullhgt"><div class="chatMessage scrolla pos_abs fullwid" style="height: 250px;"></div></div><div class="chatInput brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" style="width: 220px;" id="txtArea"  class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Write message"></textarea></div></div>';
            $(this).addClass("z1000 btm0 brd_new fr mr7 fullhgt wid240 pos-rel disp_ib");
            userId = $(this).attr("user-id");
			status = $(this).attr("status-user");
            elem._bindChatBoxInner(userId,status);
        };
        document.registerElement("chat-box", {
            prototype: chatBoxProto
        });
        document.createElement("chat-box");
    },

    //binding after creating chatbox
    _bindChatBoxInner: function(userId,status) {
        var curElem = this,
            imgId;
        $("#nchatDivs img").each(function(index, element) {
            if (userId == $(element).attr("id").split("_")[1]) {
                imgId = $(element).attr("id");
            }
        });
        $("#" + imgId).clone().appendTo($('chat-box[user-id="' + userId + '"] .chatBoxBar'));
        $('chat-box[user-id="' + userId + '"] .chatBoxBar img').attr("id", "pic_" + imgId.split("_")[1]);
        $('chat-box[user-id="' + userId + '"] #txtArea').on("keyup", function() {
            curElem._textAreaAdjust(this);
        });
        $('chat-box[user-id="' + userId + '"] #pic_' + userId).addClass("downBarPic cursp");
        $('chat-box[user-id="' + userId + '"] .chatBoxBar').append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig">' + userId + '<div class="onlineStatus f11 opa50 mt4"></div></div></div>');
        $('chat-box[user-id="' + userId + '"] .chatBoxBar .downBarText').append('<div class="iconBar cursp fr padallf_2 disp_ib opa40"><i class="nchatspr nchatic_3"></i><i class="nchatspr nchatic_2 ml10 mr10"></i><i class="nchatspr nchatic_1 mr10"></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">0</span></div>');
        $('chat-box[user-id="' + userId + '"] .pinkBubble2').hide();
		$('chat-box[user-id="' + userId + '"] .pinkBubble2 span').html("0");
		$("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(status);
        curElem._bindMaximize($('chat-box[user-id="' + userId + '"] .chatBoxBar'), userId);
        curElem._bindMinimize($('chat-box[user-id="' + userId + '"] .nchatic_2'));
        curElem._bindClose($('chat-box[user-id="' + userId + '"] .nchatic_1'));
        curElem._bindBlock($('chat-box[user-id="' + userId + '"] .nchatic_3'));
        curElem._bindSendChat(userId);
		/*setTimeout(function(){  curElem._appendRecievedMessage("hi this is recieved",userId);
		setTimeout(function(){  curElem._changeStatusOfMessg(1,userId,"recieved"); 
		setTimeout(function(){  curElem._changeStatusOfMessg(1,userId,"recievedRead"); 
		}, 2000);
		}, 2000);
		 }, 7000);*/
    },
	
	//add meesage recieved from another user
	_appendRecievedMessage: function(message, userId){
		var val,len = $('chat-box[user-id="' + userId + '"] .talkText').length+1;
		//adding message in chat area
		$('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="leftBubble"><div class="tri-left"></div><div class="tri-left2"></div><div id="text_'+userId+'_'+len+'" class="talkText">'+message+'</div></div>');
		//adding bubble for minimized tab
		if($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
			val = parseInt($('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html())+1;
			$('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html(val);
			$('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2').show();
		}
		//adding bubble for side tab
		if($("#extra_"+userId+" .pinkBubble").length != 0){
				val =  parseInt($("#extra_"+userId+" .pinkBubble span").html());
				$("#extra_"+userId+" .pinkBubble span").html(val+1);
				$("#extra_"+userId+" .pinkBubble").show();
		}
	},
	
	_changeStatusOfMessg:function(messgNo, userId, newStatus){
		if(newStatus == "recieved"){
			$("#text_"+userId+"_"+messgNo).next().removeClass("nchatic_8").addClass("nchatic_10");
		} else if(newStatus == "recievedRead"){
			$("#text_"+userId+"_"+messgNo).next().removeClass("nchatic_10").addClass("nchatic_9");
		}
	},
	
	onEnterToChatPreClick:null,

    //start:login screen
    _startLoginHTML: function() {
        

        console.log('_startLoginHTML call');
        var curEle = this;
        //user not logged in and coming for first time
        if (($(this._listingPanelID).length == 0) && (this._loginStatus == "N")) {
            console.log('case 1');
            $(curEle._loginPanelID).fadeOut('slow', function() {
                curEle._appendLoggedHTML();
            });
        }
        //user was logged earlier in which login is not call'd
        else if (($(this._listingPanelID).length == 0) && (this._loginStatus == "Y")) {
            console.log('case 2');
            if($(curEle._loginPanelID).length == 0 ){
                curEle._appendLoggedHTML();    
            }
            else{
                $(curEle._loginPanelID).fadeOut('slow', function() {
                    curEle._appendLoggedHTML();
                });
            }
        }
        //user logged out from chat in the same session
        else {
            console.log('case 3');
            $(curEle._loginPanelID).fadeOut('slow', function() {
                $(curEle._listingPanelID).fadeIn('slow');
            });
        }
        console.log("Login status value");
        console.log(this._loginStatus);
        /*
        if((this._loginStatus == "Y")){
             $(curEle._loginPanelID).fadeOut('slow', function() {
                curEle._appendLoggedHTML();
            });
        }
        */

    },
    //start:append Chat Logged in Panel
    _appendLoggedHTML: function() {
        var curEle = this;
        console.log('_appendLoggedHTML');
        $(curEle._parendID).append('<div class="fullwid fontlig nchatcolor" id="js-lsitingPanel"/> ').promise().done(function() {
            curEle._addChatTop();
            curEle.addTab();
            //first time intialization passing tab1 as param
            //curEle.addListingInit(curEle._listData);
        });
        
        //call hover functionality
       // $(curEle._listingClass).

    },
    
   //start:this function image,name in top chat logged in scenario
    addLoginHTML: function() {
        console.log('in addLoginHTML');
        var curEle = this;
        var LoginHTML = '<div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarOut"></i> </div><div> <img src="images/chat-profile-pic.jpg" class="chatmt1"/> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Login to Chat</button></div>';
        $(this._parendID).append(LoginHTML);
        $('.js-minChatBarOut').click(function() {

            curEle._minimizeChatOutPanel();
        });
        //start login button capture
        $(this._loginbtnID).click(function() {
            if( curEle.onEnterToChatPreClick  && typeof (curEle.onEnterToChatPreClick) == "function" ){
                console.log("in if");
                curEle.onEnterToChatPreClick();
            }
            if(curEle._loginStatus == "Y"){
                curEle._startLoginHTML();    
            } 
        });
    },
    //start:this function is that init forthe chat
    start: function() {
        var divElement = document.createElement("Div");
        $(divElement).addClass('pos_fix chatbg chatpos1 nz20 js-openOutPanel').appendTo(this._mainID);
		this._createPrototypeChatBox();
        if (this._checkWidth()) {

        } else {
            $('body').css('width', '80%');
            $(this._parendID).addClass('wid20p').css('height', this._getHeight());
        }
        if (this.checkLoginStatus()) {
            this._startLoginHTML();
        } else {
            this.addLoginHTML();
            console.log("in start function");
        }


    },

};
//end:prototype

//Added in chatPCHelper_js file
/*
var objJsChat = new JsChat({
    loginStatus: "N",
    profilePhoto: "<path>",
    profileName: "CYZ3546",
    listData: [

        {
            "rosterDetails": {
                "chat_status": "offline",
                "fullname": "a1",
                "Groups": ["eoi_R", "shortlisted", ],
                "id": "a1@localhost",
                "jid": "a1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "a1@gmail.com"
                },
                "NAME": {
                    "#text": "a1"
                }
            }
        }, {
            "rosterDetails": {
                "chat_status": "online",
                "fullname": "b1",
                "Groups": ["shortlisted", "accepted_by_me"],
                "id": "b1@localhost",
                "jid": "b1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "b1@gmail.com"
                },
                "NAME": {
                    "#text": "b1"
                }
            }
        }, {
            "rosterDetails": {
                "chat_status": "offline",
                "fullname": "c1",
                "Groups": ["dpp", "accepted_by_me"],
                "id": "c1@localhost",
                "jid": "c1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "c1@gmail.com"
                },
                "NAME": {
                    "#text": "c1"
                }
            }
        }, {
            "rosterDetails": {
                "chat_status": "online",
                "fullname": "d1",
                "Groups": ["dpp"],
                "id": "d1@localhost",
                "jid": "d1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "d1@gmail.com"
                },
                "NAME": {
                    "#text": "d1"
                }
            }
        }, {
            "rosterDetails": {
                "chat_status": "offline",
                "fullname": "e1",
                "Groups": ["eoi_R", "accepted_by_me"],
                "id": "e1@localhost",
                "jid": "e1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "e1@gmail.com"
                },
                "NAME": {
                    "#text": "e1"
                }
            }
        }, {
            "rosterDetails": {
                "chat_status": "offline",
                "fullname": "f1",
                "Groups": ["eoi_R", "accepted_by_me"],
                "id": "f1@localhost",
                "jid": "f1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "f1@gmail.com"
                },
                "NAME": {
                    "#text": "f1"
                }
            }
        }, {
            "rosterDetails": {
                "chat_status": "offline",
                "fullname": "g1",
                "Groups": ["eoi_R", "accepted_by_me"],
                "id": "g1@localhost",
                "jid": "g1@localhost"
            },
            "vcardDetails": {
                "EMAIL": {
                    "#text": "g1@gmail.com"
                },
                "NAME": {
                    "#text": "g1"
                }
            }
        },




    ]

    ,
});

objJsChat.start();

*/
//objJsChat.addListingInit();


/*var i =0;
	setInterval(function(){ 
		i++;
		var data= [
					{
						"rosterDetails": {
							"chat_status":"offline",
							"fullname":"fg"+i,
							"Groups":["dpp"],
							"id":"fg@localhost",
							"jid":"fg"+i+"@localhost"
						},
						"vcardDetails":{
						"EMAIL":{
						"#text":"fg@gmail.com"},
						"NAME":{
						"#text":"fg"}
						}
					}
					];
			objJsChat.addListingInit(data); 


	}, 3000);*/