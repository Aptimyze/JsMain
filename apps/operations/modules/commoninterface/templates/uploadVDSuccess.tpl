~include_partial('global/header')`

<!-- loader -->
~include_partial('global/loader',["text"=>"Uploading..."])`
<!-- loader -->

<div id="mainContent">
	~if $UNAUTHORIZED`
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew" align="center">
			<td>
				You do not have the privilage to upload data.
				<br><a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Continue&gt;&gt;</a>
			</td>
		</tr>
	</table>
	~elseif $SUCCESSFUL`
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew" align="center">
			<td>
				You have successfully uploaded the data from table. 
				<br>
				<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Continue&gt;&gt;</a>
			</td>
		</tr>
	</table>
	~elseif $ERROR`
	<div class="pdT40">
		<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
			<tr class="fieldsnew" align="center">
				<td>
					There is error in uploading process. Please try again...
					<br>
					<a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/updateVDRecords?INCOMPLETEUPLOAD=1&cid=~$cid`">UPLOAD AGAIN&gt;&gt;</a>
				</td>
			</tr>
		</table>
	</div>
	~elseif $BACKGROUND_FAILURE`
	<div class="pdT40">
		<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
			<tr class="fieldsnew" align="center">
				<td>
					There is error in running background script to populate data from temp to main tables. 
				</td>
			</tr>
		</table>
	</div>
	~elseif $NODATA`
	<div class="pdT40">
		<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
			<tr class="fieldsnew" align="center">
				<td>
					There is no data to upload. Please enter data in your table first.. 
					<br>
					<a href="~sfConfig::get('app_img_url')`/jsadmin/mainpage.php?cid=~$cid`">Continue&gt;&gt;</a>
				</td>
			</tr>
		</table>
	</div>
	~elseif $UPLOAD`
	<form name="name1" method="post" action="updateVDRecords" enctype="multipart/form-data">
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">

		<tr class="fieldsnew">
			<div class="pdT40" align="center">Click on Upload button to transfer data from your table to VD tables</div>
			<td>
				<div align="center"><input type="submit" name="upload" value="Upload" onclick="return uploadProgress();"></div>
			</td>
		</tr>
	</table>
	<input type="hidden" name="cid" value="~$cid`">
	</form>
	~/if`
</div>
~include_partial('global/footer')`