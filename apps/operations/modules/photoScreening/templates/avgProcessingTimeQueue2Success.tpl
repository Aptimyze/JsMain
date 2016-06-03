
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
		<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <input type="hidden" name="monthName" value="~$monthName`">
        <input type="hidden" name="yearName" value="~$yearName`">
		<table width="150%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
	    <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
		</tr>
        <tr class="formhead" align="center">
        <td  style=""><font size=3>Avg Time Photo Screening MIS (in mins)</font></td>
        </tr>
	</table>
	<br>

        <table width=500% align=center>
        <tr class=formhead style="">
        <td width=1% align=center>Hours</td>
        ~foreach from=$ddarr item=dd`
	    <td colspan="2" width=1% align=center>~$dd`</td>
		~/foreach`
        </tr>
        <tr class=formhead style="">
		<td width=1% align=center></td>
        ~foreach from=$ddarr item=dd`
		<td width=1% align=center>new</td>
        <td width=1% align=center>edit</td>
        ~/foreach`
        </tr>
        ~$i=0`
        ~$j=0`   
		~assign var=i value=0`
		~assign var=j value=1`
      
      	~foreach from=$hharr item=hh`
        <tr class=formhead style="">
        <td width=1% align=center>~$hh`:00-~$hh+1`:00</td>
       ~foreach from=$ddarr item=dd`
       <td width=1% align=center>~round($newarr[$i][$j])`</td>
       <td width=1% align=center>~round($editarr[$i][$j])`</td>
       ~assign var=j value=$j+1`
       ~/foreach`
       ~assign var=j value=1`
		~assign var=i value=$i+1`
       ~/foreach`
        </tr>
        <tr class=formhead style="">
		<td width=1% align=center>TOTAL</td>
		~assign var=i value=1`
		~for $j=1 to $num`
		<td width=1% align=center>~round($total[$i])`</td>
		~assign var=i value=$i+1`
        ~/for`
        </tr>
    </table>
