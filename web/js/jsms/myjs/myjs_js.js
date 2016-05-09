
var awaitingResponseCount, visitorCount, matchalertCount, limit = 0,tupleObject,tupleObject2,
	index = 0,
            pc_temp1 = 0,
	pc_temp2 = 0,
	t1 = null,
	profileCompletionCount = 0,
	start = 0,
	m,windowWidth=$(window).width();



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
	$(".pie2").css("-o-transform", "rotate(" + pc_temp1 + "deg)");
	$(".pie2").css("-moz-transform", "rotate(" + pc_temp1 + "deg)");
	$(".pie2").css("-webkit-transform", "rotate(" + pc_temp1 + "deg)");
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
	$(".pie1").css("-o-transform", "rotate(" + pc_temp2 + "deg)");
	$(".pie1").css("-moz-transform", "rotate(" + pc_temp2 + "deg)");
	$(".pie1").css("-webkit-transform", "rotate(" + pc_temp2 + "deg)");
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



/*

(function($) {
	$.fn.Slider = function(options) {
		var windowWidth = $(window).width();
		var el = $(this);
                var tuple_ratio=85;
		var childElement = el.children();
		var transformX_corr = ((tuple_ratio * 3-100)*windowWidth)/200  + 20;


		var start_scroll = 0,
			scroll_timer, scroll_flag = 0;
		var transformX = (tuple_ratio * windowWidth) / 100 + 10;

		//variable introduced to control transforming so that only eighty percent of total width is covered by the current photo margin is also given concern;
		var init = function() {

			AlterChildrenCss();

		};

		var position = function(curr_scroll) {
			var x = transformX_corr,
				pos, current_index = 1;
			if (curr_scroll <= x)
				if ((x - curr_scroll) > curr_scroll) pos = 0;
				else pos = x;
			else {
				for (;;) {

					if (curr_scroll <= x) break;
					x += transformX;
                                            current_index += 1;
				}
				if (x > (curr_scroll + transformX / 2)) pos = x - transformX;
				else pos = x;
			}
			if (current_index >= index) onnewtuples();
			el.animate({
				scrollLeft: pos + "px"
			}, 300, function() {
				scroll_flag = 0;
			});
			// el.css('-webkit-transition-duration', 1 + 's');
			//	var propValue = 'translate3d(-' + pos + 'px, 0, 0)';
			//el.css('-webkit-transform', propValue);
		};

		var check_scroll = function() {
			var current_scroll = el.scrollLeft();
			if (current_scroll == start_scroll) {
				clearInterval(scroll_timer);
				position(current_scroll);
			} else start_scroll = current_scroll;

		};

		var on_scroll = function() {

			if (scroll_flag == 0) {
				scroll_flag = 1;
				start_scroll = el.scrollLeft();
				scroll_timer = setInterval(check_scroll, 100);

			}

		};


		el.scroll(on_scroll);

		var AlterChildrenCss = function() {
			$.each(childElement, function(index, element) {
				$(element).css('width', (windowWidth * tuple_ratio) / 100 + "px");
			});

		}

		var init_obj = {
			load: init
		};

		return init_obj;

	}
})(jQuery);
*/
