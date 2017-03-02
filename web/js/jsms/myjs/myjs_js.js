var awaitingResponseCount, visitorCount, matchalertCount, limit = 0,tupleObject,tupleObject2,tupleObject3,index = 0,pc_temp1 = 0,pc_temp2 = 0,t1 = null,profileCompletionCount = 0,start = 0,m,windowWidth=$(window).width(),tupleObject,tupleObject2,tupleObject3, matchAlertNext=0, full_loaded = 0,matchOfDayNext=0;



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

function createSlider() {
var jsonObj = myjsdata,hrefStr,htmlStr,tupleLength,contact_id,tracking,elemArray = ["profilechecksum","username","tuple_title_field","age","height","caste","mtongue","income"];
	if(jsonObj.match_alert.tuples != null){
		tupleLength = jsonObj.match_alert.tuples.length,contact_id = jsonObj.match_alert.contact_id,tracking = jsonObj.match_alert.tracking.split("responseTracking=")[1];
		$.each(jsonObj.match_alert.tuples,function(index,elem) {
			$.each(elemArray,function(index2,elem2){
				if(elem[elem2] == undefined || elem[elem2] == null){
					elem[elem2] = "";
				}
			});
			if(elem.photo.url == undefined || elem.photo.url == null) {
				if(userGender == "M") {
					elem.photo.url = "IMG_URL/images/picture/120x120_f.png?noPhoto";
				} else if(userGender == "F") {
					elem.photo.url = "IMG_URL/images/picture/120x120_m.png?noPhoto";
				}
			}
		});
		$.each(jsonObj.match_alert.tuples,function(index,elem) {
			hrefStr = "/profile/viewprofile.php?profilechecksum="+elem.profilechecksum+"&responseTracking="+tracking+"&total_rec="+tupleLength+"&actual_offset="+(index+1)+"&contact_id="+contact_id;
			htmlStr = '<div class="mr10 dispibl ml0 posrel wid300" id="matchAlerttuple_'+index+'" index="'+index+'"><input class="proChecksum" type="hidden" value="'+elem.profilechecksum+'"><img class="srp_box2 contactLoader posabs dispnone top65" src="IMG_URL/images/jsms/commonImg/loader.gif"><div class="bg4 overXHidden" id="hideOnAction"><a id="detailedProfileRedirect" href="'+hrefStr+'"><div class="pad16 scrollhid hgt140"><div class="overXHidden fullheight"><div class="whitewid200p overflowWrap"><div class="fl"><img class="tuple_image hgtwid110" src="'+elem.photo.url+'" border="0"> </div><div class="fl pl_a" style="width:48%"><div class="f14 color7"> <div class="username textTru">'+(elem.name_of_user ? elem.name_of_user : elem.username)+'</div></div><div class="attr"><ul><li class="textTru"><span class="tuple_title">'+elem.tuple_title_field+'</span> </li><li class="textTru"><span class="tuple_age">'+elem.age+'</span> Years  <span class="tuple_height"> '+elem.height+' </span> </li><li class="textTru"><span class="tuple_caste whtSpaceNo">'+elem.caste+'</span></li><li class="textTru"><span class="tuple_mtongue">'+elem.mtongue+'</span></li>';
            if(userGender == "F") {
                htmlStr = htmlStr +'<li class="textTru"><span class="tuple_income">'+elem.income+'</span></li>';
            } else {
                htmlStr = htmlStr +'<li class="textTru"><span class="tuple_education">'+elem.education+'</span></li>';    
            }
            htmlStr = htmlStr +'</ul></div></div><div class="clr"></div></div></div></div> </a><div class="brdr8 fullwid hgt60"><div class="txtc fullwid fl matchAlertBtn brdr7 pad2" index="'+index+'"><input class="inputProChecksum" type="hidden" value="'+elem.profilechecksum+'"><a class="f15 color2 fontreg">Send Interest</a></div><div class="clr"></div></div></div></div>';
			$("#match_alert_tuples").append(htmlStr);
			if(index == (tupleLength-1)){
				for(var i=0;i<10;i++) {
	            	$("#match_alert_tuples").append('<div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>');
	            }
				if (parseInt(matchalertCount)) {
	            	var slider2=$("#matchalertPresent #match_alert_tuples");
	           		tupleObject2 = slider2.Slider(9,slider2,tupleLength,"match_alert",matchAlertNext);
	          		tupleObject2._defaultInit();
	                bindSlider();
	                $(".contactLoader").css("left",((windowWidth/2)-$(".contactLoader").width()/2)-20+"px");
	        		$("#matchLoader").remove();
	        		$("#match_alert_tuples").removeClass("dispnone");
	        	}
			}
		});	
	}

	if(jsonObj.interest_received.tuples != null) {
		tupleLength = jsonObj.interest_received.tuples.length,contact_id = jsonObj.interest_received.contact_id,tracking = jsonObj.interest_received.tracking.split("responseTracking=")[1];
		$.each(jsonObj.interest_received.tuples,function(index,elem) {
			$.each(elemArray,function(index2,elem2){
				if(elem[elem2] == undefined || elem[elem2] == null){
					elem[elem2] = "";
				}
			});
			if(elem.photo.url == undefined || elem.photo.url == null) {
				elem.photo.url = "IMG_URL/images/jsms/commonImg/3_4_NoFemalePhoto.jpg";
			}
		});		
		$.each(jsonObj.interest_received.tuples,function(index,elem) {
			hrefStr = "/profile/viewprofile.php?profilechecksum="+elem.profilechecksum+"&responseTracking="+tracking+"&total_rec="+tupleLength+"&actual_offset="+(index+1)+"&contact_id="+contact_id;
			htmlStr = '<div class="mr10 dispibl ml0 posrel wid300" id="eoituple_'+index+'" index="'+index+'"><input class="proChecksum" type="hidden" value="'+elem.profilechecksum+'"><img class="srp_box2 contactLoader posabs dispnone top65" src="IMG_URL/images/jsms/commonImg/loader.gif"><div class="bg4 overXHidden" id="hideOnAction"><a id="detailedProfileRedirect" href="'+hrefStr+'"><div class="pad16 scrollhid hgt140"><div class="overXHidden fullheight"><div class="whitewid200p overflowWrap"><div class="fl"><img class="tuple_image hgtwid110" src="'+elem.photo.url+'" border="0"> </div><div class="fl pl_a" style="width:48%"><div class="f14 color7"> <div class="username textTru">'+(elem.name_of_user ? elem.name_of_user : elem.username)+'</div></div><div class="attr"><ul><li class="textTru"><span class="tuple_title">'+elem.tuple_title_field+'</span> </li><li class="textTru"><span class="tuple_age">'+elem.age+'</span> Years  <span class="tuple_height"> '+elem.height+' </span> </li><li class="textTru"><span class="tuple_caste" style="white-space: nowrap;">'+elem.caste+'</span></li><li class="textTru"><span class="tuple_mtongue">'+elem.mtongue+'</span></li>';
            if(userGender == "F") {
                htmlStr = htmlStr +'<li class="textTru"><span class="tuple_income">'+elem.income+'</span></li>';
            } else {
                htmlStr = htmlStr +'<li class="textTru"><span class="tuple_education">'+elem.education+'</span></li>';    
            }
            htmlStr = htmlStr +'</ul></div></div><div class="clr"></div></div></div></div> </a><div class="brdr8 fullwid hgt60"><div class="txtc wid49p fl eoiAcceptBtn brdr7 pad2" index="'+index+'"><input class="inputProChecksum" type="hidden" value="'+elem.profilechecksum+'"><a class="f15 color2 fontreg">Accept</a></div><div class="txtc wid49p fl f15 pad2 eoiDeclineBtn" index="'+index+'"><input class="inputProChecksum" type="hidden" value="'+elem.profilechecksum+'"><a class="f15 color2 fontlig">Decline</a></div><div class="clr"></div></div></div></div>';
			$("#awaiting_tuples").append(htmlStr);
			if(index == (tupleLength-1)){
				$("#awaiting_tuples").append('<div style="margin-right:10px; display: inline-block;margin-left:0px; display:none;position:relative;" id="loadingMorePic"><div class="bg4"><div class="row minhgt199"><div class="cell vmid txtc pad17"><i class="mainsp heart"></i><div class="color3 f14 pt5">Loading More Interests</div></div></div></div> </div>')
				for(var i=0;i<10;i++) {
	            	$("#awaiting_tuples").append('<div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>');
	            }
				if (parseInt(awaitingResponseCount)) {
	            	var slider1=$("#awaitingResponsePresent #awaiting_tuples");
	          		tupleObject = slider1.Slider(7,slider1,tupleLength,"interest_received",awaitingResponseNext);
	            	tupleObject._defaultInit();
	                bindSlider();
	                $(".contactLoader").css("left",((windowWidth/2)-$(".contactLoader").width()/2)-20+"px");
	        		$("#awaitingLoader").remove();
	        		$("#awaiting_tuples").removeClass("dispnone");
	        	}
			}
		});	
	}

		if(jsonObj.match_of_the_day.tuples != null && showMatchOfTheDay){
		tupleLength = jsonObj.match_of_the_day.tuples.length,contact_id = jsonObj.match_of_the_day.contact_id,tracking = jsonObj.match_of_the_day.tracking.split("responseTracking=")[1];
		$.each(jsonObj.match_of_the_day.tuples,function(index,elem) {
			$.each(elemArray,function(index2,elem2){
				if(elem[elem2] == undefined || elem[elem2] == null){
					elem[elem2] = "";
				}
			});
			if(elem.photo.url == undefined || elem.photo.url == null) {
				if(userGender == "M") {
					elem.photo.url = "IMG_URL/images/picture/120x120_f.png?noPhoto";
				} else if(userGender == "F") {
					elem.photo.url = "IMG_URL/images/picture/120x120_m.png?noPhoto";
				}
			}
		});
		$.each(jsonObj.match_of_the_day.tuples,function(index,elem) {
			hrefStr = "/profile/viewprofile.php?profilechecksum="+elem.profilechecksum+"&responseTracking="+tracking+"&total_rec="+tupleLength+"&actual_offset="+(index+1)+"&contact_id="+contact_id;
			htmlStr = '<div class="mr10 dispibl ml0 posrel wid300" id="matchOfTheDaytuple_'+index+'" index="'+index+'"><input class="proChecksum" type="hidden" value="'+elem.profilechecksum+'"><img class="srp_box2 contactLoader posabs dispnone top65" src="IMG_URL/images/jsms/commonImg/loader.gif"><div class="bg4 overXHidden" id="hideOnAction"><a id="detailedProfileRedirect" href="'+hrefStr+'"><div class="pad16 scrollhid hgt140"><div class="overXHidden fullheight"><div class="whitewid200p overflowWrap"><div class="fl"><img class="tuple_image hgtwid110" src="'+elem.photo.url+'" border="0"> </div><div class="fl pl_a" style="width:48%"><div class="f14 color7"> <div class="username textTru">'+(elem.name_of_user ? elem.name_of_user : elem.username)+'</div></div><div class="attr"><ul><li class="textTru"><span class="tuple_title">'+elem.tuple_title_field+'</span> </li><li class="textTru"><span class="tuple_age">'+elem.age+'</span> Years  <span class="tuple_height"> '+elem.height+' </span> </li><li class="textTru"><span class="tuple_caste whtSpaceNo">'+elem.caste+'</span></li><li class="textTru"><span class="tuple_mtongue">'+elem.mtongue+'</span></li>';
            if(userGender == "F") {
                htmlStr = htmlStr +'<li class="textTru"><span class="tuple_income">'+elem.income+'</span></li>';
            } else {
                htmlStr = htmlStr +'<li class="textTru"><span class="tuple_education">'+elem.education+'</span></li>';    
            }
            htmlStr = htmlStr +'</ul></div></div><div class="clr"></div></div></div></div> </a><div class="brdr8 fullwid hgt60"><div class="txtc fullwid fl matchOfDayBtn brdr7 pad2" index="'+index+'"><input class="inputProChecksum" type="hidden" value="'+elem.profilechecksum+'"><a class="f15 color2 fontreg">Send Interest</a></div><div class="clr"></div></div></div></div>';
			$("#matchOfDay_tuples").append(htmlStr);
			if(index == (tupleLength-1)){
				for(var i=0;i<10;i++) {
	            	$("#matchOfDay_tuples").append('<div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>');
	            }
				if (parseInt(matchOfDayCount)) {
	            	var slider3=$("#matchOfDayPresent #matchOfDay_tuples");
	           		tupleObject3 = slider3.Slider(10,slider3,tupleLength,"match_of_the_day",0);
	          		tupleObject3._defaultInit();
	                bindSlider();
	                $(".contactLoader").css("left",((windowWidth/2)-$(".contactLoader").width()/2)-20+"px");
	        		$("#matchLoader").remove();
	        		$("#match_alert_tuples").removeClass("dispnone");
	        	}
			}
		});	
	}

	
}
function jsmsMyjsReady() {
    
    var arr=["awaitingResponse","visitor","matchalert","matchOfDay"];
    if(document.getElementById("awaitingResponseCount") == null) {
        return ;
    }
	awaitingResponseCount = document.getElementById("awaitingResponseCount").value;
	visitorCount = document.getElementById("visitorCount").value;
	matchalertCount = document.getElementById("matchalertCount").value;
	matchOfDayCount = document.getElementById("matchOfDayCount").value;
        
        for (i=0;i<arr.length;i++)
	setBlock(arr[i]);
      
     
    setBrowseBand();
        
        $("#hamburger").width($(window).width());
     $(".setWidth").width($(window).width());
        
//        if (parseInt(awaitingResponseCount)) {
//            var slider1=$("#awaitingResponsePresent #awaiting_tuples");
//          tupleObject = slider1.Slider(7,slider1,parseInt(awaitingResponseCount),"interest_received",awaitingResponseNext);
//            tupleObject._defaultInit();
//        }
        
//        if (parseInt(matchalertCount)) {
//            var slider2=$("#matchalertPresent #match_alert_tuples");
//           tupleObject2 =   slider2.Slider(9,slider2,parseInt(matchalertCount),"match_alert",matchAlertNext);
//          tupleObject2._defaultInit();
//                  bindSlider();
//
//        }
//                $(".contactLoader").css("left",((windowWidth/2)-$(".contactLoader").width()/2)-20+"px");
//
//        bindSlider();

        createSlider();
        $("#jsmsProfilePic").bind('click',function() {
        	$(location).attr('href',siteUrl+"/profile/viewprofile.php?ownview=1");
        });
        var circleDim = Math.round((($(window).innerWidth()-30)/4)-20);
        $(".outerCircleDiv").each(function(){
        	$(this).height(circleDim);
        	$(this).width(circleDim);
        });
        $(".innerCircleDiv").each(function(){
        	$(this).height(circleDim-2);
        	$(this).width(circleDim-2);
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
           $("#"+blockName+"Present").css('display','block');
           $("#"+blockName+"Absent").css('display','none');
	} else {
		$("#"+blockName+"Present").css('display','none');
        $("#"+blockName+"Absent").css('display','block');
	}
}
	$(window).load(function() {
        if(typeof completionScore != "undefined")  {
		  profile_completion(completionScore);
        }
	});


function closeHam()
{
    $("#wrapper").click();
}         

	$(document).ready(function() {
    var cacheMin = 2;
    var ttl = 60000 * cacheMin;
    //Saving HTML of MYJS page on first time load along with current time stamp in session storage
    if(sessionStorage.getItem("myjsTime") == undefined ||
      new Date().getTime() - sessionStorage.getItem("myjsTime") < ttl)
    {
      sessionStorage.setItem("myjsTime",new Date().getTime());
      sessionStorage.setItem("myjsHtml",document.documentElement.outerHTML);	
    } 

                $("#hamburgerIcon").bind("click", function() {
			if($("#hamburger").length == 0){ 
                                $(".loaderSmallIcon").addClass("loaderimg").removeClass("dn");
                                $("#hamIc").hide();
                                var htmlStr = '<div id="hamburger" class="hamburgerCommon dn fullwid"><div><div id="outerHamDiv" class="fullwid outerdiv"><div id="mainHamDiv" class="wid76p" style="float:left;"><div id="newHamlist" class="hamlist hampad1"><div id="HamMenu" class="fontlig padHamburger"><div class="fl fullwid pt7"><div class="dispibl txtc  newham_wid32p"><a bind-slide=1 href="/profile/contacts_made_received.php?page=eoi&filter=R" class="dispbl white f12"> <i id="int_rec" class="int_rec newham_icons1 posrel"><div class="posabs newham_pos1 dn"><div class="bg7 disptbl newham_count txtc" ><div class="vertmid dispcell">1</div></div></div></i><div>Interests <br/> Received</div></a></div><div class="dispibl txtc newham_wid32p"><a bind-slide=1 href="/profile/contacts_made_received.php?page=accept&filter=R" class="dispbl white f12"><i id="acc_mem" class="acc_mem newham_icons1 posrel"><div class="posabs newham_pos1 dn"><div class="bg7 disptbl white f12 newham_count txtc"><div class="vertmid dispcell">2</div></div></div></i><div>All<br/>Acceptances</div></a></div><div class="dispibl txtc newham_wid32p"><a bind-slide=1 href="/search/perform?justJoinedMatches=1" class="dispbl white f12"><i id="just_join" class="just_join newham_icons1  posrel"><div class="posabs newham_pos1 dn"><div class="bg7 disptbl white f12 newham_count txtc" ><div class="vertmid dispcell"></div></div></div></i><div>Just Joined <br/> Matches</div></a></div></div><div class="clr"></div></div><div class="brdr9_ham"><div class="newham_pad1 lh25"><div id="memTop" class="white fb1 fontrobbold f15"></div><a id="memBottom" href="/profile/mem_comparison.php" bind-slide=1 class="white f18">Upgrade Membership</a></div></div><div class="brdr9_ham pt20"><ul class="fontlig"><li><a href="#" onclick="translateSite(\'http://hindi.jeevansathi.com\');" bind-slide=1 class="white" style="font-size: 19px;">हिंदी में</a></li></li><li><div id=\'appDownloadLink1\' style=\'display:none\'><a onclick="window.location.href=\'/static/appredirect?type=jsmsHamburger\';" bind-slide=1 class="white">Download App | 3MB only</a></div><!--end:listing6--><!--start:listing7--><div id=\'appleAppDownloadLink1\' style=\'display:none\'><a onclick="window.location.href=\'/static/appredirect?type=jsmsHamburger&channel=iosLayer\';" bind-slide=1 class="white">Download iOS App </a></div><!--end:listing6--></li><li><div  onclick="closeHam();"  class="white" style="font-size: 17px;">Home</div></li><li><a href="/search/topSearchBand?isMobile=Y" bind-slide=1 class="white">Search</a></li><li><a href="/search/searchByProfileId" bind-slide=1 class="white">Search by Profile ID</a></li><li><a href="/search/MobSaveSearch" bind-slide=1 id="SAVE_SEARCH" class="white">Saved Searches <span class="dispibl padl10 opa70 f12 dn"></span></a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">My Matches</li><li><a href="/search/perform?justJoinedMatches=1" bind-slide=1 id="JUST_JOINED_COUNT" class="white">Just Joined Matches <span class="dispibl padl10 opa70 f12 dn"></span></a></li><li><a href="/search/verifiedMatches" bind-slide=1 class="white">Verified Matches</a></li><li><a href="/profile/contacts_made_received.php?page=matches&filter=R" bind-slide=1 id="MATCHALERT" class="white">Daily Recommendations <span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/search/perform?partnermatches=1" bind-slide=1 class="white">Desired Partner Matches</a></li><li><a href="/search/perform?kundlialerts=1" bind-slide=1 class="white">Kundli Matches <span class ="dispibl padl10 f12 white opa50">New</span></a></li><li><a href="/search/perform?twowaymatch=1" bind-slide=1 class="white">Mutual Matches</a></li><li><a href="/search/perform?reverseDpp=1" bind-slide=1 class="white">Members Looking For Me</a></li><li><a href="/profile/contacts_made_received.php?page=visitors&filter=R&matchedOrAll=A" bind-slide=1 id="VISITOR_ALERT" class="white">Profile Visitors <span class="dispibl padl10 opa70 f12 dn"></span></a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">My Contacts</li><li><a href="/profile/contacts_made_received.php?page=eoi&filter=R" bind-slide=1 id="AWAITING_RESPONSE" class="white">Interests Received <span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/profile/contacts_made_received.php?page=filtered_eoi&filter=R" bind-slide=1 id="FILTERED" class="white">Filtered Interests <span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/profile/contacts_made_received.php?page=accept&filter=R" bind-slide=1 id="ACCEPTED_MEMBERS" class="white">All Acceptances<span class="dispibl padl10 opa70 f12 dn"></span> </a></li><li><a href="/profile/contacts_made_received.php?page=phonebook_contacts_viewed&filter=M" bind-slide=1 class="white">Phonebook</a></li><li><a href="/profile/contacts_made_received.php?page=contact_viewers" bind-slide=1 class="white">Who Viewed My Contacts</a></li><li><a href="/profile/contacts_made_received.php?page=favorite" bind-slide=1 id="BOOKMARK" class="white">Shortlisted Profiles <span class="dispibl padl10 opa70 f12 dn"></span></a></li><li><a href="/profile/contacts_made_received.php?page=messages" bind-slide=1 id="MESSAGE_NEW" class="white">Messages<span class="dispibl padl10 opa70 f12 dn"></span></a></li><li><a href="/profile/contacts_made_received.php?page=decline&filter=M" bind-slide=1 class="white">Declined Members</a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li class="white fb1 ham_opa fontrobbold">More</li><li><a href="/help/index" bind-slide=1 class="white">Help</a></li><li><a href="/contactus/index" bind-slide=1 class="white">Contact Us</a></li><li><a href="/static/settings" bind-slide=1 class="white">Settings</a></li></ul></div><div class="brdr9_ham pt20"><ul class="fontlig"><li><a href="" onclick="window.location.href = \'tel:18004196299\';" title="call" alt="call" class="white">1800-419-6299 <span class="dispibl padl10 opa70 f12">Toll Free</span></a></li></ul></div></div></div><div id="hamProfile" class="dn posfix ham_pos3"><a bind-slide=1 href="/profile/viewprofile.php?ownview=1" class="dispbl fontlig f12 ham_color2"><i class="icons1 posabs ham_icon3 ham_pos4"></i><div class="pt10 txtc"><img id="profileImg" src="IMG_URL/images/jsms/commonImg/3_4_NoFemalePhoto.jpg" style="height:50px; width:50px;" class="ham_imgbrdr brdr18" /></div><div class="lh25">Edit Profile</div></a></div></div></div></div>';
                                $("#perspective").append(htmlStr);
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
	if(response.VISITOR_ALERT != 0){
		$("#VISITOR_ALERT span").html(response.VISITORS_ALL).removeClass("dn");
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
		if (_parent.page >= 0) {
                        if (_parent._isRequested) return ;
                        ++_parent.page;
			loadnew(_parent.page,_parent);
                        
		}
	};
      



/*
function add_divs(width, length, index) {
	var parent = document.getElementById("eoituples");


	var node;

	for (i = 0; i < length; i++) {
		node = document.createElement("div");

		parent.appendChild(node);
		node.style.display = "inline";
		node.style.width = width + "px";
		node.setAttribute("class", "fl");
		node.style.marginRight = "10px";


		node.style.visibility = "hidden";

		node.style.visibility = "visible";

	}

}
*/
