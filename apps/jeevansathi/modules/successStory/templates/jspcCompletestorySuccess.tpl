<header>
  <div class="sscover2">
    <div class="container mainwid pt35 pb30">
      <!--start:top horizontal bar-->
      ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
      <!--end:top horizontal bar-->
    </div>
  </div>
</header>
<div class="container mainwid fontlig">
    <!--start:years tabbing-->
    <div class="bg-white ssbrd1 fullwid pos-rel scrollhid">
        <!--start:next button-->
        <div id="nextTopYearsSlider" class="pos-abs bg-white txtc sspos2 ssdim1 z2 cursp">
            <i class="sprite2 sstabr aligncen"></i>
        </div>
        <!--end:next button-->
        <!--start:previous button-->
        <div id="prevTopYearsSlider" class="pos-abs bg-white txtc sspos3 ssdim1 z2 cursp">
            <i class="sprite2 sstabl aligncen"></i>
        </div>
        <div id="yearsSliderFrame" class="pos_rel" style="left:35px !important;">
            <!--end:previous button-->
            <ul class="hor_list clearfix fontreg f14 color11 year pos-rel z1">
                ~foreach from=$showYear key=k item=v`
                    ~if $v eq $year`
                        ~assign var=activeYearNode value=$k`
                    ~/if`
                    <li class="cursp"><a class="disp_b color11" href="/successStory/story?year=~$v`#yearsSliderFrame"><p>~$v`</p></a></li>
                ~/foreach`
            </ul>
        </div>
    </div>
    <!--end:tab 1 part-->
</div>
<!--start:contetn section-->
<div class="pos-rel" style="padding:90px 0 50px 0">
    <!--start:prv-->
    ~if $currentPosition neq 1`
    <a id="prevPageLink" href="/successStory/completestory?prev_page=1&year=~$year`&sid=~$sid`">
        <div class="pointerClass pos-abs sspos4 cursp z5">
            <div class="disp-tbl ssdim4 ssbg1 txtc">
                <div class="disp-cell vmid"><i class="sprite2 ssic2"></i></div>
            </div>
        </div>
    </a>
    ~else`
    <a id="prevPageLink" href="#" style="cursor:inherit;">
        <div class="pointerClass pos-abs sspos4 z5">
            <div class="disp-tbl ssdim4 ssbg1 txtc">
                <div class="disp-cell vmid"><i class="sprite2 ssic2"></i></div>
            </div>
        </div>
    </a>
    ~/if`
    <!--end:prv-->
    <!--start:next-->
    ~if $currentPosition neq $totalStoryCount`
    <a id="nextPageLink" href="/successStory/completestory?next_page=1&year=~$year`&sid=~$sid`">
        <div class="pointerClass pos-abs sspos5 cursp z5">
            <div class="disp-tbl ssdim4 ssbg1 txtc">
                <div class="disp-cell vmid"><i class="sprite2 ssic3"></i></div>
            </div>
        </div>
    </a>
    ~else`
    <a id="nextPageLink" href="#" style="cursor:inherit;">
        <div class="pointerClass pos-abs sspos5 z5">
            <div class="disp-tbl ssdim4 ssbg1 txtc">
                <div class="disp-cell vmid"><i class="sprite2 ssic3"></i></div>
            </div>
        </div>
    </a>
    ~/if`
    <!--end:next-->
    <!--start:text-->
    <div class="container mainwid">

        <div style="padding:0 15px 0 60px" class="clearfix">
            <!--start:photo-->
            <div class="image_cover fl" style="overflow: none;" data-imgLiquid-fill="true" data-imgLiquid-horizontalAlign="top" data-imgLiquid-verticalAlign="top">
                <img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($pic)`" class="vtop ssdim3"/>
            </div>
            <!--end:photo-->
            <!--start:text-->
            <div id="storyContent" class="fr txtl" style="width:480px;">
                <!--start:tile-->
                <div id="storyTitle" class="clearfix fullwid pb15 pos-rel disp-none">
                    <div id="storyName" class="fl colr5 f20 fontlig" style="word-wrap: break-word; width: 385px;">~$name1` ~if $name1 && $name2` weds ~/if` ~$name2`</div>
                    <div class="fr">
                        <ul class="hor_list clearfix color11 f12 fontlig pt6 opa60">
                            <li id="storyCounter" class="pr5 ssbrd4">~$currentPosition` of ~$totalStoryCount`</li>
                            <li class="pl5"><a id="seeAllLink" href="/successStory/story?year=~$year`#yearsSliderFrame" class="color11">See All</a></li>
                        </ul>
                    </div>
                    <div class="pos-abs ssline2" ></div>
                </div>
                <!--end:tile-->
                <!--start:descp-->
                <div id="storyDetails" style="padding:20px 0 26px 0" class="pos-rel disp-none">
                    <div class="content mCustomScrollbar">
                        <div class="color11 fontlig f12 lh20">
                            ~$story|decodevar`
                        </div>
                    </div>
                    <div class="pos-abs ssline2" ></div>
                </div>
                <!--end:descp-->
                <!--start:date-->
                <!-- <p class="f12 color11 pt12"><span class="fontreg  disp_ib">Wedding Date </span><span class="disp_ib pl20 fontlig">30th  November  2015</span> -->
                <!--end:date-->
            </div>
            <!--end:text-->
        </div>
    </div>
    <!--end:text-->
</div>
<!--end:contetn section-->
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->
<script type="text/javascript">
    $(window).load(function(){
        $(".image_cover img").each(function(){
            if($(this).attr('src') == ''){
                $(this).attr('src','/images/jspc/success_story/successCouple.png');
            }
        });
    });
	$(document).ready(function(){
		$("#topNavigationBar").css('top',0);
        $("#yearsSliderFrame").width($("#yearsSliderFrame").width()-70);

        $("#yearsSliderFrame").css('visibility','hidden');
        
        var $frame  = $('#yearsSliderFrame');
        var $slidee = $frame.children('ul').eq(0);
        var $wrap   = $frame.parent();
        // Call Sly on frame
        var yearPosition = "~$activeYearNode`";
        var sly = new Sly('#yearsSliderFrame',{
            horizontal: 1,
            itemNav: 'basic',
            smart: 1,
            activateOn: 'click',
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            startAt: parseInt(yearPosition),
            scrollBy: 1,
            activatePageOn: 'click',
            speed: 300,
            elasticBounds: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,
            // Buttons
            prevPage: $wrap.find('#prevTopYearsSlider'),
            nextPage: $wrap.find('#nextTopYearsSlider')
        }).init();

        sly.on('active', function(){
            var newHref = $("#yearsSliderFrame ul li.active a").attr('href');
            window.location.href = newHref; 
        });
        $('div.image-fit').imageFitCover();
        $('div.image_cover').fadeIn(750,function(){
            $("#storyTitle").fadeIn(750,function(){
                $("#storyDetails").fadeIn(750,function(){

                });
            });
        });

        $("#seeAllLink").click(function(e){
            e.preventDefault();
            var currentPos = "~$currentPosition`";
            var rem = parseFloat(currentPos/9);
            if(rem % 1 === 0){
                window.location.href = "/successStory/story?year=~$year`&page="+(rem)+"#yearsSliderFrame";
            } else {
                rem = Math.ceil(rem);
                window.location.href = "/successStory/story?year=~$year`&page="+(rem)+"#yearsSliderFrame";
            }
        });

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        //if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){
            $(".image_cover").addClass('imgLiquidFill imgLiquid');
            $(".image_cover").css('width',400);
            $(".image_cover").css('height',400);
            $(".imgLiquidFill").imgLiquid();
        //}

        $("#storyDetails .content div").mCustomScrollbar({});

        $('#yearsSliderFrame')
            .delay(300)
            .queue(function (next) { 
                $(this).css('visibility', 'visible'); 
            next(); 
        });

        $("#prevPageLink,#nextPageLink").click(function(e){
            e.preventDefault();
            var id = $(this).attr('id');
            var url_addition = "&requestType=ajax#yearsSliderFrame"
            url = $(this).attr('href') + url_addition;
            if(url.indexOf("page") != -1) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        var cp = parseInt(response.currentPosition);
                        var tc = parseInt(response.totalStoryCount);
                        if(id == 'prevPageLink'){
                            window.history.pushState('prevPage', 'Success Stories - Matrimonial Success Stories - Matrimony Testimonials', '/successStory/completestory?prev_page=1&year=~$year`&sid='+response.sid);
                        } else if(id == 'nextPageLink') {
                            window.history.pushState('nextPage', 'Success Stories - Matrimonial Success Stories - Matrimony Testimonials', '/successStory/completestory?next_page=1&year=~$year`&sid='+response.sid);
                        }
                        $("#prevPageLink").attr('href','/successStory/completestory?prev_page=1&year=~$year`&sid='+response.sid+url_addition);
                        $("#nextPageLink").attr('href','/successStory/completestory?next_page=1&year=~$year`&sid='+response.sid+url_addition);
                        $("#storyTitle,#storyDetails,div.image_cover").hide();
                        $(".mCSB_container").html(response.story).addClass('f12 fontlig lh20 color11 txtl');
                        $("#storyDetails .content").mCustomScrollbar("update");
                        $("#storyName").html(response.name1 + " weds " + response.name2);
                        if(cp == 1){
                            $("#prevPageLink").attr("href",'#').css('cursor','inherit');
                            $("#prevPageLink div.pointerClass").removeClass('cursp').css('cursor','inherit');
                        } else if(cp == tc){
                            $("#nextPageLink").attr("href",'#').css('cursor','inherit');
                            $("#nextPageLink div.pointerClass").removeClass('cursp').css('cursor','inherit');
                        } else {
                            $("#prevPageLink,#nextPageLink").css('cursor','pointer');
                            $("#prevPageLink div.pointerClass,#nextPageLink div.pointerClass").addClass('cursp').css('cursor','pointer');
                        }
                        $("#storyCounter").html(cp + " of " + tc);
                        if(response.pic){
                            $('div.image_cover').html("<img class='vtop ssdim3' src='"+response.pic+"'>");
                        } else {
                            $('div.image_cover').html("<img class='vtop ssdim3' src='/images/jspc/success_story/successCouple.png'>");
                        }
                        $("div.image_cover").fadeIn(500,function(){
                            $("#storyTitle").fadeIn(500,function(){
                                $("#storyDetails").fadeIn(500,function(){});
                            });
                        });
                        $(".image_cover").addClass('imgLiquidFill imgLiquid');
                        $(".image_cover").css('width',400);
                        $(".image_cover").css('height',400);
                        $(".imgLiquidFill").imgLiquid();
                    }
                });
            }
        })
    });
</script>