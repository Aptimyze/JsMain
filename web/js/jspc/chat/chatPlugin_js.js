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
    _construct: function() {
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
        $("chat-box").each(function(index, element) {
            if ($(this).attr("pos-state") == "open") {
                curEle._scrollUp($(this));
            }
        });
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
            setTimeout(function() {
                $(curEle._chatBottomPanelID).show();
            }, 400);
        }
    },
    //start:minimize html
    minimizedPanelHTML: function() {
        var minChatPanel = '';
        minChatPanel += '<div class="nchatbg1 nchatw2 nchatp6 pos_fix colrw nchatmax js-minpanel cursp">';
        minChatPanel += '<ul class="nchatHor clearfix f13 fontreg">';
        minChatPanel += ' <li>';
        minChatPanel += '<div class="pt5 pr10">ONLINE MATCHES</div>';
        minChatPanel += '</li>';
        minChatPanel += '<li>';
        /*if(this._loginStatus == 'Y'){
            var count = this._onlineUserMsgMe();
            if(count>0){
                minChatPanel +='<div class="bg_pink disp-tbl txtc nchatb">';
                minChatPanel +='<div class="vmid disp-cell">';
                minChatPanel += count;   
                minChatPanel +='</div>';
                minChatPanel +='</div>'; 
            }
            else{
                minChatPanel +='<div class="nchatb vishid"></div>';
            }
        }
        else{
           minChatPanel +='<div class="nchatb vishid"></div>';
        } */
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
    _minimizeChatOutPanel: function() {
        var curEle = this;
        $("chat-box").each(function(index, element) {
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
        $(curEleRef._chatBottomPanelID).hide();
        console.log("In logout Chat");
        console.log(curEleRef._loginStatus);
        if (curEleRef._loginStatus == 'N') {
            $(curEleRef._listingPanelID).fadeOut('slow', function() {
                if ($(curEleRef._loginPanelID).length == 0) {
                    console.log("Length is 0 of login panel");
                    curEleRef.addLoginHTML();
                } else {
                    $(curEleRef._loginPanelID).fadeIn('slow', function() {
                        $(curEleRef._listingPanelID).remove();
                        $(".info-hover").remove();
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
        var chatHeaderHTML = '<div class="nchatbg1 nchatp2 clearfix pos-rel nchathgt1"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarIn"></i> </div><div class="fl"> <img src="' + this._imageUrl + '" class="nchatp4 wd40"/> </div><div class="fl nchatm2 pos-rel"> <div id="js-chattopH" class="pos-abs z1 disp-none"><div class="nchatw1 nchatbg2"><div class="nchatp3"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div><div class="pos-rel pt5 f12 pl7"><span class="nchatcolor1 LogOut1 pt2 jschatLogOut cursp">Logout</span> </div></div></div></div><div class="nchatw1 nchatp9"><div class="colrw f14 pos-rel js-LogoutPanel cursp pl7"> <span class="chatName">Ashish A</span> <i class="nchatspr nchatic1 nchatm4"></i> <i class="nchatspr pos-abs nchatic2 nchatpos3"></i> </div> </div></div></div>';
        $(curEleRef._listingPanelID).append(chatHeaderHTML);
        $(curEleRef._toggleLogoutDiv).off("click").on("click", function() {
            console.log($(this));
            $(curEleRef._toggleID).toggleClass('disp-none');
        });
        $(curEleRef._logoutChat).click(function() {
            if (curEleRef.onLogoutPreClick && typeof(curEleRef.onLogoutPreClick) == "function") {
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
        $(this._tabclass).click(function() {
            curEle._chatTabs($(this).attr('id'));
        })
    },
    noResultError: function() {
        var dataLength;
        $(".js-htab").each(function(index, element) {
            dataLength = 0;
            $(this).find(".chatlist").each(function(index2, element2) {
                console.log($(this).find("li").length);
                dataLength = dataLength + $(this).find("li").length;
            });
            if (dataLength == 0) {
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
        var elem = this,
            statusArr = [],jidStr = "",
            currentID;
        console.log("addListing");
        for (var key in data) {
            if (typeof data[key]["rosterDetails"]["jid"] != "undefined") {
                var runID = data[key]["rosterDetails"]["jid"],
                    res = '',
                    status = data[key]["rosterDetails"]["chat_status"];
                console.log("addlisting for " + runID + "--" + data[key]["rosterDetails"]["chat_status"]);
                var fullJID = runID;
                res = runID.split("@");
                runID = res[0];
                jidStr = jidStr+runID+",";
                statusArr[runID] = status;
                if (typeof data[key]["rosterDetails"]["groups"] != "undefined" && data[key]["rosterDetails"]["groups"].length > 0) $.each(data[key]["rosterDetails"]["groups"], function(index, val) {
                    console.log("groups " + val);
                    var List = '',
                        fullname = data[key]["rosterDetails"]["fullname"],
                        tabShowStatus = $('div.' + val).attr('data-showuser');
                    var getNamelbl = fullname,
                        picurl = data[key]["rosterDetails"]["listing_tuple_photo"],
                        prfCheckSum = data[key]["rosterDetails"]["profile_checksum"]; //ankita for image
                    List += '<li class=\"clearfix profileIcon\"';
                    List += "id=\"" + runID + "_" + val + "\" data-status=\"" + status + "\" data-checks=\"" + prfCheckSum + "\" data-jid=\"" + fullJID + "\">";
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
                        console.log(status + "2222");
                        addNode = true;
                    } else {
                        console.log(status + "1111");
                        if (status == 'online') {
                            addNode = true;
                        }
                    }
                    console.log("addNode" + addNode);
                    if (addNode == true) {
                        if ($('#' + runID + "_" + val).length == 0) {
                            if ($('#' + runID + "_" + val).find('.nchatspr').length == 0) {
                                console.log("checking no of nodes in group " + $('div.' + val + ' ul li').size());
                                if (typeof elem._listingNodesLimit[val] == "undefined" || $('div.' + val + ' ul li').size() <= elem._listingNodesLimit[val]) {
                                    console.log("b2");
                                    var tabId = $('div.' + val).parent().attr("id");
                                    if ($("#show" + tabId + "NoResult").length != 0) {
                                        console.log("me");
                                        $("#show" + tabId + "NoResult").addClass("disp-none");
                                    }
                                    elem._placeContact("new", runID, val, status, List);
                                    if ($('div.' + val + ' ul').parent().hasClass("disp-none")) {
                                        $('div.' + val + ' ul').parent().removeClass("disp-none");
                                    }
                                    $("#" + runID + "_" + val).on("click", function() {
                                        currentID = $(this).attr("id").split("_")[0];
                                        elem._chatPanelsBox(currentID, statusArr[currentID], $(this).attr("data-jid"));
                                    });
                                }
                            }
                        } else {
                            elem._placeContact("existing", runID, val, status);
                        }
                        elem._updateStatusInChatBox(runID, status);
                    }
                });
            }
        }
        elem._chatScrollHght();
        $(elem._scrollDivId).mCustomScrollbar({
            theme: "light"
        });
        //call hover functionality
        $(elem._listingClass).on('mouseenter mouseleave', {
            global: elem
        }, elem._calltohover);
        var APIsrc ="http://xmppdev.jeevansathi.com/api/v1/social/getMultiUserPhoto?pid=";
        console.log("api");
        console.log(jidStr);
        if(jidStr){
            APIsrc += jidStr.slice(0,-1);
        }
        console.log("1123");
        console.log(APIsrc);
        /*$.each(jidArr,function(index,elem){
            if(index < jidArr.length-1) {
                APIsrc += elem+",";
            }
            else {
                APIsrc += elem;
            }
        });*/
        APIsrc += "&photoType=ProfilePic120Url,MainPicUrl";
        console.log("APIsrc",APIsrc);


        //fire query and get response


        var response = {"message":"Successful","statusCode":"0","profiles":{"a1":{"PHOTO":{"ProfilePic120Url":"https://secure.gravatar.com/avatar/ef65f74b4aa2107469060e6e8b6d9478?s=48&r=g&d=monsterid","MainPicUrl":"http:\/\/172.16.3.185\/1092\/13\/21853681-1397620904.jpeg"}},"a2":{"PHOTO":{"ProfilePic120Url":"https://secure.gravatar.com/avatar/ce41f41832224bd81f404f839f383038?s=48&r=g&d=monsterid","MainPicUrl":"http:\/\/172.16.3.185\/1140\/6\/22806868-1402139087.jpeg"}},"a3":{"PHOTO":{"ProfilePic120Url":"https://avatars0.githubusercontent.com/u/46974?v=3&s=96","MainPicUrl":"http:\/\/172.16.3.185\/1153\/15\/23075984-1403583209.jpeg"}},"a6":{"PHOTO":{"ProfilePic120Url":"","MainPicUrl":"http:\/\/xmppdev.jeevansathi.com\/uploads\/NonScreenedImages\/mainPic\/16\/29\/15997035ii6124c9f1a0ee0d7c209b7b81c3224e25iic4ca4238a0b923820dcc509a6f75849b.jpg"}},"a4":{"PHOTO":""}},"responseStatusCode":"0","responseMessage":"Successful","AUTHCHECKSUM":null,"hamburgerDetails":null,"phoneDetails":null};
        $.each(Object.keys(response.profiles),function(index,element){
            if(response.profiles[element].PHOTO.ProfilePic120Url) {
              $(".chatlist img[id*='pic_"+element+"']").attr("src",response.profiles[element].PHOTO.ProfilePic120Url);
            }
        });


    },
    //place contact in appropriate position in listing
    _placeContact: function(key, contactID, groupID, status, contactHTML) {
        if (key == "new") {
            console.log("ankita_adding" + contactID + " in groupID");
            console.log(contactHTML);
            /*if(status == "online")          //add new online element in start
                $('div.' + groupID + ' ul').prepend(contactHTML);
            else */ //add new offline element in end
            $('div.' + groupID + ' ul').append(contactHTML);
        } else if (key == "existing") {
            console.log("changing icon");
            if (status == "online") {
                //add online chat_status icon
                if ($('#' + contactID + "_" + groupID).find('.nchatspr').length == 0) {
                    $(this._mainID).find($('#' + contactID + "_" + groupID)).append('<div class="fr"><i class="nchatspr nchatic5 mt15"></i></div>');
                }
                //move this element to beginning of listing
                /* $('div').find($('#'+contactID + "_" + groupID)).detach();
                $('div.' + groupID + ' ul').prepend(content); */
                //$('#'+contactID + "_" + groupID).parent().prepend($('#'+contactID + "_" + groupID));
            }
        }
    },
    //scrolling down chat box
    _scrollDown: function(elem, type) {
        console.log(elem);
        if (type == "remove") {
            elem.animate({
                bottom: "-350px"
            }, function() {
                $(this).remove();
            });
        } else if (type == "retain" || type == "min") {
            elem.animate({
                bottom: "-307px"
            }, function() {
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
        var curEle = this;
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
            elem.find('.chatMessage').animate({
                scrollTop: (elem.find(".rightBubble").length + elem.find(".leftBubble").length) * 50
            }, 1000);
            $(elem).attr("pos-state", "open");
        });
        curEle._handleUnreadMessages(elem);
    },
    //handle unread messages
    _handleUnreadMessages: function(elem) {
        //handle received and unread messages in chatbox
        var selfJID = getConnectedUserJID(),
            receiverID = $(elem).attr("data-jid");
        $(elem).find(".received").each(function() {
            var msg_id = $(this).attr("data-msgid");
            var msgObj = {
                "from": selfJID,
                "to": receiverID,
                "msg_id": msg_id,
                "msg_state": "receiver_received_read"
            };
            $(this).removeClass("received").addClass("received_read");
            console.log("marking msg as read");
            console.log(msgObj);
            invokePluginReceivedMsgHandler(msgObj);
        });
    },
    //bind clicking minimize icon
    _bindMinimize: function(elem) {
        var curElem = this;
        $(elem).off("click").on("click", function(e) {
            e.stopPropagation();
            curElem._scrollDown($(this).closest("chat-box"), "retain");
        });
    },
    //bind clicking maximize chat box
    _bindMaximize: function(elem, userId) {
        var curElem = this;
        $(elem).off("click").on("click", function() {
            curElem._scrollDown($(this).closest("chat-box"), "retain");
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
    onPostBlockCallback: null,
    //remove from list
    _removeFromListing: function(param1, data) {
        console.log('remove element 11');
        var elem = this;
        //removeCall1 if user is removed from backend
        if (param1 == 'removeCall1' || param1 == 'delete_node') {
            console.log("calllign _removeFromListing");
            for (var key in data) {
                var runID = '';
                runID = data[key]["rosterDetails"]["jid"].split("@")[0];
                if (typeof data[key]["rosterDetails"]["groups"] != "undefined") {
                    console.log(data[key]["rosterDetails"]["groups"]);
                    $.each(data[key]["rosterDetails"]["groups"], function(index, val) {
                        var tabShowStatus = '',
                            listElements = '';
                        //this check the sub header status in the list
                        var tabShowStatus = $('div.' + val).attr('data-showuser');
                        listElements = $('#' + runID + '_' + val);
                        if (tabShowStatus == 'false' && param1 != 'delete_node') {
                            console.log("123");
                            $(listElements).find('.nchatspr').detach();
                        } 
                        else {
                            console.log("345");
                            $('div').find(listElements).detach();
                            if ($('div.' + val + ' ul li').length == 0) {
                                $('div.' + val + ' ul').parent().addClass("disp-none");
                            }
                        }
                        console.log(this);
                        elem._updateStatusInChatBox(runID, "offline");
                    });
                    console.log("here");
                }
            }
        }
        //removeCall2 if user is removed from block click on chatbox
        else if (param1 == 'removeCall2') {
            $(this._mainID).find('*[id*="' + data + '"]').detach();
            if (this.onPostBlockCallback && typeof this.onPostBlockCallback == 'function') {
                this.onPostBlockCallback(data);
            }
        }
        this.noResultError();
    },
    //bind clicking block icon
    _bindBlock: function(elem, userId) {
        var curElem = this,
            enableClose;
        $(elem).off("click").on("click", function() {
            enableClose = true;
            curElem._removeFromListing('removeCall2', userId);
            sessionStorage.setItem("htmlStr_" + userId, $('chat-box[user-id="' + userId + '"] .chatMessage').html());
            $('chat-box[user-id="' + userId + '"] .chatMessage').html('<div id="blockText" class="pos-rel wid90p txtc colorGrey padall-10">You have blocked this user</div><div class="pos-rel fullwid txtc mt20"><div id="undoBlock" class="padall-10 color5 disp_ib cursp">Undo</div></div>');
            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
            enableClose = true;
            setTimeout(function() {
                if (enableClose == true) {
                    curElem._scrollDown($('chat-box[user-id="' + userId + '"]'), "remove");
                }
            }, 5000);
            $('chat-box[user-id="' + userId + '"] #undoBlock').off("click").on("click", function() {
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                enableClose = false;
                var htmlStr = sessionStorage.getItem("htmlStr_" + userId);
                $('chat-box[user-id="' + userId + '"] .chatMessage').html(htmlStr);
                //TODO: fire query for unblock
            });
        });
    },
    _bindUnblock: function(userId) {},
    onSendingMessage: null,
    onChatBoxContactButtonsClick: null,
    //sending chat
    _bindSendChat: function(userId) {
        var _this = this,
            messageId, jid = $('chat-box[user-id="' + userId + '"]').attr("data-jid");
        var out = 1;
        var selfJID = getConnectedUserJID();
        $('chat-box[user-id="' + userId + '"] textarea').focusout(function() {
            console.log("focus out to " + jid);
            out = 1;
            //fire event typing paused
            sendTypingState(selfJID, jid, "paused");
        });
        $('chat-box[user-id="' + userId + '"] textarea').keyup(function(e) {
            var curElem = this;
            if ($(this).val().length >= 1 && out == 1) {
                console.log("typing start");
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
                    
                    $(superParent).find("#initChatText,#sentDiv").remove();
                    $(superParent).find(".chatMessage").css("height", "250px").append('<div class="rightBubble"><div class="tri-right"></div><div class="tri-right2"></div><div id ="tempText_' + userId + '_' + timeLog + '" class="talkText">' + text + '</div><i class="nchatspr nchatic_8 fr vertM"></i></div>');
                    if($(superParent).find("#sendInt").length != 0){
                        $(superParent).find(".chatMessage").append("<div class='pos-rel fr pr10' id='interestSent'>Your interest has been sent</div>")
                        $(superParent).find("#initiateText").remove();
                        $(superParent).find("#sendInt").remove();
                    }
                    var height = $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).height();
                    $($(superParent).find(".talkText")[$(superParent).find(".talkText").length - 1]).next().css("margin-top", height);
                    $('chat-box[user-id="' + userId + '"] .chatMessage').animate({
                        scrollTop: ($(".rightBubble").length + $(".leftBubble").length) * 50
                    }, 500);
                    //fire send chat query and return unique id
                    if (_this.onSendingMessage && typeof(_this.onSendingMessage) == "function") {
                        console.log("in plugin send message");
                        console.log(text);
                        console.log($('chat-box[user-id="' + userId + '"]').attr("data-jid"));
                        var profileChecksum = $(".chatlist li[id*='" + userId + "']").attr("data-checks");
                        var msgSendOutput = _this.onSendingMessage(text, $('chat-box[user-id="' + userId + '"]').attr("data-jid"), profileChecksum, $('chat-box[user-id="' + userId + '"]').attr("data-contact"));
                        messageId = msgSendOutput["msg_id"];
                        console.log("handling output of onSendingMessage in plugin");
                        $("#tempText_" + userId + "_" + timeLog).attr("id", "text_" + userId + "_" + messageId);
                        if (msgSendOutput["canSend"] == true) {
                            //msg sending success,set single tick here
                            _this._changeStatusOfMessg(messageId, userId, "recieved");
                        } else if (msgSendOutput["canSend"] == false) {
                            //msg sending failure
                            $(curElem).prop("disabled", true);
                            if (typeof msgSendOutput["errorMsg"] == "undefined") {
                                msgSendOutput["errorMsg"] = "Something went wrong..";
                            }
                            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="restrictMessgTxt" class="color5 pos-rel fr txtc wid90p">' + msgSendOutput["errorMsg"] + '</div>').addClass("restrictMessg2");
                        }
                    }
                }
            }
        });
    },
    //binding click on extra popup username listing
    _bindExtraUserNameBox: function() {
        var curElem = this;
        $('body').on('click', '.extraUsername', function() {
            curElem._scrollDown($(".extraPopup"), "retain");
            setTimeout(function() {
                $(".extraChats").css("padding-top", "0px");
            }, 100);
            var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                originalElem = $('chat-box[user-id="' + username + '"]'),
                status = $("chat-box[user-id='" + username + "'] .chatBoxBar .onlineStatus").html(),
                chatHtml = $(originalElem).find(".chatMessage").html(),
                jid = $('chat-box[user-id="' + username + '"]').attr("data-jid");
            curElem._appendChatBox(username, status, jid);
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
        $(".extraPopup").append('<div id="extra_' + data + '" class="extraChatList pad8_new"><div class="extraUsername cursp colrw minWid65 disp_ib pad8_new fontlig f14">' + $(".chatlist li[id*='" + data + "'] div").html() + '</div><div class="pinkBubble vertM scir disp_ib padall-10"><span class="noOfMessg f13 pos-abs">1</span></div><i class="nchatspr nchatic_4 cursp disp_ib mt6 ml10"></i></div>');
        $("#extra_" + data + " .pinkBubble span").html($('chat-box[user-id="' + data + '"] .chatBoxBar .pinkBubble2 span').html());
        if ($("#extra_" + data + " .pinkBubble span").html() == 0) {
            $("#extra_" + data + " .pinkBubble").hide();
        }
    },
    //append chat box on page
    _appendChatBox: function(userId, status, jid) {
        $("#chatBottomPanel").prepend('<chat-box pos-state="open" data-jid="' + jid + '" status-user="' + status + '" user-id="' + userId + '"></chat-box>');
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
            curElem._scrollDown($($('chat-box')[position]), "retain");
            $(".extraPopup").animate({
                bottom: "48px"
            });
            setTimeout(function() {
                $(".extraChats").css("padding-top", "11px");
            }, 300);
        });
    },

    _getChatBoxType:function(userId,key){
        console.log("in _getChatBoxType");
        var curElem = this;
        console.log($(".chatlist li[id*='" + userId + "']").attr("id").split(userId + "_")[1]);
        var groupID = $(".chatlist li[id*='" + userId + "']").attr("id").split(userId + "_")[1];
        console.log("ankita" + groupID + "-" + curElem._groupBasedChatBox[groupID]);
        var chatBoxType;
        var oldChatBoxType = $('chat-box[user-id="' + userId + '"]').attr("data-contact");
        if(typeof key == "undefined" || key != "updateChatBoxType"){
            console.log("in case a");
            chatBoxType = curElem._contactStatusMapping[curElem._groupBasedChatBox[groupID]]["key"];
        }
        else{
            console.log("in case b");
            switch(groupID){
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
        if(typeof chatBoxType == "undefined") 
            chatBoxType = curElem._contactStatusMapping["none_applicable"]["key"];  
        console.log("chatboxtype--" + chatBoxType);
        $('chat-box[user-id="' + userId + '"]').attr("data-contact", chatBoxType);
        return chatBoxType;
    },

    _postChatPanelsBox: function(userId) {
        var curElem = this;
        var membership = "paid"; //get membership status-pending
        
        console.log("in _postChatPanelsBox");
        
        //var membership = "free";
        var chatBoxType= curElem._getChatBoxType(userId);
        //setTimeout(function() {
            curElem._setChatBoxInnerDiv(userId, chatBoxType);
            curElem._enableChatTextArea($('chat-box[user-id="' + userId + '"]').attr("data-contact"), userId, membership);
            if($('chat-box[user-id="' + userId + '"] .spinner').length != 0)
                $('chat-box[user-id="' + userId + '"] .spinner').hide();
        //}, 500);
    },

    _updateChatPanelsBox:function(userId){
        var curElem = this;
        if($('chat-box[user-id="' + userId + '"]').length != 0){
            console.log("in _updateChatPanelsBox for "+userId);
            var chatBoxType = curElem._getChatBoxType(userId,"updateChatBoxType");
            curElem._setChatBoxInnerDiv(userId,chatBoxType);
            curElem._enableChatTextArea(chatBoxType,userId,"paid");
        }
    },

    //update contact status and enable/disable chat in chat box on basis of membership and contact status
    _setChatBoxInnerDiv: function(userId, chatBoxType) {
        console.log("in _setChatBoxInnerDiv");
        var curElem = this,
            new_contact_state = chatBoxType,
            response;
        console.log(curElem);
        switch (chatBoxType) {
            case curElem._contactStatusMapping["pg_interest_pending"]["key"]:
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="sendInterest cursp sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_6 "></i><span class="vertTexBtm"> Send Interest</span></div><div id="sentDiv" class="sendDiv disp-none pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div>');
                //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="initiateText" class="color5 pos-rel txtc fullwid nchatm90">Initiating chat will also send your interest</div>');
                $('chat-box[user-id="' + userId + '"] #sendInt').on("click", function() {
                    if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                        response = curElem.onChatBoxContactButtonsClick({
                            "receiverID": userId,
                            "buttonType": "INITIATE"
                        });
                        if (response == true) {
                            $(this).parent().find("#sentDiv").removeClass("disp-none");
                            $(this).parent().find("#initiateText").remove();
                            $(this).remove();
                            new_contact_state = curElem._contactStatusMapping["pog_acceptance_pending"]["key"];
                            $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                        } else console.log("cannot send interest in chat box");
                    }
                });
                break;
            case curElem._contactStatusMapping["pog_acceptance_pending"]["key"]:
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sentDiv" class="sendDiv pos-abs wid140 color5"><i class="nchatspr nchatic_7 "></i><span class="vertTexBtm">Interest sent</span></div>');
                //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                break;
            case curElem._contactStatusMapping["pg_acceptance_pending"]["key"]:
                $('chat-box[user-id="' + userId + '"] .chatMessage').find("#sendInt,#restrictMessgTxt").remove();
                $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div id="sendInt" class="pos-rel wid90p txtc colorGrey padall-10">The member wants to chat</div><div class="pos-rel fullwid txtc colorGrey mt20"><div id="accept" class="acceptInterest padall-10 color5 disp_ib cursp">Accept</div><div id="decline" class="acceptInterest padall-10 color5 disp_ib cursp">Decline</div></div><div id="acceptTxt" class="pos-rel fullwid txtc color5 mt25">Accept interest to continue chat</div><div id="sentDiv" class="fullwid pos-rel disp-none mt10 color5 txtc">Interest Accepted continue chat</div><div id="declineDiv" class="sendDiv txtc disp-none pos-abs wid80p mt10 color5">Interest Declined, you can\'t chat with this user anymore</div>');
                //$('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                $('chat-box[user-id="' + userId + '"] #accept').on("click", function() {
                    if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                        response = curElem.onChatBoxContactButtonsClick({
                            "receiverID": userId,
                            "buttonType": "ACCEPT"
                        });
                        if (response == true) {
                            $(this).closest(".chatMessage").find("#sentDiv").removeClass("disp-none");
                            $(this).closest(".chatMessage").find("#sendInt, #decline, #acceptTxt").remove();
                            $(this).remove();
                            new_contact_state = curElem._contactStatusMapping["both_accepted"]["key"];
                            //TODO: fire query for accepting request
                            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
                            $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                        } else console.log("cannot accept interest in chat box");
                    }
                });
                $('chat-box[user-id="' + userId + '"] #decline').on("click", function() {
                    if (typeof curElem.onChatBoxContactButtonsClick == "function") {
                        response = curElem.onChatBoxContactButtonsClick({
                            "receiverID": userId,
                            "buttonType": "DECLINE"
                        });
                        if (response == true) {
                            $(this).closest(".chatMessage").find("#declineDiv").removeClass("disp-none");
                            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
                            $(this).closest(".chatMessage").find("#sendInt, #accept, #acceptTxt").remove();
                            $(this).remove();
                            //TODO: fire query for declining request
                            new_contact_state = curElem._contactStatusMapping["pg_interest_declined"]["key"];
                            $('chat-box[user-id="' + userId + '"]').attr("data-contact", new_contact_state);
                            setTimeout(function() {
                                curElem._scrollDown($('chat-box[user-id="' + userId + '"]'), "remove");
                            }, 5000);
                        } else console.log("cannot decline interest in chat box");
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
    },
    //based on membership and chatboxtype,enable or disable chat textarea in chat box
    _enableChatTextArea: function(chatBoxType, userId, membership) {
        var curElem = this;
        //check for membership status of logged in user
        if (membership == "paid") {
            if (curElem._contactStatusMapping[chatBoxType]["enableChat"] == true) 
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", false);
            else 
                $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
        } else if (membership == "free") {
            $('chat-box[user-id="' + userId + '"] .chatMessage').append('<div class="pos-abs fullwid txtc colorGrey top120">Only paid members can start chat<div id="becomePaidMember" class="color5 cursp">Become a Paid Member</div></div>');
            $('chat-box[user-id="' + userId + '"] textarea').prop("disabled", true);
        }
        //TODO: fire query to get message history as well as offline messages  
    },
    //update status in chat box top
    _updateStatusInChatBox: function(userId, chat_status) {
        //console.log("_updateStatusInChatBox for "+userId+"-"+chat_status+"--"+$('chat-box[user-id="' + userId + '"]').length);
        if ($(".chatlist li[id*='" + userId + "']").length != 0) {
            $(".chatlist li[id*='" + userId + "']").attr("data-status", chat_status);
        }
        if ($('chat-box[user-id="' + userId + '"]').length != 0) {
            console.log("change to " + chat_status);
            $("chat-box[user-id='" + userId + "'] .chatBoxBar .onlineStatus").html(chat_status);
        }
    },
    //appending chat box
    _chatPanelsBox: function(userId, status, jid) {
        if ($(".chatlist li[id*='" + userId + "']").length != 0) status = $(".chatlist li[id*='" + userId + "']").attr("data-status");
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
            curElem._appendChatBox(userId, status, jid);
        } else {
            $(".extraChatList").each(function(index, element) {
                var id = $(this).attr("id").split("_")[1];
                if (id == userId) {
                    curElem._scrollDown($(".extraPopup"), "retain");
                    setTimeout(function() {
                        $(".extraChats").css("padding-top", "0px");
                    }, 100);
                    var username = $(this).closest(".extraChatList").attr("id").split("_")[1],
                        originalElem = $('chat-box[user-id="' + username + '"]'),
                        len = $("chat-box").length,
                        value = parseInt($(".extraNumber").text().split("+")[1]),
                        data = $($("chat-box")[len - 1 - value]).attr("user-id"),
                        chatHtml = $(originalElem).find(".chatMessage").html();
                    curElem._appendChatBox(username, status, jid);
                    originalElem.remove();
                    $("chat-box[user-id='" + username + "'] .chatMessage").html(chatHtml);
                    $(this).closest(".extraChatList").remove();
                    curElem._addDataExtraPopup(data);
                    curElem._bindExtraPopupUserClose($("#extra_" + data + " .nchatic_4"));
                }
            });
        }
        if ($(".extraChats").length > 0 && $(".extraPopup ").css("bottom") != "-300px") {
            curElem._scrollDown($(".extraPopup "), "retain");
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
        $('chat-box[user-id="' + userId + '"] .chatBoxBar').append('<div class="downBarText fullhgt"><div class="downBarUserName disp_ib pos-rel f14 colrw wid44p fontlig">' + $(".chatlist li[id*='" + userId + "'] div").html() + '<div class="onlineStatus f11 opa50 mt4"></div></div><div class="iconBar cursp fr padallf_2 disp_ib opa40"><i class="nchatspr nchatic_3"></i><i class="nchatspr nchatic_2 ml10 mr10"></i><i class="nchatspr nchatic_1 mr10"></i></div><div class="pinkBubble2 fr vertM scir disp_ib padall-10 m11"><span class="noOfMessg f13 pos-abs">0</span></div></div>');
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
        this._postChatPanelsBox(userId);
        this._bindSendChat(userId);
        //setTimeout(function(){  curElem._appendRecievedMessage("hi this is amacjrheabf erhfbjahberf aerb",userId,"ueiuh");
        /*setTimeout(function(){  this._changeStatusOfMessg("ueiuh",userId,"recieved"); 
        setTimeout(function(){  this._changeStatusOfMessg("ueiuh",userId,"recievedRead"); 
        }, 2000);
        }, 2000);*/
        //}, 8000);
        //this._postChatPanelsBox(userId);
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
        var curEle = this;
        console.log("in _appendRecievedMessage");
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
                console.log("count - " + count);
            }
        }
    },
    //get count of minimized chat boxes with unread messages
    _onlineUserMsgMe: function() {
        var noOfInputs = 0;
        $("chat-box .chatBoxBar .pinkBubble2").each(function(index, element) {
            if ($(this).find(".noOfMessg").html() != 0) {
                noOfInputs++;
            }
        });
        $(".extraChatList .pinkBubble").each(function(index, element) {
            if ($(this).find(".noOfMessg").html() != 0) {
                noOfInputs++;
            }
        });
        if ($('.showcountmin').hasClass('vishid')) {
            console.log('no exist');
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
    _handleMsgComposingStatus: function(userId, msg_state) {
        console.log("in _handleMsgComposingStatus" + msg_state + userId);
        if (typeof msg_state != "undefined") {
            if (msg_state == 'composing') {
                //localStorage.setItem("status_"+userId, $('chat-box[user-id="' + userId + '"] .onlineStatus').html());
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    console.log("yess", $('chat-box[user-id="' + userId + '"] .downBarUserName'))
                    $('chat-box[user-id="' + userId + '"] .downBarUserName').html('<div class="onlineStatus f11 opa50 mt4">typing...</div>');
                } else {
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').html("typing...");
                }
            } else if (msg_state == 'paused' || msg_state == 'gone') {
                var idStatus = "";
                console.log($(".chatlist li[id*='" + userId + "']").find(".nchatspr"));
                if ($(".chatlist li[id*='" + userId + "']").find(".nchatspr").length != 0) {
                    idStatus = "online";
                } else {
                    idStatus = "offline";
                }
                if ($('chat-box[user-id="' + userId + '"] .chatBoxBar img').hasClass("downBarPicMin")) {
                    var userName = $(".chatlist li[id*='" + userId + "'] div").html();
                    $('chat-box[user-id="' + userId + '"] .downBarUserName').html(userName + '<div class="onlineStatus f11 opa50 mt4">' + idStatus + '</div>');
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').hide();
                } else {
                    $('chat-box[user-id="' + userId + '"] .onlineStatus').html(idStatus);
                }
            }
        }
    },
    //change from sending status to sent / sent and read
    _changeStatusOfMessg: function(messgId, userId, newStatus) {
        console.log("Change status" + newStatus);
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
    onChatLoginSuccess: null, //function triggered after successful chat login
    //start:login screen
    _startLoginHTML: function() {
        console.log('_startLoginHTML call');
        var curEle = this;
        if ($(curEle._chatBottomPanelID).length != 0) {
            setTimeout(function() {
                $(curEle._chatBottomPanelID).show();
            }, 1000);
        }
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
            if ($(curEle._loginPanelID).length == 0) {
                console.log("ankita_1");
                //curEle._appendLoggedHTML();    
            } else {
                console.log("ankita_2");
                $(curEle._loginPanelID).fadeOut('fast', function() {
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
    _calHoverPos: function(param2, param3) {
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
    _getButtonStructure: function(userId, group, pCheckSum) {
        var groupButtons = chatConfig.Params[device]["buttons"][group];
        var str = '';
        var TotalBtn = '',
            widCal = '';
        TotalBtn = groupButtons.length;
        console.log('TotalBtn: ' + TotalBtn);
        widCal = parseInt(100 / TotalBtn);
        console.log('widCal: ' + widCal);
        console.log("BB");
        $.each(groupButtons, function(k, v) {
                console.log(k);
                console.log(v);
                console.log("KKKKKK" + v.action);
                if(group == chatConfig.Params["categoryNames"]["Interest Sent"]){
                    str += '<div class="nchatbg-grey lh50 brdr-0 txtc colrw"';
                }
                else{
                    str += '<button class="hBtn bg_pink lh50 brdr-0 txtc colrw cursp"';
                }
                str += 'id="' + userId + '_' + v.action + '"';
                str += 'data-pCheckSum="' + pCheckSum + '"';
                str += 'data-params="' + v.params + '"';
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
                if(group == chatConfig.Params["categoryNames"]["Interest Sent"]){
                    str += '</div>';
                }
                else{
                    str += '</button>';
                }
            })
            /*
            $.each(param2["buttonDetails"]["buttons"],function(k,v){
                
            });
            */
        return str;
    },
    //start:hover box html structure
    _hoverBoxStr: function(param1, param2, pCheckSum) {
        var _this = this;
        var group = $(".chatlist li[id*='" + param1 + "']").attr("id").split(param1 + "_")[1];
        //console.log($('#'+param1+'_hover').length);
        console.log("in hoverBoxStr");
        console.log(pCheckSum);
        if ($('#' + param1 + '_hover').length == 0) {
            var str='<div class="pos_fix info-hover fontlig nz21 vishid" id="'+param1+'_hover">';
	str+='<div class="nchatbdr3 f13 nchatgrad nchathoverdim pos-rel">';
    	str+='<img src="' + param2.PHOTO + '" class="vtop ch220"/>';
			str+='<div id="'+param1+'_hoverinfo-a">';
        		str+='<div class="padall-10 pos-rel">';
            		str+='<div class="pos-abs err2 nchatrr1 disp-none" id="'+param1+'_hoverDvBgEr">';
            			str+='<div class="padall-10 colr5 f13 fontli disp-tbl wid90" >';
            				str+='<div class="disp-cell vmid txtc lh27 ht160" id="'+param1+'_hoverBgEr"></div>';
            			str+='</div>';
            		str+='</div>';
            		str+='<ul class="listnone lh22">';
                        str+='<li>'+param2.AGE+', '+ param2.HEIGHT+'</li>';
                        str+='<li>'+param2.COMMUNITY+'</li>';
                        str+='<li>'+ param2.EDUCATION +'</li>';
                        str+='<li>'+ param2.PROFFESION +'</li>';
                        str+='<li>'+ param2.SALARY+'</li>';
                        str+='<li>'+ param2.CITY+'</li>';
                    str+='</ul>';
            	str+='</div>';
           		str+='<div class="fullwid clearfix" id="'+param1+'_BtnRespnse">';
                str+='<p class="txtc nc-color2 lh27 nhgt28"></p>';
                	str+='<div id="'+param1+'_BtnOuter">';
            			str += _this._getButtonStructure(param1, group, pCheckSum);
            		str+='</div>';
            	str+='</div>';
          str+='</div>';
          
            str+='<div id="'+param1+'_hoverDvSmEr" class="pos-rel padall-10 disp-none">';
          	str+='<div class="txtr">';
            	str+='<i class="nchatspr nchatic_1 hcross" id="'+param1+'_hcross" ></i>';
            str+='</div>';
            
                str+='<div class="disp-tbl f13 colr5 fontlig fullwid">';
                	str+='<div class="disp-cell vmid txtc nhgt180" id="'+param1+'_hoverSmEr">';
                    	
                    str+='</div>';                
                str+='</div>   ';         
            str+='</div>';
           
	str+='</div>';
str+='</div>';           	

            return str;
        }
        console.log("End of _hoverBoxStr");
    },
    onHoverContactButtonClick: null,
    //start:update vcard
    updateVCard: function(param, pCheckSum, callback) {
        //console.log('in vard update');
        var globalRef = this;
        var finalstr;
        $.each(param.vCard, function(k, v) {
            console.log("set");
            console.log(k);
            finalstr = globalRef._hoverBoxStr(k, v, pCheckSum);
            $(globalRef._mainID).append(finalstr);
        });
        console.log("Callback calling starts");
        callback();
        console.log("Callaback ends");
    },
    
    /*
     * Error handling in case of hover
     */
    hoverButtonHandling: function(jid,data,type){
        console.log("In error handling");
        console.log(jid,data);
        console.log(type);
        if(type == "error"){
            //$("#"+jid+"_BtnRespnse").addClass("disp-none");
            //$("#"+jid+"_hoverDvSmEr").removeClass("disp-none");
            $("#"+jid+"_hoverinfo-a").addClass("disp-none");
            $("#"+jid+"_hoverDvSmEr").addClass("disp_b").removeClass("disp-none");            
            $("#"+jid+"_hoverSmEr").html(data.actiondetails.errmsglabel);

        }
        else if(type == "info"){
            $("#"+jid+"_hoverDvBgEr").removeClass("disp-none");
            $("#"+jid+"_hoverBgEr").html(data.actiondetails.errmsglabel);
            $("#"+jid+"_BtnRespnse div button").addClass("nchatbg-grey colrw");
            $("#"+jid+"_BtnRespnse div button").html(data.buttondetails.button.label);
        }
        else{
            $("#"+jid+"_BtnOuter button").remove();
            $("#"+jid+"_BtnOuter").append('<button class="bg_pink lh50 brdr-0 txtc colrw cursp" style="width:100%">Start Conversation</button>');
        }
    },
    //start:check hover
    _checkHover: function(param) {
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
        //console.log('hoverNewTop:'+hoverNewTop+' shiftright:'+shiftright);
        //if element exist        
        if ($('#' + curEleID + '_hover').length != 0) {
            $('#' + curEleID + '_hover').css({
                'top': hoverNewTop,
                'visibility': 'visible',
                'right': shiftright
            });
        } else {
            //console.log('call to onPreHoverCallback');
            if (this.onPreHoverCallback && typeof this.onPreHoverCallback == 'function') {
                console.log("Before precall");
                this.onPreHoverCallback(checkSumP, curEleID, hoverNewTop, shiftright);
                //once div is created from precallback below ling shows the hovred list information
                console.log("After precall");
                console.log("Atul console");
            }
        }
        $('.info-hover').hover(function() {
            $(this).css('visibility', 'visible');
        }, function() {
            $(this).css('visibility', 'hidden');
        });
        $('#' + curEleID + '_hover .hBtn').off('click').on('click', function() {
            if (_this.onHoverContactButtonClick && typeof _this.onHoverContactButtonClick == 'function') {
                _this.onHoverContactButtonClick(this);
            }
        });
        $('.hcross').off('click').on('click', function() {
            var id = $(this).attr('id');
            var jid = id.split('_');
            jid = jid[0];
            $("#"+jid+"_hoverinfo-a").removeClass("disp-none");
            $("#"+jid+"_hoverDvSmEr").removeClass("disp_b").addClass("disp-none");
        });
    },
    _timer: null,
    //start:hover functionality
    _calltohover: function(e) {
        //console.log("In _calltohover");
        //global level ref.
        var _this = e.data.global;
        var curHoverEle = this;
        //console.log(this);
        var getID = $(this).attr('id');
        getID = getID.split("_");
        getID = getID[0];
        //set timer variable
        if (e.type == "mouseenter") {
            clearTimeout(_this._timer);
            _this._timer = setTimeout(function() {
                _this._checkHover(curHoverEle);
            }, 1000);
        } else {
            clearTimeout(_this._timer);
            $('#' + getID + '_hover').css('visibility', 'hidden');
        }
        $('.info-hover').hover(function() {
            $(this).css('visibility', 'visible');
        }, function() {
            $(this).css('visibility', 'hidden');
        });
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
        var LoginHTML = '<div class="fullwid txtc fontlig pos-rel" id="js-loginPanel"><div class="pos-abs nchatpos6"> <i class="nchatspr nchatclose cursp js-minChatBarOut"></i> </div><div class="chpt100"> <img src="' + this._imageUrl + '" /> </div><button id="js-chatLogin" class="chatbtnbg1 mauto chatw1 colrw f14 brdr-0 lh40 cursp nchatm5">Login to Chat</button></div>';
        var errorHTML = '';
        if (failed == true) {
            errorHTML += '<div class="txtc color5 f13 mt10" id="loginErr">' + curEle._loginFailueMsg + '</div>';
        }
        if (failed == false || typeof failed == "undefined" || $("#js-loginPanel").length == 0) $(this._parendID).append(LoginHTML);
        else {
            console.log("removing");
            $(curEle._loginPanelID).fadeIn('fast');
            if ($(curEle._loginPanelID).find("#loginErr").length == 0) $(curEle._loginPanelID).append(errorHTML);
        }
        $('.js-minChatBarOut').click(function() {
            curEle._minimizeChatOutPanel();
        });
        //start login button capture
        $(this._loginbtnID).click(function() {
            if (curEle.onEnterToChatPreClick && typeof(curEle.onEnterToChatPreClick) == "function") {
                console.log("in onEnterToChatPreClick");
                curEle.onEnterToChatPreClick();
            }
            if (curEle._loginStatus == "Y") {
                console.log("ankita_logged in");
                curEle._startLoginHTML();
            }
        });
    },
    //manage chat loader
    manageChatLoader: function(type) {
        if (type == "hide") {
            console.log("hiding loader_ankita");
            $("#scrollDivLoader").hide();
        }
    },
    //start:this function is that init forthe chat
    start: function() {
        var divElement = document.createElement("Div");
        $(divElement).addClass('pos_fix chatbg chatpos1 nz20 js-openOutPanel').appendTo(this._mainID);
        this._createPrototypeChatBox();
        if (this._checkWidth()) {} else {
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