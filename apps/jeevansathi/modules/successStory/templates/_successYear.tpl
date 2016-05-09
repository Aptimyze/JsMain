<div style="background:url(~sfConfig::get("app_img_url")`/profile/images/bgnew.jpg) repeat-x scroll 0 0; float:left; width:103%">
    <div class="success_yrs_bg" style="width:662px; float:left; display:inline">
    <font class="lf t12 b">Success Stories:&nbsp;</font>
        <div class="lf">
        ~foreach from=$showYear key=k item=v`
			
			
			<ul class='yrs lf'><li class='blink'>
			~if $v eq $year`
				<b>Year ~$v`</b>
			~else`
				<a href=~sfConfig::get("app_site_url")`/successStory/story?year=~$v`>Year ~$v`</a>
			~/if`
			~if $k lt '6'`&nbsp;|&nbsp;~/if`
			</li></ul>
			~/foreach`
        </div>
        </div>
        <div style="float:right;position:relative" onmouseover="javascript:toggle_display_year(1)" onmouseout="javascript:toggle_display_year(0)">
    	<div style="padding:0px; margin:0px;float:left;margin-top:5px; color:#ffffff; font-weight:bold; cursor:pointer;position:relative">
        <p>Older
        </p>
        
        </div>
        
        <div  style="float:left; display:inline;" ><img src="~sfConfig::get("app_img_url")`/profile/images/arrow.png" ></div>
        
        
<div style="position:ABSOLUTE; top:25px; LEFT:-34px;width:83px; height:AUTO; display:none;z-index:100" id="old_story" name="old_story">
<IFRAME id="iframe_sd" style="display: inline; left: 0px; top: 2px; z-index: 2; width: 94px; position:absolute;height: 60px;filter: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);filter:alpha(opacity=0);" frameBorder="0" scrolling="no"></IFRAME>
	 ~foreach from=$hideYear key=k item=v`
	 ~assign var=top_all value=$k*25`
	 ~assign var=top_ie value=$k*26`
	 <ul class='lf' style="z-index:1000;position:absolute;top:~$top_all`px;_top:~$top_ie`px"><li class='blink' style='border-left:1px solid #ee822a;border-right:1px solid #ee822a;border-top:1px solid #ee822a;border-bottom:1px solid #ee822a; background:#fff;padding:5px 10px'>
	 ~if $v eq $year`
	 <b>Year ~$v`</b>
	 ~else`
	 <a href=~sfConfig::get("app_site_url")`/successStory/story?year=~$v`>
	  Year ~$v`</a>
	  ~/if`
	  </li></ul>
	  ~/foreach`
  </div>
</div>

  
</div><BR>


<div style="clear:both">&nbsp;</div>
<script>
function toggle_display_year(show)
{
	if(show)
		displays="inline";
	else
		displays="none";
	dID("old_story").style.display=displays;
}
</script>
