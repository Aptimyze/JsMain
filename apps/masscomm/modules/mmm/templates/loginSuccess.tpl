~if empty($cid)`
<script type="text/javascript">
    location.replace("home");
</script>
~/if`


<FRAMESET cols="20%,80%">
	<frame src="menu?cid=~$cid`" name="menu">
	<frame src="welcome?cid=~$cid`" name="right"> 
</frameset>
