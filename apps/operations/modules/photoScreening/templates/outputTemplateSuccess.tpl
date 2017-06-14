~include_partial('global/header')`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- carry on variables for screening-->
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name="profileid" value="~$profileid`">
<input type="hidden" name="pictureid" value="~$screenedPicId`">
<input type="hidden" name="source" value="~$source`">
<input type="hidden" name="username" value="~$username`">
<input type="hidden" name="name" value="~$name`">
<br />
<p style = "text-align: center">
~if $messageFlag eq 1`
	
	<center><h3>Success.
	 ~if $source eq 'master'`
		<a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/masterPhotoEdit?cid=~$cid`&name=~$username`&source=~$source`&skipMemcache=1">Continue</a>
	 ~elseif $interface eq ProfilePicturesTypeEnum::$INTERFACE["2"]`
		<a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/processInterface?cid=~$cid`&name=~$username`&source=~$source`&skipMemcache=1">Continue</a>
	~elseif $source eq 'mail'`
		<a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/screenPhotosFromMail?cid=~$cid`&name=~$username`&source=~$source`&skipMemcache=1">Continue</a>
	~else`
	 <a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/screen?cid=~$cid`&name=~$username`&source=~$source`">Continue</a> </h3>
	</center>
	 ~/if`
~else`
        <span style="color:red;">~$errMessage`</span><br /><br />
	~if $source eq 'master'`
		Click on back button of browser or <a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/masterPhotoEdit?cid=~$cid`&name=~$username`&source=~$source`&profileId=~$profileid`">Go Back</a>
	~elseif $source eq 'mail'`
                <a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/screenPhotosFromMail?cid=~$cid`&name=~$username`&source=~$source`&skipMemcache=1">Continue</a>
        ~else`
		Click on back button of browser or 
		~if $interface eq ProfilePicturesTypeEnum::$INTERFACE["2"]` 
			<a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/processInterface?cid=~$cid`&name=~$name`&source=~$source`">Go Back</a>
	
		~else` 
			<a href = "~JsConstants::$siteUrl`/operations.php/photoScreening/screen?cid=~$cid`&name=~$username`&source=~$source`">Go Back</a>
		~/if`
	~/if`
~/if`
</p>
<br /><br /><br /><br />
<!-- carry on variables for screening-->
</body>
</script>
~include_partial('global/footer')`
