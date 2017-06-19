<html>
<head>
   	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <input type="hidden" name="monthName" value="~$monthName`">
        <input type="hidden" name="yearName" value="~$yearName`">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
			</tr>
	        <tr class="formhead" align="center">
	                <td colspan="2" style="background-color:lightblue"><font size=3>Renewal Follow-up Status MIS</font></td>
	        </tr>
		        <tr></tr>
		        <tr></tr>
		        <tr></tr>
		        <tr></tr>
		        <tr></tr>
		        <tr></tr>
		        <tr></tr>
		        <tr></tr>
		</table>

	~if $overall_sales_head_check eq 1`
		<p align=center><font size=3 color=red><b> Please give 'Sales Head - Overall' privilege to one user. </b></font></p>	
	~/if`
	~if $overall_sales_head_check neq 1`
        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
            <td width=4% align=center>Manager/Supervisor/Executive</td>
            <td width=4% align=center>Profiles without Follow-up Date</td>
            <td width=4% align=center>Renewal Profiles</td>
            <td width=4% align=center>Renewal Profiles Not Followed-up yet</td>
            <td width=4% align=center>Expiring Today</td>
            <td width=4% align=center>Expiring in next 1-3 days</td>
            <td width=4% align=center>Expiring in next 4-7 days</td>
            <td width=4% align=center>Expiring in next 8-14 days</td>
            <td width=4% align=center>Expiring in next 15-21 days</td>
            <td width=4% align=center>Expiring in next 22-29 days</td>
        </tr>
    	~foreach from=$hierarchy item=uu`
       		<tr class=formhead style="background-color:~$background_color[$uu.USERNAME]`">
    		~if $renewalProfilesNotFollowedup[$uu.USERNAME]`
				<td width=4%>
    				~if $uu.DIRECT_REPORTEE_STATUS eq 1`
						~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
							<b><a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/renewalFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=0&cid=~$cid`">~$uu.USERNAME`</a></b>
					~else`
						~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
							<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/renewalFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=0&cid=~$cid`">~$uu.USERNAME`</a>
					~/if`
				</td>
			~else`
				<td width=4%>
    				~if $uu.DIRECT_REPORTEE_STATUS eq 1`
						~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
							<b>~$uu.USERNAME`</b>
					~/if`
    				~if $uu.DIRECT_REPORTEE_STATUS eq 0`
						~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
							~$uu.USERNAME`
					~/if`					
				</td>
			~/if`

    		~if $profilesWithoutFollowups[$uu.USERNAME]`
		        <td width=4% align=center>
	        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/renewalFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=1&cid=~$cid`">~$profilesWithoutFollowups_t[$uu.USERNAME]`</a> 
		        </td>
		    ~else`
		        <td width=4% align=center>~$profilesWithoutFollowups_t[$uu.USERNAME]`</td>
		    ~/if`

    		~if $renewalProfiles[$uu.USERNAME]`
		        <td width=4% align=center>
	        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/renewalFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=2&cid=~$cid`">~$renewalProfiles_t[$uu.USERNAME]`</a> 
		        </td>
		    ~else`
		        <td width=4% align=center>~$renewalProfiles_t[$uu.USERNAME]`</td>
		    ~/if`

    		~if $renewalProfilesNotFollowedup[$uu.USERNAME]`
		        <td width=4% align=center>
	        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/renewalFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=3&cid=~$cid`">~$renewalProfilesNotFollowedup_t[$uu.USERNAME]`</a> 
		        </td>
		    ~else`
		        <td width=4% align=center>~$renewalProfilesNotFollowedup_t[$uu.USERNAME]`</td>
		    ~/if`

			~foreach from=$expireRangeArr key=k item=h`
	        	~if $renewalProfilesNotFollowedupRangeWise[$k][$uu.USERNAME]` 
					<td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/renewalFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=~$k+4`&cid=~$cid`">~$renewalProfilesNotFollowedupRangeWise_t[$k][$uu.USERNAME]`</a>
			        </td>
	        	~else`
			        <td width=4% align=center>~$renewalProfilesNotFollowedupRangeWise_t[$k][$uu.USERNAME]`</td>
        		~/if`
	        ~/foreach`

			</tr>
    	~/foreach`
		</table>
    ~/if`
</body>
</html>
