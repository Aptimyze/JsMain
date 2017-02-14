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
.wid6p {
    width: 6%;
}
</style>
~assign var=dropDownDayArr value= CommonFunction::getRCBDayDropDown()`
~assign var=dropDownTimeArr1 value= CommonFunction::getRCBStartTimeDropDown()`
~assign var=dropDownTimeArr2 value= CommonFunction::getRCBEndTimeDropDown()`
<div class="fullwid bg4 fontlig reqmain">
    <!--start:div header-->
    <div class="tapoverlay posabs" style="display:none;" id="tapOverlayHead"></div>
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
    <div class="posabs rv2_pos5" style="display:none;z-index:102;" id="tapOverlayContent0">
        <div class="posrel bg4"  id="ContLayer0">
            <!--start:top div-->
            <div class="bg1" id="ContHead">
                <div class="rv2_pad1 txtc">
                    <div class="posrel white">
                        <div class="f19 fontthin" id="topHeading">Select your Query</div>
                        <div class="posabs rv2_pos2 backOnCard"><i class="mainsp arow2"></i></div>
                    </div>
                </div>
            </div>
            <!--end:top div-->
            <!--start:middle part-->
            <div id="ContMid0" style="overflow:auto">
                <!--start:content-->
                <div class="rv2_pad17" id="ContentDiv0">
                    <!--start:query type card option-->
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="P" onclick="manageQueryType(this, 'query', 0);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="nameP">~$data.query_options.P`</div>
                                <div class="dispcell vertmid rv2_wid9">
                                    <div class="rv2_sprtie1 options"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="M" onclick="manageQueryType(this, 'query', 0);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="nameM">~$data.query_options.M`</div>
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
                <input type="hidden" name="qtype0" value="N"/>
                <div style="overflow:hidden;position:relative;height: 61px;" class="disp_b btmo">
                    <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp pinkRipple" id="contPaymentBtn">Continue</div>
                </div>
            </div>
        </div>
    </div>
    <div class="posabs rv2_pos5" style="display:none;z-index:102;" id="tapOverlayContent1">
        <div class="posrel bg4"  id="ContLayer1">
            <!--start:top div-->
            <div class="bg1" id="ContHead">
                <div class="rv2_pad1 txtc">
                    <div class="posrel white">
                        <div class="f19 fontthin" id="topHeading">Select Date</div>
                        <div class="posabs rv2_pos2 backOnCard"><i class="mainsp arow2"></i></div>
                    </div>
                </div>
            </div>
            <!--end:top div-->
            <!--start:middle part-->
            <div id="ContMid1" style="overflow:auto">
                <!--start:content-->
                <div class="rv2_pad17" id="ContentDiv1">
                    <!--start:query type card option-->
                    ~foreach from=$dropDownDayArr key=k item=dd`
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="~$k`" onclick="manageQueryType(this, 'day', 1);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="nameday~$k`">~$dd`</div>
                                <div class="dispcell vertmid rv2_wid9">
                                    <div class="rv2_sprtie1 options"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ~/foreach`
                    <!--end:query type card option-->
                </div>
                <!--end:content-->
            </div>
            <div class="posabs btmo fullwid" id="continueBtn">
                ~foreach from=$dropDownDayArr key=k item=dd name=loop1`
                ~if $smarty.foreach.loop1.first`
                <input type="hidden" name="qtype1" value="~$k`"/>
                ~/if`
                ~/foreach`
                <div style="overflow:hidden;position:relative;height: 61px;" class="disp_b btmo">
                    <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp pinkRipple" id="contPaymentBtn">Continue</div>
                </div>
            </div>
        </div>
    </div>
    <div class="posabs rv2_pos5" style="display:none;z-index:102;" id="tapOverlayContent2">
        <div class="posrel bg4"  id="ContLayer2">
            <!--start:top div-->
            <div class="bg1" id="ContHead">
                <div class="rv2_pad1 txtc">
                    <div class="posrel white">
                        <div class="f19 fontthin" id="topHeading">Schedule Time(IST)</div>
                        <div class="posabs rv2_pos2 backOnCard"><i class="mainsp arow2"></i></div>
                    </div>
                </div>
            </div>
            <!--end:top div-->
            <!--start:middle part-->
            <div id="ContMid2" style="overflow:auto">
                <!--start:content-->
                <div class="rv2_pad17" id="ContentDiv2">
                    <!--start:query type card option-->
                    ~foreach from=$dropDownTimeArr1 key=k item=tt`
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="~str_replace(':','_',$k)`" onclick="manageQueryType(this, 'timeStart', 2);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="nametimeStart~str_replace(':','_',$k)`">~$tt`</div>
                                <div class="dispcell vertmid rv2_wid9">
                                    <div class="rv2_sprtie1 options"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ~/foreach`
                    <!--end:query type card option-->
                </div>
                <!--end:content-->
            </div>
            <div class="posabs btmo fullwid" id="continueBtn">
                ~foreach from=$dropDownTimeArr1 key=k item=tt name=loop2`
                ~if $smarty.foreach.loop2.first`
                <input type="hidden" name="qtype2" value="~$k`"/>
                ~/if`
                ~/foreach`
                <div style="overflow:hidden;position:relative;height: 61px;" class="disp_b btmo">
                    <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp pinkRipple" id="contPaymentBtn">Continue</div>
                </div>
            </div>
        </div>
    </div>
    <div class="posabs rv2_pos5" style="display:none;z-index:102;" id="tapOverlayContent3">
        <div class="posrel bg4"  id="ContLayer3">
            <!--start:top div-->
            <div class="bg1" id="ContHead">
                <div class="rv2_pad1 txtc">
                    <div class="posrel white">
                        <div class="f19 fontthin" id="topHeading">Select End Time</div>
                        <div class="posabs rv2_pos2 backOnCard"><i class="mainsp arow2"></i></div>
                    </div>
                </div>
            </div>
            <!--end:top div-->
            <!--start:middle part-->
            <div id="ContMid3" style="overflow:auto">
                <!--start:content-->
                <div class="rv2_pad17" id="ContentDiv3">
                    <!--start:query type card option-->
                    ~foreach from=$dropDownTimeArr2 key=k item=tt`
                    <div class="pt10">
                        <div class="rv2_brdr1 color8 rv2_brrad1 fontlig" qtype="~str_replace(':','_',$k)`" onclick="manageQueryType(this, 'timeEnd', 3);">
                            <div class="disptbl fullwid">
                                <div class="pad15 dispcell vertmid pname padl10" id="nametimeEnd~str_replace(':','_',$k)`">~$tt`</div>
                                <div class="dispcell vertmid rv2_wid9">
                                    <div class="rv2_sprtie1 options"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ~/foreach`
                    <!--end:query type card option-->
                </div>
                <!--end:content-->
            </div>
            <div class="posabs btmo fullwid" id="continueBtn">
                ~foreach from=$dropDownTimeArr2 key=k item=tt name=loop3`
                ~if $smarty.foreach.loop3.last`
                <input type="hidden" name="qtype3" id="qtype" value="~$k`"/>
                ~/if`
                ~/foreach`
                <div style="overflow:hidden;position:relative;height: 61px;" class="disp_b btmo">
                    <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 cursp pinkRipple" id="contPaymentBtn">Continue</div>
                </div>
            </div>
        </div>
    </div>
    <!--start:content box-->
    <div id="requestCallbackLayerDiv">
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
                        <div id="contE" class="color8 f12 fontlig ng-binding">
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
                        <div id="contP" class="color8 f12 fontlig ng-binding">
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
            <div class="brdr1 cursp querySelectBtn" id="0">
                <div class="pad18">
                    <div class="wid94p fl">
                        <div class="color8 f12 fontlig ng-binding">
                            ~$data.query_question`
                        </div>
                        <div class="rv2_brdrbtm2 pt20" style="height: 63px;">
                            <span class="label wid70p color8 f17" id="queryDescription0">~$data.query_options.N`</span>
                        </div>
                    </div>
                    <div class="fr wid6p pt8" style="padding-top: 38px;">
                        <div class="dispcell rv2_wid6 pt20 rv2_vb"> <div class="rv2_rec1"></div></div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <!--end:div-->
            <!--start:div-->
            <div class="brdr1 cursp querySelectBtn" id="1">
                <div class="pad18">
                    <div class="wid94p fl">
                        <div class="color8 f12 fontlig ng-binding">
                            Select Date
                        </div>
                        <div class="rv2_brdrbtm2 pt20" style="height: 63px;">
                            ~foreach from=$dropDownDayArr key=k item=dd name=loop1`
                            ~if $smarty.foreach.loop1.first`
                            <span class="label wid70p color8 f17" id="queryDescription1">~$dd`</span>
                            ~/if`
                            ~/foreach`
                        </div>
                    </div>
                    <div class="fr wid6p pt8" style="padding-top: 38px;">
                        <div class="dispcell rv2_wid6 pt20 rv2_vb"> <div class="rv2_rec1"></div></div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <!--end:div-->
            <!--start:div-->
            <div class="brdr1 cursp querySelectBtn" id="2">
                <div class="pad18">
                    <div class="wid94p fl">
                        <div class="color8 f12 fontlig ng-binding">
                            Schedule Time
                        </div>
                        <div class="rv2_brdrbtm2 pt20" style="height: 63px;">
                            ~foreach from=$dropDownTimeArr1 key=k item=tt name=loop2`
                            ~if $smarty.foreach.loop2.first`
                            <span class="label wid70p color8 f17" id="queryDescription2">~$tt`</span>
                            ~/if`
                            ~/foreach`
                        </div>
                    </div>
                    <div class="fr wid6p pt8" style="padding-top: 38px;">
                        <div class="dispcell rv2_wid6 pt20 rv2_vb"> <div class="rv2_rec1"></div></div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <!--end:div-->
            <!--start:div-->
            <div class="brdr1 cursp querySelectBtn" id="3" style='display: none'>
                <div class="pad18">
                    <div class="wid94p fl">
                        <div class="color8 f12 fontlig ng-binding">
                            Select End Time
                        </div>
                        <div class="rv2_brdrbtm2 pt20" style="height: 63px;">
                            ~foreach from=$dropDownTimeArr2 key=k item=tt name=loop3`
                            ~if $smarty.foreach.loop3.last`
                            <span class="label wid70p color8 f17" id="queryDescription3">~$tt`</span>
                            ~/if`
                            ~/foreach`
                        </div>
                    </div>
                    <div class="fr wid6p pt8" style="padding-top: 38px;">
                        <div class="dispcell rv2_wid6 pt20 rv2_vb"> <div class="rv2_rec1"></div></div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <!--end:div-->
        </div>
        <!--end:content box-->
        <!--start:Next-->
        <div class="btmo posfix fullwid" id="submitBtn">
            <div id="submit" class="cursp bg7 white lh30 fullwid dispbl txtc lh50">~$data.submit_placeholder`</div>
        </div>
    </div>
    <div id="nextDiv" class="posrel fullheight fullwid dispnone">
        <div class="posrel pt40 wid70p txtc">
            <div class="fontlig f16" id="successMsg"></div>
            <div class="color2 pt20 f16"><a class="color2" href="~$referer`">Go Back</a></div>
        </div>
    </div>
    <!--end:Next-->
</div>
<script type="text/javascript">
var AndroidPromotion=0;
$(function() {
    var winHeight = $(window).height(); // total height of the device
    if (winHeight <= 720){
        $('.reqmain').css("height", 720);
    } else {
        $('.reqmain').css("height", winHeight);
    }
});
function showOverlay(id) {
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
    $('#ContLayer'+id).css({
        "width": n_wid,
        "height": n_hgt
    });
    var ContMid_hgt = n_hgt - (53 + 58);
    $("#tapOverlayHead").show();
    $("#tapOverlayContent"+id).show();
    $('#ContMid'+id).css("height", ContMid_hgt);
}
function manageQueryType(el, queryType, id){
    var qType = $(el).attr('qtype');
    $("#ContentDiv"+id).find('.selected_d').each(function(){
        $(this).removeClass('selected_d');
    });
    $(el).addClass('selected_d');
    $("input[name=qtype"+id+"]").val(qType);
    if(queryType == 'query'){
        $("#queryDescription"+id).html($("#name"+qType).text());
    } else {
        $("#queryDescription"+id).html($("#name"+queryType+qType).text());
    }
}
function submitRequest(){
    var callbackSource = "~$callbackSource`";
    var queryType = $("input[name=qtype0]").val();
    var date = $("input[name=qtype1]").val();
    var startTime = $("input[name=qtype2]").val();
    var endTime = $("input[name=qtype3]").val();
    var email = $("input[name=userEmail]").val();
    if (email == ''){
        email = "1";
    }
    var phone = $("input[name=userPhone]").val();
    if (phone == ''){
        phone = "1";
    }
    var paramStr = 'processQuery=1&device=mobile_website&channel=JSMS&callbackSource=' + callbackSource + '&email=' + email + '&phone=' + phone + '&query_type=' + queryType + '&date=' + date + '&startTime=' + startTime + '&endTime=' + endTime;
    paramStr = paramStr.replace(/amp;/g, '');
    url = "/api/v3/common/requestCallbackLayer?" + paramStr;
    $.ajax({
        type: 'POST',
        url: url,
        success: function(data) {
            response = data;
            // Set Default Colors
            $("#contP").removeClass('color2').addClass('color8');
            $("#contE").removeClass('color2').addClass('color8');
            // Handle status responses
            if (data.status == 'missingParameters') {
                ShowTopDownError(["Missing Parameters"]);
            } else if (data.status == 'invalidPhoneNo') {
                ShowTopDownError(["Please enter a valid Phone No"]);
                $("input[name=userPhone]").val('');
                $("#contP").removeClass('color8').addClass('color2');
            } else if (data.status == 'invalidEmail') {
                ShowTopDownError(["Please enter a valid e-mail"]);
                $("input[name=userEmail]").val('');
                $("#contE").removeClass('color8').addClass('color2');
            } else if (data.status == 'invalidDevice') {
                ShowTopDownError(["Invalid Device"]);
            } else if (data.status == 'invalidChannel') {
                ShowTopDownError(["Invalid Channel"]);
            } else if (data.status == 'invalidQueryType') {
                ShowTopDownError(["Please select a Query"]);
            } else if (data.status == 'invalidTime') {
                ShowTopDownError(["Please select a valid Time"]);
            } else if (data.status == 'success') {
                $("#nextDiv").removeClass("dispnone");
                $("#successMsg").text(data.successMsg);
                $("#requestCallbackLayerDiv").addClass("dispnone");
            }
        }
    });
}
$(document).ready(function() {
    $(".querySelectBtn").click(function(e) {
        showOverlay($(this).attr('id'));
    })
    $('.tapoverlay, #continueBtn').click(function(e) {
        if ($('.backOnCard').length) {
            $(".backOnCard").trigger('click');
        }
        $('html, body, #DivOuter').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });
    $(".backOnCard").click(function(e) {
        e.preventDefault();
        $("#tapOverlayHead").hide();
        $("#tapOverlayContent0").hide();
        $("#tapOverlayContent1").hide();
        $("#tapOverlayContent2").hide();
        $("#tapOverlayContent3").hide();
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