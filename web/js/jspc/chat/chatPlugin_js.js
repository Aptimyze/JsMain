"use strict";
var JsChat = function () {
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
    _listingTabs: {},
    _loginFailueMsg: "Login Failed,Try later",
    _noDataTabMsg: {
        "tab1": "There are no matching members online. Please relax your partner preference to see more matches.",
        "tab2": "You currently don’t have any accepted members, get started by sending interests or initiating chat with your matches."
    },
    _rosterDetailsKey: "rosterDetails",
    _listingNodesLimit: {},
    _groupBasedChatBox: {},
    _contactStatusMapping: {},
    _loggingEnabledPlugin: false,
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
        if (arguments[1][0].groupBasedChatBox) this._groupBasedChatBox = arguments[1][0].groupBasedChatBox;
        if (arguments[1][0].contactStatusMapping) this._contactStatusMapping = arguments[1][0].contactStatusMapping;
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
        this._chatLoggerPlugin('check status');
        if (this._loginStatus == "Y") {
            return true;
        } else {
            return false;
        }
    },
    //start:maximize html
    _maximizeChatPanel: function () {
        var curEle = this;
        this._chatLoggerPlugin('in max');
        this._chatLoggerPlugin($(this._maxChatBarOut));
        $("chat-box").each(function (index, element) {
            if ($(this).attr("pos-state") == "open") {
                curEle._scrollUp($(this));
            }
        });
        $(this._maxChatBarOut).fadeOut('slow', function () {
            $(this).remove();
        });
        if (this._checkWidth()) {
            this._chatLoggerPlugin('screen size less than 1024');
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
            setTimeout(function () {
                $(curEle._chatBottomPanelID).show();
            }, 400);
        }
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
    },
    //start:minimize html
    _minimizeChatOutPanel: function () {
        var curEle = this;
        $("chat-box").each(function (index, element) {
            curEle._scrollDown($(this), "min");
        });
        $(curEle._chatBottomPanelID).hide();
        if (this._checkWidth()) {} else {
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
        $(this._maxChatBarOut).click(function () {
            curEle._maximizeChatPanel();
        });
    },
    //start:chat tabs click
    _chatTabs: function (param) {
        $('ul.nchattab1 li').removeClass('active');
        $('#' + param).addClass('active');
        $('.js-htab').fadeOut('slow').promise().done(function () {
            $('.show' + param).fadeIn('slow')
        });
    },
    onLogoutPreClick: null,
    //start:log out from chat
    logOutChat: function () {
        var curEleRef = this,
            that = this;
        $(curEleRef._toggleID).toggleClass('disp-none');
        $(curEleRef._chatBottomPanelID).hide();
        this._chatLoggerPlugin("In logout Chat");
        this._chatLoggerPlugin(curEleRef._loginStatus);
        if (curEleRef._loginStatus == 'N') {
            $(curEleRef._listingPanelID).fadeOut('slow', function () {
                if ($(curEleRef._loginPanelID).length == 0) {
                    that._chatLoggerPlugin("Length is 0 of login panel");
                    curEleRef.addLoginHTML();
                } else {
                    $(curEleRef._loginPanelID).fadeIn('slow', function () {
                        $(".info-hover").remove();
                    });
                }
                $(curEleRef._listingPanelID).remove();
            });
        } else {
            $(curEleRef._listingPanelID).fadeOut('slow', function () {
                curEleRef.addLoginHTML();
            });
        }
    },
    //start:addChatTop function
    _addChatTop: function (param) {
        var curEleRef = this,
            that = this;
        var chatHeaderHTML = '<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="' + this._imageUrl + '" class="nchatp4 wd40"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>';
        $(curEleRef._listingPanelID).append(chatHeaderHTML);
        $(curEleRef._toggleLogoutDiv).off("click").on("click", function () {
            that._chatLoggerPlugin($(this));
            $(curEleRef._toggleID).toggleClass('disp-none');
        });
        $(curEleRef._logoutChat).click(function () {
            if (curEleRef.onLogoutPreClick && typeof (curEleRef.onLogoutPreClick) == "function") {
                that._chatLoggerPlugin("in if");
                curEleRef.onLogoutPreClick();
            }
            curEleRef.logOutChat();
        });
        $(curEleRef._minChatBarIn).click(function () {
            $(curEleRef._minimizeChatOutPanel());
        });
    },
    //start:set height for the listing scroll div
    _chatScrollHght: function () {
        this._chatLoggerPlugin('cal scroll div');
        var totalHgt = this._getHeight();
        var remHgt = parseInt(totalHgt) - 140;
        this._chatLoggerPlugin(remHgt);
        this._chatLoggerPlugin(this._scrollDivId);
        $(this._scrollDivId).css('height', remHgt);
    },
    //start:add tab
    addTab: function () {
        //this script is same as old one shared eariler need to be reworked as discussed
        this._chatLoggerPlugin('in addTab');
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
        TabsOpt += '<div class="showtab1 js-htab" id="tab1"> <div id="showtab1NoResult" class="noResult f13 fontreg disp-none">' + curEle._noDataTabMsg["tab1"] + '</div>';
        for (var i = 0; i < obj["tab1"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + " disp-none chatListing\" data-showuser=\"" + obj["tab1"]["groups"][i]["hide_offline_users"] + "\">";
            //TabsOpt += "<div class=\"" + obj["tab1"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2";
            if (obj["tab1"]["groups"][i]["show_group_name"] == false) TabsOpt += " disp-none";
            TabsOpt += "\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab1"]["groups"][i]["group_name"] + "</p></div>";
            TabsOpt += "<ul class=\"chatlist\"></ul></div>";
        }
        TabsOpt += '</div>';
        TabsOpt += '<div class="showtab2 js-htab disp-none" id="tab2"> <div id="showtab2NoResult" class="noResult f13 fontreg disp-none">' + curEle._noDataTabMsg["tab2"] + '</div>';
        for (var i = 0; i < obj["tab2"]["groups"].length; i++) {
            TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\" data-showuser=\"" + obj["tab2"]["groups"][i]["hide_offline_users"] + "\">";
            //TabsOpt += "<div class=\"" + obj["tab2"]["groups"][i]["id"] + "\">";
            TabsOpt += "<div class=\"f12 fontreg nchatbdr2";
            if (obj["tab2"]["groups"][i]["show_group_name"] == false) TabsOpt += " disp-none";
            TabsOpt += "\"><p class=\"nchatt1 fontreg pl15\">" + obj["tab2"]["groups"][i]["group_name"] + "</p></div>";
            TabsOpt += "<ul class=\"chatlist\"></ul></div>";
        }
        TabsOpt += '</div>';
        TabsOpt += '</div>';
        $(this._listingPanelID).append(TabsOpt);
        $(this._tabclass).click(function () {
            curEle._chatTabs($(this).attr('id'));
        })
    },
    noResultError: function () {
        var dataLength;
        var that = this;
        $(".js-htab").each(function (index, element) {
            dataLength = 0;
            $(this).find(".chatlist").each(function (index2, element2) {
                that._chatLoggerPlugin($(this).find("li").length);
                dataLength = dataLength + $(this).find("li").length;
            });
            if (dataLength == 0) {
                that._chatLoggerPlugin(element);
                $(element).find(".noResult").removeClass("disp-none").addClass("disp_ib");
                $(element).find(".chatListing").each(function (index, element) {
                    $(this).addClass("disp-none");
                });
            }
        });
        delete that;
    },
    addListingInit: function (data) {
        var elem = this,
            statusArr = [],
            jidStr = "",
            currentID;
        this._chatLoggerPlugin("addListing");
        for (var key in data) {
            if (typeof data[key]["rosterDetails"]["jid"] != "undefined") {
                var runID = data[key]["rosterDetails"]["jid"],
                    res = '',
                    status = data[key]["rosterDetails"]["chat_status"];
                elem._chatLoggerPlugin("addlisting for " + runID + "--" + data[key]["rosterDetails"]["chat_status"]);
                var fullJID = runID;
                res = runID.split("@");
                runID = res[0];
                jidStr = jidStr + runID + ",";
                statusArr[runID] = status;
                if (typeof data[key]["rosterDetails"]["groups"] != "undefined" && data[key]["rosterDetails"]["groups"].length > 0) {
                    var that = this;
                    $.each(data[key]["rosterDetails"]["groups"], function (index, val) {
                        that._chatLoggerPlugin("groups " + val);
                        var List = '',
                            fullname = data[key]["rosterDetails"]["fullname"],
                            tabShowStatus = $('div.' + val).attr('data-showuser');
                        var getNamelbl = fullname,
                            picurl = data[key]["rosterDetails"]["listing_tuple_photo"],
                            prfCheckSum = data[key]["rosterDetails"]["profile_checksum"],
                            nick = data[key]["rosterDetails"]["nick"]; //ankita for image
                        that._chatLoggerPlugin("prfCheckSum", data[key]["rosterDetails"])
                        List += '<li class=\"clearfix profileIcon\"';
                        List += "id=\"" + runID + "_" + val + "\" data-status=\"" + status + "\" data-checks=\"" + prfCheckSum + "\" data-nick=\"" + nick + "\" data-jid=\"" + fullJID + "\">";
                        List += "<img id=\"pic_" + runID + "_" + val + "\" src=\"" + picurl + "\" class=\"fl wid40hgt40\">";
                        List += '<div class="fl f14 fontlig pt15 pl18">';
                        List += getNamelbl;
                        List += '</div>';
                        if (status == "online") {
                            List += '<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>';
                        }
                        List += '</li>';
                        var addNode = false;
                        if (tabShowStatus == 'false') {
                            that._chatLoggerPlugin(status + "2222");
                            addNode = true;
                        } else {
                            that._chatLoggerPlugin(status + "1111");
                            if (status == 'online') {
                                addNode = true;
                            }
                        }
                        that._chatLoggerPlugin("addNode" + addNode);
                        if (addNode == true) {
                            if ($('#' + runID + "_" + val).length == 0) {
                                if ($('#' + runID + "_" + val).find('.nchatspr').length == 0) {
                                    that._chatLoggerPlugin("checking no of nodes in group " + $('div.' + val + ' ul li').size());
                                    if (typeof elem._listingNodesLimit[val] == "undefined" || $('div.' + val + ' ul li').size() <= elem._listingNodesLimit[val]) {
                                        that._chatLoggerPlugin("b2");
                                        var tabId = $('div.' + val).parent().attr("id");
                                        if ($("#show" + tabId + "NoResult").length != 0) {
                                            that._chatLoggerPlugin("me");
                                            $("#show" + tabId + "NoResult").addClass("disp-none");
                                        }
                                        elem._placeContact("new", runID, val, status, List);
                                        if ($('div.' + val + ' ul').parent().hasClass("disp-none")) {
                                            $('div.' + val + ' ul').parent().removeClass("disp-none");
                                        }
                                        $("#" + runID + "_" + val).on("click", function () {
                                            currentID = $(this).attr("id").split("_")[0];
                                            that._chatLoggerPlugin("earlier", $(this).attr("data-checks"));
                                            elem._chatPanelsBox(currentID, statusArr[currentID], $(this).attr("data-jid"), $(this).attr("data-checks"), $(this).attr("id").split("_")[1]);
                                        });
                                    }
                                }
                            } else {
                                elem._placeContact("existing", runID, val, status);
                            }
                            //elem._updateStatusInChatBox(runID, status);
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
            callbacks:{
                onScroll:function(){
                    $('.info-hover').css('visibility', 'hidden');
                }
            }
        });
        //call hover functionality
        $(elem._listingClass).on('mouseenter mouseleave', {
            global: elem
        }, elem._calltohover);
        //var APIsrc ="http://xmppdev.jeevansathi.com/api/v1/social/getMultiUserPhoto?pid=";
        this._chatLoggerPlugin("api");
        this._chatLoggerPlugin(jidStr);
        var apiParams = {};
        if (jidStr) {
            apiParams["pid"] = jidStr.slice(0, -1);
            apiParams["photoType"] = "ProfilePic120Url";
            requestListingPhoto(apiParams);
        }
    },
    //add photo in tuple div of listing
    _addListingPhoto: function (photoObj) {
        if (typeof photoObj != "undefined" && typeof Object.keys(photoObj.profiles) != "undefined") {
            $.each(Object.keys(photoObj.profiles), function (index, element) {
                if (photoObj.profiles[element].PHOTO.ProfilePic120Url) {
                    $(".chatlist img[id*='pic_" + element + "']").attr("src", photoObj.profiles[element].PHOTO.ProfilePic120Url);
                }
            });
        }
    },
    //place contact in appropriate position in listing
    _placeContact: function (key, contactID, groupID, status, contactHTML) {
        if (key == "new") {
            this._chatLoggerPlugin("ankita_adding" + contactID + " in groupID");
            this._chatLoggerPlugin(contactHTML);
            //if(status == "offline")
                $('div.' + groupID + ' ul').append(contactHTML);
            /*else
                $('div.' + groupID + ' ul').prepend(contactHTML);*/
        } else if (key == "existing") {
            this._chatLoggerPlugin("changing icon");
            if (status == "online") {
                //move this element to top---manvi
                //add online chat_status icon
                if ($('#' + contactID + "_" + groupID).find('.nchatspr').length == 0) {
                    $(this._mainID).find($('#' + contactID + "_" + groupID)).append('<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>');
                }
            }
        }
    },
    //scrolling down chat box
    _scrollDown: function (elem, type) {
        this._chatLoggerPlugin(elem);
        if (type == "remove") {
            elem.animate({
                bottom: "-350px"
            }, function () {
                $(this).remove();
            });
        }
        else if(type == "retain_extra") {
            elem.animate({
                bottom: "-1000px"
            });
        }
        else if (type == "retain" || type == "min") {
            elem.animate({
                bottom: "-307px"
            }, function () {
                $(elem.find(".nchatic_2")[0]).hide();
                $(elem.find(".nchatic_3")[0]).hide();
                elem.find(".onlineStatus").hide();
                if (elem.find(".pinkBubble2 span").html() != 0) {
                    elem.find(".pinkBubble2").show();
                }
                elem.find(".chatBoxBar").addClass("cursp");
                elem.find(".downBarPic").addClass("downBarPicMin");
                elem.find(".downBarUserName").addClass("downBarUserNameMin");
                if (type != "min") {
                    $(elem).attr("pos-state", "close");
                }
            });
        }
    },
    //adjusting text area on input by user
    _textAreaAdjust: function (o) {
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
    _scrollUp: function (elem) {
        var curEle = this;
        elem.animate({
            bottom: "0px"
        }, function () {
            $(elem.find(".nchatic_2")[0]).show();
            $(elem.find(".nchatic_3")[0]).show();
            elem.find(".onlineStatus").show();
            elem.find(".pinkBubble2").hide();
            elem.find(".pinkBubble2 span").html("0");
            elem.find(".chatBoxBar").removeClass("cursp");
            elem.find(".downBarPic").removeClass("downBarPicMin");
            elem.find(".downBarUserName").removeClass("downBarUserNameMin");
            elem.find('.chatMessage').animate({
                scrollTop: (elem.find(".rightBubble").length + elem.find(".leftBubble").length) * 50
            }, 1000);
            $(elem).attr("pos-state", "open");
        });
        curEle._handleUnreadMessages(elem);
    },
    //handle unread messages
    _handleUnreadMessages: function (elem) {
        //handle received and unread messages in chatbox
        var selfJID = getConnectedUserJID(),
            receiverID = $(elem).attr("data-jid");
        var that = this;
        $(elem).find(".received").each(function () {
            var msg_id = $(this).attr("data-msgid");
            var msgObj = {
                "from": selfJID,
                "to": receiverID,
                "msg_id": msg_id,
                "msg_state": "receiver_received_read"
            };
            $(this).removeClass("received").addClass("received_read");
            that._chatLoggerPlugin("marking msg as read");
            that._chatLoggerPlugin(msgObj);
            invokePluginReceivedMsgHandler(msgObj);
        });
        delete that;
    },
    //bind clicking minimize icon
    _bindMinimize: function (elem) {
        var curElem = this;
        $(elem).off("click").on("click", function (e) {
            e.stopPropagation();
            curElem._scrollDown($(this).closest("chat-box"), "retain");
        });
    },
    //bind clicking maximize chat box
    _bindMaximize: function (elem, userId) {
        var curElem = this;
        $(elem).off("click").on("click", function () {
            curElem._scrollDown($(".extraPopup"), "retain_extra");
            setTimeout(function () {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
            curElem._scrollUp($('chat-box[user-id="' + userId + '"]'));
        });
    },
    //bind clicking close icon
    _bindClose: function (elem) {
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
                }
            }
        });
    },
    //onPostBlockCallback: null,
    //remove from list
    _removeFromListing: function (param1, data) {
        this._chatLoggerPlugin('remove element 11');
        var elem = this;
        //removeCall1 if user is removed from backend
        if (param1 == 'removeCall1' || param1 == 'delete_node') {
            this._chatLoggerPlugin("calllign _removeFromListing");
            for (var key in data) {
                var runID = '';
                runID = data[key]["rosterDetails"]["jid"].split("@")[0];
                if (typeof data[key]["rosterDetails"]["groups"] != "undefined") {
                    this._chatLoggerPlugin(data[key]["rosterDetails"]["groups"]);
                    var that = this;
                    $.each(data[key]["rosterDetails"]["groups"], function (index, val) {
                        var tabShowStatus = '',
                            listElements = '';
                        //this check the sub header status in the list
                        var tabShowStatus = $('div.' + val).attr('data-showuser');
                        listElements = $('#' + runID + '_' + val);
                        if (tabShowStatus == 'false' && param1 != 'delete_node') {
                            that._chatLoggerPlugin("123");
                            $(listElements).find('.nchatspr').detach();
                        } else {
                            that._chatLoggerPlugin("345");
                            $('div').find(listElements).detach();
                            if ($('div.' + val + ' ul li').length == 0) {
                                $('div.' + val + ' ul').parent().addClass("disp-none");
                            }
                        }
                        that._chatLoggerPlugin(this);
                        elem._updateStatusInChatBox(runID, "offline");
                    });
                    delete that;
                    this._chatLoggerPlugin("here");
                }
            }
        }
        //removeCall2 if user is removed from block click on chatbox
        else if (param1 == 'removeCall2') {
            $(this._mainID).find('*[id*="' + data + '"]').detach();
            /*if (this.onPostBlockCallback && typeof this.onPostBlockCallback == 'function') {
                this.onPostBlockCallback(data);
            }*/
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
        if(profileChecksum){
            nick = nick + "|"+profileChecksum;
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
                    "receiverJID":$('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                    "nick":nick
                });
                if (response != false) {
                    if (response.responseMessage == "Successful") {
                        enableClose = true;
                        //curElem._removeFromListing('removeCall2', userId);
                        sessionStorage.setItem("htmlStr_" + userId, $('chat-box[user-id="' + userId + '"] .chatMessage').html());
                        $('chat-box[user-id="' + userId + '"] .chatMessage').html('<div id="blockText" class="pos-rel wid90p txtc colorGrey padall-10">You have blocked this user</div><div class="pos-rel fullwid txtc mt20"><div id="undoBlock" class="padall-10 color5 disp_ib cursp">Undo</div></div>');
                        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                        //enableClose = true;
                        $('chat-box[user-id="' + userId + '"] .nchatic_3').css('pointer-events',"none");
                        setTimeout(function () {
                            if (enableClose == true) {
                                curElem._scrollDown($('chat-box[user-id="' + userId + '"]'), "remove");
                            }
                        }, 5000);
                        $('chat-box[user-id="' + userId + '"] #undoBlock').off("click").on("click", function () {
                            
                            //var profileChecksum = $(".chatlist li[id*='" + userId + "']").attr("data-checks");
                            if (curElem.onChatBoxContactButtonsClick && typeof curElem.onChatBoxContactButtonsClick == 'function') {
                                var response = curElem.onChatBoxContactButtonsClick({
                                    "buttonType": "UNBLOCK",
                                    "receiverID": userId,
                                    "checkSum":profileChecksum,
                                    "trackingParams": chatConfig.Params.trackingParams["UNBLOCK"],
                                    "extraParams": {
                                        "ignore": 0
                                    },
                                    "receiverJID":$('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                                    "nick":nick
                                });
                                if (response != false) {
                                    if (response.responseMessage == "Successful") {
                                        $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                                        enableClose = false;
                                        var htmlStr = sessionStorage.getItem("htmlStr_" + userId);
                                        $('chat-box[user-id="' + userId + '"] .chatMessage').html(htmlStr);
                                        $('chat-box[user-id="' + userId + '"] .nchatic_3').css('pointer-events',"auto");
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
                        $('chat-box[user-id="' + userId + '"] .chatMessage').append("<div class='color5 pos-rel txtc fullwid nchatm90' id='chatBoxErr'>" + response.responseMessage + "</div>");
                        //$(this).html(response.responseMessage);
                    }
                } else {
                    $('chat-box[user-id="' + userId + '"] .chatMessage').append("<div class='color5 pos-rel txtc fullwid nchatm90' id='chatBoxErr'>Something went wrong,please try later</div>");
                }
            }
        });
    },
    _bindUnblock: function (userId) {},
    onSendingMessage: null,
    onChatBoxContactButtonsClick: null,
    //sending chat
    _bindSendChat: function (userId) {
        var _this = this,
            that = this,
            messageId, 
            jid = $('chat-box[user-id="' + userId + '"]').attr("data-jid"),
            out = 1;
        var selfJID = getConnectedUserJID();
        $('chat-box[user-id="' + userId + '"] textarea').focusout(function () {
            that._chatLoggerPlugin("focus out to " + jid);
            out = 1;
            //fire event typing paused
            sendTypingState(selfJID, jid, "paused");
        });
        $('chat-box[user-id="' + userId + '"] textarea').keyup(function (e) {
            var curElem = this;
            if ($(this).val().length >= 1 && out == 1) {
                that._chatLoggerPlugin("typing start");
                out = 0;
                //fire event typing start
                sendTypingState(selfJID, jid, "composing");
            }
            if (e.keyCode == 13 && !e.shiftKey) {
                var text = $(this).val(),
                    textAreamElem = this;
                $(textAreamElem).val("").css("height", "24px");
                if (text.length > 1) {
                    var superParent = $(this).parent().parent(),
                        timeLog = new Date().getTime();
                    $(superParent).find("#initChatText,#sentDiv,#chatBoxErr").remove();
                    $(superParent).find(".chatMessage").css("height", "250px").append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id ="tempText_' + userId + '_' + timeLog + '" class="talkText">' + text + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
                    if ($(superParent).find("#sendInt").length != 0) {
                        //$(superParent).find(".chatMessage").append("<div class='pos-rel fr pr10' id='interestSent'>Your interest has been sent</div>");
                        $(superParent).find("#initiateText,#chatBoxErr").remove();
                        //$(superParent).find("#sendInt").remove();
                    }
                    var height = $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).height();
                    $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).next().css("margin-top", height);
                    $('chat-box[user-id="' + userId + '"] .chatMessage').animate({
                        scrollTop: ($(".rightBubble").length + $(".leftBubble").length) * 50
                    }, 500);
                    //fire send chat query and return unique id
                    setTimeout(function () {
                        out = 1;
                        sendTypingState(selfJID, jid, "paused");
                        if (_this.onSendingMessage && typeof (_this.onSendingMessage) == "function") {
                            var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
                            var profileChecksum = $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("data-checks");
                            var msgSendOutput = _this.onSendingMessage(text, $('chat-box[user-id="' + userId + '"]').attr("data-jid"), profileChecksum, $('chat-box[user-id="' + userId + '"]').attr("data-contact"));
                            messageId = msgSendOutput["msg_id"];
                            //that._chatLoggerPlugin("handling output of onSendingMessage in plugin");
                            if(messageId)
                                $("#tempText_" + userId + "_" + timeLog).attr("id", "text_" + userId + "_" + messageId);
                            if(msgSendOutput["sent"] == false || msgSendOutput["cansend"] == false){
                                var error_msg = msgSendOutput['errorMsg'] || "Something went wrong";
                                $('chat-box[user-id="' + userId + '"] #restrictMessgTxt').remove();
                                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="restrictMessgTxt" class="color5 pos-rel fr txtc wid90p">'+error_msg+'</div>').addClass("restrictMessg2");
                                if(msgSendOutput["cansend"] == false){
                                    $(curElem).prop("disabled", true);
                                }
                            }
                            else{
                                if(msgSendOutput["sent"] == true) {
                                    if ($(superParent).find("#sendInt").length != 0) {
                                        $(superParent).find(".chatMessage").append("<div class='pos-rel fr pr10' id='interestSent'>Your interest has been sent</div>");
                                        //$(superParent).find("#initiateText,#chatBoxErr").remove();
                                        $(superParent).find("#sendInt").remove();
                                    }
                                    //msg sending success,set single tick here
                                    $(superParent).find("#sendDiv").remove();
                                    $(superParent).find("#interestSent").removeClass("disp-none");
                                    _this._changeStatusOfMessg(messageId, userId, "recieved");
                                }
                                if(msgSendOutput["cansend"] == true){
                                    $(curElem).prop("disabled", false);
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
    _bindExtraUserNameBox: function () {
        var curElem = this;
        $('body').on('click', '.extraUsername', function () {
            curElem._scrollDown($(".extraPopup"), "retain_extra");
            setTimeout(function () {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                originalElem = $('chat-box[user-id="' + username + '"]'),
                status = $("chat-box[user-id='" + username + "'] .chatBoxBar .onlineStatus").html(),
                chatHtml = $(originalElem).find(".chatMessage").html(),
                jid = $('chat-box[user-id="' + username + '"]').attr("data-jid");
            pcheckSum = $('chat-box[user-id="' + username + '"]').attr("data-checks"),
                groupId = $('chat-box[user-id="' + username + '"]').attr("group-id");
            curElem._appendChatBox(username, status, jid, pcheckSum, groupId);
            $(originalElem).remove();
            $("chat-box[user-id='" + username + "'] .chatMessage").html("");
            curElem._postChatPanelsBox(username);
            //$("chat-box[user-id='" + username + "'] .chatMessage").html(chatHtml);
            $(this).closest(".extraChatList").remove();
            setTimeout(function () {
                curElem._scrollUp($('chat-box[user-id="' + username + '"]'));
            }, 700);
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
        });
    },
    //binding close button on extra popup username listing
    _bindExtraPopupUserClose: function (elem) {
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
        });
    },
    //adding data in extra popup
    _addDataExtraPopup: function (data) {
        var groupId = $("chat-box[user-id='" + data + "']").attr("group-id");
        $(".extraPopup").append('<div id="extra_' + data + '" class="extraChatList pad8_new"><div class="extraUsername cursp colrw minWid65 disp_ib pad8_new fontlig f14">' + $(".chatlist li[id='" + data + "_" + groupId + "'] div").html() + '</div><div class="pinkBubble vertM scir disp_ib padall-10"><span class="noOfMessg f13 pos-abs">1</span></div><i class="nchatspr nchatic_4 cursp disp_ib mt6 ml10"></i></div>');
        $("#extra_" + data + " .pinkBubble span").html($('chat-box[user-id="' + data + '"] .chatBoxBar .pinkBubble2 span').html());
        if ($("#extra_" + data + " .pinkBubble span").html() == 0) {
            $("#extra_" + data + " .pinkBubble").hide();
        }
    },
    //append chat box on page
    _appendChatBox: function (userId, status, jid, pcheckSum, groupId) {
        $("#chatBottomPanel").prepend('<chat-box group-id="' + groupId + '" pos-state="open" data-jid="' + jid + '" status-user="' + status + '" user-id="' + userId + '" data-checks="' + pcheckSum + '"></chat-box>');
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
        var curElem = this;
        $(curElem._chatBottomPanelID).append('<div class="extraChats cursp pos_abs nchatbtmNegtaive wid30 hgt43 bg5"><div class="extraNumber colrw opa50">+1</div><div><div class="extraPopup pos_abs l0 nchatbtmNegtaive wid153 bg5"><div>');
        $(".extraChats").css("left", curElem._bottomPanelWidth - $('chat-box').length * 250 - 32);
        curElem._scrollUp($(".extraChats"));
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
    },
    _getChatBoxType: function (userId, groupID, key) {
        this._chatLoggerPlugin("in _getChatBoxType");
        var curElem = this;
        //var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        //this._chatLoggerPlugin($(".chatlist li[id='" + userId + "_" + groupID + "']").attr("id").split("_")[1]);
        //var groupID = $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("id").split("_")[1];
        this._chatLoggerPlugin("ankita" + groupID + "-" + curElem._groupBasedChatBox[groupID]);
        var chatBoxType;
        var oldChatBoxType = $('chat-box[user-id="' + userId + '"]').attr("data-contact");
        if (typeof key == "undefined" || key != "updateChatBoxType") {
            this._chatLoggerPlugin("in case a");
            chatBoxType = curElem._contactStatusMapping[curElem._groupBasedChatBox[groupID]]["key"];
        } else {
            this._chatLoggerPlugin("in case b");
            switch (groupID) {
            case chatConfig.Params.categoryNames["Acceptance"]: //acceptance from 
                chatBoxType = curElem._contactStatusMapping["pog_interest_accepted"]["key"];
                break;
            case chatConfig.Params.categoryNames["Interest Received"]:
                chatBoxType = curElem._contactStatusMapping["pg_acceptance_pending"]["key"];
                break;
            default:
                chatBoxType = curElem._contactStatusMapping[curElem._groupBasedChatBox[groupID]]["key"];
                break;
            }
        }
        if (typeof chatBoxType == "undefined") {
            chatBoxType = curElem._contactStatusMapping["none_applicable"]["key"];
        }
        this._chatLoggerPlugin("chatboxtype--" + chatBoxType);
        $('chat-box[user-id="' + userId + '"]').attr("group-id", groupID);
        $('chat-box[user-id="' + userId + '"]').attr("data-contact", chatBoxType);
        return chatBoxType;
    },
    _postChatPanelsBox: function (userId) {
        var curElem = this,
            membership = "paid"; //get membership status-pending
        this._chatLoggerPlugin("in _postChatPanelsBox");
        //var membership = "free";
        var chatBoxType = curElem._getChatBoxType(userId, $('chat-box[user-id="' + userId + '"]').attr("group-id"));
        //setTimeout(function() {
        curElem._setChatBoxInnerDiv(userId, chatBoxType);
        curElem._enableChatTextArea($('chat-box[user-id="' + userId + '"]').attr("data-contact"), userId, membership);
        if ($('chat-box[user-id="' + userId + '"] .spinner').length != 0) $('chat-box[user-id="' + userId + '"] .spinner').hide();
        //}, 500);
    },
    _updateChatPanelsBox: function (userId, newGroupId) {
        var curElem = this;
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            this._chatLoggerPlugin("in _updateChatPanelsBox for " + userId);
            var chatBoxType = curElem._getChatBoxType(userId, newGroupId, "updateChatBoxType");
            curElem._setChatBoxInnerDiv(userId, chatBoxType);
            curElem._enableChatTextArea(chatBoxType, userId, "paid");
        }
    },
    //update contact status and enable/disable chat in chat box on basis of membership and contact status
    _setChatBoxInnerDiv: function (userId, chatBoxType) {
        this._chatLoggerPlugin();
        this._chatLoggerPlugin("in _setChatBoxInnerDiv");
        var curElem = this,
            that = this,
            new_contact_state = chatBoxType,
            response,
            checkSum = $("chat-box[user-id='" + userId + "'").attr("data-checks"),
            groupId = $("chat-box[user-id='" + userId + "'").attr("group-id"),
            user_name = $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html();
        var nick;
        if(checkSum){
            nick = nick + "|"+checkSum;
        }
        this._chatLoggerPlugin(curElem);
        switch (chatBoxType) {
        case curElem._contactStatusMapping["pg_interest_pending"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="sendInterest cursp sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_6 "></i><span class="vertTexBtm"> Send Interest</span></div><div id="sentDiv" class="sendDiv disp-none pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div>');
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="initiateText" class="color5 pos-rel txtc fullwid nchatm90">Initiating chat will also send your interest</div>');
            $('chat-box[user-id="' + userId + '"] #sendInt').on("click", function () {
                if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                    response = curElem.onChatBoxContactButtonsClick({
                        "receiverID": userId,
                        "checkSum": checkSum,
                        "trackingParams": chatConfig.Params["trackingParams"]["INITIATE"],
                        "buttonType": "INITIATE",
                        "receiverJID":$('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                        "nick":nick
                    });
                    if (response != false) {
                        if (response.responseMessage != "Successful") {
                            curElem._chatLoggerPlugin($(this));
                            $(this).html(response.responseMessage);
                        } else if (response.buttondetails && response.buttondetails.button) {
                            if (response.actiondetails.errmsglabel) {
                                $(this).html(response.actiondetails.errmsglabel);
                            } else {
                                $(this).find("#sentDiv").removeClass("disp-none");
                                $(this).find("#initiateText,#chatBoxErr").remove();
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
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sentDiv" class="sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div>');
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            break;
        case curElem._contactStatusMapping["pg_acceptance_pending"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sendInt,#restrictMessgTxt,#initiateText,#chatBoxErr").remove();
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="pos-rel wid90p txtc colorGrey padall-10">The member wants to chat</div><div class="pos-rel fullwid txtc colorGrey mt20"><div id="accept" class="acceptInterest padall-10 color5 disp_ib cursp">Accept</div><div id="decline" class="acceptInterest padall-10 color5 disp_ib cursp">Decline</div></div><div id="acceptTxt" class="pos-rel fullwid txtc color5 mt25">Accept interest to continue chat</div><div id="sentDiv" class="fullwid pos-rel disp-none mt10 color5 txtc">Interest Accepted continue chat</div><div id="declineDiv" class="sendDiv txtc disp-none pos-abs wid80p mt10 color5">Interest Declined, you can\'t chat with this user anymore</div>');
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            $('chat-box[user-id="' + userId + '"] #accept').on("click", function () {
                if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                    response = curElem.onChatBoxContactButtonsClick({
                        "receiverID": userId,
                        "checkSum": checkSum,
                        "trackingParams": chatConfig.Params["trackingParams"]["ACCEPT"],
                        "buttonType": "ACCEPT",
                        "receiverJID":$('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                        "nick":nick
                    });
                    if (response != false) {
                        if (response.responseMessage != "Successful") {
                            curElem._chatLoggerPlugin($(this));
                            $(this).html(response.responseMessage);
                            $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt,#chatBoxErr").remove();
                        } else if (response.buttondetails && response.buttondetails.button) {
                            if (response.actiondetails.errmsglabel) {
                                $(this).html(response.actiondetails.errmsglabel);
                                $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                            } else {
                                $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
                                $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                                //$(this).remove();
                                new_contact_state = curElem._contactStatusMapping["both_accepted"]["key"];
                                //TODO: fire query for accepting request
                                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                                $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                                $('chat-box[user-id="' + userId + '"]').attr("group-id", chatConfig.Params.categoryNames["Acceptance"]);
                            }
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
                        "receiverJID":$('chat-box[user-id="' + userId + '"]').attr("data-jid"),
                        "nick":nick
                    });
                    if (response != false) {
                        if (response.responseMessage != "Successful") {
                            curElem._chatLoggerPlugin($(this));
                            $(this).html(response.responseMessage);
                            $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt,#chatBoxErr").remove();
                        } else if (response.buttondetails && response.buttondetails.button) {
                            if (response.actiondetails.errmsglabel) {
                                $(this).html(response.actiondetails.errmsglabel);
                                $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt").remove();
                            } else {
                                $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
                                $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                                //$(this).remove();
                                new_contact_state = curElem._contactStatusMapping["pg_interest_declined"]["key"];
                                //TODO: fire query for accepting request
                                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                                $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                                $('chat-box[user-id="' + userId + '"]').attr("group-id", chatConfig.Params.categoryNames["none_applicable"]);
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
            break;
        case curElem._contactStatusMapping["pog_interest_accepted"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sentDiv,#restrictMessgTxt").remove();
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="fullwid pos-rel mt10 color5 txtc fl">Interest Accepted continue chat</div>');
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            break;
        case curElem._contactStatusMapping["pog_interest_declined"]["key"]:
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="sendDiv txtc pos-abs wid80p mt10 color5">Interest Declined, you can\'t chat with this user anymore</div>');
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            break;
        case curElem._contactStatusMapping["none_applicable"]["key"]:
            //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            break;
        case curElem._contactStatusMapping["both_accepted"]["key"]:
            break;
        }
        //TODO: fire query to get message history as well as offline messages
        //append div of auto handle length with blank initially
        //chat api will put content in it in async mode  
    },
    //based on membership and chatboxtype,enable or disable chat textarea in chat box
    _enableChatTextArea: function (chatBoxType, userId, membership) {
        var curElem = this;
        //check for membership status of logged in user
        if (membership == "paid") {
            if (curElem._contactStatusMapping[chatBoxType]["enableChat"] == true) $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            else $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
        } else if (membership == "free") {
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="pos-abs fullwid txtc colorGrey top120">Only paid members can start chat<div id="becomePaidMember" class="color5 cursp">Become a Paid Member</div></div>');
            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
        }
        //TODO: fire query to get message history as well as offline messages  
    },
    //update status in chat box top
    _updateStatusInChatBox: function (userId, chat_status) {
        //this._chatLoggerPlugin("_updateStatusInChatBox for "+userId+"-"+chat_status+"--"+$('chat-box[user-id="' + userId + '"]').length);
        var groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
        if ($(".chatlist li[id='" + userId + "_" + groupId + "']").length != 0) {
            $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("data-status", chat_status);
        }
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            this._chatLoggerPlugin("change to " + chat_status);
            $("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(chat_status);
        }
    },

    _bottomPanelWidth: 0,

    //appending chat box
    _chatPanelsBox: function (userId, status, jid, pcheckSum, groupId) {
        this._chatLoggerPlugin("pcheckSum", pcheckSum);
        if ($(".chatlist li[id='" + userId + "_" + groupId + "']").length != 0) status = $(".chatlist li[id='" + userId + "_" + groupId + "']").attr("data-status");
        var curElem = this,
            heightPlus = false,
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
            $("body").append("<div id='chatBottomPanel' class='btmNegtaive pos_fix calhgt2 z7 fontlig'></div>");
            curElem._bottomPanelWidth = $(window).width() - $(curElem._parendID).width();
            $(curElem._chatBottomPanelID).css('max-width',curElem._bottomPanelWidth);
            $(curElem._chatBottomPanelID).css("right", $(curElem._parendID).width());
            if ($(curElem._chatBottomPanelID).css("bottom") == "-300px") {
                $(curElem._chatBottomPanelID).css("bottom", "0px");
            }
       }
        if ($('chat-box[user-id="' + userId + '"]').length == 0) {
            var bodyWidth = curElem._bottomPanelWidth,
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
            curElem._appendChatBox(userId, status, jid, pcheckSum, groupId);
        } else {
            $(".extraChatList").each(function (index, element) {
                var id = $(this).attr("id").split("_")[1];
                if (id == userId) {
                    curElem._scrollDown($(".extraPopup"), "retain_extra");
                    setTimeout(function () {
                        $(".extraChats").css("padding-top", "0px");
                    }, 100);
                    var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                        originalElem = $('chat-box[user-id="' + username + '"]'),
                        len = $("chat-box").length,
                        value = parseInt($(".extraNumber").text().split("+")[1]),
                        data = $($("chat-box")[len - 1 - value]).attr("user-id"),
                        chatHtml = $(originalElem).find(".chatMessage").html();
                    curElem._appendChatBox(username, status, jid, pcheckSum, groupId);
                    originalElem.remove();
                    $("chat-box[user-id='" + username + "'] .chatMessage").html("");
                    curElem._postChatPanelsBox(username);
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
            this.innerHTML = '<div class="chatBoxBar fullwid hgt57 bg5 pos-rel fullwid"></div><div class="chatArea fullwid fullhgt"><div class="messageArea f13 bg13 fullhgt"><div class="chatMessage pos_abs fullwid scrollxy" style="height: 250px;"><div class="spinner"></div></div></div><div class="chatInput brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" style="width: 220px;" id="txtArea"  class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Write message"></textarea></div></div>';
            $(this).addClass("z7 btm0 brd_new fr mr7 fullhgt wid240 pos-rel disp_ib");
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
    _appendInnerHtml: function (userId, status) {
        var curElem = this,
            groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id"),
            imgId;
        $("#nchatDivs img").each(function (index, element) {
            if (userId == $(element).attr("id").split("_")[1]) {
                imgId = $(element).attr("id");
            }
        });
        $("#" + imgId).clone().appendTo($('chat-box[user-id="' + userId + '"] .chatBoxBar'));
        $('chat-box[user-id="' + userId + '"] .chatBoxBar img').attr("id", "pic_" + imgId.split("_")[1]);
        $('chat-box[user-id="' + userId + '"] #txtArea').on("keyup", function () {
            curElem._textAreaAdjust(this);
        });
        $('chat-box[user-id="' + userId + '"] #pic_' + userId).addClass("downBarPic cursp");
        $('chat-box[user-id="' + userId + '"] .chatBoxBar').append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig">' + $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html() + '<div class="onlineStatus f11 opa50 mt4"></div></div><div class="iconBar cursp fr padallf_2 disp_ib opa40"><i class="nchatspr nchatic_3"></i><i class="nchatspr nchatic_2 ml10 mr10"></i><i class="nchatspr nchatic_1 mr10"></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">0</span></div></div>');
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
        this._postChatPanelsBox(userId);
        this._bindSendChat(userId);
    },
    //append self sent message on opening window again
    _appendSelfMessage: function (message, userId, uniqueId, status) {
        var curElem = this;
        //console.log("inside _appendSelfMessage",$('chat-box[user-id="' + userId + '"] .chatMessage'));
        $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id="text_' + userId + '_' + uniqueId + '" class="talkText">' + message + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
        var len = $('chat-box[user-id="' + userId + '"] .talkText').length - 1,
            height = $($('chat-box[user-id="' + userId + '"] .talkText')[len]).height();
        $($('chat-box[user-id="' + userId + '"] .talkText')[len]).next().css("margin-top", height);
        
        if (status != "sending") {
            curElem._changeStatusOfMessg(uniqueId, userId, status);
        }
    },
    //add meesage recieved from another user
    _appendRecievedMessage: function (message, userId, uniqueId) {
        var curEle = this,
            that = this;
        this._chatLoggerPlugin("in _appendRecievedMessage");
        //append received message in chatbox
        if (typeof message != "undefined" && message != "") {
            //if chat box is not opened
            if ($('chat-box[user-id="' + userId + '"]').length == 0) {
                $(".profileIcon[id^='" + userId + "']")[0].click();
            }
            //adding message in chat area
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="leftBubble"><div class="tri-left"></div><div class="tri-left2"></div><div id="text_' + userId + '_' + uniqueId + '" class="talkText received" data-msgid=' + uniqueId + '>' + message + '</div></div>');
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
                //$('chat-box[user-id="' + userId + '"] .chatMessage').find('#text_' + userId + '_' + uniqueId).addClass("received");
            } else {
                //$('chat-box[user-id="' + userId + '"] .chatMessage').find('#text_' + userId + '_' + uniqueId).addClass("received");
                curEle._handleUnreadMessages($('chat-box[user-id="' + userId + '"]'));
            }
            //adding bubble for side tab
            if ($("#extra_" + userId + " .pinkBubble").length != 0) {
                val = parseInt($("#extra_" + userId + " .pinkBubble span").html());
                $("#extra_" + userId + " .pinkBubble span").html(val + 1);
                $("#extra_" + userId + " .pinkBubble").show();
            }
            //change count of online matches panel
            if ($(".js-minpanel").length != 0) {
                var count = curEle._onlineUserMsgMe();
                that._chatLoggerPlugin("count - " + count);
            }
            $('chat-box[user-id="' + userId + '"] .chatMessage').animate({
               scrollTop: ($('chat-box[user-id="' + userId + '"] .rightBubble').length + $('chat-box[user-id="' + userId + '"] .leftBubble').length) * 50
            }, 1000);
        }
    },
    //get count of minimized chat boxes with unread messages
    _onlineUserMsgMe: function () {
        var noOfInputs = 0;
        $("chat-box .chatBoxBar .pinkBubble2").each(function (index, element) {
            if ($(this).find(".noOfMessg").html() != 0) {
                noOfInputs++;
            }
        });
        $(".extraChatList .pinkBubble").each(function (index, element) {
            if ($(this).find(".noOfMessg").html() != 0) {
                noOfInputs++;
            }
        });
        if ($('.showcountmin').hasClass('vishid')) {
            this._chatLoggerPlugin('no exist');
            //noOfInputs = 5;
            $('.countVal').html(noOfInputs);
            $('.showcountmin').toggleClass('vishid');
        } else {
            //noOfInputs = 15;
            $('.countVal').html(noOfInputs);
        }
        return noOfInputs;
    },
    //handle typing status of message
    _handleMsgComposingStatus: function (userId, msg_state) {
        this._chatLoggerPlugin("in _handleMsgComposingStatus" + msg_state + userId);
        if (typeof msg_state != "undefined") {
            if (msg_state == 'composing') {
                //localStorage.setItem("status_"+userId, $('chat-box[user-id="' + userId + '"] .onlineStatus').html());
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    this._chatLoggerPlugin("yess", $('chat-box[user-id="' + userId + '"] .downBarUserName'))
                    $('chat-box[user-id="' + userId + '"] .downBarUserName').html('<div class="onlineStatus f11 opa50 mt4">typing...</div>');
                } else {
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').html("typing...");
                }
            } else if (msg_state == 'paused' || msg_state == 'gone') {
                var idStatus = "",
                    groupId = $('chat-box[user-id="' + userId + '"]').attr("group-id");
                this._chatLoggerPlugin($(".chatlist li[id='" + userId + "_" + groupId + "']").find(".nchatspr"));
                if ($(".chatlist li[id='" + userId + "_" + groupId + "']").find(".nchatspr").length != 0) {
                    idStatus = "online";
                } else {
                    idStatus = "offline";
                }
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    var userName = $(".chatlist li[id='" + userId + "_" + groupId + "'] div").html();
                    $('chat-box[user-id="' + userId + '"] .downBarUserName').html(userName + '<div class="onlineStatus f11 opa50 mt4 colrw">' + idStatus + '</div>');
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').hide();
                } else {
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').html(idStatus);
                }
            }
        }
    },
    //change from sending status to sent / sent and read
    _changeStatusOfMessg: function (messgId, userId, newStatus) {
        if(messgId){
            this._chatLoggerPlugin("Change status" + newStatus);
            if (newStatus == "recieved") {
                $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_8").addClass("nchatic_10");
            } 
            else if (newStatus == "recievedRead") {
                $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_8").addClass("nchatic_10");
                setTimeout(function () {
                    $("#text_" + userId + "_" + messgId).next().removeClass("nchatic_10 nchatic_8").addClass("nchatic_9");
                }, 500);
            }
        }
    },
    onEnterToChatPreClick: null,
    onChatLoginSuccess: null, //function triggered after successful chat login
    //start:login screen
    _startLoginHTML: function () {
        this._chatLoggerPlugin('_startLoginHTML call');
        var curEle = this;
        if ($(curEle._chatBottomPanelID).length != 0) {
            setTimeout(function () {
                $(curEle._chatBottomPanelID).show();
            }, 1000);
        }
        //user not logged in and coming for first time 
        if (($(this._listingPanelID).length == 0) && (this._loginStatus == "N")) {
            this._chatLoggerPlugin('case 1');
            $(curEle._loginPanelID).fadeOut('slow', function () {
                curEle._appendLoggedHTML();
            });
        }
        //user was logged earlier in which login is not call'd
        else if (($(this._listingPanelID).length == 0) && (this._loginStatus == "Y")) {
            this._chatLoggerPlugin('case 2');
            if ($(curEle._loginPanelID).length == 0) {
                this._chatLoggerPlugin("ankita_1");
                //curEle._appendLoggedHTML();    
            } else {
                this._chatLoggerPlugin("ankita_2");
                $(curEle._loginPanelID).remove();
                // function () {
                //curEle._appendLoggedHTML();
                //});
            }
        }
        //user logged out from chat in the same session
        else {
            this._chatLoggerPlugin('case 3');
            $(curEle._loginPanelID).fadeOut('fast', function () {
                $(curEle._listingPanelID).fadeIn('slow');
            });
        }
        this._chatLoggerPlugin("Login status value");
        this._chatLoggerPlugin(this._loginStatus);
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
        this._chatLoggerPlugin('TotalBtn: ' + TotalBtn);
        widCal = parseInt(100 / TotalBtn);
        this._chatLoggerPlugin('widCal: ' + widCal);
        this._chatLoggerPlugin("BB");
        var that = this;
        $.each(groupButtons, function (k, v) {
            that._chatLoggerPlugin(k);
            that._chatLoggerPlugin(v);
            that._chatLoggerPlugin("KKKKKK" + v.action);
            if (group == chatConfig.Params["categoryNames"]["Interest Sent"]) {
                str += '<div class="nchatbg-grey lh50 brdr-0 txtc colrw"';
            } else {
                str += '<button class="hBtn bg_pink lh50 brdr-0 txtc colrw cursp"';
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
        this._chatLoggerPlugin("in hoverBoxStr");
        this._chatLoggerPlugin(pCheckSum);
        if ($('#' + param1 + '_hover').length == 0) {
            var str = '<div class="pos_fix info-hover fontlig nz21 vishid" id="' + param1 + '_hover">';
            str += '<div class="nchatbdr3 f13 nchatgrad nchathoverdim pos-rel">';
            str += '<img src="' + param2.photo + '" class="vtop ch220"/>';
            str += '<div id="' + param1 + '_hoverinfo-a">';
            str += '<div class="padall-10 pos-rel">';
            str += '<div class="pos-abs err2 nchatrr1 disp-none" id="' + param1 + '_hoverDvBgEr">';
            str += '<div class="padall-10 colr5 f13 fontli disp-tbl wid90" >';
            str += '<div class="disp-cell vmid txtc lh27 ht160" id="' + param1 + '_hoverBgEr"></div>';
            str += '</div>';
            str += '</div>';
            str += '<ul class="listnone lh22">';
            str += '<li>' + param2.age + ', ' + param2.height + '</li>';
            str += '<li>' + param2.caste + '</li>';
            str += '<li>' + param2.education + '</li>';
            str += '<li>' + param2.occupation + '</li>';
            str += '<li>' + param2.income + ', ' + param2.city + '</li>';
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
        this._chatLoggerPlugin("End of _hoverBoxStr");
    },
    onHoverContactButtonClick: null,
    //start:update vcard
    updateVCard: function (param, pCheckSum, callback) {
        //this._chatLoggerPlugin('in vard update');
        var globalRef = this;
        var finalstr;
        var that = this;
        //$.each(param.vCard, function (k, v) {
        that._chatLoggerPlugin("set");
        //that._chatLoggerPlugin(k);
        finalstr = globalRef._hoverBoxStr(param.jid, param, pCheckSum);
        $(globalRef._mainID).append(finalstr);
        //});
        delete that;
        this._chatLoggerPlugin("Callback calling starts");
        callback();
        this._chatLoggerPlugin("Callaback ends");
    },
    /*
     * Error handling in case of hover
     */
    hoverButtonHandling: function (jid, data, type) {
        this._chatLoggerPlugin("In error handling");
        this._chatLoggerPlugin(jid, data);
        this._chatLoggerPlugin(type);
        if (type == "error") {
            //$("#"+jid+"_BtnRespnse").addClass("disp-none");
            //$("#"+jid+"_hoverDvSmEr").removeClass("disp-none");
            $("#" + jid + "_hoverinfo-a").addClass("disp-none");
            $("#" + jid + "_hoverDvSmEr").addClass("disp_b").removeClass("disp-none");
            $("#" + jid + "_hoverSmEr").html(data.actiondetails.errmsglabel);
        } else if (type == "info") {
            $("#" + jid + "_hoverDvBgEr").removeClass("disp-none");
            $("#" + jid + "_hoverBgEr").html(data.actiondetails.errmsglabel);
            $("#" + jid + "_BtnRespnse div button").addClass("nchatbg-grey colrw");
            $("#" + jid + "_BtnRespnse div button").html(data.buttondetails.button.label);
        } else {
            $("#" + jid + "_BtnOuter button").remove();
            $("#" + jid + "_BtnOuter").append('<button class="bg_pink lh50 brdr-0 txtc colrw cursp" style="width:100%">Start Conversation</button>');
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
        if (this._checkWidth()) {
            var shiftright = 245;
        } else {
            var shiftright = Math.round($(this._parendID)[0].getBoundingClientRect().width);
        }
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
                this._chatLoggerPlugin("Before precall");
                this.onPreHoverCallback(checkSumP, curEleID, hoverNewTop, shiftright);
                //once div is created from precallback below ling shows the hovred list information
                this._chatLoggerPlugin("After precall");
            }
        }
        $('.info-hover').hover(function () {
            $(this).css('visibility', 'visible');
        }, function () {
            $(this).css('visibility', 'hidden');
        });
        $('#' + curEleID + '_hover .hBtn').off('click').on('click', function () {
            if (_this.onHoverContactButtonClick && typeof _this.onHoverContactButtonClick == 'function') {
                if($(this).html() == "Start Conversation"){
                    currentID = $(this).attr("id").split("_")[0];
                    
                    _this._chatPanelsBox(currentID, 'offline', $(this).attr("data-jid"), $(this).attr("data-checks"), $(this).attr("data-group"));
                }
                else{
                    _this.onHoverContactButtonClick(this);
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
        var curEle = this;
        this._chatLoggerPlugin('_appendLoggedHTML');
        $(curEle._parendID).append('<div class="fullwid fontlig nchatcolor" id="js-lsitingPanel"/> ').promise().done(function () {
            curEle._addChatTop();
            curEle.addTab();
            curEle.onChatLoginSuccess();
        });
    },
    /*
     * Sending typing event
     */
    sendingTypingEvent: null,
    //start:this function image,name in top chat logged in scenario
    addLoginHTML: function (failed) {
        this._chatLoggerPlugin('in addLoginHTML');
        var curEle = this;
        var LoginHTML = '<div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarOut"></i> </div><div class="chpt100"> <img src="' + this._imageUrl + '" /> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Enter to Chat</button></div>';
        var errorHTML = '';
        if (failed == true) {
            errorHTML += '<div class="txtc color5 f13 mt10" id="loginErr">' + curEle._loginFailueMsg + '</div>';
        }
        if (failed == false || typeof failed == "undefined" || $("#js-loginPanel").length == 0) $(this._parendID).append(LoginHTML);
        else {
            this._chatLoggerPlugin("removing");
            $(curEle._loginPanelID).fadeIn('fast');
            if ($(curEle._loginPanelID).find("#loginErr").length == 0) $(curEle._loginPanelID).append(errorHTML);
        }
        $('.js-minChatBarOut').click(function () {
            curEle._minimizeChatOutPanel();
        });
        //start login button capture
        var that = this;
        $(this._loginbtnID).click(function () {
            if (curEle.onEnterToChatPreClick && typeof (curEle.onEnterToChatPreClick) == "function") {
                that._chatLoggerPlugin("in onEnterToChatPreClick");
                curEle.onEnterToChatPreClick();
            }
            if (curEle._loginStatus == "Y") {
                that._chatLoggerPlugin("ankita_logged in");
                curEle._startLoginHTML();
            }
        });
        delete that;
    },
    //manage chat loader
    manageChatLoader: function (type) {
        if (type == "hide") {
            this._chatLoggerPlugin("hiding loader_ankita");
            $("#scrollDivLoader").hide();
        }
    },
    //start:this function is that init forthe chat
    start: function () {
        var divElement = document.createElement("Div");
        $(divElement).addClass('pos_fix chatbg chatpos1 z7 js-openOutPanel').appendTo(this._mainID);
        this._createPrototypeChatBox();
        if (this._checkWidth()) {} else {
            $('body').css('width', '80%');
            $(this._parendID).addClass('wid20p').css('height', this._getHeight());
        }
        if (this.checkLoginStatus()) {
            this._chatLoggerPlugin("checking login status");
            this._startLoginHTML();
        } else {
            this._chatLoggerPlugin("in start function");
            this.addLoginHTML();
        }
    },
};
