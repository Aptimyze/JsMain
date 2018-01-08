function GAMapper(GAEvent, extraParams)
{
    return;
    try {
        var PageName = currentPageName || "other page";
        if(LoginLayerByUserActions)
        var userStatus = "Unregistered";
        if(typeof(loggedInJspcGender) === "string" && loggedInJspcGender.length > 0){
            userStatus = loggedInJspcGender;
        }
        var GAMapping = {
            // verify otp layer
            "GA_VOL_MISS_CALL"              :["E", "Enter Code Screen", "Miss Call"],
            "GA_VOL_SUBMIT"                 :["E", "Enter Code Screen", "Submit"],
            "GA_VOL_RESEND"                 :["E", "Enter Code Screen", "Resend Code"],
            "GA_VOL_SUBMIT_ERROR"           :["E", "Enter Code Screen", "Wrong OTP"],
            "GA_VOL_SUBMIT_SUCCESS"         :["E", "Enter Code Screen", "Correct OTP"],
            "GA_VOL_SUCCESS_OK"             :["E", "Phone verification response", "Verify Okay"],
            
            "GA_LL_LOGIN_SUCCESS"           :["E", "login layer", "Login Success"],
            "GA_LL_LOGIN_FAILURE"           :["E", "login layer", "Login Failure"],

            
            "GA_CE"                         :["E", PageName || "", (extraParams['action'] || "")],

            "GA_CE_MYJSJUSTJOINED"          :["E", 'myjs justjoined', 'Express Interest'],
            "GA_CE_MYJSLASTSEARCH"          :["E", "myjs lastsearch", "Express Interest"],
            "GA_CE_MYJSVERIFIEDMATCHES"     :["E", "myjs verifiedmatches", "Express Interest"],
            "GA_CE_MYJSDAILYMATCHES"        :["E", "myjs dailymatches", "Express Interest"],


            "GA_SEARCH_LOGGEDOUT_PROFILE"   :["E", "Login Layer by user action", "profile"],
            "GA_SEARCH_LOGGEDOUT_ALBUM"     :["E", "Login Layer by user action", "album"],
            "GA_SEARCH_LOGGEDOUT_EOI"       :["E", "Login Layer by user action", extraParams['type'] || ''],



            "GA_LL_LOGIN"                   :["E", "login layer", "Login"],
            "GA_TOPBAR_LOGIN"               :["E", "login", "login"],
            "GA_LL_REGISTER"                :["E", "login layer", "Register"],
            "GA_TOPBAR_REGISTER"            :["E", "login", "Register"],
            "GA_TOPBAR_FORGOT"              :["E", "login", "Forgot Password"],
            "GA_LL_FORGOT"                  :["E", "login layer", "Forgot Password"],
            "GA_FORGOTL_SENDLINK"           :["E", "Forgot Password", "Send link to reset"],

            

            // "GA_LL_LOGIN_BUTTON" : ["login layer", "login", loggedInJspcGender || 'Unregistered']
            "GAV_VOL_SHOW"          :["V", "Verify otp layer"],
            // 
            "GAV_LL_SHOW"           :["V", "Login Layer"+(extraParams['action'] || "")],

            "GA_CAL_NO"             :["E", "CAL NO", extraParams['layerId'] +" "+ extraParams['button']],
            "GA_CAL_YES"            :["E", "CAL YES", extraParams['layerId'] +" "+ extraParams['button']],
            /* click on album on profile description page when logged in */
            "GA_PROFILE_ALBUM"      :["E", PageName || "", "album"],

        }
        if(GAMapping[GAEvent]){
            if(GAMapping[GAEvent][0] == "E"){
                trackJsEventGA(GAMapping[GAEvent][1], GAMapping[GAEvent][2], userStatus);
            }else if(GAMapping[GAEvent][0] == "V"){
                _gaq.push(['_trackPageview', GAMapping[GAEvent][1]]);            
            }
        }
    }
    catch(err) {
        return;
    }
}

var currentlyDisplayedLayer = '';

//this variable has been added for idfy
var idfyStr = '<div class="overlayDiv"><div class="overlayMid disp_ib">'+
        '<div class="txtc padnew20">'+
        '<div class="fontreg f18 color11 disp_ib vertSup">Welcome to verification powered by</div><i class="idfyIconBig vBot"></i></div>'+
        '<div class="f13 color2 fontreg lh21 pad1530">'+
        '<div><b>Please Note:</b> You are leaving the networks of the website <b>Jeevansathi.com</b> to a third party website <b>Idfy.com</b>, (regulated by its own privacy policy), a website not affiliated to Info Edge (India) Limited. The verification of prospects is not being carried out by Jeevansathi.com or any of its agents/associates.<br></div>'+
        '<div>Here’s how it works:</div>'+
        '<table class="txtList mt15">'+
        '<tr>'+
        '<td>Q.</td>'+
        '<td>Who is verifying the information? </td>'+
        '</tr><tr>'+
        '<td>A.</td>'+
        '<td>Jeevansathi has partnered with IDfy to help verify the profiles. IDfy is India’s leading background verification service provider.</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Q.</td>'+
        '<td>How is the information being verified?</td>'+ 
        '</tr><tr>'+
        '<td>A.</td>'+
        '<td>You will need to have some details of the person handy. Please click the verification link, fill in these details and proceed to checkout. The verification report would be available  in 3-5 days.</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Q.</td>'+
        '<td>What will you check? </td>'+
        '</tr><tr>'+
        '<td>A.</td>'+
        '<td>We can check if the person has a criminal background (Court Record Check). We can also check if he/she works in the place he/she claims to work (Employment Check Lite).</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Q.</td>'+
        '<td>What details of the person do you need to verify?</td>'+
        '</tr><tr>'+
        '<td>A.</td>'+
        '<td>You’ll need the following:</div>'+
        '<ul>'+
        '<li>Court Record Check - Full Name, Father’s Name, Date of Birth, and his/her Address.</li>'+
        '<li>Employment Lite - Name of the organization, Office/branch location, Designation (optional) and Department of the person (optional).</li>'+
        '</ul>'+
        '</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Q.</td>'+
        '<td>Do you need consent from the other person?</td>'+
        '</tr><tr>'+
        '<td>A.</td>'+
        '<td>These checks do not need any consent and as such the other person will not be contacted. All you need to ensure if that you have his/her details handy.</td>'+
        '</tr>'+
        '<tr>'+
        '<td>Q.</td>'+
        '<td>How do I pay? </td>'+
        '</tr><tr>'+
        '<td>A.</td>'+
        '<td>It’s really simple, you can pay by Debit or Credit card. It is as simple as buying a product online.</td>'+
        '</tr>'+
        '</table>'+
        
        '</div>'+
        '<a href="http://jeevansathi.idfy.com" target="blank"><div class="lh40 txtc colrw bg_pink cursp"><span class="f17 fontlig ml30">Proceed with Verification</span><span class="vTop pl28 f11 fontlig">Powered By</span><i class="idfyIcon"></i></div></a>'+
        '</div>'+
        '</div>'+
        '<div id="closeBtn" class="disp_ib vTop cursp closeBtnNew"><i class="sprite2 closeSprite"></i></div>';
// JavaScript Document
//this function is for achieving inheritence in javascript
var inheritsFrom = function (child, parent) {
    child.prototype = Object.create(parent.prototype);
    child.prototype.constructor = child;
    //child.prototype.baseConstructor = parent;
    child.prototype.parent = parent.prototype;
};
//********************************************
// Key press handling
//********************************************
$(document).keydown(function (e) {
    if ($("#photoLayerMain:visible").length > 0) {
        return photoLayerKeyHandling(e);
    } else if ($("#filterlayer:visible").length > 0) {
        return moreLayerKeyHandling(e);
    }
});
$(document).ready(function (e) {
    top.document.title = document.title;
    $("#liveChatLinkFooter,#liveChatLinkHeader").click(function () {
        window.fcWidget.open();
    });
    $(".contentHeader").each(function () {
        $(this).mCustomScrollbar();
    });
    //start script for serach by id
    $('.js-srchbyid').click(function () {
        $('.js-overlay').fadeIn(200, "linear", function () {
            $('#srcbyid-layer').fadeIn(300, "linear")
        });
        $('.js-overlay').bind("click", function () {
            closeOverlayOnClick();
            $(this).unbind("click");
        });
        $(document).keyup(function (e) {
            if (e.which == 27) {
                closeOverlayOnClick();
                $(document).unbind("keyup");
            }
        });
    });
    $('#cls-srcbyid').bind("click keypress", function () {
        closeOverlayOnClick();
    });
    $("#searchByIdBtn").click(function () {
        if ($.trim($("#SearchProId").val()).length == 0) {
            $("#SearchProIdBox").addClass("errinp");
            $("#titleErr").html("Required").addClass("errcolr").removeClass("grey5");
            $("#SearchProId").focus();
        } else callApiForProfile();
    });
    
    $('.js-gnbsearchLists').bind("click keypress", function () {
				
				var data = $(this).attr("data");
				var url = '/search/'+data;
				url =  getUrlForHeaderCaching(url);
				window.location.href = url;
        
    });

    //adding idfy code
    $(".idfyDiv, .idfyDiv2").each(function(){
        $(this).off("click").on("click",function(){
            $("#commonOverlay").removeClass("disp-none");   
            $("#commonOverlay").html(idfyStr);            
            $("#closeBtn").off("click").on("click",function(){
                $("#commonOverlay").addClass("disp-none");
            }); 
        }); 
    });
    
});

function closeOverlayOnClick() {
    $('#srcbyid-layer').fadeOut(200, "linear", function () {
        $('.js-overlay').fadeOut(200, "linear")
    });
    $("#SearchProIdBox").removeClass("errinp");
    $("#titleErr").html("Search by profile ID").removeClass("errcolr").addClass("grey5");
    $("#SearchProId").val("");
}

function getSearchQureyParameter(key) {
    var value = false;
    if (location.search.indexOf(key) != -1) {
        value = location.search.substr(location.search.indexOf(key)).split('&')[0].split('=')[1];
    }
    return value;
}
//function to make an ajax for search by profile id
function callApiForProfile() {
    userPro = $.trim($("#SearchProId").val());
    showCommonLoader();
    $.myObj.ajax({
        method: "POST",
        url: '/api/v1/profile/detail?stype=WO',
        async: true,
        cache: true,
        dataType: 'json',
        data: {
            username: userPro,
            fromSearchByPId: 1
        },
        success: function (response) {
            if (response.responseStatusCode == 0) {
                userPro = response.USERNAME;
                $("#SearchProIdBox").removeClass("errinp");
                $("#titleErr").html("");
                window.location.href = "/profile/viewprofile.php?stype=4&username=" + userPro;
            } else {
                $("#SearchProIdBox").addClass("errinp");
                $("#titleErr").html(response.responseMessage).addClass("errcolr").removeClass("grey5");
                $("#SearchProId").focus();
            }
            hideCommonLoader();
        }
    });
}
/*
 * Function to show common Loader
 */
function showCommonLoader(divElement) {
    if (typeof divElement == "undefined") divElement = "body";
    //coverProcessing
    $(divElement).prepend("<div class='commonLoader'><div class='coverProcessing'></div><div class='loaderLinear'></div></div>");
    $('.coverProcessing').fadeIn(1000);
    //Scrolling
    var current = $(window).scrollTop();
    $(window).scroll(function () {
        $(window).scrollTop(current);
    });
    $('.coverProcessing').css("position", "absolute").css("top", current);
    $(".loaderLinear").animate({
        width: '20%'
    }, 'slow', function () {
        $(".loaderLinear").animate({
            width: "40%"
        }, 4000, function () {
            $(".loaderLinear").animate({
                width: "95%"
            }, 20000, function () {});
        });
    });
}
/*
 * Function to hide common Loader
 */
function hideCommonLoader() {
    $(window).off('scroll');
    $(".loaderLinear").stop().animate({
        width: "100%"
    }, "fast", function () {
        $('.commonLoader').fadeOut(1000, function () {
            $('.commonLoader').remove();
        });
    });
}
/**
 * Animate To Top Of Results
 */
function animationToTop() {
    $("html, body").animate({
        scrollTop: 0
    }, 600);
}

function showHidePass(showImgId, passId) {
    var type = {
        0: "password",
        1: "text"
    };
    var show = 1;
    $("#" + showImgId).click(function () {
        if (show) {
            $("#" + passId).attr("type", type[show]);
            show = 0;
        } else {
            $("#" + passId).attr("type", type[show]);
            show = 1;
        }
        $("#" + passId).focus();
    });
    $("#" + passId).bind("paste keyup change", function () {
        if (this.value.length == 0) $("#" + showImgId).hide();
        else if (this.value.length > 0) $("#" + showImgId).show();
    });
}

function checkCommonPassword(pass) {
    var invalidPasswords = new Array("jeevansathi", "matrimony", "password", "marriage", "12345678", "123456789", "1234567890");
    if ($.inArray(pass.toLowerCase(), invalidPasswords) != -1) return false;
    return true;
}
/**
* This function will replace string with mapping present in object(mapObj)
* @param str {string}
* @param mapObj {object}
* example of  mapbj
  var mapObj = {
  search1:replace1,
  search2:replace2
  };
*/
$.ReplaceJsVars = function (str, mapObj) {
        var re = new RegExp(Object.keys(mapObj).join("|"), "gi");
        str = str.replace(re, function (matched) {
            return mapObj[matched];
        });
        return str;
    }
    /**
     * This function will add paramter to existing string and if that name exits it removes
     * @param str {string} 
     * @param param {string}
     * @param value {string}
     * @return updated string {string}
     */
$.addReplaceParam = function (str, param, value) {
        str = str.replace(param, 'noUseVarHahH');
        str = str + "&" + param + "=" + value;
        return str;
    }
    /**
     * This fucntion will replace null by blank values
     */
function removeNull(msg) {
    if (msg) return msg;
    return '';
}

function checkPasswordUserName(emailId, pass) {
    var email = $("#" + emailId).val();
    var end = email.indexOf('@');
    var username = email.substr(0, end);
    return (String(pass) != String(username) && String(pass) != String(email));
}

function slider() {
    var count = 0;
    var slider_width = 600;
    var speed = 500;
    if (count == 0)
    //$("#prev").hide();
    //console.log("1a");
    //console.log($("#images .basic").length);
        $("#next").click(function () {
        if (count < ($("#images .basic").length - 1)) {
            //console.log("3a");
            var move_top = (count + 1) * -slider_width;
            //console.log(move_top);
            $("#images").animate({
                left: move_top
            }, speed);
            count++;
            //console.log(count);
            $("#prev").show();
        } else if (count == ($("#images .basic").length)) {
            //$("#images").animate({left : 0} , speed);
            //count = 0;      
            $("#prev").show();
            //$("#next").hide();
        }
    });
    $("#prev").click(function () {
        $("#next").show();
        if (count > 0) {
            var move_top = (count - 1) * -slider_width;
            $("#images").animate({
                left: move_top
            }, speed);
            count--;
        }
        //if(count == 0)
        //$("#prev").hide();
    });
}
/*
 * Function to stop event propagation
 */
function stopEventPropagation(event, immediatePropagation) {
    event.stopPropagation();
    if (typeof immediatePropagation != "undefined") event.stopImmediatePropagation();
}
/*
 * Return true of dom is visible
 * Check various css property
 * @param domSelectorQuery : Query on which element will be selected
 * @returns true if visible else false
 */
function isDomElementVisible(domSelectorQuery) {
    //Dom Exist or not
    if ($(domSelectorQuery).length == 0) {
        return false;
    }
    //Check for disp-none class
    if ($(domSelectorQuery).hasClass('disp-none')) {
        return false;
    }
    //Check for disp-none class
    if ($(domSelectorQuery).hasClass('hide')) {
        return false;
    }
    //Check hidden property
    if ($(domSelectorQuery + ':hidden').length) {
        return false;
    }
    //check css property
    if ($(domSelectorQuery).css('display') == "none") {
        return false;
    }
    return true;
}

function isBrowserIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
        return true;
    return false;
}

function showErrorMsg(id, msg) {
    var msg = "<span class='js-errorMsgCommon colr5'>" + msg + "</span>"
    $(msg).insertBefore(id);
}

function hideErrorMsg(id, msg) {
    $(".js-errorMsgCommon").remove();
}

function isBrowserFirefox() {
    return (navigator.userAgent.toLowerCase().indexOf('firefox') > -1);
}
//reActivating Scroll Bar
function reloadScrollBars() {
    document.documentElement.style.overflow = 'auto'; // firefox, chrome
    document.body.scroll = "yes"; // ie only
}
//Deactivating Scroll bars
function unloadScrollBars() {
    document.documentElement.style.overflow = 'hidden'; // firefox, chrome
    document.body.scroll = "no"; // ie only
}
//Shaking Effect - Akash
jQuery.fn.shake = function (interval, distance, times) {
    interval = typeof interval == "undefined" ? 100 : interval;
    distance = typeof distance == "undefined" ? 10 : distance;
    times = typeof times == "undefined" ? 3 : times;
    var jTarget = $(this);
    jTarget.css('position', 'relative');
    for (var iter = 0; iter < (times + 1); iter++) {
        jTarget.animate({
            left: ((iter % 2 == 0 ? distance : distance * -1))
        }, interval);
    }
    return jTarget.animate({
        left: 0
    }, interval);
}

function getBellCountData(profileid, setHeader) {
    if (typeof (verifyEmailPage) != "undefined" && verifyEmailPage == "1") return;
    if (typeof (PageSource) != "undefined" && PageSource == "MyjsPc") {
        setHeader = 0;
    }
    if ((profileid != 0 || profileid != 'undefined') && setHeader) {
        url = "/api/v2/common/engagementcount";
        $.myObj.ajax({
            type: 'POST',
            url: url,
            showError: false,
            data: {
                param: "jspcHeader"
            },
            success: function (response) {
                if (setHeader) {
                    setBellCountHTML(response);
                }
                return response;
            }
        });
    } else {
        return null;
    }
}

function setBellCountHTML(data) {
    // console.log(data);
    if (data) { 
        var maxCount = 100;
        var maxWrapStr = "99+";
        if (parseInt(data.TOTAL_NEW)) {
            $("#totalBellCountParent").css('display', 'table');
            if (data.TOTAL_NEW < maxCount) {
                $("#totalBellCount").text(data.TOTAL_NEW);
            } else {
                $("#totalBellCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.NEW_MATCHES)) {
            $("#justJoinedCountParent").css('display', 'block');
            if (data.NEW_MATCHES < maxCount) {
                $("#justJoinedCount").text(data.NEW_MATCHES);
            } else {
                $("#justJoinedCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.MESSAGE_NEW)) {
            $("#messagesCountParent").css('display', 'block');
            if (data.MESSAGE_NEW < maxCount) {
                $("#messagesCount").text(data.MESSAGE_NEW);
            } else {
                $("#messagesCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.PHOTO_REQUEST_NEW)) {
            $("#photoRequestsCountParent").css('display', 'block');
            if (parseInt(data.PHOTO_REQUEST_NEW) < maxCount) {
                $("#photoRequestsCount").text(parseInt(data.PHOTO_REQUEST_NEW));
            } else {
                $("#photoRequestsCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.AWAITING_RESPONSE_NEW)) {
            $("#interestsReceivedCountParent").css('display', 'block');
            if (data.AWAITING_RESPONSE_NEW < maxCount) {
                $("#interestsReceivedCount").text(data.AWAITING_RESPONSE_NEW);
            } else {
                $("#interestsReceivedCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.ACC_ME_NEW)) {
            $("#membersAcceptedMeCountParent").css('display', 'block');
            if (data.ACC_ME_NEW < maxCount) {
                $("#membersAcceptedMeCount").text(data.ACC_ME_NEW);
            } else {
                $("#membersAcceptedMeCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.DAILY_MATCHES_NEW)) {
            $("#membersDailyMatchesCountParent").css('display', 'block');
            if (data.DAILY_MATCHES_NEW < maxCount) {
                $("#membersDailyMatchesCount").text(data.DAILY_MATCHES_NEW);
            } else {
                $("#membersDailyMatchesCount").text(maxWrapStr);
            }
        }
	if (parseInt(data.FILTERED_NEW)) {
            $("#membersFilteredInterestCountParent").css('display', 'block');
            if (data.FILTERED_NEW < maxCount) {
                $("#FilteredInterstsCount").text(data.FILTERED_NEW);
            } else {
                $("#FilteredInterstsCount").text(maxWrapStr);
            }
        }
        if (parseInt(data.DEC_ME_NEW)) {
            $("#membersDeclinedMeCountParent").css('display', 'block');
            if (data.DEC_ME_NEW < maxCount) {
                $("#DeclinedMeCount").text(data.DEC_ME_NEW);
            } else {
                $("#DeclinedMeCount").text(maxWrapStr);
            }
        } 
    }
}

function initializeTopNavBar(loggedIn, profileid, moduleName, actionName) {
    $(".TabsContent").find(".TabsMenu").each(function () {
        $(this).find("a").each(function () {
            $(this).on('mouseenter', function () {
                $(".BrowseContent figure").hide();
                var c = $(this).attr("id");
                $("." + c + "_h").show()
            })
        })
    });
    $('.BrowseContent figure').on('mouseenter', function () {
        var getClassName = $(this).attr('class');
        var b = "";
        if (getClassName) {
            b = getClassName.split("_");
        }
        $('.TabsMenu a#' + b[0]).addClass('activeCat');
    }).on('mouseleave', function () {
        $('.TabsMenu a').removeClass('activeCat');
    });
    if ($("#topNavigationBar").hasClass('stickyTopNavBar')) {
        var stickyTopNavBar = 1;
    } else {
        var stickyTopNavBar = 0;
    }
    var stickyHeader = $("#topNavigationBar").offset().top;
    $(document).ready(function () {
        if (stickyTopNavBar) {
            $(document).scroll(function () {
                if ($(this).scrollTop() > stickyHeader) {
                    $("#topNavigationBar").addClass("pos_fix navBarShadowGNB");
                    $("#topNavigationBar").removeClass("pos_rel");
                    $("#topNavigationBar").parent().removeClass("pt35");
                    $("#topNavigationBar").addClass("top_fix");
                } else {
                    $("#topNavigationBar").addClass("pos_rel");
                    $("#topNavigationBar").removeClass("pos_fix navBarShadowGNB");
                    $("#topNavigationBar").parent().addClass("pt35");
                    $("#topNavigationBar").removeClass("top_fix");
                }
            });
        } else {
            $("#topNavigationBar").removeClass("pos_fix navBarShadowGNB");
            $("#topNavigationBar").parent().addClass("pt35");
        }
        if (profileid) {
            if (moduleName != 'homepage' && moduleName != 'myjs' && moduleName != 'register' && moduleName != 'membership' && actionName != 'phoneVerificationPcDisplay' && actionName != 'logoutPage' && actionName != 'alertManager' && $("#viewBellCountHeader").length) {
                getBellCountData(profileid, 1);
            }
        }
    });
}

function popupFreshDeskGlobal(username, email) {
    setTimeout(function () {
        var len = $("#lc_chat_layout").length;
        if (len) {
            $("#lc_chat_layout").click();
            if ($("#lc_chat_layout input[id*='name']").length) {
                $("#lc_chat_layout input[id*='name']").val(username);
            }
            if ($("#lc_chat_layout input[id*='email']").length) {
                $("#lc_chat_layout input[id*='email']").val(email);
            }
            $("#lc_chat_header").click();
            // var buttonLen = $("#lc_chat_start").length;
            // if(buttonLen && $("#lc_chat_start").is(":visible")){
            //     $("#lc_chat_start").click();
            // }
        }
    }, 90000);
}

function populateFreshDeskGlobal(username, email) {
    setInterval(function () {
        var len = $("#lc_chat_layout").length;
        if (len) {
            if ($("#lc_chat_layout input[id*='name']").length) {
                $("#lc_chat_layout input[id*='name']").val(username);
            }
            if ($("#lc_chat_layout input[id*='email']").length) {
                $("#lc_chat_layout input[id*='email']").val(email);
            }
        }
        // var buttonLen = $("#lc_chat_start").length;
        // if(buttonLen && $("#lc_chat_start").is(":visible")){
        //     $("#lc_chat_start").click();
        // }
    }, 500);
}

function Set_Cookie(name, value, minutes, path, domain, secure) {
    var date = new Date();
    if (minutes) {
        //Make cookie for day
        //date.setTime(date.getTime()+(days*24*60*60*1000));
        //Make cookie for hour
        //date.setTime(date.getTime()+(days60*60*1000));
        //Make cookie for minute
        date.setTime(date.getTime() + (minutes * 60 * 1000));
    }
    document.cookie = name + "=" + escape(value) + ((minutes) ? ";expires=" + date.toGMTString() : "") + ((path) ? ";path=" + path : "") + ((domain) ? ";domain=" + domain : "") + ((secure) ? ";secure" : "");
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function showLayerCommon(layerId) {
    $('.js-overlay').unbind('click');
    $('.js-overlay').eq(0).fadeOut(100, "linear", function () {
        if (currentlyDisplayedLayer) $("#" + currentlyDisplayedLayer).eq(0).fadeOut(100, "linear", function () {
            $('.js-overlay').eq(0).fadeIn(100, "linear", function () {
                $('#' + layerId).fadeIn(100, "linear", function () {
                    currentlyDisplayedLayer = layerId;
                })
            });
        });
        else $('.js-overlay').eq(0).fadeIn(100, "linear", function () {
            $('#' + layerId).fadeIn(100, "linear", function () {
                currentlyDisplayedLayer = layerId;
            })
        });
    });

}

function closeCurrentLayerCommon(extraFunction) {
    if (extraFunction) extraFunction();
    $("#" + currentlyDisplayedLayer).eq(0).fadeOut(100, "linear", function () {
        $('.js-overlay').eq(0).fadeOut(100, "linear", function () {})
    });
}

function sendAjaxHtmlDisplay(ajaxConfig, fun) {
    ajaxConfig.dataType = 'html';
    showCommonLoader();
    ajaxConfig.success = function (resp) {
        if (!resp) return;
        resp = $(resp.trim());
        var id = resp.attr('id');
        $('#' + id).remove();
        $('body').prepend(resp);
        showLayerCommon(resp.attr('id'));
        hideCommonLoader();
        if(typeof fun=='function')
            fun();
    };
    jQuery.myObj.ajax(ajaxConfig);
}

function logOutCheck(param,upgradeFromTopNavBar){
    if(top.logOut)
       top.logOut(); 
    
    if(top.profileId) 
    {
        if(upgradeFromTopNavBar!=1 || upgradeFromTopNavBar==="undefined")
            param=param+"&profileId="+top.profileId;
    } 
    top.location.href=param; 
    return true; 
}

function isStorageExist()
{
    var bVal = true;
    if(typeof(Storage)=='undefined')
        bVal = false;

    try{
        localStorage.setItem('testLS',"true");
        localStorage.getItem('testLS');
        localStorage.removeItem('testLS');
    }catch(e)
    {
        bVal = false;
    }
    return bVal;
}

var timeToCache = 3600; // Time in seconds

function getSearchCacheLocalStorageData(profileid,label)
{
	profileSearchCacheData = localStorage.getItem(profileid);
	return jQuery.parseJSON(profileSearchCacheData);
}

function setSearchCacheLocalStorageData(profileid,label,value)
{
	var current = {};
	profileSearchCacheData = localStorage.getItem(profileid);
	if(profileSearchCacheData!=null)
	{
		current = jQuery.parseJSON(profileSearchCacheData);
	}
	if(label)
		current[label]=value;
	localStorage.setItem(profileid, JSON.stringify(current));
}


function getUrlForHeaderCaching($url)
{
	var now = $.now();
	if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser!="")
	{
		var data = getSearchCacheLocalStorageData(loggedInJspcUser);
		var lastDppHeaderCaching = null;
		var lastDppChangedActionTimestamp = null;
		var lastContactActionTimestamp = null;
		if(data)
		{
			lastDppHeaderCaching = data['dppHeaderCaching'];
			lastDppChangedActionTimestamp = data['lastDppChangedActionTimestamp'];
			lastContactActionTimestamp = data['lastContactActionTimestamp'];
		}
		if(lastDppHeaderCaching!=null)
		{
			timestamp = lastDppHeaderCaching;
			var seconds =  (now - lastDppHeaderCaching)/1000;
			if(seconds>timeToCache)
				timestamp = now;
			if(lastDppChangedActionTimestamp>timestamp)
				timestamp = lastDppChangedActionTimestamp;
			if(lastContactActionTimestamp>timestamp)
				timestamp = lastContactActionTimestamp;
		}
		else
			timestamp = now;
		setSearchCacheLocalStorageData(loggedInJspcUser,'dppHeaderCaching',timestamp);
	
		if($url.indexOf('?')!='-1')
			 return $url +"&useHeaderCaching=0&timestamp="+timestamp;
		else
			return $url+"?useHeaderCaching=0&timestamp="+timestamp;	
	}
	return $url;
}
function callAfterContact()
{
	var now = $.now();
	if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser!="")
	{
		setSearchCacheLocalStorageData(loggedInJspcUser,'lastContactActionTimestamp',now);
	}
}
function callAfterDppChange()
{
	var now = $.now();
	if(typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser!="")
	{
		setSearchCacheLocalStorageData(loggedInJspcUser,'lastDppChangedActionTimestamp',now);
	}
}

function showCustomCommonError(msg,timeInMs)
{
        if(typeof(msg)=='undefined') msg='Something went wrong. Please try again after some time.';
        if(typeof(timeInMs)=='undefined') timeInMs=1500;
            $("#commonError #js-commonErrorMsg").text(msg);
            $("#commonError").slideDown("slow");
        setTimeout('$("#commonError").slideUp("slow")',timeInMs);

}

function inviewCheck()
{
    $.belowthefold = function(element, settings) {
        var fold = $(window).height() + $(window).scrollTop();
        return fold <= $(element).offset().top - settings.threshold;
    };

    $.abovethetop = function(element, settings) {
        var top = $(window).scrollTop();
        return top >= $(element).offset().top + $(element).height() - settings.threshold;
    };

    $.rightofscreen = function(element, settings) {
        var fold = $(window).width() + $(window).scrollLeft();
        return fold <= $(element).offset().left - settings.threshold;
    };

    $.leftofscreen = function(element, settings) {
        var left = $(window).scrollLeft();
        return left >= $(element).offset().left + $(element).width() - settings.threshold;
    };

    $.inviewport = function(element, settings) {
        return !$.rightofscreen(element, settings) && !$.leftofscreen(element, settings) && !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
    };

    $.extend($.expr[':'], {
        "below-the-fold": function(a, i, m) {
            return $.belowthefold(a, {threshold : 0});
        },
        "above-the-top": function(a, i, m) {
            return $.abovethetop(a, {threshold : 0});
        },
        "left-of-screen": function(a, i, m) {
            return $.leftofscreen(a, {threshold : 0});
        },
        "right-of-screen": function(a, i, m) {
            return $.rightofscreen(a, {threshold : 0});
        },
        "in-viewport": function(a, i, m) {
            return $.inviewport(a, {threshold : 0});
        }
    });
}

function showTimerForLightningMemberShipPlan(source,type) {
    if(source == "jsmsMyjs" || source == "jspcMyjs"){
        var cT = new Date(current);
        var eT = new Date(membershipPlanExpiry);
        lightningDealExpiryInSec = Math.floor((eT-cT)/1000);
    }
    if(!lightningDealExpiryInSec) 
        return;
    var currentTime=new Date(); 
    var expiryDate=new Date();
    expiryDate.setSeconds(expiryDate.getSeconds() + parseInt(lightningDealExpiryInSec));
    if(expiryDate<currentTime) return;
    var timeDiffInSeconds=(expiryDate-currentTime)/1000;
    if (timeDiffInSeconds>48*60*60) return;  // check for the timer if the time diff is less than 48 hrs
    var temp=timeDiffInSeconds;
    var timerSeconds=temp%60;
    temp=Math.floor(temp/60);
    var timerMinutes=temp%60;
    temp=Math.floor(temp/60);
    var timerHrs=temp;
    memTimerExtraDays=Math.floor(timerHrs/24);
    memTimerTime=new Date();
    memTimerTime.setHours(timerHrs);
    memTimerTime.setMinutes(timerMinutes);
    memTimerTime.setSeconds(timerSeconds);
    src = source;
    memTimer=setInterval('updateMemTimerLightning()',1000);
}


function formatTimeLightning(i) {
    if (i < 10 && i>=0) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}


function updateMemTimerLightning(){
  var h = memTimerTime.getHours();
  var s = memTimerTime.getSeconds();
  var m = memTimerTime.getMinutes();
  if (!m && !s && !h) {
    if(!memTimerExtraDays) clearInterval(memTimer);
    else memTimerExtraDays--;
  }
  
    memTimerTime.setSeconds(s-1);
    h=h+memTimerExtraDays*24;
    
    m = formatTimeLightning(m);
    s = formatTimeLightning(s);
    h = formatTimeLightning(h);
    
    if(src == "jsmsLanding"){
        $("#jsmsLandingM").html(m);
        $("#jsmsLandingS").html(s);
    }
    else if (src == "jsmsMyjs"){
        $("#myjsM").html(m);
        $("#mysjsS").html(s);
    }
    else if( src == "jspcLanding"){
        $("#jspcLandingM").html(m);
        $("#jspcLandingS").html(s);
        $("#jspcLandingMRenew").html(m);
        $("#jspcLandingSRenew").html(s);
    }
    else if( src == "jspcMyjs"){
        $("#jspcMyjsM").html(m);
        $("#jspcMyjsS").html(s);
    }
}
