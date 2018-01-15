<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>JeevanSathi</title>
	</meta>
<script src="~sfConfig::get('app_img_url')`/min/?f=/js/tracking_js.js"></script>
</head>
        ~JsTrackingHelper::getHeadTrackJs()`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~include_partial('global/header')`

<table width=80% align="CENTER" >
	~if $linkArr.FP eq 'Y'`
		<tr align="left">
    			<td class="fieldsnew" height="23">
				<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=FP">My failed payment profiles</a>
    			</td>
   		</tr>
   		<tr align="left">
    			<td class="fieldsnew" height="23">
				<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=PH">My profiles who clicked on membership page</a>
    			</td>
   		</tr>
	~/if`
	~if $linkArr.NFP eq 'Y'`
                <tr align="left">
                        <td class="fieldsnew" height="23">
                                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=NFP">New Failed Payment profiles</a>
                        </td>
                </tr>
	~/if`
	 ~if $linkArr.WL eq 'Y'`
                <tr align="left">
                        <td class="fieldsnew" height="23">
                                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=WL">New Web Master leads</a>
                        </td>
                </tr>
        ~/if`
	~if $linkArr.ON eq 'Y'`
		<tr align="left">
        	        <td class="fieldsnew" height="23">
        	                <a href="~sfConfig::get('app_site_url')`/crm/online.php?name=~$agentName`&cid=~$cid`">My online profiles</a>
        	        </td>
        	</tr>
	~/if`
	~if $linkArr.ONP eq 'Y'`
		<tr align="left">
        	        <td class="fieldsnew" height="23">
        	                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?cid=~$cid`&flag=ONP">My online pre-allocated profiles</a>
        	        </td>
        	</tr>
	~/if`
	~if $linkArr.F eq 'Y'`
   		<tr align="left">
    			<td class="fieldsnew" height="23">
				<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=F">My profiles to be followed-up today  (~$followupProfilesForDay`)</a>
    			</td>
   		</tr>
	~/if`
	~if $linkArr.S eq 'Y'`
		<tr align="left">
        	        <td class="fieldsnew" height="23">
        	                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=S">My paid profiles expiring in next 30 days  (~$subscriptionExpiringProfiles`)</a>
        	        </td>
        	</tr>
	~/if`
	~if $linkArr.RND eq 'Y'`
		<tr align="left">
        	        <td class="fieldsnew" height="23">
        	                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=RND">My paid profiles not due for renewal yet  (~$profilesRenewalNotDue`)</a>
        	        </td>
        	</tr>
	~/if`
	~if $linkArr.N eq 'Y'`
		<tr align="left">
			<td class="fieldsnew" height="23">
					<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=N">New Profiles for today (~$newProfilesForDay`)</a>
			</td>
		</tr>
	~/if`
        ~if $linkArr.NFS eq 'Y'`
                <tr align="left">
                        <td class="fieldsnew" height="23">
                                        <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=NFS">New Field Sales profiles (~$fieldSalesProfiles`)</a>
                        </td>
                </tr>
        ~/if`
	~if $linkArr.R eq 'Y'`
		<tr align="left">
                	<td class="fieldsnew" height="23">
                	        <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=R">New Renewal Profiles  (~$renewalProfiles`)</a>
                	</td>
        	</tr>
	~/if`
	~if $linkArr.U eq 'Y'`
        	<tr align="left">
        	        <td class="fieldsnew" height="23">
        	                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=U">New Upsell Profiles  (~$upsellProfiles`)</a>
        	        </td>
        	</tr>
	~/if`
	~if $linkArr.FTA eq 'Y'`
                <tr align="left">
                        <td class="fieldsnew" height="23">
                                <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=FTA">New FTO profiles  (~$ftaProfiles`)</a>
                        </td>
                </tr>
        ~/if`
	~if $linkArr.C eq 'Y'`
		<tr align="left">
			<td class="fieldsnew" height="23">
				<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=C">My other allocated profiles  (~$prevHandledProfiles`)</a>
			</td>
		</tr>
	~/if`
	~if $linkArr.FF eq 'Y'`	
		<tr align="left">
	                <td class="fieldsnew" height="23">
	                        <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/outboundProcess?name=~$agentName`&cid=~$cid`&flag=FF">My profiles to be followed-up in future  (~$ffollowupProfilesForDay`)</a>
	                </td>
	        </tr>
	~/if`	
	~if $linkArr.FS eq 'Y'`
	   	<tr align="left">
	    		<td class="fieldsnew" height="23">
	        		<a href="~sfConfig::get('app_site_url')`/crm/followupdetail.php?name=~$agentName`&cid=~$cid`">My future follow-up summary</a>
	    		</td>
	   	</tr>
	~/if`
</table>
<br><br>
~include_partial('global/footer')`
    ~if get_slot('optionaljsb9Key')|count_characters neq 0`
                ~JsTrackingHelper::setJsLoadFlag(1)`
    ~/if`
</body>
<script src="~sfConfig::get('app_img_url')`/min/?f=/js/timetracker_js.js"></script>
 ~if get_slot('optionaljsb9Key')|count_characters neq 0`
                ~JsTrackingHelper::getTailTrackJs(0,true,2,"http://track.99acres.com/images/zero.gif","~get_slot('optionaljsb9Key')`")`
 ~/if`
<script>
var objtnm = new tnm();
objtnm.tnmPageId="~get_slot('optionaljsb9Key')`";
$(document).ready(function(){
window.onload = function () {objtnm.init();}
 window.onunload = function() { objtnm.LogCatch.call(objtnm);}
});
</script>

</html>
