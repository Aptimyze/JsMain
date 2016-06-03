<!doctype html public "-//w3c//dtd html 4.0 transitional//en">

<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
 </head>
 ~include file="head.htm"`
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width="3%" class="formhead" height="23">&nbsp;</td>
    <td width="62%" class="formhead" height="23"><font><b>Welcome :~$username`</b></font></td>
    <td width="6%" class="formhead" align='RIGHT' height="23">
     <a href="logout.php?cid=~$cid`">
      Logout
     </a>
    </td>
    <td width="3%" class="formhead" height="23">
     &nbsp;
    </td>
   </tr>
  </table>
  <table width=100% align="CENTER" >
   <tr align="CENTER">
    <td class="label" width=5% height="20"><b>S.No.</b></td>      
    <td class="label" width=15% height="20"><b>User</b></td>
    <td class="label" width=6% align="center" height="20"><b>Jan</b></td>
    <td class="label" width=6% align="center" height="20"><b>Feb</b></td>
    <td class="label" width=6% align="center" height="20"><b>Mar</b></td>
    <td class="label" width=6% align="center" height="20"><b>Apr</b></td>
    <td class="label" width=6% align="center" height="20"><b>May</b></td>
    <td class="label" width=6% align="center" height="20"><b>Jun</b></td>                
    <td class="label" width=6% align="center" height="20"><b>Jul</b></td>
    <td class="label" width=6% align="center" height="20"><b>Aug</b></td>
    <td class="label" width=6% align="center" height="20"><b>Sep</b></td>
    <td class="label" width=6% align="center" height="20"><b>Oct</b></td>
    <td class="label" width=6% align="center" height="20"><b>Nov</b></td>
    <td class="label" width=6% align="center" height="20"><b>Dec</b></td>
    <td class="label" width=8% align="center" height="20"><b>Total</b></td>    
   </tr>            
	~section name=index loop=$photo_operator_arr` 
    <tr class="label" align="CENTER">    
     <td height="20" width="5%" rowspan="2">~$smarty.section.index.index_next`.</td>            
     <td height="20" align="CENTER" width="15%" rowspan="2">~$photo_operators[index]`</td>                         
          
     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Jan.APPROVED)`     
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=01&status=APPROVED">
     	~$photo_operator_arr[index].Jan.APPROVED`
     	</a>     	
     ~else`     
     	0     
     ~/if`		
     </td>          
     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Feb.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=02&status=APPROVED">
     	~$photo_operator_arr[index].Feb.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     
     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Mar.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=03&status=APPROVED">
     	~$photo_operator_arr[index].Mar.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Apr.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=04&status=APPROVED">
     	~$photo_operator_arr[index].Apr.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].May.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=05&status=APPROVED">
     	~$photo_operator_arr[index].May.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Jun.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=06&status=APPROVED">
     	~$photo_operator_arr[index].Jun.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Jul.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=07&status=APPROVED">
     	~$photo_operator_arr[index].Jul.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Aug.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=08&status=APPROVED">
     	~$photo_operator_arr[index].Aug.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Sep.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=09&status=APPROVED">
     	~$photo_operator_arr[index].Sep.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Oct.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=10&status=APPROVED">
     	~$photo_operator_arr[index].Oct.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Nov.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=11&status=APPROVED">
     	~$photo_operator_arr[index].Nov.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Dec.APPROVED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=12&status=APPROVED">
     	~$photo_operator_arr[index].Dec.APPROVED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>
     <td height="20" align="CENTER" width="8%" rowspan="2">~$photo_operator_total[index]`</td>         
     </tr>
     
     <tr class="fieldsnew" align="CENTER">
     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Jan.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=01&status=DELETED">
     	~$photo_operator_arr[index].Jan.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     
     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Feb.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=02&status=DELETED">
     	~$photo_operator_arr[index].Feb.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     
     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Mar.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=03&status=DELETED">
     	~$photo_operator_arr[index].Mar.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Apr.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=04&status=DELETED">
     	~$photo_operator_arr[index].Apr.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].May.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=05&status=DELETED">
     	~$photo_operator_arr[index].May.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Jun.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=06&status=DELETED">
     	~$photo_operator_arr[index].Jun.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Jul.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=07&status=DELETED">
     	~$photo_operator_arr[index].Jul.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Aug.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=08&status=DELETED">
     	~$photo_operator_arr[index].Aug.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Sep.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=09&status=DELETED">
     	~$photo_operator_arr[index].Sep.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Oct.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=10&status=DELETED">
     	~$photo_operator_arr[index].Oct.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Nov.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=11&status=DELETED">
     	~$photo_operator_arr[index].Nov.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>     

     <td width=6% align="center" height="20">
     ~if ($photo_operator_arr[index].Dec.DELETED)`
     	<a href="showscreeningdetails.php?operator=~$photo_operators[index]`&month=12&status=DELETED">
     	~$photo_operator_arr[index].Dec.DELETED`
     	</a>
     ~else`
     	0
     ~/if`		
     </td>
	</tr>
   ~/section`            
   <tr class="label" align="center">
   <td width=5% align="center" height="20">Total</td>   
	 <td width=6% align="center" height="20"></td> 	
   	 <td width=6% align="center" height="20">~$month_total.Jan`</td>
     <td width=6% align="center" height="20">~$month_total.Feb`</td>
     <td width=6% align="center" height="20">~$month_total.Mar`</td>
     <td width=6% align="center" height="20">~$month_total.Apr`</td>
     <td width=6% align="center" height="20">~$month_total.May`</td>
     <td width=6% align="center" height="20">~$month_total.Jun`</td>
     <td width=6% align="center" height="20">~$month_total.Jul`</td>
     <td width=6% align="center" height="20">~$month_total.Aug`</td>
     <td width=6% align="center" height="20">~$month_total.Sep`</td>
     <td width=6% align="center" height="20">~$month_total.Oct`</td>     
     <td width=6% align="center" height="20">~$month_total.Nov`</td>
     <td width=6% align="center" height="20">~$month_total.Dec`</td>  
     <td width=8% align="center" height="20">~$complete_total`</td>   
   </tr>
   <tr bgcolor="#fbfbfb">
    <td colspan="14" height="21">
     &nbsp; 
    </td>
   </tr>
   <tr>
    <td colspan="7" height="21">
     &nbsp; 
    </td>
   </tr>    
  </table>
 <br><br><br><br><br><br><br>
 ~include file="foot.htm"`
 </body>
</html>
