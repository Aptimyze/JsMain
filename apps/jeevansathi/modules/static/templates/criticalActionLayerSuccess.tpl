<script>

var buttonClicked=0;
    function validateUserName(name){
        if(!name)return false;
        
        var arr=name.split('');
        if(/^[a-zA-Z' .]*$/.test(name) == false)return false;
        return true;
        
    }
    function criticalLayerButtonsAction(clickAction,button) {


                if(buttonClicked)return;    
                buttonClicked=1;
                var layerId= $("#CriticalActionlayerId").val();
                
                    var newNameOfUser='',namePrivacy='';
                    if(layerId==9 && button=='B1')
                    {   
                        
                        newNameOfUser = ($("#nameInpCAL").val()).trim();
                        if(!validateUserName(newNameOfUser))
                        {
                            $("#CALNameErr").show();buttonClicked=0;
                            return;
                        }
                        namePrivacy = $('input[ID="CALPrivacyShow"]').is(':checked') ? 'Y' : 'N';
                        
                      }
                    if(clickAction=="close" || clickAction=='RCB') {
                    var URL="/common/criticalActionLayerTracking";
                    $.ajax({
                        url: URL,
                        type: "POST",
                        data: {"button":button,"layerId":layerId,"namePrivacy":namePrivacy,"newNameOfUser":newNameOfUser},
                    });

                    closeCurrentLayerCommon();
                    if(clickAction=='RCB')
                    {
                        toggleRequestCallBackOverlay(1, 'RCB_CAL');
                        $('.js-dd ul li[value="M"]').trigger('click');
                    }
                
                }
                else {
                window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button; 
                }
                
        }
</script>

~if $layerId != '9'`
<div id='criticalAction-layer' class="layerMidset setshare layersZ pos_fix calwid1 disp-none">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div class="wid470 mauto">
                    <p class="f28">~$titleText`</p>
                    <p class="f14 pt25 lh22">~$contentText`</p>
                </div>            
            </div>
        </div>
        <div class="clearfix">
            ~if $button1Text neq ''`<button id='CALButtonB1'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="cursp bg_pink f18 colrw txtc fontreg lh61 brdr-0 calwid2 fl">~$button1Text`</button>~/if`
            <button id='CALButtonB2'  id='closeButtonCALayer' onclick="criticalLayerButtonsAction('~$action2`','B2');" class="cursp ~if $button1Text eq ''`bg_pink calwid1~else` bg6 calwid2 ~/if` f18 colrw txtc fontreg lh61 brdr-0 fl">~$button2Text`</button>
        </div>
    </div>
~else`
    
<style>
        .modal2 {
            width: 500px;
            height: 430px;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -17%);
            background-color: #fff;
            z-index: 1000099;
        }
        
        .colrGrey {
            color: #848285;
        }
        
        .bordrBtmGrey {
            border-bottom: 1px solid #E2E2E2;
        }
        
        .padWidget {
            padding: 16px 31px;
        }
        
        #nameInpCAL::-webkit-input-placeholder {
            /* Chrome/Opera/Safari */
            color: #DBDBDB;
        }
        
        #nameInpCAL::-moz-placeholder {
            /* Firefox 19+ */
            color: #DBDBDB;
        }
        
        #nameInpCAL:-ms-input-placeholder {
            /* IE 10+ */
            color: #DBDBDB;
        }
        
        #nameInpCAL:-moz-placeholder {
            /* Firefox 18- */
            color: #DBDBDB;
        }
        
        #nameInpCAL {
            border: 1px solid #3F484F
        }
        
        .radOption {
            user-select: none;
            -webkit-user-select: none;
            -webkit-touch-callout: none;
        }
        
        .radOption input {
            opacity: 0;
            position: absolute;
            width: 25px;
            height: 25px;
        }
        
        .radOption i {
            display: inline-block;
            vertical-align: middle;
            width: 25px;
            height: 25px;
            background-image: url(/images/jspc/commonimg/iconSprite.png);
            background-position: -27px -3px;
        }
        
        .radOption input:checked+ i {
            background-image: url(/images/jspc/commonimg/iconSprite.png);
            background-position: -1px -2px;
        }
    </style>    
<div id='criticalAction-layer' class="modal2 fontreg">
                <div class="fontlig" id="changeNameDiv">
                    <div class="f17 color11 fontreg bordrBtmGrey padWidget">Provide Your Name</div>
                    <div class="padWidget bordrBtmGrey">
                         <div class="txtc fontreg colrGrey f17">~$contentText`</div>
                        <div style='margin-top:25px;'>
                         <div class="wid500 txtl color5 f12 disp-none" style="position: absolute;top: 114px;" id="CALNameErr">Please provide  a valid name</div>
                        <input type="text" id="nameInpCAL" class="f15 wid90p pa2" value='~$nameOfUser`' placeholder="Your name here" style="">
                        </div>
                        <div class="f13 colrGrey mt5 txtc">This field will be screened</div>
                        <div class="radOption f15 color11 mt20">
                            <div class="disp_ib ml30">
                                <input type="radio" id='CALPrivacyShow' name="optionSelect" value="showAll" ~if $namePrivacy neq 'N'`checked=""~/if`><i></i> Show my name to all
                            </div>
                            <div class="disp_ib ml30">
                                <input type="radio" id='CALPrivacyShow2' name="optionSelect" value="dontShow" ~if $namePrivacy eq 'N'`checked=""~/if`><i></i> Don’t show my name
                            </div>
                        </div>
                        <div id='CALPrivacyInfo' class="disp-none f12 mt15 color11 txtc">You will also not be able to see names of other members</div>
                        <button id='CALButtonB2'  onclick="criticalLayerButtonsAction('~$action1`','B1');" class="lh63 f17 fontreg mt20 hlpcl1 cursp fullwid txtc hoverPink">~$button1Text`</button>
                    </div>
                    <div class="padWidget f13 colrGrey txtc">We will NEVER show your name to other users without your explicit consent </div>
                </div>
            </div>    
                    <script type="text/javascript">
                                    $("#CALPrivacyShow").change(function(){if($(this).is(':checked'))$("#CALPrivacyInfo").hide();});
                                    $("#CALPrivacyShow2").change(function(){if($(this).is(':checked'))$("#CALPrivacyInfo").show();});
                        
                    </script>
                        
~/if`
<input type="hidden" id="CriticalActionlayerId" value="~$layerId`">
