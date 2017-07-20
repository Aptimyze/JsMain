
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">
~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<br><br>
<table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=4>Upload Biodata</font>
        </td>
    </tr>
</table>
~if !$invalidFile`
~if $freshUpload eq true` 
    ~if $deleteStatus eq true`
        <br>
        <table width="20%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr class="formhead" align="center" width="100%">
                <td colspan="3"  height="30">
                    <font size=1>Deleted Successfully</font>
                </td>
            </tr>
        </table>
    ~/if`
    <form name="csvUpload" method="post" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/uploadBiodata?useraction=uploadBioData&client=~$client`" enctype="multipart/form-data">
        <table width="40%" border="0" align="center" cellpadding="4" cellspacing="4">
            <tr class="fieldsnew">
                <td>
                    <input type="file" name="uploaded_csv" size="25">
                </td>
                <td>
                    <input type="submit" name="upload" value="Upload">
                </td>
            </tr>
        </table>
        <input type="hidden" name="cid" value="~$cid`">
    </form>
~else if $freshUpload eq false`
    ~if $uploadSuccess eq true`
        <br>
        <table width="20%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr class="formhead" align="center" width="100%">
                <td colspan="3"  height="30">
                    <font size=1>Uploaded Successfully</font>
                </td>
            </tr>
        </table>
    ~/if`
    <table width="20%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr  align="center" width="100%">
                <td colspan="3"  height="30">
                    <a href="/operations.php/jsexclusive/uploadBiodata?useraction=deleteBioData&client=~$client`" onclick="return confirm('Are you sure you want to delete?')">Delete BioData</a><br>
                </td>
            </tr>
            <tr  align="center" width="100%">
                <td colspan="3"  height="30">
                    <a href="/operations.php/jsexclusive/uploadBiodata?useraction=viewBioData&client=~$client`">View BioData</a>
                </td>
            </tr>
    </table>
~/if`
~/if`
~if $invalidFile eq 1`
    <br>
    <table width="20%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr class="formhead" align="center" width="100%">
            <td colspan="3"  height="30">
                <font size=1>File Size cannot be more than 5MB</font>
            </td>
        </tr>
    </table>
~else if $invalidFile eq 2`
    <br>
    <table width="20%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr class="formhead" align="center" width="100%">
            <td colspan="3"  height="30">
                <font size=1>File format can be either ~$allowedExtension`</font>
            </td>
        </tr>
    </table>
~else if $invalidFile eq 3`
    <br>
    <table width="20%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr class="formhead" align="center" width="100%">
            <td colspan="3"  height="30">
                <font size=1>Error occured, please retry.</font>
            </td>
        </tr>
    </table>
~/if`


~include_partial('global/footer')`

