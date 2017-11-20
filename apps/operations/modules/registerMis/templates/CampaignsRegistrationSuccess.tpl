<html>
<head>
   <title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
        	<script type="text/javascript">
    $(function () {
        var count = 0;
        $('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2015", yearEnd: "~$rangeYear`"});
        $('#date2').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2015", yearEnd: "~$rangeYear`"});
        $('#date1_dateLists_day_list option:selected').prop('selected', false);
        $('#date1_dateLists_day_list').on('click', function(){
        	count = 1;
        });
        $('#date1_dateLists_month_list').on('click', function(){
        	if(count != 1){
        		$('#date1_dateLists_day_list option:selected').prop('selected', false);
        	}
        });
        $('[name="select_condition"]').on('click', function(){
                var selectCondition = $(this).val();
                $("[name='source_countries[]'] option:selected").removeAttr("selected");
                $("[name='source_cities[]'] option:selected").removeAttr("selected");
                if(selectCondition == "city"){
                        $(".city_row").show();
                        $(".country_row").hide();
                }else{
                        $(".city_row").hide();
                        $(".country_row").show();
                }
        });
    });    
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="~sfConfig::get('app_img_url')`/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
</table>
        <form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/registerMis/CampaignsRegistration?cid=~$cid`" method="post">
        <input type="hidden" name="cid" value="~$cid`">

	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
	<tr>
	        <td align="center" class="label"><font size=2>
			<a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font></td>
	</tr>
	<tr></tr>
	<tr class="formhead" align="center">
		<td colspan="2" style="background-color:lightblue"><font size=3>Fetch Keyword, Adgroup, Campaign information (Max 31 days allowed)</font></td>
	</tr>
	<tr></tr>
	</table>

	~if $errorMsg != ''`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		 <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr align="center">
                        <td class="label">
			<font size=2> ~$errorMsg`  </font>
                        </td>
		</tr>
		</table>
  ~/if`
		<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">

		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr align="center">
			<td class="label">
				<input type="hidden" name="range_format" value="DMY" checked="yes" />
				<font size=2>Select Date Range</font>
			</td>
			<td class="fieldsnew">
				<input id="date1" type="text" value="">
				&nbsp;&nbsp;&nbsp;
				<b>To</b>
				&nbsp;&nbsp;&nbsp;
				<input id="date2" type="text" value="">
			</td>
		</tr>
		<tr align="center">
			<td class="label"><font size=2>Select SourceGroup</font></td>
			<td class="fieldsnew">
                                <select multiple name ='source_names[]' size=15 style='min-width: 255px'>
                                  ~foreach from=$sources item=src`
                                    <option value="~$src.GROUPNAME`">~$src.GROUPNAME`</option>
                                  ~/foreach`
                                </select>
			</td>
		</tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
				<input type="submit" name="submit" value="Download">
			</td>
		</tr> 
		</table>
	</form>
</body>
</html>
