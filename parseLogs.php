<?php
require_once './ParseLogsBasedOnTime.php';

$logFilesArray = array_diff(scandir($destFileBasePath), array(
    '..',
    '.'
));

// index : 2 -> sourceIP , 12 -> DestinationIP
$sourceToDestArray = array();

if ($logFilesArray != null && count($logFilesArray) > 0) {
    foreach ($logFilesArray as $logFile) {
        
        $file = $destFileBasePath . $logFile;
        
        if (! is_file($file)) {
            continue;
        }
        
        $filePointer = fopen($file, "r") or die($fileOpenErrorMsg);
        
        if ($filePointer) {
            $delimeter = "\t";
            
            while (! feof($filePointer)) {
                $line = fgets($filePointer);
                
                $wordsArray = explode($delimeter, $line);
                
                $wordCount = count($wordsArray);
                if ($wordsArray != null && $wordCount > 13) {
                    $sourceIP = $wordsArray[2];
                    $destinationIP = $wordsArray[12];
                    
                    $destIPArray = $sourceToDestArray[$sourceIP];
                    
                    if ($destIPArray == null) {
                        $destIPArray = array();
                    }
                    
                    if (! in_array($destinationIP, $destIPArray)) {
                        $destIPArray[] = $destinationIP;
                    }
                    
                    $sourceToDestArray[$sourceIP] = $destIPArray;
                    unset($destIPArray);
                }
            }
            
            fclose($filePointer);
            
            print_r($sourceToDestArray);
        } else {
            echo "Error opening : " . $logFile;
        }
    }
}
