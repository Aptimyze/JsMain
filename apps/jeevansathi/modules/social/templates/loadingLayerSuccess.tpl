<b><div align="left" style="font-size:19px;width:400px;margin-left:0px;background:none;padding-top:25px;">Loading Page, please wait... 
<p class="clr_5" ></p>
</div></b>
<script>
function nextPage()
{
	window.location="~sfConfig::get('app_site_url')`/social/~if $importSite eq facebook`import/facebook ~elseif $importSite eq flickr`import/flickr ~elseif $importSite eq picasa`import/picasa ~elseif $importSite eq noLayer`addPhotos ~/if`";
}
function closeLayer()
{
	window.location="~sfConfig::get("app_site_url")`/social/~if $importSite eq facebook`importFailed/facebook ~elseif $importSite eq flickr`importFailed/flickr ~elseif $importSite eq picasa`importFailed/picasa ~elseif $importSite eq noLayer`addPhotos ~/if`";
        return false;
}
nextPage();
if('~$importSite`' == "facebook")
	setTimeout("closeLayer()",~sfConfig::get("mod_social_facebook_timeout")`);
else if('~$importSite`' == "flickr")
	setTimeout("closeLayer()",~sfConfig::get("mod_social_flickr_timeout")`);
else if('~$importSite`' == "picasa")
	setTimeout("closeLayer()",~sfConfig::get("mod_social_picasa_timeout")`);
else if('~$importSite`' == "noLayer")
	setTimeout("closeLayer()",~sfConfig::get("mod_social_general_timeout")`);
</script>
