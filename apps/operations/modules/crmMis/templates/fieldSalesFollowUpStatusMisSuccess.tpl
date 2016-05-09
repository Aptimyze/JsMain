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
	                <td colspan="2" style="background-color:lightblue"><font size=3>Sales Follow-up Status MIS</font></td>
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
            <td width=4% align=center>Allocation Bucket</td>
            <td width=4% align=center>Today's Follow-ups</td>
            <td width=4% align=center>Yesterday's Follow-ups</td>
            <td width=4% align=center>Day Before Yesterday's Follow-ups</td>
            <td width=4% align=center>Earlier Than Day Before Yesterday's Follow-ups</td>
            <td width=4% align=center>Total Pending Follow-ups</td>
            <td width=4% align=center>Future Follow-ups</td>
        </tr>
    	~foreach from=$hierarchy item=uu`
    		~if $is_visible[$uu.USERNAME] eq 1`
       		<tr class=formhead style="background-color:~$background_color[$uu.USERNAME]`">
    		~if $allocationBucket_free[$uu.USERNAME]`
				<td width=4%>
    				~if $uu.DIRECT_REPORTEE_STATUS eq 1`
						~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
							<b><a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=0&cid=~$cid`">~$uu.USERNAME`</a></b>
					~/if`
    				~if $uu.DIRECT_REPORTEE_STATUS eq 0`
						~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
							<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=0&cid=~$cid`">~$uu.USERNAME`</a>
					~/if`
				</td>
		        <td width=4% align=center>
		        	~if $allocationBucket_free[$uu.USERNAME] neq 0` 
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=1&cid=~$cid`">~$allocationBucket_free[$uu.USERNAME]`</a> 
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
		        <td width=4% align=center>~if $allocationBucket_free[$uu.USERNAME] neq 0` ~$allocationBucket_free[$uu.USERNAME]` ~/if`</td>
		    ~/if`

        	~if $todayFollowUps[$uu.USERNAME] neq 0` 
		        <td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=2&cid=~$cid`">~$todayFollowUps_t[$uu.USERNAME]`</a> </td>
        	~else`
				        <td width=4% align=center>~if $todayFollowUps_t[$uu.USERNAME] neq 0` ~$todayFollowUps_t[$uu.USERNAME]` ~/if`</td>
        	~/if`
        	~if $yesterdayFollowUps[$uu.USERNAME] neq 0` 
		        <td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=3&cid=~$cid`">~$yesterdayFollowUps_t[$uu.USERNAME]`</a></td> 
        	~else`
				        <td width=4% align=center>~if $yesterdayFollowUps_t[$uu.USERNAME] neq 0` ~$yesterdayFollowUps_t[$uu.USERNAME]` ~/if`</td>
        	~/if`
        	~if $dayBeforeYesterdayFollowUps[$uu.USERNAME] neq 0` 
		        <td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=4&cid=~$cid`">~$dayBeforeYesterdayFollowUps_t[$uu.USERNAME]`</a> </td>
        	~else`
				        <td width=4% align=center>~if $dayBeforeYesterdayFollowUps_t[$uu.USERNAME] neq 0` ~$dayBeforeYesterdayFollowUps_t[$uu.USERNAME]` ~/if`</td>
		        </td>
        	~/if`
        	~if $earlierThanDayBeforeYesterdayFollowUps[$uu.USERNAME] neq 0` 
		        <td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=5&cid=~$cid`">~$earlierThanDayBeforeYesterdayFollowUps_t[$uu.USERNAME]`</a> </td>
        	~else`
				        <td width=4% align=center>~if $earlierThanDayBeforeYesterdayFollowUps_t[$uu.USERNAME] neq 0` ~$earlierThanDayBeforeYesterdayFollowUps_t[$uu.USERNAME]` ~/if`</td>
        	~/if`
        	~if $totalPendingFollowUps[$uu.USERNAME] neq 0` 
		        <td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=6&cid=~$cid`">~$totalPendingFollowUps_t[$uu.USERNAME]`</a> </td>
        	~else`
				        <td width=4% align=center>~if $totalPendingFollowUps_t[$uu.USERNAME] neq 0` ~$totalPendingFollowUps_t[$uu.USERNAME]` ~/if`</td>
        	~/if`
        	~if $futureFollowUps[$uu.USERNAME] neq 0` 
		        <td width=4% align=center>
		        		<a href = "~sfConfig::get('app_site_url')`/operations.php/crmMis/fieldSalesFollowUpStatusMisResultScreen2?exec=~$uu.USERNAME`&column=7&cid=~$cid`">~$futureFollowUps_t[$uu.USERNAME]`</a></td>
        	~else`
				        <td width=4% align=center>~if $futureFollowUps_t[$uu.USERNAME] neq 0` ~$futureFollowUps_t[$uu.USERNAME]` ~/if`</td>
        	~/if`
			</tr>
			~/if`
    	~/foreach`
</table>
    ~/if`
</body>
</html>
