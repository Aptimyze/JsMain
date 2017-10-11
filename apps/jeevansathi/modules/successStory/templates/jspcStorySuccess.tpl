<header>
    <div class="sscover2">
        <div class="container mainwid pt35 pb30">
            <!--start:top horizontal bar-->
            ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
            <!--end:top horizontal bar-->
            <!--start:text-->
            <div class="clearfix ssp3">
                <div class="fr wid500 fontlig">
                    <p class="f36 color5 ssp2">More than ~$totalStories` Success Stories and counting</p>
                    <p class="color11 pt40">As our numerous success stories prove, marriages are really made at Jeevansathi.com. Here's wishing all our members who found their ideal partner here a lifetime of happiness! If you found your dream match through Jeevansathi.com, we would like to hear your success story too. So, just send in your wedding/ engagement
                    photograph and it will be exclusively featured in our "success
                    stories".</p>
                    <div class="bg_pink lh40 txtc wid230 mt30 hoverPink" style="position: relative;overflow: hidden;"><a class="colrw f14 pinkRipple" href="/successStory/layer">Send us your success story</a></div>
                </div>
            </div>
            <!--end:text-->
        </div>
    </div>
</header>
<div class="container mainwid fontlig">
    <!--start:years tabbing-->
    <div id="yearsSliderContainer" class="bg-white ssbrd1 fullwid pos-rel scrollhid sspos1">
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
                    <li class="cursp"><a class="disp_b color11" href="/successStory/story?year=~$v`"><p>~$v`</p></a></li>
                ~/foreach`
            </ul>
        </div>
    </div>
    <!--endt:years tabbing-->
    <div id="successStoryListings">
        <div>
            <!--start:title-->
            <div class="ssbrd3">
                <p class="color2 pb10 f18 fontthin">Jeevansathi Success Stories - Year ~$year` </p>
            </div>
            <!--end:title-->
            <!--start: listing-->
            <ul class="sslist1 hor_list clearfix">
            ~if $storyPToShow`
                ~foreach from=$storyPToShow key=k item=story`
                <li class="disp-none cursp storyCard">
                    <div class="fullwid bg-white pos-rel">
                        <div class="image_cover ssdim2" style="overflow: hidden" data-imgLiquid-fill="true" data-imgLiquid-horizontalAlign="top" data-imgLiquid-verticalAlign="top">
                            <div class='blur_img'>
                                <img class="vtop ssdim2" src="~PictureFunctions::getCloudOrApplicationCompleteUrl($story.MAIN_PIC_URL)`" style="object-fit: cover;">
                            </div>
                            <img class="vtop ssdim2 pos-rel" src="~PictureFunctions::getCloudOrApplicationCompleteUrl($story.MAIN_PIC_URL)`" style="object-fit: contain;top:-310px;">
                        </div>
                        <div class="txtc f20 color5 fontreg ssp4 pos-rel" style="word-wrap: break-word">
                            ~if strlen($story.combinedName) > 28`
                                <p class="disp_ib">~substr($story.combinedName,0,28)`...</p>
                            ~else`
                                <p class="disp_ib">~$story.NAME1`</p>
                                <p class="disp_ib"><i class="vicons ssic1"></i></p>
                                <p class="disp_ib">~$story.NAME2`</p>
                            ~/if`
                            <div style="background:url(~sfConfig::get('app_site_url')`/images/jspc/success_story/line1.png) no-repeat; width:309px; height:1px;bottom:0" class="pos-abs"></div>
                        </div>
                        <div class="ssp5" style="word-wrap: break-word;height:114px;">
                            <div class="color11 f13 txtj">~substr($story.STORY,0,160)`~if strlen($story.STORY) > 160`...~/if`</div>
                            <p class="f13 fontlig txtr pt5"><a class="readMoreLink sscolor2" href="~sfConfig::get("app_site_url")`/successStory/completestory?year=~$year`&sid=~$story.SID`">Read More</a></p>
                        </div>
                    </div>
                </li>
                ~/foreach`
            ~/if`
            ~if $storyToShow`
                ~foreach from=$storyToShow key=k item=story`
                <li class="disp-none cursp storyCard">
                    <div class="fullwid bg-white pos-rel">
                        <div class="image_cover ssdim2" style="overflow: hidden" data-imgLiquid-fill="true" data-imgLiquid-horizontalAlign="top" data-imgLiquid-verticalAlign="top">
                            <div class='blur_img'>
                                <img class="vtop ssdim2" src="~PictureFunctions::getCloudOrApplicationCompleteUrl($story.MAIN_PIC_URL)`" style="object-fit: cover;">
                            </div>
                            <img class="vtop ssdim2 pos-rel" src="~PictureFunctions::getCloudOrApplicationCompleteUrl($story.MAIN_PIC_URL)`" style="object-fit: contain;top:-310px;">
                        </div>
                        <div class="txtc f20 color5 fontreg ssp4 pos-rel" style="word-wrap: break-word">
                            ~if strlen($story.NAME1)+strlen($story.NAME2) > 30`
                            ~/if`
                            <div style="background:url(~sfConfig::get('app_site_url')`/images/jspc/success_story/line1.png) no-repeat; width:309px; height:1px;bottom:0" class="pos-abs"></div>
                        </div>
                        <div class="ssp5">
                            <div class="color11 f13 txtj">~substr($story.STORY,0,160)`~if strlen($story.STORY) > 160`...~/if`</div>
                            <p class="f13 fontlig txtr pt5"><a class="readMoreLink sscolor2" href="~sfConfig::get("app_site_url")`/successStory/completestory?year=~$year`&sid=~$story.SID`">Read More</a></p>
                        </div>
                    </div>
                </li>
                ~/foreach`
            ~/if`
            </ul>
            <!--end: listing-->
            <!--start:pagination-->
            <div class="clearfix pt20 pb27">
                <div class="fr">
                    <div class="clearfix">
                        <div class="fl pr10"><i id="prevBottomPageSlider" class="cursp sprite2 ssprev"></i></div>
                        <div id="pagesSliderFrame" class="fl pt7">
                            <ul class="clearfix hor_list pagination">
                            ~foreach $totalPages as $val`
                                ~if $val eq $page`
                                    ~assign var=activePageNode value=$val-1`
                                ~/if`
                                <li class="cursp"><a class="disp_b colrb" href="/successStory/story?year=~$year`&page=~$val`">~$val`</a></li>
                            ~/foreach`
                            </ul>
                        </div>
                        <div class="fl pl20"><i id="nextBottomPageSlider" class="cursp sprite2 ssnext"></i></div>
                    </div>
                </div>
            </div>
            <!--end:pagination-->
        </div>
    </div>
</div>
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
            activateMiddle: false,
            smart: 0,
            activateOn: 'click',
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            startAt: parseInt(yearPosition),
            scrollBy: 1,
            activatePageOn: 'click',
            speed: 0,
            elasticBounds: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,
            // Buttons
            prevPage: $wrap.find('#prevTopYearsSlider'),
            nextPage: $wrap.find('#nextTopYearsSlider')
        }).init();

        var referer = "~$referer`";
        var curLoc = window.location.href;
        if(curLoc.indexOf('year') != -1){
            if(referer.indexOf('year') == -1){
                $('html,body').animate({scrollTop: $("#yearsSliderContainer").offset().top},1000);
            } else {
                $('html,body').animate({scrollTop: $("#yearsSliderContainer").offset().top},1);
            }
        }

        sly.on('active', function(){
            var newHref = $("#yearsSliderFrame ul li.active a").attr('href');
            window.location.href = newHref;
        });

        $("#yearsSliderFrame ul li:first p").css('border-left','1px solid #c6c6c6');

        var $frame2  = $('#pagesSliderFrame');
        var $slidee2 = $frame2.children('ul').eq(0);
        var $wrap2   = $frame2.parent();
        // Call Sly on frame
        var pagePosition = "~$activePageNode`";
        var sly2 = new Sly('#pagesSliderFrame',{
            horizontal: 1,
            itemNav: 'basic',
            smart: 1,
            activateOn: 'click',
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            startAt: parseInt(pagePosition),
            scrollBy: 1,
            activatePageOn: 'click',
            speed: 300,
            elasticBounds: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,
            // Buttons
            prev: $wrap2.find('#prevBottomPageSlider'),
            next: $wrap2.find('#nextBottomPageSlider')
        }).init();

        var ulWid = $("#pagesSliderFrame ul").width();
        var mainContWid = $("#pagesSliderFrame").width();
        if((ulWid+10) > 193){
            $("#pagesSliderFrame").width(193);
        } else {
            $("#pagesSliderFrame").width(ulWid+10);
        }
        $("#pagesSliderFrame ul").width(ulWid+10);

        sly2.on('active', function(){
            var newHref = $("#pagesSliderFrame ul li.active a").attr('href');
            $("#pagesSliderFrame ul li.active").addClass("fontlig");
            window.location.href = newHref;
        });

        $('div.image-fit').imageFitCover();
        $('#successStoryListings ul li').fadeIn(1000, function(){});
        $('.blur_img').foggy({
           blurRadius:13,          // In pixels.
           opacity: 0.7,           // Falls back to a filter for IE.
           cssFilterSupport: true  // Use "-webkit-filter" where available.
        }); 
        $('body').addClass('bg9');
        $('li.storyCard').click(function(){
            window.location.href = $(this).find('a.readMoreLink').attr('href');
        });

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){
            $(".image_cover").addClass('imgLiquidFill imgLiquid');
            $(".imgLiquidFill").imgLiquid();
        }

        $('#yearsSliderFrame')
            .delay(300)
            .queue(function (next) { 
                $(this).css('visibility', 'visible'); 
            next(); 
        });
    });
</script>