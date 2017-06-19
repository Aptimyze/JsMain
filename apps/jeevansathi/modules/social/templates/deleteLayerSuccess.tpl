~if $userTimedOutError`

<script type = "text/javascript">
show_loggedIn_window();
</script>

~else`

<div class="pink" style="width:350px;height:100px;">
                <div class="sp12"></div>
                <div class="lf" style="padding:6px; width:100%">
                <div class="lf t14 b" style="padding-top:6px; width:88%" align="center" >Do you want to delete this photo?</div></div>
                <div class="sp12"></div>
                <div style="margin:auto; text-align:center;">
			<input type="button" class="green_btn" onclick = "deletePic('~$picId`','~$delId`','~$ifProf`','~$origProf`'); $.colorbox.close(); return false;" value="&nbsp;Yes&nbsp;" >&nbsp;&nbsp;&nbsp;
			<input type="button" class="green_btn" onclick = "$.colorbox.close();" value="&nbsp;No&nbsp;" >
                </div>
                <div class="sp12"></div>
</div>
~/if`
