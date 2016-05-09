~include_partial('global/header')`
<style>
        tr {height:40px;}
</style>

<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	~if $error==1`
		<br><font color="red"> Enter a valid Username</font> </br>
	~else`

		~if !isset($documents)`
			 <div align=center><br><b>No Uploaded Documents for ~$username` for ~$attribute` </b></div><br></br>
		~else`
		<div align=center><br><b>Uploaded Documents for <font color="green">~$username`</font> for <font color="green">~$attribute`</font></b></div><br></br>
			<table width="70%" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
                        	<tr class=label align="center">
                                	<td width=10%>SI. No.</td>
                                	<td width=30%>Document Type</td>
                                	<td width=30%>Verify Status</td>
	                        </tr>

				~foreach name=document from=$documents item= value`
					<tr>
						<td align=center>~$smarty.foreach.document.index +1`</td>
						<td align=center>~$docTypes[$value.DOCUMENT_TYPE]`</td>
						<td align=center>
							~if $value.VERIFIED_FLAG eq "U"`
								Under Screening
							~elseif $value.VERIFIED_FLAG eq "Y"`
								Accepted
							~elseif $value.VERIFIED_FLAG eq "N"`
								Rejected
							~/if`
						</td>	
						 <td width=10%>
                                                <a class="deleteDoc" href = "~sfConfig::get('app_site_url')`/operations.php/profileVerification/delete?cid=~$cid`&execname=~$execname`&username=~$username`&doc=~$doc`&id=~$value.DOCUMENT_ID`">Delete</a>
						
                               			 </td>
					</tr>
					<tr>
						<td colspan=4 align =center>
							<img src="~$value.DOCURL`">
						</td>
					</tr>	
				~/foreach`		
			</table>	
		~/if`

	~/if`
	<br><br><br></br></br></br>
	~include_partial('global/footer')`
<script>
	window.onload = function (){
		$(".deleteDoc").click(function(event){
        		var result = confirm("Do you want to delete this proof?");
			if(!result)
                        	event.preventDefault();
                });
        }       
</script>
</body>
