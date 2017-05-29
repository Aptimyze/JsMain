var siteUrl=SITE_URL;

function markAccepted()
{
        var url=siteUrl+"/profile/tncLayer.php?ACCEPT=1";
        send_ajax_request(url,"","","POST");
	$.colorbox.close();
	return false;
}
