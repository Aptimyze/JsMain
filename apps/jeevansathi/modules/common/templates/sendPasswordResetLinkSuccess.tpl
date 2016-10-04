<script language="JavaScript">
$(document).keypress(function(e) {
    if(e.which == 13) {
        return sendForgotPasswordRequest();
    }
});
        function sendForgotPasswordRequest()
        {
                var emailValue = $("#email").val();
                if(check_for_email(emailValue))
                {
                        complete_url="~sfConfig::get('app_site_url')`/profile/forgotpassword.php";

                        method="POST";
			after_call_func="after_forgot";
$.ajax
                        ({
                                url:complete_url,
                                type: "POST",
                                data:{ajaxValidation:'Y',submit_email='1',email:escape(emailValue)}                                
                                }).done(function(res){
                                result =res;
                                eval('after_forgot()');
                        });


                }
                else
                {
                        $("#wrongFormat").show();
                        $.colorbox.resize();
                }
		return false;
        }
        function check_for_email(emailadd)
        {
                var result = false;
                var theStr = new String(emailadd);
                var index = theStr.indexOf("@");
                if (index > 0)
                {
                        var pindex = theStr.indexOf(".",index);
                        if ((pindex > index+1) && (theStr.length > pindex+2))
                        result = true;
                }
                return result;
        }
function after_forgot()
        {
                if(result=='D1' || result=='E2' || result=='E1')
                {
                        if(result=='E1' || result=='E2')
                        {
                                $("#wrongEmail").show();
                        }
                        else if(result=='D1')
                        {
                                $("#passwordSuccess").show();
                                $("#passwordChange").hide();
                        }
                        return 1;
                }
        }
$( document ).ready(function() {
                                $("#passwordSuccess").hide();
                                $("#passwordChange").show();
                                $("#wrongEmail").hide();
				$("#wrongFormat").hide();
});
</script>
<style type="text/css">
/*css alreay existing on site global_4.css,common_css_5.css,header-footer_5.css */
body { margin: 0;padding: 0;font-size: 12px;color: #000;font-family: Arial,sans-serif;}
#main_cont {width: 930px;margin: auto;}
.lh23{line-height:2.3}
.pad11left{padding-left:11px}
.fs14{font-size:14px}
.pad20top{padding-top:20px}
.pad20right{padding-right:20px}
.pad10top{padding-top:10px}
.w200{width:200px}
.pad3top{padding-top:3px}
.fs16{font-size:16px}
.mar20top{margin-top:20px}
/*new css */
.fpnew_ft20{font-size:20px;}
.fpnew_clr1{color:#666666;}
.fpnew_clr2{color:#ff0000;}
.fpnew_bg1{background-color:#ededed;}
#fpnewclass input[type=radio], input.radio { float:left; clear:none; margin-left:0px; }
#fpnewclass input[type=text], input.text { border:1px solid #d4d4d4; }
#fpnewclass label { float:left; clear:none; display:block;padding-left:5px; padding-top:1px  }
#fpnewclass { }
.clearfix:after {content: ".";display: block; clear: both; visibility: hidden;line-height: 0; height: 0;}
.clearfix { display: inline-block;}
html[xmlns] .clearfix { display: block;}
* html .clearfix { height: 1%;}
.fpnewsub{ background-color:#58a52f; padding:6px 25px; color:#fff; border:0px;}

</style>

~include_partial('global/header')`
<div id="main_cont">
    <div class="fs16 fpnew_clr1 lh23 pad40left fpnew_bgchk" id="passwordSuccess">
        An Email & SMS has been sent to you. Please click on the link provided to reset your password
    </div>
    <div class="clearfix" id="passwordChange">
    	<div class="fpnew_ft20 fpnew_clr1 lh23">
        	Reset your Password
        </div>
        <div class="fs16 fpnew_clr1">
       The link has been either accessed once or it has been more than 24 hours since you requested for it. In case you are trying to reset your password again, request for a new link below.
        </div>
        <div class="fpnew_bg1 lh23 pad11left mar20top">
		Enter your registered email of Jeevansathi to receive an Email & SMS with the link to reset your password
        </div>
        <!--start:form-->
        <div class="pad20top fpnew_clr1">
            <form id="fpnewclass">
                <div class="clearfix fs14">
                    <label class="pad20right">My registered Email id is </label>
                </div>   
                <div class="pad10top">
                	<input type="text" id="email" name="email" class="w200"/>
                </div>
                <!--start:email error-->
                <div class="fpnew_clr2 fs12 pad3top" id="wrongEmail">
                	Email id you entered dosen't exist in our records
                </div>
                <div class="fpnew_clr2 fs12 pad3top" id="wrongFormat">
                	Please provide correct Email-ID
                </div>
                <!--end:email error-->
                <div class="pad10top">
                	<input type="button" name="submit" value="Submit" class="fpnewsub fs16" onclick="return sendForgotPasswordRequest();"/>
                </div>
            </form>  
        </div>   
        <!--end:form-->
    </div>
    <!--end:link expired-->
</div>
~include_partial('global/footer')`
