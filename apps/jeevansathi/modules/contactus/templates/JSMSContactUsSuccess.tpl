<div class="bg4"> 
    <!--start:top-->
    <div class="bg1 txtc pad15 topheadM">
        <div class="posrel">
            <div class="f20 white fontthin" id="test">Contact Us</div>
            <a href="/profile/mainmenu.php"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
        </div>
    </div>
    <!--end:top--> 
  
    <!--
    <!--start:slider
		<div id="ed_slider" class="swipe vh">
			<div id ="sw" class="bxslider" >
				<div id="sliderName" class="slidechild" >
					<!--start:div
					<div id ="subHeadTab" class="fullwid editAlbumBG prz" style="z-index:105;">
                        <div id ="innerSubHeadTab" class="pad5">
                            <div id="leftTabName"class="fl wid30p color5 fontlig f12 pt2 opa70">LeftTabValue</div>
                            <div id="MainTabName" class="fl wid40p txtc color5 fontlig f14 textTru" maintab="1">MainTabValue
                                <span class="arow4"></span>
                            </div>							 
							<div id="RightTabName"class="fl wid30p color5 txtr fontlig f12 pt2 opa70"></div>
							<div class="clr"></div>
						</div>
					</div>
					<!--end:div 
					<div id="EditSection" class="fullwid oa">
                        <div class="fullwid  brdr1 bwhite" slideOverLayer="OVERLAYID">
                            <div class="pad1">
                                <div class="pad2">
                                    <div class="fl wid94p wwrap">
                                        <div id="EditFieldName" class="color3 f14 fontlig"></div>
                                        <div id="EditFieldLabelValue" class="color4 f12 pt10 fontlig"></div>
                                    </div>
                                    <div class="fr wid4p pt8"> <i class="mainsp arow1"></i>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
					<!--end:div
				</div>
			</div>
		</div>
    <!--end:silder
 
    -->
    
    <div id="pageDiv" class="parentFrame">
        
    </div>
</div>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
    function setPrevNextEvents(sly){
        $($('ul .active')).find(".prev").click(function(){
//          var ind = $('ul .active').index() - 1;
            var ind = sly.getIndex($('ul .active'));
            console.log(ind);
//          ind = ind - 1;
            sly.prev();
            sly.updateRelatives();
        });
        $($('ul .active')).find(".next").click(function(){
//          var ind = $('ul .active').index() + 1;
            var ind = sly.getIndex($('ul .active'));
            console.log(ind);
//          ind = ind + 1;
            sly.next();
            sly.updateRelatives();
        });
    }
    function setHeight(){
        var sch = $('li.active .stateContentContainer').height();
        var wh = $(window).height();
        if(sch <= wh){
            $("#pageDiv").height(wh);
            $('li.active .stateContentContainer').css('height',wh+30);
        } else {
            $("#pageDiv").height(sch+80);
            $('li.active .stateContentContainer').css('height',sch+80);
        }
    }
    function generateHTMLContent(resultData){
        var cityUnsplit;
        var noOfChild = 0;
        var previousDiv;
        var previousState;
        var startingPosition = 0;
        $('#pageDiv').append("<ul id='pageUl' class='margPad0'></ul>")
        $.each(resultData.details, function(key,value){
            noOfChild ++;
            var headerUnsplit = value.header.split(" ").join("_");
            $('#pageUl').append("<li id='pageFrame"+headerUnsplit+"' class='innerFrame noDeco'></li>");
            var pageFrame = "#pageFrame"+headerUnsplit;
            $(pageFrame).append("<div id='topHeading"+headerUnsplit+"' class='fullwid editAlbumBG prz sliderView'></div>");
            var topHeading = "#topHeading"+headerUnsplit;
            $(topHeading).append("<div id='topHeadingAlign"+headerUnsplit+"' class='setp1'></div>");
            var topHeadingAlign = "#topHeadingAlign"+headerUnsplit;
            $(topHeadingAlign).append("<div id='topHeadingLeftText"+headerUnsplit+"' class='fl wid30p color5 fontlig f12 pt2 opa70 prev'></div>");
            $(topHeadingAlign).append("<div id='topHeadingText"+headerUnsplit+"' class='fl wid40p txtc color5 fontlig f14 textTru'>"+value.header+"</div>");
            $(topHeadingAlign).append("<div id='topHeadingRightText"+headerUnsplit+"' class='fl wid30p color5 txtr fontlig f12 pt2 opa70 next'></div>");
            $(topHeadingAlign).append("<div id='topHeadingClr"+headerUnsplit+"' class='clr'></div>");
            $(pageFrame).append("<div id='container"+headerUnsplit+"' class='pad19' ></div>");
            var newID = 'stateContainer_'+headerUnsplit;
            var container = "#container"+headerUnsplit;
            $(container).append("<div id='"+newID+"' class='stateContentContainer pad2'></div>");
            $(container).height($(window).height());
            var state = $('#'+newID);
            var setPadding = 0;
            if(noOfChild > 1)
            {
                $("#topHeadingLeftText"+headerUnsplit).html(previousState);
                $("#topHeadingRightText"+previousDiv).html(value.header+"|");
            }
            previousDiv = headerUnsplit;
            previousState = value.header;
            
            $.each(value.office, function(k,v){
                setPadding++;
                cityUnsplit = v.city.split(' ').join('_');
                var contactCenterId = "contactCenter"+cityUnsplit;
                if(v.city != 'Noida - Head office'){
//                if(setPadding == 1)
                    $(state).append("<div id='"+contactCenterId+"' class='pad2'></div>");
//                else
//                    $(state).append("<div id='"+contactCenterId+"'></div>");
                
                    $("#"+contactCenterId).append("<div id='contactCity"+cityUnsplit+"' class='color2 fontlig pb20'>"+v.city+"</div>");
                    $("#"+contactCenterId).append("<div id='contactPerson"+cityUnsplit+"' class='clearfix pb10'></div>");
                    var contactPerson = "contactPerson"+cityUnsplit;
                    $("#"+contactPerson).append("<div id='contactPersonIcon"+cityUnsplit+"' class='fl wid10p'><i class='mainsp set_icons1'></i></div>");
                    $("#"+contactPerson).append("<div id='contactPersonName"+cityUnsplit+"' class='fl wid90p color3'>"+v.contact_person+"</div>");
                    $("#"+contactCenterId).append("<div id='contactAddress"+cityUnsplit+"' class='clearfix pb10'></div>");
                    var contactAddress = "contactAddress"+cityUnsplit;
                    $("#"+contactAddress).append("<div id='contactAddressIcon"+cityUnsplit+"' class='fl wid10p'><i class='mainsp set_icons2'></i></div>");
                    $("#"+contactAddress).append("<div id='contactAddressDetail"+cityUnsplit+"' class='fl wid90p color3'>"+v.address+"</div>");
                    $("#"+contactCenterId).append("<div id='contactNumber"+cityUnsplit+"' class='clearfix'></div>");
                    var contactNumber = "contactNumber"+cityUnsplit;
                    $("#"+contactNumber).append("<div id='contactNumberIcon"+cityUnsplit+"' class='fl wid10p'><i class='mainsp set_icons3'></i></div>");
                    $("#"+contactNumber).append("<div id='contactNumberDetail"+cityUnsplit+"' class='fl wid90p color3'>"+v.phone.join(', ')+"</div>");
                    if(v.city_id == "~$userCity`"){
                        startingPosition = noOfChild-1;
                    }
                }
            });
//          $(state).css('height',$(state).height());
        });
        
        var ww = $(window).width();
//      $(".innerFrame").height(wh-hh);
        $(".innerFrame").width(ww);
//      $(".parentFrame").width(ww*noOfChild);
//      $(".contactHead").width(ww);
//      $("#pageDiv").css('margin-top','48px');
        var $frame = $("#pageDiv");
        var $slidee = $frame.children('ul').eq(startingPosition);
        var $wrap =  $frame.parent();
        var sly = new Sly('#pageDiv',{
            horizontal: 1,
            itemNav: 'forceCentered',
            smart: 1,
            activateMiddle: 1,
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing:0,
            startAt: startingPosition,
            scrollBy: 1,
            elasticBounds: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,          
            speed: 50,
            prev: $($('ul .active')).find(".prev"),
            next: $($('ul .active')).find(".next"),
            prevPage: $($('ul .active')).find(".prev"),
            nextPage: $($('ul .active')).find(".next"),
        }).init();
        setHeight();
        setPrevNextEvents(sly);
        sly.on('active', function(e){            
            setHeight();
            setPrevNextEvents(sly);
		});
    }
    
    $(document).ready(function(){
        var resultData;
        $.ajax({
            url: "/api/v3/contactus/info",
            success: function(data){
                generateHTMLContent(data);
            }
        });
    });
</script>