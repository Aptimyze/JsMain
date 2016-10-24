var awaitingResponseCount, visitorCount, matchalertCount, limit = 0,tupleObject,tupleObject2,
	index = 0,
            pc_temp1 = 0,
	pc_temp2 = 0,
	t1 = null,
	profileCompletionCount = 0,
	start = 0,
	m,windowWidth=$(window).width();

    var userGender="~$apiData.gender`",siteUrl="~$SITE_URL`";
	var tupleObject,tupleObject2, matchAlertNext=0, full_loaded = 0;



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


function jsmsMyjsReady() {
    
    var arr=["awaitingResponse","visitor","matchalert"];
	awaitingResponseCount = document.getElementById("awaitingResponseCount").value;
	visitorCount = document.getElementById("visitorCount").value;
	matchalertCount = document.getElementById("matchalertCount").value;
        
        for (i=0;i<arr.length;i++)
	setBlock(arr[i]);
       
    
    setBrowseBand();
        
        $("#hamburger").width($(window).width());
     $(".setWidth").width($(window).width());
        
        if (parseInt(awaitingResponseCount)) {
            var slider1=$("#awaitingResponsePresent #awaiting_tuples");
          tupleObject = slider1.Slider(7,slider1,parseInt(awaitingResponseCount),"interest_received",awaitingResponseNext);
            tupleObject._defaultInit();
        }
        
        if (parseInt(matchalertCount)) {
            var slider2=$("#matchalertPresent #match_alert_tuples");
           tupleObject2 =   slider2.Slider(9,slider2,parseInt(matchalertCount),"match_alert",matchAlertNext);
          tupleObject2._defaultInit();
                  bindSlider();

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
		profile_completion(completionScore);

	});

        

	$(document).ready(function() {
		jsmsMyjsReady();
                $(".tuple_image").each(function(index, element) {
                    var dSource=$(this).attr("data-src");   
                    if(dSource) {
                        $(this).attr("src",dSource);
                }
                   });
                $(".contactLoader").each(function(){
                $(this).attr("src","/images/jsms/commonImg/loader.gif");
                });   
		var d = new Date();
		var hrefVal = $("#calltopSearch").attr("href")+"&stime="+d.getTime();
		$("#calltopSearch").attr("href",hrefVal);
                $(document).on('contextmenu', 'img',function(e) {
                return false;
                });

                
    });


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
