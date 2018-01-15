~if $onlyError eq 1`
ERROR# Due to a temporary problem your request could not be processed. Please try after a couple of minutes.
~else`
~if $onlyError eq '2'`
<div  class="divlinks fl ce_360" style="display:block">
~/if`
<div class="ce_357">
<div class="ico-wrong sprite-new fl">&nbsp;</div>
<div class="fs15 fl w300" >
Due to a temporary problem your request could not be processed. Please try after a couple of minutes.
</div>
<script>        
</script>
</div>
~if $onlyError eq '2'`
</div>
~/if`
~/if`
