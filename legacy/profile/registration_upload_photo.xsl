<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:param name="WHICH_PHOTO" />
<xsl:param name="PROFILEID" />
<xsl:param name="CHECKSUM" />
<xsl:param name="IMG_URL" />
<xsl:template match="/uploadPhoto">
<html>
	<head>
		<style>
			.file{position:relative;z-index:2; opacity:0;filter:alpha(opacity=0);}
			.textbox{FONT-SIZE: 10px; FONT-FAMILY: verdana,Arial;width:270px}
			.blbutton{background-image:url(/profile/images/registration_new/blbutton.gif); border:#85b2d1 1px; border-style:solid; font:normal 10px arial; text-align:center; background-repeat:repeat-x; width:70px; color:#454544; height:20px;}
			.absbut{position:absolute; top:0px; left:25px;}
			* html .absbut{position:absolute; top:0px; left:3px;}
			*:first-child+html .absbut{position:absolute; top:0px; left:3px;}
		</style>
		<script>
			function fill()
			{
				docF=document.upload_photo_form;

				var field_name = docF.which_photo.value;

				docF.fakeinput.value = eval("docF." + field_name + ".value");
			}

			function auto_submit_photo()
			{
				document.upload_photo_form.submit();
			}
		</script>
	</head>
	<body style="margin-top:0px">
		<xsl:variable name="rethrowError" select="submitted/uploadError" />
		<xsl:variable name="rethrowSuccess" select="submitted/uploadSuccessful" />
		<xsl:choose>
			<xsl:when test="$rethrowError = 1">
				<span style="font:normal 11px arial;color:#ff0000;">
					<xsl:value-of select="messages/uploadError" />
				</span>
				<div style="float:left;">
					<form name="upload_photo_form" method="post" enctype="multipart/form-data" style="margin:0px">
						<input type="hidden" name="which_photo" value="{$WHICH_PHOTO}" />
						<input type="hidden" name="profileid" value="{$PROFILEID}" />
						<input type="hidden" name="checksum" value="{$CHECKSUM}" />
						<input type="hidden" name="submit_photo" value="1" />
						<div style="position:relative;width:120px;_width:345px">
							<input type="file" class="textbox file" name="{$WHICH_PHOTO}" size="1" onchange="auto_submit_photo();" style="width:0px"/>
							<div class="absbut">
								<input type="button" class="blbutton" value="Select"/>
							</div>
						</div>
					</form>
				</div>
			</xsl:when>
			<xsl:when test="$rethrowSuccess = 1">
				<span style="font:normal 11px arial;color:#5c5c5c;">
					<xsl:value-of select="messages/uploadSuccessful" />
				</span>
			</xsl:when>
			<xsl:otherwise>
				<div style="float:left;padding-top:36px">
					<form name="upload_photo_form" method="post" enctype="multipart/form-data" style="margin:0px">
						<input type="hidden" name="which_photo" value="{$WHICH_PHOTO}" />
						<input type="hidden" name="profileid" value="{$PROFILEID}" />
						<input type="hidden" name="checksum" value="{$CHECKSUM}" />
						<input type="hidden" name="submit_photo" value="1" />
						<div style="position:relative;width:120px;_width:345px">
							<input type="file" class="textbox file" name="{$WHICH_PHOTO}" size="1" onchange="auto_submit_photo();" style="width:0px"/>
							<div class="absbut">
								<input type="button" class="blbutton" value="Select"/>
							</div>
						</div>
	<!--
						<div style="position:relative;width:420px;_width:345px;">
							<input type="file" class="textbox file" name="{$WHICH_PHOTO}" size="43" onfocus="fill();" onkeyup="fill();"/>
							<div style="position:absolute; top:0px; left:0px">
								<input type="text" name="fakeinput" class="textbox" style="width:270px; _width:195px;"/>
								<input type="button" class="blbutton" value="Select"/>
								&#160;<input type="submit" class="blbutton" value="Upload" name="submit_photo" />
							</div>
						</div>
	-->
					</form>
				</div>
			</xsl:otherwise>
		</xsl:choose>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>
