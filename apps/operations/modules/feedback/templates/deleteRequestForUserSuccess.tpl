~include_partial('global/header')`

<script type="text/javascript">

	function requestdeletion(obj)
	{ 
		obj.disabled = true;
	var userName = $("#username").val().trim();
	$('#userNp').hide();
	if(userName == '')
	{	
		$('#userNp').show().css('display','inline-block');
		obj.disabled = false;
		return;
	}

	var out = $('input[name=reqDel]:checked').val();
	
	var requestBySelf = 0;
	if(out == 'requestBySelf')
	{
		requestBySelf=1;
	}


	var url='SendDeleteRequestForUser';
	var feed = {};
	feed.username = userName;
	feed.requestBySelf = requestBySelf;
	ajaxData={'feed':feed};

		$.ajax({
				url: url,
				type: "POST",
				data: ajaxData,
				//crossDomain: true,
				success: function(result){
			         if(typeof(result) != 'object')
					  var out = JSON.parse(result);

					 if(typeof(out) != 'undefined' && out['message'] == "username is not correct")
		                 {
		                 		//$('#formForReportAbuse').hide();
		                 		$('#userNp').show().css('display','inline-block');
		                 		obj.disabled = false;
		                 } 

		                 else if (out.responseStatusCode == '0')
		                 {  
		                 		$('#formForRequestDelete').hide();
		                 		$('#successfullDisplay').css('display','block');
		                 		$('#goBackButton').css('display','block');
		                 		obj.disabled = false;
		                 }  	            
		                
		              }

		});

	}

</script>
		<div id ="formForRequestDelete">
		<table width=900 align=center >
			<tr class="formhead">
				<td colspan=5 align="CENTER">REQUEST A DELETE FOR USER</td>
			</tr> <br>
			<tr>
				<td class=fieldsnew width=30% align="CENTER">Enter Username
				</td>
				<td align="center fieldsnew" width=40% colspan=100%><input type="text" id='username' size=30%>
				<p id="userNp" style="display:inline-block; padding-left:50px;font-size: 10px;display: none"><font color="red">Incorrect Username or Username not Entered.</font></p>
				</td>
										
			</tr>


                        <tr align="CENTER">
                                <td width=30% class=fieldsnew>ENTER WHO THE REQUEST IS BY (SELF/SOMEONE ELSE)
                                </td>
                                <td align="center" width=70% >
                                <input type="radio" name="reqDel" value="requestBySelf" checked="true">Requested By Self
			<input type="radio" name="reqDel" value="requestByElse">Requested by Someone Else
                                </td>

                        </tr>
			&nbsp;&nbsp;&nbsp;
                        <tr align="CENTER">
				<td colspan=3>
					<button name="submit" onclick="requestdeletion(this);">Request Deletion </button>
				<td>
                        </tr>
		   </table>
	</div>


	<tr align=center><td class=fieldsnew colspan=100%><font size=2><b style="display: none" align = "CENTER" id ="successfullDisplay">Request For deletion has been successfully raised. </b></font></td></tr>
<tr align=center> <a href=~$linkToGoBackToDeleteRequest`> <font size=2 color="red"><b align = "center" style="display: none" id="goBackButton">Go Back To Raise another request . </b></font></a></tr>
~include_partial('global/footer')`
