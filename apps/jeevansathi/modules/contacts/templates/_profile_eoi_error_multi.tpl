~if $contactEngineObj->contactHandler->getPageSource() eq 'search' || $contactEngineObj->contactHandler->getPageSource() eq 'VSM'`
ERROR#~$contactEngineObj->getComponent()->errorMessage|decodevar`
~else`
<div id="alt_error" class="ce_357" >
<div class="ico-wrong sprite-new fl">&nbsp;</div>
<div class="fs15 fl w300" style="margin-top:4px;">
~$contactEngineObj->getComponent()->errorMessage|decodevar`
</div>
<script>
</script>
</div>
~/if`
