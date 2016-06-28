<style type="text/css">
.dnd_bg{background-color:#2d2e2e;}
.dnd_brdr1{border:2px solid #3b3b3b;}
.dnd_pad1{padding:36px 32px;}
.rv2_pad9 {
    padding: 21px 0;
}
.rv2_wid9 {
    width: 40px;
}
</style>
<div class="fullwid bg4 fontlig reqmain">
    <!--start:div header-->
    <div class="bg1 reqTopDiv">
        <div class="pad1">
            <div class="rem_pad1 posrel fullwid ">
                <div class="white fontthin f19 txtc">~$data.title`</div>
                <div class="posabs" style="left:0;top:16px;">
                    <a href="~$referer`"><i class="mainsp arow2 cursp"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!--end:div header-->
    <div class="tapoverlay posabs" style="display:none;" id="tapOverlayHead"></div>
    <div class="posabs rv2_pos5" style="display:none;" id="tapOverlayContent">
        <div class="posrel bg4"  id="ContLayer">
            <!--start:top div-->
            <div class="bg1" id="ContHead">
                <div class="rv2_pad1 txtc">
                    <div class="posrel white">
                        <div class="f19 fontthin" id="topHeading">Select your Query</div>
                        <div class="posabs rv2_pos2" id="backOnCard"><i class="mainsp arow2"></i></div>
                    </div>
                </div>
            </div>
            <!--end:top div-->
            <!--start:middle part-->
            <div id="ContMid" style="overflow:auto">
                <!--start:content-->
                <div class="rv2_pad17" id="ContentDiv">
                    <!--start:query type card option-->
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="P" onclick="manageQueryType(this);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="name">~$data.query_options.P`</div>
                                <div class="dispcell vertmid rv2_wid9">
                                    <div class="rv2_sprtie1 options"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="M" onclick="manageQueryType(this);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="name">~$data.query_options.M`</div>
                                <div class="dispcell vertmid rv2_wid9">
                                    <div class="rv2_sprtie1 options"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:query type card option-->
                </div>
                <!--end:content-->
            </div>
            <div class="posabs btmo fullwid" id="continueBtn">
                <input type="hidden" name="qtype" id="qtype" value="N"/>
                <div style="overflow:hidden;position:relative;height: 61px;" class="disp_b btmo">
                    <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp pinkRipple" id="contPaymentBtn">Continue</div>
                </div>
            </div>
        </div>
    </div>
    <!--start:content box-->
    <div>
        <!--start:div-->
        <div class="brdr1">
            <div class="pad18">
                <p class="color8 f15">~$data.top_placeholder`</p>
            </div>
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="brdr1">
            <div class="pad18">
                <div class="wid94p fl">
                    <div class="color8 f12 fontlig ng-binding">
                        ~$data.email_text`
                    </div>
                    <div class="pt10">
                        <input type="text" name='userEmail' value="~$data.email_autofill`" class="color3o f17 fontlig wid80p"/>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="brdr1">
            <div class="pad18">
                <div class="wid94p fl">
                    <div class="color8 f12 fontlig ng-binding">
                        ~$data.phone_text`
                    </div>
                    <div class="pt10">
                        <input type="text" name='userPhone' value="~$data.phone_autofill`" class="color8 f17 fontlig wid80p"/>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="brdr1 cursp" id="querySelectBtn">
            <div class="pad18">
                <div class="wid94p fl">
                    <div class="color8 f12 fontlig ng-binding">
                        ~$data.query_question`
                    </div>
                    <div class="pt10">
                        <span class="label wid70p color8 f17">~$data.query_options.N`</span>
                    </div>
                </div>
                <div class="fr wid4p pt8">
                    <i class="mainsp arow1"></i>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <!--end:div-->
    </div>
    <!--end:content box-->
    <!--start:Next-->
    <div class="btmo posabs fullwid" id="submitBtn">
        <div id="submit" class="cursp bg7 white lh30 fullwid dispbl txtc lh50">~$data.submit_placeholder`</div>
    </div>
    <!--end:Next-->
</div>
<script type="text/javascript">
$(function() {
    var winHeight = $(window).height(); // total height of the device
    $('.reqmain').css("height", winHeight); // aaply the height of device to main div
});
function showOverlay() {
    var vwid = $(window).width();
    var vhgt = $(window).height();
    hgt = vhgt + "px";
    $('html, body, #DivOuter').css({
        'overflow': 'hidden',
        'height': '100%'
    });
    /* for setting content overlay */
    var n_wid = vwid - 20;
    var n_hgt = vhgt - 30;
    $('#ContLayer').css({
        "width": n_wid,
        "height": n_hgt
    });
    var ContMid_hgt = n_hgt - (53 + 58);
    $('#ContMid').css("height", ContMid_hgt);
    $("#tapOverlayHead").show();
    $("#tapOverlayContent").show();
}
function manageQueryType(el){
    var queryType = $(el).attr('qtype');
    $(el).parent().parent().find('.selected_d').each(function(){
        $(this).removeClass('.selected_d');
    });
    $(el).addClass('.selected_d');
    $("input[name=qtype]").val(queryType);
}
function submitRequest(){
    var queryType = $("input[name=qtype]").val();
    var email = $("input[name=userEmail]").val();
    var phone = $("input[name=userPhone]").val();
    var paramStr = 'processQuery=1&device=mobile_website&channel=JSMS&callbackSource=JSMSHelpModule' + '&email=' + email + '&phone=' + phone + '&query_type=' + queryType;
    paramStr = paramStr.replace(/amp;/g, '');
    url = "/api/v3/common/requestCallbackLayer?" + paramStr;
    $.ajax({
        type: 'POST',
        url: url,
        success: function(data) {
            response = data;
            // Set Default Colors
            $("input[name=userPhone]").parent().find('.ng-binding').removeClass('color2').addClass('color8');
            $("input[name=userEmail]").parent().find('.ng-binding').removeClass('color2').addClass('color8');
            // Handle status responses
            if (data.status == 'missingParameters') {
                ShowTopDownError(["Missing Parameters"]);
            } else if (data.status == 'invalidPhoneNo') {
                ShowTopDownError(["Please enter a valid Phone No"]);
                $("input[name=userPhone]").val('');
                $("input[name=userPhone]").parent().find('.ng-binding').removeClass('color8').addClass('color2');
            } else if (data.status == 'invalidEmail') {
                ShowTopDownError(["Please enter a valid e-mail"]);
                $("input[name=userEmail]").val('');
                $("input[name=userEmail]").parent().find('.ng-binding').removeClass('color8').addClass('color2');
            } else if (data.status == 'invalidDevice') {
                ShowTopDownError(["Invalid Device"]);
            } else if (data.status == 'invalidChannel') {
                ShowTopDownError(["Invalid Channel"]);
            } else if (data.status == 'invalidQueryType') {
                ShowTopDownError(["Please select a Query"]);
            } else if (data.status == 'success') {
                
            }
        }
    });
}
$(document).ready(function() {
    $("#querySelectBtn").click(function(e) {
        showOverlay();
    })
    $('.tapoverlay, #continueBtn').click(function(e) {
        if ($('#backOnCard').length) {
            $("#backOnCard").trigger('click');
        }
        $('html, body, #DivOuter').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });
    $("#backOnCard").click(function(e) {
        e.preventDefault();
        $("#tapOverlayHead").hide();
        $("#tapOverlayContent").hide();
        $('html, body, #DivOuter').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });
    $("#submitBtn").click(function(e){
        e.preventDefault();
        submitRequest();
    })
})

</script>