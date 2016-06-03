~if $fromSearch || $FromDraftMsg ||  $fromVSM`
~if $FromDraftMsg`
ERROR#~$FromDraftMsg`
~else`
Login
~/if`
~else`
<div class="ce_357">
<div class="ico-wrong sprite-new fl">&nbsp;</div>
<div class="fs15">
Please login to continue
</div>
<script>
$.colorbox({href:'/profile/login.php?SHOW_LOGIN_WINDOW=1'});
</script>
</div>
~/if`
