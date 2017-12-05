<script type="text/javascript">
  ~if $logoutCase eq '1'`
  url = window.location.href.replace(window.location.pathname,"/api/v3/membership/membershipDetails");
  console.log(url);
  $.ajax({
    type: 'POST',
    url: url,
    success:function(response){
      CommonErrorHandling(response);
    }
  });
~/if`
</script>
~if $logoutCase neq '1'`
<meta name="format-detection" content="telephone=no">
<div class="fullwid posrel" id="DivOuter" style="overflow:hidden">
    <form name="form1" id="cashChequeForm" action="~sfConfig::get('app_site_url')`/api/v3/membership/membershipDetails">
        <input type="hidden" name="displayPage" value="7">
        <!--start:overlay-->
        <div class="tapoverlay posabs" style="display:none;" id="tapOverlayHead"></div>
        <!--end:overlay-->
        <!--start:content overlay-->
        <div class="posabs rv2_pos5" style="display:none;" id="tapOverlayContent">
            <div class="posrel bg4"  id="ContLayer">
                <!--start:top div-->
                <div class="bg1" id="ContHead">
                    <div class="rv2_pad1 txtc">
                        <div class="posrel white">
                            <div class="f19 fontthin" id="overlayHeadingCity" style="display:none">~$data.overlay_city_title` </div>
                            <div class="f19 fontthin" id="overlayHeadingDate" style="display:none">~$data.overlay_date_title`</div>
                            <div class="posabs rv2_pos2"><i class="mainsp arow2 selectedContBtn"></i></div>
                            <div class="posabs rv2_pos3"><i class="rv2_sprtie1"></i></div>
                        </div>
                    </div>
                </div>
                <!--end:top div-->
                <!--start:middle part-->
                <div id="ContMid" style="overflow:auto">
                    <!--start:content-->
                    <div class="rv2_pad8" id="ContentDiv">
                        <!--start:option city -->
                        <div id="cityDropdown" style="display:none">
                            ~foreach from=$data.options[3].input_data item=ii key=kk`
                            <div class="pt15">
                                <div class="rv2_brdr1 color8 rv2_brrad1 fontlig padd22 selectedOption" cityId=~$ii.id` selectedCityLabel="~$ii.name`" onclick="addCashChequePickupValue(this);">
                                    <div class="disptbl fullwid" >
                                        <div class="dispcell vertmid pname">~$ii.name`</div>
                                        <div class="dispcell vertmid rv2_wid9">
                                            <div class="rv2_sprtie1 options">
                                                <div class="rv2_sprtie1 options"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ~/foreach`
                        </div>
                        <!--end:option city-->
                        <!--start:option date -->
                        <div id="dateDropdown" style="display:none">
                            ~foreach from=$data.options[5].input_data item=ii key=kk`
                            <div class="pt15">
                                <div class="rv2_brdr1 color8 rv2_brrad1 fontlig padd22 selectedOption" dateId=~$ii.id` selectedDateLabel="~$ii.name`" onclick="addCashChequePickupValue(this);">
                                    <div class="disptbl fullwid" >
                                        <div class="dispcell vertmid pname">~$ii.name`</div>
                                        <div class="dispcell vertmid rv2_wid9">
                                            <div class="rv2_sprtie1 options">
                                                <div class="rv2_sprtie1 options"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ~/foreach`
                        </div>
                        <!--end:option date-->
                    </div>
                    <!--end:content-->
                </div>
                <!--end:middle part-->
                <!--start:button-->
                <div class="posabs btmo fullwid">
                    <div class="fullwid bg7 txtc white f16 rv2_pad9 selectedContBtn" id="ContBtn">~$data.overlay_proceed_text`</div>
                </div>
                <!--end:button-->
            </div>
        </div>
        <!--end:content overlay-->
        <!--start:header-->
        <div class="bg1">
            <div class="rv2_pad1 txtc">
                <div class="posrel white">
                    <div class="f19 fontthin">~$data.payment_title`</div>
                    <div class="posabs rv2_pos2"><i id="pageBack" class="mainsp arow2 cursp"></i></div>
                </div>
            </div>
        </div>
        <!--end:header-->
        <!--start:main body-->
        <!--start:div-->
        <div class="rv2_brdrbtm2 rv2_bg2 txtc rv2_colr2 f14 rv2_lh70">
            ~$data.topBlockMessage`
        </div>
        <!--end:div-->
        <!--start:div-->
        <div class="bg4">
            <!--start: form field -->
            ~foreach from=$data.options key=k item=op`
            <div class="rv2_brdrbtm2 pad18">
                <div class="disptbl fullwid" ~if $op.name eq 'City'` id="openCityOptionsLayer" ~else if $op.name eq 'Preferred Date'` id="openDateOptionsLayer" ~/if`>
                    <div class="dispcell fontlig">
                        <div class="f14 color8" id="~$op.id`_error">~$op.name`</div>
                        <div class="f19 rv2_colr3 pt10">
                            ~if $op.name eq 'City'`
                            <input type="hidden" id="~$op.id`" />
                            <input type="hidden" id="~$op.id`Label" />
                            <div id="cityLabelId" class="f19">~$op.hint_text`</div>
                            ~else if $op.name eq 'Preferred Date'`
                            <input type="hidden" id="~$op.id`" />
                            <input type="hidden" id="~$op.id`Label" />
                            <div id="dateLabelId" class="f19">~$op.hint_text`</div>
                            ~else`
                            <input class="f19" type="text" name="~$op.id`" id="~$op.id`" placeholder="~$op.hint_text`" value="">
                            ~/if`
                        </div>
                    </div>
                    <div class="dispcell vertmid rv2_wid10">
                        ~if $op.name eq 'City' || $op.name eq 'Preferred Date'`
                        <div class="rv2_sprtie1 rv2_arow3"></div>
                        ~/if`
                    </div>
                </div>
            </div>
            ~/foreach`
            <!--end: form field-->
            <!--start:div-->
            <div class="rv2_bg2 txtc ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` f16 fontlig rv2_lh70">
                ~$data.bottom_text`
            </div>
            <!--end:div-->
        </div>
        <!--end:div-->
        <!--start:main body-->
        <!--start:continue button-->
        <div style="overflow:hidden;position: fixed;height: 61px;" class="fullwid disp_b btmo">
        <div class="fullwid ~if $data.device eq 'Android_app'`~$data.device`_bg7~else`bg7~/if` txtc white f16 rv2_pad9 pinkRipple cursp" id="submitPickupForm">~$data.proceed_text`</div>
        </div>
        <!--end:continue button-->
        <input type="hidden" name="mainMembership" value="~$mainMembership`">
    </form>
</div>
<script type="text/javascript">
  var AndroidPromotion = 0;
  $("#pageBack").click(function(e){
    window.history.back();
  });
  $("#cityLabelId").html('Not filled in');
  $("#dateLabelId").html('Not filled in');
  var cityLabel =$("#cityLabel").val();
  var dateLabel =$("#dateLabel").val();
  if(cityLabel){
        $("#cityLabelId").html(cityLabel);
  }
  if(dateLabel){
        $("#dateLabelId").html(dateLabel);
  }
</script>
~/if`
