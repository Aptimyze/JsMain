<div class="deleteReasonHide" id="screenedCrousel">
    <div style="width: ~$screened|count*135`px;">
         ~foreach from=$screened item=imagesArr key=countScreen`
            <img src='~$imagesArr["url"]`' style='height:100px;'>
         ~/foreach`
    </div>
</div>