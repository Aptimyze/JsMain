<script language="javascript">
function closeLayer(){
~if $FROM_DELETE_PROFILE eq 1`
window.location.href="~$SITE_URL`/profile/hide_delete_revamp.php?checksum=~$CHECKSUM`&CMDdelete=1&from_ss=1";
~else`
$.colorbox.close();
close_window();
~/if`
}
</script>
<form name="submit_ss" action="~$SITE_URL`/successStory/submitlayer" method="post" enctype="multipart/form-data" target="upload_target">
    <div id="mainform" class="overlay_wrapper_775px" style="background-color:white;" >
        <div class="top">
            <div class="text white b widthauto">Submit Your Success Story</div>
            <div class="fr div_close_button_green" style="cursor:pointer" ~if $FROM_DELETE_PROFILE eq 1`onClick="window.location.href='~$SITE_URL`/profile/hide_delete_revamp.php?CMDdelete=1&checksum=~$CHECKSUM`&from_ss=1';" ~else`onClick="$.colorbox.close(); return false;" ~/if`>&nbsp;</div>
        </div>
        <div class="clear"></div>
        <div class="scrollbox2 t12 " style="background:white;width:760px">
            <div style="width:94%">
                <div>
                    <div id="error_msg" style="visibility:hidden;">
                        <div class="lf" >
                            <div class="lf">
                                <img src="~sfConfig::get("app_img_url")`/success/images/iconError_16x16.gif" hspace="10" vspace="0" align="left">
                            </div>
                            <div class="lf t12 b red" style="width:382px;padding-top:2px;">
                                <div id="error_msg_text">Please provide at least one of Email Id or User Id
                                </div>
                            </div>
                            <!--div class="rf b"><a href="#" onClick="hide_error_msg();">[x]</a>
                        </div-->
                    </div>
                </div>
                ~if $COMMENTS neq ''`
                <div class="lf t12 b" style="float: left; width: 96%;"><div sytle="padding-left:125px">Dear ~if $NAME`~$NAME`~else`~$USERNAME`~/if`, </div>
                <div>You have already successfully uploaded  your story. Please upload your wedding photo to complete the success story.</div></div>
                ~/if`
                <div class="row5">
                    <span id="spse1_name" class="label">Your Name :</span>
                    ~if $NAME`
                    <input type="text" name="spouse1_name" value="" style="vertical-align: text-bottom; float: left;    width: 100px; margin-right: 145px; margin-left: 7px;">
                    ~else`
                    <input type="text" name="spouse1_name" value="" style="vertical-align: text-bottom; float: left;width: 100px; margin-right: 145px; margin-left: 7px;">
                    ~/if`
                    <span id="spse_name" class="label">Spouse Name :</span>
                    ~if $NAME_H eq ''`
                    <input type="text" name="spouse_name" style="width:100px; vertical-align:text-bottom">
                    ~else`
                    ~$NAME_H`
                    <input type="hidden" name="spouse_name" value="~$NAME_H`">
                    ~/if`
                </div>
                <div class="row5">
                    <label>Your ID :</label> <span>~$USERNAME`</span> <span id="spse_id" class="label">Spouse ID :</span>~if $USERNAME_W eq ''`<input type="text" name="spouse_id" style="width:100px; vertical-align:text-bottom">~else` ~$USERNAME_W` <input type="hidden" name="spouse_id" value="~$USERNAME_W`"> ~/if`
                </div>
                <div class="row5">
                    <label>Your Email  :</label> <span>~$EMAIL`</span> <span id="spse_email" class="label">Spouse Email :</span>~if $EMAIL_W eq ''`<input type="text" name="spouse_email" style="width:100px; vertical-align:text-bottom">~else` ~$EMAIL_W` <input type="hidden" name="spouse_email" value="~$EMAIL_W`">~/if`
                </div>
            </div>
            <div class="row4">
                <label id="addr">Contact  Address :</label>
                ~if $CONTACT_DETAILS eq ''`<textarea  cols="6" rows="4" name="contact_address" style="width:250px; font:normal 12px arial;height:35px; vertical-align:top">~$CONTACT`</textarea>~else` ~$CONTACT_DETAILS` <input type="hidden" name="contact_address" value="~$CONTACT_DETAILS`">~/if`
            </div>
            <div class="row4">
                <label id="wd_photo">Wedding Photo :</label>
                <input type="file" name="wedding_photo" onChange="enableButton();" style="height:22px;" >
            </div>
            <div style="margin-top: 10px;" class="row4">
                <label id="wd_dt">Wedding Date :</label>
                ~if $WEDDING_DATE eq ''`
                <select name="w_day" style="width:50px;_font-size:11px;">
                    <option selected value="">Day</option>
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
                <select name="w_month" style="width:65px;_font-size:11px;">
                    <option selected value="">Month</option>
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
                <select name="w_year" style="_font-size:11px;width:85px;">
                    <option selected value="">Year</option>
                    ~foreach from=$dateArray item=values key=kk`
                    <option value=~$values` ~if $curDate eq $values` selected ~/if`>~$values`</option>
                    ~/foreach`
                </select>
                ~else`
                ~$WEDDING_DATE`
                <input type="hidden" name="w_month" value="~$W_MONTH`">
                <input type="hidden" name="w_day" value="~$W_DAY`">
                <input type="hidden" name="w_year" value="~$W_YEAR`">
                ~/if`
            </div>
            <div class="row4">
                <label id="stry">Your Story :</label>
                ~if $COMMENTS eq ''`<textarea name="ss_story" cols="6" rows="4" style="width:350px; font:normal 12px arial;height:65px; vertical-align:top"></textarea>~else` ~$COMMENTS` <input type="hidden" name="ss_story" value="~$COMMENTS`"> ~/if`
            </div>
            <div class="row4" style="margin-top:0px;">
                <label>&nbsp;</label>
                ~if $COMMENTS eq ''`Tell us about how you met on Jeevansathi and what would be your advise for those who are still looking for their dream match on Jeevansathi ~/if`
            </div>
            <div class="row4 t11 gray" style="margin-top:0px;">
                <label>&nbsp;</label>Note: Your profile will be deleted once your success story is accepted
            </div>
        </div>
    </div>
    <div class="sp12" style="border:1px #F0CED6; border-top-style:solid"></div>
    <div style="text-align:center;width:100%">
        <input id="main_button" type="button" class="btn_view  fs13 b cp" value="Submit Success Story" style="width:170px;" onClick="check_ss();" >
    </div>
    <div class="sp12"></div>
</div>
~if $COMMENTS neq ''`
<iframe id="upload_target" name="upload_target" src="" style="width:0px;height:0px;border:0px;display:none;" onload="disableButton(); return false;">
~else`
<iframe id="upload_target" name="upload_target" src="" style="width:0px;height:0px;border:0px;display:none;" >
~/if`
</iframe>
<input type="hidden" name="checksum" value="~$CHECKSUM`">
<input type="hidden" name="submit_ss_flag" value="1">
<input type="hidden" name="my_name" ~if $NAME` value="~$NAME`" ~else` value="~$USERNAME`"~/if`>
<input type="hidden" name="username" value="~$USERNAME`">
<input type="hidden" name="email" value="~$EMAIL`">
<input type="hidden" name="profileid" value="~$PROFILEID`">
</form>