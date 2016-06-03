~include_partial('global/header')`
<style>
	tr {height:20px;}
</style>
<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" font-size:1em;>
	~if $SubmissionError`
		<div align="center" style="margin-top:20px;color:red;">
			<b>Submission Error</b> 
			<div style="font-size:18px;margin-top:8px;"><a href="#" onclick="goBack();" >Go Back</a></div>
		</div>
	~else`
	<div align=center style="margin-bottom:5px;font-size:10px;"><b><h2>Search user for removal of FSO visit</h2> </b></div>
	 <table width="600" border="1" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
		<form name="searchUser" id="usernameForm" method="POST" action="~sfConfig::get('app_site_url')`/operations.php/profileVerification/fsoRemoval">
		    <tr class=label align=center>
                        <td width=20%>&nbsp;Enter Email/Username to Search:</td>
                        <td width=10%>
                            <input name="username" id="username">
				~if $userNameInvalid==1`
					<div align="center" style="margin-top:5px;color:red;">
						<b>Enter a valid Email/Username.</b> 
					</div>
				~/if`
			</td>	
                        <td width=10%><input type="submit" name="Submit" value="Search" >&nbsp;&nbsp;&nbsp;</td>
                    </tr>
			<input type=hidden name="name" value="~$name`">
			<input type=hidden name="cid" value="~$cid`">
		</form>
         </table>
        ~if $output == "Success"`
		<div align=center><br><b> <font color="green"> FSO Visit Successfully Removed for -<font color="blue"> ~$username`</font></font> </b></div><br></br>
	~/if`
        ~if $username and $output neq "Success" and $userNameInvalid neq 1`
 	<div align=center><br><b>FSO visit removal for User Name : ~$username` </b></div><br></br>
	<form name="list" id="formScreenSuc" enctype="multipart/form-data" action="~sfConfig::get('app_site_url')`/operations.php/profileVerification/fsoRemovalSubmit" method="POST">
		<table width="100%" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
			<tr class=label align=center border=no>
				<td width=20%>Username</td>
				<td width=20%>FSO Visit</td>
				<td width=60%>~if $visit['0'] neq 'NO'`Reason For removing FSO Visit~else`DELETION LOG~/if`</td>
			</tr>	

			<input type=hidden name="allDocIds[]" value='~$value["DOCUMENT_ID"]`'>
			<tr>
				<td align=center>~$username`</td>
                                <td align=center style="color:~if $visit['0'] eq 'NO'`red;~else`green;~/if`"><b>~$visit['0']`</b></td>
                                <td align=center>
                                    ~if $visit['0'] neq 'NO'`
                                    <select name='reason' id="reasonDelete">
                                            <option value="-1">Please select resean for deletion</option>
                                            ~foreach from=$reason key=key item=value`
                                                <option value="~$key`">~$value`</option>
                                            ~/foreach`
                                        </select>
                                    <input type="text" id="other" name="reasonOther" value="" maxlength="40" style="display: none;">
                                    ~else if $visit['1'] neq '0'`
                                    <table>
                                        <tr><td>DELETED BY</td><td>-</td><td>~$visit['1']['DELETED_BY']`</td></tr>
                                        <tr><td>REASON</td><td>-</td><td>~$visit['1']['DELETE_REASON']`</td></tr>
                                        <tr><td>TIME</td><td>-</td><td>~$visit['1']['DELETION_TIME']`</td></tr>
                                    </table>
                                    ~else`
                                    FSO Never Visited<br>FSO Visit Never Deleted
                                     ~/if`
                                </td>
                          </tr>
			
                        ~if $visit['0'] neq 'NO'`
 			<tr align = "CENTER">
				<input type=hidden name="name" value="~$name`">
				<input type=hidden name="cid" value="~$cid`">
				<input type=hidden name="profileid" value="~$profileid`">
                                <td colspan="5"><br></br><input type="submit" id="submitDelete" name="Submit" value="Submit" disabled="true" >&nbsp;&nbsp;&nbsp;
                	</tr>
                        ~/if`
		</table>
	</form>
	~/if`
	
        ~/if`
	<br><br><br></br></br></br>
	~include_partial('global/footer')`
        <script>
	
	 $('#reasonDelete').change(function(e) {
          $("#submitDelete").removeAttr("disabled");
           var selected = $( "#reasonDelete" ).val();
           
            if(selected=="0"){
                $("#other").show();
           }
           else if(selected=="-1"){
                $("#submitDelete").attr("disabled","true");
            $("#other").hide();}
           else{
              
               $("#other").hide();
           }
           
	});
        $('#usernameForm').submit(function(e) {
        if ($.trim($("#username").val()) === "") {
             e.preventDefault();
             alert('Username/Email is can\'t be left Blank.');
         }
        });
        $('#formScreenSuc').submit(function(e) {
        if ($.trim($("#reasonDelete").val()) === "0") {
          if ($.trim($("#other").val()) === "") {
                 e.preventDefault();
                 alert('For FSO visit deletion, You must specify a detailed reason.');
            }
        }
        });
        
	function goBack()
	{
		window.history.go(-2);
	}
	</script>
</body>
