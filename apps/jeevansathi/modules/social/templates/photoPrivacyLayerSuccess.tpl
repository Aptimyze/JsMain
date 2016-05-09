<div class="pink" style="width:700px; height:470px;">
	<div class="topbg">
		<span class="title lf">
			Photo Privacy Feature
		</span>
		<span class="fr" style="display:none;" id="closeOption" >
			<a class="blink1 " href="#" onclick="$.colorbox.close(); return false;">
				Close [X]
			</a>
		</span>
	</div>
	<div class="clear">
	</div>

	<div class="photoscrollbox2_1 " id="disableTbRemove" >
		<input type="hidden" id="insertedId" value="~$insertedId`">
		<div class="fs16">
			You have chosen to keep your photos as "Visible to accepted members" <br>Photo privacy is now available on paid membership accounts only. 
		</div>
		<div class="sp15">
		</div>
		<div class="sp15">
		</div>
		<div class="fs16">
			You can now choose to:
		</div>
		<div class="sp15">
		</div>
		<div style="position:relative" class="box">
			<input type="radio" class="chbx vam fl" style="margin-left:-19px;" name="selection" id="1">
			<div class="fl">
				<div class=" fs16 b">
					Continue using the site as free member.
				</div>
				<div style="background: none repeat scroll 0% 0% rgb(115, 115, 115); color: rgb(255, 255, 255); position: absolute; top: 19px; left: 228px; padding: 2px 5px;" class="widthauto  fl fs16">
					or
				</div>
				<div class="sp15">
				</div>
				<div class="fs16">
					Make photos visible to all members
				</div>
				<div class="sp15">
				</div>
				<div>
					Your photos are safe
					<div  style="margin-left:0px">
							1. Photos are watermarked<br>
							2. Photos cannot be<br>&nbsp;&nbsp;&nbsp;downloaded  or  copied via<br>&nbsp;&nbsp;&nbsp;right click
					</div>
				</div>
			</div>
		</div>
		<div class="box" style="position:relative; margin-left:12px">
			<input type="radio" class="chbx vam fl"  style="margin-left:-19px" name="selection" id="2" >
			<div class="fl">
				<div class=" fs16 b">
					Continue keeping your photo private.
				</div>
				<div style="background: none repeat scroll 0% 0% rgb(115, 115, 115); color: rgb(255, 255, 255); position: absolute; top: 19px; left: 228px; padding: 2px 5px;" class="widthauto  fl fs16">
					or
				</div>
				<div class="sp15">
				</div>
				<div class="fs16">
					Become a Paid Member
				</div>
				<div class="sp15">
				</div>
			</div>
		</div>
		<div class="box" style="width:auto;border:none;">
			<input type="radio" class="chbx vam fl"  style="margin-left:-19px" name="selection" id="3">
			<div class="fl"> 
				<div class=" fs16 b">
					Remove your 
					<br>
						photo
				</div>
			</div>
		</div>
		<div class="clr">
		</div>
		<div class=" sp15">
		</div>
		<div class=" sp15">
		</div>
		<div style="color:red; text-align:center" >
			<span style="display:none;" id="errorMsg">
				Please choose an option to continue 
			</span>
		</div>
		<div class="sp5">
		</div>
		<div class="center fullwidth">
			<input style="cursor:pointer;" type="Submit" value="Select & Continue" class="fs20 btn-green-submit" onclick="submitAction();return false;">
		</div>

		<div class="sp15">
		</div>
		<div class="sp8">
		</div>
		<div class="sp8">
		</div>

		<div class="clear">
		</div>
		<div class="clear">
		</div>
		<br><br>
	</div>


	<div id="next1" class="photoscrollbox2_1 " style="display:none;" >
		<div class="fs16">Your profile photo is now visible to all the members. We ensure safety of your photos<br>
		</div>
		<div class="sp15">
		</div>
		<div class="sp15">
		</div>
		<div class="fs16">
			1. Photos are watermarked<br>
			2. Photos cannot be downloaded or copied via right click
		</div>
		<div class=" sp15">
		</div>
		<div class=" sp15">
		</div>
		<div class="fs16">
			Also you will get higher number of responses on your profile
		</div>
	</div>

	<div id="next3" class="photoscrollbox2_1 " style="display:none;" >
		<div class="fs16">
			Dear Member,<br><br>
			  As a special gesture Jeevansathi will allow you to keep your photos private for 1 more month
			  from today.<br><br>
			  However after 1 month if you do not upgrade to paid membership we will remove your photos <br>
			  from Jeevansathi.com 
		</div>
		<div class="sp15">
		</div>
		<div class="sp15">
		</div>
		<div class=" sp15">
		</div>
	</div>



</div>
<script language="javascript">
function submitAction()
{
	var selectedOption,insertedId;
	$("[name='selection']").each(function(index,element)
	{
		if($(this).is(':checked'))
		{
			insertedId = $("#insertedId").val();
			selectedOption = this.id;
			divToShow = "#next"+selectedOption;
			if(selectedOption == 1)
			{
				$.ajax(
				{
					url: SITE_URL+"/social/updatePhotoDisplay",
					success: function(response)
					{
//						alert(response);
					}
				});
			}
			$.ajax(
			{
				url: SITE_URL+"/social/updatePhotoPrivacySelection?id="+insertedId+"&option="+selectedOption,
				success: function(response)
				{
					if(selectedOption == 2)
					{
						window.location = SITE_URL+"/profile/mem_comparison.php?from_source=photoPrivacyLayer";
					}
					else
					{
						$("#disableTbRemove").hide();
						$(divToShow).show();
						$("#closeOption").show();
					}
				}
			});
		}
	});
	if(selectedOption == undefined || selectedOption == '' || selectedOption == 0)
		$("#errorMsg").show();
//	alert($("[name='selection']").val());
}
</script>
