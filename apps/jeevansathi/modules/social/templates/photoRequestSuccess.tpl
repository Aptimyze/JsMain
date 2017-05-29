<div class="pink" style="width:512px;">
<div class="topbg">
<div class="lf pd b t12">Photo Request</div>
<div class="rf pd b t12"><a href="#" class="blink" onClick="$.colorbox.close();return false;">Close [x]</a></div>
</div><div class="clear"></div>

<div class="scrollbox1 t12" style="height:auto;" >
<div id="first_layer" style="display:inline";>
<div class="save_hrbg_photo" style="padding:10px;width:412px">
 <div class="lf t14 b" style="width:100%">Do you want to request ~$USERNAME` for photo?</div>
  <div class="sp5"></div>
  <div class="lf" style="text-align:center;width:100%;margin-top:10px;">
    <input type="button" class="b green_btn" value="Continue" style="width:85px;" onclick="javascript:sendRequest();"> &nbsp;<a href="#" class="b blink" onClick="$.colorbox.close();return false;">Cancel</a>
  </div>
  <div class="sp3"></div>

        </div>
        </div>
<div id="second_layer" style="display:none">
<div style="margin:5px; text-align:center"><img src="~sfConfig::get('app_img_url')`/img_revamp/loader_big.gif">
</div>

</div>
<div id="third_layer" style="display:none">
<div style="padding: 10px;" class="">
<div style="width: 100%;" class="lf"><div style="width: auto" class="lf">
<img align="absmiddle" src="~sfConfig::get('app_img_url')`/img_revamp/confirm.gif"/></div>
<div style="padding: 6px 0pt 0pt 5px;" class="lf t14 b">Request for photo successfully sent.</div></div>
  <div class="sp12"></div>
        </div>
        </div>
<div id="error_layer" style="display:none">
<div style="padding: 10px;" class="">
 <div style="width: 100%;" class="lf"><div style="width:auto" class="lf">
<img align="absmiddle" src="~sfConfig::get('app_img_url')`/img_revamp/cross.gif"/></div>
<div style="padding: 6px 0pt 0pt 5px;" class="lf t14 b" id="ERROR_MES">Error message</div></div>
  <div class="sp12"/>
        </div>
</div>
</div>
<script type = "text/javascript">
first_id=document.getElementById("first_layer");
second_id=document.getElementById("second_layer");
third_id=document.getElementById("third_layer");
error_id=document.getElementById("error_layer");
error_mes=document.getElementById("ERROR_MES");
function photo_req_start()
{
        first_id.style.display='none';
        second_id.style.display='none';
        third_id.style.display='none';
        error_id.style.display='none';
        second_id.style.display='inline';
        $.colorbox.resize();    
}
function photo_req_end(error)
{
        var mes="Oops, please try after sometime.";
        first_id.style.display='none';
        second_id.style.display='none';
        third_id.style.display='none';
        error_id.style.display='none';
        if(error)
        {       
                if(error=='A_E')
                        mes=common_error;
                if(error=='F')
                        mes="Your profile has been filtered out";
                if(error=='G')
                        mes="Photo request for same gender is not allowed";
                if(error=='E')
                        mes="You have already requested this user for photo.";
                if(error=='U')
                        mes="Your profile is under screening.";
                error_id.style.display='inline';
                error_mes.innerHTML=mes;
        }
        else
        {
                third_id.style.display='inline';
        }
        //third_id.style.display='inline';
        $.colorbox.resize();    
}
function sendRequest()
{
	photo_req_start();
	var params = "profilechecksum=~$profilechecksum`";
	var url = '~sfConfig::get("app_site_url")`/social/photoRequest';
	$.ajax({type: "GET",url:url,data:params,cache:false, success:function(responseText){
                responseText = $.trim(responseText);
		if(responseText=="true")
			responseText="";
		photo_req_end(responseText);
        }});
}
</script>
