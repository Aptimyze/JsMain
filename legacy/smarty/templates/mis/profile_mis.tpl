<html>
<head>
        <title>Jeevansathi.com - MIS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="../jsadmin/jeevansathi.css" type="text/css">
<style>
        DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>
                                                                                                                             
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
        <td valign="top" width="30%" bgcolor="#efefef"></td>
        <td valign="top" width="40%" bgcolor="#efefef" align="center"><img src="../profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
        <td valign="bottom" width="30%" bgcolor="#efefef">
        </td>
</tr>
</tbody>
</table>
<br>
<br>
<form action="profile_mis.php" method="post">
<input type="hidden" name="checksum" value="~$checksum`"></input>
~if $searchFlag eq 1`
	<br /><br />
	 <table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
	   <tr width=100% border=1>
	    <td width=30% class="formhead" height="23" align="center"><font><b>Welcome </b></font></td>
 	 <td width=30% class="formhead" align='CENTER' height="23">
 	    <a href="mainpage.php?cid=~$checksum`">Click here to go to mainpage</a>
	    </td>
	    <td width=30% class="formhead" align='CENTER' height="23">
	     <a href="/jsadmin/logout.php?cid=~$checksum`">Logout</a>
	    </td>
	   </tr>
	  </table>
	

        <br><br>
	
	<table width=100% align="center">
	<tr class="formhead">
        <td colspan=100% align="center">&nbsp;Month : ~$month_name` &nbsp;&nbsp; Year : ~$searchYear`</td>
        </tr>
        </table>
~if $nodata eq 'Y'`
 <table width=100% align="center" border=0 cellspacing=2 cellpadding=5>
        <tr class="label">
        <td align="center">
~$msg`
        </td>
        </tr>
~else`

	<table width=100% align="center" border=0 cellspacing=2 cellpadding=5>
       	<tr class="label">
       	<td align="center">&nbsp;Day</td>
    		~foreach from=$monthDaysArray item=v`
        <td align="center" width=6% >&nbsp;~$v`</td>
      			~/foreach`
         <td align="center">&nbsp;Total</td>
     	 </tr>
	~if $flag_type eq 'Y'`
	~foreach name=outer key=loc item=rows from=$count`
	<tr class= "label">
	<td align="center">&nbsp;~$loc`</td>
    		~foreach from=$monthDaysArray item=v`
        <td align="center" width=6% >&nbsp;~$rows[$v]`</td>
      		~/foreach`
         <td align="center">&nbsp;~$total[$loc]`</td>
     	 </tr>
	~/foreach`
	<tr class= "label">
	<td align="center">&nbsp;Total</td>
    	~foreach name=tot from=$monthDaysArray item=v`
	<td align="center" width=6% >&nbsp;~$tot_d[$v]`</td>
      		~/foreach`
	<td align="center" width=6% >&nbsp;~$tot`</td>
        </tr>
	~else`
	<tr class="label">
       	<td align="center">&nbsp;Profiles</td>
    		~foreach from=$monthDaysArray item=v`
        <td align="center" width=6% >&nbsp;~$data[$v]`</td>
      			~/foreach`
         <td align="center">&nbsp;~$total`</td>
     	 </tr>
	~/if`
~/if`		
	</table>
	
~else`
	  <table width=35% border=0 align="center">
        <tr >
	<td rowspan=2>
	 <input type="radio" checked="checked" name="type" value="t_date">Date Wise:</td>	
        </td>
        <td>
	  Month:
        </td>
        <td>
                <select name="month">
                        ~foreach from=$monthArray key=k item=v`
                        ~if $k!=$todMonth`
                        <option value="~$k`">~$v`</option>
                        ~else`
                        <option value="~$k`" selected="selected">~$v`</option>
                        ~/if`
                        ~/foreach`
                </select>
        </td>
        </tr>
        <tr>
        <td>
	  Year:
        </td>
        <td>
                <select name="year">
                        ~section name=formYearLoop loop=$yearArray`
                        ~if $yearArray[formYearLoop]!=$todYear`
                        <option value="~$yearArray[formYearLoop]`">~$yearArray[formYearLoop]`</option>
                        ~else`
                        <option value="~$yearArray[formYearLoop]`" selected="selected">~$yearArray[formYearLoop]`</option>
                        ~/if`
                        ~/section`
                </select>
        </td>
        </tr>
	<tr>
	</tr>
	<tr>
	</tr>
		
	<tr>
		<td>
		<input type="radio" name="type" value="location"> Location Wise
		</td>
		<td>
		<select name= "location">
		<option value="all" selected= "selected">ALL</option>
		~section name= cen loop= $op_center`
		<option value="~$op_center[cen]`">~$op_center[cen]`</option>
		~/section`
		</select>
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="type" value="operator"> Operator Wise
		</td>
		<td>
		<select name= "operator">
		<option value="all" selected= "selected">ALL</option>
		~section name= loc loop= $op_uname`
		<option value="~$op_uname[loc]`">~$op_uname[loc]`</option>
		~/section`
		</select>
	</tr>			
        <tr>
	        <td width=100% align="center">
	        <input type="submit" name="submit1" value="GO!"></input>
	        </td>
        </tr>
        </table>
</form>
~/if`
</body>
</html>
