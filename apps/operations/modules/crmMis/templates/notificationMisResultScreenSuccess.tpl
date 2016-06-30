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
<table width="100%" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:lightblue"><font size=3>Notification MIS</font>
	  </td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:PeachPuff"><font size=2><b>Notification Type: ~$notificationType`</b></font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:PeachPuff"><font size=2><b>Month : ~$displayDate`</b></font></td>
  </tr>
</table>
        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
		~if $notifExist`
			<td width=4% align=center>Count Type</td>
			~foreach from=$newData item=count key=day`
	                <td width=4% align=center>~$day`</td>
			~/foreach`
		~else`
			<td width=4% align=center>Notification does not exist</td>
		~/if`
        </tr>
~if $channelKey eq 'A_I'`
	~assign var='color' value='#F0F0F0'`
	~assign var='loopVal' value=0`
        ~foreach from=$countTypeArr item=name key=type`
        <tr class=formhead ~if $loopVal1%2`style="background-color:~$color` ~/if`">
	    ~assign var='loopVal1' value=$loopVal1+1`	
            <td width=4% align=left>~$name`</td>
	     ~foreach from=$newData item=countVal key=day`	
	            <td width=4% align=center>
			~$newData[$day][$name]`
        	    </td>
	     ~/foreach` 	
        </tr>
        ~/foreach`
~else`
        ~assign var='color' value='#F0F0F0'`
        ~assign var='loopVal' value=0`
        ~foreach from=$countTypeArr item=name key=type`
        <tr class=formhead ~if $loopVal1%2`style="background-color:~$color` ~/if`">
            ~assign var='loopVal1' value=$loopVal1+1`
            <td width=4% align=left>~$name`</td>
             ~foreach from=$newData item=countVal key=day`
                    <td width=4% align=center>
                        ~$newData[$day][$name]`
                    </td>
             ~/foreach`
        </tr>
        ~/foreach`
~/if`

	<tr></tr>
       </table>
</body>
</html>
