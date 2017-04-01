<div class="bg4 minhgt600">
    
    	<!--heading start-->
        <div id="overlayHead" class="bg1">
            <div class="txtc pad15">
                <div class="posrel">
                    <a href="/help/index?iosWebView=~$iosWebView`"><i id="backBtn" class="posabs mainsp arow2 lt0"></i></a>
                    <div class="fontthin f20 white">Post Your Query</div>
                </div>
            </div>
        </div>
        <!--heading:end-->
        <!--request feedback form:start-->
        <div id="requestForm">
            <div class="pad18 btmBrdGrey">
                <div class="f17 fontlig darkGreyColor fullwid">Post your query and we will get back to you</div>
                <div id="errorList" class="color2 f14 mt10"></div>
            </div>
            <div class="pad18 btmBrdGrey" id="emailInp">
                <div class="fontlig f14 greyColor">* Your email ID</div>
                <input class="fullwid pad23 fontlig f16" value="~$email`" placeholder="Not filled in" />
            </div>
            <div class="pad18 btmBrdGrey" id="usernameInp">
                <div class="fontlig f14 greyColor">Username</div>
                <input class="fullwid pad23 fontlig f16" value="~$username`" placeholder="Not filled in" />
            </div>
            <div class="pad18 btmBrdGrey mb20" id="quesInp">
                <div class="fontlig f14 greyColor">* What type of query do you have?</div>
                <input class="fullwid pad23 fontlig f16" placeholder="Not filled in" />
            </div>
            <div class="posfix btmo dispib bg7 white f18 fontthin txtc pad1510  mt15 fullwid" id="submitRequest">Submit Request</div>
        </div>
        <!--request feedback form:end-->
        <!--post submission div:start-->
        <div id="nextDiv" class="posrel fullheight fullwid dispnone">
            <div class="posrel pad23 wid70p txtc">
                <div class="fontlig f16">We have taken your request and will get back to you soon</div>
                <div class="color2 mt30 f16"><a class="color2" href="/help/index?iosWebView=~$iosWebView`">Go to Home</a></div>
            </div>
        </div>
        <!--post submission div:end-->
    
</div>
<script>
var AndroidPromotion=0;
$(document).ready(function(){
    var username = "~$username`";
    var email = "~$email`";
    setInterval(function(){
        autoPopulateFreshdeskDetails(username,email);
    },100);
});
</script>