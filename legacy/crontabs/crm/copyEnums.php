<?php
$file = '/var/www/html/lib/model/enums/Membership1.enum.class.php';
$newfile = '/var/www/html/lib/model/enums/Membership.enum.class.php';

if (!copy($file, $newfile)) {
    $msg = "failed to copy $file...\n";
}
else {
    $msg = "copy successful";
}

$to="vibhor.garg@jeevansathi.com";
$sub="Enum File Copied.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);

?>

