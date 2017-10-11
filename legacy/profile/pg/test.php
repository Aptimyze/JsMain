<?php
include "libfuncs.php";
$MerchantId = "M_anyana_1395";
$OrderId = "JF041D00FC2-3704";
$Amount = "750.00";
$AuthDesc = "Y";
$CheckSum = "431494853";
$WorkingKey = "a5qdxwe59g5af94qphru8hjubw1t9o6u";

echo "Alok : " . verifychecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $CheckSum, $WorkingKey) . "\n";
?>
