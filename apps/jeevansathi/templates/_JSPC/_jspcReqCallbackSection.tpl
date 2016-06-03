~assign var=module value= $sf_request->getParameter('module')`
~if $subsection eq 'header'`
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
                <div class="pt20">
                    <div class="reqCalbck-bdr12 colr2 f17 pb5 pt10 cursp pos-rel js-drop" id="headerDatefld"> <span class="headerDatefld-val f15 fontlig js-fill">~if $module neq 'membership'`What type of query do you have ?~else`Questions regarding Jeevansathi Membership Plans~/if`</span>
                        <div class="pos-abs reqCalbck-leftcorner_trianle1 reqCalbck-pos6 z2"></div>
                        <div class="pos-abs fullwid reqCalbck-pos7">
                            <div id="reqCalbck-content-1" class="reqCalbck-content z1 reqCalbck-drop1 headerDatefld-drop disp-none" >
                                <ul data-attr="headerDatefld-list">
                                    <li selectedid="M" class="fontlig f14 ~if $module eq 'membership'`active~/if`">Questions regarding Jeevansathi Membership Plans</li>
                                    <li selectedid="P" class="fontlig f14">Questions or feedback regarding Jeevansathi Profile</li>
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
    var loginData = new Array();
    
    ~assign var=loggedIn value= $sf_request->getAttribute('login')`
    ~if $loggedIn`
        ~assign var=loginData value= $sf_request->getAttribute('loginData')`
    ~/if`
    
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

        $("#headerSubmitCallbackRequest").click(function(e){
            var phNo = $("#headerReqMob input").val();
            var email = $("#headerReqEmail input").val().toLowerCase();
            var selectedid = $("#headerDatefld ul li.active").attr('selectedid');
            if((regExIndian.test(phNo) || regExInternational.test(phNo) || regExIndianLandline.test(phNo)) && regExEmail.test(email) && selectedid != 'Q') {
                if(selectedid == 'M') {
                    $.post("/membership/addCallBck",{'phNo':phNo.trim(),'email':email.trim(),'jsSelectd':'P3','execCallbackType':'JS_ALL','tabVal':1,'device':'desktop'},function(response){
                        $("#headerReqCallBackMessage").text(response);
                        $("#headerRequestCallbackLogout").hide();
                        $("#headerRequestCallbackLogin").show();
                        $("#headerReqQueryError,#headerReqEmailError,#headerReqMobError").hide();
                    });
                } else {
                    $.post("/common/requestCallBack",{'email':email.trim(),'phone':phNo.trim(),'query_type':'P'},function(response){
                        if(response == "Y") {
                            $("#headerReqCallBackMessage").text('We shall call you at the earliest');
                        } else {
                            $("#headerReqCallBackMessage").text('Something Went Wrong...\nPlease try again !');
                        }
                        $("#headerRequestCallbackLogout").hide();
                        $("#headerRequestCallbackLogin").show();
                        $("#headerReqQueryError,#headerReqEmailError,#headerReqMobError").hide();
                    });
                }
            } else {
                if(!regExIndian.test(phNo) && !regExInternational.test(phNo) && !regExIndianLandline.test(phNo)) {
                    $("#headerReqMobError").show();
                }
                if(!regExEmail.test(email)){
                    $("#headerReqEmailError").show();
                }
                if(selectedid == "Q"){
                    $("#headerReqQueryError").show();
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
            $('.js-drop .reqCalbck-drop1').slideUp(300);
            var getElemId = $(this).attr('id');
            var DropDownName = getElemId;
            $('.' + DropDownName + '-drop').slideToggle(300);
        });
        $('.reqCalbck-drop1 ul li').click(function(event) {
            event.stopPropagation();
            var OptSel = $(this).text(),getlistName = $(this).parent().attr('data-attr');
            var b = getlistName.split('-');
            var temp = b[0];
            $('span.' + temp + '-val').text(OptSel);
            $('.js-drop .reqCalbck-drop1').slideUp(300);
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
                <div class="pt20">
                    <div class="reqCalbck-bdr12 colr2 f17 pb5 pt10 cursp pos-rel js-drop" id="footerDatefld"> <span class="footerDatefld-val f15 fontlig js-fill">~if $module neq 'membership'`What type of query do you have ?~else`Questions regarding Jeevansathi Membership Plans~/if`</span>
                        <div class="pos-abs reqCalbck-leftcorner_trianle1 reqCalbck-pos6 z2"></div>
                        <div class="pos-abs fullwid reqCalbck-pos7">
                            <div id="reqCalbck-content-1" class="reqCalbck-content z1 reqCalbck-drop1 footerDatefld-drop disp-none" >
                                <ul data-attr="footerDatefld-list">
                                    <li selectedid="M" class="fontlig f14 ~if $module eq 'membership'`active~/if`">Questions regarding Jeevansathi Membership Plans</li>                                    
                                    <li selectedid="P" class="fontlig f14">Questions or feedback regarding Jeevansathi Profile</li>
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
    var loginData = new Array();
    
    ~assign var=loggedIn value= $sf_request->getAttribute('login')`
    ~if $loggedIn`
        ~assign var=loginData value= $sf_request->getAttribute('loginData')`
    ~/if`
    
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

        $("#footerSubmitCallbackRequest").click(function(e){
            var phNo = $("#footerReqMob input").val();
            var email = $("#footerReqEmail input").val().toLowerCase();
            var selectedid = $("#footerDatefld ul li.active").attr('selectedid');
            if((regExIndian.test(phNo) || regExInternational.test(phNo) || regExIndianLandline.test(phNo)) && regExEmail.test(email) && selectedid != 'Q') {
                if(selectedid == 'M') {
                    $.post("/membership/addCallBck",{'phNo':phNo.trim(),'email':email.trim(),'jsSelectd':'P3','execCallbackType':'JS_ALL','tabVal':1,'device':'desktop'},function(response){
                        $("#footerReqCallBackMessage").text(response);
                        $("#footerRequestCallbackLogout").hide();
                        $("#footerRequestCallbackLogin").show();
                        $("#footerReqQueryError").hide();
                        $("#footerReqEmailError").hide();
                        $("#footerReqMobError").hide();
                    });
                } else {
                    $.post("/common/requestCallBack",{'email':email.trim(),'phone':phNo.trim(),'query_type':'P'},function(response){
                        if(response == "Y") {
                            $("#footerReqCallBackMessage").text('We shall call you at the earliest');
                        } else {
                            $("#footerReqCallBackMessage").text('Something Went Wrong...\nPlease try again !');
                        }
                        $("#footerRequestCallbackLogout").hide();
                        $("#footerRequestCallbackLogin").show();
                        $("#footerReqQueryError,#footerReqEmailError,#footerReqMobError").hide();
                    });
                }
            } else {
                if(!regExIndian.test(phNo) && !regExInternational.test(phNo) && !regExIndianLandline.test(phNo)) {
                    $("#footerReqMobError").show();
                }
                if(!regExEmail.test(email)){
                    $("#footerReqEmailError").show();
                }
                if(selectedid == "Q"){
                    $("#footerReqQueryError").show();
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
        $("#footerRequestCallbackLogout").css('top','-295px');
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
            $('.js-drop .reqCalbck-drop1').slideDown(300);
            var getElemId = $(this).attr('id');
            var DropDownName = getElemId;
        });
        $('.reqCalbck-drop1 ul li').click(function(event) {
            event.stopPropagation();
            $(this).addClass('active');
            var OptSel = $(this).text();
            var getlistName = $(this).parent().attr('data-attr');
            var b = getlistName.split('-');
            var temp = b[0];
            $('span.' + temp + '-val').text(OptSel);
            $('.js-drop .reqCalbck-drop1').slideUp(300);
        });
        $('.js-fill').on('keydown', function(e) {
            var s = String.fromCharCode(e.which);
        });
    });
</script>
~/if`
