<footer style="border-top: 1px solid #D4D4D4;margin-top: 15px;padding: 10px 0;font-size: 12px;"> 
	~if JsConstants::$AndroidPromotion && $sf_request->getAttribute('AppLoggedInUser')`
	<div><a href="~sfConfig::get('app_site_url')`/static/appredirect?type=androidMobFooter">
			<img src="~sfconfig::get("app_img_url")`/images/footer-app-download.png" alt="jeevansathi android app" border="0" />
			</a>
		</div>
	~/if`
	<a href="~sfConfig::get('app_site_url')`/?desktop=Y" style="color:#0046C5;">Desktop View</a>
  <p class="blckTxt">Copyright &copy;~$smarty.now|date_format:"%Y"` Jeevansathi Internet Services</p>
</footer>
~* webengage code starts *`
<script id="_webengage_script_tag" type="text/javascript">
~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
var _weq = _weq || {};
~if $smarty.server.HTTP_HOST eq "xmppdev.jeevansathi.com"`
_weq['webengage.licenseCode'] = '~ldelim`1341067c1';
~else`
_weq['webengage.licenseCode'] = '~ldelim`10a5cc320';
~/if`
_weq['webengage.widgetVersion'] = "4.0";
_weq['webengage.notification.ruleData'] = {
      "Gender" : "~$zedoValue.A2`",
      "Source": "~$zedoValue.j1`",
      "Days since registration" : ~if $zedoValue.j2`~$zedoValue.j2`~else`0~/if`,
      "Paid" : "~$zedoValue.d2`",
      "Community" : "~$zedoValue.j3`"
    };

(function(d)
{ var _we = d.createElement('script'); _we.type = 'text/javascript'; _we.async = true; _we.src = (d.location.protocol == 'https:' ? "https://ssl.widgets.webengage.com" : "http://cdn.widgets.webengage.com") + "/js/widget/webengage-min-v-4.0.js"; var _sNode = d.getElementById('_webengage_script_tag'); _sNode.parentNode.insertBefore(_we, _sNode); }

)(document);
</script>
~* webengage code ends *`
