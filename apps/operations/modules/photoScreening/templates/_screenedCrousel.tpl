<div class="deleteReasonHide" id="screenedCrousel">
    <div>
         ~foreach from=$screened item=imagesArr key=countScreen`
            <img src='~$imagesArr["url"]`' style='height:100px;'>
         ~/foreach`
    </div>
</div>
