~assign var=dropDownDayArr value= CommonFunction::getRCBDayDropDown()`
~assign var=dropDownTimeArr1 value= CommonFunction::getRCBStartTimeDropDown()`
~assign var=dropDownTimeArr2 value= CommonFunction::getRCBEndTimeDropDown()`
<!--start:overlay-->
<div class="js-requestCallBackOverlay dspN fontlig">
    <div class="overlay"></div>
    <div class="modal fontlig">
        <!--start:close button-->
        <div class="posabs Widgicon Widicon4 js-requestCallBackClose"></div>
        <!--end:close button-->
        <div class="formpadt2 fontlig" id="requestForm">
            <form id="Widget" action="javascript:void(0)" novalidate>
                <div class="fontlig f15 txtc pb18">We will call you at the earliest after you submit the request</div>
                <!--start:name-->
                <div class="Widgetbox1 fontlig">
                    <input class="fontlig" type="email" tabindex="1" name="email" id="rq_email" placeholder="Email" value="~$defaultEmail`" />
                </div>
                <span id="emailError" class="errorPad dspN fontlig"> </span>
                <!--end:name-->
                <!--start:mobile no-->
                <div class="formpadt1">
                    <div class="Widgetbox1 fontlig">
                        <input class="fontlig" type="tel" tabindex="2" name="phone" id="rq_phone" placeholder="Mobile number" value="~$defaultPhone`" />
                    </div>
                </div>
                <span id="phoneError" class="errorPad dspN fontlig"> </span>
                <!--end:mobile no-->
                <div id="rcbSideMenuDrop" class="rcbfield rcb_pt17 color2 fontlig clearfix reqCalbck-bdr12 pb15 pl3">
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
                    <input id="rcbSideMenudropDown0" type="hidden" name="dropDownDaySelected" value=""/>
                    <input id="rcbSideMenudropDown1" type="hidden" name="dropDownTimeStartSelected" value=""/>
                    <input id="rcbSideMenudropDown2" type="hidden" name="dropDownTimeEndSelected" value=""/>
                </div>
                <div id="sideMenuReqTimeError" style="color:red;display:none" class="f14 pt8">Please select valid Time Duration</div>
                <!--start:product-->
                <div class="formpadt1 fontlig">
                    <dl id="sample4" class="dropdown">
                        <dt class="js-dt">
                        <span  id="typeOfQuery" tabindex="3" >What type of query do you have?</span>
                        <div class="posabs pos1 sideicon"></div>
                        </dt>
                        <dd class="js-dd">
                        <ul>
                            <li value="P">Questions or feedback regarding jeevansathi profile</li>
                            <li value="M">Query regarding jeevansathi membership plans</li>
                        </ul>
                        </dd>
                    </dl>
                    <input id="rq_query" type="hidden" name="query_type"/>
                </div>
                <span id="querryError" class="errorPad dspN"> </span>
                <!--end:product-->
                <!--start:button-->
                <div class="formpadt1">
                    <div style="overflow: hidden;position: relative;">
                        <button type="submit" id="sidebarReqCallbackBtn" tabindex="4" class="cursp pinkRipple hoverPink bg_pink">Submit Request</button>
                    </div>
                </div>
                <!--end:button-->
            </form>
        </div>
        <div id="requestLoader" class="formpadt3 dspN">
            <img src="~sfConfig::get('app_img_url')`/images/colorbox/loader_big.gif"> </img>
        </div>
        <div id="requestSuccessMsg" class="formpadt4 dspN"> </div>
    </div>
</div>
<!--end:overlay-->
<!--start:helpwidget-->
<div id="js-helpWidget" class="dspN pos_fix hlpwhite fontreg hlppos1 wid200" style="right: -171px;">
    <div class="pos-rel clearfix">
        <div class="Widposabs hlpcl1 js-helpCollapses l0">
            <div class="vertical-text f12">HELP</div>
        </div>
        <div class="fr js-helpWidgetContent" style="width:171px">
            <div class="clearfix padalls wid80p brdrb-8 pt20 pb20"> <i class="sprite2 helpic1 fl"></i>
                <div class="fl color11 f14 pl10">~$mobileNumber`</div>
            </div>
            <div class="clearfix padalls wid80p brdrb-8 pt20 pb20 js-openRequestCallBack"> <a href="#"><i class="sprite2 helpic2 fl"></i>
                <div class="fl color11 f14 pl10">Request callback</div>
            </a> </div>
            <div class="clearfix optwidg f14"> <a href="/contactus/index?fromSideLink=1"> <i class="Widgicon Widicon3 fl"></i>
                <div class="fl pl10 f14">Live Help</div>
            </a> </div>
        </div>
    </div>
</div>
<!--end:helpwidget-->
<script> var showExpandMode = "~$showExpandMode`";
var hideHelpMenu = "~$hideHelpMenu`";

if(typeof(hideHelpMenu)=="undefined"){
hideHelpMenu = "false";
}
if(hideHelpMenu == "true"){
$("#js-helpWidget").addClass('disp-none');
}
</script>