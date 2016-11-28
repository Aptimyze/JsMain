var awaitingResponseCount, visitorCount, matchalertCount, limit = 0,tupleObject,tupleObject2,index = 0,pc_temp1 = 0,pc_temp2 = 0,t1 = null,profileCompletionCount = 0,start = 0,m,windowWidth=$(window).width(),tupleObject,tupleObject2, matchAlertNext=0, full_loaded = 0;



function start1() {
	if (profileCompletionCount >= limit) {
		clearInterval(t1);
		return;
	}
	profileCompletionCount += 1;
	pc_temp1 = pc_temp1 - 3.6;
	if (profileCompletionCount == 50) {

		clearInterval(t1);
		t2 = setInterval("start2()", 30);
	};


	$("#percent").html(profileCompletionCount + "%");
	$(".pie2").css("-o-transform", "rotate(" + pc_temp1 + "deg)").css("-moz-transform", "rotate(" + pc_temp1 + "deg)").css("-webkit-transform", "rotate(" + pc_temp1 + "deg)");
};


function start2() {
	if (profileCompletionCount >= limit) {
		clearInterval(t2);
		return;
	}
	pc_temp2 = pc_temp2 - 3.6;
	profileCompletionCount = profileCompletionCount + 1;
	/*if(count==300){
		count = 0;
		clearInterval(t2);
		t1 = setInterval("start1()",100);
	};*/
	$("#percent").html(profileCompletionCount + "%");
	$(".pie1").css("-o-transform", "rotate(" + pc_temp2 + "deg)").css("-moz-transform", "rotate(" + pc_temp2 + "deg)").css("-webkit-transform", "rotate(" + pc_temp2 + "deg)");
};

function profile_completion(lim) {

	limit = parseInt(lim);

	t1 = setInterval("start1()", 30);

};

var AlterChildrenCss = function (childElement)
        {
	        var windowWidth = $(window).width(),tuple_ratio = 80,transformX = (tuple_ratio * windowWidth) / 100 + 10;
            $.each(childElement, function (index, element) {
                $(element).css('width', transformX - 10);
            });
        }

function jsmsMyjsReady() {
    
    var arr=["awaitingResponse","visitor","matchalert"];
    if(document.getElementById("awaitingResponseCount") == null) {
        return ;
    }
	awaitingResponseCount = document.getElementById("awaitingResponseCount").value;
	visitorCount = document.getElementById("visitorCount").value;
	matchalertCount = document.getElementById("matchalertCount").value;
        
        for (i=0;i<arr.length;i++)
	setBlock(arr[i]);
       
    
        setBrowseBand();
        
        $("#hamburger").width($(window).width());
        $(".setWidth").width($(window).width());
        
        $( window ).resize(function() {
            $(".setWidth").width($(window).width());
        });
        
        if (parseInt(awaitingResponseCount)) {
            var slider1=$("#awaitingResponsePresent #awaiting_tuples");
 //         tupleObject = slider1.Slider(7,slider1,parseInt(awaitingResponseCount),"interest_received",awaitingResponseNext);
   //         tupleObject._defaultInit();
            bindScrollAnimation(slider1);
            
        }
        
        if (parseInt(matchalertCount)) {
            var slider2=$("#matchalertPresent #match_alert_tuples");
     //      tupleObject2 =   slider2.Slider(9,slider2,parseInt(matchalertCount),"match_alert",matchAlertNext);
       //   tupleObject2._defaultInit();
         //         bindSlider();
            bindScrollAnimation(slider2);
        }
                $(".contactLoader").css("left",((windowWidth/2)-$(".contactLoader").width()/2)-20+"px");

        bindSlider();
        $("#jsmsProfilePic").bind('click',function() {
        	$(location).attr('href',siteUrl+"/profile/viewprofile.php?ownview=1");
        });
        
}


function setBrowseBand() {
	if (matchalertCount > 0)
		document.getElementById("browseMyMatchBand").style.display = "none";
	else
		document.getElementById("browseMyMatchBand").style.display = "block";
}


function setBlock(blockName) {
    
	var count = eval(blockName + "Count");    
    if (count > 0) {
            
		document.getElementById(blockName + "Present").style.display = "block";
		document.getElementById(blockName + "Absent").style.display = "none";
	} else {
		document.getElementById(blockName + "Present").style.display = "none";
		document.getElementById(blockName + "Absent").style.display = "block";
	}
}
	$(window).load(function() {
        if(typeof completionScore != "undefined")  {
		  profile_completion(completionScore);
        }
	});

        

	$(document).ready(function() {
            
                $("#hamburgerIcon").bind("click", function() {
			if($("#hamburger").length == 0){
                                $(".loaderSmallIcon").addClass("loaderimg").removeClass("dn");
				$("#hamIc").hide();
				if(localStorage.getItem("hamHtml")){
					$("#perspective").append(localStorage.getItem("hamHtml"));	
				} else{
					var htmlStr = '<div id="hamburger" class="hamburgerCommon dn fullwid"><div><div id="outerHamDiv" class="fullwid outerdiv"><div id="mainHamDiv" class="wid76p" style="float:left;"><div id="newHamlist" class="hamlist hampad1"><div id="HamMenu" class="fontlig padHamburger"><div class="fl fullwid pt7"><div class="dispibl txtc  newham_wid32p"><a bind-slide=1 href="/profile/contacts_made_received.php?page=eoi&filter=R" class="dispbl white f12"> <i id="int_rec" class="int_rec newham_icons1 posrel"><div class="posabs newham_pos1 dn"><div class="bg7 disptbl newham_count txtc" ><div class="vertmid dispcell">1</div></div></div></i><div>Interests <br/> Received</div></a></div><div class="dispibl txtc newham_wid32p"><a bind-slide=1 href="/profile/contacts_made_received.php?page=accept&filter=R" class="dispbl white f12"><i id="acc_mem" class="acc_mem newham_icons1 posrel"><div class="posabs newham_pos1 dn"><div class="bg7 disptbl white f12 newham_count txtc"><div class="vertmid dispcell">2</div></div></div></i><div>All<br/>Acceptances</div></a></div><div class="dispibl txtc newham_wid32p"><a bind-slide=1 href="/search/perform?justJoinedMatches=1" class="dispbl white f12"><i id="just_join" class="just_join newham_icons1  posrel"><div class="posabs newham_pos1 dn"><div class="bg7 disptbl white f12 newham_count txtc" ><div class="vertmid dispcell"></div></div></div></i><div>Just Joined <br/> Matches</div></a></div></div><div class="clr"></div></div><div class="brdr9_ham"><div class="newham_pad1 lh25"><div id="memTop" class="white fb1 fontrobbold f15"></div><a id="memBottom" href="/profile/mem_comparison.php" bind-slide=1 class="white f18">Upgrade Membership</a></div></div><div class="brdr9_ham pt20"><ul class="fontlig"><li><a href="#" onclick="translateSite(\'http://hindi.jeevansathi.com\');" bind-slide=1 class="white" style="font-size: 19px;">हिंदी में</a></li><li><a href="/profile/mainmenu.php" bind-slide=1 class="white" style="font-size: 17px;">Home</a></li><li><a href="/search/topSearchBand?isMobile=Y" bind-slide=1 class="white">Search</a></li><li><a href="/search/searchByProfileId" bind-slide=1 class="white">Search by Profile ID</a></li><li><a href="/search/MobSaveSearch" bind-slide=1 id="SAVE_SEARCH" class="white">Saved Searches <span class="dispibl padl10 opa70 f12 dn"></span></a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">My Matches</li><li><a href="/search/perform?justJoinedMatches=1" bind-slide=1 id="JUST_JOINED_COUNT" class="white">Just Joined Matches <span class="dispibl padl10 opa70 f12 dn"></span></a></li><li><a href="/search/verifiedMatches" bind-slide=1 class="white">Verified Matches</a></li><li><a href="/profile/contacts_made_received.php?page=matches&filter=R" bind-slide=1 id="MATCHALERT" class="white">Daily Recommendations <span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/search/perform?partnermatches=1" bind-slide=1 class="white">Desired Partner Matches</a></li><li><a href="/search/perform?kundlialerts=1" bind-slide=1 class="white">Kundli Matches <span class ="dispibl padl10 f12 white opa50">New</span></a></li><li><a href="/search/perform?twowaymatch=1" bind-slide=1 class="white">Mutual Matches</a></li><li><a href="/search/perform?reverseDpp=1" bind-slide=1 class="white">Members Looking For Me</a></li><li><a href="/profile/contacts_made_received.php?page=visitors&filter=R&matchedOrAll=A" bind-slide=1 id="VISITOR_ALERT" class="white">Profile Visitors <span class="dispibl padl10 opa70 f12 dn"></span></a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">My Contacts</li><li><a href="/profile/contacts_made_received.php?page=eoi&filter=R" bind-slide=1 id="AWAITING_RESPONSE" class="white">Interests Received <span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/profile/contacts_made_received.php?page=filtered_eoi&filter=R" bind-slide=1 id="FILTERED" class="white">Filtered Interests <span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/profile/contacts_made_received.php?page=accept&filter=R" bind-slide=1 id="ACCEPTED_MEMBERS" class="white">All Acceptances<span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/profile/contacts_made_received.php?page=phonebook_contacts_viewed&filter=M" bind-slide=1 class="white">Phonebook</a></li><li><a href="/profile/contacts_made_received.php?page=contact_viewers" bind-slide=1 class="white">Who Viewed My Contacts</a></li><li><a href="/profile/contacts_made_received.php?page=favorite" bind-slide=1 id="BOOKMARK" class="white">Shortlisted Profiles <span class="dispibl padl10 opa70 f12 dn"></span></a></li><li><a href="/profile/contacts_made_received.php?page=messages" bind-slide=1 id="MESSAGE_NEW" class="white">Messages<span class="dispibl padl10 opa70 f12 dn"></span></a></li><li><a href="/profile/contacts_made_received.php?page=decline&filter=M" bind-slide=1 class="white">Declined Members</a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">More</li><li><a href="/help/index" bind-slide=1 class="white">Help</a></li><li><a href="/contactus/index" bind-slide=1 class="white">Contact Us</a></li><li><a href="/static/settings" bind-slide=1 class="white">Settings</a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li><a href="" onclick="window.location.href = \'tel:18004196299\';" title="call" alt="call" class="white">1800-419-6299 <span class="dispibl padl10 opa70 f12">Toll Free</span></a></li></ul></div><div class="brdr9_ham pt20" id=\'appDownloadLink1\' style=\'display:none\'><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">It\'s Free</li><li><a onclick="window.location.href=\'/static/appredirect?type=jsmsHamburger\';" bind-slide=1 class="white">Download  Android App </a></li></ul></div><div class="brdr9_ham pt20" id=\'appleAppDownloadLink1\' style=\'display:none\'><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">It\'s Free</li><li><a onclick="window.location.href=\'/static/appredirect?type=jsmsHamburger&channel=iosLayer\';" bind-slide=1 class="white">Download iOS App</a></li></ul></div></div></div><div id="hamProfile" class="dn posfix ham_pos3"><a bind-slide=1 href="/profile/viewprofile.php?ownview=1" class="dispbl fontlig f12 ham_color2"><i class="icons1 posabs ham_icon3 ham_pos4"></i><div class="pt10 txtc"><img id="profileImg" src="IMG_URL/images/jsms/commonImg/3_4_NoFemalePhoto.jpg" style="height:50px; width:50px;" class="ham_imgbrdr brdr18" /></div><div class="lh25">Edit Profile</div></a></div></div></div></div>';
					$("#perspective").append(htmlStr);
					localStorage.setItem("hamHtml",htmlStr);
				}
					//Jquery call 
                                getHamburgerCounts();
				var imported = document.createElement('script');
				imported.src = 'IMG_URL/min/?f=/'+hamJs;
                                imported.onerror = function() {
                                ShowTopDownError(['Something went wrong']); 
                                $("#hamburger").remove(); 
                                setTimeout(function(){
                                    $(".loaderSmallIcon").addClass("dn");
                                    $("#hamIc").show();
                                }, 100);   
                                };

				imported.onload = function() {
					BindNextPage();
				    $("#hamburgerIcon").click();
					setTimeout(function(){
						$(".loaderSmallIcon").addClass("dn").remove();
						$("#hamIc").show();
					}, 100);
					
				};
				document.head.appendChild(imported);
			}
			
		});

		jsmsMyjsReady();
                $(".tuple_image").each(function(index, element) {
                    var dSource=$(this).attr("data-src");   
                    if(dSource) {
                        $(this).attr("src",dSource)
                }
                   });
                $(".contactLoader").each(function(){
                $(this).attr("src","IMG_URL/images/jsms/commonImg/loader.gif");
                });   
		var d = new Date();
		var hrefVal = $("#calltopSearch").attr("href")+"&stime="+d.getTime();
		$("#calltopSearch").attr("href",hrefVal);
                $(document).on('contextmenu', 'img',function(e) {
                return false;
                });

                
    });
function getHamburgerCounts(){
    
    
    $.ajax({
  		          
		url: '/common/hamburgerCounts',
		type: "POST",
		//crossDomain: true,
		success: function(result)
                {

                    if(CommonErrorHandling(result)) 
                    {
                        getCount(result);                    
                    }
                    
                }
});

    
    
    
    
    
    
}
function getCount(response){	
	if(response.THUMBNAIL.url != null) {
		$("#profileImg").attr("src",response.THUMBNAIL.url);	
	}
	if(response.MEMBERSHIPT_TOP != ""){
		$("#memTop").html(response.MEMBERSHIPT_TOP);   
	}
	if(response.MEMBERSHIPT_BOTTOM != ""){
		$("#memBottom").html(response.MEMBERSHIPT_BOTTOM);   
	}
	if(response.AWAITING_RESPONSE_NEW != 0){
		$("#int_rec .newham_pos1 .vertmid").html(response.AWAITING_RESPONSE_NEW);   
		$("#int_rec .newham_pos1").removeClass("dn");  
	}
	if(response.ACC_ME_NEW != 0){
		$("#acc_mem .newham_pos1 .vertmid").html(response.ACC_ME_NEW);   
		$("#acc_mem .newham_pos1").removeClass("dn");  
	}
	if(response.JUST_JOINED_NEW != 0){
		$("#just_join .newham_pos1 .vertmid").html(response.JUST_JOINED_NEW);   
		$("#just_join .newham_pos1").removeClass("dn");  
	}
	if(response.FILTERED != 0){
		$("#FILTERED span").html(response.FILTERED).removeClass("dn");
	}
	if(response.ACCEPTED_MEMBERS != 0){
		$("#ACCEPTED_MEMBERS span").html(response.ACCEPTED_MEMBERS).removeClass("dn");
	}
	if(response.MESSAGE_NEW != 0){
		$("#MESSAGE_NEW span").html(response.MESSAGE_NEW).removeClass("dn");
	}
	if(response.AWAITING_RESPONSE != 0){
		$("#AWAITING_RESPONSE span").html(response.AWAITING_RESPONSE).removeClass("dn");
	}
	if(response.BOOKMARK != 0){
		$("#BOOKMARK span").html(response.BOOKMARK).removeClass("dn");
	}
	if(response.JUST_JOINED_COUNT != 0){
		$("#JUST_JOINED_COUNT span").html(response.JUST_JOINED_COUNT).removeClass("dn");
	}
	if(response.BOOKMARK != 0){
		$("#BOOKMARK span").html(response.BOOKMARK).removeClass("dn");
	}
	if(response.SAVE_SEARCH != 0){
		$("#SAVE_SEARCH span").html(response.SAVE_SEARCH).removeClass("dn");
	} 
	if(response.MATCHALERT != 0){
		$("#MATCHALERT span").html(response.MATCHALERT).removeClass("dn");
	} 
}

	function setNotificationView() {
                    $("#darkSection").toggleClass("posabs");
		$("#darkSection").toggleClass("tapoverlay");
		$("#notificationBellView").toggle();
        if ($("#mainContent").css("overflow")=="hidden") 
                   scrollOn();
               else scrollOff();
		
	};


	function onnewtuples(_parent) {
		if ( _parent.page >= 0 && !(_parent.requestPending==1)) {
			loadnew(_parent);
                        
		}
	};
      
function bindScrollAnimation (elem) {
    var initialPosition = "",indexTupple="",page=0;
    var id = 0,mapString="";
    if(elem.attr("id")== "match_alert_tuples"){
        id= 9;
        page=-1;
        mapString="match_alert";
    } else if(elem.attr("id")== "awaiting_tuples"){
        id=7;
        mapString = "interest_received";
    }
    AlterChildrenCss(elem.children());
    var ob = {page: page,_objId: id,  _parent: elem,_mapString:mapString,eleDomID : $(elem).attr('id'),requestPending:0};
    $.data(elem, "props",ob); 
    $(elem).bind('touchstart', function(){
        
        initialPosition = $(elem).scrollLeft();
    });
    $(elem).scroll(function() {
        clearTimeout($.data(this, "scrollCheck"));
        $.data(this, "scrollCheck", setTimeout(function() {
            var dataRight = false, swipStyle = "", tupleDivArray=$(elem).children();
            if ($(elem).scrollLeft() > initialPosition && initialPosition != "reset"){
                swipStyle = "ltr";
            } else if ($(this).scrollLeft() < initialPosition && initialPosition != "reset") {
                swipStyle = "rtl";
            }
        
            tupleDivArray.each(function(index, element) {
                var leftVal = $(this).offset().left;
              
                if (leftVal > 25 && leftVal < 35) {
                    dataRight = true;
                    indexTupple = index+1;
                }
            });
            if (dataRight == false) {
                tupleDivArray.each(function(index, element) {
                    var leftVal = $(this).offset().left, diff = 0;
                    if (swipStyle == "ltr" && leftVal > 35){
                        diff = parseInt($(elem).scrollLeft() + leftVal - 30);
                        $(elem).animate({scrollLeft:diff + "px"}, 250);
                        initialPosition = "reset";
                        indexTupple = index+1; 
                        return false;
                    }
                    else if (swipStyle == "rtl" && leftVal > 0 && $(this).prev().offset()){
                        diff = parseInt($(elem).scrollLeft() + $(this).prev().offset().left - 25);
                        $(elem).animate({scrollLeft:diff + "px"}, 250);
                        initialPosition = "reset";
                        indexTupple = index+1;
                        return false;
                    }
                });
            }
            if (indexTupple >= $(elem).find('.toupleDiv').length / 2 && swipStyle != "") {
                var boundProps = $.data(elem, "props");
                onnewtuples(boundProps);
            }

        }, 100));
    });
}

function loadnew(eleObj) {
    var proChecksumString="",page_no= (++eleObj.page);
    var prochecks=eleObj._parent.find(".proChecksum");
    proChecksumString+=prochecks.eq(0).val();
    for(i=1;i<prochecks.length;i++)
        proChecksumString+=(","+prochecks.eq(i).val());
    var child = $("#"+eleObj.eleDomID).children('.toupleDiv');
    var ajaxData={'pageNo':page_no,'infoTypeId':eleObj._objId,'profileList':proChecksumString};
    var loadingMoreObj = $("#"+eleObj.eleDomID).find("#loadingMorePic").removeClass("dn").addClass("dispibl");
    eleObj.requestPending=1;
    $.ajax({
		url: "/api/v1/myjs/perform",
		type: "POST",
		data: ajaxData,
                dataType : 'json',
		//crossDomain: true,
		success: function(rsp)
                {
                if (rsp[eleObj._mapString]['tuples']) 
                {
                var x = child[0].outerHTML,
                width = parseInt($(child[0]).css("width")),
                length = rsp[eleObj._mapString]['tuples'].length,
                index = child.length,basicId=$(child[0]).attr("id").split("_")[0]+"_";
                
/*                for (i = 0; i < length; i++) {
                    newdiv = $(child[i + index]);
                    newdiv.css("width", width + "px");
                    newdiv.html(x);
                    newdiv.attr("id",$(child[0]).attr("id").split("_")[0]+"_"+(i + index));
                    }
  */              for (i = 0; i < length; i++) {
                    var y = $(x);
                    if (eleObj._objId == 7) 
                    {
                        y.find(".eoiAcceptBtn").attr("index", (i + index)).children("input").val(rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]);
                        y.find(".eoiDeclineBtn").attr("index", (i + index)).children("input").val(rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]);
                    }
                    y.attr("id",basicId + (i + index));
                    y.find(".username").html(rsp[eleObj._mapString]['tuples'][i]["username"]);    
                    y.find(".tuple_image").attr("src", rsp[eleObj._mapString]['tuples'][i]["photo"]["url"]);
                    y.find(".tuple_title").html(rsp[eleObj._mapString]['tuples'][i]["tuple_title_field"]);
                    y.find(".tuple_age").html(rsp[eleObj._mapString]['tuples'][i]["age"]);
                    y.find(".tuple_height").html(rsp[eleObj._mapString]['tuples'][i]["height"]);
                    y.find(".tuple_caste").html(rsp[eleObj._mapString]['tuples'][i]["caste"]);
                    y.find(".tuple_mtongue").html(rsp[eleObj._mapString]['tuples'][i]["mtongue"]);
                    y.find(".tuple_education").html(rsp[eleObj._mapString]['tuples'][i]["education"]);
                    y.find(".tuple_income").html(rsp[eleObj._mapString]['tuples'][i]["income"]);
                    y.find(".proChecksum").val(rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]);
                    y.find("#detailedProfileRedirect").attr('href','/profile/viewprofile.php?profilechecksum='+rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]+'&'+rsp[eleObj._mapString]['tracking']+"&total_rec="+rsp[eleObj._mapString]['view_all_count']+"&actual_offset="+(i+1)+"&contact_id="+rsp[eleObj._mapString]['contact_id']);
                    y.css('width',width+'px');
                    loadingMoreObj.before(y);
                    
                   }
            }
                        
            loadingMoreObj.addClass("dn").removeClass("dispibl");
            eleObj.requestPending=0;
 
            if (!rsp[eleObj._mapString]['show_next']) {
                eleObj.page = -1;
            }
            else eleObj.page++;

                    
                }
    });
}