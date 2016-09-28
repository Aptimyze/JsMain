~assign var=module value= $sf_request->getParameter('module')`
~assign var=action value= $sf_request->getParameter('action')`
~assign var=dropDownDayArr value= CommonFunction::getRCBDayDropDown()`
~assign var=dropDownTimeArr1 value= CommonFunction::getRCBStartTimeDropDown()`
~assign var=dropDownTimeArr2 value= CommonFunction::getRCBEndTimeDropDown()`
~if $subsection eq 'header'`
<style type="text/css">
.wid35 {
    width:35%;
}
.wid60 {
    width:65%;
}
</style>
<!--start:callback form-->
<div id="headerRequestCallbackLogout" class="pos-abs z5" style="display:none">
    <i class="reqCalbck-sprite pos-abs reqCalbck-cross2 cursp reqCalbck-pos12" id="headerRequestCallbackLogoutCloseBtn"></i>
    <div class="bg-white" style="width:450px;border-radius:5px">
        <div class="reqCalbck-pad33">
            <form>
                <div id="headerReqEmail" class="reqCalbck-bdr12 f17 pb5">
                    <input type="text" class="fullwid brdr-0 f17 color11 fontlig whiteout" placeholder="E-Mail" value=""/>
                </div>
                <div id="headerReqEmailError" style="color:red;display:none" class="f14 pt8">Please provide a valid E-Mail Id</div>
                <div id="headerReqMob" class="reqCalbck-bdr12 f17 pb5 pt20">
                    <input type="text" class="fullwid brdr-0 f17 color11 fontlig whiteout" placeholder="Mobile number" value=""/>
                </div>
                <div id="headerReqMobError" style="color:red;display:none" class="f14 pt8">Please provide a valid Phone Number</div>
                <div id="rcbHeaderDrop" class="rcbfield rcb_pt17 color2 fontlig clearfix reqCalbck-bdr12 pb15 pl3">
                    <!--start:date-->
                    <div class="rcb_fl wid35">
                        <div class="clearfix">
                            <div class="f16 rcb_lh40 rcb_fl pr5">Date</div>
                            <div class="rcb_fl">
                                <div class="rcb_fl">
                                    <div class="wid88">
                                        <!--start:drop down UI-->
                                        <dl id="dropDown0" class="rcbdropdown">
                                            <dt><span></span></dt>
                                            <dd>
                                            <ul>
                                                ~foreach from=$dropDownDayArr key=k item=dd`
                                                <li id="~$k`" class="cursp">~$dd`</li>
                                                ~/foreach`
                                            </ul>
                                            </dd>
                                        </dl>
                                        <!--end:drop down UI-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:date-->
                    <!--start:time-->
                    <div class="rcb_fl wid60 pl4">
                        <div class="clearfix">
                            <div class="f16 rcb_lh40 rcb_fl pr5">Schedule Time(IST)</div>
                            <div class="rcb_fl">
                                <div class="rcb_fl">
                                    <div class="wid88 rcb_fl">
                                        <dl id="dropDown1" class="rcbdropdown">
                                            <dt><span></span></dt>
                                            <dd>
                                            <ul>
                                                ~foreach from=$dropDownTimeArr1 key=k item=tt`
                                                <li id="~$k`" class="cursp">~$tt`</li>
                                                ~/foreach`
                                            </ul>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="disp-none wid88 rcb_fl rcb_m2">  <dl id="dropDown2" class="rcbdropdown">
                                        <dt><span></span></dt>
                                        <dd>
                                        <ul>
                                            ~foreach from=$dropDownTimeArr2 key=k item=tt`
                                            <li id="~$k`" class="cursp">~$tt`</li>
                                            ~/foreach`
                                        </ul>
                                        </dd>
                                    </dl> </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:time-->
                    <input id="rcbHeaderdropDown0" type="hidden" name="dropDownDaySelected" value=""/>
                    <input id="rcbHeaderdropDown1" type="hidden" name="dropDownTimeStartSelected" value=""/>
                    <input id="rcbHeaderdropDown2" type="hidden" name="dropDownTimeEndSelected" value=""/>
                </div>
                <div id="headerReqTimeError" style="color:red;display:none" class="f14 pt8">Please select valid Time Duration</div>
                <div class="pt20">
                    <div class="reqCalbck-bdr12 colr2 f17 pb5 pt10 cursp pos-rel js-drop" id="headerDatefld"> <span class="headerDatefld-val f15 fontlig js-fill">~if $module neq 'membership'`What type of query do you have ?~else`Questions regarding Jeevansathi Membership Plans~/if`</span>
                        <div class="pos-abs reqCalbck-leftcorner_trianle1 reqCalbck-pos6 z2"></div>
                        <div class="pos-abs fullwid reqCalbck-pos7">
                            <div id="reqCalbck-content-1" class="reqCalbck-content z1 reqCalbck-drop1 reqCalbck-dropSec headerDatefld-drop disp-none" >
                                <ul data-attr="headerDatefld-list">
                                    <li secSelectedid="M" class="fontlig f14 ~if $module eq 'membership'`active~/if`">Questions regarding Jeevansathi Membership Plans</li>
                                    <li secSelectedid="P" class="fontlig f14">Questions or feedback regarding Jeevansathi Profile</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="headerReqQueryError" style="color:red;display:none" class="f14 pt8">Please select valid Query Type</div>
                <div class="pt20">
                    <div style="overflow: hidden;position: relative;">
                    <div id="headerSubmitCallbackRequest" class="cursp fullwid bg_pink txtc lh50 colrw pinkRipple hoverPink">Submit Request</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="headerRequestCallbackLogin" class="pos-abs z5" style="display:none">
    <i class="reqCalbck-sprite pos-abs reqCalbck-cross2 cursp reqCalbck-pos12" id="headerRequestCallbackLoginCloseBtn"></i>
    <div class="bg-white" style="width:450px;border-radius:5px">
        <div class="reqCalbck-pad33">
            <div id="headerReqCallBackMessage" class="fullwid txtc lh20" style="color: black"></div>
        </div>
    </div>
</div>
<!--end:callback form-->
<script type="text/javascript">
    function getValFLi() {
        var getdata = $('#rcbHeaderDrop .rcbdropdown dd ul').find('li:first').map(function () {
            return $(this).text();
        }).get();
        return getdata;
    }

    $("#rcbHeaderDrop dt").click(function () {
        var N_id = $(this).parent().attr('id');
        $("dd ul").css('display', 'none');
        $("#" + N_id + " dd ul").toggle();
    });

    $("#rcbHeaderDrop dd ul li").click(function () {
        var text = $(this).html();
        var text1 = $(this).text();
        var P_id = $(this).parent().parent().parent().attr('id');
        $("#rcbHeaderDrop " + "#" + P_id + " dt span").html(text);
        $("#rcbHeaderDrop " + "#" + P_id + " dd ul").css('display', 'none');
        $("#rcbHeader" + P_id + "").val($(this).attr('id'));
        var date = $("#rcbHeaderdropDown0").val();
        var startTime = $("#rcbHeaderdropDown1").val();
        var endTime = $("#rcbHeaderdropDown2").val();
        var t1 = Date.parse(date+" "+startTime), t2 = Date.parse(date+" "+endTime), now = Date.parse(new Date());
        if(t2-t1 <= 0 || t1 < now) {
            $("#headerReqTimeError").show();
        } else {
            $("#headerReqTimeError").hide();
        }
    });

    function intialize() {
        var value = getValFLi();
        $.each(value, function (i, val) {
            if (i == 2) {
                val = "9 PM";
                $("#rcbHeaderDrop #rcbHeaderdropDown" + i).val($("#rcbHeaderDrop #dropDown"+i+" dd ul li:last").attr('id'));
            } else {
                $("#rcbHeaderDrop #rcbHeaderdropDown" + i).val($("#rcbHeaderDrop #dropDown"+i+" dd ul li:first").attr('id'));
            }
            $("#rcbHeaderDrop #dropDown" + i + " dt span").html(val);
        });
    }
    $(document).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("rcbdropdown")) {
            $("#rcbHeaderDrop .rcbdropdown dd ul").hide();
        }
    });

    intialize();
    
    var loginData = new Array();
    
    ~assign var=loggedIn value= $sf_request->getAttribute('login')`
    ~if $loggedIn`
        ~assign var=loginData value= $sf_request->getAttribute('loginData')`
    ~/if`
    
    var module = "~$sf_request->getParameter('module')`";
    var secsecCallbackSource = "";

    $(window).load(function(){
        var reqCallbackError = true;
        var regExEmail=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
        var regExIndian=/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
        var regExIndianLandline=/^[0-9]\d{2,4}[-. ]?\d{6,8}$/;
        var regExInternational=/^\+(?:[0-9][-. ]? ?){7,14}[0-9]$/;
        var module = "~$sf_request->getParameter('module')`";
        var loggedIn = "~$sf_request->getAttribute('login')`";
        var loginPhone = "~$loginData.PHONE_MOB`";
        var loginEmail = "~$loginData.EMAIL`";
        
        if(loginEmail){
            $('#headerReqEmail input').val(loginEmail);
        }
        
        if(loginPhone){
            $('#headerReqMob input').val(loginPhone);            
        }

        $("#headerRequestCallback").click(function(e){
            if(!$(".overlay1").length){
                $("#topNavigationBar").addClass('pos-rel layersZ');
                $("#headerRequestCallback").addClass('js-reqcallbck opa50');
                $('header').before('<div class="overlay1"></div>');
                $("#headerRequestCallbackLogout").show();
            } else {
                $("#topNavigationBar").removeClass('pos-rel layersZ');
                $("#headerRequestCallback").removeClass('js-reqcallbck opa50');
                $('.overlay1').remove();
                $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").hide();
            }
        });
        
        $('#headerReqEmail input').on('blur', function() {
            if(!regExEmail.test($(this).val().toLowerCase())){
                $("#headerReqEmailError").show();
                reqCallbackError = true;
            } else {
                $("#headerReqEmailError").hide();
                reqCallbackError = false;
            }
        });

        $('#headerReqMob input').on('blur', function() {
            if(!regExIndian.test($(this).val()) && !regExInternational.test($(this).val()) && !regExIndianLandline.test($(this).val())) {
                $("#headerReqMobError").show();
                reqCallbackError = true;
            } else {
                $("#headerReqMobError").hide();
                reqCallbackError = false;
            }
        });

        if(module=='membership'){
            secsecCallbackSource = 'Membership_Page';
        } else {
            secsecCallbackSource = 'Header';
        }

        $("#headerSubmitCallbackRequest").click(function(e){
            var phNo = $("#headerReqMob input").val();
            var email = $("#headerReqEmail input").val().toLowerCase();
            var secSelectedid = $("#headerDatefld ul li.active").attr('secSelectedid');
            var date = $("#rcbHeaderdropDown0").val();
            var startTime = $("#rcbHeaderdropDown1").val();
            var endTime = $("#rcbHeaderdropDown2").val();
            var t1 = Date.parse(date+" "+startTime), t2 = Date.parse(date+" "+endTime), now = Date.parse(new Date());
            if((regExIndian.test(phNo) || regExInternational.test(phNo) || regExIndianLandline.test(phNo)) && regExEmail.test(email) && secSelectedid != 'Q' && (t2-t1 > 0 && t1 > now)) {
                if(secSelectedid == 'M') {
                    $.post("/membership/addCallBck",{'phNo':phNo.trim(),'email':email.trim(),'jsSelectd':'P3','execCallbackType':'JS_ALL','tabVal':1,'device':'desktop','channel':'JSPC','callbackSource':secsecCallbackSource,'date':date,'startTime':startTime,'endTime':endTime},function(response){
                        $("#headerReqCallBackMessage").text(response);
                        $("#headerRequestCallbackLogout").hide();
                        $("#headerRequestCallbackLogin").show();
                        $("#headerReqQueryError,#headerReqEmailError,#headerReqMobError,#headerReqTimeError").hide();
                    });
                } else {
                    $.post("/common/requestCallBack",{'email':email.trim(),'phone':phNo.trim(),'query_type':'P','device':'desktop','channel':'JSPC','callbackSource':secsecCallbackSource,'date':date,'startTime':startTime,'endTime':endTime},function(response){
                        if(response == "Y") {
                            $("#headerReqCallBackMessage").text('We shall call you at the earliest');
                        } else {
                            $("#headerReqCallBackMessage").text('Something Went Wrong...\nPlease try again !');
                        }
                        $("#headerRequestCallbackLogout").hide();
                        $("#headerRequestCallbackLogin").show();
                        $("#headerReqQueryError,#headerReqEmailError,#headerReqMobError,#headerReqTimeError").hide();
                    });
                }
            } else {
                if(!regExIndian.test(phNo) && !regExInternational.test(phNo) && !regExIndianLandline.test(phNo)) {
                    $("#headerReqMobError").show();
                }
                if(!regExEmail.test(email)){
                    $("#headerReqEmailError").show();
                }
                if(secSelectedid == "Q"){
                    $("#headerReqQueryError").show();
                }
                if(t2-t1 <= 0 || t1 < now) {
                    $("#headerReqTimeError").show();
                } else {
                    $("#headerReqTimeError").hide();
                }
            }
        });

        $('#headerRequestCallbackLoginCloseBtn, #headerRequestCallbackLogoutCloseBtn').click(function(e){
            $("#topNavigationBar").removeClass('pos-rel layersZ');
            $("#headerRequestCallback").removeClass('js-reqcallbck opa50');
            $('.overlay1').remove();
            $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").hide();
        });

        if(module == 'membership'){
            $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").addClass('mem-pos10');
        } else {
            $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").css('top','75px');
            
            if(loggedIn){
                $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").css('left','370px');
            } else {
                $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").css('left','300px');
            }
        }

        $(document).keyup(function(e) {
            if($("#headerRequestCallbackLogout,#headerRequestCallbackLogin").is(':visible')){
                if (e.keyCode == 27) {
                    $("#topNavigationBar").removeClass('pos-rel layersZ');
                    $("#headerRequestCallback").removeClass('js-reqcallbck opa50');
                    $('.overlay1').remove();
                    $("#headerRequestCallbackLogout,#headerRequestCallbackLogin").hide();
                }
            }
        });
        
    });
    $(function() {
        // check pick up drop down
        $('.js-drop').click(function() {
            $('.js-drop .reqCalbck-dropSec').slideUp(300);
            var getElemId = $(this).attr('id');
            var DropDownName = getElemId;
            $('.' + DropDownName + '-drop').slideToggle(300);
        });
        $('.reqCalbck-dropSec ul li').click(function(event) {
            event.stopPropagation();
            $('.reqCalbck-dropSec ul li').each(function(){
                $(this).removeClass('active');    
            });
            $(this).addClass('active');
            var OptSel = $(this).text();
            var getlistName = $(this).parent().attr('data-attr');
            var b = getlistName.split('-');
            var temp = b[0];
            $('span.' + temp + '-val').text(OptSel);
            $('.js-drop .reqCalbck-dropSec').slideUp(300);
        });
        $('.js-fill').on('keydown', function(e) {
            var s = String.fromCharCode(e.which);
        });
    });
</script>
~/if`
~if $subsection eq 'footer'`
<!--start:callback form-->
<div id="footerRequestCallbackLogout" class="pos-abs z5" style="display:none">
    <i class="reqCalbck-sprite pos-abs reqCalbck-cross2 cursp reqCalbck-pos12" id="footerRequestCallbackLogoutCloseBtn"></i>
    <div class="bg-white" style="width:450px;border-radius:5px">
        <div class="reqCalbck-pad33">
            <form>
                <div id="footerReqEmail" class="reqCalbck-bdr12 f17 pb5">
                    <input type="text" class="fullwid brdr-0 f17 color11 fontlig whiteout" placeholder="E-Mail" value=""/>
                </div>
                <div id="footerReqEmailError" style="color:red;display:none" class="f14 pt8">Please provide a valid E-Mail Id</div>
                <div id="footerReqMob" class="reqCalbck-bdr12 f17 pb5 pt20">
                    <input type="text" class="fullwid brdr-0 f17 color11 fontlig whiteout" placeholder="Mobile number" value=""/>
                </div>
                <div id="footerReqMobError" style="color:red;display:none" class="f14 pt8">Please provide a valid Phone Number</div>
                <div id="rcbFooterDrop" class="rcbfield rcb_pt17 color2 fontlig clearfix reqCalbck-bdr12 pb15 pl3">
                    <!--start:date-->
                    <div class="rcb_fl wid35">
                        <div class="clearfix">
                            <div class="f16 rcb_lh40 rcb_fl pr5">Date</div>
                            <div class="rcb_fl">
                                <div class="rcb_fl">
                                    <div class="wid88">
                                        <!--start:drop down UI-->
                                        <dl id="dropDown0" class="rcbdropdown">
                                            <dt><span></span></dt>
                                            <dd>
                                            <ul>
                                                ~foreach from=$dropDownDayArr key=k item=dd`
                                                <li id="~$k`" class="cursp">~$dd`</li>
                                                ~/foreach`
                                            </ul>
                                            </dd>
                                        </dl>
                                        <!--end:drop down UI-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:date-->
                    <!--start:time-->
                    <div class="rcb_fl wid60 pl4">
                        <div class="clearfix">
                            <div class="f16 rcb_lh40 rcb_fl pr5">Schedule Time(IST)</div>
                            <div class="rcb_fl">
                                <div class="rcb_fl">
                                    <div class="wid88 rcb_fl">
                                        <dl id="dropDown1" class="rcbdropdown">
                                            <dt><span></span></dt>
                                            <dd>
                                            <ul>
                                                ~foreach from=$dropDownTimeArr1 key=k item=tt`
                                                <li id="~$k`" class="cursp">~$tt`</li>
                                                ~/foreach`
                                            </ul>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="disp-none wid88 rcb_fl rcb_m2">  <dl id="dropDown2" class="rcbdropdown">
                                        <dt><span></span></dt>
                                        <dd>
                                        <ul>
                                            ~foreach from=$dropDownTimeArr2 key=k item=tt`
                                            <li id="~$k`" class="cursp">~$tt`</li>
                                            ~/foreach`
                                        </ul>
                                        </dd>
                                    </dl> </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:time-->
                    <input id="rcbFooterdropDown0" type="hidden" name="dropDownDaySelected" value=""/>
                    <input id="rcbFooterdropDown1" type="hidden" name="dropDownTimeStartSelected" value=""/>
                    <input id="rcbFooterdropDown2" type="hidden" name="dropDownTimeEndSelected" value=""/>
                </div>
                <div id="footerReqTimeError" style="color:red;display:none" class="f14 pt8">Please select valid Time Duration</div>
                <div class="pt20">
                    <div class="reqCalbck-bdr12 colr2 f17 pb5 pt10 cursp pos-rel js-drop" id="footerDatefld"> <span class="footerDatefld-val f15 fontlig js-fill">~if $module neq 'membership'`What type of query do you have ?~else`Questions regarding Jeevansathi Membership Plans~/if`</span>
                        <div class="pos-abs reqCalbck-leftcorner_trianle1 reqCalbck-pos6 z2"></div>
                        <div class="pos-abs fullwid reqCalbck-pos7">
                            <div id="reqCalbck-content-1" class="reqCalbck-content z1 reqCalbck-drop1 reqCalbck-dropSec footerDatefld-drop disp-none" >
                                <ul data-attr="footerDatefld-list">
                                    <li secSelectedid="M" class="fontlig f14 ~if $module eq 'membership'`active~/if`">Questions regarding Jeevansathi Membership Plans</li>                                    
                                    <li secSelectedid="P" class="fontlig f14">Questions or feedback regarding Jeevansathi Profile</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="footerReqQueryError" style="color:red;display:none" class="f14 pt8">Please select a valid Query Type</div>
                <div class="pt20">
                    <div style="overflow: hidden;position: relative;">
                    <div id="footerSubmitCallbackRequest" class="cursp fullwid bg_pink txtc lh50 colrw pinkRipple hoverPink">Submit Request</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="footerRequestCallbackLogin" class="pos-abs z5" style="display:none">
    <i class="reqCalbck-sprite pos-abs reqCalbck-cross2 cursp reqCalbck-pos12" id="footerRequestCallbackLoginCloseBtn"></i>
    <div class="bg-white" style="width:450px;border-radius:5px">
        <div class="reqCalbck-pad33">
            <div id="footerReqCallBackMessage" class="fullwid txtc lh20" style="color: black"></div>
        </div>
    </div>
</div>
<!--end:callback form-->
<script type="text/javascript">
    function getValFLi() {
        var getdata = $('#rcbFooterDrop .rcbdropdown dd ul').find('li:first').map(function () {
            return $(this).text();
        }).get();
        return getdata;
    }

    $("#rcbFooterDrop dt").click(function () {
        var N_id = $(this).parent().attr('id');
        $("dd ul").css('display', 'none');
        $("#" + N_id + " dd ul").toggle();
    });

    $("#rcbFooterDrop dd ul li").click(function () {
        var text = $(this).html();
        var text1 = $(this).text();
        var P_id = $(this).parent().parent().parent().attr('id');
        $("#rcbFooterDrop " + "#" + P_id + " dt span").html(text);
        $("#rcbFooterDrop " + "#" + P_id + " dd ul").css('display', 'none');
        $("#rcbFooter" + P_id + "").val($(this).attr('id'));
        var date = $("#rcbFooterdropDown0").val();
        var startTime = $("#rcbFooterdropDown1").val();
        var endTime = $("#rcbFooterdropDown2").val();
        var t1 = Date.parse(date+" "+startTime), t2 = Date.parse(date+" "+endTime), now = Date.parse(new Date());
        if(t2-t1 <= 0 || t1 < now) {
            $("#footerReqTimeError").show();
        } else {
            $("#footerReqTimeError").hide();
        }
    });

    function intialize() {
        var value = getValFLi();
        $.each(value, function (i, val) {
            if (i == 2) {
                val = "9 PM";
                $("#rcbFooterDrop #rcbFooterdropDown" + i).val($("#rcbFooterDrop #dropDown"+i+" dd ul li:last").attr('id'));
            } else {
                $("#rcbFooterDrop #rcbFooterdropDown" + i).val($("#rcbFooterDrop #dropDown"+i+" dd ul li:first").attr('id'));
            }
            $("#rcbFooterDrop #dropDown" + i + " dt span").html(val);
        });
    }
    $(document).bind('click', function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("rcbdropdown")) {
            $("#rcbFooterDrop .rcbdropdown dd ul").hide();
        }
    });

    intialize();

    var loginData = new Array();
    
    ~assign var=loggedIn value= $sf_request->getAttribute('login')`
    ~if $loggedIn`
        ~assign var=loginData value= $sf_request->getAttribute('loginData')`
    ~/if`

    var module = "~$sf_request->getParameter('module')`";
    var secCallbackSource = "";
    
    $(window).load(function(){
        var reqCallbackError = true;
        var regExEmail=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
        var regExIndian=/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
        var regExIndianLandline=/^[0-9]\d{2,4}[-. ]?\d{6,8}$/;
        var regExInternational=/^\+(?:[0-9][-. ]? ?){7,14}[0-9]$/;
        var module = "~$sf_request->getParameter('module')`";
        var loggedIn = "~$sf_request->getAttribute('login')`";
        var loginPhone = "~$loginData.PHONE_MOB`";
        var loginEmail = "~$loginData.EMAIL`";

        if(loginEmail){
            $('#footerReqEmail input').val(loginEmail);
        }
        
        if(loginPhone){
            $('#footerReqMob input').val(loginPhone);
        }

        $("#footerRequestCallback").click(function(e){
            if(!$(".overlay1").length){
                $("#js-footer").addClass('pos-rel layersZ');
                $("#footerRequestCallback").addClass('js-reqcallbck opa50');
                $('footer').before('<div class="overlay1"></div>');
                $("#footerRequestCallbackLogout").show();
            } else {
                $("#js-footer").removeClass('pos-rel layersZ');
                $("#footerRequestCallback").removeClass('js-reqcallbck opa50');
                $('.overlay1').remove();
                $("#footerRequestCallbackLogout").hide();
                $("#footerRequestCallbackLogin").hide();
            }
        });
        
        $('#footerReqEmail input').on('blur', function() {
            if(!regExEmail.test($(this).val().tolowerCase())){
                $("#footerReqEmailError").show();
                reqCallbackError = true;
            } else {
                $("#footerReqEmailError").hide();
                reqCallbackError = false;
            }
        });

        $('#footerReqMob input').on('blur', function() {
            if(!regExIndian.test($(this).val()) && !regExInternational.test($(this).val()) && !regExIndianLandline.test($(this).val())) {
                $("#footerReqMobError").show();
                reqCallbackError = true;
            } else {
                $("#footerReqMobError").hide();
                reqCallbackError = false;
            }
        });

        if(module=='membership'){
            secCallbackSource = 'Membership_Page';
        } else {
            secCallbackSource = 'Footer';
        }

        $("#footerSubmitCallbackRequest").click(function(e){
            var phNo = $("#footerReqMob input").val();
            var email = $("#footerReqEmail input").val().toLowerCase();
            var secSelectedid = $("#footerDatefld ul li.active").attr('secSelectedid');
            var date = $("#rcbFooterdropDown0").val();
            var startTime = $("#rcbFooterdropDown1").val();
            var endTime = $("#rcbFooterdropDown2").val();
            var t1 = Date.parse(date+" "+startTime), t2 = Date.parse(date+" "+endTime), now = Date.parse(new Date());
            if((regExIndian.test(phNo) || regExInternational.test(phNo) || regExIndianLandline.test(phNo)) && regExEmail.test(email) && secSelectedid != 'Q' && (t2-t1 > 0 && t1 > now)) {
                if(secSelectedid == 'M') {
                    $.post("/membership/addCallBck",{'phNo':phNo.trim(),'email':email.trim(),'jsSelectd':'P3','execCallbackType':'JS_ALL','tabVal':1,'device':'desktop','channel':'JSPC','callbackSource':secCallbackSource,'date':date,'startTime':startTime,'endTime':endTime},function(response){
                        $("#footerReqCallBackMessage").text(response);
                        $("#footerRequestCallbackLogout").hide();
                        $("#footerRequestCallbackLogin").show();
                        $("#footerReqQueryError").hide();
                        $("#footerReqEmailError").hide();
                        $("#footerReqMobError").hide();
                        $("#footerReqTimeError").hide();
                    });
                } else {
                    $.post("/common/requestCallBack",{'email':email.trim(),'phone':phNo.trim(),'query_type':'P','device':'desktop','channel':'JSPC','callbackSource':secCallbackSource,'date':date,'startTime':startTime,'endTime':endTime},function(response){
                        if(response == "Y") {
                            $("#footerReqCallBackMessage").text('We shall call you at the earliest');
                        } else {
                            $("#footerReqCallBackMessage").text('Something Went Wrong...\nPlease try again !');
                        }
                        $("#footerRequestCallbackLogout").hide();
                        $("#footerRequestCallbackLogin").show();
                        $("#footerReqQueryError,#footerReqEmailError,#footerReqMobError,#footerReqTimeError").hide();
                    });
                }
            } else {
                if(!regExIndian.test(phNo) && !regExInternational.test(phNo) && !regExIndianLandline.test(phNo)) {
                    $("#footerReqMobError").show();
                }
                if(!regExEmail.test(email)){
                    $("#footerReqEmailError").show();
                }
                if(secSelectedid == "Q"){
                    $("#footerReqQueryError").show();
                }
                if(t2-t1 <= 0 || t1 < now) {
                    $("#footerReqTimeError").show();   
                } else {
                    $("#headerReqTimeError").hide();
                }
            }
        });

        $('#footerRequestCallbackLoginCloseBtn, #footerRequestCallbackLogoutCloseBtn').click(function(e){
            $("#js-footer").removeClass('pos-rel layersZ');
            $("#topNavigationBar").addClass('layersZ');
            $("#footerRequestCallback").removeClass('js-reqcallbck opa50');
            $('.overlay1').remove();
            $("#footerRequestCallbackLogout").hide();
            $("#footerRequestCallbackLogin").hide();
        });

        $("#footerRequestCallbackLogout,#footerRequestCallbackLogin").css('left','215px');
        $("#footerRequestCallbackLogout").css('top','-360px');
        $("#footerRequestCallbackLogin").css('top','-135px');

        $(document).keyup(function(e) {
            if($("#footerRequestCallbackLogout,#footerRequestCallbackLogin").is(':visible')){
                if (e.keyCode == 27) {
                    $("#js-footer").removeClass('pos-rel layersZ');
                    $("#topNavigationBar").addClass('layersZ');
                    $("#footerRequestCallback").removeClass('js-reqcallbck opa50');
                    $('.overlay1').remove();
                    $("#footerRequestCallbackLogout,#footerRequestCallbackLogin").hide();
                }
            }
        });
        
    });
    $(function() {
        $('.js-drop').click(function() {
            $('.js-drop .reqCalbck-dropSec').slideDown(300);
            var getElemId = $(this).attr('id');
            var DropDownName = getElemId;
        });
        $('.reqCalbck-dropSec ul li').click(function(event) {
            event.stopPropagation();
            $('.reqCalbck-dropSec ul li').each(function(){
                $(this).removeClass('active');    
            });
            $(this).addClass('active');
            var OptSel = $(this).text();
            var getlistName = $(this).parent().attr('data-attr');
            var b = getlistName.split('-');
            var temp = b[0];
            $('span.' + temp + '-val').text(OptSel);
            $('.js-drop .reqCalbck-dropSec').slideUp(300);
        });
        $('.js-fill').on('keydown', function(e) {
            var s = String.fromCharCode(e.which);
        });
    });
</script>
~/if`
