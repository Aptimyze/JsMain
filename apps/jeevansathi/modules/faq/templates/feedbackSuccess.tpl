~assign var=loggedIn value= $sf_request->getAttribute('login')`
~if $loggedIn`
    ~assign var=loginData value= $sf_request->getAttribute('loginData')`
~/if`
<!--start:header-->
<div class="cover1">
    <div class="container mainwid pt35 pb30">
        <!--start:top horizontal bar-->
        <!--start:logo-->
        ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
        <!--end:logo-->
        <!--end:top horizontal bar-->
    </div>
</div>
<!--end:header-->
<!--start:middle part-->
<div class="bg4">
    <div class="container mainwid">
        <!--start:nav 1-->
        <div class="cubg1 crbdr1">
            <ul class="hor_list clearfix fontlig f15 cunav1 color11 fullwid">
                <li class="wid49p cursp"><span><a class="disp_b color11" href="/contactus/index">Contact Us</a></span></li>
                <li class="wid50p active"><span>Feedback</span></li>
            </ul>
        </div>
        <!--end:nav 1-->
        <div class="pt30 pb30">
            <div id="feedbackFormContainer" class="bg-white">
                <div class="cup5">
                    <p class="txtr fontlig color11 f13"><span class="color5 disp_ib pr5">*</span>Mandatory</p>
                </div>
                <!--start:form-->
                <div class="cup8">
                    <form>
                        <ul class="feedbck1 fontlig hor_list clearfix">
                            <li class="wid50p clearfix">
                                <label id="emailInputLabel" class="wid25p"><span class="disp_ib pr5 err2">*</span>Email</label>
                                <div id="emailInputContainer" class="wid70p crbdr3 cubrd1">
                                    <input id="feed_email" name="feed[email]" type="text" value="~$loginData.EMAIL`" class="color11 f15 fontlig brdr-0 cup7"/>
                                </div>
                            </li>
                            <li class="wid50p clearfix">
                                <label id="nameInputLabel" class="wid25p">Name</label>
                                <div id="nameInputContainer" class="wid70p crbdr3 cubrd1">
                                    <input id="feed_name" name="feed[name]" type="text" value="~$NAME`" class="color11 f15 fontlig brdr-0 cup7"/>
                                </div>
                            </li>
                            <li class="wid50p clearfix">
                                <label id="categoryInputLabel" class="wid25p"><span class="disp_ib pr5 err2">*</span>Category</label>
                                <div id="categoryInputContainer" class="f15 fontlig color11 wid70p crbdr3 cubrd1">
                                    ~$form['category']->render(['class'=>'chosen-select color11 f15 fontlig brdr-0 cup7 textbox'])`
                                    <!--<input id="feed_category" name="feed[category]" type="text" value="Select" class="color11 f15 fontlig brdr-0 cup7"/> -->
                                </div>
                            </li>
                            <li class="wid50p clearfix">
                                <label id="usernameInputLabel" class="wid25p">Username</label>
                                <div id="usernameInputContainer" class="wid70p crbdr3 cubrd1">
                                    <input id="feed_username" name="feed[username]" type="text" value="~$USERNAME`" class="color11 f15 fontlig brdr-0 cup7"/>
                                </div>
                            </li>
                            <li class="fullwid clearfix">
                                <label id="reasonInputLabel" class="cuwid4"><span class="disp_ib pr5 err2">*</span>Reason</label>
                                <div id="reasonInputContainer" class=" wid85p crbdr3 cubrd1">
                                    <textarea id="feed_message" name="feed[message]" class="fontlig f15 color11 cup7 brdr-0"></textarea>
                                </div>
                            </li>
                            <li class="clearfix txtc cursp" style="overflow:hidden;position: relative; margin-left: 46%;">
                                <button style="padding-bottom: 5px; padding-top: 5px;" id="CMDSubmit" name="CMDSubmit" class="cursp bg_pink lh44 colrw cup1 f20 fontreg brdr-0 pinkRipple hoverPink" value="Send">Post</button>
                            </li>
                        </ul>
                        <input type="hidden" id = "tracepath" name="tracepath" value="~$tracepath`">
                        ~$form['_csrf_token']->render()`
                        ~$form->renderGlobalErrors()`
                    </form>
                </div>
                <!--end:form-->
            </div>
            <div id="feedbackResponseContainer" class="pt30 pb30 disp-none bg-white">
                <div id="responseMessage" class="color11 fontlig lh40 f16 txtc fontlig">
                    <p class='colr5 f24 pb20 pt50'>Your feedback has been sent</p>
                    <p class="f18 lh27" style="margin: 0px auto; width: 40%; padding-bottom: 100px;">Thanks for submitting your feedback. We will get back to you within 24 hrs.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->
<script type="text/javascript">
    function validateEmail(val){
        var regEx=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
        if(!regEx.test(val)){
            return false;
        }
        return true;
    }
    function ccreateAjaxObj(url, parameters) {
        httprequest = false
        if (window.XMLHttpRequest) { // if Mozilla, Safari etc
            httprequest = new XMLHttpRequest()
            if (httprequest.overrideMimeType) httprequest.overrideMimeType('text/html')
        } else if (window.ActiveXObject) { // if IE
            try {
                httprequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    httprequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }
        if (!httprequest) {
            return false;
        }
        httprequest.onreadystatechange = aalertContents;
        httprequest.open('POST', url, true);
        httprequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httprequest.setRequestHeader("Content-length", parameters.length);
        httprequest.setRequestHeader("Connection", "close");;
        httprequest.send(parameters);
    }
    function gget() {
        var w = document.getElementById('feed_category').selectedIndex;
        var selected_text = document.getElementById('feed_category').options[w].value;
        var poststr = "&feed[name]=" + document.getElementById('feed_name').value + "&feed[username]=" + document.getElementById('feed_username').value + "&feed[email]=" + document.getElementById('feed_email').value + "&feed[message]=" + document.getElementById('feed_message').value + "&feed[_csrf_token]=" + document.getElementById('feed__csrf_token').value + "&feed[category]=" + selected_text + "&CMDSubmit=" + document.getElementById('CMDSubmit').value + "&tracepath=" + document.getElementById('tracepath').value;
        ccreateAjaxObj('/api/v3/faq/feedbackAbuse', poststr);
    }
    function aalertContents() {
        if (httprequest.readyState == 4) {
            if (httprequest.status == 200) {
                $("#feedbackFormContainer").fadeOut(500);
                $("#feedbackResponseContainer").fadeIn(500);
            } else {
                alert('There was a problem with the request.');
            }
        }
    }
    $(document).ready(function(){
        $(".chosen-select").chosen({
            "disable_search": true,
            "max_selected_options": 1
        });
        $(".chosen-drop,#feed_category_chosen").css('width',322);
        $("ul.chosen-results li").css('width','100% !important');
        $("#CMDSubmit").click(function(e){
            var email = $("#feed_email").val();
            var reason = $("#feed_message").val();
            var category = $("#feed_category").val();
            if(!validateEmail(email) || reason == '' || category == ''){
                e.preventDefault();
                if(!validateEmail(email)){
                    $("#emailInputLabel").addClass('err2');
                    $("#emailInputContainer").addClass('crerr1');
                } else {
                    $("#emailInputLabel").removeClass('err2');
                    $("#emailInputContainer").removeClass('crerr1');
                }
                if(reason == ''){
                    $("#reasonInputLabel").addClass('err2');
                    $("#reasonInputContainer").addClass('crerr1');
                } else {
                    $("#reasonInputLabel").removeClass('err2');
                    $("#reasonInputContainer").removeClass('crerr1');
                }
                if(category == ''){
                    $("#categoryInputLabel").addClass('err2');
                    $("#categoryInputContainer").addClass('crerr1');
                } else {
                    $("#categoryInputLabel").removeClass('err2');
                    $("#categoryInputContainer").removeClass('crerr1');
                }
            } else {
                e.preventDefault();
                gget();
            }
        })
    });
</script>