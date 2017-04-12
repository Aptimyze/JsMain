~assign var=loggedIn value= $sf_request->getAttribute('login')`
~assign var=currency value= $sf_request->getAttribute('currency')`
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>JS Exclusive</title>
        <link rel="stylesheet" async=true type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
    </head>
    <body>
        <!--start:top container-->
        <div class="fullwid rcb_bg1">
            <div class="container rcb_pada rcb_colr1">
                <div class="rcb_fl wid45p f24 fontlig rcb_pt13">
                    <!--start:logo-->
                    <div class="rcb_fl">
                        <a href="~if $loggedIn`/myjs/jspcPerform~else`/~/if`">
                            <img src="/images/membership-img/logo.png" alt="jeevansathi"/>
                        </a>
                    </div>
                    <div class="rcb_fl rcb_padb">|</div>
                    <div class="rcb_fl">JS Exclusive</div>
                    <!--end:logo-->
                </div>
                <div class="rcb_fr wid49p f18 fontlig">
                    <div class="rcb_fl rcb_pt17">Call Advisor : ~if $currency eq 'RS'`1800-3010-6299~else`+911204393500~/if`</div>
                    <div class="rcb_fl rcb_padj">or</div>
                    <!--start:call back btn-->
                    <div class="rcb_fl">
                        <div class="rcb_bg2" id="request">
                            <div class="rcb_padc f16 rcb_colr1 rcb_cursp"> Request a call back </div>
                        </div>
                    </div>
                    <!--end:call back btn-->
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <!--end:top container-->
        <!--start:image-->
        <div class="rcb_mainimg">
            <div class="container rcb_colr1 fontlig" style="height:600px;">
                <div class="wid45p">
                    <div class="rcb_pt17 f18"><span class="f61">A</span>mongst all of life's biggest pleasures, being with a</div>
                    <div class="rcb_mt11n"> perfect soulmate is most precious.So can be the process of
                    discovering one.</div>
                    <div class="rcb_pt10 f18"> Let's take you through this amazing journey of finding your perfect partner! </div>
                </div>
            </div>
        </div>
        <!--end:image-->
        <!--start:how it work-->
        <div class="rcb_bg3 fullwid">
            <div class="container rcb_pt45 fontlig">
                <div class="txtc rcb_colr2 f28">How it works</div>
                <div class="rcb_pade">
                    <!--start:row-->
                    <div>
                        <!--start:div-->
                        <div class="txtc wid31p rcb_fl fontlig rcb_color3 rcb_padb">
                            <div class="rcb_sprite sprite_dim rcb_pos1 rcb_ml100"></div>
                            <div class="f20 rcb_padk fontreg">Meet Your Advisor</div>
                            <div class="f16">Connect with our highly experienced & <br/>exclusive advisor who works<br/> on your behalf</div>
                        </div>
                        <!--end:div-->
                        <!--start:div-->
                        <div class="txtc wid31p fontlig rcb_color3 rcb_padb rcb_fl">
                            <div class="rcb_sprite sprite_dim rcb_pos2 rcb_ml100"></div>
                            <div class="f20 rcb_padk fontreg">Understand Your Preferences</div>
                            <div class="f16">Your advisor continuously interacts with you to know every little detail of what you <br/>really expect from your life partner</div>
                        </div>
                        <!--end:div-->
                        <!--start:div-->
                        <div class="txtc wid31p rcb_fl fontlig rcb_color3 rcb_padb">
                            <div class="rcb_sprite sprite_dim rcb_pos3 rcb_ml100"></div>
                            <div class="f20 rcb_padk fontreg">Handpick Profiles for You</div>
                            <div class="f16">Then utilizes our intelligent match making systems and expertise to shortlist<br/> potential matches for you</div>
                        </div>
                        <!--end:div-->
                    </div>
                    <!--end:row-->
                    <div class="clear"></div>
                    <!--start:row-->
                    <div class="rcb_pt45">
                        <div class="rcb_fl" style="width:150px; height:100px"></div>
                        <!--start:div-->
                        <div class="txtc wid31p rcb_fl fontlig rcb_color3 rcb_padb">
                            <div class="rcb_sprite sprite_dim rcb_pos4 rcb_ml100"></div>
                            <div class="f20 rcb_padk fontreg">Know Your View</div>
                            <div class="f16">Periodically connects with you to share those matches and take your consent to choose the most suitable ones</div>
                        </div>
                        <!--end:div-->
                        <!--start:div-->
                        <div class="txtc wid31p fontlig rcb_color3 rcb_fl" style="padding-left:60px">
                            <div class="rcb_sprite sprite_dim rcb_pos5 rcb_ml100"></div>
                            <div class="f20 rcb_padk fontreg">Talks for you</div>
                            <div class="f16">Introduces you to the chosen matches & arranges meetings on your behalf with the family /
                                prospects at your convenience till you
                            find your soulmate </div>
                        </div>
                        <!--end:div-->
                        <div class="clear"></div>
                    </div>
                    <!--end:row-->
                </div>
            </div>
            <!--end:div-->
            <div class="clear"></div>
        </div>
        <!--end:how it work-->
        <!--start:success stories-->
        <div class="rcb_bg4 fullwid">
            <div class="container fontlig rcb_pade">
                <div class="txtc rcb_colr1 f28">Success Stories</div>
                <!--start:story-->
                <div class="rcb_padh">
                    <div class="rcb_fl"> <img src="/images/membership-img/successStory1.jpg" style="width:200px;height:200px"/> </div>
                    <div class="rcb_fl rcb_pl25" style="width:600px;">
                        <div class="rcb_fl">
                            <div class="rcb_sprite quote_str"></div>
                        </div>
                        <div class="rcb_fl rcb_colr1 f16 txtstyle"><span class="rcb_opa60">Thank you Ms. Sandhya for helping us find a match for our daughter. We are happy with the personal and confidential services you provided while fixing a match for our daughter. You arranged a match for her in just 70 days of membership. Thanks again to JS Exclusive team.</span><span class="rcb_endqoute"></span></div>
                        <div class="clear"></div>
                        <div class="f16 rcb_colr1 rcb_opa60 rcb_padi">Lavesh &amp; Shruti</div>
                    </div>
                    <div class="clear"></div>
                </div>
                <!--end:story-->
            </div>
        </div>
        <!--end:success stories-->
        <!--start:success stories-->
        <div class="rcb_bg4 fullwid">
            <div class="container fontlig rcb_pade">
                <!-- <div class="txtc rcb_colr1 f28">Success Stories</div> -->
                <!--start:story-->
                <div class="rcb_padh">
                    <div class="rcb_fl"> <img src="/images/membership-img/successStory2.jpg" style="width:200px;height:200px"/> </div>
                    <div class="rcb_fl rcb_pl25" style="width:600px;">
                        <div class="rcb_fl">
                            <div class="rcb_sprite quote_str"></div>
                        </div>
                        <div class="rcb_fl rcb_colr1 f16 txtstyle"><span class="rcb_opa60">Alka was very encouraging and supportive as a relationship manager, who spared no efforts in forwarding so many profiles, and following up with the parties. Thanks to her efforts, our son's match has been fixed. We would like to thank her and Jeevansathi for all the help and support.</span><span class="rcb_endqoute"></span></div>
                        <div class="clear"></div>
                        <div class="f16 rcb_colr1 rcb_opa60 rcb_padi">Jagdish &amp; Disha</div>
                    </div>
                    <div class="clear"></div>
                </div>
                <!--end:story-->
            </div>
        </div>
        <!--end:success stories-->
        <!--start:buy JSex-->
        <div class="rcb_bg3 fullwid">
            <div class="container_1 rcb_padf fontlig">
                <div class="txtc rcb_colr2 f28">Buy JS Exclusive ~if $discountText`<span style="color:#000;">~$discountText`~/if`</span></div>
                <div class="rcb_pt35">
                    <!--start:div-->
                    ~foreach from=$data.serviceContent key=k item=v name=servicesLoop`
                    ~if $v.subscription_id eq 'X'`
                    ~foreach from=$v.durations key=kd item=vd name=servDurationsLoop`
                    <div id="~$v.subscription_id`~$vd.duration`" class="mem_select rcb_fl rcb_bg2 txtc rcb_colr1 rcb_padg wid302 rcb_ml2 rcb_cursp" memDur="~$vd.duration`" style="margin-top:1px;">~$vd.duration` months for ~$data.currency` ~if $vd.price`<span class="strike">~$vd.price_strike`</span>~/if` ~$vd.price` </div>
                    ~/foreach`
                    ~/if`
                    ~/foreach`
                    <div class="clear"></div>
                    <!--start:button-->
                    <div class="txtc rcb_pt45">
                        <div style="overflow:hidden;position: relative;" class="rcb_btn">
                            ~if $loggedIn`
                            <div id="buyNowBtn" class="rcb_bg1 rcb_btn f16 fontlig rcb_pada rcb_cursp rcb_colr1 fontreg pinkRipple hoverPink"> Buy Now </div>
                            ~else`
                            <div id="loginJsExclusive" class="loginLayerJspc rcb_bg1 rcb_btn f16 fontlig rcb_pada rcb_cursp rcb_colr1 pinkRipple hoverPink"> Login To Continue </div>
                            ~/if`
                        </div>
                    </div>
                    <!--end:button-->
                </div>
            </div>
        </div>
        <!--end:buy JSex-->
        <!--start:request call back-->
        <form id="placeRequestForm" name="placeRequestForm" action="/membership/jsexclusiveDetail" method="post" target="_top" >
            <div class="rcb_bg4 fullwid">
                <div class="container rcb_padd rcb_colr1 fontlig">
                    <div class="txtc">
                        <div class="f28">Request a call back</div>
                        <div class="f16 rcb_opa60 rcb_lh40">If you want to know more about JS Exclusive please provide details and we will get back to you</div>
                    </div>
                    <!--start: form -->
                    ~if $success eq 1`
                    <div id="successMsg" class="rcb_pt25 f16 rcb_opa60 txtc">Our Advisor will get back to you as per the date and time you have specified
                    </div>
                    ~else`
                    <div id="timeError" style="color:red;display:none" class="f14 mt26 txtc">Please select valid Time Duration</div>
                    <div class="rcbfield rcb_pt40">
                        <!--start:date-->
                        <div class="rcb_fl rcb_pl120">
                            <div class="f16 rcb_opa60 rcb_lh30">Date</div>
                            <div>
                                <div class="rcb_fl">
                                    <div class="wid199">
                                        <!--start:drop down UI-->
                                        <dl id="dropDown0" class="dropdown">
                                            <dt><span></span></dt>
                                            <dd>
                                            <ul>
                                                ~foreach from=$dropDownDayArr key=k item=dd`
                                                <li id="~$k`">~$dd`</li>
                                                ~/foreach`
                                            </ul>
                                            </dd>
                                        </dl>
                                        <!--end:drop down UI-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end:date-->
                        <!--start:time-->
                        <div class="rcb_fl rcb_pl25">
                            <div class="f16 rcb_opa60 rcb_lh30">Time</div>
                            <div>
                                <div class="rcb_fl">
                                    <div class="wid96 rcb_fl">
                                        <dl id="dropDown1" class="dropdown">
                                            <dt><span></span></dt>
                                            <dd>
                                            <ul>
                                                ~foreach from=$dropDownTimeArr1 key=k item=tt`
                                                <li id="~$k`">~$tt`</li>
                                                ~/foreach`
                                            </ul>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="wid96 rcb_fl rcb_m2">  <dl id="dropDown2" class="dropdown">
                                        <dt><span></span></dt>
                                        <dd>
                                        <ul>
                                            ~foreach from=$dropDownTimeArr2 key=k item=tt`
                                            <li id="~$k`">~$tt`</li>
                                            ~/foreach`
                                        </ul>
                                        </dd>
                                    </dl> </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <!--end:time-->
                        <!--start:mobile-->
                        <div class="rcb_fl rcb_pl25">
                            <div class="f16 rcb_opa60 rcb_lh30">Mobile</div>
                            <div>
                                <div class="rcb_fl">
                                    <input type="text" value="+91" disabled class="hgt40 rcb_colr1 rcb_padb wid25" style="font-size:16px"/>
                                    <input id="mobileInput" type="text" name="mobNumber" value="~$mobNumber`" class="hgt40 rcb_colr1 rcb_padb wid199 rcb_padl" style="font-size:16px"/>
                                </div>
                            </div>
                        </div>
                        <!--end:mobile-->
                        <div class="clear"></div>
                        <!--start:button-->
                        <div class="txtc rcb_pt45">
                            <div style="overflow:hidden;position: relative;" class="rcb_btn">
                                <div id="placeRequestBtn" class="rcb_bg1 rcb_btn f16 fontlig rcb_pada rcb_cursp pinkRipple hoverPink"> Place Request </div>
                            </div>
                        </div>
                        ~/if`
                        <!--end:button-->
                    </div>
                    <input type="hidden" name="callRequest" value="1"/>
                    <input id="ddropDown0" type="hidden" name="dropDownDaySelected" value=""/>
                    <input id="ddropDown1" type="hidden" name="dropDownTimeStartSelected" value=""/>
                    <input id="ddropDown2" type="hidden" name="dropDownTimeEndSelected" value=""/>
                    <input id="jsSelectd" type="hidden" name="jsSelectd" value="X"/>
                </form>
                <!--end: form -->
            </div>
        </div>
        <!--end:request call back-->
    </body>
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript">
        function checkEmptyOrNull(item) {
            if (item != undefined && item != null && item != "") {
                return true;
            } else {
                return false;
            }
        }
        $(document).ready(function () {
            var profileid = "~$profileid`";
            eraseCookie('selectedVas');
            eraseCookie('mainMemDur');
            eraseCookie('mainMem');
            eraseCookie('paymentMode');
            eraseCookie('cardType');
            eraseCookie('couponID');

            function getValFLi() {
                var getdata = $('.dropdown dd ul').find('li:first').map(function () {
                    return $(this).text();
                }).get();
                return getdata;
            }
            $("dt").click(function () {
                var N_id = $(this).parent().attr('id');
                $("dd ul").css('display', 'none');
                $("#" + N_id + " dd ul").toggle();
            });
            $("dd ul li").click(function () {
                var text = $(this).html();
                var text1 = $(this).text();
                var P_id = $(this).parent().parent().parent().attr('id');
                $("#" + P_id + " dt span").html(text);
                $("#" + P_id + " dd ul").css('display', 'none');
                $("#d" + P_id + "").val($(this).attr('id'));
                var date = $("#ddropDown0").val(),
                    startTime = $("#ddropDown1").val(),
                    endTime = $("#ddropDown2").val();
                var t1 = Date.parse(date + " " + startTime),
                    t2 = Date.parse(date + " " + endTime),
                    now = Date.parse(new Date());
                if (t2 - t1 <= 0 || t1 < now) {
                    $("#timeError").show();
                } else {
                    $("#timeError").hide();
                }
            });

            function intialize() {
                var value = getValFLi();
                $.each(value, function (i, val) {
                    if (i == 2) {
                        val = "9 PM";
                        $("#ddropDown" + i).val($("#dropDown" + i + " dd ul li:last").attr('id'));
                    } else {
                        $("#ddropDown" + i).val($("#dropDown" + i + " dd ul li:first").attr('id'));
                    }
                    $("#dropDown" + i + " dt span").html(val);
                });
            }
            $(document).bind('click', function (e) {
                var $clicked = $(e.target);
                if (!$clicked.parents().hasClass("dropdown")) {
                    $(".dropdown dd ul").hide();
                }
            });
            intialize();
            var selectedService = $(".mem_select:first").attr('id');
            if (checkEmptyOrNull(selectedService)) {
                $("#jsSelectd").val(selectedService);
            }
            $(".mem_select:first").removeClass('rcb_bg2').addClass('rcb_bg1');
            $(".mem_select").click(function (e) {
                $(".mem_select").each(function () {
                    if ($(this).hasClass('rcb_bg1')) {
                        $(this).removeClass('rcb_bg1').addClass('rcb_bg2');
                    }
                });
                if ($(this).hasClass('rcb_bg2')) {
                    $(this).removeClass('rcb_bg2').addClass('rcb_bg1');
                } else {
                    $(this).removeClass('rcb_bg1').addClass('rcb_bg2');
                }
                selectedService = $(this).attr('id');
                if (checkEmptyOrNull(selectedService)) {
                    $("#jsSelectd").val(selectedService);
                }
            });
            $("#buyNowBtn").click(function (e) {
                e.preventDefault();
                if (profileid != undefined && profileid != null && profileid != 0) {
                    createCookie('mainMem', 'X');
                    createCookie('mainMemDur', selectedService.replace('X', ''));
                    window.location.href = "~sfConfig::get('app_site_url')`/membership/jspc?displayPage=3&mainMem=X&mainMemDur=" + selectedService.replace('X', '');
                }
            });
            $("#placeRequestBtn").click(function (e) {
                e.preventDefault();
                var intRegex = /^\d+$/;
                var str = $("#mobileInput").val();
                var len = $("#mobileInput").val().length;
                var date = $("#ddropDown0").val(),
                    startTime = $("#ddropDown1").val(),
                    endTime = $("#ddropDown2").val();
                var t1 = Date.parse(date + " " + startTime),
                    t2 = Date.parse(date + " " + endTime),
                    now = Date.parse(new Date()),
                    error1 = false, error2 = false;
                if (t2 - t1 <= 0 || t1 < now) {
                    error1 = true;
                } else {
                    error1 = false;
                }
                if(len == 10 && intRegex.test(str)){
                    error2 = false;
                } else {
                    error2 = true;
                }
                if (!error1 && !error2) {
                    $("#placeRequestForm").submit();
                    $("#timeError").hide();
                    $("#mobileInput").removeClass("errorBorder");
                } else {
                    if (error1) {
                        $("#timeError").show();    
                    }
                    if (error2) {
                        $("#mobileInput").addClass("errorBorder");
                    }
                }
            });
            $('#request').click(function () {
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 1000);
                return false;
            });
            var callRequest = "~$callRequest`";
            if (callRequest) {
                $('html, body').scrollTop($(document).height());
            }
        });
    </script>
</html>
