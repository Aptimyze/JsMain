<?php
ini_set('memory_limit', '5912M');
ini_set('max_execution_time', 0);
$sourceFileBasePath = "/home/shivhanda/akmai_logs/";
$destFileBasePath = "/home/shivhanda/akmai_logs/parts/";

$fileArray = array(
    'Akamai_82836.esw3ccust_ghip_cstatus_hh_U.201702140000-201702150000-20170214-99.99c-13'
);

$destFilePrefix = "Akamai_";
$destFileSuffix = ".txt";
$fileOpenErrorMsg = "Can't open output file.";
$delimeter = "\t";
if ($fileArray != null && count($fileArray) > 0) {
    
    foreach ($fileArray as $fileName) {
        // Store the output file no:
        $file_count = 1;
        
        // Create a handle for the input file:
        $input_handle = fopen($sourceFileBasePath . $fileName, "r") or die($fileOpenErrorMsg);
        
        // starting hour and incrementBy
        $startHour = "0";
        $hourIncrementBy = "3";
        
        // Create an output file:
        $destinationFile = $destFileBasePath . $destFilePrefix . $file_count . $destFileSuffix;
        
        if(is_file($destinationFile) && file_exists($destinationFile))
            continue;
        
        $output_handle = fopen($destinationFile, "w") or die($fileOpenErrorMsg);
        
        // Loop through the file until you get to the end:
        while (! feof($input_handle)) {
            // Read from the file:
            $buffer = fgets($input_handle);
            $wordsArray = explode($delimeter, $buffer);
            
            if ($wordsArray != null && count($wordsArray) > 1) {
                
                $hour = date('H', strtotime($wordsArray[1]));
                if ($hour != null) {
                    
                    $startHour = $startHour == "0" ? $hour : $startHour;
                    if ($hour < ($startHour + $hourIncrementBy)) {
                        // Write the read data from the input file to the output file:
                        fwrite($output_handle, $buffer);
                    } else {
                        // Close the output file:
                        fclose($output_handle);
                        
                        $startHour = "0";
                        
                        // Increment the output file count:
                        $file_count ++;
                        
                        // Create the next output file:
                        $output_handle = fopen($destFileBasePath . $destFilePrefix . $file_count . $destFileSuffix, "w") or die($fileOpenErrorMsg);
                    }
                }
            }
        }
    }
    // Close the input file:
    fclose($input_handle);
    // Close the output file:
    fclose($output_handle);
    
    echo "Done!";
}
?>