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
    _listingClass: 'ul.chatlist li',
    _imageUrl: '',
    _listingTabs:{},
    _loginFailueMsg:"Login Failed,Try later",
    _noDataTabMsg:{"tab1":"There are no matching members online. Please relax your partner preference to see more matches.",
                   "tab2":"You currently don’t have any accepted members, get started by sending interests or initiating chat with your matches."
                   },
    _rosterDetailsKey:"rosterDetails",
    _listingNodesLimit:{},

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
        if (arguments[1][0].loginFailueMsg)
            this._loginFailueMsg = arguments[1][0].loginFailueMsg;
        if (arguments[1][0].noDataTabMsg)
            this._noDataTabMsg = arguments[1][0].noDataTabMsg;
        if (arguments[1][0].rosterDetailsKey)
            this._rosterDetailsKey = arguments[1][0].rosterDetailsKey;
        if (arguments[1][0].listingNodesLimit)
            this._listingNodesLimit = arguments[1][0].listingNodesLimit;
        if (arguments[1][0].imageUrl)
            this._imageUrl = arguments[1][0].imageUrl;
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
                if($(curEleRef._loginPanelID).length == 0){
                    console.log("Length is 0 of login panel");
                    curEleRef.addLoginHTML();
                }
                else{
                    $(curEleRef._loginPanelID).fadeIn('slow',function(){
                        $(curEleRef._listingPanelID).remove();
                    });   
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
        var chatHeaderHTML = '<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="'+this._imageUrl+'" class="nchatp4 wd40"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>';
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
        console.log('in addTab');
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
        TabsOpt += '</ul></div><div id="nchatDivs" class="nchatscrollDiv"><div id="scrollDivLoader" class="spinner"></div>';
        TabsOpt += '<div class="showtab1 js-htab" id="tab1"> <div id="showtab1NoResult" class="noResult f13 fontreg disp-none">'+curEle._noDataTabMsg["tab1"]+'</div>';
        for (var i = 0; i < obj["tab1"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + " disp-none chatListing\" data-showuser=\""+ obj["tab1"]["groups"][i]["hide_offline_users"]   +"\">";
            //TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2";
            if(obj["tab1"]["groups"][i]["show_group_name"]==false)
                TabsOpt += " disp-none";
            TabsOpt +="\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab1"]["groups"][i]["group_name"] + "</p></div>";
            TabsOpt += "<ul class=\"chatlist\"></ul></div>";

        }
        TabsOpt += '</div>';
        TabsOpt += '<div class="showtab2 js-htab disp-none" id="tab2"> <div id="showtab2NoResult" class="noResult f13 fontreg disp-none">'+curEle._noDataTabMsg["tab2"]+'</div>';
        for (var i = 0; i < obj["tab2"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\" data-showuser=\""+ obj["tab2"]["groups"][i]["hide_offline_users"]   +"\">";
            //TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2";
            if(obj["tab2"]["groups"][i]["show_group_name"]==false)
                TabsOpt += " disp-none";
            TabsOpt +="\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab2"]["groups"][i]["group_name"] + "</p></div>";
            TabsOpt += "<ul class=\"chatlist\"></ul></div>";

        }
        TabsOpt += '</div>';
        TabsOpt += '</div>';
        $(this._listingPanelID).append(TabsOpt);

        $(this._tabclass).click(function() {

            curEle._chatTabs($(this).attr('id'));
        })
    },

    noResultError:function(){
        var dataLength;
        $(".js-htab").each(function(index, element) {
            dataLength = 0;
            $(this).find(".chatlist").each(function(index2, element2) {
                console.log($(this).find("li").length);
                dataLength = dataLength + $(this).find("li").length;
            });
            if(dataLength == 0){
                console.log(element);
                $(element).find(".noResult").removeClass("disp-none").addClass("disp_ib");
                $(element).find(".chatListing").each(function(index, element) {
                       $(this).addClass("disp-none");
                   });
            }
        });
    },
    //start:addlisting
   /*addListingInit: function(data) {
        console.log("in addListingInit");
        var elem = this;
        var mainIndex = 0;
        var statusObj = [];
        for (var key in data) {
        var runID = data[key]["attributes"]["jid"],
        res = '',
        status = data[key]["chat_status"],
        username = data[key]["attributes"]["name"];
        statusObj[username]=data[key]["chat_status"];
        console.log(runID + " is now " + status);
        res = runID.split("@");
        runID = res[0];
        $.each(data[key]["group"], function(index, val) {
        var List = '',
           tabShowStatus = $('div.' + val).attr('data-showuser');
        List += '<li class=\"clearfix profileIcon\"';
        List += "id=\"" + runID + "_" + val + "\" >";
        List += "<img id=\"pic_" + runID + "_" + val + "\" src=\"images/pic1.jpg\" class=\"fl\">";
        List += '<div class="fl f14 fontlig pt15 pl18">';
        List += data[key]["attributes"]["name"];
        List += '</div>';
        //console.log(runID + " is in list " + val);
        if (status == "online") {
           List += '<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>';
        }
        List += '</li>';
        var addNode = false;
        if (tabShowStatus == 'false') {
           addNode = true;
        } else {
           if (status == 'online') {
               addNode = true;
           }
        }
        if (addNode == true) {
           if ($('#' + runID + "_" + val).length == 0) {
               if ($('#' + runID + "_" + val).find('.nchatspr').length == 0) {
                   $('div.' + val + ' ul').append(List);
                   $("#" + username + "_" + val).on("click", function() {
        var id = $(this).attr("id").split("_")[0];
                       elem._chatPanelsBox(id, statusObj[id]);
                   });
               }
           } else {
               $(elem._mainID).find($('#' + runID + "_" + val)).append('<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>');
           }
        }
        });
        mainIndex++;
        }
        elem._chatScrollHght();
        $(elem._scrollDivId).mCustomScrollbar({
        theme: "light"
        });
   },*/

   //start:addlisting
    addListingInit: function(data) {
        var elem = this,statusArr=[],currentID;
        console.log("addListing");
        for (var key in data) 
        {
            if(typeof data[key]["rosterDetails"]["jid"] != "undefined")
            {
                var runID = data[key]["rosterDetails"]["jid"],res = '',status = data[key]["rosterDetails"]["chat_status"];
                console.log("addlisting for "+runID+"--"+data[key]["rosterDetails"]["chat_status"]);
                var fullJID = runID;
                res = runID.split("@");
                runID = res[0];
                statusArr[runID] = status;
                if(typeof data[key]["rosterDetails"]["groups"] != "undefined" && data[key]["rosterDetails"]["groups"].length >0)
    				$.each(data[key]["rosterDetails"]["groups"], function(index, val) {
                        console.log("groups "+val);
                        var List = '',fullname = data[key]["rosterDetails"]["fullname"],tabShowStatus = $('div.' + val).attr('data-showuser');
                        var getNamelbl = fullname,picurl=data[key]["rosterDetails"]["listing_tuple_photo"],prfCheckSum=data[key]["rosterDetails"]["profile_checksum"];  //ankita for image
                        List += '<li class=\"clearfix profileIcon\"';
                        List += "id=\"" + runID + "_"+val + "\" data-checks=\""+ prfCheckSum +"\" data-jid=\""+ fullJID+"\">";
                        List += "<img id=\"pic_" + runID + "_" +val + "\" src=\""+picurl+"\" class=\"fl\">";
                        List += '<div class="fl f14 fontlig pt15 pl18">';
                        List += getNamelbl;
                        List += '</div>';
                        if(status == "online"){
                            List += '<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>';
                        }
                        List += '</li>';
                        var addNode = false;
                        if(tabShowStatus == 'false'){
                            console.log(status+"2222");
                            addNode = true;
                        }
                        else{
                            console.log(status+"1111");
                            if(status == 'online'){
                               addNode = true;
                            }
                        }
                        console.log("addNode"+addNode);
                        if(addNode == true){
                            if($('#'+runID + "_" + val).length==0){
                                if($('#'+runID + "_" + val).find('.nchatspr').length==0){
                                    console.log("checking no of nodes in group "+$('div.' + val + ' ul li').size());
                                    if(typeof elem._listingNodesLimit[val] == "undefined" || $('div.' + val + ' ul li').size() <= elem._listingNodesLimit[val]){
                                        console.log("b2");
                                        var tabId = $('div.' + val).parent().attr("id");
                                        if($("#show"+tabId+"NoResult").length != 0){
                                            console.log("me");
                                            
                                            $("#show"+tabId+"NoResult").addClass("disp-none");
                                        }
                                        elem._placeContact("new",runID,val,status,List);
                                        if($('div.' + val + ' ul').parent().hasClass("disp-none")){
                                            $('div.' + val + ' ul').parent().removeClass("disp-none");
                                        }
                                        $("#" + runID+"_" + val).on("click", function() {
                                           currentID = $(this).attr("id").split("_")[0];
                                            console.log("manvi",statusArr[currentID],currentID);
                                            elem._chatPanelsBox(currentID,statusArr[currentID],$(this).attr("data-jid"));
                                        });
                                    } 
                                }
                 
                            }
                            else{
                                elem._placeContact("existing",runID,val,status);   
                            }
                            //elem._updateStatusInChatBox(runID,status); 
                        }
                    });
            }
        }
        elem._chatScrollHght();
        $(elem._scrollDivId).mCustomScrollbar({
            theme: "light"
        });
        //call hover functionality
        $(elem._listingClass).on('mouseenter mouseleave',{ global: elem }, elem._calltohover);
    },

    //place contact in appropriate position in listing
    _placeContact:function(key,contactID,groupID,status,contactHTML){
        if(key == "new"){
            console.log("ankita_adding"+contactID+" in groupID");
            console.log(contactHTML);
            /*if(status == "online")          //add new online element in start
                $('div.' + groupID + ' ul').prepend(contactHTML);
            else*/                         //add new offline element in end
            $('div.' + groupID + ' ul').append(contactHTML);
        }
        else if(key == "existing" && status == "online"){
            console.log("changing icon");
            //add online chat_status icon
            if($('#'+contactID + "_" + groupID).find('.nchatspr').length==0){
                $(this._mainID).find($('#'+contactID + "_" + groupID)).append('<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>');
            }
            //move this element to beginning of listing
            /*var html = $(elem._mainID).find($('#'+contactID + "_" + groupID)).html();
            $(elem._mainID).find($('#'+contactID + "_" + groupID)).remove();
            $('div.' + groupID + ' ul').prepend(html);*/
        }
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

    onPostBlockCallback:null,
    
    //remove from list
    _removeFromListing:function(param1,data){
        console.log('remove element 11');
        //removeCall1 if user is removed from backend
        if(param1=='removeCall1' || param1 == 'delete_node'){
            console.log("calllign _removeFromListing");
            for (var key in data){
                var runID = '';
                runID = data[key]["rosterDetails"]["jid"].split("@")[0];
                if(typeof data[key]["rosterDetails"]["groups"] != "undefined")
                {
                    $.each(data[key]["rosterDetails"]["groups"], function(index, val) {
                        var tabShowStatus='',listElements = '';
                        //this check the sub header status in the list
                        var tabShowStatus = $('div.' + val).attr('data-showuser');
                        listElements = $('#'+runID+'_'+val);
                        if(tabShowStatus=='false' && param1!= 'delete_node'){
                            console.log("123");
                           $(listElements).find('.nchatspr').detach();
                        }
                        else{
                            console.log("345");
                            $('div').find(listElements).detach();
                            if($('div.' + val + ' ul li').length == 0){
                                $('div.' + val + ' ul').parent().addClass("disp-none");
                            }
                        }
                        //this._updateStatusInChatBox(runID,data[key]["rosterDetails"]["chat_status"]);
                    });
                }
            }
        }
        //removeCall2 if user is removed from block click on chatbox
        else if(param1=='removeCall2')
        {
            $(this._mainID).find('*[id*="'+data+'"]').detach();               
            if( this.onPostBlockCallback && typeof this.onPostBlockCallback == 'function' )
            {
                this.onPostBlockCallback(data);  
            }
        }
        this.noResultError();
    },

    //bind clicking block icon
    _bindBlock: function(elem, userId) {
        var curElem = this;
        $(elem).off("click").on("click", function() {
            curElem._removeFromListing('removeCall2',userId);
            sessionStorage.setItem("htmlStr_" + userId, $('chat-box[user-id="' + userId + '"] .chatMessage').html());
            $('chat-box[user-id="' + userId + '"] .chatMessage').html('<div id="blockText" class="pos-rel wid90p txtc colorGrey padall-10">You have blocked this user</div><div class="pos-rel fullwid txtc mt20"><div id="undoBlock" class="padall-10 color5 disp_ib cursp">Undo</div></div>');
            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            curElem._bindUnblock(userId);
        });
    },

    _bindUnblock: function(userId) {
        $('chat-box[user-id="' + userId + '"] #undoBlock').off("click").on("click", function() {
            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            var htmlStr = sessionStorage.getItem("htmlStr_" + userId);
            $('chat-box[user-id="' + userId + '"] .chatMessage').html(htmlStr);
            //TODO: fire query for unblock
        });
    },

    onSendingMessage: null,

    //sending chat
    _bindSendChat: function(userId) {
        var _this = this,messageId;
        $('chat-box[user-id="' + userId + '"] textarea').keyup(function(e) {
            var curElem = this;
            if (e.keyCode == 13 && !e.shiftKey) {
                var text = $(this).val(),
                    textAreamElem = this;
                $(textAreamElem).val("").css("height", "24px");
                if (text.length > 1) {
                    var superParent = $(this).parent().parent(),
                        timeLog = new Date().getTime();
                    $(superParent).find("#sendInt,#initChatText,#sentDiv").remove();
                    $(superParent).find(".chatMessage").css("height", "250px").append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id ="tempText_' + userId + '_' + timeLog + '" class="talkText">' + text + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
                    var height = $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).height();
                    $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).next().css("margin-top", height);
                    $('chat-box[user-id="' + userId + '"] .chatMessage').animate({
                        scrollTop: $(".rightBubble").length + $(".leftBubble").length * 50
                    }, 500);
                    
                    //TODO: fire send chat query and return unique id, also check for 3 messages
                    if( _this.onSendingMessage  && typeof (_this.onSendingMessage) == "function" ){
                        console.log("in plugin send message");
                        console.log(text);
                        console.log($('chat-box[user-id="' + userId + '"]').attr("data-jid"));
                        messageId = _this.onSendingMessage(text,$('chat-box[user-id="' + userId + '"]').attr("data-jid"));
                    }
                    console.log("after");
                    //setTimeout(function() {
                        //on recieving data with uniqueID
                        //set single tick here
                        $("#tempText_" + userId + "_" + timeLog).attr("id", "text_" + userId + "_" + messageId);
                        _this._changeStatusOfMessg(messageId,userId,"recieved");
                        //scenario if 3 messages have been sent
                        var threeSent = false;
                        if (threeSent == true) {
                            $(curElem).prop("disabled", true);
                            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="restrictMessgTxt" class="color5 pos-rel fr txtc wid90p">You can send more message only if she replies</div>').addClass("restrictMessg2");
                        }
                    //}, 2000);
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
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                originalElem = $('chat-box[user-id="' + username + '"]'),
                status = $("chat-box[user-id='" + username + "'] .chatBoxBar .onlineStatus").html(),
                chatHtml = $(originalElem).find(".chatMessage").html(),jid = $('chat-box[user-id="' + username + '"]').attr("data-jid") ;
            curElem._appendChatBox(username, status,jid);
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
        $("#extra_" + data + " .pinkBubble span").html($('chat-box[user-id="' + data + '"] .chatBoxBar .pinkBubble2 span').html());
        if ($("#extra_" + data + " .pinkBubble span").html() == 0) {
            $("#extra_" + data + " .pinkBubble").hide();
        }
    },

    //append chat box on page
    _appendChatBox: function(userId, status,jid) {
        $("#chatBottomPanel").prepend('<chat-box data-jid="'+jid+'" status-user="' + status + '" user-id="' + userId + '"></chat-box>');
    },

    //create side panel of extra chat
    _createSideChatBox: function() {
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

    _postChatPanelsBox: function(userId) {
        var curElem = this;
        // TODO: fire query to get scenario and get message(if any)
        var response = "";
        // var response = "free_interest_pending";
        // var response = "free_interest_sent";
        // var response = "paid_interest_pending";
        // var response = "paid_interest_sent";
        //  var response = "pog_interest_pending";
        // var response = "pog_interest_accepted";
        // var response = "pog_interest_declined";	

        setTimeout(function() {
            switch (response) {
                case "free_interest_pending":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="sendInterest cursp sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_6 "></i><span class="vertTexBtm"> Send Interest</span></div><div id="sentDiv" class="sendDiv disp-none pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div><div class="pos-abs fullwid txtc colorGrey top120">Only paid members can start chat<div id="becomePaidMember" class="color5 cursp">Become a Paid Member</div></div>');
                    $('chat-box[user-id="' + userId + '"] #sendInt').on("click", function() {
                        curElem._sendInterest(userId);
                        $(this).parent().find("#sentDiv").removeClass("disp-none");
                        $(this).remove();
                    });
                    break;
                case "free_interest_sent":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sentDiv" class="sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div><div class="pos-abs fullwid txtc colorGrey top120">Only paid members can start chat<div id="becomePaidMember" class="color5 cursp">Become a Paid Member</div></div>');
                    break;
                case "paid_interest_pending":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="sendDiv cursp pos-abs wid140 color5"><i class="nchatspr nchatic_6 "></i><span clas="vertTexBtm"> Send Interest</span></div><div id="sentDiv" class="txtc fullwid disp-none mt10 color5"> Your interest has been sent</div><div id="initChatText" class="pos-abs color5 txtc top120 left10">Initiating chat will also send your interest </div>');
                    $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                    $('chat-box[user-id="' + userId + '"] #sendInt').on("click", function() {
                        curElem._sendInterest(userId);
                        $(this).parent().find("#sentDiv").removeClass("disp-none")
                        $(this).parent().find("#initChatText").remove();
                        $(this).remove();
                    });
                    break;
                case "paid_interest_sent":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sentDiv" class="txtc fullwid mt10 color5"> Your interest has been sent</div>');
                    $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                    break;
                case "pog_interest_pending":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="pos-rel wid90p txtc colorGrey padall-10">The member wants to chat</div><div class="pos-rel fullwid txtc colorGrey mt20"><div id="accept" class="acceptInterest padall-10 color5 disp_ib cursp">Accept</div><div id="decline" class="acceptInterest padall-10 color5 disp_ib cursp">Decline</div></div><div id="acceptTxt" class="pos-rel fullwid txtc color5 mt25">Accept interest to continue chat</div><div id="sentDiv" class="fullwid pos-rel disp-none mt10 color5 txtc">Interest Accepted continue chat</div><div id="declineDiv" class="sendDiv txtc disp-none pos-abs wid80p mt10 color5">Interest Declined, you can\'t chat with this user anymore</div>');
                    $('chat-box[user-id="' + userId + '"] #accept').on("click", function() {
                        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                        $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
                        $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                        $(this).remove();
                        //TODO: fire query for accepting request
                    });
                    $('chat-box[user-id="' + userId + '"] #decline').on("click", function() {
                        $(this).closest(".chatMessage").find("#declineDiv").removeClass("disp-none");
                        $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt").remove();
                        $(this).remove();
                        //TODO: fire query for declining request
                    });
                    break;
                case "pog_interest_accepted":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="fullwid pos-rel mt10 color5 txtc">Interest Accepted continue chat</div>');
                    $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                    break;
                case "pog_interest_declined":
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="sendDiv txtc pos-abs wid80p mt10 color5">Interest Declined, you can\'t chat with this user anymore</div>');
            		break;
				default:
					$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
			}
            $('chat-box[user-id="' + userId + '"] .spinner').hide();
            //TODO: fire query to get message list	

        }, 2000);

    },
    _sendInterest: function(userId) {
        console.log("fire send interest query");
        //TODO: fire query to send interest	
    },

    //update status in chat box top
    _updateStatusInChatBox: function(userId,chat_status){
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            $("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(chat_status);
        }
    },

    //appending chat box
    _chatPanelsBox: function(userId, status,jid) {
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
                    curElem._createSideChatBox();
                } else {
                    curElem._updateSideChatBox();
                }
                curElem._bindExtraPopupUserClose($(".nchatic_4"));
                curElem._bindExtraUserNameBox();
            }
            curElem._appendChatBox(userId, status,jid);

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
                        data = $($("chat-box")[len - 1 - value]).attr("user-id"),
                        chatHtml = $(originalElem).find(".chatMessage").html();
                    curElem._appendChatBox(username, status,jid);
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

    //add data in side panel and update number
    _updateSideChatBox: function() {
        var value = parseInt($(".extraNumber").text().split("+")[1]) + 1,
            len = $("chat-box").length + 1,
            data = $($("chat-box")[len - value - 1]).attr("user-id");
        curElem._addDataExtraPopup(data);
        $(".extraNumber").text("+" + value);
    },

    //creating prototype for chat-box custom element
    _createPrototypeChatBox: function() {
        var elem = this,
            chatBoxProto = Object.create(HTMLElement.prototype),
            userId, status, response;
        chatBoxProto.attachedCallback = function() {
            this.innerHTML = '<div class="chatBoxBar fullwid hgt57 bg5 pos-rel fullwid"></div><div class="chatArea fullwid fullhgt"><div class="messageArea f13 bg13 fullhgt"><div class="chatMessage scrolla pos_abs fullwid" style="height: 250px;"><div class="spinner"></div></div></div><div class="chatInput brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" style="width: 220px;" id="txtArea"  class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Write message"></textarea></div></div>';
            $(this).addClass("z1000 btm0 brd_new fr mr7 fullhgt wid240 pos-rel disp_ib");
            userId = $(this).attr("user-id");
            status = $(this).attr("status-user");
            elem._appendInnerHtml(userId, status);
        };
        document.registerElement("chat-box", {
            prototype: chatBoxProto
        });
        document.createElement("chat-box");
    },

    //adding innerDiv after creating chatbox
    _appendInnerHtml: function(userId, status) {
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
        $('chat-box[user-id="' + userId + '"] .chatBoxBar').append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig">' + $(".chatlist li[id*='"+userId+"'] div").html() + '<div class="onlineStatus f11 opa50 mt4"></div></div><div class="iconBar cursp fr padallf_2 disp_ib opa40"><i class="nchatspr nchatic_3"></i><i class="nchatspr nchatic_2 ml10 mr10"></i><i class="nchatspr nchatic_1 mr10"></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">0</span></div></div>');
        curElem._bindInnerHtml(userId, status);
    },

    //binding innerDiv after creating chatbox
    _bindInnerHtml: function(userId, status) {
        var curElem = this;
        $('chat-box[user-id="' + userId + '"] .pinkBubble2').hide();
        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
        $('chat-box[user-id="' + userId + '"] .pinkBubble2 span').html("0");
        $("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(status);
        this._bindMaximize($('chat-box[user-id="' + userId + '"] .chatBoxBar'), userId);
        this._bindMinimize($('chat-box[user-id="' + userId + '"] .nchatic_2'));
        this._bindClose($('chat-box[user-id="' + userId + '"] .nchatic_1'));
        this._bindBlock($('chat-box[user-id="' + userId + '"] .nchatic_3'), userId);
        this._bindSendChat(userId);
        //setTimeout(function(){  curElem._appendRecievedMessage("hi this is amacjrheabf erhfbjahberf aerb",userId,"ueiuh");
        /*setTimeout(function(){  this._changeStatusOfMessg("ueiuh",userId,"recieved"); 
        setTimeout(function(){  this._changeStatusOfMessg("ueiuh",userId,"recievedRead"); 
        }, 2000);
        }, 2000);*/
        //}, 8000);
        this._postChatPanelsBox(userId);

    },

    //append self sent message on opening window again
    _appendSelfMessage: function(message, userId, uniqueId, status) {
        var curElem = this;
        $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id="text_' + userId + '_' + uniqueId + '" class="talkText">' + message + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
        var len = $('chat-box[user-id="' + userId + '"] .talkText').length - 1,
            height = $($('chat-box[user-id="' + userId + '"] .talkText')[len]).height();
        $($('chat-box[user-id="' + userId + '"] .talkText')[len]).next().css("margin-top", height);
        if (status != "sending") {
            curElem._changeStatusOfMessg(uniqueId, userId, status);
        }
    },
    //add meesage recieved from another user
    _appendRecievedMessage: function(message, userId, uniqueId) {
        console.log("in _appendRecievedMessage");
        //append received message in chatbox
        if(typeof message != "undefined" && message!= ""){
            //if chat box is not opened
            if ($('chat-box[user-id="' + userId + '"]').length == 0) {
                $(".profileIcon[id^='" + userId + "']")[0].click();
            }
            console.log("remove typing state if exists-manvi");
            //adding message in chat area
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="leftBubble"><div class="tri-left"></div><div class="tri-left2"></div><div id="text_' + userId + '_' + uniqueId + '" class="talkText">' + message + '</div></div>');
            //check for 3 messages and remove binding
            if ($('chat-box[user-id="' + userId + '"] .chatMessage').hasClass("restrictMessg2")) {
                $('chat-box[user-id="' + userId + '"] .chatMessage').find("#restrictMessgTxt").remove();
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            }
            var val;
            //adding bubble for minimized tab
            if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                val = parseInt($('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html()) + 1;
                $('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html(val);
                $('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2').show();
            }
            
            //adding bubble for side tab
            if ($("#extra_" + userId + " .pinkBubble").length != 0) {
                val = parseInt($("#extra_" + userId + " .pinkBubble span").html());
                $("#extra_" + userId + " .pinkBubble span").html(val + 1);
                $("#extra_" + userId + " .pinkBubble").show();
            }
        }
    },

    //handle typing status of message
    _handleMsgComposingStatus:function(userId,msg_state){
        console.log("in _handleMsgComposingStatus");
        if(typeof msg_state!= "undefined"){
            if(msg_state == 'composing'){
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    console.log("show typing state in minimized chat box top-manvi");
                }
                else{
                    console.log("show typing state in opended chat box top-manvi");
                }
            }
            else if(msg_state == 'paused' || msg_state == 'gone'){
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    console.log("remove typing state in minimized chat box top-manvi");
                }
                else{
                    console.log("remove typing state in opended chat box top-manvi");
                }
            }
        }
    },

    //change from sending status to sent / sent and read
    _changeStatusOfMessg: function(messgId, userId, newStatus) {
        console.log("Change status"+newStatus);
        if (newStatus == "recieved") {
            $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_8").addClass("nchatic_10");
        } else if (newStatus == "recievedRead") {
            $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_8").addClass("nchatic_10");
            setTimeout(function() { 
                $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_10 nchatic_8").addClass("nchatic_9");
            }, 500);
        }
    },

    onEnterToChatPreClick: null,

    onChatLoginSuccess:null,  //function triggered after successful chat login


    //start:login screen
    _startLoginHTML: function() {
        console.log('_startLoginHTML call');
        var curEle = this;
        //user not logged in and coming for first time dhuila is wrong
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
                console.log("ankita_1");
                //curEle._appendLoggedHTML();    
            }
            else{
                console.log("ankita_2");
                $(curEle._loginPanelID).fadeOut('fast',function() {
                    //curEle._appendLoggedHTML();
                });
            }
        }
        //user logged out from chat in the same session
        else {
            console.log('case 3');
            $(curEle._loginPanelID).fadeOut('fast', function() {
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
    
    //start:function calculate the current postion for hover box
    _calHoverPos:function(param2,param3){


        var hoverbtm, newTop, hgtHiddenBelow, hgtVisible;
        var sHeight = $(window).height();

        hoverbtm = (parseInt(param2 + param3));
        hoverbtm = parseInt(hoverbtm / 2);
        if (hoverbtm < sHeight) 
        {
            param2 = (Math.round(param2 / 2)) - 10;
            newTop = param2;
            if ((newTop + param3) > sHeight) 
            {
                hgtVisible = sHeight - param2;
                hgtHiddenBelow = param3 - hgtVisible;
                newTop = param2 - hgtHiddenBelow;
            }
        } 
        else 
        {
            hgtVisible = sHeight - param2;
            hgtHiddenBelow = param3 - hgtVisible;
            newTop = param2 - hgtHiddenBelow;
        }
        return newTop;

    },

    onPreHoverCallback:null,

    //start:hover box html structure
    _hoverBoxStr:function(param1,param2,pCheckSum){
        //console.log($('#'+param1+'_hover').length);
        console.log("in hoverBoxStr");
        console.log(pCheckSum);
        var TotalBtn = '',widCal='';
        TotalBtn = param2["buttonDetails"]["buttons"].length;
        console.log('TotalBtn: '+TotalBtn);
        widCal = parseInt(100/TotalBtn);
        console.log('widCal: '+widCal);

        if($('#'+param1+'_hover').length == 0 )
        {

            var str='<div class="pos_fix info-hover fontlig nz21 vishid"';
            str+='id="'+param1+'_hover">';
                str+='<div class="nchatbdr3 f13">';
                    str+='<img src="'+param2.PHOTO+'" class="vtop"/>';
                        str+='<div class="nchatgrad padall-10">';
                            str+='<ul class="listnone lh22">';
                                str+='<li>'+param2.AGE+', '+ param2.HEIGHT+'</li>';
                                str+='<li>'+param2.COMMUNITY+'</li>';
                                str+='<li>'+ param2.EDUCATION +'</li>';
                                str+='<li>'+ param2.PROFFESION +'</li>';
                                str+='<li>'+ param2.SALARY+'</li>';
                                str+='<li>'+ param2.CITY+'</li>';
                            str+='</ul>';
                            str+='<p class="txtc nc-color2 pt10 hgt18">';
                                str+='<span class="disp-none">You accepted her interest</span>';
                            str+='</p>';                                    
                        str+='</div>';
                        //start:button structure
                        str+='<div class="'+ param1+'_BtnRespnse nchatgrad fullwid clearfix">';
                            $.each(param2["buttonDetails"]["buttons"],function(k,v){
                                str+='<button class="hBtn bg_pink lh50 brdr-0 txtc colrw cursp"';
                                str+='id="'+param1+'_'+v.action+'"';
                                str+='data-pCheckSum="'+pCheckSum+'"';
                                str+='data-params="'+param2.buttonDetails.buttons[0].params+'"';
                                if(TotalBtn==1)
                                {
                                    str+='style="width:100%">';
                                }
                                else
                                {
                                    if(k==0)
                                    {
                                       str+='style="width:'+widCal+'%"> ';
                                    }
                                    else
                                    {
                                        str+='style="width:'+(widCal-1)+'%;margin-left:1px">';
                                    }
                                }

                                str+= v.label;
                               str+='</button>';
                            });





                        str+='</div>';
                        //end:button structure
                str+='</div>';

            str+='</div>';


            return str;
        }
        console.log("End of _hoverBoxStr"); 
    },
    
    onHoverContactButtonClick: null,
    
     //start:update vcard
    updateVCard:function(param,pCheckSum,callback){
        //console.log('in vard update');
        var globalRef = this;
        var finalstr;
        $.each(param.vCard,function(k,v){                   
          finalstr = globalRef._hoverBoxStr(k,v,pCheckSum);
          $(globalRef._mainID).append(finalstr);
        });
        console.log("Callback calling starts");
        callback();
        console.log("Callaback ends");
    },
    
     //start:check hover
    _checkHover:function(param){
        var curEleID = $(param).attr('id');            
        curEleID = curEleID.split("_");
        curEleID = curEleID[0];
        var checkSumP = $(param).attr('data-checks');
        var hoverNewTop = $(param)[0].getBoundingClientRect().top;
        var _this = this;
         //as per discussion with ashok this height is goign to be fixed
        var hoverDivHgt = 435;
        hoverNewTop = this._calHoverPos(hoverNewTop,hoverDivHgt);
        if(this._checkWidth() )
        {
            var shiftright = 245;
        } 
        else 
        {
            var shiftright = Math.round($(this._parendID)[0].getBoundingClientRect().width);
        }
        //console.log('hoverNewTop:'+hoverNewTop+' shiftright:'+shiftright);
        //if element exist        
        if($('#'+curEleID+'_hover').length != 0)
        {
            $('#'+curEleID+'_hover').css({ 
                'top':  hoverNewTop,                     
                'visibility': 'visible',
                'right':shiftright
            });    
        }
        else
        {
            //console.log('call to onPreHoverCallback');
             if( this.onPreHoverCallback && typeof this.onPreHoverCallback == 'function' )
             {
                 console.log("Before precall");
                this.onPreHoverCallback(checkSumP,curEleID,hoverNewTop,shiftright); 
                //once div is created from precallback below ling shows the hovred list information
                console.log("After precall");
                
                 console.log("Atul console");
             }
        }
        $('.info-hover').hover(
        function() {
            $(this).css('visibility', 'visible');
        },
        function() {
            $(this).css('visibility', 'hidden');
        }
                
            
        );
        $('#'+curEleID+'_hover .hBtn').off("click").on('click',function(){ 
            if( _this.onHoverContactButtonClick && typeof _this.onHoverContactButtonClick == 'function' )
            {
                _this.onHoverContactButtonClick(this);
            }
        });
    }, 
    
    _timer:'undefined',
    //start:hover functionality
    _calltohover:function(e){
        //console.log("In _calltohover");
        //global level ref.
        var _this = e.data.global;
        var curHoverEle = this;
        //console.log(this);
        var getID = $(this).attr('id');            
        getID = getID.split("_");
        getID = getID[0];
        //set timer variable
        if(e.type == "mouseenter")
        {
            _this._timer = setTimeout(function() { 
                _this._checkHover(curHoverEle);  
            }, 1000);                
        }
        else
        {
            $('#'+getID+'_hover').css('visibility', 'hidden');
            clearTimeout(_this._timer);
        }
    },
    
    //start:append Chat Logged in Panel
    _appendLoggedHTML: function() {
        var curEle = this;
        console.log('_appendLoggedHTML');
        $(curEle._parendID).append('<div class="fullwid fontlig nchatcolor" id="js-lsitingPanel"/> ').promise().done(function() {
            curEle._addChatTop();
            curEle.addTab();
            curEle.onChatLoginSuccess();
            //first time intialization passing tab1 as param
            //curEle.addListingInit(curEle._listData);
        });
        
        

    },
    
    /*
     * Sending typing event
     */
    sendingTypingEvent: null,
    
   //start:this function image,name in top chat logged in scenario
    addLoginHTML: function(failed) {
        console.log('in addLoginHTML');
        var curEle = this;
        var LoginHTML = '<div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarOut"></i> </div><div> <img src="'+this._imageUrl+'" class="chatmt1"/> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Login to Chat</button></div>';
        var errorHTML = '';
        if(failed == true)
        {
            errorHTML += '<div class="txtc color5 f13 mt10" id="loginErr">'+curEle._loginFailueMsg+'</div>';
        }
        if(failed == false || typeof failed == "undefined" || $("#js-loginPanel").length == 0)
            $(this._parendID).append(LoginHTML);
        else
        {
            console.log("removing");
            $(curEle._loginPanelID).fadeIn('fast');
            $(curEle._loginPanelID).append(errorHTML);
        }
        $('.js-minChatBarOut').click(function() {

            curEle._minimizeChatOutPanel();
        });
        //start login button capture
        $(this._loginbtnID).click(function() {
            if( curEle.onEnterToChatPreClick  && typeof (curEle.onEnterToChatPreClick) == "function" ){
                console.log("in onEnterToChatPreClick");
                curEle.onEnterToChatPreClick();
            }
            if(curEle._loginStatus == "Y"){
                console.log("ankita_logged in");
                curEle._startLoginHTML();    
            } 
        });
    },

    //manage chat loader
    manageChatLoader: function(type){
        if(type == "hide"){
            console.log("hiding loader_ankita");
            $("#scrollDivLoader").hide();
        }
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
            console.log("checking login status");
            this._startLoginHTML();
        } else {
            console.log("in start function");
            this.addLoginHTML();
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