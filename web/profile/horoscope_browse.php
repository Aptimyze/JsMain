<?php
echo '<form id="horo_form" style="display:block;background-color:#F5F5F5" onsubmit="return parent.show_loader();" name="form1" enctype="multipart/form-data"
action="/profile/horoscope_upload.php?pchecksum=~$profilechecksum`&registration_horo=1" method="POST"><p class="fl ml_10" style="margin:10px 0 10px 10px;">
<input type="file" name="horoscope" onchange="enable();" id="horoscope" style="height:26px;" /><input type="submit" name="submitted" value="upload" id="submitted" disabled id="submitted" style="height:26px;"/></p></form><script>function enable(){var a=document.getElementById("horoscope").value;if(a==""){document.getElementById("submitted").disabled="true";}else{document.getElementById("submitted").disabled="";}}</script>';
?>

