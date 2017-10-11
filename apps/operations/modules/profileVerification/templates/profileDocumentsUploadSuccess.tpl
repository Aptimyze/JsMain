~include_partial('global/header')`
<style>
        tr {height:40px;}
</style>
<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	
         <div align=center><b><h2>Upload Profile Verification Documents</h2> </b></div><br></br>
	 <form name="searchUser" id="searchUser" method="POST" action="~sfConfig::get('app_site_url')`/operations.php/profileVerification/profileDocumentsUpload">
		<input type=hidden name="cid" value="~$cid`">
                <input type=hidden name="execname" value="~$execname`">
	 	<table width="600" border="1" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
		    <tr class=label align=center>
                        <td width=20%>&nbsp;Enter Username to Search:</td>
                        <td width=10%>
				<input name="username" id="user">
				<div id="nouserError" style="display:none">
					<font color="red"> Please enter a Username</font>
				</div>
				~if $error==1`
					<br><font color="red"> Enter a valid Username</font> </br>
				~/if`
			</td>	
                        <td width=10%><input type="submit" name="Submit" value="Search" >&nbsp;&nbsp;&nbsp;</td>
                    </tr>
		 </table>
	</form>
~if $error!=1  && $username`
~if isset($output)`
	~if $output == "Success"`
		<div align=center><br><b> <font color="green"> Documents Successfully Uploaded for ~$username`</font> </b></div><br></br>
	~else`
	<div align=center><br><b> <font color="red"> Some Error Occurred.. Please Upload after some time!!</font>  </b></div><br></br>
	~/if`
~/if`
 	<div align=center><br><b>Uploaded Documents for <font color="green">~$username`</font></b></div><br></br>
	<form name="list" id="UploadDocs" enctype="multipart/form-data" action="~sfConfig::get('app_site_url')`/operations.php/profileVerification/profileDocumentsUpload" method="POST">
		<input type=hidden name="username" value="~$username`">
		<input type=hidden name="cid" value="~$cid`">
		<input type=hidden name="execname" value="~$execname`">
		<table width="100%" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
			<tr class=label align=center border=no>
				<td width=5%>SI. No.</td>
				<td width=20%>Profile Attribute</td>
				<td width=20%>Attribute Value</td>
				<td width=20%>Document Type</td>
				
			</tr>	
			~foreach from=$documentListMapping key=key item=value name=attribute`
			<tr id="~$key`">
				<td align=center>~$smarty.foreach.attribute.index +1`</td>
                                <td>Proof of ~$docAttributes[$key]`</td>
				<td>~$attributeValues[$key]`</td>
                                <td>
					<select name="doc[~$key`]" class="~$key`">
						<option value="">Please Select</option>
						~foreach from=$value key=dkey item=dvalue`
							<option value="~$dvalue`">~$docs[$dvalue]`</option>
						~/foreach`
					</select>
					<div class="notSelected" style="display:none">
                                        	<font color="red"> Please Select an Option</font>
	                                </div>

				</td>
                                <td width=10% align=center>
					<input name="~$key`" value='~$key`' type="file"  class="~$key`">
					~if isset($fileError) && isset($fileError[$key])`
						<br><font color="red">~$fileError[$key]`</font> </br>
					~/if`					
					 <div class="notUploaded" style="display:none">
                                                <font color="red"> Please Select a Path</font>
                                        </div>

				</td>
                                <td width=10%>
					~if isset($documentView) && isset($documentView[$key])`
						<a href = "~sfConfig::get('app_site_url')`/operations.php/profileVerification/view?cid=~$cid`&execname=~$execname`&username=~$username`&doc=~$key`" target="_blank">View</a>

					~/if`
				</td>
			</tr>
			~/foreach`
 			<tr align = "CENTER">
                		<td colspan="5"><br></br><input type="submit" name="Submit" value="Upload" >&nbsp;&nbsp;&nbsp;
                	</tr>
		</table>
	</form>

~/if`
	<br><br><br></br></br></br>
	~include_partial('global/footer')`
<script>
    function goBrowserBack() {
        window.history.back();
    }
	window.onload = function (){
		$("#searchUser").submit(function(event)
		{
			var username= $("#user").val();
			if(username =="" || username == null)
			{
				$("#nouserError").show();
				event.preventDefault();
			}
		});
		$("#UploadDocs").submit(function(event)
        	{
			var bool = true;
			var count = 0;

			$('tr[id]').each(function(){
				var id =$(this).attr('id'); 
				var selected = $(this).find('select').val();
				var filePath = $(this).find('input').val();
				if(selected=="" && filePath!="")
				{
					$(this).find('.notSelected').show();
					bool= false;
					count++;
				}
				else if(selected!="" && filePath=="")
				{
                                	$(this).find('.notUploaded').show();
                                	bool= false;
					count++;
                        	}
				else
				{
					if(selected!="" && filePath!="")
						count++;
					$(this).find('.notUploaded').hide();
					$(this).find('.notSelected').hide();
				}
			});
			if(!count)
			{
				alert("Please upload some document!!");
				bool = false;
			}
			if(!bool)
				 event.preventDefault();
        	});
	}
</script>
</body>
