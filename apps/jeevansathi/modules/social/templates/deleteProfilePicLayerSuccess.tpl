~if $userTimedOutError`

<script type = "text/javascript">
show_loggedIn_window();
</script>

~else`

<div class="pink" style="width:350px;height:100px;">
                <div class="lf" style="padding:6px; width:100%">
                	<div class="lf t14 b" style="padding-top:6px;margin-left:10px; width:88%">
				To delete a profile photo, please first select another photo as profile photo.
			</div>
		</div>
                <div class="sp12"></div>
                <div style="margin:auto; text-align:center;">
			<input type="button" class="green_btn" onclick = "$.colorbox.close();" value="&nbsp;OK&nbsp;" >
                </div>
</div>

~/if`
