<script language="javascript">
function after_submit(){
	if($("#fromPage").val())
  document.form1.action="/profile/~get_slot('submitAction')`?fromPage=filter_redirect";
	else
  document.form1.action="/profile/~get_slot('submitAction')`";
	
  ~get_slot('onSubmit')`
  }
</script>
<form name=form1 method=post action="#" onsubmit="return after_submit();" style="margin:0px;padding:0px;">
<noscript>
<div style="position:fixed;z-index:1000;width:930px;"><div style="text-align:center;padding-bottom:3px;font-family:verdana,Arial;font-size:12px;  font-size-adjust:none;font-stretch:normal;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;background-color:#E5E5E5;"><b><img     src="~sfConfig::get('app_img_url')`/profile/images/registration_new/error.gif" width="23" height="20"> Javascript is disabled in your browser.Due to this    certain functionalities will not work. <a href="~sfConfig::get('app_site_url')`/jshelp/js-help-new.html" target="_blank">Click Here</a> , to know how to     enable it.</b></div></div>
<input type="hidden" name="if_no_script" value="1">
</noscript>
<div class="pink_edit" style="height:auto;">
<div class="edit_input">
<div class="topbg_edit">
<div class="title_edit b lf ">~get_slot('LayerHeading')`</div>
<div class="rf"><a onclick="$.colorbox.close(); return false;" href="#" class="blink_edit">Close [X]</a></div>
</div><div class="clear"></div>
<!-- Top ends here -->
~$sf_content`

<!-- Bottom starts here -->
<div class="clear"></div>
<div class="clear"></div>
<div class="sp12" style="border:1px #F0CED6; border-top-style:solid"></div>
~if get_slot('submitAction') eq 'edit_dpp.php'`
<div style="margin-left:20px;"><u>Note</u> - Default setting in advanced & quick search will change as per changes in desired partner profile </div>
<div class="sp12"></div>
~/if`
<div class="center fullwidth"><span id="img_sav"><input type="submit" class="green_btn_2" style="height:35px;width:101px;font-size:18px;" value="~if get_slot('LayerHeading') eq 'Astro/Kundali Details' and !get_slot('submitButton')`Next~else` Save~/if`"></span>~get_slot('additnl_save')`</div>
<div class="sp12"></div>
</div>
</div>
</form>
<!--Bottom Ends Here -->
