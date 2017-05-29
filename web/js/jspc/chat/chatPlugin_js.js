"use strict";
var JsChat = function () {
    this._construct(this, arguments);
};
var lrr;
var tab1ListingIds = {},tab2ListingIds = {};
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
    _selfName: '',
    _listingTabs: {},
    _loginFailueMsg: "Login Failed,Try later",
    _noDataTabMsg: {
        //"tab1": "<span class='fontlig pb10 disp_b'>There are no shortlisted profiles online</span> You can find online members by clicking ‘Online Now’ link present on right, after conducting a search.",
        "tab1": "<span class='fontreg pb10 disp_b'>There are no matching members online</span> Please <a href='/profile/dpp' class='color5 cursp'>relax your partner preference</a> to see more matches.",
        "tab2": "You currently don’t have any accepted members, get started by sending interests or initiating chat with your matches."
    },
    _rosterDetailsKey: "rosterDetails",
    _listingNodesLimit: {},
    _groupBasedChatBox: {},
    _contactStatusMapping: {},
    _loggingEnabledPlugin: false,
    _maxMsgLimit:100,
    _rosterDeleteChatBoxMsg:"",
    _rosterGroups:[],
    _checkForDefaultEoiMsg:false,
    _setLastReadMsgStorage:true,
    _chatAutoLogin:false,
    _categoryTrackingParams:{},
    _groupBasedConfig:{},
    _defaultActiveTab:"tab1",
    _sentMsgRefTime:60000,

    _chatLoggerPlugin: function (msgOrObj) {
        if (this._loggingEnabledPlugin) {
            if (typeof (window.console) != 'undefined') {
                try {
                    throw new Error('Initiate Stack Trace');
                } catch (err) {
                    var logStack = err.stack;
                }
                var fullTrace = logStack.split('\n');
                for (var i = 0; i < fullTrace.length; ++i) {
                    fullTrace[i] = fullTrace[i].replace(/\s+/g, ' ');
                }
                var caller = fullTrace[1],
                    callerParts = caller.split('@'),
                    line = '';
                //CHROME & SAFARI
                if (callerParts.length == 1) {
                    callerParts = fullTrace[2].split('('), caller = false;
                    //we have an object caller
                    if (callerParts.length > 1) {
                        caller = callerParts[0].replace('at Object.', '');
                        line = callerParts[1].split(':');
                        line = line[2];
                    }
                    //called from outside of an object
                    else {
                        callerParts[0] = callerParts[0].replace('at ', '');
                        callerParts = callerParts[0].split(':');
                        caller = callerParts[0] + callerParts[1];
                        line = callerParts[2];
                    }
                }
                //FIREFOX
                else {
                    var callerParts2 = callerParts[1].split(':');
                    line = callerParts2.pop();
                    callerParts[1] = callerParts2.join(':');
                    caller = (callerParts[0] == '') ? callerParts[1] : callerParts[0];
                }
                console.log(' ');
                console.warn('Console log: ' + caller + ' ( line ' + line + ' )');
                console.log(msgOrObj);
                console.log({
                    'Full trace:': fullTrace
                });
                console.log(' ');
            } else {
                //shout('This browser does not support console.log!')
            }
        }
    },
    // _chatLoggerPlugin: function (message) {
    //     if (this._loggingEnabledPlugin) {
    //         console.log(message);
    //     }
    // },
    _construct: function () {
        if (arguments[1][0].loginStatus) this._loginStatus = arguments[1][0].loginStatus;
        //configurable main div
        if (arguments[1][0].mainID) this._mainID = arguments[1][0].mainID;
        if (arguments[1][0].listData) this._listData = arguments[1][0].listData;
        if (arguments[1][0].listingTabs) this._listingTabs = arguments[1][0].listingTabs;
        if (arguments[1][0].loginFailueMsg) this._loginFailueMsg = arguments[1][0].loginFailueMsg;
        if (arguments[1][0].noDataTabMsg) this._noDataTabMsg = arguments[1][0].noDataTabMsg;
        if (arguments[1][0].rosterDetailsKey) this._rosterDetailsKey = arguments[1][0].rosterDetailsKey;
        if (arguments[1][0].listingNodesLimit) this._listingNodesLimit = arguments[1][0].listingNodesLimit;
        if (arguments[1][0].imageUrl) this._imageUrl = arguments[1][0].imageUrl;
        if (arguments[1][0].selfName) this._selfName = arguments[1][0].selfName;
        if (arguments[1][0].groupBasedChatBox) this._groupBasedChatBox = arguments[1][0].groupBasedChatBox;
        if (arguments[1][0].contactStatusMapping) this._contactStatusMapping = arguments[1][0].contactStatusMapping;
        if (arguments[1][0].maxMsgLimit) {
            this._maxMsgLimit = arguments[1][0].maxMsgLimit;
        }
        if (arguments[1][0].rosterDeleteChatBoxMsg) {
            this._rosterDeleteChatBoxMsg = arguments[1][0].rosterDeleteChatBoxMsg;
        }
        if (arguments[1][0].rosterGroups) {
            this._rosterGroups = arguments[1][0].rosterGroups;
        }
        if (arguments[1][0].checkForDefaultEoiMsg) {
            this._checkForDefaultEoiMsg = arguments[1][0].checkForDefaultEoiMsg;
        }
        if (arguments[1][0].setLastReadMsgStorage) {
            this._setLastReadMsgStorage = arguments[1][0].setLastReadMsgStorage;
        }
        if (arguments[1][0].chatAutoLogin) {
            this._chatAutoLogin = arguments[1][0].chatAutoLogin;
        }
        if (arguments[1][0].categoryTrackingParams) {
            this._categoryTrackingParams = arguments[1][0].categoryTrackingParams;
        }
        if (arguments[1][0].groupBasedConfig) {
            this._groupBasedConfig = arguments[1][0].groupBasedConfig;
        }
    },
    //start:get screen height
    _getHeight: function () {
        return ($(window).height());
    },
    //start:the check width function
    _checkWidth: function () {
        if ($(window).width() < 1254) {
            return true
        } else {
            return false
        }
    },
    //start:check login status
    checkLoginStatus: function () {
        //this._chatLoggerPlugin('check status');
        if (this._loginStatus == "Y") {
            return true;
        } else {
            return false;
        }
    },
    //start:maximize html
    _maximizeChatPanel: function () {
        var curEle = this;
        /*$("chat-box").each(function (index, element) {
            if ($(this).attr("pos-state") == "open") {
                curEle._scrollUp($(this), "297px","noAnimate",true);
            }
        });*/
        var data = [];
        if(localStorage.getItem("chatBoxData")) {
            data = JSON.parse(localStorage.getItem("chatBoxData"));
        }

        $.each(data, function(index,elem){
            //console.log("data",data);
            if($('chat-box[user-id="' + elem["userId"] + '"]').length == 0){
                $("#"+elem["userId"]+"_"+elem["group"]).click();    
            }
            if($('chat-box[user-id="' + elem["userId"] + '"] img').hasClass("downBarPicMin") && elem["state"] == "open" || elem["state"] == "") {
                $('chat-box[user-id="' + elem["userId"] + '"] .chatBoxBar').click();
                setTimeout(function(){
                    curEle._scrollToBottom(elem["userId"],"noAnimate");
                },50);
                var bubbleData = [];
                if(localStorage.getItem("bubbleData_new")) {
                    bubbleData = JSON.parse(localStorage.getItem("bubbleData_new"));
                }
                var indexToBeRemoved;
                $.each(bubbleData, function(index2, elem2){
                    if(elem2.userId == elem["userId"]) {
                        indexToBeRemoved = index2;
                    }
                });
                if(indexToBeRemoved != undefined) {
                    bubbleData.splice(indexToBeRemoved,1);
                }
                localStorage.setItem("bubbleData_new", JSON.stringify(bubbleData));
        
            }
            if(!$('chat-box[user-id="' + elem["userId"] + '"] img').hasClass("downBarPicMin") && elem["state"] == "min") {
                $('chat-box[user-id="' + elem["userId"] + '"] .nchatic_2').click();
            }
        });
        curEle._changeLocalStorage("chatStateChange","","","max");
        //curEle._changeLocalStorage("chatBubbleStateChange","","","0");
        $(this._maxChatBarOut).remove();
        if (this._checkWidth()) {
            $(this._parendID).fadeIn('slow');
        } else {
            $("body").css('width','80%');
            $(this._parendID).css('display','block');
            if(my_action && (my_action=="detailed" || my_action == "noprofile")){
                curEle.handleNextPrevButtons("makeCloser");
            }
        }
        $(curEle._chatBottomPanelID).show();
    },
    //start:minimize html
    minimizedPanelHTML: function () {
        var minChatPanel = '';
        minChatPanel += '<div class="nchatbg1 nchatw2 nchatp6 pos_fix colrw nchatmax js-minpanel cursp">';
        minChatPanel += '<ul class="nchatHor clearfix f13 fontreg">';
        minChatPanel += ' <li>';
        minChatPanel += '<div class="pt5 pr10">ONLINE MATCHES</div>';
        minChatPanel += '</li>';
        minChatPanel += '<li>';
        var count = this._onlineUserMsgMe();
        minChatPanel += '<div class="bg_pink disp-tbl txtc nchatb showcountmin';
        if ((this._loginStatus == 'Y') && (count > 0)) {
            minChatPanel += '">';
        } else {
            minChatPanel += ' vishid">';
        }
        minChatPanel += '<div class="vmid disp-cell countVal">';
        minChatPanel += count;
        minChatPanel += '</div>';
        minChatPanel += '</div>';
        minChatPanel += '</li>';
        minChatPanel += '<li class="pl10">';
        minChatPanel += '<i class="nchatspr nchatopen"></i>';
        minChatPanel += '</li>';
        minChatPanel += '</ul>';
        minChatPanel += '</div>';
        $(this._mainID).append(minChatPanel);
        if(my_action && (my_action=="detailed" || my_action == "noprofile")){
            this.handleNextPrevButtons("makeFarther");
        }
    },
    //start:minimize html
    _minimizeChatOutPanel: function () {
        var curEle = this;
        $("chat-box").each(function (index, element) {
            curEle._scrollDown($(this), "min");
        });
        curEle._changeLocalStorage("chatStateChange","","","min");
        $(curEle._chatBottomPanelID).hide();
        if (curEle._checkWidth()) {
            $(curEle._parendID).fadeOut('slow', function () {
                curEle.minimizedPanelHTML();
            }).promise().done(function () {
                $(curEle._maxChatBarOut).click(function () {
                    //console.log('aaa1');
                    curEle._maximizeChatPanel();
                });
            });
        } else {
            $(curEle._parendID).css('display','none');
            $("body").css('width','100%');
            this.minimizedPanelHTML();
        }
        $(this._maxChatBarOut).click(function () {
            curEle._maximizeChatPanel();
        });
    },
    //start:chat tabs click
    _chatTabs: function (param,type) {
        var curElem = this;
        if($('#' + param).hasClass("active") == false) {
            //console.log("param",param);
            curElem._changeLocalStorage("tabStateChange","","",param);
            /*if(param == "tab1") {
                curElem._changeLocalStorage("tabStateChange","","","online");
            }
            else if(param == "tab2") {
                curElem._changeLocalStorage("tabStateChange","","","accepted");
            }*/
            $('ul.nchattab1 li').removeClass('active cursd');
            $('#' + param).addClass('active cursd');
            if(type == "noAnimate") {
                $('.js-htab').hide();
                $('.show' + param).show();  
            }
            else {
                $('.js-htab').fadeOut('slow').promise().done(function() {
                    $('.show' + param).fadeIn('slow');
                    $(curElem._scrollDivId).mCustomScrollbar("scrollTo",0,{dur:0,scrollEasing:"mcsEaseInOut"});
                }); 
            }
            /*$('.js-htab').fadeOut('slow').promise().done(function () {
                $('.show' + param).fadeIn('slow')
            });*/
        }
        
        var apiParams = {};
        if(localStorage && localStorage.getItem("tabState") == "tab1"){
            apiParams["profiles"] = tab1ListingIds;
            tab1ListingIds.length = 0;
        }
        else if(localStorage && localStorage.getItem("tabState") == "tab2") {
            apiParams["profiles"] = tab2ListingIds;
            tab2ListingIds.length = 0;
        }
        if (apiParams["profiles"] != undefined && Object.keys(apiParams["profiles"]).length > 1) {
            //apiParams["pid"] = jidStr.slice(0, -1);
            apiParams["photoType"] = "ProfilePic120Url";
            apiParams["initialList"] = true;
            //console.log("request2");
            requestListingPhoto(apiParams);
        }
    },
    onLogoutPreClick: null,
    //start:log out from chat
    logOutChat: function () {
        var curEleRef = this,
            that = this;
        $(curEleRef._toggleID).toggleClass('disp-none');
        $(curEleRef._chatBottomPanelID).hide();
        //this._chatLoggerPlugin("In logout Chat");
        //this._chatLoggerPlugin(curEleRef._loginStatus);
        if (curEleRef._loginStatus == 'N') {
            $(curEleRef._listingPanelID).fadeOut('slow', function () {
                if ($(curEleRef._loginPanelID).length == 0) {
                    //that._chatLoggerPlugin("Length is 0 of login panel");
                    curEleRef.addLoginHTML();
                } else {
                    $(curEleRef._loginPanelID).fadeIn('slow', function () {
                        
                    });
                }
                $(".info-hover").remove();
                $(curEleRef._listingPanelID).remove();
                $("chat-box").each(function(index,elem){
                    $(elem).remove();
                });
            });
        } else {
            $(curEleRef._listingPanelID).fadeOut('slow', function () {
                curEleRef.addLoginHTML();
            });
        }
    },
    //start:addChatTop function
    _addChatTop: function (param) {
        //console.log("add chat top",this._selfName);
        var curEleRef = this,
            that = this;
        
        var lengthReq = chatConfig.Params[device].nameTrimmLength;
        var stringName = this._selfName;
        var trimmedString = stringName.length > lengthReq ? stringName.substring(0, lengthReq - 3) + "..." : stringName;
        var chatHeaderHTML = '<div class="nchatbg1 nchatp2 clearfix pos-rel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="' + this._imageUrl + '" class="nchatp4 wd40" oncontextmenu="return false;" onmousedown="return false;"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="disp_ib wid97 textTru chatName">'+trimmedString+'</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp" data-siteLogout="false">Logout from chat</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="textTru chatName disp_ib wid97">'+trimmedString+'</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>';
        $(curEleRef._listingPanelID).append(chatHeaderHTML);
	$('body').on('click', function(event) {
            if(($(event.target).parent().attr('id') != "undefined" && $(event.target).parent().attr('id') != 'js-chattopH') &&
		($(event.target).attr('id') != "undefined" && $(event.target).attr('id') != "js-chattopH") &&
		$(event.target).parent().hasClass('js-LogoutPanel') == false && 
		$(event.target).hasClass('js-LogoutPanel') == false
		) {
                $(curEleRef._toggleID).addClass('disp-none');
            }
        });
        $(curEleRef._toggleLogoutDiv).on("click", function () {
            $(curEleRef._toggleID).toggleClass('disp-none');

            if ($(curEleRef._toggleID).hasClass('disp-none') == false) {
                /*setTimeout(function () {
                    $("body").on("click", function () {
                    //    $("body").off("click");
                        $(curEleRef._toggleID).addClass('disp-none');
                    });
                }, 300);*/
            }
        });
        $(curEleRef._logoutChat).click(function () {
            //console.log("Site logout clicked");
            if (curEleRef.onLogoutPreClick && typeof (curEleRef.onLogoutPreClick) == "function") {
                //that._chatLoggerPlugin("in if");
                var fromSiteLogout = $(curEleRef._logoutChat).attr("data-siteLogout");
                curEleRef.onLogoutPreClick(fromSiteLogout);
            }
            curEleRef.logOutChat();
        });
        $(curEleRef._minChatBarIn).click(function () {
            $(curEleRef._minimizeChatOutPanel());
        });
    },
    //start:set height for the listing scroll div
    _chatScrollHght: function () {
        //this._chatLoggerPlugin('cal scroll div');
        var totalHgt = this._getHeight();
        var remHgt = parseInt(totalHgt) - 140;
        //this._chatLoggerPlugin(remHgt);
        //this._chatLoggerPlugin(this._scrollDivId);
        $(this._scrollDivId).css('height', remHgt);
    },
    //start:add tab
    addTab: function () {
        //console.log("addTab");
        //this script is same as old one shared eariler need to be reworked as discussed
        //this._chatLoggerPlugin('in addTab');
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
                TabsOpt += "active cursd\">";
            } else {
                TabsOpt += "\">";
            }
            TabsOpt += "<p>" + objin["tab_name"] + "</p><div class=\"showlinec\"></div></li>";
        }
        TabsOpt += '</ul></div>';
        TabsOpt += '<div id="nchatDivs" class="nchatscrollDiv"><div id="scrollDivLoader" class="spinner pos_fix chatpos1 z7 blankLoader"></div>';
        TabsOpt += '<div class="showtab1 js-htab" id="tab1"> <div id="showtab1NoResult" class="noResult f13 fontlig disp-none">' + curEle._noDataTabMsg["tab1"] + '</div>';
        for (var i = 0; i < obj["tab1"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + " disp-none chatListing\" data-showuser=\"" + obj["tab1"]["groups"][i]["hide_offline_users"] + "\">";
            //TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2";
            if (obj["tab1"]["groups"][i]["show_group_name"] == false) TabsOpt += " disp-none";

	    var jsNonRosterGroup = '';
            if (obj["tab1"]["groups"][i]["nonRosterGroup"] == true)
	    	jsNonRosterGroup += " jsNonRosterGroup ";

            TabsOpt += "\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab1"]["groups"][i]["group_name"] + "</p></div>";
            //TabsOpt += "<ul class=\"chatlist\"></ul></div>";
            TabsOpt += "<ul class=\"chatlist "+jsNonRosterGroup+"online\"></ul>";
            TabsOpt += "<ul class=\"chatlist"+jsNonRosterGroup+" offline\"></ul></div>";
        }
        TabsOpt += '</div>';
        TabsOpt += '<div class="showtab2 js-htab disp-none" id="tab2"> <div id="showtab2NoResult" class="noResult f13 fontreg disp-none">' + curEle._noDataTabMsg["tab2"] + '</div>';
        for (var i = 0; i < obj["tab2"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\" data-showuser=\"" + obj["tab2"]["groups"][i]["hide_offline_users"] + "\">";
            //TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2";
            if (obj["tab2"]["groups"][i]["show_group_name"] == false) TabsOpt += " disp-none";
            TabsOpt += "\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab2"]["groups"][i]["group_name"] + "</p></div>";
            //TabsOpt += "<ul class=\"chatlist\"></ul></div>";
            TabsOpt += "<ul class=\"chatlist online\"></ul>";
            TabsOpt += "<ul class=\"chatlist offline\"></ul></div>";
        }
        TabsOpt += '</div>';
        TabsOpt += '</div>';
        $(this._listingPanelID).append(TabsOpt);
        $(this._tabclass).click(function () {
            curEle._chatTabs($(this).attr('id'),"");
        });
        if(localStorage.getItem("tabState") == undefined) {
            curEle._changeLocalStorage("tabStateChange","","",curEle._defaultActiveTab);    
        } else { 
            //console.log("tabstate",localStorage.getItem("tabState"));
            curEle._chatTabs(localStorage.getItem("tabState"),"noAnimate");
        }
    },
    noResultError: function () {
        var dataLength;
        var that = this;
        $(".js-htab").each(function (index, element) {
            dataLength = 0;
            $(this).find(".chatlist").each(function (index2, element2) {
                //that._chatLoggerPlugin($(this).find("li").length);
		if (!$(this).hasClass('jsNonRosterGroup'))
	                dataLength = dataLength + $(this).find("li").length;
            });
            if (dataLength == 0) {
                //that._chatLoggerPlugin(element);
                $(element).find(".noResult").removeClass("disp-none").addClass("disp_ib");
                $(element).find(".chatListing").each(function (index, element) {
                    $(this).addClass("disp-none");
                });
            }
        });
        delete that;
    },
    
    /*
     * Manage minimize and maximize panel state, not used now
     */
    manageMinMaxState: function (elem){
        //console.log("in min max state");
        var state = localStorage.getItem('panelState');
        if(state){
            var data = JSON.parse(state);
            var state = data['state'];
            var user = data['user'];
            if(user && user == loggedInJspcUser){
                if(state == 'min'){
                    $(elem._minimizeChatOutPanel());
                }
                else if(state == 'max'){
                    elem._maximizeChatPanel();
                }
            }
            else{
                localStorage.removeItem('panelState');
            }
        }
    },
    
    //check for node presence
    checkForNodePresence:function(userId,specificGroupIdArr){
        var exists = false,curElem = this,groupID,groupListArr;
        if(specificGroupIdArr == undefined){
            groupListArr = curElem._rosterGroups;
        }
        else{
            groupListArr = specificGroupIdArr;
        }
        $.each(groupListArr,function(key,groupId){
            if($(".chatlist li[id='" + userId + "_" + groupId + "']").length != 0){
                exists = true;
                groupID = groupId;
            }                
        });
        var output = {"exists":exists,"groupID":groupID};
        return output;
    },

    createHiddenListNode:function(data){
        //console.log("hidden",data);
        var addedFlag = false,curElem = this;
        for (var key in data) {
            if (typeof data[key]["rosterDetails"]["jid"] != "undefined") {
                var runID = data[key]["rosterDetails"]["jid"],
                    res = '',
                    status = data[key]["rosterDetails"]["chat_status"];
                var fullJID = runID;
                res = runID.split("@");
                runID = res[0];
                if (typeof data[key]["rosterDetails"]["groups"] != "undefined" && data[key]["rosterDetails"]["groups"].length > 0) {
                    $.each(data[key]["rosterDetails"]["groups"], function (index, val) {
                        //check for no roster listing
                        var alreadyExistingNode = curElem.checkForNodePresence(runID).exists;
                        //console.log("HiddenNode",alreadyExistingNode);
                        if (alreadyExistingNode == false) {
                            var List = '',
                                fullname = data[key]["rosterDetails"]["fullname"],
                                //tabShowStatus = $('div.' + val).attr('data-showuser'),
                                added,
                                getNamelbl = fullname,
                                picurl = data[key]["rosterDetails"]["listing_tuple_photo"],
                                prfCheckSum = data[key]["rosterDetails"]["profile_checksum"],
                                nick = data[key]["rosterDetails"]["nick"]; 
                            List += '<li class=\"clearfix profileIcon js-nonRosterNode disp-none\"';
                            List += "id=\"" + runID + "_" + val + "\" data-status=\"" + status + "\" data-checks=\"" + prfCheckSum + "\" data-nick=\"" + nick + "\" data-jid=\"" + fullJID + "\">";
                            List += "<img id=\"pic_" + runID + "_" + val + "\" src=\"" + picurl + "\" onmousedown=\"return false;\" oncontextmenu=\"return false;\" class=\"fl wid40hgt40\" >";
                            List += '<div class="fl f14 fontlig pt15 pl18">';
                            List += getNamelbl;
                            List += '</div>';
                            if (status == "online") {
                                List += '<div class="fr"><i class="nchatspr nchatic5 mt15"><div class="pos-abs fullBlockTitle disp-none tneg20_new bg-white f13 brderinp pad308">Online</div></i></div>';
                            }
                            List += '</li>';
                            if (status == "online") {
                                if ($('#' + runID + "_" + val).length == 0) {
                                    //addedFlag = curElem._placeContact(0,"add_hidden","nonRosterAdd", runID, val, status, List);
                                    addedFlag = curElem._placeContact({ "addIndex":0,
                                                                        "operation":"add_hidden",
                                                                        "key":"nonRosterAdd",
                                                                        "contactID":runID,
                                                                        "groupID":val,
                                                                        "status":status,
                                                                        "contactHTML":List
                                                                    });
                                    if(addedFlag == true){
                                        $("#" + runID + "_" + val).on("click", function () {
                                            currentID = $(this).attr("id").split("_")[0];
                                            curElem._chatPanelsBox(currentID, status, $(this).attr("data-jid"), $(this).attr("data-checks"), $(this).attr("id").split("_")[1]);
                                        });
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    },

    addListingInit: function (data,operation) {
        var elem = this,
            statusArr = [],
            //jidStr = "",
            currentID;
        //this._chatLoggerPlugin("addListing");
        for (var key in data) {
            if (typeof data[key]["rosterDetails"]["jid"] != "undefined") {
                var runID = data[key]["rosterDetails"]["jid"],
                    res = '',
                    status = data[key]["rosterDetails"]["chat_status"];
                var fullJID = runID;
                res = runID.split("@");
                runID = res[0];
                //jidStr = jidStr + runID + ",";
                statusArr[runID] = status;
                if (typeof data[key]["rosterDetails"]["groups"] != "undefined" && data[key]["rosterDetails"]["groups"].length > 0) {
                    var that = this;
                    //console.log("ankita",data[key]["rosterDetails"]["groups"]);
                    $.each(data[key]["rosterDetails"]["groups"], function (index, val) {
                        if(chatConfig.Params.pc.tab1groups.indexOf(val) !== -1){
                            //tab1ListingIds.push(key);
                            tab1ListingIds[key] = {"PROFILEID":key,"GROUP":val};
                        }
                        else if (chatConfig.Params.pc.tab2groups.indexOf(val) !== -1){
                            //tab2ListingIds.push(key);
                            tab2ListingIds[key] = {"PROFILEID":key,"GROUP":val};
                        }
                        var List = '',
                            fullname = data[key]["rosterDetails"]["fullname"],
                            tabShowStatus = $('div.' + val).attr('data-showuser'),
                            added;
                        var getNamelbl = fullname,
                            picurl = data[key]["rosterDetails"]["listing_tuple_photo"],
                            prfCheckSum = data[key]["rosterDetails"]["profile_checksum"],
                            nick = data[key]["rosterDetails"]["nick"]; //ankita for image
                        List += '<li class=\"clearfix profileIcon\"';
                        List += "id=\"" + runID + "_" + val + "\" data-status=\"" + status + "\" data-addIndex=\"" + data[key]["rosterDetails"]["addIndex"] + "\" data-checks=\"" + prfCheckSum + "\" data-nick=\"" + nick + "\" data-jid=\"" + fullJID + "\">";
                        List += "<img id=\"pic_" + runID + "_" + val + "\" src=\"" + picurl + "\" onmousedown=\"return false;\" oncontextmenu=\"return false;\" class=\"fl wid40hgt40\">";
                        List += '<div class="fl f14 fontlig pt15 pl18">';
                        List += getNamelbl;
                        List += '</div>';
                        if (status == "online") {
                            List += '<div class="fr"><i class="nchatspr nchatic5 mt15"><div class="pos-abs fullBlockTitle disp-none tneg20_new bg-white f13 brderinp pad308">Online</div></i></div>';
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
                        //that._chatLoggerPlugin("addNode" + addNode);
                        if (addNode == true) {
                            if ($('#' + runID + "_" + val).length == 0) {
                                if ($('#' + runID + "_" + val).find('.nchatspr').length == 0) {
                                    var tabId = $('div.' + val).parent().attr("id");
                                    if ($("#show" + tabId + "NoResult").length != 0) {
                                        that._chatLoggerPlugin("me");
                                        $("#show" + tabId + "NoResult").addClass("disp-none");
                                    }
                                    //added = elem._placeContact(data[key]["rosterDetails"]["addIndex"],operation,"new", runID, val, status, List);
                                    added = elem._placeContact({   "addIndex":data[key]["rosterDetails"]["addIndex"],
                                                                    "operation":operation,
                                                                    "key":"new",
                                                                    "contactID":runID,
                                                                    "groupID":val,
                                                                    "status":status,
                                                                    "contactHTML":List,
                                                                    "nodeType":data[key]["rosterDetails"]["nodeType"]
                                                                });
                                    if(added == true){
                                        if ($('div.' + val + ' ul').parent().hasClass("disp-none")) {
                                            $('div.' + val + ' ul').parent().removeClass("disp-none");
                                        }
                                        $("#" + runID + "_" + val).on("click", function () {
                                            currentID = $(this).attr("id").split("_")[0];
                                            that._chatLoggerPlugin("earlier", $(this).attr("data-checks"));
                                            setTimeout(function(){
                                               $("#"+currentID+"_hover").css("visibility","hidden"); 
                                            },100);
                                            //setTimeout(function(){
                                                elem._chatPanelsBox(currentID, statusArr[currentID], $(this).attr("data-jid"), $(this).attr("data-checks"), $(this).attr("id").split("_")[1]);
                                            //    console.log("Timeouttocreatechatbox");
                                            //},200);
                                            
                                        });
                                    }
                                }
                            } else {
                                //added = elem._placeContact(data[key]["rosterDetails"]["addIndex"],operation,"existing", runID, val, status);
                                added = elem._placeContact({   "addIndex":data[key]["rosterDetails"]["addIndex"],
                                                                "operation":operation,
                                                                "key":"existing",
                                                                "contactID":runID,
                                                                "groupID":val,
                                                                "status":status,
                                                                "nodeType":data[key]["rosterDetails"]["nodeType"]
                                                            });
                            }
                        }
                        elem._updateStatusInChatBox(runID, status);
                    });
                    delete that;
                }
            }
        }
        elem._chatScrollHght();
        $(elem._scrollDivId).mCustomScrollbar({
            theme: "light",
            callbacks: {
                onScrollStart: function () {
                    $('.info-hover').css('visibility', 'hidden');
                }
            }
        });
        //call hover functionality
        $(elem._listingClass).on('mouseenter mouseleave', {
            global: elem
        }, elem._calltohover);

        var apiParams = {};
        if(localStorage && localStorage.getItem("tabState") == "tab1"){
            apiParams["profiles"] = tab1ListingIds;
            tab1ListingIds.length = 0;
            tab1ListingIds = {};
        }
        else if(localStorage && localStorage.getItem("tabState") == "tab2") {
            apiParams["profiles"] = tab2ListingIds;
            tab2ListingIds.length = 0;
            tab2ListingIds = {};
        }
        if(apiParams["profiles"] != undefined && Object.keys(apiParams["profiles"]).length > 0){
            apiParams["photoType"] = "ProfilePic120Url";
            if(operation == "create_list"){
                apiParams["initialList"] = true;
            }
            else{
                apiParams["initialList"] = false;
            }
            //console.log("request1");
            requestListingPhoto(apiParams);
        }
        if(operation == "create_list"){
            retainHiddenListing();
        }
        setTimeout(function(){
            var newTab = false;
            //console.log("newTab update",$(".tabUId").length,localStorage.getItem("lastUId"));
            if($(".tabUId").length == 0){
                //console.log("does not exsist");
                $("body").append("<input type='hidden' class='tabUId' id='tab_"+new Date().getTime()+"'>");
                //console.log("calling update _updateChatStructure new");
                elem._updateChatStructure("new");
                //elem._updateChatStructure("exsisting");
                newTab = true;
            }

            if(localStorage.getItem("lastUId")) {
                if($(".tabUId").attr("id") != localStorage.getItem("lastUId") && newTab ==  false){
                    //console.log("calling update _updateChatStructure exsisting");
                    elem._updateChatStructure("exsisting");
                }
            } else {
                localStorage.setItem("lastUId",$(".tabUId").attr("id"));
            }
        },1000);
        
        
        
        $(window).focus(function() {
            //console.log("tab changed");
            if(localStorage.getItem("lastUId")) {
                //console.log("11");
                if($(".tabUId").attr("id") != localStorage.getItem("lastUId")){
                    //console.log("22 change");
                    reActivateNonRosterPolling("tab change");
                    elem._updateChatStructure("exsisting");
                }
            } else {
                //console.log("33");
                localStorage.setItem("lastUId",$(".tabUId").attr("id"));
            }
            //updatePresenceAfterInterval();
        });
        /*
        $(window).focus(function () {
            //console.log("Focus");
            //invokePluginLoginHandler("login");
        
        });
        */
        setTimeout(function(){
            ifChatListingIsCreated = 1;
        },1000);
    },

    //add photo in tuple div of listing
    _addListingPhoto: function (photoObj,type) {
        if(type == "api") {
            if (typeof photoObj != "undefined" && typeof Object.keys(photoObj.profiles) != "undefined") {
                $.each(Object.keys(photoObj.profiles), function (index, element) {
                    if (photoObj.profiles[element].PHOTO.ProfilePic120Url) {
                        $(".chatlist img[id*='pic_" + element + "_']").attr("src", photoObj.profiles[element].PHOTO.ProfilePic120Url);
                        if($('chat-box[user-id="' + element + '"]').length !=0) {
                            $("#pic_"+element).attr("src", photoObj.profiles[element].PHOTO.ProfilePic120Url);
                        }
                    }
                });
            }
        } else if(type == "local") {
            var photoURL = "";
            $.each(photoObj, function(index, elem){
                if(localStorage.getItem("listingPic_"+elem)) {
                    photoURL = localStorage.getItem("listingPic_"+elem).split("#")[0];
                    if(photoURL != "undefined" && photoURL) {
                        $(".chatlist img[id*='pic_" + elem + "_']").attr("src", photoURL);
                        if($('chat-box[user-id="' + elem + '"]').length !=0) {
                            $("#pic_"+elem).attr("src", photoURL);
                        }
                    }
                   
                }
                
            });
            
        }
        
    },

    //remove hidden node from listing
    _removeHiddenNode: function(userId){
        var curElem = this;
        $.each(curElem._rosterGroups,function(key,groupId){
            if($('#'+userId+"_"+groupId).length!=0){
		var className = $('#'+userId+"_"+groupId).attr('class');
		if(className.indexOf('js-nonRosterNode')!='-1')
			$('#'+userId+"_"+groupId).remove();
            }
        }); 
    },

    //place contact in appropriate position in listing
   // _placeContact: function (addIndex,operation,key, contactID, groupID, status, contactHTML) {
    _placeContact: function (details) {
        var done=false,elem=this;
        var addIndex = details["addIndex"],
            operation = details["operation"],
            key = details["key"],
            contactID = details["contactID"],
            groupID = details["groupID"],
            contactHTML = details["contactHTML"],
            status = details["status"],
            nodeType = details["nodeType"];
        if(addIndex == undefined){
            addIndex = 0;
        }
        if (key == "new") {
            var upperLimit = elem._listingNodesLimit[groupID],totalNodes = $('div.'+groupID+' ul li').size();
            if (operation == "add_node" || operation == "update_status" || typeof upperLimit == "undefined" || totalNodes < upperLimit){
                elem._removeHiddenNode(contactID);
                var listCount = $('div.'+groupID+' ul.'+status+' li').size();
                if(operation == "create_list" && nodeType == "non-roster" && typeof upperLimit != "undefined" && addIndex >= upperLimit){
                    //console.log("false",addIndex);
                    done = false;
                }
                else{
                    //console.log("true",addIndex);
                    if(addIndex == 0 || listCount == 0){
                        $('div.' + groupID + ' ul.' + status).prepend(contactHTML);
                        //console.log("here out",groupID,addIndex,-1);
                    }
                    else{
                        var insertAfterPos = elem.getNodeInsertPos(addIndex,groupID,status);
                        //console.log("here out",groupID,addIndex,insertAfterPos);
                        if(insertAfterPos == -1){
                            $('div.' + groupID + ' ul.' + status).prepend(contactHTML);
                        }
                        else{
                            $('div.' + groupID + ' ul.' + status).children(':eq('+insertAfterPos+')').after(contactHTML);
                        }
                    }
                    //update status in list
                    if(status && (operation == "update_status" || operation == "removeCall1")){
                        $(".chatlist li[id='" + contactID + "_" + groupID + "']").attr("data-status",status);
                    }
                    done = true;
                }
            }
            else if(totalNodes >= upperLimit && status == "online" && nodeType != "non-roster"){
                var onlineCount = $('div.'+groupID+' ul.online li').size();
                if(onlineCount < upperLimit){
                    $('div.'+groupID+' ul.'+'offline'+' li:last').remove();
                    $('div.' + groupID + ' ul.' + status).prepend(contactHTML);
                    done = true;
                }
                else{
                    done = false;
                }
            }
            else{
                done = false;
            }
        } else if (key == "existing") {
            //this._chatLoggerPlugin("changing icon");
            if (status == "online") {
                //add online chat_status icon
                if ($('#' + contactID + "_" + groupID).find('.nchatspr').length == 0) {
                    $(this._mainID).find($('#' + contactID + "_" + groupID)).append('<div class="fr"><i class="nchatspr nchatic5 mt15"><div class="pos-abs fullBlockTitle disp-none tneg20_new bg-white f13 brderinp pad308">Online</div></i></div>');
                }
                $('#' + contactID + "_" + groupID).prependTo('div.' + groupID + ' ul.' + status);
            } else if (status == "offline") {
                $('#' + contactID + "_" + groupID).prependTo('div.' + groupID + ' ul.' + status);
            }
            done = true;
        } else if(key == "nonRosterAdd"){
            $('div.' + groupID + ' ul.' + status).append(contactHTML);
            done = true;
        }
        return done;
    },

    //get position at which new node is to be inserted
    getNodeInsertPos:function(desiredIndex,groupID,status){
        var insertAfterPos = -1;
        $('div.' + groupID + ' ul.' + status + ' li').each(function(index,element){
            if($(element).attr("data-addIndex") > desiredIndex){
               return (index - 1);
            }
            else{
                insertAfterPos = index;
            }
        });
        //console.log("getNodeInsertPos",desiredIndex,insertAfterPos);
        return insertAfterPos;
    },
    //scrolling down chat box
    _scrollDown: function (elem, type) {
        var userId = $(elem).attr("user-id");
        //this._chatLoggerPlugin(elem);
        if (type == "remove") {
            elem.animate({
                bottom: "-350px"
            },0, function () {
                $(this).remove();
            });
        } else if (type == "retain_extra") {
            elem.animate({
                bottom: "-1000px"
            },0);
        } else if (type == "retain" || type == "min") {
            elem.animate({
                bottom: "-14px"
            },0, function () {
                $(elem.find(".nchatic_2")[0]).hide();
                $(elem.find(".nchatic_3")[0]).hide();
                if(elem.find(".onlineStatus").html() != "typing..."){
                    elem.find(".onlineStatus").hide();
                }
                if (elem.find(".pinkBubble2 span").html() != 0) {
                    elem.find(".pinkBubble2").show();
                }
                elem.find(".chatBoxBar").addClass("cursp");
                elem.find(".js-viewProfileBind").removeClass("js-viewProfileBind");
                elem.find(".chatBoxBar").addClass("js-minimizedChatBox");
                elem.find(".downBarPic").addClass("downBarPicMin");
                elem.find(".downBarUserName").addClass("downBarUserNameMin");
                /*if (type != "min") { //manvi1
                    $(elem).attr("pos-state", "close");
                }*/
            });
        }
    },
    //adjusting text area on input by user
    _textAreaAdjust: function (o) {
        o.style.height = "1px";
        o.style.height = (o.scrollHeight - 16) + "px";
        var elem = $(o);
        var height = 294 - elem.parent().height();
        //console.log("height",height);
        if (height > 189) {
            $(o).closest("div").parent().find(".chatMessage").css("height", height);
        } else {
            $(o).css("overflow", "auto");
        }
        
    },
    //scrolling up chat box
    _scrollUp: function (elem, btmValue,type,notRead) {
        var curEle = this;
        elem.animate({
            bottom: btmValue
        },0, function () {
            $(elem.find(".nchatic_2")[0]).show();
            $(elem.find(".nchatic_3")[0]).show();
            elem.find(".onlineStatus").show();
            elem.find(".pinkBubble2").hide();
            elem.find(".pinkBubble2 span").html("0");
            elem.find(".chatBoxBar").removeClass("cursp");
            elem.find(".chatBoxBar").removeClass("js-minimizedChatBox");
            elem.find(".downBarPic").removeClass("downBarPicMin");
            elem.find(".downBarUserName").removeClass("downBarUserNameMin");
            //console.log("type in _scrollUp",type);
            //console.log("manvi1",$(elem).attr("user-id"));
            if($(elem).attr("user-id") != undefined){
                //console.log("manvi2",type);
                if(type == undefined){
                    //console.log("scrolling down");
                    curEle._scrollToBottom($(elem).attr("user-id"));
                } 
                else if(type == "noAnimate"){
                    curEle._scrollToBottom($(elem).attr("user-id"),type);
                }
            }
            
            // $(elem).attr("pos-state", "open"); manvi1
        });
        if(typeof notRead == "undefined" || notRead == false){
            
            curEle._handleUnreadMessages(elem);
        }
    },
    //handle unread messages or mark specified message as read
    _handleUnreadMessages: function (elem,msgParams) {
        //handle received and unread messages in chatbox
        var selfJID = getConnectedUserJID(),
            receiverID = $(elem).attr("data-jid");
        var that = this;
        if(typeof msgParams == "undefined"){
            $(elem).find(".received").each(function () {
                var msg_id = $(this).attr("data-msgid");
                var msgObj = {
                    "from": selfJID,
                    "to": receiverID,
                    "msg_id": msg_id,
                    "msg_state": "receiver_received_read"
                };
                $(this).removeClass("received").addClass("received_read");
                //that._chatLoggerPlugin("marking msg as read");
                //that._chatLoggerPlugin(msgObj);
                invokePluginReceivedMsgHandler(msgObj);
            });
        }
        else{
            if(msgParams["msg_id"]){
                var msgObj = {
                    "from": selfJID,
                    "to": receiverID,
                    "msg_id": msgParams["msg_id"],
                    "msg_state": "receiver_received_read"
                };
                $(this).removeClass("received").addClass("received_read");
                invokePluginReceivedMsgHandler(msgObj);
            }
        }
        delete that;
    },
    //bind clicking minimize icon
    _bindMinimize: function (elem) {
        var curElem = this;
        $(elem).off("click").on("click", function (e) {
            e.stopPropagation();
            curElem._scrollDown($(this).closest("chat-box"), "retain");
            curElem._changeLocalStorage("stateChange",$(this).closest("chat-box").attr("user-id"),"","min");
        });
    },
    //bind clicking maximize chat box
    _bindMaximize: function (elem, userId) {
        var curElem = this;
        $(elem).off("click").on("click", function () {
            //console.log("clicked",userId);
			if(elem.hasClass('js-minimizedChatBox')){
				//console.log("clicked to open");
		        curElem._scrollDown($(".extraPopup"), "retain_extra");
		        setTimeout(function () {
		            $(".extraChats").css("padding-top", "0px");
		        }, 100);
		        curElem._scrollUp($('chat-box[user-id="' + userId + '"]'), "297px");
		        curElem._changeLocalStorage("stateChange",userId,"","open");
		        var bubbleData = [];
		        if(localStorage.getItem("bubbleData_new")) {
		            bubbleData = JSON.parse(localStorage.getItem("bubbleData_new"));
		        }
		        var indexToBeRemoved;
		        $.each(bubbleData, function(index, elem){
		            if(elem.userId == userId) {
		                indexToBeRemoved = index;
		            }
		        });
		        if(indexToBeRemoved != undefined) {
		            bubbleData.splice(indexToBeRemoved,1);
		        }
		        localStorage.setItem("bubbleData_new", JSON.stringify(bubbleData));
                setTimeout(function(){
                    elem.find(".js-chatBoxTopName").addClass("js-viewProfileBind");
                    elem.find(".downBarPic").addClass("js-viewProfileBind");    
                },1000);
                
		}      
  	});

    },
    //bind clicking close icon
    _bindClose: function (elem) {
        //console.log("in _bindClose ankita");
        var curElem = this;
        $(elem).off("click").on("click", function () {
            curElem._scrollDown($(this).closest("chat-box"), "remove");
            if ($(".extraNumber")) {
                var value = parseInt($(".extraNumber").text().split("+")[1]);
                var bodyWidth = $("body").width();
                var divWidth = ($("chat-box").length - 1) * 250;
                if (value == 1 && divWidth < bodyWidth) {
                    $(".extraChats, .extraPopup").remove();
                } else if (value > 1) {
                    $(".extraNumber").text("+" + (value - 1));
                    var len = $(".extraChatList").length-1;
                    $($(".extraChatList")[len]).remove();
                }
            }
            curElem._changeLocalStorage("remove",$(this).closest("chat-box").attr("user-id"),"","");
	       removeLocalStorageForNonChatBoxProfiles($(this).closest("chat-box").attr("user-id"));
            curElem._scrollDown($(".extraPopup"), "retain_extra");
            setTimeout(function () {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
        });
    },
    //onPostBlockCallback: null,
    //remove from list
    _removeFromListing: function (param1, data) {
        //console.log("_removeFromListing",param1);
        //console.log(data);
        //data = data.filter(function(n){ return n != undefined }); 
        //console.log("after filter");
        //console.log(data);
        //this._chatLoggerPlugin('remove element 11');
        var elem = this;
        //removeCall1 if user is removed from backend
        
        if (param1 == 'removeCall1' || param1 == 'delete_node') {
            //this._chatLoggerPlugin("calllign _removeFromListing");
            for (var key in data) {
                var runID = '';
                if(typeof data[key] != "undefined"){
                    if(typeof data[key]["rosterDetails"]["jid"] != "undefined"){
                        runID = data[key]["rosterDetails"]["jid"].split("@")[0];
                    }
                    if(param1 == 'delete_node'){
                        localStorage.removeItem("listingPic_"+runID);
                    }
                    //console.log("nitish",param1);
                    if (typeof data[key]["rosterDetails"]["groups"] != "undefined") {
                        //this._chatLoggerPlugin(data[key]["rosterDetails"]["groups"]);
                        var that = this;
                        $.each(data[key]["rosterDetails"]["groups"], function (index, val) {
                            var tabShowStatus = '',
                                listElements = '';
                            //this check the sub header status in the list
                            var tabShowStatus = $('div.' + val).attr('data-showuser');
                            listElements = $('#' + runID + '_' + val);
                            if (tabShowStatus == 'false' && param1 != 'delete_node') {
                                
                                $(listElements).find('.nchatspr').detach();
                                //elem._placeContact(0,param1,"existing", runID, val, "offline");
                                elem._placeContact({   "addIndex":0,
                                                        "operation":param1,
                                                        "key":"existing",
                                                        "contactID":runID,
                                                        "groupID":val,
                                                        "status":"offline",
                                                        "nodeType":data[key]["rosterDetails"]["nodeType"]
                                                    });
                            } else {
                            
                                $('div').find(listElements).detach();
                                if ($('div.' + val + ' ul li').length == 0) {
                                    $('div.' + val + ' ul').parent().addClass("disp-none");
                                }
                            }
                            //that._chatLoggerPlugin(this);
                            elem._updateStatusInChatBox(runID, "offline");
                        });
                        delete that;
                        if(param1 == 'delete_node'){
                            this._changeLocalStorage("remove",runID,"","");
                        } 
                        
                    }
                }
            }
        }
        //removeCall2 if user is removed from block click on chatbox
        else if (param1 == 'removeCall2') {
            if(typeof data!= "undefined"){
                $(this._mainID).find('*[id*="' + data + '"]').detach();
                this._changeLocalStorage("remove",data,"","");
            /*if (this.onPostBlockCallback && typeof this.onPostBlockCallback == 'function') {
                this.onPostBlockCallback(data);
            }*/
            }
        }
        this.noResultError();
       
    },
    //bind clicking block icon
    _bindBlock: function (elem, userId) {
        var curElem = this,
            enableClose, groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        var user_name = $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html();
        var nick = user_name;
        var profileChecksum = $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("data-checks");
        if (profileChecksum) {
            nick = nick + "|" + profileChecksum;
        }
        $(elem).off("click").on("click", function () {
            if (curElem.onChatBoxContactButtonsClick && typeof curElem.onChatBoxContactButtonsClick == 'function') {
                var response = curElem.onChatBoxContactButtonsClick({
                    "buttonType": "BLOCK",
                    "receiverID": userId,
                    "checkSum": profileChecksum,
                    "trackingParams": chatConfig.Params.trackingParams["BLOCK"],
                    "extraParams": {
                        "ignore": 1
                    },
                    "receiverJID": $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                    "nick": nick
                });
                if (response != false) {
                    if (response.responseMessage == "Successful") {
                        enableClose = true;
                        //curElem._removeFromListing('removeCall2', userId);
                        sessionStorage.setItem("htmlStr_" + userId, $('chat-box[user-id="' + userId + '"] .chatMessage').html());
                        $('chat-box[user-id="' + userId + '"] .chatMessage').html('<div id="blockText" class="pos-rel wid90p txtc colorGrey padall-10">You have blocked this user</div><div class="pos-rel fullwid txtc mt20"><div id="undoBlock" class="padall-10 color5 disp_ib cursp">Undo</div></div>');
                        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                        //enableClose = true;
                        $('chat-box[user-id="' + userId + '"] .nchatic_3').css('pointer-events', "none");
                        
                        setTimeout(function () {
                            if (enableClose == true) {
                                curElem._scrollDown($('chat-box[user-id="' + userId + '"]'), "remove");
                            }
                        }, 5000);
                        $('chat-box[user-id="' + userId + '"] #undoBlock').off("click").on("click", function () {
                            //var profileChecksum = $(".chatlist li[id*='" + userId + "']").attr("data-checks");
                            //console.log("undo done");
                            if (curElem.onChatBoxContactButtonsClick && typeof curElem.onChatBoxContactButtonsClick == 'function') {
                                var response = curElem.onChatBoxContactButtonsClick({
                                    "buttonType": "UNBLOCK",
                                    "receiverID": userId,
                                    "checkSum": profileChecksum,
                                    "trackingParams": chatConfig.Params.trackingParams["UNBLOCK"],
                                    "extraParams": {
                                        "ignore": 0
                                    },
                                    "receiverJID": $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                                    "nick": nick
                                });
                                if (response != false) {
                                    if (response.responseMessage == "Successful") {
                                        //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                                        enableClose = false;
                                        var htmlStr = sessionStorage.getItem("htmlStr_" + userId);
                                        $('chat-box[user-id="' + userId + '"] .chatMessage').html(htmlStr);
                                        if($('chat-box[user-id="' + userId + '"] #rosterDeleteMsg_'+ userId + '').length != 0){
                                            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                                        }
                                        curElem._reActivateChatBoxAfterUnblock(userId);
                                        curElem._enableChatTextArea($('chat-box[user-id="' + userId + '"]').attr("data-contact"),userId,getMembershipStatus());
                                        $('chat-box[user-id="' + userId + '"] .nchatic_3').css('pointer-events', "auto");
                                    } else {
                                        $('chat-box[user-id="' + userId + '"] #undoBlock').html(response.responseMessage);
                                    }
                                } else {
                                    $('chat-box[user-id="' + userId + '"] #undoBlock').html("Error");
                                }
                            }
                        });
                    } else {
                        //var htmlStr = sessionStorage.getItem("htmlStr_" + userId);
                        $('chat-box[user-id="' + userId + '"] #chatBoxErr').remove();
                        $('chat-box[user-id="' + userId + '"] .chatMessage').append("<div class='color5 pos-rel txtc fullwid nchatm85 mb20' id='chatBoxErr'>" + response.responseMessage + "</div>");
                        //$(this).html(response.responseMessage);
                    }
                } else {
                    $('chat-box[user-id="' + userId + '"] #chatBoxErr').remove();
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append("<div class='color5 pos-rel txtc fullwid nchatm85 mb20' id='chatBoxErr'>Something went wrong,please try later</div>");
                }
            }
        });
    },
    //reactivate chat box contact engine action buttons after unblock in case user does not come again in list
    _reActivateChatBoxAfterUnblock:function(userId){
        var curElem = this;
        setTimeout(function(){
            var group = $('chat-box[user-id="' + userId + '"]').attr("group-id");
            if(typeof group!= "undefined" && curElem._groupBasedConfig[group]["reListCreationAfterUnblock"]==false){
                if($('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated")=="false" && $('chat-box[user-id="' + userId + '"] #rosterDeleteMsg_'+ userId + '').length == 0){
                    //console.log("enabling chat div");
                    $('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated","true");
                    curElem._setChatBoxInnerDiv(userId, $('chat-box[user-id="' + userId + '"]').attr("data-contact"));
                }
            }
        },4000);
    },
    _bindUnblock: function (userId) {},
    onSendingMessage: null,
    onChatBoxContactButtonsClick: null,
    storeMessagesInLocalHistory: function(selfJID,other,newMsg,type){
        if(localStorageExists){
            //console.log(newMsg);
            var oldMessages = JSON.parse(localStorage.getItem('chatMsg_'+selfJID+'_'+other));
            if(type == 'send' || type == 'receive'){
                if(typeof oldMessages == "undefined" || oldMessages == '' || oldMessages == null){
                    oldMessages = [];
                }
                    oldMessages.unshift(newMsg);    
                }
            else if(type == 'history'){
                if(typeof oldMessages == "undefined" || oldMessages == '' || oldMessages == null){
                    oldMessages = newMsg;
                }
                else{
                    $.each(newMsg,function(key,val){
                       oldMessages.push(val); 
                    });
                }

                //newMsg.unshift(oldMessages);
            }
            localStorage.setItem('chatMsg_'+selfJID+'_'+other,JSON.stringify(oldMessages));
        }
    },
    //sending chat
    _bindSendChat: function (userId) {
        //console.log("_bindSendChat");
        var _this = this,
            that = this,
            messageId,
            jid = $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
            out = 1;
        var selfJID = getConnectedUserJID();
        $('chat-box[user-id="' + userId + '"] textarea').focusout(function () {
            //that._chatLoggerPlugin("focus out to " + jid);
            out = 1;
            //fire event typing paused
            sendTypingState(selfJID, jid, "paused");
        });
        $('chat-box[user-id="' + userId + '"] textarea').keyup(function (e) {
            var curElem = this;
            if ($(this).val().length >= 1 && out == 1) {
                //that._chatLoggerPlugin("typing start");
                out = 0;
                //fire event typing start
                sendTypingState(selfJID, jid, "composing");
            }
            if (e.keyCode == 13 && e.shiftKey && $(this).val().length == 1) {
                $(this).val("");
            } else if (e.keyCode == 13 && !e.shiftKey) {
                var text = $(this).val(),
                    textAreamElem = this;
                //console.log("text before", text);
                text = $("<div/>").html(text).text();
                var proceed = true;
                if(!text.replace(/\s/g, '').length) {
                    proceed = false;
                    _this._scrollToBottom(userId);
                }
                $(textAreamElem).val("").css("height", "24px");
                
                if (text.length > 1 && proceed == true) {
                    var superParent = $(this).parent().parent(),
                        timeLog = new Date().getTime();
                    var finalStr = "";
                    for (var i = 0, len = text.length; i < len-1; i++) {
                        if(text.charCodeAt(i) == 10) {
                            finalStr += "<br />"
                        } else {
                            finalStr += text[i];
                        }
                    }
                    //console.log(finalStr);
                    text = finalStr;
                    $(superParent).find("#initChatText,#sentDiv,#chatBoxErr").remove();
                    $(superParent).find(".chatMessage").css("height", "246px").append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id ="tempText_' + userId + '_' + timeLog + '" class="talkText">' + text + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
                    if ($(superParent).find("#sendInt").length != 0) {
                        //$(superParent).find(".chatMessage").append("<div class='pos-rel fr pr10' id='interestSent'>Your interest has been sent</div>");
                        $(superParent).find("#initiateText,#chatBoxErr").remove();
                        //$(superParent).find("#sendInt").remove();
                    }
                    var height = $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).height();
                    $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).next().css("margin-top", height);
                    _this._scrollToBottom(userId);
                    //fire send chat query and return unique id
                    setTimeout(function () {
                        out = 1;
                        sendTypingState(selfJID, jid, "paused");
                        if (_this.onSendingMessage && typeof (_this.onSendingMessage) == "function") {
                            var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
                            var profileChecksum = $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("data-checks");
                            if($(".chatlist li[id='" + userId + "_" + groupId + "']").length == 0){
                                profileChecksum = $('chat-box[user-id="' + userId + '"]').attr("data-checks");
                            }
                            var msgSendOutput = _this.onSendingMessage(text, $('chat-box[user-id="' + userId + '"]').attr("data-jid"), profileChecksum, $('chat-box[user-id="' + userId + '"]').attr("data-contact"));
                            //console.log("got response",msgSendOutput);
                            
                            messageId = msgSendOutput["msg_id"];
                            //that._chatLoggerPlugin("handling output of onSendingMessage in plugin");
                            if (messageId) {
                                $("#tempText_" + userId + "_" + timeLog).attr("id", "text_" + userId + "_" + messageId);
                            }
                            //console.log("sent");
                            //console.log(msgSendOutput);
                            var newMsg = {
                                'SENDER': selfJID.split('@')[0],
                                'RECEIVER': userId,
                                'DATE': '',
                                'MESSAGE': text,
                                'CHATID': messageId,
                                'ID': ''
                            };
                            
                            if (msgSendOutput["sent"] == false || msgSendOutput["cansend"] == false) {
                                var error_msg = msgSendOutput['errorMsg'] || "Something went wrong";
                                $('chat-box[user-id="' + userId + '"] #restrictMessgTxt').remove();
                                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="restrictMessgTxt" class="color5 pos-rel fr txtc wid90p mt15">' + error_msg + '</div>').addClass("restrictMessg2");
                                if ($(superParent).find("#sendInt").length != 0) {
                                    $(superParent).find("#sendInt").remove();
                                    $(superParent).find(".chatMessage").find("#sentDiv").remove();
                                }
                                if (msgSendOutput["sent"] == true) {
                                    //console.log("marking sent");
                                    _this._changeStatusOfMessg(messageId, userId, "recieved");
                                    //_this.storeMessagesInLocalHistory(selfJID.split('@')[0],userId,newMsg,'send');
                                }
                                if (msgSendOutput["cansend"] == false) {
                                    $(curElem).prop("disabled", true);
                                }
                            } else {
                                //console.log("Nits","in else");
                                if (msgSendOutput["sent"] == true) {
                                    //console.log("nits",$(superParent));
                                    if ($(superParent).find("#sendInt").length != 0 || msgSendOutput['eoi_sent'] == true) {
                                        //console.log("appending intsent msg ankita");
                                        if($(superParent).find("#sendInt").hasClass("notSendInterestDiv") == false || msgSendOutput['eoi_sent'] == true){
                                            //console.log("Append yourinterest has been sent");
                                            $(superParent).find(".chatMessage").append("<div  class='inline_txt pos-rel fr pr10' id='interestSent'>Your interest has been sent</div>");
                                        }
                                        //console.log("Remove sentDiv");
                                        $(superParent).find(".chatMessage").find("#sentDiv").remove();
                                        //console.log("yesssssssssssssssss");
                                        //$(superParent).find("#initiateText,#chatBoxErr").remove();
                                        $(superParent).find("#sendInt").remove();
                                        $(superParent).find("#interestSent").removeClass("disp-none");
                                    }
                                    //msg sending success,set single tick here
                                    $(superParent).find("#sendDiv").remove();
                                    //$(superParent).find("#interestSent").removeClass("disp-none");
                                    _this._changeStatusOfMessg(messageId, userId, "recieved");
                                    //_this.storeMessagesInLocalHistory(selfJID.split('@')[0],userId,newMsg,'send');
                                }
                                if (msgSendOutput["cansend"] == true) {
                                    $(curElem).prop("disabled", false);
                                }
                            }
                            if(msgSendOutput["sent"] == true){
                                var currTime = (new Date()).getTime(),lastMsgTime=localStorage.getItem(loggedInJspcUser+"_sentMsgRefTime");
                                if(lastMsgTime == undefined || (currTime - lastMsgTime) > _this._sentMsgRefTime){
                                    reActivateNonRosterPolling("chatting");
                                    localStorage.setItem(loggedInJspcUser+"_sentMsgRefTime",currTime);
                                }
                            }
                        }
                    }, 50);
                }
            }
        });
        delete that;
    },
    //binding click on extra popup username listing
    _bindExtraUserNameBox: function (userId) {
        var curElem = this;
        $('#extra_'+userId+" .extraUsername").on("click", function () {
            curElem._scrollDown($(".extraPopup"), "retain_extra");
            setTimeout(function () {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                originalElem = $('chat-box[user-id="' + username + '"]'),
                //status = $("chat-box[user-id='" + username + "'] .chatBoxBar .onlineStatus").html(),
                chatHtml = $(originalElem).find(".chatMessage").html(),
                jid = $('chat-box[user-id="' + username + '"]').attr("data-jid");
            var enableStatus = false;
            if($(originalElem).find("textarea").prop("disabled") == false) {
                enableStatus = true;
            }
            pcheckSum = $('chat-box[user-id="' + username + '"]').attr("data-checks"),
                groupId = $('chat-box[user-id="' + username + '"]').attr("group-id");
                curElem._changeLocalStorage("remove",username,"","");
            var status = "offline";
            if($("#"+username+"_"+groupId).length != 0) {
                if($("#"+username+"_"+groupId+" .nchatic5").length != 0){
                    status = "online";
                }
            }
            if($("#"+username+"_"+groupId).length != 0 ) {
                //console.log("append call after click event _bindExtraUserNameBox");
                curElem._appendChatBox(username, status, jid, pcheckSum, groupId,"noHis");
                $(originalElem).remove();
                var chatBrowser = navigator.userAgent;
                if (chatBrowser.indexOf("Firefox") > -1 || chatBrowser.indexOf("Mozilla") > -1) {
                    setTimeout(function(){
                        $("chat-box[user-id='" + username + "'] .chatMessage").html("");
                        $("chat-box[user-id='" + username + "'] .chatMessage").html(chatHtml);
                        //console.log("chatHtml",chatHtml);
                        $("#extra_"+username).remove();
                        //$(this).closest(".extraChatList").remove();
                        curElem._scrollUp($('chat-box[user-id="' + username + '"]'), "297px","noAnimate");
                        if(enableStatus == true){
                           $("chat-box[user-id='" + username + "'] textarea").prop("disabled",false);
                        }
                    },50);
                }
                else{
                    $("chat-box[user-id='" + username + "'] .chatMessage").html("");
                    $("chat-box[user-id='" + username + "'] .chatMessage").html(chatHtml);
                    //console.log("chatHtml",chatHtml);
                    $("#extra_"+username).remove();
                    curElem._scrollUp($('chat-box[user-id="' + username + '"]'), "297px","noAnimate");
                    if(enableStatus == true){
                        $("chat-box[user-id='" + username + "'] textarea").prop("disabled",false);
                    }
                }
                
                
                //adding data in extra popup 
                var len = $("chat-box").length,
                    value = parseInt($(".extraNumber").text().split("+")[1]),
                    finalVar = len - 1 - (value - 1),
                    data = $($("chat-box")[finalVar]).attr("user-id"),
                    dataAdded = false;
                $(".extraChatList").each(function (index, element) {
                    var id = $(this).attr("id").split("_")[1];
                    if (id == data) {
                        dataAdded = true;
                    }
                });
                if (dataAdded == false) {
                    curElem._addDataExtraPopup(data);
                    curElem._bindExtraPopupUserClose($("#extra_" + data + " .nchatic_4"));
                }
                var bubbleData = [];
                if(localStorage.getItem("bubbleData_new")) {
                    bubbleData = JSON.parse(localStorage.getItem("bubbleData_new"));
                }
                var indexToBeRemoved;
                $.each(bubbleData, function(index, elem){
                    if(elem.userId == username) {
                        indexToBeRemoved = index;
                    }
                });
                if(indexToBeRemoved != undefined) {
                    bubbleData.splice(indexToBeRemoved,1);
                }
                localStorage.setItem("bubbleData_new", JSON.stringify(bubbleData));
            } else {
                $(this).next().click();
            }
        });
    },
    //binding close button on extra popup username listing
    _bindExtraPopupUserClose: function (elem) {
        var curElem = this;
        $(elem).off("click").on("click", function () {
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1];
            $('chat-box[user-id="' + username + '"]').remove();
            $(this).closest(".extraChatList").remove();
            var value = parseInt($(".extraNumber").text().split("+")[1]);
            if (value == 1) {
                $(".extraChats , .extraPopup").remove();
            } else {
                $(".extraNumber").text("+" + (value - 1));
            }
            curElem._changeLocalStorage("remove",username,"","");
        });
    },
    //adding data in extra popup
    _addDataExtraPopup: function (data) {
        var userShowName = "",curElem = this;
        if($('chat-box[user-id="'+data+'"] .downBarUserName').length != 0) {
            //userShowName = $('chat-box[user-id="'+data+'"] .downBarUserName').html().split("<div")[0];
            userShowName = $('chat-box[user-id="'+data+'"] .js-chatBoxTopName').html();
        }
        else{
            var output = curElem.checkForNodePresence(data);
            if(output["exists"] == true){
                var groupID = output["groupID"];
                userShowName = $("#"+data+"_"+groupID+" div").html();
            }
        }
        
        $(".extraPopup").append('<div id="extra_' + data + '" class="extraChatList pad08"><div class="extraUsername cursp colrw minWid65 disp_ib pad8_new fontlig f14">' + userShowName + '</div><i class="nchatspr fr nchatic_4 cursp disp_ib mt6 ml10"></i><div class="pinkBubble scir disp_ib padall-10 fr disp-none"><span class="noOfMessg f13 pos-abs">1</span></div></div>');
        //$("#extra_" + data + " .pinkBubble").hide();
        curElem._bindExtraUserNameBox(data);
        setTimeout(function () {
           var bubbleNumber = $('chat-box[user-id="' + data + '"] .chatBoxBar .pinkBubble2 span').html();
            $("#extra_" + data + " .pinkBubble span").html(bubbleNumber);
            if ($("#extra_" + data + " .pinkBubble span").html() > 0) {
                $("#extra_" + data + " .pinkBubble").removeClass("disp-none");
            } 
        }, 500);
        
    },

    //append chat box on page
    _appendChatBox: function (userId, status, jid, pcheckSum, groupId,hisStatus) {
        var strHtm = '<chat-box group-id="' + groupId + '" data-nodeMigrated="false" data-paidInitiated="false" data-jid="' + jid + '" status-user="' + status + '" user-id="' + userId + '" data-checks="' + pcheckSum+'"';
        if(hisStatus == "noHis"){
            strHtm += 'his-status="not"';
        }
        strHtm += '></chat-box>';
        //console.log('final str in _appendChatBox',strHtm);
        $("#chatBottomPanel").prepend(strHtm);
        
    },
    //get group id from opened chat box if exists for unblock
    _fetchChatBoxGroupID: function (userId) {
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            return $('chat-box[user-id="' + userId + '"]').attr('group-id');
        } else {
            return null;
        }
    },
    //create side panel of extra chat
    _createSideChatBox: function () {
        //console.log("_createSideChatBox");
        var curElem = this;
        $(curElem._chatBottomPanelID).append('<div class="extraChats cursp pos_abs nchatbtmNegtaive wid30 hgt43 bg5"><div class="extraNumber colrw opa50">+1</div><div><div class="extraPopup pos_abs l0 nchatbtmNegtaive wid170 bg5"><div>');
        var leftCss = (curElem._bottomPanelWidth - $('chat-box').length * 250)-32;
        $(".extraChats").css("left", leftCss);
        curElem._scrollUp($(".extraChats"), "0px");
        //adding data in extra popup 
        var len = $("chat-box").length - 1,
            data = $($("chat-box")[len]).attr("user-id");
        this._addDataExtraPopup(data);
        //binding extra chat small icon click to view popup
        $(".extraChats").off("click").on("click", function () {
            var len = $("chat-box").length,
                value = parseInt($(".extraNumber").text().split("+")[1]),
                position = len - value - 1;
            curElem._scrollDown($($('chat-box')[position]), "retain");
            $(".extraPopup").animate({
                bottom: "48px"
            });
            setTimeout(function () {
                $(".extraChats").css("padding-top", "11px");
            }, 300);
        });
        //console.log("121w33");
    },
    _getChatBoxType: function (userId, groupID, key) {
        //this._chatLoggerPlugin("in _getChatBoxType");
        var curElem = this;
        //var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        //this._chatLoggerPlugin($(".chatlist li[id='" + userId + "_" + groupID + "']").attr("id").split("_")[1]);
        //var groupID = $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("id").split("_")[1];
        //this._chatLoggerPlugin("ankita" + groupID + "-" + curElem._groupBasedChatBox[groupID]);
        var chatBoxType;
        var oldChatBoxType = $('chat-box[user-id="' + userId + '"]').attr("data-contact");
        if (typeof key == "undefined" || key != "updateChatBoxType") {
            //this._chatLoggerPlugin("in case a");
            chatBoxType = curElem._contactStatusMapping[curElem._groupBasedChatBox[groupID]]["key"];
        } else {
            //this._chatLoggerPlugin("in case b");
            switch (groupID) {
            case chatConfig.Params.categoryNames["Acceptance"]: //acceptance from 
                chatBoxType = curElem._contactStatusMapping["pog_interest_accepted"]["key"];
                break;
            case chatConfig.Params.categoryNames["Interest Received"]:
                chatBoxType = curElem._contactStatusMapping["pg_acceptance_pending"]["key"];
                break;
            case chatConfig.Params.categoryNames["Interest Sent"]:
                chatBoxType = curElem._contactStatusMapping["pog_acceptance_pending"]["key"];
                break;
            default:
                chatBoxType = curElem._contactStatusMapping[curElem._groupBasedChatBox[groupID]]["key"];
                break;
            }
        }
        if (typeof chatBoxType == "undefined") {
            chatBoxType = curElem._contactStatusMapping["none_applicable"]["key"];
        }
        //this._chatLoggerPlugin("chatboxtype--" + chatBoxType);
        $('chat-box[user-id="' + userId + '"]').attr("group-id", groupID);
        $('chat-box[user-id="' + userId + '"]').attr("data-contact", chatBoxType);
        curElem._changeLocalStorage("changeOrAddGroup",userId,groupID,"");
        return chatBoxType;
    },
    _postChatPanelsBox: function (userId) {
        //console.log("in _postChatPanelsBox");
        var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        var data = [];
        var dataPresent = false;
        
        var curElem = this,membership = getMembershipStatus(); //get membership status
        var chatBoxType = curElem._getChatBoxType(userId, $('chat-box[user-id="' + userId + '"]').attr("group-id"));
        curElem._changeLocalStorage("add",userId,$('chat-box[user-id="' + userId + '"]').attr("group-id"),"open");
        //setTimeout(function() {
        //console.log("From postChatPanelsBox");
        curElem._setChatBoxInnerDiv(userId, chatBoxType);
        curElem._enableChatTextArea($('chat-box[user-id="' + userId + '"]').attr("data-contact"), userId, membership);
        if ($('chat-box[user-id="' + userId + '"] .spinner').length != 0) $('chat-box[user-id="' + userId + '"] .spinner').hide();
        //}, 500);
        $('chat-box[user-id="' + userId + '"] .chatMessage').scroll(function() {
            var height = $(this).scrollTop();
            //if(height <= 10){
            if(height == 0){
                //fetch more history
                var showMoreHistory = $("#moreHistory_"+userId).val(),latestMsgId = $("#moreHistory_"+userId).attr("data-latestMsgId");
                //localMsg = $("#moreHistory_"+userId).attr("data-localMsg");
                if(showMoreHistory == "1" && (latestMsgId/* || localMsg*/)){
                    //console.log("yess on top",height);                   
                    clearTimeout(clearTimedOut);
                    var to_checksum = $("chat-box[user-id='" + userId + "'").attr("data-checks");
                    clearTimedOut = setTimeout(function(){
                        if($("#moreHistory_"+userId).val() == "1"){
                            manageHistoryLoader(userId+"@"+openfireServerName,"show");
                            getChatHistory({
                                "from":getConnectedUserJID(),
                                "to":userId+"@"+openfireServerName,
                                //"messageId":latestMsgId,
                                "extraParams": {
                                    "pogChecksum": to_checksum 
                                }
                            }); 
                        }
                    },500); 
                    
                }
             
            }    
        });
        
    },
    _updateChatPanelsBox: function (userId, newGroupId) {
        var curElem = this,membership=getMembershipStatus();
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            $('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated","true");
            //console.log("setting falg 2",$('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated"));
            
            //this._chatLoggerPlugin("in _updateChatPanelsBox for " + userId);
            //console.log("in _updateChatPanelsBox");
            $('chat-box[user-id="' + userId + '"] #rosterDeleteMsg_'+ userId + '').remove();
            /*if($("#chatHistory_"+userId).length != 0){
                $("#chatHistory_"+userId).show();
            }*/
            var chatBoxType = curElem._getChatBoxType(userId, newGroupId, "updateChatBoxType");
            //console.log("From updatechatepanelsbox");
            curElem._setChatBoxInnerDiv(userId, chatBoxType,"chatBoxUpdate");
            curElem._enableChatTextArea(chatBoxType, userId, membership);
        }
    },
    //update contact status and enable/disable chat in chat box on basis of membership and contact status
    _setChatBoxInnerDiv: function (userId, chatBoxType,operation) {
        //this._chatLoggerPlugin();
        //this._chatLoggerPlugin("in _setChatBoxInnerDiv");
        
        var curElem = this,
            that = this,
            new_contact_state = chatBoxType,
            response,
            checkSum = $("chat-box[user-id='" + userId + "']").attr("data-checks"),
            groupId = $("chat-box[user-id='" + userId + "']").attr("group-id"),
            user_name = $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html(),
            user_jid = $("chat-box[user-id='" + userId + "']").attr("data-jid"),
            hisStatus = $("chat-box[user-id='" + userId + "']").attr("his-status");
        var nick;
        if (checkSum) {
            nick = user_name + "|" + checkSum;
        }
        //console.log("chatBoxType"+chatBoxType);
        if (curElem._contactStatusMapping[chatBoxType]["showHistory"] == true) {
            //console.log("setting moreHistory_");
            $("#moreHistory_"+userId).val("1");
            if(typeof operation == "undefined" || operation != "chatBoxUpdate"){
                //console.log("getting first history",hisStatus);
                if(hisStatus == undefined && hisStatus != "not"){
                    //fetch msg history
                    getChatHistory({
                        "from": getConnectedUserJID(),
                        "to": user_jid,
                        "extraParams": {
                            "pogChecksum": checkSum
                        }
                    },"first_history");
                }
                else if(hisStatus == "not"){
                    $("chat-box[user-id='" + userId + "'").removeAttr("his-status");
                }
            }
        }
        else{
            $("#moreHistory_"+userId).val("0"); 
        }
        //this._chatLoggerPlugin(curElem);
        switch (chatBoxType) {
        case curElem._contactStatusMapping["pg_interest_pending"]["key"]:
            if($('chat-box[user-id="' + userId + '"] .sendInterest').length == 0){
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="sendInterest cursp sendDiv pos-abs color5 mt10 wid70p txtc"><i class="nchatspr nchatic_6 "></i><span class="vertTexBtm"> Send Interest</span></div><div id="sentDiv" class="canShowBlock sendDiv disp-none pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div>');
            }
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            if($('chat-box[user-id="' + userId + '"] #chat_freeMemMsg_'+userId).length == 0 && $('chat-box[user-id="' + userId + '"] #initiateText').length == 0)
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="initiateText" class="color5 pos-rel txtc fullwid nchatm85 mb20">Initiating chat will also send your interest</div>');
            $('chat-box[user-id="' + userId + '"] #sendInt').on("click", function () {
                //console.log("clicked sent interest");
                if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                    response = curElem.onChatBoxContactButtonsClick({
                        "receiverID": userId,
                        "checkSum": checkSum,
                        "trackingParams": chatConfig.Params["trackingParams"]["INITIATE"],
                        "buttonType": "INITIATE",
                        "receiverJID": $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                        "nick": nick
                    });
                    //console.log("SendInt",response);
                    if (response != false) {
                        if (response.responseMessage != "Successful") {
                            //curElem._chatLoggerPlugin($(this));
                            $(this).html(response.responseMessage);
                        } else if (response.buttondetails || response.buttondetails.button) {
                            if (response.actiondetails.errmsglabel) {
                                $(this).html(response.actiondetails.errmsglabel);
                            } else {
                                $(this).find("#sentDiv").removeClass("disp-none");
                                $(this).find("#initiateText,#chatBoxErr,#sendInt").remove();
                                //$(this).remove();
                                new_contact_state = curElem._contactStatusMapping["pog_acceptance_pending"]["key"];
                                $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                                $('chat-box[user-id="' + userId + '"]').attr("group-id", chatConfig.Params.categoryNames["Interest Sent"]);
                            }
                        } else {
                            $(this).html(response.actiondetails.errmsglabel);
                        }
                    } else {
                        $(this).html("Something went wrong.");
                    }
                }
            });
            break;
        case curElem._contactStatusMapping["pog_acceptance_pending"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sendInt,#restrictMessgTxt,#initiateText,#chatBoxErr").remove();
            if($('chat-box[user-id="' + userId + '"] .chatMessage').find("#interestSent").length == 0){
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sentDiv" class="canShowBlock sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div>');

                //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            }
            break;
        case curElem._contactStatusMapping["pg_acceptance_pending"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sendInt,#restrictMessgTxt,#initiateText,#chatBoxErr").remove();
            if($('chat-box[user-id="' + userId + '"] .chatMessage #acceptDeclineDiv').length ==0) {
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="pos-rel wid90p txtc colorGrey padall-10 notSendInterestDiv">The member wants to chat</div><div id="acceptDeclineDiv" class="pos-rel fullwid txtc colorGrey mt20"><div id="accept" class="acceptInterest padall-10 color5 disp_ib cursp">Accept</div><div id="decline" class="acceptInterest padall-10 color5 disp_ib cursp">Decline</div></div><div id="acceptTxt" class="pos-rel fullwid txtc color5 mt1">Accept interest to continue chat</div><div id="sentDiv" class="fullwid pos-rel disp-none mt10 color5 txtc notSendInterestDiv">Interest Accepted continue chat</div><div id="declineDiv" class="sendDiv txtc disp-none pos-abs wid80p mt10 color5 declineSent notSendInterestDiv canShowBlock">Interest Declined, you can\'t chat with this user anymore</div>').promise().done(function(){
                    //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                    $('chat-box[user-id="' + userId + '"] #accept').on("click", function () {
                        if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                            response = curElem.onChatBoxContactButtonsClick({
                                "receiverID": userId,
                                "checkSum": checkSum,
                                "trackingParams": chatConfig.Params["trackingParams"]["ACCEPT"],
                                "buttonType": "ACCEPT",
                                "receiverJID": $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                                "nick": nick
                            });
                            if (response != false) {
                                if (response.responseMessage != "Successful") {
                                    //curElem._chatLoggerPlugin($(this));
                                    $(this).html(response.responseMessage);
                                    $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt,#chatBoxErr").remove();
                                } else if (response.buttondetails || response.buttondetails.button) {
                                    if (response.actiondetails.errmsglabel) {
                                        $(this).html(response.actiondetails.errmsglabel);
                                        $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                                    } else {
                                        $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
                                        $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt,#acceptDeclineDiv").remove();
                                        //$(this).remove();
                                        new_contact_state = curElem._contactStatusMapping["both_accepted"]["key"];
                                        //TODO: fire query for accepting request
                                        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                                        $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                                        $('chat-box[user-id="' + userId + '"]').attr("group-id", chatConfig.Params.categoryNames["Acceptance"]);
                                        //console.log("334");
                                        curElem._enableChatTextArea(new_contact_state, userId, getMembershipStatus());
                                        
                                        
                                    }
                                    //console.log("123");
                                    setTimeout(function(){
                                        //console.log("bhgxgs");
                                        curElem._scrollToBottom(userId,"noAnimate");
                                    },1000);
                                } else {
                                    $(this).html(response.actiondetails.errmsglabel);
                                    $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                                }
                            } else {
                                $(this).html("Something went wrong.");
                                $(this).closest(".chatMessage").find("#sendInt, #acceptTxt, #decline").remove();
                            }
                        }
                    });
                    $('chat-box[user-id="' + userId + '"] #decline').on("click", function () {
                        if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                            response = curElem.onChatBoxContactButtonsClick({
                                "receiverID": userId,
                                "checkSum": checkSum,
                                "trackingParams": chatConfig.Params["trackingParams"]["DECLINE"],
                                "buttonType": "DECLINE",
                                "receiverJID": $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                                "nick": nick
                            });
                            if (response != false) {
                                if (response.responseMessage != "Successful") {
                                    //curElem._chatLoggerPlugin($(this));
                                    $(this).html(response.responseMessage);
                                    $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt,#chatBoxErr").remove();
                                } else if (response.buttondetails || response.buttondetails.button) {
                                    if (response.actiondetails.errmsglabel) {
                                        $(this).html(response.actiondetails.errmsglabel);
                                        $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt").remove();
                                    } else {
                                        $(this).closest(".chatMessage").find("#declineDiv").removeClass("disp-none");
                                        $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt,#accept,#acceptDeclineDiv").remove();
                                        //$(this).remove();
                                        new_contact_state = curElem._contactStatusMapping["pg_interest_declined"]["key"];
                                        //TODO: fire query for accepting request
                                        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                                        $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                                        $('chat-box[user-id="' + userId + '"]').attr("group-id", chatConfig.Params.categoryNames["none_applicable"]);
                                        curElem._enableChatTextArea(new_contact_state, userId, getMembershipStatus());
                                    }
                                } else {
                                    $(this).html(response.actiondetails.errmsglabel);
                                    $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt").remove();
                                }
                            } else {
                                $(this).html("Something went wrong.");
                                $(this).closest(".chatMessage").find("#sendInt, #acceptTxt").remove();
                            }
                        }
                    });

                });
            }
            
            break;
        case curElem._contactStatusMapping["pog_interest_accepted"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sentDiv,#restrictMessgTxt,#acceptDeclineDiv,#accept,#acceptTxt").remove();
            if($('chat-box[user-id="' + userId + '"] .acceptRec').length == 0){
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="fullwid pos-rel mt10 color5 txtc fl acceptRec notSendInterestDiv">Interest Accepted continue chat</div>');
            }
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            break;
        case curElem._contactStatusMapping["pog_interest_declined"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sentDiv,#restrictMessgTxt,#acceptTxt").remove();
            if($('chat-box[user-id="' + userId + '"] .declineSent').length == 0){
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="sendDiv txtc pos-abs wid80p mt10 color5 declineSent">Interest Declined, you can\'t chat with this user anymore</div>');
            }
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            break;
        case curElem._contactStatusMapping["pg_interest_accepted"]["key"]:
            $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
            $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            break;
        case curElem._contactStatusMapping["pg_interest_declined"]["key"]:
            $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
            $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            break;
        case curElem._contactStatusMapping["none_applicable"]["key"]:
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            break;
        case curElem._contactStatusMapping["both_accepted"]["key"]:
            $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sendInt, #decline, #acceptTxt").remove();
            break;
        }
    },
    //based on membership and chatboxtype,enable or disable chat textarea in chat box
    _enableChatTextArea: function (chatBoxType, userId, membership) {
        var curElem = this;
        //check for membership status of logged in user
        if (membership == "Paid") {
            if (curElem._contactStatusMapping[chatBoxType]["enableChat"] == true) {
                //console.log("333");
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            }
            else {
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            }
        } else if (membership == "Free") {
            var checkForPaidInitiation = curElem._contactStatusMapping[chatBoxType]["checkForPaidInitiation"];
            if(checkForPaidInitiation == true){
                var hasPaidIntiated = $('chat-box[user-id="' + userId + '"]').attr("data-paidInitiated");
                //console.log("hasPaidIntiated"+hasPaidIntiated);
                if(hasPaidIntiated == "false"){
                    curElem._manageFreeMemCase("show",userId,chatBoxType,hasPaidIntiated);
                }
                else if(hasPaidIntiated == "true"){
                    curElem._manageFreeMemCase("hide",userId,chatBoxType,hasPaidIntiated); 
                }
            }
            else{
                curElem._manageFreeMemCase("hide",userId,chatBoxType,"true");
            }
        }  
    },

    _disableChatTextArea:function(userId){
        $('chat-box[user-id="' + userId + '"]').attr("data-paidInitiated","false");
        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);   
    },

    //show/hide free mem msg
    _manageFreeMemCase:function(type,userId,chatBoxType,hasPaidIntiated){
        //console.log("in _manageFreeMemCase",type);
        if(type == "hide"){
            if($('chat-box[user-id="' + userId + '"] #chat_freeMemMsg_'+userId).length != 0){
                    $('chat-box[user-id="' + userId + '"] #chat_freeMemMsg_'+userId).remove();
                   
                }
                if(typeof hasPaidIntiated != "undefined" && hasPaidIntiated == "true"){
                    //console.log("444");
                    $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                }
                //console.log("hidden");
        }
        else if(type == "show"){
            //console.log("show 1");
            $('chat-box[user-id="' + userId + '"] #initiateText').remove();
            if($('chat-box[user-id="' + userId + '"] #chat_freeMemMsg_'+userId).length == 0){
                if($('chat-box[user-id="' + userId + '"] #acceptDeclineDiv').length == 0){
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="chat_freeMemMsg_'+userId+'" class="pos-abs fullwid txtc colorGrey mt120">Only paid members can start chat<div  class="becomePaidMember_chat color5 cursp"><a href="/profile/mem_comparison.php" class = "cursp js-colorParent">Become a Paid Member</a></div></div>');
                }
                //console.log("232");
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            }
            //console.log("show 2");
        }
    },

    //update status in chat box top
    _updateStatusInChatBox: function (userId, chat_status) {
        //this._chatLoggerPlugin("_updateStatusInChatBox for "+userId+"-"+chat_status+"--"+$('chat-box[user-id="' + userId + '"]').length);
        var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        if ($(".chatlist li[id='" + userId + "_" + groupId + "']").length != 0) {
            //console.log("updating 1",chat_status);
            $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("data-status", chat_status);
        }
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            //this._chatLoggerPlugin("change to " + chat_status);
            $("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(chat_status);
        }
    },
    _bottomPanelWidth: 0,
    //appending chat box
    _chatPanelsBox: function (userId, status, jid, pcheckSum, groupId) {
        //this._chatLoggerPlugin("pcheckSum", pcheckSum);
        //console.log("in _chatPanelsBox");
        var curElem = this;
        var output = curElem.checkForNodePresence(userId);
        if(output && output["exists"] == true && output["groupID"]){
            groupId = output["groupID"];
        }
        if ($(".chatlist li[id='" + userId + "_" + groupId + "']").length != 0) {
            if($(".chatlist li[id='" + userId + "_" + groupId + "'] .nchatspr").length != 0){
                status = "online";
            }
            else{
                status = "offline";
            }
        }

        var heightPlus = false,
            bodyWidth = $("body").width();
        /*if ($(curElem._chatBottomPanelID).length == 0) {
            $("body").append("<div id='chatBottomPanel' class='btmNegtaive pos_fix calhgt2 fontlig'></div>");
        }
        var bottomPanelWidth = $(window).width() - $(curElem._parendID).width();
        $(curElem._chatBottomPanelID).css('width', bottomPanelWidth);
        if ($(curElem._chatBottomPanelID).css("bottom") == "-300px") {
            $(curElem._chatBottomPanelID).css("bottom", "0px");
        }*/
        if ($(curElem._chatBottomPanelID).length == 0) {
            $("body").append("<div id='chatBottomPanel' class='btmNegtaive pos_fix calhgt2 z5 fontlig hgt57'></div>");
            curElem._bottomPanelWidth = $(window).width() - $(curElem._parendID).width();
            $(curElem._chatBottomPanelID).css('max-width', curElem._bottomPanelWidth);
            $(curElem._chatBottomPanelID).css("right", $(curElem._parendID).width());
            if ($(curElem._chatBottomPanelID).css("bottom") == "-300px") {
                $(curElem._chatBottomPanelID).css("bottom", "0px");
            }
            if ($(".js-minpanel").length != 0) {
                  $(curElem._chatBottomPanelID).hide();  
            }
        }
        if ($('chat-box[user-id="' + userId + '"]').length == 0) {
            var bodyWidth = curElem._bottomPanelWidth,
                divWidth = ($("chat-box").length + 1) * 250;
            if (divWidth > bodyWidth) {
                //console.log("check");
                if ($(".extraChats").length == 0) {
                    curElem._createSideChatBox();
                } else {
                    curElem._updateSideChatBox();
                }
                curElem._bindExtraPopupUserClose($(".nchatic_4"));
                //curElem._bindExtraUserNameBox();
            }
            //console.log("append call after click event main click");
            curElem._appendChatBox(userId, status, jid, pcheckSum, groupId);
        } else {
            $(".extraChatList").each(function (index, element) {
                var id = $(this).attr("id").split("_")[1];
                if (id == userId) {
                    curElem._scrollDown($(".extraPopup"), "retain_extra");
                    setTimeout(function () {
                        $(".extraChats").css("padding-top", "0px");
                    }, 100);
                    curElem._changeLocalStorage("remove",userId,"","");
                    var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                        originalElem = $('chat-box[user-id="' + username + '"]'),
                        len = $("chat-box").length,
                        value = parseInt($(".extraNumber").text().split("+")[1]),
                        data = $($("chat-box")[len - 1 - value]).attr("user-id"),
                        chatHtml = $(originalElem).find(".chatMessage").html();
                    //console.log("append call after click event listing click");
                    curElem._appendChatBox(username, status, jid, pcheckSum, groupId,"noHis");
                    originalElem.remove();
                    $("chat-box[user-id='" + username + "'] .chatMessage").html("").html(chatHtml);
                    curElem._scrollToBottom(username,"noAnimate");

                    $(this).closest(".extraChatList").remove();
                    curElem._addDataExtraPopup(data);
                    curElem._bindExtraPopupUserClose($("#extra_" + data + " .nchatic_4"));
                }
            });
        }
        if ($(".extraChats").length > 0 && $(".extraPopup ").css("bottom") != "-1000px") {
            curElem._scrollDown($(".extraPopup "), "retain_extra");
            setTimeout(function () {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
        }
    },
    //add data in side panel and update number
    _updateSideChatBox: function () {
        var curElem = this;
        var value = parseInt($(".extraNumber").text().split("+")[1]) + 1,
            len = $("chat-box").length + 1,
            data = $($("chat-box")[len - value - 1]).attr("user-id");
        curElem._addDataExtraPopup(data);
        $(".extraNumber").text("+" + value);
    },
    //creating prototype for chat-box custom element
    _createPrototypeChatBox: function () {
        var elem = this,
            chatBoxProto = Object.create(HTMLElement.prototype),
            userId, status, response;
        chatBoxProto.attachedCallback = function () {
            userId = $(this).attr("user-id");
            this.innerHTML = '<div class="chatBoxBar fullwid hgt57 bg5 pos-rel fullwid"></div><div class="chatArea fullwid fullhgt"><div class="messageArea f13 bg13 fullhgt"><div id="chatMessage_'+userId+'" class="chatMessage pos_abs fullwid scrollxy js-chatBoxHeight"><input type="hidden" value="0" id="moreHistory_'+userId+'" data-latestMsgId="" data-page="0" data-localMsg="0"/><div class="spinner2 disp-none"></div><div id="chatHistory_' + userId + '" class="clearfix"></div><div class="spinner"></div></div></div><div class="chatInput brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" maxlength="'+elem._maxMsgLimit+'" style="width: 220px;" id="txtArea"  class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Write message"></textarea></div></div>';
            $(this).addClass("z5 b297 hgt352 brd_new fr mr7 fullhgt wid240 pos-rel disp_ib");
            status = $(this).attr("status-user");
            elem._appendInnerHtml(userId, status);
        };
        document.registerElement("chat-box", {
            prototype: chatBoxProto
        });
        document.createElement("chat-box");
    },
    //adding innerDiv after creating chatbox
    _appendInnerHtml: function (userId, status) {
        var curElem = this,
            groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        $("#pic_"+userId+"_"+groupId).clone().appendTo($('chat-box[user-id="' + userId + '"] .chatBoxBar'));
        $('chat-box[user-id="' + userId + '"] .chatBoxBar img').attr("id", "pic_" + userId);
        $('chat-box[user-id="' + userId + '"] #txtArea').on("keyup", function () {
            curElem._textAreaAdjust(this);
        });
        $('chat-box[user-id="' + userId + '"] #pic_' + userId).addClass("js-viewProfileBind downBarPic cursp");
        $('chat-box[user-id="' + userId + '"] .chatBoxBar').append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig"><div class="js-viewProfileBind cursp js-chatBoxTopName">' + $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html() + '</div><div class="onlineStatus f11 opa50 mt4"></div></div><div class="iconBar cursp fr padallf_2 disp_ib"><i class="nchatspr nchatic_3"><div class="pos-abs fullBlockTitle disp-none tneg20 bg-white f13 brderinp pad510">Block</div></i><i class="nchatspr nchatic_2 ml10 mr10"><div class="pos-abs fullMinTitle disp-none tneg20_2 bg-white f13 brderinp pad510">Minimize</div></i><i class="nchatspr nchatic_1 mr10"><div class="pos-abs fullCloseTitle disp-none tneg20_3 bg-white f13 brderinp pad510">Close</div></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">0</span></div></div>');
        curElem._bindInnerHtml(userId, status);
    },
    //binding innerDiv after creating chatbox
    _bindInnerHtml: function (userId, status) {
        var curElem = this;
        $('chat-box[user-id="' + userId + '"] .pinkBubble2').hide();
        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
        $('chat-box[user-id="' + userId + '"] .pinkBubble2 span').html("0");
        $("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(status);
        this._bindMaximize($('chat-box[user-id="' + userId + '"] .chatBoxBar'), userId);
        this._bindMinimize($('chat-box[user-id="' + userId + '"] .nchatic_2'));
        this._bindClose($('chat-box[user-id="' + userId + '"] .nchatic_1'));
        this._bindBlock($('chat-box[user-id="' + userId + '"] .nchatic_3'), userId);
        curElem._bindChatPoxPicClick(userId);
        this._postChatPanelsBox(userId);
        this._bindSendChat(userId);
        if($(curElem._minPanelId).length !=0){
            //console.log("11111 apend");
            curElem._scrollDown($('chat-box[user-id="' + userId + '"]'),"min");
        
        }
    },
    //bind click action on chat box pic click
    _bindChatPoxPicClick: function(userId){
        var curElem = this,chatBoxElem= $('chat-box[user-id="' + userId + '"]');
        chatBoxElem.on("click",".js-viewProfileBind",function(event){
			if(chatBoxElem.hasClass('js-minimizedChatBox') === false){
                //console.log("clicked to view profile");
		        event.preventDefault();
		        var profilechecksum = chatBoxElem.attr("data-checks"),
		        groupID = chatBoxElem.attr("group-id"),
		        trackingParamsStr = '';
		        if(typeof groupID != "undefined" && groupID && typeof curElem._categoryTrackingParams[groupID]!= "undefined"){  
		            var trackingParams = curElem._categoryTrackingParams[chatBoxElem.attr("group-id")];
		            if (trackingParams) {
		                $.each(trackingParams, function (key, val) {
		                    trackingParamsStr += '&' + key + '=' + val;
		                });
		            }
		        }
		        if(typeof profilechecksum!= "undefined" && profilechecksum){
		            //console.log("view profile");
		            window.location.href = "/profile/viewprofile.php?profilechecksum="+profilechecksum+trackingParamsStr;
		        }
			}
        });
    },

    _scrollToBottom: function (userId,type) {
        //console.log("type in _scrollToBottom",type);
        if(type == undefined) {
            if(document.getElementById("chatMessage_" + userId) != null){
                var len = document.getElementById("chatMessage_"+userId).scrollHeight;
                $('chat-box[user-id="' + userId + '"] .chatMessage').animate({
                    scrollTop: len
                }, 1000);   
            }
        } else if(type == "noAnimate") {
            setTimeout(function () {
                if(document.getElementById("chatMessage_"+userId) != null){
                    var len = document.getElementById("chatMessage_"+userId).scrollHeight;
                    $('chat-box[user-id="' + userId + '"] .chatMessage').animate({
                        scrollTop: len
                    }, 0);
                }
            }, 100);    
        }
    },
    //append chat history in chat box
    _appendChatHistory: function (selfJID, otherJID, communication,requestType,canChatMore) {
        var self_id = selfJID.split("@")[0],
            other_id = otherJID.split("@")[0],
            latestMsgId="",
            removeFreeMemMsg=false,
            other_username,
            defaultEoiSentMsg,
            defaultEoiRecMsg;
        var curElem = this;
        if ($('chat-box[user-id="' + other_id + '"]').length != 0) { 
            if(curElem._checkForDefaultEoiMsg == true){
                //other_username =  $('chat-box[user-id="' + other_id + '"] .downBarUserName').html(); 
                other_username =  $('chat-box[user-id="' + other_id + '"] .js-chatBoxTopName').html();    
                defaultEoiSentMsg = "Jeevansathi member with profile id "+ self_username +" likes your profile. Please 'Accept' to show that you like this profile.";
                defaultEoiRecMsg = "Jeevansathi member with profile id "+ other_username +" likes your profile. Please 'Accept' to show that you like this profile.";
            }
            var now_mark_unread = false,read_class="nchatic_9";
            if(curElem._setLastReadMsgStorage == false){
                now_mark_unread = true;
                read_class = "nchatic_9";
            }
            else{
                var last_read_msg = fetchLastReadMsgFromStorage(other_id);
                if(last_read_msg == undefined || last_read_msg==""){
                    now_mark_unread = true;
                    read_class = "nchatic_10";
                }
            }
            if(typeof communication != "undefined"){
                $.each(communication, function (key, logObj) {
                    latestMsgId = logObj["id"];
                    //console.log(logObj);
                    if (parseInt(logObj["sender"]) == self_id) {
                        if(logObj["chatId"] == "" && now_mark_unread == false){
                            logObj["chatId"] = generateChatHistoryID("sent");
                            //now_mark_unread = true;
                            //read_class = "nchatic_10";

                        }
                        var last_read_msg = fetchLastReadMsgFromStorage(other_id);
                        //console.log("last_read",last_read_msg);
                        
                        if(curElem._checkForDefaultEoiMsg == false || logObj["message"].indexOf(defaultEoiSentMsg) == -1){
                            //append self sent message
                            logObj["message"] = logObj["message"].replace(/\&lt;br \/\&gt;/g, "<br />");
                            $('chat-box[user-id="' + other_id + '"] .chatMessage').find("#chatHistory_" + other_id).append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id="text_' + other_id + '_' + logObj["chatId"] + '" class="talkText" data-msgid='+logObj["chatId"]+'>' + logObj["message"] + '</div><i class="nchatspr '+read_class+' fr vertM"></i></div>').promise().done(function(){
                                    var len = $('chat-box[user-id="' + other_id + '"] #text_'+other_id+'_'+logObj["chatId"]).height();
                                        
                                    $('chat-box[user-id="' + other_id + '"] #text_'+other_id+'_'+logObj["chatId"]).next().css("margin-top",len);
                            });
                        }
                        if(last_read_msg == logObj["chatId"] && now_mark_unread == false){
                            //console.log("set done");
                            now_mark_unread = true;
                            read_class = "nchatic_10";
                        }
                    } else if (parseInt(logObj["sender"]) == other_id) {
                        if(logObj["chatId"] == ""){
                            logObj["chatId"] = generateChatHistoryID("received");
                        }
                        //check for default eoi message,remove after monday JSI release
                        if(curElem._checkForDefaultEoiMsg == false || logObj["message"].indexOf(defaultEoiRecMsg) == -1){
                            if(removeFreeMemMsg == false){
                                if(typeof logObj["IS_EOI"] == "undefined" || logObj["IS_EOI"] == false){
                                    removeFreeMemMsg = true;
                                    curElem._enableChatAfterPaidInitiates(other_id);
                                }
                            }
                            /*if(logObj["IS_EOI"] == true && requestType == "first_history" && $('chat-box[user-id="' + other_id + '"]').hasClass("js-minimizedChatBox") == false){
                                curElem._handleUnreadMessages($('chat-box[user-id="' + other_id + '"]'),{"msg_id":logObj["CHATID"]});
                            }*/
                            //append received message
                            logObj["message"] = logObj["message"].replace(/\&lt;br \/\&gt;/g, "<br />");
                            $('chat-box[user-id="' + other_id + '"] .chatMessage').find("#chatHistory_" + other_id).append('<div class="clearfix"><div class="leftBubble"><div class="tri-left"></div><div class="tri-left2"></div><div id="text_' + other_id + '_' + logObj["chatId"] + '" class="talkText received_read" data-msgid=' + logObj["chatId"] + '>' + logObj["message"] + '</div></div></div>');
                        }
                    }
                });
                if(requestType == "first_history"){
                    curElem._scrollToBottom(other_id);
                }
            }
            if(latestMsgId != ""){
                //console.log("setting");
                $('chat-box[user-id="' + other_id + '"]').find("#moreHistory_"+other_id).attr("data-latestMsgId",latestMsgId);
            }
            if(typeof canChatMore != "undefined" && canChatMore == "false"){
                //console.log("set as free");
                curElem._disableChatTextArea(other_id);
            }
            setTimeout(function(){
                if(requestType == "first_history"){
                    curElem.preventSiteScroll(other_id);
                }  
            },1000);
        }
    },
    //append self sent message on opening window again
    _appendSelfMessage: function (message, userId, uniqueId, status) {
        //console.log("appending self sent msg");
        var curElem = this;
        /*if ($('chat-box[user-id="' + userId + '"]').length == 0) {
            $(".profileIcon[id^='" + userId + "']")[0].click();
        }*/
        if ($('chat-box[user-id="' + userId + '"]').length != 0){
            if ($('chat-box[user-id="' + userId + '"] .chatMessage').find("#text_" + userId + uniqueId).length == 0) {
                message = message.replace(/\&lt;br \/\&gt;/g, "<br />");
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id="text_' + userId + '_' + uniqueId + '" class="talkText" data-msgid='+uniqueId+'>' + message + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
                var len = $('chat-box[user-id="' + userId + '"] .talkText').length - 1,
                    height = $($('chat-box[user-id="' + userId + '"] .talkText')[len]).height();
                $($('chat-box[user-id="' + userId + '"] .talkText')[len]).next().css("margin-top", height);
                if (status != "sending") {
                    curElem._changeStatusOfMessg(uniqueId, userId, status);
                }
                curElem._scrollToBottom(userId);
            }
        }
    },
    
    //enable chat for free member if paid initiates
    _enableChatAfterPaidInitiates: function(userId){
        $('chat-box[user-id="' + userId + '"]').attr("data-paidInitiated","true");
        if($('chat-box[user-id="' + userId + '"] #chat_freeMemMsg_'+userId).length != 0){
            //console.log("removing");
            $('chat-box[user-id="' + userId + '"] #chat_freeMemMsg_'+userId).remove();
        }
        //console.log("222");
        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
    },

    //add meesage recieved from another user
    _appendRecievedMessage: function (message, userId, uniqueId,msg_type) {
        var curEle = this,
            that = this;
        var selfJID = getConnectedUserJID(); 
        selfJID = selfJID.split('@')[0];
        var newMsg = {
            'SENDER': userId,
            'RECEIVER': selfJID,
            'DATE': '',
            'MESSAGE': message,
            'CHATID': uniqueId,
            'ID': ''
        };
        //console.log("in _appendRecievedMessage");
        //append received message in chatbox
        
        if (message == chatConfig.Params[device].rejectObsceneMsg){
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="restrictMessgTxt" class="color5 pos-rel fr txtc wid90p mt15">' + message + '</div>').addClass("restrictMessg2");
            $("#text_"+userId+"_"+uniqueId).next().attr('obscene',true);
        }
        else if (typeof message != "undefined" && message != "") {
            var appendMsg = true;
            //if chat box is not opened
            if ($('chat-box[user-id="' + userId + '"]').length == 0) {
                //console.log("msg from history1",uniqueId);
                appendMsg = false; //as this msg already exists in history
                var checkInterval = 0;
                var chatBoxOpenInterval = setInterval(function(){
                    //console.log("checking",$(".profileIcon[id^='" + userId + "']"));
                    if($(".profileIcon[id^='" + userId + "']").length == 1){
                        $(".profileIcon[id^='" + userId + "']")[0].click();
                        if(appendMsg == false){
                            playChatNotificationSound();
                        }
                        setTimeout(function(){
                            if ($(".js-minpanel").length != 0) {
                                appendMsg = true;
                                $('chat-box[user-id="' + userId + '"] .nchatic_2').click();
                            }
                            else if($('#extra_'+userId).length == 0){
                                //console.log("add here....uncomment",uniqueId);
                                //mark this msg read on sender side
                                curEle._handleUnreadMessages($('chat-box[user-id="' + userId + '"]'),{"msg_id":uniqueId});
                            }
                        },500);
                        //console.log("clear interval");
                        clearInterval(chatBoxOpenInterval);
                    }
                    else{
                        checkInterval = checkInterval+1;
                        if(checkInterval == 15){
                            clearInterval(chatBoxOpenInterval);
                        }
                    }    
                },700);
                    
                

            }

            if(typeof msg_type != "undefined" && msg_type == "accept"){
                curEle._enableChatAfterPaidInitiates(userId);
            }
            //console.log("appendMsg",appendMsg);
            if(appendMsg == true){
                message = message.replace(/\&lt;br \/\&gt;/g, "<br />");
                //adding msg in chat area
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="clearfix"><div class="leftBubble"><div class="tri-left"></div><div class="tri-left2"></div><div id="text_' + userId + '_' + uniqueId + '" class="talkText received" data-msgid=' + uniqueId + '>' + message + '</div></div></div>');
            }
            else{
                //console.log("marking as read",uniqueId);
                //mark this msg read on sender side
                curEle._handleUnreadMessages($('chat-box[user-id="' + userId + '"]'),{"msg_id":uniqueId});
            }
            //check for 3 messages and remove binding
            if ($('chat-box[user-id="' + userId + '"] .chatMessage').hasClass("restrictMessg2")) {
                $('chat-box[user-id="' + userId + '"] .chatMessage').find("#restrictMessgTxt").remove();
                //console.log("11");
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            }
            //mark all unread msgs as read
            curEle._handlePreUnreadMessages(userId);
            var val;
            //adding bubble for minimized tab
                setTimeout(function(){
                    
                //console.log("InTimeout",$('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin"));
                    if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                        val = parseInt($('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html()) + 1;
                        $('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html(val);
                        $('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2').show();
                        //$('chat-box[user-id="' + userId + '"] .chatMessage').find('#text_' + userId + '_' + uniqueId).addClass("received");
                        var bubbleData = [];
                        if(localStorage.getItem("bubbleData_new")) {
                            bubbleData = JSON.parse(localStorage.getItem("bubbleData_new"));
                        }
                        var dataPresent = false;
                        $.each(bubbleData, function(index, elem){
                            if(elem.userId == userId) {
                                elem.bCount = val;
                                dataPresent = true;
                            }
                        });
                        if(dataPresent == false){
                            var obj = {userId:userId,bCount:val};
                            bubbleData.push(obj);
                        }
                        localStorage.setItem("bubbleData_new", JSON.stringify(bubbleData));
                    } else {
                        if($('#extra_'+userId).length == 0){
                            //$('chat-box[user-id="' + userId + '"] .chatMessage').find('#text_' + userId + '_' + uniqueId).addClass("received")
                            curEle._handleUnreadMessages($('chat-box[user-id="' + userId + '"]'));
                           
                        }
                    }
                    //adding bubble for side tab
                    if ($("#extra_" + userId + " .pinkBubble").length != 0) {
                        val = parseInt($("#extra_" + userId + " .pinkBubble span").html())+1;
                        $("#extra_" + userId + " .pinkBubble span").html(val);
                        $("#extra_" + userId + " .pinkBubble").show();
                         var bubbleData = [];
                            if(localStorage.getItem("bubbleData_new")) {
                                bubbleData = JSON.parse(localStorage.getItem("bubbleData_new"));
                            }
                            var dataPresent = false;
                            $.each(bubbleData, function(index, elem){
                                if(elem.userId == userId) {
                                    elem.bCount = val;
                                    dataPresent = true;
                                }
                            });
                            if(dataPresent == false){
                                var obj = {userId:userId,bCount:val};
                                bubbleData.push(obj);
                            }
                            localStorage.setItem("bubbleData_new", JSON.stringify(bubbleData));
                    }
                    //change count of online matches panel
                    if ($(".js-minpanel").length != 0) {
                        var count = curEle._onlineUserMsgMe();
                        //that._chatLoggerPlugin("count - " + count);
                    }
                    curEle._scrollToBottom(userId);
                    //this.storeMessagesInLocalHistory(selfJID, userId, newMsg, 'receive');
                },500);
            //play sound on receiving the message
            if(appendMsg == true){
                playChatNotificationSound();
            }
        }
            

    },
    rosterDeleteChatBoxReponse: null,
    //disable chat box on roster item deletion
    _disableChatPanelsBox:function(userId){
        //console.log("in disablechatPanelsBox");
        var curElem = this;
        if($('chat-box[user-id="' + userId + '"]').length != 0){
            if($('chat-box[user-id="' + userId + '"] .chatMessage #undoBlock').length == 0 && $('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated") == "false"){
                //console.log("disabling1",$('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated"));
                setTimeout(function(){
                    if($('chat-box[user-id="' + userId + '"] .chatMessage #undoBlock').length == 0 && $('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated") == "false"){
                       //console.log("disabling2",$('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated"));
                        var found = false;
                        $.each(curElem._rosterGroups,function(key,groupId){
                            if($(".chatlist li[id='" + userId + "_" + groupId + "']").length != 0){
                                found = true;
                            }
                            if(found == false){
                                if($('chat-box[user-id="' + userId + '"]').length != 0){
                                    
                                    $('chat-box[user-id="' + userId + '"] .chatMessage').html("");
                                    if($('chat-box[user-id="' + userId + '"] #rosterDeleteMsg_'+ userId + '').length == 0){
                                        //$('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="rosterDeleteMsg_'+userId+'" class="pt20 txtc color5">'+curElem._rosterDeleteChatBoxMsg+'</div>');
                                        var currentT = new Date().getTime();
                                        if((currentT - rosterMsgTime)> 500){
                                            var selfJID = getConnectedUserJID();
                                            curElem.rosterDeleteChatBoxReponse(selfJID,userId);
                                            //console.log("added 1");
                                            rosterMsgTime = currentT;
                                            }
                                        }
                                    
                                    $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                                }
                            }
                        });
                    }  
                },1000);
            }
            //console.log("setting migrated",$('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated"));
            $('chat-box[user-id="' + userId + '"]').attr("data-nodeMigrated","false");
        }
    },

    setListMigratedFlag :function(user_id){
        if($('chat-box[user-id="' + user_id + '"]').length != 0){
            $('chat-box[user-id="' + user_id + '"]').attr("data-nodeMigrated","false");
        }
    },

    //get count of minimized chat boxes with unread messages
    _onlineUserMsgMe: function () {
        var noOfInputs = 0;
        $("chat-box .chatBoxBar .pinkBubble2").each(function (index, element) {
            //console.log(element);
            if ($(this).find(".noOfMessg").html() != 0) {
                noOfInputs++;
            }
        });
        //console.log("noOfInputs",noOfInputs);
       $(".extraChatList .pinkBubble").each(function (index, element) {
           var userId = $(this).parent().attr("id").split("_")[1];
           //console.log("userManvi",userId);
           //console.log("htmlManvi",$('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html());
           if ($(this).find(".noOfMessg").html() != 0 && $('chat-box[user-id="' + userId + '"] .chatBoxBar .pinkBubble2 span').html() == 0) {
                noOfInputs++;
           }
        });
        if(noOfInputs != 0){
            $('.countVal').html(noOfInputs);
            if ($('.showcountmin').hasClass('vishid')) {
                $('.showcountmin').removeClass('vishid');
            } 
        } else {
            $('.showcountmin').addClass('vishid');  
        }
        
        return noOfInputs;
    },
    //handle typing status of message
    _handleMsgComposingStatus: function (userId, msg_state) {
        //this._chatLoggerPlugin("in _handleMsgComposingStatus" + msg_state + userId);
        if (typeof msg_state != "undefined") {
            if (msg_state == 'composing') {
                //localStorage.setItem("status_"+userId, $('chat-box[user-id="' + userId + '"] .onlineStatus').html());
                /*
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    //this._chatLoggerPlugin("yess", $('chat-box[user-id="' + userId + '"] .downBarUserName'))
                    $('chat-box[user-id="' + userId + '"] .downBarUserName').html('<div class="onlineStatus f11 opa50 mt4">typing...</div>');
                
                } else {
                */
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').html("typing...").show();
              //  }
            } else if (msg_state == 'paused' || msg_state == 'gone') {
                var idStatus = "",
                    groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
                //this._chatLoggerPlugin($(".chatlist li[id='" + userId + "_" + groupId + "']").find(".nchatspr"));
                if ($(".chatlist li[id='" + userId + "_" + groupId + "']").find(".nchatspr").length != 0) {
                    idStatus = "online";
                } else {
                    idStatus = "offline";
                }
                $('chat-box[user-id="' + userId + '"] .onlineStatus').html(idStatus);
                 if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').hide(); 
                 }
                /*
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    var userName = $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html();
                    $('chat-box[user-id="' + userId + '"] .downBarUserName').html(userName + '<div class="onlineStatus f11 opa50 mt4 colrw">' + idStatus + '</div>');
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').hide();
                } else {
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').html(idStatus);
                }
                */
            }
        }
    },
    //change from sending status to sent or received read
    _changeStatusOfMessg: function (messgId, userId, newStatus) {
        var curElem = this;
        if (messgId) {
            //this._chatLoggerPlugin("Change status" + newStatus);
            if (newStatus == "recieved") {
                //console.log("marked");
                $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_8").addClass("nchatic_10");
            } else if (newStatus == "recievedRead") {
                $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_8").addClass("nchatic_10");
                setTimeout(function () {
                    $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_10 nchatic_8").addClass("nchatic_9");
                    if(curElem._setLastReadMsgStorage == true){
                        setLastReadMsgStorage(userId,messgId);
                    }
                    curElem._handlePreUnreadMessages(userId);
                }, 500);

            }
        }
    },
    //handle all pre sent and unread msgs as read
    _handlePreUnreadMessages:function(userId){
        if($('chat-box[user-id="' + userId + '"]').length != 0){
            $('chat-box[user-id="' + userId + '"]').find(".nchatic_10").each(function () {
                if($(this).attr("obscene") != "true"){
                    $(this).removeClass("nchatic_10").addClass("nchatic_9");
                }
            });
        }
    },

    onEnterToChatPreClick: null,
    onChatLoginSuccess: null, //function triggered after successful chat login
    //start:login screen
    _startLoginHTML: function () {
        //this._chatLoggerPlugin('_startLoginHTML call');
        var curEle = this;
        if ($(curEle._chatBottomPanelID).length != 0) {
            setTimeout(function () {
                $(curEle._chatBottomPanelID).show();
            }, 1000);
        }
        //user not logged in and coming for first time 
        if (($(this._listingPanelID).length == 0) && (this._loginStatus == "N")) {
            //this._chatLoggerPlugin('case 1');
            $(curEle._loginPanelID).fadeOut('slow', function () {
                curEle._appendLoggedHTML();
            });
        }
        //user was logged earlier in which login is not call'd
        else if (($(this._listingPanelID).length == 0) && (this._loginStatus == "Y")) {
            //this._chatLoggerPlugin('case 2');
            if ($(curEle._loginPanelID).length == 0) {
                //this._chatLoggerPlugin("ankita_1");
                //curEle._appendLoggedHTML();    
            } else {
                //this._chatLoggerPlugin("ankita_2");
                $(curEle._loginPanelID).remove();
                $("#blankPanelLoader").removeClass("disp-none");
                // function () {
                //curEle._appendLoggedHTML();
                //});
            }
        }
        //user logged out from chat in the same session
        else {
            //this._chatLoggerPlugin('case 3');
            $(curEle._loginPanelID).fadeOut('fast', function () {
                $(curEle._listingPanelID).fadeIn('slow');
            });
        }
        //this._chatLoggerPlugin("Login status value");
        //this._chatLoggerPlugin(this._loginStatus);
    },
    //start:function calculate the current postion for hover box
    _calHoverPos: function (param2, param3) {
        var hoverbtm, newTop, hgtHiddenBelow, hgtVisible;
        var sHeight = $(window).height();
        hoverbtm = (parseInt(param2 + param3));
        hoverbtm = parseInt(hoverbtm / 2);
        if (hoverbtm < sHeight) {
            param2 = (Math.round(param2 / 2)) - 10;
            newTop = param2;
            if ((newTop + param3) > sHeight) {
                hgtVisible = sHeight - param2;
                hgtHiddenBelow = param3 - hgtVisible;
                newTop = param2 - hgtHiddenBelow;
            }
        } else {
            hgtVisible = sHeight - param2;
            hgtHiddenBelow = param3 - hgtVisible;
            newTop = param2 - hgtHiddenBelow;
        }
        return newTop;
    },
    onPreHoverCallback: null,
    /*
     * get Button Stucture on hover
     */
    _getButtonStructure: function (userId, group, pCheckSum, jid, nick) {
        var groupButtons = chatConfig.Params[device]["buttons"][group];
        var str = '';
        var TotalBtn = '',
            widCal = '';
        //console.log(groupButtons, "Nitish");
        TotalBtn = groupButtons.length;
        //this._chatLoggerPlugin('TotalBtn: ' + TotalBtn);
        widCal = parseInt(100 / TotalBtn);
        //this._chatLoggerPlugin('widCal: ' + widCal);
        //this._chatLoggerPlugin("BB");
        var that = this;
        $.each(groupButtons, function (k, v) {
            //that._chatLoggerPlugin(k);
            //that._chatLoggerPlugin(v);
            //that._chatLoggerPlugin("KKKKKK" + v.action);
            if (group == chatConfig.Params["categoryNames"]["Interest Sent"]) {
                str += '<div class="nchatbg-grey lh50 brdr-0 txtc colrw"';
            } else {
                str += '<button class="hBtn bg_pink lh50 brdr-0 txtc colrw cursp f13 fontlig"';
            }
            str += 'id="' + userId + '_' + v.action + '"';
            str += 'data-pCheckSum="' + pCheckSum + '"';
            str += 'data-params="' + v.params + '"';
            str += 'data-jid="' + jid + '"';
            str += 'data-nick="' + nick + '"';
            str += 'data-group="' + group + '"';
            if (TotalBtn == 1) {
                str += 'style="width:100%">';
            } else {
                if (k == 0) {
                    str += 'style="width:' + widCal + '%"> ';
                } else {
                    str += 'style="width:' + (widCal - 1) + '%;margin-left:1px">';
                }
            }
            str += v.label;
            if (group == chatConfig.Params["categoryNames"]["Interest Sent"]) {
                str += '</div>';
            } else {
                str += '</button>';
            }
        });
        delete that;
        return str;
    },
    //start:hover box html structure
    _hoverBoxStr: function (param1, param2, pCheckSum) {
        var _this = this;
        var group = $(".chatlist li[id^='" + param1 + "_']").attr("id").split(param1 + "_")[1],
            nick = $(".chatlist li[id^='" + param1 + "_']").attr("data-nick"),
            jid = $(".chatlist li[id^='" + param1 + "_']").attr("data-jid");
        //this._chatLoggerPlugin($('#'+param1+'_hover').length);
        //this._chatLoggerPlugin("in hoverBoxStr");
        //this._chatLoggerPlugin(pCheckSum);
        var trackingParams = _this._categoryTrackingParams[group],
            trackingParamsStr = '';
        if (typeof trackingParams != "undefined") {
            $.each(trackingParams, function (key, val) {
                trackingParamsStr += '&' + key + '=' + val;
            });
        }
        if ($('#' + param1 + '_hover').length == 0) {
            var str = '<div class="pos_fix info-hover fontlig nz21 vishid" id="' + param1 + '_hover">';
            str += '<div class="nchatbdr3 f13 nchatgrad nchathoverdim pos-rel">';
            str += '<a href = "/profile/viewprofile.php?profilechecksum=' + pCheckSum + trackingParamsStr + '" class = "cursp"><img src="' + param2.photo + '" onmousedown=\"return false;\" oncontextmenu=\"return false;\" class="vtop ch220"/></a>';
            str += '<div id="' + param1 + '_hoverinfo-a">';
            str += '<div class="padall-10 pos-rel">';
            str += '<div class="pos-abs err2 nchatrr1 disp-none" id="' + param1 + '_hoverDvBgEr">';
            str += '<div class="padall-10 colr5 f13 fontli disp-tbl wid90" >';
            str += '<div class="disp-cell vmid txtc lh27 ht160" id="' + param1 + '_hoverBgEr"></div>';
            str += '</div>';
            str += '</div>';
            str += '<ul class="listnone lh22">';
            str += '<li>' + param2.age;
            if (param2.age) {
                str += ', ';
            }
            str += param2.height + '</li>';
            str += '<li>' + param2.caste + '</li>';
            str += '<li>' + param2.education + '</li>';
            str += '<li>' + param2.occupation + '</li>';
            str += '<li>' + param2.income;
            if (param2.income) {
                str += ', ';
            }
            str += param2.location + '</li>';
            str += '</ul>';
            str += '</div>';
            str += '<div class="fullwid clearfix pos-rel" id="' + param1 + '_BtnRespnse">';
            str += '<p class="txtc nc-color2 lh27 nhgt28"></p>';
            str += '<div id="' + param1 + '_BtnOuter">';
            str += _this._getButtonStructure(param1, group, pCheckSum, jid, nick);
            str += '</div>';
            str += '</div>';
            str += '</div>';
            str += '<div id="' + param1 + '_hoverDvSmEr" class="pos-rel padall-10 disp-none">';
            str += '<div class="txtr">';
            str += '<i class="nchatspr nchatic_1 hcross cursp" id="' + param1 + '_hcross" ></i>';
            str += '</div>';
            str += '<div class="disp-tbl f13 colr5 fontlig fullwid">';
            str += '<div class="disp-cell vmid txtc nhgt180" id="' + param1 + '_hoverSmEr">';
            str += '</div>';
            str += '</div>   ';
            str += '</div>';
            str += '</div>';
            str += '</div>';
            return str;
        }
        //this._chatLoggerPlugin("End of _hoverBoxStr");
    },
    onHoverContactButtonClick: null,
    //start:update vcard
    updateVCard: function (param, pCheckSum, callback) {
        if(typeof param.jid != "undefined"){
            //this._chatLoggerPlugin('in vard update');
            var globalRef = this;
            var finalstr;
            var that = this;
            //$.each(param.vCard, function (k, v) {
            //that._chatLoggerPlugin("set");
            //that._chatLoggerPlugin(k);
            finalstr = globalRef._hoverBoxStr(param.jid, param, pCheckSum);
            $(globalRef._mainID).append(finalstr);
            //});
            delete that;
            //this._chatLoggerPlugin("Callback calling starts");
            callback();
            //this._chatLoggerPlugin("Callaback ends");
        }
    },
    /*
     * Error handling in case of hover
     */
    hoverButtonHandling: function (jid, data, type) {
        //this._chatLoggerPlugin("In error handling");
        //this._chatLoggerPlugin(jid, data);
        //this._chatLoggerPlugin(type);
        if (type == "error") {
            //$("#"+jid+"_BtnRespnse").addClass("disp-none");
            //$("#"+jid+"_hoverDvSmEr").removeClass("disp-none");
            if(data.actiondetails.errmsglabel){
                $("#" + jid + "_hoverinfo-a").addClass("disp-none");
                $("#" + jid + "_hoverDvSmEr").addClass("disp_b").removeClass("disp-none");
                $("#" + jid + "_hoverSmEr").html(data.actiondetails.errmsglabel);
            }
            var btnLength = $("#" + jid + "_BtnOuter button").length;
            var id = $("#" + jid + "_BtnOuter button").attr("id").split("_")[0],
                data_jid = $("#" + jid + "_BtnOuter button").attr("data-jid"),
                data_checks = $("#" + jid + "_BtnOuter button").attr("data-pchecksum");
            $("#" + jid + "_BtnOuter button").remove();
            var msg = '';
            if(btnLength == '2'){
                if(data.buttondetails && data.buttondetails.infomsglabel == "You declined interest"){
                    msg = "Interest Declined";
                    $("#" + jid + "_BtnOuter").append('<button class="nchatbg-grey lh50 brdr-0 txtc colrw nc" style="width:100%">'+msg+'</button>');
                }
                else{
                    msg = "Start Conversation";
                    $("#" + jid + "_BtnOuter").append('<button class="bg_pink lh50 brdr-0 txtc colrw cursp hBtn" style="width:100%" id="'+id+'_WRITE_MESSAGE" data-jid="'+data_jid+'" data-checks="'+data_checks+'" data-group="acceptance">'+msg+'</button>');
                }
            }
            
        } else if (type == "info") {
            $("#" + jid + "_hoverDvBgEr").removeClass("disp-none");
            $("#" + jid + "_hoverBgEr").html(data.actiondetails.errmsglabel);
            $("#" + jid + "_BtnRespnse div button").addClass("nchatbg-grey colrw nc").removeClass("cursp");
            $("#" + jid + "_BtnRespnse div button").html(data.buttondetails.button.label);
        } else {
            var btnLength = $("#" + jid + "_BtnOuter button").length;
            var id = $("#" + jid + "_BtnOuter button").attr("id").split("_")[0],
                data_jid = $("#" + jid + "_BtnOuter button").attr("data-jid"),
                data_checks = $("#" + jid + "_BtnOuter button").attr("data-pchecksum");
            $("#" + jid + "_BtnOuter button").remove();
            var msg = '';
            if(btnLength == '2'){
                if(data.buttondetails.infomsglabel == "You declined interest"){
                    msg = "Interest Declined";
                    $("#" + jid + "_BtnOuter").append('<button class="nchatbg-grey lh50 brdr-0 txtc colrw nc" style="width:100%">'+msg+'</button>');
                }
                else{
                    msg = "Start Conversation";
                    $("#" + jid + "_BtnOuter").append('<button class="bg_pink lh50 brdr-0 txtc colrw cursp hBtn" style="width:100%" id="'+id+'_WRITE_MESSAGE" data-jid="'+data_jid+'" data-checks="'+data_checks+'" data-group="acceptance">'+msg+'</button>');
                    $("#" + jid + "_BtnOuter").append('<button class="bg_pink lh50 brdr-0 txtc colrw cursp hBtn" style="width:100%" id="'+id+'_WRITE_MESSAGE" data-jid="'+data_jid+'" data-checks="'+data_checks+'" data-group="acceptance">'+msg+'</button>');
                }
            }
            else{
                msg = 'Interest Sent'
                $("#" + jid + "_BtnOuter").append('<button class="nchatbg-grey lh50 brdr-0 txtc colrw nc" style="width:100%">'+msg+'</button>');
            }
            /*
            if(data.buttondetails.buttons && data.buttondetails.buttons.label){
                msg = data.buttondetails.buttons.label;
            }
            else if(data.buttondetails.button && data.buttondetails.button.label){
                msg =  data.buttondetails.button.label;
            }
            $("#" + jid + "_BtnOuter").append('<button class="bg_pink lh50 brdr-0 txtc colrw cursp" style="width:100%">'+msg+'</button>');
            */
        }
    },
    //start:check hover
    _checkHover: function (param) {
        var curEleID = $(param).attr('id');
        curEleID = curEleID.split("_");
        curEleID = curEleID[0];
        var checkSumP = $(param).attr('data-checks');
        var hoverNewTop = $(param)[0].getBoundingClientRect().top;
        var _this = this;
        //as per discussion with ashok this height is goign to be fixed
        var hoverDivHgt = 435;
        hoverNewTop = this._calHoverPos(hoverNewTop, hoverDivHgt);
        if(hoverNewTop < 0){
            hoverNewTop = 0;
        }
        var shiftright = Math.round($(this._parendID)[0].getBoundingClientRect().width);
        //this._chatLoggerPlugin('hoverNewTop:'+hoverNewTop+' shiftright:'+shiftright);
        //if element exist        
        if ($('#' + curEleID + '_hover').length != 0) {
            $('#' + curEleID + '_hover').css({
                'top': hoverNewTop,
                'visibility': 'visible',
                'right': shiftright
            });
        } else {
            //this._chatLoggerPlugin('call to onPreHoverCallback');
            if (this.onPreHoverCallback && typeof this.onPreHoverCallback == 'function') {
                //this._chatLoggerPlugin("Before precall");
                this.onPreHoverCallback(checkSumP, curEleID, hoverNewTop, shiftright);
                //once div is created from precallback below ling shows the hovred list information
                //this._chatLoggerPlugin("After precall");
            }
        }
        $('.info-hover').hover(function () {
            $(this).css('visibility', 'visible');
        }, function () {
            $(this).css('visibility', 'hidden');
        });
        $('#' + curEleID + '_hover .hBtn').off('click').on('click', function () {
            if (_this.onHoverContactButtonClick && typeof _this.onHoverContactButtonClick == 'function') {
                if ($(this).html() == "Start Conversation") {
                    currentID = $(this).attr("id").split("_")[0];
                    $('#' + curEleID + "_hover").css("visibility","hidden");
                    _this._chatPanelsBox(currentID, 'offline', $(this).attr("data-jid"), $(this).attr("data-checks"), $(this).attr("data-group"));
                } else {
                    if (!$(this).hasClass("nc")) {
                        _this.onHoverContactButtonClick(this);
                    }
                }
            }
        });
        $('.hcross').off('click').on('click', function () {
            var id = $(this).attr('id');
            var jid = id.split('_');
            jid = jid[0];
            $("#" + jid + "_hoverinfo-a").removeClass("disp-none");
            $("#" + jid + "_hoverDvSmEr").removeClass("disp_b").addClass("disp-none");
        });
    },
    _timer: null,
    //start:hover functionality
    _calltohover: function (e) {
        //this._chatLoggerPlugin("In _calltohover");
        //global level ref.
        var _this = e.data.global;
        var curHoverEle = this;
        //this._chatLoggerPlugin(this);
        var getID = $(this).attr('id');
        getID = getID.split("_");
        getID = getID[0];
        //set timer variable
        if (e.type == "mouseenter") {
            clearTimeout(_this._timer);
            _this._timer = setTimeout(function () {
                _this._checkHover(curHoverEle);
            }, 500);
        } else {
            clearTimeout(_this._timer);
            $('#' + getID + '_hover').css('visibility', 'hidden');
        }
        $('.info-hover').hover(function () {
            $(this).css('visibility', 'visible');
        }, function () {
            $(this).css('visibility', 'hidden');
        });
    },
    //start:append Chat Logged in Panel
    _appendLoggedHTML: function () {
        if ($('#js-lsitingPanel').length == 0) {
            //console.log("in _appendLoggedHTML");
            var curEle = this;
            //this._chatLoggerPlugin('_appendLoggedHTML');
            $(curEle._parendID).append('<div class="fullwid fontlig nchatcolor" id="js-lsitingPanel"/> ').promise().done(function () {
                curEle._addChatTop();
                curEle.addTab();
                curEle.onChatLoginSuccess();
                $("#blankPanelLoader").addClass("disp-none");
            });
        }
    },
    
    _changeLocalStorage:function(type,userId,groupId,newState) {
        //console.log("inside function",type,userId,groupId,newState);
        var thisElem = this;
        var data = [];
        var reAdd = true;
        if(localStorage.getItem("chatBoxData")) {
            data = JSON.parse(localStorage.getItem("chatBoxData"));
        }
        if(type == "add"){
            var dataPresent = false;
            $.each(data,function(index,elem){
                if(elem["userId"] == userId) {
                    dataPresent = true;
                }
            });
            if(dataPresent == false){
                var obj = {userId:userId,state:newState,group:groupId};
                data.push(obj);
            }
        }
        else if(type == "remove"){
            var indexToBeRemoved;
            $.each(data,function(index,elem){
                if(elem["userId"] == userId) {
                    indexToBeRemoved = index;
                }
            });
            if(indexToBeRemoved != undefined) {
                data.splice(indexToBeRemoved,1);
            }
        }
        else if(type == "stateChange"){
            $.each(data,function(index,elem){
                if(elem["userId"] == userId) {
                    elem["state"] = newState;
                }
            });
        } else if(type == "changeGroup" || type == "changeOrAddGroup") {
            var found = false;
            $.each(data,function(index,elem){
                if(elem["userId"] == userId) {
                    found = true;
                    data[index]["group"] = groupId;
                }
            });
            if(type == "changeOrAddGroup" && found == false){
                thisElem._changeLocalStorage("add",userId,groupId,newState);
                reAdd = false;
            }
        }
        else if(type == "chatStateChange"){
            var chatState = "";
            if(localStorage.getItem("chatStateData")) {
                chatState = localStorage.getItem("chatStateData");
            }
            chatState = newState;
            localStorage.setItem("chatStateData",chatState);
        }
        else if(type == "tabStateChange") {
            var tabState = "";
            if(localStorage.getItem("tabState")) {
                tabState = localStorage.getItem("tabState");
            }
            if(newState.indexOf("tab") != -1) {
                tabState = newState;
                localStorage.setItem("tabState",tabState);
            } else {
                localStorage.setItem("tabState",thisElem._defaultActiveTab);
            }
        } 
        if(reAdd == true){
            localStorage.setItem("chatBoxData", JSON.stringify(data));
        }
    },
    
    _updateChatStructure:function(type) {
		//console.log("inside update function",type);
		var data = [],curEle = this;
		var currentUserId = [];
		var localId = [],pageId = [];
		if(localStorage.getItem("chatBoxData")) {
			data = JSON.parse(localStorage.getItem("chatBoxData"));
		}
        //console.log("localstorage",data);
        
		if(type == "new"){
			setTimeout(function(){ 
               // console.log("ankita",data);
				$.each(data,function(index,elem){
                    //console.log("here ankita",elem);
                    //console.log($("#"+elem["userId"]+"_"+elem["group"]));
					//console.log(elem["userId"],elem["group"],$("#"+elem["userId"]+"_"+elem["group"]));
                    $("#"+elem["userId"]+"_"+elem["group"]).click();
					if(elem["state"] == "min") {
                        var chatBrowser = navigator.userAgent;
                        //console.log("chatBrowser",chatBrowser);
                        if (chatBrowser.indexOf("Firefox") > -1 || chatBrowser.indexOf("Mozilla") > -1) {
                            setTimeout(function(){
                                $('chat-box[user-id="' + elem["userId"] + '"] .nchatic_2').click();
                            },50);
                        }
                        else{
                            $('chat-box[user-id="' + elem["userId"] + '"] .nchatic_2').click();
                        }
					}
                    //console.log("click done");
				});
                //console.log("done");
			}, 1000);
		} else if(type == "exsisting"){
			$("chat-box").each(function(index, element) {
				currentUserId.push($(element).attr("user-id"));
            });	
			$.each(data, function(index,elem){
				localId.push(elem["userId"]);
				if($('chat-box[user-id="' + elem["userId"] + '"]').length == 0){
					$("#"+elem["userId"]+"_"+elem["group"]).click();	
				}
				if($('chat-box[user-id="' + elem["userId"] + '"] img').hasClass("downBarPicMin") && elem["state"] == "open") {
					$('chat-box[user-id="' + elem["userId"] + '"] .chatBoxBar').click();
				}
				if(!$('chat-box[user-id="' + elem["userId"] + '"] img').hasClass("downBarPicMin") && elem["state"] == "min") {
                    var chatBrowser = navigator.userAgent;
                    if (chatBrowser.indexOf("Firefox") > -1 || chatBrowser.indexOf("Mozilla") > -1) {
                        setTimeout(function(){
                            $('chat-box[user-id="' + elem["userId"] + '"] .nchatic_2').click();
                            //console.log("timeout");
                        },50);
                    }
                    else{
                        $('chat-box[user-id="' + elem["userId"] + '"] .nchatic_2').click();
                    }
				}
			});
			
			$.each(currentUserId, function(index1,elem1){
				var dataPresent = false;
				$.each(data, function(index2,elem2){
					if(elem1 == elem2["userId"]) {
						dataPresent = true;
					}
				});
				if(dataPresent == false) {
					if($("#extra_"+elem1).length !=0) {
						$("#extra_"+elem1+" .nchatic_4").click();
					}
					else {
                        //console.log("closing on chat-box change manvi_check");
						$('chat-box[user-id="' + elem1 + '"] .nchatic_1').click();
					}
				}	
			});
			$("chat-box").each(function(index, element) {
				pageId.push($(element).attr("user-id"));
            });	
			localId.reverse();
			var toClickArr = [];
			$.each(localId, function(index1, elem1){
				if(elem1 == pageId[0]) {
					return false;
				} else {
					$.each(pageId, function(index2,elem2){
						//console.log("elem2",elem2);
						if(elem2 == elem1) {
							toClickArr.push(elem2);
						}
					}); 
				}
			});
			if(toClickArr.length !=0){
				toClickArr.reverse();
				$.each(toClickArr, function(index,elem){
					$("#extra_"+elem+" .extraUsername").click();
				});
			}
		}
        var bubbleData = [],chatBoxData=[];
        if(localStorage.getItem("bubbleData_new")) {
            bubbleData = JSON.parse(localStorage.getItem("bubbleData_new"));
            chatBoxData = JSON.parse(localStorage.getItem("chatBoxData"));
        }
        setTimeout(function() {
            var totalCount = 0;
            $.each(bubbleData, function(index,elem) {
                if($('chat-box[user-id="'+elem.userId+'"] .chatBoxBar img').hasClass("downBarPicMin")){
                    //console.log("manvi_check",$('chat-box[user-id="'+elem.userId+'"] .pinkBubble2'));
                    $('chat-box[user-id="'+elem.userId+'"] .pinkBubble2 span').html(elem.bCount);
                    if(elem.bCount != 0){
                        $('chat-box[user-id="'+elem.userId+'"] .pinkBubble2').show();
                    }
                }
                if($('#extra_'+elem.userId).length != 0 && elem.bCount != 0) {
                    $('#extra_'+elem.userId+' .pinkBubble span').html(elem.bCount);
                    $('#extra_'+elem.userId+' .pinkBubble').show();
                }
                if(elem.bCount != 0){
                    totalCount++;
                }
            });
            if(totalCount != 0){
                $('.countVal').html(totalCount);
                if ($('.showcountmin').hasClass('vishid')) {
                    $('.showcountmin').removeClass('vishid');
                }
            }
            else{
                $('.showcountmin').addClass('vishid'); 
            }
            
        }, 2000);
        var chatStatus = "";
        if(localStorage.getItem("chatStateData")) {
            chatStatus = localStorage.getItem("chatStateData");
        } else {
            localStorage.setItem("chatStateData","max");
        }
        if(chatStatus == "min"){
            $(".js-minChatBarIn").click();
        } else if(chatStatus == "max" && $(".js-minpanel").length !=0){
            $(".js-minpanel").click();
        } 
        //console.log("local",localStorage.getItem("tabState"));
        var tabStatus = "";
        if(localStorage.getItem("tabState")) {
            chatStatus = localStorage.getItem("tabState");
        } else {
            localStorage.setItem("tabState",curEle._defaultActiveTab);
        }
        curEle._chatTabs(chatStatus,"noAnimate");
        /*if(chatStatus == "accepted"){
            //$("#tab2").click();
            curEle._chatTabs("tab2","noAnimate");
        } else if(chatStatus == "online") {
            //$("#tab1").click();
            curEle._chatTabs("tab1","noAnimate");
        }*/
		localStorage.setItem("lastUId",$(".tabUId").attr("id"));
	},    
    
    manageLoginLoader: function(type){
        //console.log("Manage login loader");
            $("#loginLoader").toggleClass("disp-none");
    },
    /*
     * Sending typing event
     */
    sendingTypingEvent: null,
    //start:this function image,name in top chat logged in scenario
    addLoginHTML: function (failed,timeoutCase) {
        //this._chatLoggerPlugin('in addLoginHTML');
        var curEle = this;
        var LoginHTML = '<div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarOut"></i> </div><div class="chpt100" id="selfImgDiv"> <img src="' + this._imageUrl + '" onmousedown=\"return false;\" oncontextmenu=\"return false;\" /> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Enter to Chat</button><div id="loginLoader" class="loginSpinner disp-none" style="margin-top: 14px"></div></div>';
        var errorHTML = '';
        if (failed == true) {
            errorHTML += '<div class="txtc color5 f13 mt10" id="loginErr">' + curEle._loginFailueMsg + '</div>';
        }
        if (failed == false || typeof failed == "undefined" || $("#js-loginPanel").length == 0){
            $(this._parendID).append(LoginHTML);
            $("#blankPanelLoader").addClass("disp-none");
        }
        else {
            //this._chatLoggerPlugin("removing");
            $(curEle._loginPanelID).fadeIn('fast');
            if ($(curEle._loginPanelID).find("#loginErr").length == 0) $(curEle._loginPanelID).append(errorHTML);
        }
        $('.js-minChatBarOut').click(function () {
            curEle._minimizeChatOutPanel();
        });
        //start login button capture
        var that = this;
        $(this._loginbtnID).click(function () {
            //console.log("before login",that._selfName);
            if($("#js-loginPanel").length == 0 && $("#js-lsitingPanel").length == 0){
                //console.log("Loggedinclicked",$(".js-openOutPanel").is(":visible"));
                $("#blankPanelLoader").removeClass("disp-none");
            }
            if (curEle.onEnterToChatPreClick && typeof (curEle.onEnterToChatPreClick) == "function") {
                //that._chatLoggerPlugin("in onEnterToChatPreClick");
                curEle.onEnterToChatPreClick();
            }
            /*
            if (curEle._loginStatus == "Y") {
                that._chatLoggerPlugin("ankita_logged in");
                curEle._startLoginHTML();
            }
            */
        });
        delete that;
        //auto login to chat on site relogin if flag true and login authentication success
        if(curEle._chatAutoLogin == true && (failed!= true || timeoutCase == true)){
            //console.log("clicked");
            setTimeout(function(){
                invokePluginLoginHandler("autoChatLogin");
            },100);
        }
    },
    preventSiteScroll:function(userId){
        var inside = false, current;
        $('chat-box[user-id="' + userId + '"] .chatMessage').on('mouseenter',function(){
            //console.log("mouse enter");
            inside = true;
            current = $(document).scrollTop();
            $(document).scroll(function(e,d){
                if(!$(e).hasClass('.chatMessage') && inside == true && $('chat-box[user-id="' + userId + '"]').length != 0) {
                    $(window).scrollTop(current);
                }
            });
            $('chat-box[user-id="' + userId + '"] .chatMessage').on('mouseleave',function(){
                //console.log("mouse leave");
                inside = false;
            });
        });
    },

    //manage chat loader
    manageChatLoader: function (type) {
        if (type == "hide") {
            //this._chatLoggerPlugin("hiding loader_ankita");
            $("#scrollDivLoader").hide();
        }
    },
    //shift next prev buttons
    handleNextPrevButtons:function(key){
        if(key == "makeCloser"){
            var posDiv = $("#topNavigationBar")[0].getBoundingClientRect();
            $("#show_prevListingProfile > div").css("left",posDiv.left);
            $("#show_nextListingProfile > div").css("left",posDiv.left + $("#topNavigationBar").width() - $("#show_nextListingProfile > div").width());
        }
        else{
            $("#show_prevListingProfile > div").css("left",0);
            $("#show_nextListingProfile > div").removeAttr("style");
        }
    },
    //start:this function is that init forthe chat
    start: function () {
        //console.log(my_action,"ankita1");
        var divElement = document.createElement("Div");
        $(divElement).addClass('pos_fix chatbg chatpos1 z7 js-openOutPanel').appendTo(this._mainID);
        
        this._createPrototypeChatBox();
        var _this = this;
        if (this._checkWidth()) {
            $(this._parendID).css('display', 'none');
            $(this._parendID).addClass('chatw5').css('height', this._getHeight());
            this.minimizedPanelHTML();
            $(this._minPanelId).click(function () {
                _this._maximizeChatPanel();
            });
        } else {
            if(localStorage.getItem("chatStateData") == "min"){
                $(this._parendID).css('display', 'none');
                $(this._parendID).addClass('wid20p').css('height', this._getHeight());
                this.minimizedPanelHTML();
                setTimeout(function(){
                    //console.log("manvi",$("chat-box"));
                    $("chat-box").each(function (index, element) {
                        //console.log("element",element);
                        _this._scrollDown($(this), "min");
                    });
                },1000);
                $(this._minPanelId).click(function () {
                    _this._maximizeChatPanel();
                });
            } else {
                $('body').css('width', '80%');
                $(this._parendID).addClass('wid20p').css('height', this._getHeight());
                //handle postion of next prev buttons on view profile
                if(my_action && (my_action=="detailed" || my_action == "noprofile")){
                    _this.handleNextPrevButtons("makeCloser");
                }
            }
        }
        $("<div id='blankPanelLoader' class='loginSpinner pos_fix chatpos1 z7 blankLoader'></div>").appendTo(this._mainID);
        if($(".js-minpanel").length != 0){
            $("#blankPanelLoader").addClass("disp-none");
        }
        if (this.checkLoginStatus()) {
            //this._chatLoggerPlugin("checking login status");
            this._startLoginHTML();
        } else {
            //this._chatLoggerPlugin("in start function");
            this.addLoginHTML();
        }
        if(typeof showHelpScreen !== typeof undefined) {
            if (showHelpScreen == 'Y' && moduleChat && (moduleChat == "myjs" || moduleChat == "homepage")) {
                showHelpScreenFunction();
            }
        }
    },
};
