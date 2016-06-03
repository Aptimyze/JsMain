~include_partial('global/header')`

<!-- loader -->
~include_partial('global/loader',["text"=>"Uploading..."])`
<!-- loader -->

<div id="mainContent">
    ~if $SUCCESSFUL`
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
		<tr class="fieldsnew" align="center">
			<td>
				You have successfully uploaded the data from table. 
				<br>
				<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageVdOffer?name=~$name`&cid=~$cid`">Continue&gt;&gt;</a>
			</td>
		</tr>
	</table>
	~elseif $NODATA`
	<div class="pdT40">
		<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
			<tr class="fieldsnew" align="center">
				<td>
					There is no data to upload. Please enter data in your table first. 
					<br>
					<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageVdOffer?name=~$name`&cid=~$cid`">Continue&gt;&gt;</a>
				</td>
			</tr>
		</table>
	</div>
	~elseif $UPLOAD`
	<form name="name1" method="post" action="UpdateDiscountLookupRecords" enctype="multipart/form-data">
	<table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">

		<tr class="fieldsnew">
			<div class="pdT40" align="center">Click on Upload button to transfer data from your table to Discount Lookup table</div>
			<td>
				<div align="center"><input type="submit" name="upload" value="Upload" onclick="return uploadProgress();"></div>
			</td>
		</tr>
	</table>
	<input type="hidden" name="cid" value="~$cid`">
    <input type="hidden" name="name" value="~$name`">
	</form>
	~/if`
    
    
    <table width=100% align="CENTER">
        <tr align="CENTER">
            <td class="formhead" colspan="100%" height="23">
                <b>Discount Lookup Table</b>
            </td>
        </tr>
        ~if $data`
            <tr align="CENTER">
                <td class="label" width=4% height="20">
                    <b>S.No.</b>
                </td>
                <td class="label" width=10% height="20">
                    <b>Score Lower Limit</b>
                </td>
                <td class="label" width=10% height="20">
                    <b>Score Upper Limit</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>Gender</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>MTongue</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>Service</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>2_DISCOUNT</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>3_DISCOUNT</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>6_DISCOUNT</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>12_DISCOUNT</b>
                </td>
                <td class="label" width=5% height="20">
                    <b>L_DISCOUNT</b>
                </td>
            </tr>
            ~foreach from=$data item=row key=k`
                <tr align="CENTER">
                    <td class="label" width=4% height="20">
                    ~$k+1`
                    </td>
                    <td class="label" width=10% height="20">
                        ~$row.SCORE_LOWER_LIMIT`
                    </td>
                    <td class="label" width=10% height="20">
                        ~$row.SCORE_UPPER_LIMIT`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.GENDER`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.MTONGUE`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.SERVICE`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.2_DISCOUNT`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.3_DISCOUNT`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.6_DISCOUNT`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.12_DISCOUNT`
                    </td>
                    <td class="label" width=5% height="20">
                        ~$row.L_DISCOUNT`
                    </td>
                </tr>
            ~/foreach`
        ~else`
            <tr align="CENTER">
                <td>
                    No data available in the table
                </td>
            </tr>
        ~/if`
    </table>
	
</div>
~include_partial('global/footer')`
