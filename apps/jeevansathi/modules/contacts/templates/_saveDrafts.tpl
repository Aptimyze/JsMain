~if $contactEngineObj->getComponent()->SaveMessage`
<div class="fs14 ww">Your message : &quot;~if strlen($contactEngineObj->contactHandler->getElements("MESSAGE")) gt 160` ~substr($contactEngineObj->contactHandler->getElements("MESSAGE"),0,160)`&nbsp;...~else` ~$contactEngineObj->contactHandler->getElements("MESSAGE")` ~/if`&quot;
<input type='hidden' name="draftMessage" id="draftMessage" value="~$contactEngineObj->getComponent()->draftMessage`"/>
<input type='hidden' name="contactType" id="contactType" value="~$contactEngineObj->contactHandler->getContactObj()->getTYPE()`"/>
</div>

    <div class="sp15"></div>
    ~if MobileCommon::isMobile() neq 1`
    <div id = "saveDraft">
    <div class="fs16">Would you like to save your message ?</div>
    <div class="sp10"></div>

    <div class="w358CE fl">
      <div style="background:#f1f1f1; padding:5px; border:1px solid #dedede;" class="w347CE fl">
      <div class="err"  id ="errId">
      ~if $contactEngineObj->getComponent()->overflow`
        You already have 5 saved drafts.To save this draft, please replace with one of these saved drafts:
      ~/if`
      </div>
        <div class="sp5"></div>
        <div class="fl">
      <span class="fl"><input type="text" style="width:168px; height:30px" maxchars="20" name="after_draft_post_name" id="after_draft_post_id" placeholder="Name" onblur="if(this.placeholder == ''){this.placeholder = 'Name';}" onfocus="if(this.placeholder=='Name'){this.placeholder = '';}" /></span>
          &nbsp;
          <span>
          ~if $contactEngineObj->getComponent()->overflow`
            ~include_partial("contacts/messagedropdown",[drafts=>$contactEngineObj->getComponent()->drafts,fromSaveDraft=>1])`
          ~/if`
          </span> </div>
        <div class="sp5"></div>
        <div>
          <input type="button" class="fto-btn-green sprite-new cp"  value="Save" onclick="javascript:SaveDraft(~if $contactEngineObj->getComponent()->overflow` 1 ~else` 0 ~/if`);"/>
        </div>
      </div>
    </div>
    </div>
    ~/if`
~/if`
