<div class="bg4">
    <div class="perspective" id="perspective">
        <div class="" id="pcontainer">
        </div>
        <div id="hamburger" class="hamburgerCommon dn fullwid">	
            ~include_component('static', 'newMobileSiteHamburger')`	
        </div>
    </div>
        <!--heading:start-->
        <div id="overlayHead" class="bg1">
            <div class="txtc pad15">
                <div class="posrel">
                    <i id="backBtnSection" class="posabs mainsp arow2 lt0 dispnone"></i>
                    <i id="backBtnQues" class="posabs mainsp arow2 lt0 dispnone"></i>
                    <i id="hamburgerIcon" class="mainsp baricon posabs lt0" hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i>
                    <div class="fontthin f20 white">Help</div>
                </div>
            </div>
              </div>
            <!--heading:end-->
            <!--first page:start-->
            <div id="firstPageDiv">
                <div class="pad18 btmBrdGrey"><input id="searchPId" type="textbox" class="f17 fontthin greyColor fullwid" name="searchPid" value="" autocomplete="off" placeholder="Search for your Query?"> </input>
                </div>
				<!--no result found :start-->
                <div id="noResultDiv" class="dispnone">
                    <div class="posrel pad16 wid70p txtc">
                        <div class="fontlig mt30 f16">No Result Found</div>
                        <div class="color2 mt60 f16"><a class="color2" href="/help/JSMSPostQuery">Post Your Query</a></div>
                    </div>
                </div>
				<!--no result found :end-->
                <!--search question listing:start-->
                <div id="questionListing" class="dispnone">
                    <ul id="quesList2" class="fullwid pad12_25 quesList">
                    </ul>
                </div>
				<!--search question listing:start-->
                <!--section listing:start-->
                <div id="sectionListing">
                    <div class="fontlig f16 greyColor pad015">Categories</div>
                    <ul class="pad18 btmBrdGrey sectionList">
                    </ul>
                    <div class="pad16 btmBrdGrey darkGreyColor f14 txtc">Other ways to get help</div>
                    <div class="posrel pad2515">
                        <div class="txtc pad24 greyColor f13">Call toll free number</div>
                        <div class="txtc pad24 f21 color2">1-800-419-6299</div>
                        <div class="txtc pad24 greyColor f13">Daily in between 9AM - 9PM (IST)</div>
                    </div>
                    <div class="posfix btmo dispib bg7 white f18 fontthin txtc pad1510 mt15 fullwid" id="postQueryBtn"><a class="white" href="/help/JSMSPostQuery">Post Your Query</a></div>
                </div>
                <!--section listing:end-->
            </div>
       

        <!--first page:end-->
        <!--questions in a section listing:start-->
        <div id="sectionQuesDiv" class="dispnone">
            <div class="marg25 btmBrdGrey color7 fontreg sectionHeading">
            </div>
            <ul id="quesList" class="fullwid pad12_25 quesList">
            </ul>
        </div>
        <!--questions in a section listing:end-->
        <!--question answer div:start-->
        <div id="questionAnswerDiv" class="dispnone">
            <div class="marg25 btmBrdGrey color7 fontreg questionHeading">
            </div>
            <div class="pad_15 greyColor6 f14 app_txtl lh25 fontlig answer">
            </div>
        </div>
        <!--question answer div:start-->
</div>
<script>
$(document).ready(function(){
    var username = "~$username`";
    var email = "~$email`";
    setInterval(function(){
        autoPopulateFreshdeskDetails(username,email);
    },100);
});
</script>