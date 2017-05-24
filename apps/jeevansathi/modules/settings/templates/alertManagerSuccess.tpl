<body>
    <!--start:header-->
    <div class="cover1">
        <div class="container mainwid pt35 pb48">
            ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
        </div>
    </div>
    <!--end:header-->
    <!--start:middle-->
    <div class="bg-4">
        <div class="container mainwid">
            <!--start:tabbing-->
            <div class="setbg1 fullwid pos-rel">
                <ul class="settingTab clearfix fontlig f15 color11">
                    <li>
                        <div ><a class="color11" href="/settings/jspcSettings?visibility=1">Profile Visibility</a></div>
                    </li>
                    <li>
                        <div>Alert Manager</div>
                    </li>
                    <li>
                        <div><a class="color11" href="/settings/jspcSettings?hideDelete=1">Hide / Delete Profile</a></div>
                    </li>
                    <li>
                        <div><a class="color11" href="/settings/jspcSettings?changePassword=1">Change Password</a></div>
                    </li>
                    <li class="pos_abs hgt2 bg5" style="width:25%; left:25%"></li>
                </ul>
            </div>
            <div class="pt30 pb30 firefinder-match">
                <!--start:content for notification  setting-->
                ~if $alertManagerData.currentSettings`
                <div class="notificationContent">
                    ~foreach from=$alertManagerData.currentSettings key=k item=v name=settingsLoop`
                    ~if $k eq 'mail_alert_section'`
                    <div class="pb30" id="MailAlerts">
                        <p class="txtc fontreg f17 color11">Mail Alerts</p>
                        <p class="txtc pb30 pt30 fontlig f13 color11">You are receiving these Emails on your registered Email Id - ~$alertManagerData.userEmail`</p>
                        <ul class="listnone color11 f15 notsetList1">
                            <!--start:option-->
                            ~foreach from=$v key=kk item=vv name=mailAlertsLoop`
                            ~if $kk eq 'MA' or $kk eq 'VA'`
                            <li ~if $smarty.foreach.mailAlertsLoop.index gt 0` class="mt40" ~/if`>
                                <div class="setp1 clearfix">
                                    <ul class="hor_list clearfix notsetList2">
                                        <li class="setwid7 fontreg disp-tbl">
                                            <div class="disp-cell vmid hgt40">~$vv.0`</div>
                                        </li>
                                        <li class="fontlig setwid2 disp-tbl">
                                            <div class="disp-cell vmid hgt40 color2">~$vv.1`</div>
                                        </li>
                                        ~if $kk eq 'MA'`
                                        <li class="pl30">
                                            <button class="cursp bgnone setbdr1 setwid3 txtc hgt40 vtop ~if $vv.2 eq 'A'`selcted~/if` color11" onclick="updateVal('match_alert', 'A',this); return false;">Daily</button>
                                            <button class="cursp bgnone setbdr1 setwid3 txtc hgt40 vtop ~if $vv.2 eq 'O'`selcted~/if` ml10 color11" onclick="updateVal('match_alert', 'O', this); return false;">3 times/week</button>
                                            <button class="cursp bgnone setbdr1 setwid3 txtc hgt40 vtop ~if $vv.2 eq 'U'`selcted~/if` ml10 color11" onclick="updateVal('match_alert', 'U', this); return false;">Unsubscribe</button>
                                        </li>
                                        ~else`
                                        <li class="pl30">
                                            <button class="cursp bgnone setbdr1 setwid3 txtc hgt40 vtop ~if $vv.2 eq 'D'`selcted~/if` color11" onclick="updateVal('vis_alert', 'D',this); return false;">Daily</button>
                                            <button class="cursp bgnone setbdr1 setwid3 txtc hgt40 vtop ~if $vv.2 eq 'S'`selcted~/if` ml10 color11" onclick="updateVal('vis_alert', 'S',this); return false;">Days I don't Login</button>
                                            <button class="cursp bgnone setbdr1 setwid3 txtc hgt40 vtop ~if $vv.2 eq 'U'`selcted~/if` ml10 color11" onclick="updateVal('vis_alert', 'U',this); return false;">Unsubscribe</button>
                                        </li>
                                        ~/if`
                                    </ul>
                                </div>
                            </li>
                            ~else`
                            <li class="mt40">
                                <div class="setp1 clearfix">
                                    <ul class="hor_list clearfix notsetList2">
                                        <li class="setwid7 fontreg disp-tbl">
                                            <div class="disp-cell vmid hgt50">~$vv.0`</div>
                                        </li>
                                        <li class="fontlig setwid4 disp-tbl">
                                            <div class="disp-cell vmid hgt50 color2">~$vv.1`</div>
                                        </li>
                                        <li class="pl30 setwid6">
                                            <div class="pos-rel fr clearfix outerbox">
                                                <input type="checkbox" name="~$vv.3`" ~if $vv.2 eq 'S'`checked~/if` id="~$vv.3`" class="vishid settingInp" value="1">
                                                <div class="pos-abs fullwid clearfix z2 setpos1">
                                                    <div data-attr="unchk-~$vv.3`" data-val="U" class="fl selNotif cursp setdim1"></div>
                                                    <div data-attr="chk-~$vv.3`" data-val="S" class="fr selNotif cursp setdim1"></div>
                                                </div>
                                                <div class="box pos-abs setpos2"> </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            ~/if`
                            ~/foreach`
                        </ul>
                    </div>
                    ~/if`
                    ~if $k eq 'sms_alert_section'`
                    <div class="pt30 pb30 setbdr3" id="SMSAlerts">
                        <p class="txtc fontreg f17 color11">SMS Alerts</p>
                        <ul class="listnone color11 f15 notsetList1">
                            <!--start:option-->
                            ~foreach from=$v key=kk item=vv name=smsAlertsLoop`
                            <li ~if $smarty.foreach.mailAlertsLoop.index gt 0` class="mt40" ~/if`>
                                <div class="setp1 clearfix">
                                    <ul class="hor_list clearfix notsetList2">
                                        <li class="setwid7 fontreg disp-tbl">
                                            <div class="disp-cell vmid hgt50">~$vv.0`</div>
                                        </li>
                                        <li class="fontlig setwid4 disp-tbl">
                                            <div class="disp-cell vmid hgt50 color2">~$vv.1`</div>
                                        </li>
                                        <li class="pl30 setwid6">
                                            <div class="pos-rel fr clearfix outerbox">
                                                <input type="checkbox" name="~$vv.3`" ~if $vv.2 eq 'S'`checked~/if` id="~$vv.3`" class="vishid settingInp" value="1">
                                                <div class="pos-abs fullwid clearfix z2 setpos1">
                                                    <div data-attr="unchk-~$vv.3`" data-val="U" class="fl selNotif cursp setdim1"></div>
                                                    <div data-attr="chk-~$vv.3`" data-val="S" class="fr selNotif cursp setdim1"></div>
                                                </div>
                                                <div class="box pos-abs setpos2"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            ~/foreach`
                            <!--end:option-->
                        </ul>
                    </div>
                    ~/if`
                    ~if $k eq 'call_alert_section'`
                    <div class="pt30 pb30 setbdr3" id="CallAlerts">
                        <p class="txtc fontreg f17 color11">Call Alerts</p>
                        <ul class="listnone color11 f15 notsetList1">
                            <!--start:option-->
                            ~foreach from=$v key=kk item=vv name=callAlertsLoop`
                            <li ~if $smarty.foreach.mailAlertsLoop.index gt 0` class="mt40" ~/if`>
                                <div class="setp1 clearfix">
                                    <ul class="hor_list clearfix notsetList2">
                                        <li class="setwid7 fontreg disp-tbl">
                                            <div class="disp-cell vmid hgt50">~$vv.0`</div>
                                        </li>
                                        <li class="fontlig setwid4 disp-tbl">
                                            <div class="disp-cell vmid hgt50">~$vv.1`</div>
                                        </li>
                                        <li class="pl30 setwid6">
                                            <div class="pos-rel fr clearfix outerbox">
                                                <input type="checkbox" name="~$vv.3`" ~if $vv.2 eq 'S'`checked~/if` id="~$vv.3`" class="vishid settingInp" value="1">
                                                <div class="pos-abs fullwid clearfix z2 setpos1">
                                                    <div data-attr="unchk-~$vv.3`" data-val="U" class="fl selNotif cursp setdim1"></div>
                                                    <div data-attr="chk-~$vv.3`" data-val="S" class="fr selNotif cursp setdim1"></div>
                                                </div>
                                                <div class="box pos-abs setpos2"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            ~/foreach`
                            <!--end:option-->
                        </ul>
                    </div>
                    ~/if`
                    <!--show notification subscribe/unsubscribe toggle layer if registered for JSPC notifications-->
                    ~if $showNotificationToggleLayer eq 1`
                        ~if $k eq 'notification_alert_section'`
                        <div class="pt30 setbdr3" id="NotificationAlerts">
                            <p class="txtc fontreg f17 color11 pb30">Notification Alerts</p>
                            <ul class="listnone color11 f15 notsetList1">
                                <!--start:option-->
                                ~foreach from=$v key=kk item=vv name=notificationAlertsLoop`
                                <li ~if $smarty.foreach.notificationAlertsLoop.index gt 0` class="mt40" ~/if`>
                                    <div class="setp1 clearfix">
                                        <ul class="hor_list clearfix notsetList2">
                                            <li class="setwid7 fontreg disp-tbl">
                                                <div class="disp-cell vmid hgt50">~$vv.0`</div>
                                            </li>
                                            <li class="fontlig setwid4 disp-tbl">
                                                <div class="disp-cell vmid hgt50">~$vv.1`</div>
                                            </li>
                                            <li class="pl30 setwid6">
                                                <div class="pos-rel fr clearfix outerbox">
                                                    <input type="checkbox" name="~$vv.3`" ~if $vv.2 eq 'S'`checked~/if` id="~$vv.3`" class="vishid settingInp" value="1">
                                                    <div class="pos-abs fullwid clearfix z2 setpos1">
                                                        <div data-attr="unchk-~$vv.3`" data-val="U" class="fl selNotif cursp setdim1"></div>
                                                        <div data-attr="chk-~$vv.3`" data-val="S" class="fr selNotif cursp setdim1"></div>
                                                    </div>
                                                    <div class="box pos-abs setpos2"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                ~/foreach`
                                <!--end:option-->
                            </ul>
                        </div>
                        ~/if`
                    ~/if`
                    
                    ~/foreach`
                </div>
                ~/if`
                <!--end:content for notification  setting-->
            </div>
        </div>
    </div>
    <!--end:middle-->
    <!--start:footer-->
    ~include_partial('global/JSPC/_jspcCommonFooter')`
    <!--end:footer-->
</body>