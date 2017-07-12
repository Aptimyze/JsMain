~assign var=loggedIn value= $sf_request->getAttribute('login')`
~if $loggedIn`
~assign var=loginData value= $sf_request->getAttribute('loginData')`
~/if`
<div>
    <!--start:header-->
    <header>
        <div class="sscover2">
            <div class="container mainwid pt35 pb30">
                <!--start:top horizontal bar-->
                ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
                <!--end:top horizontal bar-->
            </div>
        </div>
    </header>
    <!--end:header-->
    <!--start:middle part-->
    <div id="formContainer" class="container mainwid fontlig color11">
        <div class="pt40 pb5 ssbrd3">
            <div class="f17 "><a href="/successStory/story" class="color11">Back</a></div>
        </div>
        ~if $COMMENTS eq ''`
        <div class="mauto wid80p txtc f28 pt40 pb40">We are delighted to know that you found your match with us!<br>~if $fromMailer neq 'true'`Before we delete your profile~else`Before we wish you a happy married life~/if`, we recommend that you share your success story and get attractive gifts from Jeevansathi</div>
        ~else`
        <div class="mauto wid80p txtc f28 pt40 pb40">Dear ~if $NAME`~$NAME`~else`~$USERNAME`~/if`, thanks for sharing your success story with us. <br>Please upload your wedding photo too to publish the story on Jeevansathi</div>
        ~/if`
        <!--start:form-->
        <form id="submit_ss" name="submit_ss" action="/successStory/submitlayer~if $offerConsent eq 'Y'`?offerConsent=Y~/if`~if $fromMailer eq 'true'`&fromSuccessStoryMailer=true&mailid=~$mailid`~/if`" method="post" enctype="multipart/form-data" target="_self">
            <div class="clearfix ssp6 pb30 ssbrd3">
                <div class="fl f15 pt10">Your story</div>
                <div class="fl pl15 ssfwid6">
                    <div class="ssbrd1 fullwid">
                        ~if $COMMENTS eq ''`
                        <textarea class="wid96p fontlig f15 color11 brdr-0 outw padall-10 hgt110" name="ss_story" placeholder="Tell us how you met on Jeevansathi and what would be your advice for those who are still looking for match on Jeevansathi "></textarea>
                        ~else`
                        <textarea disabled class="wid96p fontlig f15 color11 brdr-0 outw padall-10 hgt110 opa70 bg-white" name="ss_story" value="~$COMMENTS`">~$COMMENTS`</textarea>
                        ~/if`
                    </div>
                    <div class="pt20 clearfix">
                        <input type="file" name="wedding_photo" data-buttonBefore="true" accept=".jpg,.gif">
                    </div>
                </div>
            </div>
            <!--end:form 1-->
            <!--start:form 2-->
            <div class="clearfix pt20">
                <div id="errorMessage" class="fl color5 f13 ssp8"></div>
                <div class="fr color11 f11 ssp7">Fields marked * are mandatory</div>
            </div>
            <div class="clearfix pb30">
                <ul class="hor_list clearfix" id="submit2">
                    <li class="clearfix ">
                        <label>Your ID</label>
                        <input id="userId" type="text" value="~$USERNAME`" disabled class="fontlig opa70 bg-white"/>
                    </li>
                    <li class="clearfix">
                        <label>Spouse ID *</label>
                        ~if $USERNAME_W eq ''`
                        <input id="spouseId" name="spouse_id" type="text" value="" class="fontlig "/>
                        ~else`
                        <input id="spouseId" name="spouse_id" disabled type="text" value="~$USERNAME_W`" class="fontlig opa70 bg-white"/>
                        ~/if`
                    </li>
                    <li class="clearfix">
                        <label>Your Name *</label>
                        ~if $NAME eq ''`
                        <input id="userName" name="spouse1_name" type="text" value="" class="fontlig"/>
                        ~else`
                        ~if $COMMENTS eq ''`
                        <input id="userName" name="spouse1_name" type="text" value="~$NAME`" class="fontlig"/>
                        ~else`
                        <input id="userName" name="spouse1_name" disabled type="text" value="~$NAME`" class="fontlig opa70 bg-white"/>
                        ~/if`
                        ~/if`
                    </li>
                    <li class="clearfix">
                        <label>Spouse Name *</label>
                        ~if $NAME_H eq ''`
                        <input id="spouseName" name="spouse_name" type="text" value="" class="fontlig "/>
                        ~else`
                        <input id="spouseName" name="spouse_name" disabled type="text" value="~$NAME_H`" class="fontlig opa70 bg-white"/>
                        ~/if`
                    </li>
                    <li class="clearfix">
                        <label>Your Email</label>
                        <input id="userEmail" type="text" value="~$EMAIL`" disabled class="fontlig opa70 bg-white"/>
                    </li>
                    <li class="clearfix">
                        <label>Spouse Email *</label>
                        ~if $EMAIL_W eq ''`
                        <input id="spouseEmail" type="text" name="spouse_email" value="" class="fontlig "/>
                        ~else`
                        <input id="spouseEmail" type="text" disabled name="spouse_email" value="~$EMAIL_W`" class="fontlig opa70 bg-white"/>
                        ~/if`
                    </li>
                    <li class="clearfix fullwid">
                        <label>Address *</label>
                        ~if $CONTACT_DETAILS eq ''`
                        <textarea placeholder="We shall send your gift at this address" id="userAddress" name="contact_address" class="fontlig f15 bg-white" value=""></textarea>
                        ~else`
                        <textarea placeholder="We shall send your gift at this address" id="userAddress" disabled name="contact_address" class="fontlig f15 opa70 bg-white" value="~$CONTACT_DETAILS`">~$CONTACT_DETAILS`</textarea>
                        ~/if`
                    </li>
                    <li class="clearfix fullwid">
                        <label>Wedding date</label>
                        <div class="fl ssbrd1 ssfwid2">
                            ~if $WEDDING_DATE eq ''`
                            <ul class="weddate hor_list clearfix">
                                <li class="ssbrd2">
                                    <select id="w_day" class="chosen-select color11 f15 fontlig brdr-0 cup7 textbox" name="w_day">
                                        <option value="1" >1</option>
                                        <option value="2" >2</option>
                                        <option value="3" >3</option>
                                        <option value="4" >4</option>
                                        <option value="5" >5</option>
                                        <option value="6" >6</option>
                                        <option value="7" >7</option>
                                        <option value="8" >8</option>
                                        <option value="9" >9</option>
                                        <option value="10" >10</option>
                                        <option value="11" >11</option>
                                        <option value="12" >12</option>
                                        <option value="13" >13</option>
                                        <option value="14" >14</option>
                                        <option value="15" >15</option>
                                        <option value="16" >16</option>
                                        <option value="17" >17</option>
                                        <option value="18" >18</option>
                                        <option value="19" >19</option>
                                        <option value="20" >20</option>
                                        <option value="21" >21</option>
                                        <option value="22" >22</option>
                                        <option value="23" >23</option>
                                        <option value="24" >24</option>
                                        <option value="25" >25</option>
                                        <option value="26" >26</option>
                                        <option value="27" >27</option>
                                        <option value="28" >28</option>
                                        <option value="29" >29</option>
                                        <option value="30" >30</option>
                                        <option value="31" >31</option>
                                    </select>
                                </li>
                                <li class="ssbrd2">
                                    <select id="w_month" class="chosen-select color11 f15 fontlig brdr-0 cup7 textbox" name="w_month">
                                        <option value="1" >Jan</option>
                                        <option value="2" >Feb</option>
                                        <option value="3" >Mar</option>
                                        <option value="4" >Apr</option>
                                        <option value="5" >May</option>
                                        <option value="6" >Jun</option>
                                        <option value="7" >Jul</option>
                                        <option value="8" >Aug</option>
                                        <option value="9" >Sep</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dec</option>
                                    </select>
                                </li>
                                <li>
                                    <select id="w_year" class="chosen-select color11 f15 fontlig brdr-0 cup7 textbox" name="w_year">
                                        ~foreach from=$dateArray item=values key=kk`
                                        <option value=~$values` ~if $curDate eq $values` selected ~/if`>~$values`</option>
                                        ~/foreach`
                                    </select>
                                </li>
                            </ul>
                            ~else`
                            <ul class="weddate hor_list clearfix">
                                <li disabled class="ssbrd2">
                                    <select id="w_day" class="chosen-select color11 f15 fontlig brdr-0 cup7 textbox" name="w_day">
                                        <option value="~$W_DAY`" >~$W_DAY`</option>   
                                    </select>
                                </li>
                                <li disabled class="ssbrd2">
                                    <select id="w_month" class="chosen-select color11 f15 fontlig brdr-0 cup7 textbox" name="w_month">
                                        <option value="~$W_MONTH`">~$W_MONTH_TEXT`</option>
                                    </select>
                                </li>
                                <li disabled>
                                    <select id="w_year" class="chosen-select color11 f15 fontlig brdr-0 cup7 textbox" name="w_year">
                                        ~foreach from=$dateArray item=values key=kk`
                                        ~if $W_YEAR eq $values`
                                        <option value=~$values` ~if $curDate eq $values` selected ~/if`>~$values`</option>
                                        ~/if`
                                        ~/foreach`
                                    </select>
                                </li>
                            </ul>
                            ~/if`
                        </div>
                    </li>
                    ~if $fromMailer eq 'true'`
                        <li class="clearfix fullwid">
                            <div class="ssfm1" style="overflow:hidden;position: relative;display: inline-block;">
                            <input id="main_button" type="button" class="cursp fontlig ssfwid3 bg_pink colrw brdr-0 pinkRipple hoverPink" value="Submit Story" style="border:none;"></input>
                            </div>
                        </li>
                    ~else`
                    <li class="clearfix fullwid">
                        <div class="ssfm1" style="overflow:hidden;position: relative;display: inline-block;">
                        <input id="main_button" type="button" class="cursp fontlig ssfwid3 bg_pink colrw brdr-0 pinkRipple hoverPink" value="Submit Story & Delete Profile" style="border:none;"></input>
                        </div>
                        ~if $FROM_DELETE_PROFILE`
                        <div class="ssfm1" style="overflow:hidden;position: relative;display: inline-block;">
                        <input id="skip_button" type="button" class="cursp fontlig ssfwid3 bg_pink colrw brdr-0 pinkRipple hoverPink" value="Skip & Delete my Profile" style="border:none;margin-left: 10px;"></input>
                        </div>
                        ~/if`
                    </li>
                    ~/if`
                </ul>
                <input type="hidden" name="checksum" value="~$profileChecksum`">
                <input type="hidden" name="submit_ss_flag" value="1">
                <input type="hidden" name="my_name" ~if $NAME` value="~$NAME`" ~else` value="~$USERNAME`"~/if`>
                <input type="hidden" name="username" value="~$USERNAME`">
                <input type="hidden" name="email" value="~$EMAIL`">
                <input type="hidden" name="profileid" value="~$profileid`">
            </div>
        </form>
    </div>
    <!--end:form-->
    <div id="resultContainer" class="disp-none container mainwid fontlig color11">
        <div class="mauto ssfwid4 f24 txtc sspf9">
            <p>~if $fromMailer neq 'true'`Your profile is deleted &amp; your~else`Your~/if` story will be uploaded on Jeevansathi. 
            You will soon recieve a surprise gift from our side</p>
        </div>
    </div>
    <div id="skipContainer" class="disp-none container mainwid fontlig color11">
        <div class="mauto ssfwid4 f24 txtc sspf9">
            <p>Your profile is deleted, we have sent a link to your email id so that you</p>
            <p>can upload your success story in your free time</p>
        </div>
    </div>
</div>
<!--end:middle part-->
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->
<script type="text/javascript">

    function validateFields(useCase){
        var userId = $.trim($("#userId").val());
        var userName = $.trim($("#userName").val());
        var userEmail = $.trim($("#userEmail").val());
        var spouseId = $.trim($("#spouseId").val());
        var spouseName = $.trim($("#spouseName").val());
        var spouseEmail = $.trim($("#spouseEmail").val());
        var address = $.trim($("#userAddress").val());
        var errorLog = 0;
        var errorMessage = "";
        if(useCase == 'skipDelete') {
            $("#formContainer").fadeOut(500);
            $("#skipContainer").fadeIn(500);
            $(window).scrollTop(0);
            return errorLog;
        }
        if(useCase == 'photo'){
            $(".jfilestyle").addClass('err');
            errorMessage += 'The photo should be in jpg, gif format and less than 4 MB<br>';
            errorLog++;
        } else {
            $(".jfilestyle").removeClass('err');
        }
        if(useCase == 'not_compatible'){
            errorMessage += 'This is an invalid Success Story<br>';
        }
        if(userId == ""){
            $("#userId").parent().addClass('err');
            errorLog++;
        } else {
            $("#userId").parent().removeClass('err');
            $("#userId").val(userId);
        }
        if(userName == ""){
            $("#userName").parent().addClass('err');
            errorMessage += 'Please enter your name<br>';
            errorLog++;
        } else {
            $("#userName").parent().removeClass('err');
            $("#userName").val(userName);
        }
        if(userEmail == ""){
            $("#userEmail").parent().addClass('err');
            errorLog++;
        } else {
            $("#userEmail").parent().removeClass('err');
            $("#userEmail").val(userEmail);
        }
        if(spouseId == "" || useCase == 'user_invalid' || useCase == 'same_gender'){
            $("#spouseId").parent().addClass('err');
            if(useCase == 'user_invalid'){
                errorMessage += 'The Spouse User Id entered for Spouse is not registered with us<br>';
            } else if(useCase == 'same_gender'){
                errorMessage += 'User Id is of the same gender<br>';
            } else {
                errorMessage += 'Please enter the Spouse ID<br>';    
            }
            $('#spouseId').prop("disabled", false);
            errorLog++;
        } else {
            $("#spouseId").parent().removeClass('err');
            $("#spouseId").val(spouseId);
        }
        if(spouseName == ""){
            $("#spouseName").parent().addClass('err');
            errorMessage += 'Please enter the Spouse Name<br>';
            $('#spouseName').prop("disabled", false);
            errorLog++;
        } else {
            $("#spouseName").parent().removeClass('err');
            $("#spouseName").val(spouseName);
        }
        if(spouseEmail == "" || useCase == 'email_invalid' || useCase == 'email_same'){
            $("#spouseEmail").parent().addClass('err');
            if(spouseEmail == ""){
                errorMessage += 'Please enter the Spouse Email<br>';
            } else {
                if(useCase == 'email_invalid') {
                    errorMessage += 'The Spouse Email entered for Spouse is not registered with us<br>';
                } else if(useCase == 'email_same'){
                    errorMessage += 'The Spouse Email cannot be same as your Email<br>';
                }
            }
            $('#spouseEmail').prop("disabled", false);
            errorLog++;
        } else {
            $("#spouseEmail").parent().removeClass('err');
            $("#spouseEmail").val(spouseEmail);
        }
        if(address == ""){
            $("#userAddress").parent().addClass('err');
            errorMessage += 'Please enter the Address<br>';
            errorLog++;
        } else {
            $("#userAddress").parent().removeClass('err');
            document.getElementById("userAddress").value = address;
        }
        if(useCase == 'invalid_date'){
            $("ul.weddate").parent().addClass('err');
            errorMessage += 'Please enter a valid Date<br>';
        } else {
            $("ul.weddate").parent().removeClass('err');
        }
        if(useCase == 'verified'){
            $("#formContainer").fadeOut(500);
            $("#resultContainer").fadeIn(500);
        } else {
            $('html,body').animate({scrollTop: $("#errorMessage").offset().top-20},1000);
            $("#errorMessage").html(errorMessage);
        }
        return errorLog;
    }
    $(document).ready(function(){
        $(".chosen-select").chosen({
            "disable_search": true,
            "max_selected_options": 1
        });
        $(".chosen-container").css('width',185);
        $(":file").jfilestyle({buttonBefore: true,buttonText: "Select Wedding Photo"});
        $(".focus-jfilestyle label").addClass('btn1 f15 colrw bg5 brdr-0 fl fontlig');
        if("~$WEDDING_DATE`"){
            $('.chosen-select').prop('disabled', true).trigger("chosen:updated");
        }
        $("#main_button").click(function(e){
            e.preventDefault();
            var result = validateFields('submit');
                if(result == 0){
                    var formData = new FormData($("#submit_ss")[0]);
                    $.ajax({
                        type: "POST",
                        url: $('#submit_ss').attr('action'),
                        data: formData, // serializes the form's elements.
                        async: false,
                        success: function(data)
                        {
                            validateFields(data);
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                });
                return false; // avoid to execute the actual submit of the form.
            }
        });
        $("#skip_button").click(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: '/settings/jspcSettings?hideDelete=1&option=Delete&offerConsent='+(offerConsent=='Y'?'Y':'N'),
                async: false,
                success: function(data)
                {
                    validateFields(data);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        return false; // avoid to execute the actual submit of the form.
        });
    });
    var offerConsent='~$offerConsent`';
</script>