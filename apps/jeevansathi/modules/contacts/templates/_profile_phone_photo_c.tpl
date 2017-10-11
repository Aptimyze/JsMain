~if !$profileObj`
~assign var=profileObj value=$contactEngineObj->contactHandler->getViewer()`
~/if`
~if MobileCommon::isMobile()`<div>~else`<p>~/if`
<span class="fs16" class="ce_357">

~if $profileObj->getPROFILE_STATE()->getFTOStates()->getSubState() eq C1`
		<span>
		~Messages::getUploadPhotoLink(["USERNAME" => $profileObj->getUSERNAME()])`</span><b>&nbsp;&amp;&nbsp;</b>
		~if MobileCommon::isMobile()`<BR><BR>~/if`
		~if $post eq 1`
		<span>~Messages::getVerifyPhoneLinkConfirmation()`</span>
		~else`
		<span>~Messages::getVerifyPhoneLink()`</span>
		~/if`
	~else if $profileObj->getPROFILE_STATE()->getFTOStates()->getSubState() eq C2`
	
		~Messages::getUploadPhotoLink(["USERNAME" => $profileObj->getUSERNAME()])`
		
	~else if $profileObj->getPROFILE_STATE()->getFTOStates()->getSubState() eq C3`
		
		~if $post eq 1`
		<span>~Messages::getVerifyPhoneLinkConfirmation()`</span>
		~else`
		<span>~Messages::getVerifyPhoneLink()`</span>
		~/if`
	~/if`
</span>
~if MobileCommon::isMobile()`</div>~elseif $post eq 1` ~else`</p>~/if`

