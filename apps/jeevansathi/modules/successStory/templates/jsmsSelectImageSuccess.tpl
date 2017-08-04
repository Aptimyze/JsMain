<style>
    div.err {
        border: 1px solid #d9475c;
    }
    .centerDiv {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
    }
    
    .pu_addmore {
        background-position: -12px 1px;
        width: 31px;
        height: 29px;
        bottom: 57px;
        left: 85px;
    }
    
    .mt20 {
        margin-top: 20px;
    }
    
    .up_sprite {
        background-image: url("https://static.jeevansathi.com/images/jsms/photo/upload-sprite.png");
        background-repeat: no-repeat;
        display: block !important;
    }
    .no_pic_dim2 {
        width: 250px;
        height: 250px;
    }
    .pu_addmore2 {
        background-position: -12px 1px;
        width: 31px;
        height: 29px;
        bottom: 37px;
        left: 107px;
    }
    .mt20 {
        margin-top: 20px;
    }
    .mt4 {
        margin-top:4px;
    }
    .gallericon{background-position:-13px -29px;
        width: 30px;
        height: 30px;
    }
    .cameraicon{background-position: -13px -59px;
        width: 36px;
        height: 30px;
    }
    .coupleImage {
        height: 100px;
        width: 100px;
        border-radius: 50%;
    }
    
    .changeImage {
        vertical-align: top;
        margin-top: 20px;
    }
    
    .mt10 {
        margin-top: 10px;
    }
    
    .mb45 {
        margin-bottom: 45px;
    }
    
    .colorNew {
        color: #CFCFCF;
    }
    .set_arow1 {
        background-position: -221px -179px;
        width: 17px;
        height: 24px;
    }
    .set_pos1 {
        left:0;
        top:2px;
    }
    .colorBlack {color:black !important;}
</style>
<body class="bg4">
    <div id="mainContent1">
        <div class="loader" id="pageloader"></div>
        <div>
            <!--start:top-->
            <div id="overlayHead" class="bg1 txtc pad15">
                <div class="posrel lh30">
                    <div class="fontthin f20 white">Add Wedding Photo</div>
                    ~if $fromMailer neq 'true'`
                        <a href="/successStory/jsmsInputStory"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
                    ~else`
                        <a href="/successStory/layer?fromSuccessStoryMailer=~$fromMailer`&mailid=~$mailId`"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
                    ~/if`
                </div>
            </div>
            <!--end:top-->
            <!--start:option-->
            <div class="bg4 f16 fontlig color13 abs_c">
                <!--start:input field-->
                <div>
                    <div class="centerDiv">
                        <div id="addPhotoMobile" class="posrel no_pic_dim">
                            <div id="uploadPhoto">
                                <img src="/images/jsms/commonImg/couple.png">
                                <i class="up_sprite posabs pu_addmore"></i>
                            </div>
                            <div class="txtc nl_p10 f18 mt20">Choose Photo</div>
                        </div>
                    </div>
                </div>
                <!--end:input field-->
            </div>
            <!--end:option-->
        </div>
    </div>

    </div>
    </div>
    <form id="submit_ss" name="submit_ss" action="/successStory/submitlayer~if $fromMailer eq 'true'`?fromSuccessStoryMailer=true&mailid=~$mailid`~/if`" method="post" enctype="multipart/form-data" target="_self">
    <div id="mainContent2" class="dn">
        <div class="loader" id="pageloader"></div>
        <div>
            <!--start:top-->
            <div id="overlayHead" class="bg1 txtc pad15">
                <div class="posrel lh30">
                    <div class="fontthin f20 white">Add Details</div>
                    ~if $fromMailer neq 'true'`
                        <a href="/static/deleteOption"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
                    ~else`
                        <a href="/successStory/layer?fromSuccessStoryMailer=~$fromMailer`&mailid=~$mailId`"><i class="mainsp posabs set_arow1 set_pos1"></i></a>
                    ~/if`
                </div>
            </div>
            <!--end:top-->
            <!--start:option-->
            <div class="bg4 f16 fontlig color13 abs_c fullwid mb45">

                <div class="photoSection clearfix brdr15 pad3">
                    <img class="coupleImage dispibl" id="imagePic">
                    <div id="changePhoto" class="changeImage nl_p10 f17 fb fontlig color2 dispibl">Change/ Add Photo</div>
                    <div id="errorMessage" class="fr colorNew">Fill all fields</div>
                </div>
                <div class="idSection brdr15 pad3 colorNew">
                    <div>Your ID</div>
                    <input id="userId" readonly name="spouse_id" value="~$USERNAME`" class="successId mt10 colorNew colorBlack"/>
                </div>
                <div class="nameSection brdr15 pad3">
                    <div class="colorNew">Your Name</div>
                    ~if $NAME`
                    <input id="userName" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse1_name" placeholder="~$NAME`" value="~$NAME`"/>
                    ~else`
                    <input id="userName" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse1_name" placeholder="Not filled in" value=""/>
                    ~/if`
                </div>
                <div class="emailIdSection brdr15 pad3 colorNew">
                    <div>Your Email</div>
                    ~if $EMAIL`
                    <input id="userEmail" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse1_email" placeholder="~$EMAIL`" value="~$EMAIL`" readonly />
                    ~else`
                    <input id="userEmail" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse1_email" placeholder="Not filled in" value="" readonly/>
                    ~/if`
                </div>
                <div class="spouseIdSection brdr15 pad3">
                    <div class="colorNew">Spouse ID</div>
                    ~if $USERNAME_W`
                    <input id="spouseId" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse_id" placeholder="~$USERNAME_W`" value="~$USERNAME_W`"/>
                    ~else`
                    <input id="spouseId" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse_id" placeholder="Not filled in" value=""/>
                    ~/if`
                </div>
                <div class="spouseNameSection brdr15 pad3">
                    <div class="colorNew">Spouse Name</div>
                    ~if $NAME_H`
                    <input id="spouseName" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse_name" placeholder="~$NAME_H`" value="~$NAME_H`"/>
                    ~else`
                    <input id="spouseName" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse_name" placeholder="Not filled in" value=""/>
                    ~/if`
                </div>
                <div class="spouseEmailSection brdr15 pad3">
                    <div class="colorNew">Spouse Email</div>
                    ~if $EMAIL_W`
                    <input id="spouseEmail" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse_email" placeholder="~$EMAIL_W`" value="~$EMAIL_W`"/>
                    ~else`
                    <input id="spouseEmail" class="successName mt10 fullwid colorNew fontlig f16 colorBlack" name="spouse_email" placeholder="Not filled in" value=""/>
                    ~/if`
                </div>
                <div class="addSection brdr15 pad3">
                    <div class="colorNew">Address (Gift will be sent at this address)</div>
                    ~if $CONTACT_DETAILS eq ''`
                    <textarea id="userAddress" rows="2" name="contact_address" class="successAdd fullwid mt10 colorNew fontlig f16 colorBlack" placeholder="Not filled in"></textarea>
                    ~else`
                    <textarea id="userAddress" rows="2" name="contact_address" class="successAdd fullwid mt10 colorNew fontlig f16 colorBlack" placeholder="~$CONTACT_DETAILS`" value="~$CONTACT_DETAILS`" readonly>~$CONTACT_DETAILS`</textarea>
                    ~/if`
                </div>
                <div id="weddingDateContainer" class="dateSection brdr15 pad3">
                    <div class="colorNew">Wedding Date</div>
                    ~if $WEDDING_DATE eq ''`
                    <select name="w_day" style="width:50px;_font-size:11px;">
                        <option selected value="1" >1</option>
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
                    <select name="w_month" style="width:65px;_font-size:11px;">
                        <option selected value="1" >Jan</option>
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
                    <select name="w_year" style="_font-size:11px;width:85px;">
                        ~foreach from=$dateArray item=values key=kk`
                        <option value=~$values` ~if $curDate eq $values` selected ~/if`>~$values`</option>
                        ~/foreach`
                    </select>
                    ~else`
                    <span style="padding-top: 5px;" readonly class="colorNew">~$WEDDING_DATE`</span>
                    <input type="hidden" name="w_month" value="~$W_MONTH`">
                    <input type="hidden" name="w_day" value="~$W_DAY`">
                    <input type="hidden" name="w_year" value="~$W_YEAR`">
                    ~/if`
                </div>
                <br><br><br>
                ~if $successStoryMsg`
                <input type="hidden" name="ss_story" value="~$successStoryMsg`"></input>
                ~else`
                <input type="hidden" name="ss_story" value="~$COMMENTS`"></input>
                ~/if`
                <input type="hidden" name="checksum" value="~$CHECKSUM`">
                <input type="hidden" name="submit_ss_flag" value="1">
                <input type="hidden" name="my_name" ~if $NAME` value="~$NAME`" ~else` value="~$USERNAME`"~/if`>
                <input type="hidden" name="username" value="~$USERNAME`">
                <input type="hidden" name="email" value="~$EMAIL`">
                <input type="hidden" name="profileid" value="~$PROFILEID`">
                <input type="hidden" name="fromSuccessStoryMailer" value="~$fromMailer`">
                <input type="hidden" name="mailid" value="~$mailId`">
                <input style="width:0px;height:0px;position:absolute;" id="myFileInput" type="file" name="wedding_photo" accept="image/*;capture=camera">
                <!--start:submit button-->
                ~if $fromMailer neq 'true'`
                    <div id="foot" class="posfix fullwid bg7 btmo">
                        <input type="submit" id="main_button" class="cursp fullwid dispbl lh50 txtc f16 white" value="Submit Success Story & Delete Profile">
                    </div>
                ~else`
                    <div id="foot" class="posfix fullwid bg7 btmo">
                        <input type="submit" id="main_button" class="cursp fullwid dispbl lh50 txtc f16 white" value="Submit Success Story">
                    </div>
                ~/if`
                <!--end:submit button-->
            </div>
            <!--end:option-->
        </div>
    </div>
    </form>
</body>
<script>
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
        if(useCase == 'not_compatible'){
            errorMessage += 'This is an invalid Success Story<br>';
            $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
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
                $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
            } else if(useCase == 'same_gender'){
                errorMessage += 'User Id is of the same gender<br>';
                $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
            } else {
                errorMessage += 'Please enter the Spouse ID<br>';
                $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);    
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
            $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
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
                $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
            } else {
                if(useCase == 'email_invalid') {
                    errorMessage += 'The Spouse Email entered for Spouse is not registered with us<br>';
                    $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
                } else if(useCase == 'email_same'){
                    errorMessage += 'The Spouse Email cannot be same as your Email<br>';
                    $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
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
            $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
            errorLog++;
        } else {
            $("#userAddress").parent().removeClass('err');
            document.getElementById("userAddress").value = address;
        }
        if(useCase == 'invalid_date'){
            $("#weddingDateContainer").addClass('err');
            errorMessage += 'Please enter a valid Date<br>';
            $("#errorMessage").removeClass('colorNew').addClass('color2').html(errorMessage);
        } else {
            $("#weddingDateContainer").removeClass('err');
        }
        if(useCase == 'photo'){
            $("#errorMessage").removeClass('colorNew').addClass('color2').html('Invalid photo<br>');
        }
        if(useCase == 'verified'){
            window.location.href = "/P/logout.php";
        } else {
            $('html,body').animate({scrollTop: $("#errorMessage").offset().top-20},1000);
            $("#errorMessage").removeClass('colorNew').addClass('color2');
        }
        return errorLog;
    }
    $(document).ready(function(e) {
        $("#uploadPhoto").click(function() {
            $("#myFileInput").click();
            $("#errorMessg").remove();
        });
        $("#changePhoto").click(function(){
            $("#mainContent2").hide();
            $("#mainContent1").show();
        });
        $("#myFileInput").change(function() {
            var myInput = document.getElementById('myFileInput');
            var preview = document.querySelector('#imagePic');
            var file = myInput.files[0];
            var reader = new FileReader();
            if (file && (file.name.split(".")[1] == "jpg" || file.name.split(".")[1] == "JPG" ||file.name.split(".")[1] == "jpeg" || file.name.split(".")[1] == "JPEG" || file.name.split(".")[1] == "GIF" || file.name.split(".")[1] == "gif")) {
                reader.readAsDataURL(file);
                $("#mainContent1").hide();
                $("#mainContent2").show();
            } else {    
                $(".centerDiv").prepend("<span style='margin-left:20px' class='f19 nl_p10' id='errorMessg'>Invalid file format</span>");
            }
            reader.onloadend = function() {
                preview.src = reader.result;
            }
        });
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
    });
</script>
